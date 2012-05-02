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

case('delete_map'):
    $data=prepare_values($_REQUEST,array(
                             'map_key'=>array('type'=>'key'),

                         ));
    delete_map($data);
    break;
case 'change_map':
    $data=prepare_values($_REQUEST,array(

                             'map_key'=>array('type'=>'key')
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
//print_r($headers);
    //print_r($_SESSION['state']['import']['options_labels']);
    $number_of_records = $csv->countRows();
    $ignore_record = array_key_exists($index,$records_ignored_by_user);
    $raw = $csv->getrawArray();

    //print_r($raw);

    $options=$_SESSION['state']['import']['todo']=$number_of_records+1;


    $result="<table class='recordList' border=0  >
            <tr>
            <th class='list-column-left' style='text-align: left; width: 300px;'>"._('Field')."</th>
            <th class='list-column-left' style='text-align: left; width: 200px;'>"._('Record').' '.($index+1).' '._('of').' '.($number_of_records+1).' <span id="ignore_record_label" style="color:red;'.($ignore_record?'':'display:none').'">('._('Ignored').')</th>';
    $result.="<th style='width:150px'><div class='buttons'>";
    $result.=sprintf('<button style="cursor:pointer;%s" onclick="ignore_record(%d)" id="ignore" class="subtext">%s</button>',(!$ignore_record?'':'display:none'),$index,_('Ignore Record'));
    $result.=sprintf('<button style="cursor:pointer;%s" onclick="read_record(%d)" id="unignore" class="subtext">%s</button>',($ignore_record?'':'display:none'),$index,_('Read Record'));
    $result.='</div></th>';


    $result.="<th style='width:150px'><div class='buttons'>";
    $result.="<button  style='cursor:pointer;".($index < $number_of_records?'':'visibility:hidden')."'   id='next' onclick='get_record_data(".($index+1).")'>"._('Next')."</button>";
    $result.="<button style='".($index > 0?'':'visibility:hidden')."'  id='prev' onclick='get_record_data(".($index-1).")'>"._('Previous')."</button>";
    $result.="</div></th>";

    $result.='</tr>';
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
    case('family'):
	insert_products_from_csv();
    case('department'):
	insert_family_from_csv();
    case('store'):
	insert_department_from_csv();
    }
    
	
}

function insert_department_from_csv(){
    global $editor;
    include_once('class.Department.php');
    $imported_records=new ImportedRecords($_SESSION['state']['import']['key']);

    $imported_records->update(array('Imported Records Start Date'=>date('Y-m-d H:i:s')));
    //$_SESSION['state']['import']['in_progress']=1;

    $store_key=$imported_records->data['Imported Records Scope Key'];
    $customer_list_key=0;
	



    $records_ignored_by_user = $_SESSION['state']['import']['records_ignored_by_user'];
    $map = $_SESSION['state']['import']['map'];

    require_once 'csvparser.php';
    $data_to_import=array();
    if ($_SESSION['state']['import']['type']) {
        $sql=sprintf("select `Record` from `External Records` where `Store Key`=%d and `Scope`='%s' and `Read Status`='No'", $_SESSION['state']['import']['scope_key'], $_SESSION['state']['import']['scope']);
        //print $sql;

        $result=mysql_query($sql);

        $row = mysql_fetch_array($result);
        //$record_id=$row[1];
        //print $record_id;exit;
        $headers = explode('#', $row[0]);
        $number_of_records = mysql_num_rows($result);

        $raw=array();

        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result)) {
            $data = explode('#', $row[0]);
            foreach($data as $key=>$value)
            $temp[$key]=preg_replace('/"/', '', $value);

            $raw[]=$temp;
            unset($temp);
        }

    } else {
        $csv = new CSV_PARSER;

        if (isset($_SESSION['state']['import']['file_path'])) {
            $csv->load($_SESSION['state']['import']['file_path']);
        }
        $headers = $csv->getHeaders();
        $number_of_records = $csv->countRows();



        $raw = $csv->getrawArray();
    }

