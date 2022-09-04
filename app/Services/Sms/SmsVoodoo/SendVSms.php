<?php
namespace App\Services\Sms\SmsVoodoo;

use App\Services\Sms\Exceptions\OtpFailedException;
use App\Services\Sms\SmsServiceInterface;
use stdClass;

/**
 * Class SendVSms
 * @package App\Services\Sms\SmsVoodoo
 */
class SendVSms implements SmsServiceInterface
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
        $smsUrl = "https://api.voodoosms.com";
        $apiKey = "jjHJ0IOXA20J5DFQujkzVOiUxtBrBZLkATv0b9Nc1EmxHs";

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



        $req = [
	        'to' => $mobile,
	        'from' => "QiQoo",
	        'msg' => $smsCode
        ];

        $response = $this->smsApi->SendSms("Message/SendOtp", $req) ;


	    echo '<pre>'; print_r($response); echo '</pre>';

        if( $response === false ){
            throw new OtpFailedException("send otp failed");
        }

        return 'success';
    }
}
