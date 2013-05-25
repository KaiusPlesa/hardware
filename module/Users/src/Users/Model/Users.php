<?php
namespace Users\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Users
{
    public $user_id;
    public $username;
    public $display_name;
    public $password;
    public $email;
    public $user_type;
    public $last_login;
    public $state;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->user_id     = (!empty($data['user_id'])) ? $data['user_id'] : null;
        $this->username  = (!empty($data['username'])) ? $data['username'] : null;
        $this->display_name  = (!empty($data['display_name'])) ? $data['display_name'] : null;
        $this->password  = (!empty($data['password'])) ? $data['password'] : null;
        $this->email  = (!empty($data['email'])) ? $data['email'] : null;
        $this->user_type  = (!empty($data['user_type'])) ? $data['user_type'] : null;
        $this->last_login  = (!empty($data['last_login'])) ? $data['last_login'] : null;
        $this->state  = (!empty($data['state'])) ? $data['state'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'user_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
            $inputFilter->add($factory->createInput([ 
                'name' => 'email', 
                'required' => true, 
                'filters' => [ 
                    ['name' => 'StripTags'], 
                    ['name' => 'StringTrim'], 
                ], 
                'validators' => [ 
                    [ 
                        'name' => 'EmailAddress', 
                        'options' => [ 
                            'encoding' => 'UTF-8', 
                            'min'      => 5, 
                            'max'      => 255, 
                            'messages' => array( 
                                \Zend\Validator\EmailAddress::INVALID_FORMAT => 'Email address format is invalid' 
                            ) 
                        ], 
                    ], 
                ], 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'password', 
                'required' => true, 
                'filters' => [ ['name' => 'StringTrim'], ], 
                'validators' => [ 
                    [ 
                        'name' => 'StringLength', 
                        'options' => [ 
                            'encoding' => 'UTF-8', 
                            'min'      => 6, 
                            'max'      => 128, 
                        ], 
                    ], 
                ], 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'password_verify', 
                'required' => true, 
                'filters' => [ ['name' => 'StringTrim'], ], 
                'validators' => [ 
                    array( 
                        'name'    => 'StringLength', 
                        'options' => array( 'min' => 6 ), 
                    ), 
                    array( 
                        'name' => 'identical', 
                        'options' => array('token' => 'password' ) 
                    ), 
                ], 
            ]));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'username',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
        public function getInputFilterEdit()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'user_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
            $inputFilter->add($factory->createInput([ 
                'name' => 'email', 
                'required' => true, 
                'filters' => [ 
                    ['name' => 'StripTags'], 
                    ['name' => 'StringTrim'], 
                ], 
                'validators' => [ 
                    [ 
                        'name' => 'EmailAddress', 
                        'options' => [ 
                            'encoding' => 'UTF-8', 
                            'min'      => 5, 
                            'max'      => 255, 
                            'messages' => array( 
                                \Zend\Validator\EmailAddress::INVALID_FORMAT => 'Email address format is invalid' 
                            ) 
                        ], 
                    ], 
                ], 
            ])); 
            $inputFilter->add($factory->createInput(array(
                'name'     => 'username',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}