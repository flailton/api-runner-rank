<?php

namespace App\Services;

use App\Interfaces\IProvaRepository;
use App\Interfaces\IProvaService;
use App\Interfaces\ICorredorService;
use Illuminate\Support\Facades\DB;
use Exception;

class ProvaService implements IProvaService
{
    private IProvaRepository $provaRepository;
    private ICorredorService $corredorService;

    public function __construct(
        IProvaRepository $provaRepository,
        ICorredorService $corredorService
    ) {
        $this->provaRepository = $provaRepository;
        $this->corredorService = $corredorService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        $provas = $this->provaRepository->all();
        $response = [];
        foreach ($provas as $prova) {
            $response[] = $this->payload($prova);
        }
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  array $data
     * @return array $response
     */
    public function store(array $data)
    {
        try {
            DB::beginTransaction();

            $data_prova = str_replace("/", "-", $data["data_prova"]);
            if (!strtotime($data_prova)) {
                throw new Exception('A Data da Prova está em um formato inválido!');
            }
            $data['data_prova'] = date('Y-m-d', strtotime($data_prova));

            if (empty($prova = $this->provaRepository->store($data))) {
                throw new Exception('Falha ao cadastrar o prova!');
            }

            $response = $this->payload($prova);

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
     * @param  int $id
     * @return array $response
     */
    public function show(int $id)
    {
        if (empty($prova = $this->provaRepository->show($id))) {
            throw new Exception('A prova informada não existe!');
        }

        $response = $this->payload($prova);

        return $response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  array $data
     * @param  int $id
     * @return array $response
     */
    public function update(array $data, int $id)
    {
        if (empty($this->provaRepository->show($id))) {
            throw new Exception('A prova informada não existe!');
        }

        try {
            DB::beginTransaction();

            if (isset($data['data_prova'])) {
                $data_prova = str_replace("/", "-", $data["data_prova"]);
                if (!strtotime($data_prova)) {
                    throw new Exception('A Data da Prova está em um formato inválido!');
                }
                $data['data_prova'] = date('Y-m-d', strtotime($data_prova));
            }

            if (empty($prova = $this->provaRepository->update($data, $id))) {
                throw new Exception('Não foi possível atualizar o prova!');
            }

            $response = $this->payload($prova);

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
     * @param  int $id
     * @return array
     */
    public function destroy(int $id)
    {
        if (empty($this->provaRepository->show($id))) {
            throw new Exception('A prova informada não existe!');
        }

        return $this->provaRepository->destroy($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  array $data
     * @param  int $id
     * @return array $response
     */
    public function inscrever(array $data)
    {
        try {
            DB::beginTransaction();

            if (empty($prova = $this->provaRepository->show($data['prova_id']))) {
                throw new Exception('Não foi possível consultar esta prova!');
            }

            if ($this->validarCorredorById($prova['id'], $data['corredor_id'])) {
                throw new Exception('Este corredor já está inscrito nesta prova!');
            }

            if ($this->corredorService->validarProvaByDate($data['corredor_id'], $prova['data_prova'])) {
                throw new Exception('O corredor informado já está inscrito em outra prova no mesmo dia!');
            }

            if (empty($this->provaRepository->inscrever($data['prova_id'], $data['corredor_id']))) {
                throw new Exception('O corredor informado já está inscrito em outra prova no mesmo dia!');
            }

            $response = [
                'corredor' => $this->corredorService->show($data['corredor_id']),
                'prova' => $this->payload($prova)
            ];

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
     * @return boolean 
     */
    public function validarCorredorById(int $id, int $corredor_id)
    {
        $prova = $this->provaRepository->show($id);
        foreach ($prova->corredores as $corredor) {
            if ($corredor['id'] == $corredor_id) {
                return true;
            }
        }
        return false;
    }

    /**
     * Obtém o payload do serviço.
     *
     * @param  Prova  $prova
     * @return array
     */
    public function payload($prova)
    {
        return [
            'id' => $prova['id'],
            'data_prova' => $prova['data_prova'],
            'tipo_prova' => $prova->tipo_prova->distancia . $prova->tipo_prova->unidade_medida
        ];
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return array $response
     */
    public function findClassificacaoGeral()
    {
        if (empty($provas = $this->provaRepository->findClassificacaoGeral())) {
            throw new Exception('Não foi possível obter a classificação geral!');
        }

        $response = $provas;

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return array $response
     */
    public function findClassificacaoPorIdade()
    {
        if (empty($provas = $this->provaRepository->findClassificacaoPorIdade())) {
            throw new Exception('Não foi possível obter a classificação geral!');
        }

        $response = $provas;

        return $response;
    }
}
