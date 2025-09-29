<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * Generate Midtrans snap token for payment.
     */
    private function generateSnapToken($orderId, $total)
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$clientKey = config('midtrans.client_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Disable SSL verification for sandbox to avoid certificate issues
        if (config('midtrans.is_sandbox')) {
            \Midtrans\Config::$curlOptions = [
                \CURLOPT_SSL_VERIFYPEER => false,
                \CURLOPT_SSL_VERIFYHOST => false,
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name ?? 'Customer',
                'email' => Auth::user()->email ?? 'customer@example.com',
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            Log::error('Midtrans snap token generation error: ' . $e->getMessage());
            throw $e;
        }
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

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Harap login terlebih dahulu.');
        }

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

        $order = null;
        DB::transaction(function () use ($total, $orderItems, $request, &$order) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'status' => 'pending', // Pending until payment success
            ]);

            foreach ($orderItems as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }
        });

        // Generate Midtrans snap token
        $snapToken = $this->generateSnapToken($order->id, $total);

        session()->forget('cart');

        // Redirect to payment page with snap token
        return redirect()->route('payment.process', ['snap_token' => $snapToken, 'order_id' => $order->id]);
    }

    /**
     * Process payment with Midtrans snap.
     */
    public function processPayment(Request $request)
    {
        $snapToken = $request->query('snap_token');
        $orderId = $request->query('order_id');

        if (!$snapToken || !$orderId) {
            return redirect()->route('orders.index')->with('error', 'Invalid payment session.');
        }

        $order = Order::findOrFail($orderId);
        $this->authorize('view', $order); // Ensure user owns the order

        return view('payment.process', compact('snapToken', 'order'));
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
            'status' => 'required|in:pending,confirmed,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.index')->with('success', 'Order status updated.');
    }

    // Unused
    public function edit(string $id) {}
    public function destroy(string $id) {}
}
