<?php

namespace Modules\Opx\Slideshow\Controllers;

use Core\Http\Controllers\ApiActionsController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Modules\Opx\Slideshow\Models\Slide;

class ManageSlideActionsApiController extends ApiActionsController
{
    use FormatSlide;

    /**
     * Delete.
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function postDelete(Request $request): JsonResponse
    {
        return $this->deleteModels(Slide::class, $request->all(), '');
    }

    /**
     * Restore.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function postRestore(Request $request): JsonResponse
    {
        return $this->restoreModels(Slide::class, $request->all(), '');
    }

    /**
     * Disable.
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function postDisable(Request $request): JsonResponse
    {
        return $this->disableModels(Slide::class, $request->all(), 'published', '');
    }

    /**
     * Enable.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function postEnable(Request $request): JsonResponse
    {
        $models = $this->getModels(Slide::class, $request->all(), true);
        $models->loadMissing('slideshow');

        $changed = [];

        if ($models->count() > 0) {
            /** @var Slide $slide */
            foreach ($models as $slide) {
                if (!$slide->isPublished()) {
                    $slide->publish();
                    $slide->save();
                    $changed[$slide->getAttribute('id')] = $this->formatSlide($slide);
                }
            }
        }

        return response()->json([
            'message' => 'success',
            'changed' => $changed,
        ]);
    }
}