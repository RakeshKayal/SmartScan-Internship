<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Show Add Product Form
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store Product in Database
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name'        => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'price'               => 'required|numeric|min:0',
            'barcode'             => 'required|string|max:255|unique:products,barcode',
        ]);

        Product::create([
            'product_name'        => $request->product_name,
            'product_description' => $request->product_description,
            'price'               => $request->price,
            'barcode'             => $request->barcode,
        ]);

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    /**
     * Show All Products
     */
    public function index()
    {
        $products = Product::latest()->get();

        return view('products.index', compact('products'));
    }

    /**
     * Show Edit Product Form
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('products.edit', compact('product'));
    }

    /**
     * Update Product
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'product_name'        => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'price'               => 'required|numeric|min:0',
            'barcode'             => 'required|string|max:255|unique:products,barcode,' . $product->id,
        ]);

        $product->update([
            'product_name'        => $request->product_name,
            'product_description' => $request->product_description,
            'price'               => $request->price,
            'barcode'             => $request->barcode,
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Delete Product
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}