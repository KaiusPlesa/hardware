<?php
namespace Frontend\Model;

use Zend\Db\TableGateway\TableGateway;

class ProducersTable
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

    public function getProducers($prod_id)
    {
        $prod_id  = (int) $prod_id;
        $rowset = $this->tableGateway->select(array('prod_id' => $prod_id));        
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $prod_id");
        }
        return $row;
    }

    public function saveCategories(Categories $categories)
    {
        $data = array(
            'categories_name'  => $categories->categories_name,
        );

        $prod_id = (int)$categories->prod_id;
        if ($prod_id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCategories($prod_id)) {
                $this->tableGateway->update($data, array('prod_id' => $prod_id));
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
         
        $prod_id = (int)$categories->prod_id;
        if ($prod_id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCategories($prod_id)) {
                $this->tableGateway->update($data, array('prod_id' => $prod_id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteCategories($prod_id)
    {
        $this->tableGateway->delete(array('prod_id' => $prod_id));
    }
}