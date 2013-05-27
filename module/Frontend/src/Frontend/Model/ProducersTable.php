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
    
}