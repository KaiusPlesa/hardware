<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Frontend\Controller\Categories' => 'Frontend\Controller\CategoriesController',
            'Frontend\Controller\Subcategories' => 'Frontend\Controller\SubcategoriesController',
            'Frontend\Controller\Producers' => 'Frontend\Controller\ProducersController',
            'Frontend\Controller\Products' => 'Frontend\Controller\ProductsController',
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
                        'controller' => 'Frontend\Controller\Categories',
                        'action'     => 'index',
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
                        'controller' => 'Frontend\Controller\Subcategories',
                        'action'     => 'index',
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
                        'controller' => 'Frontend\Controller\Producers',
                        'action'     => 'index',
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
                        'controller' => 'Frontend\Controller\Products',
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

