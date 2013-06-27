<?php

class My_View_Helper_SetValue extends Zend_View_Helper_Abstract
{

    /**
     * Helper para setar o valor de um campo (html)
     * @author TRB@2012
     * @param string $id Id do Campo
     * @param string $value Valor do campo
     * @param boolean $html Se é para alterar o valor do HTML do campo
     */
    public function setValue( $id, $value = null, $html = false )
    {
        if ( !$html )
        {
            echo "<script type='text/javascript'>document.getElementById('$id').value = '$value'</script>";
        }
        else
        {
            echo "<script type='text/javascript'>document.getElementById('$id').innerHTML = '$value'</script>";
        }
    }

}

?>