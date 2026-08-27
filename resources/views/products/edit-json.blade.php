@extends('layouts.app')

@section('title', 'Edit JSON - ' . $product->name)

@section('content')

<div class="row justify-content-center">

    <div class="col-lg-9">

        <div class="card shadow-sm">

            <div class="card-header bg-white">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-1">
                            ✏️ Edit JSON Details
                        </h5>

                        <small class="text-muted">
                            Product: <strong>{{ $product->name }}</strong>
                        </small>
                    </div>

                    <a href="{{ route('products.show', $product) }}"
                       class="btn btn-secondary btn-sm">
                        ← Back
                    </a>

                </div>

            </div>


            <div class="card-body">

                @if($errors->any())

                    <div class="alert alert-danger">

                        <h6 class="fw-bold mb-2">
                            ❌ JSON Validation Failed
                        </h6>

                        <ul class="mb-0">

                            @foreach($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                <div id="validationMessage"
                     class="alert d-none">
                </div>


                <form
                    action="{{ route('products.update-json', $product) }}"
                    method="POST"
                    id="jsonForm"
                >

                    @csrf
                    @method('PUT')


                    <div class="mb-3">

                        <label
                            for="json_details"
                            class="form-label fw-semibold"
                        >
                            JSON Details
                        </label>

                        <textarea
                            name="json_details"
                            id="json_details"
                            rows="18"
                            class="form-control font-monospace @error('json_details') is-invalid @enderror"
                            spellcheck="false"
                            required
                        >{{ old('json_details', $jsonDetails) }}</textarea>

                        @error('json_details')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="card bg-light border mb-4">

                        <div class="card-body">

                            <h6 class="fw-bold">
                                JSON Requirements
                            </h6>

                            <ul class="mb-0">

                                <li>
                                    JSON must be valid.
                                </li>

                                <li>
                                    Root must be a JSON object.
                                </li>

                                <li>
                                    <code>brand</code> must be a string.
                                </li>

                                <li>
                                    <code>tags</code> must be an array.
                                </li>

                                <li>
                                    Tags must contain valid text values.
                                </li>

                            </ul>

                        </div>

                    </div>


                    <div class="d-flex gap-2">

                        <button
                            type="button"
                            id="validateButton"
                            class="btn btn-info"
                        >
                            🔍 Validate JSON
                        </button>


                        <button
                            type="submit"
                            class="btn btn-success"
                        >
                            💾 Save JSON
                        </button>


                        <a
                            href="{{ route('products.show', $product) }}"
                            class="btn btn-secondary"
                        >
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const textarea = document.getElementById('json_details');
    const validateButton = document.getElementById('validateButton');
    const message = document.getElementById('validationMessage');
    const form = document.getElementById('jsonForm');

    function showMessage(type, text) {

        message.classList.remove(
            'd-none',
            'alert-success',
            'alert-danger'
        );

        message.classList.add(
            type === 'success'
                ? 'alert-success'
                : 'alert-danger'
        );

        message.innerHTML = text;
    }


    function validateJson() {

        const value = textarea.value.trim();

        if (!value) {

            showMessage(
                'danger',
                '❌ JSON cannot be empty.'
            );

            return false;
        }


        try {

            const parsed = JSON.parse(value);


            if (
                parsed === null ||
                Array.isArray(parsed) ||
                typeof parsed !== 'object'
            ) {

                showMessage(
                    'danger',
                    '❌ JSON root must be an object.'
                );

                return false;
            }


            if (
                typeof parsed.brand !== 'string' ||
                parsed.brand.trim() === ''
            ) {

                showMessage(
                    'danger',
                    '❌ The JSON must contain a valid "brand" field.'
                );

                return false;
            }


            if (!Array.isArray(parsed.tags)) {

                showMessage(
                    'danger',
                    '❌ The JSON must contain a "tags" array.'
                );

                return false;
            }


            for (const tag of parsed.tags) {

                if (
                    typeof tag !== 'string' ||
                    tag.trim() === ''
                ) {

                    showMessage(
                        'danger',
                        '❌ Every tag must be a non-empty string.'
                    );

                    return false;
                }

            }


            showMessage(
                'success',
                '✅ Valid JSON! Brand and tags structure are correct.'
            );

            return true;

        } catch (error) {

            showMessage(
                'danger',
                '❌ Invalid JSON: ' + error.message
            );

            return false;
        }
    }


    validateButton.addEventListener(
        'click',
        validateJson
    );


    form.addEventListener(
        'submit',
        function (event) {

            if (!validateJson()) {

                event.preventDefault();

                textarea.focus();
            }

        }
    );

});

</script>

@endsection