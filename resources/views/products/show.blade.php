@extends('layouts.app')

@section('title', $product->name)

@section('content')

<div class="row justify-content-center">

    <div class="col-lg-8">

        <div class="card shadow-sm">


            <div class="card-header bg-white">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="mb-1">
                            {{ $product->name }}
                        </h5>

                        <small class="text-muted">
                            Product ID: #{{ $product->id }}
                        </small>

                    </div>


                    <span class="badge fs-6
                        {{ $product->status === 'active'
                            ? 'bg-success'
                            : 'bg-secondary' }}">

                        {{ ucfirst($product->status) }}

                    </span>

                </div>

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
                            Brand
                        </th>

                        <td>
                            {{ $product->details['brand'] ?? '-' }}
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

                            @if($product->stock > 5)

                                <span class="badge bg-success">
                                    {{ $product->stock }}
                                </span>

                            @elseif($product->stock > 0)

                                <span class="badge bg-warning text-dark">
                                    Low Stock: {{ $product->stock }}
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Out of Stock
                                </span>

                            @endif

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Tags
                        </th>

                        <td>

                            @forelse(
                                $product->details['tags'] ?? []
                                as $tag
                            )

                                <span class="badge bg-info text-dark">
                                    {{ $tag }}
                                </span>

                            @empty

                                -

                            @endforelse

                        </td>

                    </tr>

                </table>


                <div class="card border mb-4">

                    <div class="card-header bg-dark text-white">

                        <div class="d-flex justify-content-between">

                            <strong>
                                📋 JSON Details
                            </strong>

                            <a
                                href="{{ route(
                                    'products.edit-json',
                                    $product
                                ) }}"
                                class="btn btn-warning btn-sm">

                                Edit JSON

                            </a>

                        </div>

                    </div>


                    <div class="card-body p-0">

                        <pre class="mb-0 p-3 bg-light"
                             style="overflow-x:auto;">{{ json_encode(
    $product->details,
    JSON_PRETTY_PRINT |
    JSON_UNESCAPED_UNICODE |
    JSON_UNESCAPED_SLASHES
) }}</pre>

                    </div>

                </div>


                <div class="d-flex gap-2 flex-wrap">


                    <a
                        href="{{ route(
                            'products.edit',
                            $product
                        ) }}"
                        class="btn btn-warning">

                        ✏️ Edit

                    </a>


                    <a
                        href="{{ route(
                            'products.edit-json',
                            $product
                        ) }}"
                        class="btn btn-info">

                        📝 Edit JSON

                    </a>


                    <form
                        action="{{ route(
                            'products.duplicate',
                            $product
                        ) }}"
                        method="POST">

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-primary">

                            📋 Duplicate

                        </button>

                    </form>


                    <form
                        action="{{ route(
                            'products.destroy',
                            $product
                        ) }}"
                        method="POST"
                        onsubmit="return confirm(
                            'Move this product to trash?'
                        )">

                        @csrf

                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn btn-danger">

                            🗑️ Delete

                        </button>

                    </form>


                    <a
                        href="{{ route(
                            'products.index'
                        ) }}"
                        class="btn btn-secondary">

                        ← Back

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection