<?php

namespace App\Interfaces;

interface IService
{
    public function all();

    public function show(int $id);
}
