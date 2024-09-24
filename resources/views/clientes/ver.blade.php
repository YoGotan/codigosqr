@section('titulo-html', 'Lista de clientes')
<x-app-layout>
    <div class="pt-5">
        <div class="card_ppal">
            <h2 class="font-semibold text-xl txt-titulo-h2 leading-tight">
                {{ __('Lista de clientes') }}
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb custom">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Clientes</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="py-8">
        <div class="card_ppal">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-md-5 py-5 px-3">
                    <table id="datosDatatable" class="hover table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Fecha nacimiento</th>
                                <th>Nº Cupones</th>
                                <th>Fecha registro</th>
                                <th></th>
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
            $(function() {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    }
                });
                $.fn.dataTable.ext.type.order['date-euro-pre'] = function(d) {
                    var parts = d.match(/(\d{2})\/(\d{2})\/(\d{4})/);
                    if (!parts) return 0;

                    var date = new Date(parts[3], parts[2] - 1, parts[1]);
                    var timePart = d.match(/(\d{2}):(\d{2}):(\d{2})/);
                    if (timePart) {
                        date.setHours(timePart[1]);
                        date.setMinutes(timePart[2]);
                        date.setSeconds(timePart[3]);
                    }
                    return date.valueOf();
                };
                var table = $('#datosDatatable').DataTable({
                    order: [
                        [0, 'asc']
                    ],
                    scrollY: 500,
                    scrollX: true,
                    iDisplayLength: 100,
                    dom: '<"header-table d-flex justify-content-between flex-wrap mb-16"Blf>tr<"footer-table d-flex justify-content-between flex-wrap mt-10"ip><"clear">',
                    language: {
                        url: "{{ asset('datatable/es-ES.json') }}",
                    },
                    ajax: "{{ route('clientes') }}",
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'cliente',
                            name: 'cliente'
                        },
                        {
                            data: 'telefono',
                            name: 'telefono'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'fecha_nacimiento',
                            name: 'fecha_nacimiento'
                        },
                        {
                            data: 'num_cupones',
                            name: 'num_cupones'
                        },
                        {
                            data: 'fecha',
                            name: 'fecha'
                        },
                        {
                            data: 'ver',
                            name: 'ver',
                            orderable: false,
                            searchable: false
                        },

                    ],
                    columnDefs: [
                        {
                            className: 'dt-center',
                            targets: [0, 4, 5]
                        },
                        {
                            type: 'date-euro',
                            targets: [4,6]
                        },
                        {
                            width: 50,
                            targets: [0]
                        },
                        {
                            width: 200,
                            targets: [1,3]
                        },
                        {
                            width: 130,
                            targets: [6]
                        },
                        {
                            width: 150,
                            targets: [4]
                        },
                        {
                            width: 90,
                            targets: [5]
                        },
                    ],
                });
            });
        </script>
    @endpush
</x-app-layout>
