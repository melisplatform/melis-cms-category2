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

class MelisCmsCategory2MediaTable extends MelisGenericTable
{
    /**
     * Table name
     */
    const TABLE = 'melis_cms_category2_media';
    /**
     * Primary key
     */
    const PRIMARY_KEY = 'catm2_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getMediaFilesByCategoryId($categoryId, $fileType = 'file')
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        # join melis_cms_category2_sites
        $tblToJoin   = "melis_cms_category2";
        $relation    = "melis_cms_category2.cat2_id = melis_cms_category2_media.catm2_cat_id ";
        $joinColumns = ['*'];
        $joinType    = $select::JOIN_LEFT;
        $select->join($tblToJoin, $relation, $joinColumns, $joinType);
        // category id
        $select->where->equalTo('melis_cms_category2_media.catm2_cat_id', $categoryId);
        // file type
        $select->where->equalTo('melis_cms_category2_media.catm2_type', $fileType);

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }
}
