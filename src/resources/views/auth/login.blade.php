<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 text-center mb-2">Bem-vindo de volta</h1>
        <p class="text-center text-gray-600 text-sm">Acesse sua conta para continuar</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-2 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-600 focus:border-transparent transition" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="seu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Senha')" />
            <x-text-input id="password" class="block mt-2 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-600 focus:border-transparent transition" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-2">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-amber-600 shadow-sm focus:ring-amber-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Lembre-se de mim</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm text-amber-600 hover:text-amber-700 font-medium transition" href="{{ route('password.request') }}">
                    Esqueceu a senha?
                </a>
            @endif
        </div>

        <button type="submit" class="w-full py-3 mt-6 bg-gradient-to-r from-amber-700 to-green-700 text-white font-bold rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-600">
            Entrar
        </button>

        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-600 mt-4">
                Não tem conta? 
                <a href="{{ route('register') }}" class="text-amber-600 hover:text-amber-700 font-semibold transition">
                    Criar conta
                </a>
            </p>
        @endif
    </form>
</x-guest-layout>
