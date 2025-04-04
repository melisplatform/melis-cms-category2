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

class MelisCmsCategoryNewsListListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public $listeners = [];

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
            '*',
            'meliscmsnews_list',
            function($e){

                $sm = $e->getTarget()->getServiceManager();
                 
                $params = $e->getParams(); 
                
                $container = new Container('melisplugins');
                $langId = $container['melis-plugins-lang-id'];
                $pluginData = $params['plugin']->getFormData();

                $status = true; 
                $dateMin = !empty($pluginData['date_min']) ? $pluginData['date_min'] : null;
                $dateMax = !empty($pluginData['date_max']) ? $pluginData['date_max'] : null;
                $unpublishFilter = !empty($pluginData['unpublish_filter']) ? $pluginData['unpublish_filter'] : null;
                $orderColumn = !empty($pluginData['column']) ? $pluginData['column'] : null;
                $order = !empty($pluginData['order']) ? $pluginData['order'] : null;
                $siteId = !empty($pluginData['site_id']) ? $pluginData['site_id'] : null;
                $search = !empty($pluginData['search']) ? $pluginData['search'] : null;
                $categoryIdNews = !empty($pluginData['categoryIdNews']) ? $pluginData['categoryIdNews'] : null;
 
                if($categoryIdNews) {

                    $newsSrv = $sm->get('MelisCmsCategory2Service');
                    $newsArr = $newsSrv->getCategoryNewsList($status, $categoryIdNews, $langId, null, null, $dateMin, $dateMax, $unpublishFilter, null, null, $orderColumn, $order, $siteId,  $search);
     
                    $params['newsList'] = $newsArr; 
                }
            },
        -1000);
        
        $this->listeners[] = $callBackHandler;
    }
}