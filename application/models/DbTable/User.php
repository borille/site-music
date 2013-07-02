<?php

class Application_Model_DbTable_User extends My_Db_Table_Abstract
{

    public function init()
    {
        parent::configDbTable( NULL, 'user', 'userId' );
    }

    public function userLogin( $user, $password )
    {
        $select = $this->getSelect();
        $select->where( 'userName = ?', $user )
                ->where( 'userPassword = ?', md5( $password ) );

        $result = $this->returnOne( $select );
        var_dump( $result );
        die;
    }
}

