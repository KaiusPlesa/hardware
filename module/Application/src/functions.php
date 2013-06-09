<?php

function p($misc,$exit = false){
    echo "<pre>".print_r($misc, true)."</pre>";
    if($exit) exit;
}

function imageCheck($image){
    
    if($image){
      $img = SERVER_URL."/img/product_type/".$image;
      return $img;
    }else{
      $img = SERVER_URL."/img/no_img.jpg";
      return $img;
    }
    
}

function format_time($time = null) {
    if($time) 
        return date("Y-m-d H:i:s", $time);
    else
        return false;
}

/**
* add flash message
* message format array('message' => 'val_message' , 'title' => 'val_title' , 'type' => 'val_type')
* 
* @param mixed $flashMessenger
* @param mixed $messageArray
*/
function addMessage($flashMessenger, $messageArray){

    $flashMessenger->addMessage(json_encode($messageArray));
    
}

/**
* getPending messages
* 
* @param mixed $flashMessenger
* $retun mixed messages array
*/
function getMessages($flashMessenger){
    
    
    $return= array();
    
    if ($flashMessenger->hasMessages()) {
        $tempArray = $flashMessenger->getMessages();
        if(is_array($tempArray)){
            $return = array_merge($return, $tempArray);
        }
        
    }
    
    if ($flashMessenger->hasCurrentMessages()) {
        $tempArray = $flashMessenger->getCurrentMessages();
        if(is_array($tempArray)){
            $return = array_merge($return, $tempArray);
        }
    }
    
    foreach($return as $key => $value){
        $return[$key] = json_decode($value);
    }
    
    return $return;
    
}

function showMessages($messageArray = null){
    
    if(is_array($messageArray)){
        if(count($messageArray)>0){
            echo '<script type="text/javascript">';
            
            foreach($messageArray as $message){
                echo "toastr.".$message->type."('".$message->message."', '".$message->title."');";
            }
            
            echo '</script>';
        }
    }
    
}

function categoriesRPC ($urlRPC, $username, $password){         
    
    require_once("Application/Misc/IXR_Library.php.inc"); 
    
    if(empty($urlRPC) || empty($username) || empty($password)){
        return "";
    }
    
    try{
        
        // Create the client object 
        $client = new IXR_Client($urlRPC); 
        $client->debug = false; // Set it to false in Production Environment 

        $params = array(0,$username,$password,10); // Last Parameter tells how many posts to fetch 

        // Run a query To Read Posts From Wordpress 
        if (!$client->query('metaWeblog.getCategories', $params)) { 
            return "";
        } 

        $myresponse = $client->getResponse();

        $categoriesTitles = array();

        foreach ($myresponse as $res => $val) {
            if(!empty($val['categoryName'])){
                $categoriesTitles[] = $val['categoryName'];
            }

        }
        if(count($categoriesTitles) == 0) return "";
        return implode(",",$categoriesTitles);      
    
    }catch(\Exception $e){
        return "";
    } 
}

function checkLiveStatusRPC ($urlRPC, $username, $password){         
    
    require_once("Application/Misc/IXR_Library.php.inc"); 
    
    if(empty($urlRPC) || empty($username) || empty($password)){
        return false;
    }
    
    try{
    
        // Create the client object 
        $client = new IXR_Client($urlRPC); 
        $client->debug = false; // Set it to false in Production Environment 

        $params = array(0,$username,$password); // Last Parameter tells how many posts to fetch 

        // Run a query To Read Blog Availability From Wordpress 
        if (!$client->query('demo.sayHello', $params)) { 
            return false;
        } 

        if($client->getResponse() != "Hello!") return false;
        
        return true; 
    
    }catch(\Exception $e){
        return false;
    }       
}    

function pushPostLive($urlRPC, $username, $password , $postEntry){
    
    require_once("Application/Misc/IXR_Library.php.inc"); 
    
    if(empty($urlRPC) || empty($username) || empty($password)){
        return false;
    }
    
    try{
    
        // Create the client object 
        $client = new IXR_Client($urlRPC); 
        $client->debug = false; // Set it to false in Production Environment 
        
        $title = htmlentities($postEntry->title, ENT_NOQUOTES, 'UTF-8');  
        $keywords = htmlentities($postEntry->tags, ENT_NOQUOTES, 'UTF-8');
        $body = $postEntry->body;
        $categories = explode(',', $postEntry->categories);
        
        foreach($categories as $key => $value){
            $categories[$key] = trim($value);
        }
        $date = new IXR_Date(strtotime($postEntry->when_to_post));

        $content = array(  
            'title' => $title,  
            'description' => $body,  
            'mt_allow_comments' => 1,  // 1 to allow comments  
            'mt_allow_pings' => 1,  // 1 to allow trackbacks  
            'post_type' => 'post',  
            'mt_keywords' => $keywords,  
            'categories' => $categories,
            'date_created_gmt' => $date
        );  
        
        $params = array(0,$username,$password,$content,true);  
        //p($params);
        
        // Run a query To Read Blog Availability From Wordpress 
        if (!$client->query('metaWeblog.newPost', $params)) { 
            return false;
        } 
        
        if((int)($client->getResponse()) <= 0 ) return false;
        
        return true; 
    
    }catch(\Exception $e){
        return false;
    }
   
    
}