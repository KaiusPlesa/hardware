<?php

$dbParams = array(
    'driver'         => 'pdo',
    'database' => 'hardware',
    'username' => 'root',
    'password' => 'aiculedssulf9',
    'hostname'         => '127.0.0.1',
    'port'             => '3306'
);

return array(

    // set the default templates for the most important pages => this is prior to ZeTheme init
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../../themes/default/view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../../themes/default/view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../../themes/default/view/error/404.phtml',
            'error/index'             => __DIR__ . '/../../themes/default/view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../../themes/default/view',
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => function ($sm) use ($dbParams) {
                $dbOptions = array(
                    'driver'    => 'pdo',
                    'dsn'       => 'mysql:dbname='.$dbParams['database'].';host='.$dbParams['hostname'],
                    'database'  => $dbParams['database'],
                    'username'  => $dbParams['username'],
                    'password'  => $dbParams['password'],
                    'hostname'  => $dbParams['hostname'],
                );
                     

                $adapter = new Zend\Db\Adapter\Adapter($dbOptions);
         
                return $adapter;
            },
        ),
    ),


);