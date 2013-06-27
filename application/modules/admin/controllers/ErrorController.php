<?php

class Admin_ErrorController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function errorAction()
    {        
        $errors = $this->_getParam( 'error_handler' );

        if ( !$errors || !$errors instanceof ArrayObject )
        {
            return;
        }

        switch ( $errors->type )
        {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode( 404 );
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode( 500 );
                $priority = Zend_Log::CRIT;
                $this->view->message = 'Application error';
                break;
        }

        // Log exception, if logger available
        if ( ($log = $this->getLog() ) )
        {
            $log->log( $this->view->message, $priority );
            $log->log( 'Request Parameters: ' . implode( '/', $errors->request->getParams() ), $priority );
        }

        // conditionally display exceptions
        if ( $this->getInvokeArg( 'displayExceptions' ) == true )
        {
            $this->view->exception = $errors->exception;
        }

        $this->view->request = $errors->request;
    }

    private function getLog()
    {
        if ( !Zend_Registry::isRegistered( 'logger' ) )
        {
            return false;
        }
        return Zend_Registry::get( 'logger' );
    }

    public function forbiddenAction()
    {
        // action body
    }


}



