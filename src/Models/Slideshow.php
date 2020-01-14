<?php

namespace Modules\Opx\Slideshow\Models;

use Core\Traits\Model\Publishing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Opx\Slideshow\OpxSlideshow;

class Slideshow extends Model
{
    use SoftDeletes,
        Publishing;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function slides(): HasMany
    {
        return self::addPublishingToQuery($this->hasMany(Slide::class, 'slideshow_id')->orderBy('order'));
    }

    public function render()
    {
        $layout = $this->getAttribute('layout') ?? 'slideshow';
        $layout = str_replace('.blade.php', '', $layout);

        $this->loadMissing('slides');

        return OpxSlideshow::view($layout)->with(['slideshow' => json_encode($this->format())]);
    }

    public function format(): array
    {
        $attributes = [
            'class' => $this->getAttribute('class'),
        ];

        // Convert data field
        $data = $this->getAttribute('data');

        // format slides
        $slides = [];
        $imageSize = $this->getAttribute('image_size');
        $imageQuality = $this->getAttribute('image_quality');

        foreach ($this->getRelation('slides') ?? [] as $slide) {
            /** @var Slide $slide */
            $slides[] = $slide->format($imageSize, $imageQuality);
        }

        return array_merge($attributes, $data ?? [], ['slides' => $slides]);
    }
}
