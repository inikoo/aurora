<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once 'common.php';
require_once 'class.ImportedRecords.php';

require_once 'ar_edit_common.php';



if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'msg'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case('list_subject_imported_records'):
list_subject_imported_records();
break;


case 'cancel_import':
	$data=prepare_values($_REQUEST,array(
			'imported_records_key'=>array('type'=>'key')
		));
	cancel_import($data);
	break;

case('upload_file'):
	$data=prepare_values($_REQUEST,array(
			'subject'=>array('type'=>'string'),
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key')
		));
	upload_file($data);
	break;
case ('get_imported_records_elements'):
	$data=prepare_values($_REQUEST,array(
			'subject'=>array('type'=>'string'),
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key')
		));
	get_imported_records_elements($data);
	break;
case ('get_records_elements'):
	$data=prepare_values($_REQUEST,array(

			'imported_records_key'=>array('type'=>'key')
		));
	get_records_elements($data);
	break;
case('delete_map'):
	$data=prepare_values($_REQUEST,array(
			'map_key'=>array('type'=>'key'),

		));
	delete_map($data);
	break;
case 'use_map_options':
	$data=prepare_values($_REQUEST,array(

			'map_key'=>array('type'=>'key'),
			'imported_records_key'=>array('type'=>'key')
		));
	use_map_options($data);
	break;
case 'save_map':
	$data=prepare_values($_REQUEST,array(
			'name'=>array('type'=>'string'),
			'subject'=>array('type'=>'string'),
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),
			'imported_records_key'=>array('type'=>'key'),
			'overwrite'=>array('type'=>'string')

		));
	save_map($data);
	break;
case 'list_maps':
	list_maps();
	break;
case 'list_records':
list_records();
	break;
case 'imported_records':
	list_imported_records();
	break;

case('insert_data'):
	$data=prepare_values($_REQUEST,array(
			'imported_records_key'=>array('type'=>'key')
		));
	insert_data($data);
	break;
case('insert_data'):
	insert_data();
	break;
case('change_option'):
	$data=prepare_values($_REQUEST,array(
			'key'=>array('type'=>'numeric'),
			'option_key'=>array('type'=>'numeric'),
			'imported_records_key'=>array('type'=>'key')

		));
	change_option($data);
	break;
case('get_record_data'):


	$data=prepare_values($_REQUEST,array(
			'index'=>array('type'=>'key'),
			'imported_records_key'=>array('type'=>'key')
		));
	get_record_data($data);
	break;

case('ignore_record'):
	$data=prepare_values($_REQUEST,array(
			'index'=>array('type'=>'key'),
			'imported_records_key'=>array('type'=>'key')
		));
	$data['value']='Yes';
	update_ignore_record($data);
	break;
case('read_record'):
	$data=prepare_values($_REQUEST,array(
			'index'=>array('type'=>'key'),
			'imported_records_key'=>array('type'=>'key')
		));
	$data['value']='No';
	update_ignore_record($data);
	break;

default:

	$response=array('state'=>404,'msg'=>_('Operation not found'));
	echo json_encode($response);

}

function upload_file($data) {
	global $editor;

	//print_r($data);
	//$subject=get_parent_object($data);
	// $subject->editor=$editor;
	// $db_field=get_parent_db_field($data);
	$msg='';
	$updated=false;
	$user=$data['user'];

	if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') { //catch file overload error...
		$postMax = ini_get('post_max_size'); //grab the size limits...
		$msg= "File can not be attached, please note files larger than {$postMax} will result in this error!, let's us know, an we will increase the size limits"; // echo out error and solutions...
		$response= array('state'=>400,'msg'=>_('Files could not be attached').".<br>".$msg,'key'=>'attach');

		echo base64_encode(json_encode($response));
		exit;

	}

	foreach ($_FILES as $file_data) {

		if ($file_data['size']==0) {
			$msg= _("This file seems that is empty, have a look and try again");
			$response= array('state'=>400,'msg'=>$msg,'key'=>'attach');
			echo base64_encode(json_encode($response));
			exit;

		}

		if ($file_data['error']) {
			$msg=$file_data['error'];
			if ($file_data['error']==4) {
				$msg=' '._('please choose a file, and try again');

			}
			$response= array('state'=>400,'msg'=>_('File could not be uploaded')."<br/>".$msg,'key'=>'attach');
			echo base64_encode(json_encode($response));
			exit;
		}


		$file_type='unknown';


		$_name=preg_split('/\./',$file_data['name']);
		if (count($_name)<2) {
			$file_extension=='';
		}else {
			$file_extension=array_pop($_name);
		}

		$type=$file_data['type'];

		switch ($file_extension) {
		case 'csv':
			$file_type='posible_csv';



			$valid_csv_types=array('text/plain','application/excel','application/vnd.ms-excel','text/csv','application/csv','application/octet-stream');
			if (in_array($type,$valid_csv_types)) {


				$file_type='csv';
			}



		}


		$supported_types=array('csv');

		if (! in_array($file_type,$supported_types)) {
			$response= array('state'=>400,'msg'=>_('Sorry, but this file format is not suported').": (".$file_data['type'].")".$msg );
			echo base64_encode(json_encode($response));
			exit;

		}



		$imported_records_data=array(
			'Imported Records File Checksum'=>md5_file($file_data['tmp_name']),
			'Imported Records File Name'=>$file_data['name'],
			'Imported Records File Size'=>filesize($file_data['tmp_name']),
			'Imported Records Creation Date'=>gmdate('Y-m-d H:i:s'),
			'Imported Records Subject'=>$data['subject'],
			'Imported Records Parent'=>$data['parent'],
			'Imported Records Parent Key'=>$data['parent_key'],
			'Imported Records User Key'=>$user->id,

		);

		$imported_records=new ImportedRecords('find',$imported_records_data,'create');


		if ($imported_records->found) {

			if (count($imported_records->found_in_users)==1 and array_pop($imported_records->found_in_users)==$user->id) {
				$response= array('state'=>200,
					'action'=>'found_same_user',

					'imported_records_key'=>$imported_records->id);


				echo base64_encode(json_encode($response));
				exit;
			}
			else {

				$same_user=false;
				$msg=_('Other user is uploading the same file') .'(';
				foreach ($imported_records->found_in_users as $user_key) {
					$other_user=new User($user_key);
					$msg.=$other_user->data['User Handle'].', ';
				}
				$msg=preg_replace('/\, $/','',$msg);
				$msg.=')';

				$response= array('state'=>400,
					'msg'=>$msg
				);
				echo base64_encode(json_encode($response));
				exit;

			}








		}


		switch ($file_type) {
		case 'csv':


			if (($handle = fopen($file_data['tmp_name'], "r")) !== FALSE) {
				$row=0;
				while (($csv_row = fgetcsv($handle, 10000, ",")) !== FALSE) {
					$row++;
					if ($row==1 ) {
						$num_cols=count($csv_row);

						$imported_records->update(array('Imported Records Number Columns'=>$num_cols),'no_history');


					}
					$sql=sprintf("insert into `Imported Record` (`Imported Record Parent Key`,`Imported Record Data`,`Imported Record Index`) values (%d,%s,%d)",
						$imported_records->id,
						prepare_mysql(json_encode($csv_row)),
						$row
					);
					mysql_query($sql);

					if ($row==1) {
						$imported_records->update(array('Imported First Record Key'=>mysql_insert_id()),'no_history');

					}


				}
				fclose($handle);
			}


			list($options_keys,$options_labels,$options_fields)=get_options(
				$imported_records->data['Imported Records Subject'],
				$imported_records->data['Imported Records Parent'],
				$imported_records->data['Imported Records Parent Key']

			);
			$number_options=count($options_keys);
			$number_field_options=$number_options-1;
			$options_map='';
			for ($i=1;$i<=$num_cols;$i++) {
				if ($i<=$number_field_options) {
					$options_map.=$options_keys[$i].',';
				}else {
					$options_map.=$options_keys[0].',';
				}
			}
			$options_map=preg_replace('/\,$/', '',$options_map);
			$imported_records->update(
				array(
					'Imported Original Records'=>$row,
					'Imported Records State'=>'Review',
					'Imported Records Options Map'=>$options_map
				)
				,'no_history');

			$response= array('state'=>200,'action'=>'uploaded','imported_records_key'=>$imported_records->id);
			echo base64_encode(json_encode($response));
			exit;
			break;
		}


	}


	$response= array('state'=>400,'msg'=>_('Files could not be processed'));

	echo base64_encode(json_encode($response));





}

