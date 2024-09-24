<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Información del empleado</h2>
        <p class="mt-1 text-sm text-gray-600">Actualiza la información de perfil y la dirección de correo electrónico de
            su cuenta.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="form-group row">
            <div class="col-12 col-sm-6">
                <x-input-label for="name" :value="__('Nombre')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                    :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div class="col-12 col-sm-6">
                <x-input-label for="apellidos" :value="__('Apellidos')" />
                <x-text-input id="apellidos" name="apellidos" type="text" class="mt-1 block w-full"
                    :value="old('apellidos', $user->apellidos)" required autofocus autocomplete="apellidos" />
                <x-input-error class="mt-2" :messages="$errors->get('apellidos')" />
            </div>

        </div>

        <div class="form-group row">
            <div class="col-12 col-sm-6">
                <x-input-label for="puesto" :value="__('Puesto de trabajo')" />
                <select id="puesto" name="puesto" class="form-control">
                    <option>Camarero</option>
                    <option>Encargado</option>
                </select>
            </div>
            <div class="col-12 col-sm-6">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                    :value="old('email', $user->email)" required autocomplete="email" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        Tu dirección de email no está verificada.

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Haz clic aquí para volver a enviar el email de verificación.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('Se ha enviado un nuevo enlace de verificación a tu dirección de email.') }}
                    </p>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <div class="col">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="gridCheck1">
                    <label class="form-check-label" for="gridCheck1">
                        He leído y acepto la <u><a href="{{ route('rgpd.privacidad') }}">política de privacidad.</a></u>
                    </label>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600">Actualizado</p>
            @endif
        </div>
    </form>
</section>