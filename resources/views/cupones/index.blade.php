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
                <div class="p-md-5 py-5 px-3">
                    <table id="datosDatatable" class="hover table table-bordered">
                        <thead>
                            <tr>
                                <th>QR Cupón</th>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Tipo</th>
                                <th>Dto.</th>
                                <th>Origen</th>
                                <th>Gasto</th>
                                <th>Fecha</th>
                                <th>Usado</th>
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
                table = $('#datosDatatable').DataTable({
                    ajax: "{{ route('cupones.show') }}",
                    order: [
                        [1, 'asc']
                    ],
                    scrollY: 500,
                    scrollX: true,
                    iDisplayLength: 100,
                    dom: '<"header-table d-flex justify-content-between flex-wrap mb-16"Blf>tr<"footer-table d-flex justify-content-between flex-wrap mt-10"ip><"clear">',
                    language: {
                        url: "{{ asset('datatable/es-ES.json') }}"
                    },

                    columns: [

                        {
                            data: 'imagen',
                            name: 'imagen',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'cliente',
                            name: 'cliente'
                        },
                        {
                            data: 'tipo',
                            name: 'tipo'
                        },
                        {
                            data: 'descuento',
                            name: 'descuento'
                        },
                        {
                            data: 'local_origen',
                            name: 'local_origen'
                        },

                        {
                            data: 'gasto',
                            name: 'gasto'
                        },
                        {
                            data: 'fecha',
                            name: 'fecha',
                            width: '100px'
                        },
                        {
                            data: 'usado',
                            name: 'usado',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    columnDefs: [{
                            className: 'text-capitalize',
                            targets: [2]
                        },
                        {
                            className: 'dt-center',
                            targets: [0, 1, 3, 8]
                        },
                        {
                            type: 'date-euro',
                            targets: [7]
                        },
                        {
                            width: 50,
                            targets: [1, 3, 8]
                        },
                        {
                            width: 80,
                            targets: [0]
                        },
                        {
                            width: 150,
                            targets: [2, 4, 5]
                        },
                        {
                            width: 120,
                            targets: [7]
                        }
                    ],
                });
            });
        </script>
    @endpush
</x-app-layout>
