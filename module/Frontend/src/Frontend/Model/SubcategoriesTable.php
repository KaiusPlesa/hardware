<?php
namespace Frontend\Model;

use Zend\Db\TableGateway\TableGateway;

class SubcategoriesTable
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
      public function getSubcategById($subcateg_id)
    {
        $subcateg_id  = (int) $subcateg_id;
        $rowset = $this->tableGateway->select(array('subcateg_id' => $subcateg_id));        
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $subcateg_id");
        }
        return $row;
    }

    public function getSubcategories($subcateg_id)
    {
        $subcateg_id  = (int) $subcateg_id;
        $rowset = $this->tableGateway->select(array('subcateg_id' => $subcateg_id));        
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $subcateg_id");
        }
        return $row;
    }

    public function saveSubcategories(Subategories $subcategories)
    {
        $data = array(
            'subcategories_name'  => $subcategories->subcategorie_name,
        );

        $subcateg_id = (int)$categories->subcateg_id;
        if ($subcateg_id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCategories($subcateg_id)) {
                $this->tableGateway->update($data, array('subcateg_id' => $subcateg_id));
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
         
        $subcateg_id = (int)$categories->subcateg_id;
        if ($subcateg_id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCategories($subcateg_id)) {
                $this->tableGateway->update($data, array('subcateg_id' => $subcateg_id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteCategories($subcateg_id)
    {
        $this->tableGateway->delete(array('subcateg_id' => $subcateg_id));
    }
}