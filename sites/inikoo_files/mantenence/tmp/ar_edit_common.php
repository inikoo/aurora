<?php
require_once 'ar_common.php';
date_default_timezone_set('UTC');




if(isset($user) and is_object($user)){
$editor=array(
            'Author Name'=>$user->data['User Alias'],
            'Author Alias'=>$user->data['User Alias'],
            'Author Type'=>$user->data['User Type'],
            'Author Key'=>$user->data['User Parent Key'],
            'User Key'=>$user->id,
            'Date'=>gmdate('Y-m-d H:i:s')
        );
}else{
$editor=array(
            'Author Name'=>'',
            'Author Alias'=>'',
            'Author Type'=>'',
            'Author Key'=>'',
            'User Key'=>0,
            'Date'=>gmdate('Y-m-d H:i:s')
        );

}
        
?>