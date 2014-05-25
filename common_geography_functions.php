<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 25 May 2014 08:32:00 CEST, Malaga Spain 

 Version 2.0
*/



function get_countries_EC_Fiscal_VAT_area() {
$countries_EC_Fiscal_VAT_area=array();
		$sql=sprintf("select `Country Code`  from kbase.`Country Dimension` where `EC Fiscal VAT Area`='Yes'");
		$res=mysql_query($sql);
		while($row=mysql_fetch_assoc($res)){
			$countries_EC_Fiscal_VAT_area[]=$row['Country Code'];
		}
	
return $countries_EC_Fiscal_VAT_area;
}


?>
