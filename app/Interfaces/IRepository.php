<?php

namespace App\Interfaces;

interface IRepository
{
    public function all();

    public function show(int $id);
}
