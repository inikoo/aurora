<?php
/*
 File: Part.php

 This file contains the Part Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once('class.Product.php');

class part extends DB_Table {


    Private $current_locations_loaded=false;
    Public $sku=false;

    function __construct($a1,$a2=false) {

        $this->table_name='Part';
        $this->ignore_fields=array(
                                 'Part Key'
                             );

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id',$a1);
        } else if (($a1=='new' or $a1=='create') and is_array($a2) ) {
            $this->msg=$this->create($a2);

        } else
            $this->get_data($a1,$a2);

    }




    function get_data($tipo,$tag) {
        if ($tipo=='id' or $tipo=='sku')
            $sql=sprintf("select * from `Part Dimension` where `Part SKU`=%d ",$tag);
        else
            return;

        $result=mysql_query($sql);
        if (($this->data=mysql_fetch_array($result, MYSQL_ASSOC))) {
            $this->id=$this->data['Part SKU'];
            $this->sku=$this->data['Part SKU'];
        }


    }

    function create($data) {
        // print_r($data);
        $base_data=array(
                       'part status'=>'In Use',
                       'part xhtml currently used in'=>'',
                       'part xhtml currently supplied by'=>'',
                       'part xhtml description'=>'',
                       'part unit description'=>'',
                       'part package size metadata'=>'',
                       'part package volume'=>'',
                       'part package minimun orthogonal volume'=>'',
                       'part gross weight'=>'',
                       'part valid from'=>'',
                       'part valid to'=>'',
                   );
        foreach($data as $key=>$value) {
            if (isset( $base_data[strtolower($key)]) )
                $base_data[strtolower($key)]=_trim($value);
        }

        //    if(!$this->valid_sku($base_data['part sku']) ){

        // }


        if ($base_data['part xhtml description']=='') {
            $base_data['part xhtml description']=strip_tags($base_data['part xhtml description']);
        }

        $keys='(';
        $values='values(';
        foreach($base_data as $key=>$value) {
            $keys.="`$key`,";
            $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);

        $sql=sprintf("insert into `Part Dimension` %s %s",$keys,$values);
        if (mysql_query($sql)) {
            $this->id = mysql_insert_id();
            $this->sku =$this->id ;
            $this->new=true;
            
            $warehouse_key=1;
            if(array_key_exists('warehouse key', $data) and is_numeric($data['warehouse key'])  and  $data['warehouse key']>0){
            $warehouse_key=$data['warehouse key'];
            }
            
            $sql=sprintf("insert into `Part Warehouse Bridge` values (%d,%d)",$this->sku,$warehouse_key);
            //print "$sql\n";
            mysql_query($sql);
	    $this->get_data('id',$this->id);
            $data_for_history=array(
				    'Action'=>'created',
				    'History Abstract'=>_('Part Created'),
				    'History Details'=>_('Part')." ".$this->get_sku()." (".$this->data['Part XHTML Description'].")"._('Created')
				    );


        } else {
            print "Error Part can not be created $sql\n";
            exit;
        }

    }


	function update_custom_fields($id, $value){
		$this->update(array($id=>$value));	
	}


    function load($data_to_be_read,$args='') {
        switch ($data_to_be_read) {
        case('stock_history'):
        case('calculate_stock_history'):
            global $myconf;
            $force='';
            if (preg_match('/all/',$args))
                $force='all';
            if (preg_match('/last|audit/',$args))
                $force='last';
            if (preg_match('/continue/',$args))
                $force='continue';


            $part_sku=$this->data['Part SKU'];

            if (isset($args) and $args=='today')
                $min=strtotime('today');
            else
                $min=strtotime($myconf["data_from"]);


            $sql=sprintf("select `Location Key` from `Inventory Transaction Fact` where `Part SKU`=%d group by `Location Key` ",$part_sku);
            print "$sql\n";
            $resultxxx=mysql_query($sql);
            while (($rowxxx=mysql_fetch_array($resultxxx, MYSQL_ASSOC))) {
                $skip=false;
                $location_key=$rowxxx['Location Key'];
                print 'find PL '.$location_key.'_'.$this->data['Part SKU']."\n";
                $pl=new PartLocation($this->data['Part SKU'].'_'.$location_key);


                if ($location_key==1) {
                    if ($force=='all') {
                        $_from=$this->data['Part Valid From'];
                    }
                    elseif($force=='last') {

                        $_from=$pl->last_inventory_audit();
                        //exit("Froim: $_from\n");
                    }
                    elseif($force=='continue') {
                        $_from=$pl->last_inventory_date();
                    }
                    else {
                        $_from=$pl->first_inventory_transacion();
                    }
                    //print "$_from\n";
                    if (!$_from)
                        $skip=true;
                    $from=strtotime($_from);
                } else {
                    if ($force=='first')
                        $_from=$pl->first_inventory_transacion();
                    else
                        $_from=$pl->last_inventory_audit();

                    if (!$_from)
                        $skip=true;
                    $from=strtotime($_from);
                }



                if ($from<$min)
                    $from=$min;

                if ($this->data['Part Status']=='In Use') {
                    $to=strtotime('today');
                } else {
                    $to=strtotime($this->data['Part Valid To']);
                }


                if ($from>$to) {
                    print("error    $part_sku $location_key  ".$rowx['Part Valid From']." ".$rowx['Part Valid To']."   \n   ");
                    continue;
                }


                if ($skip) {
                    print "No trasactions: $part_sku $location_key \n";
                    continue;
                }

                $from=date("Y-m-d",$from);
                $to=date("Y-m-d",$to);
                print "** Redo daily inv S: $part_sku L: $location_key  $from $to\n";
                //  $pl=new PartLocation(array('LocationPart'=>$location_key."_".$part_sku));

                $pl->redo_daily_inventory($from,$to);


            }


            break;

        case('locations'):
            $this->load_locations($args);
           

break;

        case('stock'):
        $a.=$a;
        exit("error use update stock method  \n");
        /*
            if (!$this->current_locations_loaded)
                $this->load('locations');

            $stock='';
            $value='';
            $neg_discrepancy_value='';
            $neg_discrepancy='';
            $sql=sprintf("select sum(`Quantity On Hand`) as stock,sum(`Stock Value`) as value, sum(`Negative Discrepancy`) as neg_discrepancy, sum(`Negative Discrepancy Value`) as neg_discrepancy_value from `Part Location Dimension` where  `Part SKU`=%d ",$this->data['Part SKU']);
            //print $sql;
            $result=mysql_query($sql);
            if (($row=mysql_fetch_array($result, MYSQL_ASSOC))) {
                $stock=$row['stock'];
                $value=$row['value'];
                $neg_discrepancy_value=$row['neg_discrepancy_value'];
                $neg_discrepancy=$row['neg_discrepancy'];
            }

            if (!is_numeric($stock))
                $stock='NULL';
            if (!is_numeric($value))
                $value='NULL';

            $sql=sprintf("update `Part Dimension` set `Part Current Stock`=%s ,`Part Current Stock Cost`=%s ,`Part Current Stock Negative Discrepancy`=%f ,`Part Current Stock Negative Discrepancy Value`=%f  where `Part SKU`=%d "
                         ,$stock
                         ,$value
                         ,$neg_discrepancy
                         ,$neg_discrepancy_value
                         ,$this->id);

            //print "$sql $stock $value $neg_discrepancy $neg_discrepancy_value \n";
            // update products that depends of this part
            if (!mysql_query($sql))
                exit("  errorcant not uopdate parts stock");


            $this->data['Part Current Stock']=$stock;
            $this->data['Part Current Stock Cost']=$value;
            $this->data['Part Current Stock Negative Discrepancy']=$neg_discrepancy;
            $this->data['Part Current Stock Negative Discrepancy Value']=$neg_discrepancy_value;

            $this->load('used in list');

            foreach($this->used_in_list as $product_id) {
                $product=new Product('pid',$product_id);
                if (!$product->id) {
                    print_r($this->used_in_list);
                    exit("Error can not load prodct $product_id\n");
                }

                $product->load('stock');

            }

*/


            break;
        case('stock_data'):
            $astock=0;
            $avaue=0;

            $sql=sprintf("select ifnull(avg(`Quantity On Hand`),'ERROR') as stock,avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where  `Part SKU`=%d and `Date`>=%s and `Date`<=%s group by `Date`",$this->data['Part SKU'],prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid From']))),prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid To']))  ));
            // print "$sql\n";
            $result=mysql_query($sql);
            $days=0;
            $errors=0;
            $outstock=0;
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                if (is_numeric($row['stock']))
                    $astock+=$row['stock'];
                if (is_numeric($row['value']))
                    $avalue+=$row['value'];
                $days++;

                if (is_numeric($row['stock']) and $row['stock']==0)
                    $outstock++;
                if ($row['stock']=='ERROR')
                    $errors++;
            }

            $days_ok=$days-$errors;

            $gmroi='NULL';
            if ($days_ok>0) {
                $astock=$astock/$days_ok;
                $avalue=$avalue/$days_ok;
                if ($avalue>0)
                    $gmroi=$this->data['Part Total Profit When Sold']/$avalue;
            } else {
                $astock='NULL';
                $avalue='NULL';
            }

            $tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
            //print "$tdays $days o: $outstock e: $errors \n";
            $unknown=$tdays-$days_ok;
            $sql=sprintf("update `Part Dimension` set `Part Total AVG Stock`=%s ,`Part Total AVG Stock Value`=%s,`Part Total Keeping Days`=%f ,`Part Total Out of Stock Days`=%f , `Part Total Unknown Stock Days`=%s, `Part Total GMROI`=%s where `Part SKU`=%d"
                         ,$astock
                         ,$avalue
                         ,$tdays
                         ,$outstock
                         ,$unknown
                         ,$gmroi
                         ,$this->id);
            // print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql  ** errot con not update part stock history all");

            $astock=0;
            $avalue=0;

            $sql=sprintf("select ifnull(avg(`Quantity On Hand`),'ERROR') as stock,avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where   `Part SKU`=%d and `Date`>=%s and `Date`<=%s  and `Date`>=%s    group by `Date`",$this->data['Part SKU'],prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid From']))),prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid To']))  )  ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 year")))  );
            //print "$sql\n";
            $result=mysql_query($sql);
            $days=0;
            $errors=0;
            $outstock=0;
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                if (is_numeric($row['stock']))
                    $astock+=$row['stock'];
                if (is_numeric($row['value']))
                    $avalue+=$row['value'];
                $days++;

                if (is_numeric($row['stock']) and $row['stock']==0)
                    $outstock++;
                if ($row['stock']=='ERROR')
                    $errors++;
            }

            $days_ok=$days-$errors;

            $gmroi='NULL';
            if ($days_ok>0) {
                $astock=$astock/$days_ok;
                $avalue=$avalue/$days_ok;
                if ($avalue>0)
                    $gmroi=$this->data['Part 1 Year Acc Profit When Sold']/$avalue;
            } else {
                $astock='NULL';
                $avalue='NULL';
            }

            $tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
            //print "$tdays $days o: $outstock e: $errors \n";
            $unknown=$tdays-$days_ok;
            $sql=sprintf("update `Part Dimension` set `Part 1 Year Acc AVG Stock`=%s ,`Part 1 Year Acc AVG Stock Value`=%s,`Part 1 Year Acc Keeping Days`=%f ,`Part 1 Year Acc Out of Stock Days`=%f , `Part 1 Year Acc Unknown Stock Days`=%s, `Part 1 Year Acc GMROI`=%s where `Part SKU`=%d"
                         ,$astock
                         ,$avalue
                         ,$tdays
                         ,$outstock
                         ,$unknown
                         ,$gmroi
                         ,$this->id);
            // print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql **  errot con not update part stock history yr aa");


            $astock=0;
            $avalue=0;

            $sql=sprintf("select ifnull(avg(`Quantity On Hand`),'ERROR') as stock,avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where   `Part SKU`=%d and `Date`>=%s and `Date`<=%s  and `Date`>=%s    group by `Date`",$this->data['Part SKU'],prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid From']))),prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid To']))  )  ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -3 month")))  );
            // print "$sql\n";
            $result=mysql_query($sql);
            $days=0;
            $errors=0;
            $outstock=0;
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                if (is_numeric($row['stock']))
                    $astock+=$row['stock'];
                if (is_numeric($row['value']))
                    $avalue+=$row['value'];
                $days++;

                if (is_numeric($row['stock']) and $row['stock']==0)
                    $outstock++;
                if ($row['stock']=='ERROR')
                    $errors++;
            }

            $days_ok=$days-$errors;

            $gmroi='NULL';
            if ($days_ok>0) {
                $astock=$astock/$days_ok;
                $avalue=$avalue/$days_ok;
                if ($avalue>0)
                    $gmroi=$this->data['Part 1 Quarter Acc Profit When Sold']/$avalue;
            } else {
                $astock='NULL';
                $avalue='NULL';
            }

            $tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
            //print "$tdays $days o: $outstock e: $errors \n";
            $unknown=$tdays-$days_ok;
            $sql=sprintf("update `Part Dimension` set `Part 1 Quarter Acc AVG Stock`=%s ,`Part 1 Quarter Acc AVG Stock Value`=%s,`Part 1 Quarter Acc Keeping Days`=%f ,`Part 1 Quarter Acc Out of Stock Days`=%f , `Part 1 Quarter Acc Unknown Stock Days`=%s, `Part 1 Quarter Acc GMROI`=%s where `Part SKU`=%d"
                         ,$astock
                         ,$avalue
                         ,$tdays
                         ,$outstock
                         ,$unknown
                         ,$gmroi
                         ,$this->id);
            //   print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql z errot con not update part stock history yr bb");

            $astock=0;
            $avalue=0;

            $sql=sprintf("select ifnull(avg(`Quantity On Hand`),'ERROR') as stock,avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where `Part SKU`=%d and `Date`>=%s and `Date`<=%s  and `Date`>=%s    group by `Date`",$this->data['Part SKU'],prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid From']))),prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid To']))  )  ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 month")))  );
            // print "$sql\n";
            $result=mysql_query($sql);
            $days=0;
            $errors=0;
            $outstock=0;
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                if (is_numeric($row['stock']))
                    $astock+=$row['stock'];
                if (is_numeric($row['value']))
                    $avalue+=$row['value'];
                $days++;

                if (is_numeric($row['stock']) and $row['stock']==0)
                    $outstock++;
                if ($row['stock']=='ERROR')
                    $errors++;
            }

            $days_ok=$days-$errors;

            $gmroi='NULL';
            if ($days_ok>0) {
                $astock=$astock/$days_ok;
                $avalue=$avalue/$days_ok;
                if ($avalue>0)
                    $gmroi=$this->data['Part 1 Month Acc Profit When Sold']/$avalue;
            } else {
                $astock='NULL';
                $avalue='NULL';
            }

            $tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
            //print "$tdays $days o: $outstock e: $errors \n";
            $unknown=$tdays-$days_ok;
            $sql=sprintf("update `Part Dimension` set `Part 1 Month Acc AVG Stock`=%s ,`Part 1 Month Acc AVG Stock Value`=%s,`Part 1 Month Acc Keeping Days`=%f ,`Part 1 Month Acc Out of Stock Days`=%f , `Part 1 Month Acc Unknown Stock Days`=%s, `Part 1 Month Acc GMROI`=%s where `Part SKU`=%d"
                         ,$astock
                         ,$avalue
                         ,$tdays
                         ,$outstock
                         ,$unknown
                         ,$gmroi
                         ,$this->id);
            //   print "$sql\n";
            if (!mysql_query($sql))
                exit(" $sql x errot con not update part stock history yr cc");


            $astock=0;
            $avalue=0;

            $sql=sprintf("select ifnull(avg(`Quantity On Hand`),'ERROR') as stock,avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where `Part SKU`=%d and `Date`>=%s and `Date`<=%s  and `Date`>=%s    group by `Date`",$this->data['Part SKU'],prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid From']))),prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid To']))  )  ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 week")))  );
            // print "$sql\n";
            $result=mysql_query($sql);
            $days=0;
            $errors=0;
            $outstock=0;
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                if (is_numeric($row['stock']))
                    $astock+=$row['stock'];
                if (is_numeric($row['value']))
                    $avalue+=$row['value'];
                $days++;

                if (is_numeric($row['stock']) and $row['stock']==0)
                    $outstock++;
                if ($row['stock']=='ERROR')
                    $errors++;
            }

            $days_ok=$days-$errors;

            $gmroi='NULL';
            if ($days_ok>0) {
                $tmp=1.0000001/$days_ok;
                $astock=$astock*$tmp;
                $avalue=$avalue*$tmp;
                if ($avalue>0)
                    $gmroi=$this->data['Part 1 Week Acc Profit When Sold']/$avalue;
            } else {
                $astock='NULL';
                $avalue='NULL';
            }

            $tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
            //print "$tdays $days o: $outstock e: $errors \n";
            $unknown=$tdays-$days_ok;
            $sql=sprintf("update `Part Dimension` set `Part 1 Week Acc AVG Stock`=%s ,`Part 1 Week Acc AVG Stock Value`=%s,`Part 1 Week Acc Keeping Days`=%f ,`Part 1 Week Acc Out of Stock Days`=%f , `Part 1 Week Acc Unknown Stock Days`=%s, `Part 1 Week Acc GMROI`=%s where `Part SKU`=%d"
                         ,$astock
                         ,$avalue
                         ,$tdays
                         ,$outstock
                         ,$unknown
                         ,$gmroi
                         ,$this->id);
            //   print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql q errot con not update part stock history wk");

            break;
        case('used in list'):

            $sql=sprintf("select `Product ID` from `Product Part Dimension` PPD  left join  `Product Part List` PPL on (PPL.`Product Part Key`=PPD.`Product Part Key`)  where `Part SKU`=%d group by `Product ID`",$this->data['Part SKU']);
            // print $sql;
            $result=mysql_query($sql);
            $this->used_in_list=array();

            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $this->used_in_list[]=$row['Product ID'];
            }
            //   print_r($this->used_in_list);
            break;
        case("used in"):
            $this->update_used_in();

            break;
        case("supplied by"):
            $supplied_by='';
            $sql=sprintf("select `Supplier Product Code`,  SD.`Supplier Key`,SD.`Supplier Code` from `Supplier Product Part List` SPPL left join `Supplier Product Part Dimension` SPPD on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`) left join `Supplier Product Dimension` SPD on (SPD.`Supplier Product Key`=SPPD.`Supplier Product Key`) left join `Supplier Dimension` SD on (SD.`Supplier Key`=SPD.`Supplier Key`) where `Part SKU`=%d  order by `Supplier Key`;",$this->data['Part SKU']);
            $result=mysql_query($sql);
            //print "$sql\n";
            $supplier=array();
            $current_supplier='_';
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $_current_supplier=$row['Supplier Key'];
                if ($_current_supplier!=$current_supplier) {
                    $supplied_by.=sprintf(', <a href="supplier.php?id=%d">%s</a>(<a href="supplier_product.php?code=%s&supplier_key=%s">%s</a>'
                                          ,$row['Supplier Key']
                                          ,$row['Supplier Code']
                                          ,$row['Supplier Product Code']
                                          ,$row['Supplier Key']
                                          ,$row['Supplier Product Code']);
                    $current_supplier=$_current_supplier;
                } else {
                    $supplied_by.=sprintf(', <a href="supplier_product.php?supplier_key=%d&code=%s">%s</a>',$row['Supplier Key'],$row['Supplier Product Code'],$row['Supplier Product Code']);

                }

            }
            $supplied_by.=")";

            $supplied_by=_trim(preg_replace('/^, /','',$supplied_by));
            if ($supplied_by=='')
                $supplied_by=_('Unknown Supplier');


            $sql=sprintf("update `Part Dimension` set `Part XHTML Currently Supplied By`=%s where `Part SKU`=%d",prepare_mysql(_trim($supplied_by)),$this->id);
            //       print "$sql\n";exit;
            if (!mysql_query($sql))
                exit("error can no suplied by part 498239048");
            break;


        case("sales"):
            $this->update_sales();


            break;

        case('forecast'):
            $this->forecast();
            break;

        case('future costs'):
        case('estimated cost'):

            exit("stimeted cost should be called with part->update_future_costs()");
        }

    }

function formated_sku() {
        return $this->get_sku();

    }

    function get_sku() {
        return sprintf("SKU%05d",$this->sku);

    }


    function get($key='',$args=false) {






        if (array_key_exists($key,$this->data))
            return $this->data[$key];


    

        if (preg_match('/No Supplied$/',$key)) {
        
            $_key=preg_replace('/ No Supplied$/','',$key);
            if (preg_match('/^Part /',$key)) {
                   return $this->data["$_key Required"]-$this->data["$_key Provided"];
                   
            }else{
                return number($this->data["Part $_key Required"]-$this->data["Part $_key Provided"]);
            }
            
        }


        if (preg_match('/^(Total|1|10|6|3|Year To|Month To|Today|Week To).*(Amount|Profit)$/',$key)) {

            $amount='Part '.$key;

            return money($this->data[$amount]);
        }

        if (preg_match('/^(Total|1|10|6|3|Year To|Month To|Today|Week To).*(Margin)$/',$key)) {

            $amount='Part '.$key;

            return percentage($this->data[$amount],1);
        }


        if (preg_match('/^(Total|1|10|6|3|Year To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Provided|Broken|Adquired)$/',$key) or $key=='Current Stock'  ) {

            $amount='Part '.$key;

            return number($this->data[$amount]);
        }

        $_key=preg_replace('/^part /','',$key);
        if (isset($this->data[$_key]))
            return $this->data[$key];


        switch ($key) {
        case('SKU'):
            return sprintf('SKU%5d',$this->sku);
            break;
        case('Unit Cost'):
            return $this->get_unit_cost($args);
            break;
        case('Picking Location Key'):
            
            return $this->get_picking_location_key();
            break;
        case('Valid From'):
            return strftime("%x",strtotime($this->data['Part Valid From']));
            
        break;
         case('Valid From Datetime'):
            return strftime("%c",strtotime($this->data['Part Valid From']));
            
        break;
        
        case('Current Associated Locations'):

            if (!$this->current_locations_loaded)
                $this->load_current_locations();
            return $this->current_associated_locations;
            break;

        case('Associated Locations'):
            $associate=array();
            $associated=array();

            if ($args!='') {
                $date=" and `Date`<='".date("Y-m-d H:i:s",strtotime($args))."'";
            } else
                $date='';



            $sql=sprintf("select `Location Key` from `Inventory Transaction Fact` where `Inventory Transaction Type`='Associate' and `Part SKU`=%d  %s  group by `Location Key`  ",$this->data['Part SKU'],$date);
            //  print $sql;
            $res=mysql_query($sql);
            while ($row=mysql_fetch_array($res)) {
                $associate[]=$row['Location Key'];
            }
            foreach($associate as $location_key) {
                $sql=sprintf("select `Inventory Transaction Type` from `Inventory Transaction Fact` where (`Inventory Transaction Type`='Associate' or `Inventory Transaction Type`='Disassociate') and `Part SKU`=%d and `Location Key`=%d %s order by `Date` desc limit 1 ",$this->data['Part SKU'],$location_key,$date);
                //	  print $sql;
                $res=mysql_query($sql);
                if ($row=mysql_fetch_array($res)) {

                    if ($row['Inventory Transaction Type']=='Associate')
                        $associated[]=$location_key;
                }

            }

            return $associated;
            break;

        }

        return false;
    }



    function get_current_stock() {
        $stock=0;
        $value=0;
        $sql=sprintf("select sum(`Quantity On Hand`) as stock ,sum(`Stock Value`) as value from `Part Location Dimension` where `Part SKU`=%d ",$this->id);
        $res=mysql_query($sql);

        if ($row=mysql_fetch_array($res)) {
            $stock=$row['stock'];
            $value=$row['value'];
        }
        return array($stock,$value);

    }

    function get_stock($date) {
        $stock=0;
        $value=0;
        $sql=sprintf("select ifnull(sum(`Quantity On Hand`),0) as stock,ifnull(sum(`Value At Cost`),0) as value from `Inventory Spanshot Fact` where `Part SKU`=%d and `Date`=%s"
                     ,$this->id,prepare_mysql($date));
        $res=mysql_query($sql);

        if ($row=mysql_fetch_array($res)) {
            $stock=$row['stock'];
            $value=$row['value'];

        }
        return array($stock,$value);
    }

    function update_days_until_out_of_stock() {
        $this->get_days_until_out_of_stock();
    }

    function get_days_until_out_of_stock() {

        if ($this->data['Part Current Stock']==0) {
            $days=0;
            $days_formated='0';
            return array($days,$days_formated);
        }


        $sql=sprintf("select `Date` from `Inventory Transaction Fact` where `Part SKU`=%d and `Inventory Transaction Type`='Associate' order by `Date` desc"
                     ,$this->id);
        $res=mysql_query($sql);

        if ($row=mysql_fetch_array($res)) {
            $date=$row['Date'];
            $interval=(date('U')-strtotime($date))/3600/24;
            if ($interval<21) {
                $qty=$this->data['Part Total Provided']+$this->data['Part Total Lost'];
                $qty_per_day=$qty/$interval;
                $days=$this->data['Part Current Stock']/$qty_per_day;
                $days_formated=$days.' '._('days');
                return array($days,$days_formated);

            }


        } else {
            $days=0;
            $days_formated='ND';
            return array($days,$days_formated);
        }

//include_once('class.TimeSeries.php');


        $sql=sprintf("select `First Day` from kbase.`Week Dimension` where `Year Week`=%s",date("YW"));
        $res=mysql_query($sql);
        $no_data=true;
        if ($row=mysql_fetch_array($res)) {
            $date=date("Y-m-d",strtotime($row['First Day'].' -1 day'));
        }
        list($stock,$value)=$this->get_stock($date);
        print "$stock,$value\n";



// $tm=new TimeSeries(array('m','part sku '.$row['Part SKU']));
//  $tm->get_values();$tm->save_values();
//  $tm->forecast();

        $sql=sprintf("select `Time Series Value` from `Time Series Dimension` where `Time Series Frequency`='Weekly' and `Times Series Name`='SkuS' and `Time Series Name Key`=%d  and `Time Series Type`='Forecast' order by `Time Series Date`",$this->id);


        $resmysql_query($sql);
        $future_stock='';
        while ($row=mysql_fetch_array($res)) {

        }




    }

    function get_current_product_ids() {
        $sql=sprintf("select `Product Part Dimension`.`Product ID` from `Product Part List` left join `Product Part Dimension` on (`Product Part List`.`Product Part Key`=`Product Part Dimension`.`Product Part Key`)   where `Part SKU`=%d and `Product Part Most Recent`='Yes' group by `Product Part Dimension`.`Product ID`",$this->data['Part SKU']);
        // print $sql;
        $result=mysql_query($sql);
        $products=array();
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $products[$row['Product ID']]=array('Product ID'=>$row['Product ID']);
        }
        return $products;
    }

    function update_stock() {
        //print_r($this->get_current_stock());
        list($stock,$value)=$this->get_current_stock();
        $sql=sprintf("update `Part Dimension`  set `Part Current Stock`=%f ,`Part Current Value`=%f where  `Part SKU`=%d   "
                     ,$stock
                     ,$value
                     ,$this->id
                    );
        mysql_query($sql);

        $products=$this->get_current_product_ids();

        foreach($products as  $product_id=>$values) {
            $product=new Product('pid',$product_id);

            $product->update_availability();
        }


        //print "$sql\n";
    }



    function update_part_status($value) {
        $sql=sprintf("update `Part Dimension`  set `Part Status`=%s where  `Part SKU`=%d   "
                     ,prepare_mysql($value)
                     ,$this->id
                    );
        mysql_query($sql);

    }

    function update_valid_to($date) {
        $this->data['Part Valid To']=$date;
        $sql=sprintf("update `Part Dimension`  set `Part Valid To`=%s where  `Part SKU`=%d    "
                     ,prepare_mysql($date)
                     ,$this->id
                    );
        mysql_query($sql);
        //print "$sql\n";
        if (mysql_affected_rows()) {
            //print "sdasdas asdkokk; $date\n";
            $this->update_product_part_list_dates();
        }



    }
    function update_valid_from($date) {
        $this->data['Part Valid To']=$date;
        $sql=sprintf("update `Part Dimension`  set `Part Valid From`=%s where  `Part SKU`=%d    "
                     ,prepare_mysql($date)
                     ,$this->id
                    );
        mysql_query($sql);

        $this->update_product_part_list_dates();
        

    }
    function update_valid_dates($date) {
        $affected_from=0;
        $affected_to=0;
        $sql=sprintf("update `Part Dimension`  set `Part Valid From`=%s where  `Part SKU`=%d and `Part Valid From`>%s   "
                     ,prepare_mysql($date)
                     ,$this->id
                     ,prepare_mysql($date)

                    );
        //     print $sql;
        mysql_query($sql);
        if ($affected_from=mysql_affected_rows())
            $this->data['Part Valid From']=$date;
        $sql=sprintf("update `Part Dimension`  set `Part Valid To`=%s where  `Part SKU`=%d and `Part Valid To`<%s   "
                     ,prepare_mysql($date)
                     ,$this->id
                     ,prepare_mysql($date)

                    );
        mysql_query($sql);
        if ($affected_to=mysql_affected_rows())
            $this->data['Part Valid To']=$date;


        return $affected_to+$affected_from;
    }


    function get_suppliers() {
        $suppliers=array();
        $sql=sprintf("select `Supplier Product Code`,  SD.`Supplier Key`,`Supplier Code` from `Supplier Product Part List` SPPL   left join `Supplier Dimension` SD on (SD.`Supplier Key`=SPPL.`Supplier Key`)
                     where `Part SKU`=%d  order by `Supplier Key`;",$this->data['Part SKU']);
        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $suppliers[$row['Supplier Key']]=array('Supplier Key'=>$row['Supplier Key']);
        }
        return $suppliers;
    }

function get_supplier_products($date=false) {

if($date){
return $this->get_supplier_products_historic($date);
}

    $supplier_products=array();
    $sql=sprintf("
    
    select (select GROUP_CONCAT(`SPH Key`) from `Supplier Product History Dimension` H where H.`Supplier Product Key`=SPPD.`Supplier Product Key` ) as `Supplier Product Keys`, 
    `Supplier Product Units Per Part`,SPD.`Supplier Product Code`,  SPD.`Supplier Key`,`Supplier Code` from `Supplier Product Part List` SPPL 
    left join `Supplier Product Part Dimension` SPPD on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`) 
    left join `Supplier Product Dimension` SPD on (SPD.`Supplier Product Key`=SPPD.`Supplier Product Key`) where `Part SKU`=%d and `Supplier Product Part Most Recent`='Yes';
    ",$this->data['Part SKU']);
   //print $sql;
   $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
        $supplier_products[$row['Supplier Key'].$row['Supplier Product Code']]=array(
                    'Supplier Key'=>$row['Supplier Key'],
                    'Supplier Product Keys'=>$row['Supplier Product Keys'],
                    'Supplier Product Code'=>$row['Supplier Product Code'],
                    'Supplier Product Units Per Part'=>$row['Supplier Product Units Per Part']

                );
    }
    return $supplier_products;
}


function get_supplier_products_historic($date) {
    $supplier_products=array();
    $sql=sprintf("select `SPH Key`,  `Supplier Product Units Per Part`,SPD.`Supplier Product Code`,  SD.`Supplier Key`,SD.`Supplier Code`     from `Supplier Product Part List` SPPL    left join `Supplier Product Part Dimension` SPPD on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`)    left join `Supplier Product Dimension` SPD on (SPD.`Supplier Product Key`=SPPD.`Supplier Product Key`)    left join `Supplier Dimension` SD on (SD.`Supplier Key`=SPD.`Supplier Key`)     left join `Supplier Product History Dimension` H on ( H.`Supplier Product Key`=SPD.`Supplier Product Key` )    where `Part SKU`=%d 
    and ( (`SPH Valid From`<=%s and `SPH Valid To`>=%s and `SPH Type`='Historic') or (`SPH Valid From`<=%s and  `SPH Type`='Normal')     )       
    and ( (`Supplier Product Part Valid From`<=%s  and `Supplier Product Part Valid To`>=%s and `Supplier Product Part Most Recent`='No') or  (`Supplier Product Part Valid From`<=%s and `Supplier Product Part Most Recent`='Yes')  ) ;"
                 ,$this->data['Part SKU'],
                 prepare_mysql($date),
                 prepare_mysql($date),
                 prepare_mysql($date),
                 prepare_mysql($date),
                 prepare_mysql($date),
                 prepare_mysql($date)
                );
    //print "$sql\n\n";
    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


        if (isset($supplier_products[$row['Supplier Key'].$row['Supplier Product Code']])) {

            $supplier_products[$row['Supplier Key'].$row['Supplier Product Code']]['Supplier Product Keys'].=','.$row['SPH Key'];


        } else {

            $supplier_products[$row['Supplier Key'].$row['Supplier Product Code']]=array(
                        'Supplier Key'=>$row['Supplier Key'],
                        'Supplier Product Keys'=>$row['SPH Key'],
                        'Supplier Product Code'=>$row['Supplier Product Code'],
                        'Supplier Product Units Per Part'=>$row['Supplier Product Units Per Part']

                    );
        }

    }
    return $supplier_products;
}




    function load_locations($date='') {

        if (preg_match('/\d{4}-\{d}2-\d{2}/',$date))
            $this->load_locations_historic($date);
        else
            $this->load_current_locations();
    }

    function load_current_historic($date) {
        $this->all_historic_associated_locations=array();
        $this->associated_location_on_date=array();

        $sql=sprintf("select `Location Key` from `Inventory Transaction Fact` where `Inventory Transaction Type`='Associate' and `Part SKU`=%d  `Date`=%s  group by `Location Key`  ",$this->data['Part SKU'],$date);
        //  print $sql;
        $res=mysql_query($sql);
        while ($row=mysql_fetch_array($res)) {
            $this->all_historic_associated_locations[]=$row['Location Key'];
        }
        foreach($this->all_historic_associated_locations as $location_key) {
            $sql=sprintf("select `Inventory Transaction Type` from `Inventory Transaction Fact` where (`Inventory Transaction Type`='Associate' or `Inventory Transaction Type`='Disassociate') and `Part SKU`=%d and `Location Key`=%d %s order by `Date` desc limit 1 ",$this->data['Part SKU'],$location_key,$date);
            //	  print $sql;
            $res=mysql_query($sql);
            if ($row=mysql_fetch_array($res)) {

                if ($row['Inventory Transaction Type']=='Associate')
                    $this->associated_location_on_date[]=$location_key;
            }

        }

    }

function get_picking_location_historic($date) {
    $sql=sprintf("select `Location Key` from `Inventory Spanshot Fact` where `Part SKU` in (%s) and `Location Type`='Picking'",$this->sku);
    $location_key=1;
    $res=mysql_query($sql);

    if ($row=mysql_fetch_assoc($res)) {
        $location_key=$row['Location Key'];
    }
    return $location_key;

}

  
function get_picking_location_key($date=false) {
    if ($date) {
        return $this->get_picking_location_historic($date);
    }
    $sql=sprintf("select `Location Key` from `Part Location Dimension` where `Part SKU` in (%s) and `Can Pick`='Yes'",$this->sku);
    $location_key=1;
    $res=mysql_query($sql);

    if ($row=mysql_fetch_assoc($res)) {
        $location_key=$row['Location Key'];
    }
    
  return $location_key;
  }
  
  function get_locations($for_smarty=false){

$sql=sprintf("select * from `Part Location Dimension` where `Part SKU` in (%s)",$this->sku);

$res=mysql_query($sql);
$part_locations=array();
while($row=mysql_fetch_assoc($res)){

$location=new Location($row['Location Key']);

$row['Formated Quantity On Hand']=number($row['Quantity On Hand']);

$row['Part Formated SKU']=$this->get_sku();

$row['Location Code']=$location->data['Location Code'];
if($for_smarty){
$row_for_smarty=array();
foreach($row as $key=>$value){
$row_for_smarty[preg_replace('/\s/','',$key)]=$value;
}
$part_locations[$row['Part SKU'].'_'.$row['Location Key']]=$row_for_smarty;

}else{
$part_locations[$row['Part SKU'].'_'.$row['Location Key']]=$row;
}
}

return $part_locations;
}
  
  
  
  function get_location_keys() {
        $this->load_current_locations();
        return $this->current_associated_locations;
    }

    function load_current_locations() {
        $this->current_associated_locations=array();
        $sql=sprintf("select `Location Key` from `Part Location Dimension` where   `Part SKU`=%d    group by `Location Key`  ",$this->data['Part SKU']);
        //  print $sql;
        $res=mysql_query($sql);
        while ($row=mysql_fetch_array($res)) {
            $this->current_associated_locations[]=$row['Location Key'];
        }
        $this->current_locations_loaded=true;

    }


    function items_per_product($product_ID,$date=false) {
        $where_date='';

        $sql=sprintf("select AVG(`Parts Per Product`) as parts_per_product from `Product Part Dimension` PPD left join `Product Part List` PPL on (PPD.`Product Part Key`=PPL.`Product Part Key`) where `Part SKU`=%d and  `Product ID`=%d %s  "
                     ,$this->id
                     ,$product_ID
                     ,$where_date
                    );
       //  print "$sql\n";
        $parts_per_product=0;
        $result3=mysql_query($sql);
        if ($row3=mysql_fetch_array($result3, MYSQL_ASSOC)   ) {
            if (is_numeric($row3['parts_per_product']))
                $parts_per_product=$row3['parts_per_product'];
        }
        return $parts_per_product;


    }


    function get_comercial_value($date='') {

        return 0;
    }


    function update_stock_history() {
    
    
        $sql=sprintf("select `Location Key`  from `Inventory Transaction Fact` where `Part SKU`=%d group by `Location Key`",$this->sku);
//print $sql;
        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
        
  //      print $this->sku.'_'.$row['Location Key']."\n";
            $part_location=new PartLocation($this->sku.'_'.$row['Location Key']);
            $part_location->update_stock_history();
        }
    }



    function update_up_today_sales() {
        $this->update_sales_from_invoices('Today');
        $this->update_sales_from_invoices('Week To Day');
        $this->update_sales_from_invoices('Month To Day');
        $this->update_sales_from_invoices('Year To Day');
        $this->update_sales_from_invoices('Total');

    }

    function update_last_period_sales() {

        $this->update_sales_from_invoices('Yesterday');
        $this->update_sales_from_invoices('Last Week');
        $this->update_sales_from_invoices('Last Month');
    }


    function update_interval_sales() {
        $this->update_sales_from_invoices('3 Year');
        $this->update_sales_from_invoices('1 Year');
        $this->update_sales_from_invoices('6 Month');
        $this->update_sales_from_invoices('1 Quarter');
        $this->update_sales_from_invoices('1 Month');
        $this->update_sales_from_invoices('10 Day');
        $this->update_sales_from_invoices('1 Week');
    }
    
    
    
 function update_sales_from_invoices($interval) {

        $to_date='';

        switch ($interval) {
        case 'Last Month':
            $db_interval='Last Month';
            $from_date=date('Y-m-d 00:00:00',mktime(0,0,0,date('m')-1,1,date('Y')));
            $to_date=date('Y-m-d 00:00:00',mktime(0,0,0,date('m'),1,date('Y')));

            $from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
            $to_1yb=date('Y-m-d H:i:s',strtotime("$to_date -1 year"));
            //print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";
            break;

        case 'Last Week':
            $db_interval='Last Week';


            $sql=sprintf("select `First Day`  from kbase.`Week Dimension` where `Year`=%d and `Week`=%d",date('Y'),date('W'));
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $from_date=date('Y-m-d 00:00:00',strtotime($row['First Day'].' -1 week'));
                $to_date=date('Y-m-d 00:00:00',strtotime($row['First Day']));

            } else {
                return;
            }



            $from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
            $to_1yb=date('Y-m-d H:i:s',strtotime("$to_date -1 year"));
            break;

        case 'Yesterday':
            $db_interval='Yesterday';
            $from_date=date('Y-m-d 00:00:00',strtotime('today -1 day'));
            $to_date=date('Y-m-d 00:00:00',strtotime('today'));

            $from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
            $to_1yb=date('Y-m-d H:i:s',strtotime("today -1 year"));
            break;

        case 'Week To Day':
        case 'wtd':
            $db_interval='Week To Day';

            $from_date=false;
            $from_date_1yb=false;

            $sql=sprintf("select `First Day`  from kbase.`Week Dimension` where `Year`=%d and `Week`=%d",date('Y'),date('W'));
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $from_date=$row['First Day'].' 00:00:00';
                $lapsed_seconds=strtotime('now')-strtotime($from_date);

            } else {
                return;
            }

            $sql=sprintf("select `First Day`  from  kbase.`Week Dimension` where `Year`=%d and `Week`=%d",date('Y')-1,date('W'));
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $from_date_1yb=$row['First Day'].' 00:00:00';
            }


            $to_1yb=date('Y-m-d H:i:s',strtotime($from_date_1yb." +$lapsed_seconds seconds"));



            break;
        case 'Today':

            $db_interval='Today';
            $from_date=date('Y-m-d 00:00:00');
            $from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
            $to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
            break;

        case 'Total':
        case 'all':
            $db_interval='Total';
            $from_date=$this->data['Part Valid From'];
              $from_date_1yb=false;
            $to_1yb=false;
            break;
        case 'Month To Day':
        case 'mtd':
            $db_interval='Month To Day';
            $from_date=date('Y-m-01 00:00:00');
            $from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
            $to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
            break;
        case 'Year To Day':
        case 'ytd':
            $db_interval='Year To Day';
            $from_date=date('Y-01-01 00:00:00');
            $from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
            $to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
            //print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";
            break;
        case '3 Year':
            $db_interval=$interval;
            $from_date=date('Y-m-d H:i:s',strtotime("now -3 year"));
            $from_date_1yb=false;
            $to_1yb=false;
            break;
        case '1 Year':
            $db_interval=$interval;
            $from_date=date('Y-m-d H:i:s',strtotime("now -1 year"));
            $from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
            $to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
            break;
        case '6 Month':
            $db_interval=$interval;
            $from_date=date('Y-m-d H:i:s',strtotime("now -6 months"));
            $from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
            $to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
            break;
        case '1 Quarter':
            $db_interval=$interval;
            $from_date=date('Y-m-d H:i:s',strtotime("now -3 months"));
            $from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
            $to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
            break;
        case '1 Month':
            $db_interval=$interval;
            $from_date=date('Y-m-d H:i:s',strtotime("now -1 month"));
            $from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
            $to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
            break;
        case '10 Day':
            $db_interval=$interval;
            $from_date=date('Y-m-d H:i:s',strtotime("now -10 days"));
            $from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
            $to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
            break;
        case '1 Week':
            $db_interval=$interval;
            $from_date=date('Y-m-d H:i:s',strtotime("now -1 week"));
            $from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
            $to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
            break;

        default:
            return;
            break;
        }

         setlocale(LC_ALL, 'en_GB');

        //   print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";

         $this->data["Part $db_interval Acc Required"]=0;
            $this->data["Part $db_interval Acc Provided"]=0;
            $this->data["Part $db_interval Acc Given"]=0;
            $this->data["Part $db_interval Acc Sold Amount"]=0;
            $this->data["Part $db_interval Acc Absolute Profit"]=0;
            $this->data["Part $db_interval Acc Profit When Sold"]=0;
            $this->data["Part $db_interval Acc Sold"]=0;
            $this->data["Part $db_interval Acc Margin"]=0;



        $sql=sprintf("select    ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   
                                ifnull(sum(`Required`),0) as required,
                                ifnull(sum(`Given`),0) as given,
                                ifnull(sum(`Amount In`),0) as amount_in, 
                                ifnull(sum(-`Inventory Transaction Quantity`),0) as qty,
                                ifnull(sum(-`Inventory Transaction Amount`),0) as value 
                                from  `Inventory Transaction Fact` where `Part SKU`=%d and `Inventory Transaction Type`='Sale' and  `Date`>=%s %s     ",
        $this->sku,
        prepare_mysql($from_date),
                     ($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')  
        );
    
    
    //    print "$sql\n";
    
        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {   
            $this->data["Part $db_interval Acc Required"]=$row["required"];
            $this->data["Part $db_interval Acc Provided"]=$row["qty"];
            $this->data["Part $db_interval Acc Given"]=$row["given"];
            $this->data["Part $db_interval Acc Sold Amount"]=$row["amount_in"];
            $this->data["Part $db_interval Acc Absolute Profit"]=$row["amount_in"]-$row['value'];
            $this->data["Part $db_interval Acc Profit When Sold"]=$row["amount_in"]-$row['value']+$row['value_free'];
            $this->data["Part $db_interval Acc Sold"]=$row["qty"]-$row["given"];
            $this->data["Part $db_interval Acc Margin"]=($row["amount_in"]==0?0:$this->data["Part $db_interval Acc Profit When Sold"]/$row["amount_in"]);


        }
       

         $sql=sprintf("update `Part Dimension` set 
                                `Part $db_interval Acc Required`=%f ,
                                `Part $db_interval Acc Provided`=%f,
                                `Part $db_interval Acc Given`=%f ,
                                `Part $db_interval Acc Sold Amount`=%f ,
                                `Part $db_interval Acc Absolute Profit`=%f ,
                                `Part $db_interval Acc Profit When Sold`=%f , 
                                `Part $db_interval Acc Sold`=%f , 
                                `Part $db_interval Acc Margin`=%s where 
                                `Part SKU`=%d "
                     ,$this->data["Part $db_interval Acc Required"]
                     ,$this->data["Part $db_interval Acc Provided"]
                     ,$this->data["Part $db_interval Acc Given"]
                     ,$this->data["Part $db_interval Acc Sold Amount"]
                     ,$this->data["Part $db_interval Acc Absolute Profit"]
                     ,$this->data["Part $db_interval Acc Profit When Sold"]
                     ,$this->data["Part $db_interval Acc Sold"]
                     ,$this->data["Part $db_interval Acc Margin"]
                     
                     ,$this->id);

mysql_query($sql);
       
    //   print "$sql\n";




        if ($from_date_1yb) {
        
     //    prepare_mysql($from_date_1yb),
       //                  prepare_mysql($to_1yb)
        
        
   
   
   
        }


    }


    function update_sales_old() {
        // the product wich this one is
        $sold=0;
        $required=0;
        $provided=0;
        $given=0;
        $amount_in=0;
        $value=0;
        $value_free=0;
        $margin=0;
        $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s  and `Date`<=%s   "
                     ,prepare_mysql($this->data['Part SKU'])
                     ,prepare_mysql($this->data['Part Valid From'])
                     ,prepare_mysql($this->data['Part Valid To'])
                    );
        //       print "$sql\n\n\n";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $required=$row['required'];
            $provided=$row['qty'];
            $given=$row['given'];
            $amount_in=floatval($row['amount_in']);
            $value=floatval($row['value']);
            $value_free=floatval($row['value_free']);
            $sold=$row['qty']-$row['given'];
        }
        $abs_profit=$amount_in-$value;
        $profit_sold=$amount_in-$value+$value_free;
        if ($amount_in==0)
            $margin=0;
        else {
            $margin=$profit_sold/$amount_in;
            //	$margin=($value-$value_free)/$amount_in;
            //	$margin=sprintf("%.6f",($value)*$tmp);
            $margin=preg_replace('/:/','1',$margin);
            //$margin=$value/$amount_in;
        }

        //     var_dump( $value );
//       var_dump(  $amount_in);
//       var_dump( 0.7/7 );
        $sql=sprintf("update `Part Dimension` set `Part Total Required`=%f ,`Part Total Provided`=%f,`Part Total Given`=%f ,`Part Total Sold Amount`=%f ,`Part Total Absolute Profit`=%f ,`Part Total Profit When Sold`=%f , `Part Total Sold`=%f , `Part Total Margin`=%f  where `Part SKU`=%d "
                     ,$required
                     ,$provided
                     ,$given
                     ,$amount_in
                     ,$abs_profit
                     ,$profit_sold,$sold,$margin
                     ,$this->id);
        //    print "$sql\n";
        if (!mysql_query($sql))
            exit(" $sql\n error xxx $margin b $value c $value_free d $amount_in  con not uopdate product part when loading sales");

// --------------------------------------- Start of 3 year -----------------------------------------------------------------------------
        $sold=0;
        $required=0;
        $provided=0;
        $given=0;
        $amount_in=0;
        $value=0;
        $value_free=0;
        $margin=0;
        $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and  `Date`>=%s     ",
        prepare_mysql($this->data['Part SKU']),
        prepare_mysql(date("Y-m-d H:i:s",strtotime("now -3 year")))  );
        // print "$sql\n";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $required=$row['required'];
            $provided=$row['qty'];
            $given=$row['given'];
            $amount_in=$row['amount_in'];
            $value=$row['value'];
            $value_free=$row['value_free'];
            $sold=$row['qty']-$row['given'];
        }
        $abs_profit=$amount_in-$value;
        $profit_sold=$amount_in-$value+$value_free;
        if ($amount_in==0)
            $margin=0;
        else
            $margin=$profit_sold/$amount_in;
        $sql=sprintf("update `Part Dimension` set `Part 3 Year Acc Required`=%f ,`Part 3 Year Acc Provided`=%f,`Part 3 Year Acc Given`=%f ,`Part 3 Year Acc Sold Amount`=%f ,`Part 3 Year Acc Absolute Profit`=%f ,`Part 3 Year Acc Profit When Sold`=%f , `Part 3 Year Acc Sold`=%f , `Part 3 Year Acc Margin`=%s where `Part SKU`=%d "
                     ,$required
                     ,$provided
                     ,$given
                     ,$amount_in
                     ,$abs_profit
                     ,$profit_sold,$sold,$margin
                     ,$this->id);
        //  print "$sql\n";
        if (!mysql_query($sql))
            exit(" $sql\n error con not uopdate product part when loading sales");
// --------------------------------------- End of 3 year -------------------------------------------------------------------------------
        $sold=0;
        $required=0;
        $provided=0;
        $given=0;
        $amount_in=0;
        $value=0;
        $value_free=0;
        $margin=0;
        $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale'  and `Date`>=%s     ",
        prepare_mysql($this->data['Part SKU']),
        prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 year")))  );
        // print "$sql\n";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $required=$row['required'];
            $provided=$row['qty'];
            $given=$row['given'];
            $amount_in=$row['amount_in'];
            $value=$row['value'];
            $value_free=$row['value_free'];
            $sold=$row['qty']-$row['given'];
        }
        $abs_profit=$amount_in-$value;
        $profit_sold=$amount_in-$value+$value_free;
        if ($amount_in==0)
            $margin=0;
        else
            $margin=$profit_sold/$amount_in;
        $sql=sprintf("update `Part Dimension` set `Part 1 Year Acc Required`=%f ,`Part 1 Year Acc Provided`=%f,`Part 1 Year Acc Given`=%f ,`Part 1 Year Acc Sold Amount`=%f ,`Part 1 Year Acc Absolute Profit`=%f ,`Part 1 Year Acc Profit When Sold`=%f , `Part 1 Year Acc Sold`=%f , `Part 1 Year Acc Margin`=%s where `Part SKU`=%d "
                     ,$required
                     ,$provided
                     ,$given
                     ,$amount_in
                     ,$abs_profit
                     ,$profit_sold,$sold,$margin
                     ,$this->id);
        //  print "$sql\n";
        if (!mysql_query($sql))
            exit(" $sql\n error con not uopdate product part when loading sales");

// --------------------------------------- Start of yeartoday -----------------------------------------------------------------------------



        $sold=0;
        $required=0;
        $provided=0;
        $given=0;
        $amount_in=0;
        $value=0;
        $value_free=0;
        $margin=0;
        $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and  `Date`>=%s     ",
        prepare_mysql($this->data['Part SKU']),
        prepare_mysql(date("Y-01-01 00:00:00"))
        );
       // print "$sql\n";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $required=$row['required'];
            $provided=$row['qty'];
            $given=$row['given'];
            $amount_in=$row['amount_in'];
            $value=$row['value'];
            $value_free=$row['value_free'];
            $sold=$row['qty']-$row['given'];
        }
        $abs_profit=$amount_in-$value;
        $profit_sold=$amount_in-$value+$value_free;
        if ($amount_in==0)
            $margin=0;
        else
            $margin=$profit_sold/$amount_in;
        $sql=sprintf("update `Part Dimension` set `Part Year To Day Acc Required`=%f ,`Part Year To Day Acc Provided`=%f,`Part Year To Day Acc Given`=%f ,`Part Year To Day Acc Sold Amount`=%f ,`Part Year To Day Acc Absolute Profit`=%f ,`Part Year To Day Acc Profit When Sold`=%f , `Part Year To Day Acc Sold`=%f , `Part Year To Day Acc Margin`=%s where `Part SKU`=%d "
                     ,$required
                     ,$provided
                     ,$given
                     ,$amount_in
                     ,$abs_profit
                     ,$profit_sold,$sold,$margin
                     ,$this->id);
        //  print "$sql\n";
        if (!mysql_query($sql))
            exit(" $sql\n error con not uopdate product part when loading sales");
// --------------------------------------- End of yeartoday-------------------------------------------------------------------------------
// ----------------------------------------Start of 6 month ------------------------------------------------------------------------------
        $sold=0;
        $required=0;
        $provided=0;
        $given=0;
        $amount_in=0;
        $value=0;
        $value_free=0;
        $margin=0;
        $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s     ",
        prepare_mysql($this->data['Part SKU']),prepare_mysql(date("Y-m-d H:i:s",strtotime("now -6 month")))  );
        //      print "$sql\n";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $required=$row['required'];
            $provided=$row['qty'];
            $given=$row['given'];
            $amount_in=$row['amount_in'];
            $value=$row['value'];
            $value_free=$row['value_free'];
            $sold=$row['qty']-$row['given'];
        }
        $abs_profit=$amount_in-$value;
        $profit_sold=$amount_in-$value+$value_free;

        if ($amount_in==0)
            $margin=0;
        else
            $margin=$profit_sold/$amount_in;

        $sql=sprintf("update `Part Dimension` set `Part 6 Month Acc Required`=%f ,`Part 6 Month Acc Provided`=%f,`Part 6 Month Acc Given`=%f ,`Part 6 Month Acc Sold Amount`=%f ,`Part 6 Month Acc Absolute Profit`=%f ,`Part 6 Month Acc Profit When Sold`=%f  , `Part 6 Month Acc Sold`=%f  , `Part 6 Month Acc Margin`=%s where `Part SKU`=%d "
                     ,$required
                     ,$provided
                     ,$given
                     ,$amount_in
                     ,$abs_profit
                     ,$profit_sold,$sold,$margin
                     ,$this->id);
        //   print "$sql\n";
        if (!mysql_query($sql))
            exit("error con not uopdate product part when loading sales");
// ----------------------------------------End of 6 month --------------------------------------------------------------------------------
        $sold=0;
        $required=0;
        $provided=0;
        $given=0;
        $amount_in=0;
        $value=0;
        $value_free=0;
        $margin=0;
        $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s     ",
        prepare_mysql($this->data['Part SKU']),
        prepare_mysql(date("Y-m-d H:i:s",strtotime("now -3 month")))  );
        //      print "$sql\n";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $required=$row['required'];
            $provided=$row['qty'];
            $given=$row['given'];
            $amount_in=$row['amount_in'];
            $value=$row['value'];
            $value_free=$row['value_free'];
            $sold=$row['qty']-$row['given'];
        }
        $abs_profit=$amount_in-$value;
        $profit_sold=$amount_in-$value+$value_free;

        if ($amount_in==0)
            $margin=0;
        else
            $margin=$profit_sold/$amount_in;

        $sql=sprintf("update `Part Dimension` set `Part 1 Quarter Acc Required`=%f ,`Part 1 Quarter Acc Provided`=%f,`Part 1 Quarter Acc Given`=%f ,`Part 1 Quarter Acc Sold Amount`=%f ,`Part 1 Quarter Acc Absolute Profit`=%f ,`Part 1 Quarter Acc Profit When Sold`=%f  , `Part 1 Quarter Acc Sold`=%f  , `Part 1 Quarter Acc Margin`=%s where `Part SKU`=%d "
                     ,$required
                     ,$provided
                     ,$given
                     ,$amount_in
                     ,$abs_profit
                     ,$profit_sold,$sold,$margin
                     ,$this->id);
        //   print "$sql\n";
        if (!mysql_query($sql))
            exit("error con not uopdate product part when loading sales");



        $sold=0;
        $required=0;
        $provided=0;
        $given=0;
        $amount_in=0;
        $value=0;
        $value_free=0;
        $margin=0;
        $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale'   and `Date`>=%s     ",
        prepare_mysql($this->data['Part SKU']),
        prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 month")))  );
        //      print "$sql\n";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $required=$row['required'];
            $provided=$row['qty'];
            $given=$row['given'];
            $amount_in=$row['amount_in'];
            $value=$row['value'];
            $value_free=$row['value_free'];
            $sold=$row['qty']-$row['given'];
        }
        $abs_profit=$amount_in-$value;
        $profit_sold=$amount_in-$value+$value_free;

        if ($amount_in==0)
            $margin=0;
        else
            $margin=$profit_sold/$amount_in;


        $sql=sprintf("update `Part Dimension` set `Part 1 Month Acc Required`=%f ,`Part 1 Month Acc Provided`=%f,`Part 1 Month Acc Given`=%f ,`Part 1 Month Acc Sold Amount`=%f ,`Part 1 Month Acc Absolute Profit`=%f ,`Part 1 Month Acc Profit When Sold`=%f  , `Part 1 Month Acc Sold`=%f , `Part 1 Month Acc Margin`=%s  where `Part SKU`=%d "
                     ,$required
                     ,$provided
                     ,$given
                     ,$amount_in
                     ,$abs_profit
                     ,$profit_sold,$sold,$margin
                     ,$this->id);
        //            print "$sql\n";
        if (!mysql_query($sql))
            exit(" $sql\n error con not uopdate product part when loading sales");
// --------------------------------------------------------Start of 10 days----------------------------------------------
        $sold=0;
        $required=0;
        $provided=0;
        $given=0;
        $amount_in=0;
        $value=0;
        $value_free=0;
        $margin=0;
        $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and  `Date`>=%s     ",
        prepare_mysql($this->data['Part SKU']),prepare_mysql(date("Y-m-d H:i:s",strtotime("now -10 day")))  );
        //        print "$sql\n";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $required=$row['required'];
            $provided=$row['qty'];
            $given=$row['given'];
            $amount_in=$row['amount_in'];
            $value=$row['value'];
            $value_free=$row['value_free'];
            $sold=$row['qty']-$row['given'];
        }

        $abs_profit=$amount_in-$value;
        $profit_sold=$amount_in-$value+$value_free;
        if ($amount_in==0)
            $margin=0;
        else
            $margin=$profit_sold/$amount_in;

        $sql=sprintf("update `Part Dimension` set `Part 10 Day Acc Required`=%f ,`Part 10 Day Acc Provided`=%f,`Part 10 Day Acc Given`=%f ,`Part 10 Day Acc Sold Amount`=%f ,`Part 10 Day Acc Absolute Profit`=%f ,`Part 10 Day Acc Profit When Sold`=%f  , `Part 10 Day Acc Sold`=%f , `Part 10 Day Acc Margin`=%s where `Part SKU`=%d "
                     ,$required
                     ,$provided
                     ,$given
                     ,$amount_in
                     ,$abs_profit
                     ,$profit_sold,$sold,$margin
                     ,$this->id);
        //            print "$sql\n";
        if (!mysql_query($sql))
            exit(" $sql\n error con not uopdate product part when loading sales");
