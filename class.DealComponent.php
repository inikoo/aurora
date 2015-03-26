<?php
/*
 File: Deal.php

 This file contains the DealCompanent Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';
include_once 'class.Deal.php';

class DealComponent extends DB_Table {




	function DealComponent($a1,$a2=false) {

		$this->table_name='Deal Component';
		$this->ignore_fields=array('Deal Component Key');

		if (is_numeric($a1) and !$a2) {
			$this->get_data('id',$a1);
		} else if (($a1=='new' or $a1=='create') and is_array($a2) ) {
				$this->find($a2,'create');

			} elseif (preg_match('/find/i',$a1))
			$this->find($a2,$a1);
		else
			$this->get_data($a1,$a2);

	}

	function get_data($tipo,$tag) {

		if ($tipo=='id')
			$sql=sprintf("select * from `Deal Component Dimension` where `Deal Component Key`=%d",$tag);
		//    elseif($tipo=='code')
		//  $sql=sprintf("select * from `Deal Component Dimension` where `Deal Code`=%s",prepare_mysql($tag));
		// print $sql;
		$result=mysql_query($sql);

		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)  ) {
			$this->calculate_deal=create_function('$transaction_data,$customer_id,$date', $this->get('Deal Component'));
			$this->id=$this->data['Deal Component Key'];
		}
	}

	function find($raw_data,$options) {

		if (isset($raw_data['editor']) and is_array($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;

			}
		}

		$this->candidate=array();
		$this->found=false;
		$this->found_key=0;
		$create='';
		$update='';
		if (preg_match('/create/i',$options)) {
			$create='create';
		}
		if (preg_match('/update/i',$options)) {
			$update='update';
		}

		$data=$this->base_data();
		foreach ($raw_data as $key=>$value) {

			if (array_key_exists($key,$data))
				$data[$key]=$value;

		}


		$sql=sprintf("select `Deal Component Key` from `Deal Component Dimension` where `Deal Component Deal Key`=%d and  `Deal Component Trigger`=%s and `Deal Component Trigger Key`=%d and `Deal Component Terms Type`=%s and `Deal Component Allowance Type`=%s and `Deal Component Allowance Target`=%s and `Deal Component Allowance Target Key`=%d ",
			$data['Deal Component Deal Key'],
			prepare_mysql($data['Deal Component Trigger']),
			$data['Deal Component Trigger Key'],
			prepare_mysql($data['Deal Component Terms Type']),
			prepare_mysql($data['Deal Component Allowance Type']),
			prepare_mysql($data['Deal Component Allowance Target']),
			$data['Deal Component Allowance Target Key']

		);

		// print "$sql\n";


		$result=mysql_query($sql);
		$num_results=mysql_num_rows($result);
		if ($num_results==1) {
			$row=mysql_fetch_array($result, MYSQL_ASSOC);
			$this->found=true;
			$this->found_key=$row['Deal Component Key'];
			$this->get_data('id',$row['Deal Component Key']);

		}


		if ($create and !$this->found) {
			$this->create($data);

		}


	}



	function create($data) {




		if ($data['Deal Component Trigger Key']=='')
			$data['Deal Component Trigger Key']=0;
		if ($data['Deal Component Allowance Target Key']=='')
			$data['Deal Component Allowance Target Key']=0;

		if ($data['Deal Component Allowance']=='' and $data['Deal Component Allowance Lock']=='No') {
			$data['Deal Component Allowance']=DealComponent::parse_allowance_metadata($data['Deal Component Allowance Type'],$data['Deal Component Allowance Description']);
		}
		if ($data['Deal Component Terms']=='' and $data['Deal Component Terms Lock']=='No')
			$data['Deal Component Terms']=DealComponent::parse_term_metadata($data['Deal Component Terms Type'],$data['Deal Component Terms Description']);

		$keys='(';
		$values='values(';
		foreach ($data as $key=>$value) {
			$keys.="`$key`,";
			if ( $key=='Deal Component XHTML Terms Description Label' or $key=='Deal Component XHTML Name Label' or  $key=='Deal Component Replace' or $key=='Deal Component Allowance Target XHTML Label' or $key=='Deal Component Trigger XHTML Label')
				$values.=prepare_mysql($value,false).",";
			else
				$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Deal Component Dimension` %s %s",$keys,$values);
		// print "$sql\n";
		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->get_data('id',$this->id);
			$this->new=true;
		} else {
			print "Error can not create deal component\n $sql\n";
			exit;

		}
	}

	function get($key='') {

		if (isset($this->data[$key]))
			return $this->data[$key];

		switch ($key) {
		case('Description'):
		case('Deal Description'):
			return $this->data['Deal Component Terms Description'].' &rArr; '.$this->data['Deal Component Allowance Description'];
			break;
		}

		return false;
	}

	public static function parse_allowance_metadata($allowance_type,$allowance_description) {
		$conditions=preg_split('/\s+AND\s+/',$allowance_type);
		$metadata='';

		foreach ($conditions as $condition) {

			$metadata.=';'.DealComponent::parse_individual_allowance_metadata($condition,$allowance_description);
		}
		$metadata=preg_replace('/^;/','',$metadata);
		// print "** $allowance_type,$allowance_description ->$metadata  \n";
		return $metadata;
	}


	public static function parse_individual_allowance_metadata($allowance_type,$allowance_description) {
		// print "$allowance_type,$allowance_description\n";
		switch ($allowance_type) {
		case('Percentage Off'):
			if (preg_match('/\d+((\.|\,)\d+)?\%/i',$allowance_description,$match)) {
				$number=preg_replace('/\,/','.',$match[0]);
				$number=preg_replace('/\%/','',$number);
				return 0.01* (float) $number;
			}
			if (preg_match('/^(|.*\s+)free(\s+.*|)$/i',$allowance_description,$match)) {
				return 1;
			}
			break;
		case('Get Same Free'):
		case('Get Free'):
			$allowance_description=translate_written_number($allowance_description);
			$number=1;
			if (preg_match('/get \d+/i',$allowance_description,$match)) {
				//            print "** $allowance_description \n";

				$number=_trim(preg_replace('/[^\d]/','',$match[0]));
			}
			return $number;
			break;
		}
	}

	public static function parse_term_metadata($term_description_type,$term_description) {

		$conditions=preg_split('/\s+AND\s+/',$term_description_type);
		$metadata='';
		foreach ($conditions as $condition) {
			$metadata.=';'.DealComponent::parse_individual_term_metadata($condition,$term_description);
		}
		$metadata=_trim(preg_replace('/^;/','',$metadata));
		// print "------- $metadata\n";

		return $metadata;
	}

	public static function parse_individual_term_metadata($term_description_type,$term_description) {
		//print "$term_description_type  => $term_description\n";
		switch ($term_description_type) {
		case('Family Quantity Ordered'):
		case('Product Quantity Ordered'):
		case('Department Quantity Ordered'):
		case('Family For Every Quantity Ordered'):
		case('Product For Every Quantity Ordered'):
		case('Department For Every Quantity Ordered'):

			//print("$term_description\n");
			$term_description=translate_written_number($term_description);



			if (preg_match('/^\d+$/i',$term_description,$match))
				return $term_description;
			if (preg_match('/order \d+( or more)?/i',$term_description,$match))
				return preg_replace('/[^\d]/','',$match[0]);
			if (preg_match('/buy \d+/i',$term_description,$match))
				return preg_replace('/[^\d]/','',$match[0]);
			if (preg_match('/foreach \d+/i',$term_description,$match))
				return preg_replace('/[^\d]/','',$match[0]);
			if (preg_match('/for every \d+/i',$term_description,$match))
				return preg_replace('/[^\d]/','',$match[0]);
			if (preg_match('/\d+ oder mehr/i',$term_description,$match))
				return preg_replace('/[^\d]/','',$match[0]);

			break;
		case('Order Interval'):
			if (preg_match('/order (within|since|every) \d+ days?/i',$term_description,$match))
				return preg_replace('/[^\d]/','',$match[0]).' day';
			if (preg_match('/order (within|since|every) \d+ (calendar )?months?/i',$term_description,$match))
				return preg_replace('/[^\d]/','',$match[0]).' month';
			if (preg_match('/order (within|since|every) \d+ weeks?/i',$term_description,$match))
				return preg_replace('/[^\d]/','',$match[0]).' week';



			break;
		case('Order Number'):
			if (preg_match('/(first|1st) (order|one)|order (for|the)? (first|1st) time/i',$term_description,$match))
				return 1;
			if (preg_match('/(second|2nd) (order|one)|order (for|the)? (second|2nd) time/i',$term_description,$match))
				return 2;
			if (preg_match('/(third|3nd) (order|one)|order (for|the)? (third|3nd) time/i',$term_description,$match))
				return 3;
			if (preg_match('/order (number|no|\#)?\s*\d+/i',$term_description,$match))
				return preg_replace('/[^\d]/','',$match[0]);

			break;
		case('Order Items Net Amount'):
		case('Order Total Net Amount'):
		case('Order Items Gross Amount'):
			if (preg_match('/(less than|upto|up to)\s*(\$|\£|\€)?\d+/i',$term_description))
				$conditional='<';
			if (preg_match('/(more than|over)\s*(\$|\£|\€)?\d+/i',$term_description))
				$conditional='>';
			if (preg_match('/(equal|exactly)\s*(\$|\£|\€)?\d+/i',$term_description))
				$conditional='>';
			list($currency,$amount)=parse_money($term_description);
			return _trim("$conditional $currency $amount");
			break;
		case('Shipping Country'):
			$regex='/orders? (shipped |send |to be send |d(ie)spached )?to .*$/i';
			if ( preg_match('/orders? (shipped |send |to be send |d(ie)spached )?to .*$/i',$term_description,$match)) {
				$country=_trim(preg_replace('/orders? (shipped |send |to be send |d(ie)spached )?to /i','',$match[0]));
				//$country=_trim(preg_replace('/and order/i','',$country));

				$country=_trim(preg_replace('/(and|\+|y|with) (value|customer|order).*/i','',$country));

				$country_code=Address::parse_country($country);
				return $country_code;
			}

			break;
		}
	}

	function allowance_input_form() {
		$input_allowance=array();
		$allowances=preg_split('/\s+AND\s+/',$this->data['Deal Component Allowance Type']);
		$metadata=preg_split('/\s+|\s+/',$this->data['Deal Component Allowance']);
		foreach ($allowances as $key=>$allowance) {
			$input_allowance[]=$this->allowance_individual_input_form($allowance,$metadata[$key]);
		}
		return $input_allowance;
	}


	function get_thin_allowance_description($allowance=false,$metadata=false) {
		if (!$allowance)
			$allowance=$this->data['Deal Component Allowance Type'];
		if (!$metadata)
			$metadata=$this->data['Deal Component Allowance'];

		$label='';
		$value='';

		switch ($allowance) {
		case('Percentage Off'):
			$label=_('Discount');
			$value=percentage($metadata,1);
			break;

		}

		return array($label,$value);
	}


	function allowance_individual_input_form($allowance,$metadata) {


		$input_allowance=array();
		$input_allowance['Value Class']='';

		list($input_allowance['Label'],$input_allowance['Value'])=$this->get_thin_allowance_description($allowance,$metadata);


		if ($this->data['Deal Component Allowance Lock']=='Yes') {
			$allowance_lock_img='<img  style="position:relative;bottom:2px;height:12.9px"   src="art/icons/lock.png" alt="Locked"/>';
			$allowance_lock=true;
			$input_allowance['Value Class'].=' locked';
		}else {
			$allowance_lock_img='';
			$allowance_lock=false;
		}
		$input_allowance['Lock Label']=$allowance_lock_img;
		$input_allowance['Lock Value']=$allowance_lock;
		return $input_allowance;
	}

	function terms_input_form() {
		$input_terms=array();
		$terms=preg_split('/\s+AND\s+/',$this->data['Deal Component Terms Type']);
		$metadata=preg_split("/\;/",$this->data['Deal Component Terms']);

		//      print $this->data['Deal Component Terms Type']." ->  ". $this->data['Deal Component Terms']."\n";

		//print_r($metadata);
		//print "-------c ----\n";
		foreach ($terms as $key=>$terms) {
			$input_terms[]=$this->terms_individual_input_form($terms,$metadata[$key]);
		}
		return $input_terms;
	}



	function get_thin_terms_description($terms=false,$metadata=false) {
		if (!$terms)
			$terms=$this->data['Deal Component Terms Type'];
		if (!$metadata)
			$metadata=$this->data['Deal Component Terms'];

		$label='';
		$value='';

		switch ($terms) {
		case('Order Interval'):
			$label=_('If').' '._('order within');
			$value=$metadata;
			break;
		case('Family Quantity Ordered'):
			$label=_('If').' '._('order more than');
			$value=number($metadata);
			break;
		case('Shipping Country'):
			$label=_('If').' '._('Shipping Destination');

			$country=new Country ('code',$metadata);
			$value=$country->data['Country Name'];
			$input_terms['Value Class']='country';
			break;
		case('Order Items Net Amount'):
			$conditional='';
			if (preg_match('/^(\>|<|=|>=|<=)\s/',$metadata,$match)) {
				$conditional=_trim($match[0]);
				$metadata=preg_replace("/^$conditional/",'',$metadata);
			}

			$label=_trim($terms.' '.$conditional);
			$value=$metadata;
			break;


		}

		return array($label,$value);
	}

	function terms_individual_input_form($terms,$metadata) {

		$input_terms=array();
		$input_terms['Value Class']='';

		list($input_terms['Label'],$input_terms['Value'])=$this->get_thin_terms_description($terms,$metadata);

		//print "** $terms -> $metadata **\n";


		if ($this->data['Deal Component Terms Lock']=='Yes') {
			$terms_lock_img='<img style="position:relative;bottom:2px;height:12.9px"  src="art/icons/lock.png" alt="Locked"/>';
			$terms_lock=true;
			$input_terms['Value Class'].=' locked';
		}else {
			$terms_lock_img='';
			$terms_lock=false;
		}
		$input_terms['Lock Label']=$terms_lock_img;
		$input_terms['Lock Value']=$terms_lock;

		return $input_terms;
	}


	function get_xhtml_status() {
		switch ($this->data['Deal Component Status']) {
		case('Active'):
			return _("Active");
			break;
		case('Finish'):
			return _("Finished");
			break;
		case('Waiting'):
			return _("Waiting");
			break;
		case('Suspended'):
			return _("Suspended");
			break;


		}

	}


	function update_field_switcher($field,$value,$options='') {

		switch ($field) {
		case('term'):
			$this->update_term($value);
			break;
		case('allowance'):
			$this->update_allowance($value);
			break;
		default:
			$base_data=$this->base_data();

			if (array_key_exists($field,$base_data)) {
				$this->update_field($field,$value,$options);
			}
		}
	}



	function parse_term_description($thin_description) {


		switch ($this->data['Deal Component Terms Type']) {
		case('Department Quantity Ordered'):
		case('Family Quantity Ordered'):
		case('Product Quantity Ordered'):
			if (!is_numeric($thin_description)) {
				$msg=_('Term should be numeric');
				$error=true;
				return array($thin_description,$error,$msg);

			}elseif ($thin_description<=0) {
				$msg=_('Term should be more than zero');
				$error=true;
				return array($thin_description,$error,$msg);

			}

			$term_description="order ".number($thin_description)." or more";
			return array($term_description,false,'');
		case('Department For Every Quantity Ordered'):
		case('Family For Every Quantity Ordered'):
		case('Product For Every Quantity Ordered'):
			if (!is_numeric($thin_description)) {
				$msg=_('Term should be numeric');
				$error=true;
				return array($thin_description,$error,$msg);

			}elseif ($thin_description<=0) {
				$msg=_('Term should be more than zero');
				$error=true;
				return array($thin_description,$error,$msg);

			}

			$term_description="for every ".number($thin_description);
			return array($term_description,false,'');	
		default:
			$term_description=$thin_description;
			return array($term_description,false,'');


		}





	}


	function update_term($thin_description) {
		$this->updated=false;


		list($term_description,$error,$msg)=$this->parse_term_description($thin_description);
		if ($error) {
			$this->error=true;
			$this->msg=$msg;
			return;
		}

		$term_metadata=$this->parse_term_metadata(
			$this->data['Deal Component Terms Type'],
			$term_description
		);


		if ($term_metadata!=$this->data['Deal Component Terms']) {

			$sql=sprintf("update `Deal Component Dimension` set `Deal Component Terms Description`=%s ,`Deal Component Terms`=%s where `Deal Component Key`=%d"
				,prepare_mysql($term_description)
				,prepare_mysql($term_metadata)
				,$this->id
			);

			mysql_query($sql);
			$this->data['Deal Component Terms Description']=$term_description;
			$this->data['Deal Component Terms']=$term_metadata;

			$this->updated=true;
			list($label,$new_thin_description)=$this->get_thin_terms_description();
			$this->new_value=$new_thin_description;

		}


	}

	function parse_allowance_description($thin_description) {

		switch ($this->data['Deal Component Allowance Type']) {
		case('Percentage Off'):


			$thin_description=preg_replace('/\s*%$/','',$thin_description);

			if (!is_numeric($thin_description)) {
				$msg=_('Value should be a percentage');
				$error=true;
				return array($thin_description,$error,$msg);

			}
			$thin_description=abs($thin_description);

			if ($thin_description>100) {
				$msg=_('Discount can not be more than 100%');
				$msg=_('Value should be a percentage');
				$error=true;
				return array($thin_description,$error,$msg);
			}

			$allowance_description=number($thin_description)."% off";
			return array($allowance_description,false,'');
		default:
			$allowance_description=$thin_description;
			return array($allowance_description,false,'');

		}


	}

	function update_allowance($thin_description) {
		$this->updated=false;


		list($allowance_description,$error,$msg)=$this->parse_allowance_description($thin_description);
		if ($error) {
			$this->error=true;
			$this->msg=$msg;
			return;
		}


		$allowance_metadata=$this->parse_allowance_metadata(
			$this->data['Deal Component Allowance Type']
			,$allowance_description
		);



		if ($allowance_metadata!=$this->data['Deal Component Allowance']) {

			$sql=sprintf("insert into `Deal Component Dimension` set `Deal Component Allowance Description`=%s ,`Deal Component Allowance`=%s where `Deal Component Key`=%d"
				,prepare_mysql($allowance_description)
				,prepare_mysql($allowance_metadata)
				,$this->id
			);
			mysql_query($sql);


			$sql=sprintf("update `Deal Component Dimension` set `Deal Component Allowance Description`=%s ,`Deal Component Allowance`=%s where `Deal Component Key`=%d"
				,prepare_mysql($allowance_description)
				,prepare_mysql($allowance_metadata)
				,$this->id
			);
			mysql_query($sql);
			$this->updated=true;
			$this->data['Deal Component Allowance Description']=$allowance_description;
			$this->data['Deal Component Allowance']=$allowance_metadata;
			list($label,$new_thin_description)=$this->get_thin_allowance_description();
			$this->new_value=$new_thin_description;
		}


	}

	function update_status($value) {

		$sql=sprintf("update `Deal Component Dimension` set `Deal Component Status`=%s where `Deal Component Key`=%d"
			,prepare_mysql($value)
			,$this->id
		);
		mysql_query($sql);
		$this->data['Deal Component Status']=$value;


		if ($this->data['Deal Component Status']=='Active') {
			$from_date=false;
			if ($this->data['Deal Component Begin Date']=='') {
				$from_date=gmdate("Y-m-d H:i:s");
			}else {
				if (strtotime($this->data['Deal Component Begin Date'].' +0:00')>time()  ) {
					$from_date=gmdate("Y-m-d H:i:s");
				}

			}
			if ($from_date) {
				$this->update_field_switcher('Deal Component Begin Date',$from_date);
			}
			$this->update_field_switcher('Deal Component Public','Yes');



		}

		$deal= new Deal($this->data['Deal Component Deal Key']);

		$deal->update_status_from_components();
		$deal->update_number_components();
	}

	function update_terms_allowances($data,$options='') {

		$updated=false;
		$allowance_changed=false;
		$term_changed=false;

		list($allowance_description,$error,$msg)=$this->parse_allowance_description($data['Allowances']);

		if ($error) {
			$this->error=true;
			$this->msg=$msg;
			return;
		}
		list($term_description,$error,$msg)=$this->parse_term_description($data['Terms']);

		if ($error) {
			$this->error=true;
			$this->msg=$msg;
			return;
		}

		$allowance_metadata=$this->parse_allowance_metadata($this->data['Deal Component Allowance Type'],$allowance_description);
		if ($allowance_metadata!=$this->data['Deal Component Allowance']) {
			$allowance_changed=true;
		}

		$term_metadata=$this->parse_term_metadata($this->data['Deal Component Terms Type'],$term_description);


		if ($term_metadata!=$this->data['Deal Component Terms']) {
			$term_changed=true;
		}

		if ($allowance_changed or $term_changed) {

			if ($this->data['Deal Component Public']=='No') {

				$sql=sprintf("update `Deal Component Dimension` set `Deal Component Terms Description`=%s ,`Deal Component Terms`=%s where `Deal Component Key`=%d"
					,prepare_mysql($term_description)
					,prepare_mysql($term_metadata)
					,$this->id
				);

				mysql_query($sql);

				$sql=sprintf("update `Deal Component Dimension` set `Deal Component Allowance Description`=%s ,`Deal Component Allowance`=%s where `Deal Component Key`=%d"
					,prepare_mysql($allowance_description)
					,prepare_mysql($allowance_metadata)
					,$this->id
				);
				mysql_query($sql);
				$this->updated=true;
				$this->data['Deal Component Allowance Description']=$allowance_description;
				$this->data['Deal Component Allowance']=$allowance_metadata;
				$this->data['Deal Component Terms Description']=$term_description;
				$this->data['Deal Component Terms']=$term_metadata;



			}else {


				$old_metadata=new DealComponent($this->id);
				$deal_metadata_data=$this->data;
				$old_metadata->update_field_switcher('Deal Component Record Type','Historic');
				$old_metadata->update_field_switcher('Deal Component Status','Finish');



				$old_metadata->update_field_switcher('Deal Component Expiration Date',gmdate('Y-m-d H:i:s'));

				if ($this->data['Deal Component Status']!='Active') {
					$deal_metadata_data['Deal Component Public']='No';
				}
				$deal_metadata_data['Deal Component Expiration Date']='';

				$deal_metadata_data['Deal Component Total Acc Used Orders']=0;
				$deal_metadata_data['Deal Component Total Acc Used Customers']=0;
				unset($deal_metadata_data['Deal Component Key']);
				$deal_metadata_data['Deal Component Begin Date']=gmdate('Y-m-d H:i:s');
                $deal_metadata_data['Deal Component Allowance Description']=$allowance_description;
				$deal_metadata_data['Deal Component Allowance']=$allowance_metadata;
				$deal_metadata_data['Deal Component Terms Description']=$term_description;
				$deal_metadata_data['Deal Component Terms']=$term_metadata;
				
				

				
				$this->create($deal_metadata_data);

				$deal=new Deal($this->data['Deal Component Deal Key']);
				$deal->update_field_switcher('Deal Description',$this->get('Description'));
	


			}



		}else {
			return;

		}


		




	}

	function update_target_bridge() {

		if ($this->data['Deal Component Status']=='Finish') {
			$sql=sprintf("delete from `Deal Target Bridge` where `Deal Component Key`=%d ",$this->id);
			mysql_query($sql);
		}else {


			$sql=sprintf("insert into `Deal Target Bridge` values (%d,%s,%s,%d) ",
				$this->data['Deal Component Deal Key'],
				$this->id,
				prepare_mysql($this->data['Deal Component Allowance Target']),
				$this->data['Deal Component Allowance Target Key']

			);
			mysql_query($sql);

			if ($this->data['Deal Component Allowance Target']=='Family') {

				$sql=sprintf("select `Product ID` from `Product Dimension` where `Product Family Key`=%d and `Product Record Type`='Normal' ",$this->data['Deal Component Allowance Target Key']);
				$res2=mysql_query($sql);
				while ($row2=mysql_fetch_assoc($res2)) {

					$sql=sprintf("insert into `Deal Target Bridge` values (%d,%d,%s,%d) ",
						$this->data['Deal Component Deal Key'],
						$this->id,
						prepare_mysql('Product'),
						$row2['Product ID']

					);
					mysql_query($sql);
				}


			}


		}
	}



}

?>
