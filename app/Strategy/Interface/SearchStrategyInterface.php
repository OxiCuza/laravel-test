<?php

namespace App\Strategy\Interface;

use Illuminate\Database\Eloquent\Builder;

interface SearchStrategyInterface
{
    public function search(Builder $query, $value): void;
}
