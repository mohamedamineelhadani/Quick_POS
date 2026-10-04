<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{

    public function index()
    {
        $products = Product::with('category')->where('stock_quantity', '>', 0)->get();
        $categories = Category::orderBy('name')->get();

        return view('pos.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $grossTotal = 0;
            foreach ($request->cart as $item) {
                $product = Product::find($item['id']);
                
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("The requested quantity of {$product->name} is not available in stock.");
                }
                
                $grossTotal += $product->price * $item['quantity'];
            }

            $taxRate = 0.05;
            $taxAmount = $grossTotal * $taxRate;
            $netTotal = $grossTotal + $taxAmount;

            $order = Order::create([
                'gross_amount' => $grossTotal,
                'tax_amount' => $taxAmount,
                'net_amount' => $netTotal,
            ]);

            foreach ($request->cart as $item) {
                $product = Product::find($item['id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);

                $product->decrement('stock_quantity', $item['quantity']);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Transaction completed successfully!', 'order_id' => $order->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'An error occurred : ' . $e->getMessage()], 500);
        }
    }


    public function orderHistory()
    {
        $orders = Order::withCount('items')->orderBy('created_at', 'desc')->paginate(10);
        return view('orders.index', compact('orders'));
    }


    public function orderShow(Order $order)
    {
        $order->load('items.product');
        return view('orders.show', compact('order'));
    }
}