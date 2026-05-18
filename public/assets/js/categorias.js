(() => {
    const modalElement = document.getElementById('categoriaModal');
    const form = document.getElementById('categoriaForm');
    const btnNueva = document.getElementById('btnNuevaCategoria');
    const idInput = document.getElementById('categoriaId');
    const nombreInput = document.getElementById('categoriaNombre');
    const title = document.getElementById('categoriaModalTitle');
    const submitBtn = document.getElementById('categoriaSubmitBtn');

    if (!modalElement || !form || !btnNueva || !idInput || !nombreInput || !title || !submitBtn) {
        return;
    }

    const modal = new bootstrap.Modal(modalElement);
    const grid = window.AppDataGrid.create({
        tableSelector: '#categoriasTable',
        loadUrl: '/api/categorias',
        columns: [
            { data: 'id' },
            { data: 'nombre' },
        ],
        actionsRenderer: () => `
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-primary" data-action="edit">Editar</button>
                <button type="button" class="btn btn-sm btn-outline-danger" data-action="delete">Eliminar</button>
            </div>
        `,
        onEdit: (row) => openEdit(row),
        onDelete: (row) => confirmDelete(row),
    });

    const resetForm = () => {
        form.reset();
        idInput.value = '';
        nombreInput.value = '';
    };

    const openCreate = () => {
        resetForm();
        title.textContent = 'Nueva categoría';
        submitBtn.textContent = 'Crear';
        modal.show();
        setTimeout(() => nombreInput.focus(), 250);
    };

    const openEdit = (row) => {
        resetForm();
        title.textContent = 'Editar categoría';
        submitBtn.textContent = 'Guardar';
        idInput.value = String(row.id);
        nombreInput.value = String(row.nombre || '');
        modal.show();
        setTimeout(() => nombreInput.focus(), 250);
    };

    const confirmDelete = async (row) => {
        const result = await window.Swal.fire({
            title: '¿Eliminar categoría?',
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
            await window.AppHttp.postForm(`/api/categorias/${row.id}/eliminar`, {});
            await grid.reload();
            window.Swal.fire({ icon: 'success', title: 'Eliminada', timer: 1200, showConfirmButton: false });
        } catch (e) {
            const message = e instanceof Error ? e.message : 'No se pudo eliminar.';
            window.Swal.fire({ icon: 'error', title: 'Error', text: message });
        }
    };

    btnNueva.addEventListener('click', openCreate);

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const id = idInput.value || '';
        const nombre = nombreInput.value || '';

        const isEdit = id !== '';
        const url = isEdit ? `/api/categorias/${id}/editar` : '/api/categorias/crear';

        try {
            await window.AppHttp.postForm(url, { nombre });
            modal.hide();
            await grid.reload();
            window.Swal.fire({ icon: 'success', title: isEdit ? 'Actualizada' : 'Creada', timer: 1200, showConfirmButton: false });
        } catch (e) {
            const message = e instanceof Error ? e.message : 'No se pudo guardar.';
            window.Swal.fire({ icon: 'error', title: 'Error', text: message });
        }
    });

    grid.reload();
})();
