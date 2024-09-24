@section('titulo-html', 'Registro de usuario')
<x-app-layout>
    <div class="pt-5">
        <div class="card_ppal">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Registro de usuario') }}
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb custom">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('codigosqr') }}">Usuarios</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Registrar</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="py-8">
        <div class="card_ppal">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="py-12 p-5">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="form-group row">
                            <div class="col-12 col-sm-6">
                                <x-input-label for="name" :value="__('Nombre')" />

                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                    :value="old('name')" required autofocus />

                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div class="col-12 col-sm-6">
                                <!-- Email Address -->

                                <x-input-label for="email" :value="__('Email')" />

                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                    :value="old('email')" required />

                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Contraseña')" />

                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                                required autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />

                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                name="password_confirmation" required />

                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                href="{{ route('login') }}">
                                {{ __('¿Ya tienes cuenta?') }}
                            </a>

                            <x-primary-button class="ml-4">
                                {{ __('Registrar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>