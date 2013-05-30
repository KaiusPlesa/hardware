<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\Form;
use Zend\Form\Element;

class IndexController extends AbstractActionController{
    
    public function indexAction(){
        
       //if(!$this->getServiceLocator()->get('zfcuserauthservice')->hasIdentity()) return $this->redirect()->toRoute('zfcuser/login');
        $manufacturers = $this->getServiceLocator()->get("ZeDbManager")->get('Application\Model\Manufacturer');

       $manufacturer = $manufacturers->getAll(); 
       //$products = $product->getAll(); 
       //$product_detail = $product_details->getAll(); 
 
       $data = array();
       $data['manufacturer'] = $manufacturer;
       return new ViewModel($data);
    }
    public function productsAction(){
            
        //if(!$this->getServiceLocator()->get('zfcuserauthservice')->hasIdentity()) return $this->redirect()->toRoute('zfcuser/login');

        $idBlog = $this->params()->fromRoute('id');
        $product_details = $this->getServiceLocator()->get("ZeDbManager")->get('Application\Model\ProductDetails');
        
        
        //$product_details->getByManufacturerIdAndProductID(1,1);
               
       $results = $product_details->getAllByManufacturerID($idBlog);
       //$products = $product->getAll(); 
       //$product_detail = $product_details->getAll();       
       $data = array();
       $data['result'] = $results;
       //$data['products'] = $products;
       //$data['products_detail'] = $product_detail;
//       
//       echo"<pre>";
//       print_r($data);
//       echo"</pre>";
        
  
       return new ViewModel($data);
    }
    
    public function categoriesAction(){
        
        if(!$this->getServiceLocator()->get('zfcuserauthservice')->hasIdentity()) return $this->redirect()->toRoute('zfcuser/login');

        $user = $this->getServiceLocator()->get('zfcuserauthservice')->getIdentity();
        if(empty($user)) return $this->redirect()->toRoute('zfcuser/login');
        
        //only allowed for admin
        $userType = $user->getUserType();
        if($userType != "admin") return $this->redirect()->toRoute("posts");
        
        $categoriesTable  = $this->getServiceLocator()->get("ZeDbManager")->get('Application\Model\Categories');
        $categories = $categoriesTable->getAll();
        
        $data = array();
        $data['messages'] = getMessages($this->flashMessenger());    
        $data['categories'] = $categories;
        
        return new ViewModel($data); 
    }
    
    public function categoriesaddAction(){
        
        if(!$this->getServiceLocator()->get('zfcuserauthservice')->hasIdentity()) return $this->redirect()->toRoute('zfcuser/login');

        $user = $this->getServiceLocator()->get('zfcuserauthservice')->getIdentity();
        if(empty($user)) return $this->redirect()->toRoute('zfcuser/login');
        
        //only allowed for admin
        $userType = $user->getUserType();
        if($userType != "admin") return $this->redirect()->toRoute("posts");
        
        $data = array();
        
        $form    = new \Application\Form\CategoryAdd();
        $form->setServiceLocator($this->getServiceLocator());
        $form->prepareElements();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $dataForm = $form->getData();
                
                unset($dataForm['save_new']);
                unset($dataForm['security']);
                
                //get categories table        
                $categoriesTable  = $this->getServiceLocator()->get("ZeDbManager")->get('Application\Model\Categories');
        
                //create entity
                $category = $categoriesTable->create($dataForm);

                //save in db
                $categoriesTable->save($category);
                
                //add success message
                addMessage($this->flashMessenger(), array(
                    "message" => "The ".$category->name." category successfully saved!",
                    "title" => "Category Saved",
                    "type" => "success"
                ));
                
                // Redirect to list of categories
                return $this->redirect()->toRoute('categories');
            }else{
                if (!$this->flashMessenger()->hasMessages()){
                    addMessage($this->flashMessenger(), array(
                        "message" => "The category typed is not valid. Please modify!",
                        "title" => "Form Invalid",
                        "type" => "error"
                    ));
                }
            }
            
        } 
        
        $data['messages'] = getMessages($this->flashMessenger());         
        $data['form'] = $form;   
         
        return new ViewModel($data);
         
    }
    
    public function globalsettingsAction(){
        
        if(!$this->getServiceLocator()->get('zfcuserauthservice')->hasIdentity()) return $this->redirect()->toRoute('zfcuser/login');
        
        $user = $this->getServiceLocator()->get('zfcuserauthservice')->getIdentity();
        if(!$user) return $this->redirect()->toRoute('zfcuser/login');
        
        //only allowed for admin
        if($user->getUserType() != 'admin') return $this->redirect()->toRoute('blogs');
        
        $userId = $user->getId();
        
        $configSiteTable  = $this->getServiceLocator()->get("ZeDbManager")->get('Application\Model\ConfigSite');
        $configValues = $configSiteTable->getAll();
        
        $formArray = array();
        $formArray["action"] = $this->url('global-settings'); 
        $formArray["id"] = "globalSettingsForm"; 
        $formArray["fields"] = array();
        
        /* refund_limit site_name site_url currency revenue_share*/
        
        if($configValues){
            if(count($configValues)>0){
                foreach($configValues as $config){
                    $formArray["fields"][$config->var] = array("value" => $config->val, "label" => ucwords(str_replace("_", " ", $config->var)));    
                }
            }
        }
          
        //initialize form dinamically           
        $form = new Form();
        $form->setAttribute('action', $formArray["action"]);   
        $form->setAttribute('id', $formArray["id"]);   
        foreach($formArray["fields"] as $k => $v) {    
            $form->add(array(
                'name' => $k,
                'options' => array(
                    'label' => $v['label'],
                ),
            ));
        }
        
        $form->add(new Element\Csrf('security'));
        
        $form->add(array(
            'name' => 'save',
            'options' => array(
                'label' => 'Save Global Settings',
            ),
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Save Global Settings',
                'class' => 'btn medium btn-primary',
            ),
        ));
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                
                //keep only usefull fields
                $dataForm = $form->getData();
                unset($dataForm['save']);
                unset($dataForm['security']);
        
                foreach($dataForm as $key => $value){
                    
                    $configEntity = $configSiteTable->getByVar($key);
                    
                    if($configEntity){
                    
                        //update old value
                        $configEntity->val = $value;
                        
                        //save in db
                        $configSiteTable->save($configEntity);
                    
                    }else{
                        
                        //no variable found
                        $name = ucwords(str_replace("_", " ", $configEntity->var));
                        
                        //add warning message
                        addMessage($this->flashMessenger(), array(
                            "message" => "The setting: ".$name."  does not exist!",
                            "title" => $name." Not Saved",
                            "type" => "warning"
                        ));

                    }
                    
                }
                
                //add success message
                addMessage($this->flashMessenger(), array(
                    "message" => "The Global Settings successfully saved!",
                    "title" => "Global Settings Saved",
                    "type" => "success"
                ));
                
                // Redirect to global settings
                return $this->redirect()->toRoute('global-settings');
                
            }else{
                
                //form not valid - show error message
                if (!$this->flashMessenger()->hasMessages()){
                    addMessage($this->flashMessenger(), array(
                        "message" => "The fields typed are not valid or form expired. Please modify!",
                        "title" => "Form Invalid or Expired",
                        "type" => "error"
                    ));
                }
                
            }
            
        }else{
            
            //initialize values from db
            foreach($formArray["fields"] as $k => $v) {    
                $form->get($k)->setValue($v['value']);
            }
        
        } 
        
        $data['form'] = $form; 
        $data['messages'] = getMessages($this->flashMessenger());
         
        return new ViewModel($data);
        
    }
}