<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;


class MelisCmsCategoryListController extends AbstractActionController
{
    const TOOL_INDEX = 'melis_cms_category_v2_config';
    const TOOL_KEY = 'melis_cms_categories_v2';
    /**
     * Render Categories page
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCategoriesPageAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $noAccessPrompt = '';
        // Checks wether the user has access to this tools or not
        $melisCoreRights = $this->getServiceLocator()->get('MelisCoreRights');
        $translator = $this->getServiceLocator()->get('translator');
        if(!$melisCoreRights->canAccess($melisKey)) {
            $noAccessPrompt = $translator->translate('tr_tool_no_access');
        }
//
//        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
//        $melisTool->setMelisToolKey(self::TOOL_INDEX, self::TOOL_KEY);

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->noAccess = $noAccessPrompt;
        return $view;
    }
    
    /**
     * Render Category List
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCategoryListAction()
    {

    	$melisKey = $this->params()->fromRoute('melisKey', '');
        $melisCoreRights = $this->getServiceLocator()->get('MelisCoreRights');

//        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
//        $melisTool->setMelisToolKey(self::TOOL_INDEX, self::TOOL_KEY);

    	$view = new ViewModel();
    	$view->melisKey = $melisKey;
    	return $view;
    }

    public function searchCategoryTreeViewAction($categoryData, $fatherId, $newParent){
        $datas = $categoryData;
        $melisEcomCategoryTable = $this->getServiceLocator()->get('MelisEcomCategoryTable');
        $catData = $melisEcomCategoryTable->getChildrenCategoriesOrderedByOrder($fatherId);
        $catDatas = $catData->toArray();

        if (empty($catDatas)){
            // Parent Category doesn't have yet Children
            $melisEcomCategoryTable->save($datas,$datas['cat2_id']);
        }else{
        }
    }

    /**
     * Render Category List Header
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCategoryListHeaderAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();

        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Category List Content
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCategoryListContentAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Category List Header Add Catalog Button
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCategoryListHeaderAddCatalogAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    
    /**
     * Render Category List Header Add Category Button
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCategoryListHeaderAddCategoryAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    public function renderCategoryFilterAreaAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }
    /**
     * Render Category List Serch Input
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCategoryListSearchInputAction()
    {
        // Category Tree view Search Input
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscategory/forms/meliscategory_categories/meliscategory_categories_search_input','meliscategory_categories_search_input');
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->setVariable('meliscategory_categories_search_input', $propertyForm);
        return $view;
    }
    public function renderCategoryListSiteFilterAction()
    {
        // Site filter
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscategory/forms/meliscategory_categories/meliscategory_site_filer_form','meliscategory_site_filer_form');

        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $siteFilterForm = $factory->createForm($appConfigForm);

        $melisKey = $this->params()->fromRoute('melisKey', '');

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->setVariable('meliscategory_site_filer_form', $siteFilterForm);

        return $view;
    }
    /**
     * Render Category List Tree View
     * This method also return the list for Commerce Languages
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCategoryListTreeViewAction(){
        
        $cmsLang = $this->getServiceLocator()->get('MelisEngineTableCmsLang');
        $cmsData = $cmsLang->fetchAll();
        $cmsLangData = $cmsData->toArray();

        // Get the locale used from meliscore session
        $container = new Container('meliscore');
        $locale = $container['melis-lang-locale'];
        
        $currentLangData = $cmsLang->getEntryByField('lang_cms_locale',$locale);
        $currentLangImg = '<i class="fa fa-language"></i>';
        $currentLangName = '';
        $currentLang = $currentLangData->current();
        if (!empty($currentLang)){
            $currentLangName = $currentLang->lang_cms_name;
            $currentLangImg  = null;
        }
        
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->cmsLang = $cmsLangData;
        $view->currentLangLocale = $locale;
        $view->currentLangName = $currentLangName;
        $view->currentLangImg  = $currentLangImg;
        return $view;
    }

    /**
     * This method return Datas of the Category Tree view
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function getCategoryTreeViewAction(){
        
        $langLocale = $this->params()->fromQuery('langlocale');
        $selected = $this->params()->fromQuery('selected');
        $openStateParent = $this->params()->fromQuery('openStateParent');
        $siteId = $this->params()->fromQuery('siteId');

        $idAndNameOnly = $this->params()->fromQuery('idAndNameOnly');
        $categoriesChecked = $this->params()->fromQuery('categoriesChecked');

        if (!empty($openStateParent)){
            $openStateParent = explode(',', $openStateParent);
        }
        
        // Getting the Current language
        $cmsLang = $this->getServiceLocator()->get('MelisEngineTableCmsLang');
        $currentLang = $cmsLang->getEntryByField('lang_cms_locale',$langLocale)->current();
        $currentLocale = $currentLang->lang_cms_locale ?? null;
        $langId = $currentLang->lang_cms_id;
        $categoryList = [];
        // Getting Category Tree View form the Category Service

        $melisCmsCategorySvc = $this->getServiceLocator()->get('MelisCmsCategory2Service');
        if (empty($siteId)) {
            $categoryListData = $melisCmsCategorySvc->getCategoryTreeview(null, $langId,$siteId = null);
            // Category Tree View Preparation
            $categoryList = $this->prepareCategoryDataForTreeView($categoryListData, $selected, $openStateParent, $idAndNameOnly, $categoriesChecked, $currentLang->lang_cms_id);

        } else {
            $categoryList = $melisCmsCategorySvc->getFirstLevelCategoriesPerSite($siteId, $langId);

            $tmpData = [];
            if (! empty($categoryList)) {
                foreach ($categoryList as $idx => $val) {
                    $textColor = 'text-success';
                    if (!$val['cat2_status']) {
                        $textColor = "text-danger";
                    }
                    $categoryName = null;
                    $categoryTranslationsData = $this->getCategoryAvailableText($val['cat2_id']);
                    foreach ($categoryTranslationsData as $i5dx => $val2) {
                        if ($langId == $val2['lang_cms_id']) {
                            $categoryName = $val2['catt2_name'] ?? null;
                        }

                    }

                    // if no name to the current langId find some language that has name
                    if (empty($categoryName)) {
                        foreach ($categoryTranslationsData as $i5dx => $val2) {
                            if ($langId != $val2['lang_cms_id']) {
                                if (! empty($val2['catt2_name'])) {
                                    $categoryName = $val2['catt2_name'] . " (" . $val2['lang_cms_name'] . ")";
                                }
                            }
                        }
                    }

                    $tmpData[] = [
                        'cat2_id' => $val['cat2_id'],
                        'text'    => $val['cat2_id'] . ' - ' . $categoryName,
                        'textLang'=> false,
                        'id'      => $val['cat2_id'] . '_categoryid',
                        'icon'    => 'fa fa-circle ' . $textColor,
                        "type"    => 'category',
                        'a_attr'  => [
                            'data-fathericon' => "",
                            'data-fathercateid' => '-1'
                        ],
                        'state'   => [
                            'opened' => false,
                            'selected' => false,
                            'checked' => false
                        ]
                    ];
                }
            }

            $categoryList = $tmpData;
        }

        return new JsonModel($categoryList);
    }
    
    /**
     * This mothod prepare the the Datas form the Category Service data to array that supported of the JSTree used for 
     * Category Tree View
     * 
     * @param int Array $categoryList list of Categories
     * @param int $selected, Selected node that assign to category bu JsTree Plugin
     * @param array $openedStateParent, this contain Categories Id's that has to be open node after the request responded
     * 
     * @return int Array[]
     */
    public function prepareCategoryDataForTreeView($categoryList, $selected = false, $openedStateParent = array(), $idAndNameOnly = false, $categoryChecked = array(), $langId = null)
    {
        $translator = $this->getServiceLocator()->get('translator');
        $categorySvc = $this->getServiceLocator()->get('MelisCmsCategory2Service');

        foreach ($categoryList As $key => $val)
        {
            $numProds = null;
            $categoryList[$key]['id'] = $val['cat2_id'].'_categoryId';
            $checked = false;
            if (!empty($categoryChecked))
            {
                if (in_array($val['cat2_id'], $categoryChecked))
                {
                    $checked = true;
                }
            }

            $categoryName = null;
            $categoryTranslationsData = $this->getCategoryAvailableText($val['cat2_id']);
            foreach ($categoryTranslationsData as $i5dx => $val2) {
                if ($langId == $val2['lang_cms_id']) {
                    $categoryName = $val2['catt2_name'] ?? null;
                }

            }
            // if no name to the current langId find some language that has name
            if (empty($categoryList[$key]['text'])) {
                foreach ($categoryTranslationsData as $i5dx => $val2) {
                    if ($langId != $val2['lang_cms_id']) {
                        if (! empty($val2['catt2_name'])) {
                            $categoryName = $val2['catt2_name'] . " (" . $val2['lang_cms_name'] . ")";
                        }
                    }
                }
            }
            // Setting the Status of Category
            if ($val['cat2_status'])
            {
                $categoryList[$key]['icon'] = 'fa fa-circle text-success';
            }
            else
            {
                $categoryList[$key]['icon'] = 'fa fa-circle text-danger';
            }
            unset($categoryList[$key]['cat2_status']);

            $itemIcon = '';
            $categoryList[$key]['type'] = 'category';
            $categoryList[$key]['text'] = $val['cat2_id'].' - '. $categoryName;

            $categoryList[$key]['a_attr'] = array(
                'data-fathericon' => $itemIcon,
                'data-fathercateid' => $val['cat2_father_cat_id'],
            );
            
            unset($categoryList[$key]['cat2_father_cat_id']);
            
            $selectedState = false;
            if (!is_null($selected))
            {
                if ($selected==$val['cat2_id'])
                {
                    $selectedState = true;
                }
            }
            
            $openState = false;
            if (!empty($openedStateParent))
            {
                if (is_array($openedStateParent))
                {
                    if(in_array($val['cat2_id'], $openedStateParent))
                    {
                        $openState = true;
                    }
                }
            }
            
            // Node State
            $categoryList[$key]['state'] = array(
                'opened' => $openState,
                'selected' =>  $selectedState,
                'checked' =>  $checked,
            );
            
            if (!empty($val['children']))
            {
                $categoryList[$key]['children'] = $this->prepareCategoryDataForTreeView($categoryList[$key]['children'], $selected, $openedStateParent, $idAndNameOnly, $categoryChecked, $langId);
                
                /**
                 * Checking if the node children has a Open, Checked, Selected state
                 * the parent will set Open state
                 */
                if (!empty($categoryList[$key]['children']))
                {
                    foreach ($categoryList[$key]['children'] As $cKey => $cVal)
                    {
                        if (!empty($cVal['state']))
                        {
                            if ($cVal['state']['opened'] || $cVal['state']['selected'] ||$cVal['state']['checked'])
                            {
                                $categoryList[$key]['state']['opened'] = true;
                            }
                        }
                    }
                }
            }
        }
        
        return $categoryList;
    }
    
