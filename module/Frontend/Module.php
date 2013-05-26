<?php
namespace Frontend;

use Frontend\Model\Categories;
use Frontend\Model\Subcategories;
use Frontend\Model\Producers;
use Frontend\Model\Products;
use Frontend\Model\CategoriesTable;
use Frontend\Model\SubcategoriesTable;
use Frontend\Model\ProducersTable;
use Frontend\Model\ProductsTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Frontend\Model\CategoriesTable' =>  function($sm) {
                    $tableGateway = $sm->get('CategoriesTableGateway');
                    $table = new CategoriesTable($tableGateway);
                    return $table;
                },
                'Frontend\Model\SubcategoriesTable' =>  function($sm) {
                    $tableGateway = $sm->get('SubcategoriesTableGateway');
                    $table = new SubcategoriesTable($tableGateway);
                    return $table;
                },
                'Frontend\Model\ProducersTable' =>  function($sm) {
                    $tableGateway = $sm->get('ProducersTableGateway');
                    $table = new ProducersTable($tableGateway);
                    return $table;
                },
                 'Frontend\Model\ProductsTable' =>  function($sm) {
                    $tableGateway = $sm->get('ProductsTableGateway');
                    $table = new ProductsTable($tableGateway);
                    return $table;
                },
                'CategoriesTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Categories());
                    return new TableGateway('categories', $dbAdapter, null, $resultSetPrototype);
                },
                'SubcategoriesTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Subcategories());
                    return new TableGateway('subcategories', $dbAdapter, null, $resultSetPrototype);
                },
                'ProducersTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Producers());
                    return new TableGateway('producers', $dbAdapter, null, $resultSetPrototype);
                },
                'ProductsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Products());
                    return new TableGateway('products', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}