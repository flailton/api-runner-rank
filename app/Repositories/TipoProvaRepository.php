<?php

namespace App\Repositories;

use App\Models\TipoProva;
use App\Interfaces\ITipoProvaRepository;

class TipoProvaRepository implements ITipoProvaRepository
{
    private TipoProva $tipoProva;

    public function __construct(TipoProva $tipoProva)
    {
        $this->tipoProva = $tipoProva;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->tipoProva->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \App\Models\TipoProva
     */
    public function store($attributes)
    {
        $tipoProva = $this->tipoProva->create($attributes);

        return $tipoProva;
    }

    /**
     * Display the specified resource.
     *
     * @return \App\Models\TipoProva
     */
    public function show($id)
    {
        return $this->tipoProva->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Array  $attributes
     * @return \App\Models\TipoProva
     */
    public function update($attributes, $id)
    {
        $tipoProva = $this->tipoProva->find($id);
        $tipoProva->update($attributes);

        return $tipoProva;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipoProva  $tipoProva
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tipoProva = $this->tipoProva->find($id);
        $tipoProva->delete();

        return $tipoProva;
    }
}
