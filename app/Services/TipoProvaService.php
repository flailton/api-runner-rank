<?php

namespace App\Services;

use App\Interfaces\ITipoProvaRepository;
use App\Interfaces\ITipoProvaService;
use Illuminate\Support\Facades\DB;
use Exception;

class TipoProvaService implements ITipoProvaService
{
    private ITipoProvaRepository $tipoProvaRepository;

    public function __construct(
        ITipoProvaRepository $tipoProvaRepository
    ) {
        $this->tipoProvaRepository = $tipoProvaRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return $this->tipoProvaRepository->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipoProva  $tipoProva
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        if (empty($tipoProva = $this->tipoProvaRepository->show($id))) {
            throw new Exception('O tipo de prova informado não existe!');
        }

        return $tipoProva;
    }
}
