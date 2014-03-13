<?php

/**
 * Bazowa klasa formularzy aplikacji
 * 
 * @category   Webshot
 * @author     Lukasz Ciolecki <ciolecki.lukasz@gmail.com>
 */
class Webshot_Form extends Twitter_Form
{
    /* Traits */
    use Webshot_Trait_System, 
        Webshot_Trait_Log;

    /**
     * Uses entity
     * 
     * @var \Entity\AbstractEntity
     */
    protected $entity = null;
    
    /**
     * Save button text
     * 
     * @var string
     */
    protected $submitLabel = 'Save';
    
    /**
     * Get submit label
     * 
     * @return string
     */
    public function getSubmitLabel()
    {
        return $this->submitLabel;
    }

    /**
     * Set submit label
     * 
     * @param string $submitLabel
     * @return \Webshot_Form
     */
    public function setSubmitLabel($submitLabel)
    {
        $this->submitLabel = $submitLabel;
        return $this;
    }

        
    /**
     * Get entity
     * 
     * @return \Entity\AbstractEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set entity
     * 
     * @param \Entity\AbstractEntity $entity
     * @return \Webshot_Form
     */
    public function setEntity(\Entity\AbstractEntity $entity)
    {
        $this->entity = $entity;
        return $this;
    }
    
    /**
     * Add subbmit button
     * 
     * @param int $order
     * @return \Webshot_Form
     */
    public function addSubmit($order = null)
    {
        $this->addElement('submit', 'submit', array(
            'label' => $this->getSubmitLabel(),
            'attribs' => array('class' => 'btn'),
            'order' => null !== $order ? $order : count($this->getElements())
        ));
   
//        $this->addElement('button', 'submit', array(
//            'label' => $this->getSubmitLabel(),
//            'attribs' => array('class' => 'btn'),
//            'type' => 'submit',
//            'order' => null !== $order ? $order : count($this->getElements())
//        ));
        
        return $this;
    }
    
    /**
     * Sen response header if form is not valid => 406
     * 
     * @param array data
     * @return blean
     */
    public function isValid($data)
    {
        $valid = parent::isValid($data);
        
        if (false === $valid) {
            Zend_Controller_Front::getInstance()->getResponse()->setHttpResponseCode(Extlib\Http\Response::CODE_NOT_ACCEPTABLE);
        }
        
        return $valid;
    }
}

