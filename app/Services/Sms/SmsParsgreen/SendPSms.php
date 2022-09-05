<?php
namespace App\Services\Sms\SmsParsgreen;

use App\Services\Sms\Exceptions\OtpFailedException;
use App\Services\Sms\SmsServiceInterface;
use stdClass;

/**
 * Class SendPSms
 * @package App\Services\Sms\SmsParsgreen
 */
class SendPSms implements SmsServiceInterface
{
    /**
     * @var ConnectApi
     */
    private $smsApi;

    /**
     * SendPSms constructor.
     */
    public function __construct()
    {
        $smsUrl = "http://login.parsgreen.com";
        $apiKey = "32EDA6F2-9914-45E1-B2BC-7F80A2180996";
        $this->smsApi = new ConnectApi($smsUrl, $apiKey);
    }

    /**
     * send otp code
     *
     * @param $mobile
     * @param $country_code
     * @param $smsCode
     * @param bool $addName
     * @param int $templateId
     * @return bool|string
     * @throws OtpFailedException
     */
    public function sendOtp($country_code , $mobile, $smsCode, $addName = false, $templateId = 0)
    {
        if(env('SEND_OTP', true) == false){
            return true;
        }

        $req = new stdClass();
        $req->Mobile  = $country_code.$mobile;   //mobile
        $req->SmsCode = $smsCode;
        $req->TemplateId = $templateId; //0-6   activation code , verify code , login code
        $req->AddName = $addName;      // if true append company name end of sms

        $response = $this->smsApi->SendSms("Message/SendOtp", $req) ;

        if(empty($response) || $response->R_Code !== 0 ){
            throw new OtpFailedException($response->R_Message . ' ' . $response->R_Code);
        }

        return 'success: ' . $response->R_Success . ' code: '. $response->R_Code . ' message: ' . $response->R_Message;
    }
}
