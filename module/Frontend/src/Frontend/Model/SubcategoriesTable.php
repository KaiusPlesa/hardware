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
   
}