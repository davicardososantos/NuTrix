@props(['action' => '', 'title' => 'Confirmar Exclusão', 'message' => 'Tem certeza que deseja deletar este item? Esta ação não pode ser desfeita.', 'buttonText' => 'Deletar'])

<div x-data="{ open: false }" class="inline-block">
    <!-- Trigger Button -->
    <button @click="open = true" {{ $attributes->merge(['class' => 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold text-sm']) }}>
        {{ $slot ?? $buttonText }}
    </button>

    <!-- Modal Background -->
    <div x-show="open"
         class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity"
         @click="open = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <!-- Modal -->
    <div x-show="open"
         class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-2xl shadow-2xl z-50 w-96 max-w-[90%]"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         @click.stop>

        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-red-50 to-red-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-200 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2m0-16h.01M6 3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6a3 3 0 013-3z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">{{ $title }}</h3>
            </div>
            <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6">
            <p class="text-gray-600 text-sm leading-relaxed">{{ $message }}</p>
        </div>

        <!-- Footer -->
        <div class="flex items-center gap-3 p-6 border-t border-gray-200 bg-gray-50">
            <button @click="open = false"
                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-semibold text-sm">
                Cancelar
            </button>
            <form action="{{ $action }}" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold text-sm">
                    {{ $buttonText }}
                </button>
            </form>
        </div>
    </div>
</div>
