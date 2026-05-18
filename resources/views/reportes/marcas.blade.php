<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Marcas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #1e293b;
        }
        .page {
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            color: #1e293b;
            margin-bottom: 5px;
        }
        .header .subtitle {
            font-size: 14px;
            color: #64748b;
        }
        .meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 11px;
            color: #64748b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
        }
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        tr:hover {
            background-color: #f8fafc;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <h1>CATÁLOGO DE MARCAS</h1>
            <div class="subtitle">Sistema de Gestión de Inventario</div>
        </div>
        <div class="meta">
            <div>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</div>
            <div>Total: {{ $marcas->count() }} registros</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">#</th>
                    <th style="width: 90%;">Marca</th>
                </tr>
            </thead>
            <tbody>
                @foreach($marcas as $index => $marca)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $marca->nombre }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="footer">
            Documento generado automáticamente - Sistema MVC Productos
        </div>
    </div>
</body>
</html>
