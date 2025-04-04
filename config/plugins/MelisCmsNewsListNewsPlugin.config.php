<?php

return [
    'plugins' => [
        'meliscmsnews' => [
            'plugins' => [
                'MelisCmsNewsListNewsPlugin' => [
                    'front' => [  
                        // Category to show
                        'categoryIdNews' => null, 
                    ],
                    'melis' => [ 
                        'files' => [
                            'css' => [ 
                                '/MelisCmsNews/plugins/css/category.plugin.select.css', 
                            ],
                            'js' => [ 
                                '/MelisCmsNews/plugins/js/category.plugin.select.js',
                            ],
                        ], 
                        'modal_form' => [
                            'melis_cms_news_list_plugin_template_form' => [ 
                                'elements' => [ 
                                    [
                                        'spec' => [
                                            'name' => 'categoryIdNews',
                                            'type' => 'MelisCmsCategorySelect',
                                            'options' => [
                                                'label' => 'tr_meliscmsnews_plugin_filter_category',
                                                'tooltip' => 'tr_meliscmsnews_plugin_filter_category tooltip',
                                            ],
                                            'attributes' => [
                                                'id' => 'category_news'
                                            ],
                                        ],
                                    ],
                                ],
                                'input_filter' => [
                                    'categoryIdNews' => [
                                        'name'     => 'categoryIdNews',
                                        'required' => false,
                                        'validators' => [
                                            [
                                                'name'    => 'Digits',
                                                'options' => [
                                                    'messages' => [
                                                        \Laminas\Validator\Digits::NOT_DIGITS => 'tr_front_common_input_not_digit',
                                                        \Laminas\Validator\Digits::STRING_EMPTY => '',
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'filters'  => [
                                        ],
                                    ],
                                ]
                            ],
                        ]
                    ],
                ],
             ],
        ],
     ],
];