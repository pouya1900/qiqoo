<?php

namespace App\Services\Payment\Payir;

use App\Services\Payment\PaymentInterface;

class Payment implements PaymentInterface
{
    private $connection;
    private $apiKey;
    private $description;
    private $successMessage;
    private $errorMessage;
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->apiKey = '62ee01127b353bae99992a09291d7862';
        $this->description = 'خرید بسته موسیقی';
        $this->successMessage = 'تراکنش با موفقیت انجام شد';
        $this->errorMessage = 'بروز خطا در انجام تراکنش';

    }

    public function payRequest(int $amount, $mobile, string $callbackUrl, string $factorNumber = '')
    {
        $result = $this->connection->send($this->apiKey, $amount, $callbackUrl, $mobile, $factorNumber, $this->description);
        $result = json_decode($result);
        if ($result->status) {
            $go = "https://pay.ir/pg/$result->token";
            return [
                'success' => true,
                'data' => $go
            ];
        } else {
            return [
                'success' => false,
                'data' => $result->errorMessage
            ];
        }
    }

    public function payVerify(string $token)
    {
        $result = json_decode($this->connection->verify($this->apiKey, $token));
        if (isset($result->status)) {
            if ($result->status == 1) {
                return [
                    'success' => true,
                    'message' => $this->successMessage,
                    'data' => $result
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $this->errorMessage
                ];
            }
        } else {
            if ($_GET['status'] == 0) {
                return [
                    'success' => false,
                    'message' => $this->errorMessage
                ];
            }
        }
    }
}