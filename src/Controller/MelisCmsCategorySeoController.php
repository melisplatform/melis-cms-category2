<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2\Controller;

use Laminas\Session\Container;
use Laminas\Validator\File\IsImage;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use MelisCore\Controller\MelisAbstractActionController;

class MelisCmsCategorySeoController extends MelisAbstractActionController
{  

    /**
     * @return ViewModel
     */
    public function renderCategoryTabSeoAction()
    {
        $view = new ViewModel();

        $view->melisKey = $this->getMelisKey();
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderCategoryTabSeoHeaderAction()
    {
        $view = new ViewModel(); 
        $request = $this->getRequest();
        $query   = $request->getQuery(); 
        $categoryId = $query->catId ?? null;
 
        $view->categoryId = $categoryId;  
        $view->melisKey = $this->getMelisKey();
        return $view;
    }

    /**
     * renders the tabs content header left container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryTabSeoHeaderLeftAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', ''); 
        $request = $this->getRequest();
        $query   = $request->getQuery(); 
        $categoryId = $query->catId ?? null;

        $view->melisKey = $melisKey;
        $view->categoryId = $categoryId;  
        return $view;
    }
    /**
     * renders the tabs content header title
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryTabSeoHeaderTitleAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', ''); 
        $request = $this->getRequest();
        $query   = $request->getQuery(); 
        $categoryId = $query->catId ?? null;

        $view->melisKey = $melisKey;
        $view->categoryId = $categoryId;
        return $view;
    } 


    /**
     * renders the tab left content container
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryTabContentSeoDetailsMainAction()
    {
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');
        $request = $this->getRequest();
        $query   = $request->getQuery(); 
        $categoryId = $query->catId ?? null;

        $view->categoryId = $categoryId;
        $view->melisKey = $melisKey;
        return $view;
    }
    /**
     * renders the tab right content paragraphs
     * @return \Laminas\View\Model\ViewModel
     */
    public function renderCategoryTabSeoDetailsAction()
    {       
        $request = $this->getRequest();
        $query   = $request->getQuery(); 
        $categoryId = $query->catId ?? null; 
        $seoForm = $this->getCategorySeoForm();
        $categorySeoTable = $this->getServiceManager()->get('MelisCmsCategory2SeoTable');
        $data = ($categoryId) ? (array)$categorySeoTable->getEntryByField('category2_id', $categoryId)->toArray() : '';
        $lang_id = $this->getLangId();        
        $languages = $this->getOrderedLanguagesByCurrentLocale();
        $view = new ViewModel();
        $melisKey = $this->params()->fromRoute('melisKey', '');  
        $view->melisKey = $melisKey;
        $view->categoryId = $categoryId; 
        $view->languages = $languages;
        $view->lang_id = $lang_id;
        $view->data = $data;
        $view->seoForm = $seoForm;   
        return $view;
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

    private function getCategorySeoForm() 
    {
        $melisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $seoFormConfig = $melisCoreConfig->getFormMergedAndOrdered('meliscategory/forms/meliscategory_categories/meliscmscategory_seo_form', 'meliscmscategory_seo_form');
        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $seoForm = $factory->createForm($seoFormConfig);
        return $seoForm;
    }

    /**
     * @return mixed
     */
    private function getMelisKey()
    {
        return $this->params()->fromRoute('melisKey', '');
    }  
}