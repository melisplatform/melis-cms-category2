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

use MelisCmsCategory2\Model\MelisCmsCategory2Media;
use MelisCmsCategory2\Model\Tables\MelisCmsCategory2MediaTable;

class MelisCmsCategory2MediaTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisCmsCategory2Media());
        $tableGateway = new TableGateway('melis_cms_category2_media', $sl->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
        
        return new MelisCmsCategory2MediaTable($tableGateway);
    }
    
}