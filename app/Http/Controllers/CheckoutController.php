<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Order;
use App\Models\Customer;
use App\Enums\OrderStatus;
use App\Http\Helpers\Cart;
use App\Enums\PaymentStatus;
use App\Models\CartItem;
use App\Models\Payment;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $user = $request->user();
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        //list($products, $cartItems) = Cart::getProductsAndCartItems();
        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $lineItems = [];
        $totalPrice = 0;
        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->price * $quantity;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->title,
                        'images' => [$product->image],
                    ],
                    'unit_amount' => $product->price * 100,
                ],
                'quantity' => $cartItems[$product->id]['quantity'],
            ];
        }

        $session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
        ]);

        $orderData = [
            'total_price' => $totalPrice,
            'status' => OrderStatus::Unpaid,
            'created_by' => $user->id,
            'updated_by' => $user->id
        ];
        $order = Order::create($orderData);

        $paymentData = [
            'order_id' => $order->id,
            'amount' => $totalPrice,
            'status' => PaymentStatus::Pending,
            'type' => 'cc',
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'session_id' => $session->id
        ];

        Payment::create($paymentData);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $user = $request->user();
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        try {
            $session_id = $request->get('session_id');
            $session = \Stripe\Checkout\Session::retrieve($session_id);
            if (!$session) {
                return view('checkout.failure', 'Invalid session id');
            }

            $payment = Payment::query()->where(['session_id' => $session->id, 'status' => PaymentStatus::Pending])->first();
            if (!$payment) {
                return view('checkout.failure', ['message' => 'Payment does not exist!']);
            }

            $payment->status = PaymentStatus::Paid;
            $payment->update();

            $order = $payment->order;
            $order->status = OrderStatus::Paid;
            $order->update();

            CartItem::where('user_id', $user->id)->delete();

            // $customer = \Stripe\Customer::retrieve($session->customer);
            $customer = Customer::query()->where(['user_id' => $user->id])->first();
            return view('checkout.success', compact('customer'));
        } catch (\Exception $e) {
            throw $e;
            return view('checkout.failure', ['message' => $e->getMessage()]);
        }
    }

    public function failure(Request $request)
    {
        return view('checkout.failure');
    }
}
