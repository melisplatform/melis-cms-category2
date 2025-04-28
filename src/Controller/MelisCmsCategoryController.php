<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2\Controller;

use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Laminas\Session\Container;
use MelisCore\Controller\MelisAbstractActionController;

class MelisCmsCategoryController extends MelisAbstractActionController
{
    const PLUGIN_INDEX = 'melis_cms_categories_v2';
    const TOOL_KEY = 'meliscategory_categories_category';

    /**
     * Render Category Page Zone
     * This also return if the zone if visible or hidden depend on request
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $catId = $this->params()->fromQuery('catId');
        $catFatherId = $this->params()->fromQuery('catFatherId');

        $melisCoreRights = $this->getServiceManager()->get('MelisCoreRights');
        $translator = $this->getServiceManager()->get('translator');
        $access = true;
        $parentConfigKey = 'melis_cms_category_v2';
        if(!$melisCoreRights->canAccess($parentConfigKey)) {
            $access = false;
        }

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->hide = (!is_null($catId)||!is_null($catFatherId)) ? '' : 'hidden';
        $view->access = $access;

        return $view;
    }

    /**
     * Render Category Header
     * This also return the Title of the Zone
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryHeaderAction(){
        $view = new ViewModel();
        $translator = $this->getServiceManager()->get('translator');
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $catId = $this->params()->fromQuery('catId');
        $catFatherId = $this->params()->fromQuery('catFatherId');
        
        if (!empty($catId))
        {
            // Getting Current Langauge ID
            $melisTool = $this->getServiceManager()->get('MelisCoreTool');
            $langId = $melisTool->getCurrentLocaleID();
            
            $melisCmsCategoryService = $this->getServiceManager()->get('MelisCmsCategory2Service');
            $categoryData = $melisCmsCategoryService->getCategoryById($catId);
            $category = $categoryData->getTranslations();
            $catname = '';
            foreach ($category As $val)
            {
                if ($val->lang_cms_id == $langId)
                {
                    $catname = $val->catt2_name;
                    break;
                }
            }
            if (empty($catname)) {
                foreach ($category As $val)
                {
                    if ($val->lang_cms_id != $langId)
                    {
                        $catname = $val->catt2_name . " (" . $val->lang_cms_name . ") ";
                        break;
                    }
                }
            }

            $view->title = $translator->translate('tr_meliscore_common_edit').' "'.$catname.'"';
        }
        else
        {
            $view->title = $translator->translate('tr_melis_cms_category_v2_header_add_btn');
        }
        
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * Render Category Header Button Save
     * This also return data attribute of the Save Button
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryHeaderSaveCategoryAction(){

        $view = new ViewModel();

        $catId = $this->params()->fromQuery('catId');
        $melisKey = $this->params()->fromRoute('melisKey', '');

        $catFatherId = $this->params()->fromQuery('catFatherId');

        // Getting the FatherID of the category
        if (empty($catFatherId)){
            $catFatherId = '-1';
        }

        if (!empty($catId)){
            // Getting category Data and set as Data Attribute of the Save Button
            $melisCmsCategoryService = $this->getServiceManager()->get('MelisCmsCategory2Service');
            $categoryData = $melisCmsCategoryService->getCategoryById($catId);
            $category = $categoryData->getCategory();
            $catFatherId = $category->cat2_father_cat_id;
        }

        $view->melisKey = $melisKey;
        $view->catId = ($catId) ? $catId : 0;
        $view->catFatherId = $catFatherId;
        return $view;
    }

    /**
     * Render Category Content
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryContentAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * Render Category Tab Content
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryTabContentAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * Render Category Tab Main Content
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryTabMainAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * Render Category Tab Main Header
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryTabMainHeaderAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * Render Category Tab Main Content
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryTabMainContentAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * Render Category Tab Main Left Zone
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryTabMainLeftAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * Render Category Translations Form
     * This also return binded form with data depend on the request
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryFormTranslationsAction(){

        $view = new ViewModel();
        $catId = $this->params()->fromQuery('catId');
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $currentLocale = $this->getRequest()->getQuery('currentLocale');
        if (!empty($catId)){
            // Getting Category Translations
            $melisCmsCategoryService = $this->getServiceManager()->get('MelisCmsCategory2Service');
            $categoryData = $melisCmsCategoryService->getCategoryById($catId);
            $view->categoryTrans = $categoryData->getTranslations();
        }

        // Getting Category Translations Form from App.forms.php Config
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscategory/forms/meliscategory_categories/meliscategory_categories_category_information_form','meliscategory_categories_category_information_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);

        // Getting Form input and push to array with empty/null value as Default Value for multiple Form
        $appConfigFormElements = $appConfigForm['elements'] ?? null;
        $formDefaultValues = array();
        if (!empty($appConfigFormElements)){
            foreach ($appConfigFormElements As $key => $val)
            {
                $formDefaultValues[$val['spec']['name']] = null;
            }
        }

        // Getting Commerce available Languages
        $ecomLangtable = $this->getServiceManager()->get('MelisEngineTableCmsLang');
        $ecomLang = $ecomLangtable->fetchAll();
        $ecomLangData = $ecomLang->toArray();
        $view->setVariable('meliscommerce_categories_category_information_form', $propertyForm);
        $view->ecomLang = $ecomLangData;
        $view->melisKey = $melisKey;
        $view->formDefaultValues = $formDefaultValues;
        $view->currentLocale = $currentLocale;

        return $view;
    }

    public function renderCategoryFormDateValidityAction()
    {
        $catId = $this->params()->fromQuery('catId');

        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        // Getting Category Translations Form from App.forms.php Config
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscategory/forms/meliscategory_categories/meliscategory_categories_date_validty_form','meliscategory_categories_date_validty_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);
        // Get the locale used from meliscore session
        $container = new Container('meliscore');
        $locale = $container['melis-lang-locale'];
        if (!empty($catId)){
            $melisTranslation = $this->getServiceManager()->get('MelisCoreTranslation');

            $melisCmsCategoryService = $this->getServiceManager()->get('MelisCmsCategory2Service');
            $categoryData = $melisCmsCategoryService->getCategoryById($catId);
            $category = $categoryData->getCategory();
            $validFrom = $category->cat2_date_valid_start ?? null;
            $validTo = $category->cat2_date_valid_end ?? null;

            if (! empty($validFrom)) {
                $category->cat_date_valid_start = date('d/m/Y', strtotime($validFrom));
            }

            if (! empty($validTo)) {
                $category->cat_date_valid_end = date('d/m/Y', strtotime($validTo));
            }

            $propertyForm->bind($category);
        }

        $view = new ViewModel();
        $catId = $this->params()->fromQuery('catId');
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->setVariable('meliscommerce_categories_date_validty_form', $propertyForm);
        $view->datepickerInit = $melisTool->datePickerInit('categoryValidateDates');
        $view->locale = $locale;

        return $view;
    }

    /**
     * Render Category Countries
     * This also return the Countries of the category depend on request
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryFormSitesAction()
    {
        $catId = $this->params()->fromQuery('catId');
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $siteSelectedId = $this->getRequest()->getQuery('selectedSiteId');

        $sites = array();
        if (!empty($catId)){

            $catSiteTbl = $this->getServiceManager()->get('MelisCmsCategory2SitesTable');
            $catSiteData = $catSiteTbl->getEntryByField('cats2_cat2_id',$catId)->toArray();
            // Countries Id's assign to a array handler
            if (!empty($catSiteData)){
                foreach ($catSiteData As $val){
                    array_push($sites, $val['cats2_site_id']);
                }
            }
        }else{
            // Creating new entry, sites "all" is prechecked
            array_push($sites, '-1');
        }

        // Getting all Commerce Coutnries
        $sitesTable = $this->getServiceManager()->get('MelisEngineTableSite');
        $siteData   = $sitesTable->fetchAll()->toArray();

        $view = new ViewModel();
        $view->cartegorySites = $sites;
        $view->siteData = $siteData;
        $view->melisKey = $melisKey;
        $view->catId = $catId;
        $view->siteSelectedId = $siteSelectedId;

        return $view;
    }

    /**
     * Render Category Status
     * This also return the current status of the category depend on request
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryFormStatusAction(){
        $view = new ViewModel();
        $catId = $this->params()->fromQuery('catId');
        $melisKey = $this->params()->fromRoute('melisKey', '');

        if (!empty($catId)){
            $melisCmsCategoryService = $this->getServiceManager()->get('MelisCmsCategory2Service');
            $categoryData = $melisCmsCategoryService->getCategoryById($catId);
            $category = $categoryData->getCategory();
            $view->categorystatus = ($category->cat2_status) ? 'checked' : '';
        }

        $view->melisKey = $melisKey;
        $view->catId = $catId;

        return $view;
    }

    /**
     * Render Category Tab Main Right Zone
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryTabMainRightAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * Render Category Tab for SEO
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryTabSeoAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * Render Category Products
     * This also return the DataTable for Category Products Listing
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryTabProductsAction(){

        $translator = $this->getServiceManager()->get('translator');
        $activateTab = $this->params()->fromQuery('activateTab');
        $catId = $this->params()->fromQuery('catId');

        //Get category product count
        $prodCatTable = $this->getServiceManager()->get('MelisEcomProductCategoryTable');
        $prodCount = $prodCatTable->getCategoryProductCount($catId)->current()->count;

        // Getting Category Products Table on config
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey(self::PLUGIN_INDEX, self::TOOL_KEY);
        $columns = $melisTool->getColumns();
        $columns['actions'] = array('text' => $translator->translate('tr_meliscommerce_categories_common_label_action'));

        // Custome Datatable configuration
        $tableOption = array(
            'rowReorder' => array(
                'dataSrc' => 'pcat_order',
                'selector' => 'td:nth-child(1)',
            ),
            'serverSide' => 'false',
            'paging' => 'false',
            'responsive' => array(
                'details' => array(
                    'type' => 'column'
                )
            )
        );

        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->tableColumns = $columns;
        $view->getToolDataTableConfig = $melisTool->getDataTableConfiguration('//categoryProductListTbl', true, false, $tableOption);
        $view->catId = $catId;
        $view->activateTab = ($activateTab) ? 'active' : '';
        $view->prodCount = $prodCount;
        return $view;
    }

    /**
     * This method saving Category Details
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function saveCategoryAction()
    {
        $translator = $this->getServiceManager()->get('translator');
        $request = $this->getRequest();
        $success = 0;
        $textTitle = 'tr_melis_cms_category_v2';
        $message   = $translator->translate('tr_meliscms_categories_err_category_save_unable');
        $errors    = [];
        $logTypeCode = "CMS_CATEGORY2_ADD";
        $id = null;
        $createdId = null;
        $passedCatId = null;
        $customError = [];
        if($request->isPost()) {
            $this->getEventManager()->trigger('meliscms_category_save_start', $this, $request);
            $currentUserLoggedIn = $this->getLoggedInUserInfo();
            $userId              = $currentUserLoggedIn->usr_id ?? null;
            $categoryService     = $this->getCategoryService();
            $postValues          = $request->getPost()->toArray();
            $catTranslationData  = $postValues['cat_trans'] ?? null;
            $catSitesData        = $postValues['cat_sites'] ?? null;
            if (empty($catSitesData)) {
                $errors[] = [
                    'Site'  => $translator->translate('tr_meliscms_categories_select_site'),
                    'label' => $translator->translate('tr_meliscategory_categories_category_countries'),
                ];
            }

            $passedCatId         = $postValues['cat_id'] ?? null;
            $dateActive          = str_replace(' ','',str_replace('/','-',$postValues['cat_date_valid_start']) ?? null);
            $dateInactive        = str_replace(' ','',str_replace('/','-',$postValues['cat_date_valid_end']) ?? null);
            // melis-core config
            $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
            // form config
            $appConfigForm  = $melisMelisCoreConfig->getItem('meliscategory/forms/meliscategory_categories/meliscategory_categories_category_information_form');
            // create form
            $propertyForm   = $this->getForm($appConfigForm);
            $formValid = [];
            $propertyFormData = [];
            // validate translations form
            foreach ($catTranslationData as $idx => $val) {
                $tmpForm[] = $propertyForm->setData($val);
                if ($propertyForm->isValid()) {
                    if (!empty($val['catt2_name'])) {
                        $formValid[$val['catt2_name']] = 1;
                    }
                } else {
                    $errors[] = $this->formatErrorMessage($appConfigForm,$propertyForm->getMessages());
                }
            }

            // this means property form name field is filled
            if (! empty($formValid)) {
                $parentId       = $postValues['cat_father_cat_id'] ?? null;
                $status         = $postValues['cms_cat_status'] ?? null;
                // validate Dates
                $dateValidation = null;
                $dateValidStart = null;
                $dateValidEnd   = null;

                if (! empty($dateActive) && ! empty($dateInactive)) {
                    $dateValidation = $categoryService->validateDates($dateActive,$dateInactive);
                    if ($dateValidation == true) {
                        $dateValidStart = $dateActive;
                        $dateValidEnd   = $dateInactive;
                    } else {
                        $trDateStart = $translator->translate('tr_meliscategory_categories_category_valid_from');
                        $trDateEnd   = $translator->translate('tr_meliscategory_categories_category_valid_to');
                        $errors  = [
                            $trDateStart => $translator->translate('tr_meliscategory_categories_category_valid_from_must_equal_high_current_date'),
                            $trDateEnd   => $translator->translate('tr_meliscategory_categories_category_valid_dates_invalid')
                        ];
                        $customError['datesValidation'] = 1;
                    }
                }

                if (empty($errors)) {
                    // save Category
                    $categoryId  = $categoryService->saveCategory($parentId, $status,$userId, $dateActive, $dateInactive, $passedCatId, $postValues);
                    if (! empty($passedCatId)) {
                        $id = $passedCatId;
                    } else {
                        $id = $categoryId;
                    }
                    // rename only the tmp file in adding new category
                    if (empty($passedCatId)) {
                        // rename the tmp folder
                        $tmpPath = $_SERVER['DOCUMENT_ROOT'] . "/media/categories/tmp";
                        if (file_exists($tmpPath)) {
                            rename ($tmpPath, $_SERVER['DOCUMENT_ROOT'] . "/media/categories/$categoryId");
                        }
                    }
                    // save Category translations
                    foreach ($catTranslationData as $idx => $val) {
                        $catLangId = $val['catt2_lang_id'] ?? null;
                        if (! empty($catLangId)) {
                            $categoryService->saveCategoryTexts($categoryId, $catLangId, $val ,$passedCatId);
                        }
                    }
                    // save category sites
                    $categorySiteId = null;
                    if (! empty($catSitesData)) {
                        $catSiteTbl = $this->getCatSiteTable();
                        // delete cause we are assuming data is changing
                        if (! empty($passedCatId)) {
                            $catSiteTbl->deleteByField('cats2_cat2_id',$passedCatId);
                        }
                        if (in_array('-1',$catSitesData)) {
                            $engineSite = $this->getServiceManager()->get('MelisEngineTableSite');
                            $sites = $engineSite->fetchAll()->toArray();
                            if (! empty($sites)) {
                                foreach ($sites as $idx => $val) {
                                    $categorySiteId = $categoryService->saveCategorySites($categoryId, $val['site_id'], $passedCatId);
                                }
                            }
                        } else {
                            // save data
                            foreach( $catSitesData as $siteId) {
                                // all selected sites
                                if ($siteId != -1){
                                    $categorySiteId = $categoryService->saveCategorySites($categoryId, $siteId, $passedCatId);
                                }
                            }
                        }

                    }
                    // media
                    $images = $postValues['cat2_media_image'] ?? [];
                    // changed tmp to categoryId
                    if (! empty($images)) {
                        foreach ($images as $idx => $val) {
                            $tmpPath = str_replace('tmp',$categoryId,$val);
                            $images[$idx] = $tmpPath;
                        }
                    }
                    // also for files
                    $files  = $postValues['cat2_media_file'] ?? [];
                    if (! empty($files)) {
                        foreach ($files as $idx => $val) {
                            $tmpPath = str_replace('tmp',$categoryId,$val);
                            $files[$idx] = $tmpPath;
                        }
                    }

                    $mediaTable = $this->getServiceManager()->get('MelisCmsCategory2MediaTable');
                    // save all media when adding a category
                    if (empty($passedCatId)) {
                        if (!empty($images)) {
                            foreach($images as $idx => $val) {
                                $mediaDataImage = [
                                    'catm2_cat_id' => $categoryId,
                                    'catm2_type'   => 'image',
                                    'catm2_path'   => $val,
                                ];
                                //save media image
                                $mediaTable->save($mediaDataImage);
                            }
                        }
                        if (! empty($files)) {
                            foreach ($files as $idx => $val) {
                                $mediaDataFile = [
                                    'catm2_cat_id' => $categoryId,
                                    'catm2_type'   => 'file',
                                    'catm2_path'   => $val,
                                ];
                                //save media file
                                $mediaTable->save($mediaDataFile);
                            }
                        }
                        // clear session tmp data of medias
                        $category2Session = new Container('melis_cms_category2');
                        $category2Session->getManager()->getStorage()->clear('melis_cms_category2');
                    }
 
                    //save seo tab
                    list('success' => $isSeoSuccess, 'errors' => $errors) = $this->saveCategorySeo($categoryId); 
 
                    if ($isSeoSuccess == 1) {  
                        $success = 1;
                        $message = 'tr_meliscms_categories_err_category_save_success';
                    } else {
                        $success = 0;  
                    }


                }

            } else {
                $errors[] = [
                    'noName' => $translator->translate('tr_meliscms_categories_category_name_required_atleast_one'),
                    'label'  => $translator->translate('tr_meliscategory_categories_category_information_form_cat_name')
                ];
                $customError['trans'] = 1;
            }
        }
        if (!empty($errors)) {
            $success = 0;
            $message = $translator->translate('tr_meliscms_categories_err_category_save_unable');
        } else {

            if (! empty($passedCatId)) {
                $logTypeCode = "CMS_CATEGORY2_UPDATE";
                $message = 'tr_meliscms_categories_err_category_update_ok';
            }
        }

        if (empty($catSitesData)) {
            $customError['site'] = 1;
        }
 
        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $message,
            'errors' => $errors,
            'customError' => $customError,
            'id'          => $id
        );

        // flash messenger
        $this->getEventManager()->trigger('meliscms_category2_save_end',
            $this, array_merge($response, array('typeCode' => $logTypeCode, 'itemId' => $id)));
        // translate
        //$response['textMessage'] = $translator->translate($response['textMessage']);

        return new JsonModel($response);
    }

    
    /**
     * This function saves the seo of the category 
     */
    private function saveCategorySeo($category2_id)
    {        
        $translator = $this->getServiceManager()->get('translator');        
        // Get the form properly loaded               
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');  
        $seoFormConfig = $melisCoreConfig->getFormMergedAndOrdered('meliscategory/forms/meliscategory_categories/meliscmscategory_seo_form', 'meliscmscategory_seo_form');
        $MelisCmsCategory2SeoTable = $this->getServiceManager()->get('MelisCmsCategory2SeoTable');
        $MelisCmsCategory2TransTable = $this->getServiceManager()->get('MelisCmsCategory2TransTable');
        // Check if post
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postValues = $request->getPost()->toArray(); 
            $languages = $this->getOrderedLanguagesByCurrentLocale();
            $languageCount = count($languages);
            if ($languages) {
                foreach ($languages as $lang) {
                    // Generating form for each language
                    $seoFormtmp = $factory->createForm($seoFormConfig);
                    //loop through the language form data
                    $ctr = 1;
                    foreach ($postValues['category2_seo'] as $val) {  
                        if ($val['category2_seo_lang_id'] && $val['category2_seo_lang_id'] == $lang['lang_cms_id']) {   
                            if (!empty($val['category2_seo_url'])) {
                                $val['category2_seo_url'] = $this->cleanURL($val['category2_seo_url']);
                            }
                            if (!empty($val['category2_seo_canonical'])) {
                                $val['category2_seo_canonical'] = $this->cleanURL($val['category2_seo_canonical']);
                            }
                            if (!empty($val['category2_seo_url_redirect'])) {
                                $val['category2_seo_url_redirect'] = $this->cleanURL($val['category2_seo_url_redirect']);
                            }
                            if (!empty($val['category2_seo_url_301'])) {
                                $val['category2_seo_url_301'] = $this->cleanURL($val['category2_seo_url_301']);
                            }
                            $seoFormtmp->setData($val);    
                            break;                
                        }             
                    }   
                    if ($seoFormtmp->isValid()) {                       
                        $seoData = $seoFormtmp->getData();
                        $allEmpty = true;
                        if (!empty($seoData['category2_seo_meta_title']) || !empty($seoData['category2_seo_meta_description']) || !empty($seoData['category2_seo_url']) || !empty($seoData['category2_seo_canonical']) || !empty($seoData['category2_seo_url_redirect']) || !empty($seoData['category2_seo_url_301'])) {
                            $allEmpty = false;
                        }
                        $success = 1;
                        $seoData['category2_id'] = $category2_id;//set the category id
                        //if at least one seo property is filled up, continue
                        if (!$allEmpty)  {
                            // Check for unicity of the URL declared
                            if (!empty($seoData['category2_seo_url'])) {
                                $catSiteTbl = $this->getServiceManager()->get('MelisCmsCategory2SitesTable');
                                $catSiteData = $catSiteTbl->getEntryByField('cats2_cat2_id',$category2_id)->toArray();
                                if($catSiteData) {
                                    foreach ($catSiteData as $key => $siteData) {

                                        $categorySeo = $MelisCmsCategory2SeoTable->checkSeoUrlDuplicates($seoData['category2_seo_url'], $siteData['cats2_site_id'])->current();
                                        if (!empty($categorySeo)) {
                                            // Not this page of course
                                            if (empty($seoData['category2_seo_id']) || (!empty($seoData['category2_seo_id']) && $seoData['category2_seo_id'] != $categorySeo->category2_seo_id)) {
                                                $categoryTitleDuplicate = '';                                    
                                                $categoryText = $MelisCmsCategory2TransTable->getEntryByField('catt2_category_id', $categorySeo->category2_id)->current();
 
                                                //get all languages available in the plaftform
                                                $cmsLangTable = $this->getServiceManager()->get('MelisEngineTableCmsLang');
                                                $languages = $cmsLangTable->getEntryByField('lang_cms_id', $categorySeo->category2_seo_lang_id)->current();
                                                if (!empty($categoryText))
                                                    $categoryTitleDuplicate = $categoryText->catt2_name;                                      
                                                // This URL is already used somewhere else
                                                return [
                                                    'success' => 0, 
                                                    'errors' => [ 
                                                        'category2_seo_url' => [ 
                                                            'category2_seo_url' => $translator->translate('tr_meliscmscategory_page_tab_seo_error_duplicate_url') . ' '. $categoryTitleDuplicate,
                                                            'label' => $translator->translate('tr_meliscmscategory_page_tab_seo_error_label_seo_url'). '('.$lang["lang_cms_name"].')' 
                                                        ]
                                                    ] 
                                                ];
                                            }
                                        }
                                    }
                                }

                                //check also from page seo table                                
                                $melisTablePageSeo = $this->getServiceManager()->get('MelisEngineTablePageSeo');
                                $datasPageSeo = $melisTablePageSeo->getEntryByField('pseo_url', $seoData['category2_seo_url']);
                                if (!empty($datasPageSeo))
                                {
                                    $datasPageSeo = $datasPageSeo->current();
                                    if (!empty($datasPageSeo))
                                    {                                       
                                        $pageNameDuplicate = '';
                                        $melisPage = $this->getServiceManager()->get('MelisEnginePage');
                                        $datasPage = $melisPage->getDatasPage($datasPageSeo->pseo_id, 'saved');
                                        $pageTree = $datasPage->getMelisPageTree();
                                        if (!empty($pageTree))
                                            $pageNameDuplicate = ' ' . $pageTree->page_name . ' (' . $datasPageSeo->pseo_id . ')';
                                        // This URL is already used somewhere else
                                        return array(
                                                'success' => 0,                                              
                                                'errors' => array( 'category2_seo_url' =>
                                                                array(
                                                                        'category2_seo_url' => $translator->translate('tr_meliscmscategory_page_tab_seo_error_duplicate_url') . $pageNameDuplicate,
                                                                        'label' => $translator->translate('tr_meliscmscategory_page_tab_seo_error_label_seo_url'). '('.$lang["lang_cms_name"].')'
                                                                    )
                                                                )
                                        );
                                    }
                                }                              
                            }
                            // Cleaning special char and white spaces on SEO Url
                            $enginePage = $this->getServiceManager()->get('MelisEngineTree');
                            $seoData['category2_seo_url'] = $enginePage->cleanString(mb_strtolower($seoData['category2_seo_url']));
                            // Checking for spaces
                            if (preg_match('/\s/', $seoData['category2_seo_url'])) {
                                $seoData['category2_seo_url'] = str_replace(" ", "", $seoData['category2_seo_url']);
                            }

                            $categorySeoId = $seoData['category2_seo_id'];
                            unset($seoData['category2_seo_id']);

                            $res = $MelisCmsCategory2SeoTable->save($seoData, $categorySeoId);
                            if (!$res) {
                                $success = 0;
                            }
                        } else {
                            if (!empty($seoData['category2_seo_id'])) {
                                 // All fields are empty, let's delete the entry
                                $res = $MelisCmsCategory2SeoTable->deleteById($seoData['category2_seo_id']);
                                if (!$res) {
                                    $success = 0;
                                }
                            }
                        }
                        $result = array(
                            'success' => $success,
                            'errors' => array(),
                        );
                    } else {
                        // Add labels of errors
                        $errors = $seoFormtmp->getMessages();
                        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');                       
                        $seoFormConfig = $seoFormConfig['elements'];
                        foreach ($errors as $keyError => $valueError) {
                            foreach ($seoFormConfig as $keyForm => $valueForm) {                          
                                if ($valueForm['spec']['name'] == $keyError &&
                                        !empty($valueForm['spec']['options']['label'])){
                                            $errors[$keyError]['label'] = $valueForm['spec']['options']['label']. '('.$lang["lang_cms_name"].')';
                                            //$errors[$keyError][key($valueError)] = sprintf(current($valueError), $lang['lang_cms_name']);
                                        }
                            }
                        }
                        return array(
                                'success' => 0,                                               
                                'errors' => $errors
                        );
                    }   
                }//end foreach language
            } // end if languages       
        } else {
            $result = array(
                    'success' => 0,
                    'errors' => array(array('empty' => $translator->translate('tr_meliscms_form_common_errors_Empty datas'))),
            );
        }
        return $result;
    }

    /**
     * Rids the URL from special characters -> reference: MelisCms/PageSeoController
     * @param string $url
     * @return mixed
     */
    private function cleanURL(string $url = '')
    {
        $url = str_replace(' ', '-', $url);                 // Replaces all spaces with hyphens
        $url = preg_replace('/[^A-Za-z0-9\/\-]+/', '-', $url);  // Replaces special characters with hyphens
        // remove "/" prefix on generated URL
        if (substr($url, 0, 1) == '/') {
            return preg_replace('/\//', '', $url, 1);
        }
        return $url;
    }

    /**
    * This retrieves the list of languages available in the platform where the current locale used is the first in the list
    * @return array
    */
    private function getOrderedLanguagesByCurrentLocale()
    {
        //get all languages available in the plaftform
        $coreLang = $this->getServiceManager()->get('MelisEngineTableCmsLang');
        $languages = $coreLang->fetchAll()->toArray();      
        $curLangId = $this->getLangId();
        //set the current locale as the first value in the array
        foreach ($languages as $key => $langData) {
            if ($langData["lang_cms_id"] == $curLangId) {
                unset($languages[$key]);
                array_unshift($languages,$langData);
            }
        }
        return $languages;
    }
    
    /**
     * Get Language
     * @return int
     */
    private function getLangId()
    {
        $container = new Container('meliscore');
        $currentLang = '';

        if ($container) {
            $melisEngineLangTable = $this->getServiceManager()->get('MelisEngineTableCmsLang');
            $locale = $container['melis-lang-locale'];
            $currentLangData = $melisEngineLangTable->getEntryByField('lang_cms_locale', $locale);

            $currentLang = $currentLangData->current();
        }

        return !empty($currentLang) ? $currentLang->lang_cms_id : 1;
    }
    
    /**
     * This method get Category Data form the Post Data
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function getCategoryAction(){

        $translator = $this->getServiceManager()->get('translator');

        $success = 0;
        $errors = array();

        $request = $this->getRequest();

        $catData = array();

        if($request->isPost()) {

            $postValues = $request->getPost()->toArray();

            $catId = $postValues['cat_id'];

            // Category Countries Data Preparation
            $catCountriesData = array();
            if (!empty($postValues['cat_countries'])){
                $catCountries = explode(',', $postValues['cat_countries']);
                foreach ($catCountries As $val){
                    array_push($catCountriesData, array('ccat_country_id' => $val));
                }
            }

            // Get Cureent User ID
            $melisCoreAuth = $this->getServiceManager()->get('MelisCoreAuth');
            $userAuthDatas =  $melisCoreAuth->getStorage()->read();
            $userId = (int) $userAuthDatas->usr_id;

            // Get the locale used from meliscore session
            $container = new Container('meliscore');
            $locale = $container['melis-lang-locale'];


            $validFrom = trim($postValues['cat_date_valid_start']);
            $validTo = trim($postValues['cat_date_valid_end']);

            if ($locale == 'fr_FR')
            {
                if (!empty($validFrom))
                {

                    $validFrom = explode('/', $validFrom);

                    if (count($validFrom) == 3)
                    {
                        $validFrom = $validFrom[2].'/'.$validFrom[1].'/'.$validFrom[0];
                    }
                    else
                    {
                        $errors['cat_date_valid_start'] = array(
                            'label' => $translator->translate('tr_meliscommerce_categories_category_valid_from'),
                            'fromInvalidDate' => $translator->translate('Invalid Date Format')
                        );
                    }
                }

                if (!empty($validTo))
                {
                    $validTo = explode('/', $validTo);

                    if (count($validTo) == 3)
                    {
                        $validTo = $validTo[2].'/'.$validTo[1].'/'.$validTo[0];
                    }
                    else
                    {
                        $errors['cat_date_valid_start'] = array(
                            'label' => $translator->translate('tr_meliscommerce_categories_category_valid_from'),
                            'fromInvalidDate' => $translator->translate('Invalid Date Format')
                        );
                    }
                }
            }
            else
            {
                if (!empty($validFrom))
                {
                    $validFrom = explode('/', $validFrom);

                    if (count($validFrom) == 3)
                    {
                        $validFrom = $validFrom[2].'/'.$validFrom[0].'/'.$validFrom[1];
                    }
                    else
                    {
                        $errors['cat_date_valid_start'] = array(
                            'label' => $translator->translate('tr_meliscommerce_categories_category_valid_from'),
                            'fromInvalidDate' => $translator->translate('Invalid Date Format')
                        );
                    }
                }

                if (!empty($validTo))
                {
                    $validTo = explode('/', $validTo);

                    if (count($validTo) == 3)
                    {
                        $validTo = $validTo[2].'/'.$validTo[0].'/'.$validTo[1];
                    }
                    else
                    {
                        $errors['cat_date_valid_start'] = array(
                            'label' => $translator->translate('tr_meliscommerce_categories_category_valid_from'),
                            'fromInvalidDate' => $translator->translate('Invalid Date Format')
                        );
                    }
                }
            }
            
            if (!empty($validFrom && $validTo))
            {
                if ((bool)strtotime($validFrom) && (bool)strtotime($validTo))
                {
                    if ($validFrom > $validTo)
                    {
                        if (!empty($errors['cat_date_valid_end']))
                        {
                            $errors['cat_date_valid_end']['invalidDate'] = $translator->translate('tr_meliscommerce_categories_category_valid_dates_invalid');
                        }
                        else
                        {
                            $errors['cat_date_valid_end'] = array(
                                'label' => $translator->translate('tr_meliscommerce_categories_category_valid_from'),
                                'invalidDate' => $translator->translate('tr_meliscommerce_categories_category_valid_dates_invalid')
                            );
                        }
                    }
                }
            }

            $catData = array(
                'cat_father_cat_id' => $postValues['cat_father_cat_id'],
                'cat_status' => $postValues['cms_cat_status'],
                'cat_date_valid_start' => ($validFrom) ? date('Y-m-d H:i:s', strtotime($validFrom)) : null,
                'cat_date_valid_end' => ($validTo) ? date('Y-m-d H:i:s', strtotime($validTo)) : null,
                'cat_date_edit' => date('Y-m-d H:i:s'),
                'cat_user_id_edit' => $userId
            );

            // This is for New Catgory entry
            if (!$catId){
                // Getting the Last Order number as new order of the Category
                $melisEcomCategoryTable = $this->getServiceManager()->get('MelisCmsCategryTable');
                $catOrder = $melisEcomCategoryTable->getTotalData('cat_father_cat_id', $postValues['cat_father_cat_id']) + 1;
                $catData['cat_order'] = $catOrder;

                $catData['cat_user_id_creation'] = $userId;
                $catData['cat_date_creation'] = date('Y-m-d H:i:s');
            }
        }

        if (empty($errors)){
            $success = 1;
        }

        $result = array(
            'success' => $success,
            'errors' => array('cat_err' => $errors),
            'datas' => array('cat' => $catData),
        );

        return new JsonModel($result);
    }
    /**
     * This method will Delete/Remove Product form Category Products
     *
     * @return \Laminas\View\Model\JsonModel
     */
    public function deleteCategoryAction()
    {
        $translator = $this->getServiceManager()->get('translator');
        $request = $this->getRequest();
        // Default Values
        $catId = null;
        $errors = array();
        $success  = 0;
        $textTitle = '';
        $textMessage = '';
        $logTypeCode = 'CMS_CATEGORY2_DELETE';
        $textTitle   = $translator->translate("tr_meliscms_categories_category_delete");
        if($request->isPost()) {
            $postValues = $this->getRequest()->getPost()->toArray();
            $postValues = $this->getTool()->sanitizeRecursive($postValues);
            $catId = (int) $postValues['cat_id'];
            $categoryTable = $this->getCategory2Table();
            $categoryMediaSvc = $this->getServiceManager()->get('MelisCmsCategory2MediaService');
            $categorySvc = $this->getServiceManager()->get('MelisCmsCategory2Service');
            $categoryData = $categoryTable->getEntryById($catId)->current();
            $parentId     = $categoryData->cat2_father_cat_id ?? null;
            $currentOrder = $categoryData->cat2_order;
            $getChildren  = $categoryTable->getEntryByField('cat2_father_cat_id',$catId)->toArray();

            if (! empty($catId)) {
                if (empty($getChildren)) {
                    // execute delete
                    $categoryTable->deleteById($catId);
                    // re order or update categories after deleting the previous category
                    $categorySvc->reOrderCategories($parentId, $currentOrder);
                    $textMessage = $translator->translate('tr_meliscms_categories_category_delete_success');
                    // remove files in media directory
                    $categoryMediaSvc->removeCategoryDir($catId);
                    $success = 1;
                } else {
                    $textMessage = $translator->translate('tr_meliscms_categories_err_category_unable_delete');
                    $errors = [
                        'Ko' => $translator->translate('tr_meliscms_categories_err_category_delete_has_children'),
                        'label' => $translator->translate('tr_melis_cms_category_v2_header_title')
                    ];
                }
            }
        }

        $response = array(
            'success' => $success,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
        );

        if ($success){
            $this->getEventManager()->trigger('meliscms_category2_save_end',
                $this, array_merge($response, array('typeCode' => $logTypeCode, 'itemId' => $catId)));
        }

        return new JsonModel($response);
    }

    /**
     * Returns the translation text
     * @param String $key
     * @param array $args
     *
     * @return string
     */
    private function getTranslation($key, $args = null)
    {
        $translator = $this->getServiceManager()->get('translator');
        $text = vsprintf($translator->translate($key), $args);

        return $text;
    }

    private function getTool()
    {
        $tool =  $this->getServiceManager()->get('MelisCoreTool');
        return $tool;
    }
    /**
     * Album/src/Album/Controller/AlbumToolController.php
     * Returns the data of the current user
     * @return null
     */
    private function getLoggedInUserInfo()
    {
        $authService = $this->getServiceManager()->get('MelisCoreAuth');
        if($authService->hasIdentity()) {
            return $authService->getIdentity();
        }

        return null;
    }

    /**
     * Return zend form
     * @param $formConfig
     * @return \Laminas\Form\ElementInterface
     */
    private function getForm($formConfig)
    {
        // zend form factory
        $factory = new \Laminas\Form\Factory();
        // get form element manager
        $formElements = $this->getServiceManager()->get('FormElementManager');
        // create form element
        $factory->setFormElementManager($formElements);
        // create zend form
        $form = $factory->createForm($formConfig);

        return $form;
    }

    /**
     * @return \MelisCmsCategory2\Service\MelisCmsCategoryService
     */
    private function getCategoryService()
    {
        return $this->getServiceManager()->get('MelisCmsCategory2Service');
    }
    /**
     * @return \MelisCmsCategory2\Model\Tables\MelisCmsCategory2SitesTable
     */
    private function getCatSiteTable()
    {
        return $this->getServiceManager()->get('MelisCmsCategory2SitesTable');
    }
    /**
     * @return \MelisCmsCategory2\Model\Tables\MelisCmsCategory2SitesTable
     */
    private function getCategory2Table()
    {
        return $this->getServiceManager()->get('MelisCmsCategory2Table');
    }
    /**
     * This will pop an error after validating the form
     * @param array $errors
     * @return array
     */
    private function formatErrorMessage($formConfig , $errors = array())
    {
        $formConfig = $formConfig['elements'] ?? null;
        if (! empty($formConfig)) {
            foreach ($errors as $keyError => $valueError)
            {
                foreach ($formConfig as $keyForm => $valueForm)
                {
                    if ($valueForm['spec']['name'] == $keyError &&
                        !empty($valueForm['spec']['options']['label']))
                        $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                }
            }
        }

        return $errors;
    }


}