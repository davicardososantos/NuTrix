<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl md:text-3xl text-gray-900 flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <x-icon icon="check" size="lg" class="text-green-600" />
                    </div>
                    Paciente Cadastrado
                </h2>
                <p class="text-gray-600 mt-1 text-sm md:text-base">Compartilhe o código abaixo para que seu paciente se registre</p>
            </div>
            <a href="{{ route('patients.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition font-semibold text-sm">
                ← Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Card -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl border border-green-200 p-8 text-center mb-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <x-icon icon="check" size="lg" class="text-green-600 w-8 h-8" />
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $patient->full_name }}</h3>
                <p class="text-gray-600 mb-6">Paciente cadastrado com sucesso! Agora compartilhe o código abaixo.</p>

                <!-- Code Section -->
                <div class="bg-gradient-to-r from-amber-100 to-green-100 rounded-xl p-8 border-2 border-amber-300 mb-6">
                    <p class="text-sm text-gray-600 mb-2 font-semibold">CÓDIGO DE CADASTRO</p>
                    <div class="text-5xl font-black text-amber-700 mb-4 tracking-widest">
                        {{ $patient->code }}
                    </div>
                    <p class="text-sm text-gray-600">Este paciente pode usar este código para acessar o sistema</p>
                </div>

                <!-- Registration Link -->
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 mb-6">
                    <p class="text-sm text-gray-600 font-semibold mb-3">LINK DE CADASTRO</p>
                    <div class="flex gap-2 items-center">
                        <input 
                            type="text" 
                            value="{{ $registrationLink }}" 
                            id="registrationLink"
                            readonly
                            class="flex-1 px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm break-all"
                        />
                        <button 
                            type="button"
                            onclick="copyToClipboard()"
                            class="px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition whitespace-nowrap"
                        >
                            📋 Copiar
                        </button>
                    </div>
                </div>

                <!-- WhatsApp Share -->
                <div class="flex gap-3 justify-center mb-6">
                    <a href="https://wa.me/?text=Olá! Segue seu código de cadastro no NuTrix Meta: {{ $patient->code }} - Link: {{ $registrationLink }}" 
                       target="_blank"
                       class="px-6 py-3 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition flex items-center gap-2">
                        💬 Compartilhar via WhatsApp
                    </a>
                    <a href="mailto:{{ $patient->email }}?subject=Seu código de cadastro - NuTrix Meta&body=Olá {{ $patient->full_name }},%0A%0ASegue seu código de cadastro: {{ $patient->code }}%0A%0ALink: {{ $registrationLink }}"
                       class="px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition flex items-center gap-2">
                        ✉️ Enviar por Email
                    </a>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-left">
                    <p class="text-sm text-blue-900">
                        <strong>💡 O que fazer agora:</strong><br/>
                        <br/>
                        1. Compartilhe o código ou link com seu paciente<br/>
                        2. Quando o paciente se registrar, ele poderá fazer login normalmente<br/>
                        3. Você poderá editar e adicionar mais informações do paciente depois<br/>
                        4. O paciente utilizará a plataforma para registrar seu consumo de água
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('patients.create') }}" class="flex-1 px-6 py-3 bg-gradient-to-r from-amber-700 to-green-700 text-white font-semibold rounded-lg hover:shadow-lg hover:scale-105 transition-all text-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Cadastrar Outro Paciente
                </a>
                <a href="{{ route('patients.index') }}" class="flex-1 px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition text-center">
                    Ir para Meus Pacientes
                </a>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard() {
            const link = document.getElementById('registrationLink');
            link.select();
            document.execCommand('copy');
            
            // Visual feedback
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Copiado!';
            button.classList.add('bg-green-600');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-600');
            }, 2000);
        }
    </script>
</x-app-layout>
