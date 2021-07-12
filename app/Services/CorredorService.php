<?php

namespace App\Services;

use App\Interfaces\ICorredorRepository;
use App\Interfaces\ICorredorService;
use Illuminate\Support\Facades\DB;
use Exception;

class CorredorService implements ICorredorService
{
    private ICorredorRepository $corredorRepository;

    public function __construct(
        ICorredorRepository $corredorRepository
    ) {
        $this->corredorRepository = $corredorRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return $this->corredorRepository->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  array  $attributes
     * @return array  $corredor
     */
    public function store(array $data)
    {
        try {
            DB::beginTransaction();
            
            $data_nascimento = str_replace("/", "-", $data["data_nascimento"]);
            if(!strtotime($data_nascimento)){
                throw new Exception('A Data de Nascimento está em um formato inválido!');
            }
            $data['data_nascimento'] = date('Y-m-d', strtotime($data_nascimento));

            $data['cpf'] = preg_replace( '/[^0-9]/', '', $data["cpf"]);

            if (strlen($data['cpf']) !== 11) {
                throw new Exception('O CPF está incompleto!');
            }

            if (empty($response = $this->corredorRepository->store($data))) {
                throw new Exception('Falha ao cadastrar o corredor!');
            }

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
     * @param  \App\Models\Corredor  $corredor
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        if (empty($corredor = $this->corredorRepository->show($id))) {
            throw new Exception('O corredor informado não existe!');
        }
        
        return $corredor;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Array  $data
     * @return \Illuminate\Http\Response
     */
    public function update(array $data, int $id)
    {
        if (empty($this->corredorRepository->show($id))) {
            throw new Exception('O corredor informado não existe!');
        }

        try {
            DB::beginTransaction();

            if(isset($data['data_nascimento'])){
                $data_nascimento = str_replace("/", "-", $data["data_nascimento"]);
                if(!strtotime($data_nascimento)){
                    throw new Exception('A Data de Nascimento está em um formato inválido!');
                }
                $data['data_nascimento'] = date('Y-m-d', strtotime($data_nascimento));
            }

            if(isset($data['cpf'])){
                $data['cpf'] = preg_replace( '/[^0-9]/', '', $data["cpf"]);
                if (strlen($data['cpf']) !== 11) {
                    throw new Exception('O CPF do corredor é inválido!');
                }
            }

            if (empty($response = $this->corredorRepository->update($data, $id))) {
                throw new Exception('Não foi possível atualizar o corredor!');
            }
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
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if (empty($this->corredorRepository->show($id))) {
            throw new Exception('O corredor informado não existe!');
        }

        return $this->corredorRepository->destroy($id);
    }

    /**
     * Valida se o corredor está inscrito em uma outra prova na data informada.
     *
     * @param  int $id
     * @param  $date
     * @return boolean
     */
    public function validarProvaByDate(int $id, $data)
    {
        if (empty($corredor = $this->corredorRepository->show($id))) {
            throw new Exception('O corredor informado não existe!');
        }

        foreach($corredor->provas as $prova){
            $data_prova = str_replace("/", "-", $prova["data_prova"]);
            $data_validacao = str_replace("/", "-", $data);

            if(date('Y-m-d', strtotime($data_prova)) === date('Y-m-d', strtotime($data_validacao))){
                return true;
            }
        }
        
        return false;
    }

    /**
     * Valida se o corredor já possue algum resultado cadastrado na prova informada
     *
     * @param  int $id
     * @param  $date
     * @return boolean
     */
    public function validarResultadoByProvaId(int $id, int $prova_id)
    {
        $corredor = $this->corredorRepository->show($id);
        var_dump($corredor->resultados);
        die;
        foreach ($corredor->resultados as $resultado) {
            if ($resultado['prova_id'] === $prova_id) {
                return true;
            }
        }

        return false;
    }
}
