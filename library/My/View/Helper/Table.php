<?php

/**
 * Table View Helper
 * 
 * @link https://gist.github.com/ischenkodv/812481
 */
class My_View_Helper_Table extends Zend_View_Helper_Placeholder_Container_Standalone
{
    /**
     * @var string registry key
     */
    protected $_regKey = 'Application_View_Helper_Table';

    /**
     * Primary Keys
     * 
     * @var string|array
     */
    protected $_primaryKey = null;

    /**
     * @var array
     */
    protected $_primaryKeyParams = null;

    /**
     * Column titles
     * 
     * @var array
     */
    protected $_titles = array( );

    /**
     * Table caption
     *
     * @var string
     */
    protected $_caption = array( );

    /**
     * Table summary
     *
     * @var string
     */
    protected $_summary;

    /**
     * Table columns
     *
     * @var array
     */
    protected $_columns = array( );

    /**
     * Appended table content
     *
     * @var string
     */
    protected $_content;

    /**
     * Cell templates
     *
     * @var array
     */
    protected $_cellContent = array( );

    /**
     * Rows
     *
     * @var array|Zend_Paginator
     */
    protected $_rows = array( );

    /**
     * Message of the empty table
     *
     * @var string
     */
    protected $_emptyRowContent;

    /**
     * Footer content
     *
     * @var string
     */
    protected $_footer;

    /**
     * Paginator
     * 
     * @var Zend_Paginator
     */
    protected $_paginator = null;

    /**
     * Paginator limit
     * 
     * @var integer 
     */
    protected $_perPage = 10;

    /**
     * Table attributes
     * @param Zend_Db_Select $rows
     * @var array
     */
    protected $_attributes = array( 'class' => 'listar' );

    /**
     * Class Constructor
     * 
     * @param array|Zend_Db_Table_Rowset|Zend_Paginator|Zend_Db_Select|My_Db_Table_Abstract $data
     * @param array $columns
     * @return Application_View_Helper_Table 
     */
    public function table( $data = null, $columns = null )
    {
        if ( $data )
        {
            $this->setRows( $data );
        }
        if ( $columns )
        {
            $this->setColumns( $columns );
        }

        return $this;
    }

    /**
     * Set table paginator
     * 
     * @param Zend_Paginator|boolean $paginator 
     */
    public function setPaginator( $paginator )
    {
        $this->_paginator = $paginator;
        return $this;
    }

    /**
     * Set table caption
     *
     * @param string $caption
     * @return Application_View_Helper_Table
     */
    public function setCaption( $caption )
    {
        $this->_caption = $caption;
        return $this;
    }

    /**
     * Set table columns
     *
     * @return Application_View_Helper_Table
     */
    public function setColumns( array $columns )
    {
        $this->_columns = $columns;
        return $this;
    }

    /**
     * Set paginator limit
     * 
     * @param integer $limit
     * @return Application_View_Helper_Table 
     */
    public function setPerPage( $limit )
    {
        $this->_perPage = $limit;
        return $this;
    }

    /**
     * Set table summary
     * 
     * @param string $summary
     * @return Application_View_Helper_Table
     */
    public function setSummary( $summary )
    {
        $this->_summary = $summary;
        return $this;
    }

    /**
     * Set the primary key
     * 
     * @param string|array $primaryKey
     * @return Application_View_Helper_Table 
     */
    public function setPrimaryKey( $primaryKey )
    {
        $this->_primaryKey = $primaryKey;
        return $this;
    }

    /**
     * Set table titles
     * 
     * @param array $titles
     * @return Application_View_Helper_Table 
     */
    public function setTitles( $titles )
    {
        $this->_titles = $titles;
        return $this;
    }

    /**
     * Set message that will be shown when table has no rows
     *
     * @param string $str
     * @return Application_View_Helper_Table
     */
    public function setEmptyRowContent( $str )
    {
        $this->_emptyRowContent = $str;
        return $this;
    }

    /**
     * Add arbitrary text content inside table
     *
     * @param string $str
     * @return Application_View_Helper_Table
     */
    public function setContent( $str )
    {
        $this->_content .= $str;
        return $this;
    }

