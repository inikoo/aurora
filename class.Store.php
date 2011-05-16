<?php
/*
  File: Company.php

  This file contains the Company Class

  About:
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/

/* class: Store
   Class to manage the *Company Dimension* table
*/

include_once('class.DB_Table.php');

class Store extends DB_Table {

    // Integer: id
    // Record Id

    /*
      Constructor: Store

      Initializes the class, Search/Load or Create for the data set

      Parameters:
      arg1 -    (optional) Could be the tag for the Search Options or the Store Key for a simple object key search
      arg2 -    (optional) data used to search or create the object

      Returns:
      void

      Example:
      (start example)
      // Load data from `Store Dimension` table where  `Store Key`=3
      $key=3;
      $company = New Store($key);

      // Insert row to `Store Dimension` table
      $data=array();
      $company = New Store('new',$data);


      (end example)

    */

    function Store($a1,$a2=false,$a3=false) {
        $this->table_name='Store';
        $this->ignore_fields=array(
                                 'Store Key',
                                 'Store Departments',
                                 'Store Families',
                                 'Store For Sale Products',
                                 'Store In Process Products',
                                 'Store Not For Sale Products',
                                 'Store Discontinued Products',
                                 'Store Unknown Sales State Products',
                                 'Store Surplus Availability Products',
                                 'Store Optimal Availability Products',
                                 'Store Low Availability Products',
                                 'Store Critical Availability Products',
                                 'Store Out Of Stock Products',
                                 'Store Unknown Stock Products',
                                 'Store Total Invoiced Gross Amount',
                                 'Store Total Invoiced Discount Amount',
                                 'Store Total Invoiced Amount',
                                 'Store Total Profit',
                                 'Store Total Quantity Ordered',
                                 'Store Total Quantity Invoiced',
                                 'Store Total Quantity Delivere',
                                 'Store Total Days On Sale',
                                 'Store Total Days Available',
                                 'Store 1 Year Acc Invoiced Gross Amount',
                                 'Store 1 Year Acc Invoiced Discount Amount',
                                 'Store 1 Year Acc Invoiced Amount',
                                 'Store 1 Year Acc Profit',
                                 'Store 1 Year Acc Quantity Ordered',
                                 'Store 1 Year Acc Quantity Invoiced',
                                 'Store 1 Year Acc Quantity Delivere',
                                 'Store 1 Year Acc Days On Sale',
                                 'Store 1 Year Acc Days Available',
                                 'Store 1 Quarter Acc Invoiced Gross Amount',
                                 'Store 1 Quarter Acc Invoiced Discount Amount',
                                 'Store 1 Quarter Acc Invoiced Amount',
                                 'Store 1 Quarter Acc Profit',
                                 'Store 1 Quarter Acc Quantity Ordered',
                                 'Store 1 Quarter Acc Quantity Invoiced',
                                 'Store 1 Quarter Acc Quantity Delivere',
                                 'Store 1 Quarter Acc Days On Sale',
                                 'Store 1 Quarter Acc Days Available',
                                 'Store 1 Month Acc Invoiced Gross Amount',
                                 'Store 1 Month Acc Invoiced Discount Amount',
                                 'Store 1 Month Acc Invoiced Amount',
                                 'Store 1 Month Acc Profit',
                                 'Store 1 Month Acc Quantity Ordered',
                                 'Store 1 Month Acc Quantity Invoiced',
                                 'Store 1 Month Acc Quantity Delivere',
                                 'Store 1 Month Acc Days On Sale',
                                 'Store 1 Month Acc Days Available',
                                 'Store 1 Week Acc Invoiced Gross Amount',
                                 'Store 1 Week Acc Invoiced Discount Amount',
                                 'Store 1 Week Acc Invoiced Amount',
                                 'Store 1 Week Acc Profit',
                                 'Store 1 Week Acc Quantity Ordered',
                                 'Store 1 Week Acc Quantity Invoiced',
                                 'Store 1 Week Acc Quantity Delivere',
                                 'Store 1 Week Acc Days On Sale',
                                 'Store 1 Week Acc Days Available',
                                 'Store Total Quantity Delivered',
                                 'Store 1 Year Acc Quantity Delivered',
                                 'Store 1 Month Acc Quantity Delivered',
                                 'Store 1 Quarter Acc Quantity Delivered',
                                 'Store 1 Week Acc Quantity Delivered'


                             );
        if (is_numeric($a1) and !$a2) {
            $this->get_data('id',$a1);
        }
        elseif($a1=='find') {
            $this->find($a2,$a3);

        }
        else
            $this->get_data($a1,$a2);

    }

    // function get_unknown(){
    //   $sql=sprintf("select * from `Store Dimension` where `Store Type`='unknown'");
    //   $result=mysql_query($sql);
    //   if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
    //     $this->id=$this->data['Store Key'];
    // }





    /*
      Function: data
      Obtiene los datos de la tabla Store Dimension de acuerdo al Id o al codigo de registro.
    */
    // JFA

    function get_data($tipo,$tag) {

        if ($tipo=='id')
            $sql=sprintf("select * from `Store Dimension` where `Store Key`=%d",$tag);
        elseif($tipo=='code')
        $sql=sprintf("select * from `Store Dimension` where `Store Code`=%s",prepare_mysql($tag));
        else
            return;

        // print $sql;
        $result=mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
            $this->id=$this->data['Store Key'];


    }

    /*
      Function: find
      Busca el producto
    */
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


        //    print_r($raw_data);

        if ($data['Store Code']=='' ) {
            $this->error=true;
            $this->msg='Store code empty';
            return;
        }

        if ($data['Store Name']=='')
            $data['Store Name']=$data['Store Code'];


