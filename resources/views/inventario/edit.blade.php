@extends('layouts.ferreteria')

@section('title', 'Editar Producto - Ferretería Guisella')

@section('content')
<div class="animate-fade-up" style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 30px; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <a href="{{ route('inventario') }}" class="btn-circle" style="text-decoration: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </a>
            <div>
                <h1 style="margin: 0;">Editar Producto</h1>
                <p class="subtitle" style="margin: 0;">Modificando: {{ $producto->nombre }}</p>
            </div>
        </div>
        
        <form action="{{ route('productos.destroy', $producto->idproducto) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este producto permanentemente?')">
            @csrf
            @method('DELETE')
            <button type="submit" style="background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; padding: 10px 20px; border-radius: 12px; font-weight: 600; cursor: pointer;">
                Eliminar Producto
            </button>
        </form>
    </div>

    <form action="{{ route('productos.update', $producto->idproducto) }}" method="POST" class="category-section" style="padding: 40px;">
        @csrf
        @method('PUT')
        
        <div class="form-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 25px;">
            
            <div class="field" style="grid-column: span 2;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Nombre del Producto</label>
                <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}" 
                       style="width: 100%; padding: 12px 18px; border-radius: 14px; border: 1px solid #e2e8f0;" required>
            </div>

            <div class="field" style="grid-column: span 2;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Descripción</label>
                <textarea name="descripcion" rows="3" 
                          style="width: 100%; padding: 12px 18px; border-radius: 14px; border: 1px solid #e2e8f0; resize: none;">{{ old('descripcion', $producto->descripcion) }}</textarea>
            </div>

            <div class="field">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Precio (Bs)</label>
                <input type="number" step="0.01" name="precio" value="{{ old('precio', $producto->precio) }}" 
                       style="width: 100%; padding: 12px 18px; border-radius: 14px; border: 1px solid #e2e8f0;" required>
            </div>

            <div class="field">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Stock actual</label>
                <input type="number" name="cantidad" value="{{ old('cantidad', $producto->cantidad) }}" 
                       style="width: 100%; padding: 12px 18px; border-radius: 14px; border: 1px solid #e2e8f0;" required>
            </div>

            <div class="field">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Marca</label>
                <select name="id_marca" style="width: 100%; padding: 12px 18px; border-radius: 14px; border: 1px solid #e2e8f0; background: white;" required>
                    @foreach($marcas as $marca)
                        <option value="{{ $marca->id }}" {{ $producto->id_marca == $marca->id ? 'selected' : '' }}>{{ $marca->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Categoría</label>
                <select name="id_categoria" style="width: 100%; padding: 12px 18px; border-radius: 14px; border: 1px solid #e2e8f0; background: white;" required>
                    @foreach($categorias_formulario as $cat)
                        <option value="{{ $cat->idcategoria }}" {{ $producto->id_categoria == $cat->idcategoria ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <div style="margin-top: 40px; display: flex; justify-content: flex-end; gap: 15px;">
            <a href="{{ route('inventario') }}" style="padding: 12px 30px; border-radius: 14px; color: #64748b; text-decoration: none; font-weight: 600;">Cancelar</a>
            <button type="submit" style="padding: 12px 40px; border-radius: 14px; background: var(--primary-grad); color: white; border: none; font-weight: 700; cursor: pointer; box-shadow: 0 10px 20px -5px rgba(15, 118, 110, 0.4);">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection
