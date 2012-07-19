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


	function User($a1='id',$a2=false,$a3=false) {

		$this->table_name='User';
		$this->ignore_fields=array(
			'User Key',
			'User Last Login'
		);

		if (($a1=='new'  )and is_array($a2)) {
			$this->find($a2,'create');
			return;
		}

		if (($a1=='find'  )and is_array($a2)) {
			$this->find($a2,$a3);
			return;
		}


		if (is_numeric($a1) and !$a2) {
			$_data= $a1;
			$key='id';
		} else {
			$_data= $a2;
			$key=$a1;
		}

		$this->get_data($key,$_data,$a3);
		return;
	}


	function find($raw_data,$options='') {
		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;

			}
		}

		$create=false;
		$update=false;

		if (preg_match('/create/i',$options)) {
			$create='create';
		}
		if (preg_match('/update/i',$options)) {
			$update='update';
		}



		$data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key,$data)) {
				$data[$key]=_trim($value);
			}
		}

		if ($data['User Type']=='Customer') {
			$where_site=sprintf(" and `User Site Key`=%d",$data['User Site Key']);
		}else {
			$where_site='';
		}

		$sql=sprintf("select `User Key` from `User Dimension` where `User Type`=%s and `User Handle`=%s %s",
			prepare_mysql($data['User Type']),
			prepare_mysql($data['User Handle']),
			$where_site
		);

		//print $sql;

		$result = mysql_query($sql);

		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->found=true;
			$this->found_key=$row['User Key'];

		}



		if (!$this->found and $data['User Type']=='Customer') {
			$sql=sprintf("select `User Key`,`User Site Key` from `User Dimension` where `User Type`='Customer' and  `User Active`='No' and `User Parent Key`=%d and `User Inactive Note`=%s ",
				$data['User Parent Key'],
				prepare_mysql($data['User Handle'])
			);

			$result2 = mysql_query($sql);

			if ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {


				if ($this->reactivate($row2['User Key'],$data['User Handle'],$row2['User Site Key'])) {



					$this->found=true;
					$this->found_key=$row2['User Key'];

				}

			}

		}


		if ($this->found) {
			$this->get_data('id',$this->found_key);

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
			if (array_key_exists($key,$base_data))
				$base_data[$key]=_trim($value);
		}

		if ($base_data['User Theme Key']=='')
			$base_data['User Theme Key']=0;

		if ($base_data['User Theme Background Key']=='')
			$base_data['User Theme Background Key']=0;

		if ($base_data['User Created']=='')
			$base_data['User Created']=date("Y-m-d H:i:s");

		if ($base_data['User Handle']=='') {
			$this->msg=_('Wrong handle');
			return;
		}
		if (strlen($base_data['User Handle'])<4) {
			$this->msg=_('Handle to short');
			return;
		}

		if ($base_data['User Type']=='Customer') {
			$where_site=sprintf(" and `User Site Key`=%d",$base_data['User Site Key']);
		}else {
			$where_site='';
		}

		$sql=sprintf("select count(*) as numh  from `User Dimension` where `User Type`=%s and `User Handle`=%s %s",
			prepare_mysql($base_data['User Type']),
			prepare_mysql($base_data['User Handle']),
			$where_site
		);
		// print $sql;
		$result = mysql_query($sql) ;
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($row['numh']>0) {
				$this->error=true;
				$this->msg= _('The user')." ".$base_data['User Handle']." "._("is already in the database!");
				return;

			}
		} else {
			$this->error=true;
			$this->msg= _('Unknown error');
			return;

		}


		if ($base_data['User Type']=='Staff') {

			$sql=sprintf("select `User Handle`  from `User Dimension` where `User Type`='Staff' and `User Parent Key`=%d",$data['User Parent Key']);

			$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
			if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->msg=_('The staff member with id ')." ".$data['User Parent Key']." "._("is already in the database as")." ".$row['User Handle'];
				return;
			}

		}
		if ($base_data['User Type']=='Customer') {
			$sql=sprintf("update `Store Dimension` set `Store Total Users`=`Store Total Users`+1 where `Store Key`=%d",$base_data['User Site Key']);
			mysql_query($sql);
		}

		$keys='(';
		$values='values(';
		foreach ($base_data as $key=>$value) {
			$keys.="`$key`,";
			if ($key=='User Inactive Note')
				$values.=prepare_mysql($value,false).",";
			else
				$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `User Dimension` %s %s",$keys,$values);
		//print $sql;
		if (mysql_query($sql)) {

			$user_id=mysql_insert_id();

			$this->new=true;
			$this->msg= _('User added susesfully');
			$this->get_data('id',$user_id);
			$this->update_staff_type();



			return;
		} else {
			$this->error=true;
			$this->msg= _('Unknown error').' (2)';
			return;
		}

		$this->get_data('id',$user_id);



	}


	function get_data($key,$data,$data2='Staff') {
		global $_group;
		if ($key=='handle')
			$sql=sprintf("select * from  `User Dimension` where `User Handle`=%s and `User Type`=%s"
				,prepare_mysql($data)
				,prepare_mysql($data2)
			);
		elseif ($key=='Administrator')
			$sql=sprintf("select * from  `User Dimension` where  `User Type`='Administrator'"

			);
		elseif ($key=='Warehouse')
			$sql=sprintf("select * from  `User Dimension` where  `User Type`='Warehouse'"

			);

		else
			$sql=sprintf("select * from `User Dimension` where `User Key`=%d",$data);

		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->id=$this->data['User Key'];
			$this->data['User Password']='';
		}


	}


	function update_active($value) {
		$this->updated=false;

		if (preg_match('/^(activate|yes|si|1|true)$/i',$value))
			$value='Yes';
		if (preg_match('/^(des?activate|no|0|false)$/i',$value))
			$value='No';

		if (!preg_match('/^(Yes|No)$/',$value)) {
			$this->error=true;
			$thus->msg=_('Wrong value');
		}

		$sql=sprintf("update `User Dimension` set `User Active`=%s where `User Key`=%d  ",prepare_mysql($value),$this->id);
		mysql_query($sql);

		if (mysql_affected_rows()>0) {
			$this->updated=true;
			$this->data['User Active']=$value;
			$this->new_value=$value;
			if ($value=='Yes') {
				$history_data=array(
					'History Abstract'=>_('User Activated')
					,'History Details'=>_trim(_('User')." ".$this->data['User Alias']." (".$this->data['User Type'].")  "._('activated'))
					,'Action'=>'edited'

				);
			} else {
				$history_data=array(
					'History Abstract'=>_('User Desactivated')
					,'History Details'=>_trim(_('User')." ".$this->data['User Alias']." (".$this->data['User Type'].")  "._('deactivated'))
					,'Action'=>'edited'
				);

			}

			$this->add_history($history_data);
			$this->update_staff_type();
		} else {
			$this->msg=_('Nothing to change');
		}

	}


	function update_warehouses($value) {
		$this->updated=false;

		if ($this->data['User Type']!='Staff')
			return;
		$warehouses=preg_split('/,/',$value);
		foreach ($warehouses as $key=>$value) {
			if (!is_numeric($value) )
				unset($warehouses[$key]);
		}
		$this->read_warehouses();
		$old_warehouses=$this->warehouses;
		$to_delete = array_diff($old_warehouses, $warehouses);
		$to_add = array_diff($warehouses, $old_warehouses);
		$changed=0;
		if (count($to_delete)>0) {
			$changed+=$this->delete_warehouse($to_delete);
		}
		if (count($to_add)>0) {
			$changed+=$this->add_warehouse($to_add);
		}
		$this->read_warehouses();
		if ($changed>0) {
			$this->updated=true;
			$this->new_value=$this->warehouses;
		}
	}

	function update_stores($value) {
		$this->updated=false;

		if ($this->data['User Type']!='Staff')
			return;
		$stores=preg_split('/,/',$value);
		foreach ($stores as $key=>$value) {
			if (!is_numeric($value) )
				unset($stores[$key]);
		}
		$this->read_stores();
		$old_stores=$this->stores;
		$to_delete = array_diff($old_stores, $stores);
		$to_add = array_diff($stores, $old_stores);
		$changed=0;

		if (count($to_delete)>0) {
			$changed+=$this->delete_store($to_delete);
		}
		if (count($to_add)>0) {
			$changed+=$this->add_store($to_add);
		}
		$this->read_stores();
		if ($changed>0) {
			$this->updated=true;
			$this->new_value=$this->stores;
		}
	}

	function update_websites($value) {
		$this->updated=false;

		if ($this->data['User Type']!='Staff')
			return;
		$websites=preg_split('/,/',$value);
		foreach ($websites as $key=>$value) {
			if (!is_numeric($value) )
				unset($websites[$key]);
		}
		$this->read_websites();
		$old_websites=$this->websites;
		$to_delete = array_diff($old_websites, $websites);
		$to_add = array_diff($websites, $old_websites);
		$changed=0;

		if (count($to_delete)>0) {
			$changed+=$this->delete_website($to_delete);
		}
		if (count($to_add)>0) {
			$changed+=$this->add_website($to_add);
		}
		$this->read_websites();
		if ($changed>0) {
			$this->updated=true;
			$this->new_value=$this->websites;
		}
	}





	function update_groups($value) {

		$this->updated=false;

		//     global $_group;
		$groups=preg_split('/,/',$value);
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
			$this->updated=true;
			$this->new_value=$this->groups_key_array;
		}


	}


	public function update($tipo,$data='') {
		switch ($tipo) {
		case('isactive'):
			$this->update_active($data['value']);
			break;
		case('groups'):
			$this->update_groups($data['value']);
			break;
		case('stores'):
			$this->update_stores($data['value']);
			break;
		case('websites'):
			$this->update_websites($data['value']);
			break;
		case('warehouses'):
			$this->update_warehouses($data['value']);
			break;
		case('name'):
		case('alias'):
		case('User Alias'):
			$this->update_field('User Alias',$data['value']);
			break;
		case('User Theme Key'):
		case('User Theme Background Key'):

			$this->update_field($tipo,$data['value']);
			break;

		}


	}
	function xupdate($tipo,$data) {
		switch ($tipo) {
		case('isactive'):

			if ($data['value'])
				$value=1;
			else
				$value=0;
			if ($value==$this->data['isactive'])
				return array('ok'=>true);
			$old_value=$this->data['isactive'];
			$this->data['isactive']=$value;
			$this->save('isactive');
			$this->save_history('isactive',array('user_id'=>$data['user_id'],'date'=>date('Y-m-d H:i:s'),'old_value'=>$old_value   ));
			return array('ok'=>true);
			break;
		case('groups'):
			global $_group;
			$groups=split(',',$data['value']);
			foreach ($groups as $key=>$value) {
				if (!is_numeric($value) )
					unset($groups[$key]);
			}


			$old_groups=$this->data['groups'];
			// print_r($old_groups);
			// print_r($groups);
			$to_delete = array_diff($old_groups, $groups);
			$to_add = array_diff($groups, $old_groups);
			// print_r($to_delete);
			// print_r($to_add);

			$this->data['groups']=$groups;
			$this->data['groups_list']='';
			foreach ($this->data['groups'] as $group_id) {
				$this->data['groups_list'].=', '.$_group[$group_id];
			}
			$this->data['groups_list']=preg_replace('/^\,\s/','',$this->data['groups_list']);
			if (count($to_delete)>0) {
				$this->delete_group($to_delete);
				//$this->save_history('isactive',array('user_id'=>$data['user_id'],'date'=>date('Y-m-d H:i:s'),'old_value'=>$old_value   ));
			}
			if (count($to_add)>0) {
				$this->add_group($to_add);
				//$this->save_history('isactive',array('user_id'=>$data['user_id'],'date'=>date('Y-m-d H:i:s'),'old_value'=>$old_value   ));
			}

			return array('ok'=>true);
			break;
		}
	}



	function change_password($data) {

		if (strlen($data)!=64) {
			$this->error=true;
			$this->error_updated=true;
			$this->msg.=', Wrong password format ('.strlen($data).')';
			$this->msg_updated.=', Wrong password format';
			return;
		}

		$sql=sprintf("update `User Dimension` set `User Password`=%s where `User Key`=%d",prepare_mysql($data),$this->id);
		//print $sql;
		mysql_query($sql);
		$this->updated=true;

	}





	function add_group($to_add,$history=true) {
		$changed=0;
		foreach ($to_add as $group_id) {

			$sql=sprintf("select * from  `User Group Dimension` where `User Group Key`=%d",$group_id);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {
				$group_name=$row['User Group Name'];


				$sql=sprintf("insert into `User Group User Bridge`values (%d,%d) ",$this->id,$group_id);
				//print $sql;
				mysql_query($sql);
				if (mysql_affected_rows()>0) {
					$changed++;
					$history_data=array(
						'History Abstract'=>_('User added to Group')
						,'History Details'=>_trim(_('User')." ".$this->data['User Alias']." "._('added to')." ".$group_name)
						,'Action'=>'associate'
						,'Indirect Object'=>'Group'
						,'Indirect Object Key'=>$group_id
					);
					$this->add_history($history_data);
				}
			}


		}
		return $changed;
	}

	function delete_group($to_delete,$history=true) {
		$changed=0;
		foreach ($to_delete as $group_id) {
			$sql=sprintf("delete from `User Group User Bridge` where `User Key`=%d and `User Group Key`=%d ",$this->id,$group_id);
			//   print $sql;
			mysql_query($sql);

			if (mysql_affected_rows()>0) {
				$changed++;
				$history_data=array(
					'History Abstract'=>_('User deleted from Group')
					,'History Details'=>_trim(_('User')." ".$this->data['User Alias']." "._('removed from')." ".$this->groups[$group_id]['User Group Name'])
					,'Action'=>'disassociate'
					,'Indirect Object'=>'Group'
					,'Indirect Object Key'=>$group_id
				);
				$this->add_history($history_data);
			}
		}
		return $changed;
	}

	function add_store($to_add,$history=true) {
		$changed=0;
		foreach ($to_add as $scope_id) {

			$store=new Store($scope_id);
			if (!$store->id)
				continue;
			$sql=sprintf("insert into `User Right Scope Bridge`values (%d,'Store',%d) ",$this->id,$scope_id);
			mysql_query($sql);
			if (mysql_affected_rows()>0) {
				$changed++;
				$history_data=array(
					'History Abstract'=>_('User Rights Associated with Store')
					,'History Details'=>_trim(_('User')." ".$this->data['User Alias']." "._('rights associated with')." ".$store->data['Store Name'])
					,'Action'=>'associate'
					,'Indirect Object'=>'Store'
					,'Indirect Object Key'=>$store->id
				);
				$this->add_history($history_data);
			}
		}
		return $changed;

	}


	function delete_store($to_delete,$history=true) {
		$changed=0;
		foreach ($to_delete as $scope_id) {
			$store=new Store($scope_id);
			if (!$store->id)
				continue;
			$sql=sprintf("delete from `User Right Scope Bridge` where `User Key`=%d and `scope Key`=%d and `Scope`='Store' ",$this->id,$scope_id);
			mysql_query($sql);
		}
		if (mysql_affected_rows()>0) {
			$changed++;
			$history_data=array(
				'History Abstract'=>_('User Rights Disassociated with Store')
				,'History Details'=>_trim(_('User')." ".$this->data['User Alias']." "._('rights disassociated with')." ".$store->data['Store Name'])
				,'Action'=>'disassociate'
				,'Indirect Object'=>'Store'
				,'Indirect Object Key'=>$store->id
			);
			$this->add_history($history_data);

		}
		return $changed;
	}

	function add_website($to_add,$history=true) {
		$changed=0;
		foreach ($to_add as $scope_id) {

			$website=new Site($scope_id);
			if (!$website->id)
				continue;
			$sql=sprintf("insert into `User Right Scope Bridge`values (%d,'Website',%d) ",$this->id,$scope_id);
			mysql_query($sql);
			if (mysql_affected_rows()>0) {
				$changed++;
				$history_data=array(
					'History Abstract'=>_('User Rights Associated with Store')
					,'History Details'=>_trim(_('User')." ".$this->data['User Alias']." "._('rights associated with')." ".$website->data['Site Name'])
					,'Action'=>'associate'
					,'Indirect Object'=>'Site'
					,'Indirect Object Key'=>$website->id
				);
				$this->add_history($history_data);
			}
		}
		return $changed;

	}


	function delete_website($to_delete,$history=true) {
		$changed=0;
		foreach ($to_delete as $scope_id) {
			$website=new Site($scope_id);
			if (!$website->id)
				continue;
			$sql=sprintf("delete from `User Right Scope Bridge` where `User Key`=%d and `scope Key`=%d and `Scope`='Website' ",$this->id,$scope_id);
			mysql_query($sql);
		}
		if (mysql_affected_rows()>0) {
			$changed++;
			$history_data=array(
				'History Abstract'=>_('User Rights Disassociated with Website')
				,'History Details'=>_trim(_('User')." ".$this->data['User Alias']." "._('rights disassociated with')." ".$website->data['Site Name'])
				,'Action'=>'disassociate'
				,'Indirect Object'=>'Site'
				,'Indirect Object Key'=>$website->id
			);
			$this->add_history($history_data);

		}
		return $changed;
	}


	function add_warehouse($to_add,$history=true) {
		$changed=0;
		foreach ($to_add as $scope_id) {

			$warehouse=new Warehouse($scope_id);
			if (!$warehouse->id)
				continue;
			$sql=sprintf("insert into `User Right Scope Bridge`values (%d,'Warehouse',%d) ",$this->id,$scope_id);
			//print $sql;
			mysql_query($sql);
			if (mysql_affected_rows()>0) {
				$changed++;
				$history_data=array(
					'History Abstract'=>_('User Rights Associated with Warehouse')
					,'History Details'=>_trim(_('User')." ".$this->data['User Alias']." "._('rights associated with')." ".$warehouse->data['Warehouse Name'])
					,'Action'=>'associate'
					,'Indirect Object'=>'Warehouse'
					,'Indirect Object Key'=>$warehouse->id
				);
				$this->add_history($history_data);
			}
		}
		return $changed;

	}


	function delete_warehouse($to_delete,$history=true) {
		$changed=0;
		foreach ($to_delete as $scope_id) {
			$warehouse=new Warehouse($scope_id);
			if (!$warehouse->id)
				continue;
			$sql=sprintf("delete from `User Right Scope Bridge` where `User Key`=%d and `scope Key`=%d and `Scope`='Warehouse'",$this->id,$scope_id);
			mysql_query($sql);
		}
		if (mysql_affected_rows()>0) {
			$changed++;
			$history_data=array(
				'History Abstract'=>_('User Rights Disassociated with Warehouse')
				,'History Details'=>_trim(_('User')." ".$this->data['User Alias']." "._('rights disassociated with')." ".$warehouse->data['Warehouse Name'])
				,'Action'=>'disassociate'
				,'Indirect Object'=>'Warehouse'
				,'Indirect Object Key'=>$warehouse->id
			);
			$this->add_history($history_data);

		}
		return $changed;
	}


	function get($key) {

		//print $key;

		if (array_key_exists($key,$this->data))
			return $this->data[$key];

		switch ($key) {
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
				return strftime( "%e %b %Y %H:%M %Z", strtotime( $this->data ['User '.$key]." +00:00" ) );
			break;
		case('User Pasword'):
			return "******";
		case('isactive'):
			return $this->data['Is Active'];
		case('groups'):
			return $this->data['groups'];
		}

	}

	function get_number_suppliers() {
		return count($this->suppliers);
	}
	function get_number_warehouses() {
		return count($this->warehouses);
	}
	function get_number_stores() {
		return count($this->stores);
	}
	function get_number_websites() {
		return count($this->websites);
	}

	function is($tag='') {
		if (strtolower($this->data['User Type'])==strtolower($tag)) {
			return true;
		} else
			return false;

	}

	function can_view($tag,$tag_key=false) {

		return $this->can_do('View',$tag,$tag_key);

	}

	function can_create($tag,$tag_key=false) {
		return $this->can_do('Create',$tag,$tag_key);
	}
	function can_edit($tag,$tag_key=false) {
		return $this->can_do('Edit',$tag,$tag_key);
	}
	function can_delete($tag,$tag_key=false) {
		return $this->can_do('Delete',$tag,$tag_key);
	}


	function can_do($right_type,$tag,$tag_key=false) {

		//  print_r($this->rights_allow);

		if (!is_string($tag))
			return false;
		$tag=strtolower(_trim($tag));
		if ($tag_key==false) {
			if (isset($this->rights_allow[$right_type][$tag]))
				return true;
			else
				return false;
		}




		//    return $this->can_do_any($right_type,$tag);
		//  if(!is_numeric($tag_key) or $tag_key<=0 or !preg_match('/^\d+$/',$tag_key) )
		//     return false;
		//  return $this->can_do_this_key($right_type,$tag,$tag_key);

	}







	function can_do_anyx($right_type,$tag) {

		if (array_key_exists($tag,$this->rights_allow[$right_type]))
			return true;
		else
			return false;
	}


	function can_do_this_key($right_type,$tag,$tag_key) {




		if (isset($this->rights_allow[$right_type][$tag])) {
			if (array_key_exists($tag_key, $this->stores))
				return true;
			else
				false;
		} else
			return false;


	}


	function read_groups() {
		$this->groups=array();
		$this->groups_key_list='';
		$this->groups_key_array=array();

		$sql=sprintf("select * from `User Group User Bridge` UGUB left join `User Group Dimension` GD on (GD.`User Group Key`=UGUB.`User Group Key`) where UGUB.`User Key`=%d",$this->id);
		//print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$this->groups[$row['User Group Key']]=array('User Group Name'=>$row['User Group Name']);
			$this->groups_key_list.=','.$row['User Group Key'];
			$this->groups_key_array[]=$row['User Group Key'];
		}
		$this->groups_key_list=preg_replace('/^,/','', $this->groups_key_list);

		$this->groups_read=true;
	}



	function read_warehouses() {

		$this->warehouses=array();
		$sql=sprintf("select * from `User Right Scope Bridge` where `User Key`=%d and `Scope`='Warehouse'"
			, $this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$this->warehouses[]=$row['Scope Key'];
		}

	}


	function read_websites() {

		$this->websites=array();
		$sql=sprintf("select * from `User Right Scope Bridge` where `User Key`=%d and `Scope`='Website' "
			, $this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$this->websites[]=$row['Scope Key'];
		}


	}


	function read_stores() {

		$this->stores=array();
		$sql=sprintf("select * from `User Right Scope Bridge` where `User Key`=%d and `Scope`='Store' "
			, $this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$this->stores[]=$row['Scope Key'];
		}

	}

	function read_suppliers() {

		$this->suppliers=array();
		$sql=sprintf("select * from `User Right Scope Bridge` where `User Key`=%d and `Scope`='Supplier' "
			, $this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$this->suppliers[]=$row['Scope Key'];
		}
		$this->supplier_righs='none';
		if ($this->data['User Type']=='Supplier') {
			$sql="select count(*) as num_suppliers from `Supplier Dimension`";
			$res=mysql_query($sql);
			$row=mysql_fetch_array($res);
			$num_suppliers=$row['num_suppliers'];
			$num_suppliers=count($this->suppliers);
			if ($num_suppliers==$num_suppliers)
				$this->supplier_rights='all';
			else
				$this->supplier_righs='some';
		}
	}


	function read_rights() {

		$this->rights_allow['View']=array();
		$this->rights_allow['Delete']=array();
		$this->rights_allow['Edit']=array();
		$this->rights_allow['Create']=array();
		$this->rights=array();

		if (!$this->groups_read)
			$this->read_groups();

		if (count($this->groups)>0) {

			$sql=sprintf("select * from `User Group Rights Bridge`  UGRB left join `Right Dimension` RD on (RD.`Right Key`=UGRB.`Right Key`)  where `Group Key` in (%s)"
				, $this->groups_key_list);

			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {
				if ($row['Right Type']=='View') {
					$this->rights_allow['View'][$row['Right Name']]=array(
						'Right Name'=>$row['Right Name'],
						//'Right Access'=>$row['Right Access'],
						//'Right Access Keys'=>$row['Rigth Access Keys']
					);
					$this->rights[$row['Right Name']]['View']='View';
				}
				if ($row['Right Type']=='Delete') {
					$this->rights_allow['Delete'][$row['Right Name']]=$row['Right Name'];
					$this->rights[$row['Right Name']]['Delete']='Delete';
				}
				if ($row['Right Type']=='Edit') {
					$this->rights_allow['Edit'][$row['Right Name']]=array('Right Name'=>$row['Right Name']
						//,'Right Access'=>$row['Right Access'],'Rigth Access Keys'=>$row['Rigth Access Keys']
					);
					$this->rights[$row['Right Name']]['Edit']='Edit';
				}
				if ($row['Right Type']=='Create') {
					$this->rights_allow['Create'][$row['Right Name']]=$row['Right Name'];
					$this->rights[$row['Right Name']]['Create']='Create';
				}


			}
		}
		$sql=sprintf("select * from `User Rights Bridge`  URB left join  `Right Dimension` RD on (RD.`Right Key`=URB.`Right Key`)  where `User Key`=%d", $this->id);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_array($res)) {
			if ($row['Right Type']=='View') {
				$this->rights_allow['View'][$row['Right Name']]=$row['Right Name'];
				$this->rights[$row['Right Name']]['View']='View';
			}
			if ($row['Right Type']=='Delete') {
				$this->rights_allow['Delete'][$row['Right Name']]=$row['Right Name'];
				$this->rights[$row['Right Name']]['`Delete']='Delete';
			}
			if ($row['Right Type']=='Edit') {
				$this->rights_allow['Edit'][$row['Right Name']]=$row['Right Name'];
				$this->rights[$row['Right Name']]['Edit']='Edit';
			}
			if ($row['Right Type']=='Create') {
				$this->rights_allow['Create'][$row['Right Name']]=$row['Right Name'];
				$this->rights[$row['Right Name']]['Create']='Create';
			}
		}

		//print_r($this->rights_allow);

	}


	function can_view_list($right_name) {
		$list=array();

		if (isset($this->rights_allow['View'][$right_name])) {
			$rights_data=$this->rights_allow['View'][$right_name];
			if ($rights_data['Right Access']=='All') {

				switch ($right_name) {
				case('stores'):
					$sql=sprintf('select `Store Key`  from `Store Dimension`');

					$res=mysql_query($sql);
					while ($row=mysql_fetch_array($res)) {
						$list[]=$row['Store Key'];
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
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result)) {
			$secret_key=$row['Site Secret Key'];
			$url=$row['Site URL'];
		}





		$user_key=$this->data['User Key'];


		if ($user_key) {


			$user=new User($user_key);
			$customer=new Customer($this->data['User Parent Key']);



			$email_credential_key=1;



			$signature_name='';
			$signature_company='';

			$master_key=$user_key.'x'.generatePassword(6,10);




			$sql=sprintf("insert into `MasterKey Dimension` (`Key`,`User Key`,`Valid Until`,`IP`) values (%s,%d,%s,%s) ",
				prepare_mysql($master_key),
				$user_key,
				prepare_mysql(date("Y-m-d H:i:s",strtotime("now +24 hours"))),
				prepare_mysql(ip())
			);

			mysql_query($sql);



			//json_encode(array('D'=>generatePassword(2,10).date('U') ,'C'=>$user_key ));
			//$encrypted_secret_data=base64_encode(AESEncryptCtr($secret_data,$secret_key.$store_key,256));



			$encrypted_secret_data=base64_encode(AESEncryptCtr($master_key,$secret_key,256));


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
				$response=array('state'=>200,'result'=>'send');
				echo json_encode($response);
				exit;

			}else {
				print_r($result);
				$response=array('state'=>200,'result'=>'error');
				echo json_encode($response);
				exit;
			}


		}
		else {
			$response=array('state'=>200,'result'=>'handle_not_found');
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
				if ($this->data['User Active']=='Yes') {
					$this->data['User Staff Type']='Active Working';
				}else {
					$this->data['User Staff Type']='Inactive Working';
				}

			}else {
				if ($this->data['User Active']=='Yes') {
					$this->data['User Staff Type']='Active Not Working';
				}else {
					$this->data['User Staff Type']='Inactive Not Working';
				}

			}


		}
		$sql=sprintf("update `User Dimension` set `User Staff Type`=%s where `User Key`=%d",
			prepare_mysql($this->data['User Staff Type']),
			$this->id
		);
		mysql_query($sql);
	}


	function add_image($image_key) {

		$sql=sprintf("select `Image Key` from `Image Bridge` where `Subject Key`=%d and `Subject Type`='User Profile'", $this->id);
		$result=mysql_query($sql);
		while ($row=mysql_fetch_assoc($result)) {
			$image_id=$row['Image Key'];
			if ($image_id==$image_key) {
				continue;
			}
			else
				$this->remove_image($image_id);
		}




		///////////////////
		$sql=sprintf("select `Image Key`,`Is Principal` from `Image Bridge` where `Subject Type`='User Profile' and `Subject Key`=%d  and `Image Key`=%d",$this->id,$image_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->nochange=true;
			$this->msg=_('Image already uploaded');
			return;
		}



		$principal='Yes';


		$sql=sprintf("insert into `Image Bridge` values ('User Profile',%d,%d,%s,'')"
			,$this->id
			,$image_key
			,prepare_mysql($principal)

		);

		mysql_query($sql);


		if ($principal=='Yes') {
			$this->update_main_image($image_key);
		}


		$sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='User Profile' and   `Subject Key`=%d and  PIB.`Image Key`=%d"
			,$this->id
			,$image_key
		);

		$res=mysql_query($sql);

		if ($row=mysql_fetch_array($res)) {
			if ($row['Image Height']!=0)
				$ratio=$row['Image Width']/$row['Image Height'];
			else
				$ratio=1;
			$this->new_value=array('name'=>$row['Image Filename'],'small_url'=>'image.php?id='.$row['Image Key'].'&size=small','thumbnail_url'=>'image.php?id='.$row['Image Key'].'&size=thumbnail','filename'=>$row['Image Filename'],'ratio'=>$ratio,'caption'=>$row['Image Caption'],'is_principal'=>$row['Is Principal'],'id'=>$row['Image Key']);
			// $this->images_slideshow[]=$this->new_value;
		}

		$this->updated=true;
		$this->msg=_("image added");
	}

	function update_main_image($image_key) {

		$sql=sprintf("select `Image Key` from `Image Bridge` where `Subject Type`='User Profile' and `Subject Key`=%d  and `Image Key`=%d",$this->id,$image_key);
		$res=mysql_query($sql);
		if (!mysql_num_rows($res)) {
			$this->error=true;
			$this->msg='image not associated';
		}


		$sql=sprintf("update `Image Bridge` set `Is Principal`='Yes' where `Subject Type`='User Profile' and `Subject Key`=%d  and `Image Key`=%d",$this->id,$image_key);
		mysql_query($sql);


		$main_image_src='image.php?id='.$image_key.'&size=small';
		$main_image_key=$image_key;

		//$this->data['User Main Image']=$main_image_src;
		$this->data['User Main Image Key']=$main_image_key;
		$sql=sprintf("update `User Dimension` set `User Main Image Key`=%d where `User Key`=%d",
			$main_image_key,
			$this->id
		);

		mysql_query($sql);

		$this->updated=true;

	}

	function get_image() {
		$sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='User Profile' and   `Subject Key`=%d",$this->id);
		$res=mysql_query($sql);
		$image=array();
		while ($row=mysql_fetch_array($res)) {
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
				'ratio'=>$ratio,'caption'=>$row['Image Caption'],
				'is_principal'=>$row['Is Principal'],'id'=>$row['Image Key']);
		}
		// print_r($images_slideshow);

		return $image;
	}

	function get_image_src() {
		$sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='User Profile' and   `Subject Key`=%d",$this->id);
		$res=mysql_query($sql);
		$image=false;
		while ($row=mysql_fetch_array($res)) {
			if ($row['Image Height']!=0)
				$ratio=$row['Image Width']/$row['Image Height'];
			else
				$ratio=1;
			// print_r($row);
			$image='image.php?id='.$row['Image Key'].'&size=small';
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

		$sql=sprintf("select `Image Key`,`Is Principal` from `Image Bridge` where `Subject Type`='User Profile' and `Subject Key`=%d  and `Image Key`=%d",$this->id,$image_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$sql=sprintf("delete from `Image Bridge` where `Subject Type`='User Profile' and `Subject Key`=%d  and `Image Key`=%d",$this->id,$image_key);
			mysql_query($sql);
			$this->updated=true;


			$sql=sprintf("select `Image Key` from `Image Bridge` where `Subject Type`='User Profile' and `Subject Key`=%d  ",$this->id);
			$res2=mysql_query($sql);
			if ($row2=mysql_fetch_assoc($res2)) {
				$this->update_main_image($row2['Image Key']) ;
			}



		} else {
			$this->error=true;
			$this->msg='image not associated';

		}

	}

	function reactivate($user_key,$handle,$site_key) {


		$num_handles=0;
		$sql=sprintf("select count(*) as num_handles  from `User Dimension` where `User Type`='Customer' and `User Site Key`=%d and   `User Handle`=%s",$site_key,prepare_mysql($handle));
		$res=mysql_query($sql);




		if ($row=mysql_fetch_assoc($res)) {
			$num_handles=$row['num_handles'];
		}


		if (!$num_handles) {

			$sql=sprintf("update `User Dimension` set `User Handle`=%s,`User Inactive Note`='', `User Active`='Yes' where `User Key`=%d  "     ,

				prepare_mysql($handle),
				$user_key
			);
			mysql_query($sql);

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
			mysql_query($sql);
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

			$sql=sprintf("select count(*) as num_request, count(distinct `User Session Key`) as num_sessions , max(`Date`) as date from `User Request Dimension` where  `User Key`=%d",$this->id);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {

				$number_requests=$row['num_request'];
				$number_sessions=$row['num_sessions'];
				$last_request=$row['date'];

			}


			$sql=sprintf("update `User Dimension` set `User Requests Count`=%d,`User Sessions Count`=%d, `User Last Request`=%s where `User Key`=%d  "     ,
				$number_requests,
				$number_sessions,
				prepare_mysql($last_request),

				$this->id
			);
			mysql_query($sql);
			break;
		}


	}


	function get_table_export_fields($ar,$table) {

		$fields='';
		$sql=sprintf("select `Table Default Export Fields` from `Table Dimension` where `Table AR`=%s and `Table Name`=%s ",
			prepare_mysql($ar),
			prepare_mysql($table)
		);
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$fields=$row['Table Default Export Fields'];
		}

		return $fields;
	}

}

?>
