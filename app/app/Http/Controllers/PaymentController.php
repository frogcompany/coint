<?php
namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;


class PaymentController extends Controller
{
    /**
     * 決済フォーム表示
     */
    public function create()
    {
        return view('payment.create');
    }

    /**
     * 決済実行
     */
    public function store(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('stripe.stripe_secret_key'));

        try {
            \Stripe\Charge::create([
                'source' => $request->stripeToken,
                'amount' => 1000,
                'currency' => 'jpy',
            ]);
        } catch (Exception $e) {
            return back()->with('flash_alert', '決済に失敗しました！('. $e->getMessage() . ')');
        }
        return back()->with('status', '決済が完了しました！');
    }

}
