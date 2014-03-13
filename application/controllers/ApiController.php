<?php

/**
 * Kontroler API 
 * 
 * @category    Webshot
 * @package     Controller
 * @author      Lukasz Ciolecki <ciolecki.lukasz@gmail.com>
 * @copyright   Lukasz Ciolecki (mart)
 */
class ApiController extends Webshot_Controller_Action
{
    /**
     * CutyCapt logic
     *  
     * @var Webshot_Logic_CutyCapt
     */
    protected $logicCutyCapt = null;

    /**
     * Init
     */
    public function init()
    {
        $this->logicCutyCapt = new Webshot_Logic_CutyCapt();
    }

    public function createAction()
    {
        $service = $this->getEntityService($this->_getParam('hash', ''));
        if ($this->_request->isPost()) {
            $webshot = $this->logicCutyCapt->createScreen($service, $this->_request->getPost());
            $this->_helper->json(array('hash' => $webshot->getHash(), 'image' => $webshot->getImage()->getUrl()));
        }
        
        throw new Extlib\Exception(translate('Incorrect request method for the resource.'), Extlib\Http\Response::CODE_METHOD_NOT_ALLOWED);
    }
}

