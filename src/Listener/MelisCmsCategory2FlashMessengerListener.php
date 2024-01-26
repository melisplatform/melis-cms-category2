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
use MelisCore\Listener\MelisGeneralListener;

class MelisCmsCategory2FlashMessengerListener extends MelisGeneralListener implements ListenerAggregateInterface
{
	public $listeners = [];

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedEvents      = $events->getSharedManager();
        
        $callBackHandler = $sharedEvents->attach(
        	'MelisCmsCategory2',
            'meliscms_category2_save_end',
        	function($e){

        		$sm = $e->getTarget()->getServiceManager();
        		
        		$flashMessenger = $sm->get('MelisCoreFlashMessenger');
        		$params = $e->getParams();
                $params['textTitle'] = $params['textTitle'] ?? null;
                $params['textMessage'] = $params['textMessage'] ?? null;

        		//$flashMessenger->addToFlashMessenger('tr_melis_cms_user_account_header_title', 'tr_meliscms_user_account_save_ok');
        		$results = $e->getTarget()->forward()->dispatch(
        		    'MelisCore\Controller\MelisFlashMessenger',
        		    array_merge(array('action' => 'log'), $params))->getVariables();

        	},
        -1000);
        
        $this->listeners[] = $callBackHandler;
    }
}