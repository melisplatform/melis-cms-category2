<?php

return [
    'plugins' => [
        'meliscategory' => [
            'conf' => [
                'rightsDisplay' => 'none'
            ],
            'forms' => [
                'meliscategory_category_select_site_filter_form' => [
                    'attributes' => [
                        'name' => 'meliscategory_category_select_site_filter_form',
                        'id'   => 'meliscategory_category_select_site_filter_form',
                    ],
                    'hydrator' => 'Zend\Stdlib\Hydrator\ArraySerializable',
                    'elements' => [
                        [
                            'spec' => [
                                'name' => 'categorySelectSiteFilter',
                                'type' => 'MelisCmsPluginSiteSelect',
                                'options' => [
                                    'label' => 'Sites',
                                    'tooltip' => 'tr_meiscms_categories_fitler_site tooltip',
                                    'empty_option' => 'tr_meliscms_categories_site_filter_all'
                                ],
                                'attributes' => [
                                    'id' => 'categorySelectSiteFilter',
                                    'style' => 'cursor:pointer'
                                ]
                            ]
                        ],
                        [
                            'spec' => [
                                'name' => 'categorySelectLangFilter',
                                'type' => 'MelisCmsLanguageSelect',
                                'options' => [
                                    'label' => 'tr_meliscms_page_languages',
                                    'tooltip' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, ',
                                ],
                                'attributes' => [
                                    'id' => 'categorySelectLangFilter',
                                    'style' => 'cursor:pointer'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];