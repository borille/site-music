<?php

class My_Controller_Plugin_Internationalization extends Zend_Controller_Plugin_Abstract
{

    public function preDispatch( Zend_Controller_Request_Abstract $request )
    {
        $locale = Zend_Registry::get( 'Zend_Locale' );
        $translate = Zend_Registry::get( 'Zend_Translate' );

        if ( !Zend_Auth::getInstance()->hasIdentity() )
        {
            switch ( $locale->getLanguage() )
            {
                case 'en':
                case 'en_US':
                    $locale->setLocale( 'en' );
                    $translate->setLocale( "en" );
                    break;
                case 'pt':
                case 'pt_BR':
                    $locale->setLocale( 'pt' );
                    $translate->setLocale( "pt" );
                    break;
            }
        }
        else
        {
            switch ( Zend_Auth::getInstance()->getIdentity()->getLanguage() )
            {
                case 1:
                    $locale->setLocale( 'pt' );
                    $translate->setLocale( "pt" );
                    break;
                case 2:
                    $locale->setLocale( 'en' );
                    $translate->setLocale( "en" );
                    break;
                default:
                    $locale->setLocale( 'pt' );
                    $translate->setLocale( "pt" );
                    break;
            }
        }

        if ( !$translate->isAvailable( $locale->getLanguage() ) )
        {
            $locale->setLocale( "pt" );
            $translate->setLocale( "pt" );
        }

        Zend_Registry::set( 'Zend_Locale', $locale );
        Zend_Registry::set( 'Zend_Translate', $translate );
    }
}