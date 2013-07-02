<?php

// Define path to application directory
defined( 'APPLICATION_PATH' )
        || define( 'APPLICATION_PATH', realpath( dirname( __FILE__ ) . '/../application' ) );

// Define application environment
defined( 'APPLICATION_ENV' )
        || define( 'APPLICATION_ENV', (getenv( 'APPLICATION_ENV' ) ? getenv( 'APPLICATION_ENV' ) : 'production' ) );

// Define application environment
defined( 'LIBRARY_PATH' )
        || define( 'LIBRARY_PATH', APPLICATION_PATH . '/../library' );

// Define include path
defined( 'PUBLIC_PATH' )
        || define( 'PUBLIC_PATH', APPLICATION_PATH . '/../public' );

// Define include path
defined( 'INCLUDE_PATH' )
        || define( 'INCLUDE_PATH', 'http://' . $_SERVER['SERVER_NAME'] . '/borille/site-music/public' );

// Ensure library/ is on include_path
set_include_path( implode( PATH_SEPARATOR, array(
            realpath( APPLICATION_PATH . '/../library' ),
            get_include_path(),
        ) ) );

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
                APPLICATION_ENV,
                APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
        ->run();