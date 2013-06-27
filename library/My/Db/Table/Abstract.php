<?php

/**
 * Class for SQL table interface. Extends Zend_Db_Table_Abstract
 * 
 * @category    My
 * @package     My_Db
 * @subpackage  Table
 * @name        My_Db_Table_Abstract
 * @copyright   Copyright (c) 2012 - My
 * @author      TRB
 * @version     1.0
 */
abstract class My_Db_Table_Abstract extends Zend_Db_Table_Abstract
{
    /**
     * Guarda o total de linhas da query
     * @var integer 
     */
    protected $_total = 0;

    /**
     * Guarda o valor da última chave primária inserida no banco
     * @var mixed 
     */
    protected $_lastInsertedId = null;

    //--------------------------------------------------------------------------
    /**
     * __destruct() - Destrutor da classe
     * 
     * Fecha a conexão com o Bando de Dados
     */
    public function __destruct()
    {
        if ( $this->_db->isConnected() )
        {
            $this->_db->closeConnection();
        }
    }

    //--------------------------------------------------------------------------
    /**
     * Definir a configuração do DbTable
     * 
     * @param string        $schema     Nome do Schema
     * @param string        $name       Nome da Tabela
     * @param string|array  $primary    Nome(s) da(s) chave(s) primária(s)     * 
     * @param string        $sequence   Nome da sequence
     * @param array         $cols       Array com o nome das colunas da tabela
     */
    public function configDbTable( $schema = null, $name = null, $primary = null, $sequence = null, array $cols = null )
    {
        $this->_schema = $schema;
        $this->_name = $name;
        $this->_primary = $primary;
        $this->_sequence = $sequence;
        $this->_cols = $cols;
    }

    /**
     * Retorna uma instancia da classe filha de My_Db_Table_Abstract
     * 
     * @return My_Db_Table_Abstract 
     */
    public final static function getInstance()
    {
        return new static();
    }

    //--------------------------------------------------------------------------
    /**
     * Retorna a(s) chave(s) primária(s) da tabela
     * 
     * @return string
     */
    public function getPrimary()
    {
        return $this->_primary;
    }

    //--------------------------------------------------------------------------
    /**
     * Retornar o nome da tabela
     * 
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    //--------------------------------------------------------------------------
    /**
     * Retorna o nome da Sequence da tabela
     * 
     * @return string
     */
    public function getSequence()
    {
        return $this->_sequence;
    }

    //--------------------------------------------------------------------------
    /**
     * Retorna o nome da tabela do banco já com o schema (se existir)
     * 
     * @return string
     */
    public function getTableName()
    {
        return ($this->_schema ? $this->_schema . '.' : '') . $this->_name;
    }

    //--------------------------------------------------------------------------
    /**
     * Retorna o total de linhas da última query que utilizou o setTotal
     * 
     * @return integer 
     */
    public function getTotal()
    {
        return $this->_total;
    }

    //--------------------------------------------------------------------------
    /**
     * Retorna o valor da chave primária da última operação de insert no banco
     * 
     * @return mixed 
     */
    public function getLastInsertedId()
    {
        return $this->_lastInsertedId;
    }

    //--------------------------------------------------------------------------
    /**
     * Salva o total de linhas da query passada como parâmetro
     * 
     * @param string|Zend_Db_Table_Select $sub_select
     */
    public function setTotal( $sub_select = null )
    {
        $select = $this->_db->select();
        $select->from( $sub_select, array( 'COUNT(*) AS TOTAL' ) );

        try
        {
            $this->_total = (int) $this->_db->fetchOne( $select );
        }
        catch ( Zend_Db_Exception $e )
        {
            My_Action_Helper::logger( $e->getMessage(), Zend_Log::ERR );
        }
    }

