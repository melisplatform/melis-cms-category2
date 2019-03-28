<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2\Controller;

use Zend\Di\ServiceLocatorInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\Validator\File\IsImage;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class MelisCmsCategoryMediaController extends AbstractActionController
{
    /**
     * @var $uploadForm return the form
     */
    private $uploadForm;
    /**
     * @var $nextId return the expected id or next id
     */
    private $nextId;
    /**
     * @var $categoryTbl \MelisCmsCategory2\Model\Tables\MelisCmsCategory2Table
     */
    private $categoryTbl;
    /**
     * @var $mediaCategoryTbl \MelisCmsCategory2\Model\Tables\MelisCmsCategory2MediaTable
     */
    private $mediaCategoryTbl;
    /**
     * @var $sitesCategoryTbl \MelisCmsCategory2\Model\Tables\MelisCmsCategory2SitesTable
     */
    private $sitesCategoryTbl;
    /**
     * @var $sitesCategoryTbl \MelisCmsCategory2\Model\Tables\MelisCmsCategory2TransTable
     */
    private $transCategoryTbl;
    protected $serviceLocator = null;
    public function __construct()
    {
       // $this->serviceLocator = $serviceLocator;
      //  self::initilizeDependencies();
    }
    private function initilizeDependencies()
    {
        // set category tables
        self::setTables();
        // set the next id to be inserted by new data
        self::setNextId();
        // set uploadForm
        self::setUploadForm();

    }
    private function setTables()
    {
        // category2 table
        $this->categoryTbl      = $this->serviceLocator->get('MelisCmsCategory2Table');
        // media category table
        $this->mediaCategoryTbl = $this->serviceLocator->get('MelisCmsCategory2MediaTable');
        // sites category table
        $this->sitesCategoryTbl = $this->serviceLocator->get('MelisCmsCategory2SitesTable');
        // translations category table
        $this->transCategoryTbl = $this->serviceLocator->get('MelisCmsCategory2TransTable');
    }
    private function setNextId()
    {
        $lastInsertedId = $this->categoryTbl->getLastId()->current()->maxId ?? null;
        $expectedNextId = null;
        if (! empty($lastInsertedId)) {
            $this->nextId = (int) $lastInsertedId + 1;
        }
    }
    private function setUploadForm()
    {
        $config = $this->getServiceLocator()->get('MelisCoreConfig');
        $formConfig = $config->getItem('meliscategory/forms/meliscategory_categories/meliscategory_media_upload_form');

        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $form = $factory->createForm($formConfig);


        $this->uploadForm = $form;
    }

    /**
     * @return ViewModel
     */
    public function renderCategoryTabMediaAction()
    {
        $view = new ViewModel();

        $view->melisKey = $this->getMelisKey();
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderCategoryTabMediaHeaderAction()
    {
        $view = new ViewModel();

        $view->melisKey = $this->getMelisKey();
        return $view;
    }
    /**
     * @return ViewModel
     */
    public function renderCategoryTabMediaContentAction()
    {
        $view = new ViewModel();

        $view->melisKey = $this->getMelisKey();
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderCategoryTabMediaContentLeftAction()
    {
        $view = new ViewModel();

        $view->melisKey = $this->getMelisKey();
        return $view;
    }
    /**
     * @return ViewModel
     */
    public function renderCategoryTabMediaContentLeftImageAction()
    {
        $request = $this->getRequest();
        $query   = $request->getQuery();
        $categoryId = $query->catId ?? null;

        $view = new ViewModel();
        $view->categoryId = $categoryId;
        $view->melisKey = $this->getMelisKey();
        return $view;
    }
    /**
     * @return ViewModel
     */
    public function renderCategoryTabMediaContentLeftImageListAction()
    {
        $view = new ViewModel();
        $request = $this->getRequest();
        $query   = $request->getQuery();
        $category2Session = new Container('melis_cms_category2');
        $categoryId = $query->catId ?? null;
        $forAdding  = $query->forAdding ?? null;
        $categoryMediaTable = $this->getServiceLocator()->get('MelisCmsCategory2MediaTable');
        $categoryMediaSvc = $this->getServiceLocator()->get('MelisCmsCategory2MediaService');
        $categoryMediaData = [];
        // clear directory when adding a new category
        $categoryTable = $this->getServiceLocator()->get("MelisCmsCategory2Table");
        if ($forAdding) {
            $category2Session->getManager()->getStorage()->clear('melis_cms_category2');
            $categoryMediaSvc->removeCategoryDir();
        }
        if (! empty($categoryId) && $categoryId !== 'tmp') {
            $categoryMediaData = $categoryMediaSvc->getMediaFilesByCategoryId($categoryId,'image');
        } else {
            $mediaPath = $_SERVER['DOCUMENT_ROOT'] . "/media/categories/tmp/";
            $extensionPattern = "*.{png,jpeg,jpg,svg}";
           // $files = $categoryMediaSvc->getFilesInDir($mediaPath,$extensionPattern, true);

            $files = $category2Session['images'] ?? null;
            if (! empty($files)) {
                foreach ($files as $idx => $val) {
                    $categoryMediaData[$idx] = [
                        'catm2_path' => "/media/categories/tmp/$val",
                        'catm2_type' => 'image'
                    ];
                }
            }
        }


        $view->melisKey = $this->getMelisKey();
        $view->mediaData = $categoryMediaData;
        $view->categoryId = $categoryId;
        return $view;
    }
    /**5
     * @return ViewModel
     */
    public function renderCategoryTabMediaContentRightAction()
    {
        $view = new ViewModel();

        $view->melisKey = $this->getMelisKey();
        return $view;
    }

    /**
     * @return ViewModel
     */
    public function renderCategoryTabMediaContentRightFileAction()
    {
        $request = $this->getRequest();
        $query   = $request->getQuery();
        $categoryId = $query['catId'] ?? null;
        $categoryMediaTable = $this->getServiceLocator()->get('MelisCmsCategory2MediaTable');
        $categoryMediaSvc = $this->getServiceLocator()->get('MelisCmsCategory2MediaService');
        $categoryMediaData = [];
        $category2Session = new Container('melis_cms_category2');

        $categoryPath = null;
        if (! empty($categoryId) && $categoryId != 'tmp') {
            $categoryMediaData = $categoryMediaSvc->getMediaFilesByCategoryId($categoryId);
        } else {
            $categoryPath = "/media/categories/tmp/";
            $mediaPath = $_SERVER['DOCUMENT_ROOT'] . $categoryPath;
           // $mediaData = $categoryMediaSvc->getFilesInDir($mediaPath,null,true);
            $category2Session = new Container('melis_cms_category2');
            $mediaData = $category2Session['file'] ?? null;
            if (! empty($mediaData)) {
                foreach ($mediaData as $idx => $file) {
                    //remove path
                    $categoryMediaData[$idx] = [
                        'catm2_path' => $categoryPath . $file,
                        'catm2_type' => 'file',
                    ];
                }
            }
        }

        $view = new ViewModel();

        $view->melisKey   = $this->getMelisKey();
        $view->mediaData  = $categoryMediaData;
        $view->mediaPath  = $categoryPath;
        $view->categoryId = $categoryId;

        return $view;
    }

    /**
     * @return mixed
     */
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
        $queryParams = $request->getQuery();
        $fileType = $queryParams['fileType'] ?? 'file';

        $view = new ViewModel();
        $view->melisKey = $this->getMelisKey();
        $view->categoryMediaForm = $this->getForm();
        $view->fileType = $fileType;

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

    /**
     * this will upload the file on the given directory
     * @return JsonModel
     */
    public function uploadMediaAction()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $categoryId = $postData['catId'];
        $fileType = $postData['fileType'];
        $title = 'tr_melis_cms_category_v2';
        $category2Session = new Container('melis_cms_category2');
        $success = false;
        $message = [];
        $errors  = [];
        $tmpUpload = false;
        $imageError = false;
        $logTypeCode = 'CMS_CATEGORY2_FILE_ADD';
        $categoryTable = $this->getServiceLocator()->get("MelisCmsCategory2Table");
        $categoryMediaSvc = $this->getServiceLocator()->get('MelisCmsCategory2MediaService');
        $translator       = $this->getServiceLocator()->get('translator');
        // this for adding
        if (empty($categoryId)) {
            $categoryId = "tmp";
        }
        if ($request->isPost()) {
            $file = $request->getFiles('media_upload');
            if (! empty($file['tmp_name'])) {
                $fileName = $file['name'];
                if ($fileType == 'image') {
                    $imageValidator = new IsImage();
                    if (!$imageValidator->isValid($file)) {
                         $imageError = true;
                    }
                }
                $path = $_SERVER['DOCUMENT_ROOT'] . "/media";
                // check first if media directory is writable

                if (is_writable($path)) {
                    // create categories folder if not created
                    $path = $path . "/categories/";
                    if (! file_exists($path)) {
                        mkdir($path, 0777);
                    } else {
                        $message = 'Permission denied';
                    }
                    $categoryPath = $path ."$categoryId/";

                    // make folder for temporary
                    if (! file_exists($categoryPath)) {
                        mkdir($categoryPath, 0777);
                    } else {
                        $message = 'Permission denied';
                    }

                    if (!$imageError) {
                        $path = $path . "$categoryId/";
                        // move the file
                        $tmpFileName = $path.$fileName;
                        // check first if file exists
                        // if yes, we will rename the current one
                        if (file_exists($tmpFileName)) {
                            $fileName = $categoryMediaSvc->renameFileRec($tmpFileName);
                        }

                        // upload to specified folder
                        $success = $categoryMediaSvc->uploadFile($file,$path,$fileName);
                        if ($success === true) {
                            // if success then we will save the img on db
                            $tmpData = $categoryTable->getEntryById($categoryId)->current();
                            if (! empty($tmpData)) {
                                // if data is not empty it means category is on the db
                                // then we will save the image on db
                                $data = [
                                    'catm2_type' => $fileType,
                                    'catm2_path' => "/media/categories/$categoryId/$fileName",
                                    'catm2_cat_id' => $categoryId
                                ];
                                $catMediaTbl = $this->getServiceLocator()->get('MelisCmsCategory2MediaTable');
                                $catMediaTbl->save($data);
                                $message = "tr_meliscms_categories_upload_success";
                                if ($fileType == 'image') {
                                    $logTypeCode = 'CMS_CATEGORY2_IMAGE_ADD';
                                }
                            } else {
                                $files = [];
                                if ($fileType == 'image') {
                                    if (is_array($category2Session['images'])) {
                                        array_push($category2Session['images'],$fileName);
                                    } else {
                                        $category2Session['images'] = [$fileName];
                                    }
                                } else {
                                    if (is_array(  $category2Session['file'])) {
                                        array_push(  $category2Session['file'],$fileName);
                                    } else {
                                        $category2Session['file'] = [$fileName];
                                    }
                                }
                                $tmpUpload = true;
                            }
                        }
                    } else {
                        $message = $translator->translate('tr_meliscms_categories_upload_image_fileIsImageNotDetected');
                    }

                } else {
                    $message = 'Permission denied ';
                }
            } else {
                $message = "Permission denied ";
            }
        }

        $response = [
            'success' => $success,
            'textTitle' => $title,
            'textMessage' => $message,
            'errors' => $errors,
            'id' => $categoryId,
            'tmpUpload' => $tmpUpload
        ];
        if ($categoryId != 'tmp') {
            // flash messenger
            $this->getEventManager()->trigger('meliscms_category2_save_end', $this, array_merge($response, array('typeCode' => $logTypeCode, 'itemId' => $categoryId)));
        }

        return new JsonModel($response);
    }
    public function deleteFileAction()
    {
        $request = $this->getRequest();
        $toolSvc = $this->getServiceLocator()->get('MelisCoreTool');
        $success = false;
        $logTypeCode = "CMS_CATEGORY2_FILE_DELETE";
        $errors  = [];
        $id      = null;
        $message = "tr_meliscms_categories_delete_file_ko";
        $title   = "tr_melis_cms_category_v2";

        if ($request->isPost()){
            // media service
            $categoryMediaSvc = $this->getServiceLocator()->get('MelisCmsCategory2MediaService');
            // post values
            $postvalues = $toolSvc->sanitizeRecursive(get_object_vars($request->getPost()));
            $imageName  = $postvalues['imageName'] ??  null;
            $fileType   = $postvalues['fileType'] ?? null;
            $categoryId = $postvalues['categoryId'] ?? null;

            if (! empty($imageName) && ! empty($categoryId)) {
                // delete the file in directory and db
                $fullPath = $_SERVER['DOCUMENT_ROOT'] . "/../public/media/categories/$categoryId/";
                $status = $categoryMediaSvc->deleteFile($fullPath,$imageName);
                // default
                $message = "tr_meliscms_categories_confirm_delete_file_success";
                if ($fileType == 'image') {
                    $logTypeCode = "CMS_CATEGORY2_IMAGE_DELETE";
                    $message = 'tr_meliscms_categories_confirm_delete_image_success';
                }
                if ($status === true) {
                    $success = true;
                    $id = $categoryId;
                } else {
                    $message = "error";
                }
            }

        }

        $response = [
            'success' => $success,
            'textMessage' => $message,
            'textTitle'   => $title,
            'id'      => $id
        ];
        // flash messenger
        $this->getEventManager()->trigger('meliscms_category2_save_end', $this, array_merge($response, array('typeCode' => $logTypeCode, 'itemId' => $id)));

        return new JsonModel($response);
    }
    private function returnMBLabel($val)
    {
        $val  = trim($val);
        if (is_numeric($val))
            return $val;

        $last = strtolower($val[strlen($val)-1]);

        $val  = substr($val, 0, -1); // necessary since PHP 7.1; otherwise optional

        switch($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val = $val . "GB";
            case 'm':
                $val = $val . "MB";
            case 'k':
                $val = $val . "KB";
        }

        return $val;
    }
}