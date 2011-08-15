<?php

function show_products_in_family_info($data, $header){

		$_form=sprintf('<div><div>					
					<style type="text/css">.nophp{display:none}</style>
					<style type="text/css">table.order{font-size:8pt;font-family:arial;font-weight:bold}
					td.order{padding-right:2em;}
					</style>

					<table class="order" >');
				
				
		$form=sprintf('<table class="order" >'
						);
	if($header['on'])
		$form.=sprintf('<td style="font-size:20p;font-family:arial;" colspan="4">Price from %.2f</td>',$header['price']);				
		
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
		  
	//<td style="float:right;font-size:8pt;color:red;font-weight:800;">%s</td>	
		if ($product['Product Web Configuration']=='Online Force Out of Stock'){
					$_form.=sprintf('<tr class="nophp">
							<td>%s</td>
							<td>%.2f</td>					
							<td>%s (%s)</td>
							
							
							</tr>'
							,addslashes($product['Product Code'])
							,$product['Product Price']
							,clean_accents(addslashes($product['Product Name']))
							,$out_of_stock
							
							
							);
		//<td style="float:right;font-size:8pt;color:red;font-weight:800;">%s</td>					
					$form.=sprintf('<tr >
							<td class="order">%s</td>
							<td class="order">%.2f</td>					
							<td class="order">%s (%s)</td>
							</tr>'
							,addslashes($product['Product Code'])
							,$product['Product Price']
							,clean_accents(addslashes($product['Product Name']))
							,$out_of_stock

							);
			}
			else{
					$_form.=sprintf('<tr class="nophp">
							<td>%s</td><td>%.2f</td>
							<td>%s</td>
							</tr>'
							
							,addslashes($product['Product Code'])
							,$product['Product Price']
							,clean_accents(addslashes($product['Product Name']))
							);
							
					$form.=sprintf('<tr ><td class="order">%s</td><td class="order">%.2f</td>
							
							<td class="order">%s</td></tr>'
							
							,addslashes($product['Product Code'])
							,$product['Product Price']
							,clean_accents(addslashes($product['Product Name']))


							);
			
			 }



			  
			
			$i++;		
		}
	
		$_form.=sprintf('</table>');
	
	
		$form.=sprintf('</table>');
	
		  //print $form;exit;
		  return $_form.$form.'</div></div>';
}


function show_products_in_family($type, $data, $conf, $header){
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
	

	
	$_form=sprintf('<div><div>
					<style type="text/css">.nophp{display:none}</style>
					<style type="text/css">table.order{font-size:8pt;font-family:arial; font-weight:bold}
					input.order{width:30px}
					td.order{padding-right:2em;}
					</style>
					<table  class="order">
					<form action="%s" method="post">
					<input type="hidden" name="userid" value="%s">
					<input type="hidden" name="return" value="%s">
					<input type="hidden" name="nocart">'
			,$ecommerce_url_multi, addslashes($username), ecommerceURL($secure, $_port, $_protocol, $url, $server));
			
			
	$form=sprintf('<table class="order">
					<form action="%s" method="post">
					<input type="hidden" name="userid" value="%s">
					<input type="hidden" name="nocart">'
					,$ecommerce_url_multi
					,addslashes($username)
			
					);

	if($header['on'])
		$form.=sprintf('<td style="font-size:20px;font-family:arial;" colspan="4">Price from %.2f</td>',$header['price']);
					
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
		  
		
		if ($product['Product Web Configuration']=='Online Force Out of Stock'){
				$_form.=sprintf('<tr class="nophp">
						<td >%s</td>
						<td>%.2f</td>
						<td><input class="order" type="hidden" /></td>
						<td>%s</td><td style="float:right;font-size:8pt;color:red;font-weight:800;">%s</td>
						</tr>
						<input type="hidden"  name="discountpr%s"  value="1,%.2f"  >
						<input type="hidden"  name="product%s"  value="%s %sx %s" >'
						,addslashes($product['Product Code'])
						,$product['Product Price']
						,clean_accents(addslashes($product['Product Name']))
						,$out_of_stock
						,$i
						//,$product['Product Price']
						,get_formated_price($locale, $product)
						,$i
						,addslashes($product['Product Code'])
						,addslashes($product['Product Units Per Case'])
						,clean_accents(addslashes($product['Product Name']))
						);
//<td style="float:right;font-size:8pt;color:red;font-weight:800;">%s</td>						
				$form.=sprintf('<tr >
						<td class="order">%s</td>
						<td class="order">%.2f</td>
						<td class="order"><input class="order" type="hidden" /></td>
						<td class="order">%s (%s)</td></tr>
						<input type="hidden"  name="discountpr%s"  value="1,%.2f"  >
						<input type="hidden"  name="product%s"  value="%s %sx %s">'
						,addslashes($product['Product Code'])
						,$product['Product Price']
						,clean_accents(addslashes($product['Product Name']))
						,$out_of_stock
						,$i
						//,$product['Product Price']
						,get_formated_price($locale, $product)
						,$i
						,addslashes($product['Product Code'])
						,addslashes($product['Product Units Per Case'])
						,clean_accents(addslashes($product['Product Name']))
						);
		}
		else{
				$_form.=sprintf('<tr class="nophp">
						<td>%s</td>
						<td>%.2f</td>
						<td><input class="order" type="text" name="qty%s" id="qty%s"/></td>
						<td>%s</td>
						</tr>
						<input type="hidden"  name="discountpr%s"  value="1,%.2f"  >
						<input type="hidden"  name="product%s"  value="%s %sx %s" >'
						,addslashes($product['Product Code'])
						,$product['Product Price']
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
						
				$form.=sprintf('<tr >
						<td class="order">%s</td>
						<td class="order">%.2f</td>
						<td class="order"><input class="order" type="text"  name="qty%s"  id="qty%s"  /></td>
						<td class="order">%s</td></tr>
						<input type="hidden"  name="discountpr%s"  value="1,%.2f"  >
						<input type="hidden"  name="product%s"  value="%s %sx %s">'
						,addslashes($product['Product Code'])
						,$product['Product Price']
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
	
	$_form.=sprintf('<tr class="nophp"><td colspan="4">
						<input name="Submit" type="submit"  value="Order"> 
						<input name="Reset" type="reset"  id="Reset" value="Reset"></td></tr>
						</form>
					</table>
					'
					);


	$form.=sprintf('<tr ><td colspan="4">
					<input type="hidden" name="return" value="%s"> 
					<input name="Submit" type="submit"  value="Order">
					<input name="Reset" type="reset"  id="Reset" value="Reset"></td></tr></form></table>'
					,ecommerceURL($secure, $_port, $_protocol, $url, $server));

	  //print $form;exit;
	  return $_form.$form.'</div></div>';
}


function get_formated_price($locale='', $row){

	 $data=array(
	'Product Price'=>$row['Product Price'],
	'Product Units Per Case'=>$row['Product Units Per Case'],
	'Product Currency'=>$row['Product Currency'],
	'Product Unit Type'=>$row['Product Unit Type'],


	'locale'=>$locale);
	return formated_price($data);
}

function get($key){

	switch ($key) {
   
	case('Order Msg'):
	if ($this->locale=='de_DE')
		return 'Bestellen';
	elseif($this->locale=='fr_FR')
		return 'Commander';
	else
		return 'Order';		
	break;
	default:
		return false;
	break;
	}

}

?>