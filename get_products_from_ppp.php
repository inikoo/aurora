<?php
include_once('common.php');
//$files = glob("sites/forms/*/*.php");
$files = glob("sites/forms/3dpuzz/index.php");

$sql=sprintf("truncate table `Product Page Bridge`");
if(!$result=mysql_query($sql))
	print 'Table cannot be truncate';


foreach($files as $filename) {
    //print $filename."  ".filesize($filename)."  \n";
	
	
    if (filesize($filename)) {
        $fp = fopen($filename, 'r');
        $contents = fread($fp, filesize($filename));
        fclose($fp);
		
		$page_code=0;
		
		if(preg_match('/family.*/', $contents, $page_code_match)){
			$page_codes=preg_split('/\=\>/', $page_code_match[0]);
			$page_code=$page_codes[1];
			$page_code=preg_replace('/\'/', '', $page_code);
			$page_code=preg_replace('/\s/', '', $page_code);
		}
		
		//print $page_code;
				
        if (preg_match_all('/<\?.*show.products.*\?>/',$contents,$matches)) {
		
		//print_r($matches);
			
			
			foreach($matches[0] as $match){
				if (preg_match('/\(.*\)/',$match,$match_products)) {
	
					foreach($match_products as $match_product){
						
						$temp1=substr($match_product, 1, strlen($match_product)-2);

						if(preg_match("/\,array/", $temp1)){
							$codes=preg_split("/\,array/", $temp1);

							$code=$codes[0];
							//Option array
							$options=$codes[1];
							$option_flag=true;
						}else{
							$code=$temp1;
							$option_flag=false;
							
						}
						$_limit='';
						$_range='';
						
						$code = preg_replace("/\'/", '', $code);
						$_code=preg_split('/\,/', $code);
						
						
						
						if($option_flag){
							$options=substr($options, 1, strlen($options)-2);
							$_option=preg_split('/\,/', $options);
							
							foreach($_option as $__option){
								if(preg_match('/(limit|range)/', $__option)){
									$__option=preg_replace('/\'/', '', $__option);
									$__option=preg_replace('/\s/', '', $__option);
									$option_value_pairs=preg_split('/\=>/', $__option);
									
									
									
									if(preg_match('/limit/', $option_value_pairs[0])){
										$_limit='limit '.$option_value_pairs[1];
									}
									if(preg_match('/range/', $option_value_pairs[0])){
										list($range1, $range2)=explode(":", strtoupper($option_value_pairs[1]));
										$_range=sprintf("and ( (ord(`Product Name`) >= %d and ord(`Product Name`) <= %d) || (ord(`Product Name`) >= %d and ord(`Product Name`) <= %d))", ord($range1), ord($range2), ord($range1)+32, ord($range2)+32);
			
									}
										
								}
							}
							
							//print_r($_option);
						}
						
						
						//print_r($_code);
						foreach($_code as $product_code){
							//get info from the code
							
							$sql=sprintf("select `Product Family Key` from `Product Family Dimension` where `Product Family Code`='%s'", $product_code);
							$result=mysql_query($sql);
							
							if($row=mysql_fetch_array($result))
								$product_code=$row['Product Family Key'];
							else
								$product_code=0;
								
								
							$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Code`='%s'", $page_code);	
							//print $sql;
							$result=mysql_query($sql);
							if($row=mysql_fetch_array($result)){
								$page_key=$row['Page Key'];
							}
							else{
								print "No Page Exist in the table for $page_code";
								//exit;
							}
							
							
								
							$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline'  %s %s", $product_code, $_range, $_limit);
							//print "$sql\n";
							$result=mysql_query($sql);
							
							while($row=mysql_fetch_array($result)){
								//print $row['Product Code']."\n";
								$sql=sprintf("insert into `Product Page Bridge` (`Product ID`,`Page Key`,`Type`) values (%d, %d, '%s')", $row['Product ID'], $page_code, 'List');
								//print $sql;
								mysql_query($sql);
							}
						}
						
						
					}
				}
			}
			
		

        }
		
		if (preg_match_all('/<\?.*show.product\(.*\?>/',$contents,$matches)) {
			foreach($matches[0] as $match){
				if (preg_match('/\(.*\)/',$match,$match_products)) {
	
					foreach($match_products as $match_product){
						
						$temp1=substr($match_product, 1, strlen($match_product)-2);
						$temp1=preg_replace('/\'/','', $temp1);
						//print $temp1;
						
						$sql=sprintf("select `Product ID` from `Product Dimension` where `Product Code`='%s' and `Product Web State`!='Offline' and `Product Store Key`='1'", $temp1);
						//print $sql;
						$result=mysql_query($sql);
						if($row=mysql_fetch_array($result)){
							$sql=sprintf("insert into `Product Page Bridge` (`Product ID`,`Page Key`,`Type`) values (%d, %d, '%s')", $row['Product ID'], $page_code, 'Button');
							//print $sql;
							mysql_query($sql);
						}
					}
				}
				
			}
		}
       
    }
	
}

function removeBOM($str="") {
    if (substr($str, 0,3) == pack("CCC",0xef,0xbb,0xbf)) {
        $str=substr($str, 3);
    }
    return $str;
}
		/*
            $contents=removeBOM($contents);

            $script=$match[0];
            $contents=preg_replace('/<\?.*common.splinter.*\?>/msU','',$contents,1);
            $contents=$script.$contents;
            $fp = fopen($filename, 'w');
            rewind($fp);
            fwrite($fp,$contents);
            fclose($fp);
		*/
?>