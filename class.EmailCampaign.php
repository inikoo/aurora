<?php
/*
 File: EmailCampaign.php

 This file contains the Email Campaign Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';
include_once 'class.Email.php';

class EmailCampaign extends DB_Table {

	var $new=false;
	var $updated_data=array();
	function EmailCampaign($arg1=false,$arg2=false,$arg3=false) {
		$this->table_name='Email Campaign';
		$this->ignore_fields=array(
			'Email Campaign Key',
			'Email Campaign Maximum Emails',
			'Email Campaign Last Updated Date',
			'Email Campaign Creation Date',
			'Email Campaign Date'
		);

		if (!$arg1 and !$arg2) {
			$this->error=true;
			$this->msg='No arguments';
		}
		if (is_numeric($arg1)) {
			$this->get_data('id',$arg1);
			return;
		}



		if (is_array($arg2) and preg_match('/find/i',$arg1)) {
			$this->find($arg2,$arg3);
			return;
		}


		$this->get_data($arg1,$arg2);

	}

	function get_data($tipo,$tag) {


		$sql=sprintf("select * from `Email Campaign Dimension` where  `Email Campaign Key`=%d",$tag);

		$result =mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->id=$this->data['Email Campaign Key'];
			$this->content_data=$this->get_contents_array();
			$this->content_keys=$this->get_content_data_keys();

		}


	}





	function ready_to_send() {
		$ready_to_send=true;



		if (!$this->data['Number of Emails']) {

			return false;
		}
		if (!count($this->content_keys)) {

			return false;
		}

		foreach ($this->content_data as $content_data) {
			if ($content_data['subject']=='') {

				$ready_to_send=false;
			}

			if ($content_data['type']=='Plain') {
				if ($content_data['plain']=='') {
					$ready_to_send=false;
				}
			}
			elseif ($content_data['type']=='HTML') {
				if ($content_data['html']=='') {
					$ready_to_send=false;
				}
			}
			else {
				if (!count($content_data['paragraphs'])) {
					$ready_to_send=false;
				}
			}
		}




		return $ready_to_send;

	}

	function find($raw_data,$options) {




		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {
				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;
			}
		}

		$this->found=false;
		$this->found_key=false;


		$sql=sprintf("select `Email Campaign Key` from `Email Campaign Dimension` where `Email Campaign Store Key`=%d and `Email Campaign Name`=%s",
			$raw_data['Email Campaign Store Key'],
			prepare_mysql($raw_data['Email Campaign Name'])
		);
		// print $sql;
		$result =mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->found_key=$row['Email Campaign Key'];
			$this->found=true;

		}


		$create='';
		$update='';
		if (preg_match('/create/i',$options)) {
			$create='create';
		}
		if (preg_match('/update/i',$options)) {
			$update='update';
		}


		if ($create and !$this->found) {
			$this->create($raw_data);
		}

	}
	function create($raw_data) {

		$data=$this->base_data();
		$content_data=array('Email Content Type'=>'HTML Template');

		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key,$data))
				if (is_array($value))
					$data[$key]=serialize($value);
				else
					$data[$key]=_trim($value);
		}

		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key,$content_data))
				$content_data[$key]=_trim($value);
		}



		if ($data['Email Campaign Name']=='') {
			$this->error;
			$this->msg='no name';
			return;
		}

		$data['Email Campaign Creation Date']=date("Y-m-d H:i:s");
		$data['Email Campaign Last Updated Date']=$data['Email Campaign Creation Date'];
		$data['Email Campaign Status']='Creating';


		$keys='(';
		$values='values(';
		foreach ($data as $key=>$value) {
			$keys.="`$key`,";
			if ($key='Email Campaign Recipients Preview' or $key='Email Campaign Scope')
				$values.=prepare_mysql($value,false).",";
			else
				$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Email Campaign Dimension` %s %s",$keys,$values);


		if (mysql_query($sql)) {



			$this->id=mysql_insert_id();
			$this->get_data('id',$this->id);
			$this->new=true;

			$store=new Store($this->data['Email Campaign Store Key']);
			switch ($this->data['Email Campaign Type']) {
			case 'Marketing':
				$store->update_email_campaign_data();
				break;
			case 'Newsletter':
				//update_newsletter_data();
				break;
			case 'Reminder':
				//update_newsletter_data();
				break;
			}


			$sql=sprintf("insert into `Email Content Dimension` (`Email Content Type`,`Email Content Subject`,`Email Content Text`,`Email Content HTML`) values (%s,'','','')",
				prepare_mysql($content_data['Email Content Type'])

			);



			mysql_query($sql);
			$email_content_key=mysql_insert_id();


			$paragraph_data=array(
				array('type'=>'Main','title'=>'Donec eleifend nunc ut libero fringilla posuere','subtitle'=>'Duis mauris massa','content'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque pretium sapien nec augue dictum tincidunt. Phasellus in vulputate nibh. Morbi ac odio lorem. Suspendisse ut nibh vel nibh malesuada ullamcorper vitae sed magna. Aliquam erat volutpat.'),
				array('type'=>'Main','title'=>'Nullam interdum posuere ultricies','subtitle'=>'In sagittis augue tellus','content'=>'Morbi porttitor posuere venenatis. Aliquam tincidunt scelerisque porttitor. Vivamus vulputate tortor ut augue eleifend semper. Curabitur venenatis placerat porta. Aliquam semper magna vitae libero porttitor vulputate.'),
				array('type'=>'Side','title'=>'Pellentesque sed sapien','subtitle'=>'Aliquam urna dui','content'=>'Quisque in purus eu purus malesuada porttitor. Proin sed arcu nisi. Ut in enim arcu. Cras consectetur commodo dolor, id tempus tortor imperdiet quis. Donec iaculis interdum congue. Nullam ultrices hendrerit lectus, vitae lobortis magna sagittis et.')

			);

			foreach ($paragraph_data as $_paragraph_data_key=>$_paragraph_data) {

				$sql=sprintf("insert into `Email Content Paragraph Dimension` (

                             `Email Content Key` ,
                             `Paragraph Order` ,
                             `Paragraph Type` ,
                             `Paragraph Title` ,
                             `Paragraph Subtitle` ,
                             `Paragraph Content`
                             ) values (%d,%d,%s,%s,%s,%s)",

					$email_content_key,
					$_paragraph_data_key,
					prepare_mysql('Main'),
					prepare_mysql($_paragraph_data['title']),
					prepare_mysql($_paragraph_data['subtitle']),
					prepare_mysql($_paragraph_data['content'])
				);
				mysql_query($sql);


			}

			$sql=sprintf("insert into `Email Campaign Content Bridge`  values (%d,%d)",$this->id,$email_content_key);
			mysql_query($sql);
			$email_content_key=mysql_insert_id();
			$this->get_data('id',$this->id);
			$store=new Store($this->data['Email Campaign Store Key']);
			switch ($this->data['Email Campaign Type']) {
			case 'Marketing':
				$store->update_email_campaign_data();
				break;
			case('Newsletter'):
				$store->update_newsletter_data();
				break;
			case('Reminder'):
				$store->update_email_reminder_data();
				break;
			}



		} else {
			$this->error=true;
			$this->msg="Can not insert Email Campaign Dimension";
			// exit("$sql\n");
		}


	}



	function add_objective($scope_data) {

		$scope_data['Email Campaign Key']=$this->id;

		switch ($scope_data['Email Campaign Objective Parent']) {
		case 'Department':
			$parent=new Department($scope_data['Email Campaign Objective Parent Key']);
			$parent_key=$parent->id;
			$parent_name=$parent->data['Product Department Name'];
			$term='Order';
			$term_metadata='0;;432000';
			break;
		case 'Family':
			$parent=new Family($scope_data['Email Campaign Objective Parent Key']);
			$parent_key=$parent->id;
			$parent_name='<b>'.$parent->data['Product Family Code'].'</b>, '.$parent->data['Product Family Name'];
			$term='Order';
			$term_metadata='0;;432000';
			break;
		case 'Store':
			$parent=new Store($scope_data['Email Campaign Objective Parent Key']);
			$parent_key=$parent->id;
			$parent_name=$parent->data['Product Store Name'];
			$term='Order';
			$term_metadata='0;;432000';
			break;
		case 'Product':
			$parent=new Product('pid',$scope_data['Email Campaign Objective Parent Key']);
			$parent_key=$parent->pid;
			$parent_name='<b>'.$parent->data['Product Code'].'</b>, '.$parent->data['Product Name'];
			$term='Order';
			$term_metadata='0;;432000';
			break;
		case 'Deal':
			$parent=new DealComponentMetadataMetadata($scope_data['Email Campaign Objective Parent Key']);
			$parent_key=$parent->pid;
			$parent_name=$parent->data['Deal Component Name'];
			$term='Use';
			$term_metadata='432000';
			break;
		case 'External Link':
			$parent_key=0;
			$parent_name=$scope_data['Email Campaign Objective Parent Name'];
			$term='Visit';
			$term_metadata='432000';
			break;

		default:
			return;
			break;
		}

		$found=false;

		if ($scope_data['Email Campaign Objective Parent']!='External Link') {


			$sql=sprintf("select `Email Campaign Objective Key` from `Email Campaign Objective Dimension` where `Email Campaign Key`=%d  and `Email Campaign Objective Parent`=%s  and  `Email Campaign Objective Parent Key`=%d ",
				$this->id,
				prepare_mysql($scope_data['Email Campaign Objective Parent']),
				$parent_key
			);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$found=$row['Email Campaign Objective Key'];

			}

		}
		if ($found) {
			if ($scope_data['Email Campaign Objective Type']=='Link') {
				$sql=sprintf("update `Email Campaign Objective Dimension` set `Email Campaign Objective Type`='Link'  where `Email Campaign Key`=%d ",
					$found
				);

			}

		} else {
			$sql=sprintf("insert into `Email Campaign Objective Dimension` (`Email Campaign Key`,`Email Campaign Objective Type`,`Email Campaign Objective Parent`,`Email Campaign Objective Parent Key`,`Email Campaign Objective Name`,`Email Campaign Objective Links`,`Email Campaign Objective Links Clicks`,`Email Campaign Objective Term`,`Email Campaign Objective Term Metadata`)  values (%d,%s,%s,%d,%s,0,0,%s,%s)  ",
				$this->id,
				prepare_mysql($scope_data['Email Campaign Objective Type']),
				prepare_mysql($scope_data['Email Campaign Objective Parent']),

				$parent_key,
				prepare_mysql($parent_name),
				prepare_mysql($term),
				prepare_mysql($term_metadata)

			);
			mysql_query($sql);

		}



		//     print $sql;

	}




	function delete_email_address($email_address_key) {


		$sql=sprintf("delete from  `Email Campaign Mailing List` where `Email Campaign Mailing List Key`=%d and `Email Campaign Key`=%d",
			$email_address_key,
			$this->id
		);
		$res=mysql_query($sql);

		if (mysql_affected_rows()) {
			$this->updated=true;
			$this->update_number_emails();
			$this->update_recipients_preview();
		} else {
			$this->msg='can not delete recipient';

		}

	}


	function add_email_address_manually($data) {
		$data['Email Address']=_trim($data['Email Address']);
		if ($data['Email Address']=='') {
			$this->error=true;
			$this->msg=_('Wrong Email Address');
			return;
		}

		$sql=sprintf("select `Email Campaign Mailing List Key` from `Email Campaign Mailing List` where `Email Campaign Key`=%d and `Email Address`=%s ",
			$this->id,
			prepare_mysql($data['Email Address'])
		);
		$res=mysql_query($sql);
		//  print $sql;
		if ($row=mysql_fetch_assoc($res)) {
			$this->error=true;
			$this->msg=_('Email Address already in mailing list');
			return;

		}

		$data['Customer Key']=false;

		if ($this->insert_email_to_mailing_list($data)>0) {
			$this->updated=true;
			$this->update_number_emails();
			$this->update_recipients_preview();
		} else {
			$this->msg=_('Can not add email to mailing list');
		}

	}
	function add_emails_from_list($list_key,$force_send_to_customer_who_dont_want_to_receive_email=false) {
		$sql=sprintf("select * from `List Dimension` where `List Key`=%d",$list_key);
		$res=mysql_query($sql);
		if (!$customer_list_data=mysql_fetch_assoc($res)) {
			$this->error=true;
			$this->msg='List not found';
			return;
		}
		$emails_already_in_the_mailing_list=0;
		$emails_added=0;
		$customer_without_email_address=0;
		$customer_dont_want_to_receive_email=0;
		$sent_to_customer_dont_want_to_receive_email=0;
		$group='';
		if ($customer_list_data['List Type']=='Static') {

			$sql=sprintf("select `Customer Main Contact Name`,C.`Customer Key`,`Customer Main Plain Email`,`Customer Main Email Key`,`Customer Send Email Marketing` from `List Customer Bridge` B left join `Customer Dimension` C on (B.`Customer Key`=C.`Customer Key`) where `List Key`=%d ",
				$list_key
			);




		} else {//dynamic

			$where='where true';
			$table='`Customer Dimension` C ';

			$tmp=preg_replace('/\\\"/','"',$customer_list_data['List Metadata']);
			$tmp=preg_replace('/\\\\\"/','"',$tmp);
			$tmp=preg_replace('/\'/',"\'",$tmp);

			$raw_data=json_decode($tmp, true);
			include_once 'list_functions_customer.php';
			list($where,$table,$group)=customers_awhere($raw_data);

			$where.=sprintf(' and `Customer Store Key`=%d ',$this->data['Email Campaign Store Key'] );



			$sql=sprintf("select `Customer Main Contact Name`,C.`Customer Key`,`Customer Main Plain Email`,`Customer Main Email Key`,`Customer Send Email Marketing` from $table $where $group "

			);

		}




		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			if (!$row['Customer Main Email Key'] or $row['Customer Main Plain Email']=='') {
				$customer_without_email_address++;
				continue;
			}
			if ($row['Customer Send Email Marketing']=='No') {
				$customer_dont_want_to_receive_email++;
				if (!$force_send_to_customer_who_dont_want_to_receive_email)
					continue;
				else
					$sent_to_customer_dont_want_to_receive_email++;
			}

			$data['Email Address']=$row['Customer Main Plain Email'];
			$data['Email Key']=$row['Customer Main Email Key'];
			$data['Email Contact Name']=$row['Customer Main Contact Name'];

			$data['Customer Key']=$row['Customer Key'];
			$result=$this->insert_email_to_mailing_list($data);
			if ($result>0) {
				$emails_added++;

			} else {
				$emails_already_in_the_mailing_list++;

			}

		}


		$msg='<table>';
		$msg.='<tr><td>'._('Email Address Added').':</td><td>'.number($emails_added).'</td></tr>';

		if ($customer_without_email_address) {
			$msg.='<tr><td>'._('Customers without email').':</td><td>'.$customer_without_email_address.'</td></tr>';
		}
		if ($customer_dont_want_to_receive_email) {
			$msg.='<tr><td>'._('Skipped (Customer preferences)').':</td><td>'.$customer_dont_want_to_receive_email.'</td></tr>';
		}
		if ($emails_already_in_the_mailing_list) {
			$msg.='<tr><td>'._('Skipped (Email already added)').':</td><td>'.$emails_already_in_the_mailing_list.'</td></tr>';
		}
		$msg.='</table>';
		$this->msg=$msg;


		$this->updated=true;
		$this->update_number_emails();
		$this->update_recipients_preview();



	}
	function add_paragraph($email_content_key,$paragraph_data) {

		$sql=sprintf("select `Email Paragraph Key`,`Paragraph Order` from `Email Content Paragraph Dimension` where `Email Content Key`=%d order by `Paragraph Order` desc limit 1",$email_content_key);
		$res=mysql_query($sql);
		$last_order_index=1;
		if ($row=mysql_fetch_assoc($res)) {
			$last_order_index=$row['Paragraph Order']+1;

		}

		if ($paragraph_data['title'] or $paragraph_data['subtitle'] or $paragraph_data['content']) {

			$sql=sprintf("insert into `Email Content Paragraph Dimension` (

                         `Email Content Key` ,
                         `Paragraph Order` ,
                         `Paragraph Type` ,
                         `Paragraph Title` ,
                         `Paragraph Subtitle` ,
                         `Paragraph Content`
                         ) values (%d,%d,%s,%s,%s,%s)",

				$email_content_key,
				$last_order_index,
				prepare_mysql($paragraph_data['type']),
				prepare_mysql($paragraph_data['title'],false),
				prepare_mysql($paragraph_data['subtitle'],false),
				prepare_mysql($paragraph_data['content'])
			);
			mysql_query($sql);
			//print $sql;
			$this->update_links($email_content_key);

			$this->updated=true;
		}

	}
	function assign_email_content_key() {

		return $this->get_first_content_key();
	}
	function delete() {
		if ($this->data['Email Campaign Status']=='Creating') {
			$content_keys=$this->get_content_data_keys();
			$sql=sprintf("delete from `Email Campaign Content Bridge` where `Email Campaign Key`=%d",$this->id);
			mysql_query($sql);
			$sql=sprintf("delete from `Email Campaign Dimension` where `Email Campaign Key`=%d",$this->id);
			mysql_query($sql);
			$sql=sprintf("delete from `Email Campaign Mailing List` where `Email Campaign Key`=%d",$this->id);
			mysql_query($sql);
			$sql=sprintf("delete from `Email Content Dimension` where `Email Content Key` in (%s)",join(',',$content_keys));
			mysql_query($sql);
			$this->updated=true;

		} else {
			$this->error=true;
			$this->msg='Email Campaign can not be deleted';
		}

	}
	function delete_paragraph($email_content_key,$paragraph_key) {
		$sql=sprintf("delete from  `Email Content Paragraph Dimension` where `Email Paragraph Key`=%d ",$paragraph_key);
		mysql_query($sql);
		// print "$sql";
		if (mysql_affected_rows()) {
			$this->updated=true;
		}

	}

	function get_first_content_key() {
		$tmp=$this->content_keys;
		return array_shift($tmp);
	}
	function get_content($content_key) {

		return $this->content_data[$content_key];


	}
	function get_content_data_keys() {
		$sql=sprintf("select `Email Content Key` from `Email Campaign Content Bridge` where `Email Campaign Key`=%d"
			,$this->id
		);
		$content_keys=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$content_keys[$row['Email Content Key']]= $row['Email Content Key'];
		}
		return $content_keys;

	}
	function get($key) {



		switch ($key) {
		case('Email Campaign Content Type'):
			return $this->get_content_type();
			break;
		default:
			if (isset($this->data[$key]))
				return $this->data[$key];
		}
		return false;
	}

	function get_content_type() {

		$types=array();
		$sql=sprintf("select `Email Content Type` from  `Email Content Dimension` C  left join `Email Campaign Content Bridge` B on (B.`Email Content Key`=C.`Email Content Key`) where `Email Campaign Key`=%d ",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$types[$row['Email Content Type']]=$row['Email Content Type'];

		}

		$number_types=count($types);
		if ($number_types==0) {
			return 'Unknown';
		}
		elseif ($number_types==1) {
			return array_pop($types);

		}
		else {
			return 'Multi Mixed';
		}


	}
	function get_contents_array() {
		$email_contents_array=array();
		$sql=sprintf("select `Email Content Color Scheme Historic Key`,`Email Content Template Postcard Key`,`Email Content Color Scheme Key`,`Email Content Template Type`,`Email Content Type`,`Email Content Subject`,`Email Content Type`,C.`Email Content Key`,`Email Content Text`,`Email Content HTML`,`Email Template Header Image Key`,`Email Content Metadata` from  `Email Content Dimension`   C  left join `Email Campaign Content Bridge` B on (B.`Email Content Key`=C.`Email Content Key`) where `Email Campaign Key`=%d ",$this->id);
		$res=mysql_query($sql);
		// print $sql;
		while ($row=mysql_fetch_assoc($res)) {


			$sql2=sprintf("select * from `Email Content Paragraph Dimension` where `Email Content Key`=%d order by `Paragraph Order`",$row['Email Content Key']);
			$res2=mysql_query($sql2);
			$paragraph_data=array();
			while ($row2=mysql_fetch_assoc($res2)) {
				$paragraph_data[$row2['Email Paragraph Key']]=array(
					'order'=>$row2['Paragraph Order'],
					'type'=>$row2['Paragraph Type'],
					'title'=>$row2['Paragraph Title'],
					'subtitle'=>$row2['Paragraph Subtitle'],
					'content'=>$row2['Paragraph Content']
				);
			}



			$color_scheme=array();




			$sql=sprintf("select * from `Email Template Color Scheme Dimension` where `Email Template Color Scheme Key`=%d ",$row['Email Content Color Scheme Key']);
			$res2=mysql_query($sql);
			if ($row2=mysql_fetch_assoc($res2)) {

				foreach ($row2 as $key=>$value) {
					$color_scheme[preg_replace('/ /','_',$key)]=$value;
				}

			}

			$sql=sprintf("select * from `Email Template Historic Color Scheme Dimension` where `Email Template Historic Color Scheme Key`=%d ",$row['Email Content Color Scheme Historic Key']);
			$res2=mysql_query($sql);
			if ($row2=mysql_fetch_assoc($res2)) {

				foreach ($row2 as $key=>$value) {
					$color_scheme[preg_replace('/ /','_',$key)]=$value;
				}

			}


			$header_image_key=0;
			if ($row['Email Template Header Image Key']) {
				$sql=sprintf("select `Image Key` from `Email Template Header Image Dimension` where `Email Template Header Image Key`=%d ",$row['Email Template Header Image Key']);

				$res2=mysql_query($sql);
				if ($row2=mysql_fetch_assoc($res2)) {
					$header_image_key=$row2['Image Key'];


				}

			}

			$postcard_image_key=0;
			if ($row['Email Content Template Postcard Key']) {
				$sql=sprintf("select `Image Key` from `Email Template Postcard Dimension` where `Email Template Postcard Key`=%d ",$row['Email Content Template Postcard Key']);
				$res2=mysql_query($sql);
				if ($row2=mysql_fetch_assoc($res2)) {
					$postcard_image_key=$row2['Image Key'];


				}

			}


			$email_contents_array[$row['Email Content Key']]=array(
				'type'=>$row['Email Content Type'],
				'template_type'=>$row['Email Content Template Type'],
				'color_scheme'=>$color_scheme,


				'subject'=>$row['Email Content Subject'],
				'plain'=>$row['Email Content Text'],
				'html'=>$row['Email Content HTML'],
				'paragraphs'=>$paragraph_data,
				'header_image_key'=>$header_image_key,
				'postcard_image_key'=>$postcard_image_key
			);



		}



		return $email_contents_array;
	}
	function get_subject($email_content_key) {
		$subject='';
		$sql=sprintf("select `Email Content Subject` from  `Email Content Dimension`  where `Email Content Key`=%d",$email_content_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$subject=$row['Email Content Subject'];
		}
		return $subject;
	}
	function get_first_mailing_list_key() {

		$sql=sprintf("select `Email Campaign Mailing List Key` from `Email Campaign Mailing List` where  `Email Campaign Key`=%d limit 1",

			$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			return $row['Email Campaign Mailing List Key'];

		} else {
			return 0;

		}


	}
	function get_recipient_email($email_mailing_list_key=false) {
		if (!$email_mailing_list_key)
			$email_mailing_list_key=$this->get_first_mailing_list_key();

		$sql=sprintf("select `Email Address` from `Email Campaign Mailing List` where `Email Campaign Mailing List Key`=%d and `Email Campaign Key`=%d",
			$email_mailing_list_key,
			$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			return $row['Email Address'];
		} else {
			return '';
		}

	}
	function get_email_mailing_list_key_from_index($index) {



		$sql=sprintf("select `Email Campaign Mailing List Key` from `Email Campaign Mailing List` where `Email Campaign Key`=%d limit %d, 1 ",

			$this->id,
			($index-1)
		);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			return $row['Email Campaign Mailing List Key'];

		} else {
			return 0;
		}


	}



	function get_templete_html($html_data,$email_mailing_list_key=false,$_email_content_key=false,$color_scheme_key=false) {


		$smarty=$html_data['smarty'];
		$css_files=$html_data['css_files'];
		$js_files=$html_data['js_files'];
		$output_type=$html_data['output_type'];

		$inikoo_public_path='';
		if (array_key_exists('inikoo_public_path',$html_data))
			$inikoo_public_path=$html_data['inikoo_public_path'];


		$sql=sprintf("select * from `Email Campaign Mailing List` where `Email Campaign Mailing List Key`=%d and `Email Campaign Key`=%d",
			$email_mailing_list_key,
			$this->id
		);
		$res=mysql_query($sql);

		if ($row=mysql_fetch_assoc($res)) {
			$email_content_key=$row['Email Content Key'];


			$customer=new Customer($row['Customer Key']);
			if (!$customer->id) {
				$customer->data['Customer Main Contact Name']=$row['Email Contact Name'];
				$customer->data['Customer Name']=$row['Email Contact Name'];
				$customer->data['Customer Main Plain Email']=$row['Email Address'];
				$customer->data['Customer Type']='person';
			}

		}





		if ($_email_content_key) {


			$email_content_key=$_email_content_key;
			$customer=new Customer(0);
			$customer->data['Customer Main Contact Name']='Albert Mc Loving';
			$customer->data['Customer Name']="Albert's Widgets";
			$customer->data['Customer Type']='company';
			$customer->data['Customer Main Plain Email']="albert@example.com";
		}




		$email_content_data=$this->get_content($email_content_key);
		//print_r($email_content_data);

		if (!$email_content_data) {
			return 'error no content found';
		}

		$js_files[]='edit_email_template.js.php?email_content_key='.$email_content_key;
		$smarty->assign('css_files',$css_files);
		$smarty->assign('js_files',$js_files);

		$smarty->assign('email_content_key',$email_content_key);
		$smarty->assign('edit',($output_type=='edit'?1:0));
		$store=new Store($this->data['Email Campaign Store Key']);
		$smarty->assign('email_campaign',$this);



		$smarty->assign('paragraphs',$email_content_data['paragraphs']);

		if (isset($color_scheme_key) and is_numeric($color_scheme_key)) {
			$color_scheme=array();
			$sql=sprintf("select * from `Email Template Color Scheme Dimension` where `Email Template Color Scheme Key`=%d ",
				$_REQUEST['color_scheme_key']);
			$res2=mysql_query($sql);
			if ($row2=mysql_fetch_assoc($res2)) {

				foreach ($row2 as $key=>$value) {
					$color_scheme[preg_replace('/ /','_',$key)]=$value;
				}

			}
		} else {

			$color_scheme=$email_content_data['color_scheme'];
		}

		$smarty->assign('color_scheme',$color_scheme);


		//print_r($email_content_data['header_image_key']);

		if (!$email_content_data['header_image_key']) {
			if ($email_content_data['template_type']=='Postcard')
				$header_src=$color_scheme['Header_Slim_Image_Source'];

			else
				$header_src=$color_scheme['Header_Image_Source'];
		} else {
			$header_src='public_image.php?id='.$email_content_data['header_image_key'];


		}

		if ($email_content_data['template_type']=='Postcard') {

			if (!$email_content_data['postcard_image_key']) {

				$postcard_src=$color_scheme['Postcard_Image_Source'];
			} else {
				$postcard_src='public_image.php?id='.$email_content_data['postcard_image_key'];


			}

			$smarty->assign('postcard_src',$inikoo_public_path.$postcard_src);

		}




		$smarty->assign('header_src',$inikoo_public_path.$header_src);



		$smarty->assign('store',$store);

		switch ($email_content_data['template_type']) {
		case 'Basic':
			$output = $smarty->fetch('email_basic.tpl');
			break;
		case 'Left Column':
			$output = $smarty->fetch('email_left_column.tpl');
			break;
		case 'Right Column':
			$output = $smarty->fetch('email_right_column.tpl');
			break;
		case 'Postcard':
			$output = $smarty->fetch('email_postcard.tpl');
			break;
		default:
			$output='';
			break;
		}



		if (preg_match_all('/\%[a-z]+\%/',$output,$matches)) {
			foreach ($matches[0] as $match) {
				$output=preg_replace('/'.$match.'/',$customer->get(preg_replace('/\%/','',$match)),$output);
			}
		}
		return $output;

	}



	function consolidate() {



		foreach ($this->content_data as $content_data_key=>$content_data) {

			// print_r($content_data);


			switch ($content_data['type']) {
			case 'HTML':
				$html=$this->get_content_html($content_data_key);
				break;
			case 'HTML Template':
				$html='';

				foreach ($content_data['paragraphs'] as $paragraph_data) {
					$html.=$paragraph_data['title'].' '.$paragraph_data['subtitle'].' '.$paragraph_data['content'];
				}

				break;
			default:
				return;
				break;
			}
			$links=array();
			$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
			if (preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {
				foreach ($matches as $match) {

					$url=preg_replace("/^https?\:\/\//",'',$match[2]);
					$link_label=$match[3];

					$links[$url]=$link_label;



				}
			}


			//print_r($links);
			//exit;













			if ($content_data['type']=='HTML Template') {

				if (!$content_data['header_image_key']) {
					if ($content_data['template_type']=='Postcard') {
						$header_src=$content_data['color_scheme']['Header_Slim_Image_Source'];
					} else {
						$header_src=$content_data['color_scheme']['Header_Image_Source'];
					}

					$data=array(
						'file'=>$header_src,
						'source_path'=>'',
						'name'=>basename($header_src),
						'caption'=>''
					);

					//print_r($data);
					$image=new Image('find',$data,'create');

					if (!$image->id) {

						print_r($image);
						exit;

					}


					$sql=sprintf("select `Email Template Header Image Key` from `Email Template Header Image Dimension` where `Store Key`=%d and `Image Key`=%d",
						$this->data['Email Campaign Store Key'],
						$image->id
					);


					$res=mysql_query($sql);
					if ($row=mysql_fetch_assoc($res)) {

						$_header_image_key=$row['Email Template Header Image Key'];
					} else {


						$sql=sprintf("insert into `Email Template Header Image Dimension` (`Email Template Header Image Name`,`Store Key`,`Image Key`) values (%s,%d,%d) ",
							prepare_mysql(basename($header_src)),
							$this->data['Email Campaign Store Key'],
							$image->id

						);
						mysql_query($sql);
						$_header_image_key=mysql_insert_id();
					}

					$sql=sprintf("update `Email Content Dimension` set `Email Template Header Image Key`=%d where `Email Content Key`=%d",
						$_header_image_key,
						$content_data_key
					);
					mysql_query($sql);





					//print $sql;


				}

				if ($content_data['template_type']=='Postcard' and !$content_data['postcard_image_key']) {

					$postcard_src=$content_data['color_scheme']['Postcard_Image_Source'];


					$data=array(
						'file'=>$postcard_src,
						'source_path'=>'',
						'name'=>'email_postcard_'.$this->id.'_'.$content_data_key,
						'caption'=>''
					);

					//print_r($data);
					$image=new Image('find',$data,'create');

					if (!$image->id) {

						print_r($image);
						exit;

					}

					$sql=sprintf("update `Email Content Dimension` set `Email Content Template Postcard Key`=%d where `Email Content Key`=%d",
						$image->id,
						$content_data_key
					);
					mysql_query($sql);


				}



				$base_data=array();

				foreach ($content_data['color_scheme'] as $key=>$value) {


					if (!($key=='Email_Template_Color_Scheme_Key' or $key=='Email_Template_Color_Scheme_Name' or $key=='Header_Image_Source' or $key=='Store_Key'  )) {
						$key=preg_replace("/_/"," ",$key);
						$base_data[$key]=$value;
					}

				}

				$where='';
				$historic_keys='';
				$historic_values='';
				foreach ($base_data as $_key=>$_value) {
					$where.=sprintf(" and `%s`=%s",$_key,prepare_mysql($_value));
					$historic_keys.=",`$_key`";
					$historic_values.=",".prepare_mysql($_value);
				}
				$where=preg_replace('/^ and/','',$where);
				$historic_keys=preg_replace('/^,/','',$historic_keys);
				$historic_values=preg_replace('/^,/','',$historic_values);
				$sql="select `Email Template Historic Color Scheme Key` from `Email Template Historic Color Scheme Dimension` where $where";


				$res=mysql_query($sql);
				if ($row=mysql_fetch_assoc($res)) {

					$historic_color_scheme=$row['Email Template Historic Color Scheme Key'];
				} else {




					$sql="insert into `Email Template Historic Color Scheme Dimension`($historic_keys) values ($historic_values) ";
					mysql_query($sql);
					$historic_color_scheme=mysql_insert_id();

				}
				$sql=sprintf("update `Email Content Dimension` set `Email Content Color Scheme Historic Key`=%d where `Email Content Key`=%d",
					$historic_color_scheme,
					$content_data_key
				);
				mysql_query($sql);

			}


		}


	}


	function get_message_data($email_mailing_list_key=false,$smarty=false,$inikoo_public_path='') {

		$this->get_data('id',$this->id);

		if (!$email_mailing_list_key)
			$email_mailing_list_key=$this->get_first_mailing_list_key();
		include_once 'class.Customer.php';

		$sql=sprintf("select * from `Email Campaign Mailing List` where `Email Campaign Mailing List Key`=%d and `Email Campaign Key`=%d",
			$email_mailing_list_key,
			$this->id
		);
		$res=mysql_query($sql);
		$plain='';
		$html='';
		$to='';
		if ($row=mysql_fetch_assoc($res)) {

			$to=$row['Email Address'];

			$email_content_key=$row['Email Content Key'];
			$customer=new Customer($row['Customer Key']);
			if (!$customer->id) {
				$customer->data['Customer Main Contact Name']=$row['Email Contact Name'];
				$customer->data['Customer Name']=$row['Email Contact Name'];
				$customer->data['Customer Main Plain Email']=$row['Email Address'];

				$customer->data['Customer Type']='person';

			}

			switch ($type=$this->content_data[$email_content_key]['type']) {
			case 'Plain':
				$plain=   nl2br($this->content_data[$email_content_key]['plain']);
				$html= '';
				break;
			case 'HTML':
				$plain=   nl2br($this->content_data[$email_content_key]['plain']);
				$html=   nl2br($this->content_data[$email_content_key]['html']);
				break;
			case 'HTML Template':
				$plain=   nl2br($this->content_data[$email_content_key]['plain']);


				$html_data=array('smarty'=>$smarty,'css_files'=>array(),'js_files'=>array(),'output_type'=>'consolidated','inikoo_public_path'=>$inikoo_public_path);

				$html=  $this->get_templete_html($html_data,$email_mailing_list_key);

				break;
			default:

				break;
			}

			if (preg_match_all('/\%[a-z]+\%/',$plain,$matches)) {
				foreach ($matches[0] as $match) {
					$plain=preg_replace('/'.$match.'/',$customer->get(preg_replace('/\%/','',$match)),$plain);
				}
			}
			if (preg_match_all('/\%[a-z]+\%/',$html,$matches)) {
				foreach ($matches[0] as $match) {
					$html=preg_replace('/'.$match.'/',$customer->get(preg_replace('/\%/','',$match)),$html);
				}
			}
			$subject=$this->get_subject($email_content_key);
			$ok=true;
		} else {
			$plain= 'Error recipient not associated with mailing list';
			$html= 'Error recipient not associated with mailing list';
			$type=false;
			$subject='';
			$ok=false;
		}



		return array('ok'=>$ok,'subject'=>$subject,'plain'=>$plain,'html'=>$html,'type'=>$type,'to'=>$to);
	}
	function get_content_text($email_content_key) {
		$content_text='';
		$sql=sprintf("select `Email Content Text` from  `Email Content Dimension`  where `Email Content Key`=%d",$email_content_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$content_text= $row['Email Content Text'];
		}
		return $content_text;
	}


	function get_template_type($email_content_key) {

		$template_type='';
		$sql=sprintf("select `Email Content Template Type` from  `Email Content Dimension`  where `Email Content Key`=%d",$email_content_key);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$template_type= $row['Email Content Template Type'];
		}
		return $template_type;

	}




	function get_color_scheme($email_content_key) {

		$color_scheme='';
		$sql=sprintf("select `Email Content Color Scheme Key` from  `Email Content Dimension`  where `Email Content Key`=%d",$email_content_key);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$color_scheme= $row['Email Content Color Scheme Key'];
		}
		return $color_scheme;

	}


	function get_content_html($email_content_key) {
		$content_html='';
		$sql=sprintf("select `Email Content HTML` from  `Email Content Dimension`  where `Email Content Key`=%d",$email_content_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$content_html= $row['Email Content HTML'];
		}
		return $content_html;
	}

	function insert_email_to_mailing_list($data) {

		if (!array_key_exists('Email Key',$data)) {
			$email=new Email('email',$data['Email Address']);
			if ($email->id)
				$data['Email Key']=$email->id;
			else
				$data['Email Key']=false;
		}

		$email_content_key=$this->assign_email_content_key();

		$sql=sprintf("insert into `Email Campaign Mailing List` (`Email Campaign Key`,`Email Key`,`Email Address`,`Email Contact Name`,`Customer Key`,`Email Content Key`)
                     values (%d,%s,%s,%s,%s,%d)",
			$this->id,
			prepare_mysql($data['Email Key']),
			prepare_mysql($data['Email Address']),
			prepare_mysql($data['Email Contact Name'],false),
			prepare_mysql($data['Customer Key']),
			$email_content_key

		);
		mysql_query($sql);
		//  print $sql;
		return mysql_affected_rows();

	}
	function move_paragraph_to_the_end($email_content_key,$paragraph_key,$paragraph_type='Main',$change_original=true) {
		$sql=sprintf("select `Email Paragraph Key`,`Paragraph Order` from `Email Content Paragraph Dimension` where `Email Content Key`=%d and `Paragraph Type`=%s order by `Paragraph Order` desc limit 1",
			$email_content_key,
			prepare_mysql($paragraph_type)

		);

		$res=mysql_query($sql);
		$last_order_index=1;
		$last_paragraph_key=0;
		if ($row=mysql_fetch_assoc($res)) {
			$last_order_index=$row['Paragraph Order']+1;
			$last_paragraph_key=$row['Email Paragraph Key'];
		}
		if ($last_paragraph_key!=$paragraph_key) {

			$sql=sprintf("update `Email Content Paragraph Dimension`  set `Paragraph Order`=%d , `Paragraph Type`=%s where `Email Paragraph Key`=%d ",
				$last_order_index,
				prepare_mysql($paragraph_type),
				$paragraph_key);

			mysql_query($sql);

			if (mysql_affected_rows()) {
				$this->updated;
			}
			if ($change_original) {
				$sql=sprintf("update `Email Content Paragraph Dimension`  set  `Paragraph Original Type`=%s where `Email Paragraph Key`=%d ",
					prepare_mysql($paragraph_type),
					$paragraph_key);

				mysql_query($sql);
			}
		}


	}
	function move_paragraph_before_target($email_content_key,$paragraph_key,$target_key,$paragraph_type,$change_original=true) {


		if ($target_key==0) {

			return $this->move_paragraph_to_the_end($email_content_key,$paragraph_key,$paragraph_type,$change_original);
		}

		$sql=sprintf("select `Paragraph Type` from `Email Content Paragraph Dimension` where `Email Content Key`=%d  and `Email Paragraph Key`=%d   ",
			$email_content_key,
			$target_key
		);
		$res=mysql_query($sql);

		$paragraph_type='Main';
		if ($row=mysql_fetch_assoc($res)) {
			$paragraph_type=$row['Paragraph Type'];
		}

		$res=mysql_query($sql);
		$current_order=array();
		$i=1;
		$j=1;

		$new_order=array();
		while ($row=mysql_fetch_assoc($res)) {


			$sql=sprintf("select `Email Paragraph Key`,`Paragraph Order` from `Email Content Paragraph Dimension` where `Email Content Key`=%d and `Paragraph Type`=%s order by `Paragraph Order`",
				$email_content_key,
				prepare_mysql($paragraph_type)

			);

			$res=mysql_query($sql);
			$current_order=array();
			$i=1;
			$j=1;

			$new_order=array();
			while ($row=mysql_fetch_assoc($res)) {
				$current_order[$row['Email Paragraph Key']]=$j++;
				if ($row['Email Paragraph Key']==$paragraph_key) {
					continue;
				}
				if ($row['Email Paragraph Key']==$target_key) {
					$new_order[$paragraph_key]=$i++;
				}
				$new_order[$row['Email Paragraph Key']]=$i++;


			}
			foreach ($new_order as $_paragraph_key=>$paragraph_order) {
				$sql=sprintf("update `Email Content Paragraph Dimension`  set `Paragraph Type`=%s , `Paragraph Order`=%d where `Email Paragraph Key`=%d ",

					prepare_mysql($paragraph_type),
					$paragraph_order,
					$_paragraph_key);
				mysql_query($sql);
				if ($change_original) {
					$sql=sprintf("update `Email Content Paragraph Dimension`  set `Paragraph Original Type`=%s , `Paragraph Order`=%d where `Email Paragraph Key`=%d ",

						prepare_mysql($paragraph_type),
						$paragraph_order,
						$_paragraph_key);
					mysql_query($sql);
				}



			}


		}
	}
	function update_subject($value,$email_content_key) {


		$sql=sprintf("update `Email Content Dimension` set `Email Content Subject`=%s where `Email Content Key`=%d",
			prepare_mysql($value),
			$email_content_key
		);
		mysql_query($sql);


		if (mysql_affected_rows()>0) {
			$this->updated=true;
			$this->new_value=$value;
		}
	}
	function update_content_text($value,$email_content_key) {
		$sql=sprintf("update `Email Content Dimension` set `Email Content Text`=%s where `Email Content Key`=%d",
			prepare_mysql($value),
			$email_content_key
		);
		mysql_query($sql);


		if (mysql_affected_rows()>0) {
			$this->updated=true;
			$this->new_value=$this->get_content_text($email_content_key);
		}
	}

	function update_content_html($value,$email_content_key) {



		$sql=sprintf("update `Email Content Dimension` set `Email Content HTML`=%s where `Email Content Key`=%d",
			prepare_mysql($value),
			$email_content_key
		);
		mysql_query($sql);


		if (mysql_affected_rows()>0) {


			$this->update_links($email_content_key);


			$this->updated=true;
			$this->new_value=$this->get_content_html($email_content_key);
		}
	}


	function update_links($email_content_key) {


		switch ($this->content_data[$email_content_key]['type']) {
		case 'HTML':
			$html=$this->get_content_html($email_content_key);
			break;
		case 'HTML Template':
			$html='';

			foreach ($this->content_data[$email_content_key]['paragraphs'] as $paragraph_data) {
				$html.=$paragraph_data['title'].' '.$paragraph_data['subtitle'].' '.$paragraph_data['content'];
			}

			break;
		default:
			return;
			break;
		}
		$links=array();
		$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
		if (preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {
			foreach ($matches as $match) {

				$url=preg_replace("/^https?\:\/\//",'',$match[2]);
				$link_label=$match[3];

				$links[$url]=$link_label;



			}
		}

		//print_r($links);

		$sql=sprintf("delete from `Email Campaign Objective Dimension` where `Email Campaign Objective Type`='Link' and `Email Campaign Key`=%d ",
			$this->id
		);
		mysql_query($sql);


		foreach ($links as $url=>$link_data) {
			$alternative_urls=array();
			$page=new Page('url',$url);

			if (!$page->id or  $page->data['Page Type']!='Store' or $page->data['Page Store Key']!=$this->data['Email Campaign Store Key'] ) {

				$add_www=false;
				$remove_www=false;
				if (preg_match('/^www\./i',$url)) {
					$remove_www=true;
					$alternative_urls[]=preg_replace('/^www\./i','',$url);

				} else {
					$add_www=true;
					$alternative_urls[]='www.'.$url;

				}




				if (preg_match('/\/$/i',$url)) {
					$alternative_urls[]=$url.'index.html';
					$alternative_urls[]=$url.'index.php';
				}
				elseif (preg_match('/index\.(php|html|asp)$/i',$url)) {
					$alternative_urls[]=preg_replace('/index\.(php|html|asp)$/i','',$url);
				}
				else {
					$alternative_urls[]=$url.'/index.html';
					$alternative_urls[]=$url.'/index.php';

				}

				foreach ($alternative_urls as $value) {
					if ($add_www) {
						$alternative_urls[]='www.'.$value;
					}
					elseif ($remove_www) {
						$alternative_urls[]=preg_replace('/^www\./i','',$value);
					}
				}



			}
			foreach ($alternative_urls as $item) {
				$page=new Page('url',$item);
				if ($page->id and  $page->data['Page Type']=='Store' and $page->data['Page Store Key']==$this->data['Email Campaign Store Key']   ) {
					break;
				}

			}

			$parent_name='';
			if ($page->id and  $page->data['Page Type']=='Store' and $page->data['Page Store Key']==$this->data['Email Campaign Store Key']   ) {

				switch ($page->data['Page Store Section']) {
				case 'Department Catalogue':
					$parent='Department';
					$parent_key=$page->data['Page Parent Key'];

					break;
				case 'Family Catalogue':
					$parent='Family';
					$parent_key=$page->data['Page Parent Key'];

					break;
				case 'Product Description':
					$parent='Product';
					$parent_key=$page->data['Page Parent Key'];

					break;

					$parent='Store';
					$parent_key=$this->data['Email Campaign Store Key'];

				default:

					break;
				}


			} else {
				$parent='External Link';
				$parent_key=0;
				$parent_name=$url;
			}

			unset($page);

			$objective_data=array(
				'Email Campaign Objective Parent'=>$parent,
				'Email Campaign Objective Parent Key'=>$parent_key,
				'Email Campaign Objective Parent Name'=>$parent_name,
				'Email Campaign Objective Type'=>'Link'

			);
			//      print_r($objective_data);
			$this->add_objective($objective_data);



		}

	}



	function update_number_emails() {
		$this->data['Number of Emails']=0;
		$sql=sprintf("select count(*) as number from `Email Campaign Mailing List` where `Email Campaign Key`=%d",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->data['Number of Emails']=$row['number'];
		}
		$sql=sprintf("update `Email Campaign Dimension` set `Number of Emails`=%d where `Email Campaign Key`=%d",
			$this->data['Number of Emails'],
			$this->id);
		mysql_query($sql);
	}

	function update_send_emails() {
		$this->data['Number of Read Emails']=0;
		$sql=sprintf("select count(*) as number from `Email Send Dimension` where `Email Send Date` is not null  and  `Email Send Type`='Marketing' and `Email Send Type Parent Key`=%d",$this->id);
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->data['Number of Read Emails']=$row['number'];
		}
		$sql=sprintf("update `Email Campaign Dimension` set `Number of Read Emails`=%d where `Email Campaign Key`=%d",
			$this->data['Number of Read Emails'],
			$this->id);
		mysql_query($sql);
	}


	function update_recipients_preview() {
		$this->data['Email Campaign Recipients Preview']='';
		$sql=sprintf("select `Email Address` from `Email Campaign Mailing List` where `Email Campaign Key`=%d",$this->id);
		$res=mysql_query($sql);
		$num_previews_emails=0;
		while ($row=mysql_fetch_assoc($res)) {
			$num_previews_emails++;
			$this->data['Email Campaign Recipients Preview'].=', '.$row['Email Address'];
			if (strlen($this->data['Email Campaign Recipients Preview'])>250 and $this->data['Number of Emails']-$num_previews_emails>1)
				break;
		}
		$num_emails_not_previewed=$this->data['Number of Emails']-$num_previews_emails;
		if ($num_emails_not_previewed>0) {
			$this->data['Email Campaign Recipients Preview'].=", ... $num_emails_not_previewed "._('more');
		} else {
			$this->data['Email Campaign Recipients Preview'];

		}

		$this->data['Email Campaign Recipients Preview']=preg_replace('/^\,\s*/','',$this->data['Email Campaign Recipients Preview']);
		$sql=sprintf("update `Email Campaign Dimension` set `Email Campaign Recipients Preview`=%s where `Email Campaign Key`=%d",
			prepare_mysql($this->data['Email Campaign Recipients Preview']),
			$this->id);
		mysql_query($sql);
	}
	function update_field_switcher($field,$value,$options='') {


		switch ($field) {
		case('Email Campaign Content Text'):
			$this->update_content_text($value);
			break;
		case('Email Campaign Subject'):
			$this->update_subject($value);
			break;

		default:
			$base_data=$this->base_data();
			if (array_key_exists($field,$base_data)) {
				if ($value!=$this->data[$field]) {

					$this->update_field($field,$value,$options);
				}
			}

		}
	}


	function update_content($email_content_key,$key,$value) {

		$valid_keys=array('Email Content Type','Email Content Template Type','Email Content Color Scheme Key','Email Template Header Image Key','Email Content Template Postcard Key');
		if (in_array($key,$valid_keys)) {

			$sql=sprintf("select `%s` as old_value from  `Email Content Dimension`  where `Email Content Key`=%d ",
				$key,
				$email_content_key
			);
			mysql_query($sql);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$old_value=$row['old_value'];

			}


			$sql=sprintf("update `Email Content Dimension` set `%s`=%s  where `Email Content Key`=%d ",
				$key,
				prepare_mysql($value),
				$email_content_key
			);
			mysql_query($sql);
			if (mysql_affected_rows()) {

				if ($key=='Email Content Template Type') {

					if ($value=='Basic' or $value=='Postcard') {

						$sql=sprintf("select `Email Paragraph Key` from  `Email Content Paragraph Dimension`  where `Paragraph Type`='Side' and `Email Content Key`=%d ",

							$email_content_key
						);
						mysql_query($sql);
						$res=mysql_query($sql);
						// print $sql;
						while ($row=mysql_fetch_assoc($res)) {
							$sql=sprintf("update `Email Content Paragraph Dimension`  set `Paragraph Type`='Main' where `Email Paragraph Key=%d ",$row['Email Paragraph Key']);
							mysql_query($sql);
							//  print $sql;
							$this->move_paragraph_to_the_end($email_content_key,$row['Email Paragraph Key'],'Main',$change_original=false);
						}



					} else {
						$sql=sprintf("select `Email Paragraph Key` from  `Email Content Paragraph Dimension`  where `Paragraph Original Type`='Side' and `Paragraph Type`='Main' and `Email Content Key`=%d ",
							$email_content_key
						);
						mysql_query($sql);
						$res=mysql_query($sql);
						while ($row=mysql_fetch_assoc($res)) {
							$sql=sprintf("update `Email Content Paragraph Dimension`  set `Paragraph Type`='Side' where `Email Paragraph Key=%d ",$row['Email Paragraph Key']);
							mysql_query($sql);
							$this->move_paragraph_to_the_end($email_content_key,$row['Email Paragraph Key'],'Side',$change_original=false);
						}

					}

				}
				elseif ($key=='Email Content Type') {

					$this->data['Email Campaign Content Type']=$this->get_content_type();
					$sql=sprintf("update `Email Campaign Dimension` set `Email Campaign Content Type`=%s where `Email Campaign Key`=%d",
						prepare_mysql($this->data['Email Campaign Content Type']),
						$this->id
					);
					mysql_query($sql);


					$this->update_content_from_other_type($email_content_key,$old_value,$value);



				}

				$this->updated=true;
				$this->new_value=$value;
				$this->old_value=$old_value;
			}

		}






	}

	function update_content_from_other_type($email_content_key,$old_type,$new_type) {
		if ($old_type==$new_type) {
			return;
		}

		$content=$this->get_content_from_other_type($email_content_key,$old_type,$new_type);

		switch ($new_type) {
		case 'Plain':
			$this->update_content_text($content,$email_content_key);
			$this->updated_data=array('text'=>$content);
			break;
		case 'HTML':
			$this->update_content_html($content,$email_content_key);
			$this->updated_data=array('html'=>$content);
			break;
		case 'HTML Template':
			$sql=sprintf("delete from  `Email Content Paragraph Dimension` where `Email Content Key`=%d ",$email_content_key);
			mysql_query($sql);
			foreach ($content as $paragraph_data) {
				$this->add_paragraph($email_content_key,$paragraph_data);

			}

			break;

		}


	}


	function get_content_from_other_type($email_content_key,$old_type,$new_type) {
		if ($old_type==$new_type) {
			return;
		}

		switch ($old_type) {
		case 'Plain':



			$old_content=$this->content_data[$email_content_key]['plain'];

			break;
		case 'HTML':
			$old_content=$this->content_data[$email_content_key]['html'];
			break;
		case 'HTML Template':
			$old_content='';

			foreach ($this->content_data[$email_content_key]['paragraphs'] as $paragraph_data) {
				if ($paragraph_data['title']) {
					$old_content.='<h1>'.$paragraph_data['title'].'</h1>';
				}
				if ($paragraph_data['subtitle']) {
					$old_content.='<h2>'.$paragraph_data['subtitle'].'</h2>';
				}
				if ($paragraph_data['content']) {
					$old_content.='<p>'.$paragraph_data['content'].'</p>';
				}

			}

			break;

		}


		switch ($new_type) {
		case 'Plain':

			$content=$old_content;


			require_once 'html2text.php';
			$h2t = new html2text($old_content);
			$content=$h2t->get_text();

			break;

		case 'HTML':
			$content=$old_content;
			break;
		case 'HTML Template':
			$_content=$old_content;

			$content=array();



			$content[]=array('type'=>'Main','title'=>'','subtitle'=>'','content'=>$_content);


			break;

		}
		return $content;
	}


	function update_paragraph($email_content_key,$paragraph_key,$data) {


		$sql=sprintf("update `Email Content Paragraph Dimension` set `Paragraph Title`=%s,`Paragraph Subtitle`=%s,`Paragraph Content`=%s where `Email Paragraph Key`=%d ",
			prepare_mysql($data['title']),
			prepare_mysql($data['subtitle']),
			prepare_mysql($data['content']),
			$paragraph_key);
		mysql_query($sql);
		//print_r($sql);
		if (mysql_affected_rows()) {
			$this->content_data=$this->get_contents_array();
			$this->update_links($email_content_key);

			$this->updated=true;
		}



	}

	function set_as_ready($lag_seconds) {

		if ($this->data['Email Campaign Status']=='Sending') {
			$this->error=true;
			$this->msg=_('Campaign already sending emails');
			return;
		}
		if ($this->data['Email Campaign Status']=='Complete') {
			$this->error=true;
			$this->msg=_('Campaign already send');
			return;
		}

		$this->data['Email Campaign Status']='Ready';
		$this->data['Email Campaign Start Overdue Date']=date("Y-m-d H:i:s",strtotime(sprintf('now +%d seconds ',$lag_seconds)));
		$sql=sprintf("update `Email Campaign Dimension` set `Email Campaign Status`='Ready'  , `Email Campaign Start Overdue Date`=%s ",
			prepare_mysql($this->data['Email Campaign Start Overdue Date'])

		);

		//print $sql;
		mysql_query($sql);

	}


}



?>
