<?php
namespace Frontend\Model;

use Zend\Db\TableGateway\TableGateway;

class CategoriesTable
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

    public function getCategories($categ_id)
    {
        $categ_id  = (int) $categ_id;
        $rowset = $this->tableGateway->select(array('categ_id' => $categ_id));        
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $categ_id");
        }
        return $row;
    }

    public function saveCategories(Categories $categories)
    {
        $data = array(
            'categories_name'  => $categories->categories_name,
        );

        $categ_id = (int)$categories->categ_id;
        if ($categ_id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCategories($categ_id)) {
                $this->tableGateway->update($data, array('categ_id' => $categ_id));
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
         
        $categ_id = (int)$categories->categ_id;
        if ($categ_id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCategories($categ_id)) {
                $this->tableGateway->update($data, array('categ_id' => $categ_id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteCategories($categ_id)
    {
        $this->tableGateway->delete(array('categ_id' => $categ_id));
    }
}