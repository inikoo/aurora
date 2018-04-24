<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 April 2018 at 13:50:55 BST, Sheffield UK
 Copyright (c) 2016, Inikoo

 Version 3

*/




$redis = new Redis();


print 'x';


 if(  $redis->connect('127.0.0.1', 6379)){

     if($redis->exists('tutorial-name')){

         print $redis->get('tutorial-name').'xddddd';
     }else{
         $redis->set('tutorial-name','hola');
     }
 }else{


     print 'o';

 }




?>