//print_r($raw);
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
		//print $mapped_field_key;
		//print_r($_SESSION['state']['import']['options_db_fields']);exit;
            if ($mapped_field_key){
                $parsed_record_data[$_SESSION['state']['import']['options_db_fields'][$mapped_field_key]]=$field;
		//print_r($_SESSION['state']['import']['options_db_fields'][$mapped_field_key]);exit;
	    }
        }
        $data_to_import[]=$parsed_record_data;
    }

    $_SESSION['state']['import']['todo']=count($data_to_import);

//print_r($data_to_import);exit;


    foreach($data_to_import as $_department_data) {


        $sql=sprintf("select `External Record Key` from `External Records` where `Store Key`=%d and `Scope`='%s' and `Read Status`='No'", $_SESSION['state']['import']['scope_key'], $_SESSION['state']['import']['scope']);
        //print $sql;

        $result=mysql_query($sql);

        $row = mysql_fetch_array($result);
        $record_id=$row[0];





		$department_data=array(
				'Product Department Code'=>''
				,'Product Department Name'=>''
				,'Product Department Store Key'=>$store_key
				,'editor'=>$editor
				);

//print_r($_customer_data);
        foreach($_department_data as $key=>$value) {
            $department_data[$key]=$value;
        }

	$department=new Department('find', $department_data, 'create');
//print_r($department);exit;
        if ($department->new) {


/*
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
*/
                $imported_records->update(
                    array(
                        'Imported Records'=>( (float) $imported_records->data['Imported Records']+1),
                    ));

                //Update Read Status

                $sql=sprintf("update `External Records` set `Read Status`='Yes' where `External Record Key`=%d", $record_id);
                //print $sql;
                mysql_query($sql);





        } else {



            $imported_records->update(
                array(
                    'Error Records'=>( (float) $imported_records->data['Error Records']+1),
                ));

            if ($_SESSION['state']['import']['type']) {
                $_record_data=$raw[0];
            } else {
                $_record_data=$csv->getRow($_department_data['csv_key']-1);
            }
            //print_r( $_record_data);exit;
            $_record_data[]='Already in DB';

            $cvs_line=array_to_CSV($_record_data);
            $imported_records->append_not_imported_log($cvs_line);

            //print_r($imported_records);


        }
        unset($department);
//exit;
    }


    $imported_records->update(array('Imported Records Finish Date'=>date('Y-m-d H:i:s')));





}


function insert_family_from_csv(){
    global $editor;

    include_once('class.Department.php');


    $imported_records=new ImportedRecords($_SESSION['state']['import']['key']);

    $imported_records->update(array('Imported Records Start Date'=>date('Y-m-d H:i:s')));
    //$_SESSION['state']['import']['in_progress']=1;

    $department_key=$imported_records->data['Imported Records Scope Key'];
    $customer_list_key=0;
    $department = new Department($department_key);	



    $records_ignored_by_user = $_SESSION['state']['import']['records_ignored_by_user'];
    $map = $_SESSION['state']['import']['map'];

    require_once 'csvparser.php';
    $data_to_import=array();
    if ($_SESSION['state']['import']['type']) {
        $sql=sprintf("select `Record` from `External Records` where `Store Key`=%d and `Scope`='%s' and `Read Status`='No'", $_SESSION['state']['import']['scope_key'], $_SESSION['state']['import']['scope']);
        //print $sql;

        $result=mysql_query($sql);

        $row = mysql_fetch_array($result);
        //$record_id=$row[1];
        //print $record_id;exit;
        $headers = explode('#', $row[0]);
        $number_of_records = mysql_num_rows($result);

        $raw=array();

        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result)) {
            $data = explode('#', $row[0]);
            foreach($data as $key=>$value)
            $temp[$key]=preg_replace('/"/', '', $value);

            $raw[]=$temp;
            unset($temp);
        }

    } else {
        $csv = new CSV_PARSER;

        if (isset($_SESSION['state']['import']['file_path'])) {
            $csv->load($_SESSION['state']['import']['file_path']);
        }
        $headers = $csv->getHeaders();
        $number_of_records = $csv->countRows();



        $raw = $csv->getrawArray();
    }

