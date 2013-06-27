<?php

/**
 * Classe que serve se auxílio na chamada de métodos (normalmente Action Helpers). 
 * Essa classe é uma espécie de Façade(GoF)
 *  
 * @author TRB
 * @copyright My@2013
 */
abstract class My_Action_Helper
{

    /**
     * Função que simplifica a chamada do Helper FlashMessenger
     * 
     * @param string $msg 
     */
    public static function showMessage( $msg )
    {
        Zend_Controller_Action_HelperBroker::getStaticHelper( 'FlashMessenger' )->addMessage( $msg );
    }

    /**
     * Função que simplifica a chamada do Helper Redirector
     * 
     * @param string $controller Nome do Controller. Caso em branco, redireciona para a action do controller atual
     * @param string $action Nome da Action
     * @param array $params Parametros
     * @param string $module Nome do Modulo
     */
    public static function redirect( $controller = null, $action = 'index', $params = array( ), $module = null )
    {
        if ( $controller )
        {
            Zend_Controller_Action_HelperBroker::getStaticHelper( 'redirector' )->gotoSimple( $action, $controller, $module, $params );
        }
        else
        {
            Zend_Controller_Action_HelperBroker::getStaticHelper( 'redirector' )->goto( $action );
        }
    }

    /**
     * Redireciona para o local especificado e mostra uma mensagem utilizando o FlashMessenger helper
     * 
     * @param string $msg           Mensagem
     * @param string $controller    Nome do Controller
     * @param string $action        Nome da Action
     */
    public static function redirectAndShowMessage( $msg, $controller = null, $action = 'index' )
    {
        self::showMessage( $msg );
        self::redirect( $controller, $action );
    }

    /**
     * Função que desabilitar o conteudo e/ou o layout da View
     *      
     * @param boolean $content  FALSE - Para não desabilitar o conteudo da view
     * @param boolean $layout   FALSE - Para não desabilitar o layout da view
     * @param boolean $encode   TRUE - Para habilitar o encode 8859-1
     */
    public static function disableView( $content = true, $layout = true, $encode = false )
    {
        if ( $encode )
        {
            header( "Content-Type: text/html;  charset=ISO-8859-1", true );
        }

        if ( $content )
        {
            Zend_Controller_Action_HelperBroker::getStaticHelper( 'viewRenderer' )->setNoRender( TRUE );
        }

        if ( $layout )
        {
            Zend_Controller_Action_HelperBroker::getStaticHelper( 'layout' )->disableLayout();
        }
    }

    /**
     * Retorna o usuário autenticado no sistema
     * 
     * @param boolean $toObject Se TRUE retorna o objeto ao invés de um array
     * @return array|object 
     */
    public static function getUser( $toObject = false )
    {
        if ( $toObject )
        {
            return Zend_Auth::getInstance()->getIdentity();
        }
        else
        {
            return Zend_Auth::getInstance()->getIdentity()->toArray();
        }
    }

    /**
     * Retornar se uma solicitação é em Ajax (TRUE) ou não (FALSE)
     * 
     * @return boolean
     */
    public static function isAjax()
    {
        return (!empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' );
    }

    /**
     *
     * @param string $msg
     * @param integer $tipo
     * 
     * @example Tipo das mensagens:
     * 0 => Emergency: system is unusable
     * 1 => Alert: action must be taken immediately
     * 2 => Critical: critical conditions
     * 3 => Error: error conditions
     * 4 => Warning: warning conditions
     * 5 => Notice: normal but significant condition
     * 6 => Informational: informational messages
     * 7 => Debug: debug messages
     */
    public static function logger( $msg, $tipo = Zend_Log::INFO )
    {
        if ( Zend_Registry::isRegistered( 'logger' ) )
        {
            $logger = Zend_Registry::get( 'logger' );
            $logger->log( $msg, $tipo );
        }
    }

    /**
     * Limpa todos os arquivos em cache do sistema
     * 
     * @param boolean $showMessage Mensagem que será exibida após limpeza do cache
     */
    public static function clearCache( $message = 'Arquivos temporários excluidos com sucesso!' )
    {
        if ( Zend_Registry::isRegistered( 'cache' ) )
        {
            Zend_Registry::get( 'cache' )->clean( Zend_Cache::CLEANING_MODE_ALL );

            if ( $message )
            {
                My_Action_Helper::showMessage( $message );
            }
        }
    }

    /**
     * Salva o cookie pelo tempo especificado
     * 
     * @param string    $name   Nome do Cookie
     * @param mixed     $value  Valor para o Cookie
     * @param integer   $days   Tempo de vida (em dias) do Cookie
     */
    public static function saveCookie( $name, $value, $days = 30 )
    {
        if ( $name )
        {
            setcookie( $name, $value, time() + (86400 * $days) );
        }
    }

    /**
     * Limpa o cookie
     * 
     * @param string    $name   Nome do Cookie
     */
    public static function clearCookie( $name )
    {
        if ( $name )
        {
            setcookie( $name, null, time() - 3600 );
        }
    }

    /**
     * Transforma o valor de Bytes para outra unidade de leitura mais fácil
     * @param number $valor in Bytes   
     * @param number $cont controll size class (1 => KB, 2=>MB, ect..) Optional
     * @return mixed Human Readable Size 
     */
    public static function resizeMem( $valor, $cont = null )
    {
        $unidade = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'YB', 'ZB' );
        if ( $valor > 1024 )
        {
            $cont++;
            $valor = $valor / 1024;
            return self::resizeMem( $valor, $cont );
        }
        else
        {
            return number_format( $valor, 2 ) . " " . $unidade[$cont];
        }
    }

    /**
     * Retorna um objeto de paginação
     * @param Zend_Db_Select $data Dados
     * @param type $current
     * @param type $limit
     * @return Zend_Paginator
     */
    public static function paginator( Zend_Db_Select $data, $current = '1', $limit = null )
    {
        $paginator = null;

        if ( $data instanceof Zend_Db_Select )
        {
            $paginator = Zend_Paginator::factory( $data );
            $paginator->setCurrentPageNumber( $current );

            //verifica se foi passado limit diferente do padrão
            if ( $limit )
            {
                $paginator->setItemCountPerPage( $limit );
            }
        }
        return $paginator;
    }

    /**
     * Função para alterar o arquivo de layout
     * @param string $$layoutName Nome do arquivo de layout (sem o .phtml)
     */
    public static function setLayout( $layoutName = null )
    {
        if ( $layoutName )
        {
            Zend_Controller_Action_HelperBroker::getStaticHelper( 'layout' )->setLayout( $layoutName );
        }
    }
}