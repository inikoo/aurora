<?php
/*
 File: ar_assets.php

 Ajax Server Anchor for the Product,Family,Department and Part Clases

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once('common.php');
require_once 'class.ImportedRecords.php';

require_once 'ar_edit_common.php';
require_once 'common_import.php';



if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case 'change_map':
    $data=prepare_values($_REQUEST,array(
                             'index'=>array('type'=>'numeric'),
                             'scope'=>array('type'=>'string'),
							 'map_key'=>array('type'=>'string')
                         ));
	change_map($data);
	break;
case 'save_map':
	save_map();
	break;
case 'browse_maps':
/*
    $data=prepare_values($_REQUEST,array(
                             'scope'=>array('type'=>'string'),
                             'scope_key'=>array('type'=>'key')
                         ));
	*/
$data='';	
	browse_maps($data);
	break;
case('import_customer_csv_status'):
    import_customer_csv_status();
    break;
case('insert_data'):
    insert_data();
    break;
case('change_option'):
    $data=prepare_values($_REQUEST,array(
                             'key'=>array('type'=>'numeric'),
                             'option_key'=>array('type'=>'numeric')
                         ));
    change_option($data);
    break;
case('get_record_data'):


    $data=prepare_values($_REQUEST,array(
                             'index'=>array('type'=>'numeric'),
                             'scope'=>array('type'=>'string')
                         ));
    get_record_data($data);
    break;
case('get_external_data'):


    $data=prepare_values($_REQUEST,array(
                             'index'=>array('type'=>'numeric'),
                             'scope'=>array('type'=>'string')
                         ));
    get_external_data($data);
    break;
case('ignore_record'):
    $data=prepare_values($_REQUEST,array('index'=>array('type'=>'numeric')));
    ignore_record($data);
    break;
case('read_record'):
    $data=prepare_values($_REQUEST,array('index'=>array('type'=>'numeric')));
    read_record($data);
    break;

default:

    $response=array('state'=>404,'msg'=>_('Operation not found'));
    echo json_encode($response);

}

function change_option($data) {
    $_SESSION['state']['import']['map'][$data['key']]=$data['option_key'];
}

function ignore_record($data) {
    $_SESSION['state']['import']['records_ignored_by_user'][$data['index']]=1;

    $imported_records=new ImportedRecords($_SESSION['state']['import']['key']);
    $imported_records->update(array('Ignored Records'=>count($_SESSION['state']['import']['records_ignored_by_user'])));


    $response=array('state'=>200,'index'=>$data['index']);
    echo json_encode($response);
}

function read_record($data) {
    unset($_SESSION['state']['import']['records_ignored_by_user'][$data['index']]);

    $imported_records=new ImportedRecords($_SESSION['state']['import']['key']);
    $imported_records->update(array('Ignored Records'=>count($_SESSION['state']['import']['records_ignored_by_user'])));

    $response=array('state'=>200,'index'=>$data['index']);
    echo json_encode($response);
}


