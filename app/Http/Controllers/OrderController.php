<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display user orders.
     */
    public function index()
    {
        $orders = auth()->user()->orders()->with('orderItems.product')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    /**
     * Show checkout form.
     */
    public function create()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $products = collect($cart)->map(function ($item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $product->quantity = $item['quantity'];
                $product->subtotal = $product->price * $item['quantity'];
            }
            return $product;
        })->filter();

        $total = $products->sum('subtotal');

        return view('checkout.index', compact('products', 'total'));
    }

    /**
     * Store order from checkout.
     */
    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $total = 0;
        $orderItems = [];

        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;
                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            }
        }

        DB::transaction(function () use ($total, $orderItems, $request) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $total,
                'status' => 'pending',
            ]);

            foreach ($orderItems as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }
        });

        session()->forget('cart');

        return redirect()->route('orders.index')->with('success', 'Order placed successfully.');
    }

    /**
     * Display order detail.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('orderItems.product');
        return view('orders.show', compact('order'));
    }

    /**
     * Admin: List all orders.
     */
    public function adminIndex()
    {
        $orders = Order::with('user', 'orderItems.product')->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Admin: Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,shipped,delivered',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.index')->with('success', 'Order status updated.');
    }

    // Unused
    public function edit(string $id) {}
    public function destroy(string $id) {}
}
