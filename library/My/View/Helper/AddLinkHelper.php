<?php

class My_View_Helper_AddLinkHelper extends Zend_View_Helper_Abstract
{

    public function addLinkHelper( $controller = null, $text = 'Adicionar', $description = 'Incluir novo registro', $id = null )
    {
        //verifica se foi passado algum nome de controller, senao tenta pegar o atual
        if ( !$controller )
        {
            $controller = $this->view->controller;
        }

        $output = '<p style="margin-bottom: 10px">';

        if ( $id )
        {
            $output .= '<a href="' . $this->view->url( array( 'module' => $this->view->module, 'controller' => $controller, 'action' => 'add', 'id' => $id ), null, TRUE ) . '" title="' . $description . '">';
        }
        else
        {
            $output .= '<a href="' . $this->view->url( array( 'module' => $this->view->module, 'controller' => $controller, 'action' => 'add' ), null, TRUE ) . '" title="' . $description . '">';
        }

        $output .= '<img style="vertical-align: top" width="20" src="' . INCLUDE_PATH . '/img/add.png"/> ';
        $output .= $text;
        $output .= '</a></p>';

        return $output;
    }
}

?>