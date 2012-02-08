<?php
abstract class DB_Table {
	protected $table_name;
	protected  $ignore_fields=array();
	public $errors_while_updating=array();
	public $updated_fields=array();
	public $data=array();
	public  $id=0;
	public $warning=false;
	public $error=false;
	public $msg='';
	public $new=false;
	public $updated=false;
	public $new_value=false;
	public $error_updated=false;
	public $msg_updated='';
	public $found=false;
	public $found_key=false;
	public $no_history=false;
	public $candidate=array();
	public $updated_field=array();

	public $editor=array(
		'Author Name'=>false,
		'Author Alias'=>false,
		'Author Key'=>0,
		'User Key'=>0,
		'Date'=>false
	);


	function base_data() {


		$data=array();
		$result = mysql_query("SHOW COLUMNS FROM `".$this->table_name." Dimension`");
		if (!$result) {
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
		if (mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_assoc($result)) {
				if (!in_array($row['Field'],$this->ignore_fields))
					$data[$row['Field']]=$row['Default'];
			}
		}
		//print_r($data);
		return $data;
	}

	function base_history_data() {


		$data=array();
		$result = mysql_query("SHOW COLUMNS FROM `History Dimension`");
		if (!$result) {
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
		if (mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_assoc($result)) {
				if (!in_array($row['Field'],$this->ignore_fields))
					$data[$row['Field']]=$row['Default'];
			}
		}
		return $data;
	}

	public function update($data,$options='') {

		if (!is_array($data)) {

			$this->error=true;
			return;
		}

		if (isset($data['editor'])) {

			foreach ($data['editor'] as $key=>$value) {

				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;

			}
		}



		foreach ($data as $key=>$value) {


			if (is_string($value))
				$value=_trim($value);

			$this->update_field_switcher($key,$value,$options);


		}

		if (!$this->updated and $this->msg=='')
			$this->msg.=_('Nothing to be updated')."\n";
	}

	protected function update_field_switcher($field,$value,$options='') {


		$base_data=$this->base_data();


		if (preg_match('/^Address.*Data$/',$field)) {
			$this->update_field($field,$value,$options);

		}elseif (array_key_exists($field,$base_data)) {

			if ($value!=$this->data[$field]) {
				$this->update_field($field,$value,$options);

			}
		}
		elseif (preg_match('/^custom_field_part/i',$field)) {
			$this->update_field($field,$value,$options);
		}

	}

	protected function translate_data($data,$options='') {

		$_data=array();
		foreach ($data as $key => $value) {

			if (preg_match('/supplier/i',$options))
				$regeprix='/^Supplier /i';
			elseif (preg_match('/customer/i',$options))
				$regex='/^Customer /i';
			elseif (preg_match('/company/i',$options))
				$regex='/^Company /i';
			elseif (preg_match('/contact/i',$options))
				$regex='/^Contact /i';

			$rpl=$this->table_name.' ';


			$_key=preg_replace($regex,$rpl,$key);
			$_data[$_key]=$value;
		}




		return $_data;
	}

	protected function update_field($field,$value,$options='') {

		$this->updated=false;
		//print $field;
		//print $this->table_name;

		$null_if_empty=true;

		if ($options=='no_null') {
			$null_if_empty=false;

		}

		if (is_array($value))
			return;
		$value=_trim($value);


		$old_value=_('Unknown');
		$key_field=$this->table_name." Key";




		if ($this->table_name=='Supplier Product')
			$key_field='Supplier Product Current Key';
		else if ($this->table_name=='Part')
				$key_field='Part SKU';

			if (preg_match('/^custom_field_part/i',$field)) {
				$field1=preg_replace('/^custom_field_part_/','',$field);
				$sql=sprintf("select %s as value from `Part Custom Field Dimension` where `Part SKU`=%d", $field1, $this->id);
			}
		elseif (preg_match('/^custom_field_customer/i',$field)) {
			$field1=preg_replace('/^custom_field_customer_/','',$field);
			$sql=sprintf("select `Custom Field Key` from `Custom Field Dimension` where `Custom Field Name`=%s", prepare_mysql($field1));
			$res=mysql_query($sql);
			$r=mysql_fetch_assoc($res);

			$sql=sprintf("select `%s` as value from `Customer Custom Field Dimension` where `Customer Key`=%d", $r['Custom Field Key'], $this->id);
		}
		else
			$sql="select `".$field."` as value from  `".$this->table_name." Dimension`  where `$key_field`=".$this->id;

		//print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$old_value=$row['value'];
		}

		if (preg_match('/^custom_field_part/i',$field)) {
			if (is_string($value))
				$sql=sprintf("update `Part Custom Field Dimension` set `%s`='%s' where `Part SKU`=%d",$field1, $value, $this->id);
			else
				$sql=sprintf("update `Part Custom Field Dimension` set `%s`='%d' where `Part SKU`=%d",$field1, $value, $this->id);
		}
		elseif (preg_match('/^custom_field_customer/i',$field)) {
			if (is_string($value))
				$sql=sprintf("update `Customer Custom Field Dimension` set `%s`='%s' where `Customer Key`=%d",$r['Custom Field Key'], $value, $this->id);
			else
				$sql=sprintf("update `Customer Custom Field Dimension` set `%s`='%d' where `Customer Key`=%d",$r['Custom Field Key'], $value, $this->id);


		}
		else
			$sql="update `".$this->table_name." Dimension` set `".$field."`=".prepare_mysql($value,$null_if_empty)." where `$key_field`=".$this->id;

		//print "$sql\n";

