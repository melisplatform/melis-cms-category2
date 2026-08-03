<?php

/**
 * Web services (microservices) exposed by MelisCmsCategory2.
 *
 * All read-only category lookups. Dispatched by MelisCore's microservice controller:
 * \MelisCmsCategory2\Service\MelisCmsCategoryService::<method>(). The service is aliased
 * as "MelisCmsCategoryService" (== class short name) in module.config.php so the dispatcher
 * can resolve it.
 *
 * Form elements are declared in the SAME order as the service method parameters — the
 * dispatcher maps the Nth element to the Nth positional argument.
 */

/**
 * Helper: a single Text form element.
 */
$msElement = function (string $name, string $dataType, string $placeholder = '') {
    return [
        'spec' => [
            'name' => $name,
            'type' => 'Text',
            'options' => [ 'label' => $name ],
            'attributes' => [
                'id' => $name,
                'value' => '',
                'class' => '',
                'placeholder' => $placeholder,
                'data-type' => $dataType,
            ],
        ],
    ];
};

/**
 * Helper: input_filter entry for a required integer field.
 */
$msIntRequired = function (string $name) {
    return [
        'name' => $name,
        'required' => true,
        'validators' => [
            [
                'name' => 'IsInt',
                'options' => [
                    'message' => [
                        \Laminas\I18n\Validator\IsInt::INVALID => $name . ' must be an integer'
                    ],
                ],
            ],
        ],
        'filters' => [
            ['name' => 'StripTags'],
            ['name' => 'StringTrim'],
        ],
    ];
};

/**
 * Helper: input_filter entry for an optional field (no validator so an empty value
 * falls back to the method's default argument).
 */
$msOptional = function (string $name) {
    return [
        'name' => $name,
        'required' => false,
        'filters' => [
            ['name' => 'StripTags'],
            ['name' => 'StringTrim'],
        ],
    ];
};

$msAttributes = [
    'name' => 'microservice_form',
    'id'   => 'microservice_form',
    'method' => 'POST',
    'action' => $_SERVER['REQUEST_URI'],
];

return [
    'plugins' => [
        'microservice' => [
            'MelisCmsCategory2' => [
                'MelisCmsCategoryService' => [
                    '_description' => 'tr_meliscmscategory_ws_desc',

                    'getCategoryById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                            $msElement('onlyValid', 'bool', 'false'),
                        ],
                        'input_filter' => [
                            'categoryId' => $msIntRequired('categoryId'),
                            'langId'     => $msOptional('langId'),
                            'onlyValid'  => $msOptional('onlyValid'),
                        ],
                    ],

                    'getCategoryDataById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'categoryId' => $msIntRequired('categoryId'),
                        ],
                    ],

                    'getCategoryNameById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                            $msElement('exclude', 'bool', 'false'),
                        ],
                        'input_filter' => [
                            'categoryId' => $msIntRequired('categoryId'),
                            'langId'     => $msOptional('langId'),
                            'exclude'    => $msOptional('exclude'),
                        ],
                    ],

                    'getCategoryTranslationById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                            $msElement('onlyValid', 'bool', 'false'),
                        ],
                        'input_filter' => [
                            'categoryId' => $msIntRequired('categoryId'),
                            'langId'     => $msOptional('langId'),
                            'onlyValid'  => $msOptional('onlyValid'),
                        ],
                    ],

                    'getCategoryListByIdRecursive' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                            $msElement('onlyValid', 'bool', 'false'),
                            $msElement('start', 'int', '0'),
                            $msElement('limit', 'int', '10'),
                            $msElement('fatherId', 'int', ''),
                        ],
                        'input_filter' => [
                            'categoryId' => $msIntRequired('categoryId'),
                            'langId'     => $msOptional('langId'),
                            'onlyValid'  => $msOptional('onlyValid'),
                            'start'      => $msOptional('start'),
                            'limit'      => $msOptional('limit'),
                            'fatherId'   => $msOptional('fatherId'),
                        ],
                    ],

                    'getCategoryTreeview' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('fatherId', 'int', ''),
                            $msElement('langId', 'int', '1'),
                            $msElement('onlyValid', 'bool', 'false'),
                            $msElement('siteId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'fatherId'   => $msOptional('fatherId'),
                            'langId'     => $msOptional('langId'),
                            'onlyValid'  => $msOptional('onlyValid'),
                            'siteId'     => $msOptional('siteId'),
                        ],
                    ],

                    'getFirstLevelCategoriesPerSite' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('siteId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'siteId' => $msIntRequired('siteId'),
                            'langId' => $msOptional('langId'),
                        ],
                    ],

                    'getCategoriesPerSite' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('siteId', 'int', '1'),
                            $msElement('langId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'siteId' => $msIntRequired('siteId'),
                            'langId' => $msOptional('langId'),
                        ],
                    ],

                    'getSiteCategoryById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'categoryId' => $msIntRequired('categoryId'),
                        ],
                    ],

                    'getCategoryMediaById' => [
                        'attributes' => $msAttributes,
                        'hydrator' => 'Laminas\Hydrator\ArraySerializableHydrator',
                        'elements' => [
                            $msElement('categoryId', 'int', '1'),
                        ],
                        'input_filter' => [
                            'categoryId' => $msIntRequired('categoryId'),
                        ],
                    ],
                ],
            ],
        ],
    ],
];
