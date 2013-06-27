<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    //--------------------------------------------------------------------------
    //metodo para AutoLoad das classes criadas
    protected function _initAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader( array(
                    'basePath' => APPLICATION_PATH,
                    'namespace' => ''
                        ) );
        return $autoloader;
    }

    //--------------------------------------------------------------------------
    //iniciar a configuração do ACL
    protected function _initAcl()
    {
        $aclSetup = new My_Acl_Setup();
        return $aclSetup;
    }
}

