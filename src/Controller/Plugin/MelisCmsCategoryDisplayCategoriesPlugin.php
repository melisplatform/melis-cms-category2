<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2018 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2\Controller\Plugin;

use MelisEngine\Controller\Plugin\MelisTemplatingPlugin;
use Zend\View\Model\ViewModel;
use Zend\Stdlib\ArrayUtils;
use Zend\Session\Container;

/**
 * This plugin implements the business logic of the
 * "userLogin" plugin.
 *
 * Please look inside app.plugins.php for possible awaited parameters
 * in front and back function calls.
 *
 * front() and back() are the only functions to create / update.
 * front() generates the website view
 *
 * Configuration can be found in $pluginConfig / $pluginFrontConfig / $pluginBackConfig
 * Configuration is automatically merged with the parameters provided when calling the plugin.
 * Merge detects automatically from the route if rendering must be done for front or back.
 *
 * How to call this plugin without parameters:
 * $plugin = $this->MelisCmsCategoryDIsplayCategories();
 * $pluginView = $plugin->render();
 *
 * How to call this plugin with custom parameters:
 * $plugin = $this->MelisCmsCategoryDIsplayCategories();
 * $parameters = array(
 *      'template_path' => 'MelisCmsCategory2/default'
 * );
 * $pluginView = $plugin->render($parameters);
 *
 * How to add to your controller's view:
 * $view->addChild($pluginView, 'cmsCategoryTreeData');
 *
 * How to display in your controller's view:
 * echo $this->cmsCategoryTreeData;
 *
 *
 */
class MelisCmsCategoryDisplayCategoriesPlugin extends MelisTemplatingPlugin
{
    public function __construct($updatesPluginConfig = array())
    {
        // this key must correspond to the one at the top of the config
        $this->configPluginKey = 'meliscmscategory2';
        // this key correspond to the XML key name under which config will be saved in DB (see chapter back config)
        $this->pluginXmlDbKey = 'MelisCmsCategoryDisplayCategoriesPlugin';

        parent::__construct($updatesPluginConfig);
    }
    /**
     * This function gets the datas and create an array of variables
     * that will be associated with the child view generated.
     * @return array
     */
    public function front()
    {
        $container = new Container('melisplugins');
        $langId = $container['melis-plugins-lang-id'];

        $data          = $this->getFormData();
        $pluginId      = $data['id'];
        $categoryStart = $data['category_start'] ?? null;
        $siteId        = $data['site_id'] ?? null;
        $pageId        = $data['pageId'] ?? null;
        $startingCategory = null;
        $melisCmsCategorySvc = $this->getServiceLocator()->get('MelisCmsCategory2Service');
        // get the page lang locale
        $pageSvc       = $this->getServiceLocator()->get('MelisEnginePage');
        $pageData      = $pageSvc->getDatasPage($pageId);
        $melisPageTree = $pageData->getMelisPageTree();
        // put in the data for view
        $data['renderMode']  = $this->renderMode;
        $data['previewMode'] = $this->previewMode;
        // get category data based from category start
        if (! empty($data['category_start'])) {
            $categoryStart = $data['category_start'];
            $categoryStart = $melisCmsCategorySvc->getCategoryDataById($categoryStart)->cat2_father_cat_id;
        }
        // category data
        $categoryListData = $melisCmsCategorySvc->getCategoryTreeview($categoryStart,$langId,true,$siteId);
        // start only on what is set category_start
        if (! empty($data['category_start'])) {
            if (! empty($categoryListData)) {
                foreach ($categoryListData as $idx => $val) {
                    $categoryId = $val['cat2_id'];
                    if ($categoryId != $data['category_start']) {
                        unset($categoryListData[$idx]);
                    }
                }
            }
        }
        // reset array indexes
        $categoryListData = array_values($categoryListData);
        // return category only based from siteId selected
        if (! empty($siteId)) {
            $categoryListData = array_values($melisCmsCategorySvc->returnCategoryBasedFromSiteId($categoryListData,$siteId));
        }

        /*
         * Passing variables to the view phtml file
         */
        $viewVariables = array(
            'cmsCategoryTreeData' => $categoryListData,
            'pluginData' => $data
        );

        return $viewVariables;
    }

