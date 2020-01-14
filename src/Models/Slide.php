<?php

namespace Modules\Opx\Slideshow\Models;

use Core\Traits\Model\GetContent;
use Core\Traits\Model\Publishing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Opx\Image\OpxImage;

class Slide extends Model
{
    use SoftDeletes,
        Publishing,
        GetContent;

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'publish_start', 'publish_end'];

    protected $casts = [
        'image' => 'array',
        'data' => 'array',
    ];

    /**
     * Relation to parent slideshow.
     *
     * @return  BelongsTo
     */
    public function slideshow(): BelongsTo
    {
        return $this->belongsTo(Slideshow::class);
    }

    /**
     * Cast slide as array.
     *
     * @param int $imageSize
     * @param int $imageQuality
     *
     * @return  array
     */
    public function format(int $imageSize, int $imageQuality): array
    {
        // Filter base fields
        $filter = ['invert_color', 'invert_orientation', 'link', 'link_caption', 'new_window', 'order', 'title'];
        $attributes = array_intersect_key($this->getAttributes(), array_flip($filter));

        // Format content
        $attributes['content'] = $this->getContent('content', true, 'slideshow__slide-content');

        // Convert image
        $image = $this->getAttribute('image');
        if ($image !== null && !is_array($image)) {
            try {
                $image = json_decode($image, true);
            } catch (\Exception $e) {
                $image = null;
            }
        }
        $attributes['image_src'] = $image[0]['src'] ?? null;
        $attributes['image_alt'] = $attributes[0]['title'] ?? null;
        if($attributes['image_src'] !== null) {
            $attributes['image_src'] = OpxImage::get($attributes['image_src'], $imageSize, $imageQuality);

        }
        // Convert data field
        $data = $this->getAttribute('data');
        $attributes = array_merge($attributes, $data ?? []);

        return $attributes;
    }
}
