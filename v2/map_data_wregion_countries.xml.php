<?php
include_once('common.php');

if(isset($_REQUEST['wregion']))
$wregion_code=$_REQUEST['wregion'];
else
exit('');


$map_data=array('map_file'=>"maps/world.swf",
                'tl_long'=>"-168.49",
                'tl_lat'=>"83.63",
                'br_long'=>"190.3",
                'br_lat'=>"-55.58",
                'zoom_x'=>"0%",
                'zoom_y'=>"0%",
                'zoom'=>"100%");
$sql=sprintf("select `AMMAP Settings`,`World Region Code`,`World Region` from  kbase.`World Region Dimension`  where `World Region Code`=%s",
prepare_mysql($wregion_code)
);

$res=mysql_query($sql);
$wregion_data=array();
$wregion_index=array();
$i=0;
if($row=mysql_fetch_assoc($res)) {
    $map_data=unserialize($row['AMMAP Settings']);
}


$sql=sprintf("select `World Region Code`,`World Region`,group_concat(`Country 2 Alpha Code`) as countries from kbase.`Country Dimension` group by `World Region Code`");
$res=mysql_query($sql);
$wregion_data=array();
$wregion_index=array();
$i=0;
while ($row=mysql_fetch_assoc($res)) {
    $wregion_data[$row['World Region Code']]=array('countries'=>$row['countries'],'name'=>$row['World Region'],'value'=>0);
    $wregion_index[$row['World Region Code']]=$i;
    $i++;
}


//$map_data=array('map_file'=>"maps/world.swf",
  //              'zoom_x'=>"-202.31%",
    //            'zoom_y'=>"-258.29%",
      //          'zoom'=>"441%");
//print serialize($map_data);
if (isset($_REQUEST['report'])) {
    $smarty->assign('with_values',true);

    $report=$_REQUEST['report'];
    switch ($report) {
    case 'sales':

        if (isset( $_REQUEST['from']))
            $from=$_REQUEST['from'];
        else {
            $from=$_SESSION['state']['report_geo_sales']['from'];
        }

        if (isset( $_REQUEST['to']))
            $to=$_REQUEST['to'];
        else {
            $to=$_SESSION['state']['report_geo_sales']['to'];
        }

        $date_interval=prepare_mysql_dates($from,$to,'`Invoice Date`','only_dates');
        if ($date_interval['error']) {
            $date_interval['mysql']='';
        }

        $where=sprintf('where true %s ',$date_interval['mysql']);



       
        $sql="select `World Region Code`,count(*) as invoices,sum(`Invoice Total Amount`*`Invoice Currency Exchange` ) as sales from `Invoice Dimension`   left join kbase.`Country Dimension` on (`Invoice Billing Country 2 Alpha Code`=`Country 2 Alpha Code`)     $where group by  `World Region Code`";
        $res=mysql_query($sql);
        while ($row=mysql_fetch_array($res)) {
            //// if(array_key_exists($row['World Region Code'],$wregion_index))
            //     $index=$wregion_index[$row['World Region Code']];
            $wregion_data[$row['World Region Code']]['value']=floatval($row['sales']);
            $wregion_data[$row['World Region Code']]['name'].="\n"._('Sales').' '.money($row['sales']);
        }

        break;

    }
}







$sql=sprintf("select `World Region Code`,`Country 2 Alpha Code`,`Country Name`,`Country Code`,`World Region` from kbase.`Country Dimension` ");
$res=mysql_query($sql);
$countries_data=array();
while ($row=mysql_fetch_assoc($res)) {
    $tmp=array(
                          'code'=>$row['Country 2 Alpha Code'],
                          'url_code'=>$row['Country 2 Alpha Code'],
                          'title'=>$row['Country Name'],
                          'link'=>'',
                          'value'=>0,
                          'view'=>'country'
                      );
    if($row['World Region Code']!=$wregion_code){
    $tmp['color']='#EEEEEE';
    $tmp['color_hover']='#DDDDDD';
     $tmp['view']='wregion';
          $tmp['url_code']=$row['World Region Code'];
 $tmp['link']=$wregion_data[$row['World Region Code']]['countries'];
  $tmp['title']=$row['World Region'].' ('.$row['Country Code'].')';

}    
    $countries_data[]=$tmp;
}




$smarty->assign('map_data',$map_data);

$smarty->assign('countries_data',$countries_data);
$smarty->display('map_data_world_countries.tpl');

?>