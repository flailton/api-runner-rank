<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\IProvaService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProvaController extends Controller
{
    private IProvaService $provaService;

    /**
     * Display a listing of the resource.
     *
     * @param \App\Interfaces\IProvaService $provaService InterfaceProvaService
     */
    public function __construct(IProvaService $provaService)
    {
        $this->provaService = $provaService;
    }

    public function index()
    {
        try {
            $response['body'] = $this->provaService->all();
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

            $response['body'] = $this->provaService->store($request->all());
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
            $response['body'] = $this->provaService->show($id);
            $response['status'] = (!empty($response['status']) ? $response['status'] : 200);
        } catch (\Throwable $ex) {
            $response['body']['message'] = $ex->getMessage();
            $response['status'] = 404;
        }

        return response()->json($response['body'], $response['status']);
    }

    public function findClassificacaoGeral()
    {
        try {
            $response['body'] = $this->provaService->findClassificacaoGeral();
            $response['status'] = (!empty($response['status']) ? $response['status'] : 200);
        } catch (\Throwable $ex) {
            $response['body']['message'] = $ex->getMessage();
            $response['status'] = 404;
        }

        return response()->json($response['body'], $response['status']);
    }

    public function findClassificacaoPorIdade()
    {
        try {
            $response['body'] = $this->provaService->findClassificacaoPorIdade();
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

            $response['body'] = $this->provaService->update($request->all(), $id);
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
            $response['body'] = $this->provaService->destroy($id);
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
            'tipo_prova_id' => 'required|exists:tipo_provas,id' . $sometimes,
            'data_prova' => 'required' . $sometimes
            //'data_prova' => 'required|after_or_equal:' . date("Y-m-d", strtotime('+1 day')) . $sometimes
        ];
    }

    private function messages()
    {
        return [
            'tipo_prova_id.required' => 'O campo tipo de prova é obrigatório!',
            'tipo_prova_id.exists' => 'O tipo de prova informado é inválido!',

            'data_prova.required' => 'O campo data da prova é obrigatório!',
            //'data_prova.after_or_equal' => 'Só é possível cadastrar uma prova com 1 dia de antecedência!'
        ];
    }
}
