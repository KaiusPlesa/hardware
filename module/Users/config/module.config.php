<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Users\Controller\Users' => 'Users\Controller\UsersController',
        ),
    ),
       // Routes for new module
    'router' => array(
        'routes' => array(
            'users' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/users[/][:action][/:user_id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'user_id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Users\Controller\Users',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'users' => __DIR__ . '/../view',
        ),
    ),
);