    /**
     * This function generates the form displayed
     * when editing the parameters of the plugin
     * @return array
     */
    public function createOptionsForms() : array
    {

        // construct form
        $factory        = new \Zend\Form\Factory();
        $formElements   = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $formConfig     = $this->pluginBackConfig['modal_form'];
        $tool           = $this->getServiceLocator()->get('translator');
        $melisPage = $this->getServiceLocator()->get('MelisEnginePage');

        $response = [];
        $render   = [];
        if (!empty($formConfig)) {
            foreach ($formConfig as $formKey => $config) {
                $form = $factory->createForm($config);
                $request = $this->getServiceLocator()->get('request');
                $parameters = $request->getQuery()->toArray();

                if (!isset($parameters['validate'])) {
                    $formData = $this->getFormData();
                    if ( empty($formData['category_start'])) {
                        $formData['category_start'] = 0;
                    }

                    $form->setData($formData);
                    $viewModelTab = new ViewModel();
                    $viewModelTab->setTemplate($config['tab_form_layout']);
                    $viewModelTab->modalForm    = $form;
                    $viewModelTab->formData     = $formData;

                    $viewModelTab->labels       = [
                        'noPropsMsg' => $tool->translate('tr_meliscms_comments_plugin_no_properties'),
                    ];

                    $viewRender = $this->getServiceLocator()->get('ViewRenderer');
                    $html = $viewRender->render($viewModelTab);
                    array_push($render, [
                            'name' => $config['tab_title'],
                            'icon' => $config['tab_icon'],
                            'html' => $html
                        ]
                    );

                }
                else {

                    // validate the forms and send back an array with errors by tabs
                    $post = get_object_vars($request->getPost());
                    $success = false;
                    $form->setData($post);

                    $errors = array();
                    if ($form->isValid()) {
                        $data = $form->getData();
                        $success = true;
                        array_push($response, [
                            'name' => $this->pluginBackConfig['modal_form'][$formKey]['tab_title'],
                            'success' => $success,
                        ]);
                    } else {
                        $errors = $form->getMessages();

                        foreach ($errors as $keyError => $valueError) {
                            foreach ($config['elements'] as $keyForm => $valueForm) {
                                if ($valueForm['spec']['name'] == $keyError &&
                                    !empty($valueForm['spec']['options']['label'])
                                )
                                    $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                            }
                        }


                        array_push($response, [
                            'name' => $this->pluginBackConfig['modal_form'][$formKey]['tab_title'],
                            'success' => $success,
                            'errors' => $errors,
                            'message' => '',
                        ]);
                    }

                }
            }
        }

        if (!isset($parameters['validate'])) {
            return $render;
        }
        else {
            return $response;
        }

    }

    /**
     * Create a Zend Form
     * @param $formConfig
     * @param null $excludeElement
     * @return \Zend\Form\ElementInterface
     */
    private function getPluginForm($formConfig , $excludeElement = null)
    {
        $factory        = new \Zend\Form\Factory();
        $formElements   = $this->getServiceLocator()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $form = $factory->createForm($formConfig);
        foreach($form->getElements() as $element => $val){
            if($element == $excludeElement){
                unset($formElements->getElements()[$element]);
            }
        }
        return $form;
    }

    /**
     * Returns the data to populate the
     * form inside the modals when invoked
     * @return array|bool|null
     */
    public function getFormData()
    {
        $data = parent::getFormData();

        return $data;
    }

    /**
     * This method will decode the XML in DB to make it in the form of the plugin config file
     * so it can overide it. Only front key is needed to update.
     * The part of the XML corresponding to this plugin can be found in $this->pluginXmlDbValue
     */
    public function loadDbXmlToPluginConfig()
    {
        $configValues = array();

        $xml = simplexml_load_string($this->pluginXmlDbValue);
        if ($xml)
        {
            if (!empty($xml->template_path))
                $configValues['template_path'] = (string)$xml->template_path;
            if (!empty($xml->site_id))
                $configValues['site_id'] = (string)$xml->site_id;
            if (! empty($xml->category_start)) {
                $configValues['category_start'] = (string)$xml->category_start;
            }
        }

        return $configValues;
    }
    /**
     * This method saves the XML version of this plugin in DB, for this pageId
     * Automatically called from savePageSession listenner in PageEdition
     */
    public function savePluginConfigToXml($parameters)
    {
        $xmlValueFormatted = '';
        // template_path is mendatory for all plugins
        if (!empty($parameters['template_path']))
            $xmlValueFormatted .= "\t\t" . '<template_path><![CDATA[' . $parameters['template_path'] . ']]></template_path>';
        if (!empty($parameters['site_id']))
            $xmlValueFormatted .= "\t\t" . '<site_id><![CDATA[' . $parameters['site_id'] . ']]></site_id>';
        if (!empty($parameters['category_start']))
            $xmlValueFormatted .= "\t\t" . '<category_start><![CDATA[' . $parameters['category_start'] . ']]></category_start>';
        // for resizing
        $widthDesktop = null;
        $widthMobile   = null;
        $widthTablet  = null;

        if (! empty($parameters['melisPluginDesktopWidth'])) {
            $widthDesktop =  " width_desktop=\"" . $parameters['melisPluginDesktopWidth'] . "\" ";
        }
        if (! empty($parameters['melisPluginMobileWidth'])) {
            $widthMobile =  "width_mobile=\"" . $parameters['melisPluginMobileWidth'] . "\" ";
        }
        if (! empty($parameters['melisPluginTabletWidth'])) {
            $widthTablet =  "width_tablet=\"" . $parameters['melisPluginTabletWidth'] . "\" ";
        }

        //
        // Something has been saved, let's generate an XML for DB
        $xmlValueFormatted = "\t" . '<' . $this->pluginXmlDbKey . ' id="' . $parameters['melisPluginId'] . '"' .$widthDesktop . $widthMobile . $widthTablet . ' >' .
            $xmlValueFormatted .
            "\t" . '</' . $this->pluginXmlDbKey . '>' . "\n";


        return $xmlValueFormatted;
    }
}