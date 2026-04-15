<details class="tree-details">
    <summary class="tree-summary">{{ $categoria->nombre }}</summary>

    <div class="tree-inner">
        @foreach($categoria->subcategorias as $subcategoria)
            @include('productos.nodo', ['categoria' => $subcategoria])
        @endforeach

        @if($categoria->productos->count() > 0)
            <table class="tree-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categoria->productos as $prod)
                        <tr>
                            <td>{{ $prod->idproducto }}</td>
                            <td>{{ $prod->nombre }}</td>
                            <td>{{ $prod->precio }} Bs.</td>
                            <td>{{ $prod->cantidad }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</details>
