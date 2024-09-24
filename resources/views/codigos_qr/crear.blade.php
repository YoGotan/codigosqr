@section('titulo-html', 'Crear código QR')
<x-app-layout>
    <div class="pt-5">
        <div class="card_ppal">
            <h2 class="font-semibold text-xl txt-titulo-h2 leading-tight">
                {{ __('Crear código QR') }}
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb custom">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('codigosqr') }}">Códigos QR</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Crear</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="py-8">
        <div class="card_ppal">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="w-full">
                    <section>
                        <form method="post" action="{{ route('codigosqr.generar') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('post')
                            <div class="form-group row px-5">
                                <div class="col-12 col-sm-6">
                                    <x-input-label for="local" :value="__('Selecciona un local')" />
                                    <div class="ico_box_form">
                                        <span class="icono icon-local"></span>
                                        <select id="local" name="local">
                                            <option>...</option>
                                            <option>La Patita</option>
                                            <option>Ramonoteca</option>
                                            <option>Gastro Tortillas</option>
                                            <option>Quebec</option>
                                            <option>Lolas</option>
                                        </select>
                                    </div>
                                    @error('local')
                                    <div class="alert alert-danger">{{$message}}</div>
                                    @enderror
                                    <div class="d-flex gap-4">
                                        <div class="mt-3 flex items-center ">
                                            <x-primary-button>{{ __('Generar') }}</x-primary-button>
                                        </div>
                                        @if ($generar)
                                        <div class="mt-3 flex items-center">
                                            <x-primary-button>{{ __('Enviar a whatsApp') }}</x-primary-button>
                                        </div>
                                        <div class="mt-3 flex items-center">
                                            <x-primary-button>{{ __('Enviar por email') }}</x-primary-button>
                                        </div>
                                        @endif
                                    </div>
                                    
                                </div>
                                <div class="col-12 col-sm-6 text-right">
                                    <div id="codigoqr" class="d-inline-block">
                                        @if ($generar)
                                        {!! QrCode::size(300)->generate($url, $path) !!}
                                        {!! QrCode::size(300)->generate($url) !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>