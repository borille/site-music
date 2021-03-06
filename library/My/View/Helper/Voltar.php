<?php

class My_View_Helper_Voltar extends Zend_View_Helper_Abstract
{

    public function voltar( $params = null )
    {

        if ( is_array( $params ) && $params != null ) //verifica se o params � um array e tamb�m � diferente de null
        {
            $front = Zend_Controller_Front::getInstance();

            $router = $front->getRouter();
            $request = $front->getRequest();

            if ( $params['controller'] == null )
            {
                $params['controller'] = $request->getControllerName();
            }

            $url = $router->assemble( $params, null, TRUE );
        }
        elseif ( $params != null ) //verifica se � diferente de null
        {
            $url = $params;
        }
        else
        {
            $url = 'javascript:history.back()';
        }

        $output = "<div id='voltar'><a href='$url' title='P�gina Anterior'>";
        $output .= '<img style="vertical-align: bottom;" width="20" src="' . INCLUDE_PATH . '/img/back.png"/> ';
        $output .= 'Voltar</a></div>';

        return $output;
    }
}