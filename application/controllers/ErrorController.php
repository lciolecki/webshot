<?php

use Extlib\Http\Response;

/**
 * Kontroler obslugi bledow
 * 
 * @category    Webshot
 * @package     Controller
 * @author      Lukasz Ciolecki <ciolecki.lukasz@gmail.com>
 * @copyright   Lukasz Ciolecki (mart)
 */
class ErrorController extends Webshot_Controller_Action
{
    /**
     * Domyslna akcja bledow
     */
    public function errorAction()
    {
        $handler = $this->getErrorHandler();
        $error = new stdClass();
        $error->details = array();
        $error->exception = $handler->exception;
        $error->code = Response::CODE_INTERNAL_SERVER_ERROR;
        $error->message = Response::getMessage($error->code);
        
        switch ($handler->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                $error->code = Response::CODE_NOT_FOUND;
                $error->message = Response::getMessage($error->code);
                break;
            default:
                if ($handler->exception instanceof Extlib\Exception) {
                    $error = $this->getExceptionData($handler->exception);
                } 
                break;
        }

        if (Response::CODE_INTERNAL_SERVER_ERROR <= $error->code) {
            $this->log($error->message, Zend_Log::CRIT, $handler->exception->getTraceAsString());
        }

        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $handler->exception;
        } 
        
        $this->view->details = $error->details;
        $this->view->message = $error->message;
        $this->view->request = $handler->request;
        $this->getResponse()->setHttpResponseCode($error->code);
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->json($error);
        }
    }
    
    /**
     * Metoda zwracajaca Error Handler
     * 
     * @return ArrayObject
     */
    public function getErrorHandler()
    {
        return $this->getParam('error_handler');
    }
    
    /**
     * Metoda zwracajaca dane wyjatku
     * 
     * @param Extlib\Exception $exception
     * @return \stdClas
     */
    public function getExceptionData(Extlib\Exception $exception)
    {
        $data = new stdClass();
        $data->message = $exception->getMessage();
        $data->details = $exception->getDetails() ? $exception->getDetails() : array();
        $data->code = $exception->getCode();

//        if ($exception->getPrevious()) {
//            $data->exception = $exception->getPrevious();
//        } else {
//            $data->exception = $exception;
//        }

        return $data;
    }
}

