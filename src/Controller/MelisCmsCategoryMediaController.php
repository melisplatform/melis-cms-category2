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
        $view->categoryMediaForm = $this->getForm();

        return $view;
    }
    /**
     * This will get the form that was created in the
     * MelisCmsUserACcount/config/app.tools.php
     *
     * @param int $forUpdate
     * @return \Zend\Form\ElementInterface
     */
    private function getForm()
    {
        $config = $this->getServiceLocator()->get('MelisCoreConfig');
        $formConfig = $config->getItem('meliscategory/forms/meliscategory_categories/meliscategory_media_upload_form');

        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $form = $factory->createForm($formConfig);

        return $form;
    }

    public function uploadMediaAction()
    {
        $request = $this->getRequest();
        print_r($request);
        die;

        return [];
    }
}