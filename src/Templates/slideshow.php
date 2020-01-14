<?php

use Core\Foundation\Template\Template;
use Modules\Opx\Slideshow\OpxSlideshow;
/**
 * HELP:
 *
 * ID parameter is shorthand for defining module and field name separated by `::`.
 * [$module, $name] = explode('::', $id, 2);
 * $captionKey = "{$module}::template.section_{$name}";
 *
 * PLACEMENT is shorthand for section and group of field separated by `/`.
 * [$section, $group] = explode('/', $placement);
 *
 * PERMISSIONS is shorthand for read permission and write permission separated by `|`.
 * [$readPermission, $writePermission] = explode('|', $permissions, 2);
 */

return [
    'sections' => [
        Template::section('general'),
    ],
    'groups' => [
        Template::group('common'),
        Template::group('templates'),
        Template::group('timestamps'),
    ],
    'fields' => [

        // id
        Template::id('id', 'general/common', 'fields.id_info'),
        // name
        Template::string('name', 'general/common', '', [], '', 'required|max:100'),
        // alias
        Template::string('alias', 'general/common', '', [], '', 'required|alpha_dash|max:100'),
        // alias
        Template::string('opx_slideshow::class', 'general/common'),
        // image_size
        Template::string('opx_slideshow::image_size', 'general/common', '600'),
        // image_quality
        Template::string('opx_slideshow::image_quality', 'general/common', '65'),

        // templates
        Template::select('template', 'general/templates', 'slideshow', OpxSlideshow::getTemplatesList(), false, '', 'required', '', ['needs_reload' => true]),
        Template::select('child_template', 'general/templates', 'slide', OpxSlideshow::getTemplatesList()),
        Template::select('layout', 'general/templates', 'slideshow.blade.php', OpxSlideshow::getViewsList(), false, '', 'required'),

        // timestamps
        Template::timestampCreatedAt('general/timestamps'),
        Template::timestampUpdatedAt('general/timestamps'),
        Template::timestampDeletedAt('general/timestamps'),
    ],
];
