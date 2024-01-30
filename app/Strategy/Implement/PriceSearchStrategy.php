<?php

namespace App\Strategy\Implement;

use App\Strategy\Interface\SearchStrategyInterface;
use Illuminate\Database\Eloquent\Builder;

class PriceSearchStrategy implements SearchStrategyInterface
{
    public function search(Builder $query, $value): void
    {
        $query->where('price', '=', $value);
    }
}
