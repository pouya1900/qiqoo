<?php
namespace App\Services\Sms\SmsVoodoo;
use Exception;

/**
 * Class ConnectApi
 * @package App\Services\Sms\SmsVoodoo
 */
class ConnectApi
{
    /**
     * @var
     */
    private $url;
    /**
     * @var
     */
    private $apikey;

    /**
     * ConnectApi constructor.
     * @param $apiUrl
     * @param $apikey
     */
    function __construct($apiUrl, $apikey)
    {
        $this->url = $apiUrl;
        $this->apikey = $apikey;
    }

    /**
     * connect api and send sms
     *
     * @param $urlPath
     * @param $req
     * @return mixed|string
     */
    function SendSms($urlPath, $req)
    {
        try {
            $this->url = $this->url . '/sendsms';
            $ch = curl_init($this->url);
            $jsonDataEncoded = json_encode($req);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, [
		        'Authorization: ' . $this->apikey
	        ]);
            $result = curl_exec($ch);
            $res = json_decode($result);
            curl_close($ch);
            return $res;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
}
