<?php

class Admin_InstrumentController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->view->data = Application_Model_DbTable_Instrument::getInstance();
    }

    public function addAction()
    {
        $form = new Admin_Form_Instrument();

        if ( $this->getRequest()->isPost() )
        {
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                if ( Application_Model_DbTable_Instrument::getInstance()->insert( $form->getValues() ) )
                {
                    My_Action_Helper::showMessage( 'Salvo com Sucesso!' );
                }
                else
                {
                    My_Action_Helper::showMessage( 'Erro ao salvar!' );
                }
                My_Action_Helper::redirect( $this->view->controller, 'index', array( ), 'admin' );
            }
            else
            {
                $form->populate( $this->getRequest()->getPost() );
            }
        }
        $this->view->form = $form;
    }

    public function editAction()
    {
        $form = new Admin_Form_Instrument();
        $table = new Application_Model_DbTable_Instrument();

        if ( $this->getRequest()->isPost() )
        {
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                if ( $table->update( $form->getValues() ) )
                {
                    My_Action_Helper::showMessage( 'Salvo com Sucesso!' );
                }
                else
                {
                    My_Action_Helper::showMessage( 'Erro ao salvar!' );
                }
                My_Action_Helper::redirect( $this->view->controller, 'index', array( ), 'admin' );
            }
            else
            {
                $form->populate( $this->getRequest()->getPost() );
            }
        }
        else
        {
            $form->populate( $table->getId( $this->getRequest()->getParam( 'id' ) ) );
        }
        $this->view->form = $form;
    }
}