    //--------------------------------------------------------------------------
    /**
     * Retorna as colunas especificadas de todas as linhas (dentro da clausula where)
     * 
     * @param string|array                      $cols   Colunas desejadas
     * @param string|array                      $where  OPTIONAL An SQL WHERE clause or Zend_Db_Table_Select object.
     * @param string                            $order  OPTIONAL An SQL ORDER BY order.
     * @param int                               $count  OPTIONAL An SQL LIMIT count.
     * @param int                               $offset OPTIONAL An SQL LIMIT offset.
     * @return array 
     */
    public function listAll( $cols = '*', $where = null, $order = null, $count = null, $offset = null )
    {
        $select = $this->_db->select();
        $select->from( $this->getTableName(), $cols ? $cols : '*'  );

        if ( $where )
        {
            $this->addWhere( $select, $where );
        }

        if ( $order )
        {
            if ( is_array( $order ) )
            {
                foreach ( $order as $value )
                {
                    $select->order( $value );
                }
            }
            else
            {
                $select->order( $order );
            }
        }

        if ( $count )
        {
            $select->count( $count );
        }

        if ( $offset )
        {
            $select->offset( $offset );
        }

        try
        {
            return $this->_db->fetchAll( $select );
        }
        catch ( Zend_Db_Exception $e )
        {
            My_Action_Helper::logger( $e->getMessage(), Zend_Log::ERR );
            return array( );
        }
    }

    //--------------------------------------------------------------------------
    /**
     * Retorna as colunas especificadas da linha (dentro da clausula where)
     * 
     * @param string|array                      $cols   Colunas desejadas
     * @param string|array|Zend_Db_Table_Select $where  OPTIONAL An SQL WHERE clause or Zend_Db_Table_Select object.
     * @return array 
     */
    public function listOne( $cols = '*', $where = null )
    {
        $select = $this->_db->select();
        $select->from( $this->getTableName(), $cols ? $cols : '*'  );

        if ( $where )
        {
            $this->addWhere( $select, $where );
        }

        try
        {
            return $this->_db->fetchRow( $select );
        }
        catch ( Zend_Db_Exception $e )
        {
            My_Action_Helper::logger( $e->getMessage(), Zend_Log::ERR );
            return array( );
        }
    }

    //--------------------------------------------------------------------------
    /**
     * Retorna array com os dados da linha em que a chave primária for igual ao $pkValue
     * 
     * @param mixed         $pkValue    Valor ou Array de valores para chave primária
     * @param string|array  $cols       Colunas que seão retornadas
     * @return array
     */
    public function getId( $pkValue = null, $cols = '*' )
    {
        $select = $this->_db->select();
        $select->from( $this->getTableName(), $cols ? $cols : '*'  );

        foreach ( $this->montaWherePK( $pkValue ) as $cond )
        {
            $select->where( $cond );
        }

        try
        {
            return $this->_db->fetchRow( $select );
        }
        catch ( Zend_Db_Exception $e )
        {
            My_Action_Helper::logger( $e->getMessage(), Zend_Log::ERR );
            return array( );
        }
    }

    //--------------------------------------------------------------------------
    /**
     * Tenta executar o select passado, retornando todos os resultados
     * Caso ocorra erro, salva o erro ocorrido no arquivo de log (se estiver habilitado)
     * 
     * @param string $select
     * @return array
     */
    public function returnAll( $select )
    {
        try
        {
            return $this->_db->fetchAll( $select );
        }
        catch ( Zend_Db_Exception $e )
        {
            My_Action_Helper::logger( $e->getMessage(), Zend_Log::ERR );
            return array( );
        }
    }

    //--------------------------------------------------------------------------
    /**
     * Tenta executar o select passado, retornando um resultado
     * Caso ocorra erro, salva o erro ocorrido no arquivo de log (se estiver habilitado)
     * 
     * @param   string $select
     * @return  array
     */
    public function returnOne( $select )
    {
        try
        {
            return $this->_db->fetchRow( $select );
        }
        catch ( Zend_Db_Exception $e )
        {
            My_Action_Helper::logger( $e->getMessage(), Zend_Log::ERR );
            return array( );
        }
    }

    //--------------------------------------------------------------------------
    /**
     * getLastId()
     * Retorna o valor da última PK da tabela
     *
     * @return integer
     */
    public function getLastId()
    {
        if ( is_array( $this->_primary ) )
        {
            throw new Zend_db_Exception( 'Não foi possível determinar o último valor da PK. A tabela possui mais de uma PK' );
        }

        $select = $this->_db->select();
        $select->from( $this->getTableName(), new Zend_Db_Expr( 'MAX(' . $this->_primary . ')' ) );

        try
        {
            return (int) $this->_db->fetchOne( $select );
        }
        catch ( Zend_Db_Exception $e )
        {
            My_Action_Helper::logger( $e->getMessage(), Zend_Log::ERR );
            return false;
        }
    }

