<?php
namespace Frontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Frontend\Model\Subcategories;                 
use Frontend\Form\SigninForm;
use Frontend\Form\EditUserForm;

class SubcategoriesController extends AbstractActionController
{
    protected $subcategoriesTable;
    
    public function getSubcategoriesTable()
    {
        if (!$this->subcategoriesTable) {
            $sm = $this->getServiceLocator();
            $this->subcategoriesTable = $sm->get('Frontend\Model\SubcategoriesTable');
        }
        return $this->subcategoriesTable;
    }
    
    public function indexAction()
    {
        $user = $this->getServiceLocator()->get('zfcuserauthservice')->getIdentity();
        if(empty($user)) return $this->redirect()->toRoute('zfcuser/login');        
        $userType = $user->getUserType();

        if ($userType != 1) {
            //redirect to the login redirect route
            return $this->redirect()->toRoute('home');
       }
 
        return new ViewModel(array(
            //'subcategories' => $this->getSubcategoriesTable()->getSubcategById('7'),
            'subcategories' => $this->getSubcategoriesTable()->fetchAll(),
        ));
    }

    public function addAction()
    {   $user = $this->getServiceLocator()->get('zfcuserauthservice')->getIdentity();
        if(empty($user)) return $this->redirect()->toRoute('zfcuser/login');        
        $userType = $user->getUserType();

        if ($userType != 1) {
            //redirect to the login redirect route
            return $this->redirect()->toRoute('home');
       }
        $form = new addForm();
        $form->get('submit')->setValue('Sign In');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $users = new Users();
            $form->setInputFilter($users->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $users->exchangeArray($form->getData());
                $this->getCategoriesTable()->saveUsers($users);

                // Redirect to list of users
                return $this->redirect()->toRoute('users');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $user = $this->getServiceLocator()->get('zfcuserauthservice')->getIdentity();
        if(empty($user)) return $this->redirect()->toRoute('zfcuser/login');        
        $userType = $user->getUserType();

        if ($userType != 1) {
            //redirect to the login redirect route
            return $this->redirect()->toRoute('home');
       }
              $user_id = (int) $this->params()->fromRoute('user_id', 0);
        if (!$user_id) {
            return $this->redirect()->toRoute('users', array(
                'action' => 'edit'
            ));
        }

        // Get the Users with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $users = $this->getCategoriesTable()->getUsers($user_id);
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
                 
                $this->getCategoriesTable()->editUsers($form->getData());

                // Redirect to list of users
                return $this->redirect()->toRoute('users');
            }
        }

        return array(
            'user_id' => $user_id,
            'form' => $form,
        );
    }
    
    public function deleteAction()
    {
        $user_id = (int) $this->params()->fromRoute('user_id', 0);
        if (!$user_id) {
            return $this->redirect()->toRoute('users');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $user_id = (int) $request->getPost('user_id');
                $this->getCategoriesTable()->deleteUsers($user_id);
            }

            // Redirect to list of Users
            return $this->redirect()->toRoute('users');
        }

        return array(
            'user_id'    => $user_id,
            'users' => $this->getCategoriesTable()->getUsers($user_id)
        );
    }
    
}