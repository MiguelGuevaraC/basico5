<h2>Productos disponibles</h2>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Categoría</th>
            <th>Marca</th>
            <th>Precio</th>
            <th>Stock</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($productos as $producto)
            <tr>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->categoria?->nombre }}</td>
                <td>{{ $producto->marca?->nombre }}</td>
                <td>{{ $producto->precio }}</td>
                <td>{{ $producto->stock }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