function change_option($data) {

	$imported_records=new ImportedRecords('id',$data['imported_records_key']);
	$selected_options=preg_split('/,/',$imported_records->data['Imported Records Options Map']);
	list($options_keys,$options_labels,$options_fields)=get_options(
		$imported_records->data['Imported Records Subject'],
		$imported_records->data['Imported Records Parent'],
		$imported_records->data['Imported Records Parent Key']
	);

	$selected_options[$data['key']]=$options_keys[$data['option_key']];
	$options=join(',',$selected_options);
	$imported_records->update(array('Imported Records Options Map'=>$options),'no_history');

}

function update_ignore_record($data) {

	if ($data['value']=='Yes') {
		$state='Ignored';
	}else {
		$state='Waiting';
	}



	$sql=sprintf("update `Imported Record` set  `Imported Record Import State`=%s where `Imported Record Index`=%d and `Imported Record Parent Key`=%d",
		prepare_mysql($state),
		$data['index'],
		$data['imported_records_key']
	);
	mysql_query($sql);
	//print $sql;
	$imported_records=new ImportedRecords('id',$data['imported_records_key']);
	$imported_records->update_records_numbers();
	$response=array('state'=>200,'index'=>$data['index']);
	echo json_encode($response);


}



function get_record_data($data) {
	$index=$data['index'];
	$imported_records_key=$data['imported_records_key'];
	$imported_records=new ImportedRecords('id',$imported_records_key);

	$sql=sprintf("select * from `Imported Record` where `Imported Record Index`=%d and `Imported Record Parent Key`=%d ",
		$index,
		$imported_records->id
	);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_assoc($result)) {
		if ($row['Imported Record Parent Key']!=$imported_records->id) {
			$response=array('state'=>200,'result'=>'index not match with record set');
			echo json_encode($response);
			exit;
		}

		$index=$row['Imported Record Index'];
		$number_of_records=$imported_records->data['Imported Original Records'];
		$ignore_record=($row['Imported Record Import State']=='Ignored'?true:false);

	}else {
		$response=array('state'=>200,'result'=>'index not found');
		echo json_encode($response);
		exit;
	}


	$result="<table class='recordList' border=0  >
            <tr>
            <th class='list-column-left' style='text-align: left; width: 320px;'>
            <div class='buttons small left' >
            	<span style='float:left'>"._('Fields')." </span>

            			<button style='margin-left:10px' onClick='show_save_map()' id='new_map'>"._('Save map')."</button>


	</div>

            </th>
            <th class='list-column-left' style='text-align: left; width: 200px;'>"._('Record').' '.($index).' '._('of').' '.($number_of_records).' <span id="ignore_record_label" style="color:red;'.($ignore_record?'':'display:none').'">('._('Ignored').')</th>';
	$result.="<th style='width:150px'><div class='buttons small'>";
	$result.=sprintf('<button style="cursor:pointer;%s" onclick="ignore_record(%d)" id="ignore" class="subtext">%s</button>',(!$ignore_record?'':'display:none'),$index,_('Ignore Record'));
	$result.=sprintf('<button style="cursor:pointer;%s" onclick="read_record(%d)" id="unignore" class="subtext">%s</button>',($ignore_record?'':'display:none'),$index,_('Read Record'));
	$result.='</div></th>';


	$result.="<th style='width:150px'><div >";
	//$result.="<button  style='cursor:pointer;".($index < $number_of_records?'':'visibility:hidden')."'   id='next' onclick='get_record_data(".($index+1).")'>"._('Next')."</button>";
	$result.="<img src='art/first_button.png' title='"._('First')."' alt='"._('First')."'  style='height:14px;".($index > 1?'cursor:pointer':'opacity:.25')."'  id='first' onclick='get_record_data(1)'>";
	$result.="<img src='art/previous_button.gif' title='"._('Previous')."' alt='"._('Previous')."'  style='margin-left:10px;height:14px;".($index > 1?'cursor:pointer':'opacity:.25')."'  id='prev' onclick='get_record_data(".($index > 1?$index-1:1).")'>";

	$result.="<img src='art/next_button.gif' title='"._('Next')."' alt='"._('Next')."'  style='margin-left:10px;height:14px;cursor:pointer;".($index < $number_of_records?'cursor:pointer':'opacity:.25')."'   id='next' onclick='get_record_data(".($index < $number_of_records?$index+1:$index).")'>";
	$result.="<img src='art/last_button.png' title='"._('Last')."' alt='"._('Last')."'  style='margin-left:10px;height:14px;cursor:pointer;".($index < $number_of_records?'cursor:pointer':'opacity:.25')."'   id='next' onclick='get_record_data(".$number_of_records.")'>";

	//$result.="<button style='".($index > 0?'':'visibility:hidden')."'  id='prev' onclick='get_record_data(".($index-1).")'>"._('Previous')."</button>";
	$result.="</div></th>";

	$result.='</tr>';

	list($options_keys,$options_labels,$options_fields)=get_options(
		$imported_records->data['Imported Records Subject'],
		$imported_records->data['Imported Records Parent'],
		$imported_records->data['Imported Records Parent Key']
	);

	$selected_options=preg_split('/,/',$imported_records->data['Imported Records Options Map']);
	//print_r($selected_options);
	$record_data=json_decode($row['Imported Record Data'],true);
	$i=0;
	foreach ($selected_options as $key=>$value) {

		$select='<select id="select'.$i.'" onChange="option_changed(this.options[this.selectedIndex].value,this.selectedIndex)">';
		$i++;
		foreach ($options_fields as $option_key=>$option_label) {

			$selected='';
			if ($selected_options[$key]==$option_key)
				$selected='selected="selected"';

			$select.=sprintf('<option %s value="%d"   >%s</option>',$selected,$key,$option_label);

		}
		$select.='</select>';
		$text=$record_data[$key];
		$newtext = wordwrap($text, 50, "<br />\n");
		$result.=sprintf('<tr style="height:20px;border-top:1px solid #ccc"><td>%s</td><td colspan="3" >%s</td></tr>',$select,$newtext);
	}


	$result.='</table>';

	//print $result;

	$response=array('state'=>200,'result'=>$result,'index'=>$index);
	echo json_encode($response);
	exit;

}


