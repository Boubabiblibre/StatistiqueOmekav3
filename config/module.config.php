<?php

namespace SitesStatis;

return [
    'controllers' => [
        'factories' => [
            'SitesStatis\Controller\Admin\Index' => Service\Controller\Admin\IndexControllerFactory::class,
        ],
    ],
    'navigation' => [
        'AdminModule' => [
            [
                'label' => 'Sites Statis',
                'route' => 'admin/Sites-Statis',
                'resource' => 'SitesStatis\Controller\Admin\Index',
            ],
        ],
    ],
    'router' => [
        'routes' => [
            'admin' => [
                'child_routes' => [
                    'Sites-Statis' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/sites-statis',
                            'defaults' => [
                                '__NAMESPACE__' => 'SitesStatis\Controller\Admin',
                                'controller' => 'Index',
                                'action' => 'Index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => dirname(__DIR__) . '/language',
                'pattern' => '%s.mo',
                'text_domain' => null,
            ],
        ],
    ],
];
