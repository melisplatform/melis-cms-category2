<?php

return array(
    'plugins' => array(
        'meliscmscategory2' => array(
            'plugins' => array(
                'MelisCmsCategoryDisplayCategoriesPlugin' => array(
                    'front' => array(
                        'template_path' => array('MelisCmsCategory2/default'),
                        'id' => '',
                        // Site id of News
                        'site_id' => null,
                        'category_start' => null,
                        // List the files to be automatically included for the correct display of the plugin
                        // To overide a key, just add it again in your site module
                        // To delete an entry, use the keyword "disable" instead of the file path for the same key
                        'files' => array(
                            'css' => [
                                '/MelisCmsCategory2/plugins/css/melis-category-display-categories.css'
                            ],
                            'js' => array(
                            ),
                        ),
                    ),
                    'melis' => array(
                        'name' => 'tr_MelisCmsCategoryDisplayCategories_Name',
                        'thumbnail' => '/MelisCmsCategory2/plugins/images/category-display-plugin.jpg',
                        'description' => 'tr_MelisCmsCategoryDisplayCategories_Description',
                        'files' => array(
                            'css' => array(
                                '/MelisCmsCategory2/plugins/css/melis-category-display-categories.css'
                            ),
                            'js' => array(
                            ),
                        ),
                        'js_initialization' => array(
                        ),
                        'modal_form' => [
                            'melis_cms_category_list_plugin_template_form' => array(
                                'tab_title' => 'tr_meliscmsnews_plugin_tab_properties',
                                'tab_icon'  => 'fa fa-cog',
                                'tab_form_layout' => 'MelisCmsCategory2/plugin/modal/modal-template-form',
                                'attributes' => array(
                                    'name' => 'melis_cms_news_list_plugin_template_form',
                                    'id' => 'melis_cms_news_list_plugin_template_form',
                                    'method' => '',
                                    'action' => '',
                                ),
                                'elements' => array(
                                    array(
                                        'spec' => array(
                                            'name' => 'template_path',
                                            'type' => 'MelisEnginePluginTemplateSelect',
                                            'options' => array(
                                                'label' => 'tr_melis_Plugins_Template',
                                                'tooltip' => 'tr_melis_Plugins_Template tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'id_page_tpl_id',
                                                'class' => 'form-control',
                                                'required' => 'required',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'category_start',
                                            'type' => 'MelisCmsCategorySelect',
                                            'options' => array(
                                                'label' => 'tr_meliscmsnews_plugin_filter_category_start',
                                                'tooltip' => 'tr_meliscmsnews_plugin_filter_category_start tooltip',
                                            ),
                                            'attributes' => array(
                                                'id' => 'category_start',
                                                'required' => 'required',
                                            ),
                                        ),
                                    ),
                                    array(
                                        'spec' => array(
                                            'name' => 'site_id',
                                            'type' => 'MelisCmsPluginSiteSelect',
                                            'options' => array(
                                                'label' => 'tr_meliscmsnews_plugin_filter_site',
                                                'tooltip' => 'tr_meiscms_categories_fitler_site tooltip',
                                                'empty_option' => 'tr_melis_Plugins_Choose',
                                                'disable_inarray_validator' => true,
                                            ),
                                            'attributes' => array(
                                                'id' => 'site_id',
                                                'class' => 'form-control',
                                            ),
                                        ),
                                    ),
                                ),
                                'input_filter' => array(
                                    'template_path' => array(
                                        'name'     => 'template_path',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_front_template_path_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                    'category_start' => array(
                                        'name'     => 'category_start',
                                        'required' => true,
                                        'validators' => array(
                                            array(
                                                'name'    => 'Digits',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\Digits::NOT_DIGITS => 'tr_front_common_input_not_digit',
                                                    ),
                                                ),
                                            ),
                                            array(
                                                'name' => 'NotEmpty',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_melis_cms_categories_plugin_category_start_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                    'site_id' => array(
                                        'name'     => 'site_id',
                                        'required' => false,
                                        'validators' => array(
                                            array(
                                                'name'    => 'Digits',
                                                'options' => array(
                                                    'messages' => array(
                                                        \Zend\Validator\Digits::NOT_DIGITS => 'tr_front_common_input_not_digit',
                                                        \Zend\Validator\Digits::STRING_EMPTY => 'tr_front_common_input_empty',
                                                    ),
                                                ),
                                            ),
                                        ),
                                        'filters'  => array(
                                        ),
                                    ),
                                )
                            ),
                        ]
                    ),
                ),
             ),
        ),

     ),
);