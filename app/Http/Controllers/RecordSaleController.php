<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecordSaleController extends Controller
{
    public function index()
    {
        $products = Product::where('stock_quantity', '>', 0)->orderBy('name', 'ASC')->get();
        $customers = Customer::orderBy('full_name', 'ASC')->get();

        return view('record_sale', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'customer_id' => 'nullable|integer|exists:customers,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $quantity = (int) $validated['quantity'];

        if ($quantity > $product->stock_quantity) {
            return redirect()->back()->withInput()->with('error',
                "Insufficient stock. Only {$product->stock_quantity} unit(s) of \"{$product->name}\" available."
            );
        }

        $totalPrice = $product->price * $quantity;

        DB::transaction(function () use ($validated, $product, $quantity, $totalPrice) {
            Sale::create([
                'product_id' => $product->id,
                'customer_id' => $validated['customer_id'] ?? null,
                'sold_by' => session('user_id'),
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'created_at' => now(),
            ]);

            $product->decrement('stock_quantity', $quantity);
        });

        return redirect('/record-sale')->with('success',
            "Sale recorded! {$quantity}x \"{$product->name}\" for ₱".number_format($totalPrice, 2).'.'
        );
    }
}
