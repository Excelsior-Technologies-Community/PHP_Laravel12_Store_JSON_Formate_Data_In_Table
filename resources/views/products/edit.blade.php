@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')

<div class="row justify-content-center">

    <div class="col-lg-7">

        <div class="card shadow-sm">

            <div class="card-header bg-white">

                <h5 class="mb-1">
                    ✏️ Edit Product
                </h5>

                <small class="text-muted">
                    Product ID: #{{ $product->id }}
                </small>

            </div>


            <div class="card-body">

                <form
                    action="{{ route('products.update', $product) }}"
                    method="POST">

                    @csrf

                    @method('PUT')


                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Product Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name', $product->name) }}">

                    </div>


                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Brand
                        </label>

                        <input
                            type="text"
                            name="details[brand]"
                            class="form-control"
                            value="{{ old(
                                'details.brand',
                                $product->details['brand'] ?? ''
                            ) }}">

                    </div>


                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Tags
                        </label>

                        <input
                            type="text"
                            name="details[tags][]"
                            class="form-control"
                            value="{{ old(
                                'details.tags.0',
                                implode(
                                    ', ',
                                    $product->details['tags'] ?? []
                                )
                            ) }}">

                        <small class="text-muted">
                            Separate tags using commas.
                        </small>

                    </div>


                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Price
                            </label>

                            <input
                                type="number"
                                name="price"
                                step="0.01"
                                min="0"
                                class="form-control"
                                value="{{ old(
                                    'price',
                                    $product->price
                                ) }}">

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Stock
                            </label>

                            <input
                                type="number"
                                name="stock"
                                min="0"
                                class="form-control"
                                value="{{ old(
                                    'stock',
                                    $product->stock
                                ) }}">

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Status
                            </label>

                            <select name="status"
                                    class="form-select">

                                <option value="active"
                                    {{ old(
                                        'status',
                                        $product->status
                                    ) === 'active'
                                        ? 'selected'
                                        : '' }}>

                                    Active

                                </option>

                                <option value="inactive"
                                    {{ old(
                                        'status',
                                        $product->status
                                    ) === 'inactive'
                                        ? 'selected'
                                        : '' }}>

                                    Inactive

                                </option>

                            </select>

                        </div>

                    </div>


                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-warning">

                            Update Product

                        </button>


                        <a
                            href="{{ route('products.show', $product) }}"
                            class="btn btn-info">

                            View

                        </a>


                        <a
                            href="{{ route('products.index') }}"
                            class="btn btn-secondary">

                            Cancel

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection