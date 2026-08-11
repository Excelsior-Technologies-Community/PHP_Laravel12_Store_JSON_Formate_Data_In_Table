@extends('layouts.app')

@section('title', 'Products List')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Products</h5>
    </div>

    {{-- Search Form --}}
    <div class="card-body border-bottom">
        <form action="{{ route('products.search') }}" method="GET" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="name" class="form-control" placeholder="Search by name..." value="{{ request('name') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="brand" class="form-control" placeholder="Search by brand..." value="{{ request('brand') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="tag" class="form-control" placeholder="Search by tag (e.g. 7kg)..." value="{{ request('tag') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="card-body p-0">
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
                    <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->details['brand'] ?? '-' }}</td>
                    <td>
                        @foreach($product->details['tags'] ?? [] as $tag)
                            <span class="badge bg-info text-dark">{{ $tag }}</span>
                        @endforeach
                    </td>
                    <td>₹{{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <span class="badge {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($product->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info">View</a>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this product?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }}</small>
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
