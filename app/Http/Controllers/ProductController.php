<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display products.
     */
    public function index()
    {
        $products = Product::latest()->paginate(10);

        return view('products.index', compact('products'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a new product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'details'        => 'required|array',
            'details.brand'  => 'required|string|max:255',
            'details.tags'   => 'required|array',
            'price'          => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'status'         => 'required|in:active,inactive',
        ]);

        $data = $request->only([
            'name',
            'price',
            'stock',
            'status',
        ]);

        $data['details'] = [
            'brand' => $request->input('details.brand'),
            'tags'  => array_values(
                array_filter(
                    array_map(
                        'trim',
                        explode(
                            ',',
                            implode(
                                ',',
                                $request->input('details.tags', [])
                            )
                        )
                    )
                )
            ),
        ];

        Product::create($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Display product.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show edit form.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update product.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'details'        => 'required|array',
            'details.brand'  => 'required|string|max:255',
            'details.tags'   => 'required|array',
            'price'          => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'status'         => 'required|in:active,inactive',
        ]);

        $data = $request->only([
            'name',
            'price',
            'stock',
            'status',
        ]);

        $data['details'] = [
            'brand' => $request->input('details.brand'),
            'tags'  => array_values(
                array_filter(
                    array_map(
                        'trim',
                        explode(
                            ',',
                            implode(
                                ',',
                                $request->input('details.tags', [])
                            )
                        )
                    )
                )
            ),
        ];

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Delete product.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Search products.
     */
    public function search(Request $request)
    {
        $query = Product::query();

        if ($request->filled('tag')) {
            $query->whereJsonContains(
                'details->tags',
                trim($request->tag)
            );
        }

        if ($request->filled('brand')) {
            $query->where(
                'details->brand',
                'like',
                '%' . trim($request->brand) . '%'
            );
        }

        if ($request->filled('name')) {
            $query->where(
                'name',
                'like',
                '%' . trim($request->name) . '%'
            );
        }

        $products = $query
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('products.index', compact('products'));
    }

    /**
     * Show JSON import form.
     */
    public function importForm()
    {
        return view('products.import');
    }

    /**
     * Import products from JSON file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'json_file' => [
                'required',
                'file',
                'mimes:json,txt',
                'max:2048',
            ],
        ]);

        $file = $request->file('json_file');

        try {
            $jsonContent = file_get_contents(
                $file->getRealPath()
            );

            $products = json_decode(
                $jsonContent,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (\JsonException $exception) {
            return back()
                ->withInput()
                ->withErrors([
                    'json_file' => 'The uploaded file contains invalid JSON.',
                ]);
        }

        if (!is_array($products)) {
            return back()
                ->withInput()
                ->withErrors([
                    'json_file' =>
                        'The JSON root must contain an array of products.',
                ]);
        }

        if (empty($products)) {
            return back()
                ->withInput()
                ->withErrors([
                    'json_file' =>
                        'The JSON file does not contain any products.',
                ]);
        }

        if (count($products) > 500) {
            return back()
                ->withInput()
                ->withErrors([
                    'json_file' =>
                        'You can import a maximum of 500 products at a time.',
                ]);
        }

        $validatedProducts = [];

        foreach ($products as $index => $productData) {

            if (!is_array($productData)) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'json_file' =>
                            'Product #' .
                            ($index + 1) .
                            ' must be a JSON object.',
                    ]);
            }

            $validator = Validator::make(
                $productData,
                [
                    'name'           => 'required|string|max:255',
                    'price'          => 'required|numeric|min:0',
                    'stock'          => 'required|integer|min:0',
                    'status'         => 'required|in:active,inactive',
                    'details'        => 'required|array',
                    'details.brand'  => 'required|string|max:255',
                    'details.tags'   => 'required|array',
                    'details.tags.*' => 'required|string|max:100',
                ]
            );

            if ($validator->fails()) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'json_file' =>
                            'Error in product #' .
                            ($index + 1) .
                            ': ' .
                            implode(
                                ' ',
                                $validator->errors()->all()
                            ),
                    ]);
            }

            $validatedData = $validator->validated();

            $validatedData['details']['tags'] = array_values(
                array_filter(
                    array_map(
                        fn ($tag) => trim($tag),
                        $validatedData['details']['tags']
                    )
                )
            );

            $validatedProducts[] = $validatedData;
        }

        DB::transaction(function () use ($validatedProducts) {

            foreach ($validatedProducts as $productData) {
                Product::create($productData);
            }

        });

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                count($validatedProducts) .
                ' product(s) imported successfully from JSON!'
            );
    }

    /**
     * Export products as JSON.
     */
    public function export(Request $request)
    {
        $query = Product::query();

        if ($request->filled('tag')) {
            $query->whereJsonContains(
                'details->tags',
                trim($request->tag)
            );
        }

        if ($request->filled('brand')) {
            $query->where(
                'details->brand',
                'like',
                '%' . trim($request->brand) . '%'
            );
        }

        if ($request->filled('name')) {
            $query->where(
                'name',
                'like',
                '%' . trim($request->name) . '%'
            );
        }

        $products = $query
            ->latest()
            ->get();

        $exportData = $products->map(function (Product $product) {

            return [
                'id'         => $product->id,
                'name'       => $product->name,
                'price'      => (float) $product->price,
                'stock'      => $product->stock,
                'status'     => $product->status,
                'details'    => $product->details,
                'created_at' => $product->created_at?->toISOString(),
                'updated_at' => $product->updated_at?->toISOString(),
            ];

        })->values()->toArray();

        $json = json_encode(
            $exportData,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES
        );

        $fileName =
            'products-' .
            now()->format('Y-m-d-H-i-s') .
            '.json';

        return response()->streamDownload(
            function () use ($json) {
                echo $json;
            },
            $fileName,
            [
                'Content-Type' => 'application/json',
            ]
        );
    }

    /**
     * Show JSON editor.
     */
    public function editJson(Product $product)
    {
        $jsonDetails = json_encode(
            $product->details,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES
        );

        return view(
            'products.edit-json',
            compact('product', 'jsonDetails')
        );
    }

    /**
     * Update JSON details.
     */
    public function updateJson(Request $request, Product $product)
    {
        $request->validate([
            'json_details' => [
                'required',
                'string',
            ],
        ]);

        $json = trim($request->input('json_details'));

        /*
         * Decode JSON and detect syntax errors.
         */
        try {

            $details = json_decode(
                $json,
                true,
                512,
                JSON_THROW_ON_ERROR
            );

        } catch (\JsonException $exception) {

            return back()
                ->withInput()
                ->withErrors([
                    'json_details' =>
                        'Invalid JSON: ' .
                        $exception->getMessage(),
                ]);
        }

        /*
         * JSON root must be an object.
         */
        if (!is_array($details)) {

            return back()
                ->withInput()
                ->withErrors([
                    'json_details' =>
                        'Invalid JSON structure. The root must be a JSON object.',
                ]);
        }

        /*
         * Brand is required.
         */
        if (
            !isset($details['brand']) ||
            !is_string($details['brand']) ||
            trim($details['brand']) === ''
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'json_details' =>
                        'The JSON must contain a valid "brand" field.',
                ]);
        }

        /*
         * Tags are required and must be an array.
         */
        if (
            !isset($details['tags']) ||
            !is_array($details['tags'])
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'json_details' =>
                        'The JSON must contain a "tags" array.',
                ]);
        }

        /*
         * Validate every tag.
         */
        foreach ($details['tags'] as $index => $tag) {

            if (
                !is_string($tag) ||
                trim($tag) === ''
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'json_details' =>
                            'Every tag must be a non-empty string.',
                    ]);
            }

            $details['tags'][$index] = trim($tag);
        }

        /*
         * Remove duplicate tags.
         */
        $details['tags'] = array_values(
            array_unique($details['tags'])
        );

        /*
         * Save validated JSON.
         */
        $product->update([
            'details' => $details,
        ]);

        return redirect()
            ->route('products.show', $product)
            ->with(
                'success',
                'JSON details updated successfully!'
            );
    }
}