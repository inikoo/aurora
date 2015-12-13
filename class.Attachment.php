<?php
/*
 File: Attachment.php

 This file contains the Attachment Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';


class Attachment extends DB_Table {
	var $locations=false;
	var $compress=true;
	function Attachment($arg1=false, $arg2=false, $arg3=false) {

		global $db;
		$this->db=$db;

		$this->table_name='Attachment';
		$this->ignore_fields=array('Attachment Key');

		if (preg_match('/^(new|create)$/i', $arg1) and is_array($arg2)) {
			$this->create($arg2);
			return;
		}

		if (preg_match('/find/i', $arg1)) {
			$this->find($arg2, $arg3);
			return;
		}
		if (is_numeric($arg1)) {
			$this->get_data('id', $arg1);
			return;
		}
		$this->get_data($arg1, $arg2);
	}



	function find($raw_data, $options) {

		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key, $this->editor))
					$this->editor[$key]=$value;

			}
		}


		$this->found=false;
		$create='';
		$update='';
		if (preg_match('/create/i', $options)) {
			$create='create';
		}
		if (preg_match('/update/i', $options)) {
			$update='update';
		}


		if (isset($raw_data['file']) and $raw_data['file']!='') {
			$file=$raw_data['file'];
			$checksum=md5_file($file);


			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime=finfo_file($finfo, $file);
			finfo_close($finfo);
			if ($mime=='unknown' and (isset($raw_data['Attachment MIME Type']) and $raw_data['Attachment MIME Type']!=''))
				$mime="unknown (".$raw_data['Attachment MIME Type'].")";
			$filesize=filesize($file);
			$extension= $this->find_extension($file);

			$raw_data['Attachment MIME Type']=$mime;
			$raw_data['Attachment File Checksum']=$checksum;
			$raw_data['Attachment File Size']=$filesize;



		}


		$data=$this->base_data();
		foreach ($raw_data as $key=>$val) {
			$_key=$key;
			$data[$_key]=$val;
		}




		$sql=sprintf("select `Attachment Key` from `Attachment Dimension` where `Attachment File Checksum`=%s"
			, prepare_mysql($data['Attachment File Checksum'])
		);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$this->found=true;
			$this->found_key=$row['Attachment Key'];
		}

		//what to do if found
		if ($this->found) {
			$this->get_data('id', $this->found_key);
			$this->found=true;
			return;
		}


		if ($create) {

			$this->create($data, $options);

		}



	}


	function create($data, $options='') {

		$this->data=$this->base_data();
		foreach ($data as $key=>$value) {
			if (array_key_exists($key, $this->data))
				$this->data[$key]=_trim($value);
		}


		$filename= $data['file'];

		$this->data['Attachment Data'] = addslashes(fread(fopen($filename, "r"), filesize($filename)));


		$keys='(';
		$values='values(';
		foreach ($this->data as $key=>$value) {

			$keys.="`$key`,";

			if ($key=='Attachment Data') {
				$values.="'".$value."',";
			} else {
				$values.=prepare_mysql($value).",";
			}



		}

		$keys=preg_replace('/,$/', ')', $keys);
		$values=preg_replace('/,$/', ')', $values);



		$sql=sprintf("insert into `Attachment Dimension` %s %s", $keys, $values);

		// exit;
		if (mysql_query($sql)) {
			$this->id= mysql_insert_id();
			$this->new=true;
			$this->get_data('id', $this->id);

			$this->update_type();
			$this->create_thumbnail();


		} else {
			$error=mysql_error();
			if (preg_match('/max_allowed_packet/i', $error)) {
				$this->msg="Got a packet bigger than 'max_allowed_packet' bytes ";
			} else {
				$this->msg='Unknown error';

			}
			$this->error=true;
		}

	}


	function get_data($key, $tag) {

		if ($key=='id') {
			$sql=sprintf("select * from `Attachment Dimension` where `Attachment Key`=%d", $tag);

		}elseif ($key=='bridge_key') {
			$sql=sprintf("select * from `Attachment Bridge` B left join  `Attachment Dimension` A on (A.`Attachment Key`= B.`Attachment Key`) where `Attachment Bridge Key`=%d", $tag);
		}else
			return;

		if ($this->data = $this->db->query($sql)->fetch()) {
			$this->id=$this->data['Attachment Key'];
		}

	}


	function get_subject_data($bridge_key) {

		$sql=sprintf("select * from `Attachment Bridge` where `Attachment Bridge Key`=%d and `Attachment Key`=%d", $bridge_key, $this->id);


		if ($row = $this->db->query($sql)->fetch()) {

			foreach ($row as $key=>$value) {
				$this->data[$key]=$value;
			}
		}
	}


	function get_abstract($original_name='', $caption='', $reference=false) {

		if (!$reference) {
			$reference_type='id';
			$reference_key=$this->id;
		}else {
			$reference_type='bid';
			$reference_key=$reference;
		}

		$mime=$this->mime_type_icon($this->data['Attachment MIME Type']);
		return sprintf('%s <a href="file.php?%s=%d">%s</a> (%s) %s'
			, $mime
			, $reference_type
			, $reference_key
			, $original_name

			, file_size($this->data['Attachment File Size'])
			, $caption
		);
	}


	function get_details() {
		return '';
	}






	function get($key, $data=false) {
		switch ($key) {


		case 'Preview':

			return sprintf('/attachment_preview.php?id=%d', $this->get('Attachment Bridge Key'));

		case 'Public':
			if ($this->data['Attachment Public']=='Yes')
				return _('Yes');
			else
				return _('No');

			break;
		case 'Public Info':

			if ($this->get('Subject')=='Staff') {
				if ($this->data['Attachment Public']=='Yes')
					$visibility=sprintf('<i title="%s" class="fa fa-eye"></i> %s', _('Public'), _('Employee can see file'));
				else
					$visibility=sprintf('<span class="error" > <i title="%s" class="fa fa-eye-slash"></i> %s</span>',
						_('Private'), _('Top secret file'));
			}else {
				if ($this->data['Attachment Public']=='Yes')
					$visibility=sprintf('<i title="%s" class="fa fa-eye"></i> %s', _('Public'), _('Public'));
				else
					$visibility=sprintf('<i title="%s" class="fa fa-eye-slash"></i> %s', _('Private'), _('Private'));

			}
			return $visibility;

			break;


		case 'Subject Type':
			switch ($this->data['Attachment Subject Type']) {
			case 'Contract':
				$type=_('Employment contract');
				break;
			case 'CV':
				$type=_('Curriculum vitae');
				break;
			default:
				$type=_('Other');
				break;
			}
			return $type;
			break;
		case 'File Size':
			include_once 'utils/natural_language.php';
			return file_size($this->data['Attachment File Size']);
			break;
		case 'Type':
			switch ($this->data['Attachment Type']) {
			case 'PDF':
				$file_type=sprintf('<i title="%s" class="fa fa-fw fa-file-pdf-o"></i> %s', $this->data['Attachment MIME Type'], 'PDF');

				break;
			case 'Image':
				$file_type=sprintf('<i title="%s" class="fa fa-fw fa-picture-o"></i> %s', $this->data['Attachment MIME Type'], _('Image'));
				break;
			case 'Compresed':
				$file_type=sprintf('<i title="%s" class="fa fa-fw fa-file-archive-o"></i> %s', $this->data['Attachment MIME Type'], _('Compresed'));
				break;
			case 'Spreadsheet':
				$file_type=sprintf('<i title="%s" class="fa fa-fw fa-table"></i> %s', $this->data['Attachment MIME Type'], _('Spreadsheet'));
				break;
			case 'Text':
				$file_type=sprintf('<i title="%s" class="fa fa-fw fa-file-text-o"></i> %s', $this->data['Attachment MIME Type'], _('Text'));
				break;
			case 'Word':
				$file_type=sprintf('<i title="%s" class="fa fa-fw fa-file-word-o"></i> %s', $this->data['Attachment MIME Type'], 'Word');
				break;
			default:
				$file_type=sprintf('<i title="%s" class="fa fa-fw fa-file-o"></i> %s', $this->data['Attachment MIME Type'], _('Other'));
				break;
			}

			return $file_type;
			break;
		default:
			if (array_key_exists($key, $this->data))
				return $this->data[$key];

			if (array_key_exists('Attachment '.$key, $this->data))
				return $this->data['Attachment '.$key];
		}



		return '';
	}





	function find_extension($filename) {
		$filename = strtolower($filename) ;
		$exts = preg_split("/\.[a-z]$/i", $filename) ;
		$n = count($exts)-1;
		$exts = $exts[$n];
		return $exts;
	}


	function uncompress($srcName, $dstName) {
		$string = implode("", gzfile($srcName));
		$fp = fopen($dstName, "w");
		fwrite($fp, $string, strlen($string));
		fclose($fp);
	}


	function compress($srcName, $dstName) {
		$fp = fopen($srcName, "r");
		$data = fread($fp, filesize($srcName));
		fclose($fp);

		$zp = gzopen($dstName, "w9");
		gzwrite($zp, $data);
		gzclose($zp);
	}


	function mime_type_icon($mime_type) {
		if (preg_match('/^image/', $mime_type)) {
			return '<img src="art/icons/page_white_picture.png" alt="'.$mime_type.'" title="'.$mime_type.'" />';
		}
		elseif (preg_match('/excel/', $mime_type)) {
			return '<img src="art/icons/page_white_excel.png" alt="'.$mime_type.'" title="'.$mime_type.'"/>';
		}
		elseif (preg_match('/msword/', $mime_type)) {
			return '<img src="art/icons/page_white_word.png" alt="'.$mime_type.'" title="'.$mime_type.'"/>';
		}elseif (preg_match('/pdf/', $mime_type)) {
			return '<img src="art/icons/page_white_acrobat.png" alt="'.$mime_type.'" title="'.$mime_type.'"/>';
		}elseif (preg_match('/(zip|rar)/', $mime_type)) {
			return '<img src="art/icons/page_white_compressed.png" alt="'.$mime_type.'" title="'.$mime_type.'"/>';
		}elseif (preg_match('/(text)/', $mime_type)) {
			return '<img src="art/icons/page_white_text.png" alt="'.$mime_type.'" title="'.$mime_type.'"/>';
		}

		else
			return $mime_type;
	}


	function get_subjects() {
		$subjects=array();
		$sql=sprintf('select * from `Attachment Bridge` where `Attachment Key`=%d', $this->id);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			$subjects[]=$row;
		}
		return $subjects;
	}


	function delete($force=false) {
		$subjects=$this->get_subjects();
		$num_subjects=count($subjects);
		if ($num_subjects==0 or $force) {
			$sql=sprintf("delete from `Attachment Dimension` where `Attachment Key`=%d", $this->id);

			mysql_query($sql);
			$sql=sprintf("delete from `Attachment Bridge` where `Attachment Key`=%d", $this->id);
			mysql_query($sql);
			$this->deleted=true;
		}
	}


	function update_type() {
		$type='Other';
		if (preg_match('/^image/', $this->data['Attachment MIME Type'])) {
			$type='Image';
		}elseif (preg_match('/excel/', $this->data['Attachment MIME Type'])) {
			$type='Spreadsheet';
		}elseif (preg_match('/msword/', $this->data['Attachment MIME Type'])) {
			$type='Word';
		}elseif (preg_match('/pdf/', $this->data['Attachment MIME Type'])) {
			$type='PDF';
		}elseif (preg_match('/(zip|rar)/', $this->data['Attachment MIME Type'])) {
			$type='Compresed';
		}elseif (preg_match('/(text)/', $this->data['Attachment MIME Type'])) {
			$type='Text';
		}

		$sql=sprintf("update `Attachment Dimension` set `Attachment Type`=%s where `Attachment Key`=%d",
			prepare_mysql($type),
			$this->id
		);
		mysql_query($sql);
		$this->data['Attachment Type']=$type;

	}


	function create_thumbnail() {
		include_once 'class.Image.php';
		if (preg_match('/application\/pdf/', $this->data['Attachment MIME Type'])) {
			$tmp_file='server_files/tmp/attch'.date('U').$this->data['Attachment File Checksum'];


			$tmp_file_name=$tmp_file.'.pdf';
			file_put_contents( $tmp_file_name, $this->data['Attachment Data']);

			$im = new imagick( $tmp_file_name.'[0]');



		}
		elseif (preg_match('/image\/(png|jpg|gif|jpeg)/', $this->data['Attachment MIME Type'])) {

			$tmp_file='server_files/tmp/attch'.date('U').$this->data['Attachment File Checksum'];
			$tmp_file_name=$tmp_file;
			file_put_contents( $tmp_file_name, $this->data['Attachment Data']);
			$im = new imagick( $tmp_file_name);



		}else {
			return;
		}



		$im->setImageFormat('jpg');
		$im->thumbnailImage(500, 0);
		$im->writeImage ($tmp_file.'.jpg');


		$image_data=array(
			'file'=>$tmp_file.'.jpg',
			'source_path'=>'',
			'name'=>'attachment_thumbnail',
			'caption'=>''
		);



		$image=new Image('find', $image_data, 'create');

		if (!$image->error) {


			$sql=sprintf("delete from `Image Bridge` where `Subject Type`=%s and `Subject Key`=%d",
				prepare_mysql('Attachment Thumbnail'),
				$this->id

			);
			mysql_query($sql);

			$sql=sprintf("insert into `Image Bridge` (`Subject Type`,`Subject Key`,`Image Key`,`Is Principal`,`Caption`) values (%s,%d,%d,'Yes','')",
				prepare_mysql('Attachment Thumbnail'),
				$this->id,
				$image->id
			);
			mysql_query($sql);

			$sql=sprintf("update `Attachment Dimension` set `Attachment Thumbnail Image Key`=%d where `Attachment Key`=%d",
				$image->id,
				$this->id
			);
			mysql_query($sql);
			$this->data['Attachment Thumbnail Image Key']=$image->id;
		}else {


		}

		unlink( $tmp_file_name);
		unlink($tmp_file.'.jpg');









	}


	function set_subject($subject) {
		$this->data['Subject']=$subject;
	}


	function get_field_label($field) {

		switch ($field) {
		case 'Attachment Subject Type':
			$label=_('Content type');
			break;
		case 'Attachment Caption':
			$label=_('Short description');
			break;

		case 'Attachment Public':
			if ($this->get('Subject')=='Staff') {
				$label=_('Employee can see file');
			}else {
				$label=_('Public');
			}
			break;
		case 'Attachment File':
			$label=_('File');
			break;
		case 'Attachment File Original Name':
			$label=_('File name');
			break;
		case 'Attachment File Size':
			$label=_('File size');
			break;
		case 'Attachment Preview':
			$label=_('Preview');
			break;
		default:
			$label=$field;
			break;
		}

		return $label;
	}


	function update_field_switcher($field, $value, $options='') {
		if (is_string($value))
			$value=_trim($value);



		switch ($field) {
		case 'Attachment Caption':
		case 'Attachment Subject Type':
		case 'Attachment Public':
			$this->update_table_field($field, $value, $options, 'Attachment Bridge', 'Attachment Bridge', $this->get('Attachment Bridge Key'));

			if ($field=='Attachment Public') {
				$this->other_fields_updated=array(
					'Public_Info'=>array(
						'field'=>'Public_Info',
						'render'=>true,
						'value'=>$this->get('Public_Info'),
						'formated_value'=>$this->get('Public Info'),


					)
				);

			}

			break;
		default:
			$base_data=$this->base_data();
			if (array_key_exists($field, $base_data)) {
				$this->update_field($field, $value, $options);
			}
		}
		$bridge_key=$this->get('Attachment Bridge Key');
		$this->reread();

		$this->get_subject_data($bridge_key);

	}


}


?>
