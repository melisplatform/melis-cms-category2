<?php 
return array(
    'plugins' => array(
        'meliscategory' => array(
            'conf' => [
                'rightsDisplay' => 'none'
            ],
            'forms' => array(
                'meliscategory_categories' => array(
                    'meliscategory_categories_search_input' => array(
                        'attributes' => array(
                            'name' => '',
                            'id' => 'cmsCategoryTreeViewSearchForm',
                            'method' => '',
                            'action' => '',
                        ),
                        'hydrator'  => 'Laminas\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'cmsCategoryTreeViewSearchInput',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => '',
                                    ),
                                    'attributes' => array(
                                        'id' => 'cmsCategoryTreeViewSearchInput',
                                        'placeholder' => 'tr_meliscategory_categories_list_tree_view_search_input',
                                    )
                                )
                            ),
                        )
                    ),
                    'meliscategory_categories_category_information_form' => array(
                        'attributes' => array(
                            'name' => '',
                            'id' => 'categoryMainInformationForm',
                            'method' => '',
                            'action' => '',
                        ),
                        'hydrator'  => 'Laminas\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'catt2_lang_id',
                                    'type' => 'hidden',
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'catt2_name',
                                    'type' => 'text',
                                    'options' => array(
                                        'label' => 'tr_meliscategory_categories_category_information_form_cat_name',
                                        'tooltip' => 'tr_meliscategory_categories_category_information_form_cat_name tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'catt_name',
                                        'class' => 'form-control',
                                        'required' => true,
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'catt2_description',
                                    'type' => 'Textarea',
                                    'options' => array(
                                        'label' => 'tr_meliscategory_categories_category_information_form_cat_desc',
                                        'tooltip' => 'tr_meliscategory_categories_category_information_form_cat_desc tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'catt_description',
                                        'class' => 'form-control editme',
                                        'rows' => 10,
                                        'style' => 'resize: vertical;'
                                    )
                                )
                            ),
                        ),
                        'input_filter' => array(
                            'catt_lang_id' => array(
                                'name'     => 'catt_lang_id',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscategory_categories_input_empty',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'catt2_name' => array(
                                'name'     => 'catt2_name',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name' => 'NotEmpty',
                                        'options' => array(
                                            'messages' => array(
                                                \Laminas\Validator\NotEmpty::IS_EMPTY => 'tr_meliscategory_categories_input_empty',
                                            ),
                                        ),
                                    ),
//                                    array(
//                                        'name' => 'regex', false,
//                                        'options' => array(
//                                            'pattern' => '/^[a-zA-Z0-9]+([_ -]?[a-zA-Z0-9])*$/',
//                                            'messages' => array(
//                                                \Laminas\Validator\Regex::NOT_MATCH => 'tr_meliscmscategory_invalid_name'
//                                            ),
//                                            'encoding' => 'UTF-8',
//                                        ),
//                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                )
                            ),
                            'catt2_description' => array(
                                'name'     => 'catt2_description',
                                'required' => false,
                                'validators' => array(
//                                    array(
//                                        'name' => 'regex', false,
//                                        'options' => array(
//                                            'pattern' => '/^[a-zA-Z0-9]+([_ -]?[a-zA-Z0-9])*$/',
//                                            'messages' => array(
//                                                \Laminas\Validator\Regex::NOT_MATCH => 'tr_meliscmscategory_invalid_description'
//                                            ),
//                                            'encoding' => 'UTF-8',
//                                        ),
//                                    ),
                                ),
//                                'filters'  => array(
//                                    array('name' => 'StripTags'),
//                                    array('name' => 'StringTrim'),
//                                )
                            )
                        )
                    ),
                    'meliscategory_categories_date_validty_form' => array(
                        'attributes' => array(
                            'name' => '',
                            'id' => '',
                            'method' => '',
                            'action' => '',
                        ),
                        'hydrator'  => 'Laminas\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'cat_date_valid_start',
                                    'type' => 'DateField',
                                    'options' => array(
                                        'label' => 'tr_meliscategory_categories_category_valid_from',
                                        'tooltip' => 'tr_meliscategory_categories_category_valid_from tooltip',
                                        'class' => 'd-flex flex-row justify-content-between',
                                    ),
                                    'attributes' => array(
                                        'dateId' => 'categoryValidateDates',
                                        'dateLabel' => 'tr_meliscategory_categories_category_valid_from',
                                    )
                                )
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'cat_date_valid_end',
                                    'type' => 'DateField',
                                    'options' => array(
                                        'label' => 'tr_meliscategory_categories_category_valid_to',
                                        'tooltip' => 'tr_meliscategory_categories_category_valid_to tooltip',
                                        'class' => 'd-flex flex-row justify-content-between',
                                    ),
                                    'attributes' => array(
                                        'dateId' => 'categoryValidateDates',
                                        'dateLabel' => 'tr_meliscategory_categories_category_valid_to',
                                    )
                                )
                            ),
                        )
                    ),
                    'meliscategory_site_filer_form' => [
                        'attributes' => [
                            'name' => 'meliscategory_site_filer_form',
                            'id'   => 'meliscategory_site_filer_form',
                        ],
                        'hydrator' => 'Laminas\Stdlib\Hydrator\ArraySerializable',
                        'elements' => [
                            [
                                'spec' => [
                                    'name' => 'categorySiteFilter',
                                    'type' => 'MelisCmsPluginSiteSelect',
                                    'options' => [
                                        'label' => 'Sites',
                                        'tooltip' => 'tr_meiscms_categories_fitler_site tooltip',
                                        'empty_option' => 'tr_meliscms_categories_site_filter_all'
                                    ],
                                    'attributes' => [
                                        'id' => 'categorySiteFilter',
                                    ]
                                ]
                            ]
                        ]
                    ],
                    //category media upload form
                    'meliscategory_media_upload_form' => [
                        'attributes' => array(
                            'name' => 'meliscategory_media_upload_form',
                            'id' => 'id_meliscategory_media_upload_form',
                            'method' => '',
                            'action' => '',
                        ),
                        'hydrator'  => 'Laminas\Stdlib\Hydrator\ArraySerializable',
                        'elements' => array(
                            array(
                                'spec' => array(
                                    'name' => 'media_upload',
                                    'type' => 'file',
                                    'attributes' => [
                                        'data-classButton' => 'btn btn-primary',
                                        'class' => 'upload-category-media-image'
                                    ]
                                )
                            ),
                        )
                    ]
                ),
            ),
        ),
    ),
);
