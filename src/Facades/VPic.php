<?php
namespace MarcinJean\LaravelVPic\Facades;

use Illuminate\Support\Facades\Facade;

class VPic extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'vpic';
    }
}