function get_record_data($data) {
    $index=$data['index'];

    $records_ignored_by_user = $_SESSION['state']['import']['records_ignored_by_user'];

    require_once 'csvparser.php';
    $csv = new CSV_PARSER;

    if (isset($_SESSION['state']['import']['file_path'])) {
        $csv->load($_SESSION['state']['import']['file_path']);
    }

    //extracting the HEADERS
    $headers = $csv->getHeaders();
	//print_r($_SESSION['state']['import']['options_labels']);
    $number_of_records = $csv->countRows();
    $ignore_record = array_key_exists($index,$records_ignored_by_user);
    $raw = $csv->getrawArray();

	//print_r($raw);
	
    $options=$_SESSION['state']['import']['todo']=$number_of_records+1;


    $result="<table class='recordList' border=0  >
            <tr>
            <th class='list-column-left' style='text-align: left; width: 300px;'>"._('Assigned Field')."</th>
            <th class='list-column-left' style='text-align: left; width: 300px;'>"._('Record').' '.($index+1).' '._('of').' '.($number_of_records+1).' <span id="ignore_record_label" style="color:red;'.($ignore_record?'':'display:none').'">('._('Ignored').')</th>'."
            <th style='width:100px'>";
    $result.="<span style='cursor:pointer;".($index > 0?'':'visibility:hidden')."' class='subtext' id='prev' onclick='get_record_data(".($index-1).")'>"._('Previous')."</span>";

    $result.="<span class='subtext' style=".($index > 0?'':'visibility:hidden')."> | </span>";
    $result.="<span  style='cursor:pointer;".($index < $number_of_records?'':'visibility:hidden')."'  class='subtext' id='next' onclick='get_record_data(".($index+1).")'>"._('Next')."</span>";
    $result.="</th><th style='width:100px'>";
    $result.=sprintf('<span style="cursor:pointer;%s" onclick="ignore_record(%d)" id="ignore" class="subtext">%s</span>',(!$ignore_record?'':'display:none'),$index,_('Ignore Record'));
    $result.=sprintf('<span style="cursor:pointer;%s" onclick="read_record(%d)" id="unignore" class="subtext">%s</span>',($ignore_record?'':'display:none'),$index,_('Read Record'));
    $result.='</th></tr>';

	$i=0;
    foreach($headers as $key=>$value) {

        $select='<select id="select'.$i.'" onChange="option_changed(this.options[this.selectedIndex].value,this.selectedIndex)">';
$i++;
        foreach($_SESSION['state']['import']['options_labels'] as $option_key=>$option_label) {

            $selected='';
            if ($_SESSION['state']['import']['map'][$key]==$option_key)
                $selected='selected="selected"';

            $select.=sprintf('<option %s value="%d"   >%s</option>',$selected,$key,$option_label);

        }
        $select.='</select>';
        $text= $raw[$index][$key];
        $newtext = wordwrap($text, 50, "<br />\n");
        $result.=sprintf('<tr style="height:20px;border-top:1px solid #ccc"><td>%s</td><td colspan="3" >%s</td></tr>',$select,$newtext);
    }


    $result.='</table>';

	//print $result;
	
    $response=array('state'=>200,'result'=>$result);
    echo json_encode($response);
    exit;

}


function insert_data() {


    switch ($_SESSION['state']['import']['scope']) {
    case('customers_store'):
        insert_customers_from_csv();
    }
}










