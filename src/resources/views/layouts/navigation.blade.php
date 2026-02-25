<nav x-data="{ open: false }" class="bg-white shadow-md border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-amber-700" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="hover:text-amber-600">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    @if(Auth::user()->hasRole('patient'))
                        <x-nav-link :href="route('water-consumptions.index')" :active="request()->routeIs('water-consumptions.*')" class="hover:text-blue-600">
                            {{ __('💧 Água') }}
                        </x-nav-link>
                    @endif

                    @if(Auth::user()->hasRole('nutritionist'))
                        <x-nav-link :href="route('patients.index')" :active="request()->routeIs('patients.*')" class="hover:text-green-600">
                            {{ __('👥 Pacientes') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-3 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                            <div class="flex flex-col items-start">
                                <span class="text-sm font-semibold">{{ Auth::user()->name }}</span>
                                @if(Auth::user()->hasRole('nutritionist'))
                                    <span class="text-xs font-medium text-amber-600">Nutricionista</span>
                                @elseif(Auth::user()->hasRole('patient'))
                                    <span class="text-xs font-medium text-blue-600">Paciente</span>
                                @endif
                            </div>

                            <div class="ms-auto">
                                <svg class="fill-current h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold">Conta</p>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')">
                            ⚙️ {{ __('Configurações') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <div class="border-t border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    🚪 {{ __('Sair') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            @if(Auth::user()->hasRole('patient'))
                <x-responsive-nav-link :href="route('water-consumptions.index')" :active="request()->routeIs('water-consumptions.*')">
                    {{ __('💧 Água') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->hasRole('nutritionist'))
                <x-responsive-nav-link :href="route('patients.index')" :active="request()->routeIs('patients.*')">
                    {{ __('👥 Pacientes') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-100">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                @if(Auth::user()->hasRole('nutritionist'))
                    <div class="text-sm text-amber-600 font-medium mt-1">Nutricionista</div>
                @elseif(Auth::user()->hasRole('patient'))
                    <div class="text-sm text-blue-600 font-medium mt-1">Paciente</div>
                @endif
                <div class="font-medium text-sm text-gray-500 mt-2">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    ⚙️ {{ __('Configurações') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        🚪 {{ __('Sair') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
