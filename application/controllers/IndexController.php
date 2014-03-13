<?php

/**
 * Kontroler landing page
 * 
 * @category    Webshot
 * @package     Controller
 * @author      Lukasz Ciolecki <ciolecki.lukasz@gmail.com>
 * @copyright   Lukasz Ciolecki (mart)
 */
class IndexController extends Webshot_Controller_Action
{
    /**
     * Inicjalizacja kontrolara
     */
    public function init()
    {
    }

    public function indexAction()
    {
        $form = new Webshot_Form_TestApi(array('submitLabel' => translate('Create screen')));
        $this->view->avaiableOptions = $this->getConfig()->system->cutycapt->options->toArray();
        $this->view->form = $form->setAction($this->url(array('action' => 'webshot', 'controller' => 'system'), null, true));
    }
}

