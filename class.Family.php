<?php
/*
 File: Family.php

 This file contains the Contact Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Kaktus

 Version 2.0
*/
include_once('class.Product.php');

/* class: Family
   Class to manage the *Product Family Dimension* table
*/
// JFA

class Family extends DB_Table {


    var $products=false;
    public $images_slideshow=array();

    /*
      Constructor: Family
      Initializes the class, trigger  Search/Load/Create for the data set

      Returns:
      void
    */


    function Family($a1=false,$a2=false,$a3=false) {
        $this->table_name='Product Family';
        $this->page_data=false;
        $this->ignore_fields=array(
                                 'Product Family Key',
                                 'Product Family For Sale Products',
                                 'Product Family In Process Products',
                                 'Product Family Not For Sale Products',
                                 'Product Family Discontinued Products',
                                 'Product Family Unknown Sales State Products',
                                 'Product Family Surplus Availability Products',
                                 'Product Family Optimal Availability Products',
                                 'Product Family Low Availability Products',
                                 'Product Family Critical Availability Products',
                                 'Product Family Out Of Stock Products',
                                 'Product Family Unknown Stock Products',
                                 'Product Family Total Invoiced Gross Amount',
                                 'Product Family Total Invoiced Discount Amount',
                                 'Product Family Total Invoiced Amount',
                                 'Product Family Total Profit',
                                 'Product Family Total Quantity Ordered',
                                 'Product Family Total Quantity Invoiced',
                                 'Product Family Total Quantity Delivere',
                                 'Product Family Total Days On Sale',
                                 'Product Family Total Days Available',
                                 'Product Family 1 Year Acc Invoiced Gross Amount',
                                 'Product Family 1 Year Acc Invoiced Discount Amount',
                                 'Product Family 1 Year Acc Invoiced Amount',
                                 'Product Family 1 Year Acc Profit',
                                 'Product Family 1 Year Acc Quantity Ordered',
                                 'Product Family 1 Year Acc Quantity Invoiced',
                                 'Product Family 1 Year Acc Quantity Delivere',
                                 'Product Family 1 Year Acc Days On Sale',
                                 'Product Family 1 Year Acc Days Available',
                                 'Product Family 1 Quarter Acc Invoiced Gross Amount',
                                 'Product Family 1 Quarter Acc Invoiced Discount Amount',
                                 'Product Family 1 Quarter Acc Invoiced Amount',
                                 'Product Family 1 Quarter Acc Profit',
                                 'Product Family 1 Quarter Acc Quantity Ordered',
                                 'Product Family 1 Quarter Acc Quantity Invoiced',
                                 'Product Family 1 Quarter Acc Quantity Delivere',
                                 'Product Family 1 Quarter Acc Days On Sale',
                                 'Product Family 1 Quarter Acc Days Available',
                                 'Product Family 1 Month Acc Invoiced Gross Amount',
                                 'Product Family 1 Month Acc Invoiced Discount Amount',
                                 'Product Family 1 Month Acc Invoiced Amount',
                                 'Product Family 1 Month Acc Profit',
                                 'Product Family 1 Month Acc Quantity Ordered',
                                 'Product Family 1 Month Acc Quantity Invoiced',
                                 'Product Family 1 Month Acc Quantity Delivere',
                                 'Product Family 1 Month Acc Days On Sale',
                                 'Product Family 1 Month Acc Days Available',
                                 'Product Family 1 Week Acc Invoiced Gross Amount',
                                 'Product Family 1 Week Acc Invoiced Discount Amount',
                                 'Product Family 1 Week Acc Invoiced Amount',
                                 'Product Family 1 Week Acc Profit',
                                 'Product Family 1 Week Acc Quantity Ordered',
                                 'Product Family 1 Week Acc Quantity Invoiced',
                                 'Product Family 1 Week Acc Quantity Delivere',
                                 'Product Family 1 Week Acc Days On Sale',
                                 'Product Family 1 Week Acc Days Available',
                                 'Product Family Total Quantity Delivered',
                                 'Product Family 1 Year Acc Quantity Delivered',
                                 'Product Family 1 Month Acc Quantity Delivered',
                                 'Product Family 1 Quarter Acc Quantity Delivered',
                                 'Product Family 1 Week Acc Quantity Delivered'


                             );


        if (is_numeric($a1) and !$a2  )
            $this->get_data('id',$a1,false);
        else if (preg_match('/new|create/',$a1) ) {
            $this->find($a2,'create');
        } else if (preg_match('/find/',$a1) ) {
            $this->find($a2,$a3);
        }
        elseif($a2!='')
        $this->get_data($a1,$a2,$a3);
    }


    /*
        Function: find
        Busca the family
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
        if ($data['Product Family Store Key']=='' ) {
            $this->error=true;
            $this->msg='Store Key not provided';
            return;
        }
        if ($data['Product Family Main Department Key']=='' ) {
            $this->error=true;
            $this->msg='Department Key code empty';
            return;
        }

        if ($data['Product Family Code']=='' ) {
            $this->error=true;
            $this->msg='Family code empty';
            return;
        }

        if ($data['Product Family Name']=='')
            $data['Product Family Name']=$data['Product Family Code'];

        $sql=sprintf("select * from `Product Family Dimension` where `Product Family Code`=%s  and `Product Family Store Key`=%d "
                     ,prepare_mysql($data['Product Family Code'])
                     ,$data['Product Family Store Key']
                    );

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->found=true;
            $this->found_key=$row['Product Family Key'];
        }

        if ($this->found) {
            $this->get_data('id',$this->found_key);
        }

        if (!$this->found and $create) {

            $this->create($data);

        }



    }

    /*
      Function: create
      Crea nuevos registros en las tablas Product Family Dimension, Product Family Department Bridge, evitando duplicidad de registros.
    */
    // JFA


