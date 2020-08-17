<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2\Service;

use MelisCmsCategory2\Model\MelisCategory;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Session\Container;
use MelisCore\Service\MelisGeneralService;

/**
 *
 * This service handles the category system of MelisCommerce.
 *
 */
class MelisCmsCategoryMediaService  extends MelisGeneralService
{
//    /**
//     * @var $categoryMediaTbl \MelisCmsCategory2\Model\Tables\MelisCmsCategory2MediaTable
//     */
//    public $categoryMediaTbl;

    /**
     * @return mixed
     */
    public function getCategoryMediaTbl()
    {
        return $this->getServiceManager()->get('MelisCmsCategory2MediaTable');
    }

    /**
     * Removed the tmp directory or category directory
     * @return mixed
     */
    public function removeCategoryDir($categoryId = null)
    {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        //service event start
        $arrayParameters = $this->sendEvent('melis_cms_categories_remove_category_dir_start', $arrayParameters);
        $results  = false;
        //implementation start
        $path = $_SERVER['DOCUMENT_ROOT'] . "/media/categories/tmp/";
        if (! empty($categoryId)) {
            $path = $_SERVER['DOCUMENT_ROOT'] . "/media/categories/$categoryId";
        }

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
                    $extension = $pathInfo['extension'] ?? null;
                    if (empty($extension) && !in_array($extension,$excludeExtensions)) {
                        // put in the results variable
                        array_push($results,$file);
                    }
                    if (! empty($extension)  && !in_array($extension,$excludeExtensions)) {
                        // put in the results variable
                        array_push($results,$file);
                    }

                }
            }
        }

        $arrayParameters['results'] = $results;
        //service event end
        $arrayParameters = $this->sendEvent('melis_cms_categories_get_files_in_dir_end', $arrayParameters);

        return $arrayParameters['results'];

    }

    /**
     * This is to remove a file in a directory
     * this return bool
     * @param $path full path of the document
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
     * This is to remove a file in a directory
     * this return bool
     * @param $path full path of the document
     * @param $filename with extension
     * @return mixed
     */
    public function getFileInDir($path,$filename)
    {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        //service event start
        $arrayParameters = $this->sendEvent('melis_cms_categories_get_files_in_dir_start', $arrayParameters);
        $path       = $arrayParameters['path'];
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
                        $results[] = $tmpFilename;
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
     * @param $file must came from post
     * @param $dirToSave
     * @param $fileName
     */
    public function uploadFile($file,$dirToSave,$fileName)
    {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        //service event start
        $arrayParameters = $this->sendEvent('melis_cms_categories_upload_file_start', $arrayParameters);
        $file      = $arrayParameters['file'];
        $dirToSave = $arrayParameters['dirToSave'];
        $results   = false;
        //implentation start
        if (isset($file['tmp_name']) && ! empty($file['tmp_name'])) {
            if (is_writable($dirToSave)) {
                $uploadStatus = move_uploaded_file($file['tmp_name'],$dirToSave . $fileName);
                $results = true;
            }
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

    /**
     *  This will delete the file in the directory and in the db
     * @param $dirPath
     * @param $filenamepath
     */
    public function deleteFile($dirPath,$filenamePath,$fileType = null)
    {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        //service event start
        $arrayParameters = $this->sendEvent('melis_cms_categories_delete_file_start', $arrayParameters);
        $dirPath = $arrayParameters['dirPath'];
        $filenamePath  = $arrayParameters['filenamePath'];
        $results = false;
        // implentation start
        // get file info
        $fileInfo = pathinfo($filenamePath);
        $fileName = null;
        if (! empty($fileInfo)) {
            // get the file name and extension
            $fileName = $fileInfo['basename'];
        }
        //first  delete the file in the db
        $status = $this->getCategoryMediaTbl()->deleteByField('catm2_path',$filenamePath);
        // get session of melis_cms_category2
        $category2Session = new Container('melis_cms_category2');
        // remove file in the session
        if ($fileType == 'image') {
            $fileType = "images";
        }
        if (! empty($category2Session[$fileType])) {
            foreach ($category2Session[$fileType] as $idx => $val) {
              unset($category2Session[$fileType][$idx]);
            }
        }

        // remove file in the directory
        $results = $this->removeFileInDir($dirPath,$fileName);

        $arrayParameters['results'] = $results;
        //service event end
        $arrayParameters = $this->sendEvent('melis_cms_categories_delete_file_end', $arrayParameters);

        return $arrayParameters['results'];
    }
    /**
     *  This will delete the file in the directory and in the db
     * @param $dirPath
     * @param $filenamepath
     */
    public function getMediaFilesByCategoryId($categoryId,$fileType = 'file')
    {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        //service event start
        $arrayParameters = $this->sendEvent('melis_cms_categories_get_media_files_by_id_start', $arrayParameters);
        $categoryId = $arrayParameters['categoryId'];
        $fileType  = $arrayParameters['fileType'];
        $results = false;
        // implentation start
        // get media files
        $results = $this->getCategoryMediaTbl()->getMediaFilesByCategoryId($categoryId,$fileType)->toArray();

        $arrayParameters['results'] = $results;
        //service event end
        $arrayParameters = $this->sendEvent('melis_cms_categories_get_media_files_by_id_end', $arrayParameters);

        return $arrayParameters['results'];
    }
    // Returns a file size limit in bytes based on the PHP upload_max_filesize
    // and post_max_size
    public function file_upload_max_size()
    {
        static $max_size = -1;

        if ($max_size < 0) {
            // Start with post_max_size.
            $post_max_size = $this->parse_size(ini_get('post_max_size'));
            if ($post_max_size > 0) {
                $max_size = $post_max_size;
            }

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = $this->parse_size(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }
        return $max_size;
    }
    public function parse_size($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        else {
            return round($size);
        }
    }
}