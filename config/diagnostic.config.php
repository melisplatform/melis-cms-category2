<?php

return array(
    'plugins' => array(
        'diagnostic' => array(
            'MelisCmsCategory2' => array(
                // location of your test folder inside the module
                'testFolder' => 'test',
                // moduleTestName is the name of your test folder inside 'testFolder'
                'moduleTestName' => 'MelisCmsCategory2Test',
                // this should be properly setup so we can recreate the factory of the database
                'db' => array(
                    'getMelisCmsCategory2Table' => array(
                        'model' => 'MelisCmsCategory2\Model\MelisCmsCategory2',
                        'model_table' => 'MelisCmsCategory2\Model\Tables\MelisCmsCategory2Table',
                        'db_table_name' => 'melis_cms_category2',
                    ),
                    'getMelisCmsCategory2SitesTable' => array(
                        'model' => 'MelisCmsCategory2\Model\MelisCmsCategory2Sites',
                        'model_table' => 'MelisCmsCategory2\Model\Tables\MelisCmsCategory2SitesTable',
                        'db_table_name' => 'melis_cms_category2_sites',
                    ),
                    'getMelisCmsCategory2MediaTable' => array(
                        'model' => 'MelisCmsCategory2\Model\MelisCmsCategory2Media',
                        'model_table' => 'MelisCmsCategory2\Model\Tables\MelisCmsCategory2MediaTable',
                        'db_table_name' => 'melis_cms_category2_media',
                    ),
                    'getMelisCmsCategory2TransTable' => array(
                        'model' => 'MelisCmsCategory2\Model\MelisCmsCategory2Trans',
                        'model_table' => 'MelisCmsCategory2\Model\Tables\MelisCmsCategory2TransTable',
                        'db_table_name' => 'melis_cms_category2_trans',
                    ),
                ),
                // these are the various types of methods that you would like to give payloads for testing
                // you don't have to put all the methods in the test controller,
                // instead, just put the methods that will be needing or requiring the payloads for your test.
                'methods' => array(
                    'testCmsCategory' => array(
                        'payloads' => array(
                            'create' => array(
                                array(
                                    'cat2_father_cat_id' => '-1',
                                    'cat2_order' => 1,
                                    'cat2_status' => 1,
                                    'cat2_date_creation' => date('Y-m-d h:i:s'),
                                    'cat2_user_id_creation' => 1,
                                ),
                            ),
                            'read' => array(
                                array(
                                    'column' => 'cat2_id',
                                    'value'  => '1'
                                ),
                            ),
                            'delete' => array(
                                array(
                                    'column' => 'cat2_id',
                                    'value' => '1',
                                ),
                            )
                        ),  
                    ),
                    'testCmsSitesCategory' => array(
                        'payloads' => array(
                            'create' => array(
                                array(
                                    'cats2_site_id' => '1',
                                    'cats2_cat2_id' => 1,
                                ),
                            ),
                            'read' => array(
                                array(
                                    'column' => 'cats2_site_id',
                                    'value'  => '1'
                                ),
                            ),
                            'delete' => array(
                                array(
                                    'column' => 'cats2_site_id',
                                    'value' => '1',
                                ),
                            )
                        ),
                    ),
                    'testCmsMediaCategory' => array(
                        'payloads' => array(
                            'create' => array(
                                array(
                                    'catm2_type' => 'image',
                                    'catm2_path' => '/media/categories/1/test.png',
                                    'catm2_cat_id' => '3',
                                ),
                            ),
                            'read' => array(
                                array(
                                    'column' => 'catm2_cat_id',
                                    'value'  => '3'
                                ),
                            ),
                            'delete' => array(
                                array(
                                    'column' => 'catm2_cat_id',
                                    'value' => '3',
                                ),
                            )
                        ),
                    ),
                    'testCmsTransCategory' => array(
                        'payloads' => array(
                            'create' => array(
                                array(
                                    'catt2_category_id' => '3',
                                    'catt2_name' => 'Test category',
                                    'catt2_lang_id' => '1',
                                ),
                            ),
                            'read' => array(
                                array(
                                    'column' => 'catt2_name',
                                    'value'  => 'Test category'
                                ),
                            ),
                            'delete' => array(
                                array(
                                    'column' => 'catt2_name',
                                    'value' => 'Test category',
                                ),
                            )
                        ),
                    ),
                ),
            ),
        ),
    ),

);

