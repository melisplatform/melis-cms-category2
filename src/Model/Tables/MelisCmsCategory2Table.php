<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2017 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2\Model\Tables;

use MelisCore\Model\Tables\MelisGenericTable;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Sql\Join;
use Laminas\Db\Sql;
use Laminas\Db\Sql\Predicate\Expression;

class MelisCmsCategory2Table extends MelisGenericTable
{
    /**
     * Table name
     */
    const TABLE = 'melis_cms_category2';
    /**
     * Primary key
     */
    const PRIMARY_KEY = 'cat2_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    /**
     * Get Category List By Category Id
     * @param int $categoryId If not specified, it will bring back the root categories.
     * @param int $langId If specified, translations of category will be limited to that lang
     * @param boolean $onlyValid if true, returns only active status and valid range of dates categories
     * @param int $start If not specified, it will start at the begining of the list
     * @param int $limit If not specified, it will bring all categories of the list
     * @param int $fatherId If Zero (0), this will return the root of the category
     * @return int Array
     */
    public function getCategoryChildrenListById($categoryId, $langId, $onlyValid, $start, $limit, $fatherId = null)
    {
        $select = $this->tableGateway->getSql()->select();

        if (is_null($fatherId)) {
            if ($categoryId != -1) {
                $select->where('cat2_id = ' . (int)$categoryId);
            } else {
                $select->where('cat2_father_cat_id = -1');
            }
        } else {
            $select->where('cat2_father_cat_id = ' . (int)$fatherId);
        }

        if (is_bool($onlyValid) && $onlyValid) {
            $select->where('cat2_status = 1');
            $select->where->NEST->literal('cat2_date_valid_start <= "' . date('Y-m-d') . '"')->or->literal('cat2_date_valid_start IS NULL');;
            $select->where->NEST->literal('cat2_date_valid_end >= "' . date('Y-m-d') . '"')->or->literal('cat2_date_valid_end IS NULL');
        }

        if ($fatherId == 0) {
            if (is_numeric($start) && $start != 0) {
                $select->offset((int)$start);
            }

            if (is_numeric($limit) && $limit != 0 && !is_null($limit)) {
                $select->limit((int)$limit);
            }
        }

        $select->order('cat2_order ASC');

        $dataCategory = $this->tableGateway->selectWith($select);

        return $dataCategory;
    }

    /**
     * Get Category Deatils and Translation
     * @param int $categoryId
     * @param int $langId
     * @return NULL|\Laminas\Db\ResultSet\ResultSetInterface
     */
    public function getCategoryTranslationBylangId($categoryId, $langId = null, $onlyValid = false)
    {
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'category-' . $categoryId . '_getCategoryTranslationBylangId_' . $categoryId . '_' . $langId;
        $cacheConfig = 'commerce_memory_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
        $results = null; #$melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);

        if (!empty($results)) {
            return $results;
        }

        $select = $this->tableGateway->getSql()->select();


        $select->join('melis_cms_category2_trans', 'melis_cms_category2_trans.catt2_category_id = melis_cms_category2.' . $this->idField, array('*'), $select::JOIN_LEFT);
        $select->join('melis_cms_lang', 'melis_cms_lang.lang_cms_id = melis_cms_category2_trans.catt2_lang_id', array('*'), $select::JOIN_LEFT);

        $select->where->equalTo('melis_cms_category2.cat2_id', $categoryId);

        if (!is_null($langId)) {
            $select->where->equalTo('melis_cms_category2_trans.catt2_lang_id', $langId);
        }

        $dataCategory = $this->tableGateway->selectWith($select);

        return $dataCategory;
    }


