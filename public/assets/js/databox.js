(() => {
    const render = (container, stats) => {
        if (!(container instanceof HTMLElement)) {
            return;
        }

        const items = [
            { key: 'categorias', label: 'Categorías', color: 'primary' },
            { key: 'marcas', label: 'Marcas', color: 'success' },
            { key: 'productos', label: 'Productos', color: 'warning' },
        ];

        container.innerHTML = items.map((item) => {
            const value = stats && Number.isFinite(Number(stats[item.key])) ? String(stats[item.key]) : '0';
            return `
                <div class="col-12 col-md-4">
                    <div class="card shadow-sm databox-card border-${item.color}">
                        <div class="card-body">
                            <div class="text-muted">${item.label}</div>
                            <div class="display-6 fw-semibold">${value}</div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    };

    window.AppDatabox = { render };
})();
