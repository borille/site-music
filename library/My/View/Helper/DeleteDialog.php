<?php

class My_View_Helper_DeleteDialog extends Zend_View_Helper_Abstract
{

    public function deleteDialog()
    {
        //cria a div
        echo '<div id="confirm-delete"></div>';

        return $this->view->dialogContainer( 'confirm-delete', null, array(
                    'modal' => true,
                    'autoOpen' => false,
                    'width' => '350px',
                    'title' => 'Deseja realmente excluir?',
                    'resizable' => false
                ) );
    }
}

?>