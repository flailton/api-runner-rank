<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\ICorredorService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CorredorController extends Controller
{
    private ICorredorService $corredorService;

    /**
     * Display a listing of the resource.
     *
     * @param \App\Interfaces\ICorredorService $corredorService InterfaceCorredorService
     */
    public function __construct(ICorredorService $corredorService)
    {
        $this->corredorService = $corredorService;
    }

    public function index()
    {
        try {
            $response['body'] = $this->corredorService->all();
            $response['status'] = (!empty($response['status']) ? $response['status'] : 200);
        } catch (\Throwable $ex) {
            $response['body']['message'] = $ex->getMessage();
            $response['status'] = 404;
        }


        return response()->json($response['body'], $response['status']);
    }

    public function store(Request $request)
    {
        try {
            $request->validate($this->rules(), $this->messages());

            $response['body'] = $this->corredorService->store($request->all());
            $response['status'] = (!empty($response['status']) ? $response['status'] : 201);
        } catch (\Throwable $ex) {
            $response['body']['message'] = $ex->getMessage();
            if ($ex instanceof ValidationException) {
                $response['body']['errors'] = $ex->errors();
            }
            $response['status'] = 404;
        }

        return response()->json($response['body'], $response['status']);
    }

    public function show($id)
    {
        try {
            $response['body'] = $this->corredorService->show($id);
            $response['status'] = (!empty($response['status']) ? $response['status'] : 200);
        } catch (\Throwable $ex) {
            $response['body']['message'] = $ex->getMessage();
            $response['status'] = 404;
        }

        return response()->json($response['body'], $response['status']);
    }

    public function update(Request $request, $id)
    {
        try {
            $sometimes = '';
            if ($request->method() === 'PATCH') {
                $sometimes = '|sometimes';
            }

            $request->validate($this->rules($id, $sometimes), $this->messages());

            $response['body'] = $this->corredorService->update($request->all(), $id);
            $response['status'] = (!empty($response['status']) ? $response['status'] : 200);
        } catch (\Throwable $ex) {
            $response['body']['message'] = $ex->getMessage();
            if ($ex instanceof ValidationException) {
                $response['body']['errors'] = $ex->errors();
            }
            $response['status'] = 404;
        }

        return response()->json($response['body'], $response['status']);
    }

    public function destroy($id)
    {
        try {
            $response['body'] = $this->corredorService->destroy($id);
            $response['status'] = (!empty($response['status']) ? $response['status'] : 200);
        } catch (\Throwable $ex) {
            $response['body']['message'] = $ex->getMessage();
            $response['status'] = 404;
        }

        return response()->json($response['body'], $response['status']);
    }

    private function rules($id = '', $sometimes = '')
    {
        return [
            'nome' => 'required|min:2' . $sometimes,
            'cpf' => 'required|min:11|max:11|unique:corredores,cpf,' . $id . $sometimes,
            'data_nascimento' => 'required|before_or_equal:' . date("Y-m-d", strtotime("-18 year")) . $sometimes
        ];
    }

    private function messages()
    {
        return [
            'nome.required' => 'O campo nome é obrigatório!',
            'nome.min' => 'O nome deve ter 2 caracteres no mínimo!',

            'cpf.required' => 'O campo cpf é obrigatório!',
            'cpf.min' => 'O cpf deve ter 11 dígitos!',
            'cpf.max' => 'O cpf deve ter 11 dígitos!',
            'cpf.unique' => 'O cpf informado já está cadastrado!',

            'data_nascimento.required' => 'O campo data de nascimento é obrigatório!',
            'data_nascimento.before_or_equal' => 'Não é permitida a inscrição de menores de idade!'
        ];
    }
}
