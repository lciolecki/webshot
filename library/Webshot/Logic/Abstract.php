<?php

/**
 * Abstract logic class
 * 
 * @category    Webshot
 * @package     Logic
 * @author      Lukasz Ciolecki <ciolecki.lukasz@gmail.com>
 * @copyright   Lukasz Ciolecki (mart)
 */
abstract class Webshot_Logic_Abstract
{
    use Webshot_Trait_Log,
        Webshot_Trait_System;
    
    /**
     * Instancja konstruktora
     */
    public function __construct()
    {
        
    }
}