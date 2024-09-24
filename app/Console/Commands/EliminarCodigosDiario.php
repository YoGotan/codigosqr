<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class EliminarCodigosDiario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Codigos:eliminar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina todos los ficheros de codigos diariamente';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = public_path('codigosqr');  // Asegúrate de que esta es la ruta correcta
        $files = File::allFiles($path);

        foreach ($files as $file) {
            File::delete($file->getRealPath());
            Log::channel('stockFicheros')->info('Eliminado: ' . $file->getRealPath());
        }

        Log::channel('stockFicheros')->info('Todos los ficheros de codigos han sido eliminados.');
        return 0;
    }
}
