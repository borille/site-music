<?php

class Default_Model_User implements Zend_Acl_Role_Interface
{
    protected $id;
    protected $name;
    protected $mail;
    protected $roleId;
    protected $language;
    protected $instrument;

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

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage( $language )
    {
        $this->language = $language;
        return $this;
    }

    public function getInstrument()
    {
        return $this->instrument;
    }

    public function setInstrument( $instrument )
    {
        $this->instrument = $instrument;
        return $this;
    }
}

