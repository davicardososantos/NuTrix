<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Bem-vindo! 👋</h1>
        <p class="text-sm text-gray-600 mt-1">Crie sua conta para começar a acompanhar sua hidratação</p>
    </div>

    <!-- Patient Info Card -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <p class="text-sm text-blue-900">
            <strong>📋 Seus dados:</strong><br/>
            Nome no sistema: <strong>{{ $patient->full_name }}</strong><br/>
            Email cadastrado: <strong>{{ $patient->email }}</strong>
        </p>
    </div>

    <form method="POST" action="{{ route('paciente.cadastro.salvar', ['code' => $code]) }}">
        @csrf
        <input type="hidden" name="code" value="{{ $code }}">

        <!-- Name (Read-only) -->
        <div>
            <x-input-label for="name" value="Nome (não pode ser alterado)" />
            <x-text-input
                id="name"
                class="block mt-1 w-full bg-gray-100 cursor-not-allowed"
                type="text"
                name="name"
                value="{{ $patient->full_name }}"
                readonly
                autofocus
                autocomplete="name"
            />
        </div>

        <!-- Email Address (Read-only) -->
        <div class="mt-4">
            <x-input-label for="email" value="Email (não pode ser alterado)" />
            <x-text-input
                id="email"
                class="block mt-1 w-full bg-gray-100 cursor-not-allowed"
                type="email"
                name="email"
                value="{{ $patient->email }}"
                readonly
                autocomplete="username"
            />
        </div>

        <!-- Weight -->
        <div class="mt-4">
            <x-input-label for="weight" value="Peso (kg)" />
            <x-decimal-input
                id="weight"
                class="block mt-1 w-full"
                type="number"
                name="weight"
                :value="old('weight')"
                step="0.1"
                placeholder="70,5"
            />
            <x-input-error :messages="$errors->get('weight')" class="mt-2" />
        </div>

        <!-- Height -->
        <div class="mt-4">
            <x-input-label for="height" value="Altura (cm)" />
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

        <!-- Password Note -->
        <div class="mt-4 bg-blue-50 border border-blue-200 rounded p-3 text-sm text-blue-900">
            <p><strong>ℹ️ Nota importante:</strong> Se o email que você está usando já tem cadastro no sistema, não será necessário definir uma nova senha. Você poderá usar suas credenciais existentes.</p>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Senha (opcional se email já existe)" />
            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                autocomplete="new-password"
                placeholder="Mínimo 8 caracteres (deixe em branco se não é necessário)"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmar Senha" />
            <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full"
                type="password"
                name="password_confirmation"
                autocomplete="new-password"
                placeholder="Repita a senha acima (opcional)"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Info -->
        <div class="mt-4 text-xs text-gray-600">
            <p>Ao se cadastrar, você poderá registrar seu consumo de água e acompanhar seu progresso com sua nutricionista.</p>
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-amber-700 hover:text-amber-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-600" href="{{ route('login') }}">
                Já possui uma conta?
            </a>

            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-amber-600 to-green-600 text-white font-semibold rounded-lg hover:scale-105 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Criar Conta
            </button>
        </div>
    </form>
</x-guest-layout>
