<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-blue-50 via-white to-white pb-20 md:pb-6">
        <!-- Header -->
        <div class="bg-white border-b border-gray-100 z-30">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-purple-100 to-pink-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-layer-group text-xl md:text-2xl text-purple-600"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-black text-gray-900">Escolha a Plataforma</h1>
                        <p class="text-xs md:text-sm text-gray-500 mt-0.5">Selecione qual área deseja acessar</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
            <!-- Alert Message -->
            @if(session('message'))
                <div class="mb-6 p-4 bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-300">
                    <i class="fas fa-exclamation-circle text-lg text-amber-600 flex-shrink-0"></i>
                    <p class="font-semibold text-sm text-amber-900">{{ session('message') }}</p>
                </div>
            @endif

            <!-- Platform Selection Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($hasNutritionist)
                    <div class="group bg-white border-2 border-green-100 rounded-3xl p-8 hover:border-green-400 hover:shadow-xl hover:shadow-green-100 transition-all duration-300">
                        <div class="flex flex-col h-full">
                            <!-- Icon & Title -->
                            <div class="mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                                    <i class="fas fa-stethoscope text-3xl text-green-600"></i>
                                </div>
                                <h3 class="text-2xl font-black text-gray-900">Nutricionista</h3>
                                <p class="text-sm text-gray-600 mt-2">Gerencie pacientes e acompanhamentos</p>
                            </div>

                            <!-- Features List -->
                            <div class="space-y-3 mb-8 flex-grow">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-check-circle text-green-600 text-sm"></i>
                                    <span class="text-sm text-gray-700">Visualizar pacientes</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-check-circle text-green-600 text-sm"></i>
                                    <span class="text-sm text-gray-700">Acompanhar progresso</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-check-circle text-green-600 text-sm"></i>
                                    <span class="text-sm text-gray-700">Gerenciar metas</span>
                                </div>
                            </div>

                            <!-- Button -->
                            <form action="{{ route('portal.definir') }}" method="POST">
                                @csrf
                                <input type="hidden" name="role" value="nutritionist">
                                <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-2xl font-bold hover:shadow-lg hover:shadow-green-200 transition-all duration-300 flex items-center justify-center gap-2 group-hover:scale-105">
                                    <i class="fas fa-arrow-right"></i>
                                    Entrar como Nutricionista
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                @if($hasPatient)
                    <div class="group bg-white border-2 border-blue-100 rounded-3xl p-8 hover:border-blue-400 hover:shadow-xl hover:shadow-blue-100 transition-all duration-300">
                        <div class="flex flex-col h-full">
                            <!-- Icon & Title -->
                            <div class="mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-cyan-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                                    <i class="fas fa-droplet text-3xl text-cyan-600"></i>
                                </div>
                                <h3 class="text-2xl font-black text-gray-900">Paciente</h3>
                                <p class="text-sm text-gray-600 mt-2">Registre consumo e acompanhe metas</p>
                            </div>

                            <!-- Features List -->
                            <div class="space-y-3 mb-8 flex-grow">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-check-circle text-blue-600 text-sm"></i>
                                    <span class="text-sm text-gray-700">Registrar consumo</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-check-circle text-blue-600 text-sm"></i>
                                    <span class="text-sm text-gray-700">Visualizar progresso</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-check-circle text-blue-600 text-sm"></i>
                                    <span class="text-sm text-gray-700">Atingir suas metas</span>
                                </div>
                            </div>

                            <!-- Button -->
                            <form action="{{ route('portal.definir') }}" method="POST">
                                @csrf
                                <input type="hidden" name="role" value="patient">
                                <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-2xl font-bold hover:shadow-lg hover:shadow-blue-200 transition-all duration-300 flex items-center justify-center gap-2 group-hover:scale-105">
                                    <i class="fas fa-arrow-right"></i>
                                    Entrar como Paciente
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
