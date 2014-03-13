<?php

/**
 * Base action class
 * 
 * @category    Webshot
 * @package     Controller
 * @author      Lukasz Ciolecki <ciolecki.lukasz@gmail.com>
 * @copyright   Lukasz Ciolecki (mart)
 */
class Webshot_Controller_Action extends Zend_Controller_Action
{
    /* Traits */
    use Webshot_Trait_System, 
        Webshot_Trait_Log;
    
    /**
     * Get Entity Service by hash
     * 
     * @param string $hash
     * @return \Entity\Service
     */
    protected function getEntityService($hash)
    {
        $application = $this->getEm()->getRepository('Entity\Service')->findOneBy(array('hash' => $hash));
        if (null === $application) {
            throw new Extlib\Exception(translate('Not found an authorized service.'), Extlib\Http\Response::CODE_NOT_FOUND);
        }
        
        return $application;
    }
    
    /**
     * Get Entity File by hash
     * 
     * @param string $hash
     * @return \Entity\File
     */
    protected function getEntityFile($hash)
    {
        $file = $this->getEm()->getRepository('Entity\File')->findOneBy(array('hash' => $hash));
        if (null === $file) {
            throw new Extlib\Exception(translate('Not found an authorized service.'), Extlib\Http\Response::CODE_NOT_FOUND);
        }
        
        return $file;
    }
}