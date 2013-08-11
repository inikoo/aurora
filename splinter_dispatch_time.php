<?php 

$corporation_data=get_corporation_data();

$currency=$corporation_data['Account Currency'];

$sql=sprintf("select count(*) as orders, sum(`Order Total Net Amount`) as net , sum(`Order Total Tax Amount`) as tax from `Order Dimension` where `Order Current Dispatch State`='In Process'");
$res=mysql_query($sql);


$orders_in_process_data=array(
'orders'=>0
,'value'=>money(0,$currency)
);
if($row=mysql_fetch_assoc($res)){
$orders_in_process_data['orders']=$row['orders'];
$orders_in_process_data['value']=money($row['net']+$row['tax'],$currency);

}
$smarty->assign('orders_in_process_data',$orders_in_process_data);




?>