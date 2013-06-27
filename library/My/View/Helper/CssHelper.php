<?php

class My_View_Helper_CssHelper extends Zend_View_Helper_Abstract
{

    function cssHelper( $url = null )
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();

        //verifica se existe algum arquivo CSS public/css/controller/controller.css
        $file_uri = 'css/' . $request->getModuleName() . '/' . $request->getControllerName() . '/' . $request->getControllerName() . '.css';

        if ( file_exists( $file_uri ) )
        {
            $this->view->headLink()->appendStylesheet( $url . '/' . $file_uri );
        }

        //verifica se existe algum arquivo CSS public/css/controller/action.css
        $file_uri = 'css/' . $request->getModuleName() . '/' . $request->getControllerName() . '/' . $request->getActionName() . '.css';

        if ( file_exists( $file_uri ) )
        {
            $this->view->headLink()->appendStylesheet( $url . '/' . $file_uri );
        }
    }
}