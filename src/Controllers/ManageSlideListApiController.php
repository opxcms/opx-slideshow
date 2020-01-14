<?php

namespace Modules\Opx\Slideshow\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Core\Http\Controllers\APIListController;
use Modules\Opx\Slideshow\Models\Slide;
use Modules\Opx\Slideshow\Models\Slideshow;

class ManageSlideListApiController extends APIListController
{
    use FormatSlide;

    protected $caption = 'opx_slideshow::manage.slides';
    protected $source = 'manage/api/module/opx_slideshow/slide_list/slides';

    protected $enable = 'manage/api/module/opx_slideshow/slide_actions/enable';
    protected $disable = 'manage/api/module/opx_slideshow/slide_actions/disable';
    protected $delete = 'manage/api/module/opx_slideshow/slide_actions/delete';
    protected $restore = 'manage/api/module/opx_slideshow/slide_actions/restore';

    protected $add = 'opx_slideshow::slides_add';
    protected $edit = 'opx_slideshow::slides_edit';

    protected $children = false;

    /**
     * Get list of slides with sorting, filters and search.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function postSlides(Request $request): JsonResponse
    {
        $scope = $request->input('scope');

        $slides = Slide::query()
            ->orderBy('order')
            ->when($scope !== null, static function (Builder $query) use ($scope) {
                $query->where('slideshow_id', $scope);
            })
            ->with('slideshow')
            ->withTrashed()
            ->get();

        /** @var Collection $slides */
        if ($slides->count() > 0) {
            $slides->transform(function ($slide) {
                /** @var Slide $slide */

                return $this->formatSlide($slide);
            });
        }

        $response = ['data' => $slides->toArray()];

        return response()->json($response);
    }

    protected function makeQuickNav(): array
    {
        /** @var Collection $models */
        $slideshows = Slideshow::query()->orderBy('name')->selectRaw('id, name as caption')->get();

        return $slideshows->toArray();
    }
}