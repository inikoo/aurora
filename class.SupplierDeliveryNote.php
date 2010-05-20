<?php
include_once('class.DB_Table.php');


class SupplierDeliveryNote extends DB_Table {

    function SupplierDeliveryNote($arg1=false,$arg2=false,$arg3=false) {

        $this->table_name='Supplier Delivery Note';
        $this->ignore_fields=array('Supplier Delivery Note Key');


        if (is_string($arg1)) {
            if (preg_match('/new|create/i',$arg1)) {
                $this->find($arg2,'create');
                return;
            }
            if (preg_match('/find/i',$arg1)) {
                $this->find($arg2,$arg3);
                return;
            }


        }



        if (is_numeric($arg1)) {
            $this->get_data('id',$arg1);
            return;
        }
        $this->get_data($arg1,$arg2);

    }


    function find($raw_data,$options) {
        if (isset($raw_data['editor'])) {
            foreach($raw_data['editor'] as $key=>$value) {
                if (array_key_exists($key,$this->editor))
                    $this->editor[$key]=$value;
            }
        }

        $this->found=false;
        $this->found_key=false;
        $create='';
        $update='';
        if (preg_match('/create/i',$options)) {
            $create='create';
        }
        if (preg_match('/update/i',$options)) {
            $update='update';
        }

        $data=$this->base_data();
        foreach($raw_data as $key=>$value) {
            if (array_key_exists($key,$data))
                $data[$key]=_trim($value);
        }

        if ($data['Supplier Delivery Note Supplier Key'] and $data['Supplier Delivery Note Public ID']) {
            $sql=sprintf("select `Supplier Delivery Note Key` from `Supplier Delivery Note Dimension` where `Supplier Delivery Note Public ID`=%s  and `Supplier Delivery Note Supplier Key`=%d "
                         ,prepare_mysql($data['Supplier Delivery Note Public ID'])
                         ,$data['Supplier Delivery Note Supplier Key']
                        );

            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $this->found=true;
                $this->found_key=$row['Supplier Delivery Note Key'];
            }
        }

        if ($this->found_key) {
            $this->get_data('id',$this->found_key);
        }

