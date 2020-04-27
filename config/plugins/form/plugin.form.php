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
                    'hydrator' => 'Laminas\Stdlib\Hydrator\ArraySerializable',
                    'elements' => [
                        [
                            'spec' => [
                                'name' => 'categorySelectSiteFilter',
                                'type' => 'MelisCmsPluginSiteSelect',
                                'options' => [
                                    'label' => 'tr_meliscmsnews_plugin_filter_site',
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
                                    'tooltip' => 'tr_meliscms_category_select_lang_filter tooltip',
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