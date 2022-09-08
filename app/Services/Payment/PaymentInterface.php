<?php

namespace App\Services\Payment;
interface PaymentInterface
{
    function payRequest(int $amount, $mobile, string $callbackUrl, string $factorNumber);

    function payVerify(string $token);
}