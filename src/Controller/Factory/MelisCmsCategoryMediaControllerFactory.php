<?php
namespace MelisCmsCategory2\Controller\Factory;

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use MelisCmsCategory2\Controller\MelisCmsCategoryMediaController;

class MelisCmsCategoryMediaControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $controller = new MelisCmsCategoryMediaController($serviceLocator);
        $controller->setServiceLocator($serviceLocator);
        return $controller;
    }
}