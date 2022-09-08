<?php

namespace App\Http\Controllers\Site\Auth;

use App\Http\Controllers\Site\Controller;
use App\Events\SendOtpEvent;
use App\Http\Requests\Site\AuthRequest;
use App\Http\Requests\Site\LoginRequest;
use App\Services\Sms\SmsServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use App\Models\Activation;
use App\Models\UserLogin;
use App\Models\Country;
use App\Models\User;
use App\Models\Role;
use Jenssegers\Agent\Agent;

class AuthController extends Controller
{
    private $message;
    private $country;
    private $activation;
    private $user;
    private $role;
    private $userLogin;
    private $smsHandler;

    public function __construct(Country $country, Activation $activation, User $user, Role $role, UserLogin $userLogin, SmsServiceInterface $smsHandler)
    {
        $this->country = $country;
        $this->user = $user;
        $this->role = $role;
        $this->activation = $activation;
        $this->userLogin = $userLogin;
        $this->smsHandler = $smsHandler;
        $this->message = '';
    }

    public function showLoginPage()
    {
        $codes = $this->country->all()->pluck('phone_code')->toArray();
        return view('v1.site.pages.auth.sign-in', compact('codes'));
    }

    public function sendOtp(AuthRequest $request)
    {
        try {
            $mobile = makeMobileWithoutZero($request->mobile);

            if (!empty($this->isBlockedOtp($mobile))) {
                return redirect()->back()->withErrors([
                    'otpBlock' => trans('siteMessages.auth.otpBlock')
                ]);
            }

            $countryCode = $request->countryCode;
            // make and send otp
            Event::dispatch(new SendOtpEvent(makeMobileByZero($mobile), $countryCode, $this->smsHandler));

            return redirect()->route('getLoginCode', $mobile);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function showOtpPage($mobile)
    {
        return view('v1.site.pages.auth.login-code', compact('mobile'));
    }

    public function doLogin(LoginRequest $request, $mobile)
    {
        try {
            // check if user is blocked
            $mobile = makeMobileWithoutZero($request->mobile);

            // plus retry time
            $this->activation->updateRetryInfoByMobile($mobile);

            if (!empty($this->isBlockedOtp($mobile))) {
                return redirect()->back()->withErrors([
                    'exception' => trans('siteMessages.auth.otpBlock')
                ]);
            }

            $activation = $this->activation->getUncompletedByCodeMobile($request->activationCode, $mobile);

            if (empty($activation)) {
                return redirect()->back()->withErrors([
                    'exception' => trans('siteMessages.auth.activationCodeInvalid')
                ]);
            }

            DB::beginTransaction();

            if (!$user = $this->user->where('mobile', $mobile)->first()) {
                $user = $this->user->create([
                    'mobile' => $request->mobile,
                    'invitation_code' => Str::uuid(),
                    'phone_code' => $activation->country_code,
                    'role_id' => $this->role->where('name', 'standardUser')->get()->first()->id
                ]);
            }

            if (empty($userToken = $this->getUserToken($user))) {
                return redirect()->back()->withErrors([
                    'exception' => trans('siteMessages.auth.makeUserTokenFail')
                ]);
            }

            $activation->update([
                'completed_at' => Carbon::now()
            ]);

            $user->update([
                'mobile_verified_at' => Carbon::now(),
                'token' => $userToken
            ]);

            // use agent class
            $agent = new Agent();

            $userLoginData = [
                'uuid' => Str::uuid(),
                'platform' => 'web',
                'model' => $agent->browser(),
                'os' => $agent->device(),
                'is_active' => true,
                'login_at' => Carbon::now(),
                'created_at' => Carbon::now()
            ];

            $this->userLogin->syncUserLoginInfo($user->id, $userLoginData);

            Auth::login($user, true);

            DB::commit();
            return redirect()->route('index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function doLogout()
    {
        $this->userLogin->deactiveUserLogin(auth()->user()->id);
        Auth::logout();
        return redirect()->route('index');
    }

    /**
     * check that is device duplicated
     *
     * @param string $uuid
     * @param $userId
     * @return bool
     */

    /**
     *  get user jwt token
     *
     * @param $user
     * @return string
     */
    private function getUserToken($user)
    {
        $userToken = $user->token;
        if (empty($userToken)) {
            return $this->createJwtToken($user);
        }
        try {
            JWT::decode($userToken, env('JWT_SECRET'), ['HS256']);
        } catch (\Exception $e) {
            return $this->createJwtToken($user);
        }
        return $userToken;
    }

    /**
     * create jwt token for user
     *
     * @param $user
     * @return string
     */
    private function createJwtToken($user)
    {
        $payload = [
            'iss' => config('global.jwt.iss'), // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'roleId' => empty($user->role) ? null : $user->role->id,
            'roleName' => empty($user->role) ? null : $user->role->name,
            'iat' => time(), // Time when JWT was issued.
            'exp' => config('global.jwt.exp')
        ];
        $jwtToken = JWT::encode($payload, env('JWT_SECRET'),'HS256');
        return $jwtToken;
    }

    /**
     * @param $user
     * @return bool
     */
    private function hasProfile($user)
    {
        return !empty($user->profile) ? true : false;
    }

    private function isBlockedOtp(int $mobile)
    {
        $code = $this->activation->getUncompletedByMobile($mobile);
        if (
            !empty($code)
            && $code->retry_time >= config('global.otp.retryTime')
            && !empty($code->retry_at)
            && Carbon::parse($code->retry_at)->addSeconds(config('global.otp.blockInterupt')) > Carbon::now()
        ) {
            return $code;
        }

        return false;
    }
}
