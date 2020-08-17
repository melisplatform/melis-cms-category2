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

class MelisCmsCategory2TransTable extends MelisGenericTable
{
    /**
     * Table name
     */
    const TABLE = 'melis_cms_category2_trans';
    /**
     * Primary key
     */
    const PRIMARY_KEY = 'catt2_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    /***
     * get All data of MelisCmsUserAccounts
     * @param string $search
     * @param array $searchableColumns
     * @param string $orderBy
     * @param string $orderDirection
     * @param int $start
     * @param null $limit
     * @return mixed
     */
    public function getData($search = '', $searchableColumns = [], $orderBy = '', $orderDirection = 'ASC', $start = 0, $limit = null,$siteId = null, $adminId = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));

        if(!empty($searchableColumns) && !empty($search)) {
            foreach($searchableColumns as $column) {
                $select->where->or->like($column, '%'.$search.'%');
            }
        }
        if($siteId == 'null') $siteId = null;
        if($adminId == 'null') $adminId = null;
        if(!is_null($siteId)){
            $select->where->equalTo('uac_site_id',$siteId);
        }
        if(!is_null($adminId)){
            $select->where->equalTo('uac_admin',$adminId);
        }

        if(!empty($orderBy)) {
            $select->order($orderBy . ' ' . $orderDirection);
        }
        $getCount = $this->tableGateway->selectWith($select);
        // set current data count for pagination
        $this->setCurrentDataCount((int) $getCount->count());

        if(!empty($limit)) {
            $select->limit((int)$limit);
        }

        if(!empty($start)) {
            $select->offset((int)$start);
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    /**
     *
     * @param $email
     * @param $password
     * @return mixed
     */
    public function getUserByEmailAndPassword($email, $password)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));

        $select->where->equalTo('uac_email',$email);
        $select->where->equalTo('uac_password',$password);

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;

    }
    public function getTextByLangId($catId, $langId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('catt2_name'));

        $select->where->equalTo('catt2_category_id',$catId);

        $select->where->equalTo('catt2_lang_id',$langId);
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function getTextIdByLangIdCatId($catId , $langId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('catt2_id'));

        $select->where->equalTo('catt2_category_id',$catId);
        $select->where->equalTo('catt2_lang_id',$langId);

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }
    public function getCategoryTranslationsByCatId($catId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));

        $tblToJoin   = "melis_cms_lang";
        $relation    = "melis_cms_lang.lang_cms_id = melis_cms_category2_trans.catt2_lang_id ";
        $joinColumns = ['*'];
        $joinType    = $select::JOIN_LEFT;
        $select->join($tblToJoin,$relation,$joinColumns,$joinType);

        $select->where->equalTo('catt2_category_id',$catId);

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }

}