<?php

/**
 * Classe para configurar a Pagina��o
 * para uso dentro do sistema
 * 
 * @category My
 * @package My_Application
 * @subpackage My_Application_Resource
 * @author TRB
 * @copyright My@2012
 */
class My_Application_Resource_Paginator extends Zend_Application_Resource_ResourceAbstract
{

    public function init()
    {
        $options = $this->getOptions();

        /* Estilo */
        if ( isset( $options['style'] ) )
        {
            Zend_Paginator::setDefaultScrollingStyle( $options['style'] );
        }

        /* Elementos por P�gina */
        if ( isset( $options['perpage'] ) )
        {
            Zend_Paginator::setDefaultItemCountPerPage( $options['perpage'] );
        }

        /* Script para Renderiza��o */
        if ( isset( $options['script'] ) )
        {
            Zend_View_Helper_PaginationControl::setDefaultViewPartial( $options['script'] );
        }

        /* Pr�prio Objeto como Recurso */
        return $this;
    }
}