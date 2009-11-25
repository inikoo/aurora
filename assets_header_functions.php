<?php

function get_header_info($user,$smarty){
  $store_list=array();
  $sql=sprintf("select `Store Key`,`Store Code` from `Store Dimension` where `Store Key` in (%s) ",join(",",$user->stores));
  $res=mysql_query($sql);
  while($row=mysql_fetch_array($res)){
    $store_list[]=array('id'=>$row['Store Key'],'code'=>$row['Store Code']);
  }
  $store_list_length=count($store_list);
  $smarty->assign('store_list_length',$store_list_length);
  if($store_list_length==1){
    $department_list=array();
    $sql=sprintf("select `Product Department Key`,`Product Department Code` from `Product Department Dimension` where `Product Department Store Key` in (%s) ",join(",",$user->stores));
    $res=mysql_query($sql);
    while($row=mysql_fetch_array($res)){
      $department_list[]=array('id'=>$row['Product Department Key'],'code'=>$row['Product Department Code']);
    }
    
    $smarty->assign('tree_list',$department_list);

  }else{
    $smarty->assign('tree_list',$store_list);
  }
}


?>