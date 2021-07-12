<?php

namespace App\Interfaces;

use App\Interfaces\IService;

interface IProvaService extends IService
{
    public function store(array $attributes);

    public function update(array $attributes, int $id);

    public function destroy(int $id);

    public function payload($prova);

    public function validarCorredorById(int $id, int $corredor_id);

    public function inscrever(array $attributes);

    public function findClassificacaoGeral();

    public function findClassificacaoPorIdade();
}