    //--------------------------------------------------------------------------
    /**
     * getNextId()
     * Retorna o próximo valor da chave primaria da tabela
     *
     * @return inteiro
     */
    public function getNextId()
    {
        //guarda o valor da última Identity da tabela
        $lastId = $this->getLastId();

        if ( $lastId >= 0 )
        {
            return $lastId + 1;
        }
        else
        {
            return false;
        }
    }

    //--------------------------------------------------------------------------
    /**
     * Apaga uma linha da tabela
     * 
     * @param   mixed               $pkValue        Valor ou array com valores da chave primária da linha que será apagada
     * @param   boolean             $transaction    Se é para utilizar transação ou não (default: TRUE)
     * @return  boolean                             Sucesso ou Falha
     * @throws  Zend_Db_Exception 
     */
    public function delete( $pkValue, $transaction = true )
    {
        //monta clausula where
        $where = $this->montaWherePK( $pkValue );

        try
        {
            //inicia transação no banco
            if ( $transaction )
            {
                $this->_db->beginTransaction();
            }

            parent::delete( $where );

            //inicia transação no banco
            if ( $transaction )
            {
                $this->_db->commit();
            }
        }
        catch ( Zend_Db_Exception $e )
        {
            //inicia transação no banco
            if ( $transaction )
            {
                $this->_db->rollBack();
            }

            My_Action_Helper::logger( $e->getMessage(), Zend_Log::ERR );

            if ( !$transaction )
            {
                throw new Exception( $e->getMessage() );
            }

            return false;
        }
        return true;
    }

    //--------------------------------------------------------------------------
    /**
     * Insere uma linha no banco
     * 
     * @param   array|My_Model_Abstract   $data           Dados a serem inseridos
     * @param   boolean                     $transaction    Se é para utilizar transação ou não (default: TRUE)
     * @param   boolean                     $profiler       Define se é para fazer log dos parâmetros da operação de insert
     * @return  mixed                                       Valor da chave primária inserida
     */
    public function insert( $data, $transaction = true, $profiler = true )
    {
        //se o objeto é filho da classe My_Model_Abstract, converte para um array
        if ( get_parent_class( $data ) == 'My_Model_Abstract' )
        {
            $data = $data->toArray();
        }

        //se foi definido as colunas da tabela, monta um array com esses campos como indíce do array que será inserido
        if ( isset( $this->_cols ) )
        {
            $tmpData = array( );

            foreach ( $this->_cols as $col )
            {
                if ( array_key_exists( $col, $data ) )
                {
                    $tmpData[$col] = $data[$col];
                }
            }
            $data = $tmpData;
        }

        //se o valor da chave primaria for em branco, seta como nulo
        if ( $this->getPrimary() )
        {
            if ( !is_array( $this->getPrimary() ) )
            {
                if ( isset( $data[$this->getPrimary()] ) )
                {
                    if ( $data[$this->getPrimary()] == '' )
                    {
                        $data[$this->getPrimary()] = null;
                    }
                }
                else
                {
                    $data[$this->getPrimary()] = null;
                }
            }
        }

        try
        {
            // fazer log das transações sql
            $this->_db->getProfiler()->setEnabled( $profiler );

            //inicia transação no banco
            if ( $transaction )
            {
                $this->_db->beginTransaction();
            }

            //insere no banco
            $this->_lastInsertedId = parent::insert( $data );

            //commit
            if ( $transaction )
            {
                $this->_db->commit();
            }
        }
        catch ( Zend_Db_Exception $e )
        {
            //rollback
            if ( $transaction )
            {
                $this->_db->rollBack();
            }

            //recolhe os parametros SQL
            if ( $profiler )
            {
                $params = " params:" . implode( '; ', $this->_db->getProfiler()->getLastQueryProfile()->getQueryParams() );
                $this->_db->getProfiler()->setEnabled( false );
            }

            My_Action_Helper::logger( $e->getMessage() . $params, Zend_Log::ERR );

            if ( !$transaction )
            {
                throw new Exception( $e->getMessage() );
            }

            return false;
        }

        return $this->_lastInsertedId;
    }

