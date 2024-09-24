<?php

namespace App\Http\Controllers;

use App\Models\Codigo;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class CodigoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $data = Codigo::with('cliente', 'rangoCupon', 'local', 'cupon')->get();
        // dd($data);
        if ($request->ajax()) {
            $data = Codigo::with('cliente', 'rangoCupon', 'local', 'cupon')->get();
            // $data = Codigo::select('clientes.id as id_cliente', 'codigos.id', DB::raw("CONCAT(clientes.nombre,' ',clientes.apellidos) as nombre"), 'imagen', 'token', 'local_origen', 'usado', 'codigos.updated_at')
            // ->leftjoin('clientes','codigos.id_cliente','=','clientes.id')
            // ->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('cliente', function ($row) {
                    $btn = '';
                    if ($row->cliente != NULL) {
                        $btn = '<a data-toggle="tooltip" title="Ver datos del cliente" href="' . route('cliente.mostrar', ['cliente' => $row->cliente->id]) . '" class="btn-tabla btn-cliente" attr-nombre="' . $row->cliente->nombre . ' ' . $row->cliente->apellidos. '" attr-id="' . $row->cliente->id . '">' . $row->cliente->nombre . ' ' . $row->cliente->apellidos . '</a>';
                        return $btn;
                    }
                    return $btn;
                })
                // ->addColumn('imagen', function ($row) {
                //     $btn = '<div class="position-relative d-inline-block">
                //                 <img class="img_svg_qr" src="/' . $row->imagen . '" alt="codigo QR"/>
                //                 <span data-toggle="modal" class="position-absolute p-2 bg-white rounded-circle" data-toggle="modal" data-target="#modal_qr_'. $row->id .'"><span class="icono icon-zoom" data-toggle="tooltip" title="click para zoom" ></span></span>
                //                 <div class="modal fade" id="modal_qr_'. $row->id .'" tabindex="-1" role="dialog" aria-labelledby="modalCodigoQRZoom" aria-hidden="true">
                //                     <div class="modal-dialog modal-dialog-centered" role="document">
                //                         <div class="modal-content">
                //                             <div class="modal-header">
                //                                 <h5 class="modal-title">Código Qr</h5>
                //                                 <button type="button" class="close" data-dismiss="modal" title="Cerrar">
                //                                 <span aria-hidden="true">&times;</span>
                //                                 </button>
                //                             </div>
                //                             <div class="modal-body text-center">
                //                                 <div class="d-inline-block p-3">
                //                                     <img class="" src="' . $row->imagen . '" alt="codigo QR"/>
                //                                 </div>
                //                             </div>
                //                         </div>
                //                     </div>
                //                 </div>
                //             </div>';
                //     return $btn;
                // })
                ->addColumn('local_origen', function ($row) {
                    $btn = '';
                    if ($row->local->id == 1) {
                        $btn = '<span class="p-2 badge fd-info txt-info">' . $row->local->nombre . '</span>';
                    } else if ($row->local->id == 2) {
                        $btn = '<span class="p-2 badge fd-morado txt-morado" >' . $row->local->nombre . '</span>';
                    } else if ($row->local->id == 3) {
                        $btn = '<span class="p-2 badge fd-naranja txt-naranja" >' . $row->local->nombre . '</span>';
                    } else if ($row->local->id == 4) {
                        $btn = '<span class="p-2 badge fd-danger txt-danger" >' . $row->local->nombre . '</span>';
                    } else if ($row->local->id == 5) {
                        $btn = '<span class="p-2 badge fd-success txt-success" >' . $row->local->nombre . '</span>';
                    }else {
                        $btn = '<span class="p-2 badge fd-piscina txt-piscina" >' . $row->local->nombre . '</span>';
                    }
                    return $btn;
                })
                ->addColumn('usado', function ($row) {
                    $btn = '';
                    if ($row->usado == 0) {
                        $btn = '<span data-toggle="tooltip" title="Cupón sin usar" class="badge p-2 fd-danger txt-danger"><span class="icono icon-close"></span></span>';
                    } else {
                        $btn = '<span data-toggle="tooltip" title="Cupón usado" class="badge p-2 fd-success txt-success"><span class="icono icon-check"></span></span>';
                    }
                    return $btn;
                })
                ->addColumn('cupon', function ($row) {
                    $btn = '';
                    if ($row->cupon != NULL) {
                        $btn = $row->cupon->id;
                    }
                    return $btn;
                })
                ->addColumn('fecha', function ($row) {
                    $btn = '';
                    if ($row->usado == 0) {
                        $btn = '';
                    } else {
                        $fecha = Carbon::parse($row->updated_at);
                        $btn = '<span class="d-block pb-2">' . $fecha->translatedFormat('d/m/Y') . '</span>' . '<span class="hora d-block text-muted">' . $fecha->translatedFormat('H:m:s') . '</span>';
                    }
                    return $btn;
                })
                ->rawColumns(['cliente', 'local_origen', 'usado', 'cupon', 'fecha'])
                ->make(true);
        }
        return view('codigos_qr.ver');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $codLocales = [
            'qa' => 'Quebec ayuntamiento',
            'pa' => 'La patita',
            'qb' => 'Quebec ayuntamiento',
            'ra' => 'La Ramonoteca',
        ];
        $request->validate(
            [
                'local' => 'required|doesnt_start_with:...'
            ],
            [
                'local.doesnt_start_with' => 'Debes seleccionar un local'
            ]
        );
        $origen = $request['local'];
        $codigo = new Codigo();
        $fecha = new DateTime();
        $token = Hash::make($fecha->format('Y-m-d H:i:s') . $origen);
        $codigo->token = $token;
        $codigo->local_origen = $origen;
        $codigo->usado = 0;
        $codigo->save();
        Log::info($token);
        Log::info($origen);
        $origen = Str::replace(' ', '%20', $origen);
        $id = $codigo->id;
        Log::info($id);
        $url = 'https://app.grupoquebec.madeinsantander.net/?token=' . $token . '&origen=' . $origen;
        Log::info($url);
        $url_save_qr = 'images/codigosqr/qr-' . $id . '.svg';
        $path = public_path($url_save_qr);
        $generar = true;
        $codigo->imagen = $url_save_qr;
        $codigo->save();
        return view('codigos_qr.crear', compact('url', 'path', 'generar'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Codigo  $codigo
     * @return \Illuminate\Http\Response
     */
    public function show(Codigo $codigo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Codigo  $codigo
     * @return \Illuminate\Http\Response
     */
    public function edit(Codigo $codigo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Codigo  $codigo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $codigo = Codigo::find($request->id);
        $guardado = false;
        $usado = $request->usado;
        if ($usado == 1) {
            $codigo->usado = 0;
            $usado = 0;
        } else {
            $codigo->usado = 1;
            $usado = 1;
        }
        try {
            $codigo->save();
            $guardado = true;
        } catch (\Throwable $th) {
            $guardado = false;
        }
        return response()->json([
            'id' => $request->id,
            'guardado' => $guardado,
            'usado' => $usado
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Codigo  $codigo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Codigo $codigo)
    {
        //
    }

    public function validar(Request $request)
    {
        $cadena_qr = $request->cadena_qr;
        Log::info('cadena');
        Log::info($cadena_qr);
        $url_components = parse_url($cadena_qr);
        parse_str($url_components['query'], $data);
        $token = $data['token'];
        Log::info($token);
        $mensaje = '';
        $valido = false;
        $codigo_qr = Codigo::select('usado')->where('token', $token)->limit(1)->get()[0];
        if ($codigo_qr != null) {
            if (!$codigo_qr->usado) {
                $mensaje = 'El codigo QR no se ha usado todavia';
                $valido = true;
            } else {
                $mensaje = 'El codigo QR ya se ha usado';
                $valido = true;
            }
        } else {
            $mensaje = 'El codigo QR no existe';
            $valido = false;
        }
        return response()->json([
            'mensaje' => $mensaje,
            'valido' => $valido
        ]);
    }
}
