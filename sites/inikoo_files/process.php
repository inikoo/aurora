<?php
    include_once('common.php');

    $url=$_SERVER['REQUEST_URI'];
    $url=preg_replace('/^\//', '', $url);
    //$url=preg_replace('/\/$/', '', $url);    

    $slashes=explode("/", $url);

    array_pop($slashes);
    $path='';
    foreach($slashes as $i){
        $path.='../';
    }
    
    preg_match_all('/\//', $url, $matches);
    
    print_r($matches);exit;
    
    print $path.$url;
    
    
    $found=false;
    
    if($page_key=$site->get_page_key_from_url($url)){
        print $url;
        //header("Location: {$path}page.php?id=".$page_key);          
        exit;
    }
    
    $url_2=$url;
    $url.='/index.php';
    
    if($page_key=$site->get_page_key_from_url($url)){
        print $url;exit;
        header("Location: {$path}page.php?id=".$page_key);     
        exit;
    }
    
    $url_2.='/index.html';
    if($page_key=$site->get_page_key_from_url($url_2)){
        print $url_2;
        //header("Location: {$path}page.php?id=".$page_key);          
        exit;
    }
    
    /*
    
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

    
    
    */
    

    

    
    

    
    print "not found";
    

    
    
    
    
?>