<?php

namespace App\Interfaces;

use App\Interfaces\IService;

interface ICorredorService extends IService
{
    public function store(array $attributes);

    public function update(array $attributes, int $id);

    public function destroy(int $id);

    public function validarProvaByDate(int $id, $date);

    public function validarResultadoByProvaId(int $id, int $prova_id);
}
