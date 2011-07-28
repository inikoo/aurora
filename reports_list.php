<?php
$sql="select `Page Parent Category`,`Page Short Title`,`Page URL`,`Page Thumbnail Image Key` from `Page Dimension` P  left join `Page Internal Dimension`  I on (P.`Page Key`=I.`Page Key`)  where `Page Type`='Internal' and `Page Section`='Reports'";
//print $sql;
$res=mysql_query($sql);
$current_category='';
$report_index=array();

while($row=mysql_fetch_array($res)){
  if($current_category!=$row['Page Parent Category']){
    switch($row['Page Parent Category']){
    case('Sales Reports'):
      $title=_('Sales');
      break;
    case('Activity/Performace Reports'):
      $title=_('Activity/Performace');
      break;
    default:
      $title=$row['Page Parent Category'];

    }
    if(!isset($report_index[$row['Page Parent Category']]))
    $report_index[$row['Page Parent Category']]=array('title'=>$title,'reports'=>array());
  }
  $title=$row['Page Short Title'];
  
  $report_index[$row['Page Parent Category']]['reports'][$title]=array('title'=>$title,'url'=>$row['Page URL'],'snapshot'=>'image.php?id='.$row['Page Thumbnail Image Key']);
    

}
$smarty->assign('report_index',$report_index);
?>