        if ($create and !$this->found_key) {

            $this->create($data);

        }


    }


    function create($data) {

        if ($data['Supplier Delivery Note Public ID']=='') {
            $this->error=true;
            $this->msg='NO public id';
            return;
        }
        $supplier=new Supplier($data['Supplier Delivery Note Supplier Key']);

        if (!$supplier->id) {
            $this->error=true;
            $this->msg='wrong supplier';
            return;
        }



        //print_r($data);
        $data['Supplier Delivery Note Creation Date']=date('Y-m-d H:i:s');
        $data['Supplier Delivery Note Last Updated Date']=date('Y-m-d H:i:s');


        $data['Supplier Delivery Note File As']=$this->get_file_as($data['Supplier Delivery Note Public ID']);
        $base_data=$this->base_data();

        foreach($data as $key=>$value) {
            if (array_key_exists($key,$base_data))
                $base_data[$key]=_trim($value);
        }
        //  print_r($base_data);


        $keys='(';
        $values='values(';
        foreach($base_data as $key=>$value) {
            $keys.="`$key`,";

            if (preg_match('/XHTML|Supplier Delivery Note POs/',$key))
                $values.="'".addslashes($value)."',";
            else
                $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Supplier Delivery Note Dimension` %s %s",$keys,$values);

        //  print($sql);

        if (mysql_query($sql)) {
            $this->id = mysql_insert_id();
            $this->get_data('id',$this->id);
            $supplier->update_orders();

        } else
            exit(" error can no create supplier delivery note");


    }

    function get_data($key,$id) {
        if ($key=='id') {
            $sql=sprintf("select * from `Supplier Delivery Note Dimension` where `Supplier Delivery Note Key`=%d",$id);
            $result=mysql_query($sql);
            if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $this->id=$this->data['Supplier Delivery Note Key'];
            }
        }
        elseif($key=='public id' or $key=='public_id') {
            $sql=sprintf("select * from `Supplier Delivery Note Dimension` where `Supplier Delivery Note Public ID`=%s",prepare_mysql($id));
            $result=mysql_query($sql);
            print "$sql\n";
            if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $this->id=$this->data['Supplier Delivery Note Key'];
            }
        }
    }

    function get($key='') {

        if (array_key_exists ( $key, $this->data ))
            return $this->data [$key];

        if ($key=='Number Items')
            return number($this->data ['Supplier Delivery Note Number Items']);
        if (preg_match('/^(Total|Items|(Shipping |Charges )?Net).*(Amount)$/',$key)) {
            $amount='Supplier Delivery Note '.$key;
            return money($this->data[$amount]);
        }
        if (preg_match('/Date$/',$key)) {
            $date='Supplier Delivery Note '.$key;
            return strftime("%e-%b-%Y %H:%M",strtotime($this->data[$date]));
        }




        if (array_key_exists($key,$this->data))
            return $this->data[$key];

    }


    function add_order_transaction($data) {
        
    
    
        if ($this->data['Supplier Delivery Note Current State']=='In Process') {
        
        
        
        
        
            $sql=sprintf("select `Purchase Order Key`,`Purchase Order Line`,  `Supplier Delivery Note Line` from `Purchase Order Transaction Fact` where (`Supplier Delivery Note Key`=%d or `Purchase Order Key` in (%s)) and `Supplier Product Key`=%d ",$this->id,$this->data['Supplier Delivery Note POs'],$data ['Supplier Product Key']);
            $res=mysql_query($sql);
            //print $sql;
            if ($row=mysql_fetch_assoc($res)) {
                
                
                
          
                
                
                if ($row['Purchase Order Line']) {


                    $sql = sprintf ( "update`Purchase Order Transaction Fact` set  `Supplier Delivery Note Quantity`=%f, `Supplier Delivery Note Quantity Type`=%s,`Supplier Delivery Note Last Updated Date`=%s,`Supplier Delivery Note Line`=%d ,`Supplier Delivery Note State`=%s where `Purchase Order Key`=%d and `Purchase Order Line`=%d "
                                     ,$data ['qty']
                                     ,prepare_mysql ($data ['qty_type'])
                                     ,prepare_mysql ( $data ['date'] )
                                     ,$this->get_next_line_number()
                                     ,prepare_mysql('Inputted')
                                     ,$row['Purchase Order Key']
                                     ,$row['Purchase Order Line']
                                   );
                    //print "$sql";
                    mysql_query($sql);
                     $po=new PurchaseOrder($row['Purchase Order Key']);
                      $po->update_state();
                    
                }elseif($row['Supplier Delivery Note Line']) {
                $dn_state='Inputted';
                
             if ($data ['qty']==0) {
            
                    $sql=sprintf("delete from `Purchase Order Transaction Fact` where `Supplier Invoice Key` IS NULL and   `Purchase Order Key` is NULL and `Supplier Delivery Note Key`=%d and `Supplier Product Key`=%d ",$this->id,$data ['Supplier Product Key']);
                    mysql_query($sql);
           
                
            }else{
                $sql = sprintf ( "update`Purchase Order Transaction Fact` set  `Supplier Delivery Note Quantity`=%f, `Supplier Delivery Note Quantity Type`=%s,`Supplier Delivery Note Last Updated Date`=%s  ,`Supplier Delivery Note State`=%s  where `Supplier Delivery Note Key`=%d and `Supplier Delivery Note Line`=%d "
                                     ,$data ['qty']
                                     ,prepare_mysql ($data ['qty_type'])
                                     ,prepare_mysql ( $data ['date'] )
                                     ,prepare_mysql($dn_state)
                                     ,$this->id
                                     ,$row['Supplier Delivery Note Line']
                                   );
                    //print "$sql";
                    mysql_query($sql);
}
}
}elseif($data ['qty']>0) {
               
               $sql = sprintf ( "insert into `Purchase Order Transaction Fact` (`Supplier Delivery Note Last Updated Date`,`Supplier Product Key`,`Purchase Order Current Dispatching State`,`Supplier Key`,`Supplier Delivery Note Key`,`Supplier Delivery Note Line`,`Supplier Delivery Note Quantity`,`Supplier Delivery Note Quantity Type`) values (%s,%d,  %s    ,%d,%d,%d, %.6f,%s)   "
                                 , prepare_mysql ( $data ['date'] )
                                 , $data ['Supplier Product Key']
                                 , prepare_mysql ( 'Found in Delivery Note' )
                                 , $this->data['Supplier Delivery Note Supplier Key' ]
                                 , $this->data ['Supplier Delivery Note Key']
                                 , $data ['line_number']

                                 , $data ['qty']
                                 , prepare_mysql ( $data ['qty_type'] )
                                 ,prepare_mysql($this->data['Supplier Delivery Note Current State'])

                               );
               // print "$sql";

                mysql_query($sql);
            }


  
            
         
            
        } else {// Supplier Delivery Note Current Stat not In Process




        }


//$this->update_affected_products();

        return array('qty'=>$data ['qty']);

        //  print "$sql\n";


    }

    function update_delivered_transaction($data) {


        if ($data ['Supplier Delivery Note Received Quantity']<0)
            $data ['Supplier Delivery Note Received Quantity']=0;

        $sql=sprintf("select `Supplier Delivery Note Damaged Quantity`,`Supplier Delivery Note Quantity`,`Supplier Delivery Note Line` from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=%d and `Supplier Product Key`=%d order by `Purchase Order Last Updated Date` desc "
                     ,$this->id
                     ,$data['Supplier Product Key']
                    );
        $res=mysql_query($sql);
        $sum_quantity=0;
        $sum_damaged_quantity=0;

        $quantity_data=array();

        while ($row=mysql_fetch_array($res)) {
            $quantity_data[$row['Supplier Delivery Note Line']]=$row['Supplier Delivery Note Quantity'];
            $sum_quantity+=$row['Supplier Delivery Note Quantity'];
            $sum_damaged_quantity+=$row['Supplier Delivery Note Damaged Quantity'];

        }


        if (count($quantity_data)==0) {
            $this->error=true;
            $this->msg="Item do not found $sql";

            return;
        } else if (count($quantity_data)==1) {
            foreach($quantity_data as $key=>$value) {
                $quantity_data[$key]=$data ['Supplier Delivery Note Received Quantity'];
            }
        } else {
            $resolved=false;
            $difference=$data ['Supplier Delivery Note Received Quantity']-$sum_quantity;

            if ($data ['Supplier Delivery Note Received Quantity']==0) {
                foreach($quantity_data as $key=>$value) {
                    $quantity_data[$key]=0;
                }


            } else if ($difference==0) {
                $resolved=true;

            } else if ($difference<0) {

                foreach($quantity_data as $key=>$value) {
                    if ($resolved)
                        break;
                    if ($value==$difference) {
                        $quantity_data[$key]=0;
                        $resolved=true;
                    }
                }

                foreach($quantity_data as $key=>$value) {
                    if ($resolved)
                        break;

                    if ($value<$difference) {
                        $quantity_data[$key]=0;
                        $difference=$value-$difference;
                    } else {
                        $quantity_data[$key]=$value-$difference;
                        $difference=0;
                    }
                    if ($difference==0)
                        $resolved=true;

                }




            }



        }

        foreach($quantity_data as $line=>$received_quantity) {
            $sql = sprintf ("update`Purchase Order Transaction Fact` set  `Supplier Delivery Note Received Quantity`=%f,`Supplier Delivery Note Last Updated Date`=%s  where `Supplier Delivery Note Key`=%d and `Supplier Delivery Note Line`=%d"
                            ,$received_quantity
                            ,prepare_mysql ( $data ['Supplier Delivery Note Last Updated Date'] )
                            ,$this->id
                            ,$line
                           );
            //print "$sql";
            mysql_query($sql);
        }

        $data=array(
                  'Supplier Delivery Note Last Updated Date'=>$data ['Supplier Delivery Note Last Updated Date']
                          ,'Supplier Product Key'=>$data['Supplier Product Key']
                                                  ,'Supplier Delivery Note Damaged Quantity'=>$sum_damaged_quantity
                                ,'Supplier Delivery Note Received Quantity'=>$received_quantity

                                                  
              );
        $damaged_data=$this->update_damaged_transaction($data);

$this->update_affected_products();
        return array('qty'=>$data ['Supplier Delivery Note Received Quantity'],'damaged_qty'=>$damaged_data['damaged_qty']);

    }


    function update_damaged_transaction($data) {


        if ($data ['Supplier Delivery Note Damaged Quantity']<0)
            $data ['Supplier Delivery Note Damaged Quantity']=0;

        $sql=sprintf("select `Supplier Delivery Note Received Quantity`,`Supplier Delivery Note Line` from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=%d and `Supplier Product Key`=%d order by `Purchase Order Last Updated Date` desc "
                     ,$this->id
                     ,$data['Supplier Product Key']
                    );
        $res=mysql_query($sql);
        $sum_quantity=0;
        $sum_damaged_quantity=0;

        $quantity_data=array();
        $damaged_quantity_data=array();

        while ($row=mysql_fetch_array($res)) {
            $quantity_data[$row['Supplier Delivery Note Line']]=$row['Supplier Delivery Note Received Quantity'];
            $damaged_quantity_data[$row['Supplier Delivery Note Line']]=0;

            $sum_quantity+=$row['Supplier Delivery Note Received Quantity'];
        }


        if ($sum_quantity<$data ['Supplier Delivery Note Damaged Quantity'])
            $data ['Supplier Delivery Note Damaged Quantity']=$sum_quantity;




        if (count($quantity_data)==0) {
            $this->error=true;
            $this->msg="Item do not found $sql";

            return;
        } else if (count($quantity_data)==1) {
            foreach($quantity_data as $key=>$value) {
                $damaged_quantity_data[$key]=$data ['Supplier Delivery Note Damaged Quantity'];
            }
        } else {
            $damaged=$data ['Supplier Delivery Note Damaged Quantity'];

            foreach($quantity_data as $key=>$value) {


                if ($value>=$damaged) {
                    $damaged_quantity_data[$key]=$damaged;
                    $damaged=0;
                } else {
                    $damaged_quantity_data[$key]=$value;
                    $damaged=$damaged-$value;
                }

            }




        }





        foreach($damaged_quantity_data as $line=>$damaged_quantity) {
            $sql = sprintf ("update`Purchase Order Transaction Fact` set  `Supplier Delivery Note Damaged Quantity`=%f,`Supplier Delivery Note Last Updated Date`=%s  where `Supplier Delivery Note Key`=%d and `Supplier Delivery Note Line`=%d"
                            ,$damaged_quantity
                            ,prepare_mysql ( $data ['Supplier Delivery Note Last Updated Date'] )
                            ,$this->id
                            ,$line
                           );
            //print "$sql";
            mysql_query($sql);
        }


        return array('qty'=>$sum_quantity,'damaged_qty'=>$data ['Supplier Delivery Note Damaged Quantity']);

    }


    function get_next_line_number() {

        $sql=sprintf("select MAX(`Supplier Delivery Note Line`) as max_line from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=%d ",$this->id);
        $res=mysql_query($sql);

        $line_number=1;
        if ($row=mysql_fetch_array($res))
            $line_number=(int) $row['max_line']+1;
        return $line_number;


    }


    function get_next_public_id($supplier_key) {
        $supplier=new Supplier($supplier_key);
        $code=$supplier->data['Supplier Code'];

        $sql=sprintf("select `Supplier Delivery Note Public ID` from `Supplier Delivery Note Dimension` where `Supplier Delivery Note Supplier Key`=%d order by REPLACE(`Supplier Delivery Note Public ID`,%s,'') desc limit 1",$supplier_key,prepare_mysql($code));
        $res=mysql_query($sql);

        $line_number=1;
        if ($row=mysql_fetch_array($res))
            $line_number= (int) preg_replace('/[^\d]/','',$row['Supplier Delivery Note Public ID'])+1;

        return sprintf('%s%04d',$code,$line_number);

    }

    function get_file_as($name) {

        return $name;
    }


    function update_item_totals_from_order_transactions() {




        $sql = "select count(Distinct `Supplier Product Key`) as num_items ,sum(`Supplier Delivery Note Net Amount`) as net, sum(`Supplier Delivery Note Tax Amount`) as tax,  sum(`Supplier Delivery Note Shipping Amount`) as shipping from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=" . $this->id;
        //print "$sql\n";
        $result = mysql_query ( $sql );
        if ($row = mysql_fetch_array ( $result, MYSQL_ASSOC )) {
            //	  $total = $row ['gross'] + $row ['tax'] + $row ['shipping']  - $row ['discount'] + $this->data ['Order Items Adjust Amount'];

            $this->data ['Supplier Delivery Note Items Net Amount'] = $row ['net'];
            $this->data ['Supplier Delivery Note Number Items'] = $row ['num_items'];


            $sql = sprintf ( "update `Supplier Delivery Note Dimension` set `Supplier Delivery Note Number Items`=%d , `Supplier Delivery Note Items Net Amount`=%.2f , `Supplier Delivery Note Items Tax Amount`=%.2f where  `Supplier Delivery Note Key`=%d "
                             , $this->data ['Supplier Delivery Note Number Items']
                             , $this->data ['Supplier Delivery Note Items Net Amount']
                             , $this->data ['Supplier Delivery Note Items Tax Amount']


                             , $this->id);


            //exit;
            mysql_query ( $sql );


        }


    }

    function get_number_items() {
        $num_items=0;
        $sql=sprintf("select count(Distinct `Supplier Product Key`) as num_items  from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=%d",$this->id);
        $result = mysql_query ( $sql );
        if ($row = mysql_fetch_array ( $result, MYSQL_ASSOC )) {
            $num_items=$row['num_items'];
        }

        return $num_items;
    }





    function update_totals_from_order_transactions($force_total=false) {


        if (!$force_total)
            $force_total=array();



        $this->data ['Supplier Delivery Note Total Tax Amount'] = $this->data ['Supplier Delivery Note Items Tax Amount'] + $this->data ['Supplier Delivery Note Shipping Tax Amount']+  $this->data ['Supplier Delivery Note Charges Tax Amount']+  $this->data ['Supplier Delivery Note Tax Credited Amount'];
        $this->data ['Supplier Delivery Note Total Net Amount']=$this->data ['Supplier Delivery Note Items Net Amount']+  $this->data ['Supplier Delivery Note Shipping Net Amount']+  $this->data ['Supplier Delivery Note Charges Net Amount']+  $this->data ['Supplier Delivery Note Net Credited Amount'];

        $this->data ['Supplier Delivery Note Total Amount'] = $this->data ['Supplier Delivery Note Total Tax Amount'] + $this->data ['Supplier Delivery Note Total Net Amount'];
        $this->data ['Supplier Delivery Note Total To Pay Amount'] = $this->data ['Supplier Delivery Note Total Amount'];
        $sql = sprintf ( "update `Supplier Delivery Note Dimension` set `Supplier Delivery Note Total Net Amount`=%.2f ,`Supplier Delivery Note Total Tax Amount`=%.2f ,`Supplier Delivery Note Shipping Net Amount`=%.2f ,`Supplier Delivery Note Shipping Tax Amount`=%.2f ,`Supplier Delivery Note Charges Net Amount`=%.2f ,`Supplier Delivery Note Charges Tax Amount`=%.2f ,`Supplier Delivery Note Total Amount`=%.2f , `Supplier Delivery Note Total To Pay Amount`=%.2f  where  `Supplier Delivery Note Key`=%d "
                         , $this->data ['Supplier Delivery Note Total Net Amount']
                         , $this->data ['Supplier Delivery Note Total Tax Amount']
                         , $this->data ['Supplier Delivery Note Shipping Net Amount']
                         , $this->data ['Supplier Delivery Note Shipping Tax Amount']

                         , $this->data ['Supplier Delivery Note Charges Net Amount']
                         , $this->data ['Supplier Delivery Note Charges Tax Amount']

                         , $this->data ['Supplier Delivery Note Total Amount']
                         , $this->data ['Supplier Delivery Note Total To Pay Amount']
                         , $this->data ['Supplier Delivery Note Key']
                       );


        //exit;


        if (! mysql_query ( $sql ))
            exit ( "$sql eroro2 con no update totals" );




    }



    function delete() {

        if ($this->data['Supplier Delivery Note Current State']=='In Process') {
            $sql=sprintf("delete from `Supplier Delivery Note Dimension` where `Supplier Delivery Note Key`=%d",$this->id);
            mysql_query($sql);
            $sql=sprintf("delete from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=%d and `Purchase Order Key` is NULL and `Supplier Invoice Key` IS NULL ",$this->id);
            mysql_query($sql);
            $sql=sprintf("update `Purchase Order Transaction Fact` set `Supplier Delivery Note Key`=NULL ,`Supplier Delivery Note Line`=NULL,`Supplier Delivery Note Quantity`=0 , `Supplier Delivery Note Quantity Type`=NULL,`Supplier Delivery Note Last Updated Date`=NULL  where `Supplier Delivery Note Key`=%d  ",$this->id);
            mysql_query($sql);


        } else {
            $this->error=true;
            $this->msg='Can not deleted submitted supplier delivery note';
        }
    }

    function input($data) {


        foreach($data as $key=>$value) {
            if (array_key_exists($key,$this->data)) {
                $this->data[$key]=$value;
            }

        }

        $sql=sprintf("update `Supplier Delivery Note Dimension` set `Supplier Delivery Note Input Date`=%s,`Supplier Delivery Note Main Inputter Key`=%s,`Supplier Delivery Note Current State`='Inputted'   where `Supplier Delivery Note Key`=%d"
                     ,prepare_mysql($data['Supplier Delivery Note Input Date'])
                     ,prepare_mysql($data['Supplier Delivery Note Main Inputter Key'])
                     ,$this->id);

        //print $sql;
        mysql_query($sql);

        $sql=sprintf("update `Purchase Order Transaction Fact` set  `Supplier Delivery Note Last Updated Date`=%s, `Supplier Delivery Note State`='Inputted',`Purchase Order Current Dispatching State`='Found in Delivery Note'  where `Supplier Delivery Note Key`=%d"
                     ,prepare_mysql($data['Supplier Delivery Note Input Date'])
                     ,$this->id
                    );
        mysql_query($sql);
        //print $sql;

     $this->update_affected_products();
     $this->update_affected_purchase_orders();
    }
    
    
    function update_affected_purchase_orders(){
   $sql=sprintf("select `Purchase Order Key` from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=%d",$this->id);
        $res=mysql_query($sql);
        while ($row=mysql_fetch_array($res)) {
            $po=new PurchaseOrder($row['Purchase Order Key']);
           $po->update_state();

        }


}

    
    

function update_affected_products(){
   $sql=sprintf("select `Supplier Product Key`,`Supplier Delivery Note Quantity` from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=%d",$this->id);
        $res=mysql_query($sql);
        while ($row=mysql_fetch_array($res)) {
            $supplier_product=new SupplierProduct('key',$row['Supplier Product Key']);
            $products=$supplier_product->get_products();
            foreach($products as $product) {
                $product=new Product('pid',$product['Product ID']);
                $product->update_next_supplier_shippment();

            }

        }


}


    function mark_as_received($data) {




        foreach($data as $key=>$value) {
            if (array_key_exists($key,$this->data)) {
                $this->data[$key]=$value;
            }

        }

        $sql=sprintf("update `Supplier Delivery Note Dimension` set `Supplier Delivery Note Received Date`=%s,`Supplier Delivery Note Main Receiver Key`=%s,`Supplier Delivery Note Current State`='Received'   where `Supplier Delivery Note Key`=%d"
                     ,prepare_mysql($data['Supplier Delivery Note Received Date'])
                     ,prepare_mysql($data['Supplier Delivery Note Main Receiver Key'])
                     ,$this->id);

        //print $sql;
        mysql_query($sql);

        $sql=sprintf("update `Purchase Order Transaction Fact` set `Supplier Delivery Note Received Location Key`=%d , `Supplier Delivery Note Last Updated Date`=%s, `Supplier Delivery Note State`='Received'  where `Supplier Delivery Note Key`=%d"
                     ,$data['Supplier Delivery Note Received Location Key']
                     ,prepare_mysql($data['Supplier Delivery Note Received Date'])
                     ,$this->id
                    );
        mysql_query($sql);
        // print $sql;

        $this->update_store_products();


    }



    function mark_as_checked($data) {


        foreach($data as $key=>$value) {
            if (array_key_exists($key,$this->data)) {
                $this->data[$key]=$value;
            }

        }

        $sql=sprintf("update `Supplier Delivery Note Dimension` set `Supplier Delivery Note Input Date`=%s,`Supplier Delivery Note Main Checker Key`=%s,`Supplier Delivery Note Current State`='Checked'   where `Supplier Delivery Note Key`=%d"
                     ,prepare_mysql($data['Supplier Delivery Note Checked Date'])
                     ,prepare_mysql($data['Supplier Delivery Note Main Checker Key'])
                     ,$this->id);

        //print $sql;
        mysql_query($sql);

        $sql=sprintf("update `Purchase Order Transaction Fact` set  `Supplier Delivery Note Last Updated Date`=%s, `Supplier Delivery Note State`='Checked'  where `Supplier Delivery Note Key`=%d"
                     ,prepare_mysql($data['Supplier Delivery Note Checked Date'])
                     ,$this->id
                    );
        mysql_query($sql);
        //print $sql;


        //$unknown_convertions=$this->check_for_unknown_sku_conversions();
        //if(count($unknown_convertions))

        $this->convert_to_parts();



        $this->update_store_products();






    }


    function mark_as_damages_checked($data) {


        foreach($data as $key=>$value) {
            if (array_key_exists($key,$this->data)) {
                $this->data[$key]=$value;
            }

        }

        $sql=sprintf("update `Supplier Delivery Note Dimension` set `Supplier Delivery Note Input Date`=%s,`Supplier Delivery Note Main Inputter Key`=%s,`Supplier Delivery Note Current State`='Damages Checked'   where `Supplier Delivery Note Key`=%d"
                     ,prepare_mysql($data['Supplier Delivery Note Damages Checked Date'])
                     ,prepare_mysql($data['Supplier Delivery Note Main Damages Checker Key'])
                     ,$this->id);

        //print $sql;
        mysql_query($sql);

        $sql=sprintf("update `Purchase Order Transaction Fact` set  `Supplier Delivery Note Last Updated Date`=%s, `Supplier Delivery Note State`='Damages Checked'  where `Supplier Delivery Note Key`=%d"
                     ,prepare_mysql($data['Supplier Delivery Note Received Date'])
                     ,$this->id
                    );
        mysql_query($sql);
        //print $sql;

        $sql=sprintf("select `Supplier Product Key`,`Supplier Delivery Note Quantity` from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=%d",$this->id);
        $res=mysql_query($sql);
        while ($row=mysql_fetch_array($res)) {
            $supplier_product=new SupplierProduct('key',$row['Supplier Product Key']);
            $products=$supplier_product->get_products();
            foreach($products as $product) {
                $product=new Product('pid',$product['Product ID']);
                $product->update_next_supplier_shippment();

            }

        }

    }


    function receive($data) {


        foreach($data as $key=>$value) {
            if (array_key_exists($key,$this->data)) {
                $this->data[$key]=$value;
            }

        }

        $sql=sprintf("update `Supplier Delivery Note Dimension` set `Supplier Delivery Note Submitted Date`=%s,`Supplier Delivery Note Estimated Receiving Date`=%s,`Supplier Delivery Note Main Source Type`=%s,`Supplier Delivery Note Main Buyer Key`=%s,`Supplier Delivery Note Main Buyer Name`=%s,`Supplier Delivery Note Current Dispatch State`='Submitted'   where `Supplier Delivery Note Key`=%d"
                     ,prepare_mysql($data['Supplier Delivery Note Submitted Date'])
                     ,prepare_mysql($data['Supplier Delivery Note Estimated Receiving Date'])
                     ,prepare_mysql($data['Supplier Delivery Note Main Source Type'])
                     ,prepare_mysql($data['Supplier Delivery Note Main Buyer Key'])
                     ,prepare_mysql($data['Supplier Delivery Note Main Buyer Name'])
                     ,$this->id);


        mysql_query($sql);

        $sql=sprintf("update `Purchase Order Transaction Fact` set  `Supplier Delivery Note Last Updated Date`=%s `Supplier Delivery Note Current Dispatching State`='Submitted'  where `Supplier Delivery Note Key`=%d",prepare_mysql($data['Supplier Delivery Note Submitted Date']),$this->id);
        mysql_query($sql);

    $this->update_affected_products();
   //  $this->update_affected_purchase_orders();
      
    }

    function update_pos($raw_po_keys) {
        $po_keys=array();
        foreach($raw_po_keys as $po_key) {
            if (!is_numeric($po_key))
                continue;
            $po=new PurchaseOrder($po_key);
            if (!$po->id)
                continue;
            if ($this->data['Supplier Delivery Note Supplier Key']!=$po->data['Purchase Order Supplier Key'])
                continue;
            $po_keys[$po->id]=$po->id;
        }
        $pos=join(',',$po_keys);
        $sql=sprintf("update `Supplier Delivery Note Dimension` set `Supplier Delivery Note POs`=%s where `Supplier Delivery Note Key`=%d "
                     ,prepare_mysql($pos)
                     ,$this->id
                    );
        mysql_query($sql);
        $this->data['Supplier Delivery Note POs']=$pos;
    }


    function creating_take_values_from_pos() {
        $items=array();
        $supplier_product_keys=array();
        //print_r(preg_split('/\,/',$this->data['Supplier Delivery Note POs'] )) ;
        foreach(preg_split('/\,/',$this->data['Supplier Delivery Note POs']) as $po_key) {
            $sql=sprintf("select `Purchase Order Line`,`Supplier Product Key`,`Purchase Order Quantity`,`Purchase Order Quantity Type` from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d  ",$po_key);

            $res=mysql_query($sql);
            while ($row=mysql_fetch_array($res)) {

                if (array_key_exists($row['Supplier Product Key'],$supplier_product_keys)) {
                    $line= $supplier_product_keys[$row['Supplier Product Key']];

                    if ($items[$line]['Purchase Order Quantity Type']!=$row['Purchase Order Quantity Type']) {
                        $supplier_product=new SupplierProduct($row['Supplier Product Key']);
                        $row['Purchase Order Quantity']=$row['Purchase Order Quantity'] *$supplier_product->units_convertion_factor($row['Purchase Order Quantity Type'],$items[$line]['Purchase Order Quantity Type']);
                        $row['Purchase Order Quantity Type']=$items[$line]['Purchase Order Quantity Type'];
                    }


                }


                $supplier_product_keys[$row['Supplier Product Key']]=$row['Purchase Order Line'];
                $items[$row['Purchase Order Line']]=array(
                                                        'Supplier Product Key'=>$row['Supplier Product Key']
                                                                               ,'Purchase Order Quantity'=>$row['Purchase Order Quantity']
                                                                                                          ,'Purchase Order Quantity Type'=>$row['Purchase Order Quantity Type']
                                                                                                                                          ,'Purchase Order Line'=>$row['Purchase Order Line']
                                                                                                                                                                 ,'Purchase Order Key'=>$po_key
                                                    );

            }

        }

        foreach($items as $item) {
            $line=$this->get_next_line_number();
            $sql=sprintf("select `Supplier Delivery Note Line` from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=%d and `Supplier Product Key`=%d ",$this->id,$item['Supplier Product Key']);
            $res=mysql_query($sql);
            // print $sql;
            if ($row=mysql_fetch_array($res)) {
                if ($row['Supplier Delivery Note Line'])
                    $line=$row['Supplier Delivery Note Line'];
            }

            $sql=sprintf("update  `Purchase Order Transaction Fact` set `Supplier Delivery Note Line`=%d,`Supplier Delivery Note Key`=%d,`Supplier Delivery Note Quantity`=%f ,`Supplier Delivery Note Quantity Type`=%s where  `Purchase Order Key`=%d and `Purchase Order Line`=%d"
                         ,$line
                         ,$this->id
                         ,$item['Purchase Order Quantity']
                         ,prepare_mysql($item['Purchase Order Quantity Type'])
                         ,$item['Purchase Order Key']
                         ,$item['Purchase Order Line']
                        );
            mysql_query($sql);
            //  print $sql;
        }

    }

    function counting_take_values_from_dn() {


        $sql=sprintf("update  `Purchase Order Transaction Fact` set `Supplier Delivery Note Counted`='No',`Supplier Delivery Note Received Quantity`=`Supplier Delivery Note Quantity` where  `Supplier Delivery Note Key`=%d and `Supplier Delivery Note Counted`!='Yes' "
                     ,$this->id
                    );
        //PRINT $sql;
        mysql_query($sql);




    }

    function update_transaction_counted($data) {

        $product_key=$data['Supplier Product Key'];
        $value=$data['Supplier Delivery Note Counted'];
        $date=$data['Supplier Delivery Note Counted'];


        $sql=sprintf("select `Supplier Delivery Note Damaged Quantity`,`Supplier Delivery Note Received Quantity` from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=%d and `Supplier Product Key`=%d  "
                     ,$this->id
                     ,$product_key
                    );
        $res=mysql_query($sql);
        $total_quantity=0;
        $damaged_total_quantity=0;
//print $sql;
        while ($row=mysql_fetch_array($res)) {
            //  print $total_quantity.' '.$row['Supplier Delivery Note Received Quantity'];
            $total_quantity+=$row['Supplier Delivery Note Received Quantity'];
            $damaged_total_quantity+=$row['Supplier Delivery Note Damaged Quantity'];
        }

        if ($damaged_total_quantity>0)
            $value='Yes';

        $sql=sprintf("update  `Purchase Order Transaction Fact` set  `Supplier Delivery Note Counted`=%s ,`Supplier Delivery Note Last Updated Date`=%s where `Supplier Delivery Note Key`=%d and `Supplier Product Key`=%d  "
                     ,prepare_mysql($value)
                     ,prepare_mysql($date)

                     ,$this->id
                     ,$product_key
                    );
        mysql_query($sql);
        // print $sql;

        return array('qty'=>$total_quantity,'counted'=>$value,'damaged_qty'=>$damaged_total_quantity);

    }





    function update_store_products() {

        $sql=sprintf("select `Supplier Product Key`,`Supplier Delivery Note Quantity` from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=%d",$this->id);
        $res=mysql_query($sql);
        while ($row=mysql_fetch_array($res)) {
            $supplier_product=new SupplierProduct('key',$row['Supplier Product Key']);
            $products=$supplier_product->get_products();
            foreach($products as $product) {
                $product=new Product('pid',$product['Product ID']);
                $product->update_next_supplier_shippment();

            }

        }
    }


    function convert_to_parts() {

        include_once('class.PartLocation.php');

        $parts=array();

        $sql=sprintf("select `Supplier Delivery Note Received Location Key`,`Supplier Delivery Note Line`, `Supplier Product Key`,`Supplier Delivery Note Received Quantity`-`Supplier Delivery Note Damaged Quantity` as quantity from `Purchase Order Transaction Fact` where `Supplier Delivery Note Key`=%d ",$this->id);
        $res=mysql_query($sql);
        // print $sql;
        while ($row=mysql_fetch_array($res)) {
            $quantity=$row['quantity'];


            if ($quantity>0) {
                $supplier_product=new SupplierProduct($row['Supplier Product Key']);
                if ($supplier_product->data['Supplier Product Part Convertion']=='1:1') {
                    $parts_data=$supplier_product->get_parts();
                    $part_data=array_shift($parts_data);
                    $supplier_part_units_convertion=$supplier_product->units_convertion_factor($part_data['Supplier Product Unit']);
                    if (!$supplier_part_units_convertion)
                        continue;

                    $quantity=$quantity*$supplier_part_units_convertion;
                    $parts_quantity=$quantity/$part_data['Supplier Product Units Per Part'];

                    if (array_key_exists($part_data['Part SKU'].'_'.$row['Supplier Delivery Note Received Location Key'],$parts)) {


                        $parts[$part_data['Part SKU'].'_'.$row['Supplier Delivery Note Received Location Key']]['Quantity']+=$parts_quantity;


                    } else {

                        $parts[$part_data['Part SKU'].'_'.$row['Supplier Delivery Note Received Location Key']]=array(
                                    'Part SKU'=>$part_data['Part SKU'],
                                    'Quantity'=>$parts_quantity,
                                    'Location Key'=>$row['Supplier Delivery Note Received Location Key']
                                );
                    }


                    $sql=sprintf("update `Purchase Order Transaction Fact` set `Supplier Deliver Note Part Assigned`='Yes' where  Supplier Delivery Note Key`=%d  and `Supplier Delivery Note Line`=%d  ",$this->id,$row['Supplier Delivery Note Line']);
                    mysql_query($sql);

                    $notes=sprintf('SKUs to place: <button onClick="place(this)" id="%d"  >%s</button>',$part_data['Part SKU'],$parts_quantity);

                    $sql=sprintf('insert into `Supplier Delivery Note Item Part Bridge` values (%d,%d,%d,%f,"No",%s) '
                                 ,$this->id
                                 ,$row['Supplier Delivery Note Line']
                                 ,$part_data['Part SKU']
                                 ,$parts_quantity
                                 ,prepare_mysql($notes)
                                );
                    mysql_query($sql);



                }



            }



        }

        foreach($parts as $data) {
            //print_r($this->get_editor_data());
            $part_location_data=array('Part SKU'=>$data['Part SKU'],'Location Key'=>$data['Location Key'],'editor'=>$this->editor);
            // print_r($part_location_data);
            $part_location=new PartLocation('find',$part_location_data,'create');
            $part_location->add_stock(
                array(
                    'Quantity'=>$data['Quantity']
                               ,'Origin'=>_('Supplier Delivery Note').' <a href="supplier_dn.php?id='.$this->id.'">'.$this->data['Supplier Delivery Note Public ID'].'</a>'
                )
            );
        }





    }

}



?>