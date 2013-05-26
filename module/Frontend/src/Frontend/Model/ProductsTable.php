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

    public function saveCategories(Categories $categories)
    {
        $data = array(
            'categories_name'  => $categories->categories_name,
        );

        $product_id = (int)$categories->product_id;
        if ($product_id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCategories($product_id)) {
                $this->tableGateway->update($data, array('product_id' => $product_id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }
        public function editCategories(Categories $Categories)
    {
        $data = array(                       
            'categories_name'  => $categories->categories_name,           
        );
         
        $product_id = (int)$categories->product_id;
        if ($product_id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCategories($product_id)) {
                $this->tableGateway->update($data, array('product_id' => $product_id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteCategories($product_id)
    {
        $this->tableGateway->delete(array('product_id' => $product_id));
    }
}