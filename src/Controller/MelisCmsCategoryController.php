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

class MelisCmsCategoryController extends AbstractActionController
{
    const PLUGIN_INDEX = 'melis_cms_categories_v2';
    const TOOL_KEY = 'meliscommerce_categories';

    /**
     * Render Category Page Zone
     * This also return if the zone if visible or hidden depend on request
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCategoryAction()
    {
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $catId = $this->params()->fromQuery('catId');
        $catFatherId = $this->params()->fromQuery('catFatherId');

        // reference that will be used anywhere, for now it will be used in documents
        $container = new Container('meliscommerce');
        $container['documents'] = array('docRelationType' => 'category', 'docRelationId' => $catId);

        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->hide = (!is_null($catId)||!is_null($catFatherId)) ? '' : 'hidden';
        return $view;
    }

    /**
     * Render Category Header
     * This also return the Title of the Zone
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCategoryHeaderAction(){
        $view = new ViewModel();
        $translator = $this->serviceLocator->get('translator');
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $catId = $this->params()->fromQuery('catId');
        $catFatherId = $this->params()->fromQuery('catFatherId');
        
        if (!empty($catId))
        {
            // Getting Current Langauge ID
            $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
            $langId = $melisTool->getCurrentLocaleID();
            
            $melisComCategoryService = $this->getServiceLocator()->get('MelisCmsCategory2Service');
            $categoryData = $melisComCategoryService->getCategoryById($catId);
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
     * @return \Zend\View\Model\ViewModel
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
            $melisComCategoryService = $this->getServiceLocator()->get('MelisCmsCategory2Service');
            $categoryData = $melisComCategoryService->getCategoryById($catId);
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCategoryFormTranslationsAction(){

        $view = new ViewModel();
        $catId = $this->params()->fromQuery('catId');
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $currentLocale = $this->getRequest()->getQuery('currentLocale');
        if (!empty($catId)){
            // Getting Category Translations
            $melisComCategoryService = $this->getServiceLocator()->get('MelisCmsCategory2Service');
            $categoryData = $melisComCategoryService->getCategoryById($catId);
            $view->categoryTrans = $categoryData->getTranslations();
        }

        // Getting Category Translations Form from App.forms.php Config
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscategory/forms/meliscategory_categories/meliscategory_categories_category_information_form','meliscategory_categories_category_information_form');
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
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
        $ecomLangtable = $this->serviceLocator->get('MelisEngineTableCmsLang');
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

        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        // Getting Category Translations Form from App.forms.php Config
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscategory/forms/meliscategory_categories/meliscategory_categories_date_validty_form','meliscategory_categories_date_validty_form');
        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $propertyForm = $factory->createForm($appConfigForm);

        if (!empty($catId)){

            // Get the locale used from meliscore session
            $container = new Container('meliscore');
            $locale = $container['melis-lang-locale'];
            $melisTranslation = $this->getServiceLocator()->get('MelisCoreTranslation');

            $melisComCategoryService = $this->getServiceLocator()->get('MelisCmsCategory2Service');
            $categoryData = $melisComCategoryService->getCategoryById($catId);
            $category = $categoryData->getCategory();

            $validFrom = ((string) $category->cat2_date_valid_start != '0000-00-00 00:00:00') ?
                strftime($melisTranslation->getDateFormatByLocate($locale), strtotime($category->cat2_date_valid_start)) : null;
            $validTo = ((string) $category->cat2_date_valid_end  != '0000-00-00 00:00:00') ?
                strftime($melisTranslation->getDateFormatByLocate($locale), strtotime($category->cat2_date_valid_end)) : null;

            if (!is_null($validFrom))
            {
                $validFrom = explode(' ', $validFrom);
                $validFrom = (string) $validFrom[0] == '01/01/1970' ? '' : $validFrom[0];
            }


            if (!is_null($validTo))
            {
                $validTo = explode(' ', $validTo);
                $validTo = (string) $validTo[0] == '01/01/1970' ? '' : $validTo[0];
            }

            $category->cat_date_valid_start = (!is_null($validFrom)) ? $validFrom : null;
            $category->cat_date_valid_end = (!is_null($validTo)) ? $validTo : null;

            $propertyForm->bind($category);
        }

        $view = new ViewModel();
        $catId = $this->params()->fromQuery('catId');
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view->setVariable('meliscommerce_categories_date_validty_form', $propertyForm);
        $view->datepickerInit = $melisTool->datePickerInit('categoryValidateDates');
        return $view;
    }

    /**
     * Render Category Countries
     * This also return the Countries of the category depend on request
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCategoryFormSitesAction()
    {
        $catId = $this->params()->fromQuery('catId');
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $siteSelectedId = $this->getRequest()->getQuery('selectedSiteId');

        $sites = array();
        if (!empty($catId)){

            $catSiteTbl = $this->getServiceLocator()->get('MelisCmsCategory2SitesTable');
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
        $sitesTable = $this->getServiceLocator()->get('MelisEngineTableSite');
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
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCategoryFormStatusAction(){
        $view = new ViewModel();
        $catId = $this->params()->fromQuery('catId');
        $melisKey = $this->params()->fromRoute('melisKey', '');

        if (!empty($catId)){
            $melisComCategoryService = $this->getServiceLocator()->get('MelisCmsCategory2Service');
            $categoryData = $melisComCategoryService->getCategoryById($catId);
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
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
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCategoryTabProductsAction(){

        $translator = $this->serviceLocator->get('translator');
        $activateTab = $this->params()->fromQuery('activateTab');
        $catId = $this->params()->fromQuery('catId');

        //Get category product count
        $prodCatTable = $this->getServiceLocator()->get('MelisEcomProductCategoryTable');
        $prodCount = $prodCatTable->getCategoryProductCount($catId)->current()->count;

        // Getting Category Products Table on config
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
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
     * @return \Zend\View\Model\JsonModel
     */
    public function saveCategoryAction()
    {
        $translator = $this->getServiceLocator()->get('translator');
        $request = $this->getRequest();
        $success = 0;
        $textTitle = $translator->translate('tr_melis_cms_category_v2');
        $message   = $translator->translate('tr_meliscms_categories_err_category_save_unable');
        $errors    = [];
        $logTypeCode = "CMS_CATEGORY2_SAVE";
        $id = null;
        $createdId = null;
        $passedCatId = null;
        $customError = [];
        if($request->isPost()) {
            $this->getEventManager()->trigger('meliscms_category_save_start', $this, $request);
            $currentUserLoggedIn = $this->getLoggedInUserInfo();
            $userId              = $currentUserLoggedIn->usr_id ?? null;
            $categoryService     = $this->getCategoryService();
            $postValues          = get_object_vars($request->getPost());
            $catTranslationData  = $postValues['cat_trans'] ?? null;
            $catSitesData        = $postValues['cat_sites'] ?? null;
            if (empty($catSitesData)) {
                $errors[] = [
                    'Site'  => $translator->translate('tr_meliscms_categories_select_site'),
                    'label' => $translator->translate('tr_meliscategory_categories_category_countries'),
                ];
            }
            $passedCatId         = $postValues['cat_id'] ?? null;
            $dateActive          = str_replace(' ',null,str_replace('/','-',$postValues['cat_date_valid_start']) ?? null);
            $dateInactive        = str_replace(' ',null,str_replace('/','-',$postValues['cat_date_valid_end']) ?? null);
            // melis-core config
            $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
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
                $status         = $postValues['cat_status'] ?? null;
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
                    // rename the tmp folder
                    $tmpPath = $_SERVER['DOCUMENT_ROOT'] . "/media/categories/tmp";
                    if (file_exists($tmpPath)) {
                        rename ($tmpPath, $_SERVER['DOCUMENT_ROOT'] . "/media/categories/$categoryId");
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
                        $catSiteTbl->deleteByField('cats2_cat2_id',$passedCatId);
                        // save data
                        foreach( $catSitesData as $siteId) {
                            // all selected sites
                            if ($siteId != -1){
                                $categorySiteId = $categoryService->saveCategorySites($categoryId, $siteId, $passedCatId);
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

                    $mediaTable = $this->getServiceLocator()->get('MelisCmsCategory2MediaTable');
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
                    $success = 1;
                    $message = 'tr_meliscms_categories_err_category_save_success';
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
        }

        if (empty($catSitesData)) {
            $customError['site'] = 1;
        }
        if (! empty($passedCatId)) {
            $logTypeCode = "CMS_CATEGORY2_UPDATE";
            $message = 'tr_meliscms_categories_err_category_update_ok';
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
     * This method get Category Data form the Post Data
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function getCategoryAction(){

        $translator = $this->getServiceLocator()->get('translator');

        $success = 0;
        $errors = array();

        $request = $this->getRequest();

        $catData = array();

        if($request->isPost()) {

            $postValues = get_object_vars($request->getPost());

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
            $melisCoreAuth = $this->getServiceLocator()->get('MelisCoreAuth');
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
                'cat_status' => $postValues['cat_status'],
                'cat_date_valid_start' => ($validFrom) ? date('Y-m-d H:i:s', strtotime($validFrom)) : null,
                'cat_date_valid_end' => ($validTo) ? date('Y-m-d H:i:s', strtotime($validTo)) : null,
                'cat_date_edit' => date('Y-m-d H:i:s'),
                'cat_user_id_edit' => $userId
            );

            // This is for New Catgory entry
            if (!$catId){
                // Getting the Last Order number as new order of the Category
                $melisEcomCategoryTable = $this->getServiceLocator()->get('MelisCmsCategryTable');
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

    public function getAllToolTipColumns()
    {
        $columns = array(
            $this->getTranslation('tr_meliscommerce_product_tooltip_col_id') => array(
                'class' => 'center thClassColId',
                //'rowspan' => '2',
                'style' => 'width:10px;',
            ),
            ' ' => array(
            ),
        );

        return $columns;
    }

    /**
     * This method get Category Coutnries for the Post Data
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function getCategoryCountriesAction(){
        $translator = $this->getServiceLocator()->get('translator');

        $success = 0;
        $errors = array();

        $request = $this->getRequest();

        $catCountriesData = array();

        if($request->isPost()) {
            $postValues = get_object_vars($request->getPost());

            // Category Countries Data Preparation
            if (!empty($postValues['cat_country'])){
                $catCountries = $postValues['cat_country'];
                foreach ($catCountries As $val){
                    array_push($catCountriesData, array('ccat_country_id' => $val));
                }
            }else{
                $errors['categoryCountries'] = array(
                    'label' => $translator->translate('tr_meliscommerce_categories_category_countries'),
                    'noSelected' => $translator->translate('tr_meliscommerce_categories_category_countries_no_selected')
                );
            }
        }

        if (empty($errors)){
            $success = 1;
        }

        $result = array(
            'success' => $success,
            'errors' => array('cat_countries_err' => $errors),
            'datas' => array('cat_countries' => $catCountriesData),
        );

        return new JsonModel($result);
    }

    /**
     * This method validate and get Category Translation from the Post Data
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function validateCategoryTranslationsAction(){

        $translator = $this->getServiceLocator()->get('translator');

        $success = 0;
        $errors = array();

        $request = $this->getRequest();

        $catTrans = array();

        if($request->isPost()) {

            $postValues = get_object_vars($this->getRequest()->getPost());
            
            if ($postValues['cat_father_cat_id'] == '-1'){
                $type = 'catalog';
            }else{
                $type = 'category';
            }

            $catTransData = $postValues['cat_trans'];

            $hasCategoryName = false;

            if (!empty($catTransData))
            {
                $ecomSeotable = $this->serviceLocator->get('MelisEcomSeoTable');
                $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
                $appConfigForm = $melisMelisCoreConfig->getFormMergedAndOrdered('meliscommerce/forms/meliscommerce_categories/meliscommerce_categories_category_information_form','meliscommerce_categories_category_information_form');

                $factory = new \Zend\Form\Factory();
                $formElements = $this->serviceLocator->get('FormElementManager');
                $factory->setFormElementManager($formElements);

                
                foreach ($catTransData As $val)
                {

                    $propertyForm = $factory->createForm($appConfigForm);
                    $propertyForm->setData($val);

                    if ($propertyForm->isValid())
                    {
                        // checking for white space
                        if (!ctype_space(urldecode($val['catt_name']))&&!empty($val['catt_name']))
                        {
                            if (strlen($val['catt_name'])>100)
                            {
                                $errors['catt_name'] = array(
                                    'label' => $translator->translate('tr_meliscommerce_categories_category_information_form_cat_name'),
                                    'NotEmpty' => $translator->translate('tr_meliscommerce_categories_input_too_long_100')
                                );

                            }
                            else
                            {
                                
                                $hasCategoryName = true;
                                array_push($catTrans, $propertyForm->getData());
                            }
                        }
                        else 
                        {
                            array_push($catTrans, $propertyForm->getData());
                        }
                    }
                    else
                    {
                        $errors = $propertyForm->getMessages();

                        $appConfigFormElements = $appConfigForm['elements'];

                        foreach ($errors as $keyError => $valueError)
                        {
                            foreach ($appConfigFormElements as $keyForm => $valueForm)
                            {
                                if ($valueForm['spec']['name'] == $keyError &&
                                    !empty($valueForm['spec']['options']['label']))
                                    $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
                            }
                        }
                    }
                }
            }

            // if there is no any entry for Category Name Translation, this will cause an error
            if (!$hasCategoryName)
            {
                $errors['catt_name'] = array(
                    'label' => $translator->translate('tr_meliscommerce_categories_category_information_form_cat_name'),
                    'NotEmpty' => $translator->translate('tr_meliscommerce_categories_err_'.$type.'_name_required_atleast_one')
                );
            }
        }

        if (empty($errors)){
            $success = 1;
        }
        
        $result = array(
            'success' => $success,
            'errors' => array('cat_trans_err' => $errors),
            'datas' => array('cat_trans' => $catTrans),
        );

        return new JsonModel($result);
    }

    /**
     * This method validate and get Category SEO from the Post Data
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function validateCategorySeoAction(){

        $request = $this->getRequest();
        $postValues = get_object_vars($this->getRequest()->getPost());
        $postValues = $this->getTool()->sanitizeRecursive($postValues);

        $melisComSeoService = $this->getServiceLocator()->get('MelisComSeoService');
        $result = $melisComSeoService->validateSEOData('category', $postValues['category_seo']);

        return new JsonModel($result);
    }

    /**
     * This method will Delete/Remove Product form Category Products
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function deleteCategoryAction()
    {
        $translator = $this->getServiceLocator()->get('translator');
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
            $postValues = get_object_vars($this->getRequest()->getPost());
            $postValues = $this->getTool()->sanitizeRecursive($postValues);
            $catId = (int) $postValues['cat_id'];
         //   $catId = 7;
            $categoryTable = $this->getCategory2Table();
            $categoryMediaSvc = $this->getServiceLocator()->get('MelisCmsCategory2MediaService');
            $categorySvc = $this->getServiceLocator()->get('MelisCmsCategory2Service');
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
     * This method Saving the new order of the Product from the category Product Listing
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function reOrderCategoryProductsAction(){
        $translator = $this->getServiceLocator()->get('translator');

        $request = $this->getRequest();
        // Default Values
        $errors = array();
        $status  = 0;
        $textTitle = $translator->translate('tr_meliscommerce_categories_category_products_ordered');
        $textMessage = '';

        if($request->isPost()) {
            $postValues = get_object_vars($request->getPost());

            $catPrdOrderData = explode(',', $postValues['catPrdOrderData']);

            $melisComCategoryService = $this->getServiceLocator()->get('MelisCmsCategory2Service');

            foreach ($catPrdOrderData As $val){
                $catPrdTemp = explode('-', $val);

                // Saving new Product Order
                $melisComCategoryService->updateCategoryProductsOrdering($catPrdTemp[0], ($catPrdTemp[1] + 1));
            }

            $status = 1;
        }

        $response = array(
            'success' => $status,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
        );

        return new JsonModel($response);
    }

    /**
     * Removing Product from categoru Prodcuts List
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function removeCategoryProductAction(){
        $translator = $this->getServiceLocator()->get('translator');

        $request = $this->getRequest();
        // Default Values
        $pcatId = null;
        $errors = array();
        $status  = 0;
        $textTitle = 'tr_meliscommerce_categories_category_products_removed';
        $textMessage = '';
        $logTypeCode = '';

        if($request->isPost())
        {
            $postValues = get_object_vars($request->getPost());

            $melisComCategoryService = $this->getServiceLocator()->get('MelisCmsCategory2Service');

            $pcatId = $postValues['pcat_id'];

            if ($postValues['parent_id'] == '-1'){
                $type = 'catalog';
            }else{
                $type = 'category';
            }
            // Log Type Code
            $logTypeCode = 'ECOM_'.strtoupper($type).'_REMOVE_PRODUCT';

            $textTitle = 'tr_meliscommerce_categories_'.$type.'_products_removed';

            $result = $melisComCategoryService->deleteCategoryProduct($pcatId);

            if ($result)
            {
                $status = 1;
                $textMessage = 'tr_meliscommerce_categories_'.$type.'_products_removed_success';
            }
            else
            {
                $textMessage = 'tr_meliscore_error_message';
            }
        }

        $response = array(
            'success' => $status,
            'textTitle' => $textTitle,
            'textMessage' => $textMessage,
            'errors' => $errors,
        );

        if ($status){
            $this->getEventManager()->trigger('meliscommerce_category_product_remove_end',
                $this, array_merge($response, array('typeCode' => $logTypeCode, 'itemId' => $pcatId)));
        }

        return new JsonModel($response);
    }

    /**
     * Render Category Product List Export button
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCategoryProductListExportAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $catId = $this->params()->fromQuery('catId');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        $view->catId = $catId;
        return $view;
    }

    /**
     * Render Category Product List Refresh button
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCategoryProductListRefreshAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * Render Category Product List View Button
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCategoryProductListViewAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * Render Category Product List Remove/Delete Button
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function renderCategoryProductListRemoveAction(){
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $view = new ViewModel();
        $view->melisKey = $melisKey;
        return $view;
    }

    /**
     * This method return the Product List affected to the Category
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function getCategoryProductListAction(){

        $colId = array();
        $dataCount = 0;
        $draw = 0;
        $tableData = array();

        $catId = $this->getRequest()->getPost('catId');
        $catLangLocale = $this->getRequest()->getPost('catLangLocale');

        if ($catId){

            // Get the locale used from meliscore session
            $container = new Container('meliscore');
            $locale = $container['melis-lang-locale'];

            $melisTranslation = $this->getServiceLocator()->get('MelisCoreTranslation');

            // Getting Current Langauge ID
            $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
            $langId = $melisTool->getCurrentLocaleID();

            // Tooltip Service Manager
            $viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');
            $toolTipTable = $viewHelperManager->get('ToolTipTable');

            // Document Relation Service Manager
            $melisEcomDocRelationsTable = $this->getServiceLocator()->get('MelisEcomDocRelationsTable');

            // Documents Service
            $docSvc = $this->getServiceLocator()->get('MelisComDocumentService');

            // Getting Category Products
            $melisComCategoryService = $this->getServiceLocator()->get('MelisCmsCategory2Service');
            $categoryProducts = $melisComCategoryService->getCategoryProductsById($catId, $langId);

            // Product Service
            $productService = $this->getServiceLocator()->get('MelisComProductService');

            // Tooltip anchor
            $toolTipTextTag = '<a id="row-%s" class="toolTipCatHoverEvent tooltipTable" data-productId="%s" data-hasqtip="1" aria-describedby="qtip-%s">%s</a>';
            $ctr = 0;
            $prodImage      = '<img src="%s" width="60" height="60" class="img-rounded img-responsive"/>';
            foreach ($categoryProducts As $val){

                $categoryProduct = array();
                $productId = $val->getId();
                // Table row Datas

                $categoryProduct['pcat_order'] = null;
                $categoryProduct['DT_RowClass'] = 'is-draggable';

                $product = $val->getProduct();
                if(!empty($product)) {
                    // Getting Date creation
                    $categoryProduct['prd_date_creation'] = $product->prd_date_creation;

                    // Getting Product Status
                    if ($product->prd_status == 1) {
                        $categoryProduct['prd_status'] = '<i class="fa fa-circle text-success"></i>';
                    } else {
                        $categoryProduct['prd_status'] = '<i class="fa fa-circle text-danger"></i>';
                    }

                    // Getting Product Name
                    $productStr = $melisTool->escapeHtml($productService->getProductName($productId, $langId));
                    $categoryProduct['prd_id'] = $productId;

                    // Getting Product Order
                    $productCategories = $val->getCategories();

                    foreach ($productCategories As $cval) {
                        if ($cval->pcat_cat_id == $catId) {
                            $categoryProduct['pcat_order'] = $cval->pcat_order;
                            $categoryProduct['DT_RowId'] = $cval->pcat_id;
                            $categoryProduct['DT_RowAttr'] = array('data-productid' => $productId, 'data-productname' => $productStr);
                        }

                    }

                    // GET PRODUCT IMAGE
                    $categoryProduct['prd_img'] = sprintf($prodImage, $docSvc->getDocDefaultImageFilePath('product', $productId));

                    $categoryProduct['prd_date_creation'] = strftime($melisTranslation->getDateFormatByLocate($locale), strtotime($categoryProduct['prd_date_creation']));

                    // Setting the tooltip of the product name
                    $toolTipTable->setTable('catProductTable' . $productId, 'table-row-' . ($ctr + 1), '');
                    $toolTipTable->setColumns($this->getToolTipColumns());

                    /* $categoryProduct['prd_name'] = sprintf($toolTipTextTag, ($ctr+1), $productId, ($ctr+1), $productStr) . $toolTipTable->render(); */
                    // Detect if Mobile remove qTipTable
                    $useragent = $_SERVER['HTTP_USER_AGENT'];
                    if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
                        $categoryProduct['prd_name'] = sprintf($toolTipTextTag, ($ctr + 1), $productId, ($ctr + 1), $productStr);
                    } else {
                        $categoryProduct['prd_name'] = sprintf($toolTipTextTag, ($ctr + 1), $productId, ($ctr + 1), $productStr) . $toolTipTable->render();
                    }


                    $ctr++;

                    array_push($tableData, $categoryProduct);
                }
            }
        }

        return new JsonModel(array(
            'draw' => (int) $draw,
            'recordsTotal' => $dataCount,
            'recordsFiltered' =>  $dataCount,
            'data' => $tableData,
        ));
    }

    public function productsExportToCsvAction()
    {
        $catId = $this->params()->fromQuery('catId');

        // Getting Current Langauge ID
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $langId = $melisTool->getCurrentLocaleID();

        // Product Service
        $productService = $this->getServiceLocator()->get('MelisComProductService');

        // Getting Category Products
        $melisComCategoryService = $this->getServiceLocator()->get('MelisCmsCategory2Service');
        $categoryProducts = $melisComCategoryService->getCategoryProductsById($catId);

        $categoryProductsData = array();
        foreach ($categoryProducts As $val){

            $product = $val->getProduct();
            $productId = $val->getId();

            array_push($categoryProductsData, array(
                'prd_id' => $productId,
                'prd_status' => $product->prd_status,
                'prd_name' => $productService->getProductName($productId, $langId),
                'prd_date_added' => $product->prd_date_creation
            ));
        }
        return $melisTool->exportDataToCsv($categoryProductsData);
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
        $translator = $this->getServiceLocator()->get('translator');
        $text = vsprintf($translator->translate($key), $args);

        return $text;
    }

    /**
     * Return the Columns of the Tooltip
     *
     * @return string[][]
     */
    public function getToolTipColumns()
    {
        $columns = array(
            $this->getTranslation('tr_meliscommerce_product_tooltip_col_id') => array(
                'class' => 'center thClassColId',
                //'rowspan' => '2',
                'style' => 'width:10px;',
            ),
            ' ' => array(
            ),
            $this->getTranslation('tr_meliscommerce_product_tooltip_col_image') => array(
                'class' => 'center',
                //'rowspan' => '2',
                'style' => 'width:100px;',
            ),
            $this->getTranslation('tr_meliscommerce_product_tooltip_col_sku') => array(
                'class' => 'text-left',
                //'rowspan' => '2',
            ),

            $this->getTranslation('tr_meliscommerce_product_tooltip_col_attributes') => array(
                'class' => 'text-left',
                //'rowspan' => '2',
            ),

            $this->getTranslation('tr_meliscommerce_product_tooltip_col_country') => array(
                'class' => 'text-left',
                //'rowspan' => '2',
            ),
            $this->getTranslation('tr_meliscommerce_product_tooltip_col_price') => array(
                'class' => 'text-right',
                //'rowspan' => '2',
                'style' => 'width:100px;',
            ),

            $this->getTranslation('tr_meliscommerce_product_tooltip_col_stocks') => array(
                'class' => 'text-right',
                //'rowspan' => '2',
                'style' => 'width:20px;',
            ),

        );

        return $columns;
    }

    private function getTool()
    {
        $tool =  $this->getServiceLocator()->get('MelisCoreTool');
        return $tool;
    }
    /**
     * Album/src/Album/Controller/AlbumToolController.php
     * Returns the data of the current user
     * @return null
     */
    private function getLoggedInUserInfo()
    {
        $authService = $this->getServiceLocator()->get('MelisCoreAuth');
        if($authService->hasIdentity()) {
            return $authService->getIdentity();
        }

        return null;
    }

    /**
     * Return zend form
     * @param $formConfig
     * @return \Zend\Form\ElementInterface
     */
    private function getForm($formConfig)
    {
        // zend form factory
        $factory = new \Zend\Form\Factory();
        // get form element manager
        $formElements = $this->serviceLocator->get('FormElementManager');
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
        return $this->getServiceLocator()->get('MelisCmsCategory2Service');
    }
    /**
     * @return \MelisCmsCategory2\Model\Tables\MelisCmsCategory2SitesTable
     */
    private function getCatSiteTable()
    {
        return $this->getServiceLocator()->get('MelisCmsCategory2SitesTable');
    }
    /**
     * @return \MelisCmsCategory2\Model\Tables\MelisCmsCategory2SitesTable
     */
    private function getCategory2Table()
    {
        return $this->getServiceLocator()->get('MelisCmsCategory2Table');
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