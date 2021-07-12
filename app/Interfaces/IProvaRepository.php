<?php

namespace App\Interfaces;

use App\Interfaces\IRepository;

interface IProvaRepository extends IRepository
{
    public function store(array $attributes);

    public function update(array $attributes, int $id);

    public function destroy(int $id);

    public function inscrever(int $id, int $corredor_id);

    public function findClassificacaoGeral();

    public function findClassificacaoPorIdade();
}
