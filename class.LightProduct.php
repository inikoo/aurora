<?php
/*
  
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2011, Inikoo

*/


class LightProduct{
  
  var $id=false;
  var $data=array();
  var $locale;
  var $url;
  var $user_id;
  var $method;
  var $match=true;
  
    function __construct($arg1,$arg2=false) {
    
   
        return $this->get_data('code',$arg1,$arg2);


    }

    function get_data($tag,$id,$id2=false) {
        if ($tag=='id')
            $sql=sprintf("select * from `Product Dimension` where `Product Key`=%s",prepare_mysql($id));
        elseif($tag=='code')
			$sql=sprintf("select * from `Product Dimension` where `Product Code`=%s and `Product Store Key`=%d",prepare_mysql($id),$id2);
       
        
        else
            return false;
			
		
		
        $result=mysql_query($sql);
		
		if(!mysql_num_rows($result))
			$this->match=false;
		
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $this->id=$this->data['Product ID'];
			$this->locale=$this->data['Product Locale'];
        }

		
    }
 
 

 
	function get_full_order_form($type,$data=false){
		switch($type){
			case 'ecommerce':
				
				$this->url=$data['ecommerce_url'];				
				$this->user_id=$data['username'];
				$this->method=$data['method'];
			break;
			
			default:
			break;
		}
	//$this->locale=$row['Product Locale'];
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

		  if ($this->data['Product Web Configuration']=='Online Force Out of Stock') {
		$_form='<br/><span style="color:red;font-weight:800">'.$out_of_stock.'</span>';
		  } else {
		//global $site_checkout_address_indv,$site_checkout_id,$site_url;
		
			if($this->method=='reload'){
			
					$_form=sprintf('<form action="%s" method="post" style="margin-top:2px">
											   <input type="hidden" name="userid" value="%s">
											   <input type="hidden" name="product" value="%s %sx %s">
											   <input type="hidden" name="return" value="%s">
											   <input type="hidden" name="discountpr" value="1,%.2f">
											   <input class="order" type="text" size="1" class="qty" name="qty" value="1">
											   <input type="hidden" name="nnocart">  
											   <input class="submit" type="Submit" value="%s" style="cursor:pointer; font-size:12px;font-family:arial;" ></form>'
							   ,$this->url
							   ,addslashes($this->user_id)
							   ,addslashes($this->data['Product Code'])
							   ,addslashes($this->data['Product Units Per Case'])
							   ,clean_accents(addslashes($this->data['Product Name']))
							   //,$site_url.$_SERVER['PHP_SELF']
							   ,ecommerceURL()
							   ,$this->data['Product Price']
							   ,$this->get('Order Msg')
							   );
			}
			else{
								$_form=sprintf('<input type="hidden" name="action" value="%s">
											   <input type="hidden" name="userid" value="%s">
											   <input type="hidden" name="product" value="%s %sx %s">
											   <input type="hidden" name="return" value="%s">
											   <input type="hidden" name="discountpr" value="1,%.2f">
											   <input class="order" type="text" size="1" class="qty" name="qty" value="1">
											   <input type="hidden" name="nnocart"> 
											   
											   <button id="SC" style="margin-left:10px">%s</button>'
							   ,$this->url
							   ,addslashes($this->user_id)
							   ,addslashes($this->data['Product Code'])
							   ,addslashes($this->data['Product Units Per Case'])
							   ,clean_accents(addslashes($this->data['Product Name']))
							   //,$site_url.$_SERVER['PHP_SELF']
							   ,slfURL()
							   ,$this->data['Product Price']
							   ,$this->get('Order Msg')
							   );
			}
		  }

$_SESSION['logged_in']=1;

			if($this->data['Product RRP']>0)
				$_rrp=sprintf("<span class=\"rrp\">%s</span>",$this->get_formated_rrp($this->locale));
			else
				$_rrp='';


		  $form=sprintf('<div style="font-size:12px;font-family:arial;" class="ind_form"><span class="code">%s</span><br/><span class="name">%sx %s</span><br/><span class="price">%s</span><br/>%s<br/>%s</div>'
				,$this->data['Product Code']
				,$this->data['Product Units Per Case']
				,$this->data['Product Name']
				,$this->get_formated_price($this->locale)
				,$_rrp
				,(isset($_SESSION['logged_in'])?$_form:'')


				);

		  //print $form;exit;
		  return $form;


	}

	function get_order_list_form($data=false){
	   
		$data=$this->data;
		if ($this->locale=='de_DE') {
		$out_of_stock='nicht vorrv§tig';
		$discontinued='ausgelaufen';
		  }
		  elseif($this->locale=='fr_FR') {
		$out_of_stock='Rupture de stock';
		$discontinued='Rupture de stock';
		  }elseif($this->locale=='pl_PL') {
		$out_of_stock='Chwilowo Niedostƒôpne';
		$discontinued='Wyprzedane';
		  }elseif($this->locale=='es_ES') {
		$out_of_stock='Fuera de Stock';
		$discontinued='Agotado';
		  }
		  else {
		$out_of_stock='Out of Stock';
		$discontinued='Discontinued';
		  }

		  $counter=1;//$data['counter'];
		  $options='';//$data['options'];
		  $currency='';//$data['currency'];
		  $rrp='';
		  if (isset($options['show individual rrp']) and $options['show individual rrp'] )
		$rrp=" <span class='rrp_in_list'>(".$this->get_formated_rrp($this->locale).')</span>';

		//mb_convert_encoding($_header, "UTF-8", "ISO-8859-1,UTF-8");



		  if ($this->data['Product Web Configuration']=='Online Force Out of Stock') {
		$form=sprintf('<tr><td class="first">%s</td><td  colspan=2>%s<span  style="color:red;font-weight:800">%s</span></td></tr>'
				 // ,$this->get_formated_price($this->locale)
				  ,$this->data['Product Code']
							  ,mb_convert_encoding($this->data['Product Special Characteristic'],"ISO-8859-1", "UTF-8").' ('.money_locale($this->data['Product Price'],$this->locale,$currency).')'.$rrp

				  ,$out_of_stock
				  );
		  } else {
		$form=sprintf('<tr><td style="width:8em">%s</td><td class="qty"><input type="text"  size="3" class="qty" name="qty%d"  id="qty%d"    /><td><span class="desc">%s</span></td></tr><input type="hidden"  name="dis
		price%d"  value="%.2f"  ><input type="hidden"  name="product%d"  value="%s %dx %s" >'
				  //,money_locale($this->data['Product Price'],$this->locale,$currency)
				  ,$this->data['Product Code'].' '.money_locale($this->data['Product Price'],$this->locale,$currency).''
				  ,$counter
				  ,$counter
				  ,mb_convert_encoding($this->data['Product Special Characteristic'],"ISO-8859-1", "UTF-8")
				  ,$counter
				  ,$this->data['Product Price']
				  ,$counter
				  ,$this->data['Product Code']
				  ,$this->data['Product Units Per Case']
				  ,clean_accents($this->data['Product Name'])
				  );
			
				  
				  
				  
		  }

		  return $form."\n";


	}
	
	
	function get_info(){

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
		$offline='Not for Sale';
		  }

		  if ($this->data['Product Web State']=='Out of Stock') {
		$_form='<br/><span style="color:red;font-weight:800">'.$out_of_stock.'</span>';
		  }
		elseif($this->data['Product Web State']=='Offline'){
		$_form='<br/><span style="color:red;font-weight:800">'.$offline.'</span>';
		  }
		elseif($this->data['Product Web State']=='Discontinued'){
		$_form='<br/><span style="color:red;font-weight:800">'.$discontinued.'</span>';
		  }
		  else {
		//global $site_checkout_address_indv,$site_checkout_id,$site_url;
		
			
			
					$_form=sprintf('<input type="hidden" name="product" value="%s %sx %s">'
							   ,addslashes($this->data['Product Code'])
							   ,addslashes($this->data['Product Units Per Case'])
							   ,clean_accents(addslashes($this->data['Product Name']))
							   );
		  }

		  $_SESSION['logged_in']=1;
		  $form=sprintf('<div style="font-size:12px;font-family:arial;" class="ind_form"><span class="code">%s</span><br/><span class="name">%sx %s</span><br/><span style="color:#444;font-style: italic;">Please login to see wholesale prices</span>%s</div>'
				,$this->data['Product Code']
				,$this->data['Product Units Per Case']
				,$this->data['Product Name']
				//,$this->get_formated_price($this->locale)
				//,$this->get_formated_rrp($this->locale)
				,(isset($_SESSION['logged_in'])?$_form:'')


				);

		  //print $form;exit;
		  return $form;


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
		
	 
	function get_formated_price($locale=''){

		 $data=array(
		'Product Price'=>$this->data['Product Price'],
		'Product Units Per Case'=>$this->data['Product Units Per Case'],
		'Product Currency'=>$this->get('Product Currency'),
		'Product Unit Type'=>$this->data['Product Unit Type'],


		'locale'=>$locale);

		return formated_price($data);
	}
	
	function get_formated_rrp($locale=''){

		$data=array(
		'Product RRP'=>$this->data['Product RRP'],
		'Product Units Per Case'=>$this->data['Product Units Per Case'],
		'Product Currency'=>$this->get('Product Currency'),
		'Product Unit Type'=>$this->data['Product Unit Type'],
		'locale'=>$locale);

		return formated_rrp($data);
	}
}
?>