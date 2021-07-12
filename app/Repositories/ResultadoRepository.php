<?php

namespace App\Repositories;

use App\Models\Resultado;
use App\Interfaces\IResultadoRepository;

class ResultadoRepository implements IResultadoRepository
{
    private Resultado $resultado;

    public function __construct(Resultado $resultado)
    {
        $this->resultado = $resultado;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->resultado->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \App\Models\Resultado
     */
    public function store($attributes)
    {
        $resultado = $this->resultado->create($attributes);

        return $resultado;
    }

    /**
     * Display the specified resource.
     *
     * @return \App\Models\Resultado
     */
    public function show($id)
    {
        return $this->resultado->find($id);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  Array  $attributes
     * @return \App\Models\Resultado
     */
    public function update($attributes, $id)
    {
        $resultado = $this->resultado->find($id);
        $resultado->update($attributes);

        return $resultado;
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Resultado  $resultado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resultado = $this->resultado->find($id);
        $resultado->delete();
        
        return $resultado;
    }

    /**
     * Display the specified resource.
     *
     * @return \App\Models\Resultado
     */
    public function findResultadoByProvaCorredor($attributes)
    {
        return $this->resultado
                    ->where('corredor_id', $attributes['corredor_id'])
                    ->where('prova_id', $attributes['prova_id'])->first();
    }
}
