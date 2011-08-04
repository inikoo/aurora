
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
 
 
	function get_order_list($type, $secure, $_port, $_protocol, $url, $server, $ecommerce_url, $username, $method){
		$i=1;
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
		
	//$this->locale=$row['Product Locale'];
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
			,$this->url, addslashes($this->user_id), $this->ecommerceURL($secure, $_port, $_protocol, $url, $server));
			
			
	$form=sprintf('<table class="order" border=0 cellpadding="0" cellspacing="0">
					<form action="%s" method="post">
					<input type="hidden" name="userid" value="%s">'
					,$this->url
					,addslashes($this->user_id)
					);
	
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


		//global $site_checkout_address_indv,$site_checkout_id,$site_url;
		if ($row['Product Web State']=='Online Force Out of Stock'){
				$_form.=sprintf('<tr class="nophp">
						<td colspan=2 style="height:20px;padding:0;margin:0;"><span  style="float:right;font-size:8pt;color:red;font-weight:800;">%s</span>%s</td>
						<td><span class="desc">%s</span></td>
						</tr>
						<input type="hidden"  name="discountpr%s"  value="1,%.2f"  >
						<input type="hidden"  name="product%s"  value="%s %sx %s" >'
						,$out_of_stock
						,addslashes($row['Product Code'])
						,clean_accents(addslashes($row['Product Name']))
						,$i
						,$row['Product Price']
						,$i
						,addslashes($row['Product Code'])
						,addslashes($row['Product Units Per Case'])
						,clean_accents(addslashes($row['Product Name']))
						);
						
				$form.=sprintf('<tr ><td colspan=2 style="height:20px;padding:0;margin:0;"><span style="float:right;font-size:8pt;color:red;font-weight:800;">%s</span>%s</td>
						<td><span class="desc">%s</span></td></tr>
						<input type="hidden"  name="discountpr%s"  value="1,%.2f"  >
						<input type="hidden"  name="product%s"  value="%s %sx %s">'
						,$out_of_stock
						,addslashes($row['Product Code'])
						,clean_accents(addslashes($row['Product Name']))
						,$i
						,$row['Product Price']
						,$i
						,addslashes($row['Product Code'])
						,addslashes($row['Product Units Per Case'])
						,clean_accents(addslashes($row['Product Name']))
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
						,$row['Product Price']
						,addslashes($row['Product Code'])
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
						
				$form.=sprintf('<tr ><td class="first"><span class="price">%.2f</span>%s</td>
						<td class="qty" style="width:3em"><input type="text" class="qty" name="qty%s"  id="qty%s"  /></td>
						<td><span class="desc">%s</span></td></tr>
						<input type="hidden"  name="discountpr%s"  value="1,%.2f"  >
						<input type="hidden"  name="product%s"  value="%s %sx %s">'
						,$row['Product Price']
						,addslashes($row['Product Code'])
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
						,$this->ecommerceURL($secure, $_port, $_protocol, $url, $server));
	
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

	function get_formated_price($row, $locale=''){

		 $data=array(
		'Product Price'=>$row['Product Price'],
		'Product Units Per Case'=>$row['Product Units Per Case'],
		'Product Currency'=>$this->get('Product Currency'),
		'Product Unit Type'=>$row['Product Unit Type'],


		'locale'=>$locale);

		return formated_price($data);
	}
	
		
	 
	
}
?>
