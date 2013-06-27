<?php

class Default_UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        if ( Zend_Auth::getInstance()->hasIdentity() )
        {
            
        }
        else
        {
            My_Action_Helper::redirect( 'index' );
        }
    }

    public function loginAction()
    {
        $form = new Default_Form_Login();

        if ( $this->getRequest()->isPost() )
        {
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                if ( !Default_Model_DbTable_User::getInstance()->login( $form->getValue( 'user' ), $form->getValue( 'password' ) ) )
                {
                    My_Action_Helper::showMessage( 'Invalid user name or password!' );
                    My_Action_Helper::redirect( 'user', 'login' );
                }
                My_Action_Helper::redirect( 'index' );
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
        My_Action_Helper::redirect( 'index' );
    }

    public function signUpAction()
    {
        $form = new Default_Form_SignUp();

        if ( $this->getRequest()->isPost() )
        {
            if ( $form->isValid( $this->getRequest()->getPost() ) )
            {
                $form->getElement( 'USER_PASSWORD' )->setValue( md5( $form->getValue( 'USER_PASSWORD' ) ) );
                $form->removeElement( 'PASS_CONFIRM' );

                if ( Default_Model_DbTable_User::getInstance()->insert( $form->getValues() ) )
                {
                    My_Action_Helper::redirect( 'index' );
                }
                else
                {
                    My_Action_Helper::redirect( 'index', 'sign-up' );
                }
            }
            else
            {
                $form->populate( $this->getRequest()->getPost() );
            }
        }
        $this->view->form = $form;
    }
}

