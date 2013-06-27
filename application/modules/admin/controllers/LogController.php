<?php

class Admin_LogController extends Zend_Controller_Action
{
    protected $_logDirectory;

    //--------------------------------------------------------------------------
    public function init()
    {
        //verifica se est� habilitado o log no sistema
        if ( Zend_Registry::isRegistered( 'log' ) )
        {
            $log = Zend_Registry::get( 'log' );
            $this->_logDirectory = $log['directory'];
        }
        else
        {
            Urbs_Action_Helper::showMessage( 'Esse aplicativo n�o utiliza log!' );
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

            $this->_helper->FlashMessenger( 'N�o foi poss�vel abrir o arquivo ' . $id );
            $this->_helper->Redirector( 'index' );
        }

        //verifica se foi passado filtro
        if ( $filtro != null )
        {
            $arquivo_filtro = array( );

            //percorre as linhas do arquivo
            foreach ( $linhas as $linha )
            {
                //usa express�o regular para pegar o que est� entre parenteses na linha
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

        $this->view->title = 'Conte�do do arquivo: ' . $id;
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

            //verifica se � pra apagar um arquivo �nido
            if ( $id )
            {
                $filename = $this->_logDirectory . '/' . $id;

                try
                {
                    //deleta o arquivo
                    $this->view->lines = $this->_helper->FileHelper->deleteFile( $filename );

                    //salva no log
                    Urbs_Action_Helper::logger( 'Exclu�do arquivo de log: ' . $filename, Zend_Log::NOTICE );

                    $this->_helper->FlashMessenger( 'Exclu�do com sucesso!' );
                }
                catch ( Exception $e )
                {
                    //Salva erro no Log
                    Urbs_Action_Helper::logger( $e->getMessage(), Zend_Log::ERR );

                    $this->_helper->FlashMessenger( 'N�o foi poss�vel apagar o arquivo ' . $id );
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

                        //salva exclus�o no log                        
                        Urbs_Action_Helper::logger( 'Exclu�do arquivo de log: ' . $filename, Zend_Log::NOTICE );
                    }
                    catch ( Exception $e )
                    {
                        //Salva erro no Log
                        Urbs_Action_Helper::logger( $e->getMessage(), Zend_Log::ERR );
                    }
                }
                $this->_helper->FlashMessenger( 'Exclu�do com sucesso!' );
            }
            else
            {
                $this->_helper->FlashMessenger( 'N�o localizou arquivos para apagar!' );
            }
        }
        $this->_helper->Redirector( 'index' );
    }
}

