(() => {
    const modalElement = document.getElementById('productoModal');
    const imageModalElement = document.getElementById('imageModal');
    const categoriaModalElement = document.getElementById('categoriaModalFromProducto');
    const marcaModalElement = document.getElementById('marcaModalFromProducto');
    const form = document.getElementById('productoForm');
    const categoriaForm = document.getElementById('categoriaFormFromProducto');
    const marcaForm = document.getElementById('marcaFormFromProducto');
    const btnNuevo = document.getElementById('btnNuevoProducto');
    const btnAddCategoria = document.getElementById('btnAddCategoria');
    const btnAddMarca = document.getElementById('btnAddMarca');
    const idInput = document.getElementById('productoId');
    const nombreInput = document.getElementById('productoNombre');
    const precioInput = document.getElementById('productoPrecio');
    const stockInput = document.getElementById('productoStock');
    const categoriaSelect = document.getElementById('productoCategoria');
    const marcaSelect = document.getElementById('productoMarca');
    const fotosInput = document.getElementById('productoFotos');
    const existingFotosContainer = document.getElementById('existingFotosContainer');
    const title = document.getElementById('productoModalTitle');
    const submitBtn = document.getElementById('productoSubmitBtn');
    const categoriaNombreInput = document.getElementById('categoriaNombreFromProducto');
    const marcaNombreInput = document.getElementById('marcaNombreFromProducto');

    if (!modalElement || !imageModalElement || !categoriaModalElement || !marcaModalElement || !form || !categoriaForm || !marcaForm || !btnNuevo || !btnAddCategoria || !btnAddMarca || !idInput || !nombreInput || !precioInput || !stockInput || !categoriaSelect || !marcaSelect || !fotosInput || !existingFotosContainer || !title || !submitBtn || !categoriaNombreInput || !marcaNombreInput) {
        return;
    }

    const modal = new bootstrap.Modal(modalElement);
    const imageModal = new bootstrap.Modal(imageModalElement);
    const categoriaModal = new bootstrap.Modal(categoriaModalElement);
    const marcaModal = new bootstrap.Modal(marcaModalElement);

    let currentProductFotos = [];
    let fotosToDelete = [];

    const ensureSelect2 = (selectElement, url, selectedValue = null) => {
        const $el = window.jQuery(selectElement);
        if ($el.data('select2')) {
            $el.select2('destroy');
        }
        
        $el.select2({
            theme: 'bootstrap4',
            dropdownParent: window.jQuery(modalElement),
            width: '100%',
            ajax: {
                url: window.AppHttp.buildUrl(url),
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.data || []
                    };
                },
                cache: true
            },
            minimumInputLength: 0,
            placeholder: 'Seleccione...',
            allowClear: true
        });

        if (selectedValue) {
            const option = new Option('', selectedValue, true, true);
            $el.append(option).trigger('change');
            
            $.ajax({
                url: window.AppHttp.buildUrl(url),
                dataType: 'json',
                success: function(data) {
                    const selected = data.data.find(item => String(item.id) === String(selectedValue));
                    if (selected) {
                        const opt = new Option(selected.text, selected.id, true, true);
                        $el.empty().append(opt).trigger('change');
                    }
                }
            });
        }
    };

    const loadFormOptions = async (selectedCategoria = null, selectedMarca = null) => {
        ensureSelect2(categoriaSelect, '/api/categorias/options', selectedCategoria);
        ensureSelect2(marcaSelect, '/api/marcas/options', selectedMarca);
    };

    const renderExistingFotos = () => {
        existingFotosContainer.innerHTML = '';
        
        currentProductFotos.forEach((foto) => {
            const col = document.createElement('div');
            col.className = 'col-6 col-md-4 col-lg-3';
            
            const isDeleted = fotosToDelete.includes(foto.id);
            
            col.innerHTML = `
                <div class="card ${isDeleted ? 'opacity-50' : ''}">
                    <img src="${foto.url}" class="card-img-top" alt="Foto del producto" style="height: 150px; object-fit: cover;">
                    <div class="card-body p-2 text-center">
                        <button type="button" class="btn btn-sm ${isDeleted ? 'btn-outline-success' : 'btn-outline-danger'}" data-foto-id="${foto.id}" data-action="${isDeleted ? 'restore-foto' : 'delete-foto'}">
                            <i class="bi ${isDeleted ? 'bi-arrow-counterclockwise' : 'bi-trash'}"></i>
                        </button>
                    </div>
                </div>
            `;
            
            existingFotosContainer.appendChild(col);
        });
    };

    const resetForm = () => {
        form.reset();
        categoriaForm.reset();
        marcaForm.reset();
        idInput.value = '';
        nombreInput.value = '';
        precioInput.value = '';
        stockInput.value = '';
        stockInput.removeAttribute('readonly');
        existingFotosContainer.innerHTML = '';
        currentProductFotos = [];
        fotosToDelete = [];
        window.jQuery(categoriaSelect).trigger('change');
        window.jQuery(marcaSelect).trigger('change');
    };

    const openCreate = async () => {
        title.textContent = 'Nuevo producto';
        submitBtn.textContent = 'Crear';
        await loadFormOptions();
        resetForm();
        modal.show();
        setTimeout(() => nombreInput.focus(), 250);
    };

    const openEdit = async (row) => {
        title.textContent = 'Editar producto';
        submitBtn.textContent = 'Guardar';
        
        form.reset();
        categoriaForm.reset();
        marcaForm.reset();
        idInput.value = '';
        nombreInput.value = '';
        precioInput.value = '';
        stockInput.value = '';
        existingFotosContainer.innerHTML = '';
        currentProductFotos = [];
        fotosToDelete = [];
        
        await loadFormOptions(row.categoria_id, row.marca_id);
        
        idInput.value = String(row.id);
        nombreInput.value = String(row.nombre || '');
        precioInput.value = String(row.precio || '');
        stockInput.value = String(row.stock || '');
        stockInput.setAttribute('readonly', 'readonly');
        currentProductFotos = row.fotos || [];
        renderExistingFotos();
        modal.show();
        setTimeout(() => nombreInput.focus(), 250);
    };

    const openImageViewer = (fotos, index) => {
        const imageModalBody = document.getElementById('imageModalBody');
        const imageModalTitle = document.getElementById('imageModalTitle');
        const prevBtn = document.getElementById('imageModalPrev');
        const nextBtn = document.getElementById('imageModalNext');
        
        let currentIndex = index;
        const total = fotos.length;

        const updateViewer = () => {
            const foto = fotos[currentIndex];
            imageModalBody.innerHTML = `<img src="${foto.url}" class="img-fluid" alt="Foto del producto">`;
            imageModalTitle.textContent = `Foto ${currentIndex + 1} de ${total}`;
            
            prevBtn.disabled = currentIndex === 0;
            nextBtn.disabled = currentIndex === total - 1;
        };

        prevBtn.onclick = () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateViewer();
            }
        };

        nextBtn.onclick = () => {
            if (currentIndex < total - 1) {
                currentIndex++;
                updateViewer();
            }
        };

        updateViewer();
        imageModal.show();
    };

    const confirmDelete = async (row) => {
        const result = await window.Swal.fire({
            title: '¿Eliminar producto?',
            text: String(row.nombre || ''),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
        });

        if (!result.isConfirmed) {
            return;
        }

        try {
            await window.AppHttp.postForm(`/api/productos/${row.id}/eliminar`, {});
            await grid.reload();
            window.Swal.fire({ icon: 'success', title: 'Eliminado', timer: 1200, showConfirmButton: false });
        } catch (e) {
            const message = e instanceof Error ? e.message : 'No se pudo eliminar.';
            window.Swal.fire({ icon: 'error', title: 'Error', text: message });
        }
    };

    const deleteFoto = async (productoId, fotoId) => {
        try {
            await window.AppHttp.postForm(`/api/v1/productos/${productoId}/fotos/${fotoId}`, {
                _method: 'DELETE'
            });
        } catch (e) {
            console.error('Error al eliminar foto:', e);
        }
    };

    const formatMoney = (value) => {
        const n = Number(value);
        if (!Number.isFinite(n)) {
            return String(value || '');
        }
        return n.toFixed(2);
    };

    const renderFotos = (fotos) => {
        if (!fotos || fotos.length === 0) {
            return '<span class="text-muted">Sin fotos</span>';
        }
        const lastFoto = fotos[fotos.length - 1];
        return `
            <div class="d-flex align-items-center gap-2">
                <img src="${lastFoto.url}" alt="Foto del producto" style="width: 50px; height: 50px; object-fit: cover; border-radius: 0.5rem; cursor: pointer;" data-action="view-images">
                <span class="badge bg-secondary">${fotos.length}</span>
            </div>
        `;
    };

    const table = document.getElementById('productosTable');
    const dt = new DataTable(table, {
        serverSide: true,
        processing: true,
        responsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json',
        },
        ajax: async (data, callback, settings) => {
            const page = Math.floor(data.start / data.length) + 1;
            const perPage = data.length;
            const url = `/api/productos?page=${page}&per_page=${perPage}`;
            
            try {
                const payload = await window.AppHttp.getJson(url);
                callback({
                    draw: data.draw,
                    recordsTotal: payload.pagination.total,
                    recordsFiltered: payload.pagination.total,
                    data: payload.data,
                });
            } catch (e) {
                callback({
                    draw: data.draw,
                    recordsTotal: 0,
                    recordsFiltered: 0,
                    data: [],
                });
            }
        },
        columns: [
            { data: 'id' },
            { data: 'nombre' },
            { data: 'precio', render: (data) => formatMoney(data) },
            { data: 'stock' },
            { data: 'categoria' },
            { data: 'marca' },
            { data: 'fotos', render: (data) => renderFotos(data) },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: () => `
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" data-action="edit">Editar</button>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-action="delete">Eliminar</button>
                    </div>
                `,
            },
        ],
    });

    const resolveRowData = (target) => {
        const tr = target.closest('tr');
        if (!tr) {
            return null;
        }
        return dt.row(tr).data() || null;
    };

    table.addEventListener('click', (event) => {
        const target = event.target;
        if (!(target instanceof HTMLElement)) {
            return;
        }

        const elementWithAction = target.closest('[data-action]');
        if (!elementWithAction) {
            return;
        }

        const action = elementWithAction.dataset.action || '';
        const row = resolveRowData(elementWithAction);
        if (!row) {
            return;
        }

        if (action === 'edit') {
            openEdit(row);
        } else if (action === 'delete') {
            confirmDelete(row);
        } else if (action === 'view-images') {
            openImageViewer(row.fotos, 0);
        }
    });

    existingFotosContainer.addEventListener('click', (event) => {
        const button = event.target.closest('button[data-action]');
        if (!button) return;

        const fotoId = parseInt(button.dataset.fotoId);
        const action = button.dataset.action;

        if (action === 'delete-foto') {
            fotosToDelete.push(fotoId);
        } else if (action === 'restore-foto') {
            fotosToDelete = fotosToDelete.filter(id => id !== fotoId);
        }

        renderExistingFotos();
    });

    const grid = {
        reload: () => dt.ajax.reload(),
        instance: dt,
    };

    btnNuevo.addEventListener('click', () => {
        openCreate().catch((e) => {
            const message = e instanceof Error ? e.message : 'No se pudo abrir el formulario.';
            window.Swal.fire({ icon: 'error', title: 'Error', text: message });
        });
    });

    btnAddCategoria.addEventListener('click', () => {
        categoriaModal.show();
    });

    btnAddMarca.addEventListener('click', () => {
        marcaModal.show();
    });

    categoriaForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const nombre = categoriaNombreInput.value;
        
        try {
            const payload = await window.AppHttp.postForm('/api/categorias/crear', { nombre });
            const newCategoriaId = payload.data.id;
            
            const newOption = new Option(nombre, newCategoriaId, true, true);
            window.jQuery(categoriaSelect).empty().append(newOption).trigger('change');
            
            categoriaModal.hide();
            categoriaForm.reset();
            window.Swal.fire({ icon: 'success', title: 'Categoría creada', timer: 1200, showConfirmButton: false });
        } catch (error) {
            const message = error instanceof Error ? error.message : 'Error al crear la categoría';
            window.Swal.fire({ icon: 'error', title: 'Error', text: message });
        }
    });

    marcaForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const nombre = marcaNombreInput.value;
        
        try {
            const payload = await window.AppHttp.postForm('/api/marcas/crear', { nombre });
            const newMarcaId = payload.data.id;
            
            const newOption = new Option(nombre, newMarcaId, true, true);
            window.jQuery(marcaSelect).empty().append(newOption).trigger('change');
            
            marcaModal.hide();
            marcaForm.reset();
            window.Swal.fire({ icon: 'success', title: 'Marca creada', timer: 1200, showConfirmButton: false });
        } catch (error) {
            const message = error instanceof Error ? error.message : 'Error al crear la marca';
            window.Swal.fire({ icon: 'error', title: 'Error', text: message });
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const id = idInput.value || '';
        const payload = {
            nombre: nombreInput.value || '',
            precio: precioInput.value || '',
            categoria_id: categoriaSelect.value || '',
            marca_id: marcaSelect.value || '',
        };

        if (id === '' && stockInput.value) {
            payload.stock = stockInput.value;
        }

        if (fotosInput.files && fotosInput.files.length > 0) {
            payload.fotos = fotosInput.files;
        }

        const isEdit = id !== '';
        if (isEdit && fotosToDelete.length > 0) {
            fotosToDelete.forEach((fotoId, index) => {
                payload[`fotos_eliminar[${index}]`] = fotoId;
            });
        }

        const url = isEdit ? `/api/productos/${id}/editar` : '/api/productos/crear';

        try {
            await window.AppHttp.postForm(url, payload);
            
            modal.hide();
            grid.reload();
            window.Swal.fire({ icon: 'success', title: isEdit ? 'Actualizado' : 'Creado', timer: 1200, showConfirmButton: false });
        } catch (e) {
            const message = e instanceof Error ? e.message : 'No se pudo guardar.';
            window.Swal.fire({ icon: 'error', title: 'Error', text: message });
        }
    });
})();
