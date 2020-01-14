<?php

namespace Modules\Opx\Slideshow\Controllers;

use Core\Foundation\Templater\Templater;
use Core\Http\Controllers\APIFormController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Opx\Slideshow\Models\Slide;
use Modules\Opx\Slideshow\Models\Slideshow;
use Modules\Opx\Slideshow\OpxSlideshow;

class ManageSlideEditApiController extends APIFormController
{
    public $addCaption = 'opx_slideshow::manage.add_slide';
    public $editCaption = 'opx_slideshow::manage.edit_slide';
    public $create = 'manage/api/module/opx_slideshow/slide_edit/create';
    public $save = 'manage/api/module/opx_slideshow/slide_edit/save';
    public $redirect = '/slideshow/slides/edit/';

    /**
     * Make Slide add form.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function getAdd(Request $request): JsonResponse
    {
        $scopeId = $request->input('scope');
        $parentId = $request->input('slideshow_id', $scopeId);
        $initiator = $request->input('__initiator');

        // In case of template change we need to switch new template
        if ($initiator === 'template') {
            $name = $request->input('template', 'slideshow');
        } elseif ($parentId !== null) {
            // get template from the parent slideshow settings
            /** @var Slideshow $slideshow */
            $slideshow = Slideshow::query()->where('id', $parentId)->first();
            $name = $slideshow !== null ? $slideshow->getAttribute('child_template') : 'slide';
        }

        // get default template if not previously set
        $name = $name ?? 'slide';

        $template = new Templater(OpxSlideshow::getTemplateFileName($name));

        $template->fillDefaults();
        $template->setValues(['slideshow_id' => $parentId,'template' => $name]);

        return $this->responseFormComponent(0, $template, $this->addCaption, $this->create, null, true);
    }

    /**
     * Make Slideshow edit form.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function getEdit(Request $request): JsonResponse
    {
        $initiator = $request->input('__initiator');
        $name = null;
        $layout = null;

        $id = $request->input('id');

        /** @var Slide $slide */
        $slide = Slide::query()->where('id', $id)->withTrashed()->firstOrFail();

        // In case of template change we need to switch new template
        if ($initiator === 'template') {
            // get directly set template
            $name = $request->input('template', 'slide');

        } else {
            // get template assigned to page
            $name = $slide->getAttribute('template');
        }

        $template = $this->makeTemplate($slide, $name);

        return $this->responseFormComponent($id, $template, $this->editCaption, $this->save, null, true);
    }

    /**
     * Fill template with data.
     *
     * @param string $filename
     * @param Slide $slide
     *
     * @return  Templater
     */
    protected function makeTemplate(Slide $slide, $filename): Templater
    {
        $template = new Templater(OpxSlideshow::getTemplateFileName($filename));

        $template->fillValuesFromObject($slide);

        return $template;
    }

    /**
     * Create new slide.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function postCreate(Request $request): JsonResponse
    {
        if ($request->input('__reload') === true) {
            return $this->getAdd($request);
        }

        $name = $request->input('template', 'slide');

        $template = new Templater(OpxSlideshow::getTemplateFileName($name));

        $template->resolvePermissions();

        $template->fillValuesFromRequest($request);

        if (!$template->validate()) {
            return $this->responseValidationError($template->getValidationErrors());
        }

        $values = $template->getEditableValues();

        $slide = $this->updateSlideData(new Slide(), $values);

        // Refill template
        $template = $this->makeTemplate($slide, $name);

        $id = $slide->getAttribute('id');

        return $this->responseFormComponent($id, $template, $this->editCaption, $this->save, $this->redirect . $id, true);
    }

    /**
     * Save slide.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function postSave(Request $request): JsonResponse
    {
        if ($request->input('__reload') === true) {
            return $this->getEdit($request);
        }

        $id = $request->input('id');

        /** @var Slide $slide */
        $slide = Slide::query()->where('id', $id)->withTrashed()->firstOrFail();

        $name = $request->input('template', 'slide');

        $template = new Templater(OpxSlideshow::getTemplateFileName($name));

        $template->resolvePermissions();

        $template->fillValuesFromRequest($request);

        if (!$template->validate(['id' => $slide->getAttribute('id')])) {
            return $this->responseValidationError($template->getValidationErrors());
        }

        $values = $template->getEditableValues();

        $slide = $this->updateSlideData($slide, $values);

        // Refill template
        $template = $this->makeTemplate($slide, $name);

        return $this->responseFormComponent($id, $template, $this->editCaption, $this->save, null, true);
    }

    /**
     * Store data to item.
     *
     * @param Slide $slide
     * @param $values
     *
     * @return  Slide
     */
    protected function updateSlideData(Slide $slide, $values): Slide
    {
        $attributes = [
            'slideshow_id', 'order',
            'title', 'image', 'content', 'link', 'link_caption',
            'new_window', 'invert_color', 'invert_orientation',
            'published', 'publish_start', 'publish_end',
            'template',
        ];

        foreach (array_keys($values) as $entry) {
            if (strpos($entry, '_') === 0) {
                $attributes[] = $entry;
            }
        }

        $this->setAttributes($slide, $values, $attributes);

        $slide->save();

        return $slide;
    }

    /**
     * Upload image.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function postSaveImage(Request $request): JsonResponse
    {
        return $this->storeImageFromRequest($request, OpxSlideshow::getTemplateFileName('slide'));
    }

    /**
     * Upload image.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function postCreateImage(Request $request): JsonResponse
    {
        return $this->storeImageFromRequest($request, OpxSlideshow::getTemplateFileName('slide'));
    }
}