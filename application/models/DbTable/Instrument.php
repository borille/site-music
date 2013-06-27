<?php

class Application_Model_DbTable_Instrument extends My_Db_Table_Abstract
{

    public function init()
    {
        parent::configDbTable( NULL, 'INSTRUMENT', 'INSTRUMENT_ID' );
    }

    public function getInstruments( $language = 1 )
    {
        if ( !is_numeric( $language ) )
        {
            switch ( $language )
            {
                case 'pt_BR':
                case 'pt':
                    $language = 1;
                    break;
                case 'en_US':
                case 'en':
                    $language = 2;
                    break;
                default: $language = 1;
                    break;
            }
        }

        $select = $this->getAdapter()->select();
        $select->from( $this->getTableName(), 'INSTRUMENT_ID' )
                ->join( 'INSTRUMENT_TRANS', 'INSTRUMENT.INSTRUMENT_ID = INSTRUMENT_TRANS.INSTRUMENT_ID', 'TRANS_NAME' )
                ->where( 'INSTRUMENT.INSTRUMENT_ENABLE = ?', 'S' )
                ->where( 'INSTRUMENT_TRANS.LANGUAGE_ID = ?', $language );

        return $this->returnAll( $select );
    }
}

