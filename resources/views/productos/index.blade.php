@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 fw-bold">Productos</h1>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Reporte PDF
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('reportes.productos', 'completo') }}" target="_blank">
                        <i class="bi bi-list-ul me-2"></i>Listado Completo
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('reportes.productos', 'categoria') }}" target="_blank">
                        <i class="bi bi-tag me-2"></i>Ordenado por Categoría
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('reportes.productos', 'marca') }}" target="_blank">
                        <i class="bi bi-award me-2"></i>Ordenado por Marca
                    </a></li>
                </ul>
            </div>
            <button type="button" class="btn btn-primary" id="btnNuevoProducto">
                <i class="bi bi-plus-lg me-1"></i>Nuevo producto
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover w-100" id="productosTable">
                <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Nombre</th>
                    <th style="width: 120px;">Precio</th>
                    <th style="width: 100px;">Stock</th>
                    <th>Categoría</th>
                    <th>Marca</th>
                    <th style="width: 150px;">Fotos</th>
                    <th style="width: 160px;">Acciones</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="productoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="productoForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productoModalTitle">Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="productoId">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="productoNombre">Nombre</label>
                                <input class="form-control" type="text" id="productoNombre" required maxlength="255">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="productoPrecio">Precio</label>
                                <input class="form-control" type="number" step="0.01" min="0" id="productoPrecio" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="productoStock">Stock</label>
                                <input class="form-control" type="number" step="1" min="0" id="productoStock">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Categoría</label>
                                <div class="input-group">
                                    <select class="form-select flex-grow-1" id="productoCategoria" style="flex: 0 0 70%;"></select>
                                    <button type="button" class="btn btn-outline-primary" style="flex: 0 0 30%;height:39px" id="btnAddCategoria">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Marca</label>
                                <div class="input-group">
                                    <select class="form-select" id="productoMarca" style="flex: 0 0 70%;"></select>
                                    <button type="button" class="btn btn-outline-primary" style="flex: 0 0 30%;height:39px" id="btnAddMarca">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Fotos existentes</label>
                                <div id="existingFotosContainer" class="row g-2 mb-3"></div>
                                <label class="form-label" for="productoFotos">Agregar nuevas fotos</label>
                                <input class="form-control" type="file" id="productoFotos" multiple accept="image/*">
                                <div class="form-text">Puedes seleccionar múltiples imágenes.</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="productoSubmitBtn">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalTitle">Fotos del producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center" id="imageModalBody">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-primary" id="imageModalPrev">
                        <i class="bi bi-arrow-left"></i> Anterior
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-outline-primary" id="imageModalNext">
                        Siguiente <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="categoriaModalFromProducto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="categoriaFormFromProducto">
                    <div class="modal-header">
                        <h5 class="modal-title">Nueva categoría</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="categoriaNombreFromProducto">Nombre</label>
                            <input class="form-control" type="text" id="categoriaNombreFromProducto" required maxlength="120">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="marcaModalFromProducto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="marcaFormFromProducto">
                    <div class="modal-header">
                        <h5 class="modal-title">Nueva marca</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="marcaNombreFromProducto">Nombre</label>
                            <input class="form-control" type="text" id="marcaNombreFromProducto" required maxlength="120">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script defer src="{{ asset('assets/js/productos.js') }}"></script>
@endpush