    /**
     * Add row
     *
     * @param array $cols
     * @return Application_View_Helper_Table
     */
    public function addRow( $cols )
    {
        if ( is_array( $cols ) )
        {
            array_push( $this->_rows, $cols );
        }
        elseif ( is_object( $cols ) && method_exists( $cols, 'toArray' ) )
        {
            array_push( $this->_rows, $cols->toArray() );
        }

        return $this;
    }

    /**
     * Set Table Rows
     *
     * @param array|Zend_Db_Table_Rowset|Zend_Paginator|Zend_Db_Select $data
     * @return Application_View_Helper_Table
     */
    public function setRows( $data )
    {
        if ( is_object( $data ) )
        {
            if ( get_parent_class( $data ) == 'My_Db_Table_Abstract' )
            {
                $this->setPrimaryKey( $data->getPrimary() );
                $data = $data->getSelect();
            }

            if ( get_class( $data ) == 'Zend_Db_Select' )
            {
                $data = My_Action_Helper::paginator( $data, Zend_Controller_Front::getInstance()->getRequest()->getParam( 'page' ) );
                $this->_paginator = $data;
            }
            elseif ( get_class( $data ) == 'Zend_Paginator' )
            {
                $this->_paginator = $data;
            }
        }

        if ( count( $data ) )
        {
            foreach ( $data as $r )
            {
                $this->addRow( $r );
            }
        }
        return $this;
    }

    /**
     * Set cell content. All row variables should be used inside brackets
     *
     * @param string $str - String that will be used as a template
     * @param string|number $position - Key of the column or its count
     * @return Application_View_Helper_Table
     */
    public function setCellContent( $str, $position )
    {
        $this->_cellContent[$position] = $str;
        return $this;
    }

    /**
     * Add cell content
     *
     * @param string $str - String that will be used as a template
     * @param string|number $position - Key of the column or its count
     * @return Application_View_Helper_Table
     */
    public function addCellContent( $str, $position = '' )
    {
        if ( isset( $this->_cellContent[$position] ) )
        {
            $this->_cellContent[$position] .= $str;
        }
        else
        {
            $this->_cellContent[$position] = $str;
        }
        return $this;
    }

    /**
     * Set footer content
     *
     * @param string $footer
     * @return Application_View_Helper_Table
     */
    public function setFooter( $footer )
    {
        $this->_footer = $footer;
        return $this;
    }

    /**
     * Set table Attributes
     * 
     * @param array $attribs
     * @return Application_View_Helper_Table
     */
    public function setAttributes( array $attribs )
    {
        foreach ( $attribs as $key => $value )
        {
            $this->_attributes[$key] = $value;
        }
        return $this;
    }

    /**
     * Set table Attribute
     * 
     * @param string $name
     * @param string $value
     * @return Application_View_Helper_Table 
     */
    public function setAttribute( $name, $value )
    {
        $this->_attributes[$name] = $value;
        return $this;
    }

