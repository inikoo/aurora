<?php

date_default_timezone_set('UTC');

include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Image.php';
include_once '../../class.Product.php';
include_once '../../class.SupplierProduct.php';
error_reporting(E_ALL);

$to_stop=0;

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='kaw';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';



$sql=sprintf("select `Product Family Code`,`Product Family Key` from `Product Family Dimension` where `Product Family Main Image Key`=0  order by `Product Family Key`  desc ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
	$reference=$row['Product Family Code'];
	$family=new Family($row['Product Family Key']);


	$sufixes=array('-disp1','-disp','-display','_display','_disp','');

	foreach ($sufixes as $sufix) {
		$image_name=strtolower($reference).$sufix.".jpg";

		$tmp_file='/tmp/'.$image_name;
		$url='http://www.ancientwisdom.biz/pics/'.$image_name;
		//print "$url\n";
		if (@getimagesize($url)) {
			if (file_put_contents($tmp_file, file_get_contents($url))) {
				print "$reference $tmp_file\n";

				$image_data=array(
					'file'=>$tmp_file,
					'source_path'=>'',
					'name'=>$image_name,
					'caption'=>''
				);

				$image=new Image('find',$image_data,'create');
				


				if (!$image->error) {
			
					$family->add_image($image->id);
					$family->update_main_image($image->id);
					
				}
				unlink($tmp_file);
			}

		}



	}

}




$sql=sprintf("select `Part Reference`,`Part SKU` from `Part Dimension` where `Part Reference`!='' and  `Part Main Image Key`=0 order by `Part SKU` desc ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
	$reference=$row['Part Reference'];
	$part=new Part($row['Part SKU']);


	$sufixes=array('c','b','d','_bis','_tris','_quad','');

	foreach ($sufixes as $sufix) {
		$image_name=strtolower($reference).$sufix.".jpg";

		$tmp_file='/tmp/'.$image_name;
		$url='http://www.ancientwisdom.biz/pics/'.$image_name;
		//print "$url\n";
		if (@getimagesize($url)) {
			if (file_put_contents($tmp_file, file_get_contents($url))) {
				print "$reference $tmp_file\n";

				$image_data=array(
					'file'=>$tmp_file,
					'source_path'=>'',
					'name'=>$image_name,
					'caption'=>''
				);

				$image=new Image('find',$image_data,'create');
				


				if (!$image->error) {
					$part->add_image($image->id);
					$part->update_main_image($image->id);
					$products_pids=$part->get_all_product_ids();
					foreach ($products_pids as $tmp) {

						$product=new Product('pid',$tmp['Product ID']);
						if ($product->data['Product Use Part Pictures']=='Yes')
							$product->add_image($image->id);
							$product->update_main_image($image->id);

					}
				}
				unlink($tmp_file);
			}

		}



	}

}


exit;

//$sql=sprintf("select `Product Family Key`,`Product Family Code` from `Product Family Dimension`");

// $res=mysql_query($sql);
// while($row=mysql_fetch_array($res)){

//  print "wget www.ancientwisdom.biz/pics/".strtolower($row['Product Family Code']).".jpg;\n";
//
// }
//exit;


/* $sql="select `Product Code` from `Product Dimension`  group by `Product Code` "; */

/* $result=mysql_query($sql); */
/* while($row=mysql_fetch_array($result)   ){ */
/*   $code=$row['Product Code']; */
/*   print "$code\n"; */

$pics=array();

