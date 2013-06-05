<?php


namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\EventManager\EventInterface as Event;

class Module{

    
    public function onBootstrap(MvcEvent $e){
    
        $e->getApplication()->getServiceManager()->get('translator');
         
        $application         = $e->getApplication();
        $serviceManager      = $application->getServiceManager();
        $eventManager        = $application->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        
        $configsTable = $serviceManager->get("ZeDbManager")->get('Application\Model\ConfigSite');
        $configGlobal = $configsTable->getAll();
        $configArray = array("settings_global" => array());
        if($configGlobal){
            if(count($configGlobal)>0){
                foreach($configGlobal as $config){
                    $configArray["settings_global"][$config->var] = $config->val;    
                }
            }
        }
        unset($configGlobal);
        unset($configsTable);
        
        //@todo - inject in all controllers !
        
         
        // change the layout
 //       \Zend\EventManager\EventManager::getSharedManager()->attach('ZfcUser\Controller\UserController', 'dispatch', function($e){
//                $e->getTarget()->layout('layout/user');
//        });
        
        $events = $eventManager->getSharedManager();
        
            $events->attach('ZfcUser\Form\Register','init', function($e) use ($application) {
            $form = $e->getTarget();
            $route = $application->getMvcEvent()->getRouteMatch()->getParams(); 
            // Do what you please with the form instance ($form)

            $form->add(array(
                'name' => 'user_type',
                'type' => 'hidden',
                'options' => array(
                    'label' => 'User Type',
                ),
                'attributes' => array(
                    'type' => 'hidden',
                    'value' => $route['user_type'],
                ),
            ));
                $form->add(array(
                'name' => 'registration_time',
                'type' => 'hidden',
                'options' => array(
                    'label' => 'Registration Time',
                ),
                'attributes' => array(
                    'type' => 'hidden',
                    'value' => time(),
                ),
            ));
        
        });

        $moduleRouteListener->attach($eventManager);

    }

    public function getConfig(){
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig(){
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getServiceConfig()
    {
        return array(
            
            'factories' => array(
                 'zfcuser_user_mapper' => function ($sm) {
                    $options = $sm->get('zfcuser_module_options');
                    $mapper = new Mapper\User();
                    $mapper->setDbAdapter($sm->get('zfcuser_zend_db_adapter'));
                    $entityClass = $options->getUserEntityClass();
                    $mapper->setEntityPrototype(new $entityClass);
                    $mapper->setHydrator(new Mapper\UserHydrator());
                    $mapper->setTableName($options->getTableName());
                    return $mapper;
                },
                'zfcuser_register_form' => function ($sm) {
                    
                    $options = $sm->get('zfcuser_module_options');
                    $form = new \ZfcUser\Form\Register(null, $options);
                    //$form->setCaptchaElement($sm->get('zfcuser_captcha_element'));
                    
                    $usernameValidator =  new \ZfcUser\Validator\NoRecordExists(array(
                            'mapper' => $sm->get('zfcuser_user_mapper'),
                            'key'    => 'username'
                        ));
                        
                    $inputFilter = new \ZfcUser\Form\RegisterFilter(
                        new \ZfcUser\Validator\NoRecordExists(array(
                            'mapper' => $sm->get('zfcuser_user_mapper'),
                            'key'    => 'email'
                        )),
                        $usernameValidator,
                        $options
                    );
                    
                    $inputFilter->add(array(
                        'name'       => 'username',
                        'required'   => true,
                        'validators' => array(
                            array(
                                'name'    => 'StringLength',
                                'options' => array(
                                    'min' => 3,
                                    'max' => 255,
                                ),
                            ),
                            array(
                                'name'    => 'Zend\Validator\Regex',
                                'options' => array(
                                    'pattern' => '/^\b(\w*)$/i',
                                    'messages' => array(
                                       'regexNotMatch' => 'Whitespaces not allowed.',
                                       'regexInvalid' => 'Whitespaces not allowed.',
                                       'regexErrorous' => 'Whitespaces not allowed.',
                                    ),
                                ),
                            ),
                            $usernameValidator,
                        ),
                    ));
            
                    $form->setInputFilter($inputFilter);
                    
                    return $form;
                },
            ),
        );
    }
}