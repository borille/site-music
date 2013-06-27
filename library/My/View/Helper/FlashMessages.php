<?php

class My_View_Helper_FlashMessages extends Zend_View_Helper_Abstract
{

    public function flashMessages()
    {
        $messages = Zend_Controller_Action_HelperBroker::getStaticHelper( 'FlashMessenger' )->getMessages();
        $output = '';

        if ( !empty( $messages ) )
        {
            $output = '<div id="message">';
            foreach ( $messages as $message )
            {
                $output .= '<p>' . $message . '</p>';
            }
            $output .= '</div>';
            $output .= "<script type='text/javascript'>jQuery('#message').slideDown(500).delay(4000).slideUp(1000)</script>";
        }

        return $output;
    }

}

?>