function insert_customers_from_csv() {
    global $editor;




//    if ($_SESSION['state']['import']['in_progress'])
//       return;
    include_once('class.Customer.php');
    include_once('edit_customers_functions.php');


    $imported_records=new ImportedRecords($_SESSION['state']['import']['key']);

    $imported_records->update(array('Imported Records Start Date'=>date('Y-m-d H:i:s')));
    //$_SESSION['state']['import']['in_progress']=1;

    $store_key=$imported_records->data['Imported Records Scope Key'];
    $customer_list_key=0;


    $records_ignored_by_user = $_SESSION['state']['import']['records_ignored_by_user'];
    $map = $_SESSION['state']['import']['map'];
//   $options = $_SESSION['state']['import']['options'];
    require_once 'csvparser.php';
    $csv = new CSV_PARSER;
    if (isset($_SESSION['state']['import']['file_path'])) {
        $csv->load($_SESSION['state']['import']['file_path']);
    }
    $headers = $csv->getHeaders();
    $number_of_records = $csv->countRows();

    $data_to_import=array();

    $raw = $csv->getrawArray();

    foreach($raw as $record_key=>$record_data) {
        if (array_key_exists($record_key,$records_ignored_by_user)) {
            $record_data[]='Ignored';

            $cvs_line=array_to_CSV($record_data);
            $imported_records->append_not_imported_log($cvs_line);

            $imported_records->update(
                array(
                    'Imported Records'=>((float) $imported_records->data['Imported Records']+1),
                ));
            continue;

        }


        $parsed_record_data=array('csv_key'=>$record_key);
        foreach($record_data as $field_key=>$field) {
            //$field['csv_key']=$field_key;
            $mapped_field_key=$map[$field_key];

            if ($mapped_field_key)
                $parsed_record_data[$_SESSION['state']['import']['options_db_fields'][$mapped_field_key]]=$field;
        }
        $data_to_import[]=$parsed_record_data;
    }

    $_SESSION['state']['import']['todo']=count($data_to_import);
//print_r($data_to_import);
//print_r($_SESSION['state']['import'][]);



    foreach($data_to_import as $_customer_data) {


        $customer_data=array(
                           'Customer Company Name'=>'',
                           'Customer Main Contact Name'=>'',
                           'Customer Type'=>'',
                           'Customer Store Key'=>$store_key,
                           'Customer Address Line 1'=>'',
                           'Customer Address Line 2'=>'',
                           'Customer Address Line 3'=>'',
                           'Customer Address Postal Code'=>'',
                           'Customer Address Country Name'=>'',
                           'Customer Address Country Code'=>'',
                           'Customer Address Country 2 Alpha Code'=>'',
                           'Customer Address Country First Division'=>'',
                           'Customer Address Country Second Division'=>'',
                           'Customer Send Newsletter'=>'Yes',
                           'Customer Send Email Marketing'=>'Yes',
                           'Customer Send Postal Marketing'=>'Yes',
                           'editor'=>$editor
                       );


//print_r($_customer_data);
        foreach($_customer_data as $key=>$value) {
            $customer_data[$key]=$value;
        }


        if ($customer_data['Customer Main Contact Name']=='' and $customer_data['Customer Company Name']=='') {


            $imported_records->update(
                array(
                    'Error Records'=>( (float) $imported_records->data['Error Records']+1),
                ));


            $_record_data=$csv->getRow($_customer_data['csv_key']-1);
            $_record_data[]='No Company/Contact name';



            $cvs_line=array_to_CSV($_record_data);
            $imported_records->append_not_imported_log($cvs_line);



            continue;
        }

//        print_r($customer_data);
        if (  !( $customer_data['Customer Type']=='Person' or  $customer_data['Customer Type']=='Company')    ) {
            list($customer_data['Customer Type'] ,$customer_data['Customer Company Name'],$customer_data['Customer Main Contact Name'])=parse_company_person($customer_data['Customer Company Name'],$customer_data['Customer Main Contact Name']);
        }

        if ($customer_data['Customer Type']=='Company')
            $customer_data['Customer Name']=$customer_data['Customer Company Name'];
        else
            $customer_data['Customer Name']=$customer_data['Customer Main Contact Name'];

   


        if ($customer_data['Customer Address Country 2 Alpha Code']!='') {
            $country=new Country('2alpha',$customer_data['Customer Address Country 2 Alpha Code']);
            $customer_data['Customer Address Country Code']=$country->data['Country Code'];
            unset($country);
        }
        elseif($customer_data['Customer Address Country Code']!='') {
            $country=new Country('code',$customer_data['Customer Address Country Code']);
            $customer_data['Customer Address Country Code']=$country->data['Country Code'];
            unset($country);
        }
        elseif($customer_data['Customer Address Country Name']!='') {
            $country=new Country('code',$customer_data['Customer Address Country Name']);
            $customer_data['Customer Address Country Code']=$country->data['Country Code'];
            unset($country);
        }
        else {
            $customer_data['Customer Address Country Code']='UNK';
        }

//exit;

   
 
        $customer=new Customer('find complete',$customer_data);

        // print_r($customer_data);

//print_r($customer);
        if (!$customer->found) {




            $response=add_customer($customer_data) ;
            //   print_r($response);


            if ($response['state']==200 and $response['action']=='created') {

                if (!$customer_list_key) {
                    $customer_list_key=new_imported_csv_customers_list($store_key);

$imported_records->update(
                    array(
                        'Scope List Key'=>$customer_list_key,
                    ));


                }

                $sql=sprintf("insert into `List Customer Bridge` (`List Key`,`Customer Key`) values (%d,%d)",
                             $customer_list_key,
                             $response['customer_key']
                            );
                mysql_query($sql);

                $imported_records->update(
                    array(
                        'Imported Records'=>( (float) $imported_records->data['Imported Records']+1),
                    ));



            } else {

                $imported_records->update(
                    array(
                        'Error Records'=>( (float) $imported_records->data['Error Records']+1),
                    ));


                $_record_data=$csv->getRow($_customer_data['csv_key']-1);
                $_record_data[]='Can not add to the DB';

                $cvs_line=array_to_CSV($_record_data);
                $imported_records->append_not_imported_log($cvs_line);


            }


        } else {



            $imported_records->update(
                array(
                    'Error Records'=>( (float) $imported_records->data['Error Records']+1),
                ));


            $_record_data=$csv->getRow($_customer_data['csv_key']-1);
            $_record_data[]='Already in DB';;

            $cvs_line=array_to_CSV($_record_data);
            $imported_records->append_not_imported_log($cvs_line);


        }
        unset($customer);
//exit;
    }


    $imported_records->update(array('Imported Records Finish Date'=>date('Y-m-d H:i:s')));



    

}

