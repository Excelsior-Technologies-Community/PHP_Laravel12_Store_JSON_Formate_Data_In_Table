@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="card shadow-sm mx-auto" style="max-width: 650px;">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ $product->name }}</h5>
        <span class="badge {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }} fs-6">
            {{ ucfirst($product->status) }}
        </span>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th width="35%">ID</th>
                <td>{{ $product->id }}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $product->name }}</td>
            </tr>
            <tr>
                <th>Price</th>
                <td>₹{{ number_format($product->price, 2) }}</td>
            </tr>
            <tr>
                <th>Stock</th>
                <td>{{ $product->stock }}</td>
            </tr>
            <tr>
                <th>Brand</th>
                <td>{{ $product->details['brand'] ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tags</th>
                <td>
                    @foreach($product->details['tags'] ?? [] as $tag)
                        <span class="badge bg-info text-dark">{{ $tag }}</span>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>JSON Details</th>
                <td><pre class="mb-0 bg-light p-2 rounded">{{ json_encode($product->details, JSON_PRETTY_PRINT) }}</pre></td>
            </tr>
            <tr>
                <th>Created At</th>
                <td>{{ $product->created_at->format('d M Y, h:i A') }}</td>
            </tr>
        </table>

        <div class="d-flex gap-2 mt-2">
            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('products.destroy', $product) }}" method="POST"
                  onsubmit="return confirm('Delete this product?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection
