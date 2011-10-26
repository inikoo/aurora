<?php
/*

  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2011, Inikoo

*/
//require_once 'common_functions.php';

class LightFamily {

    var $id=false;
    var $data=array();
    var $locale;
    var $url;
    var $user_id;
    var $method;
    var $match=true;
	var $currency;
	
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

        if (!mysql_num_rows($result))
            $this->match=false;

        //print $sql;
        //print mysql_error();
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $this->id=$this->data['Product Family Key'];

            $sql=sprintf("select `Store Locale`,`Store Currency Code` from `Store Dimension` where `Store Key`=%d ",$this->data['Product Family Store Key']);

            $res2=mysql_query($sql);
            if ($row2=mysql_fetch_assoc($res2)) {
                $this->currency=$row2['Store Currency Code'];
                $this->locale=$row2['Store Locale'];
            }

        }




    }


	function get_product_in_family_with_order_form($data, $header=false, $type, $secure, $_port, $_protocol, $url, $server, $ecommerce_url, $username, $method, $options=false, $user, $path) {

		
		if(isset($options['order_by']))
			if(strtolower($options['order_by']) == 'price')
				$order_by='`Product RRP`';
			elseif(strtolower($options['order_by']) == 'code')
				$order_by='`Product Code File As`';
			elseif(strtolower($options['order_by']) == 'name')
				$order_by='`Product Name`';
			else
				$order_by='`Product Code File As`';
		else
			$order_by='`Product Code File As`';
			
		if(isset($options['limit']))
			$limit='limit '.$options['limit'];
		else
			$limit='';

		if(isset($options['range'])){
			list($range1, $range2)=explode(":", strtoupper($options['range']));
			$range_where=sprintf("and ( (ord(`Product Name`) >= %d and ord(`Product Name`) <= %d) || (ord(`Product Name`) >= %d and ord(`Product Name`) <= %d))", ord($range1), ord($range2), ord($range1)+32, ord($range2)+32);
			
		}
		else 
			$range_where="";//"  true";
			

        $print_header=true;
		$print_rrp=true;
		if(isset($options['rrp'])){
			//print 'ok';
			$print_rrp=$options['rrp'];
		}
		
		$show_unit=true;
		if(isset($options['unit'])){
			//print 'ok';
			$show_unit=$options['unit'];
		}
		
		
        $print_price=true;

        switch ($type) {
        case 'ecommerce':
            $this->url=$ecommerce_url;
            $this->user_id=$username;
            $this->method=$method;
            break;
		case 'custom':
			$this->method='sc';
			$this->user=$user;
			break;
        default:
            break;
        }

        $sql=sprintf("select count(*) as num from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline' ", $this->id);
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $number_records=$row['num'];
        } else {
            // NO PRODUCTS
            return;
        }




        if ($this->locale=='de_DE') {
            $out_of_stock='nicht vorrv§tig';
            $discontinued='ausgelaufen';
        }
        if ($this->locale=='de_DE') {
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

		

        $form=sprintf('<table class="product_list form" >' );

        if ($print_header) {

            $rrp_label='';

            if ($print_rrp) {

                $sql=sprintf("select min(`Product RRP`/`Product Units Per Case`) rrp_min, max(`Product RRP`/`Product Units Per Case`) as rrp_max,avg(`Product RRP`/`Product Units Per Case`)  as rrp_avg from `Product Dimension` where `Product Family Key`=%d and `Product Web State` in ('For Sale','Out of Stock') ", $this->id);

                $res=mysql_query($sql);
                if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
                    $rrp=$row['rrp_min'];


                    $rrp= $this->get_formated_rrp(array(
                                                      'Product RRP'=>$rrp,
                                                      'Product Units Per Case'=>1,
                                                      'Product Unit Type'=>''),array('prefix'=>false, 'show_unit'=>$show_unit));



					if($row['rrp_avg']<=0){
						$rrp_label='';
                        $print_rrp=false;
					}
                    elseif ($row['rrp_avg']==$row['rrp_min']) {
                        $rrp_label='<br/><span class="rrp">RRP: '.$rrp.'</span>';
                        $print_rrp=false;
                    } else
                        $rrp_label='<br/><span class="rrp">RRP from '.$rrp.'</span>';



                } else {
                    return;
                }
            }

            if ($print_price) {

                $sql=sprintf("select min(`Product Price`/`Product Units Per Case`) price_min, max(`Product Price`/`Product Units Per Case`) as price_max,avg(`Product Price`/`Product Units Per Case`)  as price_avg from `Product Dimension` where `Product Family Key`=%d and `Product Web State` in ('For Sale','Out of Stock') ", $this->id);

                $res=mysql_query($sql);
                if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
                    $price=$row['price_min'];


                    $price= $this->get_formated_price(array(
                                                          'Product Price'=>$price,
                                                          'Product Units Per Case'=>1,
                                                          'Product Unit Type'=>'',
                                                          'Label'=>($row['price_avg']==$row['price_min']?'price':'from')

                                                      ));


                    $price_label='<br/><span class="price">'.$price.'</span>';




                } else {
                    return;
                }
            }


            $form.='<tr class="list_info" ><td colspan="4"><p>'.$this->data['Product Family Name'].$price_label.$rrp_label.'</p></td><td>';


        }

		if($this->method=='reload'){

			$form.=sprintf('
						   <form action="%s" method="post">
						   <input type="hidden" name="userid" value="%s">
						   <input type="hidden" name="nnocart"> '
						   ,$ecommerce_url
						   ,addslashes($username)

						  );
			$counter=1;
			//$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline' ", $this->id);
			$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline'  %s order by %s %s", $this->id, $range_where, $order_by, $limit);
			//print $sql;
			$result=mysql_query($sql);
			//while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			foreach($data as $row){



				if ($print_rrp) {

					$rrp= $this->get_formated_rrp(array(
													  'Product RRP'=>$row['Product RRP'],
													  'Product Units Per Case'=>$row['Product Units Per Case'],
													  'Product Unit Type'=>$row['Product Unit Type']), array('show_unit'=>$show_unit));

				} else {
					$rrp='';
				}





				if ($row['Product Web State']=='Out of Stock') {
					$class_state='out_of_stock';
				   
					$state=' <span class="out_of_stock">('.$out_of_stock.')</span>';

				}
				elseif ($row['Product Web State']=='Discontinued') {
					$class_state='discontinued';
					$state=' <span class="discontinued">'.$discontinued.'</span>';

				}
				else {

					$class_state='';
					$state='';


				}

				$price= $this->get_formated_price(array(
													  'Product Price'=>$row['Product Price'],
													  'Product Units Per Case'=>1,
													  'Product Unit Type'=>'',
													  'Label'=>(''),
													  'price per unit text'=>''

												  ));




				if ($counter==0)
					$tr_class='class="top"';
				else
					$tr_class='';
				$form.=sprintf('<tr %s >
										<input type="hidden"  name="discountpr%s"     value="1,%.2f"  >
										<input type="hidden"  name="product%s"  value="%s %s" >
										<td class="code">%s</td><td class="price">%s</td>
										<td class="input"><input name="qty%s"  id="qty%s"  type="text" value="" class="%s"  %s ></td>
										<td class="description">%s %s</td><td class="rrp">%s</td>
										</tr>'."\n",
							   $tr_class,
							   $counter,$row['Product Price'],
							   $counter,$row['Product Code'],$row['Product Units Per Case'].'x '.$row['Product Special Characteristic'],

							   $row['Product Code'],
							   $price,
							   $counter,
							   $counter,
								 $class_state,
								 ($class_state!=''?' readonly="readonly" ':''),
							   $row['Product Units Per Case'].'x '.$row['Product Special Characteristic'],
							   $state,
							   $rrp
							  
							  );





				$counter++;
			}

			$form.=sprintf('<tr class="space"><td colspan="4">
						   <input type="hidden" name="return" value="%s">
						   <input class="button" name="Submit" type="submit"  value="Order">
						   <input class="button" name="Reset" type="reset"  id="Reset" value="Reset"></td></tr></form></table>
						   '
						   ,ecommerceURL($secure, $_port, $_protocol, $url, $server));

		}
		else if($this->method=='sc'){
			$form.=sprintf('
						   <form action="%s" method="post">
						   <input type="hidden" name="userid" value="%s">
						   <input type="hidden" name="nnocart"> '
						   ,$ecommerce_url
						   ,addslashes($username)

						  );
			$counter=1;
			//$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline' ", $this->id);
			$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline'  %s order by %s %s", $this->id, $range_where, $order_by, $limit);
			//print $sql;
			$result=mysql_query($sql);
			//while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			foreach($data as $row){



				if ($print_rrp) {

					$rrp= $this->get_formated_rrp(array(
													  'Product RRP'=>$row['Product RRP'],
													  'Product Units Per Case'=>$row['Product Units Per Case'],
													  'Product Unit Type'=>$row['Product Unit Type']), array('show_unit'=>$show_unit));

				} else {
					$rrp='';
				}





				if ($row['Product Web State']=='Out of Stock') {
					$class_state='out_of_stock';
				   
					$state=' <span class="out_of_stock">('.$out_of_stock.')</span>';

				}
				elseif ($row['Product Web State']=='Discontinued') {
					$class_state='discontinued';
					$state=' <span class="discontinued">'.$discontinued.'</span>';

				}
				else {

					$class_state='';
					$state='';


				}

				$price= $this->get_formated_price(array(
													  'Product Price'=>$row['Product Price'],
													  'Product Units Per Case'=>1,
													  'Product Unit Type'=>'',
													  'Label'=>(''),
													  'price per unit text'=>''

												  ));




				if ($counter==0)
					$tr_class='class="top"';
				else
					$tr_class='';
					
					$sql=sprintf("select * from `Order Dimension` where `Order Customer Key`=%d and `Order Current Dispatch State`='In Process' order by `Order Public ID` DESC", $this->user->get('User Parent Key'));
									$result1=mysql_query($sql);
									if($row1=mysql_fetch_array($result1))
										$order_exist=true;
									
									$order_key=$row1['Order Key'];
									
									$sql=sprintf("select `Order Quantity` from `Order Transaction Fact` where `Order Key`=%d and `Product ID`=%d", $order_key, $row['Product ID']);
									$result1=mysql_query($sql);
									if($row1=mysql_fetch_array($result1))
										$old_qty=$row1['Order Quantity'];
									else
										$old_qty=0;
										
									$form.=sprintf('<tr %s >
															<input type="hidden" id="order_id%d" value="%d">
														   <input type="hidden" id="pid%d" value="%d">
														   <input type="hidden" id="old_qty%d" value="%d">
															<td class="code">%s</td>
															<td class="price">%s</td>
															<td class="input"><input  id="qty%s"  type="text" value="1" class="%s"  %s ></td>
															<td><img src="%sinikoo_files/art/icons/basket_add.png" onClick="order_single_product(%d)" /></td>
															<td class="description">%s %s</td><td class="rrp">%s</td>
															<td><span id="loading%d"></span></td>
															</tr>'."\n",
												   $tr_class,
												   
												   $row['Product ID'],$order_key,
													$row['Product ID'],$row['Product ID'],
													$row['Product ID'],$old_qty,
												   $row['Product Code'],
												   $price,
												  
												   $row['Product ID'],
													 $class_state,
													 ($class_state!=''?' readonly="readonly" ':''),
													 $path,
													$row['Product ID'], 
												   $row['Product Units Per Case'].'x '.$row['Product Special Characteristic'],
												   $state,
												   $rrp,
												  $row['Product ID']
												  );


				$counter++;
			}

			$form.=sprintf('</form></table>');
		}
        return $form;
    }
	
	
	
	
//Start
    function get_product_in_family_no_price($data, $header_options=false, $options=false) {

		if(isset($options['order_by']))
			if(strtolower($options['order_by']) == 'price')
				$order_by='`Product RRP`';
			elseif(strtolower($options['order_by']) == 'code')
				$order_by='`Product Code File As`';
			elseif(strtolower($options['order_by']) == 'name')
				$order_by='`Product Name`';
			else
				$order_by='`Product Code File As`';
		else
			$order_by='`Product Code File As`';
			
		if(isset($options['limit']))
			$limit='limit '.$options['limit'];
		else
			$limit='';

		if(isset($options['range'])){
			list($range1, $range2)=explode(":", strtoupper($options['range']));
			$range_where=sprintf("and ( (ord(`Product Name`) >= %d and ord(`Product Name`) <= %d) || (ord(`Product Name`) >= %d and ord(`Product Name`) <= %d))", ord($range1), ord($range2), ord($range1)+32, ord($range2)+32);
			
		}
		else 
			$range_where="";//"  true";				
		
		
		$show_unit=true;
		if(isset($options['unit'])){
			//print 'ok';
			$show_unit=$options['unit'];
		}
		
        $print_header=true;
        $print_rrp=false;
        $print_register=true;

        $sql=sprintf("select count(*) as num from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline' ", $this->id);

        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $number_records=$row['num'];
        } else {
            // NO PRODUCTS XXX
            return;
        }

        if ($this->locale=='de_DE') {
            $out_of_stock='nicht vorrv§tig';
            $discontinued='ausgelaufen';
            $register='In order to see prices register';

        }
        if ($this->locale=='de_DE') {
            $out_of_stock='nicht vorrv§tig';
            $discontinued='ausgelaufen';
            $register='In order to see prices register';

        }
        elseif($this->locale=='es_ES') {
            $out_of_stock='Fuera de Stock';
            $discontinued='Fuera de Stock';
            $register='In order to see prices register';

        }
        elseif($this->locale=='fr_FR') {
            $out_of_stock='Rupture de stock';
            $discontinued='Rupture de stock';
            $register='In order to see prices register';

        }
        else {
            $out_of_stock='Out of Stock';
            $discontinued='Discontinued';
            $register='Please login to see wholesale prices';
        }
        $form=sprintf('<table class="product_list" >' );

        if ($print_header) {

            $rrp_label='';

            if ($print_rrp) {

                if ($number_records==1) {

                } elseif ($number_records>2) {

                    $sql=sprintf("select min(`Product RRP`/`Product Units Per Case`) min, max(`Product RRP`/`Product Units Per Case`) as max ,avg(`Product RRP`/`Product Units Per Case`)  as avg from `Product Dimension` where `Product Family Key`=%d and `Product Web State` in ('For Sale','Out of Stock') ", $this->id);
                    $res=mysql_query($sql);
                    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
                        $rrp=$row['min'];


                        $rrp= $this->get_formated_rrp(array(
                                                          'Product RRP'=>$rrp,
                                                          'Product Units Per Case'=>1,
                                                          'Product Unit Type'=>''),array('prefix'=>false,'show_unit'=>$show_unit));

                        if($row['rrp_avg']<=0){
							$rrp_label='';
							$print_rrp=false;
						}		
						if ($row['avg']==$row['min'])
                            $rrp_label='<br/>RRP: '.$rrp;
                        else
                            $rrp_label='<br/>RRP from '.$rrp;



                    } else {
                        return;
                    }

                }

            }


            $form.='<tr class="list_info" ><td colspan="4"><p>'.$this->data['Product Family Name'].$rrp_label.'</p></td><td>';
            if ($print_register and $number_records>10)
                $form.=sprintf('<tr class="last register"><td colspan="4">%s</td></tr>',$register);


        }
		
        //$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline' order by %s %s", $this->id, $order_by, $limit);
		$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline'  %s order by %s %s", $this->id, $range_where, $order_by, $limit);
        //print $sql;
        $result=mysql_query($sql);
        $counter=0;
        //while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		foreach($data as $row){


            if ($print_rrp) {

                $rrp= $this->get_formated_rrp(array(
                                                  'Product RRP'=>$row['Product RRP'],
                                                  'Product Units Per Case'=>$row['Product Units Per Case'],
                                                  'Product Unit Type'=>$row['Product Unit Type']), array('show_unit'=>$show_unit));

            } else {
                $rrp='';
            }
            if ($row['Product Web State']=='Out of Stock') {
                $class_state='out_of_stock';
                $state=$out_of_stock;

            }
            elseif ($row['Product Web State']=='Discontinued') {
                $class_state='discontinued';
                $state=$discontinued;

            }
            else {

                $class_state='';
                $state='';


            }


            if ($counter==0)
                $tr_class='class="top"';
            else
                $tr_class='';
            $form.=sprintf('<tr %s ><td class="code">%s</td><td class="description">%s</td><td class="rrp">%s</td><td class="%s">%s</td></tr>',
                           $tr_class,
                           $row['Product Code'],
                           $row['Product Units Per Case'].'x '.$row['Product Special Characteristic'],
                           $rrp,
                           $class_state,
                           $state
                          );


            $counter++;
        }

        if ($print_register)
            $form.=sprintf('<tr class="last register"><td colspan="4">%s</td></tr>',$register);
        $form.=sprintf('</table>');
        return $form;
    }
//End
	
	
	
	
	
	

    function get_product_list_no_price($header_options=false, $options=false) {

		if(isset($options['order_by']))
			if(strtolower($options['order_by']) == 'price')
				$order_by='`Product RRP`';
			elseif(strtolower($options['order_by']) == 'code')
				$order_by='`Product Code File As`';
			elseif(strtolower($options['order_by']) == 'name')
				$order_by='`Product Name`';
			else
				$order_by='`Product Code File As`';
		else
			$order_by='`Product Code File As`';
			
		if(isset($options['limit']))
			$limit='limit '.$options['limit'];
		else
			$limit='';

		if(isset($options['range'])){
			list($range1, $range2)=explode(":", strtoupper($options['range']));
			$range_where=sprintf("and ( (ord(`Product Name`) >= %d and ord(`Product Name`) <= %d) || (ord(`Product Name`) >= %d and ord(`Product Name`) <= %d))", ord($range1), ord($range2), ord($range1)+32, ord($range2)+32);
			
		}
		else 
			$range_where="";//"  true";				
	    $show_unit=true;
		if(isset($options['unit'])){
			//print 'ok';
			$show_unit=$options['unit'];
		}
        $print_header=true;
        $print_rrp=false;
        $print_register=true;

        $sql=sprintf("select count(*) as num from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline' ", $this->id);

        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $number_records=$row['num'];
        } else {
            // NO PRODUCTS XXX
            return;
        }

        if ($this->locale=='de_DE') {
            $out_of_stock='nicht vorrv§tig';
            $discontinued='ausgelaufen';
            $register='In order to see prices register';

        }
        if ($this->locale=='de_DE') {
            $out_of_stock='nicht vorrv§tig';
            $discontinued='ausgelaufen';
            $register='In order to see prices register';

        }
        elseif($this->locale=='es_ES') {
            $out_of_stock='Fuera de Stock';
            $discontinued='Fuera de Stock';
            $register='In order to see prices register';

        }
        elseif($this->locale=='fr_FR') {
            $out_of_stock='Rupture de stock';
            $discontinued='Rupture de stock';
            $register='In order to see prices register';

        }
        else {
            $out_of_stock='Out of Stock';
            $discontinued='Discontinued';
            $register=sprintf('<br/><span style="color:red;font-style: italic; ">Please <a style="color:red;" href="#" onclick="show_login_dialog()">login</a> or <a style="color:red;" href="#" onclick="show_register_dialog()">register</a> to see wholesale prices</span>');
        }
        $form=sprintf('<table class="product_list" >' );

        if ($print_header) {

            $rrp_label='';

            if ($print_rrp) {

                if ($number_records==1) {

                } elseif ($number_records>2) {

                    $sql=sprintf("select min(`Product RRP`/`Product Units Per Case`) min, max(`Product RRP`/`Product Units Per Case`) as max ,avg(`Product RRP`/`Product Units Per Case`)  as avg from `Product Dimension` where `Product Family Key`=%d and `Product Web State` in ('For Sale','Out of Stock') ", $this->id);
                    $res=mysql_query($sql);
                    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
                        $rrp=$row['min'];


                        $rrp= $this->get_formated_rrp(array(
                                                          'Product RRP'=>$rrp,
                                                          'Product Units Per Case'=>1,
                                                          'Product Unit Type'=>''),array('prefix'=>false, 'show_unit'=>$show_unit));

                        if($row['rrp_avg']<=0){
							$rrp_label='';
							$print_rrp=false;
						}
						elseif ($row['avg']==$row['min'])
                            $rrp_label='<br/>RRP: '.$rrp;
                        else
                            $rrp_label='<br/>RRP from '.$rrp;



                    } else {
                        return;
                    }

                }

            }


            $form.='<tr class="list_info" ><td colspan="4"><p>'.$this->data['Product Family Name'].$rrp_label.'</p></td><td>';
            if ($print_register and $number_records>10)
                $form.=sprintf('<tr class="last register"><td colspan="4">%s</td></tr>',$register);


        }
		
        //$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline' order by %s %s", $this->id, $order_by, $limit);
		$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline'  %s order by %s %s", $this->id, $range_where, $order_by, $limit);
        //print $sql;
        $result=mysql_query($sql);
        $counter=0;
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {



            if ($print_rrp) {

                $rrp= $this->get_formated_rrp(array(
                                                  'Product RRP'=>$row['Product RRP'],
                                                  'Product Units Per Case'=>$row['Product Units Per Case'],
                                                  'Product Unit Type'=>$row['Product Unit Type']), array('show_unit'=>$show_unit));

            } else {
                $rrp='';
            }
            if ($row['Product Web State']=='Out of Stock') {
                $class_state='out_of_stock';
                $state='('.$out_of_stock.')';

            }
            elseif ($row['Product Web State']=='Discontinued') {
                $class_state='discontinued';
                $state='('.$discontinued.')';

            }
            else {

                $class_state='';
                $state='';


            }


            if ($counter==0)
                $tr_class='class="top"';
            else
                $tr_class='';
            $form.=sprintf('<tr %s ><td class="code">%s</td><td class="description">%s   <span class="%s">%s</span></td><td class="rrp">%s</td></tr>',
                           $tr_class,
                           $row['Product Code'],
                           $row['Product Units Per Case'].'x '.$row['Product Special Characteristic'],
                           $class_state,
                           $state,
                           $rrp
                         
                          );


            $counter++;
        }

        if ($print_register)
            $form.=sprintf('<tr class="last register"><td colspan="4">%s</td></tr>',$register);
        $form.=sprintf('</table>');
        return $form;
    }



    function get_product_list_with_order_form($header, $type, $secure, $_port, $_protocol, $url, $server, $ecommerce_url, $username, $method, $options=false, $user, $path) {

		
		if(isset($options['order_by']))
			if(strtolower($options['order_by']) == 'price')
				$order_by='`Product RRP`';
			elseif(strtolower($options['order_by']) == 'code')
				$order_by='`Product Code File As`';
			elseif(strtolower($options['order_by']) == 'name')
				$order_by='`Product Name`';
			else
				$order_by='`Product Code File As`';
		else
			$order_by='`Product Code File As`';
			
		if(isset($options['limit']))
			$limit='limit '.$options['limit'];
		else
			$limit='';

		if(isset($options['range'])){
			list($range1, $range2)=explode(":", strtoupper($options['range']));
			$range_where=sprintf("and ( (ord(`Product Name`) >= %d and ord(`Product Name`) <= %d) || (ord(`Product Name`) >= %d and ord(`Product Name`) <= %d))", ord($range1), ord($range2), ord($range1)+32, ord($range2)+32);
			
		}
		else 
			$range_where="";//"  true";
		$print_rrp=true;
		if(isset($options['rrp'])){
			$print_rrp=$options['rrp'];
		}
		$show_unit=false;
		if(isset($options['unit'])){
			//print 'ok';
			$show_unit=$options['unit'];
		}
		
		
        $print_header=true;
        
        $print_price=true;

        switch ($type) {
        case 'ecommerce':
            $this->url=$ecommerce_url;
            $this->user_id=$username;
            $this->method=$method;
            break;
		case 'custom':
			$this->method='sc';
			$this->user=$user;
			break;
        default:
            break;
        }

        $sql=sprintf("select count(*) as num from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline' ", $this->id);
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $number_records=$row['num'];
        } else {
            // NO PRODUCTS
            return;
        }



        if ($this->locale=='de_DE') {
            $out_of_stock='nicht vorrv§tig';
            $discontinued='ausgelaufen';
        }
        if ($this->locale=='de_DE') {
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



        $form=sprintf('<table class="product_list form" >' );

        if ($print_header) {

            $rrp_label='';

            if ($print_rrp) {

                $sql=sprintf("select min(`Product RRP`/`Product Units Per Case`) rrp_min, max(`Product RRP`/`Product Units Per Case`) as rrp_max,avg(`Product RRP`/`Product Units Per Case`)  as rrp_avg from `Product Dimension` where `Product Family Key`=%d and `Product Web State` in ('For Sale','Out of Stock') %s %s", $this->id,$range_where,$limit);

                $res=mysql_query($sql);
                if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
                    $rrp=$row['rrp_min'];


                    $rrp= $this->get_formated_rrp(array(
                                                      'Product RRP'=>$rrp,
                                                      'Product Units Per Case'=>1,
                                                      'Product Unit Type'=>''),array('prefix'=>false, 'show_unit'=>$show_unit));



						if($row['rrp_avg']<=0){
							$rrp_label='';
							$print_rrp=false;
						}
						elseif  ($row['rrp_avg']==$row['rrp_min']) {
							$rrp_label='<br/><span class="rrp">RRP: '.$rrp.'</span>';
							$print_rrp=false;
						} else
							$rrp_label='<br/><span class="rrp">RRP from '.$rrp.'</span>';



                } else {
                    return;
                }
            }

            if ($print_price) {

                $sql=sprintf("select min(`Product Price`/`Product Units Per Case`) price_min, max(`Product Price`/`Product Units Per Case`) as price_max,avg(`Product Price`/`Product Units Per Case`)  as price_avg from `Product Dimension` where `Product Family Key`=%d and `Product Web State` in ('For Sale','Out of Stock') %s %s", $this->id,$range_where,$limit);

                $res=mysql_query($sql);

                if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

					
                    $price=$row['price_min'];


                    $price= $this->get_formated_price(array(
                                                          'Product Price'=>$price,
                                                          'Product Units Per Case'=>1,
                                                          'Product Unit Type'=>'',
                                                          'Label'=>($row['price_avg']==$row['price_min']?'price':'from')

                                                      ));


                    $price_label='<br/><span class="price">'.$price.'</span>';

					if($row['price_min']==null)
						$price_label='';


                } else {
                    return;
                }
            }


            $form.='<tr class="list_info" ><td colspan="4"><p>'.$this->data['Product Family Name'].$price_label.$rrp_label.'</p></td><td>';


        }


		if($this->method=='reload'){
			$form.=sprintf('
						   <form action="%s" method="post">
						   <input type="hidden" name="userid" value="%s">
						   <input type="hidden" name="nnocart"> '
						   ,$ecommerce_url
						   ,addslashes($username)

						  );
			$counter=1;
			//$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline' ", $this->id);
			$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline'  %s order by %s %s", $this->id, $range_where, $order_by, $limit);
			//print $sql;
			$result=mysql_query($sql);
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {



				if ($print_rrp) {

					$rrp= $this->get_formated_rrp(array(
													  'Product RRP'=>$row['Product RRP'],
													  'Product Units Per Case'=>$row['Product Units Per Case'],
													  'Product Unit Type'=>$row['Product Unit Type']), array('show_unit'=>$show_unit));

				} else {
					$rrp='';
				}





				if ($row['Product Web State']=='Out of Stock') {
					$class_state='out_of_stock';
				   
					$state=' <span class="out_of_stock">('.$out_of_stock.')</span>';

				}
				elseif ($row['Product Web State']=='Discontinued') {
					$class_state='discontinued';
					$state=' <span class="discontinued">('.$discontinued.')</span>';

				}
				else {

					$class_state='';
					$state='';


				}

				$price= $this->get_formated_price(array(
													  'Product Price'=>$row['Product Price'],
													  'Product Units Per Case'=>1,
													  'Product Unit Type'=>'',
													  'Label'=>(''),
													  'price per unit text'=>''

												  ));




				if ($counter==0)
					$tr_class='class="top"';
				else
					$tr_class='';
				$form.=sprintf('<tr %s >
										<input type="hidden"  name="discountpr%s"     value="1,%.2f"  >
										<input type="hidden"  name="product%s"  value="%s %s" >
										<td class="code">%s</td><td class="price">%s</td>
										<td class="input"><input name="qty%s"  id="qty%s"  type="text" value="" class="%s"  %s ></td>
										<td class="description">%s %s</td><td class="rrp">%s</td>
										</tr>'."\n",
							   $tr_class,
							   $counter,$row['Product Price'],
							   $counter,$row['Product Code'],$row['Product Units Per Case'].'x '.$row['Product Special Characteristic'],

							   $row['Product Code'],
							   $price,
							   $counter,
							   $counter,
								 $class_state,
								 ($class_state!=''?' readonly="readonly" ':''),
							   $row['Product Units Per Case'].'x '.$row['Product Special Characteristic'],
							   $state,
							   $rrp
							  
							  );





				$counter++;
			}

			if($counter==1){
				$form.='</td><tr><td>Product is Discontinued</td></tr>';
			}
			else{
			$form.=sprintf('<tr class="space"><td colspan="4">
						   <input type="hidden" name="return" value="%s">				   
						   <input class="button" name="Submit" type="submit"  value="Order">
						   <input class="button" name="Reset" type="reset"  id="Reset" value="Reset"></td></tr></form></table>
						   '
						   ,ecommerceURL($secure, $_port, $_protocol, $url, $server));
			}
		}
		else if($this->method=='sc'){
		/*
			$form.=sprintf('
						   <form action="%s" method="post">
						   <input type="hidden" name="userid" value="%s">
						   <input type="hidden" name="nnocart"> '
						   ,$ecommerce_url
						   ,addslashes($username)

						  );
		*/
			$counter=1;
			//$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline' ", $this->id);
			$sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline'  %s order by %s %s", $this->id, $range_where, $order_by, $limit);
			//print $sql;
			$result=mysql_query($sql);
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {



				if ($print_rrp) {

					$rrp= $this->get_formated_rrp(array(
													  'Product RRP'=>$row['Product RRP'],
													  'Product Units Per Case'=>$row['Product Units Per Case'],
													  'Product Unit Type'=>$row['Product Unit Type']), array('show_unit'=>$show_unit));

				} else {
					$rrp='';
				}





				if ($row['Product Web State']=='Out of Stock') {
					$class_state='out_of_stock';
				   
					$state=' <span class="out_of_stock">('.$out_of_stock.')</span>';

				}
				elseif ($row['Product Web State']=='Discontinued') {
					$class_state='discontinued';
					$state=' <span class="discontinued">('.$discontinued.')</span>';

				}
				else {

					$class_state='';
					$state='';


				}

				$price= $this->get_formated_price(array(
													  'Product Price'=>$row['Product Price'],
													  'Product Units Per Case'=>1,
													  'Product Unit Type'=>'',
													  'Label'=>(''),
													  'price per unit text'=>''

												  ));




				if ($counter==0)
					$tr_class='class="top"';
				else
					$tr_class='';
					
				$sql=sprintf("select * from `Order Dimension` where `Order Customer Key`=%d and `Order Current Dispatch State`='In Process' order by `Order Public ID` DESC", $this->user->get('User Parent Key'));
				$result1=mysql_query($sql);
				if($row1=mysql_fetch_array($result1))
					$order_exist=true;
				
				$order_key=$row1['Order Key'];
				
				$sql=sprintf("select `Order Quantity` from `Order Transaction Fact` where `Order Key`=%d and `Product ID`=%d", $order_key, $row['Product ID']);
				$result1=mysql_query($sql);
				if($row1=mysql_fetch_array($result1))
					$old_qty=$row1['Order Quantity'];
				else
					$old_qty=0;
					
				$form.=sprintf('<tr %s >
										<input type="hidden" id="order_id%d" value="%d">
									   <input type="hidden" id="pid%d" value="%d">
									   <input type="hidden" id="old_qty%d" value="%d">
										<td class="code">%s</td>
										<td class="price">%s</td>
										<td class="input"><input  id="qty%s"  type="text" value="1" class="%s"  %s ></td>
										<td><img src="%sinikoo_files/art/icons/basket_add.png" onClick="order_single_product(%d)" /></td>
										<td class="description">%s %s</td><td class="rrp">%s</td>
										<td><span id="loading%d"></span></td>
										</tr>'."\n",
							   $tr_class,
							   
							   $row['Product ID'],$order_key,
								$row['Product ID'],$row['Product ID'],
								$row['Product ID'],$old_qty,
							   $row['Product Code'],
							   $price,
							  
							   $row['Product ID'],
								 $class_state,
								 ($class_state!=''?' readonly="readonly" ':''),
								 $path,
								$row['Product ID'], 
							   $row['Product Units Per Case'].'x '.$row['Product Special Characteristic'],
							   $state,
							   $rrp,
							  $row['Product ID']
							  );





				$counter++;
			}

			if($counter==1){
				$form.='</td><tr><td>Product is Discontinued</td></tr>';
			}
			else{
			$form.=sprintf('</form></table>
						   '
						   ,ecommerceURL($secure, $_port, $_protocol, $url, $server));
			}
		}

        return $form;
    }



    function get($key) {

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
        if (strpos($url, "?")) {
            return $protocol."://".$server.$port.$this->strleft1(strtolower($url), "?");
        } else
            return $protocol."://".$server.$port.$url;
    }


    function strleft1($s1, $s2) {
        return substr($s1, 0, strpos($s1, $s2));
    }

    function get_formated_price($data,$options=false) {

        $_data=array(
                   'Product Price'=>$data['Product Price'],
                   'Product Units Per Case'=>$data['Product Units Per Case'],
                   'Product Currency'=>$this->currency,
                   'Product Unit Type'=>$data['Product Unit Type'],
                   'Label'=>$data['Label'],
                   'locale'=>$this->locale,

               );

        if (isset($data['price per unit text']))
            $_data['price per unit text']=$data['price per unit text'];

        return formated_price($_data,$options);
    }



    function get_formated_rrp($data,$options=false) {

        $data=array(
                  'Product RRP'=>$data['Product RRP'],
                  'Product Units Per Case'=>$data['Product Units Per Case'],
                  'Product Currency'=>$this->currency,
                  'Product Unit Type'=>$data['Product Unit Type'],
                  'locale'=>$this->locale);

        return formated_rrp($data,$options);
    }
	
	function get_found_in(){
		//print 'found in';
		//print $this->data['Product Family Main Department Key'];
		/*
		$sql=sprintf("select `Product Department Name`, `Product Department URL` from `Product Department Dimension` where `Product Department Key`=%d", $this->data['Product Family Main Department Key']);
		print $sql;
		$result=mysql_query($sql);
		if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			$found_in_label=$row['Product Department Name'];
			$found_in_url=$row['Product Department URL'];
		}
		*/
		$found_in_label="";
		$found_in_url="";
		return array($found_in_label,$found_in_url);
		
	}

	function get_see_also($code, $base_url){
	
		$department_codes=array();
		$department_keys=array();
		$see_also=array();
		
		$sql=sprintf("select `Product Family Name` from `Product Family Dimension` where `Product Family Code`='%s'", $code);
		$result=mysql_query($sql);
		if($row=mysql_fetch_array($result));
		$this_family_name=$row['Product Family Name'];

		//print $this_family_name;
		
		$sql=sprintf("select `Product Family Main Department Code` from `Product Family Dimension` where `Product Family Code`='%s'", $code);
		$result=mysql_query($sql);
		while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			$department_codes[]=$row['Product Family Main Department Code'];
		}
		
		$department_codes=array_unique(array_map('strtolower', $department_codes));
		//print_r($department_codes);
		
		$keys=array();
		foreach($department_codes as $department_code) {
			$keys[] = '\''.$department_code.'\'';
		}
		$department_codes = implode(',',$keys);

	
		$sql=sprintf("select `Product Department Key` from `Product Department Dimension` where `Product Department Code` in (%s)", $department_codes);
		//print $sql;
		$result=mysql_query($sql);
		while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			$department_keys[]=$row['Product Department Key'];
		}
		
		//print_r($department_keys);
		
		$department_keys = implode(',',$department_keys);
		//print_r($department_keys);
		
		$sql=sprintf("select `Product Family Name`, `Product Family Code` from `Product Family Dimension` where `Product Family Store Key`=%d and (`Product Family Record Type`= 'Normal' or `Product Family Record Type`= 'Discontinuing') and `Product Family Main Department Key` in (%s)", $this->data['Product Family Store Key'], $department_keys);
		//print $sql;
		$match='/'.strtolower($code).'/';
		$result=mysql_query($sql);
		while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			//if(!file_exists($base_url.strtolower($row['Product Family Code'])))
			if($this_family_name == $row['Product Family Name'])
				$weight=0;
			elseif(preg_match($match, strtolower(preg_replace('/\s/','',$row['Product Family Code']))))
				$weight=10;
			else
				$weight=0;
			
			//$temp='<a href="'.$base_url.strtolower($row['Product Family Code']).'">'.$row['Product Family Name'].'</a>';
			$temp=$base_url.strtolower($row['Product Family Code']);
				$see_also[]=array('url'=>$temp,
									'label'=>$row['Product Family Name'],
									'weight'=>$weight);
			

		}
		
		
		aasort($see_also,"weight");

		//$see_also_label=array();
		$see_also_array=array();
		
		foreach($see_also as $value){
			$see_also_array[$value['label']]=$value['url'];
			//$see_also_url=$value['url'];
		}
			
		
		//print_r($see_also_array);
		return $see_also_array;
		//$see_also;
	}
	
	





}
?>