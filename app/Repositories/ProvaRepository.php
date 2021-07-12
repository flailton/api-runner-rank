<?php

namespace App\Repositories;

use App\Models\Prova;
use App\Interfaces\IProvaRepository;
use Illuminate\Support\Facades\DB;

class ProvaRepository implements IProvaRepository
{
    private Prova $prova;

    public function __construct(Prova $prova)
    {
        $this->prova = $prova;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->prova->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \App\Models\Prova
     */
    public function store($attributes)
    {
        $prova = $this->prova->create($attributes);

        return $prova;
    }

    /**
     * Display the specified resource.
     *
     * @return \App\Models\Prova
     */
    public function show($id)
    {
        return $this->prova->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Array  $attributes
     * @return \App\Models\Prova
     */
    public function update($attributes, $id)
    {
        $prova = $this->prova->find($id);
        $prova->update($attributes);

        return $prova;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prova  $prova
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $prova = $this->prova->find($id);
        $prova->corredores()->detach();
        $prova->resultados()->detach();
        $prova->delete();

        return $prova;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prova  $prova
     * @return \Illuminate\Http\Response
     */
    public function inscrever($id, $corredor_id)
    {
        $prova = $this->prova->find($id);
        $prova->corredores()->attach($corredor_id);

        return $prova;
    }

    /**
     * Display the specified resource.
     *
     * @return \App\Models\Resultado
     */
    public function findClassificacaoGeral()
    {
        $subQueryPosicao = DB::table('resultados as rAux')
            ->select(DB::raw("COUNT('X')"))
            ->where(
                DB::raw("TIMEDIFF(resultados.tempo_fim_prova, resultados.tempo_inicio_prova)"),
                '>',
                DB::raw("TIMEDIFF(rAux.tempo_fim_prova, rAux.tempo_inicio_prova)")
            )
            ->where(DB::raw("resultados.prova_id"), '=', DB::raw("rAux.prova_id"));
        $subQuery = $this->prova
            ->select(
                'provas.id as prova_id',
                DB::raw('CONCAT(CAST(tipo_provas.distancia AS NCHAR), tipo_provas.unidade_medida) as tipo_prova'),
                'corredores.id as corredor_id',
                'corredores.nome as nome_corredor',
                DB::raw('(YEAR(NOW()) - YEAR(corredores.data_nascimento)) as idade_corredor'),
                DB::raw("(({$subQueryPosicao->toSql()})+ 1) as posicao")
            )
            ->join('tipo_provas', 'tipo_provas.id', '=', 'provas.tipo_prova_id')
            ->join('corredor_prova', 'provas.id', '=', 'corredor_prova.prova_id')
            ->join('corredores', 'corredores.id', '=', 'corredor_prova.corredor_id')
            ->leftJoin('resultados', function ($join) {
                $join->on('resultados.corredor_id', '=', 'corredor_prova.corredor_id')
                    ->on('resultados.prova_id', '=', 'corredor_prova.prova_id');
            });
        $query = DB::table(DB::raw('(' . $subQuery->toSql() . ') as tb'))
            ->select(
                'tb.prova_id',
                'tb.tipo_prova',
                'tb.corredor_id',
                'tb.nome_corredor',
                'tb.idade_corredor',
                'tb.posicao'
            )
            ->orderBy('tb.prova_id', 'asc')
            ->orderBy('tb.posicao', 'asc');
        return $query->get();
    }

    /**
     * Display the specified resource.
     *
     * @return \App\Models\Resultado
     */
    public function findClassificacaoPorIdade()
    {
        $subQueryPosicao = DB::table('resultados as rAux')
            ->select(DB::raw("COUNT('X')"))
            ->join('corredores as cAux', 'cAux.id', 'rAux.corredor_id')
            ->where(
                DB::raw("TIMEDIFF(resultados.tempo_fim_prova, resultados.tempo_inicio_prova)"),
                '>',
                DB::raw("TIMEDIFF(rAux.tempo_fim_prova, rAux.tempo_inicio_prova)")
            )
            ->where(DB::raw("resultados.prova_id"), '=', DB::raw("rAux.prova_id"))
            ->whereRaw(DB::raw("CASE 
                                                    WHEN (YEAR(NOW()) - YEAR(corredores.data_nascimento)) BETWEEN 18 AND 24 THEN
                                                        (YEAR(NOW()) - YEAR(cAux.data_nascimento)) BETWEEN 18 AND 24
                                                    WHEN (YEAR(NOW()) - YEAR(corredores.data_nascimento)) BETWEEN 25 AND 34 THEN
                                                        (YEAR(NOW()) - YEAR(cAux.data_nascimento)) BETWEEN 25 AND 34
                                                    WHEN (YEAR(NOW()) - YEAR(corredores.data_nascimento)) BETWEEN 35 AND 44 THEN
                                                        (YEAR(NOW()) - YEAR(cAux.data_nascimento)) BETWEEN 35 AND 44
                                                    WHEN (YEAR(NOW()) - YEAR(corredores.data_nascimento)) BETWEEN 45 AND 54 THEN
                                                        (YEAR(NOW()) - YEAR(cAux.data_nascimento)) BETWEEN 45 AND 54
                                                    WHEN (YEAR(NOW()) - YEAR(corredores.data_nascimento)) >= 55 THEN
                                                        (YEAR(NOW()) - YEAR(cAux.data_nascimento)) >= 55
                                                 END  "));
        $subQuery = $this->prova
            ->select(
                'provas.id as prova_id',
                DB::raw('CONCAT(CAST(tipo_provas.distancia AS NCHAR), tipo_provas.unidade_medida) as tipo_prova'),
                'corredores.id as corredor_id',
                'corredores.nome as nome_corredor',
                DB::raw('(YEAR(NOW()) - YEAR(corredores.data_nascimento)) as idade_corredor'),
                DB::raw("(({$subQueryPosicao->toSql()})+ 1) as posicao"),
                DB::raw("CASE 
                                        WHEN (YEAR(NOW()) - YEAR(corredores.data_nascimento)) BETWEEN 18 AND 24 THEN
                                            '18 - 25 anos'
                                        WHEN (YEAR(NOW()) - YEAR(corredores.data_nascimento)) BETWEEN 25 AND 34  THEN
                                            '25 - 35 anos'
                                        WHEN (YEAR(NOW()) - YEAR(corredores.data_nascimento)) BETWEEN 35 AND 44 THEN
                                            '35 - 45 anos'
                                        WHEN (YEAR(NOW()) - YEAR(corredores.data_nascimento)) BETWEEN 45 AND 54 THEN
                                            '45 - 55 anos'
                                        WHEN (YEAR(NOW()) - YEAR(corredores.data_nascimento)) >= 55 THEN
                                            '55+'
                                     END as faixa_etaria")
            )
            ->join('tipo_provas', 'tipo_provas.id', '=', 'provas.tipo_prova_id')
            ->join('corredor_prova', 'provas.id', '=', 'corredor_prova.prova_id')
            ->join('corredores', 'corredores.id', '=', 'corredor_prova.corredor_id')
            ->leftJoin('resultados', function ($join) {
                $join->on('resultados.corredor_id', '=', 'corredor_prova.corredor_id')
                    ->on('resultados.prova_id', '=', 'corredor_prova.prova_id');
            });
        $query = DB::table(DB::raw('(' . $subQuery->toSql() . ') as tb'))
            ->select(
                'tb.prova_id',
                'tb.tipo_prova',
                'tb.corredor_id',
                'tb.nome_corredor',
                'tb.idade_corredor',
                'tb.posicao',
                'tb.faixa_etaria'
            )
            ->orderBy('tb.prova_id', 'asc')
            ->orderBy('tb.faixa_etaria', 'asc')
            ->orderBy('tb.posicao', 'asc');
        return $query->get();
    }
}