//print_r($raw);
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
		//print $mapped_field_key;
		//print_r($_SESSION['state']['import']['options_db_fields']);exit;
            if ($mapped_field_key){
                $parsed_record_data[$_SESSION['state']['import']['options_db_fields'][$mapped_field_key]]=$field;
		//print_r($_SESSION['state']['import']['options_db_fields'][$mapped_field_key]);exit;
	    }
        }
        $data_to_import[]=$parsed_record_data;
    }

    $_SESSION['state']['import']['todo']=count($data_to_import);

//print_r($data_to_import);exit;


    foreach($data_to_import as $_family_data) {


        $sql=sprintf("select `External Record Key` from `External Records` where `Store Key`=%d and `Scope`='%s' and `Read Status`='No'", $_SESSION['state']['import']['scope_key'], $_SESSION['state']['import']['scope']);
        //print $sql;

        $result=mysql_query($sql);

        $row = mysql_fetch_array($result);
        $record_id=$row[0];





		$family_data=array(

				'Product Family Code'=>'',
				'Product Family Name'=>'',
				'Product Family Description'=>'',
				'Product Family Special Characteristic'=>'',
				'Product Family Main Department Key'=>$department->id,
				'Product Family Store Key'=>$department->data['Product Department Store Key'],
				'editor'=>$editor
			);




//print_r($_customer_data);
        foreach($_family_data as $key=>$value) {
            $family_data[$key]=$value;
        }

	if($family_data['Product Family Special Characteristic']==''){
		$family_data['Product Special Characteristic']=$family_data['Product Family Name'];
	}
		
	$family=new Family('create', $family_data);

        if ($family->new) {


/*
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
*/
                $imported_records->update(
                    array(
                        'Imported Records'=>( (float) $imported_records->data['Imported Records']+1),
                    ));

                //Update Read Status

                $sql=sprintf("update `External Records` set `Read Status`='Yes' where `External Record Key`=%d", $record_id);
                //print $sql;
                mysql_query($sql);





        } else {



            $imported_records->update(
                array(
                    'Error Records'=>( (float) $imported_records->data['Error Records']+1),
                ));

            if ($_SESSION['state']['import']['type']) {
                $_record_data=$raw[0];
            } else {
                $_record_data=$csv->getRow($_family_data['csv_key']-1);
            }
            //print_r( $_record_data);exit;
            $_record_data[]='Already in DB';

            $cvs_line=array_to_CSV($_record_data);
            $imported_records->append_not_imported_log($cvs_line);

            //print_r($imported_records);


        }
        unset($family);
//exit;
    }


    $imported_records->update(array('Imported Records Finish Date'=>date('Y-m-d H:i:s')));





}




