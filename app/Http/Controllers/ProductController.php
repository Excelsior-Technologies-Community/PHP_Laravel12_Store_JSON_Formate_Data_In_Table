<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'details'        => 'required|array',
            'details.brand'  => 'required|string',
            'details.tags'   => 'required|array',
            'price'          => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'status'         => 'required|in:active,inactive',
        ]);

        $data = $request->only(['name', 'price', 'stock', 'status']);
        $data['details'] = [
            'brand' => $request->input('details.brand'),
            'tags'  => array_filter(array_map('trim', explode(',', implode(',', $request->input('details.tags', []))))),
        ];

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'details'        => 'required|array',
            'details.brand'  => 'required|string',
            'details.tags'   => 'required|array',
            'price'          => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'status'         => 'required|in:active,inactive',
        ]);

        $data = $request->only(['name', 'price', 'stock', 'status']);
        $data['details'] = [
            'brand' => $request->input('details.brand'),
            'tags'  => array_filter(array_map('trim', explode(',', implode(',', $request->input('details.tags', []))))),
        ];

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    public function search(Request $request)
    {
        $query = Product::query();

        if ($request->filled('tag')) {
            $query->whereJsonContains('details->tags', $request->tag);
        }

        if ($request->filled('brand')) {
            $query->where('details->brand', 'like', '%' . $request->brand . '%');
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $products = $query->latest()->paginate(10)->appends($request->query());

        return view('products.index', compact('products'));
    }
}
