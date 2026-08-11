@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="card shadow-sm mx-auto" style="max-width: 650px;">
    <div class="card-header bg-white">
        <h5 class="mb-0">Add New Product</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('products.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" placeholder="e.g. Washing Machine">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Brand <span class="text-danger">*</span></label>
                <input type="text" name="details[brand]" class="form-control @error('details.brand') is-invalid @enderror"
                       value="{{ old('details.brand') }}" placeholder="e.g. Bosch">
                @error('details.brand') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Tags <span class="text-danger">*</span> <small class="text-muted">(comma separated)</small></label>
                <input type="text" name="details[tags][]" class="form-control @error('details.tags') is-invalid @enderror"
                       value="{{ old('details.tags.0') }}" placeholder="e.g. 7kg, 8kg, 10kg">
                @error('details.tags') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Price (₹) <span class="text-danger">*</span></label>
                    <input type="number" name="price" step="0.01" min="0"
                           class="form-control @error('price') is-invalid @enderror"
                           value="{{ old('price', 0) }}">
                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Stock <span class="text-danger">*</span></label>
                    <input type="number" name="stock" min="0"
                           class="form-control @error('stock') is-invalid @enderror"
                           value="{{ old('stock', 0) }}">
                    @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
