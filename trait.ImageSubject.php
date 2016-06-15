<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 February 2016 at 20:36:41 GMT+8, Kuala Lumpur, Maysia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/


trait ImageSubject {



	function add_image($raw_data) {

		include_once 'utils/units_functions.php';




		$data=array(
			'Image Width' => 0,
			'Image Height' =>  0,
			'Image File Size'=> 0,
			'Image File Checksum'=>'',
			'Image Filename'=>$raw_data['Image Filename'],
			'Image File Format'=>'',
			'Image Data'=>'',
			'upload_data'=>$raw_data['Upload Data'],
			'editor'=>$this->editor
		);


		$image=new Image('find', $data, 'create');

		if ($image->id) {
			$subject_key=$this->id;
			$subject=$this->table_name;
			$sql=sprintf("select `Image Subject Image Key`,`Image Subject Is Principal` from `Image Subject Bridge` where `Image Subject Object`=%s and `Image Subject Object Key`=%d  and `Image Subject Image Key`=%d",
				prepare_mysql($subject),
				$subject_key,
				$image->id);

			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$this->nochange=true;
					$this->msg=_('Image already uploaded');
					return;
				}
			}else {
				print_r($error_info=$this->db->errorInfo()); print "$sql";
				exit;
			}

			$number_images=$this->get_number_images();
			if ($number_images==0) {
				$principal='Yes';
			} else {
				$principal='No';
			}

			$sql=sprintf("insert into `Image Subject Bridge` (`Image Subject Object`,`Image Subject Object Key`,`Image Subject Image Key`,`Image Subject Is Principal`,`Image Subject Image Caption`,`Image Subject Date`,`Image Subject Order`) values (%s,%d,%d,%s,'',%s,%d)",
				prepare_mysql($subject),
				$subject_key,
				$image->id,
				prepare_mysql($principal),
				prepare_mysql(gmdate('Y-m-d H:i:s')),
				($number_images+1)

			);

			$this->db->exec($sql);


			$this->reindex_order();

			if ($principal=='Yes') {
				$this->update_main_image($image->id);
				if ($this->error) {
					return;
				}
			}


