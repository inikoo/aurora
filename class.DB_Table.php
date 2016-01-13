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

		$sql=sprintf('show columns from `%s Dimension`', addslashes($this->table_name));
		foreach ($this->db->query($sql) as $row) {
			if (!in_array($row['Field'], $this->ignore_fields))
				$data[$row['Field']]=$row['Default'];
		}

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
				if (!in_array($row['Field'], $this->ignore_fields))
					$data[$row['Field']]=$row['Default'];
			}
		}
		return $data;
	}


	public function update($data, $options='') {

		if (!is_array($data)) {

			$this->error=true;
			return;
		}

		if (isset($data['editor'])) {

			foreach ($data['editor'] as $key=>$value) {

				if (array_key_exists($key, $this->editor))
					$this->editor[$key]=$value;

			}
		}



		foreach ($data as $key=>$value) {


			if (is_string($value))
				$value=_trim($value);

			//print "$key,$value";
			$this->update_field_switcher($key, $value, $options);


		}

		if (!$this->updated and $this->msg=='')
			$this->msg.=_('Nothing to be updated')."\n";
	}


	protected function update_field_switcher($field, $value, $options='') {


		$base_data=$this->base_data();


		if (preg_match('/^Address.*Data$/', $field)) {
			$this->update_field($field, $value, $options);

		}elseif (array_key_exists($field, $base_data)) {

			if ($value!=$this->data[$field]) {
				$this->update_field($field, $value, $options);

			}
		}
		elseif (preg_match('/^custom_field_part/i', $field)) {
			$this->update_field($field, $value, $options);
		}

	}


	protected function translate_data($data, $options='') {

		$_data=array();
		foreach ($data as $key => $value) {

			if (preg_match('/supplier/i', $options))
				$regeprix='/^Supplier /i';
			elseif (preg_match('/customer/i', $options))
				$regex='/^Customer /i';
			elseif (preg_match('/company/i', $options))
				$regex='/^Company /i';
			elseif (preg_match('/contact/i', $options))
				$regex='/^Contact /i';

			$rpl=$this->table_name.' ';


			$_key=preg_replace($regex, $rpl, $key);
			$_data[$_key]=$value;
		}




		return $_data;
	}


	protected function update_field($field, $value, $options='') {
		$this->update_table_field($field, $value, $options, $this->table_name, $this->table_name.' Dimension', $this->id);

	}


	protected function update_table_field($field, $value, $options='', $table_name, $table_full_name, $table_key) {



		$this->updated=false;

		$null_if_empty=true;

		if ($options=='no_null') {
			$null_if_empty=false;

		}

		if (is_array($value))
			return;
		$value=_trim($value);


		$old_value=_('Unknown');
		$key_field=$table_name." Key";

		if ($table_name=='Page' or $table_name=='Page Store') {
			$key_field="Page Key";
		}

		if ($table_name=='Page' and $this->type=='Store') {
			$extra_data=$this->store_base_data();



			if (array_key_exists($field, $extra_data))
				$table_name='Page Store';
		}
		else if ($table_name=='Supplier Product') {

			$key_field='Supplier Product Current Key';
		}
		else if ($table_name=='Part') {
			$key_field='Part SKU';
		}

		if (preg_match('/^custom_field_part/i', $field)) {
			$field1=preg_replace('/^custom_field_part_/', '', $field);
			$sql=sprintf("select %s as value from `Part Custom Field Dimension` where `Part SKU`=%d", $field1, $table_key);
		}
		elseif (preg_match('/^custom_field_customer/i', $field)) {
			$field1=preg_replace('/^custom_field_customer_/', '', $field);
			$sql=sprintf("select `Custom Field Key` from `Custom Field Dimension` where `Custom Field Name`=%s", prepare_mysql($field1));
			$res=mysql_query($sql);
			$r=mysql_fetch_assoc($res);

			$sql=sprintf("select `%s` as value from `Customer Custom Field Dimension` where `Customer Key`=%d", $r['Custom Field Key'], $table_key);
		}
		else {

			$sql=sprintf("select `%s` as value from `%s` where `%s`=%d ",
				addslashes($field),
				addslashes($table_full_name),
				addslashes($key_field),
				$table_key

			);
		}

		if ($result=$this->db->query($sql)) {

			if ($row = $result->fetch()) {
				$old_value=$row['value'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit($sql);

		}




		if (preg_match('/^custom_field_part/i', $field)) {
			if (is_string($value))
				$sql=sprintf("update `Part Custom Field Dimension` set `%s`='%s' where `Part SKU`=%d", $field1, $value, $table_key);
			else
				$sql=sprintf("update `Part Custom Field Dimension` set `%s`='%d' where `Part SKU`=%d", $field1, $value, $table_key);
		}
		elseif (preg_match('/^custom_field_customer/i', $field)) {
			if (is_string($value))
				$sql=sprintf("update `Customer Custom Field Dimension` set `%s`='%s' where `Customer Key`=%d", $r['Custom Field Key'], $value, $table_key);
			else
				$sql=sprintf("update `Customer Custom Field Dimension` set `%s`='%d' where `Customer Key`=%d", $r['Custom Field Key'], $value, $table_key);


		}
		else {
			$sql=sprintf("update `%s` set `%s`=%s where `%s`=%d",
				addslashes($table_full_name),
				addslashes($field),
				prepare_mysql($value, $null_if_empty),
				addslashes($key_field),
				$table_key
			);


		}
		//print $sql;
		$update_op=$this->db->prepare($sql);
		$update_op->execute();
		$affected=$update_op->rowCount();



		if ($affected==0) {
			$this->data[$field]=$value;

		}
		else {



			$this->data[$field]=$value;
			$this->msg.=" $field "._('Record updated').", \n";
			$this->msg_updated.=" $field "._('Record updated').", \n";
			$this->updated=true;
			$this->new_value=$value;




			if (preg_match('/no( |\_)history|nohistory/i', $options)) {
				$save_history=false;
			}else {
				$save_history=true;
			}


			if (
				preg_match('/attachment bridge|site|page|part|customer|contact|company|order|staff|supplier|address|telecom|user|store|product|company area|company department|position|category/i', $table_name)
				and !$this->new
				and $save_history
			) {


				$old_value=htmlentities($old_value);
				$value=htmlentities($value);


				$this->add_changelog_record($field, $old_value, $value, $options, $table_name, $table_key);

			}

		}

	}


	function add_changelog_record($field, $old_value, $value, $options, $table_name, $table_key) {




		$history_data=array(
			'Indirect Object'=>$field,
			'old_value'=>$old_value,
			'new_value'=>$value

		);

		/*

		if ($table_name=='Product Family')
			$history_data['direct_object']='Family';
		if ($table_name=='Product Department')
			$history_data['direct_object']='Department';
		if ($table_name=='Page Store') {
			$history_data['direct_object']='Page';
			$table_name='Page';

		}
*/
		$history_key=$this->add_history($history_data, false, false, $options);


		if (!in_array($table_name, array())) {

			/*
			if ($table_name=='Product' or $table_name=='Supplier Product') {
				$subject_key=$this->pid;

			}else {
				$subject_key=$this->id;
			}
			*/

			$sql=sprintf("insert into `%s History Bridge` values (%d,%d,'No','No','Changes')", $table_name, $table_key, $history_key);

			$this->db->exec($sql);
		}

	}



	protected function get_editor_data() {

		if (isset($this->editor['Date'])  and preg_match('/^\d{4}-\d{2}-\d{2}/', $this->editor['Date']))
			$date=$this->editor['Date'];
		else
			$date=gmdate("Y-m-d H:i:s");

		$user_key=1;

		if (isset($this->editor['User Key'])and is_numeric($this->editor['User Key'])  )
			$user_key=$this->editor['User Key'];
		else
			$user_key=0;

		return array(
			'User Key'=>$user_key
			, 'Date'=>$date
		);
	}


	function get_main_id() {

		if ($this->table_name=='Product' or $this->table_name=='Supplier Product')
			return $this->pid;
		else
			return $this->id;

	}


	function add_history($raw_data, $force=false, $post_arg1=false, $options='') {

		if ($this->table_name=='Product Department')
			$table='Department';
		elseif ($this->table_name=='Product Family')
			$table='Family';
		else
			$table=$this->table_name;

		return $this->add_table_history($raw_data, $force, $post_arg1, $options, $table, $this->get_main_id());
	}


	function add_table_history($raw_data, $force, $post_arg1, $options='', $table_name, $table_key) {

		global $account;

		$editor_data=$this->get_editor_data();
		if ($this->no_history)
			return;

		if ($this->new and !$force)
			return;

		if (!isset($raw_data['Direct Object']))
			$raw_data['Direct Object']=$table_name;

		if (!isset($raw_data['Direct Object Key'])) {
			$raw_data['Direct Object Key']=$table_key;
		}

		$data=$this->base_history_data();

		foreach ($raw_data as $key=>$value) {
			$data[$key]=$value;
		}

		;
		if (array_key_exists('User Key', $raw_data)) {
			$data['User Key']=$raw_data['User Key'];
		}else {
			$data['User Key']=$editor_data['User Key'];
		}



		if (($data['Subject']=='' or  !$data['Subject Key']) and $data['Subject']!='System') {
			include_once 'class.User.php';
			$user=new User($data['User Key']);
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
		if (!isset($data['Date']) or $data['Date']=='')
			$data['Date']=$editor_data['Date'];

		if ($data['History Abstract']=='') {
			if ($data['Indirect Object']) {

				switch ($data['Indirect Object']) {
				case 'Customer Website':
					$formated_indirect_object=_('Customer website');
					break;
				case 'Customer Name':
					$formated_indirect_object=_('Customer name');
					break;


				default:
					$formated_indirect_object=$this->get_field_label($data['Indirect Object']);

				}

				switch ($table_name) {
				case 'Staff':
					if ($raw_data['new_value']=='')
						$data['History Abstract']=sprintf(_("Employee's %s was deleted"), $formated_indirect_object);
					else
						$data['History Abstract']=sprintf(_("Employee's %s was changed to %s"), $formated_indirect_object, $this->get(preg_replace('/^Staff /', '', $data['Indirect Object'])));
					break;
				case 'User':
					if ($raw_data['new_value']=='')
						$data['History Abstract']=sprintf(_("User's %s was deleted"), $formated_indirect_object);
					else
						$data['History Abstract']=sprintf(_("User's %s was changed to %s"), $formated_indirect_object, $this->get(preg_replace('/^User /', '', $data['Indirect Object'])));
					break;
				default:
					$formated_table=$table_name."'s";
					if ($raw_data['new_value']=='')
						$data['History Abstract']=sprintf(_("%s %s was deleted"), $formated_table, $formated_indirect_object);
					else
						$data['History Abstract']=sprintf(_("%s %s was changed to %s"), $formated_table, $formated_indirect_object, $raw_data['new_value']);


				}




				$formated_indirect_object.' '._('changed').' ('.$raw_data['new_value'].')';
			}else {
				$data['History Abstract']='Unknown';
			}
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
			}elseif ($data['Subject']=='System' ) {

				$data['Author Name']=_('System');
			}



		}



		if ($data['Action']=='created') {
			$data['Preposition']='';
		}

		if (isset($this->label) and $this->label) {
			$label=$this->label;
		}else {
			$label=$table_name;
		}
		if ($data['History Details']=='') {
			if (isset($raw_data['old_value']) and  isset($raw_data['new_value']) ) {

				$data['History Details']='<table>
				<tr><td style="width:120px">'._('Time').':</td><td>'.strftime("%a %e %b %Y %H:%M:%S %Z").'</td></tr>
				<tr><td>'._('User').':</td><td>'.$this->editor['Author Alias'].'</td></tr>

				<tr><td>'._('Action').':</td><td>'._('Changed').'</td></tr>
				<tr><td>'._('Old value').':</td><td>'.$raw_data['old_value'].'</td></tr>
				<tr><td>'._('New value').':</td><td>'.$raw_data['new_value'].'</td></tr>
				<tr><td>'.$label.':</td><td>'.$this->get_name().'</td></tr>


				</table>';



			}
			elseif (  isset($raw_data['new_value']) ) {

				$data['History Details']='<table>
				<tr><td style="width:120px">'._('Time').':</td><td>'.strftime("%a %e %b %Y %H:%M:%S %Z").'</td></tr>
				<tr><td>'._('User').':</td><td>'.$this->editor['Author Alias'].'</td></tr>

				<tr><td>'._('Action').':</td><td>'._('Associated').'</td></tr>
				<tr><td>'._('New value').':</td><td>'.$raw_data['new_value'].'</td></tr>
				<tr><td>'.$label.':</td><td>'.$this->get_name().'</td></tr>


				</table>';

			}
		}



		$sql=sprintf("insert into `History Dimension` (`Author Name`,`History Date`,`Subject`,`Subject Key`,`Action`,`Direct Object`,`Direct Object Key`,`Preposition`,`Indirect Object`,`Indirect Object Key`,`History Abstract`,`History Details`,`User Key`,`Deep`,`Metadata`) values (%s,%s,%s,%d,%s,%s,%d,%s,%s,%d,%s,%s,%d,%s,%s)"
			, prepare_mysql($data['Author Name'])
			, prepare_mysql($data['Date'])
			, prepare_mysql($data['Subject'])
			, $data['Subject Key']
			, prepare_mysql($data['Action'])
			, prepare_mysql($data['Direct Object'])
			, $data['Direct Object Key']
			, prepare_mysql($data['Preposition'], false)
			, prepare_mysql($data['Indirect Object'], false)
			, $data['Indirect Object Key']
			, prepare_mysql($data['History Abstract'])
			, prepare_mysql($data['History Details'])
			, $data['User Key']
			, prepare_mysql($data['Deep'])
			, prepare_mysql($data['Metadata'])
		);


		$this->db->exec($sql);

		$history_key=$this->db->lastInsertId();
		return $history_key;



	}


	function post_add_history($history_key, $type=false) {
		return false;
	}


	function set_editor($raw_data) {
		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key, $this->editor))
					$this->editor[$key]=$value;

			}
		}

	}


	function reread() {
		$this->get_data('id', $this->id);
	}



	function add_note($note, $details='', $date=false, $deleteable='No', $customer_history_type='Notes', $author=false, $subject=false, $subject_key=false) {


		list($ok, $note, $details)=$this->prepare_note($note, $details);
		if (!$ok) {
			return;
		}
		$history_data=array(
			'History Abstract'=>$note,
			'History Details'=>$details,
			'Action'=>'created',
			'Direct Object'=>'Note',
			'Prepostion'=>'on',
			'Indirect Object'=>$this->table_name,
			'Indirect Object Key'=>(($this->table_name=='Product' or $this->table_name=='Supplier Product')  ?$this->pid:$this->id)
		);

		if ($author) {
			$history_data['Author Name']=$author;
		}
		if ($subject) {
			$history_data['Subject']=$subject;
			$history_data['Subject Key']=$subject_key;
		}

		if ($date!='')
			$history_data['Date']=$date;


		$history_key=$this->add_subject_history($history_data, $force_save=true, $deleteable, $customer_history_type);

		$this->updated=true;
		$this->new_value=$history_key;
	}


	function add_subject_history($history_data, $force_save=true, $deleteable='No', $type='Changes', $table_name, $table_key) {

		$history_key=$this->add_table_history($history_data, $force_save, '', '', $table_name, $table_key);

		$sql=sprintf("insert into `%s History Bridge` values (%d,%d,%s,'No',%s)",
			$table_name,
			$table_key,
			$history_key,
			prepare_mysql($deleteable),
			prepare_mysql($type)
		);

		$this->db->exec($sql);


		return $history_key;
	}


	function add_attachment($raw_data) {
		$data=array(
			'file'=>$raw_data['Filename']
		);

		$attach=new Attachment('find', $data, 'create');


		$subject_key=$this->id;
		$subject_key=$this->get_main_id();

		if ($this->table_name=='Product Family') {
			$subject='Family';
		}elseif ($this->table_name=='Product Department') {
			$subject='Department';
		}else {

			$subject=$this->get_object_name();
		}



		if ($attach->id) {


			$sql=sprintf("insert into `Attachment Bridge` (`Attachment Key`,`Subject`,`Subject Key`,`Attachment File Original Name`,`Attachment Caption`,`Attachment Subject Type`) values (%d,%s,%d,%s,%s,%s)",
				$attach->id,
				prepare_mysql($this->get_object_name()),
				$this->get_main_id(),
				prepare_mysql($raw_data['Attachment File Original Name']),
				prepare_mysql($raw_data['Attachment Caption'], false),
				prepare_mysql($raw_data['Attachment Subject Type'])


			);
			$this->db->exec($sql);

			$subject_bridge_key=$this->db->lastInsertId();

			if (!$subject_bridge_key) {

				$this->error=true;
				$this->msg=_('File already attached');
				return $attach;
			}
			$attach->editor=$this->editor;
			$history_data=array(
				'History Abstract'=>_('File attached'),
				'History Details'=>'',
				'Action'=>'created',
			);
			$attach->add_subject_history($history_data, true, 'No', 'Changes', 'Attachment Bridge', $subject_bridge_key);


			$attach->get_subject_data($subject_bridge_key);




		}
		else {
			$this->error;
			$this->msg=$attach->msg;
		}


		return $attach;
	}


	function prepare_note($note, $details) {
		$note=_trim($note);
		if ($note=='') {
			$this->msg=_('Empty note');
			return array(0, 0, 0);
		}


		if ($details=='') {


			$details='';
			if (strlen($note)>1000) {
				$words=preg_split('/\s/', $note);
				$len=0;
				$note='';
				$details='';
				foreach ($words as $word) {
					$len+=strlen($word);
					if ($note=='')
						$note=$word;
					else {
						if ($len<1000)
							$note.=' '.$word;
						else
							$details.=' '.$word;

					}
				}



			}

		}
		return array(1, $note, $details);

	}


	function get_number_attachments_formated() {
		$attachments=0;

		if ($this->table_name=='Product' or $this->table_name=='Supplier Product')
			$subject_key=$this->pid;
		else
			$subject_key=$this->id;

		if ($this->table_name=='Product Family') {
			$subject='Family';
		}elseif ($this->table_name=='Product Department') {
			$subject='Department';
		}else {

			$subject=$this->table_name;
		}


		$sql=sprintf('select count(*) as num from `Attachment Bridge`where `Subject`=%s and `Subject Key`=%d',
			prepare_mysql($subject),
			$subject_key
		);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$attachments=number($row['num']);
		}

		return $attachments;

	}


	function get_attachments_data() {

		include_once 'utils/units_functions.php';

		if ($this->table_name=='Product' or $this->table_name=='Supplier Product')
			$subject_key=$this->pid;
		else
			$subject_key=$this->id;

		if ($this->table_name=='Product Family') {
			$subject='Family';
		}elseif ($this->table_name=='Product Department') {
			$subject='Department';
		}else {

			$subject=$this->table_name;
		}


		$sql=sprintf('select A.`Attachment Key`,`Attachment MIME Type`,`Attachment Type`,`Attachment Caption`,`Attachment Public`,`Attachment File Original Name`,`Attachment Thumbnail Image Key`,`Attachment File Size` from `Attachment Bridge` B left join `Attachment Dimension` A on  (A.`Attachment Key`=B.`Attachment Key`) where `Subject`=%s and `Subject Key`=%d',
			prepare_mysql($subject),
			$subject_key
		);

		$res=mysql_query($sql);
		$attachment_data=array();
		while ($row=mysql_fetch_assoc($res)) {

			if ($row['Attachment Type']=='Image') {
				$icon= '<img class="icon" src="art/icons/page_white_picture.png" alt="'.$row['Attachment MIME Type'].'" title="'.$row['Attachment MIME Type'].'" />';
			}elseif ($row['Attachment Type']=='Image') {
				$icon= '<img class="icon"  src="art/icons/page_white_excel.png" alt="'.$row['Attachment MIME Type'].'" title="'.$row['Attachment MIME Type'].'"/>';
			}elseif ($row['Attachment Type']=='Word') {
				$icon=  '<img class="icon" src="art/icons/page_white_word.png" alt="'.$row['Attachment MIME Type'].'" title="'.$row['Attachment MIME Type'].'"/>';
			}elseif ($row['Attachment Type']=='PDF') {
				$icon=  '<img class="icon" src="art/icons/page_white_acrobat.png" alt="'.$row['Attachment MIME Type'].'" title="'.$row['Attachment MIME Type'].'"/>';
			}elseif ($row['Attachment Type']=='Compresed') {
				$icon=  '<img class="icon" src="art/icons/page_white_compressed.png" alt="'.$row['Attachment MIME Type'].'" title="'.$row['Attachment MIME Type'].'"/>';
			}elseif ($row['Attachment Type']=='Text') {
				$icon=  '<img class="icon" src="art/icons/page_white_text.png" alt="'.$row['Attachment MIME Type'].'" title="'.$row['Attachment MIME Type'].'"/>';
			}else {
				$icon= '<img class="icon" src="art/icons/attach.png" alt="'.$row['Attachment MIME Type'].'" title="'.$row['Attachment MIME Type'].'"/>';

			}

			$name=$row['Attachment File Original Name'];
			if (strlen($name)>20) {

				$exts = preg_split("/\./i", $name) ;
				$n = count($exts)-1;

				$_exts = $exts[$n];
				unset($exts[$n]);
				$name=join(',', $exts);


				$name = substr($name, 0, 15) . " <b>&hellip;</b> ".$_exts;
			}


			$attachment_data[]=array(
				'key'=>$row['Attachment Key'],
				'type'=>$row['Attachment Type'],
				'caption'=>$row['Attachment Caption'],
				'public'=>$row['Attachment Public'],
				'name'=>$name,
				'full_name'=>$row['Attachment File Original Name'],
				'size'=>formatSizeUnits($row['Attachment File Size']),
				'thumbnail'=>$row['Attachment Thumbnail Image Key'],
				'icon'=>$icon
			);
		}

		return  $attachment_data;
	}


	function edit_note($note_key, $note, $details='', $change_date) {

		list($ok, $note, $details)=$this->prepare_note($note, $details);
		if (!$ok) {
			return;
		}
		$sql=sprintf("update `History Dimension` set `History Abstract`=%s ,`History Details`=%s where `History Key`=%d and `Indirect Object`=%s and `Indirect Object Key`=%s ",
			prepare_mysql($note),
			prepare_mysql($details),

			$note_key,
			prepare_mysql($this->table_name),

			(($this->table_name=='Product'  or $this->table_name=='Supplier Product')?$this->pid:$this->id));


		mysql_query($sql);
		if (mysql_affected_rows()) {
			if ($change_date=='update_date') {
				$sql=sprintf("update `History Dimension` set `History Date`=%s where `History Key`=%d  ",
					prepare_mysql(gmdate("Y-m-d H:i:s")),
					$note_key
				);
				mysql_query($sql);
			}

			$this->updated=true;
			$this->new_value=$note;
		}

	}


	function get_images_slidesshow() {
		include_once 'utils/units_functions.php';


		if ($this->table_name=='Product' or $this->table_name=='Supplier Product')
			$subject_key=$this->pid;
		else
			$subject_key=$this->id;

		if ($this->table_name=='Product Family') {
			$subject='Family';
		}elseif ($this->table_name=='Product Department') {
			$subject='Department';
		}else {

			$subject=$this->table_name;
		}

		$sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`=%s and   `Subject Key`=%d",
			prepare_mysql($subject),
			$subject_key
		);
		$res=mysql_query($sql);
		$images_slideshow=array();
		while ($row=mysql_fetch_array($res)) {
			if ($row['Image Height']!=0)
				$ratio=$row['Image Width']/$row['Image Height'];
			else
				$ratio=1;
			// print_r($row);
			$images_slideshow[]=array(
				'name'=>$row['Image Filename'],
				'small_url'=>'image.php?id='.$row['Image Key'].'&size=small',
				'thumbnail_url'=>'image.php?id='.$row['Image Key'].'&size=thumbnail',
				'normal_url'=>'image.php?id='.$row['Image Key'],
				'filename'=>$row['Image Filename'],
				'ratio'=>$ratio, 'caption'=>$row['Image Caption'],
				'is_principal'=>$row['Is Principal'],
				'id'=>$row['Image Key'],
				'size'=>formatSizeUnits($row['Image File Size']),
				'width'=>$row['Image Width'],
				'height'=>$row['Image Height']

			);
		}

		return $images_slideshow;
	}


	function add_image($image_key) {

		include_once 'utils/units_functions.php';

		if ($this->table_name=='Product' or $this->table_name=='Supplier Product')
			$subject_key=$this->pid;
		else
			$subject_key=$this->id;

		if ($this->table_name=='Product Family') {

			$subject='Family';
		}elseif ($this->table_name=='Product Department') {
			$subject='Department';
		}else {
			$subject=$this->table_name;
		}

		$sql=sprintf("select `Image Key`,`Is Principal` from `Image Bridge` where `Subject Type`=%s and `Subject Key`=%d  and `Image Key`=%d",
			prepare_mysql($subject),
			$subject_key,
			$image_key);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->nochange=true;
			$this->msg=_('Image already uploaded');
			return;
		}

		$number_images=$this->get_number_of_images();
		if ($number_images==0) {
			$principal='Yes';
		} else {
			$principal='No';
		}

		$sql=sprintf("insert into `Image Bridge` values (%s,%d,%d,%s,'')",
			prepare_mysql($subject),
			$subject_key,
			$image_key,
			prepare_mysql($principal)

		);
		mysql_query($sql);


		if ($principal=='Yes') {
			$this->update_main_image($image_key);
		}


		$sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`=%s and   `Subject Key`=%d and  PIB.`Image Key`=%d",
			prepare_mysql($subject),
			$subject_key,
			$image_key
		);

		$res=mysql_query($sql);
		//print $sql;
		if ($row=mysql_fetch_array($res)) {
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
				'size'=>formatSizeUnits($row['Image File Size']),
				'width'=>$row['Image Width'],
				'height'=>$row['Image Height']

			);
		}

		$this->updated=true;
		$this->msg=_("image added");
	}


	function get_name() {
		return '';

	}


	function get_object_name() {
		return $this->table_name;

	}


	function get_number_of_images() {

		if ($this->table_name=='Product' or $this->table_name=='Supplier Product')
			$subject_key=$this->pid;
		else
			$subject_key=$this->id;

		if ($this->table_name=='Product Family') {
			$subject='Family';
		}elseif ($this->table_name=='Product Department') {
			$subject='Department';
		}else {

			$subject=$this->table_name;
		}

		$number_of_images=0;
		$sql=sprintf("select count(*) as num from `Image Bridge` where `Subject Type`=%s and `Subject Key`=%d ",
			prepare_mysql($subject),
			$subject_key);
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$number_of_images=$row['num'];
		}
		return $number_of_images;
	}


	function get_formated_id($prefix='') {

		/*
        global $myconf;
        $sql="select count(*) as num from `Company Dimension`";
        $res=mysql_query($sql);
        $min_number_zeros=$myconf['company_min_number_zeros_id'];
        if ($row=mysql_fetch_array($res)) {
            if (strlen($row['num'])-1>$min_number_zeros)
                $min_number_zeros=strlen($row['num'])-01;
        }
        if (!is_numeric($min_number_zeros))
            $min_number_zeros=4;

        return sprintf("%s%0".$min_number_zeros."d",$myconf['company_id_prefix'], $this->id);
*/

		return sprintf("%s%04d", $prefix, $this->id);
	}


	function get_other_fields_update_info() {

		if (isset($this->other_fields_updated)) {
			return $this->other_fields_updated;
		}else {
			return false;
		}
	}


	function get_field_label($field) {
		return $field;
	}




}


?>
