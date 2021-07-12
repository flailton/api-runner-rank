<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prova extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_prova_id',
        'data_prova',
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
     * Obtém o tipo de prova que pertence a esta prova.
     */
    public function tipo_prova()
    {
        return $this->belongsTo(TipoProva::class);
    }

    /**
     * Obtém a data da prova formatada.
     *
     * @param  string  $val
     * @return string
     */
    public function getDataProvaAttribute($val)
    {
        return date('d/m/Y', strtotime($val));
    }

    /**
     * Obtém os corredores da prova.
     */
    public function corredores()
    {
        return $this->belongsToMany(Corredor::class, 'corredor_prova', 'prova_id', 'corredor_id');
    }

    /**
     * Obtém os resultados da prova.
     */
    public function resultados()
    {
        return $this->belongsToMany(Resultado::class, 'resultados', 'prova_id', 'id');
    }
}
