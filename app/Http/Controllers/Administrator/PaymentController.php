<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Payment;
class PaymentController extends Controller
{
    private $payment;
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subSequence = ['id' => 0, 'title' => 'پرداخت ها'];
        $payments = $this->payment->orderByPagination();
        return view('v1.admin.pages.payment.index', compact( 'payments', 'subSequence'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Activity  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        return view('v1.admin.pages.payment.show', compact('payment'));
    }
}
