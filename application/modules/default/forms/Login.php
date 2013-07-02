<?php

class Default_Form_Login extends My_Form
{

    public function init()
    {
        $user = $this->createText( 'user', $this->getView()->translate( "nome-usuario" ), array( 'style' => 'text-transform: none' ) );
        $user->setRequired( true );

        $pass = $this->createPassword( 'password', $this->getView()->translate( "senha" ) );
        $pass->setRequired( true );

        $this->addElements( array( $user, $pass, $this->createSubmit()->setAttrib( 'class', 'btn' ) ) );
    }
}

