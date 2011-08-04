<?php

function show_products_in_family($type, $data, $conf){
	//print_r($data);
	
	$secure=$conf['secure'];
	$_port=$conf['_port'];
	$_protocol=$conf['_protocol'];
	$url=$conf['url'];
	$server=$conf['server'];
	
	switch($type){
		case 'ecommerce':

		$ecommerce_url_multi=$conf['ecommerce_url_multi'];				
		$username=$conf['username'];
		$method=$conf['method'];
		break;

		default:
		break;
	}
	
	$_form=sprintf('<div style="position:absolute; left:0px; top:487px; width:192px; height:26px;">
					<div style="text-align:left;">
					<link rel="stylesheet" type="text/css" href="../order.css" />
					<link rel="stylesheet" type="text/css" href="order.css" />
					<style type="text/css">.nophp{display:none}</style>
					<style type="text/css">
					table.order {width:22em}td.first{width:8 em}
					table.order{font-size:11px;font-family:arial;}
					span.price{float:right;margin-right:5px}
					span.desc{margin-left:5px}
					span.outofstock{color:red;font-weight:800;float:right;margin-right:5px;}
					input.qty{width:100%%}td.qty{width:3em}
					</style>

					<table class="order" border=0 cellpadding="0" cellspacing="0">
					<form action="%s" method="post">
					<input type="hidden" name="userid" value="%s">
					<input type="hidden" name="return" value="%s">'
			,$ecommerce_url_multi, addslashes($username), ecommerceURL($secure, $_port, $_protocol, $url, $server));
			
			
	$form=sprintf('<table class="order" border=0 cellpadding="0" cellspacing="0">
					<form action="%s" method="post">
					<input type="hidden" name="userid" value="%s">'
					,$ecommerce_url_multi
					,addslashes($username)
					);
					
	foreach($data as $product){
		$i=1;
		$locale=$product['Product Locale'];
		if ($locale=='de_DE') {
			$out_of_stock='nicht vorrv§tig';
			$discontinued='ausgelaufen';
		}if ($locale=='de_DE') {
			$out_of_stock='nicht vorrv§tig';
			$discontinued='ausgelaufen';
		}
		elseif($locale=='es_ES') {
			$out_of_stock='Fuera de Stock';
			$discontinued='Fuera de Stock';
		}
		elseif($locale=='fr_FR') {
			$out_of_stock='Rupture de stock';
			$discontinued='Rupture de stock';
		}
		else {
			$out_of_stock='Out of Stock';
			$discontinued='Discontinued';
		}
		  
		
		if ($product['Product Web State']=='Online Force Out of Stock'){
				$_form.=sprintf('<tr class="nophp">
						<td colspan=2 style="height:20px;padding:0;margin:0;"><span  style="float:right;font-size:8pt;color:red;font-weight:800;">%s</span>%s</td>
						<td><span class="desc">%s</span></td>
						</tr>
						<input type="hidden"  name="discountpr%s"  value="1,%.2f"  >
						<input type="hidden"  name="product%s"  value="%s %sx %s" >'
						,$out_of_stock
						,addslashes($product['Product Code'])
						,clean_accents(addslashes($product['Product Name']))
						,$i
						,$product['Product Price']
						,$i
						,addslashes($product['Product Code'])
						,addslashes($product['Product Units Per Case'])
						,clean_accents(addslashes($product['Product Name']))
						);
						
				$form.=sprintf('<tr ><td colspan=2 style="height:20px;padding:0;margin:0;"><span style="float:right;font-size:8pt;color:red;font-weight:800;">%s</span>%s</td>
						<td><span class="desc">%s</span></td></tr>
						<input type="hidden"  name="discountpr%s"  value="1,%.2f"  >
						<input type="hidden"  name="product%s"  value="%s %sx %s">'
						,$out_of_stock
						,addslashes($product['Product Code'])
						,clean_accents(addslashes($product['Product Name']))
						,$i
						,$product['Product Price']
						,$i
						,addslashes($product['Product Code'])
						,addslashes($product['Product Units Per Case'])
						,clean_accents(addslashes($product['Product Name']))
						);
		}
		else{
				$_form.=sprintf('<tr class="nophp">
						<td class="first"><span class="price">%.2f</span>%s</td>
						<td class="qty"><input type="text"  class="qty" name="qty%s"  id="qty%s"    /></td>
						<td><span class="desc">%s</span></td>
						</tr>
						<input type="hidden"  name="discountpr%s"  value="1,%.2f"  >
						<input type="hidden"  name="product%s"  value="%s %sx %s" >'
						,$product['Product Price']
						,addslashes($product['Product Code'])
						,$i
						,$i
						,clean_accents(addslashes($product['Product Name']))
						,$i
						,$product['Product Price']
						,$i
						,addslashes($product['Product Code'])
						,addslashes($product['Product Units Per Case'])
						,clean_accents(addslashes($product['Product Name']))
						);
						
				$form.=sprintf('<tr ><td class="first"><span class="price">%.2f</span>%s</td>
						<td class="qty" style="width:3em"><input type="text" class="qty" name="qty%s"  id="qty%s"  /></td>
						<td><span class="desc">%s</span></td></tr>
						<input type="hidden"  name="discountpr%s"  value="1,%.2f"  >
						<input type="hidden"  name="product%s"  value="%s %sx %s">'
						,$product['Product Price']
						,addslashes($product['Product Code'])
						,$i
						,$i
						,clean_accents(addslashes($product['Product Name']))
						,$i
						,$product['Product Price']
						,$i
						,addslashes($product['Product Code'])
						,addslashes($product['Product Units Per Case'])
						,clean_accents(addslashes($product['Product Name']))
						);
		
		 }
		 $i++;
	}
	
	$_form.=sprintf('<tr id="submit_tr" class="nophp"><td id="submit_td" colspan="3" >
						<input name="Submit" type="submit" class="text" value="Order"> 
						<input name="Reset" type="reset" class="text"  id="Reset" value="Reset"></td></tr>
					</table>
					</form>'
					);


	$form.=sprintf('<tr id="submit_tr"><td id="submit_td" colspan="3" >
					<input name="Submit" type="submit" class="text" value="Order">
					<input name="Reset" type="reset" class="text"  id="Reset" value="Reset"></td></tr></table>
					<input type="hidden" name="return" value="%s"></form> '
					,ecommerceURL($secure, $_port, $_protocol, $url, $server));

	  //print $form;exit;
	  return $_form.$form.'</div></div>';
}
/*
function ecommerceURL($secure, $_port, $_protocol, $url, $server) {
	$s = empty($secure) ? '' : ($secure == "on") ? "s" : "";
	$protocol = $this->strleft1(strtolower($_protocol), "/").$s; 
	$port = ($_port == "80") ? "" : (":".$_port);
	if(strpos($url, "?")){
		return $protocol."://".$server.$port.$this->strleft1(strtolower($url), "?"); 
	}
	else
		return $protocol."://".$server.$port.$url;
}


 function strleft1($s1, $s2){ 
	return substr($s1, 0, strpos($s1, $s2)); 
 }
 
*/
?>