    //--------------------------------------------------------------------------
    /**
     * Salva(insere ou atualiza) o dados no banco
     * 
     * @param   array|My_Model_Abstract   $data           Dados a serem inseridos
     * @param   mixed|array                 $pkValue        Valor ou array com valores da chave primária da linha que será atualizada
     * @param   boolean                     $transaction    Se é para utilizar transação ou não (default: TRUE)
     * @param   boolean                     $profiler       Define se é para fazer log dos parâmetros da operação de insert
     * @return  boolean                                     Sucesso ou Falha     
     */
    public function save( $data, $pkValue = null, $transaction = true, $profiler = true )
    {
        //se o objeto é filho da classe My_Model_Abstract, converte para um array
        if ( get_parent_class( $data ) == 'My_Model_Abstract' )
        {
            $data = $data->toArray();
        }

        //se foi definido as colunas da tabela, monta um array verificando se os parametros passados pertencem a tabela
        if ( isset( $this->_cols ) )
        {
            $tmpData = array( );

            foreach ( $data as $key => $value )
            {
                if ( in_array( $key, $this->_cols ) )
                {
                    $tmpData[$key] = $value;
                }
            }
            $data = $tmpData;
        }

        try
        {
            // fazer log das transações sql
            $this->_db->getProfiler()->setEnabled( $profiler );

            //inicia transação no banco
            if ( $transaction )
            {
                $this->_db->beginTransaction();
            }

            //verifica se a tabela tem PK
            if ( $this->getPrimary() )
            {
                $insert = false;

                //verifica se todos os campos de chave primaria estao preenchidos
                if ( is_array( $this->getPrimary() ) )
                {
                    foreach ( $this->getPrimary() as $value )
                    {
                        if ( $data[$value] == null || $data[$value] == '' )
                        {
                            $insert = true;
                        }
                    }
                }
                else
                {
                    if ( isset( $data[$this->getPrimary()] ) )
                    {
                        if ( $data[$this->getPrimary()] == null || $data[$this->getPrimary()] == '' )
                        {
                            $insert = true;
                        }
                    }
                    else
                    {
                        $insert = true;
                    }
                }

                //se os campos de chave primaria estão preenchidos
                if ( $insert )
                {
                    //insere no banco
                    $this->_lastInsertedId = parent::insert( $data );
                }
                else
                {
                    //verifica se foi passado o valor da PK que será atualizada
                    if ( !$pkValue )
                    {
                        $pkValue = array( );

                        foreach ( (array) $this->getPrimary() as $value )
                        {
                            $pkValue[] = $data[$value];
                        }
                    }

                    //monta clausula where
                    $where = $this->montaWherePK( $pkValue );

                    //atualiza o banco
                    parent::update( $data, $where );
                }
            }
            else
            {
                //insere no banco
                $this->_lastInsertedId = parent::insert( $data );
            }

            //commit
            if ( $transaction )
            {
                $this->_db->commit();
            }
        }
        catch ( Zend_Db_Exception $e )
        {
            //rollback
            if ( $transaction )
            {
                $this->_db->rollBack();
            }

            //recolhe os parametros SQL
            if ( $profiler )
            {
                $params = " params:" . implode( '; ', $this->_db->getProfiler()->getLastQueryProfile()->getQueryParams() );
                $this->_db->getProfiler()->setEnabled( false );
            }

            My_Action_Helper::logger( $e->getMessage() . $params, Zend_Log::ERR );

            if ( !$transaction )
            {
                throw new Exception( $e->getMessage() );
            }

            return false;
        }
        return true;
    }

