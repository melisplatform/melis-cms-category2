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
                                        'melisKey' => 'meliscommerce_categories_page',
                                        'icon' => 'fa-th-list',
                                        'rights_checkbox_disable' => true
                                    ],
                                    'interface' => [
                                        'melis_cms_category_v2_config' => [
                                            'conf' => [
                                                'type' => 'melis_cms_category_v2_config/interface/melis_cms_categories_v2'
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
                'rights_checkbox_disable' => true
            ],
            'datas' => [],
            'ressources' => [
                'js' => [
                    '/MelisCmsCategory2/assets/jstree/dist/jstree.min.js',
                    '/MelisCmsCategory2/js/tools/category.tool.js',
                    '/MelisCmsCategory2/js/tools/documents.tool.js',
                ],
                'css' => [
                    '/MelisCmsCategory2/assets/jstree/dist/themes/proton/style.min.css',
                    '/MelisCmsCategory2/css/categories.css'
                ],
                /**
                 * the "build" configuration compiles all assets into one file to make
                 * lesser requests
                 */
                'build' => [
                    'disable_bundle' => true,
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
                        'icon' => 'fa-th-list'
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
                                'name' => 'tr_meliscommerce_categories_list',
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
                                        'name' => 'tr_meliscms_categories_list_header'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCmsCategory2',
                                        'controller' => 'MelisCmsCategoryList',
                                        'action' => 'render-category-list-header',
                                    ),
                                    'interface' => array(
                                        'meliscms_categories_list_header_add_catalog' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscms_categories_list_header_add_catalog',
                                                'melisKey' => 'meliscms_categories_list_header_add_catalog',
                                                'name' => 'tr_melis_cms_category_v2_header_add_btn'
                                            ),
                                            'forward' => array(
                                                'module' => 'MelisCmsCategory2',
                                                'controller' => 'MelisCmsCategoryList',
                                                'action' => ''
                                            )
                                        ),
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
                                        'name' => 'tr_meliscms_categories_list_content'
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCmsCategory2',
                                        'controller' => 'MelisCmsCategoryList',
                                        'action' => 'render-category-list-content',
                                    ),
                                    'interface' => array(
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
                                        'meliscms_categories_list_categories_tree' => array(
                                            'conf' => array(
                                                'id' => 'id_meliscms_catergories_list_categories_tree',
                                                'melisKey' => 'meliscms_categories_list_categories_tree',
                                                'name' => 'tr_meliscms_categories_list_categories_tree'
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
                                'name' => 'tr_meliscategory_categories_category'
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
                                                                'name' => 'tr_meliscategory_categories_category_tab_main_header'
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
                                                                        'name' => 'tr_meliscategory_categories_category_form_status',
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCmsCategory2',
                                                                        'controller' => 'MelisCmsCategory',
                                                                        'action' => 'render-category-form-status',
                                                                        'jscallback' => 'initCategoryStatus();'
                                                                    )
                                                                )
                                                            )
                                                        ),
                                                        'meliscategory_categories_category_tab_main_content' => array(
                                                            'conf' => array(
                                                                'id' => 'id_meliscategory_categories_category_tab_main_header',
                                                                'melisKey' => 'meliscategory_categories_category_tab_main_header',
                                                                'name' => 'tr_meliscategory_categories_category_tab_main_header'
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
                                                                        'name' => 'tr_meliscategory_categories_category_tab_main_left'
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
                                                                        ),
                                                                        'meliscategory_categories_category_form_date_validity' => array(
                                                                            'conf' => array(
                                                                                'id' => 'id_meliscategory_categories_category_form_date_validity',
                                                                                'melisKey' => 'meliscategory_categories_category_form_date_validity',
                                                                                'name' => 'tr_meliscategory_categories_category_form_date_validity',
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
                                                                                'name' => 'tr_meliscategory_categories_category_form_sites',
                                                                            ),
                                                                            'forward' => array(
                                                                                'module' => 'MelisCmsCategory2',
                                                                                'controller' => 'MelisCmsCategory',
                                                                                'action' => 'render-category-form-sites',
                                                                            )
                                                                        ),
                                                                       
                                                                    )
                                                                ),
                                                                'meliscategory_categories_category_tab_main_right' => array(
                                                                    'conf' => array(
                                                                        'id' => 'id_meliscategory_categories_category_tab_main_right',
                                                                        'melisKey' => 'meliscategory_categories_category_tab_main_right',
                                                                        'name' => 'tr_meliscategory_categories_category_tab_main_right'
                                                                    ),
                                                                    'forward' => array(
                                                                        'module' => 'MelisCmsCategory2',
                                                                        'controller' => 'MelisCmsCategory',
                                                                        'action' => 'render-category-tab-main-right',
                                                                    ),
                                                                    'interface' => array(
                                                                        'meliscategory_categories_category_main_product_imgs' => array(
                                                                            'conf' => array(
                                                                                'type' => 'melis_cms_category_v2_config/interface/melis_cms_categories_v2/interface/meliscategory_documents_image_attachments_conf',
                                                                                'docRelationType' => 'category',
                                                                            )
                                                                        ),
                                                                        'meliscategory_categories_category_main_file_attachments' => array(
                                                                            'conf' => array(
                                                                                'type' => 'melis_cms_category_v2_config/interface/melis_cms_categories_v2/interface/meliscategory_documents_file_attachments_conf',
                                                                                'docRelationType' => 'category',
                                                                            )
                                                                        )
                                                                    )
                                                                )
                                                            )
                                                        ),
                                                    )
                                                ),
                                            )
                                        )
                                    )
                                )
                            )
                        ),
                        'meliscategory_documents_image_attachments_conf' => array(
                            'conf' => array(
                                'id' => 'id_meliscms_documents_image_attachments',
                                'melisKey' => 'meliscms_documents_image_attachments',
                                'name' => 'tr_meliscms_documents_image_attachments',
                            ),
                            'forward' => array(
                                'module' => 'MelisCmsCategory2',
                                'controller' => 'MelisCmsCategoryDocument',
                                'action' => 'render-document-image-plugin',
                            ),
                            'interface' => array(
                                'meliscms_documents_image_lists' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscms_documents_image_lists',
                                        'melisKey' => 'meliscms_documents_image_lists',
                                        'name' => 'tr_meliscms_documents_image_lists',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCmsCategory2',
                                        'controller' => 'MelisCmsCategoryDocument',
                                        'action' => 'render-document-image-lists',
//                                 'jscallback' => 'initImageDocuments();',
                                    ),
                                ),
                            )
                        ),
