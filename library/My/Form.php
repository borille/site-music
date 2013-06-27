<?php

class My_Form extends Zend_Form
{
    protected $_decoratorOptions = array( 'tag' => 'table', 'class' => 'zend_form' );

    /**
     * Decorator padrão
     * @var array
     */
    public $elementDecorators = array(
        'ViewHelper',
        array( 'Description', array( 'escape' => false, 'tag' => 'span' ) ),
        //'Errors',
        array( array( 'data' => 'HtmlTag' ), array( 'tag' => 'td', 'class' => 'element' ) ),
        array( 'Label', array( 'tag' => 'th' ) ),
        array( array( 'row' => 'HtmlTag' ), array( 'tag' => 'tr' ) )
    );

    /**
     * @var array
     */
    public $fileDecorators = array(
        'File',
        array( 'Description', array( 'escape' => false, 'tag' => 'span' ) ),
        //'Errors',
        array( array( 'data' => 'HtmlTag' ), array( 'tag' => 'td', 'class' => 'element' ) ),
        array( 'Label', array( 'tag' => 'td' ) ),
        array( array( 'row' => 'HtmlTag' ), array( 'tag' => 'tr' ) )
    );

    /**
     *
     * @var type 
     */
    public $hiddenDecorators = array(
        'ViewHelper'
    );

    /**
     *
     * @var type 
     */
    public $elementDecoratorTd = array(
        'ViewHelper',
        array( 'Description', array( 'escape' => false, 'tag' => 'span' ) ),
        //'Errors',
        array( array( 'data' => 'HtmlTag' ), array( 'tag' => 'td', 'class' => 'element' ) ),
        array( 'Label', array( 'tag' => 'th' ) )
    );

    /**
     * Decorator para os buttons e submits
     * @var array
     */
    public $buttonDecorators = array(
        'ViewHelper',
        'Description',
        array( array( 'data' => 'HtmlTag' ), array( 'tag' => 'td', 'colspan' => '2', 'align' => 'center' ) ),
        array( array( 'row' => 'HtmlTag' ), array( 'tag' => 'tr' ) )
    );

//--------------------------------------------------------------------------
    public function __construct( $options = null )
    {
        $this->setMethod( 'post' );
        parent::__construct( $options );
    }

//--------------------------------------------------------------------------
    /**
     * Adiciona um input do tipo submit ao Form
     * 
     * @param string    $name       Nome do Campo
     * @param string    $label      Label para o campo
     * @param integer   $colspan    Colspan para a tag td
     * @return \My_Form
     */
    public function addSubmit( $name = null, $label = 'OK', $colspan = 2 )
    {
        $this->addElement( $this->createSubmit( $name, $label, $colspan ) );
        return $this;
    }

//--------------------------------------------------------------------------
    /**
     * Retorna um input do tipo submit
     * 
     * @param string $name  Nome do Campo
     * @param string $label Label para o campo
     * @param integer   $colspan    Colspan para a tag td
     * @return \My_Form
     */
    public function createSubmit( $name = null, $label = 'OK', $colspan = 2 )
    {
        $element = new Zend_Form_Element_Submit( $name ? $name : 'submit' );
        $element->setLabel( $label )
                ->setDecorators( $this->buttonDecorators );

        if ( $colspan )
        {
            $this->setColspan( $element, $colspan );
        }

        return $element;
    }

    /**
     * Retorna o um elemento input do tipo Text
     * @param string    $name       Nome do Componente
     * @param string    $label      Label do Componente
     * @param array     $options    Array com as opções
     * @return \Zend_Form_Element_Text 
     */
    public function createHidden( $name, array $options = null )
    {
        $element = new Zend_Form_Element_Hidden( $name );
        $element->setDecorators( $this->hiddenDecorators );

        if ( $options )
        {
            $element->setOptions( $options );
        }

        return $element;
    }

    /**
     * Retorna o um elemento input do tipo Text
     * @param string    $name       Nome do Componente
     * @param string    $label      Label do Componente
     * @param array     $options    Array com as opções
     * @return \Zend_Form_Element_Text 
     */
    public function createText( $name, $label = null, array $options = null )
    {
        $element = new Zend_Form_Element_Text( $name );
        $element->setLabel( $label )
                ->setDecorators( $this->elementDecorators );

        if ( $options )
        {
            $element->setOptions( $options );
        }

        return $element;
    }

    /**
     * Retorna o um elemento input do tipo TextArea
     * @param string    $name       Nome do Componente
     * @param string    $label      Label do Componente
     * @param array     $options    Array com as opções
     * @return \Zend_Form_Element_TextArea
     */
    public function createTextArea( $name, $label = null, array $options = null )
    {
        $element = new Zend_Form_Element_Textarea( $name );
        $element->setLabel( $label )
                ->setDecorators( $this->elementDecorators );

        if ( $options )
        {
            $element->setOptions( $options );
        }

        return $element;
    }

