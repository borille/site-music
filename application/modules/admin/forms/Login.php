<?php

class Admin_Form_Login extends My_Form
{

    public function init()
    {
        $user = $this->createText( 'user', 'Username', array( 'style' => 'text-transform: none' ) );
        $user->setRequired( true );

        $pass = $this->createPassword( 'password', 'Password' );
        $pass->setRequired( true );

        $this->addElements( array( $user, $pass, $this->createSubmit() ) );
    }
}

