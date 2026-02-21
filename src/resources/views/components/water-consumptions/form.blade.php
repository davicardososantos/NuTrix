@props(['consumption' => null])

<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        {{ $consumption ? 'Editar Consumo' : 'Registrar Novo Consumo' }}
    </h3>

    <form action="{{ $consumption ? route('water-consumptions.update', $consumption) : route('water-consumptions.store') }}" 
          method="POST" class="space-y-4">
        @csrf
        @if($consumption)
            @method('PUT')
        @endif

        <div>
            <x-input-label for="amount_ml" :value="__('Quantidade (ml')" />
            <x-text-input 
                id="amount_ml"
                class="block mt-1 w-full"
                type="number"
                name="amount_ml"
                :value="old('amount_ml', $consumption?->amount_ml)"
                required
                autofocus
                min="1"
                max="10000"
                placeholder="500" />
            <x-input-error :messages="$errors->get('amount_ml')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="consumption_date" :value="__('Data do Consumo')" />
            <x-text-input 
                id="consumption_date"
                class="block mt-1 w-full"
                type="date"
                name="consumption_date"
                :value="old('consumption_date', $consumption?->consumption_date?->format('Y-m-d'))"
                required
                max="{{ today()->format('Y-m-d') }}" />
            <x-input-error :messages="$errors->get('consumption_date')" class="mt-2" />
        </div>

        <div class="flex gap-3 pt-2">
            <x-primary-button>
                {{ $consumption ? __('Atualizar') : __('Registrar') }}
            </x-primary-button>
            
            <a href="{{ route('water-consumptions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Cancelar') }}
            </a>
        </div>
    </form>
</div>
