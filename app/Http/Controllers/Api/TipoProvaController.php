<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\ITipoProvaService;

class TipoProvaController extends Controller
{
    private ITipoProvaService $tipoProvaService;

    /**
     * Display a listing of the resource.
     *
     * @param \App\Interfaces\ITipoProvaService $tipoProvaService InterfaceTipoProvaService
     */
    public function __construct(ITipoProvaService $tipoProvaService)
    {
        $this->tipoProvaService = $tipoProvaService;
    }

    public function index()
    {
        try {
            $response['body'] = $this->tipoProvaService->all();
            $response['status'] = (!empty($response['status']) ? $response['status'] : 200);
        } catch (\Throwable $ex) {
            $response['body']['message'] = $ex->getMessage();
            $response['status'] = 404;
        }

        return response()->json($response['body'], $response['status']);
    }

    public function show($id)
    {
        try {
            $response['body'] = $this->tipoProvaService->show($id);
            $response['status'] = (!empty($response['status']) ? $response['status'] : 200);
        } catch (\Throwable $ex) {
            $response['body']['message'] = $ex->getMessage();
            $response['status'] = 404;
        }

        return response()->json($response['body'], $response['status']);
    }
}
