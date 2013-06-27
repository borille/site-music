<?php

class Application_Model_DbTable_User extends My_Db_Table_Abstract
{

    public function init()
    {
        parent::configDbTable( NULL, 'USER', 'USER_ID' );
    }

    public function userLogin( $user, $password )
    {
        $select = $this->getSelect();
        $select->where( 'USER_NAME = ?', $user )
                ->where( 'USER_PASSWORD = ?', md5( $password ) );
        
        $result = $this->returnOne( $select );
        var_dump($result);die;
    }
}

