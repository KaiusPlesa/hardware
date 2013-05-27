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

}