<?php

class Admin_Model_UserAdmin implements Zend_Acl_Role_Interface
{
    protected $id;
    protected $name;
    protected $mail;
    protected $roleId;

    public function getRoleId()
    {
        return $this->roleId;
    }

    public function setRoleId( $roleId )
    {
        $this->roleId = $roleId;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId( $id )
    {
        $this->id = $id;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName( $name )
    {
        $this->name = $name;
        return $this;
    }

    public function getMail()
    {
        return $this->mail;
    }

    public function setMail( $mail )
    {
        $this->mail = $mail;
        return $this;
    }
}

