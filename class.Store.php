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
        case('Potential Customers'):
            return number($this->data['Store Active Contacts']-$this->data['Store Active Contacts With Orders']);
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
        case('Percentage Active Contacts'):
            return percentage($this->data['Store Active Contacts'],$this->data['Store Contacts']);
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
        case('address'):
            $this->update_field('Store Address',$a1);
            break;
        case('marketing_description'):
            $this->update_field('Short Marketing Description',$a1);
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


    function update_email_campaign_data() {
        $sql=sprintf("select count(*) as email_campaign from `Email Campaign Dimension` where `Email Campaign Store Key`=%d  ",$this->id);

        $res=mysql_query($sql);
        $sites=array();
        while ($row=mysql_fetch_assoc($res)) {
            $email_campaign=$row['email_campaign'];
        }

        $sql=sprintf('update `Store Dimension` set `Store Email Campaigns`=%d where `Store Key`=%d',
                     $email_campaign,
                     $this->id
                    );

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
        //  print "$sql\n";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_assoc($res)) {
            $data=$row;
            $data['Page Options']=unserialize($data['Page Options']);
        }



        return $data;

    }

    function get_email_credentials_data($type) {
        $credentials=array();
        $sql=sprintf("select * from `Email Credentials Dimension` C left join `Email Credentials Store Bridge` SB on (SB.`Email Credentials Key`=C.`Email Credentials Key`) left join `Email Credentials Scope Bridge`  SCB  on (SCB.`Email Credentials Key`=C.`Email Credentials Key`)    where   `Scope`=%s and `Store Key`=%d ",
                     prepare_mysql($type),
                     $this->id
                    );
                    
        $res=mysql_query($sql);
        while ($row=mysql_fetch_assoc($res)) {
            $credentials[$row['Email Credentials Key ']]=$row;
        }


        return $credentials;

    }


function get_formated_email_credentials($type){

    $credentials=$this->get_email_credentials_data($type);
   
    $formated_credentials='';
    foreach($credentials as $credential){
        $formated_credentials.=','.$credential['Email Address'];
    }
    
    $formated_credentials=preg_replace('/^,/','',$formated_credentials);
    return $formated_credentials;
    

}


function get_email_credential_key($type){

 $sql=sprintf("select C.`Email Credentials Key` from `Email Credentials Dimension` C left join `Email Credentials Store Bridge` SB on (SB.`Email Credentials Key`=C.`Email Credentials Key`) left join `Email Credentials Scope Bridge`  SCB  on (SCB.`Email Credentials Key`=C.`Email Credentials Key`)    where   `Scope`=%s and `Store Key`=%d ",
                     prepare_mysql($type),
                     $this->id
                    );
                    
        $res=mysql_query($sql);
        if ($row=mysql_fetch_assoc($res)) {
            return $row['Email Credentials Key'];
        }else{
        
            return false;
        }


}


}