    //--------------------------------------------------------------------------
    /**
     * Atualiza uma linha no banco
     * 
     * @param   array|My_Model_Abstract   $data           Dados a serem inseridos
     * @param   mixed|array                 $pkValue        Valor ou array com valores da chave primária da linha que será atualizada
     * @param   boolean                     $transaction    Se é para utilizar transação ou não (default: TRUE)
     * @param   boolean                     $profiler       Define se é para fazer log dos parâmetros da operação de insert
     * @return  boolean                                     Sucesso ou Falha
     */
    public function update( $data, $pkValue = null, $transaction = true, $profiler = true )
    {
        //se o objeto é filho da classe My_Model_Abstract, converte para um array
        if ( get_parent_class( $data ) == 'My_Model_Abstract' )
        {
            $data = $data->toArray();
        }

        //se foi definido as colunas da tabela, monta um array verificando se os parametros passados pertencem a tabela
        if ( isset( $this->_cols ) )
        {
            $tmpData = array( );

            foreach ( $data as $key => $value )
            {
                if ( in_array( $key, $this->_cols ) )
                {
                    $tmpData[$key] = $value;
                }
            }
            $data = $tmpData;
        }

        //verifica se foi passado o valor da PK que será atualizada
        if ( !$pkValue )
        {
            $pkValue = array( );

            foreach ( (array) $this->_primary as $value )
            {
                $pkValue[] = $data[$value];
            }
        }

        //monta clausula where
        $where = $this->montaWherePK( $pkValue );

        try
        {
            // fazer log das transações sql
            $this->_db->getProfiler()->setEnabled( $profiler );

            //inicia transação no banco
            if ( $transaction )
            {
                $this->_db->beginTransaction();
            }

            //atualiza o banco
            parent::update( $data, $where );

            //commit
            if ( $transaction )
            {
                $this->_db->commit();
            }
        }
        catch ( Zend_Db_Exception $e )
        {
            //rollback
            if ( $transaction )
            {
                $this->_db->rollBack();
            }

            //recolhe os parametros SQL
            if ( $profiler )
            {
                $params = " params:" . implode( '; ', $this->_db->getProfiler()->getLastQueryProfile()->getQueryParams() );
                $this->_db->getProfiler()->setEnabled( false );
            }

            My_Action_Helper::logger( $e->getMessage() . $params, Zend_Log::ERR );

            if ( !$transaction )
            {
                throw new Exception( $e->getMessage() );
            }

            return false;
        }
        return true;
    }

    //--------------------------------------------------------------------------
    /**
     * Monta as clausulas where conforme o valor passado no parametro id
     * 
     * @param   mixed|array         $pkValue    Valor ou array com valores da chave primária da linha
     * @return  string|array
     * @throws  Zend_Db_Exception 
     */
    private function montaWherePK( $pkValue )
    {
        $where = array( );
        $primary = (array) $this->_primary;
        $pkValue = (array) $pkValue;

        if ( sizeof( $primary ) != sizeof( $pkValue ) )
        {
            throw new Zend_Db_Exception( 'Parâmetros para chave primária inválidos' );
        }
        else
        {
            $array = array_combine( $primary, $pkValue );
            foreach ( $array as $key => $value )
            {
                $where[] = $this->_db->quoteInto( "$key = ?", $value );
            }
        }

        return $where;
    }

    //--------------------------------------------------------------------------
    /**
     * Adiciona clausula where ao Zend_Db_Select passado
     * 
     * @param Zend_Db_Select    $select
     * @param string|array      $where
     * @return Zend_Db_Select
     */
    private function addWhere( Zend_Db_Select $select, $where )
    {
        $where = (array) $where;

        foreach ( $where as $key => $value )
        {
            if ( is_int( $key ) )
            {
                $select->where( $value );
            }
            else
            {
                $select->where( $key, $value );
            }
        }
        return $select;
    }

    //--------------------------------------------------------------------------
    /**
     * Retorna o resultado da consulta do cache (caso ainda não exista no cache, salva o resultado nele)
     * 
     * @param string    $cacheName          Nome que será utilizado para ler/salvar o cache
     * @param string    $callbackFunction   Nome da função que será chamada caso ainda não exista essa informação no cache
     * @param mixed     $parameters         Parâmetros da função de Callback (Opcional)
     * @return array 
     */
    public function returnFromCache( $cacheName, $callbackFunction, $parameters = null )
    {
        //verifica se o cache está habilitado
        if ( Zend_Registry::isRegistered( 'cache' ) )
        {
            $cache = Zend_Registry::get( 'cache' );

            //verifica se existe o resultado em cache
            if ( !$result = $cache->load( ( $cacheName ) ) )
            {
                //caso não exista, executa a função e salva o resultado no cache
                $result = call_user_func( array( $this, $callbackFunction ), $parameters );
                $cache->save( $result, $cacheName );
            }
        }
        else
        {
            $result = call_user_func( array( $this, $callbackFunction ), $parameters );
        }
        return $result;
    }

    //--------------------------------------------------------------------------
    /**
     * Retorna um objeto do tipo Zend_Db_Select com a seguinte query: SELECT * FROM TABLE_NAME
     * @param string|array      $cols   Colunas desejadas
     * @return Zend_Db_Select
     */
    public function getSelect( $cols = '*' )
    {
        return $this->getAdapter()->select()->from( $this->getTableName(), $cols ? $cols : '*'  );
    }
}