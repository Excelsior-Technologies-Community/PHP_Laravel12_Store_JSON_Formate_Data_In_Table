@extends('layouts.app')

@section('title', 'Trash')

@section('content')

<div class="card shadow-sm">

    <div class="card-header bg-white">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h5 class="mb-1">
                    🗑️ Trash
                </h5>

                <small class="text-muted">
                    Restore or permanently delete products
                </small>

            </div>

            <a href="{{ route('products.index') }}"
               class="btn btn-secondary btn-sm">
                ← Back to Products
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success m-3">
            {{ session('success') }}
        </div>

    @endif


    <div class="table-responsive">

        <table class="table table-hover mb-0">

            <thead class="table-dark">

                <tr>

                    <th>#</th>

                    <th>Name</th>

                    <th>Brand</th>

                    <th>Price</th>

                    <th>Deleted At</th>

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
                        ₹{{ number_format($product->price, 2) }}
                    </td>

                    <td>
                        {{ $product->deleted_at?->format('d M Y H:i') }}
                    </td>

                    <td>

                        <div class="d-flex gap-2">

                            <form
                                action="{{ route('products.restore', $product->id) }}"
                                method="POST">

                                @csrf

                                @method('PUT')

                                <button type="submit"
                                        class="btn btn-sm btn-success">

                                    ♻️ Restore

                                </button>

                            </form>


                            <form
                                action="{{ route('products.force-delete', $product->id) }}"
                                method="POST"
                                onsubmit="return confirm('This will permanently delete the product. Continue?')">

                                @csrf

                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-sm btn-danger">

                                    Permanently Delete

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6"
                        class="text-center py-5 text-muted">

                        🗑️ Trash is empty.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    @if($products->hasPages())

        <div class="card-footer">

            {{ $products->links() }}

        </div>

    @endif

</div>

@endsection