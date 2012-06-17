<?php 

$corporation_data=get_corporation_data();

$currency=$corporation_data['HQ Currency'];

$sql=sprintf("select count(*) as orders, avg(`Order Total Net Amount`*`Order Currency Exchange`) as net from `Order Dimension` where `Order Current Dispatch State`='Dispatched'");
$res=mysql_query($sql);


$average_order_value=_('ND');
$samples=0;

if($row=mysql_fetch_assoc($res)){
$average_order_value=money($row['net'],$currency);
$samples=$row['orders'];

}
$smarty->assign('average_order_value',$average_order_value);




?>