<?php

return [
    'items' => [
        'slideshow' => [
            'caption' => 'opx_slideshow::manage.slideshow',
            'section' => 'system/site',
            'route' => 'opx_slideshow::slideshow_list',
        ],
        'slideshow_slides' => [
            'caption' => 'opx_slideshow::manage.slides',
            'route' => 'opx_slideshow::slides_list',
            'parent' => 'slideshow',
        ],
    ],

    'routes' => [
        'opx_slideshow::slideshow_list' => [
            'route' => '/slideshow',
            'loader' => 'manage/api/module/opx_slideshow/slideshow_list',
        ],
        'opx_slideshow::slideshow_add' => [
            'route' => '/slideshow/add',
            'loader' => 'manage/api/module/opx_slideshow/slideshow_edit/add',
        ],
        'opx_slideshow::slideshow_edit' => [
            'route' => '/slideshow/edit/:id',
            'loader' => 'manage/api/module/opx_slideshow/slideshow_edit/edit',
        ],
        'opx_slideshow::slides_list' => [
            'route' => '/slideshow/slides',
            'loader' => 'manage/api/module/opx_slideshow/slide_list',
        ],
        'opx_slideshow::slides_add' => [
            'route' => '/slideshow/slides/add',
            'loader' => 'manage/api/module/opx_slideshow/slide_edit/add',
        ],
        'opx_slideshow::slides_edit' => [
            'route' => '/slideshow/slides/edit/:id',
            'loader' => 'manage/api/module/opx_slideshow/slide_edit/edit',
        ],
    ]
];