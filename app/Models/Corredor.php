<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Corredor extends Model
{
    use HasFactory;

    protected $table = 'corredores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'cpf',
        'data_nascimento',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data_nascimento' => 'datetime',
    ];

    /**
     * Obtém a data de nascimento formatada.
     *
     * @param  string  $val
     * @return string
     */
    public function getDataNascimentoAttribute($val)
    {
        return date('d/m/Y', strtotime($val));
    }

    /**
     * Obtém o CPF formatado.
     *
     * @param  string  $val
     * @return string
     */
    public function getCpfAttribute($val)
    {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $val);
    }

    /**
     * Obtém provas.
     */
    public function provas()
    {
        return $this->belongsToMany(Prova::class, 'corredor_prova', 'corredor_id', 'prova_id');
    }

    /**
     * Obtém os resultados da prova.
     */
    public function resultados()
    {
        return $this->belongsToMany(Resultado::class, 'resultados', 'corredor_id', 'corredor_id');
    }
}