function import_customer_csv_status() {


    $imported_records=new ImportedRecords($_SESSION['state']['import']['key']);


    $data=array(
              'todo'=>array('number'=>$imported_records->get('To do'),'comments'=>''),
              'done'=>array('number'=>$imported_records->get('Imported'),'comments'=>$imported_records->get_scope_list_link()),
              'error'=>array('number'=>$imported_records->get('Errors'),'comments'=>$imported_records->get_not_imported_log_link()),
              'ignored'=>array('number'=>$imported_records->get('Ignored'),'comments'=>$imported_records->get_ignored_log_link())

          );
    $response= array('state'=>200,'data'=>$data);
    echo json_encode($response);
}

function save_map(){
	$map_name=$_REQUEST['name'];
	$scope=$_REQUEST['scope'];
	$scope_key=$_REQUEST['scope_key'];
	$meta_data=$_REQUEST['meta_data'];
	/*
	print $map_name;
	print $scope;
	print $scope_key;
	print $meta_data;
	*/
	
	$sql=sprintf("select `Map Name` from `Import CSV Map` where `Map Name`='%s'", $map_name);
	$result=mysql_query($sql);
	if(!mysql_fetch_array($result)){
		$sql=sprintf("insert into `Import CSV Map` (`Store Key`,`Scope`,`Map Name`,`Meta Data`) values ('%d','%s','%s','%s')", $scope_key, $scope, $map_name, $meta_data);
		//print $sql;
		mysql_query($sql);
	}
	else{
		print 'blah';
	}
	
	
	$response= array('state'=>200,'data'=>'hello');
    echo json_encode($response);
}

