<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cupon extends Model
{
    use HasFactory;
    protected $table = 'cupones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_codigo',
        'imagen',
        'usado',
        'gasto',
    ];

    /**
     * Get the user that owns the Cupon
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function codigo(): BelongsTo
    {
        return $this->belongsTo(Codigo::class, 'id_codigo');
    }
}
