<?php
/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */
namespace MelisCmsCategory2\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

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
        //return variable in view
        $view->melisKey = $melisKey;
        $view->id       = $id;
        $view->locale   = $locale;
        return $view;
    }
    private function getQueryParams()
    {
        $request = $this->getRequest();
        $params = $request->getQuery();
        return $params;
    }
}