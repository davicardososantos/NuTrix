<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-black text-3xl text-gray-900">➕ Novo Paciente</h2>
                <p class="text-gray-600 mt-1">Cadastro inicial - dados complementares podem ser adicionados depois</p>
            </div>
            <a href="{{ route('patients.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition font-semibold text-sm">
                ← Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <form action="{{ route('patients.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Full Name -->
                    <div>
                        <x-input-label for="full_name" value="Nome Completo" />
                        <x-text-input 
                            id="full_name" 
                            class="block mt-1 w-full" 
                            type="text" 
                            name="full_name" 
                            :value="old('full_name')" 
                            required 
                            autofocus 
                            placeholder="João Silva da Costa"
                        />
                        <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input 
                            id="email" 
                            class="block mt-1 w-full" 
                            type="email" 
                            name="email" 
                            :value="old('email')" 
                            required 
                            placeholder="paciente@email.com"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Weight -->
                    <div>
                        <x-input-label for="weight" value="Peso (kg) - Opcional" />
                        <x-decimal-input 
                            id="weight" 
                            class="block mt-1 w-full" 
                            type="number" 
                            name="weight" 
                            :value="old('weight')" 
                            step="0.1"
                            placeholder="75,5"
                        />
                        <x-input-error :messages="$errors->get('weight')" class="mt-2" />
                    </div>

                    <!-- Height -->
                    <div>
                        <x-input-label for="height" value="Altura (cm) - Opcional" />
                        <x-decimal-input 
                            id="height" 
                            class="block mt-1 w-full" 
                            type="number" 
                            name="height" 
                            :value="old('height')" 
                            step="0.1"
                            placeholder="175,0"
                        />
                        <x-input-error :messages="$errors->get('height')" class="mt-2" />
                    </div>

                    <!-- Info Box -->
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-900">
                            <strong>💡 Dica:</strong> Os demais dados (histórico clínico, metas, etc) podem ser preenchidos depois durante o atendimento.
                        </p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-4">
                        <a href="{{ route('patients.index') }}" class="flex-1 px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition text-center">
                            Cancelar
                        </a>
                        <button 
                            type="submit"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-amber-700 to-green-700 text-white font-semibold rounded-lg hover:shadow-lg hover:scale-105 transition-all"
                        >
                            ✓ Cadastrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
