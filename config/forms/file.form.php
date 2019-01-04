<?php 
return array(
    'plugins' => array(
        'meliscategory' => array(
            'conf' => [
                'rightsDisplay' => 'none'
            ],
            'forms' => array(
                'meliscategory_file_form' => array(
                    'attributes' => array(
                        'name' => 'meliscategory_file_form',
                        'id' => 'meliscategory_file_form',
                        'method' => '',
                        'action' => '',
                    ),
                    'hydrator'  => 'Zend\Stdlib\Hydrator\ArraySerializable',
                    'elements' => array(
                        array(
                            'spec' => array(
                                'name' => 'catm2_lang_id',
                                'type' => 'hidden',
                            )
                        ),
                        array(
                            'spec' => array(
                                'name' => 'catm2_path',
                                'type' => 'MelisText',
                                'options' => array(
                                    'label' => 'File Path',
                                    'tooltip' => 'Location of the file',
                                ),
                                'attributes' => array(
                                    'id' => 'catm2_path',
                                    'placeholder' => 'Select file'
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
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscategory_categories_input_empty',
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
                            'required' => true,
                            'validators' => array(
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages' => array(
                                            \Zend\Validator\NotEmpty::IS_EMPTY => 'tr_meliscategory_categories_input_empty',
                                        ),
                                    ),
                                ),
                                array(
                                    'name' => 'regex', false,
                                    'options' => array(
                                        'pattern' => '/^[a-zA-Z0-9]+([_ -]?[a-zA-Z0-9])*$/',
                                        'messages' => array(
                                            \Zend\Validator\Regex::NOT_MATCH => 'tr_meliscmscategory_invalid_name'
                                        ),
                                        'encoding' => 'UTF-8',
                                    ),
                                ),
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
                                array(
                                    'name' => 'regex', false,
                                    'options' => array(
                                        'pattern' => '/^[a-zA-Z0-9]+([_ -]?[a-zA-Z0-9])*$/',
                                        'messages' => array(
                                            \Zend\Validator\Regex::NOT_MATCH => 'tr_meliscmscategory_invalid_description'
                                        ),
                                        'encoding' => 'UTF-8',
                                    ),
                                ),
                            ),
                            'filters'  => array(
                                array('name' => 'StripTags'),
                                array('name' => 'StringTrim'),
                            )
                        )
                    )
                ),
            ),
        ),
    ),
);
