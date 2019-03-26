<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2\Service;

use MelisCommerce\Model\MelisCategory;
use MelisCore\Service\MelisCoreGeneralService;
use Zend\Stdlib\ArrayUtils;
/**
 *
 * This service handles the category system of MelisCommerce.
 *
 */
class MelisCmsCategoryMediaService  extends MelisCoreGeneralService
{
    /**
     * @var $categoryMediaTbl \MelisCmsCategory2\Model\Tables\MelisCmsCategory2MediaTable
     */
    public $categoryMediaTbl;

    /**
     * Remove a category directory base on the categoryId in ex. root/media/categories/[categoryId]
     * @param $categoryId
     * @param null $filename
     * @return mixed
     */
    public function removeCategoryDir($categoryId, $filename = null)
    {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        //service event start
        $arrayParameters = $this->sendEvent('melis_cms_categories_remove_category_dir_start', $arrayParameters);
        $categoryId = $arrayParameters['categoryId'];
        $filename = $arrayParameters['filename'];
        $results  = false;
        //implementation start
        $path = $_SERVER['DOCUMENT_ROOT'] . "/media/categories/$categoryId";
        if (file_exists($path)) {
            // get the files
            $iterator = new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new \RecursiveIteratorIterator($iterator,\RecursiveIteratorIterator::CHILD_FIRST);
            foreach($files as $file) {
                if ($file->isDir()){
                    // remove a directory
                    rmdir($file->getRealPath());
                } else {
                    // rmeove files
                    unlink($file->getRealPath());
                }
            }
            // remove the parent directory
            rmdir($path);
            $results = true;
        }

        //implementation end
        $arrayParameters['results'] = $results;
        //service event end
        $arrayParameters = $this->sendEvent('melis_cms_categories_remove_category_dir_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * Return files in a specific directory
     * @param $path
     * @param null $extensionPattern sample : *.{png,jpeg,jpg,svg} - for images, ( * ) all files including a directory
     * @param bool $filenameOnly
     * @param array $excludeExtensions sample : [exe,flv]
     * @return mixed
     */
    public function getFilesInDir($path,$extensionPattern = null,$filenameOnly = false,$excludeExtensions = [])
    {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        //service event start
        $arrayParameters = $this->sendEvent('melis_cms_categories_get_files_in_dir_start', $arrayParameters);
        $path       = $arrayParameters['path'];
        $extensionPattern = $arrayParameters['extensionPattern'] ?? "*";
        $filenameOnly = $arrayParameters['filenameOnly'];
        $excludeExtensions = $arrayParameters['excludeExtensions'];
        $results  = [];
        //implementation start
        if (file_exists($path) && is_writable($path)) {
            $files = glob($path . $extensionPattern ,GLOB_BRACE);
            if (! empty($files)) {
                foreach ($files as $idx => $file) {
                    // if filenameOnly set to true
                    if ($filenameOnly) {
                        $file =  str_replace($path,null,$file);
                    }
                    // get the path info of a file
                    $pathInfo = pathinfo($file);
                    if (isset($pathInfo['extension']) && !in_array($pathInfo['extension'],$excludeExtensions)) {
                        // put in the results variable
                        array_push($results,$file);
                    }

                }
            }
        } else {
            $results['errors'] = "Path [" . $path . "] does not exists or no permission";
        }

        $arrayParameters['results'] = $results;
        //service event end
        $arrayParameters = $this->sendEvent('melis_cms_categories_get_files_in_dir_end', $arrayParameters);

        return $arrayParameters['results'];

    }

    /**
     * This is to remove a file in a directory
     * this return bool
     * @param $path
     * @param $filename with extension
     * @return mixed
     */
    public function removeFileInDir($path,$filename)
    {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        //service event start
        $arrayParameters = $this->sendEvent('melis_cms_categories_get_files_in_dir_start', $arrayParameters);
        $path       = $arrayParameters['path'];
        $extensions = $arrayParameters['extensions'] ?? "*";
        $filename   = $arrayParameters['filename'];
        $results    = [];
        //implementation start
        if (file_exists($path) && is_writable($path)) {
            if (file_exists($path)) {
                // get the files
                $iterator = new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS);
                $files = new \RecursiveIteratorIterator($iterator,\RecursiveIteratorIterator::CHILD_FIRST);
                // remove file
                foreach($files as $file) {
                    $tmpFilename = $file->getFileName();
                    if ($tmpFilename == $filename) {
                        // rmeove files
                        unlink($file->getRealPath());
                        $results = true;
                    }
                }
            }
        } else {
            $results = "Path [" . $path . "] does not exists or no permission";
        }

        $arrayParameters['results'] = $results;
        //service event end
        $arrayParameters = $this->sendEvent('melis_cms_categories_get_files_in_dir_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * Upload a file on a directory
     * @param $file
     * @param $dirToSave
     */
    public function uploadFile($file,$dirToSave)
    {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        //service event start
        $arrayParameters = $this->sendEvent('melis_cms_categories_upload_file_start', $arrayParameters);
        $file      = $arrayParameters['file'];
        $dirToSave = $arrayParameters['dirToSave'];
        $results   = false;
        //implentation start
        if (isset($file['tmp_name']) && ! empty($file['tmp_name'])) {
            $uploadStatus = move_uploaded_file($file['tmp_name'],$dirToSave);
            $results = true;
        }
        $arrayParameters['results'] = $results;
        //service event end
        $arrayParameters = $this->sendEvent('melis_cms_categories_upload_file_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * This rename the file
     * @param $filepath
     * @param int $ctr
     * @return null|string
     */
    public function renameFileRec($filenamePath,$ctr = 1)
    {
        $pathInfo = pathinfo($filenamePath);
        $newFileName = null;
        if (! empty($pathInfo)) {
            $directory = $pathInfo['dirname'];
            $extension = "." .$pathInfo['extension'];
            $pathFileName = $pathInfo['filename'];
            // if the file is still exists
            // rename again and again until the file is not exists anymore
            $renamedFile = $directory . "/" . $pathFileName . "_" . $ctr . $extension;
            if (file_exists($renamedFile)) {
                $ctr++;
                // pass again the current file
                $newFileName =  $this->renameFileRec($filenamePath,$ctr);
            } else {
                // return the new file name
                $newFileName = $pathInfo['filename'] . "_" . $ctr . $extension;
            }
        }

        return $newFileName;
    }
}