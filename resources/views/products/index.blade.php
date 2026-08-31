@extends('layouts.app')

@section('title', 'Products')

@section('content')

<div class="container-fluid">


{{-- =========================================================
     HEADER
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            Products
        </h3>

        <p class="text-muted mb-0">
            Manage products and JSON formatted details
        </p>
    </div>

    <div class="d-flex flex-wrap gap-2 mt-3 mt-md-0">

        <a href="{{ route('products.trash') }}"
           class="btn btn-outline-danger">
            🗑️ Trash
        </a>

        <a href="{{ route('products.import.form') }}"
           class="btn btn-outline-primary">
            📥 Import JSON
        </a>

        <a href="{{ route('products.export', request()->query()) }}"
           class="btn btn-outline-success">
            📤 JSON
        </a>

        <a href="{{ route('products.export-csv', request()->query()) }}"
           class="btn btn-outline-dark">
            📄 CSV
        </a>

        <a href="{{ route('products.create') }}"
           class="btn btn-primary">
            + Add Product
        </a>

    </div>

</div>


{{-- =========================================================
     SUCCESS MESSAGE
========================================================== --}}
@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">

        <strong>✓ Success!</strong>

        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- =========================================================
     VALIDATION ERRORS
========================================================== --}}
@if($errors->any())

    <div class="alert alert-danger alert-dismissible fade show">

        <strong>Something went wrong!</strong>

        <ul class="mb-0 mt-2">

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- =========================================================
     SEARCH & ADVANCED FILTERS
