<?php

namespace Modules\Opx\Slideshow\Controllers;

use Core\Foundation\Templater\Templater;
use Core\Http\Controllers\APIFormController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Opx\Slideshow\Models\Slideshow;
use Modules\Opx\Slideshow\OpxSlideshow;

class ManageSlideshowEditApiController extends APIFormController
{
    public $addCaption = 'opx_slideshow::manage.add_slideshow';
    public $editCaption = 'opx_slideshow::manage.edit_slideshow';
    public $create = 'manage/api/module/opx_slideshow/slideshow_edit/create';
    public $save = 'manage/api/module/opx_slideshow/slideshow_edit/save';
    public $redirect = '/slideshow/edit/';

    /**
     * Make Slideshow add form.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function getAdd(Request $request): JsonResponse
    {
        // In case of template change we need to switch new template
        if ($request->input('__initiator') === 'template') {
            $name = $request->input('template', 'slideshow');
        }

        // get default template if not previously set
        $name = $name ?? 'slideshow';

        $template = new Templater(OpxSlideshow::getTemplateFileName($name));

        $template->fillDefaults();
        $template->setValues(['template' => $name]);

        return $this->responseFormComponent(0, $template, $this->addCaption, $this->create);
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

        /** @var Slideshow $slideshow */
        $slideshow = Slideshow::withTrashed()->where('id', $id)->firstOrFail();

        // In case of template change we need to switch new template
        if ($initiator === 'template') {
            // get directly set template
            $name = $request->input('template', 'slideshow');

        } else {
            // get template assigned to page
            $name = $slideshow->getAttribute('template');
        }

        $template = $this->makeTemplate($slideshow, $name . '.php');

        return $this->responseFormComponent($id, $template, $this->editCaption, $this->save);
    }

    /**
     * Fill template with data.
     *
     * @param string $filename
     * @param Slideshow $slideshow
     *
     * @return  Templater
     */
    protected function makeTemplate(Slideshow $slideshow, $filename): Templater
    {
        $template = new Templater(OpxSlideshow::getTemplateFileName($filename));

        $template->fillValuesFromObject($slideshow);

        return $template;
    }

    /**
     * Create new menu.
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

        $name = $request->input('template', 'slideshow');

        $template = new Templater(OpxSlideshow::getTemplateFileName($name));

        $template->resolvePermissions();

        $template->fillValuesFromRequest($request);

        if (!$template->validate()) {
            return $this->responseValidationError($template->getValidationErrors());
        }

        $values = $template->getEditableValues();

        $slideshow = $this->updateSlideshowData(new Slideshow(), $values);

        // Refill template
        $template = $this->makeTemplate($slideshow, $name);

        $id = $slideshow->getAttribute('id');

        return $this->responseFormComponent($id, $template, $this->editCaption, $this->save, $this->redirect . $id);
    }

    /**
     * Save menu.
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

        /** @var Slideshow $slideshow */
        $slideshow = Slideshow::withTrashed()->where('id', $id)->firstOrFail();

        $name = $request->input('template', 'slideshow');

        $template = new Templater(OpxSlideshow::getTemplateFileName($name));

        $template->resolvePermissions();

        $template->fillValuesFromRequest($request);

        if (!$template->validate(['id' => $slideshow->getAttribute('id')])) {
            return $this->responseValidationError($template->getValidationErrors());
        }

        $values = $template->getEditableValues();

        $slideshow = $this->updateSlideshowData($slideshow, $values);

        // Refill template
        $template = $this->makeTemplate($slideshow, $name);

        return $this->responseFormComponent($id, $template, $this->editCaption, $this->save);
    }

    /**
     * Store data to item.
     *
     * @param Slideshow $slideshow
     * @param $values
     *
     * @return  Slideshow
     */
    protected function updateSlideshowData(Slideshow $slideshow, $values): Slideshow
    {
        $attributes = [
            'name', 'alias', 'class', 'image_size', 'image_quality',
            'template', 'child_template', 'layout',
        ];

        foreach (array_keys($values) as $entry) {
            if (strpos($entry, '_') === 0) {
                $attributes[] = $entry;
            }
        }

        $this->setAttributes($slideshow, $values, $attributes);

        $slideshow->save();

        return $slideshow;
    }
}