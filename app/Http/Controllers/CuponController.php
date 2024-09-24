<?php

namespace App\Http\Controllers;

use App\Models\Cupon;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\DataTables;

class CuponController extends Controller
{

    public function index(Request $request)
    {
        // $data = Cupon::with(['codigo.cliente', 'codigo.local', 'codigo.rangoCupon'])->get();
        // dd($data);
        if ($request->ajax()) {
            $data = Cupon::with(['codigo.cliente', 'codigo.local', 'codigo.rangoCupon'])->get();
            return Datatables::of($data)->addIndexColumn()

                ->addColumn('cliente', function ($row) {
                    //Log::info($row->usado);
                    $btn = '<a data-toggle="tooltip" title="Ver datos del cliente" href="' .  route('cliente.mostrar', ['cliente' => $row->codigo->cliente->id]) . '" class="btn-tabla btn-cliente" attr-nombre="' . $row->codigo->cliente->nombre . ' ' . $row->codigo->cliente->apellidos . '" attr-id="' . $row->codigo->cliente->id . '">' . $row->codigo->cliente->nombre . ' ' . $row->codigo->cliente->apellidos . '</a>';
                    return $btn;
                })
                ->addColumn('imagen', function ($row) {
                    // $btn = '<div class="position-relative d-inline-block">
                    //             <img class="img_svg_qr_cupon" src="/' . $row->imagen . '" alt="QR Cupón"/>
                    //             <span class="position-absolute p-2 bg-white rounded-circle" data-toggle="modal" data-target="#modal_qr_cupon_'. $row->id .'"><span class="icono icon-zoom" data-toggle="tooltip" title="click para zoom" ></span></span>
                    //             <div class="modal fade" id="modal_qr_cupon_'. $row->id .'" tabindex="-1" role="dialog" aria-labelledby="modalQRCuponZoom" aria-hidden="true">
                    //                 <div class="modal-dialog modal-dialog-centered" role="document">
                    //                     <div class="modal-content">
                    //                         <div class="modal-header">
                    //                             <h5 class="modal-title">QR Cupón</h5>
                    //                             <button type="button" class="close" data-dismiss="modal" title="Cerrar">
                    //                             <span aria-hidden="true">&times;</span>
                    //                             </button>
                    //                         </div>
                    //                         <div class="modal-body text-center">
                    //                             <div class="d-inline-block p-3">
                    //                                 <img class="" src="' . $row->imagen . '" alt="QR Cupón"/>
                    //                             </div>
                    //                         </div>
                    //                     </div>
                    //                 </div>
                    //             </div>
                    //         </div>';
                            
                    return '<div class="position-relative d-inline-block"><img class="img_svg_qr_cupon" src="/' . $row->imagen . '" alt="QR Cupón"/><span class="position-absolute p-2 bg-white rounded-circle" data-bs-toggle="modal" data-bs-target="#exampleModal'. $row->id .'">
  <span class="icono icon-zoom" data-toggle="tooltip" title="click para zoom" ></span>
</span>

<!-- Modal -->
<div class="modal fade" id="exampleModal'. $row->id .'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">QR Cupón</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <div class="d-inline-block p-3">
                                                    <img class="" src="' . $row->imagen . '" alt="QR Cupón"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
</div></div>';
                    // return $btn;
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
                ->addColumn('usado', function ($row) {
                    //Log::info($row->usado);
                    if ($row->usado == 0) {
                        $btn = '<span data-toggle="tooltip" title="Cupón sin usar" class="badge p-2 fd-danger txt-danger"><span class="icono icon-close"></span></span>';
                    } else {
                        $btn = '<span data-toggle="tooltip" title="Cupón usado" class="badge p-2 fd-success txt-success"><span class="icono icon-check"></span></span>';
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
                ->rawColumns(['cliente', 'imagen', 'tipo', 'descuento', 'local_origen', 'usado', 'gasto', 'fecha'])
                ->make(true);
        }
        return view('cupones.index');
    }

    public function update(Request $request, $id)
    {
        $cupon = Cupon::find($id);

        if (!$cupon) {
            return response()->json(['guardado' => false, 'mensaje' => 'Cupón no encontrado.']);
        }

        // Actualizar la información del cupón
        $cupon->usado = 1;
        $cupon->gasto = $request->gasto;
        $cupon->save();

        // Devolver una respuesta JSON
        return response()->json([
            'guardado' => true,
            'cupon' => $cupon
        ]);
    }


    public function buscarQR()
    {
        return view('cupones.buscar');
    }

    public function validar(Request $request)
    {
        try {
            // Desencriptar el token para obtener el ID del cupón
            $idCupon = Crypt::decryptString($request->token);

            // Buscar el cupón por ID y comprobar si ha sido usado
            $cupon = Cupon::with('codigo.rangoCupon')->find($idCupon);
            if (!$cupon) {
                return response()->json(['valido' => false, 'mensaje' => 'Cupón no encontrado.']);
            }
            if ($cupon->usado) {
                return response()->json(['valido' => false, 'mensaje' => 'Este cupón ya ha sido utilizado.']);
            }

            // Si el cupón es válido y no ha sido usado, enviar datos del cupón
            return response()->json([
                'valido' => true,
                'mensaje' => 'Cupón válido. Por favor, introduce el gasto.',
                'cupon' => $cupon
            ]);
        } catch (\Exception $e) {
            return response()->json(['valido' => false, 'mensaje' => 'Error al procesar el token.']);
        }
    }
}
