<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'DESC')->get();
        $editProduct = null;

        return view('products', compact('products', 'editProduct'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image',
        ]);

        $imageFilename = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageFilename = $file->hashName();
            $file->move(public_path('uploads/products'), $imageFilename);
        }

        Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock_quantity' => $validated['stock_quantity'],
            'image' => $imageFilename,
            'created_at' => now(),
        ]);

        return redirect('/products')->with('success', 'Product added successfully.');
    }

    public function edit(Product $product)
    {
        $products = Product::orderBy('created_at', 'DESC')->get();

        return view('products', [
            'products' => $products,
            'editProduct' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image',
        ]);

        $data = [
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock_quantity' => $validated['stock_quantity'],
        ];

        if ($request->hasFile('image')) {
            if ($product->image && File::exists(public_path('uploads/products/'.$product->image))) {
                File::delete(public_path('uploads/products/'.$product->image));
            }
            $file = $request->file('image');
            $data['image'] = $file->hashName();
            $file->move(public_path('uploads/products'), $data['image']);
        }

        $product->update($data);

        return redirect('/products')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && File::exists(public_path('uploads/products/'.$product->image))) {
            File::delete(public_path('uploads/products/'.$product->image));
        }
        $product->delete();

        return redirect('/products')->with('success', 'Product deleted successfully.');
    }
}