        $sql=sprintf("select * from `Store Dimension` where `Store Code`=%s  "
                     ,prepare_mysql($data['Store Code'])
                    );

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->found=true;
            $this->found_key=$row['Store Key'];
        }


        if ($create and !$this->found) {
            $this->create($data);
            return;
        }
        if ($this->found)
            $this->get_data('id',$this->found_key);

        if ($update and $this->found) {

        }

    }


    /*
      Function: get
      Obtiene datos del producto de acuerdo al codigo de producto, al tipo de producto o la totalidad de productos (esto en base al criterio de seleccion)
    */
    // JFA

    function get($key='') {

        if (isset($this->data[$key]))
            return $this->data[$key];






        switch ($key) {
        case('Contacts'):
        case('Active Contacts'):
        case('New Contacts'):
        case('Lost Contacts'):
        case('Losing Contacts'):
        case('Contacts With Orders'):
        case('Active Contacts With Orders'):
        case('New Contacts With Orders'):
        case('Lost Contacts With Orders'):
        case('Losing Contacts With Orders'):
            return number($this->data['Store '.$key]);

        case('Total Users'):
            return number($this->data['Store Total Users']);
        case('All To Pay Invoices'):
            return $this->data['Store Total Invoices']-$this->data['Store Paid Invoices']-$this->data['Store Paid Refunds'];
        case('All Paid Invoices'):
            return $this->data['Store Paid Invoices']-$this->data['Store Paid Refunds'];
        case('code'):
            return $this->data['Store Code'];
            break;
        case('type'):
            return $this->data['Store Type'];
            break;
        case('Total Products'):
            return $this->data['Store For Sale Products']+$this->data['Store In Process Products']+$this->data['Store Not For Sale Products']+$this->data['Store Discontinued Products']+$this->data['Store Unknown Sales State Products'];
            break;
        case('For Sale Products'):
            return number($this->data['Store For Sale Products']);
            break;
        case('For Public Sale Products'):
            return number($this->data['Store For Public Sale Products']);
            break;
        case('Families'):
            return number($this->data['Store Families']);
            break;
        case('Departments'):
            return number($this->data['Store Departments']);
            break;
        }
        if (preg_match('/^(Total|1).*(Amount|Profit)$/',$key)) {

            $amount='Store '.$key;

            return money($this->data[$amount]);
        }
        if (preg_match('/^(Total|1).*(Quantity (Ordered|Invoiced|Delivered|)|Customers|Customers Contacts)$/',$key) or preg_match('/^(Active Customers)$/',$key)) {

            $amount='Store '.$key;

            return number($this->data[$amount]);
        }
        if (preg_match('/^Delivery Notes For (Orders|Replacements|Shortages|Samples|Donations)$/',$key)) {

            $amount='Store '.$key;

            return number($this->data[$amount]);
        }

        if (preg_match('/(Orders|Delivery Notes|Invoices|Refunds|Orders In Process)$/',$key)) {

            $amount='Store '.$key;

            return number($this->data[$amount]);
        }

        $_key=ucfirst($key);
        if (isset($this->data[$_key]))
            return $this->data[$_key];

    }

    /*
      Function: delete
      Elimina registros de la tabla Store Dimension en base al valor del campo store key, siempre y cuando no haya productos
    */
    // JFA

    function delete() {
        $this->deleted=false;
        $this->load('products_info');

        if ($this->get('Total Products')==0) {
            $sql=sprintf("delete from `Store Dimension` where `Store Key`=%d",$this->id);
            if (mysql_query($sql)) {

                $this->deleted=true;

            } else {

                $this->msg=_('Error: can not delete store');
                return;
            }

            $this->deleted=true;
        } else {
            $this->msg=_('Store can not be deleted because it has some products');

        }
    }


    /*
      Method: load
      Obtiene registros de las tablas Product Dimension, Product Family Dimension, Product Department Dimension, y actualiza datos de Store Dimension, de acuerdo a la categoria indicada.
    */
    // JFA


    function load($tipo,$args=false) {
        switch ($tipo) {




        case('families'):
            $sql=sprintf("select * from `Product Family Dimension`  where  `Product Family Store Key`=%d",$this->id);
            //  print $sql;

            $this->families=array();
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $this->families[$row['family key']]=$row;
            }
            break;

        case('sales'):

            $this->update_store_sales();
            $this->update_sales_default_currency();


            break;
        case('products_info'):
            $this->update_product_data();
            $this->update_families();
            break;
        }

    }

    function update_code($a1) {

        if (_trim($a1)==$this->data['Store Code']) {
            $this->updated=true;
            $this->new_value=$a1;
            return;

        }

        if ($a1=='') {
            $this->msg=_('Error: Wrong code (empty)');
            return;
        }

        if (!(strtolower($a1)==strtolower($this->data['Store Code']) and $a1!=$this->data['Store Code'])) {
            $sql=sprintf("select count(*) as num from `Store Dimension` where  `Store Code`=%s COLLATE utf8_general_ci  "
                         ,prepare_mysql($a1)
                        );
            $res=mysql_query($sql);
            $row=mysql_fetch_array($res);
            if ($row['num']>0) {
                $this->msg=_("Error: There is another store with the same code");
                return;
            }
        }
        $old_value=$this->get('Store Code');
        $sql=sprintf("update `Store Dimension` set `Store Code`=%s where `Store Key`=%d  "
                     ,prepare_mysql($a1)
                     ,$this->id
                    );
        if (mysql_query($sql)) {
            $this->msg=_('Store code updated');
            $this->updated=true;
            $this->new_value=$a1;
            $this->data['Store Code']=$a1;

            $history_data=array(
                              'Indirect Object'=>'Store Code'
                                                ,'History Abstract'=>_('Store Code Changed').' ('.$this->get('Store Code').')'
                                                                    ,'History Details'=>_('Store')." ".$this->data['Store Name']." "._('changed code from').' '.$old_value." "._('to').' '. $this->get('Store Code')
                          );
//print_r($history_data);
            $this->add_history($history_data);





        } else {
            $this->msg=_("Error: Store code could not be updated");

            $this->updated=false;

        }
    }

    /*
      Function: update
      Funcion que permite actualizar el nombre o el codigo en la tabla store dimension, cuidando que no se duplique el valor del codigo o el nombre en dicha tabla
    */

    function update($key,$a1=false,$a2=false) {
        $this->updated=false;
        $this->msg='Nothing to change';

        switch ($key) {
        case('code'):
            $this->update_code($a1);
            break;

        case('slogan'):
            $this->update_field('Store Slogan',$a1);
            break;
        case('url'):
            $this->update_field('Store URL',$a1);
            break;

        case('contact'):
            $this->update_field('Store Contact',$a1);
            break;
        case('email'):
            $this->update_field('Store Email',$a1);
            break;
        case('telephone'):
            $this->update_field('Store Telephone',$a1);
            break;
        case('fax'):
            $this->update_field('Store Fax',$a1);
            break;
        case('name'):

            if (_trim($a1)==$this->data['Store Name']) {
                $this->updated=true;
                $this->new_value=$a1;
                return;

            }

            if ($a1=='') {
                $this->msg=_('Error: Wrong name (empty)');
                return;
            }

            if (!(strtolower($a1)==strtolower($this->data['Store Name']) and $a1!=$this->data['Store Name'])) {

                $sql=sprintf("select count(*) as num from `Store Dimension` where `Store Name`=%s COLLATE utf8_general_ci"
                             ,prepare_mysql($a1)
                            );

                $res=mysql_query($sql);
                $row=mysql_fetch_array($res);
                if ($row['num']>0) {
                    $this->msg=_("Error: Another store with the same name");
                    return;
                }
            }
            $old_value=$this->get('Store Name');
            $sql=sprintf("update `Store Dimension` set `Store Name`=%s where `Store Key`=%d "
                         ,prepare_mysql($a1)
                         ,$this->id
                        );
            if (mysql_query($sql)) {
                $this->msg=_('Store name updated');
                $this->updated=true;
                $this->new_value=$a1;
                $this->data['Store Name']=$a1;

                $this->add_history(array(
                                       'Indirect Object'=>'Store Name'
                                                         ,'History Abstract'=>_('Store Name Changed').' ('.$this->get('Store Name').')'
                                                                             ,'History Details'=>_('Store')." ("._('Code').":".$this->get('Store Code').") "._('name changed from').' '.$old_value." "._('to').' '. $this->get('Store Name')
                                   ));





            } else {
                $this->msg=_("Error: Store name could not be updated");

                $this->updated=false;

            }
            break;


        }


    }

    /*
      Function: create
      Funcion que permite grabar el nombre y codigo en la tabla store dimension, evitando duplicar el valor de codigo y el nombre en dicha tabla
    */
    // JFA
    function create($data) {



        $this->new=false;
        $basedata=$this->base_data();

        foreach($data as $key=>$value) {
            if (array_key_exists($key,$basedata))
                $basedata[$key]=_trim($value);
        }

        $keys='(';
        $values='values(';
        foreach($basedata as $key=>$value) {
            $keys.="`$key`,";
            if (preg_match('/Store Email|Store Telephone|Store Telephone|Slogan|URL|Fax/i',$key))
                $values.=prepare_mysql($value,false).",";
            else
                $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Store Dimension` %s %s",$keys,$values);

        if (mysql_query($sql)) {
            $this->id = mysql_insert_id();
            $this->msg=_("Store Added");
            $this->get_data('id',$this->id);
            $this->new=true;
            $sql="insert into `User Right Scope Bridge` values(1,'Store',".$this->id.");";
            mysql_query($sql);

            $sql="insert into `Store Default Currency` (`Store Key`) values(".$this->id.");";
            mysql_query($sql);



            $this->add_history(array(
                                   'Action'=>'created'
                                            ,'History Abstract'=>_('Store Created')
                                                                ,'History Details'=>_('Store')." ".$this->data['Store Name']." (".$this->get('Store Code').") "._('Created')
                               ));

            return;
        } else {
            print $sql;
            exit;
            $this->msg=_(" Error can not create store");

        }

    }


    function update_product_data() {

        $availability='No Applicable';
        $sales_type='No Applicable';
        $in_process=0;
        $public_sale=0;
        $private_sale=0;
        $discontinued=0;
        $not_for_sale=0;
        $sale_unknown=0;
        $availability_optimal=0;
        $availability_low=0;
        $availability_critical=0;
        $availability_outofstock=0;
        $availability_unknown=0;
        $availability_surplus=0;
        $new=0;




        $sql=sprintf("select sum(if(`Product Record Type`='New',1,0)) as new,sum(if(`Product Record Type`='In process',1,0)) as in_process,sum(if(`Product Sales Type`='Unknown',1,0)) as sale_unknown, sum(if(`Product Record Type`='Discontinued',1,0)) as discontinued,sum(if(`Product Sales Type`='Not for sale',1,0)) as not_for_sale,sum(if(`Product Sales Type`='Public Sale',1,0)) as public_sale,sum(if(`Product Sales Type`='Private Sale',1,0)) as private_sale,sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal,sum(if(`Product Availability State`='Low',1,0)) as availability_low,sum(if(`Product Availability State`='Surplus',1,0)) as availability_surplus,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from `Product Dimension` where `Product Store Key`=%d",$this->id);

//print $sql;
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $new=$row['new'];

            $in_process=$row['in_process'];
            $public_sale=$row['public_sale'];
            $private_sale=$row['private_sale'];
            $discontinued=$row['discontinued'];
            $not_for_sale=$row['not_for_sale'];
            $sale_unknown=$row['sale_unknown'];
            $availability_optimal=$row['availability_optimal'];
            $availability_low=$row['availability_low'];
            $availability_critical=$row['availability_critical'];
            $availability_outofstock=$row['availability_outofstock'];
            $availability_unknown=$row['availability_unknown'];
            $availability_surplus=$row['availability_surplus'];
        }

        $sql=sprintf("update `Store Dimension` set `Store In Process Products`=%d,`Store For Public Sale Products`=%d, `Store For Private Sale Products`=%d ,`Store Discontinued Products`=%d ,`Store Not For Sale Products`=%d ,`Store Unknown Sales State Products`=%d, `Store Optimal Availability Products`=%d , `Store Low Availability Products`=%d ,`Store Critical Availability Products`=%d ,`Store Out Of Stock Products`=%d,`Store Unknown Stock Products`=%d ,`Store Surplus Availability Products`=%d ,`Store New Products`=%d where `Store Key`=%d  ",
                     $in_process,
                     $public_sale,
                     $private_sale,
                     $discontinued,
                     $not_for_sale,
                     $sale_unknown,
                     $availability_optimal,
                     $availability_low,
                     $availability_critical,
                     $availability_outofstock,
                     $availability_unknown,
                     $availability_surplus,
                     $new,
                     $this->id
                    );
        // print "$sql\n";
        mysql_query($sql);





    }

    function update_customers_data() {

        $this->data['Store Contacts']=0;
        $this->data['Store New Contacts']=0;
        $this->data['Store Contacts With Orders']=0;
        $this->data['Store Active Contacts']=0;
        $this->data['Store Losing Contacts']=0;
        $this->data['Store Lost Contacts']=0;
        $this->data['Store New Contacts With Orders']=0;
        $this->data['Store Active Contacts With Orders']=0;
        $this->data['Store Losing Contacts With Orders']=0;
        $this->data['Store Lost Contacts With Orders']=0;

        $sql=sprintf("select count(*) as num ,sum(IF(`Customer New`='Yes',1,0)) as new,sum(IF(`Customer New`='Yes',1,0)) as new,sum(IF(`Customer Type by Activity`='Active'   ,1,0)) as active, sum(IF(`Customer Type by Activity`='Losing',1,0)) as losing, sum(IF(`Customer Type by Activity`='Lost',1,0)) as lost  from   `Customer Dimension` where `Customer Store Key`=%d ",$this->id);
        //  print "$sql\n";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store Contacts']=$row['num'];
            $this->data['Store New Contacts']=$row['new'];
            $this->data['Store Active Contacts']=$row['active'];
            $this->data['Store Losing Contacts']=$row['losing'];
            $this->data['Store Lost Contacts']=$row['lost'];
        }

        $sql=sprintf("select count(*) as num ,sum(IF(`Customer New`='Yes',1,0)) as new,sum(IF(`Customer New`='Yes',1,0)) as new,sum(IF(`Customer Type by Activity`='Active'   ,1,0)) as active, sum(IF(`Customer Type by Activity`='Losing',1,0)) as losing, sum(IF(`Customer Type by Activity`='Lost',1,0)) as lost  from   `Customer Dimension` where `Customer Store Key`=%d and `Customer With Orders`='Yes'",$this->id);
        //print "$sql\n";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store Contacts With Orders']=$row['num'];
            $this->data['Store New Contacts With Orders']=$row['new'];
            $this->data['Store Active Contacts With Orders']=$row['active'];
            $this->data['Store Losing Contacts With Orders']=$row['losing'];
            $this->data['Store Lost Contacts With Orders']=$row['lost'];
        }

        $sql=sprintf("update `Store Dimension` set
                     `Store Contacts`=%d,
                     `Store New Contacts`=%d,
                     `Store Active Contacts`=%d ,
                     `Store Losing Contacts`=%d ,
                     `Store Lost Contacts`=%d ,

                     `Store Contacts With Orders`=%d,
                     `Store New Contacts With Orders`=%d,
                     `Store Active Contacts With Orders`=%d,
                     `Store Losing Contacts With Orders`=%d,
                     `Store Lost Contacts With Orders`=%d
                     where `Store Key`=%d  ",
                     $this->data['Store Contacts'],
                     $this->data['Store New Contacts'],
                     $this->data['Store Active Contacts'],
                     $this->data['Store Losing Contacts'],
                     $this->data['Store Lost Contacts'],

                     $this->data['Store Contacts With Orders'],
                     $this->data['Store New Contacts With Orders'],
                     $this->data['Store Active Contacts With Orders'],
                     $this->data['Store Losing Contacts With Orders'],
                     $this->data['Store Lost Contacts With Orders'],
                     $this->id
                    );
        //print "$sql\n";
        mysql_query($sql);

    }

    function update_customers_data_old() {

        $current_from=strtotime($this->data['Store Valid From']);

        $sql="select min(`Customer First Contacted Date`) as ffrom ,count(*) as number   from `Customer Dimension` as C   where `Customer Store Key`=".$this->id;
// print "C: $current_from $sql";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $from=strtotime($row['ffrom']);
            //print "-->".$row['ffrom']."\n" ;
            if (($from<$current_from or !$current_from  )and $row['number']>0 and $row['ffrom']!='') {
                $_from=date("Y-m-d H:i:s",$from);
                $this->data['Store Valid From']=$_from;

            }
        }


//print "$current_from \n ".$this->data['Store Valid From'];


        $sql=sprintf("select count(*) as num ,sum(IF(`Customer Orders`>0,1,0)) as customers,sum(IF(`New Served Customer`='Yes',1,0)) as new_served,sum(IF(`New Customer`='Yes',1,0)) as new_contact,sum(IF(`Active Customer`='Yes'   ,1,0)) as active, sum(IF(`Active Customer`='No'  and `Actual Customer`='Yes',1,0)) as lost  from   `Customer Dimension` where `Customer Store Key`=%d",$this->id);
        // print "$sql\n";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store Total Customer Contacts']=$row['num'];
            $this->data['Store New Customer Contacts']=$row['new_contact'];
            $this->data['Store Total Customers']=$row['customers'];
            $this->data['Store Active Customers']=$row['active'];
            $this->data['Store New Customers']=$row['new_served'];
            $this->data['Store Lost Customers']=$row['lost'];
        } else {
            $this->data['Store Total Customer Contacts']=0;
            $this->data['Store New Customer Contacts']=0;
            $this->data['Store Total Customers']=0;
            $this->data['Store Active Customers']=0;
            $this->data['Store New Customers']=0;
            $this->data['Store Lost Customers']=0;

        }



        $sql=sprintf("select count(*) as lost from `Customer Dimension` where`Active Customer`='No'  and `Actual Customer`='Yes' and `Customer Store Key`=%d and `Customer Lost Date`>=%s ",
                     $this->id,
                     prepare_mysql(date("Y-m-d H:i:s",strtotime("-1 year")))
                    );
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store 1 Year Lost Customers']=$row['lost'];
        } else {
            $this->data['Store 1 Year Lost Customers']=0;
        }
        $sql=sprintf("select count(*) as lost from `Customer Dimension` where`Active Customer`='No'  and `Actual Customer`='Yes' and `Customer Store Key`=%d and `Customer Lost Date`>=%s ",
                     $this->id,
                     prepare_mysql(date("Y-m-d H:i:s",strtotime("-3 months")))
                    );
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store 1 Quarter Lost Customers']=$row['lost'];
        } else {
            $this->data['Store 1 Quarter Lost Customers']=0;
        }
        $sql=sprintf("select count(*) as lost from `Customer Dimension` where`Active Customer`='No'  and `Actual Customer`='Yes' and `Customer Store Key`=%d and `Customer Lost Date`>=%s ",
                     $this->id,
                     prepare_mysql(date("Y-m-d H:i:s",strtotime("-1 month")))
                    );
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store 1 Month Lost Customers']=$row['lost'];
        } else {
            $this->data['Store 1 Month Lost Customers']=0;
        }
        $sql=sprintf("select count(*) as lost from `Customer Dimension` where`Active Customer`='No'  and `Actual Customer`='Yes' and `Customer Store Key`=%d and `Customer Lost Date`>=%s ",
                     $this->id,
                     prepare_mysql(date("Y-m-d H:i:s",strtotime("-1 week")))
                    );
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store 1 Week Lost Customers']=$row['lost'];
        } else {
            $this->data['Store 1 Week Lost Customers']=0;
        }


        $sql=sprintf("select count(*) as new from `Customer Dimension` where`Active Customer`='Yes'  and `Customer Store Key`=%d and `Customer First Order Date`>=%s ",
                     $this->id,
                     prepare_mysql(date("Y-m-d H:i:s",strtotime("-1 year")))
                    );
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store 1 Year New Customers']=$row['new'];
        } else {
            $this->data['Store 1 Year New Customers']=0;
        }
        $sql=sprintf("select count(*) as new from `Customer Dimension` where`Active Customer`='Yes'  and `Customer Store Key`=%d and `Customer First Order Date`>=%s ",
                     $this->id,
                     prepare_mysql(date("Y-m-d H:i:s",strtotime("-1 quarter")))
                    );
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store 1 Quarter New Customers']=$row['new'];
        } else {
            $this->data['Store 1 Quarter New Customers']=0;
        }
        $sql=sprintf("select count(*) as new from `Customer Dimension` where`Active Customer`='Yes'  and `Customer Store Key`=%d and `Customer First Order Date`>=%s ",
                     $this->id,
                     prepare_mysql(date("Y-m-d H:i:s",strtotime("-1 month")))
                    );
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store 1 Month New Customers']=$row['new'];
        } else {
            $this->data['Store 1 Month New Customers']=0;
        }
        $sql=sprintf("select count(*) as new from `Customer Dimension` where`Active Customer`='Yes'  and `Customer Store Key`=%d and `Customer First Order Date`>=%s ",
                     $this->id,
                     prepare_mysql(date("Y-m-d H:i:s",strtotime("-1 week")))
                    );
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store 1 Week New Customers']=$row['new'];
        } else {
            $this->data['Store 1 Week New Customers']=0;
        }



        $sql=sprintf("select count(*) as new from `Customer Dimension` where `Customer Store Key`=%d and `Customer First Contacted Date`>=%s ",
                     $this->id,
                     prepare_mysql(date("Y-m-d H:i:s",strtotime("-1 year")))
                    );
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store 1 Year New Customers Contacts']=$row['new'];
        } else {
            $this->data['Store 1 Year New Customers Contacts']=0;
        }
        $sql=sprintf("select count(*) as new from `Customer Dimension` where `Customer Store Key`=%d and `Customer First Contacted Date`>=%s ",
                     $this->id,
                     prepare_mysql(date("Y-m-d H:i:s",strtotime("-3 months")))
                    );
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store 1 Quarter New Customers Contacts']=$row['new'];
        } else {
            $this->data['Store 1 Quarter New Customers Contacts']=0;
        }

        $sql=sprintf("select count(*) as new from `Customer Dimension` where `Customer Store Key`=%d and `Customer First Contacted Date`>=%s ",
                     $this->id,
                     prepare_mysql(date("Y-m-d H:i:s",strtotime("-1 month")))
                    );

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store 1 Month New Customers Contacts']=$row['new'];
        } else {
            $this->data['Store 1 Month New Customers Contacts']=0;
        }


        $sql=sprintf("select count(*) as new from `Customer Dimension` where `Customer Store Key`=%d and `Customer First Contacted Date`>=%s ",
                     $this->id,
                     prepare_mysql(date("Y-m-d H:i:s",strtotime("-1 week")))
                    );
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store 1 Week New Customers Contacts']=$row['new'];
        } else {
            $this->data['Store 1 Week New Customers Contacts']=0;
        }


        $sql=sprintf("select count(*) as new from `Customer Dimension` where `Customer Store Key`=%d and `Customer First Contacted Date`>=%s ",
                     $this->id,
                     prepare_mysql(date("Y-m-d H:i:s",strtotime("-1 day")))
                    );
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store 1 Day New Customers Contacts']=$row['new'];
        } else {
            $this->data['Store 1 Day New Customers Contacts']=0;
        }


        $sql=sprintf("update `Store Dimension` set `Store Valid From`=%s,`Store Total Customer Contacts`=%d , `Store New Customer Contacts`=%d ,`Store Total Customers`=%d,`Store Active Customers`=%d,`Store New Customers`=%d , `Store Lost Customers`=%d ,
                     `Store 1 Year New Customers`=%d,
                     `Store 1 Quarter New Customers`=%d,
                     `Store 1 Month New Customers`=%d,
                     `Store 1 Week New Customers`=%d,
                     `Store 1 Year Lost Customers`=%d,
                     `Store 1 Quarter Lost Customers`=%d,
                     `Store 1 Month Lost Customers`=%d,
                     `Store 1 Week Lost Customers`=%d,
                     `Store 1 Year New Customers Contacts`=%d,
                     `Store 1 Quarter New Customers Contacts`=%d,
                     `Store 1 Month New Customers Contacts`=%d,
                     `Store 1 Week New Customers Contacts`=%d,
                     `Store 1 Day New Customers Contacts`=%d
                     where `Store Key`=%d  ",
                     prepare_mysql( $this->data['Store Valid From']),
                     $this->data['Store Total Customer Contacts'],
                     $this->data['Store New Customer Contacts'],
                     $this->data['Store Total Customers'],
                     $this->data['Store Active Customers'],
                     $this->data['Store New Customers'],
                     $this->data['Store Lost Customers'],
                     $this->data['Store 1 Year New Customers'],
                     $this->data['Store 1 Quarter New Customers'],
                     $this->data['Store 1 Month New Customers'],
                     $this->data['Store 1 Week New Customers'],
                     $this->data['Store 1 Year Lost Customers'],
                     $this->data['Store 1 Quarter Lost Customers'],
                     $this->data['Store 1 Month Lost Customers'],
                     $this->data['Store 1 Week Lost Customers'],
                     $this->data['Store 1 Year New Customers Contacts'],
                     $this->data['Store 1 Quarter New Customers Contacts'],
                     $this->data['Store 1 Month New Customers Contacts'],
                     $this->data['Store 1 Week New Customers Contacts'],
                     $this->data['Store 1 Week New Customers Contacts'],
                     $this->id
                    );
        mysql_query($sql);
        //print "\n$sql\n";





    }


    function update_families() {
        $sql=sprintf("select count(*) as num from `Product Family Dimension`  where `Product Family Record Type` in ('New','Normal','Discontinuing') and  `Product Family Store Key`=%d",$this->id);
        //  print $sql;
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store Families']=$row['num'];
        }


        $sql=sprintf("update `Store Dimension` set `Store Families`=%d  where `Store Key`=%d  ",
                     $this->data['Store Families']

                     ,$this->id
                    );
        //  print "$sql\n";exit;
        mysql_query($sql);

    }

    function update_departments() {

        $sql=sprintf("select count(*) as num from `Product Department Dimension`  where  `Product Department Store Key`=%d",$this->id);
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store Departments']=$row['num'];
        }

        $sql=sprintf("update `Store Dimension` set `Store Departments`=%d  where `Store Key`=%d  ",

                     $this->data['Store Departments']
                     ,$this->id
                    );
        //print "$sql\n";
        mysql_query($sql);

    }

    function update_orders() {

        $this->data['Store Total Orders']=0;
        $this->data['Store Dispatched Orders']=0;
        $this->data['Store Cancelled Orders']=0;
        $this->data['Store Orders In Process']=0;
        $this->data['Store Unknown Orders']=0;
        $this->data['Store Suspended Orders']=0;

        $this->data['Store Total Invoices']=0;
        $this->data['Store Invoices']=0;
        $this->data['Store Refunds']=0;
        $this->data['Store Paid Invoices']=0;
        $this->data['Store Paid Refunds']=0;
        $this->data['Store Partially Paid Invoices']=0;
        $this->data['Store Partially Paid Refunds']=0;

        $this->data['Store Total Delivery Notes']=0;
        $this->data['Store Ready to Pick Delivery Notes']=0;
        $this->data['Store Picking Delivery Notes']=0;
        $this->data['Store Packing Delivery Notes']=0;
        $this->data['Store Ready to Dispatch Delivery Notes']=0;
        $this->data['Store Dispatched Delivery Notes']=0;
        $this->data['Store Cancelled Delivery Notes']=0;


        $this->data['Store Delivery Notes For Orders']=0;
        $this->data['Store Delivery Notes For Replacements']=0;
        $this->data['Store Delivery Notes For Samples']=0;
        $this->data['Store Delivery Notes For Donations']=0;
        $this->data['Store Delivery Notes For Shortages']=0;


        $sql="select count(*) as `Store Total Orders`,sum(IF(`Order Current Dispatch State`='Dispatched',1,0 )) as `Store Dispatched Orders` ,sum(IF(`Order Current Dispatch State`='Suspended',1,0 )) as `Store Suspended Orders`,sum(IF(`Order Current Dispatch State`='Cancelled',1,0 )) as `Store Cancelled Orders`,sum(IF(`Order Current Dispatch State`='Unknown',1,0 )) as `Store Unknown Orders` from `Order Dimension`   where `Order Store Key`=".$this->id;
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store Total Orders']=$row['Store Total Orders'];
            $this->data['Store Dispatched Orders']=$row['Store Dispatched Orders'];
            $this->data['Store Cancelled Orders']=$row['Store Cancelled Orders'];
            $this->data['Store Unknown Orders']=$row['Store Unknown Orders'];
            $this->data['Store Suspended Orders']=$row['Store Suspended Orders'];

            $this->data['Store Orders In Process']=  $this->data['Store Total Orders']- $this->data['Store Dispatched Orders']-$this->data['Store Cancelled Orders']-$this->data['Store Unknown Orders'];
        }

        $sql="select count(*) as `Store Total Invoices`,sum(IF(`Invoice Title`='Invoice',1,0 )) as `Store Invoices`,sum(IF(`Invoice Title`='Refund',1,0 )) as `Store Refunds` ,sum(IF(`Invoice Paid`='Yes' AND `Invoice Title`='Invoice',1,0 )) as `Store Paid Invoices`,sum(IF(`Invoice Paid`='Partially' AND `Invoice Title`='Invoice',1,0 )) as `Store Partially Paid Invoices`,sum(IF(`Invoice Paid`='Yes' AND `Invoice Title`='Refund',1,0 )) as `Store Paid Refunds`,sum(IF(`Invoice Paid`='Partially' AND `Invoice Title`='Refund',1,0 )) as `Store Partially Paid Refunds` from `Invoice Dimension`   where `Invoice Store Key`=".$this->id;
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store Total Invoices']=$row['Store Total Invoices'];
            $this->data['Store Invoices']=$row['Store Invoices'];
            $this->data['Store Paid Invoices']=$row['Store Paid Invoices'];
            $this->data['Store Partially Paid Invoices']=$row['Store Partially Paid Invoices'];
            $this->data['Store Refunds']=$row['Store Refunds'];
            $this->data['Store Paid Refunds']=$row['Store Paid Refunds'];
            $this->data['Store Partially Paid Refunds']=$row['Store Partially Paid Refunds'];
        }
        $sql="select count(*) as `Store Total Delivery Notes`,
             sum(IF(`Delivery Note State`='Cancelled'  or `Delivery Note State`='Cancelled to Restock' ,1,0 )) as `Store Returned Delivery Notes`,
             sum(IF(`Delivery Note State`='Ready to be Picked' ,1,0 )) as `Store Ready to Pick Delivery Notes`,
             sum(IF(`Delivery Note State`='Picking & Packing' or `Delivery Note State`='Picking' or `Delivery Note State`='Picker Assigned' or `Delivery Note State`='' ,1,0 )) as `Store Picking Delivery Notes`,
             sum(IF(`Delivery Note State`='Packing' or `Delivery Note State`='Packer Assigned' or `Delivery Note State`='Picked' ,1,0 )) as `Store Packing Delivery Notes`,
             sum(IF(`Delivery Note State`='Approved' or `Delivery Note State`='Packed' ,1,0 )) as `Store Ready to Dispatch Delivery Notes`,
             sum(IF(`Delivery Note State`='Dispatched' ,1,0 )) as `Store Dispatched Delivery Notes`,
             sum(IF(`Delivery Note Type`='Replacement & Shortages' or `Delivery Note Type`='Replacement' ,1,0 )) as `Store Delivery Notes For Replacements`,
             sum(IF(`Delivery Note Type`='Replacement & Shortages' or `Delivery Note Type`='Shortages' ,1,0 )) as `Store Delivery Notes For Shortages`,
             sum(IF(`Delivery Note Type`='Sample' ,1,0 )) as `Store Delivery Notes For Samples`,
             sum(IF(`Delivery Note Type`='Donation' ,1,0 )) as `Store Delivery Notes For Donations`,
             sum(IF(`Delivery Note Type`='Order' ,1,0 )) as `Store Delivery Notes For Orders`
             from `Delivery Note Dimension`   where `Delivery Note Store Key`=".$this->id;
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store Total Delivery Notes']=$row['Store Total Delivery Notes'];
            $this->data['Store Ready to Pick Delivery Notes']=$row['Store Ready to Pick Delivery Notes'];
            $this->data['Store Picking Delivery Notes']=$row['Store Picking Delivery Notes'];
            $this->data['Store Packing Delivery Notes']=$row['Store Packing Delivery Notes'];
            $this->data['Store Ready to Dispatch Delivery Notes']=$row['Store Ready to Dispatch Delivery Notes'];
            $this->data['Store Dispatched Delivery Notes']=$row['Store Dispatched Delivery Notes'];
            $this->data['Store Returned Delivery Notes']=$row['Store Returned Delivery Notes'];
            $this->data['Store Delivery Notes For Replacements']=$row['Store Delivery Notes For Replacements'];
            $this->data['Store Delivery Notes For Shortages']=$row['Store Delivery Notes For Shortages'];
            $this->data['Store Delivery Notes For Samples']=$row['Store Delivery Notes For Samples'];
            $this->data['Store Delivery Notes For Donations']=$row['Store Delivery Notes For Donations'];
            $this->data['Store Delivery Notes For Orders']=$row['Store Delivery Notes For Orders'];
        }

//print "$sql\n";

        $sql=sprintf("update `Store Dimension` set `Store Total Orders`=%d,`Store Suspended Orders`=%d,`Store Dispatched Orders`=%d,`Store Cancelled Orders`=%d,`Store Orders In Process`=%d,`Store Unknown Orders`=%d
                     ,`Store Total Invoices`=%d ,`Store Invoices`=%d ,`Store Refunds`=%d ,`Store Paid Invoices`=%d ,`Store Paid Refunds`=%d ,`Store Partially Paid Invoices`=%d ,`Store Partially Paid Refunds`=%d
                     ,`Store Total Delivery Notes`=%d,`Store Ready to Pick Delivery Notes`=%d,`Store Picking Delivery Notes`=%d,`Store Packing Delivery Notes`=%d,`Store Ready to Dispatch Delivery Notes`=%d,`Store Dispatched Delivery Notes`=%d,`Store Returned Delivery Notes`=%d
                     ,`Store Delivery Notes For Replacements`=%d,`Store Delivery Notes For Shortages`=%d,`Store Delivery Notes For Samples`=%d,`Store Delivery Notes For Donations`=%d,`Store Delivery Notes For Orders`=%d
                     where `Store Key`=%d",
                     $this->data['Store Total Orders'],
                     $this->data['Store Suspended Orders'],
                     $this->data['Store Dispatched Orders'],
                     $this->data['Store Cancelled Orders'],
                     $this->data['Store Orders In Process'],
                     $this->data['Store Unknown Orders'],
                     $this->data['Store Total Invoices'],
                     $this->data['Store Invoices'],
                     $this->data['Store Refunds'],
                     $this->data['Store Paid Invoices'],
                     $this->data['Store Paid Refunds'],
                     $this->data['Store Partially Paid Invoices'],
                     $this->data['Store Partially Paid Refunds'],
                     $this->data['Store Total Delivery Notes'],
                     $this->data['Store Ready to Pick Delivery Notes'],
                     $this->data['Store Picking Delivery Notes'],
                     $this->data['Store Picking Delivery Notes'],
                     $this->data['Store Ready to Dispatch Delivery Notes'],
                     $this->data['Store Dispatched Delivery Notes'],
                     $this->data['Store Returned Delivery Notes'],
                     $this->data['Store Delivery Notes For Replacements'],
                     $this->data['Store Delivery Notes For Shortages'],
                     $this->data['Store Delivery Notes For Samples'],
                     $this->data['Store Delivery Notes For Donations'],
                     $this->data['Store Delivery Notes For Orders'],
                     $this->id
                    );
//print $sql;
        mysql_query($sql);

    }

    function update_up_today_sales() {
        $this->update_sales_from_invoices('Today');
        $this->update_sales_from_invoices('Week To Day');
        $this->update_sales_from_invoices('Month To Day');
        $this->update_sales_from_invoices('Year To Day');
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

        $this->data["Store $db_interval Acc Invoiced Discount Amount"]=0;
        $this->data["Store $db_interval Acc Invoiced Amount"]=0;
        $this->data["Store $db_interval Acc Invoices"]=0;
        $this->data["Store $db_interval Acc Profit"]=0;
        $this->data["Store DC $db_interval Acc Invoiced Amount"]=0;
        $this->data["Store DC $db_interval Acc Invoiced Discount Amount"]=0;
        $this->data["Store DC $db_interval Acc Profit"]=0;

        $sql=sprintf("select count(*) as invoices,sum(`Invoice Items Discount Amount`) as discounts,sum(`Invoice Total Net Amount`) net  ,sum(`Invoice Total Profit`) as profit ,sum(`Invoice Items Discount Amount`*`Invoice Currency Exchange`) as dc_discounts,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) dc_net  ,sum(`Invoice Total Profit`*`Invoice Currency Exchange`) as dc_profit from `Invoice Dimension` where `Invoice Store Key`=%d and `Invoice Date`>=%s %s" ,
                     $this->id,
                     prepare_mysql($from_date),
                     ($to_date?sprintf('and `Invoice Date`<%s',prepare_mysql($to_date)):'')

                    );
        $result=mysql_query($sql);
//print "$sql\n";
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data["Store $db_interval Acc Invoiced Discount Amount"]=$row["discounts"];
            $this->data["Store $db_interval Acc Invoiced Amount"]=$row["net"];
            $this->data["Store $db_interval Acc Invoices"]=$row["invoices"];
            $this->data["Store $db_interval Acc Profit"]=$row["profit"];
            $this->data["Store DC $db_interval Acc Invoiced Amount"]=$row["dc_net"];
            $this->data["Store DC $db_interval Acc Invoiced Discount Amount"]=$row["dc_discounts"];
            $this->data["Store DC $db_interval Acc Profit"]=$row["dc_profit"];
        }

        $sql=sprintf("update `Store Dimension` set
                     `Store $db_interval Acc Invoiced Discount Amount`=%.2f,
                     `Store $db_interval Acc Invoiced Amount`=%.2f,
                     `Store $db_interval Acc Invoices`=%d,
                     `Store $db_interval Acc Profit`=%.2f
                     where `Store Key`=%d "
                     ,$this->data["Store $db_interval Acc Invoiced Discount Amount"]
                     ,$this->data["Store $db_interval Acc Invoiced Amount"]
                     ,$this->data["Store $db_interval Acc Invoices"]
                     ,$this->data["Store $db_interval Acc Profit"]
                     ,$this->id
                    );

        mysql_query($sql);
//print "$sql\n\n";
        $sql=sprintf("update `Store Default Currency` set
                     `Store DC $db_interval Acc Invoiced Discount Amount`=%.2f,
                     `Store DC $db_interval Acc Invoiced Amount`=%.2f,
                     `Store DC $db_interval Acc Profit`=%.2f
                     where `Store Key`=%d "
                     ,$this->data["Store DC $db_interval Acc Invoiced Discount Amount"]
                     ,$this->data["Store DC $db_interval Acc Invoiced Amount"]
                     ,$this->data["Store DC $db_interval Acc Profit"]
                     ,$this->id
                    );

        mysql_query($sql);



        if ($from_date_1yb) {
            $this->data["Store $db_interval Acc 1YB Invoices"]=0;
            $this->data["Store $db_interval Acc 1YB Invoiced Discount Amount"]=0;
            $this->data["Store $db_interval Acc 1YB Invoiced Amount"]=0;
            $this->data["Store $db_interval Acc 1YB Profit"]=0;
            $this->data["Store DC $db_interval Acc 1YB Invoiced Discount Amount"]=0;
            $this->data["Store DC $db_interval Acc 1YB Invoiced Amount"]=0;
            $this->data["Store DC $db_interval Acc 1YB Profit"]=0;

            $sql=sprintf("select count(*) as invoices,sum(`Invoice Items Discount Amount`) as discounts,sum(`Invoice Total Net Amount`) net  ,sum(`Invoice Total Profit`) as profit,sum(`Invoice Items Discount Amount`*`Invoice Currency Exchange`) as dc_discounts,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) dc_net  ,sum(`Invoice Total Profit`*`Invoice Currency Exchange`) as dc_profit from `Invoice Dimension` where `Invoice Store Key`=%d and `Invoice Date`>%s and `Invoice Date`<%s" ,
                         $this->id,
                         prepare_mysql($from_date_1yb),
                         prepare_mysql($to_1yb)
                        );
            // print "$sql\n\n";
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $this->data["Store $db_interval Acc 1YB Invoiced Discount Amount"]=$row["discounts"];
                $this->data["Store $db_interval Acc 1YB Invoiced Amount"]=$row["net"];
                $this->data["Store $db_interval Acc 1YB Invoices"]=$row["invoices"];
                $this->data["Store $db_interval Acc 1YB Profit"]=$row["profit"];
                $this->data["Store DC $db_interval Acc 1YB Invoiced Amount"]=$row["dc_net"];
                $this->data["Store DC $db_interval Acc 1YB Invoiced Discount Amount"]=$row["dc_discounts"];
                $this->data["Store DC $db_interval Acc 1YB Profit"]=$row["dc_profit"];
            }

            $sql=sprintf("update `Store Dimension` set
                         `Store $db_interval Acc 1YB Invoiced Discount Amount`=%.2f,
                         `Store $db_interval Acc 1YB Invoiced Amount`=%.2f,
                         `Store $db_interval Acc 1YB Invoices`=%.2f,
                         `Store $db_interval Acc 1YB Profit`=%.2f
                         where `Store Key`=%d "
                         ,$this->data["Store $db_interval Acc 1YB Invoiced Discount Amount"]
                         ,$this->data["Store $db_interval Acc 1YB Invoiced Amount"]
                         ,$this->data["Store $db_interval Acc 1YB Invoices"]
                         ,$this->data["Store $db_interval Acc 1YB Profit"]
                         ,$this->id
                        );

            mysql_query($sql);
            //print "$sql\n";
            $sql=sprintf("update `Store Default Currency` set
                         `Store DC $db_interval Acc 1YB Invoiced Discount Amount`=%.2f,
                         `Store DC $db_interval Acc 1YB Invoiced Amount`=%.2f,
                         `Store DC $db_interval Acc 1YB Profit`=%.2f
                         where `Store Key`=%d "
                         ,$this->data["Store DC $db_interval Acc 1YB Invoiced Discount Amount"]
                         ,$this->data["Store DC $db_interval Acc 1YB Invoiced Amount"]
                         ,$this->data["Store DC $db_interval Acc 1YB Profit"]
                         ,$this->id
                        );
// print "$sql\n";
            mysql_query($sql);
        }


    }


    function update_customer_activity_interval() {




        $losing_interval=false;


        $sigma_factor=3.2906;//99.9% value assuming normal distribution
        $sql="select count(*) as num,avg((`Customer Order Interval`)+($sigma_factor*`Customer Order Interval STD`)) as a from `Customer Dimension` where `Customer Orders`>2";
        $result2=mysql_query($sql);
        if ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)) {
            if ($row2['num']>30) {
                $this->data['Store Losing Customer Interval']=$row2['a'];
                $this->data['Store Lost Customer Interval']=$this->data['Store Losing Customer Interval']*4.0/3.0;
            }
        }

        if (!$losing_interval) {
            $losing_interval=5259487;
            $lost_interval=7889231;
        }
        $sql=sprintf("update `Store Dimension` set
                     `Store Losing Customer Interval`=%d,
                     `Store Lost Customer Interval`=%d
                     where `Store Key`=%d "
                     ,$this->data["Store Losing Customer Interval"]
                     ,$this->data["Store Lost Customer Interval"]

                     ,$this->id
                    );
//print "$sql\n";
        mysql_query($sql);

    }


    function update_store_sales_to_delete() {
        $on_sale_days=0;
        $current_from=strtotime($this->data['Store Valid From']);
//print "**** ".$this->data['Store Valid From']."\n";
        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as tto, sum(if(`Product Sales Type`='Public Sale',1,0)) as for_sale   from `Product Dimension` as P   where `Product Store Key`=".$this->id;



        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $from=strtotime($row['ffrom']);
            $_from=date("Y-m-d H:i:s",$from);


            if ($from<$current_from and $row['prods']>0 and $row['ffrom']!='') {
                $_from=date("Y-m-d H:i:s",$from);
                $this->data['Store Valid From']=$_from;

            }


            if ($row['for_sale']>0) {
                $to=strtotime('today');
                $_to=date("Y-m-d H:i:s");
            } else {
                $to=strtotime($row['tto']);
                $_to=date("Y-m-d H:i:s",$to);
            }
            $on_sale_days=($to-$from)/ (60 * 60 * 24);

            if ($row['prods']==0)
                $on_sale_days=0;

        }


//print "**** ".$this->data['Store Valid From']."\n";

        $sql="select    count(Distinct `Customer Key`)as customers , sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF   where `Store Key`=".$this->id;


        //print "$sql\n\n";
        // exit;
        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store Total Invoiced Gross Amount']=$row['gross'];
            $this->data['Store Total Invoiced Discount Amount']=$row['disc'];
            $this->data['Store Total Invoiced Amount']=$row['gross']-$row['disc'];

            $this->data['Store Total Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Store Total Quantity Ordered']=$row['ordered'];
            $this->data['Store Total Quantity Invoiced']=$row['invoiced'];
            $this->data['Store Total Quantity Delivered']=$row['delivered'];
            $this->data['Store Total Days On Sale']=$on_sale_days;
            //$this->data['Store Valid From']=$_from;
            $this->data['Store Valid To']=$_to;
            $this->data['Store Total Customers']=$row['customers'];

            $sql=sprintf("update `Store Dimension` set `Store Total Invoiced Gross Amount`=%s,`Store Total Invoiced Discount Amount`=%s,`Store Total Invoiced Amount`=%s,`Store Total Profit`=%s, `Store Total Quantity Ordered`=%s , `Store Total Quantity Invoiced`=%s,`Store Total Quantity Delivered`=%s ,`Store Total Days On Sale`=%f ,`Store Valid From`=%s,`Store Valid To`=%s ,`Store Total Customers`=%d where `Store Key`=%d "
                         ,prepare_mysql($this->data['Store Total Invoiced Gross Amount'])
                         ,prepare_mysql($this->data['Store Total Invoiced Discount Amount'])
                         ,prepare_mysql($this->data['Store Total Invoiced Amount'])

                         ,prepare_mysql($this->data['Store Total Profit'])
                         ,prepare_mysql($this->data['Store Total Quantity Ordered'])
                         ,prepare_mysql($this->data['Store Total Quantity Invoiced'])
                         ,prepare_mysql($this->data['Store Total Quantity Delivered'])
                         ,$on_sale_days
                         ,prepare_mysql($this->data['Store Valid From'])
                         ,prepare_mysql($this->data['Store Valid To'])
                         ,$this->data['Store Total Customers']

                         ,$this->id
                        );

            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");
        }
        //    print "$sql\n\n";
//    exit;
        // days on sale

        $on_sale_days=0;



        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`='Public Sale',1,0)) as for_sale   from `Product Dimension` as P   where `Product Store Key`=".$this->id;
        // print "$sql\n\n";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($row['prods']==0)
                $on_sale_days=0;
            else {


                if ($row['for_sale']>0)
                    $to=strtotime('today');
                else
                    $to=strtotime($row['to']);
                // print "*** ".$row['to']." T:$to  ".strtotime('today')."  ".strtotime('today -1 year')."  \n";
                // print "*** T:$to   ".strtotime('today -1 year')."  \n";
                if ($to>strtotime('today -1 year')) {
                    //print "caca";
                    $from=strtotime($row['ffrom']);
                    if ($from<strtotime('today -1 year'))
                        $from=strtotime('today -1 year');

                    //	    print "*** T:$to F:$from\n";
                    $on_sale_days=($to-$from)/ (60 * 60 * 24);
                } else {
                    //   print "pipi";
                    $on_sale_days=0;

                }
            }
        }



        //$sql="select sum(`Product 1 Year Acc Invoiced Gross Amount`) as net,sum(`Product 1 Year Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Year Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Year Acc Profit`)as profit ,sum(`Product 1 Year Acc Quantity Delivered`) as delivered,sum(`Product 1 Year Acc Quantity Ordered`) as ordered,sum(`Product 1 Year Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P  where `Product Store Key`=".$this->id;
        $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF   where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Store Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));

        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));

        $result=mysql_query($sql);
