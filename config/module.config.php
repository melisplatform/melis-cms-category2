<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

return array(
    'router' => array(
        'routes' => array(
        	'melis-backoffice' => array(
                'child_routes' => array(
                    'application-MelisCmsCategory2' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => 'MelisCmsCategory2',
                            'defaults' => array(
                                '__NAMESPACE__' => 'MelisCmsCategory2\Controller',
                                'controller'    => 'Index',
                                'action'        => '',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'default' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/[:controller[/:action]]',
                                    'constraints' => array(
                                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),    
        	),
        ),
    ),
    'translator' => array(
        'locale' => 'en_EN',
    ),
    'service_manager' => array(
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        'factories' => array(
            //db tables
            'MelisCmsCategory2Table' => 'MelisCmsCategory2\Model\Tables\Factory\MelisCmsCategory2TableFactory',
            'MelisCmsCategory2TransTable' => 'MelisCmsCategory2\Model\Tables\Factory\MelisCmsCategory2TransTableFactory',
            'MelisCmsCategory2SitesTable' => 'MelisCmsCategory2\Model\Tables\Factory\MelisCmsCategory2SitesTableFactory',
            'MelisCmsCategory2MediaTable' => 'MelisCmsCategory2\Model\Tables\Factory\MelisCmsCategory2MediaTableFactory',

            //Service
            'MelisCmsCategory2Service' => 'MelisCmsCategory2\Service\Factory\MelisCmsCategoryServiceFactory',
            'MelisCmsCategory2MediaService' => 'MelisCmsCategory2\Service\Factory\MelisCmsCategoryMediaServiceFactory',
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'MelisCmsCategory2\Controller\MelisCmsCategoryList' => 'MelisCmsCategory2\Controller\MelisCmsCategoryListController',
            'MelisCmsCategory2\Controller\MelisCmsCategory'     => 'MelisCmsCategory2\Controller\MelisCmsCategoryController',
            'MelisCmsCategory2\Controller\MelisCmsCategoryDocument'     => 'MelisCmsCategory2\Controller\MelisCmsCategoryDocumentController',
            'MelisCmsCategory2\Controller\MelisCmsCategoryMedia'     => 'MelisCmsCategory2\Controller\MelisCmsCategoryMediaController',
        ),
        'factories' => [
         //   'MelisCmsCategory2\Controller\MelisCmsCategoryMedia' => MelisCmsCategory2\Controller\Factory\MelisCmsCategoryMediaControllerFactory::class
        ]

    ),
    'controller_plugins' => array(
        'invokables' => array(

        )
    ),
    'form_elements' => array(
        'factories' => array(

        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            //'renderTreeRec' => 'MelisCmsCategory2\View\Helper\Factory\RenderRecTreeHelperFactory',
        ),
        'factories' => array(
            'renderTreeRec' => 'MelisCmsCategory2\View\Helper\Factory\RenderRecTreeHelperFactory',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_map' => array(

        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);