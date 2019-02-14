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
    public function renderMiniMediaModalContainerAction()
    {
        $view = new ViewModel();
        $request = $this->getRequest();
        $queryParams = $request->getQuery();
        $id = $queryParams['id'] ?? null;
        $melisKey = $queryParams['melisKey'];


        $view->melisKey = $melisKey;
        $view->id = $id;
        return $view;
    }
    public function browseMediaAction()
    {
        //media path
        $request = $this->getRequest();
        $uri     = $request->getUri();
        $host    = $uri->getHOst();
        $scheme  = $uri->getScheme();
        $mediaPath = $_SERVER['DOCUMENT_ROOT'] . "/media/";
        $queryParams = $request->getQuery();
        $fileType = $queryParams['fileType'] ?? 'file';
        $images = [];
        if (file_exists($mediaPath)) {
            $extensionPattern = "";
            if ($fileType == "image") {
                $extensionPattern = "*.{png,jpeg,jpg}";
            }

            $files = glob($mediaPath . $extensionPattern ,GLOB_BRACE);
            if (! empty($files)) {
               foreach ($files as $idx => $file) {
                   //remove path
                   $image[$idx] = str_replace($mediaPath,null,$file);
               }
            }
        }


        $view = new ViewModel();

        $view->melisKey = $this->getMelisKey();
        $view->images = $image;
        $view->scheme = $scheme;
        $view->host   = $host;
        return $view;
    }
}