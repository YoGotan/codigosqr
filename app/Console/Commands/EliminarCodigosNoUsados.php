<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EliminarCodigosNoUsados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'codigos:eliminar-no-usados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina todos los codigos no usados de la base de datos cada semana';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::channel('stockFicheros')->info('Iniciando el proceso de eliminación de codigos no usados...');

        // Ejecutar el query para eliminar los codigos no usados
        $cantidad = DB::table('codigos')
            ->where('usado', 0)
            ->delete();

        Log::channel('stockFicheros')->info($cantidad . ' codigos no usados han sido eliminados.');

        return 0;
    }
}