    /**
     * Saving Category Tree View form moving to another parent category
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function saveCategoryTreeViewAction()
    {
        $translator = $this->getServiceLocator()->get('translator');
        
        // Initialize Response Variable into Default Values
        $status  = 0;
        $textMessage = '';
        $textMessage = '';
        $textTitle = '';
         
        $request = $this->getRequest();
         
        if($request->isPost()) {
             
            $datas = get_object_vars($request->getPost());
             
            if (!empty($datas)){
                
                $fatherId = $datas['cat2_father_cat_id'];
                $old_fatherId = $datas['old_parent'];
                unset($datas['old_parent']);
                
                // Updating the current Parent Category with new Children
                $this->udpateCategoryTreeViewAction($datas, $fatherId, true);
                
                if ($old_fatherId!=$fatherId){
                    // updating Old Parent Category
                    $this->udpateCategoryTreeViewAction($datas, $old_fatherId, false);
                }
                
                $status = 1;
            }
        }
         
        $response = array(
            'success' => $status,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
        );
         
        return new JsonModel($response);
    }
    
    /**
     * Saving Parent Category Children
     * @param int $categoryData, category Data form Post Data
     * @param int $fatherId, the Parent ID of the Category
     * @param boolean $newParent, if true this will update to a new Parent category, otherwise stay on current Parent Id
     */
    public function udpateCategoryTreeViewAction($categoryData, $fatherId, $newParent){
        $datas = $categoryData;
        $cmsCategory = $this->getServiceLocator()->get('MelisCmsCategory2Table');
        $catData = $cmsCategory->getChildrenCategoriesOrderedByOrder($fatherId);
        $catDatas = $catData->toArray();
        
        if (empty($catDatas)){
            // Parent Category doesn't have yet Children
            $cmsCategory->save($datas,$datas['cat2_id']);
        }else{
            // Parent Category has already Children
            
            // If Category has a new Parent
            if ($newParent){
                foreach ($catDatas As $key => $val){
                    if ($catDatas[$key]['cat2_id']==$datas['cat2_id']){
                        // removing duplication of Category ID
                        unset($catDatas[$key]);
                    }
                }
                // Adding to specific index of the result array
                array_splice($catDatas, ($categoryData['cat2_order'] - 1), 0, array($categoryData));
            }
            
            // Re-ordering the Children of the Parent Category
            $ctr = 1;
            foreach ($catDatas As $key => $val){
                $catDatas[$key]['cat2_order'] = $ctr++;
            }
            
            // Updating  Children of the Parent Category one by one
            foreach ($catDatas As $key => $val){
                $cmsCategory->save($catDatas[$key],$catDatas[$key]['cat2_id']);
            }
        }
    }

    private function getCategoryAvailableText($categoryId)
    {
        $cmsCategory = $this->getServiceLocator()->get('MelisCmsCategory2TransTable');
        $categoryTranslationsData = $cmsCategory->getCategoryTranslationsByCatId($categoryId)->toArray();

        return $categoryTranslationsData;
    }


}