//print $sql;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $this->data['Store 1 Year Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Store 1 Year Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Store 1 Year Acc Invoiced Amount']=$row['gross']-$row['disc'];

            $this->data['Store 1 Year Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Store 1 Year Acc Quantity Ordered']=$row['ordered'];
            $this->data['Store 1 Year Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Store 1 Year Acc Quantity Delivered']=$row['delivered'];
            $this->data['Store 1 Year Acc Customers']=$row['customers'];
            $this->data['Store 1 Year Acc Invoices']=$row['invoices'];
            $this->data['Store 1 Year Acc Pending Orders']=$pending_orders;

            $sql=sprintf("update `Store Dimension` set `Store 1 Year Acc Invoiced Gross Amount`=%s,`Store 1 Year Acc Invoiced Discount Amount`=%s,`Store 1 Year Acc Invoiced Amount`=%s,`Store 1 Year Acc Profit`=%s, `Store 1 Year Acc Quantity Ordered`=%s , `Store 1 Year Acc Quantity Invoiced`=%s,`Store 1 Year Acc Quantity Delivered`=%s ,`Store 1 Year Acc Days On Sale`=%f ,`Store 1 Year Acc Customers`=%d,`Store 1 Year Acc Invoices`=%d,`Store 1 Year Acc Pending Orders`=%d   where `Store Key`=%d "
                         ,prepare_mysql($this->data['Store 1 Year Acc Invoiced Gross Amount'])
                         ,prepare_mysql($this->data['Store 1 Year Acc Invoiced Discount Amount'])
                         ,prepare_mysql($this->data['Store 1 Year Acc Invoiced Amount'])

                         ,prepare_mysql($this->data['Store 1 Year Acc Profit'])
                         ,prepare_mysql($this->data['Store 1 Year Acc Quantity Ordered'])
                         ,prepare_mysql($this->data['Store 1 Year Acc Quantity Invoiced'])
                         ,prepare_mysql($this->data['Store 1 Year Acc Quantity Delivered'])
                         ,$on_sale_days
                         ,$this->data['Store 1 Year Acc Customers']
                         ,$this->data['Store 1 Year Acc Invoices']
                         ,$this->data['Store 1 Year Acc Pending Orders']
                         ,$this->id
                        );
            //   print "$sql\n";


            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");
        }
        // exit;


