<?php

class My_View_Helper_Image extends Zend_View_Helper_Abstract
{

    /**
     * Retornar um elemento html do tipo img
     * @param string $name Nome do arquivo a ser carregado
     * @param string|array $options Opções do img
     * @param string $src Caminho para a imagem
     * @return string
     */
    public function image( $name, $options = null, $src = null )
    {
        if ( !$src )
        {
            $src = INCLUDE_PATH . '/img';
        }

        $src = rtrim( $src, '/' ) . '/' . $name;        
		$temp = '';
		
        if ( $options )
        {
            if ( is_array( $options ) )
            {
                foreach ( $options as $key => $value )
                {
                    $temp .= ' ' . $key . '="' . $value . '"';
                }
            }
            else
            {
                $temp = $options;
            }
        }

        $output = '<img src="' . $src . '"' . $temp . '/>';
        return $output;
    }
}

?>