    function create($data) {
        $this->new=false;

        $base_data=$this->base_data();
        foreach($data as $key=>$value) {
            if (array_key_exists($key,$base_data))
                $base_data[$key]=_trim($value);
        }

        if ($data['Product Family Special Characteristic']!='')
            $data['Product Family Special Characteristic']=$this->special_characteristic_if_duplicated($data);


        $department=new Department($base_data['Product Family Main Department Key']);
        $base_data['Product Family Main Department Code']=$department->get('Product Department Code');
        $base_data['Product Family Main Department Name']=$department->get('Product Department Name');

        $store=new Store($base_data['Product Family Store Key']);
        $base_data['Product Family Store Code']=$store->get('Store Code');
        $base_data['Product Family Currency Code']=$store->data['Store Currency Code'];


        $keys='(';
        $values='values(';
        foreach($base_data as $key=>$value) {
            $keys.="`$key`,";
            if (preg_match('/Product Family Description/',$key))
                $values.="'".addslashes($value)."',";
            else
                $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Product Family Dimension` %s %s",$keys,$values);

        // print_r($data);

        if (mysql_query($sql)) {
            $this->id = mysql_insert_id();
            $this->get_data('id',$this->id,false);
            $this->msg=_("Family Added");

            //     $sql=sprintf("insert into `Product Family Department Bridge` values (%d,%d)",$this->id,$department->id);
            //mysql_query($sql);

            $data_for_history=array('Action'=>'created'
                                             ,'History Abstract'=>_('Family Created')
                                                                 ,'History Details'=>_('Family')." ".$this->data['Product Family Name']." (".$this->get('Product Family Code').") "._('Created')
                                   );
            $this->add_history($data_for_history);



            $department->update_families();
            $store->update_families();
            $this->new=true;

        } else {
            $this->error=true;
            $this->msg=_("$sql  Error can not create the family");
        }
    }
    /*
      Method: get_data
      Obtiene los datos de la tabla Product Family Dimension de acuerdo al Id, al codigo o al code_store.
    */
// JFA
    function get_data($tipo,$tag,$tag2=false) {

        switch ($tipo) {
        case('id'):
            $sql=sprintf("select *  from `Product Family Dimension` where `Product Family Key`=%d ",$tag);
            break;
        case('code'):
        case('code_store'):
            $sql=sprintf("select *  from `Product Family Dimension` where `Product Family Code`=%s and `Product Family Store Key`=%d ",prepare_mysql($tag),$tag2);

            break;
        }

        // print $sql;
        $result=mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
            $this->id=$this->data['Product Family Key'];

    }
    /*
        Function: update
        Funcion que permite actualizar el nombre o el codigo en la tabla Product Family Dimension, evitando registros duplicados.
    */
// JFA
    function update($key,$a1=false,$a2=false) {
        $this->updated=false;
        $this->msg='Nothing to change';

        switch ($key) {
        case('special_char'):
        case('Product Family Special Characteristic'):
            $this->update_field('Product Family Special Characteristic',$a1);
            break;
        case('code'):

            if ($a1==$this->data['Product Family Code']) {
                $this->updated=true;
                $this->new_value=$a1;
                return;

            }

            if ($a1=='') {
                $this->msg=_('Error: Wrong code (empty)');
                return;
            }
            if (!(strtolower($a1)==strtolower($this->data['Product Family Code']) and $a1!=$this->data['Product Family Code'])) {

                $sql=sprintf("select count(*) as num from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Code`=%s  COLLATE utf8_general_ci "
                             ,$this->data['Product Family Store Key']
                             ,prepare_mysql($a1)
                            );
                $res=mysql_query($sql);
                $row=mysql_fetch_array($res);
                if ($row['num']>0) {
                    $this->msg=_("Error: Another family with the same code");
                    return;
                }
            }
            $old_value=$this->get('Product Family Code') ;
            $sql=sprintf("update `Product Family Dimension` set `Product Family Code`=%s where `Product Family Key`=%d "
                         ,prepare_mysql($a1)
                         ,$this->id
                        );
            if (mysql_query($sql)) {
                $this->msg=_('Family code updated');
                $this->updated=true;
                $this->new_value=$a1;

                $this->data['Product Family Code']=$a1;


                $data_for_history=array(
                                      'Indirect Object'=>'Product Family Code'
                                                        ,'History Abstract'=>_('Product family Code changed').' ('.$this->get('Product Family Code').')'
                                                                            ,'History Details'=>_('Family')." ".$this->data['Product Family Name']." "._('changed code from').' '.$old_value." "._('to').' '. $this->get('Product Family Code')
                                  );
                $this->add_history($data_for_history);

            } else {
                $this->msg=_("Error: Family code could not be updated");

                $this->updated=false;

            }
            break;

        case('name'):

            if ($a1==$this->data['Product Family Name']) {
                $this->updated=true;
                $this->new_value=$a1;
                return;

            }

            if ($a1=='') {
                $this->msg=_('Error: Wrong name (empty)');
                return;
            }
            if (!(strtolower($a1)==strtolower($this->data['Product Family Name']) and $a1!=$this->data['Product Family Name'])) {
                $sql=sprintf("select count(*) as num from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Name`=%s  COLLATE utf8_general_ci"
                             ,$this->data['Product Family Store Key']
                             ,prepare_mysql($a1)
                            );
                $res=mysql_query($sql);
                $row=mysql_fetch_array($res);
                if ($row['num']>0) {
                    $this->msg=_("Error: Another family with the same name");
                    return;
                }
            }
            $old_value=$this->get('Product Family Name') ;
            $sql=sprintf("update `Product Family Dimension` set `Product Family Name`=%s where `Product Family Key`=%d "
                         ,prepare_mysql($a1)
                         ,$this->id
                        );
            if (mysql_query($sql)) {
                $this->msg=_('Family name updated');
                $this->updated=true;
                $this->new_value=$a1;

                $this->data['Product Family Name']=$a1;

                $this->add_history(array(
                                       'Indirect Object'=>'Product Family Name'
                                                         ,'History Abstract'=>('Product Family Name Changed').' ('.$this->get('Product Family Name').')'
                                                                             ,'History Details'=>_('Product Family')." ("._('Code').":".$this->data['Product Family Code'].") "._('name changed from').' '.$old_value." "._('to').' '. $this->get('Product Family Name')
                                   ));



            } else {
                $this->msg=_("Error: Family name could not be updated");

                $this->updated=false;

            }
            break;

        case('description'):
            $this->update_description($a1);

            break;


        }

    }


    function update_description($description) {

        $old_description=$this->data['Product Family Description'];
        $this->update_field('Product Family Description',$description,'nohistory');

        if ($this->updated) {
            set_include_path(get_include_path() . PATH_SEPARATOR . 'external_libs/PEAR');
            include_once 'Text/Diff.php';
            include_once 'Text/Diff/Renderer/inline.php';

            $lines1=preg_split('/\n/',$old_description);
            $lines2=preg_split('/\n/',$this->data['Product Family Description']);


            $diff = new Text_Diff('native', array($lines1,$lines2));
            $renderer = new Text_Diff_Renderer_inline();

            $rendered_difference= preg_replace('/\<del\>/','<span class="diff_del">',$renderer->render($diff));
            $rendered_difference= preg_replace('/\<\/del\>/','</span>',$rendered_difference);
            $rendered_difference= preg_replace('/\<ins\>/','<span class="diff_ins">',$rendered_difference);
            $rendered_difference= preg_replace('/\<\/ins\>/','</span>',$rendered_difference);



            $history_data=array(
                              'History Abstract'=>_('Product Family Description Changed')
                                                 ,'History Details'=>$rendered_difference

                                                                    ,'Indirect Object'=>'Product Family Description'
                          );
            // print_r($history_data);
            $this->add_history($history_data);


        }



        //todo maje nice highlited diff history

    }


    /*
        Function: delete
        Funcion que permite eliminar registros en la tabla Product Family Dimension,Product Family Department Bridge, cuidando la integridad referencial con los productos.
    */
// JFA
    function delete() {
        $this->deleted=false;
        $this->load('products_info');

        if ($this->get('Total Products')==0) {
            $store=new Store($this->data['Product Family Store Key']);
            $this->load('Department Key List');
            $sql=sprintf("delete from `Product Family Dimension` where `Product Family Key`=%d",$this->id);

            if (mysql_query($sql)) {

                $sql=sprintf("delete from `Product Family Department Bridge` where `Product Family Key`=%d",$this->id);
                mysql_query($sql);
                foreach($this->department_keys as $dept_key) {

                    $department=new Department($dept_key);
                    $department->load('products_info');
                }
                $store->load('products_info');
                $this->deleted=true;

            } else {

                $this->msg=_('Error: can not delete family');
                return;
            }

            $this->deleted=true;
        } else {
            $this->msg=_('Family can not be deleted because it has some products');

        }
    }

    /*
        Method: load
        Carga datos de la base de datos Product Dimension, Product Family Department Bridge, actualizando registros en la tabla Product Family Dimension
    */
