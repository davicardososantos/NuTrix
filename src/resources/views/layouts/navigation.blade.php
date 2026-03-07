<nav x-data="{ mobileMenuOpen: false }" class="relative z-50">
    @php
        $activeRole = session('active_role');
        $hasPatient = Auth::user()->hasRole('patient');
        $hasNutritionist = Auth::user()->hasRole('nutritionist');
        $showPatient = $hasPatient && (!$hasNutritionist || $activeRole === 'patient');
        $showNutritionist = $hasNutritionist && (!$hasPatient || $activeRole === 'nutritionist');
    @endphp

    <!-- Mobile Top Header -->
    <div class="md:hidden fixed top-0 left-0 right-0 bg-white border-b border-gray-100 shadow-sm px-4 py-3 z-40">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('painel') }}" class="shrink-0 flex items-center gap-2">
                <img src="/logo/icone.png" alt="NuTrix" class="h-8 w-8 object-contain" />
                <span class="font-black text-base bg-gradient-to-r from-amber-700 to-green-700 bg-clip-text text-transparent">NuTrix</span>
            </a>

            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="flex flex-col gap-1.5 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <span class="w-5 h-0.5 bg-gray-900 transition-all" :class="mobileMenuOpen ? 'rotate-45 translate-y-2' : ''"></span>
                <span class="w-5 h-0.5 bg-gray-900 transition-all" :class="mobileMenuOpen ? 'opacity-0' : ''"></span>
                <span class="w-5 h-0.5 bg-gray-900 transition-all" :class="mobileMenuOpen ? '-rotate-45 -translate-y-2' : ''"></span>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Overlay & Drawer -->
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300" x-transition:leave="transition ease-in duration-200" @click.outside="mobileMenuOpen = false" class="fixed inset-0 bg-black/30 md:hidden" style="display: none;"></div>

    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="-translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="-translate-y-full" class="fixed top-14 left-0 right-0 bg-white rounded-b-3xl shadow-2xl md:hidden max-h-[90vh] overflow-y-auto z-40" style="display: none;">
        <div class="px-6 py-6 pb-8">
            <!-- Badge: Nutricionista (Top) -->
            @if($showNutritionist)
                <div class="mb-6 px-4 py-3 bg-gradient-to-r from-emerald-50 to-green-50 border border-green-200 rounded-xl">
                    <p class="text-sm font-semibold text-green-700 flex items-center gap-2">
                        <i class="fas fa-stethoscope"></i>
                        Área do Nutricionista
                    </p>
                </div>
            @endif

            <!-- Navigation Items -->
            <div class="space-y-3">
                <!-- Dashboard -->
                <a href="{{ route('painel') }}" @click="mobileMenuOpen = false" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('painel') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-home text-xl w-6"></i>
                    <span class="font-semibold">Início</span>
                </a>

                @if($showPatient)
                    <!-- Water -->
                    <a href="{{ route('consumos-agua.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('consumos-agua.*') ? 'bg-cyan-50 text-cyan-600' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-droplet text-xl w-6"></i>
                        <span class="font-semibold">Hidratação</span>
                    </a>

                    <!-- Weight -->
                    <a href="{{ route('pesos.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('pesos.*') ? 'bg-emerald-50 text-emerald-600' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-scale-balanced text-xl w-6"></i>
                        <span class="font-semibold">Peso</span>
                    </a>
                @endif

                @if($showNutritionist)
                    <!-- Patients -->
                    <a href="{{ route('pacientes.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('pacientes.*') ? 'bg-green-50 text-green-600' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-users text-xl w-6"></i>
                        <span class="font-semibold">Pacientes</span>
                    </a>
                @endif

                <!-- Divider -->
                <div class="border-t border-gray-200 my-4"></div>

                <!-- Settings -->
                <a href="{{ route('perfil.editar') }}" @click="mobileMenuOpen = false" class="flex items-center gap-4 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 transition-all">
                    <i class="fas fa-gear text-xl w-6"></i>
                    <span class="font-semibold">Configurações</span>
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-4 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition-all font-semibold text-left">
                        <i class="fas fa-sign-out-alt text-xl w-6"></i>
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Desktop Top Navigation -->
    <div class="hidden md:block bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Left: Logo & Nav -->
                <div class="flex items-center gap-16">
                    <!-- Logo -->
                    <a href="{{ route('painel') }}" class="group shrink-0 flex items-center gap-2">
                        <img src="/logo/icone.png" alt="NuTrix" class="h-9 w-9 object-contain" />
                        <span class="text-lg font-black bg-gradient-to-r from-amber-700 to-green-700 bg-clip-text text-transparent">NuTrix</span>
                    </a>

                    <!-- Navigation Links -->
                    <div class="flex items-center gap-12">
                        <!-- Dashboard -->
                        <a href="{{ route('painel') }}" class="relative group">
                            <span class="text-gray-700 font-medium transition-colors duration-300 {{ request()->routeIs('painel') ? 'text-gray-900' : 'hover:text-gray-900' }}">
                                Início
                            </span>
                            <span class="absolute -bottom-2 left-0 w-0 h-0.5 bg-gradient-to-r from-blue-600 to-cyan-600 transition-all duration-300 {{ request()->routeIs('painel') ? 'w-full' : 'group-hover:w-full' }}"></span>
                        </a>

                        @if($showPatient)
                            <!-- Water -->
                            <a href="{{ route('consumos-agua.index') }}" class="relative group">
                                <span class="text-gray-700 font-medium transition-colors duration-300 {{ request()->routeIs('consumos-agua.*') ? 'text-gray-900' : 'hover:text-gray-900' }}">
                                    Hidratação
                                </span>
                                <span class="absolute -bottom-2 left-0 w-0 h-0.5 bg-gradient-to-r from-cyan-600 to-blue-600 transition-all duration-300 {{ request()->routeIs('consumos-agua.*') ? 'w-full' : 'group-hover:w-full' }}"></span>
                            </a>

                            <!-- Weight -->
                            <a href="{{ route('pesos.index') }}" class="relative group">
                                <span class="text-gray-700 font-medium transition-colors duration-300 {{ request()->routeIs('pesos.*') ? 'text-gray-900' : 'hover:text-gray-900' }}">
                                    Peso
                                </span>
                                <span class="absolute -bottom-2 left-0 w-0 h-0.5 bg-gradient-to-r from-emerald-600 to-green-600 transition-all duration-300 {{ request()->routeIs('pesos.*') ? 'w-full' : 'group-hover:w-full' }}"></span>
                            </a>
                        @endif

                        @if($showNutritionist)
                            <!-- Patients -->
                            <a href="{{ route('pacientes.index') }}" class="relative group">
                                <span class="text-gray-700 font-medium transition-colors duration-300 {{ request()->routeIs('pacientes.*') ? 'text-gray-900' : 'hover:text-gray-900' }}">
                                    Pacientes
                                </span>
                                <span class="absolute -bottom-2 left-0 w-0 h-0.5 bg-gradient-to-r from-green-600 to-emerald-600 transition-all duration-300 {{ request()->routeIs('pacientes.*') ? 'w-full' : 'group-hover:w-full' }}"></span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Right: User Menu -->
                <div class="flex items-center gap-8">
                    <!-- User Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-3 group">
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    @if($showNutritionist)
                                        <i class="fas fa-stethoscope"></i>
                                    @endif
                                    {{ $activeRole === 'patient' ? 'Paciente' : 'Nutricionista' }}
                                </p>
                            </div>
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-semibold text-sm">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-500 transition-transform duration-300" :class="open ? 'rotate-180 text-gray-900' : ''" style="width: 14px;"></i>
                        </button>

                        <div x-show="open" x-transition @click.outside="open = false" class="absolute right-0 mt-4 w-56 bg-white border border-gray-100 rounded-xl shadow-xl shadow-black/10 z-50 overflow-hidden">
                            <!-- User Info -->
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ Auth::user()->email }}</p>
                            </div>

                            <!-- Menu Items -->
                            <a href="{{ route('perfil.editar') }}" class="flex items-center gap-3 px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200 text-sm font-medium">
                                <i class="fas fa-sliders-h w-4"></i>
                                Configurações
                            </a>

                            <div class="border-t border-gray-100"></div>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-3 px-6 py-3 text-red-600 hover:bg-red-50 transition-colors duration-200 text-sm font-medium">
                                    <i class="fas fa-sign-out-alt w-4"></i>
                                    Sair
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Top Spacer -->
    <div class="h-14 md:hidden"></div>
</nav>
