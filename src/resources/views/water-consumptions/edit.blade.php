<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-black text-3xl text-gray-900">
                💧 Editar Registro
            </h2>
            <p class="text-sm text-gray-500 mt-2">Atualize seus dados de consumo de água</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Formulário -->
                <div class="lg:col-span-1">
                    <x-water-consumptions.form :consumption="$consumption" />
                </div>

                <!-- Informações -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Criado em</p>
                            <p class="text-gray-900 font-medium">{{ $consumption->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Última atualização</p>
                            <p class="text-gray-900 font-medium">{{ $consumption->updated_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div class="pt-4 border-t">
                            <form action="{{ route('water-consumptions.destroy', $consumption) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Tem certeza que deseja deletar este consumo?')"
                                        class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                                    {{ __('Deletar Consumo') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
