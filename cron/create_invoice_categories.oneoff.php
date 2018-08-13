<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 August 2018 at 12:35:14 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Category.php';


$sql=sprintf('select `Category Key` from `Category Dimension` where  `Category Scope`="Invoice" and `Category Branch Type`="Root"  ');
if ($result=$db->query($sql)) {
    if ($row = $result->fetch()) {
        $category=new Category($row['Category Key']);


print_r($row);



        $sql=sprintf('select `Store Key`,`Store Name`,`Store Code` from  `Store Dimension`  where `Store State`="Normal"  ');
        if ($result2=$db->query($sql)) {
        		foreach ($result2 as $row2) {

        		    print_r( array(
                                 'Category Code'  => $category->get($row2['Store Code']),
                                 'Category Label' => $category->get($row2['Store Name']),


                             ));

                    $family = $category->create_category(
                        array(
                            'Category Code'  => $category->get($row2['Store Code']),
                            'Category Label' => $category->get($row2['Store Name']),


                        )
                    );
                    $sql=sprintf('insert into `Invoice Category Data` (`Invoice Category Key`)  values  (%d) ',$family->id);
                    $db->exec($sql);
                    $sql=sprintf('insert into `Invoice Category DC Data` (`Invoice Category Key`)  values  (%d) ',$family->id);
                    $db->exec($sql);

        		}
        }else {
        		print_r($error_info=$db->errorInfo());
        		print "$sql\n";
        		exit;
        }

        $family = $category->create_category(
            array(
                'Category Code'  => $category->get('VIPs'),
                'Category Label' => $category->get('VIPs'),


            )

        );

        $sql=sprintf('insert into `Invoice Category Data` (`Invoice Category Key`)  values  (%d) ',$family->id);
        $db->exec($sql);
        $sql=sprintf('insert into `Invoice Category DC Data` (`Invoice Category Key`)  values  (%d) ',$family->id);
        $db->exec($sql);


        $family = $category->create_category(
            array(
                'Category Code'  => $category->get('Partner').'x',
                'Category Label' => $category->get('Partner'),
                'Invoice Category Function Code'=>'level_type'


            )
        );
        $sql=sprintf('insert into `Invoice Category Data` (`Invoice Category Key`)  values  (%d) ',$family->id);
        $db->exec($sql);
        $sql=sprintf('insert into `Invoice Category DC Data` (`Invoice Category Key`)  values  (%d) ',$family->id);
        $db->exec($sql);

        $family = $category->create_category(
            array(
                'Category Code'  => $category->get('Other'),
                'Category Label' => $category->get('Other'),


            )
        );

        $sql=sprintf('insert into `Invoice Category Data` (`Invoice Category Key`)  values  (%d) ',$family->id);
$db->exec($sql);
        $sql=sprintf('insert into `Invoice Category DC Data` (`Invoice Category Key`)  values  (%d) ',$family->id);
        $db->exec($sql);


    }
}else {
	print_r($error_info=$db->errorInfo());
	print "$sql\n";
	exit;
}







?>
