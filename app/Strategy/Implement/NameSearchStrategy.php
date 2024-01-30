<?php

namespace App\Strategy\Implement;

use App\Strategy\Interface\SearchStrategyInterface;
use Illuminate\Database\Eloquent\Builder;

class NameSearchStrategy implements SearchStrategyInterface
{
    public function search(Builder $query, $value): void
    {
        $query->where('name', 'like', '%'.$value.'%');
    }
}
