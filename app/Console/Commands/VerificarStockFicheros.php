<?php

namespace App\Console\Commands;

use App\Models\Codigo;
use App\Models\Local;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use App\Models\Contador;

class VerificarStockFicheros extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ficheros:verificar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica el stock de ficheros y genera más si es necesario';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::channel('stockFicheros')->info('Iniciando la verificación de stock de ficheros...');
        // $contador = Contador::firstOrCreate(['id' => 1], ['last_reset' => now()]);
        // Resetear el contador si es un nuevo día
        // if ($contador->last_reset->isToday() == false) {
        //     $contador->count = 0;
        //     $contador->last_reset = now();
        //     $contador->save();
        //     Log::channel('stockFicheros')->info("Contador reseteado.");
        // }
        
        
        $locales = Local::all();
        $numLocales = count($locales);
        $totalCupones = 10;

        $rangos = ['01', '02', '03']; // Define todos los rangos necesarios

        $path = public_path('codigosqr');
        foreach ($locales as $local) {
            foreach ($rangos as $rango) {
                $pattern = "qr-{$local->codigo}-{$rango}-*.txt";
                $ficheros = glob($path . '/' . $pattern);
                $count = count($ficheros);
                Log::channel('stockFicheros')->info("Número de ficheros en carpeta {$count}");
                if ($count < $totalCupones) {
                    Log::channel('stockFicheros')->info("Stock bajo para {$local->nombre} en rango $rango, generando ficheros...");
                    $this->generarFicheros($local, $rango, $path, $totalCupones - $count);
                }
            }
        }
        
        Log::channel('stockFicheros')->info('Verificación de stock de ficheros completada.');
    }

    private function generarFicheros($local, $rango, $path, $numFicheros)
    {
        Log::channel('stockFicheros')->info("Número de ficheros generar {$numFicheros}");
        for ($i = 0; $i < $numFicheros; $i++) {
            try {
                // $fechaActual = date('Ymd');
                // $contador->count++;
                // $contador->save();
                //$contador = $this->obtenerSiguienteNumero($path, $local->codigo, $rango, $fechaActual);
                // Formato del contador para mantener los ceros a la izquierda
                $contadorConFormato = $this->obtenerSiguienteNumero($path, $local->id, $rango);
                $nombreFichero = "qr-{$local->codigo}-{$rango}-" . date('Ymd') . "-{$contadorConFormato}.txt";
                $url = $this->generateURL($local->id, ltrim($rango, '0'));
                Log::channel('stockFicheros')->info("URL fichero:" . $path . "/" . $nombreFichero);
                if (!file_put_contents("$path/$nombreFichero", $url)) {
                    Log::channel('stockFicheros')->info("No se pudo escribir el archivo: $path/$nombreFichero");
                }
            } catch (\Exception $e) {
                // Aquí puedes manejar el error, como registrar en un log o mostrar un mensaje.
                Log::channel('stockFicheros')->error("Error: " . $e->getMessage());
            }
        }
    }
    private function obtenerSiguienteNumero($path, $local, $rango) {
        return Contador::incrementar($local, $rango);
    }

    // private function obtenerSiguienteNumero($path, $codigoLocal, $rango, $fechaActual) {
    //     $pattern = "qr-{$codigoLocal}-{$rango}-{$fechaActual}-*.txt";
    //     $ficheros = glob($path . '/' . $pattern);
    //     $contador = count($ficheros) + 1;
    
    //     // Verificar si el fichero ya existe y aumenta el contador hasta que encuentre un nombre de fichero disponible
    //     while(file_exists("$path/qr-{$codigoLocal}-{$rango}-{$fechaActual}-" . str_pad($contador, 8, '0', STR_PAD_LEFT) . ".txt")) {
    //         $contador++;
    //     }
    //     Log::channel('stockFicheros')->info("Siguiente numero:" . $contador);
    //     return $contador;
    // }

    private function generateURL($idLocal, $rango)
    {
        $codigo = new Codigo();
        $codigo->token = 'token';
        Log::channel('stockFicheros')->info("token temporal:" . $codigo->token);
        $codigo->id_local = $idLocal;
        Log::channel('stockFicheros')->info("id local:" . $idLocal);
        $codigo->usado = 0;
        Log::channel('stockFicheros')->info("usado:" . $codigo->usado);
        $codigo->id_rango = $rango;
        Log::channel('stockFicheros')->info("rango:" . $codigo->id_rango);
        $codigo->save();
        $codigoId = $codigo->id;
        Log::channel('stockFicheros')->info("id: {$codigoId}");
        $dataString = $codigoId;
        $encryptedToken = Crypt::encryptString($dataString);
        // Guardar el codigo en la BD
        $codigo->token = $encryptedToken;
        $codigo->save();
        $url = url("/?token={$encryptedToken}");
        Log::channel('stockFicheros')->info("URL:" . $url);
        return $url;
    }
}
