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
class MelisCmsCategoryService  extends MelisCoreGeneralService
{
    public function saveCategory(
        $parentId,
        $status,
        $currentUserId,
        $validDateStart,
        $validDateEnd,
        $categoryId = null,
        $postValues
    ) {

        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_cms_category_save_category2_start', $arrayParameters);
        // Service implementation start
        $categoryTable = $this->getServiceLocator()->get('MelisCmsCategory2Table');
        # order
        $order = null;
        if (! empty($categoryId)) {
            $order = $categoryTable->getEntryById($categoryId)->current()->cat2_order;
        } else {
            $order = $categoryTable->getAvailableOrder($parentId)->current()->maxOrder + 1;
        }

        # construct data
        $data = [
            'cat2_father_cat_id' => $parentId,
            'cat2_status'        => $status,
            'cat2_order'         => $order,
            'cat2_date_creation' => date('Y-m-d'),
            'cat2_user_id_creation' => $currentUserId
        ];

        if (! empty($validDateStart)) {
            $data['cat2_date_valid_start'] = date("Y-m-d",strtotime($validDateStart));
        } else {
            $data['cat2_date_valid_start'] = null;
        }
        if (! empty($validDateEnd)) {
            $data['cat2_date_valid_end'] = date("Y-m-d",strtotime($validDateEnd));
        } else {
            $data['cat2_date_valid_end'] = null;
        }

        # save data in category2 table
        $newCatId = null;
        if (! empty($categoryId)) {
            $newCatId = $categoryTable->save($data,$categoryId);
        } else {
            $newCatId = $categoryTable->save($data);
        }

        // Service implementation end
        $arrayParameters['results'] = $newCatId;
        // Sending service end event
        $arrayParameters = $this->sendEvent('melis_cms_category_save_category2_end', $arrayParameters);

        return $arrayParameters['results'];

    }
    public function validateDates($dateStart, $dateEnd)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_cms_category_save_category2_start', $arrayParameters);

        // Service implementation start
        $success = 0;
        if (! empty($dateStart) && ! empty($dateEnd)) {
            // replace forward slash to dash
            $dateStart = str_replace('/','-',$dateStart);
            $dateEnd   = str_replace('/','-',$dateEnd);
            # format dates
            $dateStart = date("Y-m-d",strtotime($dateStart));
            $dateEnd   = date("Y-m-d",strtotime($dateEnd));
            if ($dateStart < $dateEnd) {
                $success = true;
            }

        } else if (! empty($dateStart) || ! empty($dateEnd)) {
            $success = true;
        }
        // Service implementation end
        $arrayParameters['results'] = $success;
        // Sending service end event
        $arrayParameters = $this->sendEvent('melis_cms_category_save_category_trans_end', $arrayParameters);

