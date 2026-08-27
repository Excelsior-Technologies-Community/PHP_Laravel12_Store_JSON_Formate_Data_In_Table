@extends('layouts.app')

@section('title', 'Products List')

@section('content')

<div class="card shadow-sm">

    {{-- Header --}}
    <div class="card-header bg-white">

        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h5 class="mb-1">Products</h5>

                <small class="text-muted">
                    Manage products and JSON formatted details
                </small>
            </div>

            <div class="d-flex gap-2">

                <a href="{{ route('products.import.form') }}"
                   class="btn btn-outline-primary btn-sm">
                    📥 Import JSON
                </a>

                <a href="{{ route('products.export', request()->query()) }}"
                   class="btn btn-outline-success btn-sm">
                    📤 Export JSON
                </a>

                <a href="{{ route('products.create') }}"
                   class="btn btn-success btn-sm">
                    + Add Product
                </a>

            </div>

        </div>

    </div>


    {{-- Search Form --}}
    <div class="card-body border-bottom">

        <form action="{{ route('products.search') }}"
              method="GET"
              class="row g-2">

            <div class="col-md-4">

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    placeholder="Search by name..."
                    value="{{ request('name') }}"
                >

            </div>

            <div class="col-md-3">

                <input
                    type="text"
                    name="brand"
                    class="form-control"
                    placeholder="Search by brand..."
                    value="{{ request('brand') }}"
                >

            </div>

            <div class="col-md-3">

                <input
                    type="text"
                    name="tag"
                    class="form-control"
                    placeholder="Search by tag (e.g. 7kg)..."
                    value="{{ request('tag') }}"
                >

            </div>

            <div class="col-md-2 d-flex gap-2">

                <button type="submit"
                        class="btn btn-primary w-100">
                    Search
                </button>

                <a href="{{ route('products.index') }}"
                   class="btn btn-secondary w-100">
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- Search / Export Information --}}
    @if(request()->filled('name') ||
        request()->filled('brand') ||
        request()->filled('tag'))

        <div class="alert alert-info rounded-0 mb-0">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>Active filters:</strong>

                    @if(request('name'))
                        <span class="badge bg-primary ms-1">
                            Name: {{ request('name') }}
                        </span>
                    @endif

                    @if(request('brand'))
                        <span class="badge bg-primary ms-1">
                            Brand: {{ request('brand') }}
                        </span>
                    @endif

                    @if(request('tag'))
                        <span class="badge bg-primary ms-1">
                            Tag: {{ request('tag') }}
                        </span>
                    @endif

                </div>

                <a href="{{ route('products.export', request()->query()) }}"
                   class="btn btn-sm btn-success">
                    📤 Export Filtered Results
                </a>

            </div>

        </div>

    @endif


    {{-- Table --}}
    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover mb-0">

                <thead class="table-dark">

                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>Tags</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($products as $product)

                        <tr>

                            <td>
                                {{ $product->id }}
                            </td>

                            <td>
                                <strong>
                                    {{ $product->name }}
                                </strong>
                            </td>

                            <td>
                                {{ $product->details['brand'] ?? '-' }}
                            </td>

                            <td>

                                @forelse($product->details['tags'] ?? [] as $tag)

                                    <span class="badge bg-info text-dark">
                                        {{ $tag }}
                                    </span>

                                @empty

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endforelse

                            </td>

                            <td>
                                ₹{{ number_format($product->price, 2) }}
                            </td>

                            <td>

                                @if($product->stock > 0)

                                    <span class="badge bg-success">
                                        {{ $product->stock }}
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Out of stock
                                    </span>

                                @endif

                            </td>

                            <td>

                                <span class="badge
                                    {{ $product->status === 'active'
                                        ? 'bg-success'
                                        : 'bg-secondary' }}">

                                    {{ ucfirst($product->status) }}

                                </span>

                            </td>

                            <td>

                                <div class="d-flex gap-1">

                                    <a href="{{ route('products.show', $product) }}"
                                       class="btn btn-sm btn-outline-info">
                                        View
                                    </a>

                                    <a href="{{ route('products.edit', $product) }}"
                                       class="btn btn-sm btn-outline-warning">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('products.destroy', $product) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Delete this product?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-outline-danger">
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="text-center text-muted py-5">

                                <div class="fs-1 mb-2">
                                    📦
                                </div>

                                <h6>
                                    No products found
                                </h6>

                                <p class="mb-0">
                                    Try changing your search filters or add a new product.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Pagination --}}
    @if($products->hasPages())

        <div class="card-footer d-flex justify-content-between align-items-center">

            <small class="text-muted">

                Showing
                {{ $products->firstItem() }}
                –
                {{ $products->lastItem() }}
                of
                {{ $products->total() }}

            </small>

            {{ $products->links() }}

        </div>

    @endif

</div>

@endsection