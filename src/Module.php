<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCmsCategory2;

use MelisCmsCategory2\Listener\MelisCmsCategory2FlashMessengerListener;
use MelisCmsCategory2\Listener\MelisCmsCategoryNewsListListener;
use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;
use Laminas\ModuleManager\ModuleManager;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Session\Container;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $sm = $e->getApplication()->getServiceManager();
        $routeMatch = $sm->get('router')->match($sm->get('request'));

        if (!empty($routeMatch)) {
            $this->createTranslations($e, $routeMatch);
        }

        $renderType = $this->getRenderType($e);
        # attach listener to back-office to avoid conflict in front
        if ($renderType == 'back') {
            # attach listener
            (new MelisCmsCategory2FlashMessengerListener())->attach($eventManager);
        }

        (new MelisCmsCategoryNewsListListener())->attach($eventManager);
    }

    /**
     * Module initializations
     * @param ModuleManager $manager
     */
    public function init(ModuleManager $manager)
    {

    }

    /**
     * Determine the rendering type of the platform
     * @param $serviceManager
     * @return string|null
     */
    private function getRenderType($serviceManager)
    {
        $sm         = $serviceManager->getApplication()->getServiceManager();
        $routeMatch = $sm->get('router')->match($sm->get('request'));
        $renderType = null;
        if (!empty($routeMatch)) {
            $routeName = $routeMatch->getMatchedRouteName();
            $module   = explode('/', $routeName);
            if (!empty($module[0])) {
                if ($module[0] == 'melis-backoffice') {
                    $renderType = 'back';
                } else {
                    $renderType = 'front';
                }
            }
        }

        return $renderType;

    }

    /**
     * Get the file configuration of the moduel
     * @return array
     */
    public function getConfig() : array
    {
        $config = array();
        $configFiles = array(
            include __DIR__ . '/../config/module.config.php',
            // interface design Melis
            include __DIR__ . '/../config/app.interface.php',
            include __DIR__ . '/../config/app.forms.php',
            //forms
            include __DIR__ . '/../config/plugins/form/plugin.form.php',
            // Tests
            include __DIR__ . '/../config/diagnostic.config.php',
            // Templating plugins
            include __DIR__ . '/../config/plugins/MelisCmsCategoryDisplayCategoriesPlugin.config.php',
            include __DIR__ . '/../config/plugins/MelisCmsNewsLatestNewsPlugin.config.php',
            include __DIR__ . '/../config/plugins/MelisCmsNewsListNewsPlugin.config.php',
        );

        foreach ($configFiles as $file) {
            $config = ArrayUtils::merge($config, $file);
        }

        return $config;
    }

    /**
     * Autoloader
     * @return array
     */
    public function getAutoloaderConfig() : array
    {
        return array(
            'Laminas\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * Creating the translations of the platform
     * @param $e
     */
    public function createTranslations($e, $routeMatch)
    {
        $sm = $e->getApplication()->getServiceManager();
        $translator = $sm->get('translator');
        $locale = null;
        // Checking if the Request is from Melis-BackOffice or Front
        $renderMode = (isset($param['renderMode'])) ? $param['renderMode'] : 'melis';
        if ($renderMode == 'melis') {
            $container = new Container('meliscore');
            $locale = $container['melis-lang-locale'];
        } else {
            $container = new Container('melisplugins');
            $locale = $container['melis-plugins-lang-locale'];
        }

        if (!empty($locale)){
            //translation type
            $translationType = array(
                'interface',
            );
            $translationList = array();
            if(file_exists($_SERVER['DOCUMENT_ROOT'].'/../module/MelisModuleConfig/config/translation.list.php')){
                $translationList = include 'module/MelisModuleConfig/config/translation.list.php';
            }
            foreach($translationType as $type){
                $transPath = '';
                $moduleTrans = __NAMESPACE__."/$locale.$type.php";
                if(in_array($moduleTrans, $translationList)){
                    $transPath = "module/MelisModuleConfig/languages/".$moduleTrans;
                }

                if(empty($transPath)){
                    // if translation is not found, use melis default translations
                    $defaultLocale = (file_exists(__DIR__ . "/../language/$locale.$type.php"))? $locale : "en_EN";
                    $transPath = __DIR__ . "/../language/$defaultLocale.$type.php";
                }

                $translator->addTranslationFile('phparray', $transPath);
            }
        }
    }
}
