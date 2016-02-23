<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 February 2016 at 20:36:41 GMT+8, Kuala Lumpur, Maysia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/
include_once 'class.DB_Table.php';


class ObjectwithImage extends DB_Table {


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
				'size'=>file_size($row['Image File Size']),
				'width'=>$row['Image Width'],
				'height'=>$row['Image Height']

			);
		}

		$this->updated=true;
		$this->msg=_("image added");
	}


	function get_images_slidesshow() {
		include_once 'utils/natural_language.php';

		if ($this->table_name=='Store Product') {
			$image_subject_type='Product';
		}else {
			$image_subject_type=$this->table_name;
		}


		$sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`=%s and   `Subject Key`=%d order by `Is Principal`",
			prepare_mysql($image_subject_type),
			$this->id
		);
		//print $sql;
		$images_slideshow=array();
		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {

				if ($row['Image Height']!=0)
					$ratio=$row['Image Width']/$row['Image Height'];
				else
					$ratio=1;
				// print_r($row);
				$images_slideshow[]=array(
					'name'=>$row['Image Filename'],
					'small_url'=>'image_root.php?id='.$row['Image Key'].'&size=small',
					'thumbnail_url'=>'image_root.php?id='.$row['Image Key'].'&size=thumbnail',
					'normal_url'=>'image_root.php?id='.$row['Image Key'],
					'filename'=>$row['Image Filename'],
					'ratio'=>$ratio, 'caption'=>$row['Image Caption'],
					'is_principal'=>$row['Is Principal'],
					'id'=>$row['Image Key'],
					'size'=>file_size($row['Image File Size']),
					'width'=>$row['Image Width'],
					'height'=>$row['Image Height']

				);

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}





		return $images_slideshow;
	}


}


?>
