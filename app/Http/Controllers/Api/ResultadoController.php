<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\IResultadoService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ResultadoController extends Controller
{
    private IResultadoService $resultadoService;

    /**
     * Display a listing of the resource.
     *
     * @param \App\Interfaces\IResultadoService $resultadoService InterfaceResultadoService
     */
    public function __construct(IResultadoService $resultadoService)
    {
        $this->resultadoService = $resultadoService;
    }

    public function index()
    {
        try {
            $response['body'] = $this->resultadoService->all();
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

            $response['body'] = $this->resultadoService->store($request->all());
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
            $response['body'] = $this->resultadoService->show($id);
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

            $response['body'] = $this->resultadoService->update($request->all(), $id);
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
            $response['body'] = $this->resultadoService->destroy($id);
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
            'corredor_id' => 'required|exists:corredores,id' . $sometimes,
            'prova_id' => 'required|exists:provas,id' . $sometimes,
            'tempo_inicio_prova' => 'required' . $sometimes,
            'tempo_fim_prova' => 'required' . $sometimes
        ];
    }

    private function messages()
    {
        return [
            'corredor_id.required' => 'O campo corredor é obrigatório!',
            'corredor_id.exists' => 'O corredor informado é inválido!',

            'prova_id.required' => 'O campo prova é obrigatório!',
            'prova_id.exists' => 'A prova informada é inválida!',

            'tempo_inicio_prova.required' => 'O campo hora de início da prova é obrigatório!',

            'tempo_fim_prova.required' => 'O campo hora de conclusão da prova é obrigatório!',
        ];
    }
}
