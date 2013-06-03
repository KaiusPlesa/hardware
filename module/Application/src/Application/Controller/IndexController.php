<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class IndexController extends AbstractActionController{
    
    protected $profileService;
    protected $moduleOptions;
    protected $tableName  = 'users';
    
    public function indexAction(){
        
        if ($this->zfcUserAuthentication()->hasIdentity()) {
        $user  = $this->zfcUserAuthentication()->getIdentity();
        $user_id = $user->getId();       
        
        $data = array(
        'user_id'  => time()
        );
              
        }
        
        $manufacturers = $this->getServiceLocator()->get("ZeDbManager")->get('Application\Model\Manufacturer');

       $manufacturer = $manufacturers->getAll(); 
 
       $data = array();
       $data['manufacturer'] = $manufacturer;
       return new ViewModel();    
    
    }
     public function categoriesAction(){
        
        $categorie = $this->params()->fromRoute('categorie');

        $product_type = $this->getServiceLocator()->get("ZeDbManager")->get('Application\Model\ProductType');

       $categ = $product_type->getAllByCategorie($categorie); 
       
       $data = array();
       $data['categ'] = $categ;
       return new ViewModel($data);
    }
    public function productsAction(){
             
        $products = $this->getServiceLocator()->get("ZeDbManager")->get('Application\Model\Products');
        $product_types = $this->getServiceLocator()->get("ZeDbManager")->get('Application\Model\ProductType');
        $categorie = $this->params()->fromRoute('categorie');

        if($categorie === "AllProducts"){
            $product = $products->getAll();
        }else{            
            $product = $products->getAllByProduct($categorie);
        }
        $product_type = $product_types->getAll();                 

        $data = array();
        $data['categorie'] = $product_type;
        $data['product'] = $product;
               
        //echo"<pre>";
        //print_r($arrayAdapter);
        //echo"</pre>";
        return new ViewModel($data);
    }
       
    public function productAction(){
                            
        //if(!$this->getServiceLocator()->get('zfcuserauthservice')->hasIdentity()) return $this->redirect()->toRoute('zfcuser/login');

        $idBlog = $this->params()->fromRoute('id');
        $product_description = $this->getServiceLocator()->get("ZeDbManager")->get('Application\Model\ProductDescription');
        
        
        //$product_details->getByManufacturerIdAndProductID(1,1);
               
       $results = $product_description->getAllByManufacturerID($idBlog);
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
    public function profileAction()
    {
        $messages = array();
        $service = $this->getProfileService();
        $service->setUser($this->getServiceLocator()->get('zfcuser_auth_service')->getIdentity());       
        if(!$this->getServiceLocator()->get('zfcuserauthservice')->hasIdentity()) return $this->redirect()->toRoute('zfcuser/login');  
        $sections = $service->getSections();

        if ($this->getRequest()->isPost()) 
        {
            $data = $this->getRequest()->getPost()->toArray();
            if ( $service->save($data) ) 
            {
                $messages[] = array(
                    'type'    => 'success',
                    'icon'    => 'icon-ok-sign',
                    'message' => 'Your profile has been updated successfully!',
                );
            }
            else
            {
                $messages[] = array(
                    'type'    => 'error',
                    'icon'    => 'icon-remove-sign',
                    'message' => 'Profile update failed!  See error messages below for more details.',
                );
            }
        }

        return new ViewModel(array(
            'messages'  => $messages,
            'user'      => $service->getUser(),
            'sections'  => $sections,
            'options'   => $this->getModuleOptions()
        ));
    }

    protected function getProfileService()
    {
        if ($this->profileService === null) {
            $this->profileService = $this->getServiceLocator()->get('CdliUserProfile\Service\Profile');
        }
        return $this->profileService;
    }

    protected function getModuleOptions()
    {
        if ($this->moduleOptions === null) {
            $this->moduleOptions = $this->getServiceLocator()->get('cdliuserprofile_module_options');
        }
        return $this->moduleOptions;
    }
    /*
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
        
        // refund_limit site_name site_url currency revenue_share
        
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
    */
}