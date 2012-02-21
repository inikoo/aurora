<?php
$sql="select `Page Parent Category`,`Page Short Title`,`Page URL`,`Page Snapshot Image Key` from `Page Dimension` P  left join `Page Internal Dimension`  I on (P.`Page Key`=I.`Page Key`)  where `Page Type`='Internal' and `Page Section`='Reports' ";

$res=mysql_query($sql);
$current_category='';
$report_index=array();

while($row=mysql_fetch_array($res)){
  if($current_category!=$row['Page Parent Category']){
    switch($row['Page Parent Category']){
    case('Sales Reports'):
      $_title=_('Sales');
      break;
    case('Tax Reports'):
      $_title=_('Tax Reports');
      break;  
    case('Activity/Performace Reports'):
      $_title=_('Activity/Performace');
      break;
    default:
      $_title=$row['Page Parent Category'];

    }
    if(!isset($report_index[$row['Page Parent Category']]))
    $report_index[$row['Page Parent Category']]=array('title'=>$_title,'reports'=>array());
  }
 // $title=$row['Page Short Title'];
  
  $report_index[$row['Page Parent Category']]['reports'][$_title]=array('title'=>$_title,'url'=>$row['Page URL'],'snapshot'=>'image.php?id='.$row['Page Snapshot Image Key']);
    

}

$smarty->assign('report_index',$report_index);
?>
