<?php
/*
 File: Department.php

 This file contains the Department Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('class.Family.php');
include_once('class.Page.php');

/* class: Department
   Class to manage the *Product Department Dimension* table
*/


class Department extends DB_Table {

public $new_value=false;

    /*
      Constructor: Department
      Initializes the class, trigger  Search/Load/Create for the data set

      Returns:
      void
    */
var $external_DB_link=false;
    function Department ($a1=false,$a2=false,$a3=false) {
        $this->table_name='Product Department';
        $this->ignore_fields=array(
                                 'Product Department Key',
                                 'Product Department Families',
                                 'Product Department For Sale Products',
                                 'Product Department In Process Products',
                                 'Product Department Not For Sale Products',
                                 'Product Department Discontinued Products',
                                 'Product Department Unknown Sales State Products',
                                 'Product Department Surplus Availability Products',
                                 'Product Department Optimal Availability Products',
                                 'Product Department Low Availability Products',
                                 'Product Department Critical Availability Products',
                                 'Product Department Out Of Stock Products',
                                 'Product Department Unknown Stock Products',
                                 'Product Department Total Invoiced Gross Amount',
                                 'Product Department Total Invoiced Discount Amount',
                                 'Product Department Total Invoiced Amount',
                                 'Product Department Total Profit',
                                 'Product Department Total Quantity Ordered',
                                 'Product Department Total Quantity Invoiced',
                                 'Product Department Total Quantity Delivere',
                                 'Product Department Total Days On Sale',
                                 'Product Department Total Days Available',
                                 'Product Department 1 Year Acc Invoiced Gross Amount',
                                 'Product Department 1 Year Acc Invoiced Discount Amount',
                                 'Product Department 1 Year Acc Invoiced Amount',
                                 'Product Department 1 Year Acc Profit',
                                 'Product Department 1 Year Acc Quantity Ordered',
                                 'Product Department 1 Year Acc Quantity Invoiced',
                                 'Product Department 1 Year Acc Quantity Delivere',
                                 'Product Department 1 Year Acc Days On Sale',
                                 'Product Department 1 Year Acc Days Available',
                                 'Product Department 1 Quarter Acc Invoiced Gross Amount',
                                 'Product Department 1 Quarter Acc Invoiced Discount Amount',
                                 'Product Department 1 Quarter Acc Invoiced Amount',
                                 'Product Department 1 Quarter Acc Profit',
                                 'Product Department 1 Quarter Acc Quantity Ordered',
                                 'Product Department 1 Quarter Acc Quantity Invoiced',
                                 'Product Department 1 Quarter Acc Quantity Delivere',
                                 'Product Department 1 Quarter Acc Days On Sale',
                                 'Product Department 1 Quarter Acc Days Available',
                                 'Product Department 1 Month Acc Invoiced Gross Amount',
                                 'Product Department 1 Month Acc Invoiced Discount Amount',
                                 'Product Department 1 Month Acc Invoiced Amount',
                                 'Product Department 1 Month Acc Profit',
                                 'Product Department 1 Month Acc Quantity Ordered',
                                 'Product Department 1 Month Acc Quantity Invoiced',
                                 'Product Department 1 Month Acc Quantity Delivere',
                                 'Product Department 1 Month Acc Days On Sale',
                                 'Product Department 1 Month Acc Days Available',
                                 'Product Department 1 Week Acc Invoiced Gross Amount',
                                 'Product Department 1 Week Acc Invoiced Discount Amount',
                                 'Product Department 1 Week Acc Invoiced Amount',
                                 'Product Department 1 Week Acc Profit',
                                 'Product Department 1 Week Acc Quantity Ordered',
                                 'Product Department 1 Week Acc Quantity Invoiced',
                                 'Product Department 1 Week Acc Quantity Delivere',
                                 'Product Department 1 Week Acc Days On Sale',
                                 'Product Department 1 Week Acc Days Available',
                                 'Product Department Total Quantity Delivered',
                                 'Product Department 1 Year Acc Quantity Delivered',
                                 'Product Department 1 Month Acc Quantity Delivered',
                                 'Product Department 1 Quarter Acc Quantity Delivered',
                                 'Product Department 1 Week Acc Quantity Delivered',
                                 'Product Department Stock Value'


                             );

        if (is_numeric($a1) and !$a2  and $a1>0 )
            $this->get_data('id',$a1,false);
        else if ( preg_match('/new|create/i',$a1)) {
            $this->find($a2,'create');
        } else if ( preg_match('/find/i',$a1)) {
            $this->find($a2,$a3);
        }
        elseif($a2!='')
        $this->get_data($a1,$a2,$a3);

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
        $create=false;
        $update=false;
        if (preg_match('/create/i',$options)) {
            $create=true;
        }
        if (preg_match('/update/i',$options)) {
            $update=true;
        }

        $data=$this->base_data();
        foreach($raw_data as $key=>$value) {
            if (array_key_exists($key,$data))
                $data[$key]=_trim($value);
        }



        if ($data['Product Department Code']=='' ) {
            $this->msg=_("Error: Wrong department code");
            $this->error=true;
            return;
        }

        if ($data['Product Department Name']=='') {
            $data['Product Department Name']=$data['Product Department Code'];
            $this->msg=_("Warning: No department name");
        }

        if ( !is_numeric($data['Product Department Store Key']) or $data['Product Department Store Key']<=0 ) {
            $this->error=true;
            $this->msg=_("Error: Incorrect Store Key");
            return;
        }
        $sql=sprintf("select `Product Department Key`from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Code`=%s "
                     ,$data['Product Department Store Key']
                     ,prepare_mysql($data['Product Department Code'])
                    );
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $this->found=true;
            $this->found_key=$row['Product Department Key'];

        }

        if ($this->found)
            $this->get_data('id',$this->found_key);

