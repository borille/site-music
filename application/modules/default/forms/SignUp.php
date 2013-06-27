<?php

class Default_Form_SignUp extends My_Form
{

    public function init()
    {
        $id = $this->createHidden( 'USER_ID' );

        $name = $this->createText( 'USER_NAME', $this->getView()->translate( "nome-usuario" ), array( 'style' => 'text-transform: none' ) );
        $name->setRequired( true )
                ->addValidator( new Zend_Validate_StringLength( array( 'max' => 30 ) ) );

        $mail = $this->createText( 'USER_MAIL', $this->getView()->translate( "email" ), array( 'style' => 'text-transform: none' ) );
        $mail->setRequired( true )
                ->addValidator( new Zend_Validate_EmailAddress() );

        $pass = $this->createPassword( 'USER_PASSWORD', $this->getView()->translate( "senha" ) );
        $pass->setRequired( true )
                ->addValidator( new Zend_Validate_StringLength( array( 'min' => 6, 'max' => 20 ) ) );

        $passConfirm = $this->createPassword( 'PASS_CONFIRM', $this->getView()->translate( "confirma-senha" ) );
        $passConfirm->setRequired( true )
                ->addValidator( new Zend_Validate_StringLength( array( 'min' => 6, 'max' => 20 ) ) )
                ->addValidator( new Zend_Validate_Identical( array( 'token' => 'USER_PASSWORD' ) ) );

        $data = Application_Model_DbTable_Language::getInstance()->listAll();
        $language = $this->createSelect( 'LANGUAGE_ID', 'LANGUAGE_ID', 'LANGUAGE_NAME', $data );
        $language->setLabel( $this->getView()->translate( "linguagem" ) );

        $data = Application_Model_DbTable_Instrument::getInstance()->getInstruments( Zend_Registry::get( 'Zend_Locale' ) );
        $instrument = $this->createSelect( 'INSTRUMENT_ID', 'INSTRUMENT_ID', 'TRANS_NAME', $data );
        $instrument->setLabel( $this->getView()->translate( "instrumento" ) );

        $this->addElements( array( $id, $name, $mail, $language, $instrument, $pass, $passConfirm, $this->createSubmit() ) );
    }
}

