<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2Test\Controller;

use MelisCore\ServiceManagerGrabber;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class MelisCmsCategory2Test extends AbstractHttpControllerTestCase
{
    protected $traceError = false;
    protected $sm;
    protected $method = 'save';

    public function setUp()
    {
        $this->sm  = new ServiceManagerGrabber();
    }
    /**
     * Get getMelisCmsCategory2Table table
     * @return mixed
     */
    private function getMelisCmsCategory2Table()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCmsCategory2', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisCmsCategory2SitesTable table
     * @return mixed
     */
    private function getMelisCmsCategory2SitesTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCmsCategory2', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisCmsCategory2MediaTable table
     * @return mixed
     */
    private function getMelisCmsCategory2MediaTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCmsCategory2', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    /**
     * Get getMelisCmsCategory2MediaTable table
     * @return mixed
     */
    private function getMelisCmsCategory2TransTable()
    {
        $conf = $this->sm->getPhpUnitTool()->getTable('MelisCmsCategory2', __METHOD__);
        return $this->sm->getTableMock(new $conf['model'], $conf['model_table'], $conf['db_table_name'], $this->method);
    }
    public function getPayload($method)
    {
        return $this->sm->getPhpUnitTool()->getPayload('MelisCmsCategory2', $method);
    }

    /**
     * START ADDING YOUR TESTS HERE
     */

    public function testCmsCategory()
    {
        $payloads = $this->getPayload(__METHOD__);
        $this->method = 'fetchAll';
        $table = $this->getMelisCmsCategory2Table();

        foreach($payloads['create'] as $data){
            $id = $table->save($data);
            $this->assertNotEmpty($id, "Failed to insert language");
        }

        foreach($payloads['read'] as $data){
            $result = $table->getEntryByField($data['column'], sprintf($data['value'],$id))->current();
            $this->assertNotEmpty($result);
        }

        foreach($payloads['delete'] as $data){
            $table->deleteById($id);
            $result = $table->getEntryByField($data['column'], sprintf($data['value']), $id);
            $this->assertEmpty($result, "Failed to delete currency");
        }
    }
    public function testCmsSitesCategory()
    {
        $payloads = $this->getPayload(__METHOD__);
        $this->method = 'fetchAll';
        $table = $this->getMelisCmsCategory2SitesTable();

        foreach($payloads['create'] as $data){
            $id = $table->save($data);
            $this->assertNotEmpty($id, "Failed to insert language");
        }

        foreach($payloads['read'] as $data){
            $result = $table->getEntryByField($data['column'], sprintf($data['value'],$id))->current();
            $this->assertNotEmpty($result);
        }

        foreach($payloads['delete'] as $data){
            $table->deleteById($id);
            $result = $table->getEntryByField($data['column'], sprintf($data['value']), $id);
            $this->assertEmpty($result, "Failed to delete currency");
        }

    }
    public function testCmsMediaCategory()
    {
        $payloads = $this->getPayload(__METHOD__);
        $this->method = 'fetchAll';
        $table = $this->getMelisCmsCategory2MediaTable();

        foreach($payloads['create'] as $data){
            $id = $table->save($data);
            $this->assertNotEmpty($id, "Failed to insert language");
        }

        foreach($payloads['read'] as $data){
            $result = $table->getEntryByField($data['column'], sprintf($data['value'],$id))->current();
            $this->assertNotEmpty($result);
        }

        foreach($payloads['delete'] as $data){
            $table->deleteById($id);
            $result = $table->getEntryByField($data['column'], sprintf($data['value']), $id);
            $this->assertEmpty($result, "Failed to delete currency");
        }
    }
    public function testCmsTransCategory()
    {
        $payloads = $this->getPayload(__METHOD__);
        $this->method = 'fetchAll';
        $table = $this->getMelisCmsCategory2TransTable();

        foreach($payloads['create'] as $data){
            $id = $table->save($data);
            $this->assertNotEmpty($id, "Failed to insert language");
        }

        foreach($payloads['read'] as $data){
            $result = $table->getEntryByField($data['column'], sprintf($data['value'],$id))->current();
            $this->assertNotEmpty($result);
        }

        foreach($payloads['delete'] as $data){
            $table->deleteById($id);
            $result = $table->getEntryByField($data['column'], sprintf($data['value']), $id);
            $this->assertEmpty($result, "Failed to delete currency");
        }
    }

}

