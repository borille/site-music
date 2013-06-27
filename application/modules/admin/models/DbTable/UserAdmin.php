<?php

class Admin_Model_DbTable_UserAdmin extends My_Db_Table_Abstract
{

    public function init()
    {
        parent::configDbTable( NULL, 'USER_ADMIN', 'USER_ADMIN_ID' );
    }

    public function login( $user, $password )
    {
        $select = $this->getSelect();
        $select->where( 'USER_ADMIN_NAME = ?', $user )
                ->where( 'USER_ADMIN_PASSWORD = ?', md5( $password ) );

        $result = $this->returnOne( $select );

        if ( is_array( $result ) )
        {
            $user = new Admin_Model_UserAdmin();
            $user->setId( $result['USER_ADMIN_ID'] )
                    ->setName( $result['USER_ADMIN_NAME'] )
                    ->setMail( $result['USER_ADMIN_MAIL'] )
                    ->setRoleId( $result['USER_ADMIN_ROLE'] );

            Zend_Auth::getInstance()->getStorage()->write( $user );
            return true;
        }
        return false;
    }
}

