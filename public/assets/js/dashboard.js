(() => {
    const container = document.getElementById('databox-container');
    if (!container) {
        return;
    }

    const chartColors = [
        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', 
        '#ec4899', '#06b6d4', '#84cc16'
    ];

    const createChart = (ctx, data, label, type = 'bar') => {
        const options = {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        };

        if (type === 'bar') {
            options.scales = {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0//sin decimales
                    }
                }
            };
        }

        return new window.Chart(ctx, {
            type: type,
            data: {
                labels: data.map(item => item.nombre),
                datasets: [{
                    label: label,
                    data: data.map(item => item.total),
                    backgroundColor: chartColors,
                    borderColor: '#ffffff',
                    borderWidth: type === 'bar' ? 1 : 2,
                }]
            },
            options: options
        });
    };

    const load = async () => {
        try {
            const payload = await window.AppHttp.getJson('/api/dashboard');
            const data = payload.data || {};
            
            window.AppDatabox.render(container, data);

            const ctx1 = document.getElementById('chartProductosPorCategoria')?.getContext('2d');
            const ctx2 = document.getElementById('chartProductosPorMarca')?.getContext('2d');
            const ctx3 = document.getElementById('chartStockPorCategoria')?.getContext('2d');

            if (ctx1 && data.productosPorCategoria) {
                createChart(ctx1, data.productosPorCategoria, 'Productos', 'bar');
            }
            if (ctx2 && data.productosPorMarca) {
                createChart(ctx2, data.productosPorMarca, 'Productos', 'pie');
            }
            if (ctx3 && data.stockPorCategoria) {
                createChart(ctx3, data.stockPorCategoria, 'Stock', 'bar');
            }
        } catch (e) {
            const message = e instanceof Error ? e.message : 'No se pudo cargar el dashboard.';
            if (window.Swal) {
                window.Swal.fire({ icon: 'error', title: 'Error', text: message });
            }
        }
    };

    load();
})();
