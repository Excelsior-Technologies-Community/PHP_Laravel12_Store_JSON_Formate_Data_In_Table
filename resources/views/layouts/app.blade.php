<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Product Manager')
    </title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <style>

        body {
            background: #f5f7fb;
        }

        .navbar-brand {
            font-weight: 700;
        }

        .card {
            border-radius: 12px;
        }

        .table th {
            white-space: nowrap;
        }

        .badge {
            margin-right: 3px;
        }

        .stat-card {
            transition: 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

    </style>

</head>


<body>


<nav class="navbar navbar-dark bg-dark px-4">

    <div class="container-fluid">

        <a class="navbar-brand"
           href="{{ route('products.dashboard') }}">

            🛒 Product Manager

        </a>


        <div class="d-flex gap-2">

            <a href="{{ route('products.dashboard') }}"
               class="btn btn-outline-light btn-sm">

                Dashboard

            </a>


            <a href="{{ route('products.index') }}"
               class="btn btn-outline-light btn-sm">

                Products

            </a>


            <a href="{{ route('products.trash') }}"
               class="btn btn-outline-danger btn-sm">

                🗑️ Trash

            </a>


            <a href="{{ route('products.create') }}"
               class="btn btn-success btn-sm">

                + Add Product

            </a>

        </div>

    </div>

</nav>


<div class="container-fluid py-4">


    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <strong>
                ✓ Success!
            </strong>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>
                Please fix the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @yield('content')

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>