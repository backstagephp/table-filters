<?php

namespace Backstage\TableFilters\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Backstage\TableFilters\TableFilters
 */
class TableFilters extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Backstage\TableFilters\TableFilters::class;
    }
}
