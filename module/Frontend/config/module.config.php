<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Frontend\Controller\Frontend' => 'Frontend\Controller\FrontendController',
        ),
    ),
       // Routes for new module
    'router' => array(
        'routes' => array(
            'categories' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/categories[/][:action][/:categ_id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'categ_id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Frontend\Controller\Frontend',
                        'action'     => 'categories',
                    ),
                ),
            ),
            'subcategories' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/subcategories[/][:action][/:subcateg_id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'subcateg_id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Frontend\Controller\Frontend',
                        'action'     => 'subcategories',
                    ),
                ),
            ),
            'producers' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/producers[/][:action][/:prod_id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'prod_id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Frontend\Controller\Frontend',
                        'action'     => 'producers',
                    ),
                ),
            ),
            'products' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/products[/][:action][/:product_id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'product_id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Frontend\Controller\Frontend',
                        'action'     => 'products',
                    ),
                ),
            ),
            'frontend' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/frontend[/][:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Frontend\Controller\Frontend',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'frontend' => __DIR__ . '/../view',
        ),
    ),
);

