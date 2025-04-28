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
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
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
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
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
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
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
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
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
                        'hydrator'  => 'Laminas\Hydrator\ArraySerializableHydrator',
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
                    ],
                    'meliscmscategory_seo_form' => [
                        'attributes' => [
                            'name' => 'categorySeoForm',
                            'id' => 'categorySeoForm',
                            'method' => 'POST',
                            'action' => '',
                        ],
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => array( 
                            array(
                                'spec' => array(
                                    'name' => 'category2_seo_id',
                                    'type' => 'hidden'                                
                                ),
                            ),    
                            array(
                                'spec' => array(
                                    'name' => 'category2_id',
                                    'type' => 'hidden'                                
                                ),
                            ), 
                            array(
                                'spec' => array(
                                    'name' => 'category2_seo_lang_id',
                                    'type' => 'hidden'                                
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'category2_seo_meta_title',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscmscategory_page_tab_seo_form_Meta Title',
                                        'tooltip' => 'tr_meliscmscategory_page_tab_seo_form_Meta Title tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'category2_seo_meta_title',
                                        'value' => '',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'category2_seo_meta_description',
                                    'type' => 'Textarea',
                                    'options' => array(
                                        'label' => 'tr_meliscmscategory_page_tab_seo_form_Meta Description',
                                        'tooltip' => 'tr_meliscmscategory_page_tab_seo_form_Meta Description tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'category2_seo_meta_description',
                                        'value' => '',
                                        'rows' => 5,
                                        'class' => 'melis-seo-desc form-control'
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'category2_seo_url',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscmscategory_page_tab_seo_form_Url',
                                        'tooltip' => 'tr_meliscmscategory_page_tab_seo_form_Url tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'category2_seo_url',
                                        'value' => '',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'category2_seo_url_redirect',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscmscategory_page_tab_seo_form_Url Redirect',
                                        'tooltip' => 'tr_meliscmscategory_page_tab_seo_form_Url Redirect tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'category2_seo_url_redirect',
                                        'value' => '',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'category2_seo_url_301',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscmscategory_page_tab_seo_form_Url 301',
                                        'tooltip' => 'tr_meliscmscategory_page_tab_seo_form_Url 301 tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'category2_seo_url_301',
                                        'value' => '',
                                    ),
                                ),
                            ),
                            array(
                                'spec' => array(
                                    'name' => 'category2_seo_canonical',
                                    'type' => 'MelisText',
                                    'options' => array(
                                        'label' => 'tr_meliscmscategory_page_tab_seo_form_canonical',
                                        'tooltip' => 'tr_meliscmscategory_page_tab_seo_form_canonical tooltip',
                                    ),
                                    'attributes' => array(
                                        'id' => 'category2_seo_canonical',
                                        'value' => '',
                                    ),
                                ),
                            ),                                            
                        ),
                        'input_filter' => array( 
                            'category2_seo_meta_title' => array(
                                'name'     => 'category2_seo_meta_title',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 65,
                                            'messages' => array(
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscmscategory_pageseo_form_page_title_long',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'category2_seo_meta_description' => array(
                                'name'     => 'category2_seo_meta_description',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => array(
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscmscategory_pageseo_form_page_desc_long',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'category2_seo_canonical' => array(
                                'name'     => 'category2_seo_canonical',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => array(
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscmscategory_pageseo_form_page_desc_long',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'category2_seo_url' => array(
                                'name'     => 'category2_seo_url',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => array(
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscmscategory_pageseo_form_page_url_too_long',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'category2_seo_url_redirect' => array(
                                'name'     => 'category2_seo_url_redirect',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => array(
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscmscategory_pageseo_form_page_url_too_long',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                            'category2_seo_url_301' => array(
                                'name'     => 'category2_seo_url_301',
                                'required' => false,
                                'validators' => array(
                                    array(
                                        'name'    => 'StringLength',
                                        'options' => array(
                                            'encoding' => 'UTF-8',
                                            'max'      => 255,
                                            'messages' => array(
                                                \Laminas\Validator\StringLength::TOO_LONG => 'tr_meliscmscategory_pageseo_form_page_url_too_long',
                                            ),
                                        ),
                                    ),
                                ),
                                'filters'  => array(
                                    array('name' => 'StripTags'),
                                    array('name' => 'StringTrim'),
                                ),
                            ),
                        ),
                    ],
                ),
            ),
        ),
    ),
);
