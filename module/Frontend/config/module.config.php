<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Frontend\Controller\Categories' => 'Frontend\Controller\CategoriesController',
            //'Frontend\Controller\Frontend' => 'Frontend\Controller\SubcategoriesController',
            //'Frontend\Controller\Frontend' => 'Frontend\Controller\ProducersController',
            //'Frontend\Controller\Frontend' => 'Frontend\Controller\ProductsController',
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
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'frontend' => __DIR__ . '/../view',
        ),
    ),
);