// JFA

    function load($tipo,$args=false) {
        switch ($tipo) {

        case('Department Key List');
            $this->department_keys=array();
            $sql=sprintf("Select `Product Department Key` from `Product Family Department Bridge` where `Product Family Key`=%d",$this->id);
            $res=mysql_query($sql);
            while ($row=mysql_fetch_array($res)) {
                $this->department_keys[]=$row['Product Department Key'];
            }
            break;
        case('products'):


            $this->products=array();
            if (!$this->id)
                return;
            $order='`Product Family Special Characteristic` ,`Product Code`';
            if (preg_match('/order by sales/i',$args))
                $order='`Product Family Special Characteristic`,`Product Same Code 1 Year Acc Invoiced Amount`,`Product Code`';
            if (preg_match('/order by name/i',$args))
                $order='`Product Family Special Characteristic`,`Product Special Characteristic`';
            if (preg_match('/order by code/i',$args))
                $order='`Product Code File As`';


            //     print $args;
            $limit='';
            if (preg_match('/limit\s+\d*\s*\,*\s*\d*/i',$args,$match)) {
                //print $match[0];
                $limit_qty=preg_replace('/[^(\d|\,)]/','',$match[0]);
                $limit='limit '.$limit_qty;

            }
            $between='';

            if (preg_match('/between\s+\(.*\)/i',$args,$match)) {

                $between_tmp=preg_replace('/.*\(/','',$match[0]);
                $between_tmp=preg_replace('/\).*/','',$between_tmp);

                $between_tmp=preg_split('/,|-/',$between_tmp);

                if (count($between_tmp)==2 and $between_tmp[0]!='' and $between_tmp[1]!='')
                    $between='and `Product Special Characteristic` between '.prepare_mysql($between_tmp[0]).' and '.prepare_mysql($between_tmp[1].'zzzzzz');

            }
            $with='';
            if (preg_match('/with codes\s+\(.*\)/i',$args,$match)) {

                $between_tmp=preg_replace('/.*\(/','',$match[0]);
                $between_tmp=preg_replace('/\).*/','',$between_tmp);



                $with=' and `Product Code` in ('.$between_tmp.') ';

            }




            $family_key=$this->id;
            $sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d %s %s order by %s %s",$family_key,$between,$with,$order,$limit);
            //  print "$sql\n";
            $this->products=array();
            $result=mysql_query($sql);
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $this->products[]=$row;
            }
            // print "ca";
            break;


        case('products_store'):
            $sql=sprintf("select * from `Product Dimension` where `Product Sales Type`!='Not for Sale' and `Product Most Recent`='Yes' and `Product Family Key`=%d and `Product Store Key`=%d",$this->id,$args);
            //  print $sql;

            $this->products=array();
            $result=mysql_query($sql);
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $this->products[$row['Product Key']]=$row;
            }
            break;


//   case('first_date'):
//      $first_date=date('U');
//      $changed=false;
//      $this->load('products');
//      foreach($this->products as $product_id=>$product_data){
//        $product=new Product($product_id);
//        $_date=$product->data['first_date'];
//        //   print "$_date\n";
//        if(is_numeric($_date)){
// 	 // print "hola $product_id   $_date   $first_date  \n";
// 	 if($_date < $first_date){
// 	   $first_date=$_date;
// 	   $changed=true;
// 	 }
//        }
//      }
//      //  print "$first_dat\n";
//      if($changed){
//        $this->data['first_date']=$first_date;
//        if(preg_match('/save/i',$args))
// 	 $this->save($tipo);
//      }

//      break;
        case('sales'):
            $this->update_sales_data();




            break;

        }
    }

    /*
       Method: save
       Actualiza registros de la tabla product_group, graba y actualiza datos en la tabla sales
    */
