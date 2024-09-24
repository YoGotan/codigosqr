<?php

namespace App\Http\Controllers;

use App\Mail\CuponMail;
use App\Models\Cliente;
use App\Models\Codigo;
use App\Models\Cupon;
use Carbon\Carbon;
use DateTime;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use function PHPUnit\Framework\isNull;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Cliente::select('id', DB::raw("CONCAT(nombre,' ',apellidos) as nombre"), 'telefono', 'email', 'fecha_nacimiento', 'created_at');
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('cliente', function ($row) {
                    $btn = '<a href="' .  route('cliente.mostrar', ['cliente' => $row->id]) . '" class="btn-tabla btn-cliente" attr-nombre="' . $row->nombre . '" attr-id="' . $row->id . '">' . $row->nombre . '</a>';
                    return $btn;
                })
                ->addColumn('fecha_nacimiento', function ($row) {
                    if ($row->fecha_nacimiento == '') {
                        $btn = '';
                    } else {
                        $hoy = today()->format('d/m');
                        $fecha = new DateTime($row->fecha_nacimiento);
                        $cumple = $fecha->format('d/m');
                        Log::info($hoy);
                        Log::info($cumple);
                        $btn = '<span ' . ($cumple == $hoy ? 'data-toggle="tooltip" title="Hoy es su cumpleaños"' : '') . ' class="' . ($cumple == $hoy ? 'badge-cumple fd-info txt-info' : '') . ' p-2">' . ($cumple == $hoy ? '<span class="pr-1 txt-info icono-cumple icon-pastel">' : '') . '</span> ' . $fecha->format('d/m/Y') . '</span>';
                    }
                    return $btn;
                })
                ->addColumn('fecha', function ($row) {
                    $fecha = new DateTime($row->created_at);
                    $btn = '<span class="d-block pb-2">' . $fecha->format('d/m/Y') . '</span>' . '<span class="hora d-block text-muted">' . $fecha->format('H:m:s') . '</span>';
                    return $btn;
                })
                ->addColumn('num_cupones', function ($row) {
                    $numCupones = $row->numCupones();
                    return $numCupones;
                })
                ->addColumn('ver', function ($row) {
                    $btn = '<a data-toggle="tooltip" title="Ver datos cliente" href="' .  route('cliente.mostrar', ['cliente' => $row->id]) . '" class="d-inline-block"><span class="badge editar p-2 fd-muted txt-muted"><span class="icono icon-buscar"></span></span></a>';
                    return $btn;
                })
                ->rawColumns(['cliente', 'fecha_nacimiento', 'fecha', 'num_cupones', 'ver'])
                ->make(true);
        }
        return view('clientes.ver');
    }

    /**
     * 
     */
    public function participar(Request $request)
    {
        Log::info($request['token']);
        if (isset($request['token'])) {
            $token = $this->validarToken($request['token']);
            if ($token['valido']) {
                return view('welcome');
            }
            return view('errores.error', ['mensaje' => $token['mensaje']]);
        }
        return view('errores.error', ['mensaje' => 'Escanea un código QR para participar']);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $datos = $request;
        $cliente = new Cliente;
        $cliente->nombre = $request['name'];
        $cliente->apellidos = $request['last_name'];
        $cliente->email = $request['email'];
        $cliente->telefono = $request['phone'];
        $cliente->fecha_nacimiento = $request['fecha_nacimiento'];
        $token = $request['token'];
        $local_origen = $request['origin'];
        $fecha_nacimiento = $request['fecha_nacimiento'];
        $cupon = $this->generarCupon(6, $local_origen);
        Log::info($cupon);
        if ($this->store($cliente)) {
            $cupon->id_cliente = $cliente->id;
            $cupon->save();
            $this->enviarEmailCupon($cliente, $cupon);
            $codigo = Codigo::where('token', $token)->limit(1)->get()[0];
            $codigo->id_cliente = $cliente->id;
            $codigo->usado = 1;
            $codigo->save();
            return view('gracias', compact('cliente', 'cupon'));
        } else {
            $mensaje = 'El usuario ya existe';
            return view('errores.error', compact('mensaje'));
        }
        //return view('dashboard', compact('cliente','codigo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Cliente $cliente)
    {
        try {
            $cliente->save();
            return true;
        } catch (\Throwable $th) {
            Log::info($th);
            return false;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        $codigos = Codigo::select('id', 'imagen', 'token', 'local_origen', 'updated_at')->where('id_cliente', $cliente->id)->get();
        $cupones = Cupon::select('id', 'cadena', 'tipo', 'porcentaje_descuento', 'cantidad_descuento', 'producto_regalo', 'local_origen', 'local_destino', 'usado', 'gasto')->where('id_cliente', $cliente->id)->get();
        $fecha = Carbon::parse($cliente->fecha_nacimiento);
        $cumple = $fecha->translatedFormat('d/m/Y');
        $gasto = 0;
        foreach ($cupones as $cupon) {
            $gasto += $cupon->gasto;
        }
        return view('clientes.cliente', compact('cliente', 'codigos', 'cupones','cumple', 'gasto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        //
    }
    public function validarToken($token)
    {
        $codigo = Codigo::where('token', $token)->get();
        if ($codigo->count() > 0) {
            if (!$codigo[0]->usado) {
                return [
                    'valido' => true
                ];
            }
            return [
                'valido' => false,
                'mensaje' => 'El código QR ya ha sido usado'
            ];
        }
        return [
            'valido' => false,
            'mensaje' => 'El código QR no existe'
        ];
    }

    public function enviarEmailCupon($cliente, $cupon)
    {
        $mailData = [
            'nombre' => $cliente->nombre,
            'tipo' => $cupon->tipo,
            'codigo' => $cupon->cadena,
            'porcentaje' => $cupon->porcentaje_descuento,
            'cantidad' => $cupon->cantidad_descuento,
            'regalo' => $cupon->producto_regalo,
        ];

        Mail::to($cliente->email)->send(new CuponMail($mailData));
    }

    public function generarCupon($strength, $local_origen)
    {
        $cupon = new Cupon();
        $chars_permitidos = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $tipos_permitidos = [
            'descuento',
            'regalo'
        ];
        $regalos_permitidos = [
            'Pincho de tortilla',
            'Ración de patatas',
            'Caña de cerveza',

        ];
        $locales_permitidos = [
            'La Patita',
            'Ramonoteca',
            'Gastro Tortillas',
            'Quebec',
            'Lolas'
        ];
        //generar cadena codigo
        $input_length = strlen($chars_permitidos);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $chars_permitidos[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        $fecha = new DateTime();
        $periodo = $fecha->format('ym');
        $cadena_cupon = 'GQ' . $periodo . $random_string;
        $cupon->cadena = $cadena_cupon;
        //tipo de descuento
        $tipo_cupon = $tipos_permitidos[random_int(0, 1)];
        $cupon->tipo = $tipo_cupon;
        Log::info('tipo: ' . $tipo_cupon);
        //valor descuento
        if ($tipo_cupon == 'descuento') {
            Log::info('entra descuento:');
            $valor_descuento = random_int(1, 5);
            $por_val = random_int(0, 1);
            switch ($por_val) {
                case 0:
                    $cupon->porcentaje_descuento = $valor_descuento;
                    break;
                case 1:
                    $cupon->cantidad_descuento = $valor_descuento;
                    break;
                default:
                    break;
            }
        } else {
            Log::info('entra descuento:');
            $regalo = $regalos_permitidos[random_int(0, 2)];
            $cupon->producto_regalo = $regalo;
        }
        $cupon->local_origen = $local_origen;
        do {
            $local_destino = $locales_permitidos[random_int(0, 4)];
        } while (strtoupper($local_destino) == strtoupper($local_origen));
        $cupon->local_destino = $local_destino;
        $cupon->usado = 0;

        return $cupon;
    }
}
