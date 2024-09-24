@section('titulo-html', 'Buscar código QR')
<x-app-layout>
    <div class="pt-5">
        <div class="card_ppal">
            <h2 class="font-semibold text-xl txt-titulo-h2 leading-tight">
                {{ __('Buscar código QR') }}
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb custom">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('codigosqr') }}">Códigos QR</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Buscar</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="py-8">
        <div class="card_ppal">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="w-full">
                    <section>
                        <div class="form-group row p-md-5 py-5 px-3">
                            {{-- <div class="col-12 col-sm-4">
                                <div class="d-flex gap-4">
                                    <div class="mt-3 flex items-center ">
                                        <a id="btn_escaner"
                                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">{{ __('Escanear QR') }}</a>
                                    </div>
                                    
                                </div>
                            </div> --}}
                            <div class="col-12" style="width:800px">
                                <span id="error_qr"></span>
                                <div id="codigoqr" class="d-inline-block" style="width:800px">
                                    {{-- <video id="preview"></video> --}}
                                    <div id="reader" width="600px"></div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>




    @push('custom-scripts')
        {{-- <script src="https://blog.minhazav.dev/assets/research/html5qrcode/html5-qrcode.min.v2.3.0.js" type="text/javascript"> --}}
        {{-- <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script> --}}
        <script src="{{asset('assets/scanqr/html5-qrcode.min.js')}}" type="text/javascript"></script>
        <script src="{{ asset('js/scaneo_qr.js') }}"></script>
        

        <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
        <script type="text/javascript">
            let scanner = new Instascan.Scanner({
                video: document.getElementById('preview')
            });
            $('#btn_escaner').on('click', function() {
                Instascan.Camera.getCameras().then(function(cameras) {
                    if (cameras.length > 0) {
                        scanner.start(cameras[1]);
                    } else {
                        console.error('No se puedo encontrar la camara.');
                    }
                }).catch(function(e) {
                    console.error(e);
                });
            });

            scanner.addListener('scan', function(content) {
                const cadena_qr = content;
                const params = {
                    cadena_qr: cadena_qr,
                };
                axios
                    .post("/codigosqr/validar", params)
                    .then(respuesta => {
                        if (respuesta.data.valido) {
                            $('#error_qr').text(respuesta.data.mensaje);
                        } else {
                            $('#error_qr').text(respuesta.data.mensaje);
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            });




            
        </script>


    @endpush
</x-app-layout>
