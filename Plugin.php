<?php namespace Pikanji\DuskTests;

use System\Classes\PluginBase;
use App;

/**
 * DuskTests Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        App::register('Laravel\Dusk\DuskServiceProvider');
    }
}
