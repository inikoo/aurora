<?php


$plot_tipo=$_SESSION['state'][$page]['plot'];

$plot_data=$_SESSION['state'][$page]['plot_data'][$plot_tipo];

$plot_period=$_SESSION['state'][$page]['plot_period'];
$plot_category=$_SESSION['state'][$page]['plot_category'];

$plot_interval=$_SESSION['state'][$page]['plot_interval'][$plot_period]['plot_bins'];
$plot_forecast=$_SESSION['state'][$page]['plot_interval'][$plot_period]['plot_forecast_bins'];
//print $plot_forecast;


$smarty->assign('plot_period',$plot_period);
$smarty->assign('plot_category',$plot_category);


$smarty->assign('plot_interval',$plot_interval);
$smarty->assign('plot_forecast',$plot_forecast);



if($page=='part' or $page=='warehouse_stock_history' ){
  $currency='GBP';
  $sql=sprintf("select `Account Currency` from `Account Dimension` ");
   $res=mysql_query($sql);
   if($row=mysql_fetch_array($res)){
     $currency=$row['Account Currency'];
   }



}
else{
  $currency=$store->data['Store Currency Code'];

}

$plot_args='tipo='.$page.'&category='.$plot_category.'&period='.$plot_period.'&keys='.$subject_id.'&currency='.$currency.'&from='.$plot_interval.'&to='.$plot_forecast;

$smarty->assign('plot_keys',$subject_id);
$smarty->assign('plot_currency',$subject_id);


if($plot_tipo=='top_departments'){
  $number_children=3;
  $plot_args.=sprintf('&top_children=%d',$number_children);
}




  if($plot_period=='d')
      $plot_formated_period='Daily';
    elseif($plot_period=='m')
      $plot_formated_period='Monthly';
    elseif($plot_period=='y')
      $plot_formated_period='Yearly';
    elseif($plot_period=='q')
      $plot_formated_period='Quarterly';
    elseif($plot_period=='w')
      $plot_formated_period='Weekly';
  
if($page=='part' and $plot_category=='stock_history'){
  $plot_formated_category=_('Stock Keeping Units');
  
}elseif($page=='part'){
  $plot_formated_category=_('Stock Value');
}else if($plot_category=='profit')
   $plot_formated_category=_('Profits');
else
  $plot_formated_category=_('Net Item Sales');


$smarty->assign('plot_formated_category',$plot_formated_category);
$smarty->assign('plot_formated_period',$plot_formated_period);



$plot_period_menu=array(
		     array("period"=>'w','label'=>_('Weekly'))
		     ,array("period"=>'m','label'=>_('Montly'))
		     ,array("period"=>'q','label'=>_('Quarterly'))
		     ,array("period"=>'y','label'=>_('Yearly'))
		     );

if($page=='part'){

$plot_period_menu=array(
		     array("period"=>'d','label'=>_('Daily')),
		     array("period"=>'w','label'=>_('Weekly')),
		     array("period"=>'m','label'=>_('Montly')),
		     array("period"=>'q','label'=>_('Quarterly'))
		   
		     );


  $plot_category_menu=array(
			    array("category"=>'stock','label'=>_('Stock Keeping Units'))
			    ,array("category"=>'value','label'=>_('Stock Value'))
			    );
  $smarty->assign('plot_category_menu',$plot_category_menu);
}else{
$plot_category_menu=array(
		     array("category"=>'sales','label'=>_('Net Item Sales'))
		     ,array("category"=>'profit','label'=>_('Profit'))
			  );
$smarty->assign('plot_category_menu',$plot_category_menu);
}

$smarty->assign('plot_period_menu',$plot_period_menu);


$plot_interval_menu=array(
  'd'=>array(
		         array("value"=>100,'label'=>_('All')),
		         array("value"=>30,'label'=>_('1 month'))),
            'y'=>array(
		         array("value"=>100,'label'=>_('All')),
		         array("value"=>5,'label'=>_('5 years'))),
'q'=>array(
		         array("value"=>100,'label'=>_('All')),
		         array("value"=>12,'label'=>_('3 years'))),
'm'=>array(
		         array("value"=>100,'label'=>_('All')),
		         array("value"=>18,'label'=>_('1½ years')),
		         array("value"=>12,'label'=>_('1 year'))
		         ),
'w'=>array(
		         array("value"=>52,'label'=>_('1 year')),
		         array("value"=>26,'label'=>_('6 months')),
		         array("value"=>12,'label'=>_('12 weeks')),

		         ),		         
		         
)
		     ;		     
$plot_forecast_interval_menu=array(


 'd'=>array(
		     array("value"=>0,'label'=>_('None')),
		     array("value"=>5,'label'=>_('5 days'))
  ),
 'y'=>array(
		     array("value"=>0,'label'=>_('None')),
		     array("value"=>3,'label'=>_('3 years'))
  ),
'q'=>array(
		     array("value"=>0,'label'=>_('None')),
		     array("value"=>3,'label'=>_('3 quarters'))
),
'm'=>array(
		     array("value"=>0,'label'=>_('None')),
		     array("value"=>3,'label'=>_('3 months')),
		     array("value"=>12,'label'=>_('12 months')),
		     array("value"=>24,'label'=>_('24 months'))

),
'w'=>array(
		     array("value"=>0,'label'=>_('None')),
		     array("value"=>5,'label'=>_('5 weeks'))
)
		     );		   		     
		     
		     
$smarty->assign('plot_interval_label',($plot_interval==100?'∀':$plot_interval).':'.$plot_forecast);
		     
$smarty->assign('plot_interval_menu',$plot_interval_menu[$plot_period]);
$smarty->assign('plot_forecast_interval_menu',$plot_forecast_interval_menu[$plot_period]);

$pie_interval_menu=array(
'all'=>array('label'=>_('All time')),
'1y'=>array('label'=>_('Last Year')),
'1q'=>array('label'=>_('Last Quarter')),
'1m'=>array('label'=>_('Last Month')),


);
if($plot_tipo=='pie'){
  $pie_forecast=$plot_data['forecast'];
  $pie_interval=$_SESSION['state'][$page]['plot_data']['pie']['interval'];
  if($plot_data['date']=='today'){
    $plot_date=date('Y-m-d');
    $smarty->assign('plot_date',$plot_date);
    $smarty->assign('plot_formated_date',strftime("%b %Y",strtotime($plot_date)));

  }

  $plot_args=sprintf('tipo=children_share&item=store&category=%s&interval=%s&keys=%d&date=%s&forecast=%s'
		     ,$plot_category
		     ,$pie_interval
		     ,$store_id
		     ,$plot_date
		     ,$plot_data['forecast']);
$smarty->assign('pie_interval_label',$pie_interval_menu[$pie_interval]['label']);

}



$smarty->assign('pie_interval_menu',$pie_interval_menu);


$smarty->assign('plot_tipo',$plot_tipo);
$smarty->assign('plot_args',$plot_args);
$smarty->assign('plot_page',$plot_data['page']);

$smarty->assign('plot_data',$_SESSION['state'][$page]['plot_data']);

?>