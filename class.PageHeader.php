<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Created: 25 November 2011 18:33:33 GMT
 Copyright (c) 2011, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';
include_once 'class.Page.php';
class PageHeader extends DB_Table {

	var $new=false;
	var $type='Header';

	function PageHeader($arg1=false,$arg2=false) {
		$this->table_name='Page Header';
		$this->ignore_fields=array('Page Header Key');


		if (!$arg1 and !$arg2) {
			$this->error=true;
			$this->msg='No arguments';
		}
		if (is_numeric($arg1)) {
			$this->get_data('id',$arg1);
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

		$this->get_data($arg1,$arg2);

	}


	function get_data($tipo,$tag,$tag2=false) {


		$sql=sprintf("select * from `Page Header Dimension` where  `Page Header Key`=%d",$tag);



		$result =mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->id=$this->data['Page Header Key'];

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




		$sql=sprintf("select `Page Header Key` from `Page Header Dimension`  where `Page Header Name`=%s and `Site Key`=%d",
			prepare_mysql($data['Page Header Name']),
			$data['Site Key']

		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$this->found=true;
			$this->found_key=$row['Page Header Key'];
			$this->get_data('id',$this->found_key);
		}


		if (!$this->found and $create) {
			$this->create($raw_data);

		}


	}


	function create($raw_data) {
		$temporal_name=false;
		$this->new=false;
		if (!isset($raw_data['Page Header Name']) or  $raw_data['Page Header Name']=='') {

			$raw_data['Page Header Name']=uniqid();
			$temporal_name='header';

		}

		$sql=sprintf("select `Page Header Name` from `Page Header Dimension`  where `Page Header Name`=%s and `Site Key`=%d",
			prepare_mysql($raw_data['Page Header Name']),
			$raw_data['Site Key']

		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$raw_data['Page Header Name']=uniqid();
			$temporal_name=$row['Page Header Name'];
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
			if (preg_match('/Template|Javascript|CSS/i',$key))
				$values.="'".addslashes($value)."',";
			else
				$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);
		$sql=sprintf("insert into `Page Header Dimension` %s %s",$keys,$values);


		if (mysql_query($sql)) {
			$this->id=mysql_insert_id();

			if ($temporal_name) {
				$sql=sprintf("update `Page Header Dimension` set `Page Header Name`=%s where `Page Header Key`=%d",
					prepare_mysql($temporal_name.$this->id),
					$this->id
				);
				mysql_query($sql);

			}


			$this->get_data('id',$this->id);

			$site=new Site ($this->data['Site Key']);
			//$site->update_headers($this->id);



		} else {
			$this->error=true;
			$this->msg='Can not insert Page Header Dimension';
			exit("$sql\n");
		}


	}

	function get($key) {

		

		switch ($key) {
		case('Name'):
		return $this->data[$this->table_name.' '.$key];
		break;
		default:
			if (isset($this->data[$key]))
				return $this->data[$key];
		}
		return false;
	}

	function get_preview_snapshot_date() {

		if ($this->data['Page Header Preview Snapshot Last Update']!='')
			return strftime("%a %e %b %Y %H:%M %Z", strtotime($this->data['Page Header Preview Snapshot Last Update'].' UTC')) ;




	}

	function get_preview_snapshot_src() {

if($this->data['Page Header Preview Image Key']>0)
		return sprintf("image.php?id=%d",$this->data['Page Header Preview Image Key']);
	else
		return 'art/nopic.png';

	}

	function get_preview_snapshot_image_key() {
		return $this->data['Page Header Preview Image Key'];
	}

	function update_preview_snapshot() {

		global $inikoo_public_url;
		$old_image_key=$this->data['Page Header Preview Image Key'];

		$new_image_key=$old_image_key;
		//      $image=new Image($image_key);


		$url="http://localhost/".dirname($_SERVER['PHP_SELF'])."/public_header_preview.php?id=".$this->id;


		ob_start();
		system("uname");

		$_system = ob_get_clean();



		if (preg_match('/darwin/i',$_system)) {
			$command="mantenence/scripts/webkit2png_mac.py  -C -o server_files/tmp/ph_image".$this->id."  --clipheight=80  --clipwidth=488  -s 0.5     ".$url;

			//       $command="mantenence/scripts/webkit2png  -C -o server_files/tmp/ph_image".$this->id."  --clipheight=80  --clipwidth=488  -s 0.5   http://localhost/dw/public_header_preview.php?id=".$this->id;

		}

		elseif (preg_match('/linux/i',$_system)) {
			$command='xvfb-run --server-args="-screen 0, 1280x1024x24" python mantenence/scripts/webkit2png_linux.py --log=server_files/tmp/webkit2png_linux.log -o server_files/tmp/ph_image'.$this->id.'-clipped.png -g 976 160 --scale 488 80   '.$url;



		}
		else {
			return;

		}



		ob_start();
		system($command,$retval);
		ob_get_clean();

		//print "$command  $retval";



		$image_data=array('file'=>"server_files/tmp/ph_image".$this->id."-clipped.png",'source_path'=>'','name'=>'page_header'.$this->id);
		$image=new Image('find',$image_data,'create');
		unlink("server_files/tmp/ph_image".$this->id."-clipped.png");
		if ($image->id) {
			$new_image_key=$image->id;

		}


		if ($new_image_key!=$old_image_key) {
			$this->data['Page Header Preview Image Key']=$new_image_key;
			$sql=sprintf("delete from `Image Bridge` where `Subject Type`=%s and `Subject Key`=%d and `Image Key`=%d ",
				prepare_mysql('Page Header Preview'),
				$this->id,
				$image->id
			);
			mysql_query($sql);

			$old_image=new Image($old_image_key);
			$old_image->delete();


			$sql=sprintf("insert into `Image Bridge` values (%s,%d,%d,'Yes','')",
				prepare_mysql('Page Header Preview'),
				$this->id,
				$image->id

			);
			mysql_query($sql);


			$preview_update_date=gmdate("Y-m-d H:i:s");
			$sql=sprintf("update `Page Header Dimension` set `Page Header Preview Image Key`=%d ,`Page Header Preview Snapshot Last Update`=%s where `Page Header Key`=%d",
				$this->data['Page Header Preview Image Key'],
				prepare_mysql($preview_update_date),
				$this->id

			);
			mysql_query($sql);
			print $sql;

			$this->data['Page Header Preview Snapshot Last Update']=$preview_update_date;
			$this->updated=true;
			$this->new_value=$this->data['Page Header Preview Image Key'];

		} else {

			$preview_update_date=gmdate("Y-m-d H:i:s");
			$sql=sprintf("update `Page Header Dimension`  set `Page Header Preview Snapshot Last Update`=%s  where `Page Header Key`=%d",
				prepare_mysql($preview_update_date),
				$this->id
			);
			mysql_query($sql);
			$this->data['Page Header Preview Snapshot Last Update']=$preview_update_date;
		
		

		}





	}


	function update_field_switcher($field,$value,$options='') {


		switch ($field) {

		default:
			$base_data=$this->base_data();
			if (array_key_exists($field,$base_data)) {

				if ($value!=$this->data[$field]) {

					$this->update_field($field,$value,$options);
				}
			}

		}



	}


	function get_number_pages() {
		$number_pages=0;
		$sql=sprintf("select count(*) as num from `Page Store Dimension`  where `Page Header Key`=%d and `Page Site Key`=%d",
			$this->id,
			$this->data['Site Key']

		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$number_pages=$row['num'];
		}
		return $number_pages;
	}

	function update_number_pages() {
		$this->data['Number Pages']=$this->get_number_pages();
		$sql=sprintf("update `Page Header Dimension` set `Number Pages`=%d  where `Page Header Key`=%d",
			$this->data['Number Pages'],
			$this->id
		);
		mysql_query($sql);

	}




	function delete() {

		include_once "class.Image.php";
		$this->deleted=false;
		$sql=sprintf("delete from `Page Header Dimension` where `Page Header Key`=%d",$this->id);
		mysql_query($sql);

		$images=array();
		$sql=sprintf("select `Image Key` from `Image Bridge` where `Subject Type` in ('Page Header','Page Header Preview') and `Subject Key`=%d",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$images[$row['Image Key']]=$row['Image Key'];
		}

		$sql=sprintf("select `Page Store External File Key` from `Page Header External File Bridge` where `Page Header Key`=%d",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$sql=sprintf("delete from `Page Store External File Dimension` where `Page Store External File Key`=%d ",$row['Page Store External File Key']);
			mysql_query($sql);
		}
		$sql=sprintf("delete from `Page Header External File Bridge` where `Page Header Key`=%d",$this->id);
		mysql_query($sql);


		$sql=sprintf("delete from `Image Bridge` where `Subject Type` in ('Page Header','Page Header Preview') and `Subject Key`=%d",$this->id);
		mysql_query($sql);

		foreach ($images as $image_key) {
			$image=new Image($image_key);
			if ($image->id)
				$image->delete();
		}


		$sql=sprintf("delete from `Page Header Dimension` where `Page Header Key`=%d",$this->id);
		mysql_query($sql);

		$this->deleted=true;

	}






}






?>
