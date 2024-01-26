<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2\Form\Factory;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\Form\Element\Text;

class MelisCmsCategorySelectFactory extends Text
{
    public function __invoke(ContainerInterface $container, $requestedName)
    {
        $element = new Text;
        $element->setAttribute('class', 'form-control melis-cms-category-select');
        return $element;
    }
}