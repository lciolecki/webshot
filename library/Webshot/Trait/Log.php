<?php

/**
 * Logger trait
 * 
 * @category   Webshot
 * @package    Trait
 * @author     Lukasz Ciolecki <ciolecki.lukasz@gmail.com>
 */
trait Webshot_Trait_Log
{   
    /**
     * Logger
     * 
     * @var Zend_Log 
     */
    protected $logger = null;
    
    /**
     * Get logger
     * 
     * @return Zend_Log
     */
    public function getLogger()
    {
        if (null === $this->logger) {
            $this->setLogger(Zend_Registry::get('logger'));
        }
        
        return $this->logger;
    }

    /**
     * Set logger
     * 
     * @param Zend_Log $logger
     * @return \Webshot_Logic_Abstract
     */
    public function setLogger(Zend_Log $logger)
    {
        $this->logger = $logger;
        return $this;
    }
    
    /**
     * Metoda logujaca 
     * 
     * @param string $message
     * @param int $priority
     * @param mixed $extras
     * @return mixed
     */
    public function log($message, $priority = \Zend_Log::DEBUG, $extras = null)
    {
        if (null !== $this->getLogger()) {
            $this->getLogger()->log($message, $priority, $extras);
        }
        
        return $this;
    }
}