// ----------------------------------------------------------start for 3 yr----------------------------------------------------------
        $on_sale_days=0;



        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`='Public Sale',1,0)) as for_sale   from `Product Dimension` as P   where `Product Store Key`=".$this->id;
        // print "$sql\n\n";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($row['prods']==0)
                $on_sale_days=0;
            else {


                if ($row['for_sale']>0)
                    $to=strtotime('today');
                else
                    $to=strtotime($row['to']);
                // print "*** ".$row['to']." T:$to  ".strtotime('today')."  ".strtotime('today -1 year')."  \n";
                // print "*** T:$to   ".strtotime('today -1 year')."  \n";
                if ($to>strtotime('today -3 year')) {
                    //print "caca";
                    $from=strtotime($row['ffrom']);
                    if ($from<strtotime('today -3 year'))
                        $from=strtotime('today -3 year');

                    //	    print "*** T:$to F:$from\n";
                    $on_sale_days=($to-$from)/ (60 * 60 * 24);
                } else {
                    //   print "pipi";
                    $on_sale_days=0;

                }
            }
        }



        //$sql="select sum(`Product 1 Year Acc Invoiced Gross Amount`) as net,sum(`Product 1 Year Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Year Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Year Acc Profit`)as profit ,sum(`Product 1 Year Acc Quantity Delivered`) as delivered,sum(`Product 1 Year Acc Quantity Ordered`) as ordered,sum(`Product 1 Year Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P  where `Product Store Key`=".$this->id;
        $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF   where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Store Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 year"))));

        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 year"))));
