<?php
namespace Frontend\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Categories
{
    public $categ_id;
    public $categorie_name;    
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->categ_id     = (!empty($data['categ_id'])) ? $data['categ_id'] : null;
        $this->categorie_name  = (!empty($data['categorie_name'])) ? $data['categorie_name'] : null;
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