<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Inicjalizacja konfiguracji aplikacji
     * 
     * @return \Zend_Config_Ini
     */
    public function _initConfig()
    {
        Extlib\System::getInstance();
        
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/webshot.ini', APPLICATION_ENV);
        Zend_Registry::set('config', $config);
        
        return $config;
    }

    /**
     * Inicjalizacja EntityManagera
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function _initEntityManager()
    {
        $this->bootstrap('doctrine');

        $container = Zend_Registry::get('doctrine');
        Zend_Registry::set('em', $container->getEntityManager('default'));
        return $container->getEntityManager('default');
    }
    
    /**
     * Inicjalizacja loggera
     * 
     * @return Zend_Log
     */
    protected function _initLogger()
    {
	$this->bootstrap('log');
	$logger = $this->getResource('log');
	Zend_Registry::set('logger', $logger);
	return $logger;
    }
    
    /**
     * Inicjalizacja autoloadingu
     * 
     * @return \Zend_Loader_Autoloader_Resource
     */
    protected function _initAutoload()
    {
        return new Zend_Loader_Autoloader_Resource(array(
            'basePath' => APPLICATION_PATH,
            'namespace' => 'Webshot',
            'resourceTypes' => array(
                'form' => array(
                   'namespace' => 'Form',
                   'path' => 'forms'
                )
            )   
        ));
    }
    
    protected function _initDebug()
    {
        $options = array(
            'plugins' => array(
                'Html',
                'File',
                'Exception',
                'Memory',
                'Time',
            )
        );

        $debug = new ZFDebug_Controller_Plugin_Debug($options);
        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        $frontController->registerPlugin($debug);
    }
}

