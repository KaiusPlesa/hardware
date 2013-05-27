<?php
namespace Frontend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Frontend\Model\Categories;          
use Frontend\Model\Subcategories;          
use Frontend\Model\Producers;          
use Frontend\Model\Products;          

class FrontendController extends AbstractActionController
{
    protected $categoriesTable;
    protected $subcategoriesTable;
    protected $producersTable;
    protected $productsTable;
    
    // START TABLES -------------------------------------------------------
    public function getCategoriesTable()
    {
        if (!$this->categoriesTable) {
            $sm = $this->getServiceLocator();
            $this->categoriesTable = $sm->get('Frontend\Model\CategoriesTable');
        }
        return $this->categoriesTable;
    }
     public function getSubcategoriesTable()
    {
        if (!$this->categoriesTable) {
            $sm = $this->getServiceLocator();
            $this->categoriesTable = $sm->get('Frontend\Model\SubcategoriesTable');
        }
        return $this->categoriesTable;
    }
     public function getProducersTable()
    {
        if (!$this->categoriesTable) {
            $sm = $this->getServiceLocator();
            $this->categoriesTable = $sm->get('Frontend\Model\ProducersTable');
        }
        return $this->categoriesTable;
    }
      public function getProductsTable()
    {
        if (!$this->categoriesTable) {
            $sm = $this->getServiceLocator();
            $this->categoriesTable = $sm->get('Frontend\Model\ProductsTable');
        }
        return $this->categoriesTable;
    }
    // END TABLES -------------------------------------------------------
    
    //REQUIRE ADMIN CODE
    /*  
    $user = $this->getServiceLocator()->get('zfcuserauthservice')->getIdentity();
        if(empty($user)) return $this->redirect()->toRoute('zfcuser/login');        
        $userType = $user->getUserType();

        if ($userType != 1) {
            //redirect to the login redirect route
            return $this->redirect()->toRoute('home');
       } 
    */
    public function indexAction()
    {
        $rowset = $this->getProducersTable()->fetchAll();
        $producers = $rowset->toArray();
        foreach($producers as $key=>$producer){           
            $prod[$producer['producer_name']] = $producer['producer_id'];
        }
        echo"<pre>"; 
        print_r($producers);      
        echo"</pre>";  
        //exit;      
        return new ViewModel(array(
            'categories' => $this->getCategoriesTable()->fetchAll(),
        ));
    }
    //----------------------------------------------------------------
    //PRINT ALL PRODUCERS ADMIN LOGIN REQUIRE
    public function producersAction()
    {
             
        return new ViewModel(array(
            'producers' => $this->getProducersTable()->fetchAll(),
        ));
    }
    //----------------------------------------------------------------
    //PRINT ALL SUBCATEGORIES ADMIN LOGIN REQUIRE
    public function subcategoriesAction()
    {
        
        return new ViewModel(array(
            //'subcategories' => $this->getSubcategoriesTable()->getSubcategById('7'),
            'subcategories' => $this->getSubcategoriesTable()->fetchAll(),
        ));
    }
    //----------------------------------------------------------------
    //PRINT ALL CATEGORIES ADMIN LOGIN REQUIRE
    public function categoriesAction()
    {

        return new ViewModel(array(
            'categories' => $this->getCategoriesTable()->fetchAll(),
        ));
    }
     //----------------------------------------------------------------
    //PRINT ALL PRODUCTS ADMIN LOGIN REQUIRE
    public function productsAction()
    {
        
        return new ViewModel(array(
            'products' => $this->getProductsTable()->fetchAll(),
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