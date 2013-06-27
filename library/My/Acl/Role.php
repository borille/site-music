<?php

class My_Acl_Role implements My_Acl_Role_Interface
{

    protected $_login;
    protected $_roleId;

    public function getLogin()
    {
        return $this->_login;
    }

    public function setLogin( $login )
    {
        $this->_login = $login;
        return $this;
    }

    public function setRoleId( $roleId )
    {
        $this->_roleId = $roleId;
        return $this;
    }

    public function getRoleId()
    {
        return $this->_roleId;
    }

}