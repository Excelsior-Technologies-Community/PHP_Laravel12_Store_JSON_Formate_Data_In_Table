@extends('layouts.app')

@section('title', 'Import Products')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="card shadow-sm">

            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">📥 Import Products from JSON</h5>
                        <small class="text-muted">
                            Upload a JSON file to import multiple products at once.
                        </small>
                    </div>

                    <a href="{{ route('products.index') }}"
                       class="btn btn-secondary btn-sm">
                        ← Back
                    </a>
                </div>
            </div>

            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>Import failed!</strong>

                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('products.import') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    <div class="mb-4">

                        <label for="json_file" class="form-label fw-semibold">
                            Select JSON File
                        </label>

                        <input
                            type="file"
                            name="json_file"
                            id="json_file"
                            class="form-control @error('json_file') is-invalid @enderror"
                            accept=".json,application/json,text/plain"
                            required
                        >

                        @error('json_file')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-text">
                            Maximum file size: 2 MB. Maximum 500 products per import.
                        </div>

                    </div>

                    <div class="alert alert-info">

                        <h6 class="fw-bold">
                            Expected JSON Format
                        </h6>

                        <pre class="mb-0 small">[
    {
        "name": "Washing Machine",
        "price": 25000,
        "stock": 10,
        "status": "active",
        "details": {
            "brand": "Bosch",
            "tags": [
                "7kg",
                "8kg",
                "10kg"
            ]
        }
    }
]</pre>

                    </div>

                    <div class="d-flex gap-2">

                        <button type="submit"
                                class="btn btn-primary">
                            📥 Import Products
                        </button>

                        <a href="{{ route('products.index') }}"
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