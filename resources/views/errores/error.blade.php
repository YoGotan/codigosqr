<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>
        <div class="text-center">
            <span class="m-3 font-weight-bold">{{$mensaje}}</span>
        </div>
    </x-auth-card>
</x-guest-layout>