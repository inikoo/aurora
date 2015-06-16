<?php
/*
 File: Site.php

 This file contains the Site Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';
include_once 'class.Page.php';
include_once 'class.PageStoreSection.php';

class Site extends DB_Table {

	var $new=false;

	function Site($arg1=false,$arg2=false) {
		$this->table_name='Site';
		$this->ignore_fields=array('Site Key');


		if (!$arg1 and !$arg2) {
			$this->error=true;
			$this->msg='No arguments';
		}
		if (is_numeric($arg1)) {
			$this->get_data('id',$arg1);
			return;
		}



		if (is_array($arg2) and preg_match('/create|new/i',$arg1)) {
			$this->find($arg2,'create');
			return;
		}


		$this->get_data($arg1,$arg2);

	}


	function get_data($tipo,$tag) {


		$sql=sprintf("select * from `Site Dimension` where  `Site Key`=%d",$tag);
		$result =mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->id=$this->data['Site Key'];
			//print_r($this->data);
			if ($this->data['Site Logo Data']!='')
				$this->data['Site Logo Data']=unserialize($this->data['Site Logo Data']);
			if ($this->data['Site Header Data']!='')
				$this->data['Site Header Data']=unserialize($this->data['Site Header Data']);
			if ($this->data['Site Content Data']!='')
				$this->data['Site Content Data']=unserialize($this->data['Site Content Data']);
			if ($this->data['Site Footer Data']!='')
				$this->data['Site Footer Data']=unserialize($this->data['Site Footer Data']);
			if ($this->data['Site Layout Data']!='')
				$this->data['Site Layout Data']=unserialize($this->data['Site Layout Data']);
			if ($this->data['Site Checkout Metadata']!='')
				$this->data['Site Checkout Metadata']=unserialize($this->data['Site Checkout Metadata']);
			else {
				$this->data['Site Checkout Metadata']=array('id'=>'','url'=>'','url_multi'=>'');
			}




		}


	}



	function get_checkout_data($item) {
		return $this->data['Site Checkout Metadata'][$item];
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

		$create='';
		$update='';
		if (preg_match('/create/i',$options)) {
			$create='create';
		}
		if (preg_match('/update/i',$options)) {
			$update='update';
		}





		$sql=sprintf("select `Site Key` from `Site Dimension` where `Site Name`=%s and `Site Store Key`=%d ",

			prepare_mysql($raw_data['Site Name']),
			$raw_data['Site Store Key']

		);



		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->found=true;
			$this->found_key=$row['Site Key'];
			$this->get_data('id',$this->found_key);
			return;
		}

		$sql=sprintf("select `Site Key` from `Site Dimension` where `Site Code`=%s or `Site URL`=%s ",

			prepare_mysql($raw_data['Site Code']),
			prepare_mysql($raw_data['Site URL'])

		);



		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->found=true;
			$this->found_key=$row['Site Key'];
			$this->get_data('id',$this->found_key);
			return;
		}




		if ($create and !$this->found) {
			$this->create($raw_data);
		}

	}


	function create($raw_data) {

		//print_r($raw_data);

		$data=$this->base_data();


		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key,$data))



				if (is_array($value))
					$data[$key]=serialize($value);
				else
					$data[$key]=_trim($value);


		}

		if ($data['Site Default Number See Also Links']=='') {
			$data['Site Default Number See Also Links']=0;
		}

		$keys='(';
		$values='values(';
		foreach ($data as $key=>$value) {
			$keys.="`$key`,";

			if (in_array($key,array('Site Contact Address','Site Contact Telephone','Site Slogan','Site Checkout Metadata','Site Menu HTML','Site Menu CSS','Site Menu Javascript','Site Search HTML','Site Search CSS','Site Search Javascript','Site FTP Directory','Site Direct Subscribe Madmimi'))) {
				$values.=prepare_mysql($value,false).",";
			}else {
				$values.=prepare_mysql($value).",";
			}
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Site Dimension` %s %s",$keys,$values);


		if (mysql_query($sql)) {
			$this->id=mysql_insert_id();
			$this->get_data('id',$this->id);
			$this->new=true;



			$flags=array('Blue'=>_('Blue'),'Green'=>_('Green'),'Orange'=>_('Orange'),'Pink'=>_('Pink'),'Purple'=>_('Purple'),'Red'=>_('Red'),'Yellow'=>_('Yellow'));
			foreach ($flags as $flag=>$flag_label) {
				$sql=sprintf("INSERT INTO `Site Flag Dimension` (`Site Flag Key`, `Site Key`, `Site Flag Color`, `Site Flag Label`, `Site Flag Active`) VALUES ( %d, %s,%s, 'Yes')",
					$this->id,
					prepare_mysql($flag),
					prepare_mysql($flag_label)
				);

				mysql_query($sql);

			}


			if ( is_numeric($this->editor['User Key']) and $this->editor['User Key']>1) {
				$sql="insert into `User Right Scope Bridge` values(1,'Website',".$this->id.");";
				mysql_query($sql);
				$sql=sprintf("insert into `User Right Scope Bridge` values(%d,'Website',%d)",
					$this->editor['User Key'],
					$this->id
				);
				mysql_query($sql);

			}

			$this->create_site_page_sections();


			$history_data=array(
				'History Abstract'=>_('Website created'),
				'History Details'=>_trim(_('New website')." ".$this->data['Site Name']." "._('added')),
				'Action'=>'created'
			);
			$this->add_subject_history($history_data);


			$store=new Store($this->data['Site Store Key']);
			$store->editor=$this->editor;
			$history_data=array(
				'History Abstract'=>_('Website created')." ".$this->data['Site Code'],
				'History Details'=>_trim(_('New website')." ".$this->data['Site Name']." "._('created')),
				'Action'=>'deleted'
			);
			$store->add_subject_history($history_data);


			$this->new=true;

		} else {
			$this->error=true;
			$this->msg='Can not insert Site Dimension';
			exit("$sql\n");
		}


	}



	function create_site_page_sections() {
		$sections=getEnumValues('Page Store Dimension', 'Page Store Section');

		foreach ($sections as $section) {
			$sql=sprintf("INSERT INTO `Page Store Section Dimension` (
                         `Page Store Section Key` ,
                         `Site Key` ,
                         `Page Store Section Code` ,
                         `Page Store Section Logo Data` ,
                         `Page Store Section Header Data` ,
                         `Page Store Section Content Data` ,
                         `Page Store Section Footer Data` ,
                         `Page Store Section Layout Data`
                         )
                         VALUES (
                         NULL , %s, %s, NULL , NULL , NULL , NULL , NULL
                         );
                         ",$this->id,prepare_mysql($section));

			mysql_query($sql);

		}
	}




	function get($key) {




		switch ($key) {
		case ('Percentage Number Pages with Products'):
			return percentage($this->data['Site Number Pages with Out of Stock Products'],$this->data['Site Number Pages with Products']);
			break;
		case ('Percentage Number Out of Stock Products'):
			return percentage($this->data['Site Number Out of Stock Products'],$this->data['Site Number Products']);
			break;



		case('Sitemap Last Update'):

			return strftime("%a %e %b %Y %H:%M %Z",strtotime($this->data['Site Sitemap Last Update'].' +0:00'));
			break;
		case('Total Users'):
			return number($this->data['Site Total Users']);
			break;
		default:




			if (array_key_exists($key,$this->data))
				return $this->data[$key];
		}
		if (preg_match('/ Acc /',$key)) {

			$amount='Site '.$key;

			return number($this->data[$amount]);
		}
		if (preg_match('/^Number /',$key)) {

			$amount='Site '.$key;

			return number($this->data[$amount]);
		}




		return false;
	}





	function update_checkout_data($field,$value) {

		$old_value=$this->data['Site Checkout Metadata'][$field];

		$this->data['Site Checkout Metadata'][$field]=$value;

		$sql=sprintf("update `Site Dimension` set `Site Checkout Metadata`=%s where `Site Key`=%d",
			prepare_mysql(serialize($this->data['Site Checkout Metadata'])),
			$this->id
		);
		mysql_query($sql);
		$this->updated=true;
		$this->new_value=$value;

		switch ($field) {
		case('url'):
			$field_label='URL';
			break;
		case('id'):
			$field_label='Id';
			break;
		default:
			$field_label=$field;
		}

		$history_data=array(
			'History Abstract'=>_('Site checkout data changed').' ['.$field_label.'] ('.$old_value.' &rarr; '.$this->new_value.')',
			'History Details'=>'',


		);
		$this->add_history($history_data);


	}




	function update_field_switcher($field,$value,$options='') {

		switch ($field) {

		case('Site Menu CSS'):
		case('Site Menu Javascript'):
		case('Site Search CSS'):
		case('Site Search Javascript'):
			if ($this->data['Site SSL']=='Yes') {
				$site_protocol='https';
			}else {
				$site_protocol='http';
			}
			$this->update_field($field,$value,'diff');
			$template_response=@file_get_contents($site_protocol.'://'.$this->data['Site URL']."/maintenance/write_templates.php?parent=site_elements&parent_key=".$this->id."&sk=x");

			break;
		case('Site Head Include'):
		case('Site Body Include'):
		case('Site Menu HTML'):
		case('Site Search HTML'):

		case('Site Forgot Password Email HTML Body'):
		case('Site Forgot Password Email Plain Body'):
		case('Site Forgot Password Email Subject'):
		case('Site Registration Disclaimer'):
		case('Site Welcome Email HTML Body'):
		case('Site Welcome Email Plain Body'):
		case('Site Welcome Email Subject'):
		case('Site Welcome Source'):
			$this->update_field($field,$value,'diff');
			break;
		case 'checkout_id':
			$this->update_checkout_data('id',$value);
			break;
		case 'checkout_url':
			$this->update_checkout_data('url',$value);
			break;

		case 'Email Address':
		case 'Login':
		case 'Password':
		case 'Incoming Mail Server':
		case 'Outgoing Mail Server':
			$this->update_site_email_credential($field, $value);
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







	function add_store_page($raw_page_data) {

		$store=new Store($this->data['Site Store Key']);

		$page_code=$this->get_unique_store_page_code($store);

		$page_data['Page Store Key']=$this->data['Site Store Key'];
		$page_data['Page Parent Key']=$this->data['Site Store Key'];
		$page_data['Page Site Key']=$this->id;
		$page_data['Page Header Key']=$this->data['Site Default Header Key'];
		$page_data['Page Footer Key']=$this->data['Site Default Footer Key'];
		$page_data['Page Type']='Store';
		$page_data['Page Store Slogan']='';
		$page_data['Page Store Description']='';
		$page_data['Page Short Title']=$store->data['Store Code'];
		$page_data['Page Store Section']='Information';

		$page_data['Number See Also Links']=0;
		$page_data['Page Code']=$page_code;
		$page_data['Page URL']=$this->data['Site URL'].'/'.strtolower($page_code);
		$page_data['editor']=$this->editor;
		$page_data['Page Locale']=$this->data['Site Locale'];


		foreach ($raw_page_data as $key=>$value) {
			$page_data[$key]=$value;
		}


		$page_section=new PageStoreSection('code',$page_data['Page Store Section']);
		$page_data['Page Store Section Key']=$page_section->id;


		$page=new Page('find',$page_data,'create');

		$this->new_page=$page->new;
		$this->new_page_key=$page->id;
		$this->msg=$page->msg;
		$this->error=$page->error;

		$this->update_product_totals();
		$this->update_page_totals();


	}
	function add_department_page($department_key,$raw_data) {

		$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Store Section`='Department Catalogue' and `Page Parent Key`=%d  and `Page Site Key`=%d ",
			$department_key,
			$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			return $row['Page Key'];
		}



		$department=new Department($department_key);

		if ($department->data['Product Department Store Key']!=$this->data['Site Store Key']) {
			$this->error=true;
			$this->msg='department store and site store dont match';
			return;
		}

		$store=new Store($department->data['Product Department Store Key']);

		$index_page=$this->get_page_object('index');


		$page_code=$this->get_unique_department_page_code($department);



		$page_data=array(
			'Page Code'=>$page_code,
			'Page URL'=>$this->data['Site URL'].'/'.strtolower($page_code),
			'Page Site Key'=>$this->id,
			'Page Type'=>'Store',
			'Page Store Key'=>$store->id,
			'Page Parent Key'=>$department->id,
			'Page Parent Code'=>$department->data['Product Department Code'],
			'Page Store Section Type'=>'Department',
			'Page Store Section'=>'Department Catalogue',
			'Page Store Last Update Date'=>gmdate('Y-m-d H:i:s'),
			'Page Store Last Structural Change Date'=>gmdate('Y-m-d H:i:s'),
			'Page Locale'=>$this->data['Site Locale'],
			'Page Source Template'=>'',
			'Page Description'=>'',
			'Page Title'=>$department->data['Product Department Name'].', '.$this->data['Site Name'],
			'Page Short Title'=>$department->data['Product Department Name'],
			'Page Store Title'=>$department->data['Product Department Name'].', '.$this->data['Site Name'],


			'Page Header Key'=>$this->data['Site Default Header Key'],
			'Page Footer Key'=>$this->data['Site Default Footer Key'],
			'Page Store Creation Date'=>gmdate('Y-m-d H:i:s'),
			'Number See Also Links'=>0,
			'editor'=>$this->editor,

		);

		foreach ($raw_data as $key=>$value) {
			if (in_array($key,array('Page Code','Page URL','Page Site Key','Page Type','Page Store Key','Page Parent Key','Page Parent Code','Page Store Section Type','Page Store Section','Page Store Last Update Date','Page Store Last Structural Change Date'))) {
				continue;
			}
			$page_data[$key]=$value;
		}




		$page_section=new PageStoreSection('code',$page_data['Page Store Section']);
		$page_data['Page Store Section Key']=$page_section->id;



		$page=new Page('find',$page_data,'create');
		if ($page->new) {
			$page->update_see_also();
		}

		$this->new_page=$page->new;
		$this->new_page_key=$page->id;
		$this->msg=$page->msg;
		$this->error=$page->error;

		$this->update_product_totals();
		$this->update_page_totals();

		return $page->id;

	}


	function add_family_page($family_key,$raw_data) {

		$this->new_page=false;


		$family=new Family($family_key);
		if ($family->data['Product Family Store Key']!=$this->data['Site Store Key']) {
			$this->error=true;
			$this->msg='family store and site store dont match';
			return;
		}


		$store=new Store($family->data['Product Family Store Key']);



		$page_code=$this->get_unique_family_page_code($family);

		$page_data=array(
			'Page Code'=>$page_code,
			'Page URL'=>$this->data['Site URL'].'/'.strtolower($page_code),
			'Page Site Key'=>$this->id,
			'Page Type'=>'Store',
			'Page Store Key'=>$store->id,
			'Page Parent Key'=>$family->id,
			'Page Parent Code'=>$family->data['Product Family Code'],
			'Page Store Section Type'=>'Family',
			'Page Store Section'=>'Family Catalogue',
			'Page Store Last Update Date'=>gmdate('Y-m-d H:i:s'),
			'Page Store Last Structural Change Date'=>gmdate('Y-m-d H:i:s'),

			'Page Store Content Display Type'=>'Source',
			'Page Store Content Template Filename'=>'',
			'Page Description'=>'',
			'Page Title'=>$family->data['Product Family Code'].', '.$family->data['Product Family Name'].', '.$this->data['Site Name'],
			'Page Short Title'=>$family->data['Product Family Name'],
			'Page Store Title'=>$family->data['Product Family Code'].', '.$family->data['Product Family Name'].', '.$this->data['Site Name'],
			'Page Locale'=>$this->data['Site Locale'],

			'Page Header Key'=>$this->data['Site Default Header Key'],
			'Page Footer Key'=>$this->data['Site Default Footer Key'],
			'Page Store Creation Date'=>gmdate('Y-m-d H:i:s'),
			'Number See Also Links'=>$this->data['Site Default Number See Also Links'],
			'editor'=>$this->editor
		);

		foreach ($raw_data as $key=>$value) {
			if (in_array($key,array('Page Code','Page URL','Page Site Key','Page Type','Page Store Key','Page Parent Key','Page Parent Code','Page Store Section Type','Page Store Section','Page Store Last Update Date','Page Store Last Structural Change Date'
					))) {
				continue;
			}

			$page_data[$key]=$value;

		}



		$page_section=new PageStoreSection('code',$page_data['Page Store Section']);
		$page_data['Page Store Section Key']=$page_section->id;


		$page=new Page('find',$page_data,'create');

		if ($page->new) {
			include_once 'class.Department.php';
			$department=new Department($family->data['Product Family Main Department Key']);
			if ($department->id) {
				$parent_pages_keys=$department->get_pages_keys();
				foreach ($parent_pages_keys as $parent_page_key) {
					$page->add_found_in_link($parent_page_key);
					break;
				}
			}
			$page->update_see_also();
		}


		$page->update_product_totals();

		$this->update_product_totals();
		$this->update_page_totals();
		$this->new_page=$page->new;
		$this->new_page_key=$page->id;
		$this->msg=$page->msg;
		$this->error=$page->error;

		return $page->id;

	}

	function add_product_page($product_pid,$raw_data) {


		$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Store Section`='Product Description' and `Page Parent Key`=%d and `Page Site Key`=%d",
			$product_pid,
			$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			return $row['Page Key'];
		}

		$product=new Product('pid',$product_pid);
		if ($product->data['Product Store Key']!=$this->data['Site Store Key']) {
			$this->error=true;
			$this->msg='product store and site store dont match';
			return;
		}


		$store=new Store($product->data['Product Store Key']);



		$page_code=$this->get_unique_product_page_code($product);

		$page_data=array(
			'Page Code'=>$page_code,
			'Page URL'=>$this->data['Site URL'].'/'.strtolower($page_code),
			'Page Site Key'=>$this->id,
			'Page Type'=>'Store',
			'Page Store Key'=>$store->id,
			'Page Parent Key'=>$product->pid,
			'Page Parent Code'=>$product->data['Product Family Code'],
			'Page Store Section Type'=>'Product',
			'Page Store Section'=>'Product Description',
			'Page Store Last Update Date'=>gmdate('Y-m-d H:i:s'),
			'Page Store Last Structural Change Date'=>gmdate('Y-m-d H:i:s'),

			'Page Store Content Display Type'=>'Source',
			'Page Store Content Template Filename'=>'',
			'Page Description'=>'',
			'Page Title'=>$product->data['Product Code'].', '.$product->data['Product Name'].', '.$this->data['Site Name'],
			'Page Short Title'=>$product->data['Product Name'],
			'Page Store Title'=>$product->data['Product Code'].', '.$product->data['Product Name'].', '.$this->data['Site Name'],
			'Page Locale'=>$this->data['Site Locale'],

			'Page Header Key'=>$this->data['Site Default Header Key'],
			'Page Footer Key'=>$this->data['Site Default Footer Key'],
			'Page Store Creation Date'=>gmdate('Y-m-d H:i:s'),
			'Number See Also Links'=>$this->data['Site Default Number See Also Links'],
			'Page State'=>'Online',
			'editor'=>$this->editor
		);

		foreach ($raw_data as $key=>$value) {
			if (in_array($key,array('Page Code','Page URL','Page Site Key','Page Type','Page Store Key','Page Parent Key','Page Parent Code','Page Store Section Type','Page Store Section','Page Store Last Update Date','Page Store Last Structural Change Date'
					))) {
				continue;
			}

			$page_data[$key]=$value;

		}



		$page_section=new PageStoreSection('code',$page_data['Page Store Section']);
		$page_data['Page Store Section Key']=$page_section->id;


		$page=new Page('find',$page_data,'create');
		if ($page->new) {

			include_once 'class.Family.php';
			$family=new Family($product->data['Product Family Key']);
			if ($family->id) {
				$parent_pages_keys=$family->get_pages_keys();
				foreach ($parent_pages_keys as $parent_page_key) {
					$page->add_found_in_link($parent_page_key);


					break;
				}
			}

			//$page->update_see_also();
		}
		$page->update_product_totals();

		$this->update_product_totals();
		$this->update_page_totals();
		$this->new_page=$page->new;
		$this->new_page_key=$page->id;
		$this->msg=$page->msg;
		$this->error=$page->error;

		return $page->id;
	}

	function base_data() {


		$data=array();
		$result = mysql_query("SHOW COLUMNS FROM `".$this->table_name." Dimension`");
		if (!$result) {
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
		if (mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_assoc($result)) {
				if (!in_array($row['Field'],$this->ignore_fields)) {
					$data[$row['Field']]=$row['Default'];
					if (preg_match('/ Data$/',$row['Field'])) {
						$data[$row['Field']]='a:0:{}';
					}

				}
			}
		}

		return $data;
	}

	function get_page_object($tipo,$key=false) {
		$page=false;
		switch ($tipo) {
		case 'index':
			$page=new Page('id',$this->data['Site Index Page Key']);
			break;
		case 'department':
			$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Store Section`='Department Catalogue' and `Page Parent Key`=%d ",
				$key

			);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$page=new Page('id',$row['Page Key']);
			}
			break;
		case 'family':
			$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Store Section`='Family Catalogue' and `Page Parent Key`=%d ",
				$key

			);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$page=new Page('id',$row['Page Key']);
			}
			break;

		}
		return $page;
	}

	function get_page_section_object($code) {
		$page_section=new PageStoreSection('code',$code,$this->id);
		return $page_section;
	}

	function get_welcome_template() {
		return $this->data['Site Welcome Source'];
	}

	function get_page_key_from_code($code) {
		$page_key=0;
		$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Site Key`=%d and `Page Code`=%s ",$this->id,prepare_mysql($code));
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$page_key=$row['Page Key'];
		}
		//print $sql;
		return $page_key;
	}

	function get_page_key_from_url($url) {

		//$url=preg_replace('http:\/\/', '', $url);
		$page_key=0;
		//        print "url: ".$url;
		$sql=sprintf("select PS.`Page Key` from `Page Store Dimension` PS left join `Page Dimension` P on (PS.`Page Key`=P.`Page Key`) where `Page Site Key`=%d and `Page URL`=%s ",
			$this->id,prepare_mysql($url));
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$page_key=$row['Page Key'];
		}
		return $page_key;
	}



	function get_unique_store_page_code($store) {

		if (!$this->is_page_store_code($store->data['Store Code']))
			return $store->data['Store Code'];

		for ($i = 2; $i <= 200; $i++) {

			if ($i<=100) {
				$suffix=$i;
			} else {
				$suffix=uniqid('', true);
			}

			if (!$this->is_page_store_code($store->data['Store Code'].$suffix))
				return $store->data['Store Code'].$suffix;
		}

		return $suffix;
	}


	function get_unique_family_page_code($family) {

		if (!$this->is_page_store_code($family->data['Product Family Code']))
			return $family->data['Product Family Code'];

		for ($i = 2; $i <= 200; $i++) {

			if ($i<=100) {
				$suffix=$i;
			} else {
				$suffix=uniqid('', true);
			}

			if (!$this->is_page_store_code($family->data['Product Family Code'].$suffix))
				return $family->data['Product Family Code'].$suffix;
		}

		return $suffix;
	}

	function get_unique_product_page_code($product) {

		if (!$this->is_page_store_code($product->data['Product Code']))
			return $product->data['Product Code'];
		if (!$this->is_page_store_code('p_'.$product->data['Product Code']))
			return 'p_'.$product->data['Product Code'];


		for ($i = 2; $i <= 200; $i++) {

			if ($i<=100) {
				$suffix=$i;
			} else {
				$suffix=uniqid('', true);
			}

			if (!$this->is_page_store_code($product->data['Product Code'].$suffix))
				return $product->data['Product Code'].$suffix;
		}

		return $suffix;
	}


	function get_unique_department_page_code($department) {

		if (!$this->is_page_store_code($department->data['Product Department Code']))
			return $department->data['Product Department Code'];

		for ($i = 2; $i <= 200; $i++) {

			if ($i<=100) {
				$suffix=$i;
			} else {
				$suffix=uniqid('', true);
			}

			if (!$this->is_page_store_code($department->data['Product Department Code'].$suffix))
				return $department->data['Product Department Code'].$suffix;
		}

		return $suffix;
	}


	function is_page_store_code($query) {

		$sql=sprintf("select PS.`Page Code`,PS.`Page Key` from `Page Store Dimension`  PS where `Page Site Key`=%d and `Page Code`=%s  "
			,$this->id
			,prepare_mysql($query)
		);

		$res=mysql_query($sql);

		if ($data=mysql_fetch_array($res)) {
			return true;


		} else {
			return false;
		}


	}

	function update_headers($new_header_key) {

		$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Header Type`='SiteDefault' and `Page Site Key`=%d",$this->id);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_array($res)) {
			$sql=sprintf("update  `Page Store Dimension` set `Page Header Key`=%d where `Page Key`=%d ",$new_header_key,$row['Page Key']);
			mysql_query($sql);

		}



	}

	function set_default_header($new_header_key) {


		$old_header_key=$this->data['Site Default Header Key'];
		$this->update_field_switcher('Site Default Header Key',$new_header_key,'no history');


		if ($this->updated) {

			$this->update_headers($new_header_key);

			$old_header=new PageHeader($old_header_key);
			$new_header=new PageHeader($new_header_key);
			$old_header->update_number_pages();
			$new_header->update_number_pages();

			$history_data=array(
				'History Abstract'=>_('Site default header changed').' ('.$old_header->data['Page Header Name'].' &rarr; '.$new_header->data['Page Header Name'].')',
				'History Details'=>'',

				'Indirect Object'=>'Page Header',
				'Indirect Object Key'=>$new_header_key
			);
			$this->add_history($history_data);

		}

	}

	function update_footers($new_footer_key) {
		$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Footer Type`='SiteDefault' and `Page Site Key`=%d",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$sql=sprintf("update  `Page Store Dimension` set `Page Footer Key`=%d where `Page Key`=%d ",$new_footer_key,$row['Page Key']);
			//  print "$sql<br>";
			mysql_query($sql);
		}

	}

	function set_default_footer($new_footer_key) {


		$old_footer_key=$this->data['Site Default Footer Key'];
		$this->update_field_switcher('Site Default Footer Key',$new_footer_key,'no history');


		if ($this->updated) {


			$this->update_footers($new_footer_key);
			$old_footer=new PageFooter($old_footer_key);
			$new_footer=new PageFooter($new_footer_key);
			$old_footer->update_number_pages();
			$new_footer->update_number_pages();

			$history_data=array(
				'History Abstract'=>_('Site default footer changed').' ('.$old_footer->data['Page Footer Name'].' &rarr; '.$new_footer->data['Page Footer Name'].')',
				'History Details'=>'',

				'Indirect Object'=>'Page Footer',
				'Indirect Object Key'=>$new_footer_key
			);
			$this->add_history($history_data);

		}

	}

	function display_search() {

		if ($this->data['Site Search Method']=='Custome') {
			return $this->data['Site Search HTML'];
		}else {

			$search=sprintf('<input id="inikoo_search" value="" type="text" style="width:200px"> <img onClick="search()" id="inikoo_search_go" src="art/search_white.png" alt="search" > ');
			return $search;
		}

	}




	function display_menu() {
		return $this->data['Site Menu HTML'];
	}


	function update_page_totals() {

		$number_pages=0;
		$number_pages_with_products=0;
		$number_pages_with_out_of_stock_products=0;

		$sql=sprintf("select count(*) as number_pages, SUM(IF(`Page Store Number Products`>0,1,0)) as number_pages_with_products ,  SUM(IF(`Page Store Number Out of Stock Products`>0,1,0)) as number_pages_with_out_of_stock_products  from `Page Store Dimension` where `Page Site Key`=%d and `Page State`='Online'",$this->id);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {
			$number_pages=$row['number_pages'];
			$number_pages_with_products=$row['number_pages_with_products'];
			$number_pages_with_out_of_stock_products=$row['number_pages_with_out_of_stock_products'];
		}

		$sql=sprintf("update `Site Dimension` set `Site Number Pages`=%d ,`Site Number Pages with Products`=%d,`Site Number Pages with Out of Stock Products`=%d where `Site Key`=%d",
			$number_pages,
			$number_pages_with_products,
			$number_pages_with_out_of_stock_products,
			$this->id
		);


		mysql_query($sql);
		$this->data['Site Number Pages']=$number_pages;
		$this->data['Site Number Pages with Products']=$number_pages_with_products;
		$this->data['Site Number Pages with Out of Stock Products']=$number_pages_with_out_of_stock_products;

	}


	function update_product_totals() {

		$number_products=0;
		$number_out_of_stock_products=0;





		$sql=sprintf("select  PPD.`Product ID`,`Parent Type`,`Product Web State`  from `Page Product Dimension` PPD left join `Product Dimension` P on (PPD.`Product ID`=P.`Product ID`) where `Site Key`=%d and !(`Product Web State`='Offline' and `Parent Type`='List' and `State`='Offline')  group by PPD.`Product ID` ",
			$this->id);
		//print "$sql\n";
		// exit;

		$result=mysql_query($sql);
		while ($row=mysql_fetch_assoc($result)) {
			$number_products++;
			if ($row['Product Web State']=='Discontinued' or $row['Product Web State']=='Out of Stock')
				$number_out_of_stock_products++;
		}


		$sql=sprintf("update `Site Dimension` set `Site Number Products`=%d,`Site Number Out of Stock Products`=%d where `Site Key`=%d",
			$number_products,
			$number_out_of_stock_products,
			$this->id
		);
		mysql_query($sql);
		//print "$sql\n";
		$this->data['Site Number Products']=$number_products;
		$this->data['Site Number Out of Stock Products']=$number_out_of_stock_products;



	}




	function get_email_credentials() {
		$sql=sprintf("select * from `Email Credentials Dimension` E left join `Email Credentials Site Bridge` B on (E.`Email Credentials Key`=B.`Email Credentials Key`) where B.`Site Key`=%d", $this->id);

		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {
			$email_credentials=$row;
		}
		else {
			//$email_credentials=array('Email'=>'', 'Password'=>'', 'Outgoing_Server'=>'', 'Incoming_Server'=>'');
			$email_credentials=false;
		}

		return $email_credentials;
	}

	function get_email_credential_key() {
		$sql=sprintf("select E.`Email Credentials Key` from `Email Credentials Dimension` E left join `Email Credentials Site Bridge` B on (E.`Email Credentials Key`=B.`Email Credentials Key`) where B.`Site Key`=%d",
			$this->id);

		$result=mysql_query($sql);
		if ($row=mysql_fetch_assoc($result)) {
			$email_credentials_key=$row['Email Credentials Key'];
		}
		else {
			$email_credentials_key=false;
		}

		return $email_credentials_key;
	}

	function get_credential_type() {
		include_once 'class.EmailCredentials.php';
		$keys=$this->get_email_credential_key();
		$email_credential = new EmailCredentials($keys);
		if ($email_credential->id) {
			return $email_credential->data['Email Provider'];
		}
		else
			return false;
	}

	function associate_email_credentials($email_credentials_key) {

		if (!$email_credentials_key) {
			$this->error=true;
			return;
		}

		$current_email_credentials_key=$this->get_email_credential_key();
		if ($email_credentials_key==$current_email_credentials_key) {
			return;
		}




		$sql=sprintf("delete from `Email Credentials Site Bridge` where `Site Key`=%d w ",
			$this->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Email Credentials Scope Bridge` where `Scope`='Site Registration'");
		mysql_query($sql);

		include_once 'class.EmailCredentials.php';

		$old_email_credentials=new EmailCredentials($current_email_credentials_key);
		$old_email_credentials->delete();

		$sql=sprintf("insert into `Email Credentials Site Bridge` values (%d,%d)",$email_credentials_key, $this->id);
		mysql_query($sql);

		$sql=sprintf("insert into `Email Credentials Scope Bridge` values (%d, 'Site Registration')",$email_credentials_key);
		mysql_query($sql);




		$this->updated=true;
		$this->msg='Updated';
		$this->newvalue=$email_credentials_key;


	}

	function create_ftp_connection() {

		if ($this->data['Site FTP Server']=='') {
			$this->error=true;
			return false;
		}

		include_once 'class.FTP.php';

		$ftp_connection=new FTP($this->data['Site FTP Server'],$this->data['Site FTP User'],$this->data['Site FTP Password'],$this->data['Site FTP Protocol'],$this->data['Site FTP Port'],$this->data['Site FTP Passive']);
		return  $ftp_connection;

	}





	function get_redirections_htaccess($host,$path) {
		$htaccess='';
		$redirect_lines=array();
		$host_bis=strtolower(preg_replace('/^www\./','',$host));
		$ftp_sever=strtolower($this->data['Site FTP Server']);

		//print "\n$host $ftp_sever\n";

		if ($ftp_sever==strtolower($host) or $ftp_sever==$host_bis) {
			$sql=sprintf("select * from `Page Redirection Dimension` where `Source Host`=%s and `Source Path`=%s",
				prepare_mysql($host),
				prepare_mysql($path,false)
			);
			$result=mysql_query($sql);
			// print $sql;
			while ($row=mysql_fetch_assoc($result)) {
				$redirect_line='/'.($row['Source Path']?$row['Source Path'].'/':'').$row['Source File'].' http://'.$row['Page Target URL'];
				$redirect_lines[$redirect_line]=1;
			}
		}
		foreach ($redirect_lines as $redirect_line=>$tmp) {
			$htaccess.="Redirect 301 $redirect_line\n";

		}
		if ($path=='') {
			$_htaccess_redirections=$htaccess;

			$htaccess="Options +FollowSymLinks\nRedirect 301 /index.html http://".$this->data['Site URL']."/index.php\n$_htaccess_redirections\nRewriteEngine On\nRewriteCond %{SCRIPT_FILENAME} !-d\nRewriteCond %{SCRIPT_FILENAME} !-f\nRewriteRule ^.*$ ./process.php";

		}

		//exit("--> $htaccess\n");
		//print "$htaccess";
		return $htaccess;

	}





	function upload_redirections($host,$path) {


		$htaccess=$this->get_redirections_htaccess($host,$path);



		if ($htaccess!='') {
			$ftp_connection=$this->create_ftp_connection();
			if (!$ftp_connection) {
				$this->error=true;
				$this->msg=$site->msg;
				return;
			}


			if ($ftp_connection->error) {

				$this->error=true;
				$this->msg=$ftp_connection->msg;
				return;
			}else {

				//print $path."/.htaccess";
				$ftp_connection->upload_string($htaccess,$path."/.htaccess");
				$ftp_connection->end();
			}


		}




	}

	function get_home_page_key() {
		$page_key=0;
		$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Store Section`='Front Page Store' and `Page Site Key`=%d ",$this->id);
		$res=mysql_query($sql);
		//print $sql;
		if ($row=mysql_fetch_assoc($res)) {
			$page_key=$row['Page Key'];
		}
		return $page_key;
	}

	function get_search_page_key() {
		$page_key=0;
		$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Store Section`='Search' and `Page Site Key`=%d ",$this->id);
		$res=mysql_query($sql);
		//print $sql;
		if ($row=mysql_fetch_assoc($res)) {
			$page_key=$row['Page Key'];
		}
		return $page_key;
	}

	function get_registration_page_key() {
		$page_key=0;
		$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Store Section`='Registration' and `Page Site Key`=%d ",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$page_key=$row['Page Key'];
		}
		return $page_key;
	}
	function get_login_page_key() {
		$page_key=0;
		$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Store Section`='Login' and `Page Site Key`=%d ",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$page_key=$row['Page Key'];
		}
		return $page_key;
	}

	function get_reset_page_key() {
		$page_key=0;
		$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Store Section`='Reset' and `Page Site Key`=%d ",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$page_key=$row['Page Key'];
		}
		return $page_key;
	}

	function get_page_key_from_section($section) {
		$page_key=0;
		$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Store Section`=%s and `Page Site Key`=%d ",
			prepare_mysql($section),
			$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$page_key=$row['Page Key'];
		}
		return $page_key;
	}


	function get_profile_page_key() {
		$page_key=0;
		$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Store Section`='Client Section' and `Page Site Key`=%d ",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$page_key=$row['Page Key'];
		}
		return $page_key;
	}
	function get_welcome_page_key() {
		$page_key=0;
		$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Store Section`='Welcome' and `Page Site Key`=%d ",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$page_key=$row['Page Key'];
		}
		return $page_key;
	}
	function get_not_found_page_key() {
		$page_key=0;
		$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Store Section`='Not Found' and `Page Site Key`=%d ",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$page_key=$row['Page Key'];
		}
		return $page_key;
	}

	function get_login_help_page_key() {
		$page_key=0;
		$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Store Section`='Login Help' and `Page Site Key`=%d ",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$page_key=$row['Page Key'];
		}
		return $page_key;
	}

	function update_up_today_requests() {
		$this->update_requests('Today');
		$this->update_requests('Week To Day');
		$this->update_requests('Month To Day');
		$this->update_requests('Year To Day');
	}

	function update_last_period_requests() {

		$this->update_requests('Yesterday');
		$this->update_requests('Last Week');
		$this->update_requests('Last Month');
	}


	function update_interval_requests() {
		$this->update_requests('Total');
		$this->update_requests('3 Year');
		$this->update_requests('1 Year');
		$this->update_requests('6 Month');
		$this->update_requests('1 Quarter');
		$this->update_requests('1 Month');
		$this->update_requests('10 Day');
		$this->update_requests('1 Week');
		$this->update_requests('1 Day');
		$this->update_requests('1 Hour');
	}

	function update_email_reminders() {

		$sql=sprintf("select count(distinct `Customer Key`) num_customers ,count(Distinct `Trigger Scope Key`) as num_products   from  `Email Site Reminder Dimension`  ER  where  `Site Key`=%d  and `Trigger Scope`='Back in Stock'",
			$this->id


		);
		//print $sql;

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->data['Site Number Back in Stock Reminder Customers']=$row['num_customers'];
			$this->data['Site Number Back in Stock Reminder Products']=$row['num_products'];
		}else {
			$this->data['Site Number Back in Stock Reminder Customers']=0;
			$this->data['Site Number Back in Stock Reminder Products']=0;
		}


		$sql=sprintf("select `Email Site Reminder State`,count(distinct `Email Site Reminder Key`) num_email_reminder  from  `Email Site Reminder Dimension`  ER  where  `Site Key`=%d  and `Trigger Scope`='Back in Stock' group by `Email Site Reminder State`",
			$this->id


		);
		//print $sql;

		$this->data['Site Number Back in Stock Reminder Waiting']=0;
		$this->data['Site Number Back in Stock Reminder Ready']=0;
		$this->data['Site Number Back in Stock Reminder Sent']=0;
		$this->data['Site Number Back in Stock Reminder Cancelled']=0;

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$this->data['Site Number Back in Stock Reminder '.$row['Email Site Reminder State']]=$row['num_email_reminder'];

		}


		$sql=sprintf('update `Site Dimension` set `Site Number Back in Stock Reminder Customers`=%d,`Site Number Back in Stock Reminder Products`=%d,`Site Number Back in Stock Reminder Waiting`=%d,`Site Number Back in Stock Reminder Ready`=%d,`Site Number Back in Stock Reminder Sent`=%d,`Site Number Back in Stock Reminder Cancelled`=%d where `Site Key`=%d',
			$this->data['Site Number Back in Stock Reminder Customers'],
			$this->data['Site Number Back in Stock Reminder Products'],
			$this->data['Site Number Back in Stock Reminder Waiting'],
			$this->data['Site Number Back in Stock Reminder Ready'],
			$this->data['Site Number Back in Stock Reminder Sent'],
			$this->data['Site Number Back in Stock Reminder Cancelled'],

			$this->id
		);
		mysql_query($sql);
		// print "$sql\n\n";

	}

	function update_requests($interval) {
		list($db_interval,$from_date,$from_date_1yb,$to_1yb)=calculate_interval_dates($interval);

		$sql=sprintf("select count(*) as num_requests ,count(distinct `User Session Key`) num_sessions ,count(Distinct `User Visitor Key`) as num_visitors   from  `User Request Dimension`  R  where  `Site Key`=%d  %s",
			$this->id,
			($from_date?' and `Date`>='.prepare_mysql($from_date):'')


		);
		//print $sql;

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->data['Site '.$db_interval.' Acc Requests']=$row['num_requests'];
			$this->data['Site '.$db_interval.' Acc Sessions']=$row['num_sessions'];
			$this->data['Site '.$db_interval.' Acc Visitors']=$row['num_visitors'];
		}else {
			$this->data['Site '.$db_interval.' Acc Requests']=0;
			$this->data['Site '.$db_interval.' Acc Sessions']=0;
			$this->data['Site '.$db_interval.' Acc Visitors']=0;

		}

		$sql=sprintf("select count(*) as num_requests ,count(distinct `User Session Key`) num_sessions ,count(Distinct `User Key`) as num_users   from  `User Request Dimension`  R   where  `Is User`='Yes' and `Site Key`=%d  %s",
			$this->id,
			($from_date?' and `Date`>='.prepare_mysql($from_date):'')


		);
		$res=mysql_query($sql);
		//print "$sql\n\n\n\n";
		if ($row=mysql_fetch_assoc($res)) {
			$this->data['Site '.$db_interval.' Acc Users Requests']=$row['num_requests'];
			$this->data['Site '.$db_interval.' Acc Users Sessions']=$row['num_sessions'];
			$this->data['Site '.$db_interval.' Acc Users']=$row['num_users'];
		}else {
			$this->data['Site '.$db_interval.' Acc Users Requests']=0;
			$this->data['Site '.$db_interval.' Acc Users Sessions']=0;
			$this->data['Site '.$db_interval.' Acc Users']=0;
		}

		$sql=sprintf('update `Site Dimension` set `Site '.$db_interval.' Acc Requests`=%d,
	`Site '.$db_interval.' Acc Sessions`=%d,
	`Site '.$db_interval.' Acc Visitors`=%d,
	`Site '.$db_interval.' Acc Users Requests`=%d,
	`Site '.$db_interval.' Acc Users Sessions`=%d,
	`Site '.$db_interval.' Acc Users`=%d
	where `Site Key`=%d',
			$this->data['Site '.$db_interval.' Acc Requests'],
			$this->data['Site '.$db_interval.' Acc Sessions'],
			$this->data['Site '.$db_interval.' Acc Visitors'],
			$this->data['Site '.$db_interval.' Acc Users Requests'],
			$this->data['Site '.$db_interval.' Acc Users Sessions'],
			$this->data['Site '.$db_interval.' Acc Users'],

			$this->id
		);
		mysql_query($sql);
		//print "$sql\n";
	}


	function update_customer_data() {
		$sql=sprintf("select count(*) as num    from `User Dimension` where `User Active`='Yes' and  `User Type`='Customer'  and `User Site Key`=%d",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->data['Site Total Users']=$row['num'];
		}
		$sql=sprintf("update `Site Dimension` set `Site Total Users`=%d where `Site Key`=%d",
			$this->data['Site Total Users'],
			$this->id
		);
		mysql_query($sql);
	}



	function add_favicon($image_key) {

		$sql=sprintf("select `Image Key`,`Is Principal` from `Image Bridge` where `Subject Type`='Site Favicon' and `Subject Key`=%d  and `Image Key`!=%d",$this->id,$image_key);


		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			$sql=sprintf("delete from `Image Bridge` where `Subject Type`='Site Favicon' and `Subject Key`=%d  and `Image Key`=%d  ",$this->id,$row['Image Key']);
			mysql_query($sql);



			$image=new Image($row['Image Key']);
			$image->delete();
		}





		$sql=sprintf("insert into `Image Bridge` values ('Site Favicon',%d,%d,%s,'')"
			,$this->id
			,$image_key
			,prepare_mysql('Yes')

		);

		mysql_query($sql);

		$image=new Image($image_key);

		if ($image->data['Image Height']!=0)
			$ratio=$image->data['Image Width']/$image->data['Image Height'];
		else
			$ratio=1;

		include_once 'common_units_functions.php';
		$this->new_value=array(
			'name'=>$image->data['Image Filename'],
			'small_url'=>'image.php?id='.$image->data['Image Key'].'&size=small',
			'thumbnail_url'=>'image.php?id='.$image->data['Image Key'].'&size=thumbnail',
			'filename'=>$image->data['Image Filename'],
			'ratio'=>$ratio,
			'caption'=>'',
			'is_principal'=>'Yes',
			'id'=>$image->data['Image Key'],
			'size'=>formatSizeUnits($image->data['Image File Size']
			)
		);


		$this->updated=true;
		$this->msg=_("image added");
	}


	function get_number_of_images() {
		$number_of_images=0;
		$sql=sprintf("select count(*) as num from `Image Bridge` where `Subject Type`='Site Favicon' and `Subject Key`=%d ",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$number_of_images=$row['num'];
		}
		return $number_of_images;
	}



	function get_images_slidesshow() {

		include_once 'common_units_functions.php';

		$sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='Site Favicon' and   `Subject Key`=%d",$this->id);
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
				'ratio'=>$ratio,'caption'=>$row['Image Caption'],
				'is_principal'=>$row['Is Principal'],'id'=>$row['Image Key'],
				'size'=>formatSizeUnits($row['Image File Size'])
			);
		}
		// print_r($images_slideshow);

		return $images_slideshow;
	}

	function get_favicon_url() {
		$sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='Site Favicon' and   `Subject Key`=%d and `Is Principal`='Yes'",$this->id);

		$res=mysql_query($sql);
		$favicon_url='art/nopic.png';
		while ($row=mysql_fetch_array($res)) {
			$favicon_url='public_image.php?id='.$row['Image Key'];
		}
		return $favicon_url;
	}

	function get_main_image_key() {

		$images_slideshow=array();

		$sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='Site Favicon' and   `Subject Key`=%d and `Is Principal`='Yes'",$this->id);

		$res=mysql_query($sql);

		while ($row=mysql_fetch_array($res)) {
			$images_slideshow=$row['Image Key'];
		}
		return $images_slideshow;
	}

	function get_current_active_logged_users($time=300) {
		$users=0;
		$sql=sprintf("select count(*) user from `User Log Dimension` where `Status`='Open' and `Site Key`=%d and `Last Visit Date`>%s ",
			$this->id,
			prepare_mysql(gmdate("Y-m-d H:i:s",strtotime("now -$time second")))
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$users=$row['user'];
		}
		return number($users);
	}

	function get_open_logged_users_sessions() {
		$sessions=0;
		$sql=sprintf("select count(*) sessions from `User Log Dimension` where `Status`='Open' and `Site Key`=%d",
			$this->id

		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$sessions=$row['sessions'];
		}
		return number($sessions);
	}


	function update_sitemap() {

		include_once 'class.Sitemap.php';
		$sitemap=new Sitemap($this->id);
		$sitemap->page('info');

		$sql=sprintf("select * from `Page Store Dimension` PS left join `Page Dimension` P on (P.`Page Key`=PS.`Page Key`) where `Page Site Key`=%d  and `Page Store Section`  not in  ('Product Description','Family Catalogue','Login','Registration')    ",
			$this->id
		);
		//print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$updated=$row['Page Store Last Update Date'];
			$sitemap->url('http://'.$row['Page URL'], $updated, 'monthly');
		}

		$sitemap->page('products');

		$sql=sprintf("select * from `Page Store Dimension` PS left join `Page Dimension` P on (P.`Page Key`=PS.`Page Key`) where `Page Site Key`=%d and (`Page Store Section`='Product Description' or `Page Store Section`='Family Catalogue') ",
			$this->id
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$updated=$row['Page Store Last Update Date'];
			//print_r($row);
			$sitemap->url('http://'.$row['Page URL'], $updated, 'weekly');
		}

		$sitemap->close();
		unset ($sitemap);

		$date=gmdate("Y-m-d H:i:s");
		$sql=sprintf("update `Site Dimension` set `Site Sitemap Last Update`=%s where `Site Key`=%d",
			prepare_mysql($date),
			$this->id);
		mysql_query($sql);
		//print $sql;
		$this->data['Site Sitemap Last Update']=$date;



	}

	function ping_sitemap() {

		$sitemap = $this->data['Site URL'] .'/'. 'sitemap_index.xml.php';
		$engines = array();
		$engines['Google'] = array('host'=>'www.google.com','path'=>'/webmasters/tools/ping?sitemap=' . urlencode($sitemap));
		$engines['Bing'] = array('host'=>'www.bing.com','path'=>'/webmaster/ping.aspx?siteMap=' . urlencode($sitemap));
		$engines['Ask'] = array('host'=>'submissions.ask.com','path'=>'/ping?sitemap=' . urlencode($sitemap));
		foreach ($engines as $engine_code => $data) {

			$host=$data['host'];
			$path=$data['path'];
			if ($fp = fsockopen($host, 80)) {
				$send = "HEAD $path HTTP/1.1\r\n";
				$send .= "HOST: $host\r\n";
				$send .= "CONNECTION: Close\r\n\r\n";
				fwrite($fp, $send);
				$http_response = fgets($fp, 128);
				fclose($fp);
				list($response, $code) = explode(' ', $http_response);
				$date=gmdate("Y-m-d H:i:s");
				if ($code -= 200) {
					$msg="OK";
				}else {
					$msg="{$host} ping was unsuccessful.<br />Code: {$code}<br />Response: {$response}";
				}
				$this->data['Site Sitemap Last Ping '.$engine_code]=$date;
				$this->data['Site Sitemap '.$engine_code.' Response']=$msg;

				$sql=sprintf("update `Site Dimension` set `Site Sitemap Last Ping $engine_code`=%s, `Site Sitemap $engine_code Response`=%s where `Site Key`=%d",
					prepare_mysql($date),
					prepare_mysql($msg),
					$this->id
				);
				mysql_query($sql);
			}
		}


	}
	function get_site_key() {
		return $this->id;
	}

	function update_content_words() {




		/*
$search = 'budda buddha';
preg_match_all('(\w+)', $search, $matches);
if (!empty($matches[0])){
   $sounds = array_map('soundex', $matches[0]);
   print_r($matches);
  }
$sql = 'SELECT word FROM words_list
    WHERE SOUNDEX(word) IN(\''.join('\',\'',$sounds).'\')';


    print $sql;
    exit;
*/

		//$ignore_words=array('and','the','but','for');

		$sql=sprintf("delete from `Site Content Word Dimension` where `Site Key`=%d",
			$this->id
		);
		mysql_query($sql);

		$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Site Key`=%d ",
			$this->id
		);
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

			$page=new Page($row['Page Key']);
			$content=$page->get_plain_content();

			$content=preg_replace('/(\?|\;|\:|\"|\)|\(|\&|\'|\*|\“|\”)/','',$content);
			$content=str_replace('?','',$content);


			$words=preg_split('/\s+/',$content);

			foreach ($words as $word) {
				$word=_trim($word);

				$word=preg_replace('/^(\.+|\,+)/','',$word);
				$word=preg_replace('/(\.+|\,+)$/','',$word);



				if (strlen($word)<3)continue;
				if ($word=='')continue;
				//if (in_array($word,$ignore_words)) {
				// continue;
				//}
				if (is_numeric($word)) {
					continue;
				}
				if (soundex($word)=='0000') {
					continue;
				}

				if (preg_match('/\d\+vat/',$word,$match))continue;

				$word=strtolower($word);
				$sql=sprintf("insert into `Site Content Word Dimension` (`Site Key`,`Word`,`Word Soundex`,`Multiplicity`) values (%d,%s,%s,1) on duplicate key update `Multiplicity`=`Multiplicity`+1",
					$this->id,
					prepare_mysql($word),
					prepare_mysql(soundex($word))

				);

				if (soundex($word)=='0000') {
					print "$sql\n";
				}

				mysql_query($sql);
			}


		}




	}


	function delete() {

		if ($this->data['Site Total Users']>0) {
			$this->error=_("Can't delete");
			return;
		}

		$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Site Key`=%d ",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$page=new Page($row['Page Key']);
			$page->delete();
		}

		$sql=sprintf("delete from `Site Dimension` where `Site Key`=%d ",$this->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Page Store Section Dimension` where `Site Key`=%d ",$this->id);
		mysql_query($sql);


		$store=new Store($this->data['Site Store Key']);
		$store->editor=$this->editor;
		$history_data=array(
			'History Abstract'=>_('Website deleted')." ".$this->data['Site Code'],
			'History Details'=>_trim(_('New website')." ".$this->data['Site Name']." "._('deleted')),
			'Action'=>'deleted'
		);
		$store->add_subject_history($history_data);

		$this->deleted=true;

	}


	function update_page_flags_numbers() {


		$sql=sprintf("select * from  `Site Flag Dimension` where `Site Key`=%d  ",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			$this->update_page_flag_number($row['Site Flag Key']);

		}
	}

	function update_page_flag_number($flag_key) {
		$num=0;
		$sql=sprintf("select count(*) as num  from  `Page Store Dimension` where `Site Flag Key`=%d ",$flag_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$num=$row['num'];

		}

		$sql=sprintf("update  `Site Flag Dimension`  set `Site Flag Number Pages`=%d where `Site Flag Key`=%d ",
			$num,
			$flag_key);
		mysql_query($sql);


	}

	function get_default_flag_key() {
		$flag_key=0;
		$sql=sprintf("select `Site Flag Key` from  `Site Flag Dimension` where `Site Flag Color`=%s and `Site Key`=%d",
			prepare_mysql($this->data['Site Default Flag Color']),
			$this->id
		);


		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$flag_key=$row['Site Flag Key'];
		}
		return $flag_key;

	}

	function update_flag($flag_key,$field,$value) {

		if (in_array($field,array('Site Flag Label','Site Flag Active'))) {


			$sql=sprintf("select * from  `Site Flag Dimension` where  `Site Flag Key`=%d and `Site Key`=%d",
				$flag_key,
				$this->id
			);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {

				$default_flag_key=$this->get_default_flag_key();
				if ($default_flag_key==$value and $field=='Site Flag Active' and $value=='No') {
					$this->error=true;
					$this->msg='can not disable default flag';
				}




				$sql=sprintf("update  `Site Flag Dimension`  set `%s`=%s where `Site Flag Key`=%d ",
					$field,
					prepare_mysql($value),
					$flag_key

				);
				mysql_query($sql);


				if ($field=='Site Flag Active' and $value=='No') {
					$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Site Key`=%d and `Site Flag Key`=%d",
						$this->id,
						$row['Site Flag Key']

					);
					//print $sql;
					$res2=mysql_query($sql);
					while ($row2=mysql_fetch_assoc($res2)) {
						$page=new Page($row2['Page Key']);
						$page->update_site_flag_key($default_flag_key);
					}

				}


				$this->updated=true;
				$this->new_value=$value;


			}else {
				$this->error=true;
				$this->msg='unknown flag';
			}



		}else {
			$this->error=true;
			$this->msg='unknown field';
		}

	}

	function get_payment_options_data() {

		$payment_options_data=array();

		$sql=sprintf("select * from `Payment Account Dimension` PA left join `Payment Service Provider Dimension` PSP on  (PA.`Payment Service Provider Key`=PSP.`Payment Service Provider Key`) left join `Payment Account Site Bridge`  B on (PA.`Payment Account Key`=B.`Payment Account Key`) where  `Show In Cart`='Yes' and `Site Key`=%d order by `Show Cart Order`",
			$this->id

		);
		//print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$payment_options_data[]=array(
				'payment_service_provider_code'=>$row['Payment Service Provider Code'],
				'payment_account_key'=>$row['Payment Account Key'],
				'payment_account'=>new Payment_Account($row['Payment Account Key'])
			);

		}
		return $payment_options_data;


	}


}
?>
