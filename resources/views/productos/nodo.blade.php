@php
    $level = $level ?? 0;
@endphp

<details class="tree-details"
         style="margin-left: {{ $level * 20 }}px;">

    <summary class="tree-summary">
        {{ $categoria->nombre }}
    </summary>

    <div class="tree-inner">

        {{-- SUBCATEGORIAS --}}
        @foreach($categoria->subcategorias as $subcategoria)
            @include('productos.nodo', [
                'categoria' => $subcategoria,
                'level' => $level + 1
            ])
        @endforeach

        {{-- PRODUCTOS --}}
        @if($categoria->productos->count() > 0)

            <div class="overflow-x-auto mt-2">
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
            </div>

        @endif
    </div>
</details>