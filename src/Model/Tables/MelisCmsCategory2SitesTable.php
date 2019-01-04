<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2017 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2\Model\Tables;

use MelisCore\Model\Tables\MelisGenericTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Join;
use Zend\Db\Sql;

class MelisCmsCategory2SitesTable extends MelisGenericTable
{
    protected $tableGateway;
    protected $idField;
    
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'cats2_id';
    }

    public function getCatSiteBySiteIdCatId($siteId, $catId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('cats2_id'));

        $select->where->equalTo('cats2_site_id',$siteId);
        $select->where->equalTo('cats2_cat2_id',$catId);

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }
}