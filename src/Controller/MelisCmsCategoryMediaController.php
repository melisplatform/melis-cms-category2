<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2\Controller;

use Laminas\Di\ServiceLocatorInterface;
use Laminas\Session\Container;
use Laminas\Validator\File\IsImage;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use MelisCore\Controller\MelisAbstractActionController;

class MelisCmsCategoryMediaController extends MelisAbstractActionController
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
       // $this->getServiceManager() = $serviceLocator;
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
        $this->categoryTbl      = $this->getServiceManager()->get('MelisCmsCategory2Table');
        // media category table
        $this->mediaCategoryTbl = $this->getServiceManager()->get('MelisCmsCategory2MediaTable');
        // sites category table
        $this->sitesCategoryTbl = $this->getServiceManager()->get('MelisCmsCategory2SitesTable');
        // translations category table
        $this->transCategoryTbl = $this->getServiceManager()->get('MelisCmsCategory2TransTable');
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
        $config = $this->getServiceManager()->get('MelisCoreConfig');
        $formConfig = $config->getItem('meliscategory/forms/meliscategory_categories/meliscategory_media_upload_form');

        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
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
        $categoryMediaTable = $this->getServiceManager()->get('MelisCmsCategory2MediaTable');
        $categoryMediaSvc = $this->getServiceManager()->get('MelisCmsCategory2MediaService');
        $categoryMediaData = [];
        // clear directory when adding a new category
        $categoryTable = $this->getServiceManager()->get("MelisCmsCategory2Table");
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
//            print_r($category2Session->getArrayCopy());
//            die;
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
        $categoryMediaTable = $this->getServiceManager()->get('MelisCmsCategory2MediaTable');
        $categoryMediaSvc = $this->getServiceManager()->get('MelisCmsCategory2MediaService');
        $categoryMediaData = [];
        $category2Session = new Container('melis_cms_category2');

        $categoryPath = "/media/categories/$categoryId/";
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
     * @return \Laminas\Form\ElementInterface
     */
    private function getForm()
    {
        $config = $this->getServiceManager()->get('MelisCoreConfig');
        $formConfig = $config->getItem('meliscategory/forms/meliscategory_categories/meliscategory_media_upload_form');

        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
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
        $title = 'tr_meliscms_categories_media_select_file';
        $category2Session = new Container('melis_cms_category2');
        $success = false;
        $message = [];
        $errors  = [];
        $tmpUpload = false;
        $maxFileSizeUpload = null;
        $imageError = false;
        $logTypeCode = 'CMS_CATEGORY2_FILE_ADD';
        $categoryTable = $this->getServiceManager()->get("MelisCmsCategory2Table");
        $categoryMediaSvc = $this->getServiceManager()->get('MelisCmsCategory2MediaService');
        $translator       = $this->getServiceManager()->get('translator');
        // this for adding
        if (empty($categoryId)) {
            $categoryId = "tmp";
        }
        if ($fileType == 'image') {
            $title = 'tr_meliscms_categories_media_select_image';
            $logTypeCode = 'CMS_CATEGORY2_IMAGE_ADD';
        }
        if ($request->isPost()) {
            $file = $request->getFiles('media_upload');

            if (! empty($file['tmp_name'])) {
                // file name
                $fileName = $file['name'];
                if ($fileType == 'image') {
                    // changed title
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

                            $message = "tr_meliscms_categories_upload_file_success";
                            if ($fileType == 'image') {
                                $logTypeCode = 'CMS_CATEGORY2_IMAGE_ADD';
                                $message = 'tr_meliscms_categories_upload_image_success';
                            }
                            if (! empty($tmpData)) {
                                // if data is not empty it means category is on the db
                                // then we will save the image on db
                                $data = [
                                    'catm2_type' => $fileType,
                                    'catm2_path' => "/media/categories/$categoryId/$fileName",
                                    'catm2_cat_id' => $categoryId
                                ];
                                $catMediaTbl = $this->getServiceManager()->get('MelisCmsCategory2MediaTable');
                                $catMediaTbl->save($data);
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

                        $message = 'tr_meliscms_categories_upload_image_fileIsImageNotDetected';
                    }

                } else {
                    $message = 'Permission denied';
                }
            } else {
                $maxFileSizeUpload = (($categoryMediaSvc->file_upload_max_size() / 1024) / 1024) ;
                $message = 'tr_meliscms_categories_file_size_limit';

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
        if (! is_null($maxFileSizeUpload)) {
            $response['textMessage'] =  sprintf($translator->translate($response['textMessage']),$maxFileSizeUpload);
        }


        return new JsonModel($response);
    }
    public function deleteFileAction()
    {
        $request = $this->getRequest();
        $toolSvc = $this->getServiceManager()->get('MelisCoreTool');
        $success = false;
        $logTypeCode = "CMS_CATEGORY2_FILE_DELETE";
        $errors  = [];
        $id      = null;
        $message = "tr_meliscms_categories_delete_file_ko";
        $title   = "tr_meliscms_categories_media_select_file";

        if ($request->isPost()){
            // media service
            $categoryMediaSvc = $this->getServiceManager()->get('MelisCmsCategory2MediaService');
            // post values
            $postvalues = $request->getPost()->toArray();
            $imageName  = $postvalues['imageName'] ??  null;
            $fileType   = $postvalues['fileType'] ?? null;
            $categoryId = $postvalues['categoryId'] ?? null;
            if ($fileType == 'image') {
                $title = 'tr_meliscms_categories_media_select_image';
            }

            if (! empty($imageName) && ! empty($categoryId)) {
                // delete category in db and delete files in the directory
                $fullPath = $_SERVER['DOCUMENT_ROOT'] . "/../public/media/categories/$categoryId/";
                $status = $categoryMediaSvc->deleteFile($fullPath,$imageName,$fileType);
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
        if ($id != "tmp") {
            // flash messenger
            $this->getEventManager()->trigger('meliscms_category2_save_end', $this, array_merge($response, array('typeCode' => $logTypeCode, 'itemId' => $id)));
        }

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