        if (!$this->found & $create) {
            $this->create($data);
        } else if ($create) {
            $this->msg=_('There is already another department with this code');

        }









    }


    /*
      Function: create
      Crea nuevos registros en la tabla product department dimension, evitando duplicidad de registros.
    */
    // JFA

    function create($data) {



        $this->new=false;

        if ($data['Product Department Name']!='')
            $data['Product Department Name']=$this->name_if_duplicated($data);

        $store=new Store($data['Product Department Store Key']);
        if (!$store->id) {
            $this->error=true;
            exit("error store not ".$data['Product Department Store Key']." found\n");
        }

        $data['Product Department Store Code']=$store->data['Store Code'];
	$data['Product Department Currency Code']=$store->data['Store Currency Code'];

        $keys='(';
        $values='values(';
        foreach($data as $key=>$value) {
	  $keys.="`$key`,";
	  if(preg_match('/Product Department Description|Marketing|Slogan/i',$key))
	    $values.=prepare_mysql($value,false).",";

	  else
	    $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Product Department Dimension` %s %s",$keys,$values);

	//   print "$sql\n";
        if (mysql_query($sql)) {
            $this->id = mysql_insert_id();
            $this->msg=_("Department Added");
            $this->get_data('id',$this->id,false);
            $this->new=true;
	    
	    $this->add_history(array(
				     'Action'=>'created'
				     ,'History Abstract'=>_('Department Created')
				     ,'History Details'=>_('Department')." ".$this->data['Product Department Name']." (".$this->get('Product Department Code').") "._('Created')
				     ));

          
            $store->update_departments();
            return;
        } else {
	  $this->error=true;
	  $this->msg=_("$sql Error can not create department");

        }

    }

    /*
       Method: get_data
       Obtiene los datos de la tabla Product Department Dimension de acuerdo al Id, al codigo o al code_store.
    */
// JFA

    function get_data($tipo,$tag,$tag2=false) {

        switch ($tipo) {
        case('id'):
            $sql=sprintf("select * from `Product Department Dimension` where `Product Department Key`=%d ",$tag);
            break;
        case('code'):
            $sql=sprintf("select * from `Product Department Dimension` where `Product Department Code`=%s and `Product Department Most Recent`='Yes'",prepare_mysql($tag));
        case('code_store'):
            $sql=sprintf("select * from `Product Department Dimension` where `Product Department Code`=%s and `Product Department Most Recent`='Yes' and `Product Department Store Key`=%d",prepare_mysql($tag),$tag2);

            break;
        default:
            $sql=sprintf("select * from `Product Department Dimension` where `Product Department Type`='Unknown' ");
        }
        

        $result=mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
            $this->id=$this->data['Product Department Key'];

    }

 function update_sales_type($value) {
    if (
        $value=='Public Sale' or $value=='Private Sale' or $value=='Not For Sale'
    ) {
        $sales_state=$value;

        $sql=sprintf("update `Product Department Dimension` set `Product Department Sales Type`=%s  where  `Product Department Key`=%d "
                     ,prepare_mysql($sales_state)
                     ,$this->id
                    );
        //print $sql;
        if (mysql_query($sql)) {
            if ($this->external_DB_link)mysql_query($sql,$this->external_DB_link);
            $this->msg=_('Department Sales Type updated');
            $this->updated=true;

            $this->new_value=$value;
            return;
        } else {
            $this->msg=_("Error: Department sales type could not be updated ");
            $this->updated=false;
            return;
        }
    } else
        $this->msg=_("Error: wrong value")." [Sales Type] ($value)";
    $this->updated=false;
}


    function update($key,$a1=false,$a2=false) {
        $this->updated=false;
        $this->msg='Nothing to change';

        switch ($key) {
        case('sales_type'):
        $this->update_sales_type($a1);
        break;
        case('code'):

            if ($a1==$this->data['Product Department Code']) {
                $this->updated=true;
                $this->new_value=$a1;
                return;

            }

            if ($a1=='') {
                $this->msg=_('Error: Wrong code (empty)');
                return;
            }

            if (!(strtolower($a1)==strtolower($this->data['Product Department Code']) and $a1!=$this->data['Product Department Code'])) {

                $sql=sprintf("select count(*) as num from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Code`=%s  COLLATE utf8_general_ci"
                             ,$this->data['Product Department Store Key']
                             ,prepare_mysql($a1)
                            );
                $res=mysql_query($sql);
                $row=mysql_fetch_array($res);
                if ($row['num']>0) {
                    $this->msg=_("Error: Another department with the same code");
                    return;
                }
            }
            $old_value=$this->get('Product Department Code');
            $sql=sprintf("update `Product Department Dimension` set `Product Department Code`=%s where `Product Department Key`=%d "
                         ,prepare_mysql($a1)
                         ,$this->id
                        );
            if (mysql_query($sql)) {
                $this->msg=_('Department code updated');
                $this->updated=true;
                $this->new_value=$a1;

                $this->data['Product Department Code']=$a1;
                $editor_data=$this->get_editor_data();

		
		$this->add_history(array(
				 'Indirect Object'=>'Product Department Code'
				 ,'History Abstract'=>_('Product Department Changed').' ('.$this->get('Product Department Name').')'
				 ,'History Details'=>_('Store')." ".$this->data['Product Department Name']." "._('code changed from').' '.$old_value." "._('to').' '. $this->get('Product Department Code')
				 ));




            } else {
                $this->msg=_("Error: Department code could not be updated");

                $this->updated=false;

            }
            break;

        case('name'):

            if ($a1==$this->data['Product Department Name']) {
                $this->updated=true;
                $this->new_value=$a1;
                return;

            }

            if ($a1=='') {
                $this->msg=_('Error: Wrong name (empty)');
                return;
            }
            $sql=sprintf("select count(*) as num from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Name`=%s  COLLATE utf8_general_ci"
                         ,$this->data['Product Department Store Key']
                         ,prepare_mysql($a1)
                        );
            $res=mysql_query($sql);
            $row=mysql_fetch_array($res);
            if ($row['num']>0) {
                $this->msg=_("Error: Another department with the same name");
                return;
            }
            $old_value=$this->get('Product Department Name');
            $sql=sprintf("update `Product Department Dimension` set `Product Department Name`=%s where `Product Department Key`=%d "
                         ,prepare_mysql($a1)
                         ,$this->id
                        );
            if (mysql_query($sql)) {
                $this->msg=_('Department name updated');
                $this->updated=true;
                $this->new_value=$a1;
                $this->data['Product Department Name']=$a1;


		$this->add_history(array(
					 'Indirect Object'=>'Product Department Name'
					 ,'History Abstract'=>_('Product Department Name Changed').' ('.$this->get('Product Department Name').')'
					 ,'History Details'=>_('Product Department')." ("._('Code').":".$this->data['Product Department Code'].") "._('name changed from').' '.$old_value." "._('to').' '. $this->get('Product Department Name')
					 ));

             

            } else {
                $this->msg=_("Error: Department name could not be updated");

                $this->updated=false;

            }
            break;
        }
    }

    /*
        Function: delete
        Funcion que permite eliminar registros en la tabla Product Department Dimension, cuidando la integridad referencial con los productos.
    */
// JFA
    function delete() {
        $this->deleted=false;
        $this->load('products_info');

        if ($this->get('Total Products')==0) {
            $store=new Store($this->data['Product Department Store Key']);
            $sql=sprintf("delete from `Product Department Dimension` where `Product Department Key`=%d",$this->id);
            if (mysql_query($sql)) {

                $this->deleted=true;

            } else {

                $this->msg=_('Error: can not delete department');
                return;
            }

            $this->deleted=true;
        } else {
            $this->msg=_('Department can not be deleted because it has associated some products');

        }
    }

    /*
        Method: load
        Carga datos de la base de datos Product Dimension, Product Department Bridge, Product Family Dimension, Product Family Department Bridge, actualizando registros en la tabla Product Department Dimension
    */
// JFA

    function load($tipo,$args=false) {
        switch ($tipo) {

        case('families'):
            $sql=sprintf("select * from `Product Family Dimension` PFD  left join `Product Family Department Bridge` as B on (B.`Product Family Key`=PFD.`Product Family Key`) where `Product Deparment Key`=%d",$this->id);
            //  print $sql;

            $this->families=array();
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $this->families[$row['Product Family Key']]=$row;
            }
            break;

        case('sales'):
            $this->update_sales_data();


            break;
	case('products_info'):
	  $this->update_product_data();
	  break;

        }

    }

    /*
       Method: save
       Actualiza registros de la tablas product_department, product_group, graba y actualiza datos en la tabla sales
    */
// JFA

    function save($tipo) {
        switch ($tipo) {
        case('first_date'):

            if (is_numeric($this->data['first_date'])) {
                $sql=sprintf("update product_department set first_date=%s where id=%d",
                             prepare_mysql(
                                 date("Y-m-d H:i:s",strtotime('@'.$this->data['first_date'])))
                             ,$this->id);
            } else
                $sql=sprintf("update product_group set first_date=NULL where id=%d",$this->id);

            //     print "$sql;\n";
            mysql_query($sql);

            break;
        case('sales'):
            $sql=sprintf("select id from sales where tipo='dept' and tipo_id=%d",$this->id);
            $res=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $sales_id=$row['id'];
            } else {
                $sql=sprintf("insert into sales (tipo,tipo_id) values ('dept',%d)",$this->id);
                mysql_query($sql);
                $sales_id=$this->db->lastInsertID();

            }
            foreach($this->data['sales'] as $key=>$value) {
                if (preg_match('/^aw/',$key)) {
                    if (is_numeric($value))
                        $sql=sprintf("update sales set %s=%f where id=%d",$key,$value,$sales_id);
                    else
                        $sql=sprintf("update sales set %s=NULL where id=%d",$key,$sales_id);
                    mysql_query($sql);

                }
                if (preg_match('/^ts/',$key)) {
                    $sql=sprintf("update sales set %s=%.2f where id=%d",$key,$value,$sales_id);
                    // print "$sql\n";
                    mysql_query($sql);
                }

            }

            break;
        }

    }

    /*
       Function: get
       Obtiene informacion de los diferentes estados de los productos en el departamento
    */
// JFA

    function get($key) {

        if (array_key_exists($key,$this->data))
            return $this->data[$key];

        if (preg_match('/^(Total|1).*(Amount|Profit)$/',$key)) {

            $amount='Product Department '.$key;

            return money($this->data[$amount]);
        }
        if (preg_match('/^(Total|1).*(Quantity (Ordered|Invoiced|Delivered|)|Invoices|Pending Orders|Customers)$/',$key)) {

            $amount='Product Department '.$key;

            return number($this->data[$amount]);
        }

        switch ($key) {
	case('For Public For Sale Families'):
	  return number($this->data['Product Department For Public For Sale Families']);
	  break;
	case('For Public Discontinued Families'):
	  return number($this->data['Product Department For Public Discontinued Families']);
	  break;
	case('For Sale Products'):
	  return number($this->data['Product Department For Sale Products']);
            break;
        case('Families'):
            return number($this->data['Product Department Families']);
            break;


        case('Total Products'):
            return $this->data['Product Department For Sale Products']+$this->data['Product Department In Process Products']+$this->data['Product Department Not For Sale Products']+$this->data['Product Department Discontinued Products']+$this->data['Product Department Unknown Sales State Products'];
            break;

//   case('weeks'):
//      $_diff_seconds=date('U')-$this->data['first_date'];
//      $day_diff=$_diff_seconds/24/3600;
//      $weeks=$day_diff/7;
//      return $weeks;
        }

    }
    /*
       Method: add_product
       Agrega registros a la tabla Product Department Bridge, actualiza la tabla Product Dimension
    */
