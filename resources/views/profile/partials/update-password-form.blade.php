<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Actualizar contraseña</h2>
        <p class="mt-1 text-sm text-gray-600">Utiliza numeros, letras mayusculas y caracteres especiales, para mantener
            tu cuenta segura.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="form-group row">
            <div class="col-12 col-sm-6">
                <x-input-label for="current_password" :value="__('Contraseña actual')" />
                <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full"
                    autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-12 col-sm-6">
                <x-input-label for="password" :value="__('Nueva contraseña')" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>
            <div class="col-12 col-sm-6">
                <x-input-label for="password_confirmation" :value="__('Confirma la contraseña')" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                    class="mt-1 block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'password-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600">Actualizada</p>
            @endif
        </div>
    </form>
</section>