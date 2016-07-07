<?php
/*
 File: User.php

 This file contains the User Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';


class User extends DB_Table {


	private $groups_read=false;
	private $rights_read=false;


	function User($a1='id', $a2=false, $a3=false) {
		global $db;
		$this->db=$db;

		$this->table_name='User';
		$this->ignore_fields=array(
			'User Key',
			'User Last Login'
		);

		if (($a1=='new'  )and is_array($a2)) {
			$this->find($a2, 'create');
			return;
		}

		if (($a1=='find'  )and is_array($a2)) {
			$this->find($a2, $a3);
			return;
		}


		if (is_numeric($a1) and !$a2) {
			$_data= $a1;
			$key='id';
		} else {
			$_data= $a2;
			$key=$a1;
		}

		$this->get_data($key, $_data, $a3);
		return;
	}


	function find($raw_data, $options='') {
		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key, $this->editor))
					$this->editor[$key]=$value;

			}
		}

		$create=false;
		$update=false;

		if (preg_match('/create/i', $options)) {
			$create='create';
		}
		if (preg_match('/update/i', $options)) {
			$update='update';
		}



		$data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key, $data)) {
				$data[$key]=_trim($value);
			}
		}

		if ($data['User Type']=='Customer') {
			$where_site=sprintf(" and `User Site Key`=%d", $data['User Site Key']);
		}else {
			$where_site='';
		}

		$sql=sprintf("select `User Key` from `User Dimension` where `User Type`=%s and `User Handle`=%s %s",
			prepare_mysql($data['User Type']),
			prepare_mysql($data['User Handle']),
			$where_site
		);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->found=true;
				$this->found_key=$row['User Key'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




		if (!$this->found and $data['User Type']=='Customer') {
			$sql=sprintf("select `User Key`,`User Site Key` from `User Dimension` where `User Type`='Customer' and  `User Active`='No' and `User Parent Key`=%d and `User Inactive Note`=%s ",
				$data['User Parent Key'],
				prepare_mysql($data['User Handle'])
			);




			if ($result2=$this->db->query($sql)) {
				if ($row2 = $result2->fetch()) {



					if ($this->reactivate($row2['User Key'], $data['User Handle'], $row2['User Site Key'])) {



						$this->found=true;
						$this->found_key=$row2['User Key'];

					}


				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}






		}


		if ($this->found) {
			$this->get_data('id', $this->found_key);

		}

		if (!$this->found and $create) {
			$this->create($raw_data);
		}


	}


	function create($data) {

		$this->new=false;
		$this->msg=_('Unknown Error').' (0)';
		$base_data=$this->base_data();

		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $base_data))
				$base_data[$key]=_trim($value);
		}

		$this->editor=$data['editor'];

		if ($base_data['User Created']=='')
			$base_data['User Created']=gmdate("Y-m-d H:i:s");

		if ($base_data['User Handle']=='') {
			$this->msg=_("Login can't be empty");
			return;
		}
		if (strlen($base_data['User Handle'])<4) {
			$this->msg=_('Login too short');
			$this->error=true;
			return;
		}

		if ($base_data['User Type']=='Customer') {
			$where_site=sprintf(" and `User Site Key`=%d", $base_data['User Site Key']);
		}else {
			$where_site='';
		}

		$sql=sprintf("select count(*) as numh  from `User Dimension` where `User Type`=%s and `User Handle`=%s %s",
			prepare_mysql($base_data['User Type']),
			prepare_mysql($base_data['User Handle']),
			$where_site
		);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				if ($row['numh']>0) {
					$this->error=true;
					$this->msg=_('Duplicate user login');
					return;
				}
			}else {
				$this->error=true;
				$this->msg= _('Unknown error');
				return;

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}





		if ($base_data['User Type']=='Staff') {

			$sql=sprintf("select `User Handle`  from `User Dimension` where `User Type`='Staff' and `User Parent Key`=%d", $data['User Parent Key']);

			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$this->msg=_('The staff member with id ')." ".$data['User Parent Key']." "._("is already in the database as")." ".$row['User Handle'];
					return;
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}



		}
		if ($base_data['User Type']=='Customer') {
			$sql=sprintf("update `Store Dimension` set `Store Total Users`=`Store Total Users`+1 where `Store Key`=%d", $base_data['User Site Key']);
			$this->db->exec($sql);
		}
		if ($base_data['User Type']=='Administrator') {
			$base_data['User Alias']=_('Administrator');
		}


		$keys='(';
		$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";
			if ($key=='User Inactive Note')
				$values.=prepare_mysql($value, false).",";
			else
				$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/', ')', $keys);
		$values=preg_replace('/,$/', ')', $values);
		$sql=sprintf("insert into `User Dimension` %s %s", $keys, $values);

		if ($this->db->exec($sql)) {



			$user_id=$this->db->lastInsertId();
			$this->get_data('id', $user_id);


			$this->new=true;
			$history_data=array(
				'History Abstract'=>sprintf(_('%s user record created'), $this->get('Handle')),
				'History Details'=>'',
				'Action'=>'created'
			);

			$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());


			$this->msg= _('User added successfully');


			$this->update_staff_type();

			if ($this->data['User Type']=='Staff' or $this->data['User Type']=='Administrator') {
				$sql=sprintf("insert into `User Staff Settings Dimension` (`User Key`) values (%d)  ",
					$this->id
				);
				$this->db->exec($sql);
				$this->get_data('id', $this->id);
			}

			return;
		} else {
			$this->error=true;
			$this->msg= _('Unknown error').' (2)';
			return;
		}

		$this->get_data('id', $user_id);



	}


	function get_data($key, $data, $data2='Staff') {
		global $_group;
		if ($key=='handle')
			$sql=sprintf("select * from  `User Dimension` where `User Handle`=%s and `User Type`=%s"
				, prepare_mysql($data)
				, prepare_mysql($data2)
			);
		elseif ($key=='Administrator')
			$sql=sprintf("select * from  `User Dimension` where  `User Type`='Administrator'"

			);
		elseif ($key=='Warehouse')
			$sql=sprintf("select * from  `User Dimension` where  `User Type`='Warehouse'"

			);

		else
			$sql=sprintf("select * from `User Dimension` where `User Key`=%d", $data);


		if ($this->data = $this->db->query($sql)->fetch()) {


			$this->id=$this->data['User Key'];
			$this->data['User Password']='';

			if ($this->data['User Type']=='Staff' or $this->data['User Type']=='Administrator'  or $this->data['User Type']=='Warehouse') {

				$sql=sprintf("select * from `User Staff Settings Dimension` where `User Key`=%d", $this->id);

				if ($row = $this->db->query($sql)->fetch()) {



					$this->data=array_merge($this->data, $row);
				}
			}
		}


	}


	function update_active($value) {
		$this->updated=false;

		$old_value=$this->get('Active');
		if (!preg_match('/^(Yes|No)$/', $value)) {
			$this->error=true;
			$this->msg=sprintf( _('Wrong value %s'), $value);
			return;
		}

		$this->update_field('User Active', $value);



		switch ($this->data['User Type']) {
		case 'Staff':
			include_once 'class.Staff.php';
			$staff=new Staff($this->data['User Parent Key']);
			$staff->editor=$this->editor;
			$staff->get_user_data();
			$new_value=$this->get('Active');
			$staff->add_changelog_record('Staff User Active', $old_value, $new_value, '', $staff->table_name, $staff->id);

			break;
		default:
			return;
			break;
		}




		$this->other_fields_updated=array(
			'User_Password'=>array(
				'field'=>'User_Password',
				'render'=>($this->get('User Active')=='Yes'?true:false),
				'value'=>$this->get('User Password'),
				'formatted_value'=>$this->get('Password'),


			),
			'User_PIN'=>array(
				'field'=>'User_PIN',
				'render'=>($this->get('User Active')=='Yes'?true:false),
				'value'=>$this->get('User PIN'),
				'formatted_value'=>$this->get('PIN'),


			)
		);

	}







	function update_websites($value) {
		$this->updated=false;

		if ($this->data['User Type']!='Staff') {
			$this->error=true;
			return;
		}
		$websites=preg_split('/,/', $value);
		foreach ($websites as $key=>$value) {
			if (!is_numeric($value) )
				unset($websites[$key]);
		}
		$old_websites=preg_split('/,/', $this->get_websites());

		$old_formatted_websites=$this->get_websites_formatted();
		$to_delete = array_diff($old_websites, $websites);
		$to_add = array_diff($websites, $old_websites);
		$changed=0;



		if (count($to_delete)>0) {
			$changed+=$this->delete_website($to_delete);
		}
		if (count($to_add)>0) {
			$changed+=$this->add_website($to_add);
		}

		$number_websites=$this->get_number_websites();

		if ($number_websites>0) {
			$sql=sprintf("select `User Group Key` from `User Group Dimension` where `User Group Name`='Webmaster' ");
			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$groups_changed=$this->add_group(array($row['User Group Key']));
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}


		}else {
			$this->read_groups();
			$sql=sprintf("select `User Group Key` from `User Group Dimension` where `User Group Name`='Webmaster' ");
			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$groups_changed=$this->delete_group(array($row['User Group Key']));
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}



		}


		if ($changed>0) {
			$this->updated=true;
		}

	}


	function update_groups($value) {

		$this->updated=false;

		$groups=preg_split('/,/', $value);
		foreach ($groups as $key=>$value) {
			if (!is_numeric($value) )
				unset($groups[$key]);
		}


		$this->read_groups();


		$old_groups=$this->groups_key_array;

		$to_delete = array_diff($old_groups, $groups);
		$to_add = array_diff($groups, $old_groups);



		$changed=0;
		if (count($to_delete)>0) {
			$changed+=$this->delete_group($to_delete);

		}
		if (count($to_add)>0) {
			$changed+=$this->add_group($to_add);

		}
		$this->read_groups();

		if ($changed>0) {
			$this->read_websites();
			$this->updated=true;
			$this->new_value=array('websites'=>$this->websites, 'groups'=>$this->groups_key_array);
		}


	}


	function update_field_switcher($field, $value, $options='', $metadata='') {


		if (is_string($value))
			$value=_trim($value);

		switch ($field) {
		case('Staff Position'):
		
		    if(!in_array($this->get('User Type'),array('Staff','Contractor')))return;
		
			include_once 'class.Staff.php';
			$employee=new Staff($this->get('User Parent Key'));
		
			if ($employee->id) {
				$employee->update_roles($value);
			}
			
			break;

		case('User Groups'):
			$this->update_groups($value);
			break;
		case('User Stores'):
			$this->update_stores($value);
			break;
		case('User Websites'):
			$this->update_websites($value);
			break;
		case('User Warehouses'):
			$this->update_warehouses($value);
			break;
		case('User Active'):
			$this->update_active($value);
			break;
		case('User Password'):
			$this->update_password($value, $options);
			break;
		case('User PIN'):
			$this->update_pin($value, $options);
			break;
		case('groups'):
			$this->update_groups($value);
			break;
		case('stores'):
			$this->update_stores($value);
			break;
		case('websites'):
			$this->update_websites($value);
			break;
		case('warehouses'):
			$this->update_warehouses($value);
			break;
		case('User Theme Key'):
		case('User Theme Background Key'):
			$this->update_staff_setting_field($tipo, $value);
			break;
		case('User Handle'):
			$old_value=$this->get('Handle');
			$this->update_field($field, $value, $options);
			switch ($this->data['User Type']) {
			case 'Staff':
				include_once 'class.Staff.php';
				$staff=new Staff($this->data['User Parent Key']);
				$staff->editor=$this->editor;
				$staff->get_user_data();
				$new_value=$this->get('Handle');
				$staff->add_changelog_record('Staff User Handle', $old_value, $new_value, '', $staff->table_name, $staff->id);

				break;
			default:
				return;
				break;
			}


			break;
		default:
			$base_data=$this->base_data();


			if (array_key_exists($field, $base_data)) {

				$this->update_field($field, $value, $options);
			}
		}
		$this->reread();
	}




	function update_staff_setting_field($field, $value, $options='') {

		$this->updated=false;


		$null_if_empty=true;

		if ($options=='no_null') {
			$null_if_empty=false;

		}

		if (is_array($value))
			return;
		$value=_trim($value);


		$old_value=_('Unknown');
		$key_field=$this->table_name." Key";


		$sql="select `".$field."` as value from  `User Staff Settings Dimension`  where `$key_field`=".$this->id;


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$old_value=$row['value'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




		$sql="update  `User Staff Settings Dimension` set `".$field."`=".prepare_mysql($value, $null_if_empty)." where `$key_field`=".$this->id;

		$update_op=$this->db->prepare($sql);
		$update_op->execute();
		$affected=$update_op->rowCount();




		if ($affected==-1) {
			$this->msg.=' '._('Record can not be updated')."\n";
			$this->error_updated=true;
			$this->error=true;

			return;
		}
		elseif ($affected==0) {
			$this->data[$field]=$value;
		}
		else {



			$this->data[$field]=$value;
			$this->msg.=" $field "._('Record updated').", \n";
			$this->msg_updated.=" $field "._('Record updated').", \n";
			$this->updated=true;
			$this->new_value=$value;

			$save_history=true;
			if (preg_match('/no( |\_)history|nohistory/i', $options))
				$save_history=false;

			if (
				preg_match('/site|page|part|customer|contact|company|order|staff|supplier|address|telecom|user|store|product|company area|company department|position|category/i', $this->table_name)
				and !$this->new
				and $save_history
			) {

				$history_data=array(
					'Indirect Object'=>$field,
					'old_value'=>$old_value,
					'new_value'=>$value

				);
				if ($this->table_name=='Product Family')
					$history_data['direct_object']='Family';
				if ($this->table_name=='Product Department')
					$history_data['direct_object']='Department';

				$history_key=$this->add_history($history_data);
				if (
					in_array($this->table_name, array('Customer', 'Store', 'Product Department', 'Product Family', 'Product', 'Part', 'Supplier', 'Supplier Product'))) {
					$sql=sprintf("insert into `%s History Bridge` values (%d,%d,'No','No','Changes')", $this->table_name, $this->id, $history_key);
					$this->db->exec($sql);

				}

			}

		}

	}


	function update_password($value, $options='') {

		$this->update_field('User Password', $value, $options);

		switch ($this->data['User Type']) {
		case 'Staff':
			include_once 'class.Staff.php';
			$staff=new Staff($this->data['User Parent Key']);
			$staff->editor=$this->editor;
			$staff->get_user_data();
			$staff->add_changelog_record('Staff User Password', '******', '******', '', $staff->table_name, $staff->id);

			break;
		default:
			return;
			break;
		}


	}


	function update_pin($value, $options='') {


		switch ($this->data['User Type']) {
		case 'Staff':
			include_once 'class.Staff.php';
			$staff=new Staff($this->data['User Parent Key']);
			$staff->editor=$this->editor;
			$staff->get_user_data();
			$staff->update(array('Staff PIN'=>$value));
			break;
		default:
			return;
			break;
		}



	}


	function add_group($to_add, $history=true) {

		include 'conf/user_groups.php';

		$changed=0;
		foreach ($to_add as $group_key) {

			if (array_key_exists($group_key, $user_groups)) {
				$group_name=$user_groups[$group_key]['Name'];


				$sql=sprintf("insert into `User Group User Bridge`values (%d,%d) ", $this->id, $group_key);
				$_changed = $this->db->exec($sql);
				if ($_changed>0) {
					$changed++;

					$history_data=array(
						'History Abstract'=>sprintf(_("User's was added to group %s"),  $user_groups[$group_key]['Name']  ),
						'History Details'=>'',
						'Action'=>'disassociate',
						'Indirect Object'=>'User Group',
						'Indirect Object Key'=>$group_key
					);
					$history_key=$this->add_history($history_data);
					$sql=sprintf("insert into `%s History Bridge` values (%d,%d,'No','No','Changes')", $this->table_name, $this->id, $history_key);
					$this->db->exec($sql);

				}

			}

		}
		return $changed;
	}


	function delete_group($to_delete, $history=true) {

		include 'conf/user_groups.php';

		$changed=0;
		foreach ($to_delete as $group_key) {

			$sql=sprintf("delete from `User Group User Bridge` where `User Key`=%d and `User Group Key`=%d ", $this->id, $group_key);
			$_changed = $this->db->exec($sql);

			if ($_changed>0) {
				$changed++;


				$history_data=array(
					'History Abstract'=>sprintf(_("User's was removed from group %s"),  $user_groups[$group_key]['Name']  ),
					'History Details'=>'',
					'Action'=>'disassociate',
					'Indirect Object'=>'User Group',
					'Indirect Object Key'=>$group_key
				);
				$history_key=$this->add_history($history_data);
				$sql=sprintf("insert into `%s History Bridge` values (%d,%d,'No','No','Changes')", $this->table_name, $this->id, $history_key);
				$this->db->exec($sql);


				//if ($group_key==10) {
				// $this->update_groups('', 'no_history');
				//}





			}
		}



		return $changed;
	}



	function add_website($to_add, $history=true) {
		$changed=0;
		foreach ($to_add as $scope_id) {

			$website=new Site($scope_id);
			if (!$website->id)
				continue;
			$sql=sprintf("insert into `User Right Scope Bridge`values (%d,'Website',%d) ", $this->id, $scope_id);
			$update_op=$this->db->prepare($sql);
			$update_op->execute();
			$affected=$update_op->rowCount();

			if ($affected>0) {
				$changed++;


				$history_data=array(
					'History Abstract'=>sprintf(_("User's rights for website %s were granted"), $website->data['Site Code']),
					'History Details'=>'',
					'Action'=>'disassociate',
					'Indirect Object'=>'Website',
					'Indirect Object Key'=>$website->id
				);

				$history_key=$this->add_history($history_data);
				$sql=sprintf("insert into `%s History Bridge` values (%d,%d,'No','No','Changes')", $this->table_name, $this->id, $history_key);
				$this->db->exec($sql);
			}
		}
		return $changed;

	}


	function delete_website($to_delete, $history=true) {
		$changed=0;
		foreach ($to_delete as $website_key) {

			$sql=sprintf("delete from `User Right Scope Bridge` where `User Key`=%d and `Scope Key`=%d and `Scope`='Website' ", $this->id, $website_key);
			$_changed = $this->db->exec($sql);
			$changed+=$_changed;

			$website=new Site($website_key);
			if ($website->id and $_changed) {
				$history_data=array(
					'History Abstract'=>sprintf(_("User's rights for website %s were removed"), $website->data['Site Code']),
					'History Details'=>'',
					'Action'=>'disassociate',
					'Indirect Object'=>'Website',
					'Indirect Object Key'=>$website->id
				);
				$history_key=$this->add_history($history_data);
				$sql=sprintf("insert into `%s History Bridge` values (%d,%d,'No','No','Changes')", $this->table_name, $this->id, $history_key);
				$this->db->exec($sql);
			}



		}


		return $changed;
	}


	function add_warehouse($to_add, $history=true) {
		$changed=0;
		foreach ($to_add as $scope_id) {

			$warehouse=new Warehouse($scope_id);
			if (!$warehouse->id)
				continue;
			$sql=sprintf("insert into `User Right Scope Bridge`values (%d,'Warehouse',%d) ", $this->id, $scope_id);
			$update_op=$this->db->prepare($sql);
			$update_op->execute();
			$affected=$update_op->rowCount();

			if ($affected>0) {
				$changed++;


				$history_data=array(
					'History Abstract'=>sprintf(_("User's rights for warehouse %s were granted"), $warehouse->data['Warehouse Code']),
					'History Details'=>'',
					'Action'=>'disassociate',
					'Indirect Object'=>'Warehouse',
					'Indirect Object Key'=>$warehouse->id
				);

				$history_key=$this->add_history($history_data);
				$sql=sprintf("insert into `%s History Bridge` values (%d,%d,'No','No','Changes')", $this->table_name, $this->id, $history_key);
				$this->db->exec($sql);
			}
		}
		return $changed;

	}


	function delete_warehouse($to_delete, $history=true) {

		include_once 'class.Warehouse.php';
		$changed=0;
		foreach ($to_delete as $warehouse_key) {

			$sql=sprintf("delete from `User Right Scope Bridge` where `User Key`=%d and `Scope Key`=%d and `Scope`='Warehouse' ", $this->id, $warehouse_key);
			$_changed = $this->db->exec($sql);
			$changed+=$_changed;

			$warehouse=new Warehouse($warehouse_key);
			if ($warehouse->id and $_changed) {
				$history_data=array(
					'History Abstract'=>sprintf(_("User's rights for warehouse %s were removed"), $warehouse->data['Warehouse Code']),
					'History Details'=>'',
					'Action'=>'disassociate',
					'Indirect Object'=>'Warehouse',
					'Indirect Object Key'=>$warehouse->id
				);
				$history_key=$this->add_history($history_data);
				$sql=sprintf("insert into `%s History Bridge` values (%d,%d,'No','No','Changes')", $this->table_name, $this->id, $history_key);
				$this->db->exec($sql);
			}



		}


		return $changed;
	}


	function update_warehouses($value) {

		$this->updated=false;

		if ($this->data['User Type']!='Staff') {
			$this->error=true;
			return;
		}
		$warehouses=preg_split('/,/', $value);
		foreach ($warehouses as $key=>$value) {
			if (!is_numeric($value) )
				unset($warehouses[$key]);
		}
		$old_warehouses=preg_split('/,/', $this->get_warehouses());

		$old_formatted_warehouses=$this->get_warehouses_formatted();
		$to_delete = array_diff($old_warehouses, $warehouses);
		$to_add = array_diff($warehouses, $old_warehouses);
		$changed=0;



		if (count($to_delete)>0) {
			$changed+=$this->delete_warehouse($to_delete);
		}
		if (count($to_add)>0) {
			$changed+=$this->add_warehouse($to_add);
		}

		if ($changed>0) {
			$this->updated=true;
		}

	}


	function add_store($to_add, $history=true) {
		include_once 'class.Store.php';
		$changed=0;
		foreach ($to_add as $scope_id) {

			$store=new Store($scope_id);
			if (!$store->id)
				continue;
			$sql=sprintf("insert into `User Right Scope Bridge`values (%d,'Store',%d) ", $this->id, $scope_id);
			$update_op=$this->db->prepare($sql);
			$update_op->execute();
			$affected=$update_op->rowCount();

			if ($affected>0) {
				$changed++;


				$history_data=array(
					'History Abstract'=>sprintf(_("User's rights for store %s were granted"), $store->data['Store Code']),
					'History Details'=>'',
					'Action'=>'disassociate',
					'Indirect Object'=>'Store',
					'Indirect Object Key'=>$store->id
				);

				$history_key=$this->add_history($history_data);
				$sql=sprintf("insert into `%s History Bridge` values (%d,%d,'No','No','Changes')", $this->table_name, $this->id, $history_key);
				$this->db->exec($sql);
			}
		}
		return $changed;

	}


	function delete_store($to_delete, $history=true) {
		include_once 'class.Store.php';

		include_once 'class.Store.php';
		$changed=0;
		foreach ($to_delete as $store_key) {

			$sql=sprintf("delete from `User Right Scope Bridge` where `User Key`=%d and `Scope Key`=%d and `Scope`='Store' ", $this->id, $store_key);
			$_changed = $this->db->exec($sql);
			$changed+=$_changed;

			$store=new Store($store_key);
			if ($store->id and $_changed) {
				$history_data=array(
					'History Abstract'=>sprintf(_("User's rights for store %s were removed"), $store->data['Store Code']),
					'History Details'=>'',
					'Action'=>'disassociate',
					'Indirect Object'=>'Store',
					'Indirect Object Key'=>$store->id
				);
				$history_key=$this->add_history($history_data);
				$sql=sprintf("insert into `%s History Bridge` values (%d,%d,'No','No','Changes')", $this->table_name, $this->id, $history_key);
				$this->db->exec($sql);
			}



		}


		return $changed;
	}


	function update_stores($value) {

		$this->updated=false;

		if ($this->data['User Type']!='Staff') {
			$this->error=true;
			return;
		}
		$stores=preg_split('/,/', $value);
		foreach ($stores as $key=>$value) {
			if (!is_numeric($value) )
				unset($stores[$key]);
		}
		$old_stores=preg_split('/,/', $this->get_stores());

		$old_formatted_stores=$this->get_stores_formatted();
		$to_delete = array_diff($old_stores, $stores);
		$to_add = array_diff($stores, $old_stores);
		$changed=0;



		if (count($to_delete)>0) {
			$changed+=$this->delete_store($to_delete);
		}
		if (count($to_add)>0) {
			$changed+=$this->add_store($to_add);
		}

		if ($changed>0) {
			$this->updated=true;
		}

	}


	function get($key) {

		if (!$this->id)
			return;


		switch ($key) {

		case 'User Groups':
			return $this->get_groups();
			break;
		case 'Groups':
			return $this->get_groups_formatted();
			break;
		case 'User Stores':
			return $this->get_stores();
			break;
		case 'Stores':
			return $this->get_stores_formatted();
			break;
		case 'User Websites':
			return $this->get_websites();
			break;
		case 'Websites':
			return $this->get_websites_formatted();
			break;
		case 'User Warehouses':
			return $this->get_warehouses();
			break;
		case 'Warehouses':
			return $this->get_warehouses_formatted();
			break;



		case('User Password'):
		case('User PIN'):
			return '';
			break;
		case('Password'):
			return '******';
			break;
		case('PIN'):
			return '****';
			break;

		case('Preferred Locale'):


			include 'utils/available_locales.php';

			if (array_key_exists($this->data['User Preferred Locale'], $available_locales)) {
				$locale=$available_locales[$this->data['User Preferred Locale']];

				return $locale['Language Name'].($locale['Language Name']!=$locale['Language Original Name']?' ('.$locale['Language Original Name'].')':'');
			}else {

				return $this->data['User Preferred Locale'];
			}
			break;

		case('Active'):

			switch ( $this->data['User Active']) {
			case('Yes'):
				$formatted_value=_('Yes');
				break;
			case('No'):
				$formatted_value=_('No');
				break;

			default:
				$formatted_value=$this->data['User Active'];
			}

			return $formatted_value;

			break;


		case('Login Count'):
		case('Failed Login Count'):
			return number($this->data['User '.$key]);
			break;

		case('Created '):
		case('Last Failed Login'):
		case('Last Login'):
			if ($this->data ['User '.$key]=='' or $this->data ['User '.$key]=='0000-00-00 00:00:00')
				return '';
			else
				return strftime("%a %e %b %Y %H:%M %Z", strtotime( $this->data ['User '.$key]." +00:00" ) );
			break;

		case('isactive'):
			return $this->data['Is Active'];
			break;
		case('groups'):
			return $this->data['groups'];
			break;

		case('Staff Position'):
		case('Position'):
			include_once 'class.Staff.php';
			$employee=new Staff($this->get_staff_key());
			return $employee->get($key);
			break;
		default:
			if (array_key_exists($key, $this->data))
				return $this->data[$key];

			if (array_key_exists('User '.$key, $this->data))
				return $this->data['User '.$key];

		}

	}


	function get_field_label($field) {

		switch ($field) {




		case 'User Active':
			$label=_('active');
			break;
		case 'User Handle':
			$label=_('login');
			break;

		case 'User Password':
			$label=_('password');
			break;

		case 'User PIN':
			$label=_('PIN');
			break;

		case 'Preferred Locale':
			$label=_('language');
			break;
		case 'User Password Recovery Email':
			$label=_("recovery email");
			break;
		case 'User Password Recovery Mobile':
			$label=_("recovery mobile");
			break;
		case 'User Stores':
			$label=_('stores');
			break;
		case 'User Websites':
			$label=_('websites');
			break;
		case 'User Warehouses':
			$label=_('warehouses');
			break;		
		default:
			$label=$field;

		}

		return $label;

	}


	function get_staff_key() {
		$staff_key=0;
		if ($this->data['User Type']=='Staff' or $this->data['User Type']=='Contractor') {
			$staff_key=$this->data['User Parent Key'];
		}else {
			$staff_key=0;
		}
		return $staff_key;
	}


	function get_staff_alias() {
		$staff_alias='';
		$staff_key=$this->get_staff_key();
		if ($staff_key) {
			$staff=new Staff($staff_key);
			$staff_alias=$staff->data['Staff Alias'];
		}
		return $staff_alias;
	}


	function get_staff_name() {
		$staff_name='';
		$staff_key=$this->get_staff_key();
		if ($staff_key) {
			$staff=new Staff($staff_key);
			$staff_name=$staff->data['Staff Name'];
		}
		return $staff_name;
	}


	function get_customer_key() {
		$customer_key=0;
		if ($this->data['User Type']=='Customer') {
			$customer_key=$this->data['User Parent Key'];
		}else {
			$customer_key=0;
		}
		return $customer_key;
	}


	function get_customer_name() {
		$customer_name='';
		$customer_key=$this->get_customer_key();
		if ($customer_key) {
			$customer=new Customer($customer_key);
			$customer_name=$customer->data['Customer Name'];
		}
		return $customer_name;
	}


	function get_number_suppliers() {
		return count($this->suppliers);
	}


	//function get_number_warehouses() {
	// return count($this->warehouses);
	//}


	// function get_number_stores() {
	//  return count($this->stores);
	// }


	// function get_number_websites() {
	//  return count($this->websites);
	// }


	function is($tag='') {
		if (strtolower($this->data['User Type'])==strtolower($tag)) {
			return true;
		} else
			return false;

	}


	function can_view($tag, $tag_key=false) {

		return $this->can_do('View', $tag, $tag_key);

	}


	function can_create($tag, $tag_key=false) {
		return $this->can_do('Create', $tag, $tag_key);
	}


	function can_edit($tag, $tag_key=false) {
		return $this->can_do('Edit', $tag, $tag_key);
	}


	function can_delete($tag, $tag_key=false) {
		return $this->can_do('Delete', $tag, $tag_key);
	}


	function can_do($right_type, $tag, $tag_key=false) {


		if (!is_string($tag))
			return false;
		$tag=strtolower(_trim($tag));



		if ($tag_key==false) {
			if (isset($this->rights_allow[$right_type][$tag])) {

				return true;
			}else {
				return false;
			}
		}




		//    return $this->can_do_any($right_type,$tag);
		//  if(!is_numeric($tag_key) or $tag_key<=0 or !preg_match('/^\d+$/',$tag_key) )
		//     return false;
		//  return $this->can_do_this_key($right_type,$tag,$tag_key);

	}


	function can_do_anyx($right_type, $tag) {

		if (array_key_exists($tag, $this->rights_allow[$right_type]))
			return true;
		else
			return false;
	}


	function get_groups() {
		$groups=array();
		$sql=sprintf("select GROUP_CONCAT(`User Group Key`) as groups from `User Group User Bridge` UGUB  where UGUB.`User Key`=%d", $this->id);
		if ($row = $this->db->query($sql)->fetch()) {
			$groups=$row['groups'];
		}
		return $groups;
	}


	function get_number_groups() {
		$number_groups=0;
		$sql=sprintf("select count(*) as groups from `User Group User Bridge` UGUB  where UGUB.`User Key`=%d", $this->id);
		if ($row = $this->db->query($sql)->fetch()) {
			$number_groups=$row['groups'];
		}
		return $number_groups;
	}


	function get_groups_formatted() {

		$number_groups=$this->get_number_groups();

		if ($number_groups==0) {
			return '<span class="none" ><i class="fa fa-toggle-off"></i> '._('none').'</span>';
		}if ($number_groups==12) {
			return '<span class="all" ><i class="fa fa-toggle-on"></i> '._('all').'</span>';
		}else {

			include 'conf/user_groups.php';
			$groups=array();
			$sql=sprintf("select `User Group Key` as `key` from `User Group User Bridge` UGUB  where UGUB.`User Key`=%d", $this->id);
			foreach ($this->db->query($sql) as $row) {
				if (isset($user_groups[$row['key']]))
					$groups[]=$user_groups[$row['key']]['Name'];
			}
			return join($groups, ', ');
		}
	}


	function get_stores() {
		$stores=array();
		$sql=sprintf("select GROUP_CONCAT(`Scope Key`) as stores  from `User Right Scope Bridge` where `User Key`=%d and `Scope`='Store'", $this->id);
		if ($row = $this->db->query($sql)->fetch()) {
			$stores=$row['stores'];
		}
		return $stores;
	}


	function get_number_stores() {
		$number_stores=0;
		$sql=sprintf("select count(*) as stores from `User Right Scope Bridge` where `User Key`=%d and `Scope`='Store'", $this->id);
		if ($row = $this->db->query($sql)->fetch()) {
			$number_stores=$row['stores'];
		}
		return $number_stores;
	}


	function get_stores_formatted() {
		global $account;

		$number_stores=$this->get_number_stores();

		if ($number_stores==0) {
			return '<span class="none" ><i class="fa fa-toggle-off"></i> '._('none').'</span>';
		}if ($number_stores==$account->get('Stores')) {
			return '<span class="all" ><i class="fa fa-toggle-on"></i> '._('all').'</span>';
		}else {

			$stores=array();
			$sql=sprintf("select `Scope Key`,`Store Code`,`Store Name` as `key` from `User Right Scope Bridge`  left join `Store Dimension` on (`Store Key`=`Scope Key`) where `User Key`=%d and `Scope`='Store'", $this->id);
			foreach ($this->db->query($sql) as $row) {

				$stores[]=$row['Store Code'];
			}
			return join($stores, ', ');
		}
	}


	function get_websites() {
		$websites=array();
		$sql=sprintf("select GROUP_CONCAT(`Scope Key`) as websites  from `User Right Scope Bridge` where `User Key`=%d and `Scope`='Website'", $this->id);
		if ($row = $this->db->query($sql)->fetch()) {
			$websites=$row['websites'];
		}
		return $websites;
	}


	function get_number_websites() {
		$number_websites=0;
		$sql=sprintf("select count(*) as websites from `User Right Scope Bridge` where `User Key`=%d and `Scope`='Website'", $this->id);
		if ($row = $this->db->query($sql)->fetch()) {
			$number_websites=$row['websites'];
		}
		return $number_websites;
	}


	function get_websites_formatted() {
		global $account;

		$number_websites=$this->get_number_websites();

		if ($number_websites==0) {
			return '<span class="none" ><i class="fa fa-toggle-off"></i> '._('none').'</span>';
		}if ($number_websites==$account->get('Websites')) {
			return '<span class="all" ><i class="fa fa-toggle-on"></i> '._('all').'</span>';
		}else {

			$websites=array();
			$sql=sprintf("select `Scope Key`,`Site Code`,`Site Name` as `key` from `User Right Scope Bridge`  left join `Site Dimension` on (`Site Key`=`Scope Key`) where `User Key`=%d and `Scope`='Website'", $this->id);

			foreach ($this->db->query($sql) as $row) {
				if ($row['Site Code']!='')
					$websites[]=$row['Site Code'];
			}
			return join($websites, ', ');
		}
	}


	function get_warehouses() {
		$warehouses=array();
		$sql=sprintf("select GROUP_CONCAT(`Scope Key`) as warehouses  from `User Right Scope Bridge` where `User Key`=%d and `Scope`='Warehouse'", $this->id);
		if ($row = $this->db->query($sql)->fetch()) {
			$warehouses=$row['warehouses'];
		}
		return $warehouses;
	}


	function get_number_warehouses() {
		$number_warehouses=0;
		$sql=sprintf("select count(*) as warehouses from `User Right Scope Bridge` where `User Key`=%d and `Scope`='Warehouse'", $this->id);
		if ($row = $this->db->query($sql)->fetch()) {
			$number_warehouses=$row['warehouses'];
		}
		return $number_warehouses;
	}


	function get_warehouses_formatted() {
		global $account;

		$number_warehouses=$this->get_number_warehouses();
		if ($number_warehouses==0) {
			return '<span class="none" ><i class="fa fa-toggle-off"></i> '._('none').'</span>';
		}if ($number_warehouses==$account->get('Warehouses')) {
			return '<span class="all" ><i class="fa fa-toggle-on"></i> '._('all').'</span>';
		}else {

			$warehouses=array();
			$sql=sprintf("select `Scope Key`,`Warehouse Code`,`Warehouse Name` as `key` from `User Right Scope Bridge`  left join `Warehouse Dimension` on (`Warehouse Key`=`Scope Key`) where `User Key`=%d and `Scope`='Warehouse'", $this->id);

			foreach ($this->db->query($sql) as $row) {
				if ($row['Warehouse Code']!='')
					$warehouses[]=$row['Warehouse Code'];
			}
			return join($warehouses, ', ');
		}
	}






	function read_groups() {

		include 'conf/user_groups.php';
		$this->groups=array();
		$this->groups_key_list='';
		$this->groups_key_array=array();


		$sql=sprintf("select `User Group Key` from `User Group User Bridge`  where  `User Key`=%d", $this->id);
		//print $sql;
		//exit;
		if ($result=$this->db->query($sql)) {

			foreach ($result as $row) {

				if (isset($user_groups[$row['User Group Key']])) {

					$this->groups[$row['User Group Key']]=array('User Group Name'=>$user_groups[$row['User Group Key']]['Name']);
					$this->groups_key_list.=','.$row['User Group Key'];
					$this->groups_key_array[]=$row['User Group Key'];
				}
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		$this->groups_key_list=preg_replace('/^,/', '', $this->groups_key_list);



		$this->groups_read=true;
	}


	function read_warehouses() {

		$this->warehouses=array();
		$sql=sprintf("select * from `User Right Scope Bridge` where `User Key`=%d and `Scope`='Warehouse'"
			, $this->id);

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$this->warehouses[]=$row['Scope Key'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



	}


	function read_websites() {

		$this->websites=array();
		$sql=sprintf("select * from `User Right Scope Bridge` where `User Key`=%d and `Scope`='Website' "
			, $this->id);

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$this->websites[]=$row['Scope Key'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




	}


	function read_stores() {

		$this->stores=array();
		$sql=sprintf("select * from `User Right Scope Bridge` where `User Key`=%d and `Scope`='Store' "
			, $this->id);
		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$this->stores[]=$row['Scope Key'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



	}


	function read_suppliers() {

		$this->suppliers=array();
		$sql=sprintf("select * from `User Right Scope Bridge` where `User Key`=%d and `Scope`='Supplier' "
			, $this->id);

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$this->suppliers[]=$row['Scope Key'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		$this->supplier_righs='none';
		if ($this->data['User Type']=='Supplier') {
			$sql="select count(*) as num from `Supplier Dimension`";

			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$total_number_suppliers=$row['num'];
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}



			$total_number_allowed_suppliers=count($this->suppliers);
			if ($total_number_suppliers==$total_number_allowed_suppliers)
				$this->supplier_rights='all';
			else
				$this->supplier_righs='some';
		}
	}


	function read_rights() {

		include 'conf/user_groups.php';
		include 'conf/user_rights.php';

		$this->rights_allow['View']=array();
		$this->rights_allow['Delete']=array();
		$this->rights_allow['Edit']=array();
		$this->rights_allow['Create']=array();
		$this->rights=array();

		if (!$this->groups_read)
			$this->read_groups();



		$rights=array();
		foreach ($this->groups_key_array as $group_key) {
			//print "* $group_key *  ";
			//print_r($user_groups[$group_key]['Rights']);
			$rights=array_merge($rights, $user_groups[$group_key]['Rights']);
			//print_r($rights);
		}



		//print "****";

		$sql=sprintf("select group_concat(`Right Code`) as rights from `User Rights Bridge` where `User Key`=%d", $this->id);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				if ($row['rights']!='')
					$rights=array_merge($rights, preg_split('/,/', $row['rights']));
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		foreach ($rights as $right) {
			$right_data=$user_rights[$right];


			if ($right_data['Right Type']=='View') {
				$this->rights_allow['View'][$right_data['Right Name']]=1;

				$this->rights[$right_data['Right Name']]['View']='View';
			}
			if ($right_data['Right Type']=='Delete') {
				$this->rights_allow['Delete'][$right_data['Right Name']]=1;
				$this->rights[$right_data['Right Name']]['Delete']='Delete';
			}
			if ($right_data['Right Type']=='Edit') {
				$this->rights_allow['Edit'][$right_data['Right Name']]=1;
				$this->rights[$right_data['Right Name']]['Edit']='Edit';
			}
			if ($right_data['Right Type']=='Create') {
				$this->rights_allow['Create'][$right_data['Right Name']]=1;
				$this->rights[$right_data['Right Name']]['Create']='Create';
			}



		}
		//print_r($this->groups_key_array);
		//print_r($this->rights_allow);
		//exit;
	}


	function can_view_list($right_name) {
		$list=array();

		if (isset($this->rights_allow['View'][$right_name])) {
			$rights_data=$this->rights_allow['View'][$right_name];
			if ($rights_data['Right Access']=='All') {

				switch ($right_name) {
				case('stores'):
					$sql=sprintf('select `Store Key`  from `Store Dimension`');

					if ($result=$this->db->query($sql)) {
						foreach ($result as $row) {
							$list[]=$row['Store Key'];
						}
					}else {
						print_r($error_info=$this->db->errorInfo());
						exit;
					}



					break;
				}

			}

		}

		return $list;
	}


	function forgot_password() {



		//global $secret_key,$public_url;

		$sql=sprintf("select `Site Secret Key`,`Site URL` from `Site Dimension` where `Site Store Key`=%s", $this->data['User Site Key']);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$secret_key=$row['Site Secret Key'];
				$url=$row['Site URL'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}







		$user_key=$this->data['User Key'];


		if ($user_key) {


			$user=new User($user_key);
			$customer=new Customer($this->data['User Parent Key']);



			$email_credential_key=1;



			$signature_name='';
			$signature_company='';

			$master_key=$user_key.'x'.generatePassword(6, 10);




			$sql=sprintf("insert into `MasterKey Dimension` (`Key`,`User Key`,`Valid Until`,`IP`) values (%s,%d,%s,%s) ",
				prepare_mysql($master_key),
				$user_key,
				prepare_mysql(date("Y-m-d H:i:s", strtotime("now +24 hours"))),
				prepare_mysql(ip())
			);

			$this->db->exec($sql);



			//json_encode(array('D'=>generatePassword(2,10).date('U') ,'C'=>$user_key ));
			//$encrypted_secret_data=base64_encode(AESEncryptCtr($secret_data,$secret_key.$store_key,256));



			$encrypted_secret_data=base64_encode(AESEncryptCtr($master_key, $secret_key, 256));


			$plain_message=$customer->get('greetings')."\n\n We received request to reset the password associated with this email account.\n\nIf you did not request to have your password reset, you can safely ignore this email. We assure that yor customer account is safe.\n\nCopy and paste the following link to your browser's address window.\n\n ".$url."?p=".$encrypted_secret_data."\n\n Once you hace returned our page you will be asked to choose a new password\n\nThank you \n\n".$signature_name."\n".$signature_company;


			$html_message=$customer->get('greetings')."<br/>We received request to reset the password associated with this email account.<br><br>
		If you did not request to have your password reset, you can safely ignore this email. We assure that yor customer account is safe.<br><br>
		<b>Click the link below to reset your password</b>
		<br><br>
		<a href=\"".$url."?p=".$encrypted_secret_data."\">".$url."?p=".$encrypted_secret_data."</a>
		<br></br>
		If clicking the link doesn't work you can copy and paste it into your browser's address window. Once you have returned to our website, you will be asked to choose a new password.
		<br><br>
		Thank you";



			//$to='rulovico@gmail.com';
			$to='migara@inikoo.com';
			$data=array(
				'type'=>'HTML',
				'subject'=> 'Reset your password',
				'plain'=>$plain_message,
				'email_credentials_key'=>$email_credential_key,
				'to'=>$to,
				'html'=>$html_message,

			);




			$send_email=new SendEmail();

			$send_email->smtp('HTML', $data);

			$result=$send_email->send();

			if ($result['msg']=='ok') {
				$response=array('state'=>200, 'result'=>'send');
				echo json_encode($response);
				exit;

			}else {
				print_r($result);
				$response=array('state'=>200, 'result'=>'error');
				echo json_encode($response);
				exit;
			}


		}
		else {
			$response=array('state'=>200, 'result'=>'handle_not_found');
			echo json_encode($response);
			exit;
		}

	}


	function update_staff_type() {

		if ($this->data['User Type']!='Staff') {
			$this->data['User Staff Type']='';

		}
		else {

			$staff=new Staff($this->data['User Parent Key']);
			if ($staff->data['Staff Currently Working']=='Yes') {
				$this->data['User Staff Type']='Working';


			}else {
				$this->data['User Staff Type']='NotWorking';

			}


		}
		$sql=sprintf("update `User Dimension` set `User Staff Type`=%s where `User Key`=%d",
			prepare_mysql($this->data['User Staff Type']),
			$this->id
		);
		$this->db->exec($sql);
	}


	function add_image($image_key) {

		$sql=sprintf("select `Image Key` from `Image Bridge` where `Subject Key`=%d and `Subject Type`='User Profile'", $this->id);
		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$image_id=$row['Image Key'];
				if ($image_id==$image_key) {
					continue;
				}
				else
					$this->remove_image($image_id);
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}






		$sql=sprintf("select `Image Key`,`Is Principal` from `Image Bridge` where `Subject Type`='User Profile' and `Subject Key`=%d  and `Image Key`=%d", $this->id, $image_key);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->nochange=true;
				$this->msg=_('Image already uploaded');
				return;
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}





		$principal='Yes';


		$sql=sprintf("insert into `Image Bridge` values ('User Profile',%d,%d,%s,'')"
			, $this->id
			, $image_key
			, prepare_mysql($principal)

		);

		$this->db->exec($sql);


		if ($principal=='Yes') {
			$this->update_main_image($image_key);
		}


		$sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='User Profile' and   `Subject Key`=%d and  PIB.`Image Key`=%d"
			, $this->id
			, $image_key
		);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {

				if ($row['Image Height']!=0)
					$ratio=$row['Image Width']/$row['Image Height'];
				else
					$ratio=1;
				include_once 'utils/units_functions.php';
				$this->new_value=array(
					'name'=>$row['Image Filename'],
					'small_url'=>'image.php?id='.$row['Image Key'].'&size=small',
					'thumbnail_url'=>'image.php?id='.$row['Image Key'].'&size=thumbnail',
					'filename'=>$row['Image Filename'],
					'ratio'=>$ratio,
					'caption'=>$row['Image Caption'],
					'is_principal'=>$row['Is Principal'],
					'id'=>$row['Image Key'],
					'size'=>file_size($row['Image File Size']
					)
				);

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		$this->updated=true;
		$this->msg=_("image added");
	}


	function update_main_image($image_key) {

		$sql=sprintf("select `Image Key` from `Image Bridge` where `Subject Type`='User Profile' and `Subject Key`=%d  and `Image Key`=%d", $this->id, $image_key);
		if ($result=$this->db->query($sql)) {
			if (!$row = $result->fetch()) {
				$this->error=true;
				$this->msg='image not associated';
				return;
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}





		$sql=sprintf("update `Image Bridge` set `Is Principal`='Yes' where `Subject Type`='User Profile' and `Subject Key`=%d  and `Image Key`=%d", $this->id, $image_key);
		$this->db->exec($sql);


		$main_image_src='image.php?id='.$image_key.'&size=small';
		$main_image_key=$image_key;

		//$this->data['User Main Image']=$main_image_src;
		$this->data['User Main Image Key']=$main_image_key;
		$sql=sprintf("update `User Dimension` set `User Main Image Key`=%d where `User Key`=%d",
			$main_image_key,
			$this->id
		);

		$this->db->exec($sql);

		$this->updated=true;

	}


	function get_image() {
		$image=array();
		$sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='User Profile' and   `Subject Key`=%d", $this->id);

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {

				if ($row['Image Height']!=0)
					$ratio=$row['Image Width']/$row['Image Height'];
				else
					$ratio=1;
				// print_r($row);
				$image=array(
					'name'=>$row['Image Filename'],
					'small_url'=>'image.php?id='.$row['Image Key'].'&size=small',
					'thumbnail_url'=>'image.php?id='.$row['Image Key'].'&size=thumbnail',
					'filename'=>$row['Image Filename'],
					'ratio'=>$ratio, 'caption'=>$row['Image Caption'],
					'is_principal'=>$row['Is Principal'], 'id'=>$row['Image Key']);

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




		return $image;
	}


	function get_image_src() {
		$image=false;

		$sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='User Profile' and   `Subject Key`=%d", $this->id);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				if ($row['Image Height']!=0) {
					$ratio=$row['Image Width']/$row['Image Height'];
				}else {
					$ratio=1;
				}
				$image='image.php?id='.$row['Image Key'].'&size=small';
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		return $image;
	}


	function get_image_key() {
		$data=$this->get_image();

		$new_data=end($data);
		//print_r($new_data);
		return $new_data;
	}


	function remove_image($image_key) {

		$sql=sprintf("select `Image Key`,`Is Principal` from `Image Bridge` where `Subject Type`='User Profile' and `Subject Key`=%d  and `Image Key`=%d", $this->id, $image_key);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {


				$sql=sprintf("delete from `Image Bridge` where `Subject Type`='User Profile' and `Subject Key`=%d  and `Image Key`=%d", $this->id, $image_key);
				$this->db->exec($sql);
				$this->updated=true;


				$sql=sprintf("select `Image Key` from `Image Bridge` where `Subject Type`='User Profile' and `Subject Key`=%d  ", $this->id);

				if ($result2=$this->db->query($sql)) {
					if ($row2 = $result2->fetch()) {
						$this->update_main_image($row2['Image Key']) ;
					}
				}else {
					print_r($error_info=$this->db->errorInfo());
					exit;
				}

			} else {
				$this->error=true;
				$this->msg='image not associated';

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


	}


	function reactivate($user_key, $handle, $site_key) {


		$num_handles=0;
		$sql=sprintf("select count(*) as num_handles  from `User Dimension` where `User Type`='Customer' and `User Site Key`=%d and   `User Handle`=%s", $site_key, prepare_mysql($handle));

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$num_handles=$row['num_handles'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




		if (!$num_handles) {

			$sql=sprintf("update `User Dimension` set `User Handle`=%s,`User Inactive Note`='', `User Active`='Yes' where `User Key`=%d  "     ,

				prepare_mysql($handle),
				$user_key
			);
			$this->db->exec($sql);

			return true;
		}else {
			return false;

		}

	}


	function deactivate() {

		if ($this->data['User Active']=='No') {
			return;
		}

		switch ($this->data['User Type']) {
		case 'Customer';

			$sql=sprintf("update `User Dimension` set `User Handle`=%s,`User Inactive Note`=%s, `User Active`='No' where `User Key`=%d  "     ,
				prepare_mysql($this->id),
				prepare_mysql($this->data['User Handle']),
				$this->id
			);
			$this->db->exec($sql);
			$this->data['User Active']='No';
			$this->data['User Inactive Note']=$this->data['User Handle'];
			$this->data['User Handle']=$this->id;
			break;
		}

	}


	function update_request_data() {
		switch ($this->data['User Type']) {
		case 'Customer';

			$number_requests=0;
			$number_sessions=0;
			$last_request='';

			$sql=sprintf("select count(*) as num_request, count(distinct `User Session Key`) as num_sessions , max(`Date`) as date from `User Request Dimension` where  `User Key`=%d", $this->id);

			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$number_requests=$row['num_request'];
					$number_sessions=$row['num_sessions'];
					$last_request=$row['date'];
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}




			$sql=sprintf("update `User Dimension` set `User Requests Count`=%d,`User Sessions Count`=%d, `User Last Request`=%s where `User Key`=%d  "     ,
				$number_requests,
				$number_sessions,
				prepare_mysql($last_request),

				$this->id
			);
			$this->db->exec($sql);
			break;
		}


	}


	function update_table_export_field($table_key, $fields) {


		$sql=sprintf("select `Table Key` from `Table User Export Fields`  where `Table Key`=%d and `User Key`=%d", $table_key, $this->id);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {


				$sql=sprintf("update `Table User Export Fields`   set `Fields`=%s where `Table Key`=%d and `User Key`=%d",
					prepare_mysql($fields),
					$table_key,
					$this->id
				);
				$this->db->exec($sql);

			}else {
				$sql=sprintf("insert into `Table User Export Fields` values (%d,%d,%s) ",
					$table_key,
					$this->id,
					prepare_mysql($fields)
				);

				$this->db->exec($sql);

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




	}


	function get_tab_defaults($tab) {


		include 'conf/tabs.defaults.php';


		if (isset($tab_defaults[$tab])) {

			return $tab_defaults[$tab];
		}if (isset($tab_defaults_alias[$tab])) {
			return $tab_defaults[$tab_defaults_alias[$tab]];
		}

		exit("User class: error get_tab_defaults not configured: $tab");
	}


	function create_api_key($data) {

		$data['API Key User Key']=$this->id;
		$data['API Key Valid From']=gmdate('Y-m-d H:i:s');

		$api_key= new API_Key('create', $data);

		$this->create_user_error=$api_key->error;
		$this->create_user_msg=$api_key->msg;
		$this->api_key=$api_key;

		return $this->api_key;

	}


}


?>
