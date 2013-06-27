<?php

class Admin_LogController extends Zend_Controller_Action
{
    protected $_logDirectory;

    //--------------------------------------------------------------------------
    public function init()
    {
        //verifica se está habilitado o log no sistema
        if ( Zend_Registry::isRegistered( 'log' ) )
        {
            $log = Zend_Registry::get( 'log' );
            $this->_logDirectory = $log['directory'];
        }
        else
        {
            Urbs_Action_Helper::showMessage( 'Esse aplicativo não utiliza log!' );
            Urbs_Action_Helper::redirect( 'index' );
        }
    }

    //--------------------------------------------------------------------------
    public function indexAction()
    {
        //lista os arquivos do diretorio "public/log"
        $this->view->arquivos = $this->_helper->FileHelper->listPathFiles( $this->_logDirectory );

        $data_inicio = $this->_helper->FileHelper->getOldestFile( $this->_logDirectory );
        $data_fim = $this->_helper->FileHelper->getNewestFile( $this->_logDirectory );

        $this->view->data_inicio = date( 'd/m/Y', $data_inicio['data'] );
        $this->view->data_fim = date( 'd/m/Y', $data_fim['data'] );
    }

    //--------------------------------------------------------------------------
    public function viewAction()
    {
        $filtro = $this->_getParam( 'filtro', null );
        $id = $this->_getParam( 'id', null );

        $filename = $this->_logDirectory . '/' . $id;

        try
        {
            //abre o arquivo            
            $linhas = $this->_helper->FileHelper->openFile( $filename );
        }
        catch ( Exception $e )
        {
            //Salva erro no Log
            Urbs_Action_Helper::logger( $e->getMessage(), Zend_Log::ERR );

            $this->_helper->FlashMessenger( 'Não foi possível abrir o arquivo ' . $id );
            $this->_helper->Redirector( 'index' );
        }

        //verifica se foi passado filtro
        if ( $filtro != null )
        {
            $arquivo_filtro = array( );

            //percorre as linhas do arquivo
            foreach ( $linhas as $linha )
            {
                //usa expressão regular para pegar o que está entre parenteses na linha
                preg_match( '#\((.*?)\)#', $linha, $match );

                //verifica se a linha se aplica ao filtro
                if ( $match[1] == $filtro )
                {
                    $arquivo_filtro[] = $linha;
                }
            }
            $this->view->lines = $arquivo_filtro;
        }
        else
        {
            $this->view->lines = $linhas;
        }

        $this->view->title = 'Conteúdo do arquivo: ' . $id;
        $this->view->file = $id;
        $this->view->filtro = $filtro;
    }

    //--------------------------------------------------------------------------
    public function deleteAction()
    {
        //verifica se comando veio por get
        if ( $this->getRequest()->isGet() )
        {
            $id = $this->_getParam( 'id', null );

            //verifica se é pra apagar um arquivo únido
            if ( $id )
            {
                $filename = $this->_logDirectory . '/' . $id;

                try
                {
                    //deleta o arquivo
                    $this->view->lines = $this->_helper->FileHelper->deleteFile( $filename );

                    //salva no log
                    Urbs_Action_Helper::logger( 'Excluído arquivo de log: ' . $filename, Zend_Log::NOTICE );

                    $this->_helper->FlashMessenger( 'Excluído com sucesso!' );
                }
                catch ( Exception $e )
                {
                    //Salva erro no Log
                    Urbs_Action_Helper::logger( $e->getMessage(), Zend_Log::ERR );

                    $this->_helper->FlashMessenger( 'Não foi possível apagar o arquivo ' . $id );
                }
            }
        }
        elseif ( $this->getRequest()->isPost() )
        {
            //pega os parametro passados
            $data_inicio = $this->_getParam( 'data_inicio', null );
            $data_fim = $this->_getParam( 'data_fim', null );

            //guarda os arquivos que foram criados entre essas datas
            $arquivos = $this->_helper->FileHelper->getFilesBetweenDates( $data_inicio, $data_fim, $this->_logDirectory );

            //se tiver algum arquivos
            if ( $arquivos )
            {
                //percorre arquivos
                foreach ( $arquivos as $arquivo )
                {
                    $filename = $this->_logDirectory . '/' . $arquivo;

                    try
                    {
                        //deleta o arquivo
                        $this->view->lines = $this->_helper->FileHelper->deleteFile( $filename );

                        //salva exclusão no log                        
                        Urbs_Action_Helper::logger( 'Excluído arquivo de log: ' . $filename, Zend_Log::NOTICE );
                    }
                    catch ( Exception $e )
                    {
                        //Salva erro no Log
                        Urbs_Action_Helper::logger( $e->getMessage(), Zend_Log::ERR );
                    }
                }
                $this->_helper->FlashMessenger( 'Excluído com sucesso!' );
            }
            else
            {
                $this->_helper->FlashMessenger( 'Não localizou arquivos para apagar!' );
            }
        }
        $this->_helper->Redirector( 'index' );
    }
}

