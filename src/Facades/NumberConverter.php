<?php

namespace Jayen\NumberConverter\Facades;

use Illuminate\Support\Facades\Facade;

class NumberConverter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'number-converter';
    }
}