function browse_maps($data){

/*
	$map_data='<table style="margin:10px">';
	$sql=sprintf("select * from `Import CSV Map` where `Store Key`=%d and `Scope`='%s'", $data['scope_key'], $data['scope']);
	
	$result=mysql_query($sql);
	while($row=mysql_fetch_array($result)){
		$map_data.='<tr><td><span  style="margin:0 10px" class="unselectable_text state_details" onClick="new_customer()" value="'.$row['Meta Data'].'" >'.$row['Map Name'].'</span></td></tr>';
	}
	
	$map_data.="</table>";
	
	//print $map_data;
	
    $response=array('state'=>200,'map_data'=>$map_data);
    echo json_encode($response);
    exit;
		
*/
    global $user;
    if (isset( $_REQUEST['scope']))$scope=$_REQUEST['scope'];
    else $scope='customers_store';
    if (isset( $_REQUEST['sf']))$start_from=$_REQUEST['sf'];
    else $start_from=0;
    if (isset( $_REQUEST['nr']))$number_results=$_REQUEST['nr'];
    else $number_results=20;
    if (isset( $_REQUEST['o'])) $order=$_REQUEST['o'];
    else$order='code';
    if (isset( $_REQUEST['od']))$order_dir=$_REQUEST['od'];
    else$order_dir='';
    if (isset( $_REQUEST['f_field']))$f_field=$_REQUEST['f_field'];
    else$f_field='code';
    if (isset( $_REQUEST['f_value']))$f_value=$_REQUEST['f_value'];
    else$f_value='';
    if (isset( $_REQUEST['tableid']))$tableid=$_REQUEST['tableid'];
    else$tableid=0;

    if (isset( $_REQUEST['store_key']))$store_key=$_REQUEST['store_key'];
    else$store_key='';

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';



    if (!in_array($store_key,$user->stores)) {
        $where=sprintf('where false ');
    } else {
        $where=sprintf('where `Store Key`=%d',$store_key);
    }




    $filter_msg='';
    $wheref='';


    if ($f_field=='code' and $f_value!='')
        $wheref.=" and  `Product Department Code` like '".addslashes($f_value)."%'";
    elseif($f_field=='name' and $f_value!='')
    $wheref.=" and  `Product Department Name` like '".addslashes($f_value)."%'";


    $sql="select count(DISTINCT `Map Name`) as total from `Import CSV Map` $where $wheref  ";

	//print $sql;
	
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($res);
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(DISTINCT `Map Name`) as total from `Import CSV Map`  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($res);
    }


    $rtext=$total_records." ".ngettext('Map','Maps',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=_('(Showing all)');


    $filter_msg='';

    switch ($f_field) {
    case('code'):
        if ($total==0 and $filtered>0)
            $filter_msg=_("There isn't any maps with code")." <b>".$f_value."*</b> ";
        elseif($filtered>0)
        $filter_msg=_('Showing')." $total ("._('maps with code like')." <b>$f_value</b>)";
        break;
    case('name'):
        if ($total==0 and $filtered>0)
            $filter_msg=_("There isn't any map with name")." <b>".$f_value."*</b> ";
        elseif($filtered>0)
        $filter_msg=_('Showing')." $total ("._('maps with name like')." <b>$f_value</b>)";
        break;

    }





    $_order=$order;
    $_dir=$order_direction;



    if ($order=='name')
        $order='`Map Key`';
    else
        $order='`Map Key`';





    $adata=array();
    $sql="select  `Map Name`,`Meta Data` from `Import CSV Map` $where $wheref  order by $order $order_direction  limit $start_from,$number_results;";

	//print $sql;
    $res=mysql_query($sql);

    while ($row=mysql_fetch_array($res)) {
		unset($data);
		$data=explode(",", $row['Meta Data']);
		$map='';
		foreach($data as $key=>$val){
		//print_r($_SESSION['state']['import']['options_labels'][$val]);
		$map.=$_SESSION['state']['import']['options_labels'][$val].' ';
		}
        $adata[]=array(

                     'name'=>$map,
                     'code'=>$row['Map Name'],


                 );

    }
    mysql_free_result($res);

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,
                                      'records_returned'=>$total,
                                      'records_perpage'=>$number_results,
                                      // 'records_text'=>$rtext,
                                      // 'records_order'=>$order,
                                      // 'records_order_dir'=>$order_dir,
                                      // 'filtered'=>$filtered,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp
                                     )
                   );

    echo json_encode($response);
		
	
}

function change_map($data){
	//print $data['map_key'];

	$sql=sprintf("select `Meta Data` from `Import CSV Map` where `Map Name`='%s'", $data['map_key']);
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	
	$meta_data=explode(",", $row['Meta Data']);
	//print_r($meta_data);
	$changed_options=array();
	$i=0;
	foreach($meta_data as $key=>$value){
		if($_SESSION['state']['import']['map'][$key]!=$value){
		$_SESSION['state']['import']['map'][$key]=$value;
		$changed_options[$i]=$value;
		}
	$i++;
	}
	
	//print_r($_SESSION['state']['import']['map']);
	//$response=array('state'=>200,'changes'=>$changed_options);
	//   echo json_encode($response);
	get_record_data($data);
}


