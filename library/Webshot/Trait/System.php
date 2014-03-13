<?php

/**
 * System trait
 * 
 * @category   Webshot
 * @package    Trait
 * @author      Lukasz Ciolecki <ciolecki.lukasz@gmail.com>
 */
trait Webshot_Trait_System
{
    /**
     * Config instance
     * 
     * @var Zend_Config
     */
    protected $config = null;
    
    /**
     * ager
     * 
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em = null;
    
    /**
     * System session
     * 
     * @var Zend_Session_Namespace
     */
    protected $session = null;
    
    /**
     * System cookie
     * 
     * @var Extlib\Cookie
     */
    protected $cookie = null;
    
    /**
     * System instance
     * 
     * @var \Extlib\System
     */
    protected $system = null;

     /**
     * Get config
     * 
     * @return Zend_Config
     */
    public function getConfig()
    {
        if (null === $this->config) {
            $this->setConfig(Zend_Registry::get('config'));
        }
        
        return $this->config;
    }

    /**
     * Set config
     * 
     * @param Zend_Config $config
     * @return \Webshot_Logic_Abstract
     */
    public function setConfig(Zend_Config $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Get EntityManager
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm()
    {
        if (null === $this->em) {
            $this->setEm(Zend_Registry::get('em'));
        }
        
        return $this->em;
    }

    /**
     * Set EntityManager
     * 
     * @param \Doctrine\ORM\EntityManager $em
     * @return \Webshot_Logic_Abstract
     */
    public function setEm(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
        return $this;
    }

    /**
     * Get system session
     * 
     * @return Zend_Session_Namespace
     */
    public function getSession()
    {
        if (null === $this->session) {
            $this->setSession(new Zend_Session_Namespace('webshot'));
        }
       
        return $this->session;
    }

    /**
     * Set system session
     * 
     * @param Zend_Session_Namespace $session
     * @return \Webshot_Logic_Abstract
     */
    public function setSession(Zend_Session_Namespace $session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * Get system cookie
     * 
     * @return Extlib\Cookie
     */
    public function getCookie()
    {
        if (null === $this->cookie) {
            $this->setCookie(new \Extlib\Cookie('webshot'));        
            
        }
        
        return $this->cookie;
    }

    /**
     * Set system cookie
     * 
     * @param Extlib\Cookie $cookie
     * @return \Webshot_Logic_Abstract
     */
    public function setCookie(Extlib\Cookie $cookie)
    {
        $this->cookie = $cookie;
        return $this;
    }
    
    /**
     * Get system object
     * 
     * @return \Extlib\System
     */
    public function getSystem()
    {
        if (null === $this->system) {
            $this->system = \Extlib\System::getInstance();
        }
        
        return $this->system;
    }
    
    /**
     * Check signature
     * 
     * @param array $params
     * @param string $key
     * @return boolean
     */
    public function checkSignKey(array $params, $key)
    {
        $signKey = '';
        if (isset($params[$this->getConfig()->system->signKeyName])) {
            $signKey = $params[$this->getConfig()->system->signKeyName];
            unset($params[$this->getConfig()->system->signKeyName]);
        }
        
        return $signKey === $this->createSignKey($params, $key);
    }
    
    /**
     * Create signature
     * 
     * @param array $params
     * @param string $key
     * @return string
     */
    public function createSignKey(array $params, $key)
    {
        return md5(implode('', $params) . $key);
    }
    
    /**
     * Url creator
     *     
     * @param  array $urlOptions 
     * @param  mixed $name 
     * @param  bool $reset 
     * @return string
     */
    public function url(array $urlOptions = array(), $name = null, $reset = false, $encode = true)
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        return $router->assemble($urlOptions, $name, $reset, $encode);
    }
}