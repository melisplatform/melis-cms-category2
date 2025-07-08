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

class MelisCmsCategory2SitesTable extends MelisGenericTable
{

    /**
     * Table name
     */
    const TABLE = 'melis_cms_category2_sites';
    /**
     * Primary key
     */
    const PRIMARY_KEY = 'cats2_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getCatSiteBySiteIdCatId($siteId, $catId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('cats2_id'));

        $select->where->equalTo('cats2_site_id', $siteId);
        $select->where->equalTo('cats2_cat2_id', $catId);

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }
}
