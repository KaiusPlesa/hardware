<?php
namespace Frontend\Model;

use Zend\Db\TableGateway\TableGateway;

class ProductsTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getProducts($product_id)
    {
        $product_id  = (int) $product_id;
        $rowset = $this->tableGateway->select(array('product_id' => $product_id));        
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $product_id");
        }
        return $row;
    }
    
}