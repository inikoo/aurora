
<?php
/*
  
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2011, Inikoo

*/
//require_once 'common_functions.php';

class LightFamily{
  
  var $id=false;
  var $data=array();
  var $locale;
  var $url;
  var $user_id;
  var $method;
  var $match=true;
  
    function __construct($arg1,$arg2) {
    
   
        $this->get_data('code',$arg1,$arg2);


    }

    function get_data($tag,$id,$id2=false) {
        if ($tag=='id')
            $sql=sprintf("select * from `Product Family Dimension` where `Product Family Key`=%s",prepare_mysql($id));
        elseif($tag=='code')
			$sql=sprintf("select * from `Product Family Dimension` where `Product Family Code`=%s and `Product Family Store Key`=%d",prepare_mysql($id),$id2);
		else
            return false;
			

			
        $result=mysql_query($sql);
		
		if(!mysql_num_rows($result))
			$this->match=false;	
		
		//print $sql;
		print mysql_error();
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $this->id=$this->data['Product Family Key'];
			
        }

		/*
		$sql="select * from `Product Dimention`";
		$result=mysql_query($sql);
		mysql_fetch_array($result);
		*/
    }
 
 
 
 	function get_order_list_info($header){
		$i=1;
		$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d", $this->id);
		$result=mysql_query($sql);
		//print $sql;
	
		$_form=sprintf('<div><div>					
					<style type="text/css">.nophp{display:none}</style>
					<style type="text/css">table.order{font-size:11px;font-family:arial; }
					td.order{padding-right:2em;}
					</style>

					<table class="order" >
					<input type="hidden" name="nocart"> ');
				
				
		$form=sprintf('<table class="order" ><input type="hidden" name="nocart"> '
						);
	
		$sql=sprintf("select `Product Price` from `Product Dimension` where `Product Family Key`=%d order by `Product Price` limit 0,1", $this->id);
		$res=mysql_query($sql);
		$price_row=mysql_fetch_array($res, MYSQL_ASSOC);
		$price=$price_row['Product Price'];	
	
	
		if($header['on'])
			$form.=sprintf('<th style="font-size:20px;font-family:arial;">Price from %.2f</th>',$price);
			
		while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			$this->locale=$row['Product Locale'];
			
			if ($this->locale=='de_DE') {
			$out_of_stock='nicht vorrv§tig';
			$discontinued='ausgelaufen';
			  }if ($this->locale=='de_DE') {
			$out_of_stock='nicht vorrv§tig';
			$discontinued='ausgelaufen';
			  }
			elseif($this->locale=='es_ES') {
			$out_of_stock='Fuera de Stock';
			$discontinued='Fuera de Stock';
			  }

			  elseif($this->locale=='fr_FR') {
			$out_of_stock='Rupture de stock';
			$discontinued='Rupture de stock';
			  }
			  else {
			$out_of_stock='Out of Stock';
			$discontinued='Discontinued';
			  }

//<td style="float:right;font-size:8pt;color:red;font-weight:800;">%s</td>
		if ($row['Product Web State']=='Online Force Out of Stock'){
					$_form.=sprintf('<tr class="nophp">
							<td class="order">%s</td>
							<td>%.2f</td>
							<td>%s (%s)</td>
							
							</tr>'
							,addslashes($row['Product Code'])
							,$row['Product Price']
							
							
							,clean_accents(addslashes($row['Product Name']))
							,$out_of_stock
							);
//<td style="float:right;font-size:8pt;color:red;font-weight:800;">%s</td>							
					$form.=sprintf('<tr ><td class="order">%s</td>
							<td class="order">%.2f</td>
							<td class="order">%s (%s)</td>
							
							</tr>'
							,addslashes($row['Product Code'])
							,$row['Product Price']
							
							
							,clean_accents(addslashes($row['Product Name']))
							,$out_of_stock
							);
			}
			else{
					$_form.=sprintf('<tr class="nophp">
							<td>%s</td><td>%.2f</td>
							<td>%s</td>
							</tr>'
							
							,addslashes($row['Product Code'])
							,$row['Product Price']
							,clean_accents(addslashes($row['Product Name']))
							);
							
					$form.=sprintf('<tr ><td class="order">%s</td><td class="order">%.2f</td>
							
							<td class="order">%s</td></tr>'
							
							,addslashes($row['Product Code'])
							,$row['Product Price']
							,clean_accents(addslashes($row['Product Name']))


							);
			
			 }



			  
			
			$i++;		
		}
	
		$_form.=sprintf('</table>');
	
	
		$form.=sprintf('</table>');
	
		  //print $form;exit;
		  return $_form.$form.'</div></div>';
	}
	
	
	
	function get_order_list($header, $type, $secure, $_port, $_protocol, $url, $server, $ecommerce_url, $username, $method){
		$i=1;
		$price=0;
		$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d", $this->id);
		$result=mysql_query($sql);
		//print $sql;
		switch($type){
			case 'ecommerce':			
				$this->url=$ecommerce_url;				
				$this->user_id=$username;
				$this->method=$method;
			break;

			default:
			break;
		}
		
		/*
		
							

		*/
	

	$sql=sprintf("select `Product Price` from `Product Dimension` where `Product Family Key`=%d order by `Product Price` limit 0,1", $this->id);
	$res=mysql_query($sql);
	$price_row=mysql_fetch_array($res, MYSQL_ASSOC);
	$price=$price_row['Product Price'];	
	
	//$this->locale=$row['Product Locale'];
	$_form=sprintf('<div><div>
					<style type="text/css">.nophp{display:none}</style>
					<style type="text/css">table.order{font-size:11px;font-family:arial;}
					input.order{width:30px}
					td.order{padding-right:2em;}
					</style>
					<table  class="order">
					<form action="%s" method="post">
					<input type="hidden" name="userid" value="%s">
					<input type="hidden" name="return" value="%s">
					<input type="hidden" name="nocart"> '
			,$ecommerce_url, addslashes($username), ecommerceURL($secure, $_port, $_protocol, $url, $server));
			
			
	$form=sprintf('<table class="order">
					<form action="%s" method="post">
					<input type="hidden" name="userid" value="%s">
					<input type="hidden" name="nocart"> '
					,$ecommerce_url
					,addslashes($username)
					
					);
	
	if($header['on'])
		$form.=sprintf('<th style="font-size:20px;font-family:arial;">Price from %.2f</th>',$price);	
	
	
	while($row=mysql_fetch_array($result, MYSQL_ASSOC)){

		
		$this->locale=$row['Product Locale'];
		
		if ($this->locale=='de_DE') {
		$out_of_stock='nicht vorrv§tig';
		$discontinued='ausgelaufen';
		  }if ($this->locale=='de_DE') {
		$out_of_stock='nicht vorrv§tig';
		$discontinued='ausgelaufen';
		  }
		elseif($this->locale=='es_ES') {
		$out_of_stock='Fuera de Stock';
		$discontinued='Fuera de Stock';
		  }

		  elseif($this->locale=='fr_FR') {
		$out_of_stock='Rupture de stock';
		$discontinued='Rupture de stock';
		  }
		  else {
		$out_of_stock='Out of Stock';
		$discontinued='Discontinued';
		  }


		
		if ($row['Product Web State']=='Online Force Out of Stock'){
				$_form.=sprintf('<tr class="nophp">
						<td>%s</td>
						<td>%.2f</td>
						<td><input class="order" type="hidden" /></td>
						<td>%s</td>
						<td style="float:right;font-size:8pt;color:red;font-weight:800;">%s</td>
						</tr>
						<input type="hidden"  name="discountpr%s"  value="1,%.2f"  >
						<input type="hidden"  name="product%s"  value="%s %sx %s" >'
						,addslashes($row['Product Code'])
						,get_formated_price($this->locale, $row)
						,clean_accents(addslashes($row['Product Name']))
						,$out_of_stock
						
						,$i
						//,$row['Product Price']
						,get_formated_price($this->locale, $row)
						,$i
						,addslashes($row['Product Code'])
						,addslashes($row['Product Units Per Case'])
						,clean_accents(addslashes($row['Product Name']))
						);
						
				$form.=sprintf('<tr ><td class="order">%s</td>
						<td class="order">%.2f</td>
						<td class="order"><input class="order" type="hidden" /></td>
						<td class="order">%s (%s)</td>
						
						</tr>
						<input type="hidden"  name="discountpr%s"  value="1,%.2f"  >
						<input type="hidden"  name="product%s"  value="%s %sx %s" >'
						,addslashes($row['Product Code'])
						,get_formated_price($this->locale, $row)
						,clean_accents(addslashes($row['Product Name']))
						,$out_of_stock
						
						,$i
						//,$row['Product Price']
						,get_formated_price($this->locale, $row)
						,$i
						,addslashes($row['Product Code'])
						,addslashes($row['Product Units Per Case'])
						,clean_accents(addslashes($row['Product Name']))
						);
		}
		else{
				$_form.=sprintf('<tr class="nophp">
						<td>%s</td><td>%.2f</td>
						<td><input class="order" type="text" name="qty%s"  id="qty%s"    /></td>
						<td>%s</td>
						</tr>
						<input type="hidden"  name="discountpr%s"  value="1,%.2f"  >
						<input type="hidden"  name="product%s"  value="%s %sx %s" >'
						,addslashes($row['Product Code'])
						,$row['Product Price']
						,$i
						,$i
						,clean_accents(addslashes($row['Product Name']))
						,$i
						,$row['Product Price']
						,$i
						,addslashes($row['Product Code'])
						,addslashes($row['Product Units Per Case'])
						,clean_accents(addslashes($row['Product Name']))
						);
						
				$form.=sprintf('<tr ><td class="order">%s</td><td >%.2f</td>
						<td class="order"><input class="order"  type="text"  name="qty%s"  id="qty%s"  /></td>
						<td class="order">%s</td></tr>
						<input type="hidden"  name="discountpr%s"  value="1,%.2f"  >
						<input type="hidden"  name="product%s"  value="%s %sx %s">'
						,addslashes($row['Product Code'])
						,$row['Product Price']
						,$i
						,$i
						,clean_accents(addslashes($row['Product Name']))
						,$i
						,$row['Product Price']
						,$i
						,addslashes($row['Product Code'])
						,addslashes($row['Product Units Per Case'])
						,clean_accents(addslashes($row['Product Name']))
						);
		
		 }
		 $i++;
	}
	
	$_form.=sprintf('<tr class="nophp"><td>
						<input name="Submit" type="submit"  value="Order"> 
						<input name="Reset" type="reset"  id="Reset" value="Reset"></td></tr>
						</form>
					</table>
					'
					);


	$form.=sprintf('<tr ><td >
					<input type="hidden" name="return" value="%s"> 
					<input name="Submit" type="submit"  value="Order">
					<input name="Reset" type="reset"  id="Reset" value="Reset"></td></tr></form></table>
					'
					,ecommerceURL($secure, $_port, $_protocol, $url, $server));

	  //print $form;exit;
	  return $_form.$form.'</div></div>';
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

	function get_formated_price($locale='', $row){

		 $data=array(
		'Product Price'=>$row['Product Price'],
		'Product Units Per Case'=>$row['Product Units Per Case'],
		'Product Currency'=>$row['Product Currency'],
		'Product Unit Type'=>$row['Product Unit Type'],


		'locale'=>$locale);
		return formated_price($data);
	}
	
		
	 
	
}
?>
