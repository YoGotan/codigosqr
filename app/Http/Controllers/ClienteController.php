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
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        // $data = Cliente::with('codigos.cupon')->get();
        // dd($data);
        if ($request->ajax()) {
            $data = Cliente::with('codigos.cupon')->get();
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('cliente', function ($row) {
                    $btn = '<a href="' .  route('cliente.mostrar', ['cliente' => $row->id]) . '" class="btn-tabla btn-cliente" attr-nombre="' . $row->nombre . ' ' . $row->apellidos .'" attr-id="' . $row->id . '">' . $row->nombre . ' ' . $row->apellidos .'</a>';
                    return $btn;
                })
                ->addColumn('fecha_nacimiento', function ($row) {
                    if ($row->fecha_nacimiento == '') {
                        $btn = '';
                    } else {
                        $hoy = Carbon::now()->translatedFormat('d/m');
                        $fecha = Carbon::parse($row->fecha_nacimiento);
                        $cumple = $fecha->translatedFormat('d/m');
                        Log::info($hoy);
                        Log::info($cumple);
                        $btn = '<span ' . ($cumple == $hoy ? 'data-toggle="tooltip" title="Hoy es su cumpleaños"' : '') . ' class="' . ($cumple == $hoy ? 'badge-cumple fd-info txt-info' : '') . ' p-2">' . ($cumple == $hoy ? '<span class="pr-1 txt-info icono-cumple icon-pastel">' : '') . '</span> ' . $fecha->format('d/m/Y') . '</span>';
                    }
                    return $btn;
                })
                ->addColumn('fecha', function ($row) {
                    $fecha = Carbon::parse($row->created_at);
                    $btn = '<span class="d-block pb-2">' . $fecha->translatedFormat('d/m/Y') . '</span>' . '<span class="hora d-block text-muted">' . $fecha->translatedFormat('H:m:s') . '</span>';
                    return $btn;
                })
                ->addColumn('num_cupones', function ($row) {
                    $numCupones = count($row->codigos);
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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'fecha_nacimiento' => 'required|date',
            'token' => 'required|string',
            // 'origin' parece no usarse, se podría quitar si no es necesario
        ]);

        $cliente = Cliente::firstOrCreate(
            ['email' => $validatedData['email']],
            [
                'nombre' => $validatedData['name'],
                'apellidos' => $validatedData['last_name'],
                'telefono' => $validatedData['phone'],
                'fecha_nacimiento' => $validatedData['fecha_nacimiento']
            ]
        );
        try {
            // Desencriptar el token para obtener el ID del código QR
            $codigoId = Crypt::decryptString($validatedData['token']);
            $codigo = Codigo::findOrFail($codigoId);
        } catch (\Exception $e) {
            Log::error("Error al desencriptar el token: {$e->getMessage()}");
            // Aquí deberías manejar el error adecuadamente
            return view('errores.error', ['mensaje' => 'Token inválido.']);
        }

        // Crear un cupón asociado con el código
        $cupon = $this->generarCupon($validatedData['token']);

        // Asociar el cliente con el código y marcarlo como usado
        $codigo->id_cliente = $cliente->id;
        $codigo->usado = 1;
        $codigo->save();

        // Enviar el email al cliente
        $this->enviarEmailCupon($cliente, $cupon);

        return view('gracias', compact('cliente', 'cupon'));
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
    public function show(Int $id)
    {
        $cliente = Cliente::with('codigos.cupon')->find($id);
        // dd($cliente);
        // $codigos = Codigo::with('codigos')->where('id_cliente', $cliente->id)->get();
        // $cupones = Cupon::select('id', 'cadena', 'tipo', 'porcentaje_descuento', 'cantidad_descuento', 'producto_regalo', 'local_origen', 'local_destino', 'usado', 'gasto')->where('id_cliente', $cliente->id)->get();
        $fecha = Carbon::parse($cliente->fecha_nacimiento);
        $cumple = $fecha->translatedFormat('d/m/Y');
        $gasto = 0;
        foreach ($cliente->codigos as $codigo) {
            $gasto += $codigo->cupon->gasto;
        }
        // return view('clientes.cliente', compact('cliente', 'codigos', 'cupones', 'cumple', 'gasto'));
        return view('clientes.cliente', compact('cliente', 'gasto'));
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
        $codigo = Codigo::where('token', $token)->first();;
        if ($codigo) {
            // Verificar si el cupón ha sido usado
            if ($codigo->usado) {
                return [
                    'valido' => false,
                    'mensaje' => 'El código QR ya ha sido usado'
                ];
            }

            // Verificar si el cupón está caducado
            $fechaCreacion = $codigo->created_at;
            $fechaLimite = $fechaCreacion->addDays(2); // Añade 2 días a la fecha de creación
            $fechaActual = now();  // Fecha actual

            if ($fechaActual->greaterThan($fechaLimite)) {
                return [
                    'valido' => false,
                    'mensaje' => 'El código QR ha caducado'
                ];
            }

            // Si no ha sido usado y no está caducado, es válido
            return [
                'valido' => true
            ];
        }

        // Si no se encuentra el código
        return [
            'valido' => false,
            'mensaje' => 'El código QR no existe'
        ];
    }

    public function enviarEmailCupon($cliente, $cupon)
    {
        $mailData = [
            'nombre' => $cliente->nombre,
            'imagen' => $cupon->imagen,
            'local' => $cupon->codigo->local->nombre,
            'descuento' => $cupon->codigo->rangoCupon->descuento,
        ];

        Mail::to($cliente->email)->send(new CuponMail($mailData));
    }

    public function generarCupon($token)
    {
        // Desencriptar el token para obtener el ID del código QR
        $codigoId = Crypt::decryptString($token);

        // Buscar el código en la base de datos
        $codigo = Codigo::findOrFail($codigoId);

        // Obtiene el local y el rango asociado
        $local = $codigo->local;
        $rango = $codigo->rangoCupon;

        //Crear el cupon
        $cupon = new Cupon();
        $cupon->id_codigo = $codigo->id;
        // Asignar otros valores al cupón, como el descuento basado en el rango, etc.
        $cupon->usado = 0;
        $cupon->save();

        // Generar el token para el cupón
        $dataString = $cupon->id;
        $encryptedTokenCupon = Crypt::encryptString($dataString);

        // Crear el QR y almacenar la imagen
        $urlCupon = "https://example.com/ruta/cupon?token={$encryptedTokenCupon}";
        $path = 'images/cupones/cupon-' . $cupon->id . '.png';
        $imagenQR = QrCode::format('png')
            ->size(300)
            ->generate($urlCupon, public_path($path));
        $cupon->imagen = $path;
        $cupon->save();
        return $cupon;
    }
}
