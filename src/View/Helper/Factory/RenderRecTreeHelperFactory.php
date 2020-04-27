<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2017 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2\View\Helper\Factory;

use MelisCmsCategory2\View\Helper\RenderRecTreeHelper;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\FactoryInterface;

class RenderRecTreeHelperFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{
		$serviceLoc = $sl->getServiceManager();
		$helper = new RenderRecTreeHelper($serviceLoc);
	    
	    return $helper;
	}

}