<?php

namespace Jayen\NumberConverter\Facades;

use Illuminate\Support\Facades\Facade;

class NumberConverter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'number-converter';
    }
}