// JFA
    function add_product($product_id,$args=false) {


        $product=New Product($product_id);
        if ($product->id) {
            $sql=sprintf("insert into `Product Department Bridge` (`Product Key`,`Product Department Key`) values (%d,%d)",$product->id,$this->id);
            mysql_query($sql);
            $this->load('products_info');

          
            if (preg_match('/principal/',$args)) {
                $sql=sprintf("update  `Product Dimension` set `Product Main Department Key`=%d ,`Product Main Department Code`=%s,`Product Main Department Name`=%s where `Product ID`=%d    "
                             ,$this->id
                             ,prepare_mysql($this->get('Product Department Code'))
                             ,prepare_mysql($this->get('Product Department Name'))
                             ,$product->pid);

                mysql_query($sql);
            }
        }
    }

    /*
       Method: add_family
       Agrega registros a la tabla Product Family Department Bridge, actualiza la tabla Product Department Dimension, Product Family Dimension
    */
// JFA

    function add_family($family_id,$args=false) {
        $family=New Family($family_id);
        if ($family->id) {
            $sql=sprintf("insert into `Product Family Department Bridge` (`Product Family Key`,`Product Department Key`) values (%d,%d)",$family->id,$this->id);
            mysql_query($sql);

            $sql=sprintf("select count(*) as num from `Product Family Department Bridge`  where `Product Department Key`=%d",$this->id);
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $sql=sprintf("update `Product Department Dimension` set `Product Department Families`=%d   where `Product Department Key`=%d  ",
                             $row['num'],
                             $this->id
                            );
                //  print "$sql\n";exit;
                mysql_query($sql);
            }
            if (!preg_match('/noproduct/i',$args) ) {
                foreach($family->get('products') as $key => $value) {
                    $this->add_product($key,$args);
                }
            }

            if (preg_match('/principal/',$args)) {
                $sql=sprintf("update  `Product Family Dimension` set `Product Family Main Department Key`=%d ,`Product Family Main Department Code`=%s,`Product Family Main Department Name`=%s where `Product Family Key`=%s    "
                             ,$this->id
                             ,prepare_mysql($this->get('Product Department Code'))
                             ,prepare_mysql($this->get('Product Department Name'))
                             ,$family->id);
                mysql_query($sql);
            }
        }
    }


    function update_sales_data() {
        $on_sale_days=0;

        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as tto, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension`  where `Product Main Department Key`=".$this->id;

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $from=strtotime($row['ffrom']);
            $_from=date("Y-m-d H:i:s",$from);
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
        $sql="select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF  where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')  and  `Product Department Key`=".$this->id;
// print $sql;
        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql="select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF  where `Product Department Key`=".$this->id;

// print $sql;


        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Product Department Total Invoiced Gross Amount']=$row['gross'];
            $this->data['Product Department Total Invoiced Discount Amount']=$row['disc'];
            $this->data['Product Department Total Invoiced Amount']=$row['gross']-$row['disc'];

            $this->data['Product Department Total Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Product Department Total Quantity Ordered']=$row['ordered'];
            $this->data['Product Department Total Quantity Invoiced']=$row['invoiced'];
            $this->data['Product Department Total Quantity Delivered']=$row['delivered'];
            $this->data['Product Department Total Days On Sale']=$on_sale_days;
            $this->data['Product Department Total Customers']=$row['customers'];
            $this->data['Product Department Total Invoices']=$row['invoices'];
            $this->data['Product Department Total Pending Orders']=$pending_orders;


            $this->data['Product Department Valid From']=$_from;
            $this->data['Product Department Valid To']=$_to;
            $sql=sprintf("update `Product Department Dimension` set `Product Department Total Invoiced Gross Amount`=%s,`Product Department Total Invoiced Discount Amount`=%s,`Product Department Total Invoiced Amount`=%s,`Product Department Total Profit`=%s, `Product Department Total Quantity Ordered`=%s , `Product Department Total Quantity Invoiced`=%s,`Product Department Total Quantity Delivered`=%s ,`Product Department Total Days On Sale`=%f ,`Product Department Valid From`=%s,`Product Department Valid To`=%s ,`Product Department Total Customers`=%d,`Product Department Total Invoices`=%d,`Product Department Total Pending Orders`=%d where `Product Department Key`=%d "
                         ,prepare_mysql($this->data['Product Department Total Invoiced Gross Amount'])
                         ,prepare_mysql($this->data['Product Department Total Invoiced Discount Amount'])
                         ,prepare_mysql($this->data['Product Department Total Invoiced Amount'])
                         ,prepare_mysql($this->data['Product Department Total Profit'])
                         ,prepare_mysql($this->data['Product Department Total Quantity Ordered'])
                         ,prepare_mysql($this->data['Product Department Total Quantity Invoiced'])
                         ,prepare_mysql($this->data['Product Department Total Quantity Delivered'])
                         ,$on_sale_days
                         ,prepare_mysql($this->data['Product Department Valid From'])
                         ,prepare_mysql($this->data['Product Department Valid To'])
                         ,$this->data['Product Department Total Customers']
                         ,$this->data['Product Department Total Invoices']
                         ,$this->data['Product Department Total Pending Orders']

                         ,$this->id
                        );

          //   print "$sql\n";
           //  exit;
            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");
        }
        // days on sale

// --------------------------------------------------------start for 3 year-------------------------------------------------------------------
        $on_sale_days=0;



        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P where `Product Main Department Key`=".$this->id;
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
                    $on_sale_days=0;

                }
            }
        }



        //$sql="select sum(`Product 1 Year Acc Invoiced Gross Amount`) as net,sum(`Product 1 Year Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Year Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Year Acc Profit`)as profit ,sum(`Product 1 Year Acc Quantity Delivered`) as delivered,sum(`Product 1 Year Acc Quantity Ordered`) as ordered,sum(`Product 1 Year Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P where `Product Main Department Key`=".$this->id;
        $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF  where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled') 
        and  `Product Department Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 year"))));
        
        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF  where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 year"))));

	//	print "$sql\n";

        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Product Department 3 Year Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Product Department 3 Year Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Product Department 3 Year Acc Invoiced Amount']=$row['gross']-$row['disc'];

            $this->data['Product Department 3 Year Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Product Department 3 Year Acc Quantity Ordered']=$row['ordered'];
            $this->data['Product Department 3 Year Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Product Department 3 Year Acc Quantity Delivered']=$row['delivered'];
	    $this->data['Product Department 3 Year Acc Customers']=$row['customers'];
            $this->data['Product Department 3 Year Acc Invoices']=$row['invoices'];
            $this->data['Product Department 3 Year Acc Pending Orders']=$pending_orders;

            $sql=sprintf("update `Product Department Dimension` set `Product Department 3 Year Acc Invoiced Gross Amount`=%s,`Product Department 3 Year Acc Invoiced Discount Amount`=%s,`Product Department 3 Year Acc Invoiced Amount`=%s,`Product Department 3 Year Acc Profit`=%s, `Product Department 3 Year Acc Quantity Ordered`=%s , `Product Department 3 Year Acc Quantity Invoiced`=%s,`Product Department 3 Year Acc Quantity Delivered`=%s ,`Product Department 3 Year Acc Days On Sale`=%f  ,`Product Department 3 Year Acc Customers`=%d,`Product Department 3 Year Acc Invoices`=%d,`Product Department 3 Year Acc Pending Orders`=%d where `Product Department Key`=%d "
                         ,prepare_mysql($this->data['Product Department 3 Year Acc Invoiced Gross Amount'])
                         ,prepare_mysql($this->data['Product Department 3 Year Acc Invoiced Discount Amount'])
                         ,prepare_mysql($this->data['Product Department 3 Year Acc Invoiced Amount'])

                         ,prepare_mysql($this->data['Product Department 3 Year Acc Profit'])
                         ,prepare_mysql($this->data['Product Department 3 Year Acc Quantity Ordered'])
                         ,prepare_mysql($this->data['Product Department 3 Year Acc Quantity Invoiced'])
                         ,prepare_mysql($this->data['Product Department 3 Year Acc Quantity Delivered'])
                         ,$on_sale_days
			 ,$this->data['Product Department 3 Year Acc Customers']
                         ,$this->data['Product Department 3 Year Acc Invoices']
                         ,$this->data['Product Department 3 Year Acc Pending Orders']
                         ,$this->id
                        );
	    //print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");
        }
        // exit;
// --------------------------------------------------------end for 3 year---------------------------------------------------------------------


        $on_sale_days=0;



        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P where `Product Main Department Key`=".$this->id;
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
                    $on_sale_days=0;

                }
            }
        }



        //$sql="select sum(`Product 1 Year Acc Invoiced Gross Amount`) as net,sum(`Product 1 Year Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Year Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Year Acc Profit`)as profit ,sum(`Product 1 Year Acc Quantity Delivered`) as delivered,sum(`Product 1 Year Acc Quantity Ordered`) as ordered,sum(`Product 1 Year Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P where `Product Main Department Key`=".$this->id;
        $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF  where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled') 
        and  `Product Department Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));
 // print "$sql\n\n";     
        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF  where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));

	//	print "$sql\n";

        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Product Department 1 Year Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Product Department 1 Year Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Product Department 1 Year Acc Invoiced Amount']=$row['gross']-$row['disc'];

            $this->data['Product Department 1 Year Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Product Department 1 Year Acc Quantity Ordered']=$row['ordered'];
            $this->data['Product Department 1 Year Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Product Department 1 Year Acc Quantity Delivered']=$row['delivered'];
	    $this->data['Product Department 1 Year Acc Customers']=$row['customers'];
            $this->data['Product Department 1 Year Acc Invoices']=$row['invoices'];
            $this->data['Product Department 1 Year Acc Pending Orders']=$pending_orders;

            $sql=sprintf("update `Product Department Dimension` set `Product Department 1 Year Acc Invoiced Gross Amount`=%s,`Product Department 1 Year Acc Invoiced Discount Amount`=%s,`Product Department 1 Year Acc Invoiced Amount`=%s,`Product Department 1 Year Acc Profit`=%s, `Product Department 1 Year Acc Quantity Ordered`=%s , `Product Department 1 Year Acc Quantity Invoiced`=%s,`Product Department 1 Year Acc Quantity Delivered`=%s ,`Product Department 1 Year Acc Days On Sale`=%f  ,`Product Department 1 Year Acc Customers`=%d,`Product Department 1 Year Acc Invoices`=%d,`Product Department 1 Year Acc Pending Orders`=%d where `Product Department Key`=%d "
                         ,prepare_mysql($this->data['Product Department 1 Year Acc Invoiced Gross Amount'])
                         ,prepare_mysql($this->data['Product Department 1 Year Acc Invoiced Discount Amount'])
                         ,prepare_mysql($this->data['Product Department 1 Year Acc Invoiced Amount'])

                         ,prepare_mysql($this->data['Product Department 1 Year Acc Profit'])
                         ,prepare_mysql($this->data['Product Department 1 Year Acc Quantity Ordered'])
                         ,prepare_mysql($this->data['Product Department 1 Year Acc Quantity Invoiced'])
                         ,prepare_mysql($this->data['Product Department 1 Year Acc Quantity Delivered'])
                         ,$on_sale_days
			 ,$this->data['Product Department 1 Year Acc Customers']
                         ,$this->data['Product Department 1 Year Acc Invoices']
                         ,$this->data['Product Department 1 Year Acc Pending Orders']
                         ,$this->id
                        );
	  // print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");
        }
        // exit;
 


// --------------------------------------start for yeartoday-----------------------------------
     $on_sale_days=0;
if(!function_exists('YTD')){
function YTD(){
$first_day_of_year = date('Y').'-01-01';
$today = date('Y-m-d');
$diff = abs((strtotime($today) - strtotime($first_day_of_year))/ (60 * 60 * 24));
return $diff;
}
}
$yeartoday=YTD();
        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Main Department Key`=".$this->id;

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($row['prods']==0)
                $on_sale_days=0;
            else {


                if ($row['for_sale']>0)
                    $to=strtotime('today');
                else
                    $to=strtotime($row['to']);
                if ($to>strtotime("today -$yeartoday")) {

                    $from=strtotime($row['ffrom']);
                    if ($from<strtotime("today -$yeartoday"))
                        $from=strtotime("today -$yeartoday");


                    $on_sale_days=($to-$from)/ (60 * 60 * 24);
                } else
                    $on_sale_days=0;
            }
        }

        //$sql="select sum(`Product 1 Quarter Acc Invoiced Amount`) as net,sum(`Product 1 Quarter Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Quarter Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Quarter Acc Profit`)as profit ,sum(`Product 1 Quarter Acc Quantity Delivered`) as delivered,sum(`Product 1 Quarter Acc Quantity Ordered`) as ordered,sum(`Product 1 Quarter Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P   where `Product Department Key`=".$this->id;
 $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF  where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled') 
        and  `Product Department Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- $yeartoday day"))));
        
        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross 
        ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  
        from `Order Transaction Fact`  OTF  where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- $yeartoday day"))));

        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Product Department YearToDay Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Product Department YearToDay Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Product Department YearToDay Acc Invoiced Amount']=$row['gross']-$row['disc'];

            $this->data['Product Department YearToDay Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Product Department YearToDay Acc Quantity Ordered']=$row['ordered'];
            $this->data['Product Department YearToDay Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Product Department YearToDay Acc Quantity Delivered']=$row['delivered'];
	    $this->data['Product Department YearToDay Acc Customers']=$row['customers'];
            $this->data['Product Department YearToDay Acc Invoices']=$row['invoices'];
            $this->data['Product Department YearToDay Acc Pending Orders']=$pending_orders;

            $sql=sprintf("update `Product Department Dimension` set `Product Department YearToDay Acc Invoiced Gross Amount`=%s,`Product Department YearToDay Acc Invoiced Discount Amount`=%s,`Product Department YearToDay Acc Invoiced Amount`=%s,`Product Department YearToDay Acc Profit`=%s, `Product Department YearToDay Acc Quantity Ordered`=%s , `Product Department YearToDay Acc Quantity Invoiced`=%s,`Product Department 10 Day Acc Quantity Delivered`=%s  ,`Product Department 10 Day Acc Days On Sale`=%f  ,`Product Department 10 Day Acc Customers`=%d,`Product Department YearToDay Acc Invoices`=%d,`Product Department YearToDay Acc Pending Orders`=%d where `Product Department Key`=%d "
                         ,prepare_mysql($this->data['Product Department YearToDay Acc Invoiced Gross Amount'])
                         ,prepare_mysql($this->data['Product Department YearToDay Acc Invoiced Discount Amount'])
                         ,prepare_mysql($this->data['Product Department YearToDay Acc Invoiced Amount'])

                         ,prepare_mysql($this->data['Product Department YearToDay Acc Profit'])
                         ,prepare_mysql($this->data['Product Department YearToDay Acc Quantity Ordered'])
                         ,prepare_mysql($this->data['Product Department YearToDay Acc Quantity Invoiced'])
                         ,prepare_mysql($this->data['Product Department YearToDay Acc Quantity Delivered'])
                         ,$on_sale_days
			 ,$this->data['Product Department YearToDay Acc Customers']
                         ,$this->data['Product Department YearToDay Acc Invoices']
                         ,$this->data['Product Department YearToDay Acc Pending Orders']
                         ,$this->id
                        );
            // print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");
        }

// --------------------------------------ends for yeartoday-------------------------------------


// ----------------------------------start for 6 month-----------------------------------------


       $on_sale_days=0;


        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Main Department Key`=".$this->id;

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($row['prods']==0)
                $on_sale_days=0;
            else {


                if ($row['for_sale']>0)
                    $to=strtotime('today');
                else
                    $to=strtotime($row['to']);
                if ($to>strtotime('today -6 month')) {

                    $from=strtotime($row['ffrom']);
                    if ($from<strtotime('today -6 month'))
                        $from=strtotime('today -6 month');


                    $on_sale_days=($to-$from)/ (60 * 60 * 24);
                } else
                    $on_sale_days=0;
            }
        }

        //$sql="select sum(`Product 1 Quarter Acc Invoiced Amount`) as net,sum(`Product 1 Quarter Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Quarter Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Quarter Acc Profit`)as profit ,sum(`Product 1 Quarter Acc Quantity Delivered`) as delivered,sum(`Product 1 Quarter Acc Quantity Ordered`) as ordered,sum(`Product 1 Quarter Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P   where `Product Department Key`=".$this->id;
 $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF  where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled') 
        and  `Product Department Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 6 month"))));
        
        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross 
        ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  
        from `Order Transaction Fact`  OTF  where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 6 month"))));

        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Product Department 6 Month Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Product Department 6 Month Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Product Department 6 Month Acc Invoiced Amount']=$row['gross']-$row['disc'];

            $this->data['Product Department 6 Month Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Product Department 6 Month Acc Quantity Ordered']=$row['ordered'];
            $this->data['Product Department 6 Month Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Product Department 6 Month Acc Quantity Delivered']=$row['delivered'];
	    $this->data['Product Department 6 Month Acc Customers']=$row['customers'];
            $this->data['Product Department 6 Month Acc Invoices']=$row['invoices'];
            $this->data['Product Department 6 Month Acc Pending Orders']=$pending_orders;

            $sql=sprintf("update `Product Department Dimension` set `Product Department 6 Month Acc Invoiced Gross Amount`=%s,`Product Department 6 Month Acc Invoiced Discount Amount`=%s,`Product Department 6 Month Acc Invoiced Amount`=%s,`Product Department 6 Month Acc Profit`=%s, `Product Department 6 Month Acc Quantity Ordered`=%s , `Product Department 6 Month Acc Quantity Invoiced`=%s,`Product Department 6 Month Acc Quantity Delivered`=%s  ,`Product Department 6 Month Acc Days On Sale`=%f  ,`Product Department 6 Month Acc Customers`=%d,`Product Department 6 Month Acc Invoices`=%d,`Product Department 6 Month Acc Pending Orders`=%d where `Product Department Key`=%d "
                         ,prepare_mysql($this->data['Product Department 6 Month Acc Invoiced Gross Amount'])
                         ,prepare_mysql($this->data['Product Department 6 Month Acc Invoiced Discount Amount'])
                         ,prepare_mysql($this->data['Product Department 6 Month Acc Invoiced Amount'])

                         ,prepare_mysql($this->data['Product Department 6 Month Acc Profit'])
                         ,prepare_mysql($this->data['Product Department 6 Month Acc Quantity Ordered'])
                         ,prepare_mysql($this->data['Product Department 6 Month Acc Quantity Invoiced'])
                         ,prepare_mysql($this->data['Product Department 6 Month Acc Quantity Delivered'])
                         ,$on_sale_days
			 ,$this->data['Product Department 6 Month Acc Customers']
                         ,$this->data['Product Department 6 Month Acc Invoices']
                         ,$this->data['Product Department 6 Month Acc Pending Orders']
                         ,$this->id
                        );
            // print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");
        }


// ----------------------------------end for 6 month-----------------------------------------


       $on_sale_days=0;


        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Main Department Key`=".$this->id;

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

        //$sql="select sum(`Product 1 Quarter Acc Invoiced Amount`) as net,sum(`Product 1 Quarter Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Quarter Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Quarter Acc Profit`)as profit ,sum(`Product 1 Quarter Acc Quantity Delivered`) as delivered,sum(`Product 1 Quarter Acc Quantity Ordered`) as ordered,sum(`Product 1 Quarter Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P   where `Product Department Key`=".$this->id;
 $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF  where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled') 
        and  `Product Department Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));
        
        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross 
        ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  
        from `Order Transaction Fact`  OTF  where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 month"))));

        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Product Department 1 Quarter Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Product Department 1 Quarter Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Product Department 1 Quarter Acc Invoiced Amount']=$row['gross']-$row['disc'];

            $this->data['Product Department 1 Quarter Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Product Department 1 Quarter Acc Quantity Ordered']=$row['ordered'];
            $this->data['Product Department 1 Quarter Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Product Department 1 Quarter Acc Quantity Delivered']=$row['delivered'];
	    $this->data['Product Department 1 Quarter Acc Customers']=$row['customers'];
            $this->data['Product Department 1 Quarter Acc Invoices']=$row['invoices'];
            $this->data['Product Department 1 Quarter Acc Pending Orders']=$pending_orders;

            $sql=sprintf("update `Product Department Dimension` set `Product Department 1 Quarter Acc Invoiced Gross Amount`=%s,`Product Department 1 Quarter Acc Invoiced Discount Amount`=%s,`Product Department 1 Quarter Acc Invoiced Amount`=%s,`Product Department 1 Quarter Acc Profit`=%s, `Product Department 1 Quarter Acc Quantity Ordered`=%s , `Product Department 1 Quarter Acc Quantity Invoiced`=%s,`Product Department 1 Quarter Acc Quantity Delivered`=%s  ,`Product Department 1 Quarter Acc Days On Sale`=%f  ,`Product Department 1 Quarter Acc Customers`=%d,`Product Department 1 Quarter Acc Invoices`=%d,`Product Department 1 Quarter Acc Pending Orders`=%d where `Product Department Key`=%d "
                         ,prepare_mysql($this->data['Product Department 1 Quarter Acc Invoiced Gross Amount'])
                         ,prepare_mysql($this->data['Product Department 1 Quarter Acc Invoiced Discount Amount'])
                         ,prepare_mysql($this->data['Product Department 1 Quarter Acc Invoiced Amount'])

                         ,prepare_mysql($this->data['Product Department 1 Quarter Acc Profit'])
                         ,prepare_mysql($this->data['Product Department 1 Quarter Acc Quantity Ordered'])
                         ,prepare_mysql($this->data['Product Department 1 Quarter Acc Quantity Invoiced'])
                         ,prepare_mysql($this->data['Product Department 1 Quarter Acc Quantity Delivered'])
                         ,$on_sale_days
			 ,$this->data['Product Department 1 Quarter Acc Customers']
                         ,$this->data['Product Department 1 Quarter Acc Invoices']
                         ,$this->data['Product Department 1 Quarter Acc Pending Orders']
                         ,$this->id
                        );
            // print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");
        }

        $on_sale_days=0;

        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P where `Product Main Department Key`=".$this->id;
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

        //$sql="select  sum(`Product 1 Month Acc Invoiced Amount`) as net,sum(`Product 1 Month Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Month Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Month Acc Profit`)as profit ,sum(`Product 1 Month Acc Quantity Delivered`) as delivered,sum(`Product 1 Month Acc Quantity Ordered`) as ordered,sum(`Product 1 Month Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P where `Product Main Department Key`=".$this->id;
  $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF  where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled') 
        and  `Product Department Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));
        
        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross 
        ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  
        from `Order Transaction Fact`  OTF   where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));

        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Product Department 1 Month Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Product Department 1 Month Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Product Department 1 Month Acc Invoiced Amount']=$row['gross']-$row['disc'];

            $this->data['Product Department 1 Month Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Product Department 1 Month Acc Quantity Ordered']=$row['ordered'];
            $this->data['Product Department 1 Month Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Product Department 1 Month Acc Quantity Delivered']=$row['delivered'];
	    $this->data['Product Department 1 Month Acc Customers']=$row['customers'];
            $this->data['Product Department 1 Month Acc Invoices']=$row['invoices'];
            $this->data['Product Department 1 Month Acc Pending Orders']=$pending_orders;

            $sql=sprintf("update `Product Department Dimension` set `Product Department 1 Month Acc Invoiced Gross Amount`=%s,`Product Department 1 Month Acc Invoiced Discount Amount`=%s,`Product Department 1 Month Acc Invoiced Amount`=%s,`Product Department 1 Month Acc Profit`=%s, `Product Department 1 Month Acc Quantity Ordered`=%s , `Product Department 1 Month Acc Quantity Invoiced`=%s,`Product Department 1 Month Acc Quantity Delivered`=%s  ,`Product Department 1 Month Acc Days On Sale`=%f ,`Product Department 1 Month Acc Customers`=%d,`Product Department 1 Month Acc Invoices`=%d,`Product Department 1 Month Acc Pending Orders`=%d where `Product Department Key`=%d "
                         ,prepare_mysql($this->data['Product Department 1 Month Acc Invoiced Gross Amount'])
                         ,prepare_mysql($this->data['Product Department 1 Month Acc Invoiced Discount Amount'])
                         ,prepare_mysql($this->data['Product Department 1 Month Acc Invoiced Amount'])

                         ,prepare_mysql($this->data['Product Department 1 Month Acc Profit'])
                         ,prepare_mysql($this->data['Product Department 1 Month Acc Quantity Ordered'])
                         ,prepare_mysql($this->data['Product Department 1 Month Acc Quantity Invoiced'])
                         ,prepare_mysql($this->data['Product Department 1 Month Acc Quantity Delivered'])
                         ,$on_sale_days
	 ,$this->data['Product Department 1 Month Acc Customers']
                         ,$this->data['Product Department 1 Month Acc Invoices']
                         ,$this->data['Product Department 1 Month Acc Pending Orders']
                         ,$this->id

                        );
            // print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");
        }


// --------------------------------------start for 10 days-----------------------------------
     $on_sale_days=0;


        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Main Department Key`=".$this->id;

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($row['prods']==0)
                $on_sale_days=0;
            else {


                if ($row['for_sale']>0)
                    $to=strtotime('today');
                else
                    $to=strtotime($row['to']);
                if ($to>strtotime('today -10 day')) {

                    $from=strtotime($row['ffrom']);
                    if ($from<strtotime('today -10 day'))
                        $from=strtotime('today -10 day');


                    $on_sale_days=($to-$from)/ (60 * 60 * 24);
                } else
                    $on_sale_days=0;
            }
        }

        //$sql="select sum(`Product 1 Quarter Acc Invoiced Amount`) as net,sum(`Product 1 Quarter Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Quarter Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Quarter Acc Profit`)as profit ,sum(`Product 1 Quarter Acc Quantity Delivered`) as delivered,sum(`Product 1 Quarter Acc Quantity Ordered`) as ordered,sum(`Product 1 Quarter Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P   where `Product Department Key`=".$this->id;
 $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF  where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled') 
        and  `Product Department Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 10 day"))));
        
        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross 
        ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  
        from `Order Transaction Fact`  OTF  where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 10 day"))));

        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Product Department 10 Day Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Product Department 10 Day Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Product Department 10 Day Acc Invoiced Amount']=$row['gross']-$row['disc'];

            $this->data['Product Department 10 Day Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Product Department 10 Day Acc Quantity Ordered']=$row['ordered'];
            $this->data['Product Department 10 Day Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Product Department 10 Day Acc Quantity Delivered']=$row['delivered'];
	    $this->data['Product Department 10 Day Acc Customers']=$row['customers'];
            $this->data['Product Department 10 Day Acc Invoices']=$row['invoices'];
            $this->data['Product Department 10 Day Acc Pending Orders']=$pending_orders;

            $sql=sprintf("update `Product Department Dimension` set `Product Department 10 Day Acc Invoiced Gross Amount`=%s,`Product Department 10 Day Acc Invoiced Discount Amount`=%s,`Product Department 10 Day Acc Invoiced Amount`=%s,`Product Department 10 Day Acc Profit`=%s, `Product Department 10 Day Acc Quantity Ordered`=%s , `Product Department 10 Day Acc Quantity Invoiced`=%s,`Product Department 10 Day Acc Quantity Delivered`=%s  ,`Product Department 10 Day Acc Days On Sale`=%f  ,`Product Department 10 Day Acc Customers`=%d,`Product Department 10 Day Acc Invoices`=%d,`Product Department 10 Day Acc Pending Orders`=%d where `Product Department Key`=%d "
                         ,prepare_mysql($this->data['Product Department 10 Day Acc Invoiced Gross Amount'])
                         ,prepare_mysql($this->data['Product Department 10 Day Acc Invoiced Discount Amount'])
                         ,prepare_mysql($this->data['Product Department 10 Day Acc Invoiced Amount'])

                         ,prepare_mysql($this->data['Product Department 10 Day Acc Profit'])
                         ,prepare_mysql($this->data['Product Department 10 Day Acc Quantity Ordered'])
                         ,prepare_mysql($this->data['Product Department 10 Day Acc Quantity Invoiced'])
                         ,prepare_mysql($this->data['Product Department 10 Day Acc Quantity Delivered'])
                         ,$on_sale_days
			 ,$this->data['Product Department 10 Day Acc Customers']
                         ,$this->data['Product Department 10 Day Acc Invoices']
                         ,$this->data['Product Department 10 Day Acc Pending Orders']
                         ,$this->id
                        );
            // print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");
        }

// --------------------------------------ends for 10 days-------------------------------------


        $on_sale_days=0;
        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P   where `Product Main Department Key`=".$this->id;
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



	// $sql="select sum(`Product 1 Week Acc Invoiced Amount`) as net,sum(`Product 1 Week Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Week Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Week Acc Profit`)as profit ,sum(`Product 1 Week Acc Quantity Delivered`) as delivered,sum(`Product 1 Week Acc Quantity Ordered`) as ordered,sum(`Product 1 Week Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P   where `Product Department Key`=".$this->id;
  $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF  where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled') 
        and  `Product Department Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));
        
        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross 
        ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  
        from `Order Transaction Fact`   where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));


        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Product Department 1 Week Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Product Department 1 Week Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Product Department 1 Week Acc Invoiced Amount']=$row['gross']-$row['disc'];
            $this->data['Product Department 1 Week Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Product Department 1 Week Acc Quantity Ordered']=$row['ordered'];

            $this->data['Product Department 1 Week Acc Quantity Ordered']=$row['ordered'];
            $this->data['Product Department 1 Week Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Product Department 1 Week Acc Quantity Delivered']=$row['delivered'];
   $this->data['Product Department 1 Week Acc Customers']=$row['customers'];
            $this->data['Product Department 1 Week Acc Invoices']=$row['invoices'];
            $this->data['Product Department 1 Week Acc Pending Orders']=$pending_orders;

            $sql=sprintf("update `Product Department Dimension` set `Product Department 1 Week Acc Invoiced Gross Amount`=%s,`Product Department 1 Week Acc Invoiced Discount Amount`=%s,`Product Department 1 Week Acc Invoiced Amount`=%s,`Product Department 1 Week Acc Profit`=%s, `Product Department 1 Week Acc Quantity Ordered`=%s , `Product Department 1 Week Acc Quantity Invoiced`=%s,`Product Department 1 Week Acc Quantity Delivered`=%s ,`Product Department 1 Week Acc Days On Sale`=%f ,`Product Department 1 Week Acc Customers`=%d,`Product Department 1 Week Acc Invoices`=%d,`Product Department 1 Week Acc Pending Orders`=%d  where `Product Department Key`=%d "
                         ,prepare_mysql($this->data['Product Department 1 Week Acc Invoiced Gross Amount'])
                         ,prepare_mysql($this->data['Product Department 1 Week Acc Invoiced Discount Amount'])
                         ,prepare_mysql($this->data['Product Department 1 Week Acc Invoiced Amount'])
                         ,prepare_mysql($this->data['Product Department 1 Week Acc Profit'])
                         ,prepare_mysql($this->data['Product Department 1 Week Acc Quantity Ordered'])
                         ,prepare_mysql($this->data['Product Department 1 Week Acc Quantity Invoiced'])
                         ,prepare_mysql($this->data['Product Department 1 Week Acc Quantity Delivered'])
                         ,$on_sale_days
	 ,$this->data['Product Department 1 Week Acc Customers']
                         ,$this->data['Product Department 1 Week Acc Invoices']
                         ,$this->data['Product Department 1 Week Acc Pending Orders']
                         ,$this->id
                        );
            // print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");

        }
    }
    function name_if_duplicated($data) {

        $sql=sprintf("select * from `Product Department Dimension` where `Product Department Name`=%s  and `Product Department Store Key`=%d "
                     ,prepare_mysql($data['Product Department Name'])
                     ,$data['Product Department Store Key']
                    );

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $s_char=$row['Product Department Name'];
            $number=1;
            $sql=sprintf("select * from `Product Department Dimension` where `Product Department Name` like '%s (%%)'  and `Product Department Store Key`=%d "
                         ,addslashes($data['Product Department Name'])
                         ,$data['Product Department Store Key']
                        );
            $result2=mysql_query($sql);

            while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)) {

                if (preg_match('/\(\d+\)$/',$row2['Product Department Name'],$match))
                    $_number=preg_replace('/[^\d]/','',$match[0]);
                if ($_number>$number)
                    $number=$_number;
            }

            $number++;

            return $data['Product Department Name']." ($number)";

        } else {
            return $data['Product Department Name'];
        }


    }


    function update_averages_per_item(){

      
      $sql=sprintf("select *  from `Product Dimension` where `Product Main Department Key`=%d  ",$this->id);
      $res=mysql_query($sql);
      $avg_weekly_sales_per_product=0;
      $avg_weekly_sales_per_product_1y=0;
      $avg_weekly_sales_per_product_1q=0;
      $avg_weekly_sales_per_product_1m=0;
      $avg_weekly_sales_per_product_1w=0;
      $avg_weekly_profit_per_product=0;
      $avg_weekly_profit_per_product_1y=0;
      $avg_weekly_profit_per_product_1q=0;
      $avg_weekly_profit_per_product_1m=0;
      $avg_weekly_profit_per_product_1w=0;
      $count=0;
      $count_1y=0;
      $count_1q=0;
      $count_1m=0;
      $count_1w=0;
      while($row=mysql_fetch_array($res)){
	if( $row['Product Total Days On Sale']>0){
	  $avg_weekly_sales_per_product+=7*$row['Product Total Invoiced Amount']/$row['Product Total Days On Sale'];
	  $avg_weekly_profit_per_product+=7*$row['Product Total Profit']/$row['Product Total Days On Sale'];
	  $count++;
	}
	if( $row['Product 1 Year Acc Days On Sale']>0){
	  $avg_weekly_sales_per_product_1y+=7*$row['Product 1 Year Acc Invoiced Amount']/$row['Product 1 Year Acc Days On Sale'];
	  $avg_weekly_profit_per_product_1y+=7*$row['Product 1 Year Acc Profit']/$row['Product 1 Year Acc Days On Sale'];
	  $count_1y++;
	}
	if( $row['Product 1 Quarter Acc Days On Sale']>0){
	  $avg_weekly_sales_per_product_1q+=7*$row['Product 1 Quarter Acc Invoiced Amount']/$row['Product 1 Quarter Acc Days On Sale'];
	  $avg_weekly_profit_per_product_1q+=7*$row['Product 1 Quarter Acc Profit']/$row['Product 1 Quarter Acc Days On Sale'];
	   $count_1q++;
	}
	if( $row['Product 1 Month Acc Days On Sale']>0){
	  $avg_weekly_sales_per_product_1m+=7*$row['Product 1 Month Acc Invoiced Amount']/$row['Product 1 Month Acc Days On Sale'];
	  $avg_weekly_profit_per_product_1m+=7*$row['Product 1 Month Acc Profit']/$row['Product 1 Month Acc Days On Sale'];
	   $count_1m++;
	}
	if( $row['Product 1 Week Acc Days On Sale']>0){
	  $avg_weekly_sales_per_product_1w+=7*$row['Product 1 Week Acc Invoiced Amount']/$row['Product 1 Week Acc Days On Sale'];
	  $avg_weekly_profit_per_product_1w+=7*$row['Product 1 Week Acc Profit']/$row['Product 1 Week Acc Days On Sale'];

	  $count_1w++;

	}
	 
      }
      if($count!=0){
	$avg_weekly_sales_per_product=$avg_weekly_sales_per_product/$count;
	$avg_weekly_sales_per_product_1y/=$count_1y;
	$avg_weekly_sales_per_product_1q/=$count_1q;
	$avg_weekly_sales_per_product_1m/=$count_1m;
	$avg_weekly_sales_per_product_1w/=$count_1w;
	$avg_weekly_profit_per_product/=$count;
	$avg_weekly_profit_per_product_1y/=$count_1y;
	$avg_weekly_profit_per_product_1q/=$count_1q;
	$avg_weekly_profit_per_product_1m/=$count_1m;
	$avg_weekly_profit_per_product_1w/=$count_1w;
      
      
      }

      $this->data['Product Department Total Avg Week Sales Per Product']=$avg_weekly_sales_per_product;
      $this->data['Product Department Total Avg Week Profit Per Product']=$avg_weekly_profit_per_product;
      $this->data['Product Department 1 Year Acc Avg Week Sales Per Product']=$avg_weekly_sales_per_product_1y;
      $this->data['Product Department 1 Year Acc Avg Week Profit Per Product']=$avg_weekly_profit_per_product_1y;
      $this->data['Product Department 1 Quarter Acc Avg Week Sales Per Product']=$avg_weekly_sales_per_product_1q;
      $this->data['Product Department 1 Quarter Acc Avg Week Profit Per Product']=$avg_weekly_profit_per_product_1q;
      $this->data['Product Department 1 Month Acc Avg Week Sales Per Product']=$avg_weekly_sales_per_product_1m;
      $this->data['Product Department 1 Month Acc Avg Week Profit Per Product']=$avg_weekly_profit_per_product_1m;
      $this->data['Product Department 1 Week Acc Avg Week Sales Per Product']=$avg_weekly_sales_per_product_1w;
      $this->data['Product Department 1 Week Acc Avg Week Profit Per Product']=$avg_weekly_profit_per_product_1w;


      $sql=sprintf("update `Product Department Dimension` set `Product Department Total Avg Week Sales Per Product`=%.2f , `Product Department Total Avg Week Profit Per Product`=%.2f ,`Product Department 1 Year Acc Avg Week Sales Per Product`=%.2f , `Product Department 1 Year Acc Avg Week Profit Per Product`=%.2f,`Product Department 1 Quarter Acc Avg Week Sales Per Product`=%.2f , `Product Department 1 Quarter Acc Avg Week Profit Per Product`=%.2f,`Product Department 1 Month Acc Avg Week Sales Per Product`=%.2f , `Product Department 1 Month Acc Avg Week Profit Per Product`=%.2f ,`Product Department 1 Week Acc Avg Week Sales Per Product`=%.2f , `Product Department 1 Week Acc Avg Week Profit Per Product`=%.2f where `Product Department Key`=%d   "
		   ,$this->data['Product Department Total Avg Week Sales Per Product']
		   ,$this->data['Product Department Total Avg Week Profit Per Product']
		   ,$this->data['Product Department 1 Year Acc Avg Week Sales Per Product']
		   ,$this->data['Product Department 1 Year Acc Avg Week Profit Per Product']
		   ,$this->data['Product Department 1 Quarter Acc Avg Week Sales Per Product']
		   ,$this->data['Product Department 1 Quarter Acc Avg Week Profit Per Product']
		   ,$this->data['Product Department 1 Month Acc Avg Week Sales Per Product']
		   ,$this->data['Product Department 1 Month Acc Avg Week Profit Per Product']
		   ,$this->data['Product Department 1 Week Acc Avg Week Sales Per Product']
		   ,$this->data['Product Department 1 Week Acc Avg Week Profit Per Product']

		   ,$this->id);
	mysql_query($sql);
	//print "$sql\n";
    }


    function update_product_data() {
      $sql=sprintf("select sum(if(`Product Record Type`='In process',1,0)) as in_process,sum(if(`Product Sales Type`='Unknown',1,0)) as sale_unknown, sum(if(`Product Record Type`='Discontinued',1,0)) as discontinued,sum(if(`Product Sales Type`='Not for sale',1,0)) as not_for_sale,sum(if(`Product Sales Type`='Public Sale',1,0)) as public_sale,sum(if(`Product Sales Type`='Private Sale',1,0)) as private_sale,sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal,sum(if(`Product Availability State`='Low',1,0)) as availability_low,sum(if(`Product Availability State`='Surplus',1,0)) as availability_surplus,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from `Product Dimension` where `Product Main Department Key`=%d",$this->id);
      
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
 

  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     
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

  $sql=sprintf("update `Product Department Dimension` set `Product Department In Process Products`=%d,`Product Department For Public Sale Products`=%d ,`Product Department For Private Sale Products`=%d,`Product Department Discontinued Products`=%d ,`Product Department Not For Sale Products`=%d ,`Product Department Unknown Sales State Products`=%d, `Product Department Optimal Availability Products`=%d , `Product Department Low Availability Products`=%d ,`Product Department Critical Availability Products`=%d ,`Product Department Out Of Stock Products`=%d,`Product Department Unknown Stock Products`=%d ,`Product Department Surplus Availability Products`=%d  where `Product Department Key`=%d  ",
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

	       // prepare_mysql($sales_type),
	       // prepare_mysql($availability),
	       $this->id
	    );
  
  mysql_query($sql);
  // print "$sql\n";



  $this->get_data('id',$this->id);
    }

    function update_customers(){
      $number_active_customers=0;
      $number_active_customers_more_than_50=0;

      $sql=sprintf(" select    (select sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)  from  `Order Transaction Fact`  where  `Order Transaction Fact`.`Customer Key`=OTF.`Customer Key` ) as total_amount  , sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as amount,OTF.`Customer Key` from `Order Transaction Fact`  OTF  left join `Customer Dimension` C on (C.`Customer Key`=OTF.`Customer Key`)where `Product Department Key`=%d and `Customer Type by Activity` in ('New','Active') and `Invoice Transaction Gross Amount`>0  group by  OTF.`Customer Key`",$this->id);
          // print "$sql\n";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$number_active_customers++;
	if($row['total_amount']!=0 and ($row['amount']/$row['total_amount'])>0.5 )
	  $number_active_customers_more_than_50++;
      }
      
      $this->data['Product Department Active Customers']=$number_active_customers;
      $this->data['Product Department Active Customers More 0.5 Share']=$number_active_customers_more_than_50;
	    
 $sql=sprintf("update `Product Department Dimension` set `Product Department Active Customers`=%d ,`Product Department Active Customers More 0.5 Share`=%d where `Product Department Key`=%d  ",
	      $this->data['Product Department Active Customers'],
	      $this->data['Product Department Active Customers More 0.5 Share'],
	      $this->id
                        );
 // print "$sql\n";
 mysql_query($sql);
 
    }

    function update_families() {
        $sql=sprintf("select count(*) as num from `Product Family Dimension`  where`Product Family Main Department Key`=%d",$this->id);
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Product Department Families']=$row['num'];
            $sql=sprintf("update `Product Department Dimension` set `Product Department Families`=%d  where `Product Department Key`=%d  ",
                         $this->data['Product Department Families'],
                         $this->id
                        );
            mysql_query($sql); //print "$sql\n";
        }

  $sql=sprintf("select count(*) as num from `Product Family Dimension`  where `Product Family Main Department Key`=%d and `Product Family Sales Type`='Public Sale' and `Product Family Record Type` in ('New','Normal','Discontinuing')  ",$this->id);
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Product Department For Public For Sale Families']=$row['num'];
            $sql=sprintf("update `Product Department Dimension` set `Product Department For Public For Sale Families`=%d  where `Product Department Key`=%d  ",
                         $this->data['Product Department For Public For Sale Families'],
                         $this->id
                        );
            mysql_query($sql); //print "$sql\n";
        }

  $sql=sprintf("select count(*) as num from `Product Family Dimension`  where `Product Family Main Department Key`=%d  and `Product Family Sales Type`='Public Sale' and `Product Family Record Type`='Discontinued'    "   ,$this->id);
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Product Department For Public Discontinued Families']=$row['num'];
            $sql=sprintf("update `Product Department Dimension` set `Product Department For Public Discontinued Families`=%d  where `Product Department Key`=%d  ",
                         $this->data['Product Department For Public Discontinued Families'],
                         $this->id
                        );
	    //   print "$sql\n";
            mysql_query($sql);
        }


    }


   

function add_image($image_key,$args='') {
 
 $tmp_images_dir='app_files/pics/';
    $principal='No';
    if (preg_match('/principal/i',$args))
      $principal='Yes';
  $sql=sprintf("select count(*) as num from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where  `Subject Type`='Department' and `Subject Key`=%d",$this->id);
      $res=mysql_query($sql);
     $row=mysql_fetch_array($res,MYSQL_ASSOC );
$number_images=$row['num'];
	if ($number_images==0)
	  $principal='Yes';
	$sql=sprintf("insert into `Image Bridge` values ('Department',%d,%d,%s,'') on duplicate key update `Is Principal`=%s "
	    ,$this->id
	    ,$image_key
	    ,prepare_mysql($principal)
	    	    ,prepare_mysql($principal)
	    );
	//	print "$sql\n";
	mysql_query($sql);
       $sql=sprintf("select `Image Thumbnail URL`,`Image Small URL`,`Is Principal`,ID.`Image Key`,`Image Caption`,`Image URL`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='Department' and   `Subject Key`=%d and  PIB.`Image Key`=%d"
		    ,$this->id
		    ,$image_key
		    );
       //  print $sql;
      $res=mysql_query($sql);

      if ($row=mysql_fetch_array($res)) {
	  if ($row['Image Height']!=0)
	    $ratio=$row['Image Width']/$row['Image Height'];
	  else
	    $ratio=1;
	$this->new_value=array('name'=>$row['Image Filename'],'small_url'=>$row['Image Small URL'],'thumbnail_url'=>$row['Image Thumbnail URL'],'filename'=>$row['Image Filename'],'ratio'=>$ratio,'caption'=>$row['Image Caption'],'is_principal'=>$row['Is Principal'],'id'=>$row['Image Key']);
	$this->images_slideshow[]=$this->new_value;
      }
	$this->msg="image added";
      }
      
      
      
function load_images(){
  $sql=sprintf("select PIB.`Is Principal`,ID.`Image Key`,`Image Caption`,`Image URL`,`Image Thumbnail URL`,`Image Small URL`,`Image Large URL`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='Department' and `Subject Key`=%d",$this->id);

      //      print $sql;
      $res=mysql_query($sql);
      $this->images=array();
     


      while ($row=mysql_fetch_array($res,MYSQL_ASSOC )) {
	
	  $this->images[$row['Image Key']]=$row;

      }


}
function load_images_slidesshow(){
  $sql=sprintf("select `Image Thumbnail URL`,`Image Small URL`,`Is Principal`,ID.`Image Key`,`Image Caption`,`Image URL`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='Department' and   `Subject Key`=%d",$this->id);
  //       print $sql;
      $res=mysql_query($sql);
      $this->images_slideshow=array();


      while ($row=mysql_fetch_array($res)) {
	  if ($row['Image Height']!=0)
	    $ratio=$row['Image Width']/$row['Image Height'];
	  else
	    $ratio=1;
	  $this->images_slideshow[]=array('name'=>$row['Image Filename'],'small_url'=>$row['Image Small URL'],'thumbnail_url'=>$row['Image Thumbnail URL'],'filename'=>$row['Image Filename'],'ratio'=>$ratio,'caption'=>$row['Image Caption'],'is_principal'=>$row['Is Principal'],'id'=>$row['Image Key']);
	}
      

}
function update_main_image(){
   
    $this->load_images();
    $num_images=count($this->images);
    $main_image_src='art/nopic.png';
    if($num_images>0){
      
      //print_r($this->images_original);
      foreach( $this->images as $image ){
	// print_r($image);
	$main_image_src=$image['Image Small URL'];
	  if($image['Is Principal']=='Yes'){
	    
	    break;
	  }
      }
    }
    
    $sql=sprintf("update `Product Department Dimension` set `Product Department Main Image`=%s  where `Product Department Key`=%d",
		 prepare_mysql($main_image_src)
		 ,$this->id
		 );
    // print "$sql\n";
    mysql_query($sql);
  }

function get_page_data(){
  $data=array();
  $sql=sprintf("select * from `Page Store Dimension` PSD left join `Page Dimension` PD on (PSD.`Page Key`=PD.`Page Key`) where PSD.`Page Key`=%d",$this->data['Product Department Page Key']);
  // print $sql;
  $res=mysql_query($sql);
  if($row=mysql_fetch_array($res)){
    $data=$row;
  }
  
  return $data;

}

 function update_sales_default_currency() {
        $this->data_default_currency=array();
        $this->data_default_currency['Product Department DC Total Invoiced Gross Amount']=0;
        $this->data_default_currency['Product Department DC Total Invoiced Discount Amount']=0;
        $this->data_default_currency['Product Department DC Total Invoiced Amount']=0;
        $this->data_default_currency['Product Department DC Total Profit']=0;
        $this->data_default_currency['Product Department DC 1 Year Acc Invoiced Gross Amount']=0;
        $this->data_default_currency['Product Department DC 1 Year Acc Invoiced Discount Amount']=0;
        $this->data_default_currency['Product Department DC 1 Year Acc Invoiced Amount']=0;
        $this->data_default_currency['Product Department DC 1 Year Acc Profit']=0;
        $this->data_default_currency['Product Department DC 1 Quarter Acc Invoiced Discount Amount']=0;
        $this->data_default_currency['Product Department DC 1 Quarter Acc Invoiced Amount']=0;
        $this->data_default_currency['Product Department DC 1 Quarter Acc Profit']=0;
        $this->data_default_currency['Product Department DC 1 Month Acc Invoiced Gross Amount']=0;
        $this->data_default_currency['Product Department DC 1 Month Acc Invoiced Discount Amount']=0;
        $this->data_default_currency['Product Department DC 1 Month Acc Invoiced Amount']=0;
        $this->data_default_currency['Product Department DC 1 Month Acc Profit']=0;
        $this->data_default_currency['Product Department DC 1 Week Acc Invoiced Gross Amount']=0;
        $this->data_default_currency['Product Department DC 1 Week Acc Invoiced Discount Amount']=0;
        $this->data_default_currency['Product Department DC 1 Week Acc Invoiced Amount']=0;
        $this->data_default_currency['Product Department DC 1 Week Acc Profit']=0;



        $sql="select     sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc  from `Order Transaction Fact`  OTF   where `Product Department Key`=".$this->id;


        //print "$sql\n\n";
        // exit;
        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data_default_currency['Product Department DC Total Invoiced Gross Amount']=$row['gross'];
            $this->data_default_currency['Product Department DC Total Invoiced Discount Amount']=$row['disc'];
            $this->data_default_currency['Product Department DC Total Invoiced Amount']=$row['gross']-$row['disc'];
            $this->data_default_currency['Product Department DC Total Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

        }



        $sql=sprintf("select  sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc
                     from `Order Transaction Fact`  OTF    where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));

        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data_default_currency['Product Department DC 1 Year Acc Invoiced Gross Amount']=$row['gross'];
            $this->data_default_currency['Product Department DC 1 Year Acc Invoiced Discount Amount']=$row['disc'];
            $this->data_default_currency['Product Department DC 1 Year Acc Invoiced Amount']=$row['gross']-$row['disc'];
            $this->data_default_currency['Product Department DC 1 Year Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

        }

        $sql=sprintf("select   sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc  from `Order Transaction Fact`  OTF    where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 month"))));
        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data_default_currency['Product Department DC 1 Quarter Acc Invoiced Gross Amount']=$row['gross'];
            $this->data_default_currency['Product Department DC 1 Quarter Acc Invoiced Discount Amount']=$row['disc'];
            $this->data_default_currency['Product Department DC 1 Quarter Acc Invoiced Amount']=$row['gross']-$row['disc'];
            $this->data_default_currency['Product Department DC 1 Quarter Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

        }

        $sql=sprintf("select    sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross  ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc    from `Order Transaction Fact`  OTF    where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));



        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data_default_currency['Product Department DC 1 Month Acc Invoiced Gross Amount']=$row['gross'];
            $this->data_default_currency['Product Department DC 1 Month Acc Invoiced Discount Amount']=$row['disc'];
            $this->data_default_currency['Product Department DC 1 Month Acc Invoiced Amount']=$row['gross']-$row['disc'];
            $this->data_default_currency['Product Department DC 1 Month Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

        }
        $sql=sprintf("select  sum(`Cost Supplier`*`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`*`Invoice Currency Exchange Rate`) as gross   ,sum(`Invoice Transaction Total Discount Amount`*`Invoice Currency Exchange Rate`)as disc    from `Order Transaction Fact`  OTF    where `Product Department Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));
        //	print $sql;
        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data_default_currency['Product Department DC 1 Week Acc Invoiced Gross Amount']=$row['gross'];
            $this->data_default_currency['Product Department DC 1 Week Acc Invoiced Discount Amount']=$row['disc'];
            $this->data_default_currency['Product Department DC 1 Week Acc Invoiced Amount']=$row['gross']-$row['disc'];
            $this->data_default_currency['Product Department DC 1 Week Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];

        }

        $insert_values='';
        $update_values='';
        foreach($this->data_default_currency as $key=>$value) {
            $insert_values.=sprintf(',%.2f',$value);
            $update_values.=sprintf(',`%s`=%.2f',addslashes($key),$value);
        }
        $insert_values=preg_replace('/^,/','',$insert_values);
        $update_values=preg_replace('/^,/','',$update_values);


        $sql=sprintf('Insert into `Product Department Default Currency` values (%d,%s) ON DUPLICATE KEY UPDATE %s  ',$this->id,$insert_values,$update_values);
        mysql_query($sql);
        //print "$sql\n";



    }


}

?>