$path="pics/";
$img_array_full_path = glob($path."*.jpg");
//print_r($img_array_full_path);
foreach ($img_array_full_path as $pic_path) {
	$_pic_path=preg_replace('/.*\//','',$pic_path);
	if (preg_match('/[a-z0-9\-]+(|_l|_bis|_tris|_quad|_display|_displ|dpl|_box)+.jpg/',$_pic_path)) {
		// print "$pic_path\n";
		if (preg_match('/^[a-z]+\-\d+[a-z]{0,3}/',$_pic_path,$match)) {
			$root=$match[0];
			if (array_key_exists($root,$pics))
				$pics[$root][]=$pic_path;
			else
				$pics[$root]=array($pic_path);
		}



	}
}
//print_r($pics);
//exit;
chdir('../../');
foreach ($pics as $key=>$value) {
	$parts=array();
	$sql=sprintf("select `Product ID`,`Product Code` from `Product Dimension` where `Product Code`=%s ",prepare_mysql($key));
	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res)) {
		$product=new Product('pid',$row['Product ID']);

		$sql=sprintf("select PPL.`Part SKU` from `Product Part Dimension` PPD left join  `Product Part List`       PPL   on (PPL.`Product Part Key`=PPD.`Product Part Key`)    left join `Part Dimension` PD on (PD.`Part SKU`=PPL.`Part SKU`) where PPD.`Product ID`=%d;",$product->pid);
		//print $sql;
		$result2=mysql_query($sql);

		while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {
			$parts[$row2['Part SKU']]=$row2['Part SKU'];

		}

	}

	// print_r($parts);


	foreach ($value as $img_filename) {

		print "----".getcwd()."------ ".$img_filename."   \n";
		$rand=rand().rand();

		$tmp_file='app_files/pics/tmp/tmp'.$rand.'.jpg';
		copy('mantenence/scripts/'.$img_filename,$tmp_file );
		// exit;

		$data=array(
			'file'=>'tmp'.$rand.'.jpg',
			'path'=>'app_files/pics/assets/',
			'name'=>$img_filename,
			'caption'=>''
		);

		//print_r($data);
		$image=new Image('find',$data,'create');

		if (!$image->id) {

			print_r($image);
			exit;

		}


		foreach ($parts as $part_sku) {
			$part=new Part($part_sku);
			$part->add_image($image->id,'principal');
			$part->update_main_image();

			$sql=sprintf("select  `Product ID` from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where  `Part SKU`=%d  "
				,$part->sku
			);

			$res=mysql_query($sql);
			$product_ids=array();
			while ($row=mysql_fetch_array($res)) {
				$product=new Product('pid',$row['Product ID']);
				$product->add_image($image->id,'principal');
				$product->update_main_image();

			}



			$sql=sprintf("select  SPPD.`Supplier Product ID`
                         from `Supplier Product Part List` SPPL
                         left join `Supplier Product Part Dimension` SPPD on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`)
                         left join `Supplier Product Dimension` SPD on (SPD.`Supplier Product ID`=SPPD.`Supplier Product ID`) where `Part SKU`=%d ;
                         ",$part->sku);
			// print $sql;
			$result=mysql_query($sql);
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				$supplier_products=new SupplierProduct('pid',$row['Supplier Product ID']);
				$supplier_products->add_image($image->id,'principal');
				$supplier_products->update_main_image();



			}
		}




		unlink($tmp_file);



	}




}

/*
exit;

$sql=sprintf("select `Product ID`,`Product Code` from `Product Dimension` where `Product Code`=%s ",prepare_mysql($key));
//print "$sql\n";
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {

    $product=new Product('pid',$row['Product ID']);



    foreach($value as $img_filename) {

        print "----".getcwd()."------ ".$img_filename."   \n";
        $rand=rand().rand();

        $tmp_file='app_files/pics/tmp/tmp'.$rand.'.jpg';
        copy('mantenence/scripts/'.$img_filename,$tmp_file );
        // exit;

        $data=array(
                  'file'=>'tmp'.$rand.'.jpg',
                  'path'=>'app_files/pics/assets/',
                  'name'=>$row['Product Code'],
                  'caption'=>''
              );

        print_r($data);
        $image=new Image('find',$data,'create');

        if (!$image->id) {

            print_r($image);
            exit;

        }

//    print_r($image);
        // exit;
        $product->add_image($image->id,'principal');
        //print_r($product);
        // print $product->msg."\n";
        $product->update_main_image();
        unlink($tmp_file);

        exit;

    }

}
mysql_free_result($res);




*/



?>