========================================================== --}}
<div class="card shadow-sm mb-4">

    <div class="card-header bg-white">

        <strong>
            🔎 Search & Advanced Filters
        </strong>

    </div>


    <div class="card-body">

        <form method="GET"
              action="{{ route('products.index') }}">

            <div class="row g-3">

                {{-- Product Name --}}
                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Product Name
                    </label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           placeholder="Search name..."
                           value="{{ request('name') }}">

                </div>


                {{-- Brand --}}
                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Brand
                    </label>

                    <input type="text"
                           name="brand"
                           class="form-control"
                           placeholder="Search brand..."
                           value="{{ request('brand') }}">

                </div>


                {{-- Tag --}}
                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        JSON Tag
                    </label>

                    <input type="text"
                           name="tag"
                           class="form-control"
                           placeholder="e.g. 7kg"
                           value="{{ request('tag') }}">

                </div>


                {{-- Status --}}
                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Status
                    </label>

                    <select name="status"
                            class="form-select">

                        <option value="">
                            All Status
                        </option>

                        <option value="active"
                            {{ request('status') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive"
                            {{ request('status') === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                </div>


                {{-- Minimum Price --}}
                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Minimum Price
                    </label>

                    <input type="number"
                           step="0.01"
                           min="0"
                           name="min_price"
                           class="form-control"
                           placeholder="₹ Minimum"
                           value="{{ request('min_price') }}">

                </div>


                {{-- Maximum Price --}}
                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Maximum Price
                    </label>

                    <input type="number"
                           step="0.01"
                           min="0"
                           name="max_price"
                           class="form-control"
                           placeholder="₹ Maximum"
                           value="{{ request('max_price') }}">

                </div>


                {{-- Minimum Stock --}}
                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Minimum Stock
                    </label>

                    <input type="number"
                           min="0"
                           name="min_stock"
                           class="form-control"
                           placeholder="Minimum stock"
                           value="{{ request('min_stock') }}">

                </div>


                {{-- Maximum Stock --}}
                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Maximum Stock
                    </label>

                    <input type="number"
                           min="0"
                           name="max_stock"
                           class="form-control"
                           placeholder="Maximum stock"
                           value="{{ request('max_stock') }}">

                </div>


                {{-- Sort By --}}
                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Sort By
                    </label>

                    <select name="sort_by"
                            class="form-select">

                        <option value="created_at"
                            {{ request('sort_by', 'created_at') === 'created_at' ? 'selected' : '' }}>
                            Created Date
                        </option>

                        <option value="name"
                            {{ request('sort_by') === 'name' ? 'selected' : '' }}>
                            Product Name
                        </option>

                        <option value="price"
                            {{ request('sort_by') === 'price' ? 'selected' : '' }}>
                            Price
                        </option>

                        <option value="stock"
                            {{ request('sort_by') === 'stock' ? 'selected' : '' }}>
                            Stock
                        </option>

                    </select>

                </div>


                {{-- Sort Order --}}
                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Order
                    </label>

                    <select name="sort_order"
                            class="form-select">

                        <option value="desc"
                            {{ request('sort_order', 'desc') === 'desc' ? 'selected' : '' }}>
                            Descending
                        </option>

                        <option value="asc"
                            {{ request('sort_order') === 'asc' ? 'selected' : '' }}>
                            Ascending
                        </option>

                    </select>

                </div>


                {{-- Filter Buttons --}}
                <div class="col-md-4 d-flex align-items-end gap-2">

                    <button type="submit"
                            class="btn btn-primary">
                        🔍 Apply Filters
                    </button>

                    <a href="{{ route('products.index') }}"
                       class="btn btn-secondary">
                        Reset
                    </a>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
     BULK ACTION FORM
========================================================== --}}
<form method="POST"
      action="{{ route('products.bulk-action') }}"
      id="bulkForm">

    @csrf


    <div class="card shadow-sm">


        {{-- =====================================================
             CARD HEADER
        ====================================================== --}}
        <div class="card-header bg-white">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                <div>

                    <strong>
                        Product List
                    </strong>

                    <small class="text-muted ms-2">

                        @if($products->total() > 0)

                            {{ $products->total() }} product(s)

                        @else

                            0 products

                        @endif

                    </small>

                </div>


                <div class="d-flex gap-2">

                    <select name="action"
                            id="bulkAction"
                            class="form-select form-select-sm"
                            style="width: 220px;">

                        <option value="">
                            Bulk Action
                        </option>

                        <option value="delete">
                            🗑️ Move to Trash
                        </option>

                    </select>


                    <button type="submit"
                            class="btn btn-danger btn-sm"
                            onclick="return confirmBulkAction()">

                        Apply

                    </button>

                </div>

            </div>

        </div>


        {{-- =====================================================
             TABLE
        ====================================================== --}}
        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-dark">

                    <tr>

                        {{-- Select All --}}
                        <th width="50"
                            class="text-center">

                            <input type="checkbox"
                                   id="selectAll"
                                   class="form-check-input">

                        </th>


                        {{-- Sequential ID --}}
                        <th width="70">
                            ID
                        </th>


                        <th>
                            Name
                        </th>


                        <th>
                            Brand
                        </th>


                        <th>
                            Tags
                        </th>


                        <th>
                            Price
                        </th>


                        <th>
                            Stock
                        </th>


                        <th>
                            Status
                        </th>


                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse($products as $product)

                    <tr>

                        {{-- Checkbox --}}
                        <td class="text-center">

                            <input type="checkbox"
                                   name="ids[]"
                                   value="{{ $product->id }}"
                                   class="form-check-input product-checkbox">

                        </td>


                        {{-- =================================================
                             SEQUENTIAL ID

                             Page 1:
                             1 2 3 4 5

                             Page 2:
                             6 7 8 9 10
                        ================================================== --}}
                        <td>

                            <span class="fw-semibold">

                                {{ $products->firstItem() + $loop->index }}

                            </span>

                        </td>


                        {{-- Product Name --}}
                        <td>

                            <strong>
                                {{ $product->name }}
                            </strong>

                        </td>


                        {{-- Brand --}}
                        <td>

                            {{ $product->details['brand'] ?? '-' }}

                        </td>


                        {{-- Tags --}}
                        <td>

                            @forelse($product->details['tags'] ?? [] as $tag)

                                <span class="badge bg-info text-dark me-1 mb-1">

                                    {{ $tag }}

                                </span>

                            @empty

                                <span class="text-muted">
                                    -
                                </span>

                            @endforelse

                        </td>


                        {{-- Price --}}
                        <td>

                            <strong>

                                ₹{{ number_format($product->price, 2) }}

                            </strong>

                        </td>


                        {{-- Stock --}}
                        <td>

                            @if($product->stock > 5)

                                <span class="badge bg-success">

                                    {{ $product->stock }}

                                </span>

                            @elseif($product->stock > 0)

                                <span class="badge bg-warning text-dark">

                                    Low: {{ $product->stock }}

                                </span>

                            @else

                                <span class="badge bg-danger">

                                    Out of stock

                                </span>

                            @endif

                        </td>


                        {{-- Status --}}
                        <td>

                            @if($product->status === 'active')

                                <span class="badge bg-success">

                                    Active

                                </span>

                            @else

                                <span class="badge bg-secondary">

                                    Inactive

                                </span>

                            @endif

                        </td>


                        {{-- Actions --}}
                        <td>

                            <div class="d-flex gap-1 flex-wrap">


                                {{-- View --}}
                                <a href="{{ route('products.show', $product) }}"
                                   class="btn btn-sm btn-outline-info">

                                    View

                                </a>


                                {{-- Edit --}}
                                <a href="{{ route('products.edit', $product) }}"
                                   class="btn btn-sm btn-outline-warning">

                                    Edit

                                </a>


                                {{-- Duplicate --}}
                                <form action="{{ route('products.duplicate', $product) }}"
                                      method="POST">

                                    @csrf

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-primary">

                                        Copy

                                    </button>

                                </form>


                                {{-- Delete --}}
                                <form action="{{ route('products.destroy', $product) }}"
                                      method="POST"
                                      onsubmit="return confirm('Move this product to trash?')">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger">

                                        Delete

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="9"
                            class="text-center py-5">

                            <div class="fs-1">
                                📦
                            </div>

                            <h5 class="mt-2">
                                No products found
                            </h5>

                            <p class="text-muted mb-0">
                                Try changing your filters.
                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        {{-- =========================================================
             PAGINATION
        ========================================================== --}}
        @if($products->hasPages())

            <div class="card-footer bg-white">

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">


                    {{-- Result Information --}}
                    <div class="text-muted small">

                        Showing

                        <strong>
                            {{ $products->firstItem() }}
                        </strong>

                        to

                        <strong>
                            {{ $products->lastItem() }}
                        </strong>

                        of

                        <strong>
                            {{ $products->total() }}
                        </strong>

                        products

                    </div>


                    {{-- Pagination --}}
                    <div>

                        {{ $products->onEachSide(1)->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

</form>


</div>

{{-- =========================================================
JAVASCRIPT
========================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const selectAll =
        document.getElementById('selectAll');

    const checkboxes =
        document.querySelectorAll('.product-checkbox');


    /*
    |--------------------------------------------------------------------------
    | Select All
    |--------------------------------------------------------------------------
    */

    if (selectAll) {

        selectAll.addEventListener(
            'change',
            function () {

                checkboxes.forEach(
                    function (checkbox) {

                        checkbox.checked =
                            selectAll.checked;

                    }
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Individual Checkbox
    |--------------------------------------------------------------------------
    */

    checkboxes.forEach(
        function (checkbox) {

            checkbox.addEventListener(
                'change',
                function () {

                    const checked =
                        document.querySelectorAll(
                            '.product-checkbox:checked'
                        ).length;

                    selectAll.checked =
                        checked === checkboxes.length;

                    selectAll.indeterminate =
                        checked > 0 &&
                        checked < checkboxes.length;

                }
            );

        }
    );

});


/*
|--------------------------------------------------------------------------
| Confirm Bulk Action
|--------------------------------------------------------------------------
*/

function confirmBulkAction()
{

    const selected =
        document.querySelectorAll(
            '.product-checkbox:checked'
        );


    const action =
        document.getElementById(
            'bulkAction'
        ).value;


    /*
    |--------------------------------------------------------------------------
    | No Products Selected
    |--------------------------------------------------------------------------
    */

    if (selected.length === 0) {

        alert(
            'Please select at least one product.'
        );

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | No Action Selected
    |--------------------------------------------------------------------------
    */

    if (!action) {

        alert(
            'Please select a bulk action.'
        );

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | Confirmation
    |--------------------------------------------------------------------------
    */

    return confirm(
        'Are you sure you want to perform this action on ' +
        selected.length +
        ' product(s)?'
    );

}

</script>

@endsection