function insert_data($data) {
	global $account_code;
	include 'splinters/new_fork.php';
	$user=$data['user'];

	$imported_records=new ImportedRecords('id',$data['imported_records_key']);

	if ($imported_records->data['Imported Records State']!='Review') {

		

		$msg='wrong imported records state '.$imported_records->data['Imported Records State'];

		$response= array(
			'state'=>400,'msg'=>$msg,'imported_records_key'=>$imported_records->id
		);
		echo json_encode($response);
		exit;
	}

	$import_data=array(
		'imported_records_key'=>$imported_records->id,
		'user_key'=>$user->id

	);

	list($fork_key,$msg)=new_fork('import',$import_data,$account_code);

	if ($fork_key) {
	
		$imported_records->update(array('Imported Records Fork Key'=>$fork_key,'Imported Records State'=>'Queued'));

		$response= array(
			'state'=>200,'fork_key'=>$fork_key,'msg'=>$msg,'imported_records_key'=>$imported_records->id
		);
		echo json_encode($response);

	}else {
		$response= array(
			'state'=>400,'msg'=>'unkown error','imported_records_key'=>$imported_records->id
		);
		echo json_encode($response);
	}



}

function insert_data_old($data) {
	$imported_records=new ImportedRecords('id',$data['imported_records_key']);
	$imported_records->insert_data();


	switch ($imported_records->data['Imported Records Subject']) {
	case('customers_store'):
		insert_customers($imported_records);
	case('family'):
		insert_products_from_csv();
	case('department'):
		insert_family_from_csv();
	case('store'):
		insert_department_from_csv();
	}


}

function insert_department_from_csv() {
	global $editor;
	include_once 'class.Department.php';
	$imported_records=new ImportedRecords($_SESSION['state']['import']['key']);

	$imported_records->update(array('Imported Records Start Date'=>date('Y-m-d H:i:s')));
	//$_SESSION['state']['import']['in_progress']=1;

	$store_key=$imported_records->data['Imported Records Parent Key'];
	$customer_list_key=0;




	$records_ignored_by_user = $_SESSION['state']['import']['records_ignored_by_user'];
	$map = $_SESSION['state']['import']['map'];

	require_once 'class.csv_parser.php';
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
			foreach ($data as $key=>$value)
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
	foreach ($raw as $record_key=>$record_data) {
		if (array_key_exists($record_key,$records_ignored_by_user)) {
			$record_data[]='Ignored';

			$cvs_line=array_to_CSV($record_data);
			$imported_records->append_log($cvs_line);

			$imported_records->update(
				array(
					'Imported Records'=>((float) $imported_records->data['Imported Records']+1),
				));
			continue;

		}


		$parsed_record_data=array('csv_key'=>$record_key);
		foreach ($record_data as $field_key=>$field) {
			//$field['csv_key']=$field_key;
			$mapped_field_key=$map[$field_key];
			//print $mapped_field_key;
			//print_r($_SESSION['state']['import']['options_db_fields']);exit;
			if ($mapped_field_key) {
				$parsed_record_data[$_SESSION['state']['import']['options_db_fields'][$mapped_field_key]]=$field;
				//print_r($_SESSION['state']['import']['options_db_fields'][$mapped_field_key]);exit;
			}
		}
		$data_to_import[]=$parsed_record_data;
	}

	$_SESSION['state']['import']['todo']=count($data_to_import);

	//print_r($data_to_import);exit;


	foreach ($data_to_import as $_department_data) {


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
		foreach ($_department_data as $key=>$value) {
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
			$imported_records->append_log($cvs_line);

			//print_r($imported_records);


		}
		unset($department);
		//exit;
	}


	$imported_records->update(array('Imported Records Finish Date'=>date('Y-m-d H:i:s')));





}

