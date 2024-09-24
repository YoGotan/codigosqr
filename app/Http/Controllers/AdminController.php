<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Codigo;
use App\Models\Cupon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $data = Cupon::with(['codigo.cliente', 'codigo.local', 'codigo.rangoCupon'])->get();
        // dd($data);
        $codigo = new Codigo();
        if ($request->ajax()) {
            $data = Cupon::with(['codigo.cliente', 'codigo.local', 'codigo.rangoCupon'])->get();
            // $data = Cupon::select('clientes.id as id_cliente', 'cupons.id', 'cupons.id_cliente', DB::raw("CONCAT(clientes.nombre,' ',clientes.apellidos) as nombre"), 'cadena', 'tipo', 'porcentaje_descuento', 'cantidad_descuento', 'producto_regalo', 'local_origen', 'local_destino', 'usado', 'gasto', 'cupons.updated_at')
            //     ->leftjoin('clientes', 'cupons.id_cliente', '=', 'clientes.id')
            //     ->get();
            // Log::info('fecha usado');
            // Log::info($data[1]->updated_at);
            return Datatables::of($data)->addIndexColumn()

                ->addColumn('cliente', function ($row) {
                    //Log::info($row->usado);
                    $btn = '<a data-toggle="tooltip" title="Ver datos del cliente" href="' .  route('cliente.mostrar', ['cliente' => $row->codigo->cliente->id]) . '" class="btn-tabla btn-cliente" attr-nombre="' . $row->codigo->cliente->nombre . ' ' . $row->codigo->cliente->apellidos . '" attr-id="' . $row->codigo->cliente->id . '">' . $row->codigo->cliente->nombre . ' ' . $row->codigo->cliente->apellidos . '</a>';
                    return $btn;
                })
                ->addColumn('tipo', function ($row) {
                    //Log::info($row->usado);
                    if ($row->codigo->rangoCupon->id == 1) {
                        $btn = '<span class="p-2 badge fd-amarillo txt-amarillo">' . $row->codigo->rangoCupon->id . '</span>';
                    } elseif($row->codigo->rangoCupon->id == 2) {
                        $btn = '<span class="p-2 badge fd-piscina txt-piscina">' . $row->codigo->rangoCupon->id . '</span>';
                    } else {
                        $btn = '<span class="p-2 badge fd-marron txt-marron">' . $row->codigo->rangoCupon->id . '</span>';
                    }
                    return $btn;
                })
                ->addColumn('descuento', function ($row) {
                    if ($row->codigo->rangoCupon->id == 1) {
                        $btn = '<span class="p-2 badge fd-amarillo txt-amarillo">' . $row->codigo->rangoCupon->descuento . '</span>';
                    } elseif($row->codigo->rangoCupon->id == 2) {
                        $btn = '<span class="p-2 badge fd-piscina txt-piscina">' . $row->codigo->rangoCupon->descuento . '</span>';
                    } else {
                        $btn = '<span class="p-2 badge fd-marron txt-marron">' . $row->codigo->rangoCupon->descuento . '</span>';
                    }
                    return $btn;
                })
                ->addColumn('local_origen', function ($row) {
                    Log::info($row->local_origen);
                    if ($row->codigo->local->id == 1) {
                        $btn = '<span class="p-2 badge fd-info txt-info">' . $row->codigo->local->nombre . '</span>';
                    } else if ($row->codigo->local->id == 2) {
                        $btn = '<span class="p-2 badge fd-morado txt-morado" >' . $row->codigo->local->nombre . '</span>';
                    } else if ($row->codigo->local->id == 3) {
                        $btn = '<span class="p-2 badge fd-naranja txt-naranja" >' . $row->codigo->local->nombre . '</span>';
                    } else if ($row->codigo->local->id == 4) {
                        $btn = '<span class="p-2 badge fd-danger txt-danger" >' . $row->codigo->local->nombre . '</span>';
                    } else if ($row->codigo->local->id == 5) {
                        $btn = '<span class="p-2 badge fd-success txt-success" >' . $row->codigo->local->nombre . '</span>';
                    }
                    return $btn;
                })
                // ->addColumn('local_destino', function ($row) {
                //     Log::info($row->local_destino);
                //     $local = Str::lower($row->local_destino);
                //     if ($local == 'la patita') {
                //         $btn = '<span class="p-2 badge fd-info txt-info">' . $local . '</span>';
                //     } else if ($local == 'ramonoteca') {
                //         $btn = '<span class="p-2 badge fd-morado txt-morado" >' . $local . '</span>';
                //     } else if ($local == 'lolas') {
                //         $btn = '<span class="p-2 badge fd-naranja txt-naranja" >' . $local . '</span>';
                //     } else if ($local == 'gastro tortillas') {
                //         $btn = '<span class="p-2 badge fd-danger txt-danger" >' . $local . '</span>';
                //     } else if ($local == 'quebec') {
                //         $btn = '<span class="p-2 badge fd-success txt-success" >' . $local . '</span>';
                //     }
                //     return $btn;
                // })
                ->addColumn('usado', function ($row) {
                    //Log::info($row->usado);
                    if ($row->usado == 0) {
                        $btn = '<a data-toggle="modal" data-target="#modal_qr_usado_' . $row->id . '" class="d-inline-block btn-tabla btn-usado" attr-uso="0" attr-id="' . $row->id . '"><span data-toggle="tooltip" title="Cambiar a usado" attr-uso="0" class="badge p-2 fd-danger txt-danger"><span class="icono icon-close"></span></span></a>
                        <div class="modal fade" id="modal_qr_usado_' . $row->id . '" tabindex="-1" role="dialog" aria-labelledby="modalCodigoQRZoom" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Introduce el importe del ticket</h5>
                                        <button type="button" class="close" data-dismiss="modal" data-toggle="tooltip" title="Cerrar">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">                                        
                                        <div class="mt-4">
                                            <label for="gasto_' . $row->id . '" class="text-left block font-medium text-sm text-gray-700">Gasto</label>
                                            <input id="gasto_' . $row->id . '" type="text" name="gasto" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md" required="required">
                                        </div>
                                        <div class="flex items-center justify-end mt-4">
                                            <a onclick="cambiarEstadoCupon(this)" attr-uso="0" attr-id="' . $row->id . '" class="btn_form_gastos inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 ml-4" id="btn_participar">
                                                Guardar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
                        //$btn = '<a data-toggle="tooltip" title="Cambiar a usado" onclick="cambiarEstadoCupon(this)" class="d-inline-block btn-tabla btn-usado" attr-uso="0" attr-id="' . $row->id . '"><span class="badge p-2 fd-danger txt-danger"><span class="icono icon-close"></span></span></a>';
                    } else {
                        $btn = '<a data-toggle="tooltip" title="Un cupon usado no se puede modificar" class="d-inline-block btn-tabla btn-usado" attr-uso="1" attr-id="' . $row->id . '"><span attr-uso="1" class="badge p-2 fd-success txt-success"><span class="icono icon-check"></span></span></a>';
                        //$btn = '<a data-toggle="tooltip" title="Cambiar a no usado" onclick="cambiarEstadoCupon(this)" class="d-inline-block btn-tabla btn-usado" attr-uso="1" attr-id="' . $row->id . '"><span class="badge p-2 fd-success txt-success"><span class="icono icon-check"></span></span></a>';                        
                    }
                    return $btn;
                })
                ->addColumn('gasto', function ($row) {
                    //Log::info($row->usado);
                    if ($row->usado == 0) {
                        $btn = '<span attr-id="' . $row->id . '" class="gasto d-block pb-2"></span>';
                    } else {
                        $btn = '<span attr-id="' . $row->id . '" class="gasto d-block pb-2">' . $row->gasto . '€</span>';
                    }
                    return $btn;
                })
                ->addColumn('fecha', function ($row) {
                    //Log::info($row->usado);
                    if ($row->usado == 0) {
                        $btn = '<span attr-id="' . $row->id . '" class="fecha_uso d-block pb-2"></span><span class="hora_uso d-block text-muted"></span>';
                    } else {
                        $fecha = Carbon::parse($row->updated_at);
                        $btn = '<span attr-id="' . $row->id . '" class="fecha_uso d-block pb-2">' . $fecha->translatedFormat('d/m/Y') . '</span><span class="hora_uso d-block text-muted">' . $fecha->format('H:i:s') . '</span>';
                    }
                    return $btn;
                })
                ->rawColumns(['cliente', 'tipo', 'descuento', 'local_origen', 'usado', 'gasto', 'fecha'])
                ->make(true);
        }
        return view('dashboard');
    }

    public function graficas()
    {
        $dataBarUsuarios = $this->cargarBarUsuarios(now(), now()->subDays(15), null);
        $dataBarClientes = $this->cargarBarClientes(now(), now()->subDays(15), null);
        $dataPieCupones = $this->cargarPieCupones(now(), now()->subDays(90), null);
        Log::info($dataBarUsuarios);
        return view('graficos', compact('dataBarUsuarios', 'dataBarClientes', 'dataPieCupones'));
    }

    public function actualizarBarUsuarios(Request $request)
    {
        $mes = $request->mes;
        Log::info('mes:' . $mes);
        Log::info('mes:' . Carbon::now()->month);
        if ($mes == 0) {
            $data = $this->cargarBarUsuarios(now(), now()->subDays(15), null);
        } else {
            $data = $this->cargarBarUsuarios(null, null, $mes);
        }
        return response()->json([
            'labels' => $data['labels'],
            'data' => $data['data']
        ]);
    }

    public function actualizarBarClientes(Request $request)
    {
        $mes = $request->mes;
        Log::info('mes:' . $mes);
        Log::info('mes:' . Carbon::now()->month);
        if ($mes == 0) {
            $data = $this->cargarBarClientes(now(), now()->subDays(15), null);
        } else {
            $data = $this->cargarBarClientes(null, null, $mes);
        }
        return response()->json([
            'labels' => $data['labels'],
            'data' => $data['data']
        ]);
    }
    public function actualizarPieCupones(Request $request)
    {
        $mes = $request->mes;
        Log::info('mes:' . $mes);
        Log::info('mes:' . Carbon::now()->month);
        if ($mes == 0) {
            $data = $this->cargarPieCupones(now(), now()->subDays(15), null);
        } else {
            $data = $this->cargarPieCupones(null, null, $mes);
        }
        return response()->json([
            'labels' => $data['labels'],
            'data' => $data['data']
        ]);
    }

    public function cargarBarUsuarios($fechaFin, $fechaInicio, $mes)
    {
        $users = User::select(DB::raw('COUNT(*) as count'), DB::raw("DATE_FORMAT(created_at,'%d %b') as fecha"))
            ->whereYear('created_at', date('Y'))
            ->where(function ($query) use ($mes, $fechaFin, $fechaInicio) {
                if ($mes) {
                    $query->whereMonth('created_at', $mes);
                } else {
                    $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
                }
            })
            ->orderBy('created_at', 'asc')
            ->groupBy('fecha')
            ->pluck('count', 'fecha');
        Log::info($users);

        $labels = $users->keys();
        $data = $users->values();
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    public function cargarBarClientes($fechaFin, $fechaInicio, $mes)
    {
        $users = Cliente::select(DB::raw('COUNT(*) as count'), DB::raw("DATE_FORMAT(created_at,'%d %b') as fecha"))
            ->whereYear('created_at', date('Y'))
            ->where(function ($query) use ($mes, $fechaFin, $fechaInicio) {
                if ($mes) {
                    $query->whereMonth('created_at', $mes);
                } else {
                    $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
                }
            })
            ->orderBy('created_at', 'asc')
            ->groupBy('fecha')
            ->pluck('count', 'fecha');
        Log::info($users);

        $labels = $users->keys();
        $data = $users->values();
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    public function cargarPieCupones($fechaFin, $fechaInicio, $mes)
    {
        $users = Cupon::select(DB::raw('COUNT(*) as count'), 'tipo as tipo')
            ->whereYear('created_at', date('Y'))
            ->where(function ($query) use ($mes, $fechaFin, $fechaInicio) {
                if ($mes) {
                    $query->whereMonth('created_at', $mes);
                } else {
                    $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
                }
            })
            ->groupBy('tipo')
            ->pluck('count', 'tipo');
        Log::info($users);

        $labels = $users->keys();
        $data = $users->values();
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }


    public function generarQRyCupon(string $codigoLocal)
    {
        $codLocales = [
            'qa' => 'Quebec ayuntamiento',
            'pa' => 'La patita',
            'ra' => 'La Ramonoteca',
        ];

        //Crear codigo 
        $codigo = new Codigo();

        //Encriptar información
        $dataString = $codigo->id . '|' . Carbon::now()->translatedFormat('Y-m-d H:i:s') . '|' . $codigoLocal;

        $encryptedToken = Crypt::encryptString($dataString);
        $urlWithToken = url('?token=') . urlencode($encryptedToken);

        //Guardar el codigo QR en la BD
        $codigo->token = $encryptedToken;
        $codigo->local_origen = $codigoLocal;
        $codigo->save();
    }
}
