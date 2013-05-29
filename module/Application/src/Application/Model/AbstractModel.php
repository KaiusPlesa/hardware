<?php

namespace Application\Model;

use Zend\Db\Sql\Expression;

abstract class AbstractModel extends \ZeDb\Model{

    function getCount(){

        return $this->select()->count();

    }

}