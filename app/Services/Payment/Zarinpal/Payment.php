<?php

class Payment
{
    private $merchantId;
    private $portalDescription;
    private $callbackUrl;
    public function __construct($callbackUrl)
    {
        $this->merchantId = env('ZARINPAL_MERCHANTID');
        $this->portalDescription = 'ps.gholiof';
        $this->callbackUrl = $callbackUrl;
    }

    public function payRequest($user, $amount)
    {
        $data = [
            'MerchantID' =>$amount,
            'Amount' => $amount,
            'CallbackURL' => $this->callbackUrl,
            'Description' => $this->portalDescription
        ];
        $jsonData = json_encode($data);
        $ch = curl_init('https://www.zarinpal.com/pg/rest/WebGate/PaymentRequest.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));
        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true);
        curl_close($ch);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            if ($result["Status"] == 100) {
                header('Location: https://www.zarinpal.com/pg/StartPay/' . $result["Authority"]);
            } else {
                echo 'ERR: ' . $result["Status"];
            }
        }
    }

    public function payVerify($authority, $amount)
    {
        $data = [
            'MerchantID' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
            'Authority' => $authority,
            'Amount' => $amount
        ];
        $jsonData = json_encode($data);
        $ch = curl_init('https://www.zarinpal.com/pg/rest/WebGate/PaymentVerification.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $result = json_decode($result, true);
        if(!empty($result) && $result['Status'] == 100){
            return $result['RefId'];
        }
        if ($err) {
            throw new \Exception($err);
        }
        throw new \Exception($result['Status']);
    }
}