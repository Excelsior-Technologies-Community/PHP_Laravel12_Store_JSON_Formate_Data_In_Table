<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * =========================================================
     * DASHBOARD
     * =========================================================
     */
    public function dashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | Product Statistics
        |--------------------------------------------------------------------------
        */

        $totalProducts = Product::count();

        $activeProducts = Product::where(
            'status',
            'active'
        )->count();

        $inactiveProducts = Product::where(
            'status',
            'inactive'
        )->count();

        $trashedProducts = Product::onlyTrashed()->count();


        /*
        |--------------------------------------------------------------------------
        | Inventory Statistics
        |--------------------------------------------------------------------------
        */

        $outOfStock = Product::where(
            'stock',
            0
        )->count();

        $lowStock = Product::whereBetween(
            'stock',
            [1, 5]
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Date Statistics
        |--------------------------------------------------------------------------
        */

        $createdToday = Product::whereDate(
            'created_at',
            today()
        )->count();


        $createdThisMonth = Product::whereMonth(
            'created_at',
            now()->month
        )
            ->whereYear(
                'created_at',
                now()->year
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Inventory Totals
        |--------------------------------------------------------------------------
        */

        $totalStock = Product::sum('stock');


        $totalValue = Product::sum(
            DB::raw('price * stock')
        );


        /*
        |--------------------------------------------------------------------------
        | Latest Products
        |--------------------------------------------------------------------------
        */

        $latestProducts = Product::oldest()
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Dashboard View
        |--------------------------------------------------------------------------
        */

        return view(
            'products.dashboard',
            compact(
                'totalProducts',
                'activeProducts',
                'inactiveProducts',
                'trashedProducts',
                'outOfStock',
                'lowStock',
                'createdToday',
                'createdThisMonth',
                'totalStock',
                'totalValue',
                'latestProducts'
            )
        );
    }


    /**
     * =========================================================
     * PRODUCT LIST
     * Search + Filters + Sorting + Pagination
     * =========================================================
     */
    public function index(Request $request)
    {
        $query = Product::query();


        /*
        |--------------------------------------------------------------------------
        | Apply Filters
        |--------------------------------------------------------------------------
        */

        $this->applyFilters(
            $query,
            $request
        );


        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $sortBy = $request->get(
            'sort_by',
            'created_at'
        );


        $sortOrder = $request->get(
            'sort_order',
            'desc'
        );


        /*
        |--------------------------------------------------------------------------
        | Allowed Sort Columns
        |--------------------------------------------------------------------------
        */

        $allowedSortColumns = [
            'created_at',
            'name',
            'price',
            'stock',
        ];


        if (!in_array(
            $sortBy,
            $allowedSortColumns,
            true
        )) {

            $sortBy = 'created_at';

        }


        /*
        |--------------------------------------------------------------------------
        | Allowed Sort Orders
        |--------------------------------------------------------------------------
        */

        if (!in_array(
            $sortOrder,
            ['asc', 'desc'],
            true
        )) {

            $sortOrder = 'asc';

        }


        /*
        |--------------------------------------------------------------------------
        | Apply Sorting
        |--------------------------------------------------------------------------
        */

        $query->orderBy(
            $sortBy,
            $sortOrder
        );


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $products = $query
            ->paginate(5)
            ->withQueryString();


        return view(
            'products.index',
            compact('products')
        );
    }


    /**
     * =========================================================
     * CREATE
     * =========================================================
     */
    public function create()
    {
        return view(
            'products.create'
        );
    }


    /**
     * =========================================================
     * STORE
     * =========================================================
     */
    public function store(Request $request)
    {
        $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'details' => [
                'required',
                'array',
            ],

            'details.brand' => [
                'required',
                'string',
                'max:255',
            ],

            'details.tags' => [
                'required',
                'array',
            ],

            'details.tags.*' => [
                'nullable',
                'string',
                'max:100',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

        ]);


        $data = $request->only([
            'name',
            'price',
            'stock',
            'status',
        ]);


        $data['details'] = [

            'brand' => trim(
                $request->input(
                    'details.brand'
                )
            ),

            'tags' => $this->formatTags(
                $request->input(
                    'details.tags',
                    []
                )
            ),

        ];


        Product::create($data);


        return redirect()
            ->route(
                'products.index'
            )
            ->with(
                'success',
                'Product created successfully!'
            );
    }


    /**
     * =========================================================
     * SHOW
     * =========================================================
     */
    public function show(Product $product)
    {
        return view(
            'products.show',
            compact('product')
        );
    }


    /**
     * =========================================================
     * EDIT
     * =========================================================
     */
    public function edit(Product $product)
    {
        return view(
            'products.edit',
            compact('product')
        );
    }


    /**
     * =========================================================
     * UPDATE
     * =========================================================
     */
    public function update(
        Request $request,
        Product $product
    ) {

        $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'details' => [
                'required',
                'array',
            ],

            'details.brand' => [
                'required',
                'string',
                'max:255',
            ],

            'details.tags' => [
                'required',
                'array',
            ],

            'details.tags.*' => [
                'nullable',
                'string',
                'max:100',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

        ]);


        $data = $request->only([
            'name',
            'price',
            'stock',
            'status',
        ]);


        $data['details'] = [

            'brand' => trim(
                $request->input(
                    'details.brand'
                )
            ),

            'tags' => $this->formatTags(
                $request->input(
                    'details.tags',
                    []
                )
            ),

        ];


        $product->update($data);


        return redirect()
            ->route(
                'products.show',
                $product
            )
            ->with(
                'success',
                'Product updated successfully!'
            );
    }


    /**
     * =========================================================
     * SOFT DELETE
     * =========================================================
     */
    public function destroy(Product $product)
    {
        $product->delete();


        return redirect()
            ->route(
                'products.index'
            )
            ->with(
                'success',
                'Product moved to trash successfully!'
            );
    }


    /**
     * =========================================================
     * TRASH
     * =========================================================
     */
    public function trash()
    {
        $products = Product::onlyTrashed()
            ->oldest('deleted_at')
            ->paginate(5);


        return view(
            'products.trash',
            compact('products')
        );
    }


    /**
     * =========================================================
     * RESTORE
     * =========================================================
     */
    public function restore($id)
    {
        $product = Product::onlyTrashed()
            ->findOrFail($id);


        $product->restore();


        return redirect()
            ->route(
                'products.trash'
            )
            ->with(
                'success',
                'Product restored successfully!'
            );
    }


    /**
     * =========================================================
     * FORCE DELETE
     * =========================================================
     */
    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()
            ->findOrFail($id);


        $product->forceDelete();


        return redirect()
            ->route(
                'products.trash'
            )
            ->with(
                'success',
                'Product permanently deleted!'
            );
    }


    /**
     * =========================================================
     * BULK ACTION
     * =========================================================
     */
    public function bulkAction(Request $request)
    {
        $request->validate([

            'action' => [
                'required',
                'in:delete,restore,force_delete',
            ],

            'ids' => [
                'required',
                'array',
                'min:1',
            ],

            'ids.*' => [
                'integer',
                'exists:products,id',
            ],

        ]);


        $ids = $request->input('ids');


        switch ($request->action) {

            /*
            |--------------------------------------------------------------------------
            | Delete
            |--------------------------------------------------------------------------
            */

            case 'delete':

                Product::whereIn(
                    'id',
                    $ids
                )->delete();


                $message =
                    count($ids) .
                    ' product(s) moved to trash.';

                break;


            /*
            |--------------------------------------------------------------------------
            | Restore
            |--------------------------------------------------------------------------
            */

            case 'restore':

                Product::onlyTrashed()
                    ->whereIn(
                        'id',
                        $ids
                    )
                    ->restore();


                $message =
                    count($ids) .
                    ' product(s) restored.';

                break;


            /*
            |--------------------------------------------------------------------------
            | Permanent Delete
            |--------------------------------------------------------------------------
            */

            case 'force_delete':

                Product::onlyTrashed()
                    ->whereIn(
                        'id',
                        $ids
                    )
                    ->forceDelete();


                $message =
                    count($ids) .
                    ' product(s) permanently deleted.';

                break;


            default:

                $message =
                    'No action performed.';

        }


        return back()->with(
            'success',
            $message
        );
    }


    /**
     * =========================================================
     * DUPLICATE
     * =========================================================
     */
    public function duplicate(Product $product)
    {
        $newProduct =
            $product->replicate();


        $newProduct->name =
            $product->name .
            ' (Copy)';


        $newProduct->status =
            'inactive';


        $newProduct->save();


        return redirect()
            ->route(
                'products.edit',
                $newProduct
            )
            ->with(
                'success',
                'Product duplicated successfully!'
            );
    }


    /**
     * =========================================================
     * EXPORT CSV
     * =========================================================
     */
    public function exportCsv(Request $request)
    {
        $query = Product::query();


        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $this->applyFilters(
            $query,
            $request
        );


        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $sortBy = $request->get(
            'sort_by',
            'created_at'
        );


        $sortOrder = $request->get(
            'sort_order',
            'desc'
        );


        $allowedSortColumns = [
            'created_at',
            'name',
            'price',
            'stock',
        ];


        if (!in_array(
            $sortBy,
            $allowedSortColumns,
            true
        )) {

            $sortBy = 'created_at';

        }


        if (!in_array(
            $sortOrder,
            ['asc', 'desc'],
            true
        )) {

            $sortOrder = 'desc';

        }


        $products = $query
            ->orderBy(
                $sortBy,
                $sortOrder
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | File Name
        |--------------------------------------------------------------------------
        */

        $fileName =
            'products-' .
            now()->format(
                'Y-m-d-H-i-s'
            ) .
            '.csv';


        /*
        |--------------------------------------------------------------------------
        | Download
        |--------------------------------------------------------------------------
        */

        return response()->streamDownload(

            function () use ($products) {

                $handle = fopen(
                    'php://output',
                    'w'
                );


                /*
                | BOM for Excel UTF-8
                */

                fwrite(
                    $handle,
                    "\xEF\xBB\xBF"
                );


                fputcsv(
                    $handle,
                    [
                        'ID',
                        'Name',
                        'Brand',
                        'Tags',
                        'Price',
                        'Stock',
                        'Status',
                        'Created At',
                    ]
                );


                foreach ($products as $product) {

                    fputcsv(
                        $handle,
                        [

                            $product->id,

                            $product->name,

                            $product->details['brand']
                                ?? '',

                            implode(
                                ', ',
                                $product->details['tags']
                                    ?? []
                            ),

                            $product->price,

                            $product->stock,

                            $product->status,

                            $product->created_at
                                ? $product->created_at
                                    ->format(
                                        'Y-m-d H:i:s'
                                    )
                                : '',

                        ]
                    );

                }


                fclose($handle);

            },

            $fileName,

            [
                'Content-Type' =>
                    'text/csv; charset=UTF-8',
            ]

        );
    }


    /**
     * =========================================================
     * IMPORT FORM
     * =========================================================
     */
    public function importForm()
    {
        return view(
            'products.import'
        );
    }


    /**
     * =========================================================
     * IMPORT JSON
     * =========================================================
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


        $file =
            $request->file(
                'json_file'
            );


        /*
        |--------------------------------------------------------------------------
        | Read JSON
        |--------------------------------------------------------------------------
        */

        try {

            $jsonContent =
                file_get_contents(
                    $file->getRealPath()
                );


            $products =
                json_decode(
                    $jsonContent,
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );

        } catch (\JsonException $exception) {

            return back()
                ->withInput()
                ->withErrors([
                    'json_file' =>
                        'The uploaded file contains invalid JSON.',
                ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Root Validation
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | Validate Each Product
        |--------------------------------------------------------------------------
        */

        $validatedProducts = [];


        foreach (
            $products as $index => $productData
        ) {

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

                    'name' =>
                        'required|string|max:255',

                    'price' =>
                        'required|numeric|min:0',

                    'stock' =>
                        'required|integer|min:0',

                    'status' =>
                        'required|in:active,inactive',

                    'details' =>
                        'required|array',

                    'details.brand' =>
                        'required|string|max:255',

                    'details.tags' =>
                        'required|array',

                    'details.tags.*' =>
                        'required|string|max:100',

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
                                $validator
                                    ->errors()
                                    ->all()
                            ),
                    ]);

            }


            $validatedData =
                $validator->validated();


            $validatedData['details']['tags'] =
                $this->formatTags(
                    $validatedData['details']['tags']
                );


            $validatedProducts[] =
                $validatedData;

        }


        /*
        |--------------------------------------------------------------------------
        | Database Transaction
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $validatedProducts
            ) {

                foreach (
                    $validatedProducts
                    as $productData
                ) {

                    Product::create(
                        $productData
                    );

                }

            }
        );


        return redirect()
            ->route(
                'products.index'
            )
            ->with(
                'success',
                count($validatedProducts) .
                ' product(s) imported successfully from JSON!'
            );
    }


    /**
     * =========================================================
     * EXPORT JSON
     * =========================================================
     */
    public function export(Request $request)
    {
        $query = Product::query();


        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $this->applyFilters(
            $query,
            $request
        );


        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $sortBy = $request->get(
            'sort_by',
            'created_at'
        );


        $sortOrder = $request->get(
            'sort_order',
            'desc'
        );


        $allowedSortColumns = [
            'created_at',
            'name',
            'price',
            'stock',
        ];


        if (!in_array(
            $sortBy,
            $allowedSortColumns,
            true
        )) {

            $sortBy = 'created_at';

        }


        if (!in_array(
            $sortOrder,
            ['asc', 'desc'],
            true
        )) {

            $sortOrder = 'desc';

        }


        $products = $query
            ->orderBy(
                $sortBy,
                $sortOrder
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | JSON Data
        |--------------------------------------------------------------------------
        */

        $exportData = $products
            ->map(
                function (
                    Product $product
                ) {

                    return [

                        'id' =>
                            $product->id,

                        'name' =>
                            $product->name,

                        'price' =>
                            (float) $product->price,

                        'stock' =>
                            $product->stock,

                        'status' =>
                            $product->status,

                        'details' =>
                            $product->details,

                        'created_at' =>
                            $product->created_at
                                ?->toISOString(),

                        'updated_at' =>
                            $product->updated_at
                                ?->toISOString(),

                    ];

                }
            )
            ->values()
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Encode JSON
        |--------------------------------------------------------------------------
        */

        $json = json_encode(
            $exportData,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES
        );


        /*
        |--------------------------------------------------------------------------
        | File Name
        |--------------------------------------------------------------------------
        */

        $fileName =
            'products-' .
            now()->format(
                'Y-m-d-H-i-s'
            ) .
            '.json';


        return response()->streamDownload(

            function () use ($json) {

                echo $json;

            },

            $fileName,

            [
                'Content-Type' =>
                    'application/json',
            ]

        );
    }


    /**
     * =========================================================
     * EDIT JSON
     * =========================================================
     */
    public function editJson(Product $product)
    {
        $jsonDetails =
            json_encode(
                $product->details,
                JSON_PRETTY_PRINT |
                JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES
            );


        return view(
            'products.edit-json',
            compact(
                'product',
                'jsonDetails'
            )
        );
    }


    /**
     * =========================================================
     * UPDATE JSON
     * =========================================================
     */
    public function updateJson(
        Request $request,
        Product $product
    ) {

        $request->validate([

            'json_details' => [
                'required',
                'string',
            ],

        ]);


        $json =
            trim(
                $request->input(
                    'json_details'
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Decode JSON
        |--------------------------------------------------------------------------
        */

        try {

            $details =
                json_decode(
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
        |--------------------------------------------------------------------------
        | Root Validation
        |--------------------------------------------------------------------------
        */

        if (!is_array($details)) {

            return back()
                ->withInput()
                ->withErrors([
                    'json_details' =>
                        'JSON root must be an object.',
                ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Brand Validation
        |--------------------------------------------------------------------------
        */

        if (
            !isset($details['brand']) ||
            !is_string(
                $details['brand']
            ) ||
            trim(
                $details['brand']
            ) === ''
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'json_details' =>
                        'The JSON must contain a valid "brand" field.',
                ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Tags Validation
        |--------------------------------------------------------------------------
        */

        if (
            !isset($details['tags']) ||
            !is_array(
                $details['tags']
            )
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'json_details' =>
                        'The JSON must contain a "tags" array.',
                ]);

        }


        foreach (
            $details['tags']
            as $index => $tag
        ) {

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


            $details['tags'][$index] =
                trim($tag);

        }


        /*
        |--------------------------------------------------------------------------
        | Remove Duplicate Tags
        |--------------------------------------------------------------------------
        */

        $details['tags'] =
            array_values(
                array_unique(
                    $details['tags']
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $product->update([
            'details' => $details,
        ]);


        return redirect()
            ->route(
                'products.show',
                $product
            )
            ->with(
                'success',
                'JSON details updated successfully!'
            );
    }


    /**
     * =========================================================
     * FORMAT TAGS
     * =========================================================
     */
    private function formatTags(
        array $tags
    ): array {

        $result = [];


        foreach ($tags as $tag) {

            /*
            |--------------------------------------------------------------------------
            | Support comma-separated tags
            |--------------------------------------------------------------------------
            */

            $parts =
                explode(
                    ',',
                    $tag
                );


            foreach ($parts as $part) {

                $part =
                    trim($part);


                if ($part !== '') {

                    $result[] =
                        $part;

                }

            }

        }


        return array_values(
            array_unique(
                $result
            )
        );
    }


    /**
     * =========================================================
     * APPLY COMMON FILTERS
     * =========================================================
     */
    private function applyFilters(
        $query,
        Request $request
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Name
        |--------------------------------------------------------------------------
        */

        if ($request->filled('name')) {

            $query->where(
                'name',
                'like',
                '%' .
                trim(
                    $request->name
                ) .
                '%'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Brand
        |--------------------------------------------------------------------------
        */

        if ($request->filled('brand')) {

            $query->where(
                'details->brand',
                'like',
                '%' .
                trim(
                    $request->brand
                ) .
                '%'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Tag
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tag')) {

            $query->whereJsonContains(
                'details->tags',
                trim(
                    $request->tag
                )
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            if (
                in_array(
                    $request->status,
                    [
                        'active',
                        'inactive',
                    ],
                    true
                )
            ) {

                $query->where(
                    'status',
                    $request->status
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Minimum Price
        |--------------------------------------------------------------------------
        */

        if ($request->filled('min_price')) {

            $query->where(
                'price',
                '>=',
                $request->min_price
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Maximum Price
        |--------------------------------------------------------------------------
        */

        if ($request->filled('max_price')) {

            $query->where(
                'price',
                '<=',
                $request->max_price
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Minimum Stock
        |--------------------------------------------------------------------------
        */

        if ($request->filled('min_stock')) {

            $query->where(
                'stock',
                '>=',
                $request->min_stock
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Maximum Stock
        |--------------------------------------------------------------------------
        */

        if ($request->filled('max_stock')) {

            $query->where(
                'stock',
                '<=',
                $request->max_stock
            );

        }

    }
}

