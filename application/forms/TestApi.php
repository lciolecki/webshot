<?php

/**
 * Formularz testujacy api
 * 
 * @category    Webshot
 * @package     Form
 * @author      Lukasz Ciolecki <ciolecki.lukasz@gmail.com>
 */
class Webshot_Form_TestApi extends Webshot_Form
{
    /**
     * Demo service hash
     */
    const DEMO_SERVICE_HASH = '333d01bbec79831a699e7fb4f6a6d2d2';

    /**
     * Inicjalizacja formularza
     * 
     * @return mixed
     */
    public function init()
    {
        $this->setMethod(Zend_Form::METHOD_POST);
        $this->setAttribs(array(
            'role' => 'form',
            'class' => 'form-horizontal',
            'id' => 'webshot-form'
        ));
        
        $this->addElement('text', 'url', array(
            'required' => true,
            'label' => translate('Page url'),
            'filters' => array(
                'StringTrim'
            ),
            'validators' => array(
                array(new Extlib\Validate\Url()),
            ),
            'attribs' => array(
                'placeholder' => 'http://google.pl'
            ),
        ));
        
        $validator = new Extlib\Validate\Doctrine2\RecordExists(array(
            'entity' => 'Entity\Service',
            'field' => 'hash'
        ));
        
        $this->addElement('hidden', 'hash', array(
            'value' => self::DEMO_SERVICE_HASH,
            'required' => true,
            'label' => translate('Service identity'),
            'filters' => array(
                'StringTrim'
            ),
            'validators' => array(
                $validator
            )
        ));
        
        $this->addSubmit();
        
        return parent::init();
    }
}