//                        'meliscategory_documents_modal_container' => array(
//                            'conf' => array(
//                                'id' => 'id_meliscms_documents_modal_container',
//                                'melisKey' => 'meliscms_documents_modal_container',
//                                'name' => 'tr_meliscommerce_documents_modal_container'
//                            ),
//                            'forward' => array(
//                                'module' => 'MelisCmsCategory2',
//                                'controller' => 'MelisCmsCategoryDocument',
//                                'action' => 'render-document-generic-modal-container',
//
//                            ),
//                            'interface' => array(
//                                'meliscommerce_documents_modal_form' => array(
//                                    'conf' => array(
//                                        'id' => 'id_meliscommerce_documents_modal_form',
//                                        'melisKey' => 'meliscommerce_documents_modal_form',
//                                        'name' => 'tr_meliscommerce_documents_modal_form'
//                                    ),
//                                    'forward' => array(
//                                        'module' => 'MelisCmsCategory2',
//                                        'controller' => 'MelisCmsCategoryDocument',
//                                        'action' => 'render-document-generic-modal-form',
//                                    ),
//                                )
//                            )
//                        ),
                        'meliscategory_documents_file_attachments_conf' => array(
                            'conf' => array(
                                'id' => 'id_meliscommerce_documents_file_attachments',
                                'melisKey' => 'meliscommerce_documents_file_attachments',
                                'name' => 'tr_meliscommerce_documents_file_attachments',
                            ),
                            'forward' => array(
                                'module' => 'MelisCmsCategory2',
                                'controller' => 'MelisCmsCategoryDocument',
                                'action' => 'render-document-file-plugin',
                            ),
                            'interface' => array(
                                'meliscommerce_documents_file_attachments_lists' => array(
                                    'conf' => array(
                                        'id' => 'id_meliscommerce_documents_file_attachments_lists',
                                        'melisKey' => 'meliscommerce_documents_file_attachments_lists',
                                        'name' => 'tr_meliscommerce_documents_file_attachments_lists',
                                    ),
                                    'forward' => array(
                                        'module' => 'MelisCmsCategory2',
                                        'controller' => 'MelisCmsCategoryDocument',
                                        'action' => 'render-document-file-lists',
                                    ),
                                ),
                            )
                        ),

                    )
                )
//                'melis_cms_category_v2_display' => [
//                    'conf' => [
//                        'id'       => 'melis_cms_category_v2_display',
//                        'name'     => 'tr_melis_cms_category_v2_header_title',
//                        'melisKey' => 'melis_cms_category_v2_display',
//                        'icon'     => 'fa-th-list',
//                        'rights_checkbox_disable' => true
//                    ],
//                    'forward' => [
//                        'module'     => 'MelisCmsCategory2',
//                        'controller' => 'MelisCmsCategory2',
//                        'action'     => 'render-category-container'
//                    ],
//                    'interface' => [
//                        'melis_cms_category_v2_display_header' => [
//                            'conf' => [
//                                'id'       => 'melis_cms_category_v2_display',
//                                'name'     => 'tr_melis_cms_category_v2_header_title',
//                                'melisKey' => 'melis_cms_category_v2_display',
//                                'icon'     => 'fa-th-list',
//                                'rights_checkbox_disable' => true
//                            ],
//                            'forward' => [
//                                'module'     => 'MelisCmsCategory2',
//                                'controller' => 'MelisCmsCategory2',
//                                'action'     => 'render-category-container'
//                            ],
//                        ]
//                    ]
//                ]

            ]
        ]
    ]
);