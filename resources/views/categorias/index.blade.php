@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 fw-bold">Categorías</h1>
        <button type="button" class="btn btn-primary" id="btnNuevaCategoria">
            <i class="bi bi-plus-lg me-1"></i>Nueva categoría
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover w-100" id="categoriasTable">
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

    <div class="modal fade" id="categoriaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="categoriaForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="categoriaModalTitle">Categoría</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="categoriaId">
                        <div class="mb-3">
                            <label class="form-label" for="categoriaNombre">Nombre</label>
                            <input class="form-control" type="text" id="categoriaNombre" required maxlength="120">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="categoriaSubmitBtn">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script defer src="{{ asset('assets/js/categorias.js') }}"></script>
@endpush
