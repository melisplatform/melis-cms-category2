<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Sql\Expression;
use MelisEngine\Model\Tables\MelisGenericTable;

class MelisCmsCategory2SeoTable extends MelisGenericTable 
{
    /**
     * Table name
     */
    const TABLE = 'melis_cms_category2_seo';
    /**
     * Primary key
     */
    const PRIMARY_KEY = 'category2_seo_id';

    /**
     * MelisCmsCategory2SeoTable constructor.
     */
    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    /**
     * @param $categoryId
     * @param null $langId
     * @return mixed
     */
    public function getCategory2Seo($categoryId, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();  

        $select->where('melis_cms_category2_seo.category2_id ='.$categoryId);
        
        if (!is_null($langId)) {
            $select->where('melis_cms_category2_seo.category2_seo_lang_id ='.$langId);
        }
        
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
    /**
     * @param $seoUrl
     * @param null $siteId
     * @return mixed
     */
    public function checkSeoUrlDuplicates($seoUrl, $siteId)
    {
        $select = $this->tableGateway->getSql()->select();  
        if ($seoUrl) {
            $select->where->like('melis_cms_category2_seo.category2_seo_url', $seoUrl);     
        }
        if (!is_null($siteId)) {
            $select->join(array('category2' => 'melis_cms_category2'), 'category2.cat2_id = melis_cms_category2_seo.category2_id', array(), $select::JOIN_LEFT);

            $select->join(array('category2_sites' => 'melis_cms_category2_sites'), 'category2_sites.cats2_cat2_id  = category2.cat2_id', array(), $select::JOIN_LEFT);

            $select->join(array('site' => 'melis_cms_site'), 'site.site_id = category2_sites.cats2_site_id', array('site_name'), $select::JOIN_LEFT);
            
            $select->where('category2_sites.cats2_site_id ='.$siteId);
        }
        $resultData = $this->tableGateway->selectWith($select);
        return $resultData;
    }
}