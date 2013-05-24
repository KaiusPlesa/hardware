<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Users\Model\Users;          
use Users\Form\SigninForm;
use Users\Form\EditUserForm;

class UsersController extends AbstractActionController
{
    protected $usersTable;
    
    public function getUsersTable()
    {
        if (!$this->usersTable) {
            $sm = $this->getServiceLocator();
            $this->usersTable = $sm->get('Users\Model\UsersTable');
        }
        return $this->usersTable;
    }
    
    public function indexAction()
    {
        return new ViewModel(array(
            'users' => $this->getUsersTable()->fetchAll(),
        ));
    }

    public function signinAction()
    {
        $form = new SigninForm();
        $form->get('submit')->setValue('Sign In');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $users = new Users();
            $form->setInputFilter($users->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $users->exchangeArray($form->getData());
                $this->getUsersTable()->saveUsers($users);

                // Redirect to list of users
                return $this->redirect()->toRoute('users');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
              $id_user = (int) $this->params()->fromRoute('id_user', 0);
        if (!$id_user) {
            return $this->redirect()->toRoute('users', array(
                'action' => 'edit'
            ));
        }

        // Get the Users with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $users = $this->getUsersTable()->getUsers($id_user);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('users', array(
                'action' => 'index'
            ));
        }

        $form  = new EditUserForm();
        $form->bind($users);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
      
            $form->setInputFilter($users->getInputFilterEdit());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                 
                $this->getUsersTable()->editUsers($form->getData());

                // Redirect to list of users
                return $this->redirect()->toRoute('users');
            }
        }

        return array(
            'id_user' => $id_user,
            'form' => $form,
        );
    }
    
    public function deleteAction()
    {
        $id_user = (int) $this->params()->fromRoute('id_user', 0);
        if (!$id_user) {
            return $this->redirect()->toRoute('users');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id_user = (int) $request->getPost('id_user');
                $this->getUsersTable()->deleteUsers($id_user);
            }

            // Redirect to list of Users
            return $this->redirect()->toRoute('users');
        }

        return array(
            'id_user'    => $id_user,
            'users' => $this->getUsersTable()->getUsers($id_user)
        );
    }
    
}