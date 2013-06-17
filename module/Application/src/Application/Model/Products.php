<?php

namespace Application\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\ResultSet\ResultSet;

class Products extends AbstractModel{
    
    protected $tableName = 'products';
    
    public function getProductDetails($id,$table){
        
        $sql = new Sql($this->adapter);       
        $select = $sql->select();

        $select->quantifier('DISTINCT');
        $select->from(array("a" => $this->tableName));             
        $select->join(array('b' => $table),'b.product_id = a.id');
        $select->where(array('a.id='.$id));

        $statement = $sql->prepareStatementForSqlObject($select);
        $countries = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($countries);

        //echo $select->getSqlString();
        //p($resultSet->toArray());
        //exit;
        return $resultSet->toArray();
        
    }
    

}