// ---------------------------------------------------------End of 10 days----------------------------------------------
        $sold=0;
        $required=0;
        $provided=0;
        $given=0;
        $amount_in=0;
        $value=0;
        $value_free=0;
        $margin=0;
        $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale'  and `Date`>=%s",
        prepare_mysql($this->data['Part SKU']),prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 week")))  );
        //        print "$sql\n";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $required=$row['required'];
            $provided=$row['qty'];
            $given=$row['given'];
            $amount_in=$row['amount_in'];
            $value=$row['value'];
            $value_free=$row['value_free'];
            $sold=$row['qty']-$row['given'];
        }

        $abs_profit=$amount_in-$value;
        $profit_sold=$amount_in-$value+$value_free;
        if ($amount_in==0)
            $margin=0;
        else
            $margin=$profit_sold/$amount_in;

        $sql=sprintf("update `Part Dimension` set `Part 1 Week Acc Required`=%f ,`Part 1 Week Acc Provided`=%f,`Part 1 Week Acc Given`=%f ,`Part 1 Week Acc Sold Amount`=%f ,`Part 1 Week Acc Absolute Profit`=%f ,`Part 1 Week Acc Profit When Sold`=%f  , `Part 1 Week Acc Sold`=%f , `Part 1 Week Acc Margin`=%s where `Part SKU`=%d "
                     ,$required
                     ,$provided
                     ,$given
                     ,$amount_in
                     ,$abs_profit
                     ,$profit_sold,$sold,$margin
                     ,$this->id);
        //            print "$sql\n";
        if (!mysql_query($sql))
            exit(" $sql\n error con not uopdate product part when loading sales");

    }





    function forecast() {

        // -------------- simple forecast -------------------------

        $sql=sprintf("select `Date` from `Inventory Transaction Fact` where `Part SKU`=%d and `Inventory Transaction Type`='Associate' order by `Date` desc"
                     ,$this->id);
        $res=mysql_query($sql);

        if ($row=mysql_fetch_array($res)) {
            $date=$row['Date'];
            $interval=(date('U')-strtotime($date))/3600/24;
        } else
            $interval=0;

        if ($this->data['Part Current Stock']=='' or $this->data['Part Current Stock']<0) {
            $this->data['Part Days Available Forecast']='NULL';
            $this->data['Part XHTML Available For Forecast']=_('Unknown Stock');
        }
        elseif($this->data['Part Current Stock']==0) {
            $this->data['Part Days Available Forecast']=0;
            $this->data['Part XHTML Available For Forecast']=_('Out of Stock');
        }
        else {

            if ($this->data['Part 1 Quarter Acc Required']>0) {



                // print $this->data['Part 1 Quarter Acc Required']."xxxx\n";
                if ($interval>(365/4)) {
                    $interval=365/4;
                }
                print $this->data['Part 1 Quarter Acc Required']/$interval;


                $this->data['Part Days Available Forecast']=$interval*$this->data['Part Current Stock']/$this->data['Part 1 Quarter Acc Required'];
                $this->data['Part XHTML Available For Forecast']=number($this->data['Part Days Available Forecast']).' '._('days');
            }
            elseif($this->data['Part 1 Year Acc Required']>0) {
                if ($interval>(365)) {
                    $interval=365;
                }

                $this->data['Part Days Available Forecast']=$interval*$this->data['Part Current Stock']/$this->data['Part 1 Year Acc Required'];
                $this->data['Part XHTML Available For Forecast']=number($this->data['Part Days Available Forecast']).' '._('days');
            }
            else {
                $this->data['Part Days Available Forecast']='NULL';
                $this->data['Part XHTML Available For Forecast']=_('No enough data');
            }
        }


        $sql=sprintf("update `Part Dimension` set `Part Days Available Forecast`=%s,`Part XHTML Available For Forecast`=%s where `Part SKU`=%d",$this->data['Part Days Available Forecast'],prepare_mysql($this->data['Part XHTML Available For Forecast']),$this->id );
        //print $sql;
        if (!mysql_query($sql))
            print "$sql\n";



    }


