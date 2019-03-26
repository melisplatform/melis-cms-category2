<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2\Service\Factory;

use MelisCmsCategory2\Service\MelisCmsCategoryMediaService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;


class MelisCmsCategoryMediaServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $service = new MelisCmsCategoryMediaService();
        $service->setServiceLocator($sl);
        $service->categoryMediaTbl = $sl->get('MelisCmsCategory2MediaTable');

        return $service;
    }

}