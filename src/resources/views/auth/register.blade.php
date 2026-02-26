<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Cadastro de Nutricionista</h1>
        <p class="text-sm text-gray-600 mt-1">Criar uma conta para acessar o NuTrix Meta</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Full Name -->
        <div>
            <x-input-label for="full_name" value="Nome Completo" />
            <x-text-input id="full_name" class="block mt-1 w-full" type="text" name="full_name" :value="old('full_name')" required autofocus autocomplete="name" placeholder="João Silva da Nutrição" />
            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="seu@email.com" />
            <div id="emailCheckMessage" class="text-sm mt-2 hidden"></div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- CPF -->
        <div class="mt-4">
            <x-input-label for="cpf" value="CPF (obrigatório)" />
            <x-text-input id="cpf" class="block mt-1 w-full" type="text" name="cpf" :value="old('cpf')" required placeholder="12345678901" maxlength="11" />
            <p class="text-xs text-gray-500 mt-1">Apenas números, sem pontuação</p>
            <x-input-error :messages="$errors->get('cpf')" class="mt-2" />
        </div>

        <!-- CRN -->
        <div class="mt-4">
            <x-input-label for="crn" value="CRN (opcional)" />
            <x-text-input id="crn" class="block mt-1 w-full" type="text" name="crn" :value="old('crn')" placeholder="123456/UF" autocomplete="off" />
            <p class="text-xs text-gray-500 mt-1">Conselho Regional de Nutricionistas</p>
            <x-input-error :messages="$errors->get('crn')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="phone" value="Telefone (opcional)" />
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" placeholder="(11) 99999-9999" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Senha" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Mínimo 8 caracteres" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmar Senha" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repita a senha acima" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Terms -->
        <div class="mt-4 text-xs text-gray-600">
            <p>Ao se cadastrar, você concorda com nossos Termos de Serviço e Política de Privacidade.</p>
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-amber-700 hover:text-amber-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-600" href="{{ route('login') }}">
                Já possui uma conta?
            </a>

            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-amber-700 to-green-700 text-white font-semibold rounded-lg hover:scale-105 transition-all">
                Criar Conta
            </button>
        </div>
    </form>

    <script>
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        const emailCheckMessage = document.getElementById('emailCheckMessage');
        let isEmailExists = false;

        // emailInput.addEventListener('blur', async () => {
        //     const email = emailInput.value.trim();
            
        //     if (!email || !email.includes('@')) {
        //         emailCheckMessage.classList.add('hidden');
        //         return;
        //     }

        //     try {
                // const response = await fetch('', {
        //             method: 'POST',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //                 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
        //             },
        //             body: JSON.stringify({ email }),
        //         });

        //         const data = await response.json();
        //         isEmailExists = data.exists;

        //         if (isEmailExists) {
        //             emailCheckMessage.classList.remove('hidden');
        //             emailCheckMessage.className = 'text-sm mt-2 bg-blue-50 border border-blue-200 text-blue-900 p-2 rounded';
        //             emailCheckMessage.innerHTML = '✓ Este email já existe no sistema. Não será necessário definir uma senha.';
        //             passwordInput.disabled = true;
        //             passwordConfirmInput.disabled = true;
        //             passwordInput.value = '';
        //             passwordConfirmInput.value = '';
        //             passwordInput.removeAttribute('required');
        //             passwordConfirmInput.removeAttribute('required');
        //         } else {
        //             emailCheckMessage.classList.add('hidden');
        //             passwordInput.disabled = false;
        //             passwordConfirmInput.disabled = false;
        //             passwordInput.setAttribute('required', 'required');
        //             passwordConfirmInput.setAttribute('required', 'required');
        //         }
        //     } catch (error) {
        //         console.error('Erro ao verificar email:', error);
        //     }
        // });

        // Form validation before submit
        document.querySelector('form').addEventListener('submit', (e) => {
            if (!isEmailExists && (!passwordInput.value || !passwordConfirmInput.value)) {
                e.preventDefault();
                alert('Por favor, preencha os campos de senha.');
            }
        });
    </script>
</x-guest-layout>
