<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 header-sticky">
    <!-- Primary Navigation Menu -->
    <div class="card_ppal">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div id="logo_header" class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="flex items-center">

                <div class="flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <a
                                class="icono-link inline-flex items-center text-sm leading-4 font-medium rounded-md text-gray-500 focus:outline-none transition ease-in-out duration-150">
                                <span class="icono icon-sistema"></span>
                                {{-- <span class="badge fd-info text-white font-weight-bold float-right">2</span> --}}
                            </a>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('codigosqr')" class="blq_h_img_txt_info">
                                <div class="figure p-1">
                                    <span class="icono icon-codigo-qr"></span>
                                </div>
                                <div class="content p-2 w-full">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p>Códigos QR</p>
                                    </div>
                                </div>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('cupones.show')" class="blq_h_img_txt_info">
                                <div class="figure p-1">
                                    <span class="icono icon-cupon"></span>
                                </div>
                                <div class="content p-2 w-full">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p>Ver cupones QR</p>
                                    </div>
                                </div>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('cupones.buscar')" class="blq_h_img_txt_info">
                                <div class="figure p-1">
                                    <span class="icono icon-buscar-qr"></span>
                                </div>
                                <div class="content p-2 w-full">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p>Buscar cupones QR</p>
                                    </div>
                                </div>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('clientes')" class="blq_h_img_txt_info">
                                <div class="figure p-1">
                                    <span class="icono icon-cliente"></span>
                                </div>
                                <div class="content p-2 w-full">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p>Ver clientes</p>
                                    </div>
                                </div>
                            </x-dropdown-link>
                            
                            <x-dropdown-link :href="route('usuarios')" class="blq_h_img_txt_info">
                                <div class="figure p-1">
                                    <span class="icono icon-empleados"></span>
                                </div>
                                <div class="content p-2 w-full">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p>Ver usuarios</p>
                                    </div>
                                </div>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('register')" class="blq_h_img_txt_info">
                                <div class="figure p-1">
                                    <span class="icono icon-empleados"></span>
                                </div>
                                <div class="content p-2 w-full">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p>Registrar usuarios</p>
                                    </div>
                                </div>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('graficas')" class="blq_h_img_txt_info">
                                <div class="figure p-1">
                                    <span class="icono icon-estadisticas"></span>
                                </div>
                                <div class="content p-2 w-full">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p>Gráficas</p>
                                    </div>
                                </div>
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>

                {{-- <div class="flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <a
                                class="icono-link inline-flex items-center text-sm leading-4 font-medium rounded-md text-gray-500 focus:outline-none transition ease-in-out duration-150">
                                <span class="icono icon-campana"></span>
                                <span class="badge fd-danger text-white font-weight-bold float-right">5</span>
                            </a>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')" class="blq_h_img_txt_info">
                                <div class="figure p-1">
                                    <span class="icono icon-users"></span>
                                </div>
                                <div class="content p-2 w-full">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p>Nuevo cliente registrado</p>
                                        <p class="sub-text text-muted pl-3">
                                            <span
                                                class="badge text-white fd-danger font-weight-bold float-right">5</span>
                                        </p>

                                    </div>
                                    <p class="sub-text text-muted">
                                        03/12/22 11:45:35
                                    </p>
                                </div>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')" class="blq_h_img_txt_info">
                                <div class="figure p-1">
                                    <img src="{{ asset('images/users/38-logo-disenium.png') }}" alt="userr">
                                </div>
                                <div class="content p-2 w-full">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p>Nuevo empleado registrado</p>
                                        <p class="sub-text text-muted pl-3">
                                            <span
                                                class="badge text-white fd-danger font-weight-bold float-right">5</span>
                                        </p>

                                    </div>
                                    <p class="sub-text text-muted">
                                        03/12/22 11:45:35
                                    </p>
                                </div>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')" class="blq_h_img_txt_info">
                                <div class="figure p-1">
                                    <span class="icono icon-cupon"></span>
                                </div>
                                <div class="content p-2 w-full">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p>Nuevo cupón canjeado</p>
                                        <p class="sub-text text-muted pl-3">
                                            <span
                                                class="badge text-white fd-danger font-weight-bold float-right">5</span>
                                        </p>

                                    </div>
                                    <p class="sub-text text-muted">
                                        03/12/22 11:45:35
                                    </p>
                                </div>
                            </x-dropdown-link>

                        </x-slot>
                    </x-dropdown>
                </div> --}}

                <div class="flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <a id="avatar"
                                class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-gray-500 focus:outline-none transition ease-in-out duration-150">
                                <img src="{{ asset('images/users/38-logo-disenium.png') }}" alt="imagen avatar">
                            </a>
                        </x-slot>

                        <x-slot name="content">
                            <div id="info_avatar" class="p-2 d-flex flex-column align-items-center">
                                <div class="figure p-1 w-20">
                                    <img src="{{ asset('images/users/38-logo-disenium.png') }}" alt="imagen avatar">
                                </div>
                                <div class="content p-2 pb-3 w-full d-flex justify-content-center flex-column align-items-center border-bottom">
                                    <div class="text-center">
                                        <h4 class="font-weight-bold">{{ Auth::user()->name }}</h4>
                                        <p class="sub-text text-muted">
                                            {{ Auth::user()->email }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <x-dropdown-link :href="route('profile.edit')" class="blq_h_img_txt_info">
                                <div class="figure p-1">
                                    <span class="icono icon-perfil"></span>
                                </div>
                                <div class="content p-2 w-full">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p>Editar perfil</p>
                                    </div>
                                </div>
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();" class="blq_h_img_txt_info">
                                    <div class="figure p-1">
                                        <span class="icono icon-logout"></span>
                                    </div>
                                    <div class="content p-2 w-full">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p>Cerrar sesión</p>
                                        </div>
                                    </div>
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
            <!-- Hamburger -->
            {{-- <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div> --}}

        </div>
    </div>

    {{-- <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                    </x-response-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
            </div>
        </div>
    </div> --}}
</nav>