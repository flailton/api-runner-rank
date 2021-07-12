<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resultado extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'corredor_id',
        'prova_id',
        'tempo_inicio_prova',
        'tempo_fim_prova',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Obtém o o corredor deste resultado.
     */
    public function corredor()
    {
        return $this->belongsTo(Corredor::class);
    }

    /**
     * Obtém o a prova deste resultado.
     */
    public function prova()
    {
        return $this->belongsTo(Prova::class);
    }
}
