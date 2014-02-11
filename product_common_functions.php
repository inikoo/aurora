<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 February 2014 16:28:32 CET, Malaga , ES
 Copyright (c) 2014, Inikoo

 Version 2.0
*/

function get_product_web_state_labels($products) {

	$product_data=array();
	foreach ($products as $product) {

		if ($product['ProductNumberWebPages']==0) {
			$web_state='<img src="art/icons/world_light_bw.png" title="'._('Not in website').'" />';
		}elseif ($product['ProductWebState']=='For Sale') {
			$web_state='<div style="position:relative"><img class="icon" src="art/icons/world.png" /> '.($product['ProductNumberWebPages']>1? ' <span style="position:absolute;left:16px;top:6px;font-size:8px;background:red;color:white;padding:1px 1.7px 1px 2.2px;opacity:0.8;border-radius:30%">3</span>':'').' </div>';
		}elseif ($product['ProductWebState']=='Out of Stock') {
			$web_state='<img src="art/icons/no_stock.jpg" />';
		}else {
			$web_state='<img src="art/icons/sold_out.gif" />';
		}


		

			if ($product['ProductWebConfiguration']=='Online Auto') {
				$web_state_configuration=_('Link to part');
			}elseif ($product['ProductWebConfiguration']=='Offline') {
				$web_state_configuration='<img src="art/icons/police_hat.jpg" style="height:18px;;vertical-align:top" /> '._('Offline');
			}elseif ($product['ProductWebConfiguration']=='Online Force Out of Stock') {
				$web_state_configuration='<img src="art/icons/police_hat.jpg" style="height:18px;;vertical-align:top" /> '._('Out of stock');
			}elseif ($product['ProductWebConfiguration']=='Online Force For Sale') {
				$web_state_configuration='<img src="art/icons/police_hat.jpg" style="height:18px;;vertical-align:top" /> '._('Online');
			}else {
				$web_state_configuration='';
			}
	

		$product_data[]=array(
			'pid'=>$product['ProductID'],
			'web_state'=>$web_state,
			'web_state_configuration'=>$web_state_configuration,
			'ProductWebConfiguration'=>$product['ProductWebConfiguration']
		);



	}
	return $product_data;
}


?>