        return $arrayParameters['results'];
    }
    public function saveCategoryTexts($categoryId,$catLangId, $postData, $id = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_cms_category_save_category_trans_start', $arrayParameters);
        // Service implementation start
        $categoryTextsTable = $this->getServiceLocator()->get('MelisCmsCategory2TransTable');
        # construct data
        $postData['catt2_category_id'] = $categoryId;
        $postData['catt2_lang_id'] = $catLangId;
        unset($postData['catt_lang_id']);


        $saveCatTransId = null;

        if (! empty($id)) {
            $catTransId = $categoryTextsTable->getTextIdByLangIdCatId($id, $catLangId)->current()->catt2_id ?? null;
            if (! empty($catTransId)) {
                // for updating
                $categoryTextsTable->save($postData, $catTransId);
            } else {
                // for creating
                $categoryTextsTable->save($postData);
            }
        } else {
            // for creating
            $categoryTextsTable->save($postData);
        }

        // Service implementation end
        $arrayParameters['results'] = $saveCatTransId;
        // Sending service end event
        $arrayParameters = $this->sendEvent('melis_cms_category_save_category_trans_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    public function saveCategorySites($categoryId, $siteId, $id = null, $tobeDeleted = false)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_cms_category_save_category_sites_start', $arrayParameters);
        // Service implementation start
        $categorySitesTable = $this->getServiceLocator()->get('MelisCmsCategory2SitesTable');
        # construct data
        $data = [
            'cats2_site_id' => $siteId,
            'cats2_cat2_id' => $categoryId
        ];

        $saveCatSiteId = null;
        if (! empty($id)) {
            $catSiteId = $categorySitesTable->getCatSiteBySiteIdCatId($siteId, $id)->current()->cats2_id ?? null;
            if (! empty($catSiteId)) {
                $categorySitesTable->save($data, $catSiteId);
            } else {
                $categorySitesTable->save($data);
            }
            if ($tobeDeleted) {
                $categorySitesTable->deleteById($catSiteId);
            }

        } else {
            $categorySitesTable->save($data);
        }

        // Service implementation end
        $arrayParameters['results'] = $saveCatSiteId;
        // Sending service end event
        $arrayParameters = $this->sendEvent('melis_cms_category_save_category_sites_end', $arrayParameters);

        return $arrayParameters['results'];
    }
    public function getCategoryMediaById($categoryId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_cms_category_category_get_media_start', $arrayParameters);
        // Service implementation start
        $categoryMediaTbl = $this->getServiceLocator()->get('MelisCmsCategory2MediaTable');
        $categoryMediaData = $categoryMediaTbl->getEntryByField('catm2_cat_id', $categoryId);
        // Service implementation end
        $arrayParameters['results'] = $categoryMediaData;
        // Sending service end event
        $arrayParameters = $this->sendEvent('melis_cms_category_category_get_media_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * Getting the Category Details Recursively
     * This function will return children data of the parent category
     * @param int $categoryId If not specified, it will bring back the root categories.
     * @param int $langId If specified, translations of category will be limited to that lang
     * @param boolean $onlyValid if true, returns only active status and valid range of dates categories
     * @param int $start If not specified, it will start at the begining of the list
     * @param int $limit If not specified, it will bring all categories of the list
     * @param int $fatherId If Zero (0), this will return the root of the category
     * @return int Array
     */
    public function getCategoryListByIdRecursive($categoryId, $langId = null, $onlyValid = false, $start = null, $limit = null, $fatherId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();
        
        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscms_categories_service_get_category_list_recursive_start', $arrayParameters);
        
        $categoryId = $arrayParameters['categoryId'];
        $langId = $arrayParameters['langId'];
        $onlyValid = $arrayParameters['onlyValid'];
        $start = $arrayParameters['start'];
        $limit = $arrayParameters['limit'];
        $fatherId = $arrayParameters['fatherId'];
        
        $melisEcomCategoryTable = $this->getServiceLocator()->get('MelisCmsCategory2Table');
        
        $dataCategoryData = $melisEcomCategoryTable->getCategoryChildrenListById($categoryId, $langId, $onlyValid, $start, $limit, $fatherId);

        foreach ($dataCategoryData As $val){
            
            // Retrieving category entity
            $melisCategory = $this->getCategoryById($val->cat2_id, $langId, $onlyValid);

            array_push($results, $melisCategory);
        }

        $arrayParameters['results'] = $results;
        // Service implementation end

        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscms_categories_service_get_category_list_recursive_end', $arrayParameters);

        return $arrayParameters['results'];
    }


    /**
     *
     * This method gets a category by its categoryId and brings back
     * all the datas for a category: category, text translations, country
     *
     * @param int $categoryId Category Id to look for
     * @param int $langId If specified, translations of category will be limited to that lang
     *
     * @return MelisCategory|null Category object
     */
    public function getCategoryById($categoryId, $langId = null, $onlyValid = false)
    {

        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'category-' . $categoryId . '-getCategoryById_' . $categoryId . '_' . $langId;

        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');

        if (!empty($results)) return $results;

        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscms_categories_service_category_byid_start', $arrayParameters);

        // Service implementation start
        $melisCategory = new \MelisCmsCategory2\Entity\MelisCategory();

        $melisEcomCategoryTable = $this->getServiceLocator()->get('MelisCmsCategory2Table');

        // Getting Categories under Category ID
        $melisCategoryDataRes = $melisEcomCategoryTable->getEntryById($arrayParameters['categoryId']);
        $category = $melisCategoryDataRes->current();

        if (!empty($category))
        {
            // Id
            $melisCategory->setId($category->cat2_id);
            // category
            $melisCategory->setCategory($category);
            // get translations
            $catTrans = $this->getCategoryTranslationById($category->cat2_id, $arrayParameters['langId'], $arrayParameters['onlyValid']);
            // set translation
            $melisCategory->setTranslations($catTrans);
            // get children
            $catChildren = $this->getCategoryListByIdRecursive(null, $arrayParameters['langId'], $arrayParameters['onlyValid'], null, null, $category->cat2_id);
            // set chhildren
            $melisCategory->setChildren($catChildren);
            // get category sites
            $categorySites = $this->getSiteCategoryById($category->cat2_id);
            // set sites
            $melisCategory->setSites($categorySites);
            // get category media
            $categoryMedia = $this->getCategoryMediaById($category->cat2_id)->toArray();
            // set category media
            $melisCategory->setMedia($categoryMedia);

        }


        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $melisCategory;
        // Service implementation end

        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscms_categories_service_category_byid_end', $arrayParameters);


        return $arrayParameters['results'];
    }

    /**
     * This method will return the name of the category
     * @param int $categoryId category id
     * @param int $langId if specified this will return with the same language,
     * otherwise this will return first category name the available.
     * @return String||null
     */
    public function getCategoryNameById($categoryId, $langId = null, $exclude = false)
    {
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'category-' . $categoryId . '-getCategoryNameById_' . $categoryId . '_' . $langId;

        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;

        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = null;

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscms_categories_service_category_get_name_start', $arrayParameters);

        // Service implementation

        $category = $this->getCategoryById($arrayParameters['categoryId']);

        $catTrans = $category->getTranslations();

        $catNameStr = null;

        if (!empty($catTrans))
        {
             // Getting the first available translation of the Category
            foreach ($catTrans As $val)
            {
                if (!empty($val->catt2_name) && $val->elang_id == $arrayParameters['langId'])
                {
                    $catNameStr = $val->catt2_name;
                    break;
                }
            }

            if (empty($catNameStr))
            {
                // Getting the first available translation of the Category
                foreach ($catTrans As $val)
                {
                    if (!empty($val->catt2_name))
                    {
                        $catNameStr = $val->catt2_name . ' ('.$val->elang_name.')';
                        break;
                    }
                }
            }
        }

        $results = $catNameStr;
        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscms_categories_service_category_products_byid_end', $arrayParameters);

        // Save cache key
        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);

        return $arrayParameters['results'];
    }

    /**
     * This method gets the affected translations of a category
     *
     * @param int $categoryId, id of the category
     * @param int $langId, langauge id of the category match on melis_ecom_category_trans.catt_lang_id else this will return available translation
     * @param boolean $onlyValid, true return only active status else return all
     * @return MelisEcomCategory[]|null MelisEcomCategory object
     */
    public function getCategoryTranslationById($categoryId, $langId = null, $onlyValid = false)
    {

        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'category-' . $categoryId . '-getCategoryTranslationById_' . $categoryId . '_' . $langId;
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');

        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscms_categories_service_category_get_category_translations_start', $arrayParameters);

        // Service implementation start
        $melisEcomCategoryTable = $this->getServiceLocator()->get('MelisCmsCategory2Table');
        $melisCategoryTranslation = $melisEcomCategoryTable->getCategoryTranslationBylangId($arrayParameters['categoryId'], $arrayParameters['langId'], $arrayParameters['onlyValid']);

        foreach ($melisCategoryTranslation As $val)
        {
            array_push($results, $val);
        }

        if (empty($results))
        {
            $catText = $melisEcomCategoryTable->getCategoryTranslationBylangId($arrayParameters['categoryId'], null, $arrayParameters['onlyValid'])->current();

            if (!empty($catText))
            {
                array_push($results, $catText);
            }
        }
        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;

        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscms_categories_service_category_get_category_translations_end', $arrayParameters);

        // Save cache key
        //$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $arrayParameters['results']);

        return $arrayParameters['results'];
    }
    /**
     * Get Category Tree View, listing all sub category by using the FatherID/ParentID of the Category
     * @param int $fatherId
     * @param int $langId
     * @return int Array
     */
    public function getCategoryTreeview($fatherId = null, $langId = null, $onlyValid = false,$siteId = null)
    {

        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = array();

        // Sending service start event
        $arrayParameters = $this->sendEvent('meliscms_categories_service_get_category_tree_view_start', $arrayParameters);

        $fatherId = $arrayParameters['fatherId'];
        $langId = $arrayParameters['langId'];
        $onlyValid= $arrayParameters['onlyValid'];
        $siteId   = $arrayParameters['siteId'];


        $melisCmsCategory2Tbl = $this->getServiceLocator()->get('MelisCmsCategory2Table');
        $categoryData = $melisCmsCategory2Tbl->getCategoryByFatherId($fatherId, $onlyValid, $siteId);
        $catData = $categoryData->toArray();
        /**
         * TEMPORARY, NEED TO CREATE GENERAL HELPER FOR THIS
         */
        $escaper = new \Zend\Escaper\Escaper('utf-8');

        foreach ($catData As $key => $val)
        {
            $cat2Id = $val['cat2_id'];
            // Getting Category Name
            $categoryData = $this->getCategoryById($val['cat2_id'], $langId, $onlyValid);

            $category = $categoryData->getTranslations();

            $catName = '';
            $catNameLangName = '';
            foreach ($category As $val)
            {
                if ($val->lang_cms_id == $langId)
                {
                    $catName = $val->catt2_name;
                    break;
                }
                else
                {
                    // Getting available Name concatinated with the Language Name
                    $catName = $val->catt2_name;
                    $catNameLangName = $val->lang_cms_name;
                    break;
                }
            }

            $catData[$key]['text'] = $escaper->escapeHtml($catName); //$tool->escapeHtml($catName);
            $catData[$key]['textLang'] = (!empty($catNameLangName)) ?? null;
            $catData[$key]['sites'] = $this->getSiteCategoryById($cat2Id);

            $fatherId = $catData[$key]['cat2_id'];

            $catData[$key]['children'] = $this->getCategoryTreeview($fatherId, $langId, $onlyValid, $siteId);

        }
        // find available text just to double check
        if (! empty($langId)) {
            $catData = $this->getCategoryAvailableText($catData,$langId);
        }
        // return results
        $results = $catData;

        // Service implementation end
        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;

        // Sending service end event
        $arrayParameters = $this->sendEvent('meliscms_categories_service_get_category_tree_view_end', $arrayParameters);
        
        return $arrayParameters['results'];
    }
    public function getFirstLevelCategoriesPerSite($siteId, $langId = 1)
    {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

        //service event start
        $arrayParameters = $this->sendEvent('meliscms_service_category_get_first_level_categories_per_site_start', $arrayParameters);
        $siteId = $arrayParameters['siteId'];
        $langId = $arrayParameters['langId'];

        //implementation start
        $categoryTable = $this->getServiceLocator()->get('MelisCmsCategory2Table');
        $categories = $categoryTable->getFirstLevelCategoriesPerSite($siteId,$langId)->toArray();
        
        //implementation end
        $arrayParameters['results'] = $categories;
        //service event end
        $arrayParameters = $this->sendEvent('meliscms_service_category_get_first_level_categories_per_site_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * Get all the categories
     * @param $siteId
     * @param int $langId
     * @return mixed
     */
    public function getCategoriesPerSite($siteId, $langId = 1)
    {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

        //service event start
        $arrayParameters = $this->sendEvent('meliscms_service_category_get_categories_per_site_start', $arrayParameters);
        $siteId = $arrayParameters['siteId'];
        $langId = $arrayParameters['langId'];

        //implementation start
        $categoryTable = $this->getServiceLocator()->get('MelisCmsCategory2Table');
        $categories = $categoryTable->getCategoriesPerSite($siteId,$langId)->toArray();

        //implementation end
        $arrayParameters['results'] = $categories;
        //service event end
        $arrayParameters = $this->sendEvent('meliscms_service_category_get_categories_per_site_end', $arrayParameters);

        return $arrayParameters['results'];
    }
    public function reOrderCategories($parentId, $currentOrder)
    {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        //service event start
        $arrayParameters = $this->sendEvent('meliscms_service_category_get_categories_per_site_start', $arrayParameters);
        $parentId = $arrayParameters['parentId'];
        $currentOrder = $arrayParameters['currentOrder'];
        $results = [];
        //implementation start
        $categoryTable = $this->getServiceLocator()->get('MelisCmsCategory2Table');
        $categoryOrdersData = $categoryTable->getCategoryOrders($parentId,$currentOrder)->toArray();
        if (! empty($categoryOrdersData)) {
            foreach ($categoryOrdersData as $idx => $val) {
                $categoryId = $val['cat2_id'];
                $categoryOrder = [
                    'cat2_order' => ($val['cat2_order'] - 1)
                ];
                // update the order of category
                $status = $categoryTable->save($categoryOrder, $categoryId);
                $results = $status;
            }
        }

        //implementation end
        $arrayParameters['results'] = $results;
        //service event end
        $arrayParameters = $this->sendEvent('meliscms_service_category_get_categories_per_site_end', $arrayParameters);

        return $arrayParameters['results'];
    }
    public function getSiteCategoryById($categoryId)
    {
        $categorySiteTbl = $this->getServiceLocator()->get('MelisCmsCategory2SitesTable');
        $categorySitesData = $categorySiteTbl->getEntryByField('cats2_cat2_id',$categoryId)->toArray();
        $data = [];
        if (! empty($categorySitesData)) {
            foreach ($categorySitesData as $idx => $val) {
                $data[] = $val['cats2_site_id'];
            }
        }
        return $data;
    }

    /**
     * @param $langId
     * @return mixed
     */
    public function getLocaleData($langId)
    {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        //service event start
        $arrayParameters = $this->sendEvent('meliscms_service_category_get_locale_data_start', $arrayParameters);
        $results = [];
        $tableLang = $this->getServiceLocator()->get('MelisEngineTableCmsLang');
        $results = $tableLang->getEntryById($langId)->current();
        //implementation end
        $arrayParameters['results'] = $results;
        //service event end
        $arrayParameters = $this->sendEvent('meliscms_service_category_get_locale_data_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * @param $categoryId
     * @return mixed
     */
    public function getCategoryDataById($categoryId)
    {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        //service event start
        $arrayParameters = $this->sendEvent('meliscms_service_category_get_categories_data_by_id_start', $arrayParameters);
        $results = [];
        $melisCmsCategoryTbl = $this->getServiceLocator()->get('MelisCmsCategory2Table');
        $results = $melisCmsCategoryTbl->getEntryById($categoryId)->current();

        //implementation end
        $arrayParameters['results'] = $results;
        //service event end
        $arrayParameters = $this->sendEvent('meliscms_service_category_get_categories_data_by_id_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * This will put a key [linked_to_site_bool] (bool) in the given array based from site affected
     * @param $categoryData
     * @param null $siteIdFilter
     * @return mixed
     */
    public function putCategoriesIndicatorAssocSite($categoryData, $siteIdFilter = null)
    {
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        //service event start
        $arrayParameters = $this->sendEvent('meliscms_service_category_put_categories_indicator_assoc_site_start', $arrayParameters);
        $categoryData = $arrayParameters['categoryData'];
        $siteIdFilter = $arrayParameters['siteIdFilter'];
        $results = [];
        if (! empty($categoryData)) {
            foreach ($categoryData as $idx => $val) {
                if (in_array($siteIdFilter,$val['sites'])) {
                    // put a key that indicates linked to the site
                    $categoryData[$idx]['linked_to_site_bool'] = true;
                }
                // recursive strategy
                if (isset($val['children']) && ! empty($val['children'])) {
                    $categoryData[$idx]['children'] = $this->putCategoriesIndicatorAssocSite($val['children'],$siteIdFilter);
                }
            }
        }
        $results = $categoryData;

        //implementation end
        $arrayParameters['results'] = $results;
        //service event end
        $arrayParameters = $this->sendEvent('meliscms_service_category_put_categories_indicator_assoc_site_end', $arrayParameters);

        return $arrayParameters['results'];
    }
    private function getCategoryAvailableText($categoryData,$currentLangId = null)
    {
        $cmsCategory = $this->getServiceLocator()->get('MelisCmsCategory2TransTable');
        if (! empty($categoryData)) {
            foreach ($categoryData as $idx => $val) {
                $categoryId = $val['cat2_id'];
                $categoryText = $val['text'] ?? null;
                // if empty we look for other translations on the other language
                if (empty($categoryText)) {
                    $categoryTranslationsData = $cmsCategory->getCategoryTranslationsByCatId($categoryId)->toArray();
                    foreach ($categoryTranslationsData as $key => $value) {
                        if (! empty($value['catt2_name'])) {
                            $categoryData[$idx]['text'] = $value['catt2_name'] . " ( " . $value['lang_cms_name'] . ")";
                        }
                    }
                }
                // for children recursive
                if (isset($val['children']) && ! empty($val['children'])) {
                    $categoryData[$idx]['children'] = $this->getCategoryAvailableText($val['children'],$currentLangId);
                }
            }
        }

        return $categoryData;
    }

    public function returnCategoryBasedFromSiteId($categoryListData, $siteId)
    {
        if (! empty($categoryListData)) {
            foreach ($categoryListData as $idx => $val) {
                // check category if it is linked to the siteId
                if (!in_array($siteId,$val['sites'])) {
                    unset($categoryListData[$idx]);
                }
                // recursive strategy form children
                if (isset($val['children']) && ! empty($val['children'])) {
                    $categoryListData[$idx]['children'] = $this->returnCategoryBasedFromSiteId($val['children'],$siteId);
                }
            }
        }

        return $categoryListData;
    }
}