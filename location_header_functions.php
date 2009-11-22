<?php

function get_header_info($user,$smarty){
   
  $warehouse_list=array();
  $sql=sprintf("select `Warehouse Key`,`Warehouse Code`,`Warehouse Name` from `Warehouse Dimension` where `Warehouse Key` in (%s) ",join(",",$user->warehouses));
  $res=mysql_query($sql);
  while($row=mysql_fetch_array($res)){
    $warehouse_list[]=array('id'=>$row['Warehouse Key'],'code'=>$row['Warehouse Code'],'name'=>$row['Warehouse Name']);
  }
  $warehouse_list_length=count($warehouse_list);
  $smarty->assign('warehouse_list_length',$warehouse_list_length);
  if($warehouse_list_length==1){
    $warehouse_area_list=array();
    $sql=sprintf("select `Warehouse Area Key`,`Warehouse Area Code` from `Warehouse Area Dimension` where `Warehouse Key`=%d ",$warehouse_list[0]['id']);
    $res=mysql_query($sql);
    while($row=mysql_fetch_array($res)){
        $warehouse_area_list[]=array('id'=>$row['Warehouse Area Key'],'code'=>$row['Warehouse Area Code']);
    }
    $smarty->assign('warehouse_id',$warehouse_list[0]['id']);
    $smarty->assign('warehouse_name',$warehouse_list[0]['name']);
    $smarty->assign('tree_list',$warehouse_area_list);

  }else{
    $smarty->assign('tree_list',$warehouse_list);
  }
}


?>