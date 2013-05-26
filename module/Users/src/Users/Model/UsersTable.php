<?php
namespace Users\Model;

use Zend\Db\TableGateway\TableGateway;

class UsersTable
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

    public function getUsers($user_id)
    {
        $user_id  = (int) $user_id;
        $rowset = $this->tableGateway->select(array('user_id' => $user_id));        
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $user_id");
        }
        return $row;
    }

    public function saveUsers(Users $users)
    {
        $data = array(
            'username'  => $users->username,
            'password'  => md5($users->password),
            'email'  => $users->email,
            'user_type'  => 2,
            'last_login'  => time(),
        );

        $user_id = (int)$users->user_id;
        if ($user_id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUsers($user_id)) {
                $this->tableGateway->update($data, array('user_id' => $user_id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }
        public function editUsers(Users $users)
    {
        $data = array(                       
            'username'  => $users->username,           
            'email'  => $users->email,
        );
         
        $user_id = (int)$users->user_id;
        if ($user_id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUsers($user_id)) {
                $this->tableGateway->update($data, array('user_id' => $user_id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteUsers($user_id)
    {
        $this->tableGateway->delete(array('user_id' => $user_id));
    }
}