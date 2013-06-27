<?php

class Admin_Form_Instrument extends My_Form
{

    public function init()
    {
        $id = $this->createHidden( 'INSTRUMENT_ID' );

        $name = $this->createText( 'INSTRUMENT_NAME', 'Nome', array( 'style' => 'text-transform: none' ) );
        $name->setRequired( true )
                ->addValidator( new Zend_Validate_StringLength( array( 'max' => 30 ) ) );

        $data = array(
            array( 'VALUE' => 'S', 'LABEL' => 'Sim' ),
            array( 'VALUE' => 'N', 'LABEL' => 'Não' )
        );

        $enable = $this->createSelect( 'INSTRUMENT_ENABLE', 'VALUE', 'LABEL', $data );
        $enable->setLabel( 'Ativo' );

        $this->addElements( array( $id, $name, $enable, $this->createSubmit() ) );
    }
}