@props(['entry' => null])

<div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <!-- Header -->
    <div class="px-6 md:px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-transparent">
        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
            <i class="fas {{ $entry ? 'fa-edit' : 'fa-plus-circle' }} text-emerald-600 text-2xl"></i>
            {{ $entry ? 'Editar Peso' : 'Registrar Novo Peso' }}
        </h3>
    </div>

    <!-- Form -->
    <form action="{{ $entry ? route('pesos.atualizar', $entry) : route('pesos.store') }}"
          method="POST" class="p-6 md:p-8 space-y-6">
        @csrf
        @if($entry)
            @method('PUT')
        @endif

        <!-- Weight Input -->
        <div class="group">
            <div class="flex items-center gap-2 mb-3">
                <i class="fas fa-scale-balanced text-emerald-600"></i>
                <x-input-label for="weight_kg" value="Peso (kg)" class="font-bold text-gray-900" />
            </div>
            <div class="relative">
                <x-text-input
                    id="weight_kg"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                    type="number"
                    name="weight_kg"
                    :value="old('weight_kg', $entry?->weight_kg)"
                    required
                    autofocus
                    step="0.1"
                    min="20"
                    max="300"
                    placeholder="75,5" />
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold pointer-events-none">kg</span>
            </div>
            <x-input-error :messages="$errors->get('weight_kg')" class="mt-2" />
        </div>

        <!-- Date Input -->
        <div class="group">
            <div class="flex items-center gap-2 mb-3">
                <i class="fas fa-calendar text-purple-600"></i>
                <x-input-label for="measured_date" value="Data da Medicao" class="font-bold text-gray-900" />
            </div>
            <x-text-input
                id="measured_date"
                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                type="date"
                name="measured_date"
                :value="old('measured_date', $entry?->measured_date?->format('Y-m-d') ?? today()->format('Y-m-d'))"
                required
                max="{{ today()->format('Y-m-d') }}" />
            <x-input-error :messages="$errors->get('measured_date')" class="mt-2" />
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-200 pt-6"></div>

        <!-- Buttons -->
        <div class="flex gap-3">
            <x-primary-button class="flex-1 justify-center gap-2 px-6 py-3 text-base font-bold bg-gradient-to-r from-emerald-600 to-green-600 hover:shadow-lg hover:shadow-emerald-200 transition-all duration-300">
                <i class="fas {{ $entry ? 'fa-check-circle' : 'fa-plus-circle' }}"></i>
                {{ $entry ? 'Atualizar' : 'Registrar' }}
            </x-primary-button>

            <a href="{{ route('pesos.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 text-gray-900 font-bold rounded-xl hover:bg-gray-300 transition-all duration-300 text-base">
                <i class="fas fa-times"></i>
                Cancelar
            </a>
        </div>
    </form>
</div>
