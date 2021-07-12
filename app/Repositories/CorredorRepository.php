<?php

namespace App\Repositories;

use App\Models\Corredor;
use App\Interfaces\ICorredorRepository;

class CorredorRepository implements ICorredorRepository
{
    private Corredor $corredor;

    public function __construct(Corredor $corredor)
    {
        $this->corredor = $corredor;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->corredor->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \App\Models\Corredor
     */
    public function store($attributes)
    {
        $corredor = $this->corredor->create($attributes);

        return $corredor;
    }

    /**
     * Display the specified resource.
     *
     * @return \App\Models\Corredor
     */
    public function show($id)
    {
        return $this->corredor->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Array  $attributes
     * @return \App\Models\Corredor
     */
    public function update($attributes, $id)
    {
        $corredor = $this->corredor->find($id);
        $corredor->update($attributes);

        return $corredor;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Corredor  $corredor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $corredor = $this->corredor->find($id);
        $corredor->provas()->detach();
        $corredor->resultados()->detach();
        $corredor->delete();

        return $corredor;
    }
}
