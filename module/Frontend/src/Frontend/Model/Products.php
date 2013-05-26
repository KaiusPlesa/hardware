<?php
namespace Frontend\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Products
{
    public $product_id;
    public $categ_id;
    public $subcateg_id;
    public $prod_id;
    public $product_name;    
    public $product_socket;    
    public $product_FSB;    
    public $product_memory_type;    
    public $product_memory_frequency;    
    public $product_memory_capacity;    
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->product_id     = (!empty($data['product_id'])) ? $data['product_id'] : null;
        $this->categ_id     = (!empty($data['categ_id'])) ? $data['categ_id'] : null;
        $this->subcateg_id     = (!empty($data['subcateg_id'])) ? $data['subcateg_id'] : null;
        $this->prod_id     = (!empty($data['prod_id'])) ? $data['prod_id'] : null;
        $this->product_name  = (!empty($data['product_name'])) ? $data['product_name'] : null;
        $this->product_socket  = (!empty($data['product_socket'])) ? $data['product_socket'] : null;
        $this->product_FSB  = (!empty($data['product_FSB'])) ? $data['product_FSB'] : null;
        $this->product_memory_type  = (!empty($data['product_memory_type'])) ? $data['product_memory_type'] : null;
        $this->product_memory_frequency  = (!empty($data['product_memory_frequency'])) ? $data['product_memory_frequency'] : null;
        $this->product_memory_capacity  = (!empty($data['product_memory_capacity'])) ? $data['product_memory_capacity'] : null;
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