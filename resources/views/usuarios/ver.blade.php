@section('titulo-html', 'Lista de usuarios')
<x-app-layout>
    <div class="pt-5">
        <div class="card_ppal">
            <h2 class="font-semibold text-xl txt-titulo-h2 leading-tight">
                {{ __('Lista de usuarios') }}
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb custom">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Usuarios</li>
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
                                <th>Email</th>
                                <th>Fecha Registro</th>
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
                        url: '{{ asset('datatable/es-ES.json') }}',
                    },
                    ajax: "{{ route('usuarios') }}",
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'nombre',
                            name: 'nombre'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'fecha',
                            name: 'fecha'
                        },
                        {
                            data: 'editar',
                            name: 'editar',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    columnDefs: [{
                            className: 'text-capitalize',
                            targets: [1]
                        },
                        {
                            type: 'date-euro',
                            targets: [3]
                        },
                        {
                            width: 200,
                            targets: [1,2]
                        },
                        {
                            width: 80,
                            targets: [0,4]
                        },
                        {
                            width: 150,
                            targets: [3]
                        },
                    ],
                });
            });
        </script>
    @endpush
</x-app-layout>
