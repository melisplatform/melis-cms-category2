<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2\Form\Factory;

use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\Form\Element\Text;
use MelisCore\Form\Factory\MelisTextFactory;

class MelisCmsCategorySelectFactory extends MelisTextFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return Text|object
     */
    public function __invoke(ContainerInterface $container, $requestedName)
    {
        $element = new Text;
        $element->setAttribute('class', 'melis-cms-category-select');
        return $element;
    }
}