    /**
     * Getting Categories under Category ID
     * @param int $categoryId
     * @param boolean $onlyValid
     * @param int $fatherId If Zero (0), this will return the root of the category
     * @return int Array()
     */
    public function getSubCategoryIdById($categoryId, $onlyValid, $fatherId = 0, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();

        if (!is_null($langId)) {
            $join = new Expression('melis_cms_category2_trans.catt2_category_id = melis_cms_category2.' . $this->idField . ' AND catt2_lang_id=' . $langId);
            $select->join('melis_cms_category2_trans', $join, array('*'), $select::JOIN_LEFT);
        }

        if ($fatherId == 0) {
            if (!in_array($categoryId, array('-1')) && is_numeric($categoryId)) {
                $categoryId = (int) $categoryId;
                $select->where('cat2_id = ' . (int)$categoryId);
            } else {
                $select->where('cat2_father_cat_id = -1');
            }
        } else {
            $select->where('cat2_father_cat_id = ' . (int)$fatherId);
        }

        if (is_bool($onlyValid) && $onlyValid) {
            $select->where('cat2_status = 1');
            $select->where->NEST->literal('cat2_date_valid_start <= "' . date('Y-m-d') . '"')
                ->or->literal('cat2_date_valid_start IS NULL');;
            $select->where->NEST->literal('cat2_date_valid_end >= "' . date('Y-m-d') . '"')
                ->or->literal('cat2_date_valid_end IS NULL');
        }

        $select->order('cat2_order ASC');

        $dataCategory = $this->tableGateway->selectWith($select);

        return $dataCategory;
    }

