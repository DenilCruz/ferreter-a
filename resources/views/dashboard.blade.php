<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/95 backdrop-blur-sm overflow-hidden shadow-lg sm:rounded-xl border border-slate-200/80">
                <div class="p-6 sm:p-8 text-slate-700 space-y-4">
                    <p class="text-lg">{{ __('Sesión iniciada correctamente.') }}</p>
                    <p class="text-sm text-slate-500">{{ __('Usa el menú superior para ir al inventario o a tu perfil.') }}</p>
                    <div class="flex flex-wrap gap-3 pt-2">
                        <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 bg-teal-700 border border-transparent rounded-lg font-semibold text-sm text-white shadow-md shadow-teal-700/25 hover:bg-teal-600 transition">
                            {{ __('Ir al inventario') }}
                        </a>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-sm text-slate-700 hover:bg-slate-50 transition">
                            {{ __('Editar perfil') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
