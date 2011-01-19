<?php
include_once('common.php');

$map_data=array('map_file'=>"maps/world.swf",
'tl_long'=>"-168.49",
'tl_lat'=>"83.63",
'br_long'=>"190.3",
'br_lat'=>"-55.58",
'zoom_x'=>"0%",
'zoom_y'=>"0%",
'zoom'=>"100%");
$sql=sprintf("select `Continent Code`,`Continent`,group_concat(`Country 2 Alpha Code`) as countries from kbase.`Country Dimension` group by `Continent Code`");
$res=mysql_query($sql);
$continent_data=array();
while($row=mysql_fetch_assoc($res)){
$continent_data[$row['Continent Code']]=array('countries'=>$row['countries'],'name'=>$row['Continent'],'value'=>0);
}





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



       
        $sql="select `Continent Code`,count(*) as invoices,sum(`Invoice Total Amount`*`Invoice Currency Exchange` ) as sales from `Invoice Dimension`   left join kbase.`Country Dimension` on (`Invoice Billing Country 2 Alpha Code`=`Country 2 Alpha Code`)     $where group by  `Continent Code`";
        $res=mysql_query($sql);
        while ($row=mysql_fetch_array($res)) {
            //// if(array_key_exists($row['World Region Code'],$continent__index))
            //     $index=$continent__index[$row['World Region Code']];
            $continent_data[$row['Continent Code']]['value']=floatval($row['sales']);
            $continent_data[$row['Continent Code']]['name'].="\n"._('Sales').' '.money($row['sales']);
        }

        break;

    }
}










$sql=sprintf("select `Continent Code`,`Country 2 Alpha Code`,`Country Name` from kbase.`Country Dimension` ");
$res=mysql_query($sql);
$countries_data=array();
while($row=mysql_fetch_assoc($res)){
$countries_data[]=array(
    'code'=>$row['Country 2 Alpha Code'],
    'url_code'=>$row['Continent Code'],
    'title'=>$continent_data[$row['Continent Code']]['name'],
    'link'=>$continent_data[$row['Continent Code']]['countries'],
  'value'=>$continent_data[$row['Continent Code']]['value'],
    'view'=>'continent'
 );
}
$smarty->assign('map_data',$map_data);
$smarty->assign('view','continent');

$smarty->assign('countries_data',$countries_data);
$smarty->display('map_data_world_countries.tpl');

?>

