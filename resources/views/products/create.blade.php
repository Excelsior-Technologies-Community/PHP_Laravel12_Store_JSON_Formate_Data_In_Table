@extends('layouts.app')

@section('title', 'Add Product')

@section('content')

<div class="row justify-content-center">

    <div class="col-lg-7">

        <div class="card shadow-sm">

            <div class="card-header bg-white">

                <h5 class="mb-1">
                    ➕ Add New Product
                </h5>

                <small class="text-muted">
                    Create a product with JSON formatted details.
                </small>

            </div>


            <div class="card-body">

                <form
                    action="{{ route('products.store') }}"
                    method="POST">

                    @csrf


                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Product Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="e.g. Washing Machine">

                        @error('name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Brand
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="details[brand]"
                            class="form-control @error('details.brand') is-invalid @enderror"
                            value="{{ old('details.brand') }}"
                            placeholder="e.g. Bosch">

                        @error('details.brand')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Tags
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="details[tags][]"
                            class="form-control"
                            value="{{ old('details.tags.0') }}"
                            placeholder="e.g. 7kg, 8kg, 10kg">

                        <small class="text-muted">
                            Separate multiple tags using commas.
                        </small>

                    </div>


                    <div class="row">


                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-semibold">
                                Price
                            </label>

                            <input
                                type="number"
                                name="price"
                                step="0.01"
                                min="0"
                                class="form-control"
                                value="{{ old('price', 0) }}">

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-semibold">
                                Stock
                            </label>

                            <input
                                type="number"
                                name="stock"
                                min="0"
                                class="form-control"
                                value="{{ old('stock', 0) }}">

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-semibold">
                                Status
                            </label>

                            <select name="status"
                                    class="form-select">

                                <option value="active"
                                    {{ old('status', 'active') === 'active'
                                        ? 'selected'
                                        : '' }}>
                                    Active
                                </option>

                                <option value="inactive"
                                    {{ old('status') === 'inactive'
                                        ? 'selected'
                                        : '' }}>
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>


                    <div class="alert alert-info">

                        <strong>
                            JSON Example:
                        </strong>

                        <pre class="mb-0 mt-2">{
    "brand": "Bosch",
    "tags": [
        "7kg",
        "8kg",
        "10kg"
    ]
}</pre>

                    </div>


                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary">

                            Save Product

                        </button>


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