@section('titulo-html', 'Estadísticas')
<x-app-layout>
    <div class="pt-5">
        <div class="mx-5">
            <h2 class="font-semibold text-xl txt-titulo-h2 leading-tight">
                {{ __('Estadísticas') }}
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb custom">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Estadísticas</li>
                </ol>
            </nav>            
        </div>
    </div>

    <div class="py-8 mx-4">
        <div class="d-flex">
            <div class="bg-white w-full overflow-hidden shadow-sm sm:rounded-lg mx-4">
                <div class="py-12 p-5">
                    <canvas id="doughnut" height="100px"></canvas>
                </div>
            </div>
            <div class="bg-white w-full overflow-hidden shadow-sm sm:rounded-lg mx-4">
                <div class="py-12 p-5">
                    <div class="mb-5 d-flex flex-row align-items-center justify-content-between">
                        <h2 class="font-normal text-lg txt-titulo-h2 leading-tight">Cupones</h2>
                        <div class="ico_box_form">
                            <span class='icono icon-calendar'></span>
                            <select name="fechaPieCupones" id="fechaPieCupones">
                                <option value="0">Últimos 15 días</option>
                                <option value="01">Enero</option>
                                <option value="02">Febrero</option>
                                <option value="03">Marzo</option>
                                <option value="04">Abril</option>
                                <option value="05">Mayo</option>
                                <option value="06">Junio</option>
                                <option value="07">Julio</option>
                                <option value="08">Agosto</option>
                                <option value="09">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>                        
                    </div>        
                    <canvas id="PieCupones" height="100px"></canvas>
                </div>
            </div>
            <div class="bg-white w-full overflow-hidden shadow-sm sm:rounded-lg mx-4">
                <div class="py-12 p-5">
                    <canvas id="polarArea" height="100px"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="py-8  mx-4">
        <div class="d-flex">
            <div class="bg-white w-full overflow-hidden shadow-sm sm:rounded-lg mx-4">
                <div class="py-12 p-5">
                    <div class="mb-5 d-flex flex-row align-items-center justify-content-between">
                        <h2 class="font-normal text-lg txt-titulo-h2 leading-tight">Usuarios registrados</h2>
                        <div class="ico_box_form">
                            <span class='icono icon-calendar'></span>
                            <select name="fechaBarUsuarios" id="fechaBarUsuarios">
                                <option value="0">Últimos 15 días</option>
                                <option value="01">Enero</option>
                                <option value="02">Febrero</option>
                                <option value="03">Marzo</option>
                                <option value="04">Abril</option>
                                <option value="05">Mayo</option>
                                <option value="06">Junio</option>
                                <option value="07">Julio</option>
                                <option value="08">Agosto</option>
                                <option value="09">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>                        
                    </div>                    
                    <canvas id="barUsuarios" height="200px"></canvas>
                </div>
            </div>
            <div class="bg-white w-full overflow-hidden shadow-sm sm:rounded-lg mx-4">
                <div class="py-12 p-5">
                    <div class="mb-5 d-flex flex-row align-items-center justify-content-between">
                        <h2 class="font-normal text-lg txt-titulo-h2 leading-tight">Clientes registrados</h2>
                        <div class="ico_box_form">
                            <span class='icono icon-calendar'></span>
                            <select name="fechaBarClientes" id="fechaBarClientes">
                                <option value="0">Últimos 15 días</option>
                                <option value="01">Enero</option>
                                <option value="02">Febrero</option>
                                <option value="03">Marzo</option>
                                <option value="04">Abril</option>
                                <option value="05">Mayo</option>
                                <option value="06">Junio</option>
                                <option value="07">Julio</option>
                                <option value="08">Agosto</option>
                                <option value="09">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>                        
                    </div> 
                    <canvas id="barClientes" height="200px"></canvas>
                </div>
            </div>

        </div>
    </div>
    @push('custom-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>    
    <script type="text/javascript">
        var labelsBarUsuarios =  {{ Js::from($dataBarUsuarios['labels']) }};
        var usersBarUsuarios =  {{ Js::from($dataBarUsuarios['data']) }}; 
        var labelsBarClientes =  {{ Js::from($dataBarClientes['labels']) }};
        var usersBarClientes =  {{ Js::from($dataBarClientes['data']) }}; 
        var labelsPieCupones =  {{ Js::from($dataPieCupones['labels']) }};
        var usersPieCupones =  {{ Js::from($dataPieCupones['data']) }}; 
    </script>
    <script src="{{ asset('assets/charts/mycharts.js')}}"></script>    
    @endpush
</x-app-layout>