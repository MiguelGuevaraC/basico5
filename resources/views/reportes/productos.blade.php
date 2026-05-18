<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Productos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #1e293b;
        }
        .page {
            padding: 25px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 18px;
        }
        .header h1 {
            font-size: 22px;
            color: #1e293b;
            margin-bottom: 5px;
        }
        .header .subtitle {
            font-size: 13px;
            color: #64748b;
        }
        .header .tipo-reporte {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 16px;
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 18px;
            font-size: 10px;
            color: #64748b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.05em;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        tr:hover {
            background-color: #f8fafc;
        }
        .precio {
            text-align: right;
        }
        .stock {
            text-align: center;
        }
        .foto {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }
        .seccion-titulo {
            background: linear-gradient(90deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 12px 15px;
            margin: 20px 0 10px;
            border-left: 4px solid #3b82f6;
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
        }
        .seccion-titulo:first-child {
            margin-top: 0;
        }
        .footer {
            margin-top: 25px;
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
            <h1>LISTADO DE PRODUCTOS</h1>
            <div class="subtitle">Sistema de Gestión de Inventario</div>
            <div class="tipo-reporte">{{ $tituloOrden }}</div>
        </div>
        <div class="meta">
            <div>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</div>
            <div>Total: {{ $productos->count() }} productos</div>
        </div>

        @if($agrupacion && count($secciones) > 0)
            @foreach($secciones as $nombreSeccion => $productosSeccion)
                <div class="seccion-titulo">{{ $nombreSeccion }} ({{ $productosSeccion->count() }})</div>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 6%;">#</th>
                            <th style="width: 8%;">Foto</th>
                            <th style="width: 30%;">Producto</th>
                            <th style="width: 18%;">{{ $agrupacion === 'categoria' ? 'Marca' : 'Categoría' }}</th>
                            <th style="width: 15%;" class="precio">Precio</th>
                            <th style="width: 10%;" class="stock">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productosSeccion as $index => $producto)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @php
                                        $foto = $producto->fotos->first();
                                    @endphp
                                    @if($foto)
                                        <img src="{{ public_path('storage/' . $foto->ruta) }}" class="foto" alt="Foto">
                                    @else
                                        <span style="color: #94a3b8;">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $agrupacion === 'categoria' ? ($producto->marca?->nombre ?? 'N/A') : ($producto->categoria?->nombre ?? 'N/A') }}</td>
                                <td class="precio">$ {{ number_format($producto->precio, 2) }}</td>
                                <td class="stock">{{ $producto->stock }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        @else
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 7%;">Foto</th>
                        <th style="width: 28%;">Producto</th>
                        <th style="width: 15%;">Categoría</th>
                        <th style="width: 15%;">Marca</th>
                        <th style="width: 12%;" class="precio">Precio</th>
                        <th style="width: 10%;" class="stock">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $index => $producto)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @php
                                    $foto = $producto->fotos->first();
                                @endphp
                                @if($foto)
                                    <img src="{{ public_path('storage/' . $foto->ruta) }}" class="foto" alt="Foto">
                                @else
                                    <span style="color: #94a3b8;">N/A</span>
                                @endif
                            </td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->categoria?->nombre ?? 'N/A' }}</td>
                            <td>{{ $producto->marca?->nombre ?? 'N/A' }}</td>
                            <td class="precio">$ {{ number_format($producto->precio, 2) }}</td>
                            <td class="stock">{{ $producto->stock }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="footer">
            Documento generado automáticamente - Sistema MVC Productos
        </div>
    </div>
</body>
</html>
