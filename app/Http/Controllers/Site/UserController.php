<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\Site\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\Profile;
use App\Traits\ImageUtilsTrait;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use ImageUtilsTrait;
    protected $user;
    protected $role;
    protected $profile;
    protected $storageDisk;

    public function __construct(User $user, Role $role, Profile $profile)
    {
        $this->user = $user;
        $this->role = $role;
        $this->profile = $profile;
        $this->storageDisk = config('image.storage.global');
    }

    public function dashboard()
    {
        $user = auth()->user();
        $user->load('ads');
        return view('v1.site.pages.dashboard.index', compact('user'));
    }

    public function profile(User $user, $name)
    {
        $user->load('ads');
        return view('v1.site.pages.user-profile', compact('user'));
    }

    // update user profile in dashboard
    public function updateUserProfile(User $user, UserUpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $user->update($request->input());
            $user->profile->update($request->input());
            if($request->has('image')){
                $newAvatar = $this->storeImage($request->image, $this->storageDisk);
                $oldAvatar = $this->user->avatar()->get()->pluck('id')->toArray();
                $this->updateImages($user, 'userAvatar', $newAvatar->id, $oldAvatar);
            }
            DB::commit();
            session()->flash('notifications', ['message' => trans('site/messages.userProfileSuccess'), 'alert_type' => 'success']);
            return redirect()->route('user.dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('notifications', ['message' => trans('site/messages.operationFailed'), 'alert_type' => 'error']);
            return redirect()->back();
        }
    }
}
