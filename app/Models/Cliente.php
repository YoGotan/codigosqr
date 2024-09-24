<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;
    protected $table = 'clientes'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'apellidos',
        'telefono',
        'token',
        'fecha_nacimiento',
        'email',

    ];
    
    /**
     * Get all of the comments for the Cliente
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function codigos(): HasMany
    {
        return $this->hasMany(Codigo::class, 'id_cliente');
    }
}
