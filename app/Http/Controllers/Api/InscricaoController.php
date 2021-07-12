<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\IProvaService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InscricaoController extends Controller
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

    public function inscrever(Request $request)
    {
        try {
            $request->validate($this->rules(), $this->messages());

            $response['body'] = $this->provaService->inscrever($request->all());
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

    private function rules()
    {
        return [
            'prova_id' => 'required|exists:provas,id',
            'corredor_id' => 'required|exists:corredores,id'
        ];
    }

    private function messages()
    {
        return [
            'prova_id.required' => 'O campo prova é obrigatório!',
            'prova_id.exists' => 'A prova informada é inválida!',

            'corredor_id.required' => 'O campo corredor é obrigatório!',
            'corredor_id.exists' => 'O corredor informado é inválido!'
        ];
    }
}
