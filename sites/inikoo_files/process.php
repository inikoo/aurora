<?php
    include_once('common.php');

    $url=$_SERVER['REQUEST_URI'];
    $url=preg_replace('/^\//', '', $url);
    $url=preg_replace('/\/$/', '', $url);    

    $slashes=explode("/", $url);

    array_pop($slashes);
    $path='';
    foreach($slashes as $i){
        $path.='../';
    }
    $found=false;
    
    
    $links=array();
    $ext=array('', 'html', 'php');
    $_ext=$ext;
    
    foreach($ext as $key=>$value){
        $first=array_shift($_ext);
        
        foreach($_ext as $val)
        $links[$first][]=$val;
        
        array_push($_ext, $first);
    }
    
    while(!$found){
        foreach($links as $key=>$val){
            if($page_key=$site->get_page_key_from_url($url)){
                $found=true;
            }
        }
    }

    
    
    
    
    if($page_key=$site->get_page_key_from_url($url)){
        header("Location: {$path}page.php?id=".$page_key);          
    }
    $url_2=$url;
    $url.='/index.php';
    
    if($page_key=$site->get_page_key_from_url($url)){
        header("Location: {$path}page.php?id=".$page_key);          
    }
    
    $url_2.='/index.html';
    if($page_key=$site->get_page_key_from_url($url)){
        header("Location: {$path}page.php?id=".$page_key);          
    }
    

    
    

    
    print "not found";
    

    
    
    
    
?>