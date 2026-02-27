<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-blue-50 via-white to-white pb-20 md:pb-6">
        <!-- Back Navigation -->
        <div class="sticky top-14 md:top-16 bg-white border-b border-gray-100 z-30">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <a href="{{ route('water-consumptions.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold transition-colors">
                    <i class="fas fa-chevron-left"></i>
                    <span>Voltar</span>
                </a>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-cyan-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-edit text-2xl text-cyan-600"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-gray-900">Editar Registro</h1>
                        <p class="text-sm text-gray-500 mt-1">Atualize seus dados de consumo de água</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form Section -->
                <div class="lg:col-span-2">
                    <x-water-consumptions.form :consumption="$consumption" />
                </div>

                <!-- Info Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm sticky top-20 md:top-24">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-600"></i> Informações
                        </h3>

                        <div class="space-y-5">
                            <!-- Created Date -->
                            <div class="pb-5 border-b border-gray-200">
                                <p class="text-xs font-bold text-gray-600 uppercase tracking-wider">Criado em</p>
                                <p class="text-gray-900 font-semibold mt-2 flex items-center gap-2">
                                    <i class="fas fa-calendar-plus text-blue-400"></i>
                                    {{ $consumption->created_at->format('d/m/Y \à\s H:i') }}
                                </p>
                            </div>

                            <!-- Updated Date -->
                            <div class="pb-5 border-b border-gray-200">
                                <p class="text-xs font-bold text-gray-600 uppercase tracking-wider">Última atualização</p>
                                <p class="text-gray-900 font-semibold mt-2 flex items-center gap-2">
                                    <i class="fas fa-clock text-green-400"></i>
                                    {{ $consumption->updated_at->format('d/m/Y \à\s H:i') }}
                                </p>
                            </div>

                            <!-- Consumption Amount -->
                            <div class="pb-5 border-b border-gray-200">
                                <p class="text-xs font-bold text-gray-600 uppercase tracking-wider">Quantidade</p>
                                <p class="text-2xl font-black text-cyan-600 mt-2 flex items-center gap-2">
                                    <i class="fas fa-droplet"></i>
                                    {{ $consumption->amount_ml }}ml
                                </p>
                            </div>

                            <!-- Consumption Date -->
                            <div>
                                <p class="text-xs font-bold text-gray-600 uppercase tracking-wider">Data do Consumo</p>
                                <p class="text-gray-900 font-semibold mt-2 flex items-center gap-2">
                                    <i class="fas fa-calendar text-purple-400"></i>
                                    {{ $consumption->consumption_date->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Delete Button -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <x-delete-modal 
                                :action="route('water-consumptions.destroy', $consumption)" 
                                title="Deletar Registro de Água" 
                                message="Tem certeza que deseja deletar este consumo de água? Esta ação não pode ser desfeita." 
                                button-text="Deletar"
                                class="w-full px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-red-200 transition-all duration-300 flex items-center justify-center gap-2"
                            >
                                <i class="fas fa-trash-alt"></i> Deletar Consumo
                            </x-delete-modal>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
