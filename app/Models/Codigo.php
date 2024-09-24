<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Codigo extends Model
{
    use HasFactory;
    protected $table = 'codigos'; 


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_cliente',
        'imagen',
        'token',
        'id_local',
        'id_rango',
        'usado',

    ];

    /**
     * Get the user that owns the Codigo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    /**
     * Get the user that owns the Codigo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function local(): BelongsTo
    {
        return $this->belongsTo(Local::class, 'id_local');
    }

    /**
     * Get the user that owns the Codigo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rangoCupon(): BelongsTo
    {
        return $this->belongsTo(RangoCupon::class, 'id_rango');
    }


    /**
     * Get the user associated with the Codigo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cupon(): HasOne
    {
        return $this->hasOne(Cupon::class, 'id_codigo');
    }

}
