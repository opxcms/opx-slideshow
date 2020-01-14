<?php

namespace Modules\Opx\Slideshow\Controllers;

use Core\Http\Controllers\ApiActionsController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Modules\Opx\Slideshow\Models\Slideshow;

class ManageSlideshowActionsApiController extends ApiActionsController
{
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
        return $this->deleteModels(Slideshow::class, $request->all(), '');
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
        return $this->restoreModels(Slideshow::class, $request->all(), '');
    }
}