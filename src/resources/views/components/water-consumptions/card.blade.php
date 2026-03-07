@props(['consumption'])

<div class="bg-white rounded-lg shadow p-4 mb-4 border-l-4 border-blue-500">
    <div class="flex justify-between items-start">
        <div class="flex-1">
            <p class="text-sm text-gray-500">{{ $consumption->consumption_date->format('d/m/Y') }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $consumption->amount_ml }} ml</p>
            <p class="text-sm text-gray-600">{{ round($consumption->amount_ml / 1000, 2) }} L</p>
        </div>
        <div class="text-xs text-gray-400">
            {{ $consumption->created_at->format('H:i') }}
        </div>
    </div>

    <div class="mt-4 flex gap-2">
        <a href="{{ route('consumos-agua.editar', $consumption) }}"
           class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded text-sm hover:bg-blue-200 transition">
            {{ __('Editar') }}
        </a>

        <x-delete-modal :action="route('consumos-agua.excluir', $consumption)" title="Deletar Consumo" message="Tem certeza que deseja deletar este consumo? Esta ação não pode ser desfeita." button-text="Deletar" class="px-3 py-1 bg-red-100 text-red-700 rounded text-sm hover:bg-red-200">
            {{ __('Deletar') }}
        </x-delete-modal>
    </div>
</div>
