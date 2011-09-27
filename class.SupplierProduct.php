<?php
/*
 File: SupplierProduct.php

 This file contains the SupplierProduct Class
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
class supplierproduct extends DB_Table {

    public $external_DB_link=false;

    function supplierproduct($a1,$a2=false,$a3=false) {

        $this->table_name='Supplier Product';
        $this->ignore_fields=array(
                                 'Supplier Product Key'
                             );

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id',$a1);
        } else if (($a1=='new' or $a1=='create') and is_array($a2) ) {
            $this->msg=$this->create($a2);
        }
        elseif($a1=='find') {
            $this->find($a2,$a3);

        }
        else
            $this->get_data($a1,$a2,$a3);


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
            if (array_key_exists($key,$data))
                $data[$key]=_trim($value);
        }

        if (!isset($raw_data['SPH Units Per Case']) or $raw_data['SPH Units Per Case']=='')
            $raw_data['SPH Units Per Case']=1;


        $data['SPH Units Per Case']=$raw_data['SPH Units Per Case'];
        $data['SPH Case Cost']=$raw_data['SPH Case Cost'];

        if ($data['Supplier Product Code']=='' or $raw_data['SPH Case Cost']=='' ) {
            $this->error=true;
            $this->msg='No code/cost';
            return;
        }

        if ($data['Supplier Key']=='')
            $data['Supplier Key']=1;
        if ($data['Supplier Product Name']=='')
            $data['Supplier Product Name']=$data['Supplier Product Code'];





        $sql=sprintf("select `Supplier Product Code`,`Supplier Product Key` from `Supplier Product Dimension` where `Supplier Product Code`=%s  and  `Supplier Key`=%d "
                     ,prepare_mysql($data['Supplier Product Code'])
                     ,$data['Supplier Key']
                    );
        $result4=mysql_query($sql);
        if ($row4=mysql_fetch_array($result4)) {
            $this->found_in_code=true;
            $this->found_pid=$row4['Supplier Product Key'];
            $this->found_code=$row4['Supplier Product Code'];
            $this->get_data('code',$data['Supplier Product Code'],$data['Supplier Key']);
            $sql=sprintf("select `SPH Key` from `Supplier Product History Dimension` where `Supplier Product Key`=%d  and `SPH Case Cost`=%.2f and `SPH Units Per Case`=%d"

                         ,$row4['Supplier Product Key']
                         ,$data['SPH Case Cost']
                         ,$data['SPH Units Per Case']
                        );
            //print "$sql\n";
            $result2=mysql_query($sql);
            if ($row2=mysql_fetch_array($result2)) {
                $this->found_in_key=true;
                $this->found=true;
                $this->found_key=$row2['SPH Key'];
                $this->get_data('key',$this->found_key);

            }
        }



        //	print "FK: ".$this->found_in_key." FC:".$this->found_in_code."\n";

        if ($create) {

            if ($this->found_in_key) {
                $this->get_data('key',$this->found_key);



            }
            elseif($this->found_in_code) {

                $this->get_data('pid',$this->pid);
                $data['Supplier Product Key']=$this->pid;
                $this->create_key($data);

            }
            else {
                // print_r($data);
                $this->create($data);
            }

            if (isset($raw_data['Supplier Product Valid From']))
                $this->update_valid_dates($raw_data['Supplier Product Valid From']);
            if (isset($raw_data['Supplier Product Valid To']))
                $this->update_valid_dates($raw_data['Supplier Product Valid To']);
            else {
                $this->update_valid_dates(date('Y-m-d H:i:s'));
            }
        }

    }
    function get_data($tipo,$tag,$supplier_key=1) {
        if ($tipo=='id' or $tipo=='key') {
            $sql=sprintf("select * from `Supplier Product History Dimension` where `SPH Key`=%d ",$tag);
            //print "$sql\n";
            $result=mysql_query($sql);
            if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $this->id=$this->data['SPH Key'];
                $this->key=$this->id;
                $sql=sprintf("select * from `Supplier Product Dimension` where `Supplier Product Key`=%d "

                             ,$this->data['Supplier Product Key']
                            );

                $result2=mysql_query($sql);
                if ($row=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {
                    $this->code=$row['Supplier Product Code'];
                    $this->supplier_key=$row['Supplier Key'];
                    $this->pid=$row['Supplier Product Key'];
                    foreach($row as $key=>$value) {
                        $this->data[$key]=$value;
                    }


                } else {
                    $this->pid=0;
                    $this->code='';
                    $this->supplier_key='';
                    $this->data['Supplier Product Code']='';
                    $this->data['Supplier Key']='';

                }
            }
            return;

        } else if ($tipo=='code') {
            $sql=sprintf("select * from `Supplier Product Dimension` where `Supplier Product Code`=%s and   `Supplier Key`=%d "
                         ,prepare_mysql($tag)
                         ,$supplier_key
                        );

            $result=mysql_query($sql);
            if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $this->id=$this->data['Supplier Product Current Key'];

                $this->pid=$this->data['Supplier Product Key'];

                $this->key=$this->id;
                $this->code=$this->data['Supplier Product Code'];
                $this->supplier_key=$this->data['Supplier Key'];
            }
            return;

        }
        elseif($tipo=='pid') {
            $sql=sprintf("select * from `Supplier Product Dimension` where `Supplier Product Key`=%d",
                         $tag

                        );

            $result=mysql_query($sql);
            if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $this->id=$this->data['Supplier Product Current Key'];

                $this->pid=$this->data['Supplier Product Key'];

                $this->key=$this->id;
                $this->code=$this->data['Supplier Product Code'];
                $this->supplier_key=$this->data['Supplier Key'];

                $sql=sprintf("select * from `Supplier Product History Dimension` where `SPH Key`=%d ",$this->id);

                $result2=mysql_query($sql);
                if ($row=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {

                    foreach($row as $key=>$value) {
                        $this->data[$key]=$value;
                    }

                }
            }

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
                           'SPH Case Cost'=>'',
                           'SPH Units Per Case'=>'1',
                           'SPH Type'=>'Normal',
                           'SPH Valid From'=>date("Y-m-d H:i:s"),
                           'SPH Valid To'=>date("Y-m-d H:i:s"),
                       );





            foreach($data as $key=>$value) {

                if ($key=='Supplier Product Valid From') {
                    $key='SPH Valid From';
                }
                elseif($key=='Supplier Product Valid To') {
                    $key='SPH Valid To';
                }


                $key=preg_replace('/^supplier product /i','sph ',$key);
                if (array_key_exists($key,$base_data))

                    $base_data[$key]=_trim($value);
            }


            if (array_key_exists('Supplier Product Key',$data))
                $base_data['Supplier Product Key']=$data['Supplier Product Key'];


            $keys='(';
            $values='values(';
            foreach($base_data as $key=>$value) {

                if ($key=='SPH Case Cost') {
                    $keys.="`$key`,";
                    $values.=sprintf("%.2f",$value).",";

                } else {

                    $keys.="`$key`,";
                    $values.=prepare_mysql($value).",";

                }
            }
            $keys=preg_replace('/,$/',')',$keys);
            $values=preg_replace('/,$/',')',$values);
            $sql=sprintf("insert into `Supplier Product History Dimension` %s %s",$keys,$values);
            // print "$sql\n\n";
            if (mysql_query($sql)) {
                $this->key = mysql_insert_id();
                $this->new_key=true;
                $this->new_key_id=$this->key;
                $this->get_data('key',$this->key);
                //print $this->key."\n";

            } else {
                print "$sql  Error can not create Supplier Product\n";
                exit;
            }

        }
        function create_code($data) {
            $base_data=array(
                           'Supplier Key'=>1,
                           'Supplier Product Code'=>'',
                           'Supplier Product Name'=>'',
                           'Supplier Product Description'=>'',

                           'Supplier Product Valid From'=>date("Y-m-d H:i:s"),
                           'Supplier Product Valid To'=>date("Y-m-d H:i:s"),

                       );

            foreach($data as $key=>$value) {
                if (isset($base_data[$key]))
                    $base_data[$key]=_trim($value);
            }
            $supplier=new Supplier($base_data['Supplier Key']);
            $base_data['Supplier Code']=$supplier->data['Supplier Code'];
            $base_data['Supplier Name']=$supplier->data['Supplier Name'];
            $base_data['Supplier Product Current Key']=$this->key;


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
                $this->code = $base_data['Supplier Product Code'];
                $this->supplier_key = $base_data['Supplier Key'];
                $this->pid=mysql_insert_id();
                $this->new_key_id=$this->pid;
                $this->new_code=true;

                $sql=sprintf("update `Supplier Product History Dimension` set `Supplier Product Key`=%d where `SPH Key`=%d",
                             $this->pid,
                             $this->id
                            );
                mysql_query($sql);
                $this->get_data('pid',$this->pid);
            } else {
                print "$sql  Error can not create Supplier Product\n";
                exit;
            }

        }
        function get_products() {
            $products=array();
            $sql=sprintf("select PD.`Product ID`,`Product Code`,`Supplier Product Units Per Part`,`Parts Per Product`
                         from `Supplier Product Part List` SPPL
                         left join `Supplier Product Part Dimension` SPPD on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`)
                         left join `Product Part List` PPL on (SPPL.`Part SKU`=PPL.`Part SKU`)
                         left join `Product Part Dimension` PPD on (PPD.`Product Part Key`=PPL.`Product Part Key`)
                         left join `Product Dimension` PD on (PPD.`Product ID`=PD.`Product ID`)
                         where SPPD.`Supplier Product Key`=%d and `Supplier Product Part Most Recent`='Yes' group by `Product Code`;"

                         ,$this->pid
                        );


            $result=mysql_query($sql);
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $units_ratio=  1.0/$row['Supplier Product Units Per Part']/$row['Parts Per Product'];
                $products[$row['Product ID']]=array('Product ID'=>$row['Product ID'],'Product Code'=>$row['Product Code'],'Units Ratio'=>$units_ratio);
            }

            return $products;
        }
        function load($data_to_be_read,$args='') {
            switch ($data_to_be_read) {

            case('parts'):

                $parts=$this->get_parts();

                $this->parts_sku=array();
                foreach($parts as $key=>$value) {
                    $this->parts_sku[]=$key;
                }


                break;
            case('sales'):
                $this->upload_sales();
            case('current_key_sales'):
                $this->upload_current_key_sales();



                break;

            }
        }

        function get_part_skus() {
            $part_skus=array();
            $sql=sprintf("select SPPL.`Part SKU` from  `Supplier Product Part Dimension` SPPD left join  `Supplier Product Part List` SPPL on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`) left join `Part Dimension` P on (SPPL.`Part SKU`=P.`Part SKU`) where `Supplier Product Key`=%d and `Supplier Product Part Most Recent`='Yes' group by  `Part SKU`;",
                         $this->pid
                        );
            $result=mysql_query($sql);
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $part_skus[$row['Part SKU']]=$row['Part SKU'];
            }
            return $part_skus;
        }

        function get_parts() {
            $parts=array();
            $sql=sprintf("select SPPL.`Part SKU`,`Supplier Product Units Per Part`,`Part Unit`  from  `Supplier Product Part Dimension` SPPD left join  `Supplier Product Part List` SPPL on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`) left join `Part Dimension` P on (SPPL.`Part SKU`=P.`Part SKU`) where `Supplier Product Key`=%d and `Supplier Product Part Most Recent`='Yes' group by  `Part SKU`;",
                         $this->pid
                        );


            $result=mysql_query($sql);
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $parts[$row['Part SKU']]=array(
                                             'Part_SKU'=>$row['Part SKU'],
                                             'Supplier_Product_Units_Per_Part'=>$row['Supplier Product Units Per Part'],
                                             'Part_Unit'=>$row['Part Unit'],
                                             'part'=>new Part($row['Part SKU']),
                                             'Parts_Per_Supplier_Product_Unit'=>1/$row['Supplier Product Units Per Part'],
                                         );
            }

            return $parts;
        }
        function get_parts_objects() {
            $parts=array();
            $sql=sprintf("select `Part SKU`  from  `Supplier Product Part Dimension` SPPD left join  `Supplier Product Part List` SPPL on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`) where `Supplier Product Key`=%d and `Supplier Product Part Most Recent`='Yes' group by  `Part SKU`;",
                         $this->id
                        );
            // print "$sql\n";
            //exit;
            $result=mysql_query($sql);
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $parts[$row['Part SKU']]=new Part($row['Part SKU']);

            }

            return $parts;
        }
        function get($key='') {

            if (array_key_exists($key,$this->data))
                return $this->data[$key];

            $_key=preg_replace('/^Supplier Product /','',$key);
            if (isset($this->data[$_key]))
                return $this->data[$key];


            switch ($key) {
            case('Units Per Case'):
                return number($this->data['Supplier Product '.$key]);
                break;
            case('Unit'):
                return $this->get_formated_unit();
                break;
            case('Formated Cost'):
                //print_r($this->data);
                //return $this->data['Supplier Product Cost'];
                return money($this->data['Supplier Product Cost'],$this->data['Supplier Product Currency']);

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
            $sql=sprintf("update `Supplier Product History Dimension`  set `SPH Valid From`=%s where  `SPH Key`=%d and `SPH Valid From`>%s   "
                         ,prepare_mysql($date)
                         ,$this->id
                         ,prepare_mysql($date)

                        );
            mysql_query($sql);
            $affected+=mysql_affected_rows();
            $sql=sprintf("update `Supplier Product History Dimension`  set `SPH Valid To`=%s where  `SPH Key`=%d and `SPH Valid To`<%s   "
                         ,prepare_mysql($date)
                         ,$this->id
                         ,prepare_mysql($date)

                        );
            mysql_query($sql);
            $affected+=mysql_affected_rows();
            return $affected;
        }
        function update_valid_dates_code($date) {
            $affected=0;
            $sql=sprintf("update `Supplier Product Dimension`  set `Supplier Product Valid From`=%s where  `Supplier Product Code`=%s and `Supplier Key`=%d and `Supplier Product Valid From`>%s   "
                         ,prepare_mysql($this->code)
                         ,prepare_mysql($date)
                         ,$this->supplier_key
                         ,prepare_mysql($date)

                        );
            mysql_query($sql);
            $affected+=mysql_affected_rows();
            $sql=sprintf("update `Supplier Product Dimension`  set `Supplier Product Valid To`=%s where  `Supplier Product Code`=%s and `Supplier Key`=%d  and `Supplier Product Valid To`<%s   "
                         ,prepare_mysql($date)
                         ,prepare_mysql($this->code)
                         ,$this->supplier_key
                         ,prepare_mysql($date)

                        );
            mysql_query($sql);
            $affected+=mysql_affected_rows();
            return $affected;
        }
        function update_stock() {
            $parts=$this->get_parts();
            $stock=0;
            if (count($parts)==1) {
                $part_data=array_pop($parts);
                $part=new Part($part_data['Part SKU']);
                $stock=$part->data['Part Current Stock']*$part_data['Supplier Product Units Per Part'];


            }

            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Stock`=%f  where `Supplier Product Code`=%s and `Supplier Key`=%d "
                         ,$stock
                         ,prepare_mysql($this->data['Supplier Product Code'])
                         ,$this->data['Supplier Key']
                        );
            mysql_query($sql);
//print "$sql\n";

        }
        function update_days_availeable() {
            $parts=$this->get_parts();
            $days_until=0;
            if (count($parts)==1) {
                $part_data=array_pop($parts);
                $part=new Part($part_data['Part SKU']);
                $days_until=$part->data['Part Days Available Forecast'];



            }

            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Days Available`=%f  where `Supplier Product Code`=%s and `Supplier Key`=%d "
                         ,$days_until
                         ,prepare_mysql($this->data['Supplier Product Code'])
                         ,$this->data['Supplier Key']
                        );
            mysql_query($sql);
//print "$sql\n";

        }
        function update_cost($value) {
            $change_at='now';

            $amount=$value;
            if ($amount==$this->data['Supplier Product Cost']) {
                $this->updated=false;
                $this->new_value=money($amount,$this->data['Supplier Product Currency']);
                return;

            }
            $old_formated_price=$this->get('Formated Price');
            $sql=sprintf("select `SPH Key` from `Supplier Product History Dimension` where `Supplier Product Code`=%s and `Supplier Key`=%d and `SPH Case Cost`=%.2f "

                         ,prepare_mysql($this->code)
                         ,$this->supplier_key
                         ,$amount
                        );
            //   print $sql;
            $res=mysql_query($sql);

            $num_historic_records=mysql_num_rows($res);
            if ($num_historic_records==0) {
                $data=array(
                          'SPH Case Cost'=>$amount
                                          ,'Supplier Product Code'=>$this->code
                                                                   ,'Supplier Key'=>$this->supplier_key
                      );
                $this->create_key($data);

                if ($change_at=='now') {
                    $this->change_current_key($this->new_key_id);

                }
                $this->updated=true;

            }
            elseif($num_historic_records==1) {
                $row=mysql_fetch_array($res);
                $key_matched=$row['SPH Key'];
                if ($change_at=='now') {
                    $this->change_current_key($key_matched);

                }
                $this->updated=true;
            }
            else {
                exit("exit more that one hitoric product\n ");

            }



            if ($this->updated) {




                $this->new_value=$this->get('Formated Cost');

                $note=_('Supplier Product Cost Changed').' ('.$this->code.','.$this->get('Formated Cost').')';
                $details=_('Supplier Product').": ".$this->code." (Supplier:".$this->data['Supplier Code'].") "._('cost changed').' '._('from')." ".$old_formated_price."  "._('to').' '. $this->get('Formated Cost') ;
                $action='edited';



            }


        }
        function update_dimensions($field,$value) {

            if ($value=='') {
                $value='NULL';
            }
            elseif(!is_numeric($value)) {
                $this->error=true;
                $this->msg=_('Value has to be a number');
                return;
            }
            elseif($value==0) {
                $this->error=true;
                $this->msg=_('Value can not be zero');
                return;
            }
            elseif($value<0) {
                $this->error=true;
                $this->msg=_('Value can not negative');
                return;
            }
            else {
                $value=sprintf("%f",$value);
            }

            switch ($field) {
            case 'unit_net_weight':
            case 'Supplier Product Unit Net Weight':
                $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Unit Net Weight`=%s where `Supplier Product Key`=%d",$value,$this->pid);
                $indirect_object='Supplier Product Unit Net Weight';

                $old_value=$this->data['Supplier Product Unit Net Weight'];

                if ($old_value=='') {
                    $abstract=_('Supplier Product Net Unit Weight set to').' '.weight($value);
                    $details=_('Supplier Product')." ".$this->code." (ID:".$this->pid.") "._('Net Unit Weight set to').' '.weight($value);

                } else {
                    $abstract=_('Product Unit Net Weight Changed').' ('.weight($old_value).'&rarr;'.weight($value).')';
                    $details=_('Supplier Product')." ".$this->code." (ID:".$this->pid.") "._('Net Weight changed').' '._('from')." ".weight($old_value)." "._('per unit')." "._('to').' '. weight($value).' '._('per unit');
                }
                break;
            case 'unit_gross_weight':
            case 'Supplier Product Unit Gross Weight':
                $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Unit Gross Weight`=%s where `Supplier Product Key`=%d",$value,$this->pid);
                $indirect_object='Supplier Product Unit Gross Weight';

                $old_value=$this->data['Supplier Product Unit Gross Weight'];

                if ($old_value=='') {
                    $abstract=_('Supplier Product Gross Unit Weight set to').' '.weight($value);
                    $details=_('Supplier Product')." ".$this->code." (ID:".$this->pid.") "._('Gross Unit Weight set to').' '.weight($value);

                } else {
                    $abstract=_('Product Unit Gross Weight Changed').' ('.weight($old_value).'&rarr;'.weight($value).')';
                    $details=_('Supplier Product')." ".$this->code." (ID:".$this->pid.") "._('Gross Weight changed').' '._('from')." ".weight($old_value)." "._('per unit')." "._('to').' '. weight($value).' '._('per unit');
                }
                break;
            case 'case_gross_weight':
            case 'Supplier Product Case Gross Weight':
                $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Case Gross Weight`=%s where `Supplier Product Key`=%d",$value,$this->pid);
                $indirect_object='Supplier Product Case Gross Weight';

                $old_value=$this->data['Supplier Product Case Gross Weight'];

                if ($old_value=='') {
                    $abstract=_('Supplier Product Gross Case Weight set to').' '.weight($value);
                    $details=_('Supplier Product')." ".$this->code." (ID:".$this->pid.") "._('Gross Case Weight set to').' '.weight($value);

                } else {
                    $abstract=_('Product Case Gross Weight Changed').' ('.weight($old_value).'&rarr;'.weight($value).')';
                    $details=_('Supplier Product')." ".$this->code." (ID:".$this->pid.") "._('Gross Weight changed').' '._('from')." ".weight($old_value)." "._('per case')." "._('to').' '. weight($value).' '._('per case');
                }
                break;
            case 'unit_gross_volume':
            case 'Supplier Product Unit Gross Volume':
                $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Unit Gross Volume`=%s where `Supplier Product Key`=%d",$value,$this->pid);
                $indirect_object='Supplier Product Unit Gross Volume';

                $old_value=$this->data['Supplier Product Unit Gross Volume'];

                if ($old_value=='') {
                    $abstract=_('Supplier Product Gross Unit Volume set to').' '.volume($value);
                    $details=_('Supplier Product')." ".$this->code." (ID:".$this->pid.") "._('Gross Unit Volume set to').' '.volume($value);

                } else {
                    $abstract=_('Product Unit Gross Volume Changed').' ('.volume($old_value).'&rarr;'.volume($value).')';
                    $details=_('Supplier Product')." ".$this->code." (ID:".$this->pid.") "._('Gross Volume changed').' '._('from')." ".volume($old_value)." "._('per unit')." "._('to').' '. volume($value).' '._('per unit');
                }
                break;
            case 'unit_mov':
            case 'Supplier Product Unit Minimun Orthogonal Gross Volume':
                $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Unit Minimun Orthogonal Gross Volume`=%s where `Supplier Product Key`=%d",$value,$this->pid);
                $indirect_object='Supplier Product Unit Gross Volume';

                $old_value=$this->data['Supplier Product Unit Minimun Orthogonal Gross Volume'];

                if ($old_value=='') {
                    $abstract=_('Supplier Product Unit Minimun Orthogonal Gross Volume set to').' '.volume($value);
                    $details=_('Supplier Product')." ".$this->code." (ID:".$this->pid.") "._('Unit Minimun Orthogonal Gross Volume set to').' '.volume($value);

                } else {
                    $abstract=_('Supplier Product Unit Minimun Orthogonal Gross Volume Changed').' ('.volume($old_value).'&rarr;'.volume($value).')';
                    $details=_('Supplier Product')." ".$this->code." (ID:".$this->pid.") "._('Supplier Product Minimun Orthogonal Gross Volume changed').' '._('from')." ".volume($old_value)." "._('per unit')." "._('to').' '. volume($value).' '._('per unit');
                }
                break;
            case 'case_mov':
            case 'Supplier Product Case Minimun Orthogonal Gross Volume':
                $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Case Minimun Orthogonal Gross Volume`=%s where `Supplier Product Key`=%d",$value,$this->pid);
                $indirect_object='Supplier Product Case Gross Volume';

                $old_value=$this->data['Supplier Product Case Minimun Orthogonal Gross Volume'];

                if ($old_value=='') {
                    $abstract=_('Supplier Product Case Minimun Orthogonal Gross Volume set to').' '.volume($value);
                    $details=_('Supplier Product')." ".$this->code." (ID:".$this->pid.") "._('Case Minimun Orthogonal Gross Volume set to').' '.volume($value);

                } else {
                    $abstract=_('Supplier Product Case Minimun Orthogonal Gross Volume Changed').' ('.volume($old_value).'&rarr;'.volume($value).')';
                    $details=_('Supplier Product')." ".$this->code." (ID:".$this->pid.") "._('Supplier Product Minimun Orthogonal Gross Volume changed').' '._('from')." ".volume($old_value)." "._('per case')." "._('to').' '. volume($value).' '._('per case');
                }
                break;

            default:

                break;
            }

            mysql_query($sql);
            $affected=mysql_affected_rows();
            if ($affected==-1) {
                $this->msg.=' '._('Record can not be updated')."\n";
                $this->error_updated=true;
                $this->error=true;

                return;
            }
            elseif($affected==0) {
                $this->msg.=_('Same value as the old record');

            }
            else {
                $this->updated=true;
                $this->new_value=$value;
                $this->data[$indirect_object]=$value;
                $this->add_history(array(
                                       'Direct Object Key'=>$this->pid,
                                       'Indirect Object'=>$indirect_object,
                                       'History Abstract'=>$abstract,
                                       'History Details'=>$details
                                   ));

            }

        }
        function update_unit_type($value) {

            if (!in_array($value,getEnumValues("Supplier Product Dimension","Supplier Product Unit Type" ))) {
                $this->error=true;
                $this->msg='Invalid Value';
                return;
            }
            $old_value=$this->data['Supplier Product Unit Type'];
            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Unit Type`=%s where `Supplier Product Key`=%d",
                         prepare_mysql($value),
                         $this->pid
                        );
            mysql_query($sql);

            $affected=mysql_affected_rows();
            if ($affected==-1) {
                $this->msg.=' '._('Record can not be updated')."\n";
                $this->error_updated=true;
                $this->error=true;

                return;
            }
            elseif($affected==0) {
                $this->msg.=_('Same value as the old record');

            }
            else {
                $this->updated=true;
                $this->new_value=$value;
                $this->data['Supplier Product Unit Type']=$value;

                if ($old_value=='Unknown') {
                    $abstract=_('Supplier Product Unit Type set to').' '.$value;
                    $details=_('Supplier Product')." ".$this->code." (ID:".$this->pid.") "._('Supplier Product Unit Type set to').' '.$value;

                } else {
                    $abstract=_('Supplier Product Unit Type  Changed').' ('.$old_value.'&rarr;'.$value.')';
                    $details=_('Supplier Product')." ".$this->code." (ID:".$this->pid.") "._('Supplier Product Unit Type changed').' '._('from')." ".$old_value." "._('to').' '. $value;
                }



                $this->add_history(array(
                                       'Direct Object Key'=>$this->pid,
                                       'Indirect Object'=>'Supplier Product Unit Type',
                                       'History Abstract'=>$abstract,
                                       'History Details'=>$details
                                   ));

            }



        }
        function update_unit_packing_type($value) {

            if (!in_array($value,getEnumValues("Supplier Product Dimension","Supplier Product Unit Package Type" ))) {
                $this->error=true;
                $this->msg='Invalid Value';
                return;
            }
            $old_value=$this->data['Supplier Product Unit Package Type'];
            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Unit Package Type`=%s where `Supplier Product Key`=%d",
                         prepare_mysql($value),
                         $this->pid
                        );
            mysql_query($sql);
            $affected=mysql_affected_rows();
            if ($affected==-1) {
                $this->msg.=' '._('Record can not be updated')."\n";
                $this->error_updated=true;
                $this->error=true;

                return;
            }
            elseif($affected==0) {
                $this->msg.=_('Same value as the old record');

            }
            else {
                $this->updated=true;
                $this->new_value=$value;
                $this->data['Supplier Product Unit Package Type']=$value;

                if ($old_value=='Unknown') {
                    $abstract=_('Supplier Product Unit Package Type set to').' '.$value;
                    $details=_('Supplier Product')." ".$this->code." (ID:".$this->pid.") "._('Supplier Product Unit Package Type set to').' '.$value;

                } else {
                    $abstract=_('Supplier Product Unit Package Type  Changed').' ('.$old_value.'&rarr;'.$value.')';
                    $details=_('Supplier Product')." ".$this->code." (ID:".$this->pid.") "._('Supplier Product Unit Package Type  changed').' '._('from')." ".$old_value." "._('to').' '. $value;
                }


                $this->add_history(array(
                                       'Direct Object Key'=>$this->pid,
                                       'Indirect Object'=>'Supplier Product Unit Package Type',
                                       'History Abstract'=>$abstract,
                                       'History Details'=>$details
                                   ));

            }



        }
        function update_field_switcher($field,$value,$options='') {
//print "$field $value";
            switch ($field) {
            case('Supplier Product Cost'):
                $this->update_cost($value);
                break;
            case('Supplier Product Buy State'):
                $this->update_buy_state($value);
                break;
            case 'unit_net_weight':
            case 'Supplier Product Unit Net Weight':
            case 'unit_gross_weight':
            case 'Supplier Product Unit Gross Weight':
            case 'Supplier Product Unit Gross Volume':
            case 'Supplier Product Unit Minimun Orthogonal Gross Volume':
            case 'Supplier Product Case Gross Weight':
            case 'Supplier Product Case Minimun Orthogonal Gross Volume':
            case 'unit_gross_volume':
            case 'unit_mov':
            case 'case_gross_weight':
            case 'case_mov':
                $this->update_dimensions($field,$value);
                break;
            case('unit_packing_type'):
                $this->update_unit_packing_type($value);
                break;
            case('Supplier Product Unit Type'):
            case('unit_type'):
                $this->update_unit_type($value);
                break;
            case('url'):
                $field="Supplier Product URL";


            default:
                $base_data=$this->base_data();
                if (preg_match('/^Address.*Data$/',$field))
                    $this->update_field($field,$value,$options);
                elseif(array_key_exists($field,$base_data)) {

                    if ($value!=$this->data[$field]) {

                        $this->update_field($field,$value,$options);
                    }
                }


            }


        }
        function change_current_key($new_current_key) {

            $sql=sprintf("select `SPH Case Cost` from `Supplier Product History Dimension` where `Supplier Key`=%d and `Supplier Product Code`=%s and `SPH Key`=%d "
                         ,$this->supplier_key
                         ,prepare_mysql($this->code)
                         ,$new_current_key
                        );
//print $sql;
            $res=mysql_query($sql);
            $num_historic_records=mysql_num_rows($res);
            if ($num_historic_records==0) {
                $this->error=true;
                $this->msg.=';Can not change product current key because mre key is not associated with ID';
                return;
            }
            $row=mysql_fetch_array($res);

            $price=$row['SPH Case Cost'];


            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Cost`=%.2f,`Supplier Product Current Key`=%d  where `Supplier Product Code`=%s and `Supplier Key`=%d "
                         ,$price
                         ,$new_current_key
                         ,prepare_mysql($this->code)
                         ,$this->supplier_key
                        );
            // print $sql;
            mysql_query($sql);
            $this->data['Supplier Product Cost']=sprintf("%.2f",$price);
            $this->data['Supplier Product Current Key']=$new_current_key;

            $this->id =$new_current_key;


        }
        protected function update_buy_state($value,$options='') {
            $field='Supplier Product Buy State';
            //print "** Update Field $field $value\n";

            $old_value=_('Unknown');

            $key_field=$this->table_name." Key";
            if ($this->table_name=='Supplier Product')
                $key_field='Supplier Product Current Key';

            $sql="select `".$field."` as value from  `".$this->table_name." Dimension`  where `$key_field`=".$this->id;
            //print "$sql\n";
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $old_value=$row['value'];
            }


            $sql="update `".$this->table_name." Dimension` set `".$field."`=".prepare_mysql($value)." where `$key_field`=".$this->id;
//  print $sql;

            mysql_query($sql);
            $affected=mysql_affected_rows();
            if ($affected==-1) {
                $this->msg.=' '._('Record can not be updated')."\n";
                $this->error_updated=true;
                $this->error=true;

                return;
            }
            elseif($affected==0) {
                //$this->msg.=' '._('Same value as the old record');

            } else {


                if ($value=='Deleted') {
                    $deleted_code=$this->code.'.deleted';
                    $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Code`=%s where `Supplier Product Code`=%s and `Supplier Key`=%d "
                                 ,prepare_mysql($deleted_code)
                                 ,prepare_mysql($this->code)
                                 ,$this->supplier_key
                                );
                    //print $sql;
                    mysql_query($sql);
                    $sql=sprintf("update `Supplier Product History Dimension` set `Supplier Product Code`=%s where `Supplier Product Code`=%s and `Supplier Key`=%d "
                                 ,prepare_mysql($deleted_code)
                                 ,prepare_mysql($this->code)
                                 ,$this->supplier_key
                                );
                    mysql_query($sql);
                }

                $this->data[$field]=$value;
                $this->msg.=" $field "._('Record updated').", \n";
                $this->msg_updated.=" $field "._('Record updated').", \n";
                $this->updated=true;
                $this->new_value=$value;

                $save_history=true;
                if (preg_match('/no( |\_)history|nohistory/i',$options))
                    $save_history=false;
                if (
                    preg_match('/customer|contact|company|order|staff|supplier|address|telecom|user|store|product|company area|company department|position/i',$this->table_name)
                    and !$this->new
                    and $save_history
                ) {
                    $history_data=array(
                                      'Indirect Object'=>$field
                                                        ,'History Abstract'=>$field.' ' ._('Changed')
                                                                            ,'History Details'=>$field.' '._('changed from').' '.$old_value.' to '.$this->new_value
                                  );




                    if ($this->table_name=='Product Family')
                        $history_data['Direct Object']='Family';
                    if ($this->table_name=='Product Department')
                        $history_data['Direct Object']='Department';


                    $this->add_history($history_data);

                }

            }

        }
        function get_historic_keys() {
            $sql=sprintf("select `SPH Key` from `Supplier Product History Dimension` where `Supplier Product Key`=%d ",
                         $this->pid

                        );
            // print $sql;
            $res=mysql_query($sql);
            $historic_keys=array();
            while ($row=mysql_fetch_array($res)) {
                $historic_keys[]=$row['SPH Key'];
            }
            return $historic_keys;
        }





        function update_up_today_sales() {
            $this->update_sales('All');
            $this->update_sales('Today');
            $this->update_sales('Week To Day');
            $this->update_sales('Month To Day');
            $this->update_sales('Year To Day');
        }

        function update_last_period_sales() {

            $this->update_sales('Yesterday');
            $this->update_sales('Last Week');
            $this->update_sales('Last Month');
        }

        function update_interval_sales() {
            $this->update_sales('3 Year');
            $this->update_sales('1 Year');
            $this->update_sales('6 Month');
            $this->update_sales('1 Quarter');
            $this->update_sales('1 Month');
            $this->update_sales('10 Day');
            $this->update_sales('1 Week');
        }

        function update_sales($interval) {
            $to_date='';

            switch ($interval) {


            case 'All':
            case 'Total':
                $db_interval='Total';


                $from_date=date('Y-m-d 00:00:00',strtotime($this->data['Supplier Product Valid From']));
                $to_date=date('Y-m-d H:i:s');


                break;

            case 'Last Month':
            case 'last_m':
                $db_interval='Last Month';
                $from_date=date('Y-m-d 00:00:00',mktime(0,0,0,date('m')-1,1,date('Y')));
                $to_date=date('Y-m-d 00:00:00',mktime(0,0,0,date('m'),1,date('Y')));

                $from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
                $to_1yb=date('Y-m-d H:i:s',strtotime("$to_date -1 year"));
                //print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";
                break;

            case 'Last Week':
            case 'last_w':
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
            case 'yesterday':
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
            case 'today':
                $db_interval='Today';
                $from_date=date('Y-m-d 00:00:00');
                $from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
                $to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
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
            case '3y':
                $db_interval=$interval;
                $from_date=date('Y-m-d H:i:s',strtotime("now -3 year"));
                $from_date_1yb=false;
                $to_1yb=false;
                break;
            case '1 Year':
            case '1y':
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
            case '1q':
                $db_interval=$interval;
                $from_date=date('Y-m-d H:i:s',strtotime("now -3 months"));
                $from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
                $to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
                break;
            case '1 Month':
            case '1m':
                $db_interval=$interval;
                $from_date=date('Y-m-d H:i:s',strtotime("now -1 month"));
                $from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
                $to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
                break;
            case '10 Day':
            case '10d':
                $db_interval=$interval;
                $from_date=date('Y-m-d H:i:s',strtotime("now -10 days"));
                $from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
                $to_1yb=date('Y-m-d H:i:s',strtotime("now -1 year"));
                break;
            case '1 Week':
            case '1w':
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


            $this->data["Supplier Product $db_interval Acc Parts Profit"]=0;
            $this->data["Supplier Product $db_interval Acc Parts Profit After Storing"]=0;
            $this->data["Supplier Product $db_interval Acc Parts Cost"]=0;
            $this->data["Supplier Product $db_interval Acc Parts Sold Amount"]=0;
            $this->data["Supplier Product $db_interval Acc Parts Bought"]=0;
            $this->data["Supplier Product $db_interval Acc Parts Required"]=0;
            $this->data["Supplier Product $db_interval Acc Parts Dispatched"]=0;
            $this->data["Supplier Product $db_interval Acc Parts No Dispatched"]=0;
            $this->data["Supplier Product $db_interval Acc Parts Sold"]=0;
            $this->data["Supplier Product $db_interval Acc Parts Lost"]=0;
            $this->data["Supplier Product $db_interval Acc Parts Broken"]=0;
            $this->data["Supplier Product $db_interval Acc Parts Returned"]=0;
            $this->data["Supplier Product $db_interval Acc Parts Margin"]=0;



            $sql=sprintf("select sum(`Inventory Transaction Amount`) as profit,sum(`Inventory Transaction Storing Charge Amount`) as cost_storing
                         from `Inventory Transaction Fact` ITF  where `Supplier Product Key`=%d and `Date`>=%s %s" ,
                         $this->id,
                         prepare_mysql($from_date),
                         ($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

                        );
            $result=mysql_query($sql);
//print "$sql\n";
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $this->data["Supplier Product $db_interval Acc Parts Profit"]=-1.0*$row['profit'];
                $this->data["Supplier Product $db_interval Acc Parts Profit After Storing"]=$this->data["Supplier Product $db_interval Acc Parts Profit"]-$row['cost_storing'];

            }

            $sql=sprintf("select sum(`Inventory Transaction Amount`) as cost, sum(`Inventory Transaction Quantity`) as bought
                         from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='In'  and `Supplier Product Key`=%d and `Date`>=%s %s" ,
                         $this->id,
                         prepare_mysql($from_date),
                         ($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

                        );
            $result=mysql_query($sql);
//print "$sql\n";
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $this->data["Supplier Product $db_interval Acc Parts Cost"]=$row['cost'];
                $this->data["Supplier Product $db_interval Acc Parts Bought"]=$row['bought'];

            }

            $sql=sprintf("select sum(`Inventory Transaction Amount`) as sold_amount,
                         sum(`Inventory Transaction Quantity`) as dispatched,
                         sum(`Required`) as required,
                         sum(`Given`) as given,
                         sum(`Required`-`Inventory Transaction Quantity`) as no_dispatched,
                         sum(`Given`-`Inventory Transaction Quantity`) as sold
                         from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Sale' and `Supplier Product Key`=%d and `Date`>=%s %s" ,
                         $this->id,
                         prepare_mysql($from_date),
                         ($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

                        );
            $result=mysql_query($sql);
//print "$sql\n";
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $this->data["Supplier Product $db_interval Acc Parts Sold Amount"]=-1.0*$row['sold_amount'];
                $this->data["Supplier Product $db_interval Acc Parts Sold"]=$row['sold'];
                $this->data["Supplier Product $db_interval Acc Parts Dispatched"]=-1.0*$row['dispatched'];
                $this->data["Supplier Product $db_interval Acc Parts Required"]=$row['required'];
                $this->data["Supplier Product $db_interval Acc Parts No Dispatched"]=$row['no_dispatched'];


            }

            $sql=sprintf("select sum(`Inventory Transaction Quantity`) as broken
                         from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Broken' and `Supplier Product Key`=%d and `Date`>=%s %s" ,
                         $this->id,
                         prepare_mysql($from_date),
                         ($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

                        );
            $result=mysql_query($sql);
//print "$sql\n";
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $this->data["Supplier Product $db_interval Acc Parts Broken"]=-1.*$row['broken'];

            }


            $sql=sprintf("select sum(`Inventory Transaction Quantity`) as lost
                         from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Lost' and `Supplier Product Key`=%d and `Date`>=%s %s" ,
                         $this->id,
                         prepare_mysql($from_date),
                         ($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

                        );
            $result=mysql_query($sql);
//print "$sql\n";
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $this->data["Supplier Product $db_interval Acc Parts Lost"]=-1.*$row['lost'];

            }

            if ($this->data["Supplier Product $db_interval Acc Parts Sold Amount"]!=0)
                $margin=$this->data["Supplier Product $db_interval Acc Parts Profit After Storing"]/$this->data["Supplier Product $db_interval Acc Parts Sold Amount"];
            else
                $margin=0;

            $this->data["Supplier Product $db_interval Acc Parts Margin"]=$margin;
            $sql=sprintf("update `Supplier Product Dimension` set
                         `Supplier Product $db_interval Acc Parts Profit`=%.2f,
                         `Supplier Product $db_interval Acc Parts Profit After Storing`=%.2f,
                         `Supplier Product $db_interval Acc Parts Cost`=%.2f,
                         `Supplier Product $db_interval Acc Parts Sold Amount`=%.2f,
                         `Supplier Product $db_interval Acc Parts Sold`=%f,
                         `Supplier Product $db_interval Acc Parts Dispatched`=%f,
                         `Supplier Product $db_interval Acc Parts Required`=%f,
                         `Supplier Product $db_interval Acc Parts No Dispatched`=%f,
                         `Supplier Product $db_interval Acc Parts Broken`=%f,
                         `Supplier Product $db_interval Acc Parts Lost`=%f,
                         `Supplier Product $db_interval Acc Parts Returned`=%f,
                         `Supplier Product $db_interval Acc Parts Margin`=%f
                         where `Supplier Product Key`=%d ",
                         $this->data["Supplier Product $db_interval Acc Parts Profit"],
                         $this->data["Supplier Product $db_interval Acc Parts Profit After Storing"],
                         $this->data["Supplier Product $db_interval Acc Parts Cost"],
                         $this->data["Supplier Product $db_interval Acc Parts Sold Amount"],
                         $this->data["Supplier Product $db_interval Acc Parts Sold"],
                         $this->data["Supplier Product $db_interval Acc Parts Dispatched"],
                         $this->data["Supplier Product $db_interval Acc Parts Required"],
                         $this->data["Supplier Product $db_interval Acc Parts No Dispatched"],
                         $this->data["Supplier Product $db_interval Acc Parts Broken"],
                         $this->data["Supplier Product $db_interval Acc Parts Lost"],
                         $this->data["Supplier Product $db_interval Acc Parts Returned"],
                         $margin,
                         $this->pid

                        );

            mysql_query($sql);
            // print "$sql\n";

        }





        function upload_sales_old() {
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


            $historic_keys=join(',',$this->get_historic_keys());


            if ($historic_keys=='') {
                print_r($this);
                exit("Mega error Supplier Product with out historic key\n");
            }


            $sql=sprintf("select   sum(`Inventory Transaction Storing Charge Amount`) as storing,   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where   `Inventory Transaction Type`='Sale' and `Supplier Product Key` in (%s)  "
                         ,$historic_keys);
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


//print "** $required ***\n";


            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Total Cost`=%.2f,`Supplier Product Total Parts Required`=%f ,`Supplier Product Total Parts Provided`=%f,`Supplier Product Total Parts Used`=%f ,`Supplier Product Total Sold Amount`=%f ,`Supplier Product Total Parts Profit`=%f ,`Supplier Product Total Parts Profit After Storing`=%f  where  `Supplier Key`=%d  and `Supplier Product Code`=%s"
                         ,$cost
                         ,$required
                         ,$provided
                         ,$given+$provided
                         ,$amount_in
                         ,$profit_sold
                         ,$profit_sold_after_storing
                         ,$this->data['Supplier Key']
                         ,prepare_mysql($this->data['Supplier Product Code'])

                        );
            //   print "$sql\n";
            //  exit;
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
            $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where  `Inventory Transaction Type`='Sale' and `Supplier Product Key` in (%s) and `Date`>=%s    ",$historic_keys,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 year")))  );
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

            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product 1 Year Acc Cost`=%.2f,`Supplier Product 1 Year Acc Parts Required`=%f ,`Supplier Product 1 Year Acc Parts Provided`=%f,`Supplier Product 1 Year Acc Parts Used`=%f ,`Supplier Product 1 Year Acc Sold Amount`=%f ,`Supplier Product 1 Year Acc Parts Profit`=%f  where   `Supplier Key`=%d  and `Supplier Product Code`=%s"
                         ,$cost=0
                                ,$required
                         ,$provided
                         ,$given+$provided
                         ,$amount_in
                         ,$profit_sold
                         ,$this->data['Supplier Key']
                         ,prepare_mysql($this->data['Supplier Product Code'])

                        );
            //    print "$sql\n";
            if (!mysql_query($sql))
                exit("error con not uopdate product part when loading sales");






            //1 quarter


            $sold=0;
            $required=0;
            $provided=0;
            $given=0;
            $amount_in=0;
            $value=0;
            $value_free=0;
            $margin=0;
            $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where  `Inventory Transaction Type`='Sale' and `Supplier Product Key` in (%s)   and `Date`<=%s and `Date`>=%s     ",$historic_keys,prepare_mysql($this->data['Supplier Product Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -3 month")))  );
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
            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product 1 Quarter Acc Cost`=%.2f,`Supplier Product 1 Quarter Acc Parts Required`=%f ,`Supplier Product 1 Quarter Acc Parts Provided`=%f,`Supplier Product 1 Quarter Acc Parts Used`=%f ,`Supplier Product 1 Quarter Acc Sold Amount`=%f ,`Supplier Product 1 Quarter Acc Parts Profit`=%f  where   `Supplier Key`=%d  and `Supplier Product Code`=%s "
                         ,$cost
                         ,$required
                         ,$provided
                         ,$given+$provided
                         ,$amount_in
                         ,$profit_sold
                         ,$this->data['Supplier Key']
                         ,prepare_mysql($this->data['Supplier Product Code'])
                        )
                 ;
            //                  print "$sql\n";
            if (!mysql_query($sql))
                exit("error con not uopdate product part when loading sales 1 q");




            //1 month




            $sold=0;
            $required=0;
            $provided=0;
            $given=0;
            $amount_in=0;
            $value=0;
            $value_free=0;
            $margin=0;
            $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Inventory Transaction Type`='Sale'  and `Supplier Product Key` in (%s) and `Date`<=%s and `Date`>=%s   ",$historic_keys,prepare_mysql($this->data['Supplier Product Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 month")))  );
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
            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product 1 Month Acc Cost`=%.2f, `Supplier Product 1 Month Acc Parts Required`=%f ,`Supplier Product 1 Month Acc Parts Provided`=%f,`Supplier Product 1 Month Acc Parts Used`=%f ,`Supplier Product 1 Month Acc Sold Amount`=%f ,`Supplier Product 1 Month Acc Parts Profit`=%f  where   `Supplier Key`=%d  and `Supplier Product Code`=%s"
                         ,$cost
                         ,$required
                         ,$provided
                         ,$given+$provided
                         ,$amount_in
                         ,$profit_sold
                         ,$this->data['Supplier Key']
                         ,prepare_mysql($this->data['Supplier Product Code'])
                        );
            //                  print "$sql\n";
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
            $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transaction Fact` where `Inventory Transaction Type`='Sale'  and `Supplier Product Key` in (%s)  and `Date`<=%s and `Date`>=%s    ",
                         $historic_keys,
                         prepare_mysql($this->data['Supplier Product Valid To']) ,
                         prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 week")))
                        );
            //print "$sql\n";
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
            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product 1 Week Acc Cost`=%.2f,`Supplier Product 1 Week Acc Parts Required`=%f ,`Supplier Product 1 Week Acc Parts Provided`=%f,`Supplier Product 1 Week Acc Parts Used`=%f ,`Supplier Product 1 Week Acc Sold Amount`=%f ,`Supplier Product 1 Week Acc Parts Profit`=%f  where   `Supplier Key`=%d  and `Supplier Product Code`=%s"
                         ,$cost
                         ,$required
                         ,$provided
                         ,$given+$provided
                         ,$amount_in
                         ,$profit_sold
                         ,$this->data['Supplier Key']
                         ,prepare_mysql($this->data['Supplier Product Code'])
                        );
            //                print "$sql\n";

            // exit;
            if (!mysql_query($sql))
                exit("error con not uopdate product part when loading sales");









        }



        function upload_current_key_sales() {
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


            $sql=sprintf("update `Supplier Product History Dimension` set `SPH Total Cost`=%.2f,`SPH Total Parts Required`=%f ,`SPH Total Parts Provided`=%f,`SPH Total Parts Used`=%f ,`SPH Total Sold Amount`=%f ,`SPH Total Parts Profit`=%f ,`SPH Total Parts Profit After Storing`=%f  where `SPH Key`=%d "
                         ,$cost
                         ,$required
                         ,$provided
                         ,$given+$provided
                         ,$amount_in
                         ,$profit_sold
                         ,$profit_sold_after_storing
                         ,$this->id


                        );

            if (!mysql_query($sql))
                exit("*** error con not uopdate product part when loading sales");





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

            $sql=sprintf("update `Supplier Product History Dimension` set `SPH 1 Year Acc Cost`=%.2f,`SPH 1 Year Acc Parts Required`=%f ,`SPH 1 Year Acc Parts Provided`=%f,`SPH 1 Year Acc Parts Used`=%f ,`SPH 1 Year Acc Sold Amount`=%f ,`SPH 1 Year Acc Parts Profit`=%f  where `SPH Key`=%d "
                         ,$cost=0
                                ,$required
                         ,$provided
                         ,$given+$provided
                         ,$amount_in
                         ,$profit_sold
                         ,$this->id

                        );
            //    print "$sql\n";
            if (!mysql_query($sql))
                exit("*error con not uopdate product part when loading sales");




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
            $sql=sprintf("update `Supplier Product History Dimension` set `SPH 1 Quarter Acc Cost`=%.2f,`SPH 1 Quarter Acc Parts Required`=%f ,`SPH 1 Quarter Acc Parts Provided`=%f,`SPH 1 Quarter Acc Parts Used`=%f ,`SPH 1 Quarter Acc Sold Amount`=%f ,`SPH 1 Quarter Acc Parts Profit`=%f  where `SPH Key`=%d "
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
            $sql=sprintf("update `Supplier Product History Dimension` set `SPH 1 Month Acc Cost`=%.2f, `SPH 1 Month Acc Parts Required`=%f ,`SPH 1 Month Acc Parts Provided`=%f,`SPH 1 Month Acc Parts Used`=%f ,`SPH 1 Month Acc Sold Amount`=%f ,`SPH 1 Month Acc Parts Profit`=%f  where `SPH Key`=%d "
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
            $sql=sprintf("update `Supplier Product History Dimension` set `SPH 1 Week Acc Cost`=%.2f,`SPH 1 Week Acc Parts Required`=%f ,`SPH 1 Week Acc Parts Provided`=%f,`SPH 1 Week Acc Parts Used`=%f ,`SPH 1 Week Acc Sold Amount`=%f ,`SPH 1 Week Acc Parts Profit`=%f  where `SPH Key`=%d "
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






        }







        function get_formated_unit() {

            switch ($this->data['Supplier Product Unit Type']) {
            case('ea'):
                return _('Item');
                break;
            default:
                return _($this->data['Supplier Product Unit Type']);

            }

        }
        function get_formated_price($locale='') {

            $data=array(
                      'Product Price'=>$this->data['SPH Case Cost'],
                      'Product Units Per Case'=>$this->data['SPH Units Per Case'],
                      'Product Currency'=>$this->get('Supplier Product Currency'),
                      'Product Unit Type'=>$this->data['Supplier Product Unit Type'],


                      'locale'=>$locale);

            return formated_price($data);
        }
        function get_formated_price_per_case($locale='') {

            $data=array(
                      'Product Price'=>$this->data['SPH Case Cost']*$this->data['SPH Units Per Case'],
                      'Product Units Per Case'=>1,
                      'Product Currency'=>$this->get('Supplier Product Currency'),
                      'Product Unit Type'=>_('Case'),

                      'Label'=>'',


                      'locale'=>$locale);

            return formated_price($data);
        }
        function get_formated_price_per_unit($locale='') {

            $data=array(
                      'Product Price'=>$this->data['SPH Case Cost'],
                      'Product Units Per Case'=>$this->data['SPH Units Per Case'],
                      'Product Currency'=>$this->get('Supplier Product Currency'),
                      'Product Unit Type'=>$this->data['Supplier Product Unit Type'],

                      'Label'=>'',


                      'locale'=>$locale);

            return formated_price_per_unit($data);
        }
        function units_convertion_factor($unit_from,$unit_to=false) {
            return 1;
        }
        function get_part_locations() {

        }
      
        
        function new_current_part_list($header_data,$list) {

            $product_part_key=$this->find_product_part_list($list);
            if ($product_part_key) {
                $this->update_product_part_list($product_part_key,$header_data,$list);
            } else {
                $product_part_key=$this->create_product_part_list($header_data,$list);
            }
            $this->set_part_list_as_current($product_part_key);

        }
        function create_product_part_list($header_data,$list) {
            $product_part_key=0;
            $_base_list_data=array(
                                 'Part SKU'=>'',
                                 'Supplier Product Units Per Part'=>''
                             );
            $_base_data=array(
                            'Supplier Product Key'=>$this->pid,
                            'Supplier Product Part Type'=>'Simple',
                            'Supplier Product Part Metadata'=>'',
                            'Supplier Product Part Valid From'=>date('Y-m-d H:i:s'),
                            'Supplier Product Part Valid To'=>date('Y-m-d H:i:s'),
                            'Supplier Product Part Most Recent'=>'No',
                            'Supplier Product Part In Use'=>'No'

                        );

            $base_data=$_base_data;
            foreach($header_data as $key=>$value) {
                if (array_key_exists ($key,$base_data))
                    $base_data[$key]=_trim($value);
            }

            $keys='(';
            $values='values(';
            foreach($base_data as $key=>$value) {
                $keys.="`$key`,";
                if ($key=='Supplier Product Part Metadata' )
                    $values.=prepare_mysql($value,false).',';
                else
                    $values.=prepare_mysql($value).',';
            }
            $keys=preg_replace('/,$/',')',$keys);
            $values=preg_replace('/,$/',')',$values);
            $sql=sprintf("insert into `Supplier Product Part Dimension` %s %s",$keys,$values);
            if (mysql_query($sql)) {
                $product_part_key=mysql_insert_id();
                if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);

                $this->new_value=array('Supplier Product Part Key'=>$product_part_key);
                $this->updated=true;
                $this->new_part_list=true;
                $this->new_part_list_key=$product_part_key;

                foreach($list as $data) {
                    $items_base_data=$_base_list_data;
                    foreach($data as $key=>$value) {
                        if (array_key_exists ($key,$items_base_data))
                            $items_base_data[$key]=_trim($value);
                    }
                    $items_base_data['Supplier Product Part Key']=$product_part_key;
                    $keys='(';
                    $values='values(';
                    foreach($items_base_data as $key=>$value) {
                        $keys.="`$key`,";

                        $values.=prepare_mysql($value).',';
                    }
                    $keys=preg_replace('/,$/',')',$keys);
                    $values=preg_replace('/,$/',')',$values);
                    $sql=sprintf("insert into `Supplier Product Part List` %s %s",$keys,$values);
                    mysql_query($sql);
                    if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
                }
            }
            return $product_part_key;
        }
        function set_part_list_as_current($product_part_key) {
            $current_part_key=$this->get_current_part_key();
            if ($current_part_key!=$product_part_key) {
                $sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Valid To`=%s where `Supplier Product Part Key`=%d  ",prepare_mysql(date('Y-m-d H:i:s')),$current_part_key);
                mysql_query($sql);
                $sql=sprintf("update `Supplier Product Part List` set `Supplier Product Part Most Recent`='No' where `Supplier Product Key`=%d  ",$this->pid);
                mysql_query($sql);
                $sql=sprintf("update `Supplier Product Part List` set `Supplier Product Part Most Recent`='Yes' where `Supplier Product Part Key`=%d  ",$product_part_key);
                mysql_query($sql);
                $sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Most Recent`='No' where `Supplier Product Key`=%d  ",$this->pid);
                mysql_query($sql);
                $sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Most Recent`='Yes' ,`Supplier Product Part Valid To`=NULL  where `Supplier Product Part Key`=%d  ",$product_part_key);
                mysql_query($sql);
            }

            foreach($this->get_parts_objects() as $part) {

                $part->update_estimated_future_cost();
            }
            //  exit;

        }
        function update_product_part_list($product_part_key,$header_data,$list) {

            $this->new_value=array();

            $old_data=$this->get_product_part_dimension_data($product_part_key);
            $old_items_data=$this->get_product_part_list_data($product_part_key);

            if ($old_data['Supplier Product Part Metadata']!=$header_data['Supplier Product Part Metadata']) {
                $sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Metadata`=%s where `Supplier Product Part Key`=%d"
                             ,prepare_mysql($header_data['Supplier Product Part Metadata'])
                             ,$product_part_key
                            );
                mysql_query($sql);
                $this->updated=true;
                $this->part_list_updated=true;

                $this->new_value['Supplier Product Part Metadata']=$header_data['Supplier Product Part Metadata'];
            }


        }
        function get_product_part_dimension_data($product_part_key) {
            $sql=sprintf("select * from `Supplier Product Part Dimension` where `Supplier Product Part Key`=%d  ",$product_part_key);

            $res=mysql_query($sql);
            if ($row=mysql_fetch_assoc($res)) {
                return $row;
            } else
                return false;
        }
        function get_product_part_list_data($product_part_key) {
            $data=array();
            $sql=sprintf("select * from `Supplier Product Part List` where `Supplier Product Part Key`=%d  ",$product_part_key);
            $res=mysql_query($sql);
            while ($row=mysql_fetch_assoc($res)) {
                $data[$row['Part SKU']]=$row;
            }
            return $data;
        }
        function get_current_part_key() {
            $product_part_key=0;
            $sql=sprintf("select `Supplier Product Part Key` from `Supplier Product Part Dimension` where `Supplier Product Key`=%d and `Supplier Product Part Most Recent`='Yes' ",$this->pid);

            $res=mysql_query($sql);
            if ($row=mysql_fetch_assoc($res)) {
                $product_part_key=$row['Supplier Product Part Key'];

            }
            return $product_part_key;
        }
        function find_product_part_list($list) {

            $this_list_num_parts=count($list);
            $good_product_parts=array();
            $found_product_parts=array();

            foreach($list as $key=>$value) {

                $sql=sprintf("select PPD.`Supplier Product Part Key` from  `Supplier Product Part Dimension`  PPD  left join  `Supplier Product Part List` PPL on (PPL.`Supplier Product Part Key`=PPD.`Supplier Product Part Key`)where `Supplier Product Key`=%d and `Part SKU`=%d  and `Supplier Product Units Per Part`=%f and `Supplier Product Part Type`=%s   ",
                             $this->pid,
                             $value['Part SKU'],
                             $value['Supplier Product Units Per Part'],
                             prepare_mysql($value['Supplier Product Part Type'])
                            );

                $res=mysql_query($sql);

                $found_list[$value['Part SKU']]=array();
                while ($row=mysql_fetch_assoc($res)) {
                    $found_list[$value['Part SKU']][$row['Supplier Product Part Key']]=$row['Supplier Product Part Key'];
                    $found_product_parts[$row['Supplier Product Part Key']]=$row['Supplier Product Part Key'];
                }
            }

            foreach($found_list as $sku=>$found_data) {
                if (count($found_data)==0) {
                    return 0;
                }
            }

            foreach($found_product_parts as $product_part_key) {
                $sql=sprintf("select count(*) as num from  `Supplier Product Part List` where `Supplier Product Part Key`=%d",$product_part_key);
                $res=mysql_query($sql);
                $num_parts;
                if ($row=mysql_fetch_assoc($res)) {
                    $num_parts=$row['num'];
                }
                if ($num_parts!=$this_list_num_parts)
                    break;

                foreach($found_list as $sku=>$found_data) {
                    if (!array_key_exists($product_part_key,$found_data)) {
                        break;
                    }
                    $good_product_parts[$product_part_key]=$product_part_key;
                }

            }


            if (count($good_product_parts)==0) {
                return 0;
            }
            elseif(count($good_product_parts)==1) {
                return array_pop($good_product_parts);
            }
            else {
                print "Error ====\n";
                print_r($list);
                print_r($good_product_parts);
                exit("Debug this part list is duplicated\n");
            }

        }
        function new_historic_part_list($header_data,$list) {
//print_r($header_data);
//print_r($list);
            $product_part_key=$this->find_product_part_list($list);
            if ($product_part_key) {
                $this->update_product_part_list($product_part_key,$header_data,$list);
                $this->update_product_part_list_historic_dates($product_part_key,$header_data['Supplier Product Part Valid From'],$header_data['Supplier Product Part Valid To']);

            } else {
                $product_part_key=$this->create_product_part_list($header_data,$list);
            }
        }

        function update_product_part_list_historic_dates($product_part_key,$date1,$date2) {
            $sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Valid From`=%s where `Supplier Product Part Key`=%d and (`Supplier Product Part Valid From` is null or `Supplier Product Part Valid From`>%s)"
                         ,prepare_mysql($date1)
                         ,$product_part_key
                         ,prepare_mysql($date1)
                        );
            mysql_query($sql);
            //print "$sql\n";
            $sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Valid To`=%s where `Supplier Product Part Key`=%d and (`Supplier Product Part Valid To` is null or `Supplier Product Part Valid To`<%s)"
                         ,prepare_mysql($date2)
                         ,$product_part_key
                         ,prepare_mysql($date2)
                        );
            mysql_query($sql);
        }


        function update_sold_as() {
            $used_in_products='';
            $raw_used_in_products='';

            $part_skus=$this->get_part_skus();
            if (count($part_skus)==0)
                $part_skus=0;
            else
                $part_skus=join(',',$part_skus);

            $sql=sprintf("select `Store Code`,PD.`Product ID`,`Product Code` from `Product Part List` PPL left join `Product Part Dimension` PPD on (PPD.`Product Part Key`=PPL.`Product Part Key`) left join `Product Dimension` PD on (PD.`Product ID`=PPD.`Product ID`) left join `Store Dimension`  on (PD.`Product Store Key`=`Store Key`)  where PPL.`Part SKU` in (%s)  order by `Product Code`,`Store Code`",$part_skus);
            $result=mysql_query($sql);
            // print "$sql\n";
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
            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product XHTML Sold As`=%s ,`Supplier Product Sold As`=%s  where `Supplier Product Key`=%d",
                         prepare_mysql(_trim($used_in_products)),
                         prepare_mysql(_trim($raw_used_in_products)),
                         $this->pid);

            mysql_query($sql);
            //print "$sql\n";
        }

        function update_store_as() {
            $used_in_products='';



            $used_in_parts='';
            $sql=sprintf("select PD.`Part SKU` from `Supplier Product Part List` SPPL left join `Supplier Product Part Dimension` SPPD on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`) left join `Part Dimension` PD on (SPPL.`Part SKU`=PD.`Part SKU`) where `Supplier Product Key`=%d  and `Supplier Product Part Most Recent`='Yes' group by PD.`Part SKU`;",
                         $this->pid
                        );
            $result=mysql_query($sql);
            $num_parts=0;
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
                $used_in_parts.=sprintf(', <a href="part.php?id=%d">%s</a>',$row['Part SKU'],$row['Part SKU']);
                $num_parts++;
            }
            $used_in_parts=preg_replace('/^, /','',$used_in_parts);

            if ($num_parts==0)
                $used_in_parts='';
            else if ($num_parts==1)
                $used_in_parts='SKU:'.$used_in_parts;
            else
                $used_in_parts='SKUs:'.$used_in_parts;

            $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product XHTML Store As`=%s where  `Supplier Product Key`=%d"
                         ,prepare_mysql($used_in_parts)

                         ,$this->pid
                        );
            //print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql error can not update used in insuppiler product \n");

            //exit;


        }
        
        
        
        
      function add_image($image_key,$args='') {

        $tmp_images_dir='app_files/pics/';
        $principal='No';
        if (preg_match('/principal/i',$args))
            $principal='Yes';
        $sql=sprintf("select count(*) as num from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where  `Subject Type`='Supplier Product' and `Subject Key`=%d",$this->pid);

        $res=mysql_query($sql);
        $row=mysql_fetch_array($res,MYSQL_ASSOC );
        $number_images=$row['num'];
        if ($number_images==0)
            $principal='Yes';

        $sql=sprintf("insert into `Image Bridge` values ('Supplier Product',%d,%d,%s,'') on duplicate key update `Is Principal`=%s"
                     ,$this->pid
                     ,$image_key
                     ,prepare_mysql($principal)
                     ,prepare_mysql($principal)
                    );
        //print "$sql\n";
        mysql_query($sql);


       

        $sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='Supplier Product' and   `Subject Key`=%d and  PIB.`Image Key`=%d"
                     ,$this->pid
                     ,$image_key
                    );

        $res=mysql_query($sql);

        if ($row=mysql_fetch_array($res)) {
            if ($row['Image Height']!=0)
                $ratio=$row['Image Width']/$row['Image Height'];
            else
                $ratio=1;
            $this->new_value=array('name'=>$row['Image Filename'],'small_url'=>'image.php?id='.$row['Image Key'].'&size=small','thumbnail_url'=>'image.php?id='.$row['Image Key'].'&size=thumbnail','filename'=>$row['Image Filename'],'ratio'=>$ratio,'caption'=>$row['Image Caption'],'is_principal'=>$row['Is Principal'],'id'=>$row['Image Key']);
            $this->images_slideshow[]=$this->new_value;
        }
        $this->msg="image added";
    }

    function update_main_image() {
        $this->load_images();;
        $num_images=count($this->images);


        $main_image_src='art/nopic.png';
        if ($num_images>0) {

            //print_r($this->images_original);
            foreach( $this->images as $image ) {
                // print_r($image);
                $main_image_src='image.php?id='.$image['Image Key'].'&size=small';
                if ($image['Is Principal']=='Yes') {

                    break;
                }
            }
        }

        $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Main Image`=%s  where `Supplier Product SKU`=%d",
                     prepare_mysql($main_image_src)
                     ,$this->pid
                    );
        
        mysql_query($sql);
       
    }

    function load_images() {
        $sql=sprintf("select PIB.`Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='Supplier Product' and `Subject Key`=%d",$this->pid);

        $res=mysql_query($sql);
        $this->images=array();

        while ($row=mysql_fetch_array($res,MYSQL_ASSOC )) {
            $this->images[$row['Image Key']]=$row;
        }
    }

    function load_images_slidesshow() {
        $sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='Supplier Product' and `Subject Key`=%d",$this->pid);
        $res=mysql_query($sql);
        $this->images_slideshow=array();
        while ($row=mysql_fetch_array($res)) {
            if ($row['Image Height']!=0)
                $ratio=$row['Image Width']/$row['Image Height'];
            else
                $ratio=1;
            // print_r($row);
            $this->images_slideshow[]=array('name'=>$row['Image Filename'],'small_url'=>'image.php?id='.$row['Image Key'].'&size=small','thumbnail_url'=>'image.php?id='.$row['Image Key'].'&size=thumbnail','filename'=>$row['Image Filename'],'ratio'=>$ratio,'caption'=>$row['Image Caption'],'is_principal'=>$row['Is Principal'],'id'=>$row['Image Key']);
        }

    }
   
        
        

    }
