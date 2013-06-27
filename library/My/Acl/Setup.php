<?php

class My_Acl_Setup implements My_Acl_Interface
{
    /**
     * @var Zend_Acl
     */
    protected $_acl;

    /**
     * @var array Todos os controllers que a aplicacao terá
     */
    protected $_controllers;

    /**
     * Construtor da classe que cria o objeto Zend_Acl
     * e invoca as funções de configuração
     */
    public function __construct()
    {
        $this->_acl = new Zend_Acl();

        $this->setupControllers();
        $this->setupRoles();
        $this->setupResources();
        $this->setupPrivileges();
        $this->saveAcl();
    }

    /**
     * define os Controllers da aplicação
     */
    public function setupControllers()
    {
        $this->_controllers = array(
            'admin:index',
            'admin:user',
            'admin:error',
            'admin:log',
            'admin:instrument'
        );
    }

    /**
     * define os perfis de usuários que o sistema possui
     */
    public function setupRoles()
    {
        $this->_acl->addRole( new Zend_Acl_Role( 'D' ) ); //developer
        $this->_acl->addRole( new Zend_Acl_Role( 'A' ), 'D' ); //administrador
        $this->_acl->addRole( new Zend_Acl_Role( 'U' ), 'A' ); //usuario
        $this->_acl->addRole( new Zend_Acl_Role( 'G' ), 'U' ); //guest
    }

    /**
     * Adiciona os controllers as regras de acesso (ACL)
     */
    public function setupResources()
    {
        foreach ( $this->_controllers as $controller )
        {
            $this->_acl->addResource( new Zend_Acl_Resource( $controller ) );
        }
    }

    /**
     * Libera(allow) ou nega(deny) acesso conforme perfis dos usuários
     * pode ser controlado acesso para as actions, individualmente
     * 
     * U => Usuário
     * A => Administrador
     * G => Convidado
     */
    public function setupPrivileges()
    {
        $this->_acl->allow( 'D' );
        $this->_acl->deny( 'U', array( 'admin:index', 'admin:user' ) );
    }

    /**
     * Salva as Regras de acesso, pare serem utilizadas posteriormente
     */
    public function saveAcl()
    {
        Zend_Registry::set( 'acl', $this->_acl );
    }
}