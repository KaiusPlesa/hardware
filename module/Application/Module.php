<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
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