// JFA
    function save($tipo) {
        switch ($tipo) {
        case('first_date'):

            $sql=sprintf("update product_group set first_date=%s where id=%d",
                         prepare_mysql(
                             date("Y-m-d H:i:s",strtotime('@'.$this->data['first_date'])))
                         ,$this->id);
            //     print "$sql;";
            mysql_query($sql);

            break;
        case('sales'):
            $sql=sprintf("select id from sales where tipo='fam' and tipo_id=%d",$this->id);
            $res=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $sales_id=$row['id'];
            } else {
                $sql=sprintf("insert into sales (tipo,tipo_id) values ('fam',%d)",$this->id);
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
       Obtiene informacion de los diferentes precios de los productos
    */
// JFA

    function get($key,$options=false) {

        if (!$this->id)
            return '';

        if (array_key_exists($key,$this->data))
            return $this->data[$key];


        if (preg_match('/^(Total|1).*(Amount|Profit)$/',$key)) {

            $amount='Product Family '.$key;

            return money($this->data[$amount]);
        }
        if (preg_match('/^(Total|1).*(Quantity (Ordered|Invoiced|Delivered|)|Invoices|Pending Orders|Customers)$/',$key)) {

            $amount='Product Family '.$key;

            return number($this->data[$amount]);
        }


        switch ($key) {
        case('Similar Families'):
            return "<span style='color:#666;font-style:italic;'>"._('No similar families')."</span>";
            break;
        case('Price From Info'):
            $min=99999999;
            $product_id='';
            $changed=false;
            foreach($this->products as $key => $value) {
                if ($value['Product Price']<$min and $value['Product Price']>0) {
                    $min=$value['Product Price'];
                    $product_id=$value['Product Key'];
                    $changed=true;
                }
            }

            if ($changed) {
                $product=new Product($product_id);
                return '<div class="prod_info">'.$product->get('Price Formated','from').'</div>';
            } else
                return '';


            break;

        case('Product Family Description Length'):
            return strlen($this->data['Product Family Description']);
            break;
        case('Product Family Description MD5 Hash'):
            return md5($this->data['Product Family Description']);
            break;



        case('Full Order Form'):
            global $site_checkout_address,$site_checkout_id,$site_url;

            if ($this->locale=='de_DE') {
                $order_txt='Bestellen';
                $reset_txt='Löschen';
                $price_from='ab';
                $lenght_factor=1.5;
            }
            elseif($this->locale=='fr_FR') {
                $order_txt='Commander';
                $reset_txt='Annuler';
                $price_from='à partir de';
                $lenght_factor=1.0;

            }
            else {
                $order_txt='Order';
                $reset_txt='Reset';
                $price_from='Prices from';

                $lenght_factor=1.5;
            }



            $max_code_len=0;
            $max_desc_len=0;
            $info='';

            $min_desc_len=15;
            $min_code_len=8;

            foreach($this->products as $key => $value) {




                $code_len=strlen($value['Product Code']);
                if ($code_len>$max_code_len)
                    $max_code_len=$code_len;
                $desc_len=strlen($value['Product Special Characteristic']);
                if ($desc_len>$max_desc_len)
                    $max_desc_len=$desc_len;
            }
            $desc_len=$max_desc_len*1.5*$lenght_factor;
            $code_len=$max_code_len*1.1*$lenght_factor;

            if ($desc_len<$min_desc_len)
                $desc_len=$min_desc_len;
            if ($code_len<$min_code_len)
                $code_len=$min_code_len;





//  print $max_desc_len;
//  $first=$max_code_len;



            $style=sprintf('<link rel="stylesheet" type="text/css" href="../order.css" /><link rel="stylesheet" type="text/css" href="order.css" /><style type="text/css">table.order {width:%sem}td.first{width:%fem}table.order {font-size:11px;font-family:arial;}span.price{float:right;margin-right:5px}span.desc{margin-left:5px}span.outofstock{color:red;font-weight:800;float:right;margin-right:5px;}input.qty{width:100%%}td.qty{width:3em}</style>
                           <style type="text/css">.prod_info{text-align:left;} .prod_info span{magin:0;color:red;font-family:arial;;font-weight:800;font-size:12px}</style>',$desc_len,$code_len);

//$style=sprintf('<style type="text/css"> span.info_price{font-size:20px}</style>');
// $style='';
            $form=sprintf('%s<table class="Order" border=0><FORM METHOD="POST" ACTION="%s"><INPUT TYPE="HIDDEN" NAME="userid" VALUE="%s"><input type="hidden" name="return" value="%s">'
                          ,$style
                          ,addslashes($site_checkout_address)
                          ,addslashes($site_checkout_id)
                          ,$site_url.$_SERVER['PHP_SELF']
                         );

            $form.="\n";


            $i=1;

            $filter=false;
            if (isset($options['filter']))
                $filter=true;

            $until=false;
            if (isset($options['until']) and is_numeric($options['until']))
                $until=true;


            $header='normal';
            if (isset($options['header'])) {
                //	 print $options['header'];
                switch ($options['header']) {
                case 'none':
                    //case 0:
                case false:
                case '':
                    $header='nonec';
                    break;
                case ('subfamilies'):
                case ('groups'):
                    $header='subfamilies';
                    break;
                case('price from'):
                case('prices from'):
                    $header='price from';
                    break;
                }

            }

            // print $header;

            foreach($this->products as $key => $value) {

                if ($filter and !preg_match('/'.$options['filter'].'/i',$value['Product Name']))
                    continue;
                if ($until and $i>$options['until'])
                    break;

                $product=new Product($value['Product Key']);
                $product->locale=$this->locale;

                if ($i==1 ) {

                    if ($header=='normal')
                        $info=$product->get('Price Anonymous Info',$options);
                    elseif($header=='price from')
                    $info=$this->get('Price From Info');
                    else if ($header=='subfamilies')
                        $info=$product->get('Price Subfamily Info',$options);
                } else if ($header=='subfamilies' and $current_famsdescription!=$product->data['Product Family Special Characteristic']) {
                    $options['inside form']=true;
                    $form.=$product->get('Price Subfamily Info',$options);
                }

                $current_famsdescription=$product->data['Product Family Special Characteristic'];

                $form.=$product->get('Order List Form',array('counter'=>$i,'options'=>$options));

                $i++;
            }
            $form.=sprintf('<tr id="submit_tr"><td id="submit_td" colspan="3" ><input name="Submit" type="submit" class="text" value="%s"> <input name="Reset" type="reset" class="text"  id="Reset" value="%s"></td></tr></form></table>',$order_txt,$reset_txt);

            return $info.$form;

            break;
        case('Total Products'):
            return $this->data['Product Family For Sale Products']+$this->data['Product Family In Process Products']+$this->data['Product Family Not For Sale Products']+$this->data['Product Family Discontinued Products']+$this->data['Product Family Unknown Sales State Products'];
            break;

        case('products'):
            if (!$this->products)
                $this->load('products');
            return $this->products;

            break;
        case('weeks'):
            if (is_numeric($this->data['first_date'])) {
                $date1=date('d-m-Y',strtotime('@'.$this->data['first_date']));
                $day1=date('N')-1;
                $date2=date('d-m-Y');
                $days=datediff('d',$date1,$date2);
                $weeks=number_weeks($days,$day1);
            } else
                $weeks=0;
            return $weeks;
        }

    }

    /*
       Method: add_product
       Actualiza la tabla Product Dimension
    */
// JFA

    function add_product($product_id,$args=false) {

        $product=New Product($product_id);
        if ($product->id) {
            $sql=sprintf("update  `Product Dimension` set `Product Family Key`=%d ,`Product Family Code`=%s,`Product Family Name`=%s where `Product Key`=%s    "
                         ,$this->id
                         ,prepare_mysql($this->get('Product Family Code'))
                         ,prepare_mysql($this->get('Product Family Name'))
                         ,$product->id);
            mysql_query($sql);
            $this->load('products_info');
            // print "$sql\n";
        }
    }

    function update_sales_data() {

        //$sql="select  sum(`Product Total Invoiced Amount`) as net,sum(`Product Total Invoiced Gross Amount`) as gross,sum(`Product Total Invoiced Discount Amount`) as disc, sum(`Product Total Profit`)as profit ,sum(`Product Total Quantity Delivered`) as delivered,sum(`Product Total Quantity Ordered`) as ordered,sum(`Product Total Quantity Invoiced`) as invoiced  from `Product Dimension` where `Product Family Key`=".$this->id;
        $sql="select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF left join    `Product History Dimension` as PH  on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)   where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')  and  `Product Family Key`=".$this->id;
        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql="select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF left join    `Product History Dimension` as PH  on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)   where `Product Family Key`=".$this->id;



        // $sql="select  sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF left join    `Product History Dimension` as PH  on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)   where `Product Family Key`=".$this->id;


        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Product Family Total Invoiced Gross Amount']=$row['gross'];
            $this->data['Product Family Total Invoiced Discount Amount']=$row['disc'];
            $this->data['Product Family Total Invoiced Amount']=$row['gross']-$row['disc'];
            $this->data['Product Family Total Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Product Family Total Quantity Ordered']=$row['ordered'];
            $this->data['Product Family Total Quantity Invoiced']=$row['invoiced'];
            $this->data['Product Family Total Quantity Delivered']=$row['delivered'];
            $this->data['Product Family Total Customers']=$row['customers'];
            $this->data['Product Family Total Invoices']=$row['invoices'];
            $this->data['Product Family Total Pending Orders']=$pending_orders;
        } else {
            $this->data['Product Family Total Invoiced Gross Amount']=0;
            $this->data['Product Family Total Invoiced Discount Amount']=0;
            $this->data['Product Family Total Invoiced Amount']=0;
            $this->data['Product Family Total Profit']=0;
            $this->data['Product Family Total Quantity Ordered']=0;
            $this->data['Product Family Total Quantity Invoiced']=0;
            $this->data['Product Family Total Quantity Delivered']=0;
            $this->data['Product Family Total Customers']=0;
            $this->data['Product Family Total Invoices']=0;
            $this->data['Product Family Total Pending Orders']=$pending_orders;

        }

        $sql=sprintf("update `Product Family Dimension` set `Product Family Total Invoiced Gross Amount`=%.2f,`Product Family Total Invoiced Discount Amount`=%.2f,`Product Family Total Invoiced Amount`=%.2f,`Product Family Total Profit`=%.2f, `Product Family Total Quantity Ordered`=%f , `Product Family Total Quantity Invoiced`=%f,`Product Family Total Quantity Delivered`=%f   ,`Product Family Total Customers`=%d,`Product Family Total Invoices`=%d,`Product Family Total Pending Orders`=%d where `Product Family Key`=%d "
                     ,$this->data['Product Family Total Invoiced Gross Amount']
                     ,$this->data['Product Family Total Invoiced Discount Amount']
                     ,$this->data['Product Family Total Invoiced Amount']
                     ,$this->data['Product Family Total Profit']
                     ,$this->data['Product Family Total Quantity Ordered']
                     ,$this->data['Product Family Total Quantity Invoiced']
                     ,$this->data['Product Family Total Quantity Delivered']
                     ,$this->data['Product Family Total Customers']
                     ,$this->data['Product Family Total Invoices']
                     ,$this->data['Product Family Total Pending Orders']
                     ,$this->id
                    );

        if (!mysql_query($sql))
            exit("$sql\ncan not update fam sales total\n");


        // $sql="select  sum(`Product 1 Year Acc Invoiced Amount`) as net,sum(`Product 1 Year Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Year Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Year Acc Profit`)as profit ,sum(`Product 1 Year Acc Quantity Delivered`) as delivered,sum(`Product 1 Year Acc Quantity Ordered`) as ordered,sum(`Product 1 Year Acc Quantity Invoiced`) as invoiced  from `Product Dimension` where `Product Family Key`=".$this->id;

        $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF left join    `Product History Dimension` as PH  on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)   where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Product Family Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));

        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross  ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF left join    `Product History Dimension` as PH  on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)   where `Product Family Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));

        //	exit($sql);

        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Product Family 1 Year Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Product Family 1 Year Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Product Family 1 Year Acc Invoiced Amount']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Product Family 1 Year Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Product Family 1 Year Acc Quantity Ordered']=$row['ordered'];
            $this->data['Product Family 1 Year Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Product Family 1 Year Acc Quantity Delivered']=$row['delivered'];
            $this->data['Product Family 1 Year Acc Customers']=$row['customers'];
            $this->data['Product Family 1 Year Acc Invoices']=$row['invoices'];
            $this->data['Product Family 1 Year Acc Pending Orders']=$pending_orders;
        } else {
            $this->data['Product Family 1 Year Acc Invoiced Gross Amount']=0;
            $this->data['Product Family 1 Year Acc Invoiced Discount Amount']=0;
            $this->data['Product Family 1 Year Acc Invoiced Amount']=0;
            $this->data['Product Family 1 Year Acc Profit']=0;
            $this->data['Product Family 1 Year Acc Quantity Ordered']=0;
            $this->data['Product Family 1 Year Acc Quantity Invoiced']=0;
            $this->data['Product Family 1 Year Acc Quantity Delivered']=0;
            $this->data['Product Family 1 Year Acc Customers']=0;
            $this->data['Product Family 1 Year Acc Invoices']=0;
            $this->data['Product Family 1 Year Acc Pending Orders']=$pending_orders;
        }

        $sql=sprintf("update `Product Family Dimension` set `Product Family 1 Year Acc Invoiced Gross Amount`=%.2f,`Product Family 1 Year Acc Invoiced Discount Amount`=%.2f,`Product Family 1 Year Acc Invoiced Amount`=%.2f,`Product Family 1 Year Acc Profit`=%.2f, `Product Family 1 Year Acc Quantity Ordered`=%f , `Product Family 1 Year Acc Quantity Invoiced`=%f,`Product Family 1 Year Acc Quantity Delivered`=%f  ,`Product Family 1 Year Acc Customers`=%d,`Product Family 1 Year Acc Invoices`=%d,`Product Family 1 Year Acc Pending Orders`=%d  where `Product Family Key`=%d "
                     ,$this->data['Product Family 1 Year Acc Invoiced Gross Amount']
                     ,$this->data['Product Family 1 Year Acc Invoiced Discount Amount']
                     ,$this->data['Product Family 1 Year Acc Invoiced Amount']
                     ,$this->data['Product Family 1 Year Acc Profit']
                     ,$this->data['Product Family 1 Year Acc Quantity Ordered']
                     ,$this->data['Product Family 1 Year Acc Quantity Invoiced']
                     ,$this->data['Product Family 1 Year Acc Quantity Delivered']
                     ,$this->data['Product Family 1 Year Acc Customers']
                     ,$this->data['Product Family 1 Year Acc Invoices']
                     ,$this->data['Product Family 1 Year Acc Pending Orders']
                     ,$this->id
                    );

        if (!mysql_query($sql))
            exit("$sql\ncan not update fam sales 1 year\n");


        //$sql="select  sum(`Product 1 Quarter Acc Invoiced Amount`) as net,sum(`Product 1 Quarter Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Quarter Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Quarter Acc Profit`)as profit ,sum(`Product 1 Quarter Acc Quantity Delivered`) as delivered,sum(`Product 1 Quarter Acc Quantity Ordered`) as ordered,sum(`Product 1 Quarter Acc Quantity Invoiced`) as invoiced  from `Product Dimension` where `Product Family Key`=".$this->id;

        $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF left join    `Product History Dimension` as PH  on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)   where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Product Family Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 month"))));

        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`  OTF left join    `Product History Dimension` as PH  on (OTF.`Product Key`=PH.`Product Key`)
                     left join `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)   where `Product Family Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 month"))));

        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Product Family 1 Quarter Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Product Family 1 Quarter Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Product Family 1 Quarter Acc Invoiced Amount']=$row['gross']-$row['disc'];

            $this->data['Product Family 1 Quarter Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Product Family 1 Quarter Acc Quantity Ordered']=$row['ordered'];
            $this->data['Product Family 1 Quarter Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Product Family 1 Quarter Acc Quantity Delivered']=$row['delivered'];
            $this->data['Product Family 1 Quarter Acc Customers']=$row['customers'];
            $this->data['Product Family 1 Quarter Acc Invoices']=$row['invoices'];
            $this->data['Product Family 1 Quarter Acc Pending Orders']=$pending_orders;
        } else {
            $this->data['Product Family 1 Quarter Acc Invoiced Gross Amount']=0;
            $this->data['Product Family 1 Quarter Acc Invoiced Discount Amount']=0;
            $this->data['Product Family 1 Quarter Acc Invoiced Amount']=0;
            $this->data['Product Family 1 Quarter Acc Profit']=0;
            $this->data['Product Family 1 Quarter Acc Quantity Ordered']=0;
            $this->data['Product Family 1 Quarter Acc Quantity Invoiced']=0;
            $this->data['Product Family 1 Quarter Acc Quantity Delivered']=0;
            $this->data['Product Family 1 Quarter Acc Customers']=0;
            $this->data['Product Family 1 Quarter Acc Invoices']=0;
            $this->data['Product Family 1 Quarter Acc Pending Orders']=$pending_orders;
        }

        $sql=sprintf("update `Product Family Dimension` set `Product Family 1 Quarter Acc Invoiced Gross Amount`=%.2f,`Product Family 1 Quarter Acc Invoiced Discount Amount`=%.2f,`Product Family 1 Quarter Acc Invoiced Amount`=%.2f,`Product Family 1 Quarter Acc Profit`=%.2f, `Product Family 1 Quarter Acc Quantity Ordered`=%f , `Product Family 1 Quarter Acc Quantity Invoiced`=%f,`Product Family 1 Quarter Acc Quantity Delivered`=%f  ,`Product Family 1 Quarter Acc Customers`=%d,`Product Family 1 Quarter Acc Invoices`=%d,`Product Family 1 Quarter Acc Pending Orders`=%d  where `Product Family Key`=%d "
                     ,$this->data['Product Family 1 Quarter Acc Invoiced Gross Amount']
                     ,$this->data['Product Family 1 Quarter Acc Invoiced Discount Amount']
                     ,$this->data['Product Family 1 Quarter Acc Invoiced Amount']
                     ,$this->data['Product Family 1 Quarter Acc Profit']
                     ,$this->data['Product Family 1 Quarter Acc Quantity Ordered']
                     ,$this->data['Product Family 1 Quarter Acc Quantity Invoiced']
                     ,$this->data['Product Family 1 Quarter Acc Quantity Delivered']
                     ,$this->data['Product Family 1 Quarter Acc Customers']
                     ,$this->data['Product Family 1 Quarter Acc Invoices']
                     ,$this->data['Product Family 1 Quarter Acc Pending Orders']
                     ,$this->id
                    );

        if (!mysql_query($sql))
            exit("$sql\ncan not update fam sales 1 quarter\n");

        //$sql="select  sum(`Product 1 Month Acc Invoiced Amount`) as net,sum(`Product 1 Month Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Month Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Month Acc Profit`)as profit ,sum(`Product 1 Month Acc Quantity Delivered`) as delivered,sum(`Product 1 Month Acc Quantity Ordered`) as ordered,sum(`Product 1 Month Acc Quantity Invoiced`) as invoiced  from `Product Dimension` where `Product Family Key`=".$this->id;
        $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF left join    `Product History Dimension` as PH  on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)   where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Product Family Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));

        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`  OTF left join    `Product History Dimension` as PH  on (OTF.`Product Key`=PH.`Product Key`)
                     left join `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)   where `Product Family Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));

        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Product Family 1 Month Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Product Family 1 Month Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Product Family 1 Month Acc Invoiced Amount']=$row['gross']-$row['disc'];
            $this->data['Product Family 1 Month Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Product Family 1 Month Acc Quantity Ordered']=$row['ordered'];
            $this->data['Product Family 1 Month Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Product Family 1 Month Acc Quantity Delivered']=$row['delivered'];
            $this->data['Product Family 1 Month Acc Customers']=$row['customers'];
            $this->data['Product Family 1 Month Acc Invoices']=$row['invoices'];
            $this->data['Product Family 1 Month Acc Pending Orders']=$pending_orders;

        } else {
            $this->data['Product Family 1 Month Acc Invoiced Gross Amount']=0;
            $this->data['Product Family 1 Month Acc Invoiced Discount Amount']=0;
            $this->data['Product Family 1 Month Acc Invoiced Amount']=0;
            $this->data['Product Family 1 Month Acc Profit']=0;
            $this->data['Product Family 1 Month Acc Quantity Ordered']=0;
            $this->data['Product Family 1 Month Acc Quantity Invoiced']=0;
            $this->data['Product Family 1 Month Acc Quantity Delivered']=0;
            $this->data['Product Family 1 Month Acc Customers']=0;
            $this->data['Product Family 1 Month Acc Invoices']=0;
            $this->data['Product Family 1 Month Acc Pending Orders']=$pending_orders;

        }

        $sql=sprintf("update `Product Family Dimension` set `Product Family 1 Month Acc Invoiced Gross Amount`=%.2f,`Product Family 1 Month Acc Invoiced Discount Amount`=%.2f,`Product Family 1 Month Acc Invoiced Amount`=%.2f,`Product Family 1 Month Acc Profit`=%.2f, `Product Family 1 Month Acc Quantity Ordered`=%f , `Product Family 1 Month Acc Quantity Invoiced`=%f,`Product Family 1 Month Acc Quantity Delivered`=%f  ,`Product Family 1 Month Acc Customers`=%d,`Product Family 1 Month Acc Invoices`=%d,`Product Family 1 Month Acc Pending Orders`=%d where `Product Family Key`=%d "
                     ,$this->data['Product Family 1 Month Acc Invoiced Gross Amount']
                     ,$this->data['Product Family 1 Month Acc Invoiced Discount Amount']
                     ,$this->data['Product Family 1 Month Acc Invoiced Amount']
                     ,$this->data['Product Family 1 Month Acc Profit']
                     ,$this->data['Product Family 1 Month Acc Quantity Ordered']
                     ,$this->data['Product Family 1 Month Acc Quantity Invoiced']
                     ,$this->data['Product Family 1 Month Acc Quantity Delivered']
                     ,$this->data['Product Family 1 Month Acc Customers']
                     ,$this->data['Product Family 1 Month Acc Invoices']
                     ,$this->data['Product Family 1 Month Acc Pending Orders']
                     ,$this->id
                     ,$this->id
                    );

        if (!mysql_query($sql))
            exit("$sql\ncan not update fam sales 1 month\n");

        //$sql="select  sum(`Product 1 Week Acc Invoiced Amount`) as net,sum(`Product 1 Week Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Week Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Week Acc Profit`)as profit ,sum(`Product 1 Week Acc Quantity Delivered`) as delivered,sum(`Product 1 Week Acc Quantity Ordered`) as ordered,sum(`Product 1 Week Acc Quantity Invoiced`) as invoiced  from `Product Dimension` where `Product Family Key`=".$this->id;

        $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact`  OTF left join    `Product History Dimension` as PH  on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)   where  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')
                     and  `Product Family Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));

        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`/`Invoice Currency Exchange Rate`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`  OTF left join    `Product History Dimension` as PH  on (OTF.`Product Key`=PH.`Product Key`)
                     left join `Product Dimension` P on (PH.`Product ID`=P.`Product ID`)   where `Product Family Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));

        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Product Family 1 Week Acc Invoiced Gross Amount']=$row['gross'];
            $this->data['Product Family 1 Week Acc Invoiced Discount Amount']=$row['disc'];
            $this->data['Product Family 1 Week Acc Invoiced Amount']=$row['gross']-$row['disc'];
            $this->data['Product Family 1 Week Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Product Family 1 Week Acc Quantity Ordered']=$row['ordered'];
            $this->data['Product Family 1 Week Acc Quantity Invoiced']=$row['invoiced'];
            $this->data['Product Family 1 Week Acc Quantity Delivered']=$row['delivered'];
            $this->data['Product Family 1 Week Acc Customers']=$row['customers'];
            $this->data['Product Family 1 Week Acc Invoices']=$row['invoices'];
            $this->data['Product Family 1 Week Acc Pending Orders']=$pending_orders;

        } else {
            $this->data['Product Family 1 Week Acc Invoiced Gross Amount']=0;
            $this->data['Product Family 1 Week Acc Invoiced Discount Amount']=0;
            $this->data['Product Family 1 Week Acc Invoiced Amount']=0;
            $this->data['Product Family 1 Week Acc Profit']=0;
            $this->data['Product Family 1 Week Acc Quantity Ordered']=0;
            $this->data['Product Family 1 Week Acc Quantity Invoiced']=0;
            $this->data['Product Family 1 Week Acc Quantity Delivered']=0;
            $this->data['Product Family 1 Week Acc Customers']=0;
            $this->data['Product Family 1 Week Acc Invoices']=0;
            $this->data['Product Family 1 Week Acc Pending Orders']=$pending_orders;


        }

        $sql=sprintf("update `Product Family Dimension` set `Product Family 1 Week Acc Invoiced Gross Amount`=%.2f,`Product Family 1 Week Acc Invoiced Discount Amount`=%.2f,`Product Family 1 Week Acc Invoiced Amount`=%.2f,`Product Family 1 Week Acc Profit`=%.2f, `Product Family 1 Week Acc Quantity Ordered`=%f , `Product Family 1 Week Acc Quantity Invoiced`=%f,`Product Family 1 Week Acc Quantity Delivered`=%f  ,`Product Family 1 Week Acc Customers`=%d,`Product Family 1 Week Acc Invoices`=%d,`Product Family 1 Week Acc Pending Orders`=%d  where `Product Family Key`=%d "
                     ,$this->data['Product Family 1 Week Acc Invoiced Gross Amount']
                     ,$this->data['Product Family 1 Week Acc Invoiced Discount Amount']
                     ,$this->data['Product Family 1 Week Acc Invoiced Amount']
                     ,$this->data['Product Family 1 Week Acc Profit']
                     ,$this->data['Product Family 1 Week Acc Quantity Ordered']
                     ,$this->data['Product Family 1 Week Acc Quantity Invoiced']
                     ,$this->data['Product Family 1 Week Acc Quantity Delivered']
                     ,$this->data['Product Family 1 Week Acc Customers']
                     ,$this->data['Product Family 1 Week Acc Invoices']
                     ,$this->data['Product Family 1 Week Acc Pending Orders']
                     ,$this->id
                    );

        if (!mysql_query($sql))
            exit("$sql\ncan not update fam sales 1 week\n");
    }


    function special_characteristic_if_duplicated($data) {

        $sql=sprintf("select * from `Product Family Dimension` where `Product Family Special Characteristic`=%s  and `Product Family Store Key`=%d "
                     ,prepare_mysql($data['Product Family Special Characteristic'])
                     ,$data['Product Family Store Key']
                    );

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $s_char=$row['Product Family Special Characteristic'];
            $number=1;
            $sql=sprintf("select * from `Product Family Dimension` where `Product Family Special Characteristic` like '%s (%%)'  and `Product Family Store Key`=%d "
                         ,addslashes($data['Product Family Special Characteristic'])
                         ,$data['Product Family Store Key']
                        );
            $result2=mysql_query($sql);

            while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)) {

                if (preg_match('/\(\d+\)$/',$row2['Product Family Special Characteristic'],$match))
                    $_number=preg_replace('/[^\d]/','',$match[0]);
                if ($_number>$number)
                    $number=$_number;
            }

            $number++;

            return $data['Product Family Special Characteristic']." ($number)";

        } else {
            return $data['Product Family Special Characteristic'];
        }


    }

    function update_product_data() {


        $sql=sprintf("select     sum(if(`Product Record Type`='Discontinuing',1,0)) as to_be_discontinued , sum(if(`Product Record Type`='Historic',1,0)) as historic ,  sum(if(`Product Record Type` in ('Normal','New','Discontinuing') ,1,0)) as for_sale ,   sum(if(`Product Record Type`='In process',1,0)) as in_process ,sum(if(`Product Sales Type`='Unknown',1,0)) as sale_unknown, sum(if(`Product Record Type`='Discontinued',1,0)) as discontinued,sum(if(`Product Sales Type`='Not for sale',1,0)) as not_for_sale,sum(if(`Product Sales Type`='Public Sale',1,0)) as public_sale,sum(if(`Product Sales Type`='Private Sale',1,0)) as private_sale,sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal,sum(if(`Product Availability State`='Low',1,0)) as availability_low,sum(if(`Product Availability State`='Surplus',1,0)) as availability_surplus,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from `Product Dimension` where `Product Family Key`=%d",$this->id);
        //  print $sql;

	
        $availability='No Applicable';
        $sales_type='No Applicable';
        $historic=0;
	$for_sale=0;
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
	$to_be_discontinued=0;

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	  $to_be_discontinued=$row['to_be_discontinued'];
	  $historic=$row['historic'];
	  $for_sale=$row['for_sale'];
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



            if ($public_sale==0 and $private_sale==0 and $not_for_sale>0) {
                $sales_type='Not for Sale';
            }
            elseif($public_sale==0 and $private_sale>0) {
                $sales_type='Private Sale Only';
            }
            elseif($public_sale>0 ) {
                $sales_type='Public Sale';
            }
            else
                $sales_type='Unknown';

            $avalilable_products=$availability_optimal+$availability_low+$availability_critical+$availability_surplus+$availability_unknown;
            if ( $avalilable_products>0 and $availability_outofstock>0  ) {
                $availability='Some Out of Stock';
            } else if ($avalilable_products>0) {
                $availability='Normal';
            } else if ($avalilable_products==0 and $availability_outofstock>0) {
                $availability='All Out of Stock';
            }


        }


	$record_type='Normal';

	if($for_sale==0){
	  if($in_process>0 and $discontinued==0)
	    $record_type='In Processs';
	  else
	    $record_type='Discontinued';
	}else{
	  if($for_sale==$to_be_discontinued)
	   $record_type='Discontinuing';

	}
	



        $sql=sprintf("update `Product Family Dimension` set `Product Family Record Type`=%s,`Product Family In Process Products`=%d,`Product Family For Public Sale Products`=%d ,`Product Family For Private Sale Products`=%d,`Product Family Discontinued Products`=%d ,`Product Family Not For Sale Products`=%d ,`Product Family Unknown Sales State Products`=%d, `Product Family Optimal Availability Products`=%d , `Product Family Low Availability Products`=%d ,`Product Family Critical Availability Products`=%d ,`Product Family Out Of Stock Products`=%d,`Product Family Unknown Stock Products`=%d ,`Product Family Surplus Availability Products`=%d ,`Product Family Sales Type`=%s,`Product Family Availability`=%s where `Product Family Key`=%d  ",
                     prepare_mysql($record_type),
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
                     prepare_mysql($sales_type),
                     prepare_mysql($availability),
                     $this->id
                    );



        mysql_query($sql);
        // print "$sql\n";

        $this->get_data('id',$this->id);
    }




    function product_timeline($extra_where='') {
        //todo: this scheme dont take in count products with 2 sku or sku changing over time
        $min_date=date('Y-m-d H:i:s');
        $max_date=date('Y-m-d H:i:s');

        $sql=sprintf("select `Product Code`,`Product ID`,`Product Sales Type`,`Product Record Type`,`Product Valid From`,`Product Valid To` from `Product Dimension`  where `Product Family Key`=%d  %s  ",$this->id,$extra_where);
        $res=mysql_query($sql);
        $products=array();
        $skus=array();
        while ($row=mysql_fetch_array($res)) {
            if (strtotime($min_date)>strtotime($row['Product Valid From']))
                $min_date=$row['Product Valid From'];
            if (strtotime($min_date)>strtotime($row['Product Valid To']))
                $min_date=$row['Product Valid To'];

            $_product=new Product('pid',$row['Product ID']);
            $units_per_case=$_product->data['Product Units Per Case'];
            $sku=$_product->get('Parts SKU');


            $products[]=array(
                            'code'=>$row['Product Code'],'units_per_case'=>$units_per_case,'sku'=>$sku[0],'id'=>$row['Product ID'],'from'=>$row['Product Valid From'],'to'=>($row['Product Record Type']!='Discontinued'?date('Y-m-d H:i:s'):$row['Product Valid To']));
        }
        //print "$min_date $max_date\n";
        //print_r($products);

        $sql=sprintf("select `Date` from kbase.`Date Dimension` where `Date`>=DATE(%s) and `Date`<=DATE(%s)",prepare_mysql($min_date),prepare_mysql($max_date));
        $res=mysql_query($sql);
        $dates=array();
        $dates_skus=array();
        $dates_ppp=array();
        //print $sql;
        while ($row=mysql_fetch_array($res)) {
            $dates[$row['Date']]=array();
            $dates_skus[$row['Date']]=array();
            $dates_ppp[$row['Date']]=array();
            foreach($products as $product) {
                if (!(strtotime($product['to'])<strtotime($row['Date'].' OO:00:00')  or  strtotime($product['from'])>strtotime($row['Date'].' 23:59:59')  )) {
                    $dates[$row['Date']][]=$product['id'];
                    $dates_skus[$row['Date']][]=$product['sku'];
                    $dates_ppp[$row['Date']][]=$product['units_per_case'];
                }

            }
            sort($dates[$row['Date']]);

        }

        //  print_r($dates);

        $border_dates=array();
        $pivot=array();
        foreach($dates as $key=>$date) {
            if ($pivot!=$date) {
                //print "$key\n";
                $border_dates[]=$key;
            }
            $pivot=$date;
        }
        $border_dates[]=$key;
        //print_r($border_dates);
        $counter=0;
        $product_interval=array();
        foreach($border_dates as $border) {

            $recent='No';
            if ($counter==count($border_dates)-1)
                $recent='Yes';
            if ($counter>0) {
                $product_interval[]=array(
                                        'Product IDs'=>$dates[$lower_bound],
                                        'Product SKUs'=>$dates_skus[$lower_bound],
                                        'Products Per Part'=>$dates_ppp[$lower_bound],
                                        'Valid From'=>$lower_bound.' 00:00:00',
                                        'Valid To'=>date('Y-m-d h:i:s',strtotime($border.' -1 second')),
                                        'Most Recent'=>$recent
                                    );
            }
            $lower_bound=$border;
            $counter++;
        }

        //print_r($product_interval);

        return $product_interval;


    }

    function get_next_product_code() {
        $next_code='';
        $sql=sprintf("select `Product Code File As` from `Product Dimension` where `Product Family Key`=%d order by  `Product Code File As` desc limit 1 ",$this->id);
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $next_code=$row['Product Code File As'];
            if (preg_match('/^[a-z]+\-\d+$/',$row['Product Code File As'])) {
                $last_number=1;
                if (preg_match('/\d+$/',$row['Product Code File As'],$match)) {
                    $last_number+=$match[0];
                }
                $next_code=sprintf("%s-%02d",$this->data['Product Family Code'],$last_number);
            }


            return $next_code;
        } else
            return '';

    }

    function update_sales_state() {

        $this->update_product_data();

    }

    function get_number_products() {
        $number_products=0;
        $sql=sprintf("select count(*) as num from `Product Dimension` where `Product Family Key`=%d "
                     ,$this->id
                    );
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $number_products=$row['num'];

        }
        return $number_products;
    }



    function get_number_products_by_sales_type($tipo=false) {
        $number_products=array('Public Sale'=>0,'Private Sale'=>0,'Not for Sale'=>0,'Discontinued'=>0);

        $sql=sprintf("select count(*) as num, `Product Sales Type` from `Product Dimension` where `Product Family Key`=%d group by `Product Sales Type`"
                     ,$this->id
                    );
        $res=mysql_query($sql);
        while ($row=mysql_fetch_array($res)) {
            $number_products[$row['Product Sales Type']]=$row['num'];

        }
        if (!$tipo)
            return $number_products;
        else if (array_key_exists($tipo,$number_products))
            return $number_products[$tipo];
        else
            return 0;

    }


    function add_image($image_key,$args='') {

        $tmp_images_dir='app_files/pics/';
        $principal='No';
        if (preg_match('/principal/i',$args))
            $principal='Yes';
        $sql=sprintf("select count(*) as num from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where  `Subject Type`='Family' and `Subject Key`=%d",$this->id);
        $res=mysql_query($sql);
        $row=mysql_fetch_array($res,MYSQL_ASSOC );
        $number_images=$row['num'];
        if ($number_images==0)
            $principal='Yes';
        $sql=sprintf("insert into `Image Bridge` values ('Family',%d,%d,%s,'') on duplicate key update `Is Principal`=%s "
                     ,$this->id
                     ,$image_key
                     ,prepare_mysql($principal)
                     ,prepare_mysql($principal)
                    );
        //	print "$sql\n";
        mysql_query($sql);
        $sql=sprintf("select `Image Thumbnail URL`,`Image Small URL`,`Is Principal`,ID.`Image Key`,`Image Caption`,`Image URL`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='Product' and   `Subject Key`=%d and  PIB.`Image Key`=%d"
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
    function load_images() {
        $sql=sprintf("select PIB.`Is Principal`,ID.`Image Key`,`Image Caption`,`Image URL`,`Image Thumbnail URL`,`Image Small URL`,`Image Large URL`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='Family' and `Subject Key`=%d",$this->id);

        //      print $sql;
        $res=mysql_query($sql);
        $this->images=array();



        while ($row=mysql_fetch_array($res,MYSQL_ASSOC )) {

            $this->images[$row['Image Key']]=$row;

        }


    }
    function load_images_slidesshow() {
        $sql=sprintf("select `Image Thumbnail URL`,`Image Small URL`,`Is Principal`,ID.`Image Key`,`Image Caption`,`Image URL`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Subject Type`='Family' and   `Subject Key`=%d",$this->id);
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
    function update_main_image() {

        $this->load_images();
        print_r($this->images);
        $num_images=count($this->images);
        $main_image_src='art/nopic.png';
        if ($num_images>0) {

            //print_r($this->images_original);
            foreach( $this->images as $image ) {
                // print_r($image);
                $main_image_src=$image['Image Small URL'];
                if ($image['Is Principal']=='Yes') {

                    break;
                }
            }
        }

        $sql=sprintf("update `Product Family Dimension` set `Product Family Main Image`=%s  where `Product Family Key`=%d",
                     prepare_mysql($main_image_src)
                     ,$this->id
                    );
        // print "$sql\n";
        mysql_query($sql);
    }


    function get_page_data() {
    
        $data=array();
        $sql=sprintf("select * from `Page Store Dimension` PSD left join `Page Dimension` PD on (PSD.`Page Key`=PD.`Page Key`) where PSD.`Page Key`=%d",$this->data['Product Family Page Key']);
        // print $sql;
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res,MYSQL_ASSOC)) {
            $data=$row;
                return $data;

        }else
        return array();




    }


    function create_page($data) {


        $store=new Store($this->data['Product Family Store Key']);
        $store_page_data=$store->get_page_data();

        //      print_r($store_page_data);

        if (!array_key_exists('Showcases',$data)) {

            $showcases=array();

            if ($store_page_data['Display Presentation']='Yes'  ) {
                $showcases['Presentation']=array('Display'=>true,'Type'=>'Template','Contents'=>$this->data['Product Family Name']);
            }
            if ($store_page_data['Display Offers']='Yes' ) {
                $showcases['Offers']=array('Display'=>true,'Type'=>'Auto');
            }
            if ($store_page_data['Display New Products']='Yes' ) {
                $showcases['New']=array('Display'=>true,'Type'=>'Auto');
            }
        } else
            $showcases=$data['Showcases'];


        if (!array_key_exists('Product Layouts',$data)) {

            $product_layouts=array();

            if ($store_page_data['Product Thumbnails Layout']='Yes' ) {
                $product_layouts['Thumbnails']=array('Display'=>true,'Type'=>'Auto');
            }

            if ($store_page_data['Product List Layout ']='Yes' ) {
                $product_layouts['List']=array('Display'=>true,'Type'=>'Auto');
            }

            if ($store_page_data['Product Slideshow Layout']='Yes' ) {
                $product_layouts['Slideshow']=array('Display'=>true,'Type'=>'Auto');
            }
            if ($store_page_data['Product Manual Layout']='Yes' ) {
                $product_layouts['Manual']=array('Display'=>true,'Type'=>$store_page_data['Product Manual Layout Type'],'Data'=>$store_page_data['Product Manual Layout Data']);
            }

            if (count($product_layouts==0)) {
                $product_layouts['Thumbnails']=array('Display'=>true,'Type'=>'Auto');
            }
        } else
            $product_layouts=$data['Product Layouts'];

        if (!array_key_exists('Showcases Layout',$data))
            $showcases_layout=$store_page_data['Showcases Layout'];
        else
            $showcases_layout=$data['Showcases Layout'];






        $page_data=array(
                       'Page Code'=>'PD_'.$store->data['Store Code'].'_'.$this->data['Product Family Code']
                                   ,'Page Source Template'=>''
                                                           ,'Page URL'=>'family.php?code='.$this->data['Product Family Code']
                                                                       ,'Page Source Template'=>'pages/'.$store->data['Store Code'].'/family.tpl'
                                                                                               ,'Page Description'=>'Family Showcase Page'
                                                                                                                   ,'Page Title'=>$this->data['Product Family Name']
                                                                                                                                 ,'Page Short Title'=>$this->data['Product Family Name']
                                                                                                                                                     ,'Page Store Title'=>$this->data['Product Family Name']
                                                                                                                                                                         ,'Page Store Subtitle'=>''
                                                                                                                                                                                                ,'Page Store Slogan'=>$data['Page Store Slogan']
                                                                                                                                                                                                                     ,'Page Store Abstract'=>$data['Page Store Resume']
                                                                                                                                                                                                                                            ,'Page Store Showcases'=>$showcases
                                                                                                                                                                                                                                                                    ,'Page Store Showcases Layout'=>$showcases_layout
                                                                                                                                                                                                                                                                                                   ,'Page Store Product Layouts'=>$product_layouts
                   );

        $page_data['Page Store Function']='Family Catalogue';
        $page_data['Page Store Creation Date']=date('Y-m-d H:i:s');
        $page_data['Page Store Last Update Date']=date('Y-m-d H:i:s');
        $page_data['Page Store Last Structural Change Date']=date('Y-m-d H:i:s');
        $page_data['Page Section']='catalogue';
        $page_data['Page Type']='Store';
        $page_data['Page Store Source Type'] ='Dynamic';
        $page_data['Page Store Code']=$store->data['Store Code'];
        $page_data['Page Parent Key']=$this->id;


        $page=new Page('find',$page_data,'create');
//print_r($page);
//exit;
        $sql=sprintf("update `Product Family Dimension` set `Product Family Page Key`=%d  where `Product Family Key`=%d",$page->id,$this->id);
        mysql_query($sql);

    }


function has_layout($type){


if(!$this->data['Family Page Key'])
return false;
if(!$this->page_data){
    $this->page_data=$this->get_page_data();
    if(!$this->page_data)
        return false;
}

switch ($type) {
    case "thumbnails":
        if($this->page_data['Product Thumbnails Layout']=='Yes')
            return true;
        break;
        case "list":
        case "lists":
        if($this->page_data['Product List Layout']=='Yes')
            return true;
        break;    
         
        case "slideshow":
        if($this->page_data['Product Slideshow Layout']=='Yes')
            return true;
        break;    
        case "manual":
        if($this->page_data['Product Manual Layout']=='Yes')
            return true;
        break;    
    default:
        return false;
        break;
}

return false;
}

}
?>
