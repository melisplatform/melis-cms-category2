<?php

return array(
    'plugins' => array(
        'meliscategory' => array(
            'tools' => array(   
                'meliscategory_categories' => array(
                    'conf' => array(
                        'title' => 'tr_meliscategory_categories_tool_title',
                        'id' => 'id_meliscategory_categories',
                    ),
                    'table' => array(
                        // table ID
                        'target' => '#melisCategoryTable',
                        'ajaxUrl' => '/melis/MelisCmsCategory2/MelisComCategory/getCategoryProductList',
                        'dataFunction' => 'initCategoryTreeView',
                        'ajaxCallback' => '',
                        'filters' => array(
                            'left' => array(),
                            'center' => array(),
                            'right' => array(
                                'category-product-list-export' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCategory',
                                    'action' => 'render-category-product-list-export',
                                ),
                                'category-product-list-refresh' => array(
                                    'module' => 'MelisCommerce',
                                    'controller' => 'MelisComCategory',
                                    'action' => 'render-category-product-list-refresh',
                                ),
                            ),
                        ),
                        'columns' => array(
                            'pcat_order' => array(
                                'text' => '<i class="fa fa-plus"> </i> ',
                                'css' => array('width' => '1%', 'visible' => false),
                                'sortable' => true,
                            ),
                            'prd_id' => array(
                                'text' => 'tr_meliscategory_categories_category_prd_id',
                                'css' => array('width' => '1%'),
                                'sortable' => false,
                            ),
                            'prd_img' => array(
                                'text' => 'tr_meliscategory_categories_category_prd_img',
                                'css' => array('width' => '5%'),
                                'sortable' => false,
                            ),
                            'prd_status' => array(
                                'text' => 'tr_meliscategory_categories_category_prd_status',
                                'css' => array('width' => '1%'),
                                'sortable' => false,
                            ),
                            'prd_name' => array(
                                'text' => 'tr_meliscategory_categories_category_prd_name',
                                'css' => array('width' => '30%'),
                                'sortable' => false,
                            ),
                            'prd_date_creation' => array(
                                'text' => 'tr_meliscategory_categories_category_prd_date_creation',
                                'css' => array('width' => '1%'),
                                'sortable' => false,
                            ),
                        ),
                        // define what columns can be used in searching
                        'searchables' => array(),
                        'actionButtons' => array(
                            'edit' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCategory',
                                'action' => 'render-category-product-list-view',
                            ),
                            'delete' => array(
                                'module' => 'MelisCommerce',
                                'controller' => 'MelisComCategory',
                                'action' => 'render-category-product-list-remove',
                            ),
                        )
                    ),
                ), // end 
            ),
        ),
    ),
);