    /**
     * Get Category Data By father Id
     * @param int $fatherId
     * @param boolean $onlyValid
     * @return MelisEcomCategory Object
     */
    public function getCategoryByFatherId($fatherId = 0, $onlyValid = false, $siteId = null)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('cat2_id', 'cat2_status', 'cat2_father_cat_id'));

        if ($fatherId == 0) {
            $select->where('cat2_father_cat_id = -1');
        } else {
            $select->where('cat2_father_cat_id = ' . (int)$fatherId);
        }

        if (is_bool($onlyValid) && $onlyValid) {
            $select->where('cat2_status = 1');
        }
        $select->order('cat2_order ASC');

        $dataCategory = $this->tableGateway->selectWith($select);

        return $dataCategory;
    }

    public function getCategoryTreeview($categoryId = null, $fatherId = null, $onlyValid = false)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('cat2_id', 'cat2_status', 'cat2_father_cat_id'));

        if (is_null($fatherId)) {
            if ($categoryId != -1 && !is_null($categoryId)) {
                $select->where('cat2_id = ' . (int)$categoryId);
            } else {
                $select->where('cat2_father_cat_id = -1');
            }
        } else {
            $select->where('cat2_father_cat_id = ' . (int)$fatherId);
        }

        if (is_bool($onlyValid) && $onlyValid) {
            $select->where('cat2_status = 1');
            $select->where->NEST->literal('cat2_date_valid_start <= "' . date('Y-m-d') . '"')
                ->or->literal('cat2_date_valid_start IS NULL');
            $select->where->NEST->literal('cat2_date_valid_end >= "' . date('Y-m-d') . '"')
                ->or->literal('cat2_date_valid_end IS NULL');
        }

        $select->order('cat2_order ASC');

        $dataCategory = $this->tableGateway->selectWith($select);

        return $dataCategory;
    }

    /**
     * Get Category Transalations
     * @param int $catId
     * @param int $langId
     * @param booloean $anyLang, if not specified this will return 1 translation of the category in any language
     * @return MelisEcomCategory Object
     */
    public function getCategoryNameAsTextById($catId, $langId, $anyLang = false)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array(new \Laminas\Db\Sql\Expression('CONCAT(cat2_id," - ",catt2_name) As text')));
        $select->join(
            'melis_cms_category2_trans',
            'melis_cms_category2_trans.catt2_category_id = melis_cms_category2.' . $this->idField,
            array('catt2_lang_id'),
            $select::JOIN_RIGHT
        );

        if ($anyLang) {
            $select->join(
                'melis_cms_lang',
                'melis_cms_lang.lang_cms_id = melis_cms_category2_trans.catt2_lang_id',
                array('lang_cms_name'),
                $select::JOIN_LEFT
            );
            $select->limit(1);
        } else {
            $select->where('catt2_lang_id = ' . (int)$langId);
        }

        $select->where('cat2_id = ' . (int)$catId);


        $dataCategoryTrans = $this->tableGateway->selectWith($select);

        return $dataCategoryTrans;
    }

    /**
     * Get Category Children By Father Id
     * @param int $fatherId
     * @return MelisEcomCategory Object
     */
    public function getChildrenCategoriesOrderedByOrder($fatherId)
    {

        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('cat2_id', 'cat2_father_cat_id', 'cat2_order'));
        $select->where('cat2_father_cat_id =' . (int)$fatherId);
        $select->order('cat2_order ASC');

        $dataCategory = $this->tableGateway->selectWith($select);

        return $dataCategory;
    }

    public function getParentCategory($catId, $langId = null, $addSeo = false)
    {
        // Retrieve cache version if front mode to avoid multiple calls
        $cacheKey = 'categories_table_getParentCategory_' . $catId . '_' . $langId . '_' . $addSeo;
        $cacheConfig = 'commerce_big_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;

        $select = $this->tableGateway->getSql()->select();

        if (!is_null($langId)) {
            $join = new Expression('melis_cms_category2_trans.catt2_category_id = melis_cms_category2.' . $this->idField . ' AND catt2_lang_id =' . $langId);
            $select->join(
                'melis_cms_category2_trans',
                $join,
                array('*'),
                $select::JOIN_LEFT
            );
        }

        $select->where('cat2_id =' . (int)$catId);

        $dataCategory = $this->tableGateway->selectWith($select);

        $dataCategory = $dataCategory->toArray();

        // if ($this->cacheResults)
        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $dataCategory);


        return $dataCategory;
    }

    public function getFatherCategory($catId, $langId)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->join('melis_cms_category2_trans', 'melis_cms_category2_trans.catt2_category_id = melis_cms_category2.cat2_id', array('catt2_name'), $select::JOIN_LEFT);

        $select->where->equalTo('melis_cms_category2.cat2_id', $catId);

        $select->where->equalTo('melis_cms_category2_trans.catt2_lang_id', $langId);

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }

    public function getCategoriesByIds($categoryIds, $onlyValid = false, $langId = null, $column = 'cat2_id', $order = 'ASC')
    {
        $select = $this->tableGateway->getSql()->select();

        if (!is_null($langId)) {
            $join = new Expression('melis_cms_category2_trans.catt2_category_id = melis_cms_category2.' . $this->idField . ' AND catt2_lang_id =' . $langId);
        } else {
            $join = new Expression('melis_cms_category2_trans.catt2_category_id = melis_cms_category2.' . $this->idField . ' AND catt2_name IS NOT NULL');
        }

        $select->join('melis_cms_category2_trans', $join, array('*'), $select::JOIN_LEFT);

        # $select->join('melis_ecom_country_category', 'melis_ecom_country_category.ccat_category_id = melis_cms_category2.'.$this->idField, array(), $select::JOIN_LEFT);

        if (!empty($categoryIds) && is_array($categoryIds)) {
            $select->where->in('cat2_id', $categoryIds);
        }
        //        elseif (!is_null($categoryIds))
        //        {
        //            $select->where->equalTo('cat2_id', $categoryIds);
        //        }

        if (is_bool($onlyValid) && $onlyValid) {
            $select->where('cat2_status = 1');
            $select->where->NEST->literal('cat2_date_valid_start <= "' . date('Y-m-d') . '"')
                ->or->literal('cat2_date_valid_start IS NULL');
            $select->where->NEST->literal('cat2_date_valid_end >= "' . date('Y-m-d') . '"')
                ->or->literal('cat2_date_valid_end IS NULL');
        }

        $select->order($column . ' ' . $order);

        $select->group($this->idField);

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }

    public function getCategoryList($onlyValid = false, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();

        if (!is_null($langId)) {
            $join = new Expression('melis_cms_category2_trans.catt2_category_id = melis_cms_category2.' . $this->idField . ' AND catt2_lang_id =' . $langId);
        } else {
            $join = new Expression('melis_cms_category2_trans.catt2_category_id = melis_cms_category2.' . $this->idField . ' AND catt2_name IS NOT NULL');
        }

        $select->join('melis_cms_category2_trans', $join, array('*'), $select::JOIN_LEFT);

        if (is_bool($onlyValid) && $onlyValid) {
            $select->where('cat2_status = 1');
            $select->where->NEST->literal('cat2_date_valid_start <= "' . date('Y-m-d') . '"')->or->literal('cat2_date_valid_start IS NULL');
            $select->where->NEST->literal('cat2_date_valid_end >= "' . date('Y-m-d') . '"')->or->literal('cat2_date_valid_end IS NULL');
        }

        $select->group($this->idField);
        $select->order('catt2_name ASC');

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }


    public function getChildrenByLangId($fatherId, $langId, $valid, $order = false)
    {
        $select = $this->tableGateway->getSql()->select();

        if (!is_null($langId)) {
            $join = new Expression('melis_cms_category2_trans.catt2_category_id = melis_cms_category2.' . $this->idField . ' AND catt2_lang_id =' . $langId);
        } else {
            $join = new Expression('melis_cms_category2_trans.catt2_category_id = melis_cms_category2.' . $this->idField . ' AND catt2_name IS NOT NULL');
        }

        $select->join('melis_cms_category2_trans', $join, ['*'], $select::JOIN_LEFT);
        $select->where->equalTo('cat2_father_cat_id', $fatherId);

        if (is_bool($valid) && $valid) {
            $select->where('cat2_status = 1');
            $select->where->NEST->literal('cat2_date_valid_start <= "' . date('Y-m-d') . '"')->or->literal('cat2_date_valid_start IS NULL');
            $select->where->NEST->literal('cat2_date_valid_end >= "' . date('Y-m-d') . '"')->or->literal('cat2_date_valid_end IS NULL');
        }

        if ($order) {
            $select->order('cat2_order ASC');
        } else {
            $select->order('catt2_name ASC');
        }

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }

    public function getAvailableOrder($categoryIdParentId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['maxOrder' => new Sql\Expression('MAX(cat2_order)')]);
        $select->where('cat2_father_cat_id = ' . $categoryIdParentId);
        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }

    /**
     * Return categories under a certain site with siteId and languageId
     * this will return only associated with the root only
     * @param $siteId
     * @param $langId
     * @return mixed
     */
    public function getFirstLevelCategoriesPerSite($siteId, $langId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        # join melis_cms_category2_sites
        $tblToJoin   = "melis_cms_category2_sites";
        $relation    = "melis_cms_category2_sites.cats2_cat2_id = melis_cms_category2.cat2_id ";
        $joinColumns = ['*'];
        $joinType    = $select::JOIN_LEFT;
        $select->join($tblToJoin, $relation, $joinColumns, $joinType);
        # join melis_cms_category2_trans
        $tblToJoin   = "melis_cms_category2_trans";
        $relation    = "melis_cms_category2_trans.catt2_category_id = melis_cms_category2.cat2_id ";
        $joinColumns = ['catt2_lang_id', 'catt2_name', 'catt2_description'];
        $joinType    = $select::JOIN_LEFT;
        $select->join($tblToJoin, $relation, $joinColumns, $joinType);
        # join melis_cms_lang
        $tblToJoin   = "melis_cms_lang";
        $relation    = "melis_cms_lang.lang_cms_id = melis_cms_category2_trans.catt2_lang_id ";
        $joinColumns = ['*'];
        $joinType    = $select::JOIN_LEFT;
        $select->join($tblToJoin, $relation, $joinColumns, $joinType);
        //site id
        $select->where->equalTo('melis_cms_category2_sites.cats2_site_id', $siteId);
        // lang id
        $select->where->equalTo('melis_cms_category2_trans.catt2_lang_id', $langId);


        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }

    /**
     * Return all categories under a certain site
     *
     * @param $siteId
     * @param $langId
     * @return mixed
     */
    public function getCategoriesPerSite($siteId, $langId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        # join melis_cms_category2_sites
        $tblToJoin   = "melis_cms_category2_sites";
        $relation    = "melis_cms_category2_sites.cats2_cat2_id = melis_cms_category2.cat2_id ";
        $joinColumns = ['*'];
        $joinType    = $select::JOIN_LEFT;
        $select->join($tblToJoin, $relation, $joinColumns, $joinType);
        # join melis_cms_site_domain
        $tblToJoin   = "melis_cms_category2_trans";
        $relation    = "melis_cms_category2_trans.catt2_category_id = melis_cms_category2.cat2_id ";
        $joinColumns = ['catt2_name', 'catt2_description'];
        $joinType    = $select::JOIN_LEFT;
        $select->join($tblToJoin, $relation, $joinColumns, $joinType);
        //site id
        $select->where->equalTo('melis_cms_category2_sites.cats2_site_id', $siteId);
        // lang id
        $select->where->equalTo('melis_cms_category2_trans.catt2_lang_id', $langId);

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }

    public function getLastId()
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['maxId' => new Sql\Expression('MAX(cat2_id)')]);
        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }

    public function getCategoryOrders($parentId, $currentOrder)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['*']);

        $select->where->equalTo('cat2_father_cat_id', $parentId);
        $select->where("cat2_order > $currentOrder");

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }


    // 

    public function getCategoryNewsList(
        $status             = null,
        $categoryIdNews     = null,
        $langId             = null,
        $dateMin            = null,
        $dateMax            = null,
        $publishDateMin     = null,
        $publishDateMax     = null,
        $unpublishFilter    = false,
        $start              = null,
        $limit              = null,
        $orderColumn        = null,
        $order              = null,
        $siteId             = null,
        $search             = null,
        $count              = false
    ) {
        $select = $this->tableGateway->getSql()->select();

        $select->join('melis_cms_news_category', 'melis_cms_news_category.cnc_cat2_id = melis_cms_category2.cat2_id', '*', $select::JOIN_LEFT);
        $select->join('melis_cms_news', 'melis_cms_news.cnews_id = melis_cms_news_category.cnc_cnews_id', '*', $select::JOIN_LEFT);
        $select->join('melis_cms_news_texts', 'melis_cms_news_texts.cnews_id = melis_cms_news.cnews_id', '*', $select::JOIN_LEFT);


        if (!is_null($search)) {
            $search = '%' . $search . '%';
            $select->where->NEST->like('melis_cms_news.cnews_id', $search)
                ->or->like('melis_cms_news_texts.cnews_title', $search);
        }

        if (!is_null($siteId)) {
            $select->where->equalTo('cnews_site_id', $siteId);
        }

        if (!is_null($status)) {
            $select->where('cnews_status =' . (int)$status);
        }

        if (!empty($categoryIdNews)) {
            $select->where('cnc_cat2_id =' . (int)$categoryIdNews);
        }


        if (!is_null($langId)) {
            $select->where('melis_cms_news_texts.cnews_lang_id =' . $langId);
        }

        if (!is_null($dateMin)) {
            $select->where('cnews_creation_date >= "' . $dateMin . '"');
        }

        if (!is_null($dateMax)) {
            $select->where('cnews_creation_date <= "' . $dateMax . '"');
        }

        if (!is_null($publishDateMin)) {
            $select->where('DATE(cnews_publish_date)>= "' . $publishDateMin . '"');
        }

        if (!is_null($publishDateMax)) {
            $select->where('DATE(cnews_publish_date) <= "' . $publishDateMax . '"');
        }

        if (!is_null($limit)) {
            $select->limit((int) $limit);
        }

        if ($unpublishFilter) {
            $select->where->nest->greaterThan('cnews_unpublish_date', date("Y-m-d H:i:s"))->or->isNull('cnews_unpublish_date')->unnest;
        }

        if (!is_null($start)) {
            $select->offset($start);
        }

        $cnews_text_cols = [
            'cnews_text_id',
            'cnews_title',
            'cnews_subtitle',
            'cnews_paragraph1',
            'cnews_paragraph2',
            'cnews_paragraph3',
            'cnews_paragraph4',
            'cnews_paragraph5',
            'cnews_paragraph6',
            'cnews_paragraph7',
            'cnews_paragraph8',
            'cnews_paragraph9',
            'cnews_paragraph10',
            'cnews_id',
            'cnews_lang_id',
        ];

        if (!is_null($orderColumn) && !is_null($order)) {
            if ($orderColumn == 'site_label') {
                $select->order('melis_cms_site.' . $orderColumn . ' ' . $order);
            } elseif (in_array($orderColumn, $cnews_text_cols)) {
                $select->order('melis_cms_news_texts.' . $orderColumn . ' ' . $order);
            } else {
                $select->order('melis_cms_news.' . $orderColumn . ' ' . $order);
            }
        }

        if (!empty($count)) {
            $select->group('melis_cms_news.cnews_id');
        }



        $select->where('melis_cms_news_texts.cnews_title !=""');

        $resultData = $this->tableGateway->selectWith($select);

        return $resultData;
    }
}
