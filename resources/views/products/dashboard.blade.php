@extends('layouts.app')

@section('title', 'Product Dashboard')

@section('content')

<div class="container-fluid px-0">

{{-- =========================================================
     DASHBOARD HEADER
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            📊 Product Dashboard
        </h3>

        <p class="text-muted mb-0">
            Overview of your product inventory and management.
        </p>
    </div>

    <div class="d-flex gap-2 mt-3 mt-md-0">

        <a href="{{ route('products.index') }}"
           class="btn btn-outline-dark">
            📦 Products
        </a>

        <a href="{{ route('products.create') }}"
           class="btn btn-primary">
            + Add Product
        </a>

    </div>

</div>


{{-- =========================================================
     STATISTICS CARDS
========================================================== --}}
<div class="row g-3 mb-4">

    {{-- Total Products --}}
    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start">

                    <div>
                        <p class="text-muted mb-1">
                            Total Products
                        </p>

                        <h2 class="fw-bold mb-0">
                            {{ $totalProducts }}
                        </h2>
                    </div>

                    <div class="fs-2">
                        📦
                    </div>

                </div>

                <small class="text-muted">
                    Active products only
                </small>

            </div>

        </div>

    </div>


    {{-- Active --}}
    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start">

                    <div>
                        <p class="text-muted mb-1">
                            Active Products
                        </p>

                        <h2 class="fw-bold text-success mb-0">
                            {{ $activeProducts }}
                        </h2>
                    </div>

                    <div class="fs-2">
                        ✅
                    </div>

                </div>

                <small class="text-muted">
                    Currently available
                </small>

            </div>

        </div>

    </div>


    {{-- Inactive --}}
    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start">

                    <div>
                        <p class="text-muted mb-1">
                            Inactive Products
                        </p>

                        <h2 class="fw-bold text-secondary mb-0">
                            {{ $inactiveProducts }}
                        </h2>
                    </div>

                    <div class="fs-2">
                        ⏸️
                    </div>

                </div>

                <small class="text-muted">
                    Currently disabled
                </small>

            </div>

        </div>

    </div>


    {{-- Trash --}}
    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start">

                    <div>
                        <p class="text-muted mb-1">
                            Trashed Products
                        </p>

                        <h2 class="fw-bold text-danger mb-0">
                            {{ $trashedProducts }}
                        </h2>
                    </div>

                    <div class="fs-2">
                        🗑️
                    </div>

                </div>

                <small class="text-muted">
                    Soft deleted products
                </small>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     INVENTORY STATISTICS
========================================================== --}}
<div class="row g-3 mb-4">

    {{-- Out of Stock --}}
    <div class="col-lg-3 col-md-6">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <p class="text-muted mb-1">
                    Out of Stock
                </p>

                <h3 class="fw-bold text-danger">
                    {{ $outOfStock }}
                </h3>

                <div class="progress" style="height: 6px;">

                    @php
                        $outOfStockPercentage = $totalProducts > 0
                            ? min(100, ($outOfStock / $totalProducts) * 100)
                            : 0;
                    @endphp

                    <div class="progress-bar bg-danger"
                         style="width: {{ $outOfStockPercentage }}%">
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Low Stock --}}
    <div class="col-lg-3 col-md-6">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <p class="text-muted mb-1">
                    Low Stock
                </p>

                <h3 class="fw-bold text-warning">
                    {{ $lowStock }}
                </h3>

                <small class="text-muted">
                    Stock between 1 and 5
                </small>

            </div>

        </div>

    </div>


    {{-- Created Today --}}
    <div class="col-lg-3 col-md-6">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <p class="text-muted mb-1">
                    Created Today
                </p>

                <h3 class="fw-bold text-primary">
                    {{ $createdToday }}
                </h3>

                <small class="text-muted">
                    Products added today
                </small>

            </div>

        </div>

    </div>


    {{-- Created This Month --}}
    <div class="col-lg-3 col-md-6">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <p class="text-muted mb-1">
                    Created This Month
                </p>

                <h3 class="fw-bold text-info">
                    {{ $createdThisMonth }}
                </h3>

                <small class="text-muted">
                    Products added this month
                </small>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     TOTAL STOCK
