<?php

namespace Database\Seeders;

use App\Models\TipoProva;
use Illuminate\Database\Seeder;

class TipoProvaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $distancias = [3, 5, 10, 21, 42];
        foreach($distancias as $distancia){
            $tipoProva = new TipoProva();
            $tipoProva->distancia = $distancia;
            $tipoProva->unidade_medida = 'km';
            $tipoProva->save();
        }
    }
}