//print $sql;
        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store 3 Year Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Store 3 Year Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Store 3 Year Acc Invoiced Amount']=$row['gross']-$row['disc'];

            $this->data['Store 3 Year Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Store 3 Year Acc Quantity Ordered']=$row['ordered'];
            $this->data['Store 3 Year Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Store 3 Year Acc Quantity Delivered']=$row['delivered'];
            $this->data['Store 3 Year Acc Customers']=$row['customers'];
            $this->data['Store 3 Year Acc Invoices']=$row['invoices'];
            $this->data['Store 3 Year Acc Pending Orders']=$pending_orders;

            $sql=sprintf("update `Store Dimension` set `Store 3 Year Acc Invoiced Gross Amount`=%s,`Store 3 Year Acc Invoiced Discount Amount`=%s,`Store 3 Year Acc Invoiced Amount`=%s,`Store 3 Year Acc Profit`=%s, `Store 3 Year Acc Quantity Ordered`=%s , `Store 3 Year Acc Quantity Invoiced`=%s,`Store 3 Year Acc Quantity Delivered`=%s ,`Store 3 Year Acc Days On Sale`=%f ,`Store 3 Year Acc Customers`=%d,`Store 3 Year Acc Invoices`=%d,`Store 3 Year Acc Pending Orders`=%d   where `Store Key`=%d "
                         ,prepare_mysql($this->data['Store 3 Year Acc Invoiced Gross Amount'])
                         ,prepare_mysql($this->data['Store 3 Year Acc Invoiced Discount Amount'])
                         ,prepare_mysql($this->data['Store 3 Year Acc Invoiced Amount'])

                         ,prepare_mysql($this->data['Store 3 Year Acc Profit'])
                         ,prepare_mysql($this->data['Store 3 Year Acc Quantity Ordered'])
                         ,prepare_mysql($this->data['Store 3 Year Acc Quantity Invoiced'])
                         ,prepare_mysql($this->data['Store 3 Year Acc Quantity Delivered'])
                         ,$on_sale_days
                         ,$this->data['Store 3 Year Acc Customers']
                         ,$this->data['Store 3 Year Acc Invoices']
                         ,$this->data['Store 3 Year Acc Pending Orders']
                         ,$this->id
                        );
            // print "$sql\n";


            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");
        }
        // exit;
