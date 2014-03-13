<?php

/**
 * System controller
 * 
 * @category    Webshot
 * @package     Controller
 * @author      Lukasz Ciolecki <ciolecki.lukasz@gmail.com>
 * @copyright   Lukasz Ciolecki (mart)
 */
class SystemController extends Webshot_Controller_Action
{
    public function imageAction()
    {
        $file = $this->getEntityFile($this->getParam('hash'));
        $this->_helper->file($file->getFullFilePath());
    }
    
    public function webshotAction()
    {
        if ($this->getRequest()->isXmlHttpRequest() && $this->_request->isPost()) {
            $service = $this->getEntityService($this->getParam('hash'));
            $params = $this->_request->getPost();
            $params[$this->getConfig()->system->signKeyName] = $this->createSignKey($this->_request->getPost(), $service->getSecretKey());

            $url = sprintf('%s/api/create', $this->getSystem()->getDomain());            
            $client = new Zend_Http_Client($url);
            $client->setMethod(Zend_Http_Client::POST)
                   ->setHeaders('X-Requested-With', 'XMLHttpRequest') //Send by ajax request. If an error occurs json object will be returned
                   ->setParameterPost($params)
                   ->setConfig(array('timeout' => 180));
            
            $response = $client->request();
            
            $return = Zend_Json::decode($response->getBody());
            $code = isset($return['code']) ? $return['code'] : 200;
  
            $this->getResponse()->setHttpResponseCode($code);
            $this->_helper->json($return);
        }
    }
}

