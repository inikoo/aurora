<?php
/*
 File: Page.php

 This file contains the Page Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';
include_once 'class.PageStoreSection.php';
include_once 'class.Site.php';

class Page extends DB_Table {

	var $new=false;
	var $logged=false;
	var $snapshots_taken=0;

	function Page($arg1=false,$arg2=false,$arg3=false) {
		$this->table_name='Page';
		$this->ignore_fields=array('Page Key');


		if (!$arg1 and !$arg2) {
			$this->error=true;
			$this->msg='No arguments';
		}
		if (is_numeric($arg1)) {
			$this->get_data('id',$arg1);
			return;
		}
		if (is_string($arg1) and !$arg2) {
			$this->get_data('url',$arg1);
			return;
		}


		if (is_array($arg2) and preg_match('/create|new/i',$arg1)) {
			$this->find($arg2,$arg3.' create');
			return;
		}
		if (  preg_match('/find/i',$arg1)) {
			$this->find($arg2,$arg3);
			return;
		}

		$this->get_data($arg1,$arg2,$arg3);

	}


	function get_data($tipo,$tag,$tag2=false) {

		if (preg_match('/url|address|www/i',$tipo)) {
			$sql=sprintf("select * from `Page Dimension` where  `Page URL`=%s",prepare_mysql($tag));
		}
		elseif ($tipo=='store_page_code') {
			$sql=sprintf("select * from `Page Store Dimension` PS left join `Page Dimension` P  on (P.`Page Key`=PS.`Page Key`) where `Page Code`=%s and `Page Store Key`=%d ",
				prepare_mysql($tag2),
				$tag
			);
		}
		elseif ($tipo=='site_code') {
			$sql=sprintf("select * from `Page Store Dimension` PS left join `Page Dimension` P  on (P.`Page Key`=PS.`Page Key`) where `Page Code`=%s and PS.`Page Site Key`=%d ",
				prepare_mysql($tag2),
				$tag
			);

		}
		else {
			$sql=sprintf("select * from `Page Dimension` where  `Page Key`=%d",$tag);
		}


		$result =mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->id=$this->data['Page Key'];
			$this->type=$this->data['Page Type'];

			if ($this->type=='Store') {
				$sql=sprintf("select * from `Page Store Dimension` where  `Page Key`=%d",$this->id);
				$result2 =mysql_query($sql);
				if ($row=mysql_fetch_array($result2, MYSQL_ASSOC)) {
					foreach ($row as $key=>$value) {
						$this->data[$key]=$value;
					}

					if ($this->data['Page Store Logo Data']!='')
						$this->data['Page Store Logo Data']=unserialize($this->data['Page Store Logo Data']);
					if ($this->data['Page Store Header Data']!='')
						$this->data['Page Store Header Data']=unserialize($this->data['Page Store Header Data']);
					if ($this->data['Page Store Content Data']!='')
						$this->data['Page Store Content Data']=unserialize($this->data['Page Store Content Data']);
					if ($this->data['Page Store Footer Data']!='')
						$this->data['Page Store Footer Data']=unserialize($this->data['Page Store Footer Data']);
					if ($this->data['Page Store Layout Data']!='')
						$this->data['Page Store Layout Data']=unserialize($this->data['Page Store Layout Data']);

				}

				//print "cacaca   ".$this->id."\n";
				if (array_key_exists('Page Site Key', $this->data)) {
					$this->site=new Site($this->data['Page Site Key']);


				}

			}
			elseif ($this->type=='Internal') {
				$sql=sprintf("select * from `Page Internal Dimension` where  `Page Key`=%d",$this->id);
				$result2 =mysql_query($sql);
				if ($row=mysql_fetch_array($result2, MYSQL_ASSOC)) {
					foreach ($row as $key=>$value) {
						$this->data[$key]=$value;
					}

				}

			}


		}

	}


	function find($raw_data,$options) {

		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;

			}
		}

		$create='';
		$update='';
		if (preg_match('/create/i',$options)) {
			$create='create';
		}
		if (preg_match('/update/i',$options)) {
			$update='update';
		}


		if ($raw_data['Page Type']=='Store') {

			$sql=sprintf("select P.`Page Key` from `Page Dimension` P left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`)   where `Page URL`=%s and `Page Site Key`=%d "
				,prepare_mysql($raw_data['Page URL'])
				,$raw_data['Page Site Key']
			);

			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {
				$this->found=true;
				$this->found_key=$row['Page Key'];
				$this->get_data('id',$this->found_key);
			}
		}

		if (!$this->found and $create) {
			$this->create($raw_data);

		}


	}


	function get_options() {

		if (array_key_exists('Page Options',$this->data)) {

			return unserialize( $this->data['Page Options'] );
		} else {
			return false;
		}

	}


	function create_internal($raw_data) {

		$data=$this->internal_base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key,$data))
				$data[$key]=_trim($value);

		}

		$keys='(';
		$values='values(';
		$data['Page Key']=$this->id;
		foreach ($data as $key=>$value) {
			$keys.="`$key`,";


			$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Page Internal Dimension` %s %s",$keys,$values);
		//print $sql;
		if (mysql_query($sql)) {
			$this->id=mysql_insert_id();
			$this->get_data('id',$this->id);


		} else {
			$this->error=true;
			$this->msg='Can not insert Page Internal Dimension';
		}


	}


	function create($raw_data) {


		$this->new=false;
		if (!isset($raw_data['Page Code']) or  $raw_data['Page Code']=='') {

			$raw_data['Page Code']=preg_replace('/\s/','',strtolower($raw_data['Page Section'].'_'.$raw_data['Page Short Title']));
		}

		if (!isset($raw_data['Page URL']) or  $raw_data['Page URL']=='') {

			$raw_data['Page URL']="info.php?page=".$raw_data['Page Code'];
		}




		$data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key,$data))
				$data[$key]=_trim($value);


		}



		$keys='(';
		$values='values(';
		foreach ($data as $key=>$value) {
			$keys.="`$key`,";
			if (preg_match('/Page Title|Page Description|Javascript|CSS|Page Keywords/i',$key))
				$values.="'".addslashes($value)."',";
			else
				$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Page Dimension` %s %s",$keys,$values);


		if (mysql_query($sql)) {
			$this->id=mysql_insert_id();
			$this->get_data('id',$this->id);

			$this->update_valid_url();
			$this->update_working_url();

			if ($this->data['Page Type']=='Internal') {
				$this->create_internal($raw_data);
			}
			elseif ($this->data['Page Type']=='Store') {
				$this->create_store_page($raw_data);
				
			}

			$sql=sprintf("insert into `Page State Timeline`  (`Page Key`,`Site Key`,`Store Key`,`Date`,`State`,`Operation`) values (%d,%d,%d,%s,%s,'Created') ",
				$this->id,
				$this->data['Page Site Key'],
				$this->data['Page Site Key'],
				prepare_mysql(gmdate('Y-m-d H:i:s')),
				prepare_mysql($this->data['Page State'])

			);
			mysql_query($sql);



		} else {
			$this->error=true;
			$this->msg='Can not insert Page Dimension';
			exit("$sql\n");
		}


	}

	function create_store_page($raw_data) {
		//print_r($raw_data);

		$data=$this->store_base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key,$data)) {
				$data[$key]=$value;
				if (is_string($value)) {
					$data[$key]=_trim($value);
				}
				elseif (is_array($value))
					$data[$key]=serialize($value);
			}
		}







		$data['Page Key']=$this->id;



		if (!is_array($data['Page Store Showcases'])) {
			$data['Page Store Showcases']=array();
		}

		if (array_key_exists('Presentation',$data['Page Store Showcases'])) {
			$data['Presentation Showcase']='Yes';
		}
		if (array_key_exists('Offers',$data['Page Store Showcases'])) {
			$data['Offers Showcase']='Yes';
		}
		if (array_key_exists('New',$data['Page Store Showcases'])) {
			$data['New Showcase']='Yes';
		}
		$data['Page Store Showcases']=serialize($data['Page Store Showcases']);

		if (!is_array($data['Page Store Product Layouts'])) {
			$data['Page Store Product Layouts']=array();
		}

		if (array_key_exists('List',$data['Page Store Product Layouts'])) {
			$data['List Layout']='Yes';
		}
		if (array_key_exists('Slideshow',$data['Page Store Product Layouts'])) {
			$data['Product Slideshow Layout']='Yes';
		}
		if (array_key_exists('Thumbnails',$data['Page Store Product Layouts'])) {
			$data['Product Thumbnails Layout']='Yes';
		}
		if (array_key_exists('Manual',$data['Page Store Product Layouts'])) {
			$data['Product Manual Layout']='Yes';
		}

		//print_r($data);





		$data['Page Store Product Layouts']=serialize($data['Page Store Product Layouts']);

		$keys='(';

		$values='values(';
		foreach ($data as $key=>$value) {
			$keys.="`$key`,";
			if ($key=='Page Source Template')
				$values.=prepare_mysql($value,false).",";

			else if (preg_match('/Subtitle|Title|Resume|Presentation|Slogan|Manual Layout Data|Page Store Showcases|Page Store Showcases/i',$key))
					$values.="'".addslashes($value)."',";
				else
					$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Page Store Dimension` %s %s",$keys,$values);
		//print "$sql\n";
		if (mysql_query($sql)) {

			$this->get_data('id',$this->id);
			$this->new=true;
			$sql=sprintf("select `Site Flag Key` from  `Site Flag Dimension` where `Site Flag Color`=%s and `Site Key`=%d",
				prepare_mysql($this->data['Site Flag']),
				$this->data['Page Site Key']
			);


			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				$this->update_site_flag_key($row['Site Flag Key']);
			}


			$content=$this->get_plain_content();


			$sql=sprintf("insert into `Page Store Search Dimension` values (%d,%d,%s,%s,%s,%s)",
				$this->id,
				$this->data['Page Site Key'],
				prepare_mysql($this->data['Page URL']),
				prepare_mysql($this->data['Page Store Title']),
				prepare_mysql($this->data['Page Store Resume']),
				prepare_mysql($content)
			);
			mysql_query($sql);

			$this->update_see_also();

		} else {
			$this->error=true;
			$this->msg='Can not insert Page Store Dimension';
			print "$sql\n";
			exit;
		}

	}


	function update_store_search() {

		if ($this->data['Page Type']=='Store') {

			//print "=========================\n";
			//print $this->get_xhtml_content();
			//print "-------------------------\n";
			//print $this->get_plain_content();
			$sql=sprintf("update `Page Store Search Dimension` set `Page Store Title`=%s,`Page Store Resume`=%s,`Page Store Content`=%s where `Page Key`=%d",
				prepare_mysql($this->data['Page Store Title']),
				prepare_mysql($this->data['Page Store Resume']),
				prepare_mysql($this->get_plain_content()),

				$this->id
			);
			mysql_query($sql);
		}

	}

	function update_working_url() {
		$old_value=$this->data['Page Working URL'];
		$this->data['Page Working URL']=$this->get_url_state($this->data['Page URL']);
		if ($old_value!=$this->data['Page Working URL']) {
			$sql=sprintf("update `Page Dimension` set `Page Working URL`=%s where `Page Key`=%d"
				,prepare_mysql($this->data['Page Working URL'])
				,$this->id
			);
			mysql_query($sql);
		}

	}

	function update_valid_url() {
		$old_value=$this->data['Page Valid URL'];
		$this->data['Page Valid URL']=($this->is_valid_url($this->data['Page URL'])?'Yes':'No');
		if ($old_value!=$this->data['Page Valid URL']) {
			$sql=sprintf("update `Page Dimension` set `Page Valid URL`=%s where `Page Key`=%d"
				,prepare_mysql($this->data['Page Valid URL'])
				,$this->id
			);
			mysql_query($sql);
		}

	}

	function get($key) {
		switch ($key) {
		case('link'):
			return $this->display();
			break;
		default:
			if (isset($this->data[$key]))
				return $this->data[$key];
		}

		if (preg_match('/ Acc /',$key)) {

			$amount='Page Store '.$key;

			return number($this->data[$amount]);
		}

		return false;
	}

	function display($tipo='link') {

		switch ($tipo) {
		case('html'):
		case('xhtml'):
		case('link'):
		default:
			return '<a href="'.$this->data['Page URL'].'">'.$this->data['Page Title'].'</a>';

		}


	}

	function get_url_state($url) {
		$state='Unknown';

		return $state;

	}

	function is_valid_url($url) {
		if (preg_match("/^(http(s?):\\/\\/|ftp:\\/\\/{1})((\w+\.)+)\w{2,}(\/?)$/i", $url))
			return true;
		else
			return false;

	}

	function internal_base_data() {
		$data=array();
		$result = mysql_query("SHOW COLUMNS FROM `Page Internal Dimension`");
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

	function store_base_data() {
		$data=array();
		$result = mysql_query("SHOW COLUMNS FROM `Page Store Dimension`");
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

	function update_thumbnail_key($image_key) {

		$old_value=$this->data['Page Snapshot Image Key'];
		if ($old_value!=$image_key) {
			$this->updated;
			$this->data['Page Snapshot Image Key']=$image_key;

			$sql=sprintf("update `Page Dimension` set `Page Snapshot Image Key`=%d ,`Page Snapshot Last Update`=NOW() where `Page Key`=%d "
				,$this->data['Page Snapshot Image Key']
				,$this->id
			);
			mysql_query($sql);

			$sql=sprintf("delete from  `Image Bridge` where `Subject Type`='Website' and `Subject Key`=%d "
				,$this->id

			);
			mysql_query($sql);

			if ($this->data['Page Snapshot Image Key']) {
				$sql=sprintf("insert into `Image Bridge` (`Subject Type`,`Subject Key`,`Image Key`) values('Website',%d,%d)"
					,$this->id
					,$image_key
				);
				print $sql;
				mysql_query($sql);
			}

		}

	}

	function update_show_layout($layout,$value) {
		switch ($layout) {
		case 'thumbnails':
			$field="Product Thumbnails Layout";
			break;
		case 'list':
		case 'lists':
			$field="List Layout";
			break;
		case 'slideshow':
			$field="Product Slideshow Layout";
			break;
		case 'manual':
			$field="Product Manual Layout";
			break;
		default:
			$this->error=true;
			$this->msg='Invalid field';
			return;
			break;
		}
		$value=($value=='true'?'Yes':'No');

		$sql=sprintf("update `Page Store Dimension` set `%s`=%s where `Page Key`=%d"
			,$field
			,prepare_mysql($value)
			,$this->id
		);
		mysql_query($sql);
		if (mysql_affected_rows()) {
			$this->updated=true;
			$this->new_value=$value;

		} else {
			$this->msg=_('Nothing to change');

		}



	}

	function get_header_template() {
		$template='';
		$sql=sprintf("select `Template` from `Page Header Dimension` where `Page Header Key`=%d",$this->data['Page Header Key']);
		$result = mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$template= $row['Template'];
		}
		return $template;
	}

	function get_footer_template() {


		if ($this->data['Page Footer Type']=='None') {
			return '';
		}

		$template='';
		$sql=sprintf("select `Template` from `Page Footer Dimension` where `Page Footer Key`=%d",$this->data['Page Footer Key']);

		$result = mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$template= $row['Template'];
		}
		return $template;
	}


	function get_css() {
		$css='';


		$sql=sprintf("select `CSS` from `Page Header Dimension` where `Page Header Key`=%d",$this->data['Page Header Key']);
		$result = mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$css.=$row['CSS'];
		}

		$css.=$this->data['Page Store CSS'];
		return  $css;
	}
	function get_javascript() {
		return $this->data['Page Store Javascript'];
	}
	function update_presentation_template_data($value,$options) {




		$myFile = "sites/templates/splinters/presentation/".$this->id.'.tpl';
		$fh = fopen($myFile, 'w');
		fwrite($fh,$value );
		fclose($fh);
		$this->update_field('Product Presentation Template Data',$value,$options);



	}


	function update_field_switcher($field,$value,$options='') {


		switch ($field) {
		case('Page Store See Also Type'):
			$this->update_field('Page Store See Also Type',$value,$options);
			if ($value=='Auto') {
				$this->update_see_also();
			}
			break;
		case('Site Flag Key'):
			$this->update_site_flag_key($value);
			break;
		case('code'):
		case('page_code'):
		case('Page Code'):
			$this->update_code($value);
			break;
		case('Page Header Key'):
			$this->update_header_key($value);
			break;
		case('Page Footer Key'):
			$this->update_footer_key($value);
			break;
		case('url'):
			return;
			break;
		case('page_title'):
		case('title'):
			$this->update_field('Page Title',$value,$options);
			break;

		case('footer_type'):
			$this->update_field('Page Footer Type',$value,$options);
			break;

		case('link_title'):
			$this->update_field('Page Short Title',$value,$options);
			break;
		case('Page Stealth Mode'):
			$this->update_field('Page Stealth Mode',$value,$options);

			$sql=sprintf("select `Page Store Key`  from `Page Store See Also Bridge` where `Page Store See Also Key`=%d ",$this->id);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {
				$_page=new Page ($row['Page Store Key']);
				$_page->update_see_also();
			}

			break;
		case('Page Store Resume'):
		case('description'):
		case('page_html_head_resume'):
			$this->update_field('Page Store Resume',$value,$options);
			$this->update_store_search();
			break;
		case('Page Keywords'):
		case('keywords'):
		case('page_keywords'):
			$this->update_field('Page Keywords',$value,$options);
			break;

		case('store_title'):
			$this->update_field('Page Store Title',$value,$options);
			$this->update_store_search();
			break;
		case('subtitle'):
			$this->update_field('Page Store Subtitle',$value,$options);
			break;
		case('slogan'):
			$this->update_field('Page Store Slogan',$value,$options);
			break;
		case('resume'):
			$this->update_field('Page Store Resume',$value,$options);
			break;
		case('display_type'):
			$this->update_field('Page Store Content Display Type',$value,$options);
			break;
		case('filename'):
			$this->update_field('Page Store Content Template Filename',$value,$options);
			break;

		case('Page State'):
			$this->update_state($value,$options);
			break;

		case('Page Store CSS'):
		case('Number See Also Links'):
		case('Number Found In Links'):
		case('Page Footer Height'):
		case('Page Header Height'):
		case('Page Content Height'):
		case('Page Head Include'):
		case('Page Body Include'):

			$this->update_field($field,$value,$options);
			break;

		case('Page Store Source'):
			$this->update_field($field,$value,$options);
			$this->update_store_search();
			break;
		case('presentation_template_data'):
			$this->update_presentation_template_data($value,$options);
			break;
		default:

			$base_data=$this->base_data();
			if (array_key_exists($field,$base_data)) {

				if ($value!=$this->data[$field]) {

					$this->update_field($field,$value,$options);
				}
			}else {
				$this->error=true;
				$this->msg="field not found ($field)";

			}

		}



	}


	function update_header_key($value,$options='') {
		if ($this->type!='Store') {
			return;
		}

		$old_value=$this->data['Page Header Key'];


		$site=new Site($this->data['Page Site Key']);

		$default_header_key=$site->data['Site Default Header Key'];
		if ($value==$default_header_key) {
			$header_type='SiteDefault';
		}else {
			$header_type='Set';

		}

		$sql=sprintf("update `Page Store Dimension`  set  `Page Header Key`=%d , `Page Header Type`=%s    where `Page Key`=%d",
			$value,prepare_mysql($header_type),$this->id);
		// print $sql;


		mysql_query($sql);
		$affected=mysql_affected_rows();
		if ($affected==-1) {
			$this->msg.=' '._('Record can not be updated')."\n";
			$this->error_updated=true;
			$this->error=true;

			return;
		}
		elseif ($affected==0) {
			$this->msg.=' '._('Same value as the old record');

		} else {

			$this->msg.=_('Header updated').", \n";
			$this->msg_updated.=_('Code updated').", \n";
			$this->updated=true;
			$this->new_value=$value;
			$this->data['Page Header Key']=$value;
			$this->data['Page Header Type']=$header_type;
			$save_history=true;
			if (preg_match('/no( |\_)history|nohistory/i',$options))
				$save_history=false;

			if (!$this->new and $save_history) {
				$history_data=array(
					'indirect_object'=>'Page Code'
					,'old_value'=>$old_value
					,'new_value'=>$value

				);



				$this->add_history($history_data);



			}





			//$this->update_field('Page URL',$url,'nohistory');

		}



	}

	function update_footer_key($value,$options='') {
		if ($this->type!='Store') {
			return;
		}

		$old_value=$this->data['Page Footer Key'];


		$site=new Site($this->data['Page Site Key']);

		$default_footer_key=$site->data['Site Default Footer Key'];
		if ($value==$default_footer_key) {
			$footer_type='SiteDefault';
		}else {
			$footer_type='Set';

		}

		$sql=sprintf("update `Page Store Dimension`  set  `Page Footer Key`=%d , `Page Footer Type`=%s    where `Page Key`=%d",
			$value,prepare_mysql($footer_type),$this->id);
		// print $sql;


		mysql_query($sql);
		$affected=mysql_affected_rows();
		if ($affected==-1) {
			$this->msg.=' '._('Record can not be updated')."\n";
			$this->error_updated=true;
			$this->error=true;

			return;
		}
		elseif ($affected==0) {
			$this->msg.=' '._('Same value as the old record');

		} else {

			$this->msg.=_('Footer updated').", \n";
			$this->msg_updated.=_('Code updated').", \n";
			$this->updated=true;
			$this->new_value=$value;
			$this->data['Page Footer Key']=$value;
			$this->data['Page Footer Type']=$footer_type;
			$save_history=true;
			if (preg_match('/no( |\_)history|nohistory/i',$options))
				$save_history=false;

			if (!$this->new and $save_history) {
				$history_data=array(
					'indirect_object'=>'Page Code'
					,'old_value'=>$old_value
					,'new_value'=>$value

				);



				$this->add_history($history_data);



			}





			//$this->update_field('Page URL',$url,'nohistory');

		}



	}


	function update_code($value,$options='') {


		if ($this->type!='Store') {
			return;
		}

		$value=_trim($value);
		if ($value=='') {
			$this->msg.=' '._('Invalid Code')."\n";
			$this->error_updated=true;
			$this->error=true;
			return;
		}

		if ($value==$this->data['Page Code']) {
			$this->msg.=' '._('Same value as the old record');
			return;
		}

		$old_value=$this->data['Page Code'];



		$sql=sprintf("select `Page Code`  from  `Page Store Dimension`  where `Page Store Key`=%d and `Page Code`=%s ",
			$this->data['Page Store Key'],
			prepare_mysql($value)

		);

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
			$this->msg.=' '._('Code already usen on this website')."\n";
			$this->error_updated=true;
			$this->error=true;

			return;


		}



		$site=new Site($this->data['Page Site Key']);
		$url=$site->data['Site URL'].'/'.strtolower($value);

		$sql=sprintf("update `Page Store Dimension`  set  `Page Code`=%s  where `Page Key`=%d",prepare_mysql($value),$this->id);
		// print $sql;


		mysql_query($sql);
		$affected=mysql_affected_rows();
		if ($affected==-1) {
			$this->msg.=' '._('Record can not be updated')."\n";
			$this->error_updated=true;
			$this->error=true;

			return;
		}
		elseif ($affected==0) {
			$this->msg.=' '._('Same value as the old record');

		} else {

			$this->msg.=_('Code updated').", \n";
			$this->msg_updated.=_('Code updated').", \n";
			$this->updated=true;
			$this->new_value=$value;
			$this->data['Page Code']=$value;

			$save_history=true;
			if (preg_match('/no( |\_)history|nohistory/i',$options))
				$save_history=false;

			if (!$this->new and $save_history) {
				$history_data=array(
					'indirect_object'=>'Page Code'
					,'old_value'=>$old_value
					,'new_value'=>$value

				);



				$this->add_history($history_data);


				$site=new Site($this->data['Page Site Key']);
				$url=$site->data['Site URL'].'/'.strtolower($value);

				$sql=sprintf("update `Page Dimension`  set  `Page URL`=%s  where `Page Key`=%d",prepare_mysql($url),$this->id);

				mysql_query($sql);

				$sql=sprintf("update `Page Redirection Dimension`  set  `Page Target URL`=%s  where `Page Target Key`=%d",prepare_mysql($url),$this->id);

				mysql_query($sql);
			}





			//$this->update_field('Page URL',$url,'nohistory');

		}

	}




	function get_data_for_smarty($data) {


		$page_section=new PageStoreSection('code',$this->data['Page Store Section'],$this->data['Page Site Key']);
		$data=$page_section->get_data_for_smarty($data);

		$header_style=$data['header_style'];
		if ($this->data['Page Store Header Data'] and array_key_exists('style',$this->data['Page Store Header Data']))
			foreach ($this->data['Page Store Header Data']['style'] as $key=>$value) {
				$header_style.="$key:$value;";
			}
		$data['header_style']=$header_style;

		$footer_style=$data['footer_style'];
		if ($this->data['Page Store Footer Data'] and array_key_exists('style',$this->data['Page Store Footer Data']))
			foreach ($this->data['Page Store Footer Data']['style'] as $key=>$value) {
				$footer_style.="$key:$value;";
			}
		$data['footer_style']=$footer_style;

		$content_style=$data['content_style'];
		$showcases=array();
		if ($this->data['Page Store Content Data'] ) {

			if (array_key_exists('style',$this->data['Page Store Content Data'])) {
				foreach ($this->data['Page Store Content Data']['style'] as $key=>$value) {
					$content_style.="$key:$value;";
				}
			}

			if (array_key_exists('Showcases',$this->data['Page Store Content Data'])) {
				foreach ($this->data['Page Store Content Data']['Showcases'] as $showcase_key=>$showcase) {
					$style='';
					if (array_key_exists('style',$showcase)) {
						foreach ($this->data['Page Store Content Data']['Showcases'][$showcase_key]['style'] as $key=>$value) {
							$style.="$key:$value;";
						}
					}
					$showcase['style']=$style;
					$showcases[]=$showcase;

				}
			}

		}

		$data['content_style']=$content_style;
		$data['showcases']=$showcases;
		$data['resume']=$this->data['Page Store Resume'];
		$data['slogan']=$this->data['Page Store Slogan'];
		$data['subtitle']=$this->data['Page Store Subtitle'];
		$data['title']=$this->data['Page Title'];
		return $data;
	}




	function get_found_in() {

		$found_in=array();
		$sql=sprintf("select `Page Store Found In Key` from  `Page Store Found In Bridge` where `Page Store Key`=%d",
			$this->id);

		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			$found_in_page=new Page($row['Page Store Found In Key']);
			if ($found_in_page->id) {

				$link='<a href="http://'.$found_in_page->data['Page URL'].'">'.$found_in_page->data['Page Short Title'].'</a>';

				$found_in[]=array(
					'link'=>$link,
					'found_in_label'=>$found_in_page->data['Page Short Title'],
					'found_in_url'=>$found_in_page->data['Page URL'],
					'found_in_key'=>$found_in_page->id,
					'found_in_code'=>$found_in_page->data['Page Code']
				);
			}

		}
		return $found_in;

	}



	function get_see_also() {

		$see_also=array();
		$sql=sprintf("select `Page Store See Also Key`,`Correlation Type`,`Correlation Value` from  `Page Store See Also Bridge` where `Page Store Key`=%d order by `Correlation Value` desc ",
			$this->id);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			$see_also_page=new Page($row['Page Store See Also Key']);
			if ($see_also_page->id) {


				switch ($row['Correlation Type']) {
				case 'Manual':
					$formated_correlation_type=_('Manual');
					$formated_correlation_value='';
					break;
				case 'Sales':
					$formated_correlation_type=_('Sales');
					$formated_correlation_value=percentage($row['Correlation Value'],1);
					break;
				case 'Semantic':
					$formated_correlation_type=_('Semantic');
					$formated_correlation_value=number($row['Correlation Value']);
					break;
				default:
					$formated_correlation_type=$row['Correlation Type'];
					break;
				}
				//if ($site_url)
				//$link='<a href="http://'.$site_url.'/'.$see_also_page->data['Page URL'].'">'.$see_also_page->data['Page Short Title'].'</a>';

				//else
				$link='<a href="http://'.$see_also_page->data['Page URL'].'">'.$see_also_page->data['Page Short Title'].'</a>';

				$see_also[]=array(
					'link'=>$link,
					'see_also_label'=>$see_also_page->data['Page Short Title'],
					'see_also_url'=>$see_also_page->data['Page URL'],
					'see_also_key'=>$see_also_page->id,
					'see_also_code'=>$see_also_page->data['Page Code'],
					'see_also_correlation_type'=>$row['Correlation Type'],
					'see_also_correlation_formated'=>$formated_correlation_type,
					'see_also_correlation_value'=>$row['Correlation Value'],
					'see_also_correlation_formated_value'=>$formated_correlation_value,
					'see_also_image_key'=>$see_also_page->data['Page Store Image Key']
				);
			}

		}
		return $see_also;

	}

	function delete_external_file($external_file_key) {

		$sql=sprintf("select count(*) as num from `Page Store External File Bridge` where `Page Store External File Key`=%d and `Page Key`!=%d",
			$external_file_key,
			$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if ($row['num']==0) {

				$sql=sprintf("delete from `Page Store External File Dimension` where `Page Store External File Key`=%d",$external_file_key);

			}
		}

	}

	function delete($create_deleted_page_record=true) {



		$this->deleted=false;



		$sql=sprintf("delete from `Page Dimension` where `Page Key`=%d",$this->id);
		// print "$sql\n";
		mysql_query($sql);
		$sql=sprintf("delete from `Page Store Dimension` where `Page Key`=%d",$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Page Redirection Dimension` where `Page Target Key`=%d",$this->id);
		mysql_query($sql);


		$sql=sprintf("select `Page Store External File Key` from `Page Store External File Bridge` where `Page Key`=%d",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$this->delete_external_file($row['Page Store External File Key']);
		}
		$sql=sprintf("delete from `Page Store External File Bridge` where `Page Key`=%d",$this->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Page Store Found In Bridge` where `Page Store Key`=%d",$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Page Store Found In Bridge` where `Page Store Found In Key`=%d",$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from  `Page Store See Also Bridge` where `Page Store Key`=%d",$this->id);
		mysql_query($sql);


		$sql=sprintf("insert into `Page State Timeline`  (`Page Key`,`Site Key`,`Store Key`,`Date`,`State`,`Operation`) values (%d,%d,%d,%s,'Offline','Deleted') ",
			$this->id,
			$this->data['Page Site Key'],
			$this->data['Page Site Key'],
			prepare_mysql(gmdate('Y-m-d H:i:s'))

		);
		mysql_query($sql);



		$sql=sprintf("delete `Page Product Dimension` where `Page Key`=%d",$this->id);
		mysql_query($sql);

		$images=array();
		$sql=sprintf("select `Image Key` from `Image Bridge` where `Subject Type`='Page' and `Subject Key`=%d",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$images[]=$row['Image Key'];
		}
		$sql=sprintf("delete from  `Image Bridge` where `Subject Type`='Page' and `Subject Key`=%d",$this->id);
		mysql_query($sql);

		foreach ($images as $image_key) {
			$image=new Image($image_key);
			$image->delete();
			if (!$image->deleted)
				$image->update_other_size_data();


		}

		$sql=sprintf("select `Page Store Key`  from  `Page Store See Also Bridge` where `Page Store See Also Key`=%d ",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$_page=new Page ($row['Page Store Key']);
			$_page->update_see_also();
		}


		$this->deleted=true;


		if (array_key_exists('Page Site Key',$this->data)) {
			$site=new Site($this->data['Page Site Key']);
			$site->update_page_totals();
		}





		if ($create_deleted_page_record) {
			include_once 'class.PageDeleted.php';
			$data=array(
				'Page Code'=>$this->data['Page Code'] ,
				'Page Key'=>$this->id,
				'Site Key'=>$this->data['Page Site Key'] ,
				'Store Key'=>$this->data['Page Store Key'] ,
				'Page Store Section'=>$this->data['Page Store Section'] ,
				'Page Parent Key'=>$this->data['Page Parent Key'] ,
				'Page Parent Code'=>$this->data['Page Parent Code'] ,
				'Page Title'=>$this->data['Page Store Title'] ,
				'Page Short Title'=>$this->data['Page Short Title'] ,
				'Page Description'=>$this->data['Page Description'] ,
				'Page URL'=>$this->data['Page URL'] ,
				'Page Valid To'=>'NOW()' ,

			);

			$deleted_page=new PageDeleted();
			$deleted_page->create($data);
			$this->new_value=$deleted_page->id;
		}
		$this->deleted=true;


	}


	function update_image_key() {


		$image_key='';

		if ($this->data['Page Type']!='Store' )
			return;



		switch ($this->data['Page Store Section']) {
		case 'Department Catalogue':
		include_once('class.Department.php');

			$department=new Department($this->data['Page Parent Key']);

			break;
		case 'Family Catalogue':
include_once('class.Family.php');
			$family=new Family($this->data['Page Parent Key']);

			if ($family->id and $family->data['Product Family Main Image Key']) {
				$image_key=$family->data['Product Family Main Image Key'];

			}

			break;
		default:

			break;
		}




		if ($image_key!=$this->data['Page Store Image Key']) {

			$sql=sprintf("update `Page Store Dimension` set `Page Store Image Key`=%s  where `Page Key`=%d ",
				prepare_mysql($image_key),
				$this->id);
			mysql_query($sql);

			$this->data['Page Store Image Key']=$image_key;
		}

	}

	function update_see_also() {




		if ($this->data['Page Type']!='Store' or $this->data['Page Store See Also Type']=='Manual')
			return;

		$max_links=$this->data['Number See Also Links'];


		$max_sales_links=ceil($max_links*.6);


		$min_sales_correlation_samples=5;
		$correlation_upper_limit=.5/($min_sales_correlation_samples);
		$see_also=array();
		$number_links=0;


		switch ($this->data['Page Store Section']) {
		case 'Department Catalogue':
			break;
		case 'Family Catalogue':

			$family=new Family($this->data['Page Parent Key']);

			$sql=sprintf("select * from `Product Family Sales Correlation` where `Family A Key`=%d order by `Correlation` desc limit 200",
				$this->data['Page Parent Key']);
			$res=mysql_query($sql);

			while ($row=mysql_fetch_assoc($res)) {
				//    print_r($row);

				if ($row['Samples']>$min_sales_correlation_samples and $row['Correlation']>=$correlation_upper_limit) {
					$family=new Family($row['Family B Key']);
					if ($family->data['Product Family Record Type']=='Normal' or $family->data['Product Family Record Type']=='Discontinuing') {

						$page_keys=$family->get_pages_keys();

						$see_also_page_key=array_pop($page_keys);
						if ($see_also_page_key) {

							$see_also_page=new Page($see_also_page_key);
							if ($see_also_page->id and $see_also_page->data['Page State']=='Online' and $see_also_page->data['Page Stealth Mode']=='No') {
								$see_also[$see_also_page_key]=array('type'=>'Sales','value'=>$row['Correlation']);
								$number_links=count($see_also);
								//print "$number_links>=$max_links\n";
								if ($number_links>=$max_sales_links  )
									break;
							}
						}
					}
				}


			}






			if ($number_links<$max_links) {
				$sql=sprintf("select * from `Product Family Semantic Correlation` where `Family A Key`=%d order by `Weight` desc limit 100",
					$this->data['Page Parent Key'],
					$max_links
				);
				$res=mysql_query($sql);
				// print "$sql\n";
				while ($row=mysql_fetch_assoc($res)) {
					if (!array_key_exists($row['Family B Key'], $see_also)) {
						$family=new Family($row['Family B Key']);
						if ($family->data['Product Family Record Type']=='Normal' or $family->data['Product Family Record Type']=='Discontinuing') {

							$page_keys=$family->get_pages_keys();
							$see_also_page_key=array_pop($page_keys);
							$see_also_page=new Page($see_also_page_key);
							if ($see_also_page->id and $see_also_page->data['Page State']=='Online' and $see_also_page->data['Page Stealth Mode']=='No') {
								$see_also[$see_also_page_key]=array('type'=>'Semantic','value'=>$row['Weight']);
								$number_links=count($see_also);
								if ($number_links>=$max_links)
									break;
							}
						}
					}
				}


			}
			// if ($number_links==0) {
			/// print_r($see_also);
			// exit("error\n");
			// }

			//print_r($see_also);


			break;
		default:

			break;
		}



		$sql=sprintf("delete from `Page Store See Also Bridge`where `Page Store Key`=%d ",
			$this->id);
		mysql_query($sql);
		$count=0;
		foreach ($see_also  as $see_also_page_key=>$see_also_data) {

			if ($count>=$max_links)
				break;

			$sql=sprintf("insert  into `Page Store See Also Bridge` values (%d,%d,%s,%f) ",
				$this->id,
				$see_also_page_key,
				prepare_mysql($see_also_data['type']),
				$see_also_data['value']
			);
			mysql_query($sql);
			//  print "$sql\n";
		}

	}


	function update_product_totals() {
		if ($this->data['Page Type']=='Store') {
			$number_products=0;
			$number_out_of_stock_products=0;
			$number_sold_out_products=0;
			$number_list_products=0;
			$number_button_products=0;

			$sql=sprintf("select PPD.`Product ID`,`Parent Type`,`Product Web State`  from `Page Product Dimension` PPD left join `Product Dimension` P on (PPD.`Product ID`=P.`Product ID`) where `Page Key`=%d",
				$this->id);
			//print $sql;
			//exit;

			$result=mysql_query($sql);
			while ($row=mysql_fetch_assoc($result)) {
				if (!($row['Product Web State']=='Offline' and $row['Parent Type']=='List')) {
					$number_products++;
					if ($row['Product Web State']=='Discontinued' or $row['Product Web State']=='Out of Stock')
						$number_out_of_stock_products++;
					if ($row['Product Web State']=='Discontinued')
						$number_sold_out_products++;


					if ( $row['Parent Type']=='List')
						$number_list_products++;
					if ( $row['Parent Type']=='Button')
						$number_button_products++;


				}
			}

			$sql=sprintf("update `Page Store Dimension` set `Page Store Number Products`=%d,`Page Store Number Out of Stock Products`=%d,
			`Page Store Number Sold Out Products`=%d,`Page Store Number List Products`=%d,`Page Store Number Button Products`=%d
			where `Page Key`=%d",
				$number_products,
				$number_out_of_stock_products,
				$number_sold_out_products,
				$number_list_products,
				$number_button_products,
				$this->id
			);
			mysql_query($sql);
			//print "$sql\n";
			$this->data['Page Store Number Products']=$number_products;
			$this->data['Page Store Number Out of Stock Products']=$number_out_of_stock_products;
			$this->data['Page Store Number Sold Out Products']=$number_sold_out_products;
			$this->data['Page Store Number List Products']=$number_list_products;
			$this->data['Page Store Number Button Products']=$number_button_products;



		}

	}



	function get_formated_store_section() {
		if ($this->data['Page Type']!='Store' )
			return;


		switch ($this->data['Page Store Section']) {
		case 'Front Page Store':
			$formated_store_section=_('Front Page Store');
			break;
		case 'Search':
			$formated_store_section=_('Search');
			break;
		case 'Product Description':
			$formated_store_section=_('Product Description');
			break;
		case 'Information':
			$formated_store_section=_('Information');
			break;
		case 'Category Catalogue':
			$formated_store_section=_('Category Catalogue');
			break;
		case 'Family Catalogue':
			$formated_store_section=_('Family Catalogue').' <a href="family.php?id='.$this->data['Page Parent Key'].'">'.$this->data['Page Parent Code'].'</a>';
			break;
		case 'Department Catalogue':
			$formated_store_section=_('Department Catalogue').' <a href="department.php?id='.$this->data['Page Parent Key'].'">'.$this->data['Page Parent Code'].'</a>';
			break;
		case 'Store Catalogue':
			$formated_store_section=_('Store Catalogue').' <a href="store.php?id='.$this->data['Page Parent Key'].'">'.$this->data['Page Parent Code'].'</a>';
			break;
		case 'Registration':
			$formated_store_section=_('Registration');
			break;
		case 'Client Section':
			$formated_store_section=_('Client Section');
			break;
		case 'Check Out':
			$formated_store_section=_('Check Out');
			break;
		default:
			$formated_store_section=$this->data['Page Store Section'];
			break;
		}

		return $formated_store_section;
	}

	function display_buttom($tag) {
		return $this->display_button($tag);
	}

	function display_button($tag) {
		$html='';
		include_once 'class.Product.php';
		$product=new Product('code_store',$tag,$this->data['Page Store Key']);

		if ($product->id) {

			if ($this->logged) {

				switch ($this->site->data['Site Checkout Method']) {
				case 'Mals':

					$html.=$this->display_button_emals_commerce($product);
					break;
				case 'AW':

					$html.=$this->display_button_aw_checkout($product);
					break;
				case 'Inikoo':
					$html.=$this->display_button_inikoo($product);

					break;
				default:
					break;
				}
			} else {
				$html=$this->display_button_logged_out($product);
			}
		}
		return $html;
	}

	function display_button_aw_checkout($product) {




		if ($product->data['Product Web State']=='Out of Stock') {


			$sql=sprintf("select `Email Site Reminder Key` from `Email Site Reminder Dimension` where `Trigger Scope`='Back in Stock' and `Trigger Scope Key`=%d and `User Key`=%d and `Email Site Reminder In Process`='Yes' ",
				$product->pid,
				$this->user->id

			);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$email_reminder='<br/><span id="send_reminder_wait_'.$product->pid.'"  style="display:none;color:#777"><img style="height:10px;position:relative;bottom:-1px"  src="art/loading.gif"> '._('Processing request').'</span><span id="send_reminder_container_'.$product->pid.'"  style="color:#777"><span id="send_reminder_info_'.$product->pid.'" >'._("We'll notify you via email").' <span style="cursor:pointer" id="cancel_send_reminder_'.$row['Email Site Reminder Key'].'"  onClick="cancel_send_reminder('.$row['Email Site Reminder Key'].','.$product->pid.')"  >('._('Cancel').')</span></span></span>';
			}else {
				$email_reminder='<br/><span id="send_reminder_wait_'.$product->pid.'"  style="display:none;color:#777"><img style="height:10px;position:relative;bottom:-1px"  src="art/loading.gif"> '._('Processing request').'</span><span id="send_reminder_container_'.$product->pid.'" style="color:#777" ><span id="send_reminder_'.$product->pid.'" style="cursor:pointer;" onClick="send_reminder('.$product->pid.')">'._('Notify me when back in stock').' <img style="position:relative;bottom:-2px" src="art/send_mail.png"/></span></span><span id="send_reminder_msg_'.$product->pid.'"></span></span>';

			}


			if ($product->data['Product Next Supplier Shipment']!='') {
				$next_shipment='. '._('Expected').': '.$product->get('Next Supplier Shipment');
			}
			else {
				$next_shipment='';
			}

			$message='<br/><span style="color:red;font-weight:800">'._('Out of Stock').'</span><span style="color:red">'.$next_shipment.$email_reminder.'</span>';
		}
		elseif ($product->data['Product Web State']=='Offline') {
			$message='<br/><span style="color:red;font-weight:800">'._('Sold Out').'</span>';
		}
		elseif ($product->data['Product Web State']=='Discontinued') {
			$message='<br/><span style="color:red;font-weight:800">'._('Sold Out').'</span>';
		}
		else {


$form_id='order_button_'.$product->pid;

			$form_id='order_button_'.$product->pid;

			$button='<img onmouseover="this.src=\'art/ordernow_hover_'.$this->site->data['Site Locale'].'.png\'" onmouseout="this.src=\'art/ordernow_'.$this->site->data['Site Locale'].'.png\'"    onClick="document.forms[\''.$form_id.'\'].submit();"  style="height:28px;cursor:pointer;" src="art/ordernow_'.$this->site->data['Site Locale'].'.png" alt="'._('Order Product').'">';
//  <input type='hidden' name='userid' value='%s'>
			$message=sprintf("<br/><div class='order_but' style='text-align:left'>
                             <form action='%s' method='post' id='%s' name='%s'  >
                             
                             
                     
                             
                            
                             <input type='hidden' name='product' value='%s %sx %s'>
                             <input type='hidden' name='return' value='%s'>
                             <input type='hidden' name='price' value='%s'>
                            <input type='hidden' name='customer_last_order' value='%s'>
                            <input type='hidden' name='customer_key' value='%s'>
                             
                             
                             
                             
                             <table border=0>
                             <tr>
                             <td>
                             <input style='height:20px;text-align:center'    type='text' size='2' class='qty' name='qty' value='1'>
                             </td>
                             <td>
                             %s
                             </td>
                             </table>
                             </form>


                             </div>",
                             
                             $this->site->get_checkout_data('url').'/shopping_cart.php',$form_id,$form_id,
				//$this->site->get_checkout_data('id'),
				$product->data['Product Code'],
				$product->data['Product Units Per Case'],
				$product->data['Product Name'],
				$this->data['Page URL'],
				number_format($product->data['Product Price'],2,'.',''),
				$this->customer->get('Customer Last Order Date'),
				$this->customer->id,
                             
                             
				
				
				
				
				
				
				$button


			);
		}

		$data=array(
			'Product Price'=>$product->data['Product Price'],


			'Product Units Per Case'=>$product->data['Product Units Per Case'],
			'Product Currency'=>$product->get('Product Currency'),
			'Product Unit Type'=>$product->data['Product Unit Type'],


			'locale'=>$this->site->data['Site Locale']);

		$price= '<span class="price">'.formated_price($data).'</span><br>';

		$data=array(
			'Product Price'=>$product->data['Product RRP'],
			'Product Units Per Case'=>$product->data['Product Units Per Case'],
			'Product Currency'=>$product->get('Product Currency'),
			'Product Unit Type'=>$product->data['Product Unit Type'],
			'Label'=>_('RRP').":",

			'locale'=>$this->site->data['Site Locale']);

		$rrp= '<span class="rrp">'.formated_price($data).'</span><br>';




		$form=sprintf('<div  class="ind_form">
                      <span class="code">%s</span><br/>
                      <span class="name">%sx %s</span><br>
                      %s
                      %s
                      %s
                      </div>',
			$product->data['Product Code'],
			$product->data['Product Units Per Case'],
			$product->data['Product Name'],
			$price,
			$rrp,
			$message
		);




		return $form;


	


	}
	function display_button_emals_commerce($product) {



		if ($product->data['Product Web State']=='Out of Stock') {


			$sql=sprintf("select `Email Site Reminder Key` from `Email Site Reminder Dimension` where `Trigger Scope`='Back in Stock' and `Trigger Scope Key`=%d and `User Key`=%d and `Email Site Reminder In Process`='Yes' ",
				$product->pid,
				$this->user->id

			);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$email_reminder='<br/><span id="send_reminder_wait_'.$product->pid.'"  style="display:none;color:#777"><img style="height:10px;position:relative;bottom:-1px"  src="art/loading.gif"> '._('Processing request').'</span><span id="send_reminder_container_'.$product->pid.'"  style="color:#777"><span id="send_reminder_info_'.$product->pid.'" >'._("We'll notify you via email").' <span style="cursor:pointer" id="cancel_send_reminder_'.$row['Email Site Reminder Key'].'"  onClick="cancel_send_reminder('.$row['Email Site Reminder Key'].','.$product->pid.')"  >('._('Cancel').')</span></span></span>';
			}else {
				$email_reminder='<br/>
					<span id="send_reminder_wait_'.$product->pid.'"  style="display:none;color:#777"><img style="height:10px;position:relative;bottom:-1px"  src="art/loading.gif"> '._('Processing request').'</span>
					<span id="send_reminder_container_'.$product->pid.'" style="color:#777" >
						<span id="send_reminder_'.$product->pid.'" style="cursor:pointer;" onClick="send_reminder('.$product->pid.')">'._('Notify me when back in stock').' <img style="position:relative;bottom:-2px" src="art/send_mail.png"/></span>
					</span>
					<span id="send_reminder_msg_'.$product->pid.'"></span>';

			}


			if ($product->data['Product Next Supplier Shipment']!='') {
				$next_shipment='. '._('Expected').': '.$product->get('Next Supplier Shipment');
			}
			else {
				$next_shipment='';
			}

			$message='<br/><span style="color:red;font-weight:800">'._('Out of Stock').'</span><span style="color:red">'.$next_shipment.$email_reminder.'</span>';
		}
		elseif ($product->data['Product Web State']=='Offline') {
			$message='<br/><span style="color:red;font-weight:800">'._('Sold Out').'</span>';
		}
		elseif ($product->data['Product Web State']=='Discontinued') {
			$message='<br/><span style="color:red;font-weight:800">'._('Sold Out').'</span>';
		}
		else {


			$form_id='order_button_'.$product->pid;

			$button='<img onmouseover="this.src=\'art/ordernow_hover_'.$this->site->data['Site Locale'].'.png\'" onmouseout="this.src=\'art/ordernow_'.$this->site->data['Site Locale'].'.png\'"    onClick="order_product_from_button(\''.$form_id.'\')"  style="height:28px;cursor:pointer;" src="art/ordernow_'.$this->site->data['Site Locale'].'.png" alt="'._('Order Product').'"> <span style="visibility:hidden" id="waiting_'.$form_id.'"><img src="art/loading.gif" style="height:22px;position:relative;bottom:3px"></span>';

			$message=sprintf("<br/><div class='order_but' style='text-align:left'>
                            
                             <input type='hidden' id='product_code_%s' value='%s'>
                             <input type='hidden' id='product_description_%s' value='%s %sx %s'>

                             <input type='hidden' id='return_%s' value='%s'>
                             <input type='hidden' id='price_%s' value='%s'>
                             <table border=0>
                             <tr>
                             <td>
                             <input style='height:20px;text-align:center' id='qty_%s'   type='text' size='2' class='qty' name='qty' value='1'>
                             </td>
                             <td>
                             %s
                             </td>
                             </table>
                           


                             </div>",
			//	$this->site->get_checkout_data('url').'/cf/add.cfm',$form_id,$form_id,
				$form_id,$product->data['Product Code'],
				$form_id,$product->data['Product Code'],$product->data['Product Units Per Case'],$product->data['Product Name'],
				$form_id,$this->data['Page URL'],
				$form_id,number_format($product->data['Product Price'],2,'.',''),
				$form_id,
				$button


			);
		}

		$data=array(
			'Product Price'=>$product->data['Product Price'],


			'Product Units Per Case'=>$product->data['Product Units Per Case'],
			'Product Currency'=>$product->get('Product Currency'),
			'Product Unit Type'=>$product->data['Product Unit Type'],


			'locale'=>$this->site->data['Site Locale']);

		$price= '<span class="price">'.formated_price($data).'</span><br>';

		$data=array(
			'Product Price'=>$product->data['Product RRP'],
			'Product Units Per Case'=>$product->data['Product Units Per Case'],
			'Product Currency'=>$product->get('Product Currency'),
			'Product Unit Type'=>$product->data['Product Unit Type'],
			'Label'=>_('RRP').":",

			'locale'=>$this->site->data['Site Locale']);

		$rrp= '<span class="rrp">'.formated_price($data).'</span><br>';




		$form=sprintf('<div  class="ind_form">
                      <span class="code">%s</span><br/>
                      <span class="name">%sx %s</span><br>
                      %s
                      %s
                      %s
                      </div>',
			$product->data['Product Code'],
			$product->data['Product Units Per Case'],
			$product->data['Product Name'],
			$price,
			$rrp,
			$message
		);




		return $form;


	}

	function display_button_inikoo($product) {

		$old_quantity=0;
		if (isset($this->order) and $this->order) {

			$sql=sprintf("select `Order Quantity` from `Order Transaction Fact` where `Order Key`=%d and `Product ID`=%d",
				$this->order->id,
				$product->pid);
			$result1=mysql_query($sql);
			if ($product1=mysql_fetch_array($result1))
				$old_quantity=$product1['Order Quantity'];


		}




		if ($old_quantity<=0) {
			$old_quantity='';
		}



		if ($product->data['Product Web State']=='Out of Stock') {
			$message='<br/><span style="color:red;font-weight:800">'._('Out of Stock').'</span>';
		}
		elseif ($product->data['Product Web State']=='Offline') {
			$message='<br/><span style="color:red;font-weight:800">'._('Sold Out').'</span>';
		}
		elseif ($product->data['Product Web State']=='Discontinued') {
			$message='<br/><span style="color:red;font-weight:800">'._('Sold Out').'</span>';
		}
		else {
			$message=sprintf("<div class='order_but' style='margin-top:5px;text-align:left'>

                        %s:
                             <input onKeyUp=\"button_changed(%d)\"  type='text' size='2' class='qty' id='but_qty%d' value='%s' ovalue='%s' >
                             <span style='display:none;' id='but_processing%d'> <img style='height:10px' src='art/loading.gif'></span>
                             <button onCLick=\"order_product_from_button(%d)\" id='but_button%d' style='visibility:hidden' >%s</button>

                             </div>"
				,
				_('Quantity to order'),
				$product->pid,
				$product->pid,
				$old_quantity,
				$old_quantity,
				$product->pid,
				$product->pid,
				$product->pid,

				_('Order Product')


			);
		}

		$data=array(
			'Product Price'=>$product->data['Product Price'],
			'Product Units Per Case'=>$product->data['Product Units Per Case'],
			'Product Currency'=>$product->get('Product Currency'),
			'Product Unit Type'=>$product->data['Product Unit Type'],
			'locale'=>$this->site->data['Site Locale']);
		$price= '<span class="price">'.formated_price($data).'</span><br>';
		$data=array(
			'Product Price'=>$product->data['Product RRP'],
			'Product Units Per Case'=>$product->data['Product Units Per Case'],
			'Product Currency'=>$product->get('Product Currency'),
			'Product Unit Type'=>$product->data['Product Unit Type'],
			'Label'=>_('RRP').":",
			'locale'=>$this->site->data['Site Locale']);
		$rrp= '<span class="rrp">'.formated_price($data).'</span><br>';
		$form=sprintf('<div  class="ind_form">
                      <span class="code">%s</span><br/>
                      <span class="name">%sx %s</span><br>
                      %s
                      %s
                      %s
                      </div>',
			$product->data['Product Code'],
			$product->data['Product Units Per Case'],
			$product->data['Product Name'],
			$price,
			$rrp,
			$message
		);




		return $form;
	}
	function display_button_logged_out($product) {

		if ($product->data['Product Web State']=='Out of Stock') {
			$message='<br/><span style="color:red;font-weight:800">'._('Out of Stock').'</span>';
		}
		elseif ($product->data['Product Web State']=='Offline') {
			$message='<br/><span style="color:red;font-weight:800">'._('Sold Out').'</span>';
		}
		elseif ($product->data['Product Web State']=='Discontinued') {
			$message='<br/><span style="color:red;font-weight:800">'._('Sold Out').'</span>';
		}
		else {
			$message=sprintf('<br/><span style="color:green;font-style:italic;">'._('In stock').'. '._('For prices, please').' <a style="color:green;" href="login.php?from='.$this->id.'" >'._('login').'</a> '._('or').' <a style="color:green;" href="registration.php">'._('register').'</a> </span>');
		}

		$form=sprintf('<div  class="ind_form">
                      <span class="code">%s</span><br/>
                      <span class="name">%sx %s</span>%s
                      </div>',
			$product->data['Product Code'],
			$product->data['Product Units Per Case'],
			$product->data['Product Name'],
			$message
		);


		return $form;
	}

	function display_list($list_code='default') {
		if (!$this->data['Page Type']=='Store' or !$this->data['Page Store Section']=='Family Catalogue' ) {
			return '';
		}

		$products=$this->get_products_from_list($list_code);
		$this->print_rrp=false;

		if (count($products)==0)
			return;

		if ($this->logged) {
			return $this->display_list_logged_in($products);
		} else {
			return $this->display_list_logged_out($products);
		}
	}



	function get_products_from_list($list_code) {

		$products=array();
		$sql=sprintf("select * from `Page Product List Dimension` where `Page Key`=%d and `Page Product List Code`=%s",
			$this->id,
			prepare_mysql($list_code)
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$family_key=$row['Page Product List Parent Key'];
			if ($row['Page Product List Type']=='FamilyList') {
				switch ($row['List Order']) {
				case 'Code':
					$order_by='`Product Code File As`';
					break;
				case 'Name':
					$order_by='`Product Name`';
					break;
				case 'Special Characteristic':
					$order_by='`Product Special Characteristic`';
					break;
				case 'Price':
					$order_by='`Product Price`';
					break;
				case 'RRP':
					$order_by='`Product RRP`';
					break;
				case 'Sales':
					$order_by='`Product 1 Year Acc Quantity Ordered` desc';
					break;
				case 'Date':
					$order_by='`Product Valid From`';
					break;
				default:
					$order_by='`Product Code File As`';
					break;
				}

				$limit=sprintf('limit %d',$row['List Max Items']);


				if ($row['Range']!='') {
					$range=preg_split('/-/',$row['Range']);

					if ($range[0]=='a' and $range[1]=='z' ) {
						$range_where='';
					}else if ($range[1]=='z') {
							$range_where=sprintf("and  $order_by>=%s  ", prepare_mysql($range[0]));

						}elseif ($range[0]=='a') {
						$range_where=sprintf("and  $order_by<=%s  ", prepare_mysql(++$range[1]));

					}else {
						$range_where=sprintf("and  $order_by>=%s  and $order_by<=%s", prepare_mysql($range[0]), prepare_mysql(++$range[1]));

					}

				} else {
					$range_where='';
				}
				$sql=sprintf("select `Product Next Supplier Shipment`,`Product Currency`,`Product Name`,`Product ID`,`Product Code`,`Product Price`,`Product RRP`,`Product Units Per Case`,`Product Unit Type`,`Product Web State`,`Product Special Characteristic` from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline'  %s order by %s %s",
					$family_key,
					$range_where,
					$order_by,
					$limit);
				//print $sql;
				$result=mysql_query($sql);
				while ($row2=mysql_fetch_array($result, MYSQL_ASSOC)) {

					if ($row2['Product Next Supplier Shipment']=='') {
						$row2['Next Supplier Shipment']='';
					}else {
						$row2['Next Supplier Shipment']=strftime("%a, %e %b %y",strtotime($row2['Product Next Supplier Shipment'].' +0:00'));
					}



					$products[$row2['Product ID']]=$row2;




				}

			}


			switch ($row['List Product Description']) {
			case 'Units Name':
				foreach ($products as $key=>$product) {
					$products[$key]['description']=sprintf("%dx %s",$product['Product Units Per Case'],$product['Product Name']);
					$products[$key]['long_description']=sprintf("%dx %s",$product['Product Units Per Case'],$product['Product Name']);
				}
				break;
			case 'Units Name RRP':




				foreach ($products as $key=>$product) {
					$rrp=money_locale($product['Product RRP'],$this->site->data['Site Locale'],$product['Product Currency']);
					$tmp=sprintf("%dx %s <span class='rrp' >(%s: %s)</span>",
						$product['Product Units Per Case'],
						$product['Product Name'],
						_('RRP'),
						$rrp
					);

					$products[$key]['description']=$tmp;
					$products[$key]['long_description']=$tmp;
				}
				break;
			case 'Units Special Characteristic':
				foreach ($products as $key=>$product) {
					$products[$key]['description']=sprintf("%dx %s",$product['Product Units Per Case'],$product['Product Special Characteristic']);
					$products[$key]['long_description']=sprintf("%dx %s",$product['Product Units Per Case'],$product['Product Name']);

				}
				break;
			case 'Units Special Characteristic RRP':




				foreach ($products as $key=>$product) {
					$rrp=money_locale($product['Product RRP'],$this->site->data['Site Locale'],$product['Product Currency']);

					$products[$key]['description']=sprintf("%dx %s <span class='rrp' >(%s: %s)</span>",
						$product['Product Units Per Case'],
						$product['Product Special Characteristic'],
						_('RRP'),
						$rrp
					);
					$products[$key]['long_description']=sprintf("%dx %s <span class='rrp' >(%s: %s)</span>",
						$product['Product Units Per Case'],
						$product['Product Name'],
						_('RRP'),
						$rrp
					);

				}
				break;

			default:
				foreach ($products as $key=>$product) {
					$products[$key]['description']=sprintf("%dx %s",$product['Product Units Per Case'],$product['Product Name']);
					$products[$key]['long_description']=sprintf("%dx %s",$product['Product Units Per Case'],$product['Product Name']);

				}
				break;
			}

		}





		return $products;
	}

	function get_list_header($products) {

		$html='';

		switch ($this->data['Page Store Section']) {
		case 'Family Catalogue':
			$family=new Family($this->data['Page Parent Key']);
			$html=sprintf('<tr class="list_info"><td colspan=5>%s %s</td></tr>',$family->data['Product Family Code'],$family->data['Product Family Name']);

			break;
		default:

			break;
		}

		$html.=sprintf('<tr class="list_info price"><td style="padding-top:0;padding-bottom:0;text-align:left" colspan="6">%s </td></tr></tr>',$this->get_list_price_header_auto($products));
		$html.=sprintf('<tr class="list_info rrp"><td style="padding-top:0;padding-bottom:0;" colspan="6">%s</td></tr></tr>',$this->get_list_rrp_header_auto($products));


		return $html;

	}

	function display_list_logged_out($products) {



		$show_unit=true;
		//if (isset($options['unit'])) {
		//    $show_unit=$options['unit'];
		// }
		$print_header=true;
		$print_rrp=false;
		$print_register=true;

		$number_records=count($products);
		$out_of_stock=_('OoS');
		$discontinued=_('Sold Out');
		$register=_('Please').' '.'<a href="login.php?from='.$this->id.'">'._('login').'</a> '._('or').' <a href="registration.php">'._('register').'</a>';

		$register='<span style="font-size:120%">'._('For prices, please').' <a  href="login.php?from='.$this->id.'" >'._('login').'</a> '._('or').' <a  href="registration.php">'._('register').'</a> </span>';



		$form=sprintf('<table class="product_list" style="position:relative;z-index:2;" >' );

		if ($print_header) {

			$rrp_label='';

			if ($print_rrp) {

				if ($number_records==1) {

				} elseif ($number_records>2) {

					$sql=sprintf("select min(`Product RRP`/`Product Units Per Case`) min, max(`Product RRP`/`Product Units Per Case`) as max ,avg(`Product RRP`/`Product Units Per Case`)  as avg from `Product Dimension` where `Product Family Key`=%d and `Product Web State` in ('For Sale','Out of Stock') ", $family->id);
					$res=mysql_query($sql);
					if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
						$rrp=$row['min'];


						$rrp= $this->get_formated_rrp(array(
								'Product RRP'=>$rrp,
								'Product Units Per Case'=>1,
								'Product Unit Type'=>''),array('prefix'=>false, 'show_unit'=>$show_unit));

						if ($row['rrp_avg']<=0) {
							$rrp_label='';
							$print_rrp=false;
						}
						elseif ($row['avg']==$row['min'])
							$rrp_label='<br/>'._('RRP').': '.$rrp;
						else
							$rrp_label='<br/>'._('RRP from').' '.$rrp;



					} else {
						return;
					}

				}

			}


			$form.='<tr class="list_info" ><td colspan="4"><p>'.$rrp_label.'</p></td><td>';
			if ($print_register and $number_records>10)
				$form.=sprintf('<tr class="last register"><td colspan="4">%s</td></tr>',$register);


		}
		$counter=0;

		foreach ($products as $product) {



			if ($print_rrp) {

				$rrp= $this->get_formated_rrp(array(
						'Product RRP'=>$product['Product RRP'],
						'Product Units Per Case'=>$product['Product Units Per Case'],
						'Product Unit Type'=>$product['Product Unit Type']), array('show_unit'=>$show_unit));

			} else {
				$rrp='';
			}
			if ($product['Product Web State']=='Out of Stock') {
				$class_state='out_of_stock';
				$state='('.$out_of_stock.')';

			}
			elseif ($product['Product Web State']=='Discontinued') {
				$class_state='discontinued';
				$state='('.$discontinued.')';

			}
			else {

				$class_state='';
				$state='';


			}


			if ($counter==0)
				$tr_class='class="top"';
			else
				$tr_class='';
			$form.=sprintf('<tr %s ><td class="code">%s</td><td class="description">%s   <span class="%s">%s</span></td><td class="rrp">%s</td></tr>',
				$tr_class,
				$product['Product Code'],
				$product['Product Units Per Case'].'x '.$product['Product Special Characteristic'],
				$class_state,
				$state,
				$rrp

			);


			$counter++;
		}

		if ($print_register)
			$form.=sprintf('<tr class="last register"><td colspan="4">%s</td></tr>',$register);
		$form.=sprintf('</table>');
		return $form;
	}

	function display_list_logged_in($products) {

		$print_rrp=true;
		$number_records=count($products);
		$out_of_stock=_('OoS');
		$discontinued=_('Sold Out');


		$form=sprintf('<table border=0  class="product_list form" style="position:relative;z-index:2;">' );
		$rrp_label='';
		$price_label='';

		//        $form.='<tr class="list_info" ><td colspan="4"><p>'.$price_label.$rrp_label.'</p></td></tr>';

		$form.=$this->get_list_header($products);

		switch ($this->site->data['Site Checkout Method']) {
		case 'Mals':

			$form.=$this->get_list_emals_commerce($products);
			break;
		case 'AW':

			$form.=$this->get_list_aw_checkout($products);
			break;
		case 'Inikoo':
			$form.=$this->get_list_inikoo($products);

			break;
		default:
			break;
		}


		$form.='</table>';
		return $form;
	}


	function get_list_inikoo($products) {
		$form='';
		$counter=0;


		if (isset($this->order) and $this->order) {
			$order_key=$this->order->id;
		}else {
			$order_key=0;
		}
		/*
		if ($this->user->data['User Type']=='Customer') {

			$sql=sprintf("select `Order Key` from `Order Dimension` where `Order Customer Key`=%d and `Order Current Dispatch State`='In Process by Customer' order by `Order Public ID` DESC", $this->user->get('User Parent Key'));
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result))


				$order_key=$row['Order Key'];
		}

*/
		foreach ($products as $product) {

			if ($this->print_rrp) {

				$rrp= $this->get_formated_rrp(array(
						'Product RRP'=>$product['Product RRP'],
						'Product Units Per Case'=>$product['Product Units Per Case'],
						'Product Unit Type'=>$product['Product Unit Type']), array('show_unit'=>$show_unit));

			} else {
				$rrp='';
			}




			$price= $this->get_formated_price(array(
					'Product Price'=>$product['Product Price'],
					'Product Units Per Case'=>1,
					'Product Unit Type'=>'',
					'Label'=>(''),
					'price per unit text'=>''
				));
			if ($counter==0)
				$tr_class='class="top"';
			else
				$tr_class='';

			$old_qty=0;
			if ($order_key) {
				$sql=sprintf("select `Order Quantity` from `Order Transaction Fact` where `Order Key`=%d and `Product ID`=%d",
					$order_key,
					$product['Product ID']);
				$result1=mysql_query($sql);
				if ($product1=mysql_fetch_array($result1))
					$old_qty=$product1['Order Quantity'];
			}






			if ($product['Product Web State']=='Out of Stock') {


				$order_button=sprintf('<td></td><td colspan=2 style="padding:0px"><div style="background:#ffdada;color:red;display:table-cell; vertical-align:middle;font-size:90%%;text-align:center;;border:1px solid #ccc;height:18px;width:58px;">%s</div></td>',_('Sold Out'));


			}
			elseif ($product['Product Web State']=='Discontinued') {
				//    $class_state='discontinued';
				//  $state=' <span class="discontinued">('._('Sold Out').')</span>';
				$order_button=sprintf('<td colspan=3>%s</td>',_('Sold Out'));
			}
			else {
				// $class_state='';
				// $state='';

				$order_button=sprintf('
                                      <td ><span id="loading%d"></span></td>
                                      <td style="padding:0" class="input">
                                       <input  onKeyUp="order_product_from_list_changed(%d)"  style="height:20px" id="qty%s"  type="text" value="%s" ovalue="%s"  >
                                      </td>
                                      <td style="padding:0">
                                      	<button id="list_button%d" onClick="order_product_from_list(%d)"  style="cursor:pointer;visibility:hidden;background:#fff;border:1px solid #ccc;border-left:none;height:22px;padding:0 2px">
                                      	<img id="list_button_img%d" style="pointer:cursor;position:relative;bottom:2px;width:16px;;height:16px" src="art/icons/basket_add.png" />
                                      	</button>
                                      </td>',
					$product['Product ID'],

					$product['Product ID'],
					$product['Product ID'],

					($old_qty>0?$old_qty:''),
					($old_qty>0?$old_qty:''),


					$product['Product ID'],

					$product['Product ID'],
					$product['Product ID']

				);


			}




			$form.=sprintf('<tr %s >
                           <td class="code">%s</td>
                           %s
                           <input type="hidden" id="order_id%d" value="%d">
                           <input type="hidden" id="pid%d" value="%d">
                           <input type="hidden" id="old_qty%d" value="%d">

                           <td class="price">%s</td>



                           <td class="description">%s <span class="rrp">%s</span></td>


                           </tr>'."\n",
				$tr_class,
				$product['Product Code'],
				$order_button,
				$product['Product ID'],$order_key,
				$product['Product ID'],$product['Product ID'],
				$product['Product ID'],$old_qty,

				$price,




				$product['Product Units Per Case'].'x '.$product['Product Special Characteristic'], $rrp

			);





			$counter++;
		}


		return $form;



	}

	function get_list_emals_commerce_old($products) {


		$form_id="order-form".rand();

		$form=sprintf('
                      <form action="%s" method="post" name="'.$form_id.'" id="'.$form_id.'" >
                      <input type="hidden" name="userid" value="%s">
                      <input type="hidden" name="nocart">
                        <input type="hidden" name="return" value="%s"> 
                        <input type="hidden" name="sd" value="ignore"> 

                      '
                
                      
                      
			,$this->site->get_checkout_data('url').'/cf/addmulti.cfm'
			,$this->site->get_checkout_data('id')
			,$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
		);
		$counter=1;
		foreach ($products as $product) {


			if ($this->print_rrp) {

				$rrp= $this->get_formated_rrp(array(
						'Product RRP'=>$product['Product RRP'],
						'Product Units Per Case'=>$product['Product Units Per Case'],
						'Product Unit Type'=>$product['Product Unit Type']), array('show_unit'=>$show_unit));

			} else {
				$rrp='';
			}






			$price= $this->get_formated_price(array(
					'Product Price'=>$product['Product Price'],
					'Product Units Per Case'=>1,
					'Product Unit Type'=>'',
					'Label'=>(''),
					'price per unit text'=>''

				));






			if ($product['Product Web State']=='Out of Stock') {




				$sql=sprintf("select `Email Site Reminder Key` from `Email Site Reminder Dimension` where `Trigger Scope`='Back in Stock' and `Trigger Scope Key`=%d and `User Key`=%d and `Email Site Reminder In Process`='Yes' ",
					$product['Product ID'],
					$this->user->id

				);
				$res=mysql_query($sql);
				if ($row=mysql_fetch_assoc($res)) {
					$email_reminder='<br/><span id="send_reminder_wait_'.$product['Product ID'].'"  style="display:none;color:#777"><img style="height:10px;position:relative;bottom:-1px"  src="art/loading.gif"> '._('Processing request').'</span><span id="send_reminder_container_'.$product['Product ID'].'"  style="color:#777"><span id="send_reminder_info_'.$product['Product ID'].'" >'._("We'll notify you via email").' <span style="cursor:pointer" id="cancel_send_reminder_'.$row['Email Site Reminder Key'].'"  onClick="cancel_send_reminder('.$row['Email Site Reminder Key'].','.$product['Product ID'].')"  >('._('Cancel').')</span></span></span>';
				}else {
					$email_reminder='<br/><span id="send_reminder_wait_'.$product['Product ID'].'"  style="display:none;color:#777"><img style="height:10px;position:relative;bottom:-1px"  src="art/loading.gif"> '._('Processing request').'</span><span id="send_reminder_container_'.$product['Product ID'].'" style="color:#777" ><span id="send_reminder_'.$product['Product ID'].'" style="cursor:pointer;" onClick="send_reminder('.$product['Product ID'].')">'._('Notify me when back in stock').' <img style="position:relative;bottom:-2px" src="art/send_mail.png"/></span></span><span id="send_reminder_msg_'.$product['Product ID'].'"></span></span>';

				}


				$class_state='out_of_stock';

				if ($product['Product Next Supplier Shipment']!='') {
					$out_of_stock_label=_('Out of stock').', '._('expected').': '.$product['Next Supplier Shipment'];
					$out_of_stock_label2=_('Expected').': '.$product['Next Supplier Shipment'];

				}
				else {
					$out_of_stock_label=_('Out of stock');
					$out_of_stock_label2=_('Out of stock');

				}

				$input=' <span class="out_of_stock" style="font-size:80%" title="'.$out_of_stock_label.'">'._('OoS').'</span>';
				$input='';


			}
			elseif ($product['Product Web State']=='Discontinued') {
				$class_state='discontinued';
				$input=' <span class="discontinued">('._('Sold Out').')</span>';

			}
			else {

				$input=sprintf('<input name="qty%s"  id="qty%s"  type="text" value=""  >',
					$counter,
					$counter
				);


			}

			$tr_style='';

			if ($counter==1)
				$tr_class='top';
			else
				$tr_class='';


			if ($product['Product Web State']=='Out of Stock') {
				$tr_class.='out_of_stock_tr';
				$tr_style="background-color:rgba(255,209,209,.6);border-top:1px solid #FF9999;;border-bottom:1px solid #FFB2B2;font-size:95%;padding-bottom:0px;";
				$description=$product['description']."<br/><span class='out_of_stock' style='opacity:.6;filter: alpha(opacity = 60);' >$out_of_stock_label2</span>$email_reminder";
			}else {
				$tr_style="padding-bottom:5px";
				$description=$product['description'];
			}


			$form.=sprintf('<tr class="%s" style="%s">
                           <input type="hidden" name="price%s" value="%s"  >
                           <input type="hidden" name="product%s"  value="%s %s" >
                           <td class="code" style="vertical-align:top;">%s</td>
                           <td class="price" style="vertical-align:top;">%s</td>
                           <td class="input" style="vertical-align:top;">
                           %s
                           </td>
                           <td class="description" style="vertical-align:top;">%s</td>
                           </tr>'."\n",
				$tr_class,$tr_style,

				$counter,
				number_format($product['Product Price'],2,'.',''),
				$counter,$product['Product Code'],
				clean_accents($product['long_description']),

				$product['Product Code'],
				$price,

				$input,

				$description







			);





			$counter++;
		}


		$form.=sprintf('<tr ><td colspan="4">
                       <input type="hidden" name="xreturn" value="%s">

                       </td></tr></form>
                       <tr><td colspan=1></td><td colspan="3">
                       <img onmouseover="this.src=\'art/ordernow_hover_%s.png\'" onmouseout="this.src=\'art/ordernow_%s.png\'"   onClick="document.forms[\''.$form_id.'\'].submit();" style="height:30px;cursor:pointer" src="art/ordernow_%s.png" alt="'._('Order Product').'">
                        </td></tr>
                       </table>
                       ',
			$this->data['Page URL'],
			$this->site->data['Site Locale'],
			$this->site->data['Site Locale'],
			$this->site->data['Site Locale']
		);
		return $form;
	}
	
	function get_list_emals_commerce($products) {


		$form_id="order_form".rand();

		$form=sprintf('
                      <form action="%s" method="post" name="'.$form_id.'" id="'.$form_id.'" >
                      <input type="hidden" name="userid" value="%s">
                      <input type="hidden" name="nocart">
                        <input type="hidden" name="return" value="%s"> 
                        <input type="hidden" name="sd" value="ignore"> 
                      '
                
                      
                      
			,$this->site->get_checkout_data('url').'/cf/addmulti.cfm'
			,$this->site->get_checkout_data('id')
			,$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
		);
		
		//$form='<form><table id="list_'.$form_id.'" border=1>';
		$form='<tbody id="list_'.$form_id.'" >';
		$counter=1;
		foreach ($products as $product) {


			if ($this->print_rrp) {

				$rrp= $this->get_formated_rrp(array(
						'Product RRP'=>$product['Product RRP'],
						'Product Units Per Case'=>$product['Product Units Per Case'],
						'Product Unit Type'=>$product['Product Unit Type']), array('show_unit'=>$show_unit));

			} else {
				$rrp='';
			}






			$price= $this->get_formated_price(array(
					'Product Price'=>$product['Product Price'],
					'Product Units Per Case'=>1,
					'Product Unit Type'=>'',
					'Label'=>(''),
					'price per unit text'=>''

				));






			if ($product['Product Web State']=='Out of Stock') {




				$sql=sprintf("select `Email Site Reminder Key` from `Email Site Reminder Dimension` where `Trigger Scope`='Back in Stock' and `Trigger Scope Key`=%d and `User Key`=%d and `Email Site Reminder In Process`='Yes' ",
					$product['Product ID'],
					$this->user->id

				);
				$res=mysql_query($sql);
				if ($row=mysql_fetch_assoc($res)) {
					$email_reminder='<br/><span id="send_reminder_wait_'.$product['Product ID'].'"  style="display:none;color:#777"><img style="height:10px;position:relative;bottom:-1px"  src="art/loading.gif"> '._('Processing request').'</span><span id="send_reminder_container_'.$product['Product ID'].'"  style="color:#777"><span id="send_reminder_info_'.$product['Product ID'].'" >'._("We'll notify you via email").' <span style="cursor:pointer" id="cancel_send_reminder_'.$row['Email Site Reminder Key'].'"  onClick="cancel_send_reminder('.$row['Email Site Reminder Key'].','.$product['Product ID'].')"  >('._('Cancel').')</span></span></span>';
				}else {
					$email_reminder='<br/><span id="send_reminder_wait_'.$product['Product ID'].'"  style="display:none;color:#777"><img style="height:10px;position:relative;bottom:-1px"  src="art/loading.gif"> '._('Processing request').'</span><span id="send_reminder_container_'.$product['Product ID'].'" style="color:#777" ><span id="send_reminder_'.$product['Product ID'].'" style="cursor:pointer;" onClick="send_reminder('.$product['Product ID'].')">'._('Notify me when back in stock').' <img style="position:relative;bottom:-2px" src="art/send_mail.png"/></span></span><span id="send_reminder_msg_'.$product['Product ID'].'"></span></span>';

				}


				$class_state='out_of_stock';

				if ($product['Product Next Supplier Shipment']!='') {
					$out_of_stock_label=_('Out of stock').', '._('expected').': '.$product['Next Supplier Shipment'];
					$out_of_stock_label2=_('Expected').': '.$product['Next Supplier Shipment'];

				}
				else {
					$out_of_stock_label=_('Out of stock');
					$out_of_stock_label2=_('Out of stock');

				}

				$input=' <span class="out_of_stock" style="font-size:80%" title="'.$out_of_stock_label.'">'._('OoS').'</span>';
				$input='';


			}
			elseif ($product['Product Web State']=='Discontinued') {
				$class_state='discontinued';
				$input=' <span class="discontinued">('._('Sold Out').')</span>';

			}
			else {

				$input=sprintf('<input   id="qty_%s_%s"  type="text" value=""  >',
					$form_id,
					$counter
				);


			}

			$tr_style='';

			if ($counter==1)
				$tr_class='top';
			else
				$tr_class='';


			if ($product['Product Web State']=='Out of Stock') {
				$tr_class.='out_of_stock_tr';
				$tr_style="background-color:rgba(255,209,209,.6);border-top:1px solid #FF9999;;border-bottom:1px solid #FFB2B2;font-size:95%;padding-bottom:0px;";
				$description=$product['description']."<br/><span class='out_of_stock' style='opacity:.6;filter: alpha(opacity = 60);' >$out_of_stock_label2</span>$email_reminder";
			}else {
				$tr_style="padding-bottom:5px";
				$description=$product['description'];
			}


			$form.=sprintf('<tr class="product_item %s" style="%s" counter="%s">
                           <input type="hidden" id="price_%s_%s" value="%s"  >
                           <input type="hidden" id="product_%s_%s"  value="%s %s" >
                           <td class="code" style="vertical-align:top;">%s</td>
                           <td class="price" style="vertical-align:top;">%s</td>
                           <td class="input" style="vertical-align:top;">
                           %s
                           </td>
                           <td class="description" style="vertical-align:top;">%s</td>
                           </tr>'."\n",
				$tr_class,$tr_style,$counter,

				$form_id,$counter,number_format($product['Product Price'],2,'.',''),
				$form_id,$counter,$product['Product Code'],clean_accents($product['long_description']),

				$product['Product Code'],
				$price,

				$input,

				$description







			);





			$counter++;
		}


		$form.=sprintf('<tr ><td colspan="4">
                       <input type="hidden" name="xreturn" value="%s">

                       </td></tr>
                       <tr><td colspan=1></td><td colspan="3">
                       <img onmouseover="this.src=\'art/ordernow_hover_%s.png\'" onmouseout="this.src=\'art/ordernow_%s.png\'"   onClick="order_from_list(\''.$form_id.'\')" style="height:30px;cursor:pointer" src="art/ordernow_%s.png" alt="'._('Order Product').'">
                        <img src="art/loading.gif" style="height:24px;position:relative;bottom:3px;visibility:hidden" id="waiting_%s">
                        </td></tr>
                       </tbody>
                       ',
			$this->data['Page URL'],
			$this->site->data['Site Locale'],
			$this->site->data['Site Locale'],
			$this->site->data['Site Locale'],
			$form_id
		);
		return $form;
	}

	function get_list_aw_checkout($products) {





		$form_id="order-form".rand();
//<input type="hidden" name="userid" value="%s">
		$form=sprintf('
                      <form action="%s" method="post" name="'.$form_id.'" id="'.$form_id.'" >
                      
                      
                      
                       <input type="hidden" name="customer_last_order" value="%s">
 						<input type="hidden" name="customer_key" value="%s">
                      <input type="hidden" name="nnocart"> ',
			$this->site->get_checkout_data('url').'/shopping_cart.php',
		//	$this->site->get_checkout_data('id'),
			$this->customer->get('Customer Last Order Date'),
			$this->customer->id

		);
		$counter=1;
		foreach ($products as $product) {


			if ($this->print_rrp) {

				$rrp= $this->get_formated_rrp(array(
						'Product RRP'=>$product['Product RRP'],
						'Product Units Per Case'=>$product['Product Units Per Case'],
						'Product Unit Type'=>$product['Product Unit Type']), array('show_unit'=>$show_unit));

			} else {
				$rrp='';
			}






			$price= $this->get_formated_price(array(
					'Product Price'=>$product['Product Price'],
					'Product Units Per Case'=>1,
					'Product Unit Type'=>'',
					'Label'=>(''),
					'price per unit text'=>''

				));






			if ($product['Product Web State']=='Out of Stock') {




				$sql=sprintf("select `Email Site Reminder Key` from `Email Site Reminder Dimension` where `Trigger Scope`='Back in Stock' and `Trigger Scope Key`=%d and `User Key`=%d and `Email Site Reminder In Process`='Yes' ",
					$product['Product ID'],
					$this->user->id

				);
				$res=mysql_query($sql);
				if ($row=mysql_fetch_assoc($res)) {
					$email_reminder='<br/><span id="send_reminder_wait_'.$product['Product ID'].'"  style="display:none;color:#777"><img style="height:10px;position:relative;bottom:-1px"  src="art/loading.gif"> '._('Processing request').'</span><span id="send_reminder_container_'.$product['Product ID'].'"  style="color:#777"><span id="send_reminder_info_'.$product['Product ID'].'" >'._("We'll notify you via email").' <span style="cursor:pointer" id="cancel_send_reminder_'.$row['Email Site Reminder Key'].'"  onClick="cancel_send_reminder('.$row['Email Site Reminder Key'].','.$product['Product ID'].')"  >('._('Cancel').')</span></span></span>';
				}else {
					$email_reminder='<br/><span id="send_reminder_wait_'.$product['Product ID'].'"  style="display:none;color:#777"><img style="height:10px;position:relative;bottom:-1px"  src="art/loading.gif"> '._('Processing request').'</span><span id="send_reminder_container_'.$product['Product ID'].'" style="color:#777" ><span id="send_reminder_'.$product['Product ID'].'" style="cursor:pointer;" onClick="send_reminder('.$product['Product ID'].')">'._('Notify me when back in stock').' <img style="position:relative;bottom:-2px" src="art/send_mail.png"/></span></span><span id="send_reminder_msg_'.$product['Product ID'].'"></span></span>';

				}


				$class_state='out_of_stock';

				if ($product['Product Next Supplier Shipment']!='') {
					$out_of_stock_label=_('Out of stock').', '._('expected').': '.$product['Next Supplier Shipment'];
					$out_of_stock_label2=_('Expected').': '.$product['Next Supplier Shipment'];

				}
				else {
					$out_of_stock_label=_('Out of stock');
					$out_of_stock_label2=_('Out of stock');

				}

				$input=' <span class="out_of_stock" style="font-size:80%" title="'.$out_of_stock_label.'">'._('OoS').'</span>';
				$input='';


			}
			elseif ($product['Product Web State']=='Discontinued') {
				$class_state='discontinued';
				$input=' <span class="discontinued">('._('Sold Out').')</span>';

			}
			else {

				$input=sprintf('<input name="qty%s"  id="qty%s"  type="text" value=""  >',
					$counter,
					$counter
				);


			}

			$tr_style='';

			if ($counter==1)
				$tr_class='top';
			else
				$tr_class='';


			if ($product['Product Web State']=='Out of Stock') {
				$tr_class.='out_of_stock_tr';
				$tr_style="background-color:rgba(255,209,209,.6);border-top:1px solid #FF9999;;border-bottom:1px solid #FFB2B2;font-size:95%;padding-bottom:0px;";
				$description=$product['description']."<br/><span class='out_of_stock' style='opacity:.6;filter: alpha(opacity = 60);' >$out_of_stock_label2</span>$email_reminder";
			}else {
				$tr_style="padding-bottom:5px";
				$description=$product['description'];
			}


			$form.=sprintf('<tr class="%s" style="%s">
                           <input type="hidden" name="price%s" value="%s"  >
                           <input type="hidden" name="product%s"  value="%s %s" >
                           <td class="code" style="vertical-align:top;">%s</td>
                           <td class="price" style="vertical-align:top;">%s</td>
                           <td class="input" style="vertical-align:top;">
                           %s
                           </td>
                           <td class="description" style="vertical-align:top;">%s</td>
                           </tr>'."\n",
				$tr_class,$tr_style,

				$counter,
				number_format($product['Product Price'],2,'.',''),
				$counter,$product['Product Code'],
				clean_accents($product['long_description']),

				$product['Product Code'],
				$price,

				$input,

				$description







			);





			$counter++;
		}


		$form.=sprintf('<tr ><td colspan="4">
                       <input type="hidden" name="return" value="%s">

                       </td></tr></form>
                       <tr><td colspan=1></td><td colspan="3">
                       <img onmouseover="this.src=\'art/ordernow_hover_%s.png\'" onmouseout="this.src=\'art/ordernow_%s.png\'"   onClick="document.forms[\''.$form_id.'\'].submit();" style="height:30px;cursor:pointer" src="art/ordernow_%s.png" alt="'._('Order Product').'">
                        </td></tr>
                       </table>
                       ',
			$this->data['Page URL'],
			$this->site->data['Site Locale'],
			$this->site->data['Site Locale'],
			$this->site->data['Site Locale']
		);
		return $form;
	



////========

		$form=sprintf('
                      <form action="%s" method="post">
                      <input type="hidden" name="userid" value="%s">
                      <input type="hidden" name="customer_last_order" value="%s">
 						<input type="hidden" name="customer_key" value="%s">
                      <input type="hidden" name="nnocart"> ',
			$this->site->get_checkout_data('url').'/shopping_cart.php',
			$this->site->get_checkout_data('id'),
			$this->customer->get('Customer Last Order Date'),
			$this->customer->id

		);
		$counter=1;
		foreach ($products as $product) {


			if ($this->print_rrp) {

				$rrp= $this->get_formated_rrp(array(
						'Product RRP'=>$product['Product RRP'],
						'Product Units Per Case'=>$product['Product Units Per Case'],
						'Product Unit Type'=>$product['Product Unit Type']), array('show_unit'=>$show_unit));

			} else {
				$rrp='';
			}






			$price= $this->get_formated_price(array(
					'Product Price'=>$product['Product Price'],
					'Product Units Per Case'=>1,
					'Product Unit Type'=>'',
					'Label'=>(''),
					'price per unit text'=>''

				));






			if ($product['Product Web State']=='Out of Stock') {
				$class_state='out_of_stock';

				$input=' <span class="out_of_stock" style="font-size:70%">'._('OoS').'</span>';



			}
			elseif ($product['Product Web State']=='Discontinued') {
				$class_state='discontinued';
				$input=' <span class="discontinued">('._('Sold Out').')</span>';

			}
			else {

				$input=sprintf('<input name="qty%s"  id="qty%s"  type="text" value=""  >',
					$counter,
					$counter
				);


			}



			if ($counter==1)
				$tr_class='class="top"';
			else
				$tr_class='';




			$form.=sprintf('<tr %s >
                           <input type="hidden" name="price%s" value="%s"  >
                           <input type="hidden" name="product%s"  value="%s %s" >
                           <td class="code">%s</td>
                           <td class="price">%s</td>
                           <td class="input">
                           %s
                           </td>
                           <td class="description">%s</td>
                           </tr>'."\n",
				$tr_class,

				$counter,
				number_format($product['Product Price'],2,'.',''),
				$counter,
				$product['Product Code'],
				clean_accents($product['long_description']),

				$product['Product Code'],
				$price,

				$input,



				$product['description']



			);





			$counter++;
		}


		$form.=sprintf('<tr class="space"><td colspan="4">
                       <input type="hidden" name="return" value="%s">
                       <input class="button" name="Submit" type="submit"  value="'._('Order Product').'">
                       <input class="button" name="Reset" type="reset"  id="Reset" value="'._('Reset').'"></td></tr></form></table>
                       '
			,$this->data['Page URL']);
		return $form;
	}

	function get_list_price_header_auto($products) {
		$price_label='';
		$min_price=999999999999;
		$max_price=-99999999999;
		$number_products_with_price=0;


		$same_units=true;
		$units=1;
		$counter=0;
		foreach ($products as $product) {

			if ($counter and $product['Product Units Per Case']!=$units) {
				$same_units=false;
			}else {
				$units=$product['Product Units Per Case'];
			}

			if ($product['Product Price']) {
				$number_products_with_price++;
				if ($min_price>$product['Product Price'])
					$min_price=$product['Product Price'];
				if ($max_price<$product['Product Price'])
					$max_price=$product['Product Price'];
			}
			$counter++;
		}


		if ($number_products_with_price and $same_units) {
			$price= $this->get_formated_price(
				array(
					'Product Price'=>$min_price,
					'Product Units Per Case'=>$units,
					'Product Unit Type'=>'',
					'Label'=>($min_price==$max_price?_('Price'):_('Price from')).':'
				)
			);

			if ($min_price==$max_price) {
				$price_label='<span class="price">'.$price.'</span>';
			} else {
				$price_label='<span class="price">'.$price.'</span>';
			}

		}

		return $price_label;
	}

	function get_list_rrp_header_auto($products) {
		$rrp_label='';
		$min_rrp=999999999999;
		$max_rrp=-99999999999;
		$number_products_with_rrp=0;

		$same_units=true;
		$units=1;
		$counter=0;

		foreach ($products as $product) {

			if ($counter and $product['Product Units Per Case']!=$units) {
				$same_units=false;
			}else {
				$units=$product['Product Units Per Case'];
			}


			if ($product['Product RRP']) {
				$number_products_with_rrp++;
				if ($min_rrp>($product['Product RRP']))
					$min_rrp=$product['Product RRP'];
				if ($max_rrp<$product['Product RRP'])
					$max_rrp=$product['Product RRP'];
			}

			$counter++;
		}

		if ($number_products_with_rrp and $same_units) {
			$rrp= $this->get_formated_price(array(
					'Product Price'=>$min_rrp,
					'Product Units Per Case'=>$units,
					'Product Unit Type'=>'',
					'Label'=>($min_rrp==$max_rrp?_('RRP'):_('RRP from')).':'
				)
			);

			if ($min_rrp==$max_rrp) {
				$rrp_label='<span class="rrp">'.$rrp.'</span>';
			} else {
				$rrp_label='<span class="rrp">'.$rrp.'</span>';
			}

		}

		return $rrp_label;
	}

	function get_formated_rrp($data,$options=false) {

		$data=array(
			'Product RRP'=>$data['Product RRP'],
			'Product Units Per Case'=>$data['Product Units Per Case'],
			'Product Currency'=>$this->currency,
			'Product Unit Type'=>$data['Product Unit Type'],
			'Label'=>$data['Label'],
			'locale'=>$this->site->data['Site Locale']);
		if (isset($data['price per unit text']))
			$_data['price per unit text']=$data['price per unit text'];

		return formated_rrp($data,$options);
	}

	function get_formated_price($data,$options=false) {

		$_data=array(
			'Product Price'=>$data['Product Price'],
			'Product Units Per Case'=>$data['Product Units Per Case'],
			'Product Currency'=>$this->currency,
			'Product Unit Type'=>$data['Product Unit Type'],
			'Label'=>$data['Label'],
			'locale'=>$this->site->data['Site Locale']

		);

		if (isset($data['price per unit text']))
			$_data['price per unit text']=$data['price per unit text'];

		return formated_price($_data,$options);
	}

	function display_title() {
		return $this->get('Page Store Title');
	}

	function display_top_bar() {

		if ($this->logged) {
			//$ecommerce_basket.ecommerceURL()
			//$ecommerce_checkout
			switch ($this->site->data['Site Checkout Method']) {
			case 'Mals':
			
				$basket='<div style="float:left;"> '._('Total').': '.$this->currency_symbol.'<span id="total"> <img src="art/loading.gif" style="width:14px;position:relative;top:2px"/></span> (<span id="number_items"><img src="art/loading.gif" style="width:14px;position:relative;top:2px"/></span> '._('items').') <span class="link basket"  id="see_basket"  onClick=\'window.location="'.$this->site->get_checkout_data('url').'/cf/review.cfm?userid='.$this->site->get_checkout_data('id').'&return='.$this->data['Page URL'].'"\' >'._('Basket & Checkout').'</span>  <img src="art/gear.png" style="visibility:hidden" class="dummy_img" /></div>' ;
					$basket='<div style="float:left;position:relative;top:4px;margin-right:20px"><span>'.$this->customer->get_hello().'</span>  <span class="link" onClick=\'window.location="logout.php"\' id="logout">'._('Log Out').'</span> <span  class="link" onClick=\'window.location="profile.php"\' >'._('My Account').'</span> </div>';

				$basket.='<div  style="float:right;position:relative;top:2px"><span style="cursor:pointer" onClick=\'window.location="'.$this->site->get_checkout_data('url').'/cf/review.cfm?sd=ignore&userid='.$this->site->get_checkout_data('id').'&return='.$this->data['Page URL'].'"\' > '._('Total').': '.$this->currency_symbol.'<span id="total"> <img src="art/loading.gif" style="width:14px;position:relative;top:2px;"/></span> (<span id="number_items"><img src="art/loading.gif" style="width:14px;position:relative;top:2px"/></span> '._('items').')</span> <img onClick=\'window.location="'.$this->site->get_checkout_data('url').'/cf/review.cfm?sd=ignore&userid='.$this->site->get_checkout_data('id').'&return='.$this->data['Page URL'].'"\' src="art/basket.jpg" style="height:15px;position:relative;top:3px;margin-left:10px;cursor:pointer"/> <span style="color:#ff8000;margin-left:0px" class="link basket"  id="see_basket"  onClick=\'window.location="'.$this->site->get_checkout_data('url').'/cf/review.cfm?sd=ignore&userid='.$this->site->get_checkout_data('id').'&return='.$this->data['Page URL'].'"\' >'._('Basket & Checkout').'</span> </div>' ;
			$html=$basket;

			break;
			case 'AW':
				$customer_data=urlencode(base64_encode(json_encode(array(
								'key'=>$this->customer->id,
								'email'=>$this->customer->get('Customer Main Plain Email'),
								'name'=>$this->customer->get('Customer Name'),
								'contact'=>$this->customer->get('Customer Main Contact Name'),
								'telephone'=>$this->customer->get('Customer Main Plain Telephone'),
								'vat_number'=>$this->customer->get('Customer Tax Number'),
								'billing_address'=>preg_replace('/\<br\/\>/','|',$this->customer->get('Customer XHTML Billing Address')),
								'delivery_address'=>preg_replace('/\<br\/\>/','|',$this->customer->get('Customer XHTML Main Delivery Address'))
							)))
				);
				$remote_page=$this->site->get_checkout_data('url').'/basket.php?data=' . $customer_data . '&scwdw=1&return='.$this->data['Page URL'];
				//print $remote_page;
				$basket= '<div style="position:absolute;left:990px;">'.file_get_contents($remote_page ).'</div>';

				$basket.='<div style="float:left;"><span class="link basket"  id="see_basket"  onClick=\'window.location="'.$this->site->get_checkout_data('url').'/basket.php?data='.$customer_data.'"\' >'._('Basket & Checkout').'</span>  <img src="art/gear.png" style="visibility:hidden" class="dummy_img" /></div>' ;
							$html=$basket.'<div style="float:right"><span>'.$this->customer->get_hello().'</span>  <span class="link" onClick=\'window.location="logout.php"\' id="logout">'._('Log Out').'</span> <span  class="link" onClick=\'window.location="profile.php"\' >'._('My Account').'</span> <img alt="'._('Profile').'" src="art/gear.png"  onClick=\'window.location="profile.php"\' id="show_actions_dialog" ></div>';

				break;
			default:

				if ($this->order) {


					$basket='<div style="float:left;">
				<span id="basket_total">'.$this->order->get('Total Amount').'</span>
				<span class="link basket"  id="see_basket"  onClick=\'window.location="basket.php"\' >'._('See Basket').'</span>
				<span class="link basket"  id="checkout"  onClick=\'window.location="checkout.php"\' >'._('Check Out').'</span>

				<img src="art/gear.png" style="visibility:hidden" class="dummy_img" /></div>' ;
				}else {
					$basket='<div style="float:left;">
								<span>'.money_locale(0,$this->site->data['Site Locale'],$this->currency).'</span>

				<span class="link basket" style="margin-left:5px" id="see_basket"  onClick=\'window.location="basket.php"\' >'._('See Basket').'</span>

				<img src="art/gear.png" style="visibility:hidden" class="dummy_img" /></div>' ;

				}

			$html=$basket.'<div style="float:right"><span>'.$this->customer->get_hello().'</span>  <span class="link" onClick=\'window.location="logout.php"\' id="logout">'._('Log Out').'</span> <span  class="link" onClick=\'window.location="profile.php"\' >'._('My Account').'</span> <img alt="'._('Profile').'" src="art/gear.png"  onClick=\'window.location="profile.php"\' id="show_actions_dialog" ></div>';

				break;
			}


		} else {
			$html='<div style="float:right"> <span class="link" onClick=\'window.location="registration.php"\' id="show_register_dialog">'._('Create Account').'</span> <span class="link"  onClick=\'window.location="login.php?from='.$this->id.'"\' id="show_login_dialog">'._('Log in').'</span><img src="art/gear.png" style="visibility:hidden" class="dummy_img" /></div>';
		}



		return $html;


	}

	function display_label() {

		return $this->data['Page Parent Code'];
	}




	function update_list_products() {
		if ($this->data['Page Type']!='Store' )
			return;



		$lists=$this->get_list_products_from_source();
		$valid_list_keys=array();
		foreach ($lists as $list_key) {

			$sql=sprintf("select `Page Product List Key` from `Page Product List Dimension` where `Page Key`=%d and `Page Product List Code`=%s  ",
				$this->id,
				prepare_mysql($list_key)
			);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$valid_list_keys[]=$row['Page Product List Key'];

			} else {
				if ($this->data['Page Store Section']=='Family Catalogue') {
					$sql=sprintf("insert into `Page Product List Dimension` (`Page Key`,`Site Key`,`Page Product List Code`,`Page Product List Type`,`Page Product List Parent Key`) values  (%d,%d,%s,%s,%d)",
						$this->id,
						$this->data['Page Site Key'],
						prepare_mysql($list_key),
						prepare_mysql('FamilyList'),
						$this->data['Page Parent Key']

					);
					mysql_query($sql);
					//print "$sql\n";
					$valid_list_keys[]=prepare_mysql(mysql_insert_id());
				}
			}

			if (count($valid_list_keys)>0) {
				$sql=sprintf("delete from `Page Product List Dimension` where `Page Key`=%d and `Page Product List Key` not in (%s) ",$this->id,join(',',$valid_list_keys));
				mysql_query($sql);
			} else {
				$sql=sprintf("delete from `Page Product List Dimension` where `Page Key`=%d",$this->id);
				mysql_query($sql);
			}
		}

		$products_from_family=array();
		$number_lists=0;
		$number_products=0;

		$sql=sprintf("select `Page Product List Code`,`Page Product List Key` from `Page Product List Dimension` where `Page Key`=%d  ",
			$this->id

		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {



			$new_products_on_list=$this->get_products_from_list($row['Page Product List Code']);



			$sql=sprintf("select `Product ID`,`Page Product Key` from `Page Product Dimension` where `Parent Key`=%d and `Parent Type`='List'",
				$row['Page Product List Key']
			);
			$res2=mysql_query($sql);
			//print "$sql\n";
			$old_products_on_list=array();
			while ($row2=mysql_fetch_assoc($res2)) {
				$old_products_on_list[$row2['Product ID']]=$row2['Page Product Key'];
			}

			foreach ($new_products_on_list as $product_pid=>$tmp) {
				if (array_key_exists($product_pid,$old_products_on_list)) {

				}else {
					$product=new Product('pid',$product_pid);

					$sql=sprintf("insert into `Page Product Dimension` (`Parent Key`,`Site Key`,`Page Key`,`Product ID`,`Family Key`,`Parent Type`,`State`) values  (%d,%d,%d,%d,%d,'List',%s)",
						$row['Page Product List Key'],
						$this->data['Page Site Key'],
						$this->id,

						$product_pid,
						$product->data['Product Family Key'],
						prepare_mysql($this->data['Page State'])

					);
					mysql_query($sql);

					// print "$sql\n";
					$product->update_number_pages();


				}
			}
			//print_r($old_products_on_list);
			foreach ($old_products_on_list as $product_pid=>$page_product_key) {

				//print "$product_pid";
				//print_r($new_products_on_list);

				if (!array_key_exists($product_pid,$new_products_on_list)) {
					$sql=sprintf("delete from `Page Product Dimension` where `Page Product Key`=%d",
						$page_product_key
					);
					//print "$sql\n";
					mysql_query($sql);

					$product=new Product('pid',$product_pid);
					$product->update_number_pages();

				}
			}


			$sql=sprintf("update `Page Product List Dimension` set `Page Product List Number Products`=%d where `Page Product List Key`=%d",
				count($new_products_on_list),
				$row['Page Product List Key']
			);
			mysql_query($sql);

			$number_products+=count($new_products_on_list);
			$number_lists++;
		}



		$this->data['Number Products In Lists']=$number_products;
		$this->data['Number Lists']=$number_lists;
		$this->data['Number Products']=$this->data['Number Buttons']+$this->data['Number Products In Lists'];

		$sql=sprintf("update `Page Store Dimension`  set  `Number Products`=%d ,`Number Lists`=%d,`Number Products In Lists`=%d where `Page Key`=%d",

			$this->data['Number Products'],
			$this->data['Number Lists'],
			$this->data['Number Products In Lists'],
			$this->id);
		$res=mysql_query($sql);


	}


	function get_button_products_from_parent() {
		$sql=sprintf("select `Product Currency`,`Product Name`,`Product ID`,`Product Code`,`Product Price`,`Product RRP`,`Product Units Per Case`,`Product Unit Type`,`Product Web State`,`Product Special Characteristic` from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline'",
			$this->data['Page Parent Key']);

		$result=mysql_query($sql);
		$products=array();
		while ($row2=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$products[]=$row2;
		}
		return $products;
	}

	function get_body_includes() {

		$include='';
		if ($this->data['Page Type']!='Store' )
			return '';

		if ($this->data['Page Use Site Body Include']=='Yes')
			$include.=$this->site->data['Site Body Include'];
		$include.=$this->data['Page Body Include'];
		return $include;
	}

	function get_head_includes() {

		$include='';
		if ($this->data['Page Type']!='Store' )
			return '';

		if ($this->data['Page Use Site Head Include']=='Yes')
			$include.=$this->site->data['Site Head Include'];
		$include.=$this->data['Page Head Include'];
		return $include;
	}


	function update_button_products($source='Source') {

		if ($this->data['Page Type']!='Store' )
			return;

		include_once 'class.Product.php';

		if ($source=='Source') {

			$buttons=$this->get_button_products_from_source();
		}
		else if ($source=='Parent') {
				$buttons=$this->get_button_products_from_parent();
			}
		else {
			reuturn;
		}

		//print_r($buttons);

		$old_page_buttons_to_delete=array();
		$sql=sprintf("select `Page Product Button Key`,`Product ID` from  `Page Product Button Dimension`  where `Page Key`=%d",
			$this->id);



		$result=mysql_query($sql);
		$products=array();
		while ($row2=mysql_fetch_assoc($result)) {

			$old_page_buttons_to_delete[$row2['Page Product Button Key']]=$row2['Product ID'];
		}
		//print count($old_page_buttons_to_delete);
		//print_r($old_page_buttons_to_delete);

		$number_buttons=0;
		foreach ($buttons as $product_code) {



			$product=new Product('code_store',$product_code,$this->data['Page Store Key']);
			//print_r($product);
			if ($product->id) {
				$number_buttons++;
				if (!in_array($product->pid,$old_page_buttons_to_delete)) {
					$sql=sprintf("insert into `Page Product Button Dimension` (`Site Key`,`Page Key`,`Product ID`) values  (%d,%d,%d)",
						$this->data['Page Site Key'],
						$this->id,
						$product->pid
					);
					mysql_query($sql);
					//print "$sql\n";
					$page_product_key=mysql_insert_id();
					$sql=sprintf("insert into `Page Product Dimension` (`Page Key`,`Site Key`,`Product ID`,`Family Key`,`Parent Key`,`Parent Type`,`State`) values  (%d,%d,%d,%d,%d,'Button',%s)",
						$this->id,
						$this->data['Page Site Key'],
						$product->pid,
						$product->data['Product Family Key'],
						$page_product_key,
						prepare_mysql($this->data['Page State'])
					);
					mysql_query($sql);

					$product->update_number_pages();


				}else {
					$key = array_search($product->pid, $old_page_buttons_to_delete);
					if (false !== $key) {
						unset($old_page_buttons_to_delete[$key]);
					}
				}
			}
		}
		//print count($old_page_buttons_to_delete);
		//print_r($old_page_buttons_to_delete);

		foreach ($old_page_buttons_to_delete as $key=>$product_pid) {
			$sql=sprintf("delete  from  `Page Product Button Dimension`  where `Page Product Button Key`=%d", $key);
			mysql_query($sql);
			//print "$sql\n";
			$sql=sprintf("delete  from  `Page Product Dimension`  where `Parent Key`=%d and `Parent Type`='Button'", $key);
			mysql_query($sql);
			$product=new Product('pid',$product_pid);
			$product->update_number_pages();

		}

		$this->data['Number Buttons']=$number_buttons;

		$this->data['Number Products']=$this->data['Number Products In Lists']+$this->data['Number Buttons'];
		$sql=sprintf("update `Page Store Dimension`  set `Number Buttons`=%d , `Number Products`=%d where `Page Key`=%d",
			$this->data['Number Buttons'],
			$this->data['Number Products'],

			$this->id);





	}

	function get_list_products_from_source() {



		$html=$this->data['Page Store Source'];


		$lists=array();

		$regexp = '\{\s*\$page->display_list\s*\((.*)\)\s*\}';
		if (preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {
			foreach ($matches as $match) {
				$lists[]=($match[1]==''?'default':$match[1]);
			}
		}

		return $lists;
	}

	function get_button_products_from_source() {
		$html=$this->data['Page Store Source'];


		$html=preg_replace('/display_buttom/','display_button',$html);

		$buttons=array();

		$regexp = '\{\s*\$page->display_button\s*\((.*)\)\s*\}';
		if (preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {
			foreach ($matches as $match) {

				$id=($match[1]==''?'default':$match[1]);
				$id=preg_replace('/^\"/','',$id);
				$id=preg_replace('/^\'/','',$id);
				$id=preg_replace('/\"$/','',$id);
				$id=preg_replace('/\'$/','',$id);
				$buttons[]=$id;
			}
		}

		return $buttons;
	}

	function display_search() {
		print $this->site->display_search();
	}

	function display_menu() {
		print $this->site->display_menu();
	}


	function update_preview_snapshot($dirname=false) {


		if ($this->data['Page Type']!='Store' )
			return;

		if (!$dirname) {
			$dirname=dirname($_SERVER['PHP_SELF']);
		}


		$r = join('',unpack('v*', fread(fopen('/dev/urandom', 'r'),25)));
		$pwd=uniqid('',true).sha1(mt_rand()).'.'.$r;

		$sql=sprintf("insert into `MasterKey Internal Dimension` (`User Key`,`Key`,`Valid Until`,`IP`)values (%s,%s,%s,%s) "
			,1
			,prepare_mysql($pwd)
			,prepare_mysql(date("Y-m-d H:i:s",strtotime("now +5 minute")))
			,prepare_mysql(ip(),false)
		);

		// print $sql;
		mysql_query($sql);


		$old_image_key=$this->data['Page Preview Snapshot Image Key'];

		//   $new_image_key=$old_image_key;
		//      $image=new Image($image_key);



		$height=$this->data['Page Header Height']+$this->data['Page Content Height']+$this->data['Page Footer Height']+10;
		//ar_edit_sites.php?tipo=update_page_snapshot&id=1951;

		$url="http://localhost/".$dirname."/authorization.php?url=".urlencode("page_preview.php?header=0&id=".$this->id).'\&mk='.$pwd;

		ob_start();
		system("uname");



		$_system = ob_get_clean();
		if (preg_match('/darwin/i',$_system)) {
			$command="mantenence/scripts/webkit2png_mac.py  -C -o app_files/tmp/pp_image".$this->id."  --clipheight=".($height*0.5)."  --clipwidth=512  -s 0.5  ".$url;

			//       $command="mantenence/scripts/webkit2png  -C -o app_files/tmp/ph_image".$this->id."  --clipheight=80  --clipwidth=488  -s 0.5   http://localhost/dw/public_header_preview.php?id=".$this->id;

		}

		elseif (preg_match('/linux/i',$_system)) {
			$command='xvfb-run --server-args="-screen 0, 1280x1024x24" python mantenence/scripts/webkit2png_linux.py --style=windows  --log=app_files/tmp/webkit2png_linux.log -o app_files/tmp/pp_image'.$this->id.'-clipped.png    '.$url;



			//  $command='xvfb-run --server-args="-screen 0, 1280x1024x24" python mantenence/scripts/webkit2png_linux.py --log=app_files/tmp/webkit2png_linux.log -o app_files/tmp/pp_image'.$this->id.'-clipped.png --scale  512 '.(ceil($height*0.5)).'    '.$url;
		}
		else {
			return;

		}




		ob_start();
		system($command,$retval);
		ob_get_clean();


		// print "$url\n\n";

		$this->snapshots_taken++;


		$image_data=array('file'=>"app_files/tmp/pp_image".$this->id."-clipped.png",'source_path'=>'','name'=>'page_preview'.$this->id);

		//   print_r($image_data);
		$image=new Image('find',$image_data,'create');

		unlink("app_files/tmp/pp_image".$this->id."-clipped.png");

		$new_image_key=$image->id;
		if (!$new_image_key) {
			print $image->msg;
			exit("xx \n");

		}





		if ($new_image_key!=$old_image_key) {
			$this->data['Page Preview Snapshot Image Key']=$new_image_key;
			$sql=sprintf("delete from `Image Bridge` where `Subject Type`=%s and `Subject Key`=%d and `Image Key`=%d ",
				prepare_mysql('Page Preview'),
				$this->id,
				$image->id
			);
			mysql_query($sql);
			//print $sql;
			$old_image=new Image($old_image_key);
			$old_image->delete();


			$sql=sprintf("insert into `Image Bridge` values (%s,%d,%d,'Yes','')",
				prepare_mysql('Page Preview'),
				$this->id,
				$image->id

			);
			mysql_query($sql);

			$image->update_other_size_data();



			$sql=sprintf("update `Page Store Dimension` set `Page Preview Snapshot Image Key`=%d,`Page Preview Snapshot Last Update`=NOW()  where `Page Key`=%d",
				$this->data['Page Preview Snapshot Image Key'],
				$this->id

			);
			mysql_query($sql);


			$this->updated=true;
			$this->new_value=$this->data['Page Preview Snapshot Image Key'];

		} else {


			$sql=sprintf("update `Page Store Dimension` set `Page Preview Snapshot Last Update`=NOW()  where `Page Key`=%d",
				$this->id
			);
			mysql_query($sql);

		}


		//  usleep(250000);
		$this->get_data('id',$this->id);
		$new_height=$this->data['Page Header Height']+$this->data['Page Content Height']+$this->data['Page Footer Height']+10;

		if ($new_height!=$height) {
			$this->update_preview_snapshot();
		}

	}

	function get_snapshot_date() {
		return strftime("%a %e %b %Y %H:%M %Z", strtotime($this->data['Page Snapshot Last Update'].' UTC')) ;
	}
	function get_preview_snapshot_date() {

		if ($this->data['Page Preview Snapshot Last Update']!='')
			return strftime("%a %e %b %Y %H:%M %Z", strtotime($this->data['Page Preview Snapshot Last Update'].' UTC')) ;
	}

	function get_preview_snapshot_src() {

		return sprintf("image.php?id=%d",$this->data['Page Preview Snapshot Image Key']);
	}

	function get_preview_snapshot_image_key() {
		return $this->data['Page Preview Snapshot Image Key'];
	}

	function add_found_in_link($parent_key) {

		if ($this->id==$parent_key) {
			$this->error=true;
			$this->msg='same page key';
			return;
		}

		$sql=sprintf("insert into `Page Store Found In Bridge` values (%d,%d)  ",
			$this->id,
			$parent_key);

		mysql_query($sql);
		$this->update_number_found_in();
		$this->updated=true;
	}

	function remove_found_in_link($parent_key) {



		$sql=sprintf("delete from  `Page Store Found In Bridge` where `Page Store Key`=%d and `Page Store Found In Key`=%d   ",
			$this->id,
			$parent_key);

		mysql_query($sql);
		$this->update_number_found_in();
		$this->updated=true;
	}

	function update_state($value,$options) {

		$old_state=$this->data['Page State'];
		$this->update_field('Page State',$value,$options);


		if ($old_state!=$this->data['Page State']) {
			$sql=sprintf("insert into `Page State Timeline`  (`Page Key`,`Site Key`,`Store Key`,`Date`,`State`,`Operation`) values (%d,%d,%d,%s,%s,'Change') ",
				$this->id,
				$this->data['Page Site Key'],
				$this->data['Page Site Key'],
				prepare_mysql(gmdate('Y-m-d H:i:s')),
				prepare_mysql($this->data['Page State'])

			);
			mysql_query($sql);

			$sql=sprintf("update `Page Product Dimension` set `State`=%s where `Page Key`=%d",
				$this->data['Page State'],
				$this->id
			);
			mysql_query($sql);
			$sql=sprintf("select `Page Store Key`  from  `Page Store See Also Bridge` where `Page Store See Also Key`=%d ",$this->id);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {
				$_page=new Page ($row['Page Store Key']);
				$_page->update_see_also();
			}

		}


	}

	function update_number_found_in() {
		$number_found_in_links=0;
		$sql=sprintf("select count(*) as num from  `Page Store Found In Bridge` where `Page Store Key`=%d",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$number_found_in_links=$row['num'];
		}
		$this->data['Number Found In Links']=$number_found_in_links;
		$sql=sprintf("update `Page Store Dimension` set `Number Found In Links`=%d  where `Page Key`=%d",
			$number_found_in_links,
			$this->id

		);
		mysql_query($sql);



	}

	function get_page_height() {
		return $this->data['Page Header Height']+$this->data['Page Content Height']+$this->data['Page Footer Height']+22;
	}

	function add_redirect($source_url='') {

		if ($source_url=='') {
			$this->error=true;
			$this->msg=_('No URL provied');

			return;
		}

		$site=new Site($this->data['Page Site Key']);

		$url_array=explode("/", _trim($source_url));
		//print_r($url_array);

		if (count($url_array)<2) {
			$this->error=true;
			$this->msg=_('Errorr, the URL should a site subdirectory');
			return;
		}

		$host=array_shift($url_array);
		$file=array_pop($url_array);
		$path=join('/',$url_array);

		if ($file=='') {
			$file='index.html';
		}
		if ($host=='') {

			$host=$site->data['Site URL'];
		}



		$_source=$host.'/'.$path.'/'.$file;

		$_source_bis=strtolower(preg_replace('/^www\./','',$_source));

		$target=strtolower($this->data['Page URL']);

		//print "$source_url -> $target";
		if (
			$target==strtolower($_source_bis) or
			$target==$_source

		) {
			$this->error=true;
			$this->msg=_('Same URL as the redirect');

			return;
		}




		//print $_source." --> ".$site->data['Site FTP Server']."\n";

		if (strtolower($site->data['Site FTP Server'])==$host) {
			$ftp_pass='Yes';
		}
		else {
			$ftp_pass='No';
		}

		$sql=sprintf("insert into `Page Redirection Dimension` (`Source Host`,`Source Path`,`Source File`, `Page Target URL`,`Page Target Key`, `Can Upload`) values
		(%s,%s,%s, %s,%d, %s)"
			,prepare_mysql($host)
			,prepare_mysql($path,false)
			,prepare_mysql($file)
			,prepare_mysql($this->data['Page URL'])
			,$this->id
			,prepare_mysql($ftp_pass));

		mysql_query($sql);
		//print "$sql\n";
		$redirection_key=mysql_insert_id();

		return $redirection_key;

	}

	function get_all_redirects_data($smarty=false) {

		$data=array();
		$sql=sprintf("select * from `Page Redirection Dimension` where `Page Target Key`=%d", $this->id);
		$result=mysql_query($sql);

		while ($row=mysql_fetch_assoc($result)) {
			if ($smarty) {
				$_row=array();
				foreach ($row as $key=>$value) {
					$_row[str_replace(' ','',$key)]=$value;
				}
				$_row['Source']=$_row['SourceHost'].'/'.($_row['SourcePath']?$_row['SourcePath'].'/':'').$_row['SourceFile'];
				$data[]=$_row;
			}else {
				$row['Source']=$row['Source Host'].'/'.($row['Source Path']?$row['Source Path'].'/':'').$row['Source File'];
				$data[]=$row;
			}
		}

		return $data;
	}

	function get_redirect_data($redirect_key,$smarty=false) {

		$data=false;
		$sql=sprintf("select * from `Page Redirection Dimension` where `Page Target Key`=%d and `Page Redirection Key`=%d", $this->id,$redirect_key);
		$result=mysql_query($sql);

		if ($row=mysql_fetch_assoc($result)) {
			if ($smarty) {
				$_row=array();
				foreach ($row as $key=>$value) {
					$_row[str_replace(' ','',$key)]=$value;
				}
				$_row['Source']=$_row['SourceHost'].'/'.($_row['SourcePath']?$_row['SourcePath'].'/':'').$_row['SourceFile'];
				$data=$_row;
			}else {
				$row['Source']=$row['Source Host'].'/'.($row['Source Path']?$row['Source Path'].'/':'').$row['Source File'];
				$data=$row;
			}
		}

		return $data;
	}




	function display_vertical_menu() {

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



	function update_requests($interval) {

		if ($this->data['Page Type']!='Store' )
			return;



		list($db_interval,$from_date,$to_date,$from_date_1yb,$to_1yb)=calculate_inteval_dates($interval);

		$sql=sprintf("select count(*) as num_requests ,count(distinct `User Session Key`) num_sessions ,count(Distinct `User Visitor Key`) as num_visitors   from  `User Request Dimension`   where `Page Key`=%d  %s",
			$this->id,
			($from_date?' and `Date`>='.prepare_mysql($from_date):'')


		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->data['Page Store '.$db_interval.' Acc Requests']=$row['num_requests'];
			$this->data['Page Store '.$db_interval.' Acc Sessions']=$row['num_sessions'];
			$this->data['Page Store '.$db_interval.' Acc Visitors']=$row['num_visitors'];
		}else {
			$this->data['Page Store '.$db_interval.' Acc Requests']=0;
			$this->data['Page Store '.$db_interval.' Acc Sessions']=0;
			$this->data['Page Store '.$db_interval.' Acc Visitors']=0;

		}

		$sql=sprintf("select count(*) as num_requests ,count(distinct `User Session Key`) num_sessions ,count(Distinct `User Key`) as num_users   from  `User Request Dimension`  where  `Is User`='Yes' and `Page Key`=%d  %s",
			$this->id,
			($from_date?' and `Date`>='.prepare_mysql($from_date):'')


		);
		$res=mysql_query($sql);
		//print "$sql\n\n\n\n";
		if ($row=mysql_fetch_assoc($res)) {
			$this->data['Page Store '.$db_interval.' Acc Users Requests']=$row['num_requests'];
			$this->data['Page Store '.$db_interval.' Acc Users Sessions']=$row['num_sessions'];
			$this->data['Page Store '.$db_interval.' Acc Users']=$row['num_users'];
		}else {
			$this->data['Page Store '.$db_interval.' Acc Users Requests']=0;
			$this->data['Page Store '.$db_interval.' Acc Users Sessions']=0;
			$this->data['Page Store '.$db_interval.' Acc Users']=0;
		}

		$sql=sprintf('update `Page Store Dimension` set `Page Store '.$db_interval.' Acc Requests`=%d,
	`Page Store '.$db_interval.' Acc Sessions`=%d,
	`Page Store '.$db_interval.' Acc Visitors`=%d,
	`Page Store '.$db_interval.' Acc Users Requests`=%d,
	`Page Store '.$db_interval.' Acc Users Sessions`=%d,
	`Page Store '.$db_interval.' Acc Users`=%d
	where `Page Key`=%d',
			$this->data['Page Store '.$db_interval.' Acc Requests'],
			$this->data['Page Store '.$db_interval.' Acc Sessions'],
			$this->data['Page Store '.$db_interval.' Acc Visitors'],
			$this->data['Page Store '.$db_interval.' Acc Users Requests'],
			$this->data['Page Store '.$db_interval.' Acc Users Sessions'],
			$this->data['Page Store '.$db_interval.' Acc Users'],

			$this->id
		);
		mysql_query($sql);
		//print "$sql\n";
	}

	function get_all_products() {
		$sql=sprintf("select pd.`Product ID`, `Product Code` from `Page Product Button Dimension` ppd left join `Product Dimension` pd on (ppd.`Product ID` = pd.`Product ID`) where `Page Key`=%d", $this->id);
		//print $sql;
		$result1=mysql_query($sql);
		$products=array();
		while ($row1=mysql_fetch_assoc($result1)) {
			$products[]=array('code'=>$row1['Product Code']);

		}
		return $products;
	}


	function display_product_image($tag) {

		$html='';
		include_once 'class.Product.php';
		$product=new Product('code_store',$tag,$this->data['Page Store Key']);
		//print_r($product);
		if ($product->id) {
			$html=$this->display_button_logged_out($product);
			$small_url='public_image.php?id='.$product->data["Product Main Image Key"].'&size=small';
			$normal_url='public_image.php?id='.$product->data["Product Main Image Key"];
			$code=$product->data['Product Code'];
			$html='<ul class="gallery clearfix"><li>
			<a  style="border:none;text-decoration:none" href="'.$normal_url.'" rel="prettyPhoto" >
			<img style="float:left;border:0px solid#ccc;padding:2px;margin:2px;cursor:pointer;width:150px" src="'.$small_url.'" alt="'.$code.'" />
			</a></li></ul>';

		}
		return $html;
	}

	function get_site_key() {

		if ($this->type=='Store') {
			return $this->data['Page Site Key'];
		}else {
			return 0;
		}
	}

	function get_plain_content() {
		$content=$this->get_xhtml_content();
		$content=preg_replace('/\<br\/?\>/',' ',$content);
		$content=preg_replace('/:/',' ',$content);

		$content=strip_tags($content);
		$content=preg_replace('/\s+/',' ',$content);

		$content = html_entity_decode($content, ENT_QUOTES, "utf-8");

		$content=preg_replace('/\&amp\;/','',$content);
		$content=preg_replace('/\&nbsp\;/','',$content);
		$content=preg_replace('/\{.+\}/','',$content);
		$content=preg_replace('/(\"|\“|\”)/','',$content);



		return $content;
	}

	function get_xhtml_content() {

		if ($this->data['Page Type']=='Store') {
			if ($this->data['Page Store Content Display Type']=='Source') {
				return $this->data['Page Store Source'];
			}else {
				return '';
			}

		}else {
			return '';
		}
	}


	function update_site_flag_key($value) {


		$sql=sprintf("select `Site Key`,`Site Flag Color` from  `Site Flag Dimension` where `Site Flag Key`=%d",
			$value);

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


			if ($row['Site Key']!=$this->data['Page Site Key']) {
				$this->error=true;
				$this->msg='flag key not in this site';
				return;
			}

			$old_key=$this->data['Site Flag Key'];

			$sql=sprintf("update `Page Store Dimension` set `Site Flag Key`=%d ,`Site Flag`=%s where `Page Key`=%d"
				,$value
				,prepare_mysql($row['Site Flag Color'])
				,$this->id
			);

			mysql_query($sql);
			$this->data['Site Flag Key']=$value;
			$this->new_value=$this->data['Site Flag Key'];
			$this->msg=_('Site flag changed');
			$this->updated=true;

			$site=new Site($this->data['Page Site Key']);
			$site->update_page_flag_number($this->data['Site Flag Key']);
			if ($old_key) {
				$site->update_page_flag_number($old_key);

			}



		}else {
			$this->error=true;
			$this->msg='flag key not found';

		}

	}


	function get_formated_state() {

		switch ($this->data['Page State']) {
		case 'Offline':
			return _('Offline');
			break;
		case 'Online':
			return _('Online');
			break;

		}

	}

}






?>
