<?php

class Application_Model_DbTable_Language extends My_Db_Table_Abstract
{

    public function init()
    {
        parent::configDbTable( NULL, 'LANGUAGE', 'LANGUAGE_ID' );
    }
}

