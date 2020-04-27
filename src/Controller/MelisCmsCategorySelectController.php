<?php
/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */
namespace MelisCmsCategory2\Controller;

use Laminas\View\Model\ViewModel;
use Laminas\Session\Container;
use MelisCore\Controller\AbstractActionController;

class MelisCmsCategorySelectController extends AbstractActionController
{
    public function renderCategorySelectModalAction()
    {
        // view model class
        $view = new ViewModel();
        //get parameters of this request
        $params   = $this->getQueryParams();
        $melisKey = $params['melisKey'] ?? null;
        $id       = $params['id'] ?? null;
        //return variable in view
        $view->melisKey = $melisKey;
        $view->id       = $id;


        return $view;
    }
    public function renderCategorySelectModalContentAction()
    {
        // view model class
        $view = new ViewModel();
        //get parameters of this request
        $params   = $this->getQueryParams();
        $melisKey = $params['melisKey'] ?? null;
        $id       = $params['id'] ?? null;
        // get current locale
        $container = new Container('meliscore');
        $locale = $container['melis-lang-locale'];
        //get locale data
        $langTable = $this->getServiceManager()->get('MelisEngineTableCmsLang');
        // get all available lang
        $langData = $langTable->fetchAll()->toArray();
        //get site filter form
        $melisConfig = $this->getServiceManager()->get('MelisConfig');
        $siteFilterForm = $this->createForm($melisConfig->getItem('/meliscategory/forms/meliscategory_category_select_site_filter_form'));
        $langFilter = $siteFilterForm->get('categorySelectLangFilter');
        $tmpdata = [];
        if (! empty($langData)) {
            foreach ($langData as $idx => $val) {
                $tmpdata[$val['lang_cms_locale']] = $val['lang_cms_name'];
            }
        }
        // set a new value options for lang filter
        $langFilter->setValueOptions($tmpdata);
        // set lang filter to current BO lang
        $langFilter->setValue($locale);

        //return variable in view
        $view->melisKey = $melisKey;
        $view->id       = $id;
        $view->locale   = $locale;
        $view->siteFilterForm = $siteFilterForm;
        return $view;
    }
    private function getQueryParams()
    {
        $request = $this->getRequest();
        $params = $request->getQuery();
        return $params;
    }
    private function createForm($formConfig)
    {
        $factory        = new \Laminas\Form\Factory();
        $formElements   = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $form = $factory->createForm($formConfig);
        return $form;
    }
}