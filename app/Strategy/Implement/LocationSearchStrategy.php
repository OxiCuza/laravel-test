<?php

namespace App\Strategy\Implement;

use App\Strategy\Interface\SearchStrategyInterface;
use Illuminate\Database\Eloquent\Builder;

class LocationSearchStrategy implements SearchStrategyInterface
{
    public function search(Builder $query, $value): void
    {
        $query->where('location', 'like', '%'.$value.'%');
    }
}
