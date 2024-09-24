@section('titulo-html', 'Participar')
<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <form id="form_participar" method="POST" action="{{route('cliente.nuevo')}}">
            @csrf

            <!-- Name -->
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-3">
                    <x-input-label for="name" :value="__('Nombre')" />

                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                        required autofocus />

                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <x-input-label for="last_name" :value="__('Apellidos')" />

                    <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name"
                        :value="old('last_name')" required autofocus />

                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>
            </div>

            <!-- Email -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />

                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required />

                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Teléfono -->
            <div class="mt-4">
                <x-input-label for="phone" :value="__('Teléfono')" />

                <x-text-input id="phone" class="block mt-1 w-full" type="tel" pattern="[0-9]{9}" name="phone"
                    :value="old('phone')" required />

                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <!-- Cumpleaños -->
            <div class="mt-4">
                <x-input-label for="fecha_nacimiento" :value="__('Fecha de nacimiento')" />

                <x-text-input id="fecha_nacimiento" class="block mt-1 w-full" type="date" name="fecha_nacimiento"
                    :value="old('fecha_nacimiento')" required />

                <x-input-error :messages="$errors->get('fecha_nacimiento')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                {{-- <a
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('login') }}">
                    {{ __('¿Ya tienes cuenta?') }}
                </a> --}}
                <input type="hidden" id="token" name="token" value="{{ request()->query('token')}}" />
                {{-- <input type="hidden" id="origen" name="origin" value="{{ request()->query('origen')}}" /> --}}
                <x-primary-button id="btn_participar" class="ml-4">
                    {{ __('Participar') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
    @push('scripts')
{{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        document.getElementById('btn_participar').addEventListener("click",  function (e){
            e.preventDefault();
            if(document.getElementById('origen').value == ''){
                showBasico(
                        'Tiene que venir de un codigo QR'
                    );
            }else{
                document.getElementById('form_participar').submit();
            }
        });
        showBasico = function($mensaje) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: $mensaje,
            });
        };
    });
    
</script> --}}
@endpush
</x-guest-layout>

