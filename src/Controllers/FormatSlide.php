<?php

namespace Modules\Opx\Slideshow\Controllers;

use Modules\Opx\Slideshow\Models\Slide;
use Modules\Opx\Slideshow\Models\Slideshow;

trait FormatSlide
{
    /**
     * Format slide to list item.
     *
     * @param Slide $slide
     *
     * @return  array
     */
    protected function formatSlide(Slide $slide): array
    {
        $props = [];

        if ($slide->getAttribute('order')) {
            $props[] = "order: {$slide->getAttribute('order')}";
        }

        if ($slide->getAttribute('publish_start') !== null) {
            $props[] = trans('manage.publish_start') . ': ';
            $props[] = 'datetime:' . $slide->getAttribute('publish_start')->toIso8601String();
        }

        if ($slide->getAttribute('publish_end') !== null) {
            $props[] = trans('manage.publish_end') . ': ';
            $props[] = 'datetime:' . $slide->getAttribute('publish_end')->toIso8601String();
        }

        $slide->loadMissing('slideshow');
        /** @var Slideshow $slideshow */
        $slideshow = $slide->getRelation('slideshow');

        return [
            'id' => $slide->getAttribute('id'),
            'title' => $slide->getAttribute('title'),
            'subtitle' => null,
            'description' => '(' . $slideshow->getAttribute('name') . ')',
            'properties' => $props,
            'enabled' => $slide->isPublished(),
            'deleted' => $slide->getAttribute('deleted_at') !== null,
            'children_count' => 0,
        ];
    }
}