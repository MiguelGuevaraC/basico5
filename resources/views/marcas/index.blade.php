@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 fw-bold">Marcas</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('reportes.marcas') }}" target="_blank" class="btn btn-success">
                <i class="bi bi-file-earmark-pdf me-1"></i>Reporte PDF
            </a>
            <button type="button" class="btn btn-primary" id="btnNuevaMarca">
                <i class="bi bi-plus-lg me-1"></i>Nueva marca
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover w-100" id="marcasTable">
                <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Nombre</th>
                    <th style="width: 160px;">Acciones</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="marcaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="marcaForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="marcaModalTitle">Marca</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="marcaId">
                        <div class="mb-3">
                            <label class="form-label" for="marcaNombre">Nombre</label>
                            <input class="form-control" type="text" id="marcaNombre" required maxlength="120">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="marcaSubmitBtn">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script defer src="{{ asset('assets/js/marcas.js') }}"></script>
@endpush
