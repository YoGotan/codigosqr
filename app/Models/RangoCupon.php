<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RangoCupon extends Model
{
    use HasFactory;
    protected $table = 'rangos_cupon'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rango',
        'descuento',
    ];

    /**
     * Get all of the comments for the RangoCupon
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function codigos(): HasMany
    {
        return $this->hasMany(Codigo::class, 'id_rango');
    }
}
