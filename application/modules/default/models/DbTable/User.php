<?php

class Default_Model_DbTable_User extends My_Db_Table_Abstract
{

    public function init()
    {
        parent::configDbTable( NULL, 'USER', 'USER_ID' );
    }

    public function login( $user, $password )
    {
        $select = $this->getSelect();
        $select->where( 'USER_NAME = ?', $user )
                ->where( 'USER_PASSWORD = ?', md5( $password ) );

        $result = $this->returnOne( $select );

        if ( is_array( $result ) )
        {
            $user = new Default_Model_User();
            $user->setId( $result['USER_ID'] )
                    ->setName( $result['USER_NAME'] )
                    ->setMail( $result['USER_MAIL'] )
                    ->setLanguage( $result['LANGUAGE_ID'] )
                    ->setInstrument( $result['INSTRUMENT_ID'] )
                    ->setRoleId( 'U' );

            Zend_Auth::getInstance()->getStorage()->write( $user );
            return true;
        }
        return false;
    }
}

