<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2015 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\Session\Container;
use MelisCore\Listener\MelisGeneralListener;

class MelisCmsCategoryDeleteSeoListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public $listeners = [];

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            '*',
            'meliscms_category2_save_end',
            function($e){

                $sm = $e->getTarget()->getServiceManager();
                 
                $params = $e->getParams(); 

                if($params['typeCode'] == 'CMS_CATEGORY2_DELETE') {
                    $categoryId = $params['itemId'];
                    $MelisCmsCategorySeoService = $sm->get('MelisCmsCategorySeoService');
                    $MelisCmsCategorySeoService->deleteCategorySeoData($categoryId); 
                    
                } 
            },
        -1000);
        
        $this->listeners[] = $callBackHandler;
    }
}