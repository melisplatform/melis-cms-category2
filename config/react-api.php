<?php

/**
 * React back-office JSON API routes for the Categories tool (MelisCmsCategory2).
 *
 * Owned by the module (modularity rule) — merged into the router via Module::getConfig().
 * All routes nest under the existing `melis-backoffice > application-MelisCmsCategory2`
 * (base `/melis/MelisCmsCategory2`), so the effective prefix is
 * `/melis/MelisCmsCategory2/react-api/*`. Controller: MelisCmsCategoryReactApiController.
 */

use MelisCmsCategory2\Controller\MelisCmsCategoryReactApiController;

$ns = 'MelisCmsCategory2\Controller';

$literal = static function (string $route, string $action) use ($ns): array {
    return [
        'type'    => 'Literal',
        'options' => [
            'route'    => $route,
            'defaults' => [
                '__NAMESPACE__' => $ns,
                'controller'    => 'MelisCmsCategoryReactApi',
                'action'        => $action,
            ],
        ],
    ];
};

$segmentId = static function (string $route, string $action) use ($ns): array {
    return [
        'type'    => 'Segment',
        'options' => [
            'route'       => $route,
            'constraints' => ['id' => '[0-9]+'],
            'defaults'    => [
                '__NAMESPACE__' => $ns,
                'controller'    => 'MelisCmsCategoryReactApi',
                'action'        => $action,
            ],
        ],
    ];
};

return [
    'router' => [
        'routes' => [
            'melis-backoffice' => [
                'child_routes' => [
                    'application-MelisCmsCategory2' => [
                        'child_routes' => [
                            'react-api-tree'     => $literal('/react-api/tree', 'tree'),
                            'react-api-langs'    => $literal('/react-api/langs', 'langs'),
                            'react-api-sites'    => $literal('/react-api/sites', 'sites'),
                            'react-api-save'     => $literal('/react-api/save', 'save'),
                            'react-api-reorder'  => $literal('/react-api/reorder', 'reorder'),
                            'react-api-delete'   => $segmentId('/react-api/delete/:id', 'delete'),
                            'react-api-media-up' => $literal('/react-api/media/upload', 'mediaUpload'),
                            'react-api-media-del'=> $segmentId('/react-api/media/delete/:id', 'mediaDelete'),
                            'react-api-cat-media'=> $segmentId('/react-api/category/:id/media', 'categoryMedia'),
                            'react-api-category' => $segmentId('/react-api/category/:id', 'get'),
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'MelisCmsCategory2\Controller\MelisCmsCategoryReactApi' => MelisCmsCategoryReactApiController::class,
        ],
    ],
];