function insert_products_from_csv(){
    global $editor;

    include_once('class.Product.php');
    include_once('class.Family.php');

    $imported_records=new ImportedRecords($_SESSION['state']['import']['key']);

    $imported_records->update(array('Imported Records Start Date'=>date('Y-m-d H:i:s')));
    //$_SESSION['state']['import']['in_progress']=1;

    $family_key=$imported_records->data['Imported Records Scope Key'];
    $customer_list_key=0;
    $family = new Family($family_key);	

    $store = new Store($family->data['Product Family Store Key']);

    $records_ignored_by_user = $_SESSION['state']['import']['records_ignored_by_user'];
    $map = $_SESSION['state']['import']['map'];

    require_once 'csvparser.php';
    $data_to_import=array();
    if ($_SESSION['state']['import']['type']) {
        $sql=sprintf("select `Record` from `External Records` where `Store Key`=%d and `Scope`='%s' and `Read Status`='No'", $_SESSION['state']['import']['scope_key'], $_SESSION['state']['import']['scope']);
        //print $sql;

        $result=mysql_query($sql);

        $row = mysql_fetch_array($result);
        //$record_id=$row[1];
        //print $record_id;exit;
        $headers = explode('#', $row[0]);
        $number_of_records = mysql_num_rows($result);

        $raw=array();

        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result)) {
            $data = explode('#', $row[0]);
            foreach($data as $key=>$value)
            $temp[$key]=preg_replace('/"/', '', $value);

            $raw[]=$temp;
            unset($temp);
        }

    } else {
        $csv = new CSV_PARSER;

        if (isset($_SESSION['state']['import']['file_path'])) {
            $csv->load($_SESSION['state']['import']['file_path']);
        }
        $headers = $csv->getHeaders();
        $number_of_records = $csv->countRows();



        $raw = $csv->getrawArray();
    }

//print_r($raw);
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
		//print $mapped_field_key;
		//print_r($_SESSION['state']['import']['options_db_fields']);exit;
            if ($mapped_field_key){
                $parsed_record_data[$_SESSION['state']['import']['options_db_fields'][$mapped_field_key]]=$field;
		//print_r($_SESSION['state']['import']['options_db_fields'][$mapped_field_key]);exit;
	    }
        }
        $data_to_import[]=$parsed_record_data;
    }

    $_SESSION['state']['import']['todo']=count($data_to_import);

