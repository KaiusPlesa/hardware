<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Client;
use Zend\Http\Request;

class ParserController extends AbstractActionController{
    
    public function indexAction(){
        
        if(!$this->zfcUserAuthentication()->getIdentity()) return $this->redirect()->toRoute('zfcuser/login'); 
        if($this->zfcUserAuthentication()->getIdentity()->getId() != 0) return $this->redirect()->toRoute('zfcuser/login'); 

        $client = new \Zend\Http\Client();

        $client->setUri('http://www.emag.ro/procesoare/sort-priceasc/c');
        
        $response = $client->send();
        return $response;
        exit;
        
        $dom = new \Zend\Dom\Query($response);
                    
                    $rows = $dom->execute('.wrapper-content a');
                    p($dom);
                    $count = count($rows); // get number of matches: 4
                    foreach ($rows as $result) {
                        p($result);
                    }
                    exit;
                    foreach($rows as $row){
                        if(empty($version))$version = trim($row->nodeValue);
                        else $pageId = trim($row->nodeValue);
                        
                    }
        
        return $response;
        //$data['index'] = $response;
        //return new ViewModel($data);
    }
}
?>
