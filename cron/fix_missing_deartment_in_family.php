<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 April 2018 at 20:13:23 GMT+8,  Kuala Lumpur Malysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);


$sql=sprintf('select `Category Key`,`Category Code`,`Category Store Key` from `Product Category Dimension` left join `Category Dimension` on (`Product Category Key`=`Category Key`) 
where 

`Category Branch Type`="Head" and `Product Category Department Category Key` is null and `Category Scope`="Product" ');

if ($result=$db->query($sql)) {
		foreach ($result as $row) {
           // print_r($row);

            $category=get_object('Category',$row['Category Key']);
            foreach($category->get_category_data() as $cat_data){
            	//print_r($cat_data);
            	
            	$sql=sprintf('update  `Product Category Dimension`  set `Product Category Department Category Key`=%d where `Product Category Key`=%d  ',
            	$cat_data['category_key'],
            	$row['Category Key']
            	);
            	
                //$category->update(array('Product Category Department Category Key'=>$cat_data['category_key']),'no_history');


            	break;
			}

		}
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}





?>