========================================================== --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-body">

        <div class="row align-items-center">

            <div class="col-md-8">

                <p class="text-muted mb-1">
                    Total Inventory Stock
                </p>

                <h2 class="fw-bold mb-1">
                    {{ number_format($totalStock) }}
                </h2>

                <small class="text-muted">
                    Total quantity available across all active products
                </small>

            </div>

            <div class="col-md-4 text-md-end mt-3 mt-md-0">

                <span class="display-5">
                    📊
                </span>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     PRODUCT STATUS SUMMARY
========================================================== --}}
<div class="row g-3 mb-4">

    <div class="col-lg-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-header bg-white border-0 pt-3">

                <h5 class="fw-bold mb-0">
                    Product Status
                </h5>

            </div>

            <div class="card-body">

                @php
                    $activePercentage = $totalProducts > 0
                        ? ($activeProducts / $totalProducts) * 100
                        : 0;

                    $inactivePercentage = $totalProducts > 0
                        ? ($inactiveProducts / $totalProducts) * 100
                        : 0;
                @endphp

                <div class="mb-3">

                    <div class="d-flex justify-content-between mb-1">

                        <span>
                            Active
                        </span>

                        <strong>
                            {{ $activeProducts }}
                        </strong>

                    </div>

                    <div class="progress">

                        <div class="progress-bar bg-success"
                             style="width: {{ $activePercentage }}%">
                        </div>

                    </div>

                </div>


                <div>

                    <div class="d-flex justify-content-between mb-1">

                        <span>
                            Inactive
                        </span>

                        <strong>
                            {{ $inactiveProducts }}
                        </strong>

                    </div>

                    <div class="progress">

                        <div class="progress-bar bg-secondary"
                             style="width: {{ $inactivePercentage }}%">
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Inventory --}}
    <div class="col-lg-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-header bg-white border-0 pt-3">

                <h5 class="fw-bold mb-0">
                    Inventory Status
                </h5>

            </div>

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">

                    <div>
                        <span class="fw-semibold">
                            🟢 Available
                        </span>

                        <small class="d-block text-muted">
                            Products with stock
                        </small>
                    </div>

                    <strong class="fs-5">
                        {{ $totalProducts - $outOfStock }}
                    </strong>

                </div>


                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">

                    <div>
                        <span class="fw-semibold">
                            🟡 Low Stock
                        </span>

                        <small class="d-block text-muted">
                            1–5 units remaining
                        </small>
                    </div>

                    <strong class="fs-5 text-warning">
                        {{ $lowStock }}
                    </strong>

                </div>


                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <span class="fw-semibold">
                            🔴 Out of Stock
                        </span>

                        <small class="d-block text-muted">
                            0 units remaining
                        </small>
                    </div>

                    <strong class="fs-5 text-danger">
                        {{ $outOfStock }}
                    </strong>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     LATEST PRODUCTS
========================================================== --}}
<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-0 py-3">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h5 class="fw-bold mb-1">
                    Latest Products
                </h5>

                <small class="text-muted">
                    Recently added products
                </small>

            </div>

            <a href="{{ route('products.index') }}"
               class="btn btn-sm btn-outline-primary">
                View All
            </a>

        </div>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th class="ps-3">
                            #
                        </th>

                        <th>
                            Product
                        </th>

                        <th>
                            Brand
                        </th>

                        <th>
                            Price
                        </th>

                        <th>
                            Stock
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="text-end pe-3">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($latestProducts as $product)

                        <tr>

                            <td class="ps-3">
                                #{{ $product->id }}
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

                                @if($product->stock > 5)

                                    <span class="badge bg-success">
                                        {{ $product->stock }}
                                    </span>

                                @elseif($product->stock > 0)

                                    <span class="badge bg-warning text-dark">
                                        {{ $product->stock }}
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Out of stock
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($product->status === 'active')

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            <td class="text-end pe-3">

                                <a href="{{ route('products.show', $product) }}"
                                   class="btn btn-sm btn-outline-info">
                                    View
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center py-5 text-muted">

                                📦 No products found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


</div>

@endsection