    /**
     * Retorna o um elemento input do tipo Password
     * @param string    $name       Nome do Componente
     * @param string    $label      Label do Componente
     * @param array     $options    Array com as opções
     * @return \Zend_Form_Element_Text 
     */
    public function createPassword( $name, $label = null, array $options = null )
    {
        $element = new Zend_Form_Element_Password( $name );
        $element->setLabel( $label )
                ->setDecorators( $this->elementDecorators );

        if ( $options )
        {
            $element->setOptions( $options );
        }

        return $element;
    }

//--------------------------------------------------------------------------
    /**
     * Retorna um elemento do tipo select preenchido com os valores passados
     * @param string $name
     * @param string $valueCol
     * @param string $labelCol
     * @param array $data
     * @return \Zend_Form_Element_Select 
     */
    public function createSelect( $name, $valueCol, $labelCol, array $data )
    {
        $select = new Zend_Form_Element_Select( $name );

        foreach ( $data as $value )
        {
            $select->addMultiOption( $value[$valueCol], $value[$labelCol] );
        }

        $select->setDecorators( $this->elementDecorators );
        
        return $select;
    }

    /**
     * Retorna um campo input com mascara e calendário
     * @param string $name      Nome do campo
     * @param string $format    Formato da Data
     * @param string $mask      Máscara para a Data
     * @param boolean $calendar Exibir o calendário
     * @return \Zend_Form_Element_Text 
     */
    public function createDate( $name, $label, $format = 'dd/MM/yyyy', $mask = '99/99/9999', $calendar = true )
    {
        if ( $calendar )
        {
            $src = INCLUDE_PATH . '/img/calendar.png';
            $calendar = "<img style='vertical-align: bottom; cursor: pointer;' width='25px' src='$src' onClick=\"displayCalendar(document.getElementById('$name'),'dd/mm/yyyy',this)\" target='_self' title='Abrir Calendário'/>";
        }

        if ( $mask )
        {
            $mask = "<script type='text/javascript'>jQuery(function($){ $(\"#$name\").mask(\"$mask\");});</script>";
        }

        $element = new Zend_Form_Element_Text( $name );
        $element->setDescription( $calendar . $mask )
                ->setLabel( $label )
                ->setAttrib( 'size', 10 )
                ->addValidator( new Zend_Validate_Date( array( 'locale' => 'pt_BR', 'format' => $format ) ) )
                ->setDecorators( $this->elementDecorators );

        return $element;
    }

//--------------------------------------------------------------------------
    /**
     * Retorna o um elemento input do tipo File
     * @param string    $name       Nome do Componente
     * @param string    $label      Label do Componente
     * @param array     $options    Array com as opções
     * @return \Zend_Form_Element_File
     */
    public function createFile( $name, $label = null, array $options = null )
    {
        $element = new Zend_Form_Element_File( $name );
        $element->setLabel( $label )
                ->setDestination( 'tmp' )
                ->setDecorators( $this->fileDecorators );

        if ( $options )
        {
            $element->setOptions( $options );
        }

        return $element;
    }

//--------------------------------------------------------------------------
    public function loadDefaultDecorators()
    {
        $this->setDecorators( array(
            'FormErrors',
            'FormElements',
            array( 'HtmlTag', $this->_decoratorOptions ), 'Form'
        ) );
    }

//--------------------------------------------------------------------------
    /**
     * Adicionao a opção colspan a tag TD do elemento passado
     * @param   Zend_Form_Element $element
     * @param   integer|string    $value
     * @param   string            $decorator
     * @return  \My_Form
     */
    public function setColspan( $element, $value = 2, $decorator = null )
    {
        $this->setElementDecoratorOption( $element, $decorator, 'colspan', $value );
        return $this;
    }

    /**
     * Adiciona a opção no decorator do elemento passado
     * @param string    $element
     * @param string    $decorator
     * @param string    $option
     * @param mixed     $value
     * @return \My_Form 
     */
    public function setElementDecoratorOption( $element, $decorator, $option, $value )
    {
        $element->getDecorator( $decorator ? $decorator : 'data'  )->setOption( $option, $value );
        return $this;
    }

    /**
     * Somente desenha a tag de abertura no elemento passado
     * @param string $element
     * @param string $decorator
     * @return \My_Form 
     */
    public function onlyOpenTag( $element, $decorator = 'row' )
    {
        $this->setElementDecoratorOption( $element, $decorator, 'openOnly', true );
        return $this;
    }

    /**
     * Somente desenha a tag de fechamento no elemento passado
     * @param string $element
     * @param string $decorator
     * @return \My_Form 
     */
    public function onlyCloseTag( $element, $decorator = 'row' )
    {
        $this->setElementDecoratorOption( $element, $decorator, 'closeOnly', true );
        return $this;
    }

    /**
     * Adiciona opções ao Decorator Padrão
     * @param array $option 
     */
    public function setDecoratorOptions( array $option = null )
    {
        if ( $option )
        {
            foreach ( $option as $key => $value )
            {
                $this->_decoratorOptions[$key] = $value;
            }
        }
        return $this;
    }

    /**
     *
     * @param Zend_Form_Element $element
     * @param string            $mask 
     */
    public function setMask( $element, $mask )
    {
        if ( $mask )
        {
            $name = $element->getName();
            $element->setDescription( "<script type='text/javascript'>jQuery(function($){ $(\"#$name\").mask(\"$mask\");});</script>" );
        }
        return $this;
    }
}