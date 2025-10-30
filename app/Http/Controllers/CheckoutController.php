<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;


class CheckoutController extends Controller
{
    public function start(Request $req)
    {
        $provider = $req->validate(
            ['provider' => 'required|in:stripe,paypal,momo,vnpay']
        )['provider'];
        $cart = session('cart', []);
        if (empty($cart))
            return back()->with('error', 'Giỏ hàng trống');

        $user = $req->user();
        $products = Product::whereIn('id', array_keys($cart))->get();
        if ($products->isEmpty())
            return back()->with('error', 'Sản phẩm không hợp lệ');

        $order = DB::transaction(function () use ($products, $cart, $user) {
            $order = Order::create([
                'id' => (string)str()->uuid(),
                'user_id' => $user->id,
                'amount' => 0,
                'currency' => 'USD',
                'status' => 'pending',
                'email' => $user->email,
                'name' => $user->name,
            ]);
            $total = 0;

            foreach ($products as $p) {
                $qty = $cart[$p->id];
                $line = round($p->price * $qty, 2);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $p->id,
                    'quantity' => $qty,
                    'unit_price' => $p->price,
                    'total' => $line
                ]);
                $total = round($total + $line, 2);
            }
            $order->update(['amount' => $total]);

            Payment::create([
                'id' => (string)str()->uuid(),
                'order_id' => $order->id,
                'provider' => request('provider'),
                'status' => "initiated",
                'amount' => $total,
                'currency' => 'USD'
            ]);

            return $order;
        });

        $gateway = PaymentService::make($provider);
        $url = $gateway->createPayment($order);

        return Inertia::location($url);
    }
}