<?php
namespace Users\Form;

use Zend\Form\Form;
use Zend\Captcha;

class EditUserForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('users');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id_user',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Name',
            ),
        ));
        $this->add(array(
            'name' => 'username',
            'type' => 'Text',
            'options' => array(
                'label' => 'Username',
            ),
        ));
         $this->add(array(
            'name' => 'email',
            'type' => 'Text',
            'options' => array(
                'label' => 'Email Address',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Sign Up',
                'id' => 'submitbutton',
                'class' => 'addButton',
                
            ),
        ));
    }
}