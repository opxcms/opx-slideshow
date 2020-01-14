<?php

namespace Modules\Opx\Slideshow;

use Illuminate\Support\Facades\Facade;

/**
 * @method  static string getTemplateFileName(string $name)
 * @method  static array getTemplatesList()
 * @method  static array getViewsList()
 * @method  static mixed make(string $alias)
 * @method  static mixed view($view)
 */
class OpxSlideshow extends Facade
{
    /**
     * Get the registered name of the component.
     *
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'opx_slideshow';
    }
}
