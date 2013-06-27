<?php

class Admin_UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->view->data = Admin_Model_DbTable_UserAdmin::getInstance();
    }

    public function loginAction()
    {
        $form = new Admin_Form_Login();

        if ( $this->getRequest()->isPost() )
        {
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                if ( !Admin_Model_DbTable_UserAdmin::getInstance()->login( $form->getValue( 'user' ), $form->getValue( 'password' ) ) )
                {
                    My_Action_Helper::showMessage( 'Invalid user name or password!' );
                    My_Action_Helper::redirect( 'user', 'login', array( ), 'admin' );
                }
                My_Action_Helper::redirect( 'index', 'index', array( ), 'admin' );
            }
            else
            {
                $form->populate( $this->getRequest()->getPost() );
            }
        }
        $this->view->form = $form;
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::destroy();
        My_Action_Helper::redirect( 'index' );
    }

    public function addAction()
    {
        $form = new Admin_Form_UserAdmin();

        if ( $this->getRequest()->isPost() )
        {
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                $form->getElement( 'USER_ADMIN_PASSWORD' )->setValue( md5( $form->getValue( 'USER_ADMIN_PASSWORD' ) ) );
                $form->removeElement( 'PASS_CONFIRM' );

                if ( Admin_Model_DbTable_UserAdmin::getInstance()->insert( $form->getValues() ) )
                {
                    My_Action_Helper::showMessage( 'Salvo com Sucesso!' );
                }
                else
                {
                    My_Action_Helper::showMessage( 'Erro ao salvar!' );
                }
                My_Action_Helper::redirect( 'user', 'index', array( ), 'admin' );
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
        $form = new Admin_Form_UserAdmin();
        $table = new Admin_Model_DbTable_UserAdmin();

        if ( $this->getRequest()->isPost() )
        {
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                $form->getElement( 'USER_ADMIN_PASSWORD' )->setValue( md5( $form->getValue( 'USER_ADMIN_PASSWORD' ) ) );
                $form->removeElement( 'PASS_CONFIRM' );

                if ( $table->update( $form->getValues() ) )
                {
                    My_Action_Helper::showMessage( 'Salvo com Sucesso!' );
                }
                else
                {
                    My_Action_Helper::showMessage( 'Erro ao salvar!' );
                }
                My_Action_Helper::redirect( 'user', 'index', array( ), 'admin' );
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

