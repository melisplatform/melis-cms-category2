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
            'MelisCmsCategory2\Controller\MelisCmsCategoryMedia'     => 'MelisCmsCategory2\Controller\MelisCmsCategoryMediaController',
            'MelisCmsCategory2\Controller\MelisCmsCategorySelect'     => 'MelisCmsCategory2\Controller\MelisCmsCategorySelectController',
        ),

    ),
    'controller_plugins' => array(
        'invokables' => array(
            // templating plugins
            'MelisCmsCategoryDisplayCategoriesPlugin' => 'MelisCmsCategory2\Controller\Plugin\MelisCmsCategoryDisplayCategoriesPlugin',
        )
    ),
    'form_elements' => array(
        'factories' => array(
            'MelisCmsCategorySelect' => 'MelisCmsCategory2\Form\Factory\MelisCmsCategorySelectFactory',

        )
    ),
    'view_helpers' => array(
        'factories' => array(
            'renderTreeRec' => 'MelisCmsCategory2\View\Helper\Factory\RenderRecTreeHelperFactory',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_map' => array(
            'MelisCmsCategory2/default' => __DIR__ . '/../view/melis-cms-category2/plugins/default.phtml',
            'MelisCmsCategory2/plugin/modal/modal-template-form'     => __DIR__ . '/../view/melis-cms-category2/plugins/modal-template-form.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    // Config Files
    'tinyMCE' => array(
        'toolTranslate' => 'MelisCmsCategory2/public/js/tinyMCE/toolTranslate.php',
    ),
);