// -------------------------------------------------------end 3 yr-------------------------------------------------------------


//-------------------------------------------------------------start of year to day---------------------------------------------------
        $on_sale_days=0;
        if (!function_exists('YTD')) {
            function YTD() {
                $first_day_of_year = date('Y').'-01-01';
                $today = date('Y-m-d');
//$diff = abs(strtotime($today) - strtotime($first_day_of_year));
                $diff = abs((strtotime($today) - strtotime($first_day_of_year))/ (60 * 60 * 24));
//$years = floor($diff / (365*60*60*24));
//$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
//$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
//$yeartoday=$years." year ".$months." month ".$days." day";
//("%d years, %d months, %d days\n", $years, $months, $days);
//return $yeartoday;
                return $diff;
            }
        }
        $yeartoday=YTD();

        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`='Public Sale',1,0)) as for_sale   from `Product Dimension` as P   where `Product Store Key`=".$this->id;
        // print "$sql\n\n";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($row['prods']==0)
                $on_sale_days=0;
            else {


                if ($row['for_sale']>0)
                    $to=strtotime('today');
                else
                    $to=strtotime($row['to']);
                // print "*** ".$row['to']." T:$to  ".strtotime('today')."  ".strtotime('today -1 year')."  \n";
                // print "*** T:$to   ".strtotime('today -1 year')."  \n";
                if ($to>strtotime("today -$yeartoday day")) {

                    $from=strtotime($row['ffrom']);
                    if ($from<strtotime("today -$yeartoday day"))
                        $from=strtotime("today -$yeartoday day");

                    //	    print "*** T:$to F:$from\n";
                    $on_sale_days=($to-$from)/ (60 * 60 * 24);
                } else {
                    //   print "pipi";
                    $on_sale_days=0;

                }
            }
        }



        //$sql="select sum(`Product 1 Year Acc Invoiced Gross Amount`) as net,sum(`Product 1 Year Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Year Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Year Acc Profit`)as profit ,sum(`Product 1 Year Acc Quantity Delivered`) as delivered,sum(`Product 1 Year Acc Quantity Ordered`) as ordered,sum(`Product 1 Year Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P  where `Product Store Key`=".$this->id;
        $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF   where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Store Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- $yeartoday day"))));
//print $sql;
        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- $yeartoday day"))));

        $result=mysql_query($sql);
//print $sql;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store YearToDay Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Store YearToDay Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Store YearToDay Acc Invoiced Amount']=$row['gross']-$row['disc'];

            $this->data['Store YearToDay Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Store YearToDay Acc Quantity Ordered']=$row['ordered'];
            $this->data['Store YearToDay Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Store YearToDayr Acc Quantity Delivered']=$row['delivered'];
            $this->data['Store YearToDay Acc Customers']=$row['customers'];
            $this->data['Store YearToDay Acc Invoices']=$row['invoices'];
            $this->data['Store YearToDay Acc Pending Orders']=$pending_orders;

            $sql=sprintf("update `Store Dimension` set `Store YearToDay Acc Invoiced Gross Amount`=%s,`Store YearToDay Acc Invoiced Discount Amount`=%s,`Store YearToDay Acc Invoiced Amount`=%s,`Store YearToDay Acc Profit`=%s, `Store YearToDay Acc Quantity Ordered`=%s , `Store YearToDay Acc Quantity Invoiced`=%s,`Store YearToDay Acc Quantity Delivered`=%s ,`Store YearToDay Acc Days On Sale`=%f ,`Store YearToDay Acc Customers`=%d,`Store YearToDay Acc Invoices`=%d,`Store YearToDay Acc Pending Orders`=%d   where `Store Key`=%d "
                         ,prepare_mysql($this->data['Store YearToDay Acc Invoiced Gross Amount'])
                         ,prepare_mysql($this->data['Store YearToDay Acc Invoiced Discount Amount'])
                         ,prepare_mysql($this->data['Store YearToDay Acc Invoiced Amount'])

                         ,prepare_mysql($this->data['Store YearToDay Acc Profit'])
                         ,prepare_mysql($this->data['Store YearToDay Acc Quantity Ordered'])
                         ,prepare_mysql($this->data['Store YearToDay Acc Quantity Invoiced'])
                         ,prepare_mysql($this->data['Store YearToDay Acc Quantity Delivered'])
                         ,$on_sale_days
                         ,$this->data['Store YearToDay Acc Customers']
                         ,$this->data['Store YearToDay Acc Invoices']
                         ,$this->data['Store YearToDay Acc Pending Orders']
                         ,$this->id
                        );
            //  print "$sql\n";


            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");
        }
        // exit;

// -------------------------------------------------------end of year to day-------------------------------------------------------------

// ----------------------------------------------------------start for 6 month ----------------------------------------------------------
        $on_sale_days=0;



        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`='Public Sale',1,0)) as for_sale   from `Product Dimension` as P   where `Product Store Key`=".$this->id;
        // print "$sql\n\n";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($row['prods']==0)
                $on_sale_days=0;
            else {


                if ($row['for_sale']>0)
                    $to=strtotime('today');
                else
                    $to=strtotime($row['to']);
                // print "*** ".$row['to']." T:$to  ".strtotime('today')."  ".strtotime('today -1 year')."  \n";
                // print "*** T:$to   ".strtotime('today -1 year')."  \n";
                if ($to>strtotime('today -6 month')) {
                    //print "caca";
                    $from=strtotime($row['ffrom']);
                    if ($from<strtotime('today -6 month'))
                        $from=strtotime('today -6 month');

                    //	    print "*** T:$to F:$from\n";
                    $on_sale_days=($to-$from)/ (60 * 60 * 24);
                } else {
                    //   print "pipi";
                    $on_sale_days=0;

                }
            }
        }



        //$sql="select sum(`Product 1 Year Acc Invoiced Gross Amount`) as net,sum(`Product 1 Year Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Year Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Year Acc Profit`)as profit ,sum(`Product 1 Year Acc Quantity Delivered`) as delivered,sum(`Product 1 Year Acc Quantity Ordered`) as ordered,sum(`Product 1 Year Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P  where `Product Store Key`=".$this->id;
        $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF   where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Store Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 6 month"))));

        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 6 month"))));
//print $sql;
        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store 6 Month Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Store 6 Month Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Store 6 Month Acc Invoiced Amount']=$row['gross']-$row['disc'];

            $this->data['Store 6 Month Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Store 6 Month Acc Quantity Ordered']=$row['ordered'];
            $this->data['Store 6 Month Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Store 6 Month Acc Quantity Delivered']=$row['delivered'];
            $this->data['Store 6 Month Acc Customers']=$row['customers'];
            $this->data['Store 6 Month Acc Invoices']=$row['invoices'];
            $this->data['Store 6 Month Acc Pending Orders']=$pending_orders;

            $sql=sprintf("update `Store Dimension` set `Store 6 Month Acc Invoiced Gross Amount`=%s,`Store 6 Month Acc Invoiced Discount Amount`=%s,`Store 6 Month Acc Invoiced Amount`=%s,`Store 6 Month Acc Profit`=%s, `Store 6 Month Acc Quantity Ordered`=%s , `Store 6 Month Acc Quantity Invoiced`=%s,`Store 6 Month Acc Quantity Delivered`=%s ,`Store 6 Month Acc Days On Sale`=%f ,`Store 6 Month Acc Customers`=%d,`Store 6 Month Acc Invoices`=%d,`Store 6 Month Acc Pending Orders`=%d   where `Store Key`=%d "
                         ,prepare_mysql($this->data['Store 6 Month Acc Invoiced Gross Amount'])
                         ,prepare_mysql($this->data['Store 6 Month Acc Invoiced Discount Amount'])
                         ,prepare_mysql($this->data['Store 6 Month Acc Invoiced Amount'])

                         ,prepare_mysql($this->data['Store 6 Month Acc Profit'])
                         ,prepare_mysql($this->data['Store 6 Month Acc Quantity Ordered'])
                         ,prepare_mysql($this->data['Store 6 Month Acc Quantity Invoiced'])
                         ,prepare_mysql($this->data['Store 6 Month Acc Quantity Delivered'])
                         ,$on_sale_days
                         ,$this->data['Store 6 Month Acc Customers']
                         ,$this->data['Store 6 Month Acc Invoices']
                         ,$this->data['Store 6 Month Acc Pending Orders']
                         ,$this->id
                        );
            //  print "$sql\n";


            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");
        }
        // exit;