function insert_family_from_csv() {
	global $editor;

	include_once 'class.Department.php';


	$imported_records=new ImportedRecords($_SESSION['state']['import']['key']);

	$imported_records->update(array('Imported Records Start Date'=>date('Y-m-d H:i:s')));
	//$_SESSION['state']['import']['in_progress']=1;

	$department_key=$imported_records->data['Imported Records Parent Key'];
	$customer_list_key=0;
	$department = new Department($department_key);



	$records_ignored_by_user = $_SESSION['state']['import']['records_ignored_by_user'];
	$map = $_SESSION['state']['import']['map'];

	require_once 'class.csv_parser.php';
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
			foreach ($data as $key=>$value)
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
	foreach ($raw as $record_key=>$record_data) {
		if (array_key_exists($record_key,$records_ignored_by_user)) {
			$record_data[]='Ignored';

			$cvs_line=array_to_CSV($record_data);
			$imported_records->append_log($cvs_line);

			$imported_records->update(
				array(
					'Imported Records'=>((float) $imported_records->data['Imported Records']+1),
				));
			continue;

		}


		$parsed_record_data=array('csv_key'=>$record_key);
		foreach ($record_data as $field_key=>$field) {
			//$field['csv_key']=$field_key;
			$mapped_field_key=$map[$field_key];
			//print $mapped_field_key;
			//print_r($_SESSION['state']['import']['options_db_fields']);exit;
			if ($mapped_field_key) {
				$parsed_record_data[$_SESSION['state']['import']['options_db_fields'][$mapped_field_key]]=$field;
				//print_r($_SESSION['state']['import']['options_db_fields'][$mapped_field_key]);exit;
			}
		}
		$data_to_import[]=$parsed_record_data;
	}

	$_SESSION['state']['import']['todo']=count($data_to_import);

	//print_r($data_to_import);exit;


	foreach ($data_to_import as $_family_data) {


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
		foreach ($_family_data as $key=>$value) {
			$family_data[$key]=$value;
		}

		if ($family_data['Product Family Special Characteristic']=='') {
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
			$imported_records->append_log($cvs_line);

			//print_r($imported_records);


		}
		unset($family);
		//exit;
	}


	$imported_records->update(array('Imported Records Finish Date'=>date('Y-m-d H:i:s')));





}

function insert_products_from_csv() {
	global $editor;

	include_once 'class.Product.php';
	include_once 'class.Family.php';

	$imported_records=new ImportedRecords($_SESSION['state']['import']['key']);

	$imported_records->update(array('Imported Records Start Date'=>date('Y-m-d H:i:s')));
	//$_SESSION['state']['import']['in_progress']=1;

	$family_key=$imported_records->data['Imported Records Parent Key'];
	$customer_list_key=0;
	$family = new Family($family_key);

	$store = new Store($family->data['Product Family Store Key']);

	$records_ignored_by_user = $_SESSION['state']['import']['records_ignored_by_user'];
	$map = $_SESSION['state']['import']['map'];

	require_once 'class.csv_parser.php';
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
			foreach ($data as $key=>$value)
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
	foreach ($raw as $record_key=>$record_data) {
		if (array_key_exists($record_key,$records_ignored_by_user)) {
			$record_data[]='Ignored';

			$cvs_line=array_to_CSV($record_data);
			$imported_records->append_log($cvs_line);

			$imported_records->update(
				array(
					'Imported Records'=>((float) $imported_records->data['Imported Records']+1),
				));
			continue;

		}


		$parsed_record_data=array('csv_key'=>$record_key);
		foreach ($record_data as $field_key=>$field) {
			//$field['csv_key']=$field_key;
			$mapped_field_key=$map[$field_key];
			//print $mapped_field_key;
			//print_r($_SESSION['state']['import']['options_db_fields']);exit;
			if ($mapped_field_key) {
				$parsed_record_data[$_SESSION['state']['import']['options_db_fields'][$mapped_field_key]]=$field;
				//print_r($_SESSION['state']['import']['options_db_fields'][$mapped_field_key]);exit;
			}
		}
		$data_to_import[]=$parsed_record_data;
	}

	$_SESSION['state']['import']['todo']=count($data_to_import);

	//print_r($data_to_import);exit;
	//print $_SESSION['state']['import']['todo']

	foreach ($data_to_import as $_product_data) {


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
			'Product Parts Weight'=>'',
			'Product Part Metadata'=>''
		);


		foreach ($_product_data as $key=>$value) {
			$product_data[$key]=$value;
		}
		$product_data['Product Part Metadata'] = $product_data['Part SKU'];
		unset($product_data['Part SKU']);

		//print_r($product_data);exit;
		if ($product_data['Product Special Characteristic']=='') {
			$product_data['Product Special Characteristic']=$product_data['Product Name'];
		}


		if (!is_numeric($product_data['Product Price'])) {
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
			$imported_records->append_log($cvs_line);
			continue;
		}

		if (!is_numeric($product_data['Product Units Per Case'])) {
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
			$imported_records->append_log($cvs_line);
			continue;
		}


		$sql=sprintf("select `Product ID`,`Product Name`,`Product Code` from `Product Dimension` where `Product Store Key`=%d and `Product Code`=%s  "
			,$store->id
			,prepare_mysql($product_data['Product Code'])
		);
		$res=mysql_query($sql);

		//print $sql;exit;

		// print_r($product_data);exit;


		if (!$data = mysql_fetch_array($res)) {


			// print_r($customer_data);

			//$response=add_customer($customer_data) ;
			//  print_r($response);
			$product=new Product('create', $product_data);
			//print_r($product);exit;
			//print_r($product);exit;

			if ($product->new_id) {
				// print $product->new_id;
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

				if ($product_data['Product Part Metadata'] != 0) {
					include_once 'class.Part.php';
					$part= new Part($product_data['Product Part Metadata']);
					$part_list[]=array(
						'Part SKU'=>$part->get('Part SKU'),
						'Parts Per Product'=>1,
						'Product Part Type'=>'Simple'
					);



					$product->new_current_part_list(array(),$part_list);

					$product->update_parts();
					$product->update_cost();
				}


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
				$imported_records->append_log($cvs_line);


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
			$imported_records->append_log($cvs_line);

			//print_r($imported_records);


		}
		unset($product);
		//exit;
	}


	$imported_records->update(array('Imported Records Finish Date'=>date('Y-m-d H:i:s')));





}

