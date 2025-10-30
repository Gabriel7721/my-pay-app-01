<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $ids = array_map('intval', array_keys($cart));
        $products = Product::whereIn('id', $ids)->get()->KeyBy('id');
        $items = [];
        $total = 0;

        foreach ($cart as $pid => $qty) {
            $pid = (int)$pid;
            if (!isset($products[$pid])) continue;

            $p = $products[$pid];
            $qty = max(1, (int)$qty);
            $line = $p->price * $qty;
            $total +=  $line;
            $items[] = [
                'product' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => $p->price,
                    'image_url' => $p->image_url
                ],
                'qty' => $qty,
                'line' => $line,
            ];
        }
        return Inertia::render('Cart', ['items' => $items, 'total' => $total]);
    }
    public function add(Request $req)
    {
        $data = $req->validate([
            'product_id' => 'required|integer|exists:products,id',
            'qty'        => 'nullable|integer|min:1|max:99',
        ]);

        $pid = (int)$data['product_id'];
        $qty = (int)($data['qty'] ?? 1);

        $product = Product::find($pid);
        $cart = session('cart', []);
        $current = (int)($cart[$pid] ?? 0);

        $newQty = min($current + $qty, max(1, (int)$product->stock));
        $cart[$pid] = $newQty;

        session(['cart' => $cart]);

        return back()->with('success', 'Đã thêm vào giỏ');
    }
    public function update(Request $req)
    {
        $data = $req->validate([
            'product_id' => 'required|integer|exists:products,id',
            'qty'        => 'required|integer|min:1|max:99',
        ]);
        $pid = (int)$data['product_id'];
        $qty = (int)($data['qty']);

        $product = Product::find($pid);

        $qty = min($qty, max(1, (int)$product->stock));

        $cart = session('cart', []); // *

        $cart[$pid] = $qty;

        session(['cart' => $cart]);
        
        return back();
    }
    public function remove(Request $req)
    {
        $pid = (int)$req->input('product_id');
        $cart = session('cart', []);
        unset($cart[$pid]);
        session(['cart' => $cart]);
        return back();
    }
    public function clear()
    {
        session()->forget('cart');
        return back();
    }
}
