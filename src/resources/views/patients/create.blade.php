<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-green-50 via-white to-white pb-20 md:pb-6">
        <!-- Header -->
        <div class="bg-white border-b border-gray-100 z-30">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-6">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-plus text-xl md:text-2xl text-green-600"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-black text-gray-900">Novo Paciente</h1>
                            <p class="text-xs md:text-sm text-gray-500 mt-0.5">Cadastro inicial - dados complementares podem ser adicionados depois</p>
                        </div>
                    </div>
                    <a href="{{ route('patients.index') }}" class="px-4 md:px-6 py-3 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                        <i class="fas fa-arrow-left"></i>
                        <span class="hidden md:inline">Voltar</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 md:px-8 py-6 border-b border-gray-200">
                    <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                        <i class="fas fa-clipboard-list text-green-600"></i>
                        Dados do Paciente
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Preencha as informações básicas</p>
                </div>

                <!-- Form Body -->
                <form action="{{ route('patients.store') }}" method="POST" class="p-6 md:p-8 space-y-6">
                    @csrf

                    <!-- Full Name -->
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-user text-green-600"></i>
                            <x-input-label for="full_name" value="Nome Completo" class="font-bold text-gray-900" />
                        </div>
                        <x-text-input
                            id="full_name"
                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
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
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-envelope text-blue-600"></i>
                            <x-input-label for="email" value="Email" class="font-bold text-gray-900" />
                        </div>
                        <x-text-input
                            id="email"
                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            placeholder="paciente@email.com"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Weight & Height Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Weight -->
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-weight text-blue-600"></i>
                                <x-input-label for="weight" value="Peso (kg)" class="font-bold text-gray-900" />
                                <span class="text-xs text-gray-500 font-normal">(Opcional)</span>
                            </div>
                            <x-decimal-input
                                id="weight"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
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
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-ruler text-green-600"></i>
                                <x-input-label for="height" value="Altura (cm)" class="font-bold text-gray-900" />
                                <span class="text-xs text-gray-500 font-normal">(Opcional)</span>
                            </div>
                            <x-decimal-input
                                id="height"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                type="number"
                                name="height"
                                :value="old('height')"
                                step="0.1"
                                placeholder="175,0"
                            />
                            <x-input-error :messages="$errors->get('height')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-blue-200 rounded-xl p-5">
                        <p class="text-sm text-blue-900 flex items-start gap-3">
                            <i class="fas fa-lightbulb text-blue-600 text-lg flex-shrink-0 mt-0.5"></i>
                            <span>
                                <strong class="font-bold">Dica:</strong> Os demais dados (histórico clínico, metas, etc) podem ser preenchidos depois durante o atendimento.
                            </span>
                        </p>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 pt-6"></div>

                    <!-- Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('patients.index') }}" class="flex-1 px-6 py-3 bg-gray-200 text-gray-800 font-bold rounded-xl hover:bg-gray-300 transition-all duration-300 text-center flex items-center justify-center gap-2">
                            <i class="fas fa-times"></i>
                            Cancelar
                        </a>
                        <button
                            type="submit"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-green-200 transition-all duration-300 flex items-center justify-center gap-2"
                        >
                            <i class="fas fa-check-circle"></i>
                            Cadastrar Paciente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
