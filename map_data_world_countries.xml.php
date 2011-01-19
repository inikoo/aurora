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

//'code'=>$row['Country 2 Alpha Code'],

$sql=sprintf("select `Country 2 Alpha Code`,`Country Name` from kbase.`Country Dimension`");
$res=mysql_query($sql);
$countries_data=array();
$country_index=array();
$i=0;
while ($row=mysql_fetch_assoc($res)) {
    $countries_data[]=array('code'=>$row['Country 2 Alpha Code'],'url_code'=>$row['Country 2 Alpha Code'],'title'=>$row['Country Name'],'value'=>0,'view'=>'country');
    $country_index[$row['Country 2 Alpha Code']]=$i;
    $i++;
}
$smarty->assign('map_data',$map_data);
$smarty->assign('view','country');



if (isset($_REQUEST['report'])) {
    $smarty->assign('with_values',true);

    $report=$_REQUEST['report'];
    switch ($report) {
    case('customer_total_contacts'):
        
        $where='';
        if(isset($_REQUEST['store_key'])){
        
        if(is_numeric($_REQUEST['store_key']))
        $where=sprintf('where `Customer Store Key`=%d',$_REQUEST['store_key']);
        
        }
        $sql="select count(*) as number,`Customer Main Country 2 Alpha Code` from `Customer Dimension`     $where group by  `Customer Main Country 2 Alpha Code`";
        //print $sql;
        $res=mysql_query($sql);
        while ($row=mysql_fetch_array($res)) {
            if (array_key_exists($row['Customer Main Country 2 Alpha Code'],$country_index))
                $index=$country_index[$row['Customer Main Country 2 Alpha Code']];
            $countries_data[$index]['value']=$row['number'];
            $countries_data[$index]['title'].="\n"._('Number of Contacts').': '.number($row['number']);
        }

        break;

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

        $sql="select `Invoice Billing Country 2 Alpha Code`,count(*) as invoices,sum(`Invoice Currency Exchange`*`Invoice Total Amount`) as sales from `Invoice Dimension`     $where group by  `Invoice Billing Country 2 Alpha Code`";
        $res=mysql_query($sql);
        while ($row=mysql_fetch_array($res)) {
            if (array_key_exists($row['Invoice Billing Country 2 Alpha Code'],$country_index))
                $index=$country_index[$row['Invoice Billing Country 2 Alpha Code']];
            $countries_data[$index]['value']=floatval($row['sales']);
            $countries_data[$index]['title'].="\n"._('Sales').' '.money($row['sales']);
        }

        break;

    }
}









$smarty->assign('countries_data',$countries_data);
$smarty->display('map_data_world_countries.tpl');

?>