//print_r($data_to_import);exit;
print $_SESSION['state']['import']['todo']

    foreach($data_to_import as $_product_data) {


        $sql=sprintf("select `External Record Key` from `External Records` where `Store Key`=%d and `Scope`='%s' and `Read Status`='No'", $_SESSION['state']['import']['scope_key'], $_SESSION['state']['import']['scope']);
        //print $sql;

        $result=mysql_query($sql);

        $row = mysql_fetch_array($result);
        $record_id=$row[0];

        $product_data=array(
				'Product Stage'=>'Normal',
				'Product Sales Type'=>'Public Sale',
				'Product Type'=>'Normal',
				'Product Stage'=>'Normal',
				'Product Record Type'=>'Normal',
				'Product Web Configuration'=>'Online Auto',
				'Product Store Key'=>$store->id,
				'Product Currency'=>$store->data['Store Currency Code'],
				'Product Locale'=>$store->data['Store Locale'],
				'Product Price'=>'',
				'Product RRP'=>'',
				'Product Units Per Case'=>'',
				'Product Family Key'=>$family->id,

				'Product Valid From'=>$editor['Date'],
				'Product Valid To'=>$editor['Date'],
				'Product Code'=>'',
				'Product Name'=>'',
				'Product Description'=>'',
				'Product Special Characteristic'=>'',
				'Product Main Department Key'=>$family->data['Product Family Main Department Key'],
				'editor'=>$editor,
				'Product Net Weight'=>'',
				'Product Gross Weight'=>'',
			);


//print_r($_customer_data);
        foreach($_product_data as $key=>$value) {
            $product_data[$key]=$value;
        }

	if($product_data['Product Special Characteristic']==''){
		$product_data['Product Special Characteristic']=$product_data['Product Name'];
	}
		

	if(!is_numeric($product_data['Product Price'])){
            $imported_records->update(
                array(
                    'Error Records'=>( (float) $imported_records->data['Error Records']+1),
                ));

            if ($_SESSION['state']['import']['type']) {
                $_record_data=$raw[0];
            } else {
                $_record_data=$csv->getRow($_product_data['csv_key']-1);
            }
            //print_r( $_record_data);exit;
            $_record_data[]='Invalid Price, cannot insert into DB';

            $cvs_line=array_to_CSV($_record_data);
            $imported_records->append_not_imported_log($cvs_line);
	    continue;
	}

	if(!is_numeric($product_data['Product Units Per Case'])){
            $imported_records->update(
                array(
                    'Error Records'=>( (float) $imported_records->data['Error Records']+1),
                ));

            if ($_SESSION['state']['import']['type']) {
                $_record_data=$raw[0];
            } else {
                $_record_data=$csv->getRow($_product_data['csv_key']-1);
            }
            //print_r( $_record_data);exit;
            $_record_data[]='Invalid Unit, cannot insert into DB';

            $cvs_line=array_to_CSV($_record_data);
            $imported_records->append_not_imported_log($cvs_line);
	    continue;
	}


	$sql=sprintf("select `Product ID`,`Product Name`,`Product Code` from `Product Dimension` where `Product Store Key`=%d and `Product Code`=%s  "
		,$store->id
		,prepare_mysql($product_data['Product Code'])
	);
	$res=mysql_query($sql);

	//print $sql;exit;




        if (!$data = mysql_fetch_array($res)) {


            // print_r($customer_data);

            //$response=add_customer($customer_data) ;
            //  print_r($response);
	    $product=new Product('create', $product_data);
		//print_r($product);exit;


            if ($product->new_id) {
/*
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
*/
                $imported_records->update(
                    array(
                        'Imported Records'=>( (float) $imported_records->data['Imported Records']+1),
                    ));

                //Update Read Status

                $sql=sprintf("update `External Records` set `Read Status`='Yes' where `External Record Key`=%d", $record_id);
                //print $sql;
                mysql_query($sql);


            } else {

                $imported_records->update(
                    array(
                        'Error Records'=>( (float) $imported_records->data['Error Records']+1),
                    ));


                $_record_data=$csv->getRow($_product_data['csv_key']-1);
                $_record_data[]='Can not add to the DB';

                $cvs_line=array_to_CSV($_record_data);
                $imported_records->append_not_imported_log($cvs_line);


            }


        } else {



            $imported_records->update(
                array(
                    'Error Records'=>( (float) $imported_records->data['Error Records']+1),
                ));

            if ($_SESSION['state']['import']['type']) {
                $_record_data=$raw[0];
            } else {
                $_record_data=$csv->getRow($_product_data['csv_key']-1);
            }
            //print_r( $_record_data);exit;
            $_record_data[]='Already in DB';

            $cvs_line=array_to_CSV($_record_data);
            $imported_records->append_not_imported_log($cvs_line);

            //print_r($imported_records);


        }
        unset($product);
//exit;
    }


    $imported_records->update(array('Imported Records Finish Date'=>date('Y-m-d H:i:s')));





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
    $data_to_import=array();
    if ($_SESSION['state']['import']['type']) {
        $sql=sprintf("select `Record` from `External Records` where `Store Key`=%d and `Scope`='%s' and `Read Status`='No'", $_SESSION['state']['import']['scope_key'], $_SESSION['state']['import']['scope']);
        //print $sql;

        $result=mysql_query($sql);

        $row = mysql_fetch_array($result);
        //$record_id=$row[1];
        //print $record_id;exit;
        $headers = explode('#', $row[0]);
        $number_of_records = mysql_num_rows($result);

        $raw=array();

        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result)) {
            $data = explode('#', $row[0]);
            foreach($data as $key=>$value)
            $temp[$key]=preg_replace('/"/', '', $value);

            $raw[]=$temp;
            unset($temp);
        }

    } else {
        $csv = new CSV_PARSER;

        if (isset($_SESSION['state']['import']['file_path'])) {
            $csv->load($_SESSION['state']['import']['file_path']);
        }
        $headers = $csv->getHeaders();
        $number_of_records = $csv->countRows();



        $raw = $csv->getrawArray();
    }


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


        $sql=sprintf("select `External Record Key` from `External Records` where `Store Key`=%d and `Scope`='%s' and `Read Status`='No'", $_SESSION['state']['import']['scope_key'], $_SESSION['state']['import']['scope']);
        //print $sql;

        $result=mysql_query($sql);

        $row = mysql_fetch_array($result);
        $record_id=$row[0];

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


            if ($_SESSION['state']['import']['type']) {
                $_record_data=$raw[0];
            } else {
                $_record_data=$csv->getRow($_customer_data['csv_key']-1);
            }
            //$_record_data=$csv->getRow($_customer_data['csv_key']-1);

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


            // print_r($customer_data);

            $response=add_customer($customer_data) ;
            //  print_r($response);


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

                //Update Read Status

                $sql=sprintf("update `External Records` set `Read Status`='Yes' where `External Record Key`=%d", $record_id);
                //print $sql;
                mysql_query($sql);


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

            if ($_SESSION['state']['import']['type']) {
                $_record_data=$raw[0];
            } else {
                $_record_data=$csv->getRow($_customer_data['csv_key']-1);
            }
            //print_r( $_record_data);exit;
            $_record_data[]='Already in DB';;

            $cvs_line=array_to_CSV($_record_data);
            $imported_records->append_not_imported_log($cvs_line);

            //print_r($imported_records);


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