function insert_customers($imported_records) {
	global $editor;




	include_once 'class.Customer.php';
	include_once 'edit_customers_functions.php';



	$imported_records->update(array('Imported Records Start Date'=>date('Y-m-d H:i:s')));
	//$_SESSION['state']['import']['in_progress']=1;

	$store_key=$imported_records->data['Imported Records Parent Key'];
	$customer_list_key=0;


	$sql=sprintf("select * from `Imported Record` where `Imported Record Parent Key`=%d and `Ignore Record`='No' order by `Imported Record Index`");



	foreach ($raw as $record_key=>$record_data) {
		if (array_key_exists($record_key,$records_ignored_by_user)) {
			$record_data[]='Ignored';

			$cvs_line=array_to_CSV($record_data);
			$imported_records->append_log($cvs_line);

			$imported_records->update(
				array(
					'Imported Records'=>((float) $imported_records->data['Imported Records']+1),
				));
			continue;

		}


		$parsed_record_data=array('csv_key'=>$record_key);
		foreach ($record_data as $field_key=>$field) {
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



	foreach ($data_to_import as $_customer_data) {


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
		foreach ($_customer_data as $key=>$value) {
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
			$imported_records->append_log($cvs_line);



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
		elseif ($customer_data['Customer Address Country Code']!='') {
			$country=new Country('code',$customer_data['Customer Address Country Code']);
			$customer_data['Customer Address Country Code']=$country->data['Country Code'];
			unset($country);
		}
		elseif ($customer_data['Customer Address Country Name']!='') {
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
				$imported_records->append_log($cvs_line);


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
			$imported_records->append_log($cvs_line);

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
		'ignored'=>array('number'=>$imported_records->get('Ignored'),'comments'=>$imported_records->get_log_link())

	);
	$response= array('state'=>200,'data'=>$data);
	echo json_encode($response);
}

function save_map($data) {

	$imported_records=new ImportedRecords('id',$data['imported_records_key']);


	$sql=sprintf("select `Map Key`,`Map Name` from `Import Map` where `Subject`=%s and`Parent`=%s and `Parent Key`=%d  and `Map Name`=%s",
		prepare_mysql($data['subject']),
		prepare_mysql($data['parent']),
		$data['parent_key'],
		prepare_mysql($data['name'])
	);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result)) {
		if ($data['overwrite']=='No') {

			$response= array('state'=>400,'type'=>'used_name','msg'=>'');
			echo json_encode($response);
			return;
		}else {
			$sql=sprintf("update `Import Map` set `Meta Data`=%s where `Map Key`=%d",
				prepare_mysql($imported_records->data['Imported Records Options Map']),
				$row['Map Key']
			);
			mysql_query($sql);
			$response= array('state'=>200,'msg'=>"<img src='art/icons/accept.png'/> "._('Map overwrited'));
			echo json_encode($response);
			return;
		}



	}


	$sql=sprintf("insert into `Import Map` (`Subject`,`Parent`,`Parent Key`,`Map Name`,`Meta Data`) values (%s,%s,%d,%s,%s)",
		prepare_mysql($data['subject']),
		prepare_mysql($data['parent']),
		$data['parent_key'],
		prepare_mysql($data['name']),
		prepare_mysql($imported_records->data['Imported Records Options Map'])

	);
	//print $sql;
	mysql_query($sql);
	$response= array('state'=>200,'msg'=>"<img src='art/icons/accept.png'/> "._('Map saved'));
	echo json_encode($response);


}

function list_maps() {

	global $user;
	if (isset( $_REQUEST['subject']))$subject=$_REQUEST['subject'];
	else exit();
	if (isset( $_REQUEST['parent']))$parent=$_REQUEST['parent'];
	else exit();
	if (isset( $_REQUEST['parent_key']))$parent_key=$_REQUEST['parent_key'];
	else exit();
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


	list($options_keys,$options_labels,$options_fields)=get_options(
		$subject,
		$parent,
		$parent_key
	);






	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';

	$where=sprintf(" where `Subject`=%s and `Parent`=%s and `Parent Key`=%d",
		prepare_mysql($_REQUEST['subject']),
		prepare_mysql($_REQUEST['parent']),
		$_REQUEST['parent_key']
	);


	/*
switch ($scope) {
	case 'customers_store':
	case 'store':
		$scope_key=$_REQUEST['parent_key'];
		break;
	case 'family':
		require_once 'class.Family.php';
		$family = new Family($_REQUEST['parent_key']);
		$scope_key=$family->data['Product Family Store Key'];
		break;
	case 'department':
		require_once 'class.Department.php';
		$department = new Department($_REQUEST['parent_key']);
		$scope_key=$department->data['Product Department Store Key'];
		break;

	}
	if (!in_array($scope_key,$user->stores)) {
		$where=sprintf('where false ');
	} else {
		$where=sprintf('where `Store Key`=%d',$scope_key);
		switch ($_REQUEST['subject']) {
		case 'customers':
			$where.=sprintf(" and `Subject`='%s'",'customers_store');
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

*/




	$filter_msg='';
	$wheref='';


	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Map Name` like '".addslashes($f_value)."%'";


	$sql="select count(*) as total from `Import Map` $where $wheref  ";

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
		$sql="select count(*) as total from `Import Map`  $where   ";
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
		elseif ($filtered>0)
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
	$sql="select  `Map Key`,`Map Name`,`Meta Data` from `Import Map` $where $wheref  order by $order $order_direction  limit $start_from,$number_results;";

	//print $sql;
	$res=mysql_query($sql);

	while ($row=mysql_fetch_array($res)) {
		unset($data);
		$data=explode(",", $row['Meta Data']);
		$map='';


		foreach ($data as $key=>$val) {
			$map.=$options_fields[$val].', ';
		}
		$map=preg_replace('/\, $/','',$map);
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

function use_map_options($data) {

	$sql=sprintf("select `Meta Data` from `Import Map` where `Map Key`=%d", $data['map_key']);

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result)) {

		$new_map_options=preg_split('/,/',$row['Meta Data']);

		$imported_records=new ImportedRecords('id',$data['imported_records_key']);

		$imported_records_options=preg_split('/,/',$imported_records->data['Imported Records Options Map']);
		foreach ($imported_records_options as $key =>$option) {
			if (isset($new_map_options[$key])) {
				$imported_records_options[$key]=$new_map_options[$key];

			}else {
				$imported_records_options[$key]='Ignore';
			}

		}


		$imported_records->update(array('Imported Records Options Map'=>join(',',$imported_records_options)),'no_history');
		$response=array('state'=>200);
		echo json_encode($response);

	}else {
		$response=array('state'=>400);
		echo json_encode($response);

	}


}

function cancel_import($data) {
	$imported_records=new ImportedRecords('id',$data['imported_records_key']);
	$imported_records->cancel();

	if ($imported_records->cancelled) {

		$response=array('state'=>200);
		echo json_encode($response);

	}else {
		$response=array('state'=>400,'msg'=>$imported_records->msg);
		echo json_encode($response);

	}
}


function delete_map($data) {


	$sql=sprintf("select `Parent Key`,`Subject`  from `Import Map` where `Map Key`=%d", $data['map_key']);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result)) {

		switch ($row['Subject']) {
		case 'customers':
			if (!in_array($row['Parent Key'],$data['user']->stores)) {
				$response= array('state'=>400,'msg'=>'Forbidden');
				echo json_encode($response);
				return;
			}
			break;
		}

	} else {
		$response= array('state'=>400,'msg'=>'Map not found');
		echo json_encode($response);
		return;
	}


	$sql=sprintf("delete from  `Import Map` where `Map Key`=%d ",$data['map_key']);
	mysql_query($sql);
	$response= array('state'=>200,'msg'=>'');
	echo json_encode($response);
}



function get_options($subject,$parent,$parent_key) {

	switch ($subject) {

	case('customers'):


		$fields=array(
			'Ignore'=>_('Ignore'),
			'Customer Company Name'=>_('Company Name'),
			'Customer Tax Number'=>_('Tax Number'),
			'Customer Main Contact Name'=>_('Contact Name'),
			'Customer Main Plain Email'=>_('Email'),
			'Customer Main Plain Telephone'=>_('Telephone'),
			'Customer Main Plain Mobile'=>_('Mobile'),
			'Customer Main Plain FAX'=>_('Fax'),
			'Customer Address Line 1'=>_('Address Line 1'),
			'Customer Address Line 2'=>_('Address Line 2'),
			'Customer Address Line 3'=>_('Address Line 3'),
			'Customer Address Town Second Division'=>_('Town Second Division'),
			'Customer Address Town First Division'=>_('Town First Division'),

			'Customer Address Town'=>_('Town'),
			'Customer Address Postal Code'=>_('Postal Code'),

			'Customer Address Country Fifth Division'=>_('Country Fifth Division'),
			'Customer Address Country Forth Division'=>_('Country Forth Division'),
			'Customer Address Country Third Division'=>_('Country Third Division'),
			'Customer Address Country Second Division'=>_('Country Second Division'),
			'Customer Address Country First Division'=>_('Country First Division'),
			'Customer Address Country Name'=>_('Country'),
			'Customer Address Country Code'=>_('Country Code (XXX)'),
			'Customer Address Country 2 Alpha Code'=>_('Country Code (XX)'),





		);

		$categories=array();
		$sql=sprintf("select `Category Key`,`Category Label` from `Category Dimension` where `Category Subject`='Customer' and `Category Deep`=1 and `Category Store Key`=%d",
			$parent_key);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {


			$fields['cat'.$row['Category Key']]=$row['Category Label'];

		}



		break;

	case('supplier_products'):
		$fields=array();
		break;

	case('staff'):
		$fields=array();
		break;

	case('positions'):
		$fields=array();
		break;

	case('areas'):
		$fields=array();
		break;

	case('departments'):
		$fields=array();
		break;

	case('family'):
		$fields=array(
			'Ignore'=>_('Ignore')
			,'Part SKU'=>_('SKU')
			,'Product Code'=>_('Code')
			,'Product Name'=>_('Name')
			,'Product Units Per Case'=>_('Units')
			,'Product Price'=>_('Price')
			,'Product RRP'=>_('RRP')
			,'Product Net Weight'=>_('Weight')
			,'Product Special Characteristic'=>_('Special Characteristic')
			,'Product Description'=>_('Description')
		);
		break;
	case('department'):
		$fields=array(
			'Ignore'=>_('Ignore')
			,'Product Family Code'=>_('Code')
			,'Product Family Name'=>_('Name')
			,'Product Family Description'=>_('Description')
		);
		break;
	case('store'):
		$fields=array(
			'Ignore'=>_('Ignore')
			,'Product Department Code'=>_('Code')
			,'Product Department Name'=>_('Name')
		);
		break;
	default:
		$fields=array();
	}

	$db_fields=array();
	$labels=array();
	foreach ($fields as $key=>$item) {
		$db_fields[]=$key;
		$labels[]=$item;
	}
	return array($db_fields,$labels,$fields);

}

function list_subject_imported_records() {
	global $myconf;




	if (isset( $_REQUEST['subject']))
		$subject=$_REQUEST['subject'];
	else {
		exit("error no subject\n");
	}

	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		exit("error no parent\n");
	}


	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		exit("error no parent_key\n");
	}
	$conf=$_SESSION['state'][$subject]['imported_records'];



	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_order=$order;
	$_dir=$order_direction;




	foreach (array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'f_field'=>$f_field,'f_value'=>$f_value) as $key=>$item) {
		$_SESSION['state'][$subject]['imported_records'][$key]=$item;
	}
//	$_SESSION['state'][$subject]['imported_records']['elements']=$elements;



	$where=sprintf(' where `Imported Records Subject`=%s and `Imported Records Parent`=%s and `Imported Records Parent Key`=%d ',
		prepare_mysql($subject),
		prepare_mysql($parent),
		$parent_key
	);


	$wheref='';
	if ($f_field=='filename' and $f_value!=''  )
		$wheref.=" and  `Imported Records File Name` like '".addslashes($f_value)."%'    ";
	else if ($f_field=='user'  and $f_value!='' )
			$wheref.=" and  `User Alias` like '".addslashes($f_value)."%'    ";


/*
		$_elements='';
	$num_elements_checked=0;
	foreach ($elements as $_key=>$_value) {
		if ($_value) {
			$num_elements_checked++;
			$_elements.=",'".addslashes($_key)."'";

		}
	}

	if ($_elements=='') {
		$where.=' and false' ;
	}elseif ($num_elements_checked==5) {

	}else {
		$_elements=preg_replace('/^,/','',$_elements);
		$where.=' and `Imported Records State` in ('.$_elements.')' ;
	}
*/

	$sql="select count(distinct `Imported Records Key`) as total from `Imported Records Dimension` IR left join `User Dimension` U on (`Imported Records User Key`=`User Key`) left join `List Dimension` L on (`List Key`=`Imported Records Subject List Key`) $where $wheref";

//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(distinct `Imported Records Key`) as total from `Imported Records Dimension` IR  $where";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	} else {
		$filtered=0;
		$total_records=$total;
	}

	mysql_free_result($res);

	$filter_msg='';


	$rtext=$total_records." ".ngettext('record','records',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp='('._("Showing all").')';

	switch ($f_field) {
	case('filename'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with name")." <b>*".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with name')." <b>*".$f_value."*</b>)";
		break;
	case('user'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff on area")." <b>".$f_value."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff on area')." <b>".$f_value."</b>)";
		break;


	}


	if ($order=='user')
		$order='`User Handle`';
	elseif ($order=='status')
		$order='`Imported Records State`';
	elseif ($order=='filename')
		$order='`Imported Records File Name`';
	elseif ($order=='records')
		$order='`Imported Original Records`';
	else
		$order='`Imported Records Creation Date`';

	$sql="select * from `Imported Records Dimension` IR left join `User Dimension` U on (`Imported Records User Key`=`User Key`)  left join `List Dimension` L on (`List Key`=`Imported Records Subject List Key`)  $where $wheref order by $order $order_direction limit $start_from,$number_results";
	//print $sql;
	$adata=array();
	$res=mysql_query($sql);
	while ($data=mysql_fetch_array($res)) {


		// $id=sprintf('<a href="staff.php?id=%d">%03d</a>',$data['Staff Key'],$data['Staff Key']);
		$filename=sprintf('<a href="imported_records.php?id=%d">%s</a>',$data['Imported Records Key'],$data['Imported Records File Name']);
		$list=sprintf('<a href="list.php?id=%d">%s</a>',$data['List Key'],$data['List Name']);

		////'Uploading','Review','Queued','InProcess','Finished'

		switch ($data['Imported Records State']) {
		case 'Uploading':
			$status=_('Uploading');
			break;
		case 'Review':
			$status=_('Review');
			break;
		case 'Queued':
			$status=_('Queued');
			break;
		case 'InProcess':
			$status=_('In Process');
			break;
		case 'Finished':
			$status=_('Imported');
			break;
	case 'Cancelled':
			$status=_('Cancelled');
			break;
		}


		$adata[]=array(
			// 'id'=>$id,
			'user'=>$data['User Handle'],
			'filename'=>$filename,
		'imported'=>number($data['Imported Imported Records']),
			'name'=>$list,
			'date'=>strftime("%c", strtotime($data['Imported Records Creation Date'].' +0:00')),
			'records'=>number($data['Imported Imported Records']),
			'status'=>$status

		);
	}
	mysql_free_result($res);


	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,

			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);

	echo json_encode($response);
}
function list_imported_records() {
	global $myconf;

	$conf=$_SESSION['state']['imported_records']['imported_records'];



	if (isset( $_REQUEST['subject']))
		$subject=$_REQUEST['subject'];
	else {
		exit("error no subject\n");
	}

	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		exit("error no parent\n");
	}


	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		exit("error no parent_key\n");
	}



	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];





	$elements=$conf['elements'];

	if (isset( $_REQUEST['elements_notworking'])) {
		$elements['NotWorking']=$_REQUEST['elements_notworking'];

	}
	if (isset( $_REQUEST['elements_working'])) {
		$elements['Working']=$_REQUEST['elements_working'];
	}






	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_order=$order;
	$_dir=$order_direction;



	// $_SESSION['state']['hr']['staff']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
	// $_SESSION['state']['hr']['view']=$view;

	foreach (array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'f_field'=>$f_field,'f_value'=>$f_value) as $key=>$item) {
		$_SESSION['state']['imported_records']['imported_records'][$key]=$item;
	}
	$_SESSION['state']['imported_records']['imported_records']['elements']=$elements;



	$where=sprintf(' where `Imported Records Subject`=%s and `Imported Records Parent`=%s and `Imported Records Parent Key`=%d ',
		prepare_mysql($subject),
		prepare_mysql($parent),
		$parent_key
	);
	/*
	switch($subject){

	case 'customers':

	break;

	}
*/

	$wheref='';
	if ($f_field=='filename' and $f_value!=''  )
		$wheref.=" and  `Imported Records File Name` like '".addslashes($f_value)."%'    ";
	else if ($f_field=='user'  and $f_value!='' )
			$wheref.=" and  `User Alias` like '".addslashes($f_value)."%'    ";



		$_elements='';
	$num_elements_checked=0;
	foreach ($elements as $_key=>$_value) {
		if ($_value) {
			$num_elements_checked++;
			$_elements.=",'".addslashes($_key)."'";

		}
	}

	if ($_elements=='') {
		$where.=' and false' ;
	}elseif ($num_elements_checked==5) {

	}else {
		$_elements=preg_replace('/^,/','',$_elements);
		$where.=' and `Imported Records State` in ('.$_elements.')' ;
	}


	$sql="select count(distinct `Imported Records Key`) as total from `Imported Records Dimension` IR left join `User Dimension` U on (`Imported Records User Key`=`User Key`) $where $wheref";


	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(distinct `Imported Records Key`) as total from `Imported Records Dimension` IR  $where";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	} else {
		$filtered=0;
		$total_records=$total;
	}

	mysql_free_result($res);

	$filter_msg='';


	$rtext=$total_records." ".ngettext('record','records',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp='('._("Showing all").')';

	switch ($f_field) {
	case('filename'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with name")." <b>*".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with name')." <b>*".$f_value."*</b>)";
		break;
	case('user'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff on area")." <b>".$f_value."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff on area')." <b>".$f_value."</b>)";
		break;


	}


	if ($order=='user')
		$order='`User Handle`';
	elseif ($order=='status')
		$order='`Imported Records State`';
	elseif ($order=='filename')
		$order='`Imported Records File Name`';
	elseif ($order=='records')
		$order='`Imported Original Records`';
	else
		$order='`Imported Records Creation Date`';

	$sql="select * from `Imported Records Dimension` IR left join `User Dimension` U on (`Imported Records User Key`=`User Key`)   $where $wheref order by $order $order_direction limit $start_from,$number_results";
	//print $sql;
	$adata=array();
	$res=mysql_query($sql);
	while ($data=mysql_fetch_array($res)) {


		// $id=sprintf('<a href="staff.php?id=%d">%03d</a>',$data['Staff Key'],$data['Staff Key']);
		$filename=sprintf('<a href="imported_records.php?id=%d">%s</a>',$data['Imported Records Key'],$data['Imported Records File Name']);

		////'Uploading','Review','Queued','InProcess','Finished'

		switch ($data['Imported Records State']) {
		case 'Uploading':
			$status=_('Uploading');
			break;
		case 'Review':
			$status=_('Review');
			break;
		case 'Queued':
			$status=_('Queued');
			break;
		case 'InProcess':
			$status=_('In Process');
			break;
		case 'Finished':
			$status=_('Finished');
			break;

		}


		$adata[]=array(
			// 'id'=>$id,
			'user'=>$data['User Handle'],
			'filename'=>$filename,
			'date'=>strftime("%c", strtotime($data['Imported Records Creation Date'].' +0:00')),
			'records'=>number($data['Imported Original Records']),
			'status'=>$status

		);
	}
	mysql_free_result($res);


	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,

			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);

	echo json_encode($response);
}

function list_records() {
	global $myconf;

	$conf=$_SESSION['state']['imported_records']['records'];


	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		exit("error no parent_key\n");
	}


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];



	//'Ignored','Waiting','Importing','Imported','Error'

	$elements=$conf['elements'];

	if (isset( $_REQUEST['elements_Ignored'])) {
		$elements['Ignored']=$_REQUEST['elements_Ignored'];

	}
	if (isset( $_REQUEST['elements_Waiting'])) {
		$elements['Waiting']=$_REQUEST['elements_Waiting'];
	}
	if (isset( $_REQUEST['elements_Importing'])) {
		$elements['Importing']=$_REQUEST['elements_Importing'];
	}
	if (isset( $_REQUEST['elements_Imported'])) {
		$elements['Imported']=$_REQUEST['elements_Imported'];
	}
	if (isset( $_REQUEST['elements_Error'])) {
		$elements['Error']=$_REQUEST['elements_Error'];
	}



	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_order=$order;
	$_dir=$order_direction;


	foreach (array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'f_field'=>$f_field,'f_value'=>$f_value) as $key=>$item) {
		$_SESSION['state']['imported_records']['records'][$key]=$item;
	}
	$_SESSION['state']['imported_records']['records']['elements']=$elements;



	$where=sprintf(' where `Imported Record Parent Key`=%d ',
		$parent_key
	);

	$wheref='';
	if ($f_field=='note' and $f_value!=''  )
		$wheref.=" and  `Imported Record Subject Note` like '".addslashes($f_value)."%'    ";



	$_elements='';
	$num_elements_checked=0;
	foreach ($elements as $_key=>$_value) {
		if ($_value) {
			$num_elements_checked++;
			$_elements.=",'".addslashes($_key)."'";

		}
	}

	if ($_elements=='') {
		$where.=' and false' ;
	}elseif ($num_elements_checked==5) {

	}else {
		$_elements=preg_replace('/^,/','',$_elements);
		$where.=' and `Imported Record Import State` in ('.$_elements.')' ;
	}


	$sql="select count(distinct `Imported Record Key`) as total from `Imported Record` IR  $where $wheref";
//print $sql;

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref!='') {
	$sql="select count(distinct `Imported Record Key`) as total from `Imported Record` IR  $where ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	} else {
		$filtered=0;
		$total_records=$total;
	}

	mysql_free_result($res);

	$filter_msg='';


	$rtext=$total_records." ".ngettext('record','records',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp='('._("Showing all").')';

	switch ($f_field) {
	case('note'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no record with note")." <b>*".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('record with note')." <b>*".$f_value."*</b>)";
		break;


	}


	if ($order=='user')
		$order='`User Handle`';
	elseif ($order=='status')
		$order='`Imported Record Import State`';
	elseif ($order=='filename')
		$order='`Imported Records File Name`';
	elseif ($order=='records')
		$order='`Imported Original Records`';
	else
		$order='`Imported Record Index`';

	$sql="select * from `Imported Record` IR    $where $wheref order by $order $order_direction limit $start_from,$number_results";
	//print "$sql\n";
	$adata=array();
	$res=mysql_query($sql);
	while ($data=mysql_fetch_array($res)) {


		switch ($data['Imported Record Import State']) {
		case 'Ignored':
			$status=_('Ignored');
			break;
		case 'Waiting':
			$status=_('Waiting');
			break;
		case 'Importing':
			$status=_('Importing');
			break;
		case 'Imported':
			$status=_('Imported');
			break;
		case 'Error':
			$status=_('Error');
			break;
case 'Cancelled':
			$status=_('Cancelled');
			break;
		}


		$raw_data=$data['Imported Record Data'];

		

		$adata[]=array(
			
			'index'=>number($data['Imported Record Index']),
			'note'=>$data['Imported Record XHTML Note'],
			'status'=>$status,
			'data'=>'<span style="font-size:85%">'.$raw_data.'</span>'

		);
	}
	mysql_free_result($res);


	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,

			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);

	echo json_encode($response);
}



function get_imported_records_elements($data) {

	$elements_numbers=array('Uploading'=>0,'Review'=>0,'Queued'=>0,'InProcess'=>0,'Finished'=>0,'Cancelled'=>0);

	$sql=sprintf('select count(distinct `Imported Records Key`) as num , `Imported Records State` from `Imported Records Dimension` IR  where `Imported Records Subject`=%s and `Imported Records Parent`=%s and `Imported Records Parent Key`=%d group by `Imported Records State`',
		prepare_mysql($data['subject']),
		prepare_mysql($data['parent']),
		$data['parent_key']
	);

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$elements_numbers[$row['Imported Records State']]=number($row['num']);
	}

	$response=
		array('state'=>200,
		'elements_numbers'=>$elements_numbers

	);

	echo json_encode($response);


}


function get_records_elements($data) {




	$elements_numbers=array('Ignored'=>0,'Waiting'=>0,'Importing'=>0,'Imported'=>0,'Error'=>0,'Cancelled'=>0);

	$sql=sprintf('select count(distinct `Imported Record Key`) as num , `Imported Record Import State` from `Imported Record` IR  where `Imported Record Parent Key`=%d group by `Imported Record Import State`',
		$data['imported_records_key']
	);

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$elements_numbers[$row['Imported Record Import State']]=number($row['num']);
	}

	$response=
		array('state'=>200,
		'elements_numbers'=>$elements_numbers

	);

	echo json_encode($response);


}


?>
