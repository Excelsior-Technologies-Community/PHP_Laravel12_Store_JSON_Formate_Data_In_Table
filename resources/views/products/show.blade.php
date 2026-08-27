@extends('layouts.app')

@section('title', $product->name)

@section('content')

<div class="card shadow-sm mx-auto" style="max-width: 750px;">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">

        <div>
            <h5 class="mb-0">
                {{ $product->name }}
            </h5>

            <small class="text-muted">
                Product ID: #{{ $product->id }}
            </small>
        </div>

        <span class="badge
            {{ $product->status === 'active'
                ? 'bg-success'
                : 'bg-secondary' }}
            fs-6"
        >
            {{ ucfirst($product->status) }}
        </span>

    </div>


    <div class="card-body">

        <table class="table table-bordered">

            <tr>

                <th width="35%">
                    ID
                </th>

                <td>
                    {{ $product->id }}
                </td>

            </tr>


            <tr>

                <th>
                    Name
                </th>

                <td>
                    {{ $product->name }}
                </td>

            </tr>


            <tr>

                <th>
                    Price
                </th>

                <td>
                    ₹{{ number_format($product->price, 2) }}
                </td>

            </tr>


            <tr>

                <th>
                    Stock
                </th>

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

            </tr>


            <tr>

                <th>
                    Brand
                </th>

                <td>
                    {{ $product->details['brand'] ?? '-' }}
                </td>

            </tr>


            <tr>

                <th>
                    Tags
                </th>

                <td>

                    @forelse($product->details['tags'] ?? [] as $tag)

                        <span class="badge bg-info text-dark">
                            {{ $tag }}
                        </span>

                    @empty

                        <span class="text-muted">
                            No tags
                        </span>

                    @endforelse

                </td>

            </tr>

        </table>


        {{-- JSON Details Section --}}

        <div class="card border mb-4">

            <div class="card-header bg-dark text-white
                        d-flex justify-content-between align-items-center">

                <strong>
                    📋 JSON Details
                </strong>

                <a
                    href="{{ route('products.edit-json', $product) }}"
                    class="btn btn-warning btn-sm"
                >
                    ✏️ Edit JSON
                </a>

            </div>


            <div class="card-body p-0">

                <pre
                    class="mb-0 p-3 bg-light"
                    style="overflow-x:auto;"
                >{{ json_encode(
                    $product->details,
                    JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_UNICODE |
                    JSON_UNESCAPED_SLASHES
                ) }}</pre>

            </div>

        </div>


        {{-- Action Buttons --}}

        <div class="d-flex gap-2 flex-wrap">

            <a
                href="{{ route('products.edit', $product) }}"
                class="btn btn-warning"
            >
                ✏️ Edit Product
            </a>


            <a
                href="{{ route('products.edit-json', $product) }}"
                class="btn btn-info"
            >
                📝 Edit JSON
            </a>


            <form
                action="{{ route('products.destroy', $product) }}"
                method="POST"
                onsubmit="return confirm('Delete this product?')"
            >

                @csrf
                @method('DELETE')

                <button class="btn btn-danger">
                    🗑️ Delete
                </button>

            </form>


            <a
                href="{{ route('products.index') }}"
                class="btn btn-secondary"
            >
                ← Back
            </a>

        </div>

    </div>

</div>

@endsection