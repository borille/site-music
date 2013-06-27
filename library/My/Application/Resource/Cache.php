<?php

/**
 * Classe para iniciar o cache
 * para uso dentro do sistema
 * 
 * @category My
 * @package My_Application
 * @subpackage My_Application_Resource
 * @author TRB
 * @copyright My@2012
 */
class My_Application_Resource_Cache extends Zend_Application_Resource_ResourceAbstract
{

    public function init()
    {
        $options = $this->getOptions();

        $cache = Zend_Cache::factory( $options['frontEnd'], $options['backEnd'], $options['frontEndOptions'], $options['backEndOptions'] );

        //salva no registro para ser utilizado posteriormente
        Zend_Registry::set( 'cache', $cache );
        return $cache;
    }

}