<?php
/*
  File: class.Image.php

  This file contains the Image Class

  About:
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/



class Image {

	var $id = false;
	var $im = "";
	var $resized_im = "";
	var $im_x = 0;
	var $im_y = 0;
	var $jpgCompression=90;
	var $msg='';
	var $new=false;
	var $deleted=false;
	var $found_key=0;
	var $delete_source_file=false;


	public $editor=array(
		'Author Name'=>false,
		'Author Alias'=>false,
		'Author Key'=>0,
		'User Key'=>0,
		'Date'=>false
	);


	function Image($a1, $a2=false, $a3=false) {
		global $db;
		$this->db=$db;

		$this->tmp_path='server_files/tmp/';
		$this->found=false;
		$this->error=false;
		$this->thumbnail_size=array(25, 20);
		$this->small_size=array(320, 280);
		$this->large_size=array(800, 600);
		if (is_numeric($a1) and !$a2) {

			$this->get_data('id', $a1);
		} else if (($a1=='new' or $a1=='create') and is_string($a2) ) {
			$this->find($a2, 'create');
		}
		elseif ($a1=='find') {
			$this->find($a2, $a3);

		}
		else
			$this->get_data($a1, $a2);
	}



	function get_data($tipo='id', $id) {
		if ($tipo=='id') {


			$sql=sprintf("select `Image Key`,`Image Data`,`Image Thumbnail Data`,`Image Small Data`,`Image Large Data`,`Image Filename`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Size`,`Image File Format` from `Image Dimension` where `Image Key`=%d ",
				$id
			);

			if ($this->data = $this->db->query($sql)->fetch()) {
				$this->id=$this->data['Image Key'];

			}
		}elseif ($tipo=='image_bridge_key') {
			$sql=sprintf("select * from `Image Subject Bridge` where `Image Subject Key`=%d ", $id);

			if ($this->data = $this->db->query($sql)->fetch()) {
				$this->id=$this->data['Image Subject Image Key'];
				if ($this->id) {
					$sql=sprintf("select `Image Key`,`Image Data`,`Image Thumbnail Data`,`Image Small Data`,`Image Large Data`,`Image Filename`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Size`,`Image File Format` from `Image Dimension` where `Image Key`=%d ",
						$this->id
					);

					if ($row = $this->db->query($sql)->fetch()) {

						foreach ($row as $key=>$value) {
							$this->data[$key]=$value;
						}
					}else{
					
					    $this->id=0;
					}


				}


			}



		}


	}





	function find($raw_data, $options) {



		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key, $this->editor))
					$this->editor[$key]=$value;

			}
			unset($raw_data['editor']);
		}






		if (preg_match('/\.\.\//', $raw_data['upload_data']['tmp_name'])) {
			$this->error=true;
			$this->msg=_('Invalid filename, return paths forbiden');
			return;
		}




		$create='';

		if (preg_match('/create/i', $options)) {
			$create='create';
		}



		if (!is_file($raw_data['upload_data']['tmp_name'])) {
			$this->error=true;
			$this->msg=_('No image file').' ('.$raw_data['upload_data']['tmp_name'].')';
			return;
		}


		$raw_data['Image File Checksum']=md5_file($raw_data['upload_data']['tmp_name']);




		$sql=sprintf("select `Image Key` from `Image Dimension` where `Image File Checksum`=%s",
			prepare_mysql($raw_data['Image File Checksum'])

		);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->found=true;
				$this->found_key=$row['Image Key'];
				$this->get_data('id', $this->found_key);
			}
		}else {
			print_r($error_info=$this->db->errorInfo()); print "$sql";
			exit;
		}

		if (!$this->found and $create) {
			$this->create($raw_data);

		}


	}




	function create($data) {

		$tmp_file=$data['upload_data']['tmp_name'];
		unset($data['upload_data']);
		$data['Image File Size']=filesize($tmp_file);

		$data['Image File Format']=$this->guess_file_format($tmp_file);
		$im=$this->get_image_from_file($data['Image File Format'], $tmp_file);
		if (!$im) {
			return;
		}


		$data['Image Width']=imagesx($im);
		$data['Image Height']=imagesy($im);

		unset($data['upload_data']);


		$data['Image Data']=$this->get_image_blob($im, $data['Image File Format']);




		$keys='(';
		$values='values(';
		foreach ($data as $key=>$value) {
			$keys.="`$key`,";
			if ($key=='Image Data')
				$values.="'".addslashes($value)."',";

			else
				$values.=prepare_mysql($value).",";
		}
		$keys=preg_replace('/,$/', ')', $keys);
		$values=preg_replace('/,$/', ')', $values);
		$sql=sprintf("insert into `Image Dimension` %s %s", $keys, $values);

		if ($this->db->exec($sql)) {
			$this->id=$this->db->lastInsertId();
			$this->im=$im;

			$this->new=true;
			$this->get_data('id', $this->id);





		} else {
			$this->error=true;
			$this->msg='Can not insert the image ';
			return;
		}



		if ($this->delete_source_file)
			unlink($tmp_file);


		$this->create_other_size_data();

		$sql=sprintf("update `Image Dimension` set `Last Modify Date`=NOW() where `Image Key`=%d ", $this->id);
		$this->db->exec($sql);


	}


	// scale the image constraining proportions (maxX and maxY)

	function create_thumbnail() {

		if ($this->data['Image Thumbnail Data']!='')
			return;

		$thumbnail_im= $this->transformToFit($this->thumbnail_size[0], $this->thumbnail_size[1]);
		if ($this->error) {
			$this->msg=_('Can not resize image');
			return;
		}


		$image_blob=$this->get_image_blob($thumbnail_im);
		$sql=sprintf("update `Image Dimension` set `Image Thumbnail Data`='%s' where `Image Key`=%d ", addslashes($image_blob), $this->id);

		$this->db->exec($sql);
		$this->data['Image Thumbnail Data']=$image_blob;
	}






	function get_image_from_file($format, $srcImage) {


		if ($format=='jpeg') {
			$im = imagecreatefromjpeg($srcImage);
		}
		elseif ($format=='png') {
			$im = imagecreatefrompng($srcImage);
			imagealphablending($im, true);
			imagesavealpha($im, true);
		}
		elseif ($format=='gif') {
			$im = imagecreatefromgif($srcImage);
		}
		elseif ($format=='wbmp') {
			$im = imagecreatefromwbmp($srcImage);
		}
		elseif ($format=='psd') {
			include_once 'class.PSD.php';
			$im = imagecreatefrompsd($srcImage);
		}
		else {
			$this->error=true;
			$this->msg=_('File format not supported')." ($format)";
			return false;
		}

		if (!$im) {
			$this->error=true;
			$this->msg=_('Can not read image');;
			return false;
		}

		return $im;

	}



	function get_image_from_string($str) {

		// $str can be `Image Data Dimension` value in `Image Dimension`

		return imagecreatefromstring(base64_decode($row['Image Data']));


	}







	function saveImage($im, $destImage) {

		if ($this->data['Image File Format']=='jpeg' or $this->data['Image File Format']=='psd' ) {
			imagejpeg($im, $destImage , $this->jpgCompression);

		}
		elseif ($this->data['Image File Format']=='png' or $this->data['Image File Format']=='wbmp')
			imagepng($im, $destImage);
		elseif ($this->data['Image File Format']=='gif')
			imagegif($im, $destImage);
	}


	function get_image_blob( $im, $format='') {

		if (!$format) {
			$format=$this->data['Image File Format'];
		}

		ob_start();
		if ($format=='jpeg' or $format=='psd' ) {
			imagejpeg($im, NULL, $this->jpgCompression);

		}
		elseif ($format=='png' or $format=='wbmp')
			imagepng($im);
		elseif ($format=='gif')
			imagegif($im);

		$image_data = ob_get_contents();
		ob_end_clean();

		return $image_data;

	}



	function setCompression($val=70) {
		if ($val>0 && $val<10) {
			$val=10*$val;
		}
		elseif ($val>100) {
			$val=100;
		}
		elseif ($val<0) {
			$val=0;
		}
		$this->jpgCompression=$val;
	}


	function resizeImage($width, $height) {
		$dst_img    = imagecreatetruecolor($width, $height);
		imagecopyresampled($dst_img, $this->im, 0, 0, 0, 0, $width+1, $height+1, $this->data['Image Width'], $this->data['Image Height']);
		return $dst_img;
	}




	// scale the image constraining proportions (maxX and maxY)

	function create_small() {

		if ($this->data['Image Small Data']!='')
			return;

		if ($this->data['Image Width']<320 or $this->data['Image Height']<280) {
			$sql=sprintf("update `Image Dimension` set `Image Small Data`=NULL where `Image Key`=%d",
				$this->id
			);
			$this->db->exec($sql);
			return;
		}

		$small_im=$this->transformToFit(320, 280);
		if ($this->error) {
			$this->msg=_('Can not resize image');
			return;
		}



		$image_blob=$this->get_image_blob($small_im);

		$sql=sprintf("update `Image Dimension` set `Image Small Data`='%s' where `Image Key`=%d"
			, addslashes($image_blob)
			, $this->id
		);
		$this->db->exec($sql);
		$this->data['Image Small Data']=$image_blob;

	}




	function create_large() {

		if ($this->data['Image Large Data']!='')
			return;


		if ($this->data['Image Width']<800 or $this->data['Image Height']<600) {
			$sql=sprintf("update `Image Dimension` set `Image Large Data`=NULL where `Image Key`=%d",
				$this->id
			);
			$this->db->exec($sql);
			return;
		}

		$large_im=$this->transformToFit(800, 600);
		if ($this->error) {
			$this->msg=_('Can not resize image');
			return;
		}

		$image_blob=$this->get_image_blob($large_im);

		$sql=sprintf("update `Image Dimension` set `Image Large Data`='%s' where `Image Key`=%d"
			, addslashes($image_blob)
			, $this->id
		);
		$this->db->exec($sql);
		$this->data['Image Large Data']=$image_blob;

	}


	// scale the image constraining proportions (maxX and maxY)
	function transformToFit($newX, $newY) {
		$x=$this->data['Image Width'];
		$y=$this->data['Image Height'];
		if ($x==0) {
			$this->error=true;
			$this->msg='image width is zero';
			return;
		}

		$mlt=$newX/$x;
		$nx=ceil($x * $mlt);
		$ny=ceil($y * $mlt);

		if ($ny>$newY) {
			$mlt=$newY/$ny;
			$nx=ceil($nx * $mlt);
			$ny=ceil($ny * $mlt);
		}

		return $this->resizeImage($nx, $ny);
	}


	// speaks for itself
	function strokeImage($strokeWidth, $strokeColor="000000") {
		$code = $this->colordecode($strokeColor);
		$width = imagesx($this->im);
		$height = imagesy($this->im);
		$color = imagecolorallocate($this->im, $code[r], $code[g], $code[b]);
		if ($strokeWidth>1) {
			for ($i=0; $i<$strokeWidth; $i++) {
				imagerectangle($this->im, $i, $i, $width-($i+1), $height-($i+1), $color);
			}
		} else {
			imagerectangle($this->im, 0, 0, $width-1, $height-1, $color);
		}
	}


	function colordecode($hex) {
		$code[r] = hexdec(substr($hex, 0 , 2));
		$code[g] = hexdec(substr($hex, 2 , 2));
		$code[b] = hexdec(substr($hex, 4 , 2));
		return $code;
	}



	function get_subjects() {
		$subjects=array();
		$sql=sprintf('select `Image Subject Object`,`Image Subject Is Principal`,`Image Subject Object Key` from `Image Subject Bridge` where `Image Subject Key`=%d', $this->id);

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$subjects[]=array(
					'Subject Type'=>$row['Image Subject Object'],
					'Subject Key'=>$row['Image Subject Object Key'],
					'Is Principal'=>$row['Image Subject Object Key Is Principal']
				);

			}
		}else {
			print_r($error_info=$this->db->errorInfo()); print "$sql";
			exit;
		}


		return $subjects;
	}


	function get_subjects_types($result_type='array') {
		$subject_types=array();
		$sql=sprintf('select `Image Subject Type` from `Image Subject Bridge` where `Image Subject Key`=%d ', $this->id);

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$subject_types[$row['Subject Type']]=$row['Image Subject Type'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo()); print "$sql";
			exit;
		}




		if ($result_type=='array')
			return $subject_types;
		else
			return implode(",", $subject_types);

	}



	function update_other_size_data() {
		$subjects=$this->get_subjects_types('string');
		if ($subjects=='Page' or $subjects=='') {
			$this->remove_other_sizes_data();

		}else {
			$this->create_other_size_data();
		}

	}


	function create_other_size_data() {
		$this->create_thumbnail();
		$this->create_small();
		//$this->create_large();
	}


	function remove_other_sizes_data() {
		$sql=sprintf("update `Image Dimension` set `Image Small Data`=NULL,`Image Thumbnail Data`=NULL,`Image Large Data`=NULL where `Image Key`=%d ",
			$this->id
		);
		$this->db->exec($sql);
	}


	function delete($force=false) {
		$subjects=$this->get_subjects();
		$num_subjects=count($subjects);
		if ($num_subjects==0 or $force) {
			$sql=sprintf("delete from `Image Dimension` where `Image Key`=%d", $this->id);
			// print $sql;
			$this->db->exec($sql);
			$sql=sprintf("delete from `Image Bridge` where `Image Key`=%d", $this->id);
			$this->db->exec($sql);
			$this->deleted=true;
		}
	}


	function get_url() {
		return "image.php?id=".$this->id;
	}


	function guess_file_format($filename) {

		$mimetype='Unknown';


		ob_start();
		system("uname");
		$system='Unknown';
		$_system = ob_get_clean();

		// print "S:$system M:$mimetype\n";

		if (preg_match('/darwin/i', $_system)) {
			ob_start();
			$system='Mac';
			system('file -I "'.addslashes($filename).'"');
			$mimetype=ob_get_clean();
			$mimetype=preg_replace('/^.*\:/', '', $mimetype);

		}
		elseif (preg_match('/linux/i', $_system)) {
			ob_start();
			$system='Linux';
			$mimetype = system('file -ib "'.addslashes($filename).'"');
			$mimetype=ob_get_clean();
		}
		else {
			$system='Other';

		}


		//print "** $filename **";

		if (preg_match('/png/i', $mimetype))
			$format='png';
		else if (preg_match('/jpeg/i', $mimetype))
			$format='jpeg';
		else if (preg_match('/image.psd/i', $mimetype))
			$format='psd';
		else if (preg_match('/gif/i', $mimetype))
			$format='gif';
		else if (preg_match('/wbmp$/i', $mimetype))
			$format='wbmp';

		else {
			$format='other';
		}
		//  print "S:$system M:$mimetype\n";
		// return;

		return $format;

	}

function get($key) {


		if (!$this->id)
			return;

		switch ($key) {
	

		default:
			if (array_key_exists($key, $this->data))
				return $this->data[$key];

			if (array_key_exists('Image '.$key, $this->data))
				return $this->data['Image '.$key];

		}


	}

}
