<?php

namespace App\Interfaces;

use App\Interfaces\IService;

interface IResultadoService extends IService
{
    public function store(array $attributes);

    public function update(array $attributes, int $id);

    public function destroy(int $id);

    public function payload($resultado);
}
