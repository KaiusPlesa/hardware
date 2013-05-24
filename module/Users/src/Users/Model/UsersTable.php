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

    public function getUsers($id_user)
    {
        $id_user  = (int) $id_user;
        $rowset = $this->tableGateway->select(array('id_user' => $id_user));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id_user");
        }
        return $row;
    }

    public function saveUsers(Users $users)
    {
        $data = array(
            'name' => $users->name,
            'username'  => $users->username,
            'password'  => md5($users->password),
            'email'  => $users->email,
            'user_type'  => 2,
            'last_login'  => time(),
        );

        $id_user = (int)$users->id;
        if ($id_user == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUsers($id_user)) {
                $this->tableGateway->update($data, array('id_user' => $id_user));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }
        public function editUsers(Users $users)
    {
        $data = array(           
            'name' => $users->name,
            'username'  => $users->username,           
            'email'  => $users->email,
        );
         
        $id_user = (int)$users->id_user;
        if ($id_user == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUsers($id_user)) {
                $this->tableGateway->update($data, array('id_user' => $id_user));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteUsers($id_user)
    {
        $this->tableGateway->delete(array('id_user' => $id_user));
    }
}