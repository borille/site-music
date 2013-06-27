<?php

class My_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Auth
     */
    protected $_auth = null;

    /**
     * @var Zend_Acl
     */
    protected $_acl = null;

    /**
     * @var array
     */
    protected $_notLoggedRoute = array(
        'controller' => 'user',
        'action' => 'login'
    );

    /**
     * @var array
     */
    protected $_forbiddenRoute = array(
        'controller' => 'error',
        'action' => 'forbidden'
    );

    public function __construct()
    {
        $this->_auth = Zend_Auth::getInstance();
        $this->_acl = Zend_Registry::get( 'acl' );
    }

    public function preDispatch( Zend_Controller_Request_Abstract $request )
    {
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $module = $request->getModuleName();

        if ( $module == 'admin' )
        {
            //verificações de segurança e redirecionamento
            if ( !$this->_auth->hasIdentity() )
            {
                //usuario ainda nao fez o login
                $controller = $this->_notLoggedRoute['controller'];
                $action = $this->_notLoggedRoute['action'];
            }
            else if ( !$this->_isAuthorized( $module, $controller, $action ) )
            {
                //usuario fez o login mas nao tem acesso ao controller em questao
                $controller = $this->_forbiddenRoute['controller'];
                $action = $this->_forbiddenRoute['action'];
            }
        }

        //redireciona para o controller/action correto
        $request->setControllerName( $controller );
        $request->setActionName( $action );
        $request->setModuleName( $module );
    }

    protected function _isAuthorized( $module, $controller, $action )
    {
        $user = $this->_auth->getIdentity();

        //verifica se o sistema possui o controller e se usuario tem acesso ao mesmo (controller/action)
        if ( !$this->_acl->has( "$module:$controller" ) || !$this->_acl->isAllowed( $user, "$module:$controller", $action ) )
        {
            return false;
        }

        return true;
    }
}