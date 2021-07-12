<?php

namespace App\Services;

use App\Interfaces\IResultadoRepository;
use App\Interfaces\IResultadoService;
use App\Interfaces\IProvaService;
use App\Interfaces\ICorredorService;
use Illuminate\Support\Facades\DB;
use Exception;

class ResultadoService implements IResultadoService
{
    private IResultadoRepository $resultadoRepository;
    private IProvaService $provaService;
    private ICorredorService $corredorService;

    public function __construct(
        IResultadoRepository $resultadoRepository,
        IProvaService $provaService,
        ICorredorService $corredorService
    ) {
        $this->resultadoRepository = $resultadoRepository;
        $this->provaService = $provaService;
        $this->corredorService = $corredorService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return array $response
     */
    public function all()
    {
        $resultados = $this->resultadoRepository->all();
        $response = [];
        foreach($resultados as $resultado){
            $response[] = $this->payload($resultado);
        }

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  array  $attributes
     * @return array  $resultado
     */
    public function store(array $data)
    {
        try {
            DB::beginTransaction();
            
            if(empty($data["tempo_inicio_prova"] = $this->validarHora($data["tempo_inicio_prova"]))){
                throw new Exception('A hora de início da prova está em um formato inválido!');
            }

            if(empty($data["tempo_fim_prova"] = $this->validarHora($data["tempo_fim_prova"]))){
                throw new Exception('A hora de conclusão da prova está em um formato inválido!');
            }

            if(!$this->provaService->validarCorredorById($data["prova_id"], $data["corredor_id"])){
                throw new Exception('O corredor informado não está inscrito nesta prova!');
            }
            if(!empty($this->resultadoRepository->findResultadoByProvaCorredor($data))){
                throw new Exception('O corredor informado já possui um resultado para esta prova!');
            }
            
            
            if (empty($resultado = $this->resultadoRepository->store($data))) {
                throw new Exception('Falha ao cadastrar o resultado!');
            }

            $response = $this->payload($resultado);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $response['errors'] = $th->getMessage();
            $response['status'] = 406;
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return array $response
     */
    public function show(int $id)
    {
        if (empty($resultado = $this->resultadoRepository->show($id))) {
            throw new Exception('O resultado informado não existe!');
        }

        return $this->payload($resultado);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Array  $data
     * @param  int  $id
     * @return array $response
     */
    public function update(array $data, int $id)
    {
        if (empty($this->resultadoRepository->show($id))) {
            throw new Exception('O resultado informado não existe!');
        }

        try {
            DB::beginTransaction();

            if(isset($data['tempo_inicio_prova'])){
                if(empty($data["tempo_inicio_prova"] = $this->validarHora($data["tempo_inicio_prova"]))){
                    throw new Exception('A hora de início da prova está em um formato inválido!');
                }
            }

            if(isset($data['tempo_fim_prova'])){
                if(empty($data["tempo_fim_prova"] = $this->validarHora($data["tempo_fim_prova"]))){
                    throw new Exception('A hora de conclusão da prova está em um formato inválido!');
                }
            }

            if(isset($data["corredor_id"])){
                if(!$this->provaService->validarCorredorById($data["prova_id"], $data["corredor_id"])){
                    throw new Exception('O corredor informado não está inscrito nesta prova!');
                }
            }

            if (empty($resultado = $this->resultadoRepository->update($data, $id))) {
                throw new Exception('Não foi possível atualizar o resultado!');
            }

            $response = $this->payload($resultado);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $response['errors'] = $th->getMessage();
            $response['status'] = 406;
        }

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return array
     */
    public function destroy(int $id)
    {
        if (empty($this->resultadoRepository->show($id))) {
            throw new Exception('O resultado informado não existe!');
        }

        return $this->resultadoRepository->destroy($id);
    }

    /**
     * Obtém o payload do serviço.
     *
     * @param  Resultado  $resultado
     * @return array
     */
    public function payload($resultado)
    {
        return [
            'id' => $resultado['id'],
            'corredor' => $resultado->corredor,
            'prova' => $this->provaService->payload($resultado->prova),
            'tempo_inicio_prova' => $resultado['tempo_inicio_prova'],
            'tempo_fim_prova' => $resultado['tempo_fim_prova']
        ];
    }

    /**
     * Valida se a hora é válida.
     *
     * @param  Resultado  $resultado
     * @return array
     */
    public function validarHora($hora)
    {   
        if(empty($tempo_inicio_prova = strtotime($hora))){
            return false;
        }

        return date('H:i:s', $tempo_inicio_prova);
    }
}