    /**
     * Render table
     *
     * @return string
     */
    public function toString()
    {
        $xhtml = $this->getPrefix();

        $xhtml .= '<table ';
        if ( count( $this->_attributes ) )
        {
            foreach ( $this->_attributes as $key => $value )
            {
                $xhtml .= $key . '="' . $value . '" ';
            }
        }
        $xhtml .= (($this->_summary) ? 'summary="' . $this->_summary . '"' : '') . '>';

        if ( $this->_caption )
        {
            $xhtml .= '<caption>' . $this->_caption . '</caption>';
        }

        if ( count( $this->_columns ) )
        {
            $xhtml .= '<tr>';

            $bIsFirst = true;
            foreach ( $this->_columns as $key => $value )
            {
                $xhtml .= '<th' . (($bIsFirst) ? ' class="first_column"' : '') . '>' . $value . '</th>';
                $bIsFirst = false;
            }

            $xhtml .= '</tr>';
        }
        else
        {
            if ( !$this->_titles && isset( $this->_rows[0] ) )
            {
                $this->_titles = array_keys( $this->_rows[0] );
            }

            if ( isset( $this->_cellContent ) )
            {
                foreach ( $this->_cellContent as $key => $value )
                {
                    if ( !in_array( $key, $this->_titles ) )
                    {
                        array_push( $this->_titles, $key );
                    }
                }
            }
        }

        if ( count( $this->_titles ) )
        {
            $xhtml .= '<tr>';

            $bIsFirst = true;
            foreach ( $this->_titles as $value )
            {
                $xhtml .= '<th' . (($bIsFirst) ? ' class="first_column"' : '') . '>' . $value . '</th>';
                $bIsFirst = false;
            }

            $xhtml .= '</tr>';
        }

        if ( count( $this->_rows ) )
        {
            $columns = (count( $this->_columns ) > 0) ? array_keys( $this->_columns ) : array_keys( $this->_rows[0] );

            if ( isset( $this->_cellContent ) )
            {
                foreach ( $this->_cellContent as $key => $value )
                {
                    if ( !in_array( $key, $columns ) )
                    {
                        array_push( $columns, $key );
                    }
                }
            }

            $counter = 0;
            foreach ( $this->_rows as $r )
            {
                $counter++;
                $xhtml .= ($counter % 2) ? '<tr class="alt">' : '<tr>';

                $i = 0;
                foreach ( $columns as $key )
                {
                    $xhtml .= '<td' . (($i == 0) ? ' class="first_column"' : '') . '>';

                    if ( isset( $this->_cellContent[$key] ) )
                    {
                        $this->_currentRow = $r;    // reference current row for _replacePlaceholders callback
                        if ( function_exists( $this->_cellContent[$key] ) )
                        {
                            $xhtml .= $this->_cellContent[$key]( $this->_currentRow );
                        }
                        else
                        {
                            $xhtml .= preg_replace_callback( '/\{([a-zA-Z0-9_]+)\}/', array( $this, "_replacePlaceholders" ), stripslashes( $this->_cellContent[$key] ) );
                        }
                    }
                    elseif ( isset( $r[$key] ) )
                    {
                        $xhtml .= stripslashes( $r[$key] );
                    }
                    elseif ( isset( $r[$i] ) )
                    {
                        $xhtml .= stripslashes( $r[$i] );
                    }

                    $xhtml .= '</td>';
                    $i++;
                }
                $this->_currentRow = null;
                $xhtml .= '</tr>';
            }

            if ( $this->_content )
            {
                $xhtml .= $this->_content;
            }
        }
        else
        {
            $xhtml .= '<tr><td class="first_column" colspan="' . count( $this->_columns ) . '">' . $this->_emptyRowContent . '</td></tr>';
        }

        if ( $this->_footer )
        {
            $xhtml .= '<tfoot>';
            $xhtml .= '<tr>';
            if ( is_array( $this->_footer ) )
            {
                foreach ( $this->_footer as $key => $cell )
                {
                    $xhtml .= '<td' . (($counter == 0) ? ' class="first_column"' : '') . '>' . $cell . '</td>';
                }
            }
            else
            {
                $xhtml .= '<td class="first_column" colspan="' . count( $this->_columns ) . '">' . $this->_footer . '</td>';
            }
            $xhtml .= '</tr>';
            $xhtml .= '</tfoot>';
        }
        $xhtml .= '</table>';

        if ( $this->_paginator )
        {
            $xhtml .= $this->_paginator;
        }

        return $xhtml;
    }

    /**
     * Function used to replace string placeholders
     *
     * @param array $matches
     * @return string
     */
    private function _replacePlaceholders( $matches )
    {
        return (isset( $this->_currentRow[$matches[1]] )) ? $this->_currentRow[$matches[1]] : '';
    }

    /**
     * Shows Edit Link
     *      
     * @param string $ColumnTitle
     * @param string|array $primaryKey
     * @return \Application_View_Helper_Table 
     */
    public function showEdit( $ColumnTitle = '', $primaryKey = null )
    {
        if ( $primaryKey )
        {
            $this->setPrimaryKey( $primaryKey );
            $this->_primaryKeyParams = null;
        }

        $this->addCellContent( $this->_getContentUrl( 'edit.png', 'edit', 'Editar Registro' ), $ColumnTitle );
        return $this;
    }

    /**
     * Shows View Link
     * 
     * @param string $ColumnTitle
     * @param string|array $primaryKey     
     * @return \Application_View_Helper_Table 
     */
    public function showView( $ColumnTitle = '', $primaryKey = null )
    {
        if ( $primaryKey )
        {
            $this->setPrimaryKey( $primaryKey );
            $this->_primaryKeyParams = null;
        }

        $this->addCellContent( $this->_getContentUrl( 'view.png', 'view', 'Visualizar Registro' ), $ColumnTitle );
        return $this;
    }

