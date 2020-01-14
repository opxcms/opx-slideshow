<?php

use Core\Foundation\Template\Template;
use Modules\Opx\Slideshow\Models\Slideshow;
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
        Template::section('opx_slideshow::slide'),
        Template::section('general'),
    ],
    'groups' => [
        Template::group('common'),
        Template::group('publication'),
        Template::group('templates'),
        Template::group('timestamps'),
    ],
    'fields' => [

        // id
        Template::id('id', 'general/common', 'fields.id_info'),
        // slideshow id
        Template::select('opx_slideshow::slideshow_id', 'general/common', '', Template::makeList(Slideshow::class, true), true, '', 'required', '', ['needs_reload' => true]),
        // order
        Template::string('order', 'general/common'),
        // templates
        Template::select('template', 'general/templates', 'slideshow', OpxSlideshow::getTemplatesList(), false, '', 'required', '', ['needs_reload' => true]),
        // publication
        Template::publicationPublished('general/publication'),
        Template::publicationPublishStart('general/publication'),
        Template::publicationPublishEnd('general/publication'),
        // timestamps
        Template::timestampCreatedAt('general/timestamps'),
        Template::timestampUpdatedAt('general/timestamps'),
        Template::timestampDeletedAt('general/timestamps'),


        // title
        Template::string('opx_slideshow::title', 'slide/', '', [], '', 'required|max:100'),
        // image
        Template::image('image', 'slide/', true, 'images', 'slide_', '', 'max:1'),
        // content
        Template::text('content', 'slide/'),
        // link
        Template::string('opx_slideshow::link', 'slide/', '', [], '', 'nullable|alpha_dash|max:100'),
        Template::string('opx_slideshow::link_caption', 'slide/', '', [], '', 'nullable|alpha_dash|max:100'),
        Template::checkbox('opx_slideshow::new_window', 'slide/'),
        Template::checkbox('opx_slideshow::invert_color', 'slide/'),
        Template::checkbox('opx_slideshow::invert_orientation', 'slide/'),
    ],
];
