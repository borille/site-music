<?php

/**
 * Classe para salvar as configurações gerais do aplicativo
 * para uso dentro do sistema
 * 
 * @category My
 * @package My_Application
 * @subpackage My_Application_Resource
 * @author TRB
 * @copyright My@2012
 */
class My_Application_Resource_App extends Zend_Application_Resource_ResourceAbstract
{

    public function init()
    {
        //Registra para uso posterior
        Zend_Registry::set( 'app', $this->getOptions() );
    }

}