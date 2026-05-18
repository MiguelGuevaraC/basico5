@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 fw-bold">Dashboard</h1>
    </div>

    <div id="databox-container" class="row g-4 mb-4"></div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Productos por Categoría</h5>
                    <canvas id="chartProductosPorCategoria"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Productos por Marca</h5>
                    <canvas id="chartProductosPorMarca"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Stock por Categoría</h5>
                    <canvas id="chartStockPorCategoria"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script defer src="{{ asset('assets/js/dashboard.js') }}"></script>
@endpush
