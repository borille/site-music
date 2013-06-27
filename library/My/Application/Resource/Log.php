<?php

/**
 * Classe para configurar o Log
 * para uso dentro do sistema
 * 
 * @category My
 * @package My_Application
 * @subpackage My_Application_Resource
 * @author TRB
 * @copyright My@2012
 */
class My_Application_Resource_Log extends Zend_Application_Resource_ResourceAbstract
{

    public function init()
    {
        $options = $this->getOptions();
		
        //Registra para uso posterior
        Zend_Registry::set( 'log', $options );		

        //nome do arquivo de log
        $nome = $options['directory'] . '/Log_' . date( 'Y_m_d' ) . '.log';

        //cria o arquivo
        $writer = new Zend_Log_Writer_Stream( $nome );
        $logger = new Zend_Log( $writer );

        //seta o formato da data e hora do log
        $logger->setTimestampFormat( 'd/m/Y H:i:s' );

        //Registra para uso posterior
        Zend_Registry::set( 'logger', $logger );

        //verifica se tem arquivo de log para limpar
        if ( !isset( $_SESSION['limpa_log'] ) )
        {
            //apaga arquivos mais velhos que 30 dias
            if ( My_Controller_Action_Helper_FileHelper::deleteFilesOlderThen( $options['life_time'], $options['directory'] ) )
            {
                //seta flag para não executar mais a limpeza de log durante a mesma SESSION
                $_SESSION['limpa_log'] = true;
            }
        }
    }

}