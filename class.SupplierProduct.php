<?php
/*
 File: SupplierProduct.php

 This file contains the SupplierProduct Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Kaktus

 Version 2.0
*/
class supplierproduct extends DB_Table {
    function supplierproduct($a1,$a2=false,$a3=false) {

        $this->table_name='Supplier Product';
        $this->ignore_fields=array(
                                 'Supplier Product Key'
                             );

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id',$a1);
        }
        else if (($a1=='new' or $a1=='create') and is_array($a2) ) {
            $this->msg=$this->create($a2);
        }
        elseif($a1=='find') {
            $this->find($a2,$a3);

        }
        else
            $this->get_data($a1,$a2);


    }
    function find($raw_data,$options) {

        if (isset($raw_data['editor'])) {
            foreach($raw_data['editor'] as $key=>$value) {
                if (array_key_exists($key,$this->editor))
                    $this->editor[$key]=$value;
            }
        }

        $this->found_in_code=false;
        $this->found_in_key=false;



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
            if(array_key_exists($key,$data))
                $data[$key]=_trim($value);
        }




        if ($data['Supplier Product Code']=='' or $data['Supplier Product Cost']=='' ) {
            $this->error=true;
            $this->msg='No code/cost';
            return;
        }

        if ($data['Supplier Key']=='')
            $data['Supplier Key']=1;
        if ($data['Supplier Product Name']=='')
            $data['Supplier Product Name']=$data['Supplier Product Code'];



        $sql=sprintf("select `Supplier Product Code` from `Supplier Product Dimension` where `Supplier Product Code`=%s  and  `Supplier Key`=%d "
                     ,prepare_mysql($data['Supplier Product Code'])
                     ,$data['Supplier Key']
                    );
        $result4=mysql_query($sql);
        if ($row4=mysql_fetch_array($result4)) {
            $this->found_in_code=true;
            $this->found_code=$row4['Supplier Product Code'];
            $this->get_data('code',$data['Supplier Product Code'],$data['Supplier Key']);
            $sql=sprintf("select `SPH Key` from `Supplier Product History Dimension` where `Supplier Product Code`=%s and `SPH Cost`=%.4f "
                         ,prepare_mysql($data['Supplier Product Code'])
                         ,$data['Supplier Product Cost']

                        );
           // print("$sql\n");
            $result2=mysql_query($sql);
            if ($row2=mysql_fetch_array($result2)) {
                $this->found_in_key=true;
                $this->found_key=$row2['SPH Key'];
                $this->get_data('key',$this->found_key);
                
            }
        }





        if ($create) {

            if ($this->found_in_key) {
                $this->get_data('key',$this->found_key);



            }
            elseif($this->found_in_code) {

                $this->get_data('code',$this->found_code,$data['Supplier Key']);
                $this->create_key($data);

            }
            else {
                //print "NEW CODE\n";
                $this->create($data);
            }

            if (isset($raw_data['date1']))
                $this->update_valid_dates($raw_data['date1']);
            if (isset($raw_data['date2']))
                $this->update_valid_dates($raw_data['date2']);
            else {
                $this->update_valid_dates(date('Y-m-d H:i:s'));
            }
        }

    }
    function get_data($tipo,$tag,$supplier_key=1) {
        if ($tipo=='id' or $tipo=='key') {
            $sql=sprintf("select * from `Supplier Product History Dimension` where `SPH Key`=%d ",$tag);
            $result=mysql_query($sql);
            if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $this->id=$this->data['SPH Key'];
                $this->key=$this->id;
                $sql=sprintf("select * from `Supplier Product Dimension` where `Supplier Product Code`=%s and   `Supplier Key`=%d "
                             ,prepare_mysql($this->data['Supplier Product Code'])
                             ,$this->data['Supplier Key']
                            );
                $result2=mysql_query($sql);
                if ($row=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {
                    $this->code=$row['Supplier Product Code'];
                    $this->supplier_key=$row['Supplier Key'];
                    $this->data['Product Supplier Code']=$row['Supplier Product Code'];
                    $this->data['Supplier Key']=$row['Supplier Key'];
                } else {
                    $this->code='';
                    $this->supplier_key='';
                    $this->data['Product Supplier Code']='';
                    $this->data['Supplier Key']='';

                }
            }
            return;

        } else if ($tipo=='code') {
            $sql=sprintf("select * from `Supplier Product Dimension` where `Supplier Product Code`=%s and   `Supplier Key`=%d "
                         ,prepare_mysql($tag)
                         ,$supplier_key
                        );
//print "$sql\n";
            $result=mysql_query($sql);
            if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $this->id=$this->data['Supplier Product Current Key'];
                $this->key=$this->id;
                $this->code=$this->data['Supplier Product Code'];
                $this->supplier_key=$this->data['Supplier Key'];
            }
            return;

        }

    }
    function create($data) {
        $this->new_key=false;
        $this->new_code=false;
        $this->create_key($data);
        $this->create_code($data);



        $this->msg='Supplier Product Created';
        $this->new=true;


    }
    function create_key($data) {
//print_r($data);
        $base_data=array(
                       'sph cost'=>'',
                       'sph valid from'=>date("Y-m-d H:i:s"),
                       'sph valid to'=>date("Y-m-d H:i:s"),
                   );
        foreach($data as $key=>$value) {
            $key=strtolower(preg_replace('/^supplier product /i','sph ',$key));
              if (array_key_exists($key,$base_data))
          
                $base_data[$key]=_trim($value);
        }
         
        $base_data['Supplier Product Code']=$data['Supplier Product Code'];
        $base_data['Supplier Key']=$data['Supplier Key'];

        $keys='(';
        $values='values(';
        foreach($base_data as $key=>$value) {
            $keys.="`$key`,";
            $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Supplier Product History Dimension` %s %s",$keys,$values);
       //print "$sql\n\n";
        if (mysql_query($sql)) {
             $this->key = mysql_insert_id();
            $this->new_key=true;
            $this->get_data('key',$this->key);
            //print $this->key."\n";
        
        } else {
            print "$sql  Error can not create Product Supplier\n";
            exit;
        }

    }
    function create_code($data) {

        $base_data=array(
                       'supplier key'=>1,
                       'supplier product code'=>'',
                       'supplier product name'=>'',
                       'supplier product description'=>'',
                       'supplier product cost'=>'',
                       'supplier product valid from'=>date("Y-m-d H:i:s"),
                       'supplier product valid to'=>date("Y-m-d H:i:s"),
                    
                   );
       
        foreach($data as $key=>$value) {
            if (isset($base_data[strtolower($key)]))
                $base_data[strtolower($key)]=_trim($value);
        }
         $supplier=new Supplier($base_data['supplier key']);
        $base_data['supplier code']=$supplier->data['Supplier Code'];
        $base_data['supplier name']=$supplier->data['Supplier Name'];
        $base_data['supplier product current key']=$this->key;
        
        $keys='(';
        $values='values(';
        foreach($base_data as $key=>$value) {
            $keys.="`$key`,";
            $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Supplier Product Dimension` %s %s",$keys,$values);
       //  print "$sql\n\n";
        if (mysql_query($sql)) {
        //print mysql_affected_rows()."\n";
            $this->code = $base_data['supplier product code'];
            $this->supplier_key = $base_data['supplier key'];

            $this->new_code=true;

            $this->get_data('code',$this->code,$this->supplier_key);
        } else {
            print "$sql  Error can not create Product Supplier\n";
            exit;
        }

    }
    function load($data_to_be_read,$args='') {
        switch ($data_to_be_read) {
        case('used in'):

            $used_in_products='';
            $sql=sprintf("select PD.`Product ID`,`Product Code` from `Supplier Product Part List` SPPL left join `Product Part List` PPL on (SPPL.`Part SKU`=PPL.`Part SKU`) left join `Product Dimension` PD on (PPL.`Product ID`=PD.`Product ID`) where `Supplier Product Code`=%s and `Supplier Key`=%d and `Supplier Product Part Most Recent`='Yes' group by `Product Code`;"
            ,prepare_mysql($this->code)
            ,$this->supplier_key
            );


            $result=mysql_query($sql);
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $used_in_products.=sprintf(', <a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code']);
            }
            $used_in_products=preg_replace('/^, /','',$used_in_products);

            $used_in_parts='';
            $sql=sprintf("select PD.`Part SKU` from `Supplier Product Part List` SPPL  left join `Part Dimension` PD on (SPPL.`Part SKU`=PD.`Part SKU`) where `Supplier Product Code`=%s and `Supplier Key`=%d  and `Supplier Product Part Most Recent`='Yes' group by PD.`Part SKU`;"
  ,prepare_mysql($this->code)   
            ,$this->supplier_key
            );
            $result=mysql_query($sql);
            $num_parts=0;
            // print "$sql\n";
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $used_in_parts.=sprintf(', <a href="part.php?id=%d">%s</a>',$row['Part SKU'],$row['Part SKU']);
                $num_parts++;
            }
            $used_in_parts=preg_replace('/^, /','',$used_in_parts);

            if ($num_parts==0)
                $used_in_parts='';
            else if ($num_parts==1)
                $used_in_parts='(SKU:'.$used_in_parts.')';
            else
                $used_in_parts='(SKUs:'.$used_in_parts.')';

            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product XHTML Used In`=%s where `Supplier Product Code`=%s and `Supplier Key`=%d"
                ,prepare_mysql(_trim($used_in_products.' '.$used_in_parts))
                ,prepare_mysql($this->code)
                ,$this->supplier_key
                );
            //print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql error can not update used in insuppiler product \n");

            //exit;

            break;
        case('parts'):

            $sql=sprintf("select PD.`Part SKU` as sku from `Supplier Product Dimension` SPD left join `Supplier Product Part List` SPPL on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`)  left join `Part Dimension` PD on (SPPL.`Part SKU`=PD.`Part SKU`) where `Supplier Product Key`=%d group by PD.`Part SKU`;"
            ,$this->data['Supplier Product Key']
            );
            $result=mysql_query($sql);
            $num_parts=0;
            $this->parts_sku=array();
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $this->parts_sku[]=$row['sku'];
            }

            break;
        case('sales'):
            $this->load('parts');
            // total
            $sold=0;
            $required=0;
            $provided=0;
            $given=0;
            $amount_in=0;
            $value=0;
            $value_free=0;
            $margin=0;
            $storing=0;
            $cost=0;


            $sql=sprintf("select   sum(`Inventory Transaction Storing Charge Amount`) as storing,   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where   `Inventory Transaction Type`='Sale' and `Supplier Product Key`=%d  ",$this->id);
            //print_r($this->parts_sku);
            //   print "$sql\n";
            //exit;
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $required=$row['required'];
                $provided=-$row['qty'];
                $given=$row['given'];
                $amount_in=$row['amount_in'];
                $value=$row['value'];
                $value_free=$row['value_free'];
                $sold=-$row['qty']-$row['given'];
                $storing=$row['storing'];
            }
            $abs_profit=$amount_in+$value;
            $profit_sold=$amount_in+$value-$value_free;
            if ($amount_in==0)
                $margin=0;
            else
                $margin=($value-$value_free)/$amount_in;
            $profit_sold_after_storing=$profit_sold-$storing;


            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Total Cost`=%.2f,`Supplier Product Total Parts Required`=%f ,`Supplier Product Total Parts Provided`=%f,`Supplier Product Total Parts Used`=%f ,`Supplier Product Total Sold Amount`=%f ,`Supplier Product Total Parts Profit`=%f ,`Supplier Product Total Parts Profit After Storing`=%f  where `Supplier Product Key`=%d "
                         ,$cost
                         ,$required
                         ,$provided
                         ,$given+$provided
                         ,$amount_in
                         ,$profit_sold
                         ,$profit_sold_after_storing
                         ,$this->id);
            // print "$sql\n";
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
            $storing=0;
            $cost=0;

            // toal same code
            $sql=sprintf("select   sum(`Inventory Transaction Storing Charge Amount`) as storing,   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` ITF left join `Supplier Product Dimension` PSD on (ITF.`Supplier Product Key`=PSD.`Supplier Product Key`) where   `Inventory Transaction Type`='Sale' and `Supplier Product ID`=%d    ",$this->data['Supplier Product ID'] );

            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $required=$row['required'];
                $provided=-$row['qty'];
                $given=$row['given'];
                $amount_in=$row['amount_in'];
                $value=$row['value'];
                $value_free=$row['value_free'];
                $sold=-$row['qty']-$row['given'];
                $storing=$row['storing'];
            }
            $abs_profit=$amount_in+$value;
            $profit_sold=$amount_in+$value-$value_free;
            if ($amount_in==0)
                $margin=0;
            else
                $margin=($value-$value_free)/$amount_in;
            $profit_sold_after_storing=$profit_sold-$storing;


            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Same ID Total Cost`=%.2f,`Supplier Product Same ID Total Parts Required`=%f ,`Supplier Product Same ID Total Parts Provided`=%f,`Supplier Product Same ID Total Parts Used`=%f ,`Supplier Product Same ID Total Sold Amount`=%f ,`Supplier Product Same ID Total Parts Profit`=%f ,`Supplier Product Same ID Total Parts Profit After Storing`=%f  where `Supplier Product ID`=%d "
                         ,$cost
                         ,$required
                         ,$provided
                         ,$given+$provided
                         ,$amount_in
                         ,$profit_sold
                         ,$profit_sold_after_storing
                         ,$this->data['Supplier Product ID']);
            // print "$sql\n";
            if (!mysql_query($sql))
                exit("error con not uopdate product part when loading sales");



            // 1 year



            $sold=0;
            $required=0;
            $provided=0;
            $given=0;
            $amount_in=0;
            $value=0;
            $value_free=0;
            $margin=0;
            $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where  `Inventory Transaction Type`='Sale' and `Supplier Product Key`=%d and `Date`>=%s    ",$this->id,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 year")))  );
            // print "$sql\n";
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $required=$row['required'];
                $provided=-$row['qty'];
                $given=$row['given'];
                $amount_in=$row['amount_in'];
                $value=$row['value'];
                $value_free=$row['value_free'];
                $sold=-$row['qty']-$row['given'];
            }
            $abs_profit=$amount_in+$value;
            $profit_sold=$amount_in+$value-$value_free;
            if ($amount_in==0)
                $margin=0;
            else
                $margin=($value-$value_free)/$amount_in;
            $profit_sold_after_storing=$profit_sold;

            $cost=0;

            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product 1 Year Acc Cost`=%.2f,`Supplier Product 1 Year Acc Parts Required`=%f ,`Supplier Product 1 Year Acc Parts Provided`=%f,`Supplier Product 1 Year Acc Parts Used`=%f ,`Supplier Product 1 Year Acc Sold Amount`=%f ,`Supplier Product 1 Year Acc Parts Profit`=%f  where `Supplier Product Key`=%d "
                         ,$cost=0
                                ,$required
                         ,$provided
                         ,$given+$provided
                         ,$amount_in
                         ,$profit_sold
                         ,$this->id);
            //    print "$sql\n";
            if (!mysql_query($sql))
                exit("error con not uopdate product part when loading sales");


            // 1 year same id

            $sold=0;
            $required=0;
            $provided=0;
            $given=0;
            $amount_in=0;
            $value=0;
            $value_free=0;
            $margin=0;
            $storing=0;
            $cost=0;


            $sql=sprintf("select   sum(`Inventory Transaction Storing Charge Amount`) as storing,   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` ITF left join `Supplier Product Dimension` PSD on (ITF.`Supplier Product Key`=PSD.`Supplier Product Key`) where   `Inventory Transaction Type`='Sale' and `Supplier Product ID`=%d and `Date`>%s ",$this->data['Supplier Product ID'],prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 year"))) );

            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $required=$row['required'];
                $provided=-$row['qty'];
                $given=$row['given'];
                $amount_in=$row['amount_in'];
                $value=$row['value'];
                $value_free=$row['value_free'];
                $sold=-$row['qty']-$row['given'];
                $storing=$row['storing'];
            }
            $abs_profit=$amount_in+$value;
            $profit_sold=$amount_in+$value-$value_free;
            if ($amount_in==0)
                $margin=0;
            else
                $margin=($value-$value_free)/$amount_in;
            $profit_sold_after_storing=$profit_sold-$storing;


            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Same ID 1 Year Acc Cost`=%.2f,`Supplier Product Same ID 1 Year Acc Parts Required`=%f ,`Supplier Product Same ID 1 Year Acc Parts Provided`=%f,`Supplier Product Same ID 1 Year Acc Parts Used`=%f ,`Supplier Product Same ID 1 Year Acc Sold Amount`=%f ,`Supplier Product Same ID 1 Year Acc Parts Profit`=%f ,`Supplier Product Same ID 1 Year Acc Parts Profit After Storing`=%f  where `Supplier Product ID`=%d "
                         ,$cost
                         ,$required
                         ,$provided
                         ,$given+$provided
                         ,$amount_in
                         ,$profit_sold
                         ,$profit_sold_after_storing
                         ,$this->data['Supplier Product ID']);
            // print "$sql\n";
            if (!mysql_query($sql))
                exit("error con not uopdate product part when loading sales 1 y same");





            //1 quarter


            $sold=0;
            $required=0;
            $provided=0;
            $given=0;
            $amount_in=0;
            $value=0;
            $value_free=0;
            $margin=0;
            $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where  `Inventory Transaction Type`='Sale' and `Supplier Product Key`=%d   and `Date`<=%s and `Date`>=%s     ",$this->id,prepare_mysql($this->data['Supplier Product Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -3 month")))  );
            //      print "$sql\n";
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $required=$row['required'];
                $provided=-$row['qty'];
                $given=$row['given'];
                $amount_in=$row['amount_in'];
                $value=$row['value'];
                $value_free=$row['value_free'];
                $sold=-$row['qty']-$row['given'];
            }
            $abs_profit=$amount_in+$value;
            $profit_sold=$amount_in+$value-$value_free;
            if ($amount_in==0)
                $margin=0;
            else
                $margin=($value-$value_free)/$amount_in;

            $cost=0;
            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product 1 Quarter Acc Cost`=%.2f,`Supplier Product 1 Quarter Acc Parts Required`=%f ,`Supplier Product 1 Quarter Acc Parts Provided`=%f,`Supplier Product 1 Quarter Acc Parts Used`=%f ,`Supplier Product 1 Quarter Acc Sold Amount`=%f ,`Supplier Product 1 Quarter Acc Parts Profit`=%f  where `Supplier Product Key`=%d "
                         ,$cost
                         ,$required
                         ,$provided
                         ,$given+$provided
                         ,$amount_in
                         ,$profit_sold
                         ,$this->id);
            //                  print "$sql\n";
            if (!mysql_query($sql))
                exit("error con not uopdate product part when loading sales 1 q");



            // 1 quarter same id

            $sold=0;
            $required=0;
            $provided=0;
            $given=0;
            $amount_in=0;
            $value=0;
            $value_free=0;
            $margin=0;
            $storing=0;
            $cost=0;


            $sql=sprintf("select   sum(`Inventory Transaction Storing Charge Amount`) as storing,   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` ITF left join `Supplier Product Dimension` PSD on (ITF.`Supplier Product Key`=PSD.`Supplier Product Key`) where   `Inventory Transaction Type`='Sale' and `Supplier Product ID`=%d and `Date`>%s ",$this->data['Supplier Product ID'],prepare_mysql(date("Y-m-d H:i:s",strtotime("now -3 month"))) );

            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $required=$row['required'];
                $provided=-$row['qty'];
                $given=$row['given'];
                $amount_in=$row['amount_in'];
                $value=$row['value'];
                $value_free=$row['value_free'];
                $sold=-$row['qty']-$row['given'];
                $storing=$row['storing'];
            }
            $abs_profit=$amount_in+$value;
            $profit_sold=$amount_in+$value-$value_free;
            if ($amount_in==0)
                $margin=0;
            else
                $margin=($value-$value_free)/$amount_in;
            $profit_sold_after_storing=$profit_sold-$storing;


            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Same ID 1 Quarter Acc Cost`=%.2f,`Supplier Product Same ID 1 Quarter Acc Parts Required`=%f ,`Supplier Product Same ID 1 Quarter Acc Parts Provided`=%f,`Supplier Product Same ID 1 Quarter Acc Parts Used`=%f ,`Supplier Product Same ID 1 Quarter Acc Sold Amount`=%f ,`Supplier Product Same ID 1 Quarter Acc Parts Profit`=%f ,`Supplier Product Same ID 1 Quarter Acc Parts Profit Aftr Storing`=%f  where `Supplier Product ID`=%d "
                         ,$cost
                         ,$required
                         ,$provided
                         ,$given+$provided
                         ,$amount_in
                         ,$profit_sold
                         ,$profit_sold_after_storing
                         ,$this->data['Supplier Product ID']);
            // print "$sql\n";
            if (!mysql_query($sql))
                exit("error con not uopdate product part when loading sales 1 q s");


            //1 month




            $sold=0;
            $required=0;
            $provided=0;
            $given=0;
            $amount_in=0;
            $value=0;
            $value_free=0;
            $margin=0;
            $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Inventory Transaction Type`='Sale'  and `Supplier Product Key`=%d and `Date`<=%s and `Date`>=%s   ",$this->id,prepare_mysql($this->data['Supplier Product Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 month")))  );
            //      print "$sql\n";
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $required=$row['required'];
                $provided=-$row['qty'];
                $given=$row['given'];
                $amount_in=$row['amount_in'];
                $value=$row['value'];
                $value_free=$row['value_free'];
                $sold=-$row['qty']-$row['given'];
            }
            $abs_profit=$amount_in+$value;
            $profit_sold=$amount_in+$value-$value_free;
            if ($amount_in==0)
                $margin=0;
            else
                $margin=($value-$value_free)/$amount_in;

            $cost=0;
            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product 1 Month Acc Cost`=%.2f, `Supplier Product 1 Month Acc Parts Required`=%f ,`Supplier Product 1 Month Acc Parts Provided`=%f,`Supplier Product 1 Month Acc Parts Used`=%f ,`Supplier Product 1 Month Acc Sold Amount`=%f ,`Supplier Product 1 Month Acc Parts Profit`=%f  where `Supplier Product Key`=%d "
                         ,$cost
                         ,$required
                         ,$provided
                         ,$given+$provided
                         ,$amount_in
                         ,$profit_sold
                         ,$this->id);
            //                  print "$sql\n";
            if (!mysql_query($sql))
                exit("error con not uopdate product part when loading sales");


            //1 month same id




            $sold=0;
            $required=0;
            $provided=0;
            $given=0;
            $amount_in=0;
            $value=0;
            $value_free=0;
            $margin=0;

            $sql=sprintf("select   sum(`Inventory Transaction Storing Charge Amount`) as storing,   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` ITF left join `Supplier Product Dimension` PSD on (ITF.`Supplier Product Key`=PSD.`Supplier Product Key`) where   `Inventory Transaction Type`='Sale' and `Supplier Product ID`=%d and `Date`>%s ",$this->data['Supplier Product ID'],prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 month"))) );

            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $required=$row['required'];
                $provided=-$row['qty'];
                $given=$row['given'];
                $amount_in=$row['amount_in'];
                $value=$row['value'];
                $value_free=$row['value_free'];
                $sold=-$row['qty']-$row['given'];
                $storing=$row['storing'];
            }
            $abs_profit=$amount_in+$value;
            $profit_sold=$amount_in+$value-$value_free;
            if ($amount_in==0)
                $margin=0;
            else
                $margin=($value-$value_free)/$amount_in;
            $profit_sold_after_storing=$profit_sold-$storing;


            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Same ID 1 Month Acc Cost`=%.2f,`Supplier Product Same ID 1 Month Acc Parts Required`=%f ,`Supplier Product Same ID 1 Month Acc Parts Provided`=%f,`Supplier Product Same ID 1 Month Acc Parts Used`=%f ,`Supplier Product Same ID 1 Month Acc Sold Amount`=%f ,`Supplier Product Same ID 1 Month Acc Parts Profit`=%f ,`Supplier Product Same ID 1 Month Acc Parts Profit After Storing`=%f  where `Supplier Product ID`=%d "
                         ,$cost
                         ,$required
                         ,$provided
                         ,$given+$provided
                         ,$amount_in
                         ,$profit_sold
                         ,$profit_sold_after_storing
                         ,$this->data['Supplier Product ID']);
            // print "$sql\n";
            if (!mysql_query($sql))
                exit("error con not uopdate product part when loading sales");


            // 1 week



            $sold=0;
            $required=0;
            $provided=0;
            $given=0;
            $amount_in=0;
            $value=0;
            $value_free=0;
            $margin=0;
            $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Inventory Transaction Type`='Sale'  and `Supplier Product Key`=%d  and `Date`<=%s and `Date`>=%s    ",$this->id,prepare_mysql($this->data['Supplier Product Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 week")))  );
            //      print "$sql\n";
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $required=$row['required'];
                $provided=-$row['qty'];
                $given=$row['given'];
                $amount_in=$row['amount_in'];
                $value=$row['value'];
                $value_free=$row['value_free'];
                $sold=-$row['qty']-$row['given'];
            }
            $abs_profit=$amount_in+$value;
            $profit_sold=$amount_in+$value-$value_free;
            if ($amount_in==0)
                $margin=0;
            else
                $margin=($value-$value_free)/$amount_in;

            $cost=0;
            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product 1 Week Acc Cost`=%.2f,`Supplier Product 1 Week Acc Parts Required`=%f ,`Supplier Product 1 Week Acc Parts Provided`=%f,`Supplier Product 1 Week Acc Parts Used`=%f ,`Supplier Product 1 Week Acc Sold Amount`=%f ,`Supplier Product 1 Week Acc Parts Profit`=%f  where `Supplier Product Key`=%d "
                         ,$cost
                         ,$required
                         ,$provided
                         ,$given+$provided
                         ,$amount_in
                         ,$profit_sold
                         ,$this->id);
            //                  print "$sql\n";
            if (!mysql_query($sql))
                exit("error con not uopdate product part when loading sales");



//1 month same id




            $sold=0;
            $required=0;
            $provided=0;
            $given=0;
            $amount_in=0;
            $value=0;
            $value_free=0;
            $margin=0;

            $sql=sprintf("select   sum(`Inventory Transaction Storing Charge Amount`) as storing,   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` ITF left join `Supplier Product Dimension` PSD on (ITF.`Supplier Product Key`=PSD.`Supplier Product Key`) where   `Inventory Transaction Type`='Sale' and `Supplier Product ID`=%d and `Date`>%s ",$this->data['Supplier Product ID'],prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 week"))) );

            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $required=$row['required'];
                $provided=-$row['qty'];
                $given=$row['given'];
                $amount_in=$row['amount_in'];
                $value=$row['value'];
                $value_free=$row['value_free'];
                $sold=-$row['qty']-$row['given'];
                $storing=$row['storing'];
            }
            $abs_profit=$amount_in+$value;
            $profit_sold=$amount_in+$value-$value_free;
            if ($amount_in==0)
                $margin=0;
            else
                $margin=($value-$value_free)/$amount_in;
            $profit_sold_after_storing=$profit_sold-$storing;


            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Same ID 1 Week Acc Cost`=%.2f,`Supplier Product Same ID 1 Week Acc Parts Required`=%f ,`Supplier Product Same ID 1 Week Acc Parts Provided`=%f,`Supplier Product Same ID 1 Week Acc Parts Used`=%f ,`Supplier Product Same ID 1 Week Acc Sold Amount`=%f ,`Supplier Product Same ID 1 Week Acc Parts Profit`=%f ,`Supplier Product Same ID 1 Week Acc Parts Profit After Storing`=%f  where `Supplier Product ID`=%d "
                         ,$cost
                         ,$required
                         ,$provided
                         ,$given+$provided
                         ,$amount_in
                         ,$profit_sold
                         ,$profit_sold_after_storing
                         ,$this->data['Supplier Product ID']);
            // print "$sql\n";
            if (!mysql_query($sql))
                exit("error con not uopdate product part when loading sales");






            break;

        }
    }
    function get($key='') {

        if (array_key_exists($key,$this->data))
            return $this->data[$key];

        $_key=preg_replace('/^Supplier Product /','',$key);
        if (isset($this->data[$_key]))
            return $this->data[$key];


        switch ($key) {

        }

        return false;
    }
    function valid_id($id) {
        if (is_numeric($id) and $id>0 and $id<9223372036854775807)
            return true;
        else
            return false;
    }
    function used_id($id) {
        $sql="select count(*) as num from `Supplier Product Dimension` where `Supplier Product ID`=".prepare_mysql($id);
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($row['num']>0)
                return true;
        }
        return false;
    }
    function new_id() {
        $sql="select max(`Supplier Product ID`) as id from `Supplier Product Dimension`";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            return $row['id']+1;
        } else
            return 1;

    }
    function new_part_list($product_list_id,$part_list) {

       // if (!$this->valid_id($product_list_id))
        //    $product_list_id=$this->new_part_list_id();
//print_r($this);
        $_base_data=array(
                        'supplier product code'=>$this->data['Supplier Product Code'],
                        'supplier key'=>$this->data['Supplier Key'],
                        'part sku'=>'',
                        'factor supplier product'=>'',
                        'supplier product units per part'=>'',
                        'supplier product part valid from'=>date('Y-m-d H:i:s'),
                        'supplier product part valid to'=>date('Y-m-d H:i:s'),
                        'supplier product part most recent'=>'Yes',
                        'supplier product part most recent key'=>'',
                    );
        foreach($part_list as $data) {
            $base_data=$_base_data;
            foreach($data as $key=>$value) {
            $key=strtolower($key);
                               if (array_key_exists($key,$base_data))

                    $base_data[$key]=_trim($value);
            }

            //$base_data['supplier product part id']=$product_list_id;

            $keys='(';
            $values='values(';
            foreach($base_data as $key=>$value) {
                $keys.="`$key`,";
                $values.=prepare_mysql($value).",";
            }
            $keys=preg_replace('/,$/',')',$keys);
            $values=preg_replace('/,$/',')',$values);
            $sql=sprintf("insert into `Supplier Product Part List` %s %s",$keys,$values);
            //	print "---------------------\n$sql\n";
            if (mysql_query($sql)) {
                $id = mysql_insert_id();

                if ($base_data['supplier product part most recent']=='Yes') {
                    $sql=sprintf("update `Supplier Product Part List`  set `Supplier Product Part Most Recent`='No',`Supplier Product Part Most Recent Key`=%d where `Supplier Key`=%d and `Supplier Product Code`=%s
                                 and `Supplier Product Part Key`!=%d  "
                                 ,$id
                                 ,$base_data['supplier key']
                                 ,prepare_mysql($base_data['supplier product code'])
                                 ,$id);

                    mysql_query($sql);
                    $sql=sprintf('update `Supplier Product Part List` set `Supplier Product Part Most Recent Key`=%d where `Supplier Product Part Key`=%d',$id,$id);

                    mysql_query($sql);
                }
            } else {
                print "$sql Error can not create new Supplier Product Part\n";
                exit;
            }

        }

    }
 
    function update_valid_dates($date) {
        $this->update_valid_dates_key($date);
        $this->update_valid_dates_code($date);

    }
    function update_valid_dates_key($date) {
        $affected=0;
        $sql=sprintf("update `Supplier Product Historic Dimension`  set `SPH Valid From` where  `SPH Key`=%d and `SPH Valid From`>%s   "
                     ,prepare_mysql($this->id)
                     ,prepare_mysql($date)

                    );
        mysql_query($sql);
        $affected+=mysql_affected_rows();
        $sql=sprintf("update `Supplier Product Historic Dimension`  set `SPH Valid To` where  `SPH Key`=%d and `SPH Valid To`<%s   "
                     ,prepare_mysql($this->id)
                     ,prepare_mysql($date)

                    );
        mysql_query($sql);
        $affected+=mysql_affected_rows();
        return $affected;
    }
    function update_valid_dates_code($date) {
        $affected=0;
        $sql=sprintf("update `Supplier Product Dimension`  set `Supplier Product Valid From` where  `Supplier Product Code`=%s and `Supplier Key`=%d and `Supplier Product Valid From`>%s   "
                     ,prepare_mysql($this->code)
                     ,$this->supplier_key
                     ,prepare_mysql($date)

                    );
        mysql_query($sql);
        $affected+=mysql_affected_rows();
        $sql=sprintf("update `Supplier Product Dimension`  set `Supplier Product Valid To` where  `Supplier Product Code`=%s and `Supplier Key`=%d  and `Supplier Product Valid To`<%s   "
                     ,prepare_mysql($this->code)
                     ,$this->supplier_key
                     ,prepare_mysql($date)

                    );
        mysql_query($sql);
        $affected+=mysql_affected_rows();
        return $affected;
    }
}