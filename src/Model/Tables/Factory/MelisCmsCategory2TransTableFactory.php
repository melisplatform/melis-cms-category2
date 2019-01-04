<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2017 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2\Model\Tables\Factory;


use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Hydrator\ObjectProperty;

use MelisCmsCategory2\Model\MelisCmsCategory2Trans;
use MelisCmsCategory2\Model\Tables\MelisCmsCategory2TransTable;

class MelisCmsCategory2TransTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisCmsCategory2Trans());
        $tableGateway = new TableGateway('melis_cms_category2_trans', $sl->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
        
        return new MelisCmsCategory2TransTable($tableGateway);
    }
    
}