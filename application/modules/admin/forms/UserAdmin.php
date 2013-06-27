<?php

class Admin_Form_UserAdmin extends My_Form
{

    public function init()
    {
        $id = $this->createHidden( 'USER_ADMIN_ID' );

        $name = $this->createText( 'USER_ADMIN_NAME', 'Username', array( 'style' => 'text-transform: none' ) );
        $name->setRequired( true )
                ->addValidator( new Zend_Validate_StringLength( array( 'max' => 30 ) ) );

        $mail = $this->createText( 'USER_ADMIN_MAIL', 'Mail', array( 'style' => 'text-transform: none' ) );
        $mail->setRequired( true )
                ->addValidator( new Zend_Validate_EmailAddress() );

        $pass = $this->createPassword( 'USER_ADMIN_PASSWORD', 'Password' );
        $pass->setRequired( true )
                ->addValidator( new Zend_Validate_StringLength( array( 'min' => 6, 'max' => 20 ) ) );

        $passConfirm = $this->createPassword( 'PASS_CONFIRM', 'Confirm Password' );
        $passConfirm->setRequired( true )
                ->addValidator( new Zend_Validate_StringLength( array( 'min' => 6, 'max' => 20 ) ) )
                ->addValidator( new Zend_Validate_Identical( array( 'token' => 'USER_ADMIN_PASSWORD' ) ) );

        $this->addElements( array( $id, $name, $mail, $pass, $passConfirm, $this->createSubmit() ) );
    }
}

