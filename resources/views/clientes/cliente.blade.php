@section('titulo-html', 'Perfil cliente')
<x-app-layout>

    <div class="pt-5">
        <div class="card_ppal">
            <h2 class="font-semibold text-xl txt-titulo-h2 leading-tight">
                Perfil cliente <span class="badge fd-info txt-info">#{{$cliente->id}}</span>
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb custom">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clientes') }}">Clientes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Perfil</li>
                </ol>
            </nav>
        </div>
    </div>

    <div id="perfil_cliente" class="py-8">
        <div class="card_ppal">
            <div class="w-full">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="card_cliente bg-white shadow sm:rounded-lg p-5 mr-2">
                            <header class="mb-4">
                                <h2 class="text-lg font-medium text-gray-900">Información personal</h2>
                            </header>

                            <div class="content">
                                <p class="p-1">{{ $cliente->nombre }} {{ $cliente->apellidos }}</p>
                                <p class="p-1"><span class="icono pr-2 icon-mail"></span> {{ $cliente->email }}</p>
                                <p class="p-1"><span class="icono pr-2 icon-telefono"></span> {{ $cliente->telefono }}
                                <p class="p-1"><span class="icono pr-2 icon-pastel-de-cumpleanos"></span> {{ \Carbon\Carbon::parse($cliente->fecha_nacimiento)->translatedFormat('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="card_cliente bg-white shadow sm:rounded-lg p-5 ml-2">
                            <header class="mb-4">
                                <h2 class="text-lg font-medium text-gray-900">Códigos QR Usados</h2>
                            </header>
                            <div class="d-flex flex-wrap">
                                @foreach ($cliente->codigos as $codigo)
                                <div class="content_qr">
                                    <div class="position-relative d-inline-block">
                                        <img title="Token: {{ $codigo->token }}" class="img_svg_qr"
                                            src="/{{$codigo->imagen}}" alt="codigo QR" />
                                        <span title="click para zoom"
                                            class="position-absolute p-2 bg-white rounded-circle icon-zoom"
                                            data-toggle="modal" data-target="#modal_qr_{{$codigo->id}}"></span>
                                        <div class="modal fade" id="modal_qr_{{$codigo->id}}" tabindex="-1"
                                            role="dialog" aria-labelledby="modalCodigoQRZoom" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Código Qr</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            title="Cerrar">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <div class="d-inline-block p-3">
                                                            <img class="" src="/{{$codigo->imagen}}" alt="codigo QR" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="mt-3 font-weight-normal p-2 badge fd-muted">{{ $codigo->local_origen
                                        }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card_cliente bg-white shadow sm:rounded-lg p-5 ml-2">
                            <header class="mb-4">
                                <h2 class="text-lg font-medium text-gray-900">Cupones 
                                @if ($gasto > 0)
                                    <span class="ml-2 p-2 badge fd-info txt-info">{{ $gasto }}€ gastados</span></h2> 
                                @endif
                            </header>
                            <div class="d-flex flex-wrap">
                                @foreach ($cliente->codigos as $codigo)
                                <div class="content_cupon mr-4">
                                    <div class="content d-flex flex-column ">
                                        <div class="position-relative">
                                            @if ($codigo->cupon->usado)
                                            <div class="bg-white rounded-circle d-inline-block btn-usado-cliente btn-usado"
                                                attr-id="{{ $codigo->cupon->id }}<"><svg attr-uso="1"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="#42ab49" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M4.5 12.75l6 6 9-13.5" />
                                                </svg></div>
                                            @else
                                            <div onclick="cambiarEstadoCupon(this)"
                                                class="bg-white rounded-circle d-inline-block btn-usado-cliente btn-usado"
                                                attr-id="{{ $codigo->cupon->id }}<"><svg attr-uso="0"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="#e2504c" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg></div>
                                            @endif

                                            {{-- @if ($codigo->cupon->descuento != '') --}}
                                            <p class="p-1"><span class="icono icon-vale-cantidad"></span></p>
                                            <p><span class="p-2 badge fd-info txt-info">{{ $codigo->rangoCupon->descuento
                                                    }}</span></p>
                                            {{-- @endif
                                            @if ($codigo->cupon->descuento != '')
                                            <p class="p-1"><span class="icono icon-vale-porcentaje"></span></p>
                                            <p><span class="p-2 badge fd-info txt-info">{{
                                                    $codigo->cupon->descuento }}
                                                    %</span></p>
                                            @endif --}}
                                            {{-- @if ($cupon->producto_regalo != '')
                                            <p class="p-1"><span class="icono icon-vale-regalo"></span></p>
                                            <p><span class="p-2 badge fd-info txt-info">{{ $cupon->producto_regalo
                                                    }}</span></p>
                                            @endif --}}
                                            <p><span class="badge text-capitalize font-weight-normal p-2">{{
                                                    $codigo->local->nombre }}</span></p>
                                            {{-- <p><span class="badge text-capitalize font-weight-normal p-2">{{
                                                    $cupon->local_destino }}</span></p> --}}
                                        </div>
                                    </div>
                                    {{-- <p>
                                        <span class="mb-2 font-weight-normal p-2 badge fd-muted">{{ $codigo->cupon->cadena }}</span>
                                    </p> --}}
                                    @if ($codigo->cupon->usado)
                                    <p>
                                        <span class="mb-4 font-weight-normal p-2 badge">GASTO: <span class="font-weight-bold">{{ $codigo->cupon->gasto }}€</span></span>
                                    </p>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>