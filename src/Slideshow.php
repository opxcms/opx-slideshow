<?php

namespace Modules\Opx\Slideshow;

use Core\Foundation\Module\BaseModule;
use JsonException;
use Modules\Opx\Slideshow\Models\Slideshow as SlideshowModel;

class Slideshow extends BaseModule
{
    /** @var string  Module name */
    protected $name = 'opx_slideshow';

    /** @var string  Module path */
    protected $path = __DIR__;

    /**
     * Render slideshow by alias.
     *
     * @param string $alias
     *
     * @return  mixed|null
     * @throws JsonException
     */
    public function make(string $alias)
    {
        /** @var SlideshowModel $slideshow */
        $slideshow = SlideshowModel::query()->where('alias', $alias)->with('slides')->first();

        if($slideshow === null) {
            return null;
        }

        return $slideshow->render();
    }
}
