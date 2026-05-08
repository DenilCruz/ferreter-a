@extends('layouts.ferreteria')

@section('title', 'Nuevo Producto - Ferretería Guisella')

@section('content')
<div class="animate-fade-up" style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 30px; display: flex; align-items: center; gap: 15px;">
        <a href="{{ route('inventario') }}" class="btn-circle" style="text-decoration: none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <div>
            <h1 style="margin: 0;">Registrar Producto</h1>
            <p class="subtitle" style="margin: 0;">Añade un nuevo artículo al catálogo general</p>
        </div>
    </div>

    <form action="{{ route('productos.store') }}" method="POST" class="category-section" style="padding: 40px;">
        @csrf
        <div class="form-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 25px;">
            
            <div class="field">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">ID del Producto</label>
                <input type="number" name="idproducto" value="{{ old('idproducto') }}" 
                       style="width: 100%; padding: 12px 18px; border-radius: 14px; border: 1px solid #e2e8f0; background: #f8fafc;" 
                       placeholder="Ej: 1001" required>
                @error('idproducto') <p style="color: #ef4444; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Nombre Comercial</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" 
                       style="width: 100%; padding: 12px 18px; border-radius: 14px; border: 1px solid #e2e8f0;" 
                       placeholder="Nombre del producto" required>
            </div>

            <div class="field" style="grid-column: span 2;">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Descripción Detallada</label>
                <textarea name="descripcion" rows="3" 
                          style="width: 100%; padding: 12px 18px; border-radius: 14px; border: 1px solid #e2e8f0; resize: none;" 
                          placeholder="Características técnicas, materiales, etc...">{{ old('descripcion') }}</textarea>
            </div>

            <div class="field">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Precio de Venta (Bs)</label>
                <input type="number" step="0.01" name="precio" value="{{ old('precio') }}" 
                       style="width: 100%; padding: 12px 18px; border-radius: 14px; border: 1px solid #e2e8f0;" 
                       placeholder="0.00" required>
            </div>

            <div class="field">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Stock Inicial</label>
                <input type="number" name="cantidad" value="{{ old('cantidad', 0) }}" 
                       style="width: 100%; padding: 12px 18px; border-radius: 14px; border: 1px solid #e2e8f0;" required>
            </div>

            <div class="field">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Marca</label>
                <select name="id_marca" style="width: 100%; padding: 12px 18px; border-radius: 14px; border: 1px solid #e2e8f0; background: white;" required>
                    <option value="">Seleccionar marca...</option>
                    @foreach($marcas as $marca)
                        <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label style="display: block; font-size: 0.8rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Categoría</label>
                <select name="id_categoria" style="width: 100%; padding: 12px 18px; border-radius: 14px; border: 1px solid #e2e8f0; background: white;" required>
                    <option value="">Seleccionar categoría...</option>
                    @foreach($categorias_formulario as $cat)
                        <option value="{{ $cat->idcategoria }}">{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <div style="margin-top: 40px; display: flex; justify-content: flex-end; gap: 15px;">
            <a href="{{ route('inventario') }}" style="padding: 12px 30px; border-radius: 14px; color: #64748b; text-decoration: none; font-weight: 600;">Cancelar</a>
            <button type="submit" style="padding: 12px 40px; border-radius: 14px; background: var(--primary-grad); color: white; border: none; font-weight: 700; cursor: pointer; box-shadow: 0 10px 20px -5px rgba(15, 118, 110, 0.4);">
                Guardar Producto
            </button>
        </div>
    </form>
</div>
@endsection
