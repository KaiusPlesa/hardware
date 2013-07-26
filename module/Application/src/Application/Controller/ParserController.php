<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Client;
use Zend\Http\Request;

class ParserController extends AbstractActionController
{
    
    public function indexAction()
    {   
        $categorie = 'procesoare';
        $client = new \Zend\Http\Client();
        $client->setAdapter('\Zend\Http\Client\Adapter\Curl');
        
        $response = $this->getResponse();
        //set content-type
        $response->getHeaders()->addHeaderLine('content-type', 'text/html; charset=utf-8');              
        $url = 'http://www.emag.ro/'.$categorie.'/sort-priceasc/c'; 
        $client->setUri($url);
        $result                 = $client->send();
        //content of the web
        $body                   = $result->getBody();
        
        $dom = new \Zend\Dom\Query($body);
        $pages = $dom->execute('span.pagini-2');
        foreach($pages as $key){
           $rowMaxPag = explode(' ',$key->textContent); 
           $maxPag = explode(':',$rowMaxPag['3']); 
           
        }
        for($i=1;$i<=$maxPag[0];$i++){
            
           $page[] = $i;
        }

        foreach($page as $pag){
            if($pag == 1){
                $url = 'http://www.emag.ro/procesoare/sort-priceasc/c';    
            }else{
                $url = 'http://www.emag.ro/procesoare/sort-priceasc/p'.$pag.'/c';
            }
        $client->setUri($url);

        $result                 = $client->send();
        //content of the web
        $body                   = $result->getBody();
        
        $dom = new \Zend\Dom\Query($body);
        //get div with id="content" and h2's NodeList
        $title = $dom->execute('div.big-box div.col-2-prod');        
        
        foreach($title as $key=>$r){
            //per h2 NodeList, has element with tagName = 'a'
            //DOMElement get Element with tagName = 'a'
            $aelement     = $r->getElementsByTagName("a")->item(0);    
            
            if ($aelement->hasAttributes()) {                  
                $content[]= 'http://www.emag.ro'.$aelement->getAttributeNode('href')->nodeValue.'';            
                
            }
        }
 
        }
        $response->setContent($content);
       
        foreach($content as $key=>$val){
            $client->setUri($val);

            $result                 = $client->send();
            //content of the web
            $body                   = $result->getBody();
            
            $dom = new \Zend\Dom\Query($body);
            //get div with id="content" and h2's NodeList
            $title = $dom->execute('div.holder-specificatii p');   
        
        foreach($title as $key=>$r){

            $values[$r->textContent] = $r->textContent;   
            p($r);  
       
        }
        //p($values);
        }
         
    }
}
?>