function update_estimated_future_cost() {
list($avg_cost,$min_cost)=$this->get_estimated_future_cost();



 $sql=sprintf("update `Part Dimension` set `Part Average Future Cost Per Unit`=%s,`Part Minimum Future Cost Per Unit`=%s where `Part SKU`=%d "
                     ,prepare_mysql($avg_cost)
                     ,prepare_mysql($min_cost)
                     ,$this->id);
             
 //print "$sql\n";      
 mysql_query($sql);
}








    function get_unit_cost($date=false) {



        if ($date) {
            // print "from date";
            
            $sql=sprintf("select AVG(`Supplier Product Cost Per Case`/`Supplier Product Units Per Case`*`Supplier Product Units Per Part`) as cost 
from `Supplier Product Dimension` SP 
left join `Supplier Product Part Dimension` SPPD  on (SP.`Supplier Product Key`=SPPD.`Supplier Product Key` )
left join `Supplier Product Part List` B  on (SPPD.`Supplier Product Part Key`=B.`Supplier Product Part Key` )
 where `Part SKU`=%d and (  ( `Supplier Product Part Most Recent`='Yes'  and `Supplier Product Part Valid From`<=%s ) or ( `Supplier Product Part Most Recent`='No' and `Supplier Product Part Valid From`<=%s and `Supplier Product Part Valid To`>=%s) ) ",
 $this->sku
  ,prepare_mysql($date)
                         ,prepare_mysql($date)
                         ,prepare_mysql($date)
 
 );
 
 
          
            	//print $sql;
            //exit;
            $res=mysql_query($sql);
            if ($row=mysql_fetch_array($res)) {
                if (is_numeric($row['cost']))
                    return $row['cost'];
            }
        }
        // print "not found in date";

        $sql=sprintf("select AVG(`Supplier Product Cost Per Case`/`Supplier Product Units Per Case`*`Supplier Product Units Per Part`) as cost 
from `Supplier Product Dimension` SP 
left join `Supplier Product Part Dimension` SPPD  on (SP.`Supplier Product Key`=SPPD.`Supplier Product Key` )
left join `Supplier Product Part List` B  on (SPPD.`Supplier Product Part Key`=B.`Supplier Product Part Key` )
 where `Part SKU`=%d and `Supplier Product Part Most Recent`='Yes' ",$this->sku);
	//  print $sql;
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            return $row['cost'];
        }

        return 0;




    }






    function get_estimated_future_cost() {
        $sql=sprintf("select min(`Supplier Product Cost Per Case`*`Supplier Product Units Per Part`/`Supplier Product Units Per Case`) as min_cost ,avg(`Supplier Product Cost Per Case`*`Supplier Product Units Per Part`/`Supplier Product Units Per Case`) as avg_cost   from `Supplier Product Part List` SPPL left join  `Supplier Product Part Dimension` SPPD on (  SPPL.`Supplier Product Part Key`=SPPD.`Supplier Product Part Key`)    left join  `Supplier Product Dimension` SPD  on (SPPD.`Supplier Product Key`=SPD.`Supplier Product Key`)      where `Part SKU`=%d and `Supplier Product Part Most Recent`='Yes'",$this->sku);
	//	print "$sql\n";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            if (is_numeric($row['avg_cost']))
                $avg_cost=$row['avg_cost'];
            else
                $avg_cost='';
            if (is_numeric($row['min_cost']))
                $min_cost=$row['min_cost'];
            else
                $min_cost='';

        } else {
            $avg_cost='';
            $min_cost='';
        }

	//	print "($avg_cost,$min_cost\n";
    return array($avg_cost,$min_cost);
    
    }
    function update_used_in() {
        $used_in_products='';
        $raw_used_in_products='';
        $sql=sprintf("select `Store Code`,PD.`Product ID`,`Product Code` from `Product Part List` PPL left join `Product Part Dimension` PPD on (PPD.`Product Part Key`=PPL.`Product Part Key`) left join `Product Dimension` PD on (PD.`Product ID`=PPD.`Product ID`) left join `Store Dimension`  on (PD.`Product Store Key`=`Store Key`)  where PPL.`Part SKU`=%d  order by `Product Code`,`Store Code`",$this->data['Part SKU']);
        $result=mysql_query($sql);
          //  print "$sql\n";
        $used_in=array();
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            if (!array_key_exists($row['Product Code'],$used_in))
                $used_in[$row['Product Code']]=array();
            if (!array_key_exists($row['Store Code'],$used_in[$row['Product Code']]))
                $used_in[$row['Product Code']][$row['Store Code']]=array();
            $used_in[$row['Product Code']][$row['Store Code']][$row['Product ID']]=1;

        }
        // print_r($used_in);
        foreach($used_in as $code=>$store_data) {
            $raw_used_in_products.=' '.$code;
            $used_in_products.=sprintf(', <a href="product.php?code=%s">%s</a>',$code,$code);
            $used_in_products_2='';
            foreach($store_data as $store_code=>$product_id_data) {
                foreach($product_id_data as $product_id=>$tmp) {
                    $used_in_products_2.=sprintf(',<a href="product.php?pid=%d">%s</a>',$product_id,$store_code);
                }
            }
            $used_in_products_2=preg_replace('/^,/','',$used_in_products_2);
            $used_in_products.=" ($used_in_products_2)";

        }

        //$used_in_products.=sprintf(', <a href="product.php?code=%s">%s</a>',$row['Product Code'],$row['Product Code']);
        //$raw_used_in_products=' '.$row['Product Code'];

        $used_in_products=preg_replace('/^, /','',$used_in_products);
        $sql=sprintf("update `Part Dimension` set `Part XHTML Currently Used In`=%s ,`Part Currently Used In`=%s  where `Part SKU`=%d",prepare_mysql(_trim($used_in_products)),prepare_mysql(_trim($raw_used_in_products)),$this->id);
        //  print "$sql\n";
        mysql_query($sql);
    }


    function wrap_transactions() {

        $sql=sprintf("select `Location Key` from `Inventory Transaction Fact` where  `Part SKU`=%d  group by `Location Key`  ",$this->sku);
        $locations=array(1=>1);    
        $res2=mysql_query($sql);
        while ($row2=mysql_fetch_array($res2)) {
            $locations[$row2['Location Key']]=$row2['Location Key'];
        
        }
        
        foreach($locations as $location_key){

            $sql=sprintf("select `Date`,`Inventory Transaction Type` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  order by `Date`,`Inventory Transaction Key`   ",$this->sku,$location_key);
            //print "$sql\n";
            $res3=mysql_query($sql);
            if ($row3=mysql_fetch_array($res3)) {
                if ($row3['Inventory Transaction Type']=='Associate') {
                    $sql=sprintf("delete from  `Inventory Transaction Fact` where `Part SKU`=%d  and `Inventory Transaction Type` in ('Associate') and `Date`=%s and `Location Key`=%d  "
                                 ,$this->sku
                                 ,prepare_mysql($row3['Date'])
                                 ,$location_key
                                );
                    // print "$sql\n";
                    mysql_query($sql);
                }
            }

            $sql=sprintf("select `Date`,`Inventory Transaction Type` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  order by `Date` desc ,`Inventory Transaction Key` desc ",$this->sku,$location_key);
            $last_itf_date='none';
            $res3=mysql_query($sql);
            //print "$sql\n";
            if ($row3=mysql_fetch_array($res3)) {
                if ($row3['Inventory Transaction Type']=='Disassociate') {
                    $sql=sprintf("delete from  `Inventory Transaction Fact` where `Part SKU`=%d  and `Inventory Transaction Type` in ('Disassociate') and `Date`=%s and `Location Key`=%d  "
                                 ,$this->sku
                                 ,prepare_mysql($row3['Date'])
                                 ,$location_key
                                );
                    //print "$sql\n";
                    mysql_query($sql);
                }
            }





            $sql=sprintf('select `Inventory Audit Date` from `Inventory Audit Dimension` where `Inventory Audit Part SKU`=%d and `Inventory Audit Location Key`=%d  order by `Inventory Audit Date`' ,$this->sku,$location_key);
            $first_audit_date='none';
            $res3=mysql_query($sql);
            if ($row3=mysql_fetch_array($res3)) {
                $first_audit_date=($row3['Inventory Audit Date']);
            }
             //   print "\n$sql\n";
            $sql=sprintf("select `Date` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  order by `Date`  ",$this->sku,$location_key);
            $first_itf_date='none';
            $res3=mysql_query($sql);
            if ($row3=mysql_fetch_array($res3)) {
                $first_itf_date=($row3['Date']);
            }
           //  print "$sql\n";
            //print "R: $first_audit_date $first_itf_date \n ";
            if ($first_audit_date=='none' and $first_itf_date=='none') {
            //    print "\nError1 : Part ".$this->sku." ".$this->data['Part XHTML Currently Used In']."  \n";
            //    exit;
            //    return;
                $first_date=$this->data['Part Valid From'];
                // print "\nError1 : Part ".$this->sku." ".$this->data['Part XHTML Currently Used In']." ".$this->data['Part Valid From']." \n";
                
            }elseif($first_audit_date=='none') {
                $first_date=$first_itf_date;
            }
            elseif($first_itf_date=='none') {
                $first_date=$first_audit_date;
            }
            else {
                if (strtotime($first_itf_date)< strtotime($first_audit_date) )
                    $first_date=$first_itf_date;
                else
                    $first_date=$first_audit_date;

            }

           
            $pl_data=array(
                         'Part SKU'=>$this->sku,
                         'Location Key'=>$location_key,
                         'Date'=>$first_date);
           //print_r($pl_data);
            $part_location=new PartLocation('find',$pl_data
                                            ,'create');
           // print_r($part_location);
            if ($part_location->found) {

                $sql=sprintf("delete from  `Inventory Transaction Fact` where `Inventory Transaction Type` in ('Associate') and `Part SKU`=%d and `Location Key`=%d  limit 1 "
                             ,$this->sku
                             ,$location_key
                            );
               

                mysql_query($sql);
                $location=new Location($location_key);
                $details=_('Part')." SKU".sprintf("%05d",$this->sku)." "._('associated with location').": ".$location->data['Location Code'];
                $sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`) values (%d,%d,%s,%f,%.2f,%s,%s,%s)"
                             ,$this->sku
                             ,$location_key
                             ,"'Associate'"
                             ,0
                             ,0
                             ,0
                             ,prepare_mysql($details)
                             ,prepare_mysql($first_date)
                             
                            );
                mysql_query($sql);
                //print "$sql\n";
            }
           


            if ($this->data['Part Status']=='Discontinued') {

                $sql=sprintf('select `Inventory Audit Date` from `Inventory Audit Dimension` where `Inventory Audit Part SKU`=%d and `Inventory Audit Location Key`=%d  order by `Inventory Audit Date` desc' ,$this->sku,$location_key);
                $last_audit_date='none';
                $res3=mysql_query($sql);
                if ($row3=mysql_fetch_array($res3)) {
                    $last_audit_date=($row3['Inventory Audit Date']);
                }

                $sql=sprintf("select `Date` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  order by `Date` desc  ",$this->sku,$location_key);
                $last_itf_date='none';
                $res3=mysql_query($sql);
                if ($row3=mysql_fetch_array($res3)) {
                    $last_itf_date=($row3['Date']);
                }
                //print "$sql\n";

                if ($last_audit_date=='none' and $last_itf_date=='none') {
                    print "\nError2: Part ".$this->sku." ".$this->data['Part XHTML Currently Used In']."  \n";
                    return;
                }
                elseif($last_audit_date=='none') {
                    $last_date=$last_itf_date;
                }


                elseif($last_itf_date=='none') {

                    $last_date=$last_audit_date;
                }
                else {
                    if (strtotime($last_itf_date)>strtotime($last_audit_date) )
                        $last_date=$last_itf_date;
                    else
                        $last_date=$last_audit_date;

                }


                $data=array('Date'=>$last_date,'Note'=>_('Discontinued'));

                $part_location->disassociate($data);
                $this->update_valid_to($last_date);
                $this->update_stock();

            }
           
            $this->update_valid_from($first_date);

        }



        //Todo wrap by valid_dates


    }
    function get_description() {
        return $this->data['Part XHTML Description'];
    }


    function get_product_ids() {


        $sql=sprintf("select  `Product ID` from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where  `Part SKU`=%d   "
                     ,$this->sku
                    );

        $res=mysql_query($sql);
        $product_ids=array();
        if ($row=mysql_fetch_array($res)) {
            $product_ids[$row['Product ID']]= $row['Product ID'];
        }

        return $product_ids;




    }

    function update_product_part_list_dates() {




        $part_from=$this->data['Part Valid From'];
        $part_to=$this->data['Part Valid To'];

        foreach($this->get_product_ids()   as $pid) {

            $product=new Product('pid',$pid);

            $product_from=$product->data['Product Valid From'];
            $product_to=$product->data['Product Valid To'];
            $store_key=$product->data['Product Store Key'];

            $from=$part_from;

            if ($this->data['Part Status']=='In Use') {
                $to='';
            } else {
                $to=$part_to;
                if (strtotime($to)<strtotime($product_to))
                    $to=$product_to;
            }



            $from=$part_from;
            if (strtotime($from)>strtotime($product_from))
                $from=$product_from;





//print "$from -> $to\n";


            $sql=sprintf("select  PPD.`Product Part Key` from `Product Part List` PPL left join `Product Part Dimension` PPD  on (PPD.`Product Part Key`=PPL.`Product Part Key`) where `Part SKU`=%d  and PPD.`Product ID`=%d "
                         ,$this->sku,$pid);
            $res2=mysql_query($sql);

            if ($row2=mysql_fetch_array($res2)) {

                $status='No';
                if ($to=='')
                    $status='Yes';

                $sql=sprintf("update `Product Part Dimension` set `Product Part Valid From`=%s , `Product Part Valid To`=%s ,`Product Part Most Recent`=%s where `Product Part Key`=%d"
                             ,prepare_mysql($from)
                             ,prepare_mysql($to)

                             ,prepare_mysql($status)
                             ,$row2['Product Part Key']
                            );
//print "$sql\n";
                if (!mysql_query($sql))
                    print "$sql\n";

            }

        }





    }

function update_full_search(){

$first_full_search=$this->get_sku().' '.strip_tags($this->data['Part XHTML Description']);
$second_full_search=strip_tags($this->data['Part XHTML Currently Supplied By']);

$sql=sprintf("insert into `Search Full Text Dimension` (`Store Key`,`Subject`,`Subject Key`,`First Search Full Text`,`Second Search Full Text`,`Search Result Name`,`Search Result Description`,`Search Result Image`)  values  (%s,'Part',%d,%s,%s,%s,%s,%s) on duplicate key 
update `First Search Full Text`=%s ,`Second Search Full Text`=%s ,`Search Result Name`=%s,`Search Result Description`=%s,`Search Result Image`=%s "
,0
,$this->sku
,prepare_mysql($first_full_search)
,prepare_mysql($second_full_search,false)
,prepare_mysql($this->get_sku(),false)
,prepare_mysql($this->data['Part XHTML Description'],false)
,prepare_mysql('',false)
,prepare_mysql($first_full_search)
,prepare_mysql($second_full_search,false)
,prepare_mysql($this->get_sku(),false)
,prepare_mysql($this->data['Part XHTML Description'],false)

,prepare_mysql('',false)
);
mysql_query($sql);

}



}
