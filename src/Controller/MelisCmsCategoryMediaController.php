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

class MelisCmsCategoryMediaController extends AbstractActionController
{
    public function renderCategoryTabMediaAction()
    {
        $view = new ViewModel();

        $view->melisKey = $this->getMelisKey();
        return $view;
    }
    public function renderCategoryTabMediaHeaderAction()
    {
        $view = new ViewModel();

        $view->melisKey = $this->getMelisKey();
        return $view;
    }
    public function renderCategoryTabMediaContentAction()
    {
        $view = new ViewModel();

        $view->melisKey = $this->getMelisKey();
        return $view;
    }
    public function renderCategoryTabMediaContentLeftAction()
    {
        $view = new ViewModel();

        $view->melisKey = $this->getMelisKey();
        return $view;
    }
    public function renderCategoryTabMediaContentLeftImageAction()
    {
        $view = new ViewModel();

        $view->melisKey = $this->getMelisKey();
        return $view;
    }
    public function renderCategoryTabMediaContentLeftImageListAction()
    {
        $view = new ViewModel();

        $view->melisKey = $this->getMelisKey();
        return $view;
    }
    public function renderCategoryTabMediaContentRightAction()
    {
        $view = new ViewModel();

        $view->melisKey = $this->getMelisKey();
        return $view;
    }
    public function renderCategoryTabMediaContentRightFileAction()
    {
        $view = new ViewModel();

        $view->melisKey = $this->getMelisKey();
        return $view;
    }
    private function getMelisKey()
    {
       return $this->params()->fromRoute('melisKey', '');
    }

}