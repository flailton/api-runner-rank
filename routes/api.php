<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\Api;

Route::apiResource('corredores', Api\CorredorController::class);
Route::apiResource('provas', Api\ProvaController::class);
Route::post('inscricao', [Api\InscricaoController::class, 'inscrever']);
Route::get('classificacao_geral', [Api\ProvaController::class, 'findClassificacaoGeral']);
Route::get('classificacao_idade', [Api\ProvaController::class, 'findClassificacaoPorIdade']);
Route::apiResource('resultados', Api\ResultadoController::class);
Route::apiResource('tipo_provas', Api\TipoProvaController::class);
