(() => {
    const normalizeColumns = (columns, actionsRenderer) => {
        const finalColumns = [...columns];
        if (typeof actionsRenderer === 'function') {
            finalColumns.push({
                data: null,
                orderable: false,
                searchable: false,
                render: actionsRenderer,
            });
        }
        return finalColumns;
    };

    const create = (config) => {
        const table = document.querySelector(config.tableSelector);
        if (!table) {
            throw new Error(`Tabla no encontrada: ${config.tableSelector}`);
        }

        const actionsRenderer = config.actionsRenderer || null;
        const columns = normalizeColumns(config.columns || [], actionsRenderer);
        const dt = new DataTable(table, {
            data: [],
            columns,
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json',
            },
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

            const button = target.closest('button[data-action]');
            if (!(button instanceof HTMLButtonElement)) {
                return;
            }

            const action = button.dataset.action || '';
            const row = resolveRowData(button);
            if (!row) {
                return;
            }

            if (action === 'edit' && typeof config.onEdit === 'function') {
                config.onEdit(row);
            }
            if (action === 'delete' && typeof config.onDelete === 'function') {
                config.onDelete(row);
            }
        });

        const reload = async () => {
            const payload = await window.AppHttp.getJson(config.loadUrl);
            const rows = payload.data || [];
            dt.clear();
            dt.rows.add(rows);
            dt.draw();
        };

        return {
            reload,
            instance: dt,
        };
    };

    window.AppDataGrid = { create };
})();