		mysql_query($sql);
		$affected=mysql_affected_rows();
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
			if (preg_match('/no( |\_)history|nohistory/i',$options))
				$save_history=false;

			if (
				preg_match('/site|page|part|customer|contact|company|order|staff|supplier|address|telecom|user|store|product|company area|company department|position|category/i',$this->table_name)
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
				if ($this->table_name=='Customer') {
					$sql=sprintf("insert into `Customer History Bridge` values (%d,%d,'No','No','Changes')",$this->id,$history_key);
					mysql_query($sql);

				}

			}

		}

	}


	protected function get_editor_data() {



		if (isset($this->editor['Date'])  and preg_match('/^\d{4}-\d{2}-\d{2}/',$this->editor['Date']))
			$date=$this->editor['Date'];
		else
			$date=date("Y-m-d H:i:s");

		$user_key=1;



		if (isset($this->editor['User Key'])and is_numeric($this->editor['User Key'])  )
			$user_key=$this->editor['User Key'];
		else
			$user_key=0;



		return array(
			'User Key'=>$user_key
			,'Date'=>$date
		);
	}



	function add_history($raw_data,$force=false) {


		$editor_data=$this->get_editor_data();
		if ($this->no_history)
			return;

		if ($this->new and !$force)
			return;
		if ($this->table_name=='Product Department')
			$table='Department';
		elseif ($this->table_name=='Product Family')
			$table='Family';
		else
			$table=$this->table_name;


		if (!isset($raw_data['Direct Object']))
			$raw_data['Direct Object']=$table;

		if (!isset($raw_data['Direct Object Key'])) {
			if ($this->table_name=='Product')
				$raw_data['Direct Object Key']=$this->pid;
			else
				$raw_data['Direct Object Key']=$this->id;
		}





		$data=$this->base_history_data();



		foreach ($raw_data as $key=>$value) {
			$data[$key]=$value;
		}



		if ($data['Subject']=='' or  !$data['Subject Key']) {
			include_once 'class.User.php';
			$user=new User($editor_data['User Key']);
			if ($user->id) {

				$data['Subject']=$user->data['User Type'];
				$data['Subject Key']=$user->data['User Parent Key'];
				$data['Author Name']=$user->data['User Alias'];
			} else {
				$data['Subject']='Staff';
				$data['Subject Key']=0;
				$data['Author Name']=_('Unknown');
			}

		}




		$data['User Key']=$editor_data['User Key'];





		if (!isset($data['Date']) or $data['Date']=='')
			$data['Date']=$editor_data['Date'];

		if ($data['History Abstract']=='') {
			if ($data['Indirect Object'])
				$data['History Abstract']=$data['Indirect Object'].' '._('changed');
			else
				$data['History Abstract']='Unknown';
		}



		if (!array_key_exists('Author Name', $data)) {
			$data['Author Name']='';
		}




		if ($data['Author Name']=='') {



			if ($data['Subject']=='Customer' ) {
				include_once 'class.Customer.php';
				$customer=new Customer($data['Subject Key']);
				$data['Author Name']=$customer->data['Customer Name'];
			}
			elseif ($data['Subject']=='Staff' ) {
				include_once 'class.Staff.php';
				$staff=new Staff($data['Subject Key']);
				$data['Author Name']=$staff->data['Staff Alias'];
			}
			elseif ($data['Subject']=='Supplier' ) {
				include_once 'class.Supplier.php';

				$supplier=new Supplier($data['Subject Key']);
				$data['Author Name']=$staff->data['Supplier Name'];
			}



		}



		if ($data['Action']=='created') {
			$data['Preposition']='';
		}

		if ($data['History Details']=='') {
			if (isset($raw_data['old_value']) and  isset($raw_data['new_value']) ) {
				$data['History Details']=$data['Indirect Object'].' '._('changed from')." \"".$raw_data['old_value']."\" "._('to')." \"".$raw_data['new_value']."\"";
				$data['History Abstract'].=' ('.$raw_data['old_value'].'&rarr;'.$raw_data['new_value'].')';

			}
			elseif (  isset($raw_data['new_value']) ) {
				$data['History Details']=$data['Indirect Object'].' '._('changed to')." \"".$raw_data['new_value']."\"";
			}
		}



		$sql=sprintf("insert into `History Dimension` (`Author Name`,`History Date`,`Subject`,`Subject Key`,`Action`,`Direct Object`,`Direct Object Key`,`Preposition`,`Indirect Object`,`Indirect Object Key`,`History Abstract`,`History Details`,`User Key`,`Deep`,`Metadata`) values (%s,%s,%s,%d,%s,%s,%d,%s,%s,%d,%s,%s,%d,%s,%s)"
			,prepare_mysql($data['Author Name'])
			,prepare_mysql($data['Date'])
			,prepare_mysql($data['Subject'])
			, $data['Subject Key']
			,prepare_mysql($data['Action'])
			,prepare_mysql($data['Direct Object'])
			,$data['Direct Object Key']
			,prepare_mysql($data['Preposition'],false)
			,prepare_mysql($data['Indirect Object'],false)
			,$data['Indirect Object Key']
			,prepare_mysql($data['History Abstract'])
			,prepare_mysql($data['History Details'])
			, $data['User Key']
			,prepare_mysql($data['Deep'])
			,prepare_mysql($data['Metadata'])
		);

		//       print $sql;
		// print_r($raw_data);
		//dsdfdffd();
		mysql_query($sql);

		return mysql_insert_id();



	}

	function set_editor($raw_data) {
		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;

			}
		}

	}

	function reread() {
		$this->get_data('id',$this->id);
	}


}

?>