    /**
     * Show Dialog Box
     * 
     * @param string $ColumnTitle
     * @param string|array $primaryKey     
     * @param string $functionName
     * @return \Application_View_Helper_Table 
     */
    public function showViewDialog( $ColumnTitle = '', $primaryKey = null, $functionName = 'view' )
    {
        if ( $primaryKey )
        {
            $this->setPrimaryKey( $primaryKey );
            $this->_primaryKeyParams = null;
        }

        $params = '';
        foreach ( $this->getPrimaryKeyParams() as $value )
        {
            $params .= '\'' . $value . '\',';
        }
        $params = rtrim( $params, ',' );

        $image = $this->view->Image( 'view.png', array( 'style' => "vertical-align: bottom; padding: 0px 5px 0px 5px; width: 18px" ) );
        $content = '<a href="#" onClick="' . $functionName . '(' . $params . ')" title="Visualizar Registro">' . $image . '</a>';

        $this->addCellContent( $content, $ColumnTitle );
        return $this;
    }

    /**
     * Shows Delete Link
     * 
     * @param string $ColumnTitle
     * @param string|array $primaryKey     
     * @return \Application_View_Helper_Table 
     */
    public function showDelete( $ColumnTitle = '', $primaryKey = null )
    {
        if ( $primaryKey )
        {
            $this->setPrimaryKey( $primaryKey );
            $this->_primaryKeyParams = null;
        }

        $this->addCellContent( $this->_getContentUrl( 'delete.png', 'delete', 'Excluir Registro' ), $ColumnTitle );
        return $this;
    }

    /**
     * Shows confirmDelete Link
     *      
     * @param string $ColumnTitle
     * @param string|array $primaryKey
     * @return \Application_View_Helper_Table 
     */
    public function showConfirmDelete( $ColumnTitle = '', $primaryKey = null, $functionName = 'confirmDelete' )
    {
        if ( $primaryKey )
        {
            $this->setPrimaryKey( $primaryKey );
            $this->_primaryKeyParams = null;
        }

        $image = $this->view->Image( 'delete.png', array( 'style' => "vertical-align: bottom; padding: 0px 5px 0px 5px; width: 18px" ) );
        $url = $this->view->url( array_merge( array( 'module' => $this->view->module, 'controller' => $this->view->controller, 'action' => 'delete' ), $this->getPrimaryKeyParams() ), null, true, false );
        $content = '<a href="#" onClick="' . $functionName . '(\'' . $url . '\')" title="Excluir Registro">' . $image . '</a>';

        $this->addCellContent( $content, $ColumnTitle );
        $this->view->DeleteDialog();

        return $this;
    }

    /**
     * Return an html link tag
     *
     * @param string $image Image Name
     * @param string $action Action Name
     * @param string $title
     * @return string 
     */
    private function _getContentUrl( $image, $action, $title )
    {
        $image = $this->view->Image( $image, array( 'style' => "vertical-align: bottom; padding: 0px 5px 0px 5px; width: 18px" ) );
        $url = $this->view->url( array_merge( array( 'module' => $this->view->module, 'controller' => $this->view->controller, 'action' => $action ), $this->getPrimaryKeyParams() ), null, true, false );
        $content = '<a href="' . $url . '" title="' . $title . '">' . $image . '</a>';

        return $content;
    }

    /**
     * @return array
     * @throws Exception 
     */
    protected function getPrimaryKeyParams()
    {
        if ( !$this->_primaryKeyParams )
        {
            if ( !$this->_primaryKey )
            {
                throw new Exception( 'Não foi definido a PK da tabela!' );
            }

            $this->_primaryKeyParams = array( );

            if ( is_array( $this->_primaryKey ) )
            {
                foreach ( $this->_primaryKey as $key => $value )
                {
                    $this->_primaryKeyParams[$key] = '{' . $value . '}';
                }
            }
            else
            {
                $this->_primaryKeyParams['id'] = '{' . $this->_primaryKey . '}';
            }
        }
        return $this->_primaryKeyParams;
    }
}