function get_external_data($data) {
    $index=$data['index'];

    $records_ignored_by_user = $_SESSION['state']['import']['records_ignored_by_user'];

    //require_once 'csvparser.php';
    //$csv = new CSV_PARSER;

   // if (isset($_SESSION['state']['import']['file_path'])) {
    //    $csv->load($_SESSION['state']['import']['file_path']);
    //}

    //extracting the HEADERS
	$sql=sprintf("select `Record` from `External Records` where `Store Key`=%d and `Scope`='%s' and `Read Status`='No'", $_SESSION['state']['import']['scope_key'], $_SESSION['state']['import']['scope']);
	//print $sql;

	$result=mysql_query($sql);

	$row = mysql_fetch_array($result);


	$headers = explode('#', $row[0]);
	
    //$headers = $csv->getHeaders();
	//print_r($_SESSION['state']['import']['options_labels']);
    $number_of_records = mysql_num_rows($result);
    $ignore_record = array_key_exists($index,$records_ignored_by_user);
    
	$raw=array();
	
	$result=mysql_query($sql);
	while($row=mysql_fetch_array($result)){
		$data = explode('#', $row[0]);
		foreach($data as $key=>$value)
			$temp[$key]=preg_replace('/"/', '', $value);
			
		$raw[]=$temp;
		unset($temp);
	}
	
	//print_r($raw);


    $options=$_SESSION['state']['import']['todo']=$number_of_records;


    $result="<table class='recordList' border=0  >
            <tr>
            <th class='list-column-left' style='text-align: left; width: 300px;'>"._('Assigned Field')."</th>
            <th class='list-column-left' style='text-align: left; width: 300px;'>"._('Record').' '.($index+1).' '._('of').' '.($number_of_records).' <span id="ignore_record_label" style="color:red;'.($ignore_record?'':'display:none').'">('._('Ignored').')</th>'."
            <th style='width:100px'>";
    $result.="<span style='cursor:pointer;".($index > 0?'':'visibility:hidden')."' class='subtext' id='prev' onclick='get_record_data(".($index-1).")'>"._('Previous')."</span>";

    $result.="<span class='subtext' style=".($index > 0?'':'visibility:hidden')."> | </span>";
    $result.="<span  style='cursor:pointer;".($index < $number_of_records?'':'visibility:hidden')."'  class='subtext' id='next' onclick='get_record_data(".($index+1).")'>"._('Next')."</span>";
    $result.="</th><th style='width:100px'>";
    $result.=sprintf('<span style="cursor:pointer;%s" onclick="ignore_record(%d)" id="ignore" class="subtext">%s</span>',(!$ignore_record?'':'display:none'),$index,_('Ignore Record'));
    $result.=sprintf('<span style="cursor:pointer;%s" onclick="read_record(%d)" id="unignore" class="subtext">%s</span>',($ignore_record?'':'display:none'),$index,_('Read Record'));
    $result.='</th></tr>';

	$i=0;
    foreach($headers as $key=>$value) {

        $select='<select id="select'.$i.'" onChange="option_changed(this.options[this.selectedIndex].value,this.selectedIndex)">';
$i++;
        foreach($_SESSION['state']['import']['options_labels'] as $option_key=>$option_label) {

            $selected='';
            if ($_SESSION['state']['import']['map'][$key]==$option_key)
                $selected='selected="selected"';

            $select.=sprintf('<option %s value="%d"   >%s</option>',$selected,$key,$option_label);

        }
        $select.='</select>';
        $text= $raw[$index][$key];
        $newtext = wordwrap($text, 50, "<br />\n");
        $result.=sprintf('<tr style="height:20px;border-top:1px solid #ccc"><td>%s</td><td colspan="3" >%s</td></tr>',$select,$newtext);
    }


    $result.='</table>';

	//print $result;
	
    $response=array('state'=>200,'result'=>$result);
    echo json_encode($response);
    exit;

}
?>