// -------------------------------------------------------end of 6 month-------------------------------------------------------------




        $on_sale_days=0;


        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`='Public Sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Store Key`=".$this->id;

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($row['prods']==0)
                $on_sale_days=0;
            else {


                if ($row['for_sale']>0)
                    $to=strtotime('today');
                else
                    $to=strtotime($row['to']);
                if ($to>strtotime('today -3 month')) {

                    $from=strtotime($row['ffrom']);
                    if ($from<strtotime('today -3 month'))
                        $from=strtotime('today -3 month');


                    $on_sale_days=($to-$from)/ (60 * 60 * 24);
                } else
                    $on_sale_days=0;
            }
        }

        //$sql="select sum(`Product 1 Quarter Acc Invoiced Amount`) as net,sum(`Product 1 Quarter Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Quarter Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Quarter Acc Profit`)as profit ,sum(`Product 1 Quarter Acc Quantity Delivered`) as delivered,sum(`Product 1 Quarter Acc Quantity Ordered`) as ordered,sum(`Product 1 Quarter Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P  where `Product Store Key`=".$this->id;
        $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF   where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Store Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 month"))));

        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 month"))));



        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store 1 Quarter Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Store 1 Quarter Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Store 1 Quarter Acc Invoiced Amount']=$row['gross']-$row['disc'];

            $this->data['Store 1 Quarter Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Store 1 Quarter Acc Quantity Ordered']=$row['ordered'];
            $this->data['Store 1 Quarter Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Store 1 Quarter Acc Quantity Delivered']=$row['delivered'];
            $this->data['Store 1 Quarter Acc Customers']=$row['customers'];
            $this->data['Store 1 Quarter Acc Invoices']=$row['invoices'];
            $this->data['Store 1 Quarter Acc Pending Orders']=$pending_orders;


            $sql=sprintf("update `Store Dimension` set `Store 1 Quarter Acc Invoiced Gross Amount`=%s,`Store 1 Quarter Acc Invoiced Discount Amount`=%s,`Store 1 Quarter Acc Invoiced Amount`=%s,`Store 1 Quarter Acc Profit`=%s, `Store 1 Quarter Acc Quantity Ordered`=%s , `Store 1 Quarter Acc Quantity Invoiced`=%s,`Store 1 Quarter Acc Quantity Delivered`=%s  ,`Store 1 Quarter Acc Days On Sale`=%f ,`Store 1 Quarter Acc Customers`=%d,`Store 1 Quarter Acc Invoices`=%d,`Store 1 Quarter Acc Pending Orders`=%d   where `Store Key`=%d "
                         ,prepare_mysql($this->data['Store 1 Quarter Acc Invoiced Gross Amount'])
                         ,prepare_mysql($this->data['Store 1 Quarter Acc Invoiced Discount Amount'])
                         ,prepare_mysql($this->data['Store 1 Quarter Acc Invoiced Amount'])

                         ,prepare_mysql($this->data['Store 1 Quarter Acc Profit'])
                         ,prepare_mysql($this->data['Store 1 Quarter Acc Quantity Ordered'])
                         ,prepare_mysql($this->data['Store 1 Quarter Acc Quantity Invoiced'])
                         ,prepare_mysql($this->data['Store 1 Quarter Acc Quantity Delivered'])
                         ,$on_sale_days
                         ,$this->data['Store 1 Quarter Acc Customers']
                         ,$this->data['Store 1 Quarter Acc Invoices']
                         ,$this->data['Store 1 Quarter Acc Pending Orders']
                         ,$this->id
                        );
            // print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");
        }







        $on_sale_days=0;

        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`='Public Sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Store Key`=".$this->id;
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($row['prods']==0)
                $on_sale_days=0;
            else {


                if ($row['for_sale']>0)
                    $to=strtotime('today');
                else
                    $to=strtotime($row['to']);
                if ($to>strtotime('today -1 month')) {

                    $from=strtotime($row['ffrom']);
                    if ($from<strtotime('today -1 month'))
                        $from=strtotime('today -1 month');


                    $on_sale_days=($to-$from)/ (60 * 60 * 24);
                } else
                    $on_sale_days=0;
            }
        }

        //$sql="select  sum(`Product 1 Month Acc Invoiced Amount`) as net,sum(`Product 1 Month Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Month Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Month Acc Profit`)as profit ,sum(`Product 1 Month Acc Quantity Delivered`) as delivered,sum(`Product 1 Month Acc Quantity Ordered`) as ordered,sum(`Product 1 Month Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P  where `Product Store Key`=".$this->id;
        $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF   where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Store Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));

        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));



        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store 1 Month Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Store 1 Month Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Store 1 Month Acc Invoiced Amount']=$row['gross']-$row['disc'];

            $this->data['Store 1 Month Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Store 1 Month Acc Quantity Ordered']=$row['ordered'];
            $this->data['Store 1 Month Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Store 1 Month Acc Quantity Delivered']=$row['delivered'];
            $this->data['Store 1 Month Acc Customers']=$row['customers'];
            $this->data['Store 1 Month Acc Invoices']=$row['invoices'];
            $this->data['Store 1 Month Acc Pending Orders']=$pending_orders;

            $sql=sprintf("update `Store Dimension` set `Store 1 Month Acc Invoiced Gross Amount`=%s,`Store 1 Month Acc Invoiced Discount Amount`=%s,`Store 1 Month Acc Invoiced Amount`=%s,`Store 1 Month Acc Profit`=%s, `Store 1 Month Acc Quantity Ordered`=%s , `Store 1 Month Acc Quantity Invoiced`=%s,`Store 1 Month Acc Quantity Delivered`=%s  ,`Store 1 Month Acc Days On Sale`=%f ,`Store 1 Month Acc Customers`=%d,`Store 1 Month Acc Invoices`=%d,`Store 1 Month Acc Pending Orders`=%d   where `Store Key`=%d "
                         ,prepare_mysql($this->data['Store 1 Month Acc Invoiced Gross Amount'])
                         ,prepare_mysql($this->data['Store 1 Month Acc Invoiced Discount Amount'])
                         ,prepare_mysql($this->data['Store 1 Month Acc Invoiced Amount'])

                         ,prepare_mysql($this->data['Store 1 Month Acc Profit'])
                         ,prepare_mysql($this->data['Store 1 Month Acc Quantity Ordered'])
                         ,prepare_mysql($this->data['Store 1 Month Acc Quantity Invoiced'])
                         ,prepare_mysql($this->data['Store 1 Month Acc Quantity Delivered'])
                         ,$on_sale_days
                         ,$this->data['Store 1 Month Acc Customers']
                         ,$this->data['Store 1 Month Acc Invoices']
                         ,$this->data['Store 1 Month Acc Pending Orders']
                         ,$this->id
                        );
            // print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");
        }



// ----------------------------------------------------------start for 10 days ----------------------------------------------------------
        $on_sale_days=0;



        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`='Public Sale',1,0)) as for_sale   from `Product Dimension` as P   where `Product Store Key`=".$this->id;
        // print "$sql\n\n";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($row['prods']==0)
                $on_sale_days=0;
            else {


                if ($row['for_sale']>0)
                    $to=strtotime('today');
                else
                    $to=strtotime($row['to']);
                // print "*** ".$row['to']." T:$to  ".strtotime('today')."  ".strtotime('today -1 year')."  \n";
                // print "*** T:$to   ".strtotime('today -1 year')."  \n";
                if ($to>strtotime('today -10 days')) {
                    //print "caca";
                    $from=strtotime($row['ffrom']);
                    if ($from<strtotime('today -10 days'))
                        $from=strtotime('today -10 days');

                    //	    print "*** T:$to F:$from\n";
                    $on_sale_days=($to-$from)/ (60 * 60 * 24);
                } else {
                    //   print "pipi";
                    $on_sale_days=0;

                }
            }
        }



        //$sql="select sum(`Product 1 Year Acc Invoiced Gross Amount`) as net,sum(`Product 1 Year Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Year Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Year Acc Profit`)as profit ,sum(`Product 1 Year Acc Quantity Delivered`) as delivered,sum(`Product 1 Year Acc Quantity Ordered`) as ordered,sum(`Product 1 Year Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P  where `Product Store Key`=".$this->id;
        $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF   where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Store Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 10 days"))));

        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 10 days"))));

        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store 10 Day Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Store 10 Day Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Store 10 Day Acc Invoiced Amount']=$row['gross']-$row['disc'];

            $this->data['Store 10 Day Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Store 10 Day Acc Quantity Ordered']=$row['ordered'];
            $this->data['Store 10 Day Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Store 10 Day Acc Quantity Delivered']=$row['delivered'];
            $this->data['Store 10 Day Acc Customers']=$row['customers'];
            $this->data['Store 10 Day Acc Invoices']=$row['invoices'];
            $this->data['Store 10 Day Acc Pending Orders']=$pending_orders;

            $sql=sprintf("update `Store Dimension` set `Store 10 Day Acc Invoiced Gross Amount`=%s,`Store 10 Day Acc Invoiced Discount Amount`=%s,`Store 10 Day Acc Invoiced Amount`=%s,`Store 10 Day Acc Profit`=%s, `Store 10 Day Acc Quantity Ordered`=%s , `Store 10 Day Acc Quantity Invoiced`=%s,`Store 10 Day Acc Quantity Delivered`=%s ,`Store 10 Day Acc Days On Sale`=%f ,`Store 10 Day Acc Customers`=%d,`Store 10 Day Acc Invoices`=%d,`Store 10 Day Acc Pending Orders`=%d   where `Store Key`=%d "
                         ,prepare_mysql($this->data['Store 10 Day Acc Invoiced Gross Amount'])
                         ,prepare_mysql($this->data['Store 10 Day Acc Invoiced Discount Amount'])
                         ,prepare_mysql($this->data['Store 10 Day Acc Invoiced Amount'])

                         ,prepare_mysql($this->data['Store 10 Day Acc Profit'])
                         ,prepare_mysql($this->data['Store 10 Day Acc Quantity Ordered'])
                         ,prepare_mysql($this->data['Store 10 Day Acc Quantity Invoiced'])
                         ,prepare_mysql($this->data['Store 10 Day Acc Quantity Delivered'])
                         ,$on_sale_days
                         ,$this->data['Store 10 Day Acc Customers']
                         ,$this->data['Store 10 Day Acc Invoices']
                         ,$this->data['Store 10 Day Acc Pending Orders']
                         ,$this->id
                        );
            //  print "$sql\n";


            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");
        }
        // exit;
