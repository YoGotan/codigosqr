<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contador extends Model
{
    use HasFactory;

    protected $table = 'contador'; // Nombre de la tabla en la base de datos

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */    

    // Define los campos que pueden ser asignados de manera masiva
    protected $fillable = ['local_id','rango_id','count', 'last_reset'];

    // Indica que `last_reset` debe ser tratada como una instancia de Carbon/DateTime
    protected $dates = ['last_reset'];
    
    
    /**
     * Incrementa el contador para un local y rango específicos.
     * Resetear si es un nuevo día.
     */
    public static function incrementar($localId, $rangoId) {
        $today = now()->startOfDay();

        $contador = self::firstOrNew(
            ['local_id' => $localId, 'rango_id' => $rangoId],
            ['last_reset' => $today]
        );

        // Check if last reset was before today
        if ($contador->last_reset < $today) {
            $contador->count = 0;
            $contador->last_reset = $today;
        }

        $contador->count++;
        $contador->save();

        return $contador->count;
    }
}
