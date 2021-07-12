<?php

namespace App\Interfaces;

use App\Interfaces\IRepository;

interface ICorredorRepository extends IRepository
{
    public function store(array $attributes);

    public function update(array $attributes, int $id);

    public function destroy(int $id);
}