// -------------------------------------------------------end of 10 days-------------------------------------------------------------
        $on_sale_days=0;
        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`='Public Sale',1,0)) as for_sale   from `Product Dimension` as P where `Product Store Key`=".$this->id;
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($row['prods']==0)
                $on_sale_days=0;
            else {


                if ($row['for_sale']>0)
                    $to=strtotime('today');
                else
                    $to=strtotime($row['to']);
                if ($to>strtotime('today -1 week')) {

                    $from=strtotime($row['ffrom']);
                    if ($from<strtotime('today -1 week'))
                        $from=strtotime('today -1 week');


                    $on_sale_days=($to-$from)/ (60 * 60 * 24);
                } else
                    $on_sale_days=0;
            }
        }


        //$sql="select sum(`Product 1 Week Acc Invoiced Amount`) as net,sum(`Product 1 Week Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Week Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Week Acc Profit`)as profit ,sum(`Product 1 Week Acc Quantity Delivered`) as delivered,sum(`Product 1 Week Acc Quantity Ordered`) as ordered,sum(`Product 1 Week Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P   where `Product Store Key`=".$this->id;

        $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF   where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Store Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));

        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross   ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced   from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));
        //	print $sql;
        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Store 1 Week Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Store 1 Week Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Store 1 Week Acc Invoiced Amount']=$row['gross']-$row['disc'];
            $this->data['Store 1 Week Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Store 1 Week Acc Quantity Ordered']=$row['ordered'];
            $this->data['Store 1 Week Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Store 1 Week Acc Quantity Delivered']=$row['delivered'];

            $this->data['Store 1 Week Acc Customers']=$row['customers'];
            $this->data['Store 1 Week Acc Invoices']=$row['invoices'];
            $this->data['Store 1 Week Acc Pending Orders']=$pending_orders;

            $sql=sprintf("update `Store Dimension` set `Store 1 Week Acc Invoiced Gross Amount`=%s,`Store 1 Week Acc Invoiced Discount Amount`=%s,`Store 1 Week Acc Invoiced Amount`=%s,`Store 1 Week Acc Profit`=%s, `Store 1 Week Acc Quantity Ordered`=%s , `Store 1 Week Acc Quantity Invoiced`=%s,`Store 1 Week Acc Quantity Delivered`=%s ,`Store 1 Week Acc Days On Sale`=%f  ,`Store 1 Week Acc Customers`=%d,`Store 1 Week Acc Invoices`=%d,`Store 1 Week Acc Pending Orders`=%d   where `Store Key`=%d "
                         ,prepare_mysql($this->data['Store 1 Week Acc Invoiced Gross Amount'])
                         ,prepare_mysql($this->data['Store 1 Week Acc Invoiced Discount Amount'])
                         ,prepare_mysql($this->data['Store 1 Week Acc Invoiced Amount'])
                         ,prepare_mysql($this->data['Store 1 Week Acc Profit'])
                         ,prepare_mysql($this->data['Store 1 Week Acc Quantity Ordered'])
                         ,prepare_mysql($this->data['Store 1 Week Acc Quantity Invoiced'])
                         ,prepare_mysql($this->data['Store 1 Week Acc Quantity Delivered'])
                         ,$on_sale_days
                         ,$this->data['Store 1 Week Acc Customers']
                         ,$this->data['Store 1 Week Acc Invoices']
                         ,$this->data['Store 1 Week Acc Pending Orders']
                         ,$this->id
                        );
            // print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");

        }



    }
    function update_sales_default_currency_to_delete() {
        $this->data_default_currency=array();
        $this->data_default_currency['Store DC Total Invoiced Gross Amount']=0;
        $this->data_default_currency['Store DC Total Invoiced Discount Amount']=0;
        $this->data_default_currency['Store DC Total Invoiced Amount']=0;
        $this->data_default_currency['Store DC Total Profit']=0;
// ----------------------------------start for 3 yr, yeartoday, 6m, 10 days-----------------------------
        $this->data_default_currency['Store DC 3 Year Acc Invoiced Gross Amount']=0;
        $this->data_default_currency['Store DC 3 Year Acc Invoiced Discount Amount']=0;
        $this->data_default_currency['Store DC 3 Year Acc Invoiced Amount']=0;
        $this->data_default_currency['Store DC 3 Year Acc Profit']=0;

        $this->data_default_currency['Store DC YearToDay Acc Invoiced Gross Amount']=0;
        $this->data_default_currency['Store DC YearToDay Acc Invoiced Discount Amount']=0;
        $this->data_default_currency['Store DC YearToDay Acc Invoiced Amount']=0;
        $this->data_default_currency['Store DC YearToDay Acc Profit']=0;

        $this->data_default_currency['Store DC 6 Month Acc Invoiced Gross Amount']=0;
        $this->data_default_currency['Store DC 6 Month Acc Invoiced Discount Amount']=0;
        $this->data_default_currency['Store DC 6 Month Acc Invoiced Amount']=0;
        $this->data_default_currency['Store DC 6 Month Acc Profit']=0;

        $this->data_default_currency['Store DC 10 Day Acc Invoiced Gross Amount']=0;
        $this->data_default_currency['Store DC 10 Day Acc Invoiced Discount Amount']=0;
        $this->data_default_currency['Store DC 10 Day Acc Invoiced Amount']=0;
        $this->data_default_currency['Store DC 10 Day Acc Profit']=0;
// --------------------------------- end for 3 yr, yeartoday, 6m, 10 days-------------------------------------
        $this->data_default_currency['Store DC 1 Year Acc Invoiced Gross Amount']=0;
        $this->data_default_currency['Store DC 1 Year Acc Invoiced Discount Amount']=0;
        $this->data_default_currency['Store DC 1 Year Acc Invoiced Amount']=0;
        $this->data_default_currency['Store DC 1 Year Acc Profit']=0;
        $this->data_default_currency['Store DC 1 Quarter Acc Invoiced Discount Amount']=0;
        $this->data_default_currency['Store DC 1 Quarter Acc Invoiced Amount']=0;
        $this->data_default_currency['Store DC 1 Quarter Acc Profit']=0;
        $this->data_default_currency['Store DC 1 Month Acc Invoiced Gross Amount']=0;
        $this->data_default_currency['Store DC 1 Month Acc Invoiced Discount Amount']=0;
        $this->data_default_currency['Store DC 1 Month Acc Invoiced Amount']=0;
        $this->data_default_currency['Store DC 1 Month Acc Profit']=0;
        $this->data_default_currency['Store DC 1 Week Acc Invoiced Gross Amount']=0;
        $this->data_default_currency['Store DC 1 Week Acc Invoiced Discount Amount']=0;
        $this->data_default_currency['Store DC 1 Week Acc Invoiced Amount']=0;
        $this->data_default_currency['Store DC 1 Week Acc Profit']=0;



        $sql="select     sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc  from `Order Transaction Fact`  OTF   where `Store Key`=".$this->id;


        // print "$sql\n\n";
        //  exit;
        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data_default_currency['Store DC Total Invoiced Gross Amount']=$row['gross'];
            $this->data_default_currency['Store DC Total Invoiced Discount Amount']=$row['disc'];
            $this->data_default_currency['Store DC Total Invoiced Amount']=$row['gross']-$row['disc'];
            $this->data_default_currency['Store DC Total Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

        }
// ----------------------------------------------------------------strt for 3 yr------------------------------------------------
        $sql=sprintf("select  sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc
                     from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 year"))));

        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data_default_currency['Store DC 3 Year Acc Invoiced Gross Amount']=$row['gross'];
            $this->data_default_currency['Store DC 3 Year Acc Invoiced Discount Amount']=$row['disc'];
            $this->data_default_currency['Store DC 3 Year Acc Invoiced Amount']=$row['gross']-$row['disc'];
            $this->data_default_currency['Store DC 3 Year Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

        }

// ----------------------------------------------------------------end for 3 yr----------------------------------------------------
        $sql=sprintf("select  sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc
                     from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));

        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data_default_currency['Store DC 1 Year Acc Invoiced Gross Amount']=$row['gross'];
            $this->data_default_currency['Store DC 1 Year Acc Invoiced Discount Amount']=$row['disc'];
            $this->data_default_currency['Store DC 1 Year Acc Invoiced Amount']=$row['gross']-$row['disc'];
            $this->data_default_currency['Store DC 1 Year Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

        }


// ----------------------------------------------------------------strt for yeartoday------------------------------------------------
        $yeartoday=YTD();
        $sql=sprintf("select  sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc
                     from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- $yeartoday"))));

        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data_default_currency['Store DC YearToDay Acc Invoiced Gross Amount']=$row['gross'];
            $this->data_default_currency['Store DC YearToDay Acc Invoiced Discount Amount']=$row['disc'];
            $this->data_default_currency['Store DC YearToDay Acc Invoiced Amount']=$row['gross']-$row['disc'];
            $this->data_default_currency['Store DC YearToDay Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

        }

// ----------------------------------------------------------------end for yeartoday----------------------------------------------------

// ----------------------------------------------------------------strt for 6 month------------------------------------------------
        $sql=sprintf("select  sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc
                     from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 6 month"))));

        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data_default_currency['Store DC 6 Month Acc Invoiced Gross Amount']=$row['gross'];
            $this->data_default_currency['Store DC 6 Month Acc Invoiced Discount Amount']=$row['disc'];
            $this->data_default_currency['Store DC 6 Month Acc Invoiced Amount']=$row['gross']-$row['disc'];
            $this->data_default_currency['Store DC 6 Month Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

        }

// ----------------------------------------------------------------end for 6 month----------------------------------------------------
        $sql=sprintf("select   sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc  from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 month"))));
        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data_default_currency['Store DC 1 Quarter Acc Invoiced Gross Amount']=$row['gross'];
            $this->data_default_currency['Store DC 1 Quarter Acc Invoiced Discount Amount']=$row['disc'];
            $this->data_default_currency['Store DC 1 Quarter Acc Invoiced Amount']=$row['gross']-$row['disc'];
            $this->data_default_currency['Store DC 1 Quarter Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

        }



        $sql=sprintf("select    sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross  ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc    from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));



        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data_default_currency['Store DC 1 Month Acc Invoiced Gross Amount']=$row['gross'];
            $this->data_default_currency['Store DC 1 Month Acc Invoiced Discount Amount']=$row['disc'];
            $this->data_default_currency['Store DC 1 Month Acc Invoiced Amount']=$row['gross']-$row['disc'];
            $this->data_default_currency['Store DC 1 Month Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

        }

// ----------------------------------------------------------------strt for 10 days------------------------------------------------
        $sql=sprintf("select  sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc
                     from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 10 day"))));
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data_default_currency['Store DC 10 Day Acc Invoiced Gross Amount']=$row['gross'];
            $this->data_default_currency['Store DC 10 Day Acc Invoiced Discount Amount']=$row['disc'];
            $this->data_default_currency['Store DC 10 Day Acc Invoiced Amount']=$row['gross']-$row['disc'];
            $this->data_default_currency['Store DC 10 Day Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
        }
// ----------------------------------------------------------------end for 10 days----------------------------------------------------

        $sql=sprintf("select  sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross   ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc    from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));
        //	print $sql;
        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data_default_currency['Store DC 1 Week Acc Invoiced Gross Amount']=$row['gross'];
            $this->data_default_currency['Store DC 1 Week Acc Invoiced Discount Amount']=$row['disc'];
            $this->data_default_currency['Store DC 1 Week Acc Invoiced Amount']=$row['gross']-$row['disc'];
            $this->data_default_currency['Store DC 1 Week Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

        }

        $insert_values='';
        $update_values='';
        foreach($this->data_default_currency as $key=>$value) {
            $insert_values.=sprintf(',%.2f',$value);
            $update_values.=sprintf(',`%s`=%.2f',addslashes($key),$value);
        }
        $insert_values=preg_replace('/^,/','',$insert_values);
        $update_values=preg_replace('/^,/','',$update_values);


        $sql=sprintf('Insert into `Store Default Currency` values (%d,%s) ON DUPLICATE KEY UPDATE %s  ',$this->id,$insert_values,$update_values);
        mysql_query($sql);
        //print "$sql\n";



    }


    function create_site($data) {


        $data['Site Store Key']=$this->id;
        $data['Site Name']=$this->data['Store Name'];



        $site=new Site('new',$data);
        return $site;
    }


    function get_active_sites_keys() {
        $sql=sprintf("select `Site Key` from `Site Dimension` where `Site Store Key`=%d and `Site Active`='Yes' ",$this->id);

        $res=mysql_query($sql);
        $sites=array();
        while ($row=mysql_fetch_assoc($res)) {
            $sites[$row['Site Key']]=$row['Site Key'];
        }
//print "$sql\n";
//print_r($sites);
        return $sites;
    }





    function get_page_data() {
        $data=array();
        $sql=sprintf("select * from `Page Store Dimension` PSD left join `Page Dimension` PD on (PSD.`Page Key`=PD.`Page Key`) where PSD.`Page Key`=%d",$this->data['Store Page Key']);
        print "$sql\n";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_assoc($res)) {
            $data=$row;
            $data['Page Options']=unserialize($data['Page Options']);
        }



        return $data;

    }

}
