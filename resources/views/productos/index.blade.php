<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Inventario
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

            <div class="bg-white shadow rounded-xl p-6 border border-slate-200">
            <!-- TÍTULO -->
            <div>
                <h2 class="text-xl font-bold mb-2">Gestionar Productos</h2>
            </div>

            @auth

            <!-- BOTONES -->
            <div class="flex gap-3 flex-wrap">

                <button onclick="toggleForm('agregar')"
                    class="px-4 py-2 rounded-lg border border-slate-300 hover:bg-slate-100">
                    Agregar producto
                </button>

                <button onclick="toggleForm('modificar')"
                    class="px-4 py-2 rounded-lg border border-slate-300 hover:bg-slate-100">
                    Modificar producto
                </button>

                <button onclick="toggleForm('eliminar')"
                    class="px-4 py-2 rounded-lg border border-red-400 text-red-600 hover:bg-red-50">
                    Eliminar producto
                </button>

            </div>

            <!-- ALERTAS -->
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->has('form'))
                <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-lg">
                    {{ $errors->first('form') }}
                </div>
            @endif

            <!-- AGREGAR -->
            <div id="container-agregar" class="hidden bg-white shadow rounded-xl p-6 border border-slate-200 mt-4">

                <div class="flex items-center justify-between mb-4">
    
                    <h3 class="text-lg font-semibold">
                        Datos
                    </h3>

                    <button type="button"
                        onclick="closeForms()"
                        class="text-slate-500 hover:text-red-600 text-xl font-bold leading-none">
                        ✕
                    </button>

                </div>

                <form action="{{ route('productos.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <input class="input" name="idproducto" placeholder="ID producto">
                        <input class="input" name="nombre" placeholder="Nombre">
                        <input class="input" name="descripcion" placeholder="Descripción">
                        <input class="input" name="precio" placeholder="Precio">
                        <input class="input" name="cantidad" placeholder="Cantidad">
                        <input class="input" name="id_marca" placeholder="ID marca">

                        <select class="input" name="id_categoria">
                            <option value="">Categoría</option>
                            @foreach($categorias_formulario as $cat)
                                <option value="{{ $cat->idcategoria }}">
                                    {{ $cat->nombre }}
                                </option>
                            @endforeach
                        </select>

                        <input class="input" type="date" name="fechacaducidad">

                        <div class="col-span-1 md:col-span-2 flex justify-center">
                            <button class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 w-auto">
                                Guardar
                            </button>
                        </div>

                    </div>
                </form>
            </div>

            <!-- MODIFICAR -->
            <div id="container-modificar" class="hidden bg-white shadow rounded-xl p-6 border border-slate-200 mt-4">

                <div class="flex items-center justify-between mb-4">
    
                    <h3 class="text-lg font-semibold">
                        Datos
                    </h3>

                    <button type="button"
                        onclick="closeForms()"
                        class="text-slate-500 hover:text-red-600 text-xl font-bold leading-none">
                        ✕
                    </button>

                </div>

                <form id="form-modificar" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <input class="input" id="idproducto-modificar" name="idproducto" placeholder="ID producto">

                        <input class="input" id="modnombre" name="nombre" placeholder="Nombre">
                        <input class="input" id="moddescripcion" name="descripcion" placeholder="Descripción">
                        <input class="input" id="modprecio" name="precio" placeholder="Precio">
                        <input class="input" id="modcantidad" name="cantidad" placeholder="Cantidad">

                        <div class="col-span-1 md:col-span-2 flex justify-center">
                            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition w-auto">
                                Actualizar
                            </button>
                        </div>

                    </div>
                </form>
            </div>

            <!-- ELIMINAR -->
            <div id="container-eliminar" class="hidden bg-white shadow rounded-xl p-6 border border-red-200 mt-4">

                <div class="flex items-center justify-between mb-4">
    
                    <h3 class="text-lg font-semibold">
                        Datos
                    </h3>

                    <button type="button"
                        onclick="closeForms()"
                        class="text-slate-500 hover:text-red-600 text-xl font-bold leading-none">
                        ✕
                    </button>

                </div>

                <form id="form-eliminar" method="POST">
                    @csrf
                    @method('DELETE')
                    <input class="input border-red-300" id="idproducto-eliminar" name="idproducto" placeholder="ID producto">

                    <div class="mt-4 flex justify-center">
                        <button class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition w-auto"
                            onclick="return confirm('¿Estás seguro de eliminar este producto? Esta acción no se puede deshacer.');">
                            Eliminar
                        </button>
                    </div>
                </form>
            </div>
            </div>

            @endauth

            <!-- CATALOGO -->
            <div class="bg-white shadow rounded-xl p-6 border border-slate-200">

                <h2 class="text-xl font-bold mb-2">Categorias</h2>
                <p class="text-sm text-slate-500 mb-4">Despliegue para ver las subcategorías</p>

                @foreach($categorias as $categoria)
                    @include('productos.nodo', [
                        'categoria' => $categoria,
                        'level' => 0
                    ])
                @endforeach

            </div>

        </div>
    </div>

    <!-- ESTILOS MINIMOS (solo input reusable) -->
    <style>
        .input {
            width: 100%;
            border: 1px solid #cbd5e1;
            padding: 10px;
            border-radius: 8px;
            outline: none;
        }
        .input:focus {
            border-color: #0f766e;
            box-shadow: 0 0 0 3px rgba(15,118,110,0.2);
        }
    </style>

    <!-- JS -->
    <script>
        function toggleForm(type) {
            ['agregar','modificar','eliminar'].forEach(t => {
                document.getElementById('container-' + t).classList.add('hidden');
            });

            document.getElementById('container-' + type).classList.remove('hidden');
        }
        function closeForms() {
            ['agregar','modificar','eliminar'].forEach(t => {
                document.getElementById('container-' + t).classList.add('hidden');
            });
}
    </script>

</x-app-layout>