function save_map() {
    $map_name=_trim($_REQUEST['name']);
    $scope=$_REQUEST['scope'];

    
    $meta_data=$_REQUEST['meta_data'];


	switch($scope){
	case 'customers_store':
	case 'store':
		$scope_key=$_REQUEST['scope_key'];
	break;
	case 'family':
		require_once 'class.Family.php';
		$family = new Family($_REQUEST['scope_key']);
		$scope_key=$family->data['Product Family Store Key'];
	break;
	case 'department':
		require_once 'class.Department.php';
		$department = new Department($_REQUEST['scope_key']);
		$scope_key=$department->data['Product Department Store Key'];
	break;
	}

    if ($map_name=='') {
        $response= array('state'=>400,'type'=>'no_name');
        echo json_encode($response);
        return;
    }


    $sql=sprintf("select `Map Name` from `Import CSV Map` where `Store Key`=%d  and `Map Name`='%s'", $scope_key,$map_name);
    $result=mysql_query($sql);
    if (!mysql_fetch_array($result)) {
        $sql=sprintf("insert into `Import CSV Map` (`Store Key`,`Scope`,`Map Name`,`Meta Data`) values ('%d','%s','%s','%s')", $scope_key, $scope, $map_name, $meta_data);
        //print $sql;
        mysql_query($sql);
        $response= array('state'=>200,'msg'=>"<img src='art/icons/accept.png'/> "._('Map saved'));
        echo json_encode($response);

    } else {
        $response= array('state'=>400,'type'=>'used_name','msg'=>_('Map name already used'));
        echo json_encode($response);
    }



}

