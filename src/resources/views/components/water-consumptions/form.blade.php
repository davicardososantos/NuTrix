@props(['consumption' => null])

<div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <!-- Header -->
    <div class="px-6 md:px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-transparent">
        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
            <i class="fas {{ $consumption ? 'fa-edit' : 'fa-plus-circle' }} text-blue-600 text-2xl"></i>
            {{ $consumption ? 'Editar Consumo' : 'Registrar Novo Consumo' }}
        </h3>
    </div>

    <!-- Form -->
    <form action="{{ $consumption ? route('consumos-agua.atualizar', $consumption) : route('consumos-agua.store') }}"
          method="POST" class="p-6 md:p-8 space-y-6">
        @csrf
        @if($consumption)
            @method('PUT')
        @endif

        <!-- Amount Input -->
        <div class="group">
            <div class="flex items-center gap-2 mb-3">
                <i class="fas fa-droplet text-cyan-600"></i>
                <x-input-label for="amount_ml" value="Quantidade (ml)" class="font-bold text-gray-900" />
            </div>
            <div class="relative">
                <x-text-input
                    id="amount_ml"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    type="number"
                    name="amount_ml"
                    :value="old('amount_ml', $consumption?->amount_ml)"
                    required
                    autofocus
                    min="1"
                    max="10000"
                    placeholder="500" />
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold pointer-events-none">ml</span>
            </div>
            <x-input-error :messages="$errors->get('amount_ml')" class="mt-2" />
        </div>

        <!-- Date Input -->
        <div class="group">
            <div class="flex items-center gap-2 mb-3">
                <i class="fas fa-calendar text-purple-600"></i>
                <x-input-label for="consumption_date" value="Data do Consumo" class="font-bold text-gray-900" />
            </div>
            <x-text-input
                id="consumption_date"
                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                type="date"
                name="consumption_date"
                :value="old('consumption_date',$consumption?->consumption_date?->format('Y-m-d') ?? today()->format('Y-m-d'))"
                required
                max="{{ today()->format('Y-m-d') }}" />
            <x-input-error :messages="$errors->get('consumption_date')" class="mt-2" />
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-200 pt-6"></div>

        <!-- Buttons -->
        <div class="flex gap-3">
            <x-primary-button class="flex-1 justify-center gap-2 px-6 py-3 text-base font-bold bg-gradient-to-r from-blue-600 to-cyan-600 hover:shadow-lg hover:shadow-blue-200 transition-all duration-300">
                <i class="fas {{ $consumption ? 'fa-check-circle' : 'fa-plus-circle' }}"></i>
                {{ $consumption ? 'Atualizar' : 'Registrar' }}
            </x-primary-button>

            <a href="{{ route('consumos-agua.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 text-gray-900 font-bold rounded-xl hover:bg-gray-300 transition-all duration-300 text-base">
                <i class="fas fa-times"></i>
                Cancelar
            </a>
        </div>
    </form>
</div>
