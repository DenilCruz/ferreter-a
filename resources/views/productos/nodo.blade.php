<details style="margin-left: 20px; margin-bottom: 5px;">
    <summary style="cursor: pointer; font-weight: bold; padding: 5px; background: #e9ecef; border-radius: 4px;">
        {{ $categoria->nombre }}
    </summary>

    <div style="padding-left: 15px; border-left: 2px dashed #ccc; margin-top: 5px;">
        
        @foreach($categoria->subcategorias as $subcategoria)
            @include('productos.nodo', ['categoria' => $subcategoria])
        @endforeach

        @if($categoria->productos->count() > 0)
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #ddd; padding: 8px; background-color: #333; color: white;">ID</th>
                        <th style="border: 1px solid #ddd; padding: 8px; background-color: #333; color: white;">Producto</th>
                        <th style="border: 1px solid #ddd; padding: 8px; background-color: #333; color: white;">Precio</th>
                        <th style="border: 1px solid #ddd; padding: 8px; background-color: #333; color: white;">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categoria->productos as $prod)
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $prod->idproducto }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $prod->nombre }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $prod->precio }} Bs.</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $prod->cantidad }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        
    </div>
</details>