function browse_maps($data) {

    global $user;
    if (isset( $_REQUEST['scope']))$scope=$_REQUEST['scope'];
    else $scope='customers_store';
    if (isset( $_REQUEST['sf']))$start_from=$_REQUEST['sf'];
    else $start_from=0;
    if (isset( $_REQUEST['nr']))$number_results=$_REQUEST['nr'];
    else $number_results=20;
    if (isset( $_REQUEST['o'])) $order=$_REQUEST['o'];
    else$order='name';
    if (isset( $_REQUEST['od']))$order_dir=$_REQUEST['od'];
    else$order_dir='';
    if (isset( $_REQUEST['f_field']))$f_field=$_REQUEST['f_field'];
    else$f_field='name';
    if (isset( $_REQUEST['f_value']))$f_value=$_REQUEST['f_value'];
    else$f_value='';
    if (isset( $_REQUEST['tableid']))$tableid=$_REQUEST['tableid'];
    else$tableid=0;






	switch($scope){
	case 'customers_store':
	case 'store':
		$scope_key=$_REQUEST['scope_key'];
	break;
	case 'family':
		require_once 'class.Family.php';
		$family = new Family($_REQUEST['scope_key']);
		$scope_key=$family->data['Product Family Store Key'];
	break;
	case 'department':
		require_once 'class.Department.php';
		$department = new Department($_REQUEST['scope_key']);
		$scope_key=$department->data['Product Department Store Key'];
	break;

	}


    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';

	if (!in_array($scope_key,$user->stores)) {
		$where=sprintf('where false ');
	} else {
		$where=sprintf('where `Store Key`=%d',$scope_key);
		switch($_REQUEST['scope']){
		case 'customers_store':
			$where.=sprintf(" and `Scope`='%s'",'customers_store');
		break;
		case 'family':
			$where.=sprintf(" and `Scope`='%s'",'family');
		break;
		case 'department':
			$where.=sprintf(" and `Scope`='%s'",'department');
		break;
		case 'store':
			$where.=sprintf(" and `Scope`='%s'",'store');
		break;
		}
	}






    $filter_msg='';
    $wheref='';


    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Map Name` like '".addslashes($f_value)."%'";


    $sql="select count(DISTINCT `Map Name`) as total from `Import CSV Map` $where $wheref  ";

   // print $sql;

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


    $rtext=$total_records." ".ngettext('Record','Records',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=_('(Showing all)');


    $filter_msg='';

    switch ($f_field) {

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
        $order='`Map Name`';
    else
        $order='`Map Key`';





    $adata=array();
    $sql="select  `Map Key`,`Map Name`,`Meta Data` from `Import CSV Map` $where $wheref  order by $order $order_direction  limit $start_from,$number_results;";

    //print $sql;
    $res=mysql_query($sql);

    while ($row=mysql_fetch_array($res)) {
        unset($data);
        $data=explode(",", $row['Meta Data']);
        $map='';
        foreach($data as $key=>$val) {
            //print_r($_SESSION['state']['import']['options_labels'][$val]);
            $map.=$_SESSION['state']['import']['options_labels'][$val].' ';
        }
        $adata[]=array(

                     'map'=>$map,
                     'name'=>$row['Map Name'],
                     'map_key'=>$row['Map Key'],
                     'delete'=>'<img src="art/icons/cross.png">'
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

function change_map($data) {
    //print $data['map_key'];

    $sql=sprintf("select `Meta Data` from `Import CSV Map` where `Map Key`='%s'", $data['map_key']);
    //print $sql;
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);

    $meta_data=explode(",", $row['Meta Data']);
    //print_r($meta_data);
    $changed_options=array();
    $i=0;
    //print $row['Meta Data'];
    //print_r($meta_data);
    //print_r($_SESSION['state']['import']['map']);
    foreach($meta_data as $key=>$value) {
        if ($_SESSION['state']['import']['map'][$key]!=$value) {
            $_SESSION['state']['import']['map'][$key]=$value;
            $changed_options[$i]=$value;
        }
        $i++;
    }

    //print_r($_SESSION['state']['import']['map']);
    $response=array('state'=>200,'changes'=>$changed_options);
    echo json_encode($response);
    //get_record_data($data);
}

function delete_map($data) {


    $sql=sprintf("select `Store Key`  from `Import CSV Map` where `Map Key`='%s'", $data['map_key']);
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result)) {

        if (!in_array($row['Store Key'],$data['user']->stores)) {
            $response= array('state'=>400,'msg'=>'Forbidden');
            echo json_encode($response);
            return;
        }

    } else {
        $response= array('state'=>400,'msg'=>'Map not found');
        echo json_encode($response);
        return;
    }


    $sql=sprintf("delete from  `Import CSV Map` where `Map Key`=%d ",$data['map_key']);
    mysql_query($sql);
    $response= array('state'=>200,'msg'=>'');
    echo json_encode($response);
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
    while ($row=mysql_fetch_array($result)) {
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
            <th class='list-column-left' style='text-align: left; width: 300px;'>"._('Field')."</th>
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
