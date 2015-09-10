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







$sql=sprintf("select `World Region Code`,`Country 2 Alpha Code`,`Country Name` from kbase.`Country Dimension` ");
$res=mysql_query($sql);
$countries_data=array();
while ($row=mysql_fetch_assoc($res)) {
    $countries_data[]=array(
                          'code'=>$row['Country 2 Alpha Code'],
                          'url_code'=>$row['World Region Code'],
                          'title'=>$wregion_data[$row['World Region Code']]['name'],
                          'link'=>$wregion_data[$row['World Region Code']]['countries'],
                          'value'=>$wregion_data[$row['World Region Code']]['value'],
                          'view'=>'wregion'
                      );
}




$smarty->assign('map_data',$map_data);
$smarty->assign('view','wregion');

$smarty->assign('countries_data',$countries_data);
$smarty->display('map_data_world_countries.tpl');

?>