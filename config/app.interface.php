<?php 

return array(
    'plugins' => [
        'meliscore' => [
            'datas' => [],
            'interface' => [
                'meliscore_leftmenu' => [
                    'interface' => [
                        'meliscms_toolstree_section' => [
                            'interface' => [
                                'melis_cms_category_v2' => [
                                    'conf' => [
                                        'id' => 'melis_cms_category_v2',
                                        'name' => 'tr_melis_cms_category_v2',
                                        'melisKey' => 'melis_cms_category_v2',
                                        'icon' => 'fa-th-list',
                                    ],
                                    'interface' => [
                                        'melis_cms_category_v2_config' => [
                                            'conf' => [
                                                'type' => '/melis_cms_category_v2_config/interface/melis_cms_categories_v2'
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],

        'melis_cms_category_v2_config' => [
            'conf' => [
                'id'   => 'melis_cms_category_v2_config',
                'name' => 'tr_melis_cms_category_v2',
                'melisKey' => 'melis_cms_category_v2_config',
                'icon' => 'fa-th-list',
               'rightsDisplay' => 'none'
            ],
            'datas' => [],
            'ressources' => [
                'js' => [
                    '/MelisCmsCategory2/assets/jstree/dist/jstree.min.js',
                    '/MelisCmsCategory2/js/tools/category.tool.js',
                    '/MelisCmsCategory2/js/tools/category.media.tool.js',
                    // for selecting a category in a tree
                    '/MelisCmsCategory2/plugins/js/category.plugin.select.js',
                ],
                'css' => [
                    '/MelisCmsCategory2/assets/jstree/dist/themes/proton/style.min.css',
                    '/MelisCmsCategory2/css/categories.css',
                     // for selecting a category in a tree
                    '/MelisCmsCategory2/plugins/css/category.plugin.select.css',
                ],
                /**
                 * the "build" configuration compiles all assets into one file to make
                 * lesser requests
                 */
                'build' => [
                    // 'disable_bundle' => true,
                    // lists of assets that will be loaded in the layout
                    'css' => [
                        '/MelisCmsCategory2/build/css/bundle.css',
                    ],
                    'js' => [
                        '/MelisCmsCategory2/build/js/bundle.js',
                    ]
                ]
            ],
            'interface' => [
                'melis_cms_categories_v2' => array(
                    'conf' => array(
                        'id' => 'id_melis_cms_categories_v2',
                        'melisKey' => 'melis_cms_categories_v2',
                        'name' => 'tr_melis_cms_category_v2',
                        'icon' => 'fa-th-list',
                        'follow_regular_rendering' => false,
                        'rights_checkbox_disable' => true,
                    ),
                    'forward' => array(
                        'module' => 'MelisCmsCategory2',
                        'controller' => 'MelisCmsCategoryList',
                        'action' => 'render-categories-page',
                    ),
                    'interface' => array(
                        'melis_cms_categories_v2_list' => array(
                            'conf' => array(
                                'id' => 'id_melis_cms_categories_v2_list',
                                'melisKey' => 'melis_cms_categories_v2_list',
                                'name' => 'tr_melis_categories_list',
                            ),
                            'forward' => array(
                                'module' => 'MelisCmsCategory2',
                                'controller' => 'MelisCmsCategoryList',
                                'action' => 'render-category-list',
                            ),
                            'interface' => array(
                                'meliscms_categories_list_header' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscms_categories_list_header',
                                        'melisKey' => 'meliscms_categories_list_header',
                                        'name' => 'tr_meliscore_tool_gen_header',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCmsCategory2',
                                        'controller' => 'MelisCmsCategoryList',
                                        'action' => 'render-category-list-header',
                                    ),
                                    'interface' => array(
                                        'meliscms_categories_list_header_add_category' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscms_categories_list_header_add_category',
                                                'melisKey' => 'meliscms_categories_list_header_add_category',
                                                'name' => 'tr_melis_cms_category_v2_header_add_btn'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCmsCategory2',
                                                'controller' => 'MelisCmsCategoryList',
                                                'action' => 'render-category-list-header-add-category'
                                            )
                                        )
                                    )
                                ),
                                'meliscms_categories_list_content' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscms_categories_list_content',
                                        'melisKey' => 'meliscms_categories_list_content',
                                        'name' => 'tr_meliscore_tool_gen_content'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCmsCategory2',
                                        'controller' => 'MelisCmsCategoryList',
                                        'action' => 'render-category-list-content',
                                    ),
                                    'interface' => array(
                                        //filter area
                                        'meliscms_categories_filter_area' => [
                                            'conf' => array(
                                                'id' => 'meliscms_categories_filter_area',
                                                'melisKey' => 'meliscms_categories_filter_area',
                                                'name' => 'tr_meliscms_categories_filter_area'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCmsCategory2',
                                                'controller' => 'MelisCmsCategoryList',
                                                'action' => 'render-category-filter-area',
                                            ),
                                            'interface' => [
                                                // content
                                                'meliscms_categories_list_search_input' => array(
                                                    'conf' => array(
                                                        'id' => 'meliscms_categories_list_search_input',
                                                        'melisKey' => 'meliscms_categories_list_search_input',
                                                        'name' => 'tr_meliscms_categories_list_search_input'
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCmsCategory2',
                                                        'controller' => 'MelisCmsCategoryList',
                                                        'action' => 'render-category-list-search-input',
                                                    )
                                                ),
                                                //site filter
                                                'meliscms_categories_site_filter' => [
                                                    'conf' => array(
                                                        'id' => 'meliscms_categories_site_filter',
                                                        'melisKey' => 'meliscms_categories_site_filter',
                                                        'name' => 'tr_meliscms_categories_site_filter'
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCmsCategory2',
                                                        'controller' => 'MelisCmsCategoryList',
                                                        'action' => 'render-category-list-site-filter',
                                                    )
                                                ],
                                            ],
                                        ],
                                        //Category tree view
                                        'meliscms_categories_list_categories_tree' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscms_catergories_list_categories_tree',
                                                'melisKey' => 'meliscms_categories_list_categories_tree',
                                                'name' => 'tr_meliscms_categories_list_categories_tree',
                                                'rightsDisplay' => 'none'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCmsCategory2',
                                                'controller' => 'MelisCmsCategoryList',
                                                'action' => 'render-category-list-tree-view',
                                                'jscallback' => 'initCmsCategoryTreeView();'
                                            )
                                        )
                                    )
                                )
                            )
                        ),
                        'meliscategory_categories_category' => array(
                            'conf' => array(
                                'id' => 'id_meliscategory_categories_category',
                                'melisKey' => 'meliscategory_categories_category',
                                'name' => 'tr_meliscategory_categories_category',
                            ),
                            'forward' => array(
                                'module' => 'MelisCmsCategory2',
                                'controller' => 'MelisCmsCategory',
                                'action' => 'render-category',
                            ),
                            'interface' => array(
                                'meliscategory_categories_category_header' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscategory_categories_category_header',
                                        'melisKey' => 'meliscategory_categories_category_header',
                                        'name' => 'tr_meliscategory_categories_category_header'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCmsCategory2',
                                        'controller' => 'MelisCmsCategory',
                                        'action' => 'render-category-header',
                                    ),
                                    'interface' => array(
                                        'meliscategory_categories_category_header_save_category' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscategory_categories_category_header_save_category',
                                                'melisKey' => 'meliscategory_categories_category_header_save_category',
                                                'name' => 'tr_meliscategory_categories_category_header_save_category'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCmsCategory2',
                                                'controller' => 'MelisCmsCategory',
                                                'action' => 'render-category-header-save-category',
                                            )
                                        )
                                    )
                                ),
                                'meliscategory_categories_category_content' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscategory_categories_category_content',
                                        'melisKey' => 'meliscategory_categories_category_content',
                                        'name' => 'tr_meliscategory_categories_category_content'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCmsCategory2',
                                        'controller' => 'MelisCmsCategory',
                                        'action' => 'render-category-content',
                                    ),
                                    'interface' => array(
                                        'meliscategory_categories_category_tabs' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscategory_categories_category_tabs',
                                                'melisKey' => 'meliscategory_categories_category_tabs',
                                                'name' => 'tr_meliscategory_categories_category_tab_content'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCmsCategory2',
                                                'controller' => 'MelisCmsCategory',
                                                'action' => 'render-category-tab-content',
                                            ),
                                            'interface' => array(
                                                'meliscategory_categories_category_tab_main' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscategory_categories_category_tab_main',
                                                        'melisKey' => 'meliscategory_categories_category_tab_main',
                                                        'name' => 'tr_meliscmscategory_properties_tab',
                                                        'icon' => 'glyphicons tag'
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCmsCategory2',
                                                        'controller' => 'MelisCmsCategory',
                                                        'action' => 'render-category-tab-main',
                                                    ),
                                                    'interface' => array(
                                                        'meliscategory_categories_category_tab_main_header' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscategory_categories_category_tab_main_header',
                                                                'melisKey' => 'meliscategory_categories_category_tab_main_header',
                                                                'name' => 'tr_meliscategory_categories_category_header'
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCmsCategory2',
                                                                'controller' => 'MelisCmsCategory',
                                                                'action' => 'render-category-tab-main-header',
                                                            ),
                                                            'interface' => array(
                                                                'meliscategory_categories_category_form_status' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscategory_categories_category_form_status',
                                                                        'melisKey' => 'meliscategory_categories_category_form_status',
                                                                        'name' => 'tr_meliscategory_categories_common_label_status',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCmsCategory2',
                                                                        'controller' => 'MelisCmsCategory',
                                                                        'action' => 'render-category-form-status',
                                                                        'jscallback' => 'initCmsCategoryStatus();'
                                                                    )
                                                                )
                                                            )
                                                        ),
                                                        'meliscategory_categories_category_tab_main_content' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscategory_categories_category_tab_main_header',
                                                                'melisKey' => 'meliscategory_categories_category_tab_main_header',
                                                                'name' => 'tr_meliscategory_categories_category_content'
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCmsCategory2',
                                                                'controller' => 'MelisCmsCategory',
                                                                'action' => 'render-category-tab-main-content',
                                                            ),
                                                            'interface' => array(
                                                                'meliscategory_categories_category_tab_main_left' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscategory_categories_category_tab_main_left',
                                                                        'melisKey' => 'meliscategory_categories_category_tab_main_left',
                                                                        'name' => 'tr_melis_cms_category_media_tab_content_left'
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCmsCategory2',
                                                                        'controller' => 'MelisCmsCategory',
                                                                        'action' => 'render-category-tab-main-left',
                                                                    ),
                                                                    'interface' => array(
                                                                        'meliscategory_categories_category_form_transalations' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscategory_categories_category_form_transalations',
                                                                                'melisKey' => 'meliscategory_categories_category_form_transalations',
                                                                                'name' => 'tr_meliscategory_categories_category_form_transalations',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCmsCategory2',
                                                                                'controller' => 'MelisCmsCategory',
                                                                                'action' => 'render-category-form-translations',
                                                                            )
                                                                        )
                                                                    )
                                                                ),
                                                                'meliscategory_categories_category_tab_main_right' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscategory_categories_category_tab_main_right',
                                                                        'melisKey' => 'meliscategory_categories_category_tab_main_right',
                                                                        'name' => 'tr_melis_cms_category_media_tab_content_right'
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCmsCategory2',
                                                                        'controller' => 'MelisCmsCategory',
                                                                        'action' => 'render-category-tab-main-right',
                                                                    ),
                                                                    'interface' => array(
                                                                        'meliscategory_categories_category_form_date_validity' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscategory_categories_category_form_date_validity',
                                                                                'melisKey' => 'meliscategory_categories_category_form_date_validity',
                                                                                'name' => 'tr_meliscategory_categories_category_date_validity',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCmsCategory2',
                                                                                'controller' => 'MelisCmsCategory',
                                                                                'action' => 'render-category-form-date-validity',
                                                                            )
                                                                        ),
                                                                        'meliscategory_categories_category_form_sites' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscategory_categories_category_form_sites',
                                                                                'melisKey' => 'meliscategory_categories_category_form_sites',
                                                                                'name' => 'tr_meliscategory_categories_category_countries',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCmsCategory2',
                                                                                'controller' => 'MelisCmsCategory',
                                                                                'action' => 'render-category-form-sites',
                                                                            )
                                                                        )
                                                                    )
                                                                )
                                                            )
                                                        ),
                                                    )
                                                ),
                                                'meliscategory_category_tab_media' => array(
                                                    'conf' => array(
                                                        'id' => 'id_meliscategory_category_tab_media',
                                                        'melisKey' => 'meliscategory_category_tab_media',
                                                        'name' => 'tr_melis_cms_category_media_tab',
                                                        'icon' => 'glyphicons picture'
                                                    ),
                                                    'forward' => array(
                                                        'module' => 'MelisCmsCategory2',
                                                        'controller' => 'MelisCmsCategoryMedia',
                                                        'action' => 'render-category-tab-media',
                                                    ),
                                                    'interface' => [
                                                        'meliscategory_category_tab_media_header' => [
                                                            'conf' => array(
                                                                'id' => 'id_meliscategory_category_tab_media_header',
                                                                'melisKey' => 'meliscategory_category_tab_media_header',
                                                                'name' => 'tr_melis_cms_category_media_tab_header',
                                                                'rightsDisplay' => 'none'
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCmsCategory2',
                                                                'controller' => 'MelisCmsCategoryMedia',
                                                                'action' => 'render-category-tab-media-header',
                                                            ),
                                                        ],
                                                        'meliscategory_category_tab_media_content' => [
                                                            'conf' => array(
                                                                'id' => 'id_meliscategory_category_tab_media_content',
                                                                'melisKey' => 'meliscategory_category_tab_media_content',
                                                                'name' => 'tr_melis_cms_category_media_tab_content',
                                                            ),
                                                            'forward' => array(
                                                                'module' => 'MelisCmsCategory2',
                                                                'controller' => 'MelisCmsCategoryMedia',
                                                                'action' => 'render-category-tab-media-content',
                                                            ),
                                                            'interface' => [
                                                                'meliscategory_category_tab_media_content_left' => [
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscategory_category_tab_media_content_left',
                                                                        'melisKey' => 'meliscategory_category_tab_media_content_left',
                                                                        'name' => 'tr_melis_cms_category_media_tab_content_left',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCmsCategory2',
                                                                        'controller' => 'MelisCmsCategoryMedia',
                                                                        'action' => 'render-category-tab-media-content-left',
                                                                    ),
                                                                    'interface' => [
                                                                        'meliscategory_category_tab_media_content_left_image' => [
                                                                            'conf' => array(
                                                                                'id' => 'meliscategory_category_tab_media_content_left_image',
                                                                                'melisKey' => 'meliscategory_category_tab_media_content_left_image',
                                                                                'name' => 'tr_melis_cms_category_media_tab_content_left_image',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCmsCategory2',
                                                                                'controller' => 'MelisCmsCategoryMedia',
                                                                                'action' => 'render-category-tab-media-content-left-image',
                                                                            ),
                                                                            'interface' => [
                                                                                'meliscategory_category_tab_media_content_left_image_list' => [
                                                                                    'conf' => array(
                                                                                        'id' => 'id_meliscategory_category_tab_media_content_left_image_list',
                                                                                        'melisKey' => 'meliscategory_category_tab_media_content_left_image_list',
                                                                                        'rightsDisplay' => 'none'
                                                                                    ),
                                                                                    'forward' => array(
                                                                                        'module' => 'MelisCmsCategory2',
                                                                                        'controller' => 'MelisCmsCategoryMedia',
                                                                                        'action' => 'render-category-tab-media-content-left-image-list',
                                                                                    ),
                                                                                ]
                                                                            ]
                                                                        ],
                                                                    ]
                                                                ],
                                                                'meliscategory_category_tab_media_content_right' => [
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscategory_category_tab_media_content_right',
                                                                        'melisKey' => 'meliscategory_category_tab_media_content_right',
                                                                        'name' => 'tr_melis_cms_category_media_tab_content_right',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCmsCategory2',
                                                                        'controller' => 'MelisCmsCategoryMedia',
                                                                        'action' => 'render-category-tab-media-content-right',
                                                                    ),
                                                                    'interface' => [
                                                                        'meliscategory_category_tab_media_content_right_file' => [
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscategory_category_tab_media_content_right_file',
                                                                                'melisKey' => 'meliscategory_category_tab_media_content_right_file',
                                                                                'name' => 'tr_melis_cms_category_media_tab_content_right_image',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCmsCategory2',
                                                                                'controller' => 'MelisCmsCategoryMedia',
                                                                                'action' => 'render-category-tab-media-content-right-file',
                                                                            ),
                                                                        ]
                                                                    ]
                                                                ]
                                                            ]
                                                        ]
                                                    ]
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        ),
                        'meliscategory_documents_modal_container' => array(
                            'conf' => array(
                                'id' => 'id_meliscategory_documents_modal_container',
                                'melisKey' => 'meliscategory_documents_modal_container',
                                'name' => 'tr_meliscommerce_documents_modal_container',
                                'rightsDisplay' => 'none'
                            ),
                            'forward' => array(
                                'module' => 'MelisCmsCategory2',
                                'controller' => 'MelisCmsCategoryMedia',
                                'action' => 'render-mini-media-modal-container',
                            ),
                            'interface' => array(
                                'meliscategory_mini_media_library' => [
                                    'conf' => array(
                                        'id' => 'id_meliscategory_mini_media_library',
                                        'melisKey' => 'meliscategory_mini_media_library',
                                        'name' => 'Media'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCmsCategory2',
                                        'controller' => 'MelisCmsCategoryMedia',
                                        'action' => 'browse-media'
                                    ),
                                ],
                            )
                        ),
                        'melis_cms_categories_category_select_modal' => [
                            'conf' => [
                                'id' => 'melis_cms_categories_category_select_modal',
                                'melisKey' => 'melis_cms_categories_category_select_modal',
                                'rightsDisplay' => 'none'
                            ],
                            'forward' => [
                                'module' => 'MelisCmsCategory2',
                                'controller' => 'MelisCmsCategorySelect',
                                'action'     => 'render-category-select-modal',
                                'jscallback' => '',
                                'jsdatas' => array()
                            ],
                            'interface' => [
                                'melis_cms_categories_category_select_modal_content' => [
                                    'conf' => [
                                        'id' => 'melis_cms_categories_category_select_modal_content',
                                        'melisKey' => 'melis_cms_categories_category_select_modal_content',
                                    ],
                                    'forward' => [
                                        'module' => 'MelisCmsCategory2',
                                        'controller' => 'MelisCmsCategorySelect',
                                        'action'     => 'render-category-select-modal-content',
                                        'jscallback' => "initCategorySelectTree('.melis-cms-category-select-tree')",
                                        'jsdatas' => array()
                                    ],
                                ]
                            ]
                        ]
                    )
                )
            ]
        ]
    ]
);