<?php

/**
 * Classe para salvar as configurações utilizadas no login do usuário do sistema
 * para uso dentro do sistema
 * 
 * @category My
 * @package My_Application
 * @subpackage My_Application_Resource
 * @author TRB
 * @copyright My@2012
 */
class My_Application_Resource_Login extends Zend_Application_Resource_ResourceAbstract
{

    public function init()
    {
        //Registra para uso posterior
        Zend_Registry::set( 'login', $this->getOptions() );
    }

}