			$sql=sprintf("select `Image Subject Is Principal`,`Image Key`,`Image Subject Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Subject Bridge` B left join `Image Dimension` ID on (`Image Key`=`Image Subject Image Key`) where `Image Subject Object`=%s and `Image Subject Object Key`=%d and  `Image Key`=%d",
				prepare_mysql($subject),
				$subject_key,
				$image->id
			);


			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {

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
						'caption'=>$row['Image Subject Image Caption'],
						'is_principal'=>$row['Image Subject Is Principal'],
						'id'=>$row['Image Key'],
						'size'=>file_size($row['Image File Size']),
						'width'=>$row['Image Width'],
						'height'=>$row['Image Height']

					);


				}
			}else {
				print_r($error_info=$this->db->errorInfo()); print "$sql";
				exit;
			}



			$this->updated=true;
			$this->msg=_("Image added");
			return $image;
		}else {
			$this->error=true;
			$this->msg="Can't create/found image, ".$image->msg;
			return false;
		}

	}


	function get_images_slidesshow() {
		include_once 'utils/natural_language.php';

		if ($this->table_name=='Store Product') {
			$image_subject_type='Product';
		}else {
			$image_subject_type=$this->table_name;
		}


		$sql=sprintf("select `Image Subject Is Principal`,`Image Key`,`Image Subject Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Subject Bridge` B left join `Image Dimension` I on (`Image Subject Image Key`=`Image Key`) where `Image Subject Object`=%s and   `Image Subject Object Key`=%d order by `Image Subject Is Principal`,`Image Subject Date`,`Image Subject Key`",
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
					'ratio'=>$ratio, 'caption'=>$row['Image Subject Image Caption'],
					'is_principal'=>$row['Image Subject Is Principal'],
					'id'=>$row['Image Key'],
					'size'=>file_size($row['Image File Size']),
					'width'=>$row['Image Width'],
					'height'=>$row['Image Height']

				);

			}
		}else {
			print_r($error_info=$this->db->errorInfo()); print "$sql";
			exit;
		}





		return $images_slideshow;
	}


	function get_number_images() {

		$subject=$this->table_name;

		$number_of_images=0;
		$sql=sprintf("select count(*) as num from `Image Subject Bridge` where `Image Subject Object`=%s and `Image Subject Object Key`=%d ",
			prepare_mysql($subject),
			$this->id
		);
		//print $sql;


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$number_of_images=$row['num'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo()); print "$sql";
			exit;
		}


		return $number_of_images;
	}


	function get_main_image_key() {

		$image_key=false;

		$subject=$this->table_name;

		$sql=sprintf("select `Image Subject Image Key` from `Image Subject Bridge` where `Image Subject Object`=%s and `Image Subject Object Key`=%d  ORDER BY FIELD(`Image Subject Is Principal`, 'Yes','No') limit 1",
			prepare_mysql($subject),
			$this->id

		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$image_key=$row['Image Subject Image Key'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo()); print "$sql";
			exit;
		}


		return $image_key;

	}

	function update_main_image($image_key) {


		$subject=$this->table_name;


		if ($image_key) {

			$sql=sprintf("select `Image Subject Key` from `Image Subject Bridge` where `Image Subject Object`=%s and `Image Subject Object Key`=%d  and `Image Subject Image Key`=%d",
				prepare_mysql($subject),
				$this->id,
				$image_key
			);



			if ($result=$this->db->query($sql)) {
				if (!$row = $result->fetch()) {
					$this->error=true;
					$this->msg='image not associated';
					return;
				}
			}else {
				print_r($error_info=$this->db->errorInfo()); print "$sql";
				exit;
			}





			$sql=sprintf("update `Image Subject Bridge` set `Image Subject Is Principal`='No' where `Image Subject Object`=%s and `Image Subject Object Key`=%d  ",
				prepare_mysql($subject),
				$this->id
			);
			$this->db->exec($sql);
			$sql=sprintf("update `Image Subject Bridge` set `Image Subject Is Principal`='Yes' where `Image Subject Object`=%s and `Image Subject Object Key`=%d  and `Image Key`=%d",
				prepare_mysql($subject),
				$this->id,
				$image_key
			);
			$this->db->exec($sql);


			$main_image_src='image_root.php?id='.$image_key.'&size=small';
			$main_image_key=$image_key;

		}else {
			$main_image_src='/art/nopic.png';
			$main_image_key=0;
		}

		$this->data['Product Main Image']=$main_image_src;
		$this->data['Product Main Image Key']=$main_image_key;
		$sql=sprintf("update `%s Dimension` set `%s Main Image`=%s ,`%s Main Image Key`=%d where `%s Key`=%d",
			addslashes($this->table_name),
			addslashes($this->table_name),
			prepare_mysql($main_image_src),
			addslashes($this->table_name),
			$main_image_key,
			addslashes($this->table_name),
			$this->id
		);

		$this->db->exec($sql);


		/*
		$page_keys=$this->get_pages_keys();
		foreach ($page_keys as $page_key) {
			$page=new Page($page_key);
			$page->update_image_key();
		}
        */
		$this->updated=true;

	}


	function delete_image($image_bridge_key) {

		$sql=sprintf('select `Image Subject Key` from `Image Subject Bridge` where `Image Subject Key`=%d ', $image_bridge_key);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {



				$sql=sprintf('delete from `Image Subject Bridge` where `Image Subject Key`=%d ', $image_bridge_key);
				$this->db->exec($sql);

				$image=new Image($row['Image Subject Key']);
				$image->editor=$this->editor;

				$image->delete();
				$order_index=$this->reindex_order();

				$this->update_main_image(array_shift($order_index));

			}else {
				$this->error;
				$this->msg=_('Image not found');
			}
		}else {
			print_r($error_info=$this->db->errorInfo()); print "$sql";
			exit;
		}


	}


	function reindex_order() {

		$order_index=array();

		$subject=$this->table_name;
		$sql=sprintf("select `Image Subject Key` from `Image Subject Bridge` where `Image Subject Object`=%s and   `Image Subject Object Key`=%d order by `Image Subject Order`,`Image Subject Date`,`Image Subject Key`",
			prepare_mysql($subject),
			$this->id
		);
		//print $sql;
		$order=1;
		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {

				$sql=sprintf("update `Image Subject Bridge` set `Image Subject Order`=%d where `Image Subject Key`=%d ",
					$order,
					$row['Image Subject Key']
				);
				$order_index[]=$row['Image Subject Key'];
				$this->db->exec($sql);
				$order++;
			}
		}else {
			print_r($error_info=$this->db->errorInfo()); print "$sql";
			exit;
		}
		
		return $order_index;
	}




}



?>
