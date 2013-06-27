<?php

class My_Controller_Action_Helper_FileHelper extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * listPathFiles( $path )
     * 
     * Retorna o nome dos arquivos de uma pasta
     * 
     * @param string $path Nome da Pasta
     * @return array Nome dos Arquivos
     */
    function listPathFiles( $path )
    {
        $files = array();

        try
        {
            //verifica se o caminho passado é um diretorio
            if ( is_dir( $path ) )
            {
                if ( ($handle = opendir( $path ) ) )
                {
                    //percorre os arquivos do diretorio
                    while ( ($file = readdir( $handle )) !== false )
                    {
                        if ( $file != '.' && $file != '..' )
                        {
                            $files[] = $file;
                        }
                    }
                    closedir( $handle );
                }
            }
            
            //ordena dos mais novos para os mais velhos
            arsort( $files, SORT_STRING );
            
            return $files;
        }
        catch ( Exception $e )
        {
            throw new Exception( 'Erro ao listar arquivos da pasta ' . $path );
        }
    }

    //--------------------------------------------------------------------------
    /**
     * openFile( $fileName )
     * 
     * Retorna um array com as linhas do arquivo informado
     *
     * @param string $fileName Nome do arquivo a ser aberto
     * @return array Linhas do arquivo
     */
    public function openFile( $fileName )
    {
        //verifica se o arquivo existe
        if ( file_exists( $fileName ) )
        {
            try
            {
                return file( $fileName );
            }
            catch ( Exception $e )
            {
                throw new Exception( 'Não foi possível abrir o arquivo ' . $fileName );
            }
        }
        else
        {
            throw new Exception( 'O arquivo ' . $fileName . ' não existe' );
        }
    }

    //--------------------------------------------------------------------------
    /**
     * deleteFile( $fileName )
     * 
     * Apaga um arquivo
     * 
     * @param string $fileName
     * @return bool Retorna TRUE em caso de sucesso
     */
    public function deleteFile( $fileName )
    {
        //verifica se o arquivo existe
        if ( file_exists( $fileName ) )
        {
            try
            {
                return unlink( $fileName );
            }
            catch ( Exception $e )
            {
                throw new Exception( 'Não foi possível apagar o arquivo ' . $fileName );
            }
        }
        else
        {
            throw new Exception( 'O arquivo ' . $fileName . ' não existe' );
        }
    }

    //--------------------------------------------------------------------------
    public function getFilesCreationTime( $directory )
    {
        if ( ( $handle = opendir( $directory ) ) )
        {
            while ( false !== ($file = readdir( $handle )) )
            {
                $files[] = $file;
            }

            foreach ( $files as $val )
            {
                $fileName = $directory . '/' . $val;

                if ( is_file( $fileName ) )
                {
                    $file_date[$val] = filectime( $fileName );
                }
            }
        }
        closedir( $handle );

        return $file_date;
    }

    //--------------------------------------------------------------------------
    /**
     * getOldestFile
     * 
     * Retorna o nome do arquivo mais velho da pasta
     * 
     * @param string $directory
     * @return array nome e data do arquivo
     */
    public function getOldestFile( $directory )
    {
        $file_date = self::getFilesCreationTime( $directory );

        asort( $file_date, SORT_NUMERIC );
        reset( $file_date );
        $oldest = key( $file_date );

        $result = array(
            'nome' => $oldest,
            'data' => $file_date[$oldest]
        );

        return $result;
    }

    //--------------------------------------------------------------------------
    /**
     * getNewestFile
     * 
     * Retorna o nome do arquivo mais novo da pasta
     * 
     * @param string $directory
     * @return array nome e data do arquivo
     */
    public function getNewestFile( $directory )
    {
        $file_date = self::getFilesCreationTime( $directory );

        arsort( $file_date, SORT_NUMERIC );
        reset( $file_date );
        $newest = key( $file_date );

        $result = array(
            'nome' => $newest,
            'data' => $file_date[$newest]
        );

        return $result;
    }

    //--------------------------------------------------------------------------
    /**
     * getFilesBetweenDates
     * 
     * Retorna uma lista com os nomes dos arquivos que foram criados entre as datas especificadas
     * 
     * @param Zend_Date $data_ini data de inicio da pesquisa
     * @param Zend_Date $data_fim data de fim da pesquisa
     * @param string $path Nome do diretorio a procurar
     * @return array Nome dos arquivos entre as datas
     */
    public function getFilesBetweenDates( $data_ini, $data_fim, $path )
    {
        //cria os objetos
        $data_ini = new Zend_Date( $data_ini );
        $data_fim = new Zend_Date( $data_fim );
        $data_hoje = new Zend_Date( Date( 'd/m/Y' ) );

        //guarda a data de todos os arquivos da pasta
        $arquivos = self::getFilesCreationTime( $path );

        $result = array();

        if ( $arquivos )
        {
            foreach ( $arquivos as $key => $value )
            {
                $data_arquivo = new Zend_Date( $value );

                //compara data do arquivo com datas inicial e final
                $compara_ini = $data_arquivo->compareDate( $data_ini );
                $compara_fim = $data_arquivo->compareDate( $data_fim );

                //verifica se esta entre as datas inicial e final
                if ( $compara_ini >= 0 && $compara_fim <= 0 )
                {
                    //verifica se não está querendo apagar o arquivo do mesmo dia
                    if ( $data_arquivo->compareDate( $data_hoje ) != 0 )
                    {
                        //salva nome do arquivo
                        $result[] = $key;
                    }
                }
            }
        }
        return $result;
    }
    //--------------------------------------------------------------------------
    /**
     * getFilesOutsideDates
     * 
     * Retorna uma lista com os nomes dos arquivos que foram criados fora das datas especificadas
     * 
     * @param Zend_Date $data_ini data de inicio da pesquisa
     * @param Zend_Date $data_fim data de fim da pesquisa
     * @param string $path Nome do diretorio a procurar
     * @return array Nome dos arquivos
     */
    public function getFilesOutsideDates( $data_ini, $data_fim, $path )
    {
        //cria os objetos
        $data_ini = new Zend_Date( $data_ini );
        $data_fim = new Zend_Date( $data_fim );
        $data_hoje = new Zend_Date( Date( 'd/m/Y' ) );

        //guarda a data de todos os arquivos da pasta
        $arquivos = self::getFilesCreationTime( $path );

        $result = array();		
		
        if ( $arquivos )
        {
            foreach ( $arquivos as $key => $value )
            {
                $data_arquivo = new Zend_Date( $value );

                //compara data do arquivo com datas inicial e final
                $compara_ini = $data_arquivo->compareDate( $data_ini );
                $compara_fim = $data_arquivo->compareDate( $data_fim );

                //verifica se não esta entre as datas inicial e final
                if (!( $compara_ini >= 0 && $compara_fim <= 0 ))
                {
                    //verifica se não está querendo apagar o arquivo do mesmo dia
                    if ( $data_arquivo->compareDate( $data_hoje ) != 0 )
                    {
                        //salva nome do arquivo
                        $result[] = $key;
                    }
                }
            }
        }
        return $result;
    }
	//--------------------------------------------------------------------------
    /**
     * deleteFilesOlderThen
     * 
     * Apaga os arquivos mais velhos que a quantidade de dias informado
     * 
     * @param int $days número de dias
     * @param string $path caminho da pasta dos arquivos     
     * @return bool sucesso ou fracasso
     */
	public function deleteFilesOlderThen( $days = 7, $path = 'log' )
        {
            $data_fim = new Zend_Date();
            $data_ini = new Zend_Date();

            //deixa $days dias no log
            $data_ini = $data_ini->subDay( $days )->toString( 'dd/MM/Y' );
            $data_fim = $data_fim->toString( 'dd/MM/Y' );

            //busca arquivos que tem mais de $days dias de criação            
            $arquivos = self::getFilesOutsideDates( $data_ini, $data_fim, 'log' );

            //se tiver algum arquivos
            if ( $arquivos )
            {
                //percorre arquivos
                foreach ( $arquivos as $arquivo )
                {
                    $filename = $path . '/' . $arquivo;

                    try
                    {
                        //deleta o arquivo
                        self::deleteFile( $filename );

                        //salva exclusão no log
                        if ( Zend_Registry::isRegistered( 'logger' ) )
                        {
                            Zend_Registry::get( 'logger' )->notice( 'Excluído arquivo de log: ' . $filename );
                        }
                    }
                    catch ( Exception $e )
                    {
                        //Salva erro no Log
                        if ( Zend_Registry::isRegistered( 'logger' ) )
                        {
                            Zend_Registry::get( 'logger' )->err( $e->getMessage() );
							return false;
                        }
                    }
                }
            }			
			return true;
        }
}

?>
