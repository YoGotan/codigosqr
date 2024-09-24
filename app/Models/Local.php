<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Local extends Model
{
    use HasFactory;

    protected $table = 'locales'; // Nombre de la tabla en la base de datos

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'codigo',
    ];

    /**
     * Get all of the comments for the Local
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function codigos(): HasMany
    {
        return $this->hasMany(Codigo::class, 'id_local');
    }
}
