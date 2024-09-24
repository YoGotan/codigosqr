@section('titulo-html', 'Panel cupones')
<x-app-layout> 
    <div class="pt-5">
        <div class="card_ppal">
            <h2 class="font-semibold text-xl txt-titulo-h2 leading-tight">
                {{ __('Panel cupones') }}
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb custom">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Códigos QR</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="py-8">
        <div class="card_ppal">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="py-12 p-5">
                    <table class="hover table table-bordered user_datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Tipo</th>
                                <th>Dto.</th>
                                <th>Origen</th>
                                <th width="100px">Usado</th>
                                <th>Gasto</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @push('custom-scripts')

    <script type="text/javascript">
    var table = null;
    $(function () {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });
        table = $('.user_datatable').DataTable({
            processing: true,
            serverSide: true,
            language: {
                url: '{{ asset("datatable/es-ES.json") }}'
            },
            ajax: "{{ route('dashboard') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'cliente', name: 'cliente'},
                {data: 'tipo', name: 'tipo'},
                {data: 'descuento', name: 'descuento'},
                {data: 'local_origen', name: 'local_origen'},
                {data: 'usado', name: 'usado', orderable: false, searchable: false},
                {data: 'gasto', name: 'gasto'},
                {data: 'fecha', name: 'fecha', width: '100px'},
            ],
            columnDefs: [
                { className: 'dt-center text-capitalize', targets: [3, 5, 6] },
                { className: 'dt-center', targets: [0, 7] }
            ],
        });          
    });
    function cambiarEstadoCupon(boton){
        const id_cupon = boton.getAttribute('attr-id');
        const usado = boton.getAttribute('attr-uso');
        const gasto = $('#gasto_'+id_cupon).val();
        console.log(id_cupon);
        console.log(usado);
        const params = {
            id: id_cupon,
            usado: usado,
            gasto: gasto
        };
        axios
        .post("/cupones/actualizar", params)
        .then(respuesta => {
            if(respuesta.data.guardado){
                if(respuesta.data.usado){
                    // $('.btn-usado[attr-id='+respuesta.data.id+']').attr('attr-uso', '1');
                    // $('.btn-usado[attr-id='+respuesta.data.id+']>span').attr('data-original-title', 'Un cupon usado no se puede modificar');
                    // $('.btn-usado[attr-id='+respuesta.data.id+']>span').removeClass('txt-danger');
                    // $('.btn-usado[attr-id='+respuesta.data.id+']>span').removeClass('fd-danger');
                    // $('.btn-usado[attr-id='+respuesta.data.id+']>span').addClass('fd-success');
                    // $('.btn-usado[attr-id='+respuesta.data.id+']>span').addClass('fd-success');
                    // $('.btn-usado[attr-id='+respuesta.data.id+']>span>span').removeClass('icon-close');
                    // $('.btn-usado[attr-id='+respuesta.data.id+']>span>span').removeClass('icon-close');
                    // $('.btn-usado[attr-id='+respuesta.data.id+']>span>span').addClass('icon-check');
                    // $('.fecha_uso[attr-id='+respuesta.data.id+']').text(respuesta.data.fecha_uso);
                    // $('.fecha_uso[attr-id='+respuesta.data.id+']~.hora_uso').text(respuesta.data.hora_uso);
                    // $('.gasto[attr-id='+respuesta.data.id+']').text(respuesta.data.gasto+'€');
                    table.ajax.reload(null, false);
                    setTimeout(() => {
                        $('[data-toggle="tooltip"]').tooltip();
                    }, 1000);
                }
                else {
                    $('.btn-usado[attr-id='+respuesta.data.id+']').attr('attr-uso', '0');
                    $('.btn-usado[attr-id='+respuesta.data.id+']').attr('data-original-title', 'Cambiar a usado');
                    $('.btn-usado[attr-id='+respuesta.data.id+']>span').removeClass('txt-success');
                    $('.btn-usado[attr-id='+respuesta.data.id+']>span').removeClass('fd-success');
                    $('.btn-usado[attr-id='+respuesta.data.id+']>span').addClass('fd-danger');
                    $('.btn-usado[attr-id='+respuesta.data.id+']>span').addClass('fd-danger');
                    $('.btn-usado[attr-id='+respuesta.data.id+']>span>span').removeClass('icon-check');
                    $('.btn-usado[attr-id='+respuesta.data.id+']>span>span').removeClass('icon-close');
                    $('.btn-usado[attr-id='+respuesta.data.id+']>span>span').addClass('icon-close');
                    $('.fecha_uso[attr-id='+respuesta.data.id+']').text('');
                    $('.fecha_uso[attr-id='+respuesta.data.id+']~.hora_uso').text('');
                }
                $('#modal_qr_usado_'+respuesta.data.id).modal('toggle');
                $('#modal_qr_usado_'+respuesta.data.id).attr('id', 'modal');
            }
        })
        .catch(function(error) {
            console.log(error);
        });
      };
    </script>



    @endpush
</x-app-layout>