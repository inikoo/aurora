<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 18 September 2016 at 10:47:50 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

trait LocationCategory {




	function update_number_of_parts() {

		$number_of_parts=0;

		$sql=sprintf("select count(`Part SKU`) as num  from  `Part Location Dimension` L left join `Category Bridge` B on (L.`Location Key`=B.`Subject Key`)  where B.`Category Key`=%d and `Subject`='Location' group by `Part SKU`  ",
			$this->id);


		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$number_of_parts=number($row['num']);
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




		$sql=sprintf("update `Location Category Dimension` set `Location Category Number Parts`=%d  where `Part Category Key`=%d",
			$number_of_parts,
			
			$this->id
		);
		$this->db->exec($sql);


	}





}

?>
