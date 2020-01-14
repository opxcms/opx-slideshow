<?php

namespace Modules\Opx\Slideshow\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Core\Http\Controllers\APIListController;
use Modules\Opx\Slideshow\Models\Slideshow;

class ManageSlideshowListApiController extends APIListController
{
    protected $caption = 'opx_slideshow::manage.slideshow';
    protected $source = 'manage/api/module/opx_slideshow/slideshow_list/slideshows';

    protected $delete = 'manage/api/module/opx_slideshow/slideshow_actions/delete';
    protected $restore = 'manage/api/module/opx_slideshow/slideshow_actions/restore';

    protected $add = 'opx_slideshow::slideshow_add';
    protected $edit = 'opx_slideshow::slideshow_edit';

    protected $children = false;

    /**
     * Get list of users with sorting, filters and search.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function postSlideshows(Request $request): JsonResponse
    {
        $slideshows = Slideshow::withTrashed()->get();

        /** @var Collection $slideshow */
        if ($slideshows->count() > 0) {
            $slideshows->transform(function ($slideshow) {
                /** @var Slideshow $slideshow */
                return $this->makeListRecord(
                    $slideshow->getAttribute('id'),
                    $slideshow->getAttribute('name'),
                    null,
                    null,
                    [$slideshow->getAttribute('alias')],
                    true,
                    $slideshow->getAttribute('deleted_at') !== null
                );
            });
        }

        $response = ['data' => $slideshows->toArray()];

        return response()->json($response);
    }
}