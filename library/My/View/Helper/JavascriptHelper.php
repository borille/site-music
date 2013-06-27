<?php

class My_View_Helper_JavascriptHelper extends Zend_View_Helper_Abstract
{

    function javascriptHelper( $url = null )
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();

        //verifica se existe algum arquivo JS public/js/controller/controller.js
        $file_uri = 'js/' . $request->getModuleName() . '/' . $request->getControllerName() . '/' . $request->getControllerName() . '.js';

        if ( file_exists( $file_uri ) )
        {
            $this->view->headScript()->appendFile( $url . '/' . $file_uri );
        }

        //verifica se existe algum arquivo JS public/js/controller/action.js
        $file_uri = 'js/' . $request->getModuleName() . '/' . $request->getControllerName() . '/' . $request->getActionName() . '.js';

        if ( file_exists( $file_uri ) )
        {
            $this->view->headScript()->appendFile( $url . '/' . $file_uri );
        }
    }
}