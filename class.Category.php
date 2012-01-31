<?php
/*
 File: Category.php

 This file contains the Category Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.Node.php');

class Category extends DB_Table {

    function Category($a1,$a2=false,$a3=false) {

        $this->table_name='Category';
        $this->ignore_fields=array('Category Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id',$a1);
        } else if (($a1=='new' or $a1=='create') and is_array($a2) ) {
            $this->find($a2,'create');

        }
        elseif(preg_match('/find/i',$a1))
        $this->find($a2,$a1);
        else
            $this->get_data($a1,$a2,$a3);

    }

    function get_data($tipo,$tag,$tag2=false) {
        switch ($tipo) {
        case 'name_store':
            $sql=sprintf("select * from `Category Dimension` where `Category Name`=%s and `Category Store Key`=%d",prepare_mysql($tag),$tag2);
            break;
        default:
            $sql=sprintf("select * from `Category Dimension` where `Category Key`=%d",$tag);

            break;
        }
        $result=mysql_query($sql);
        //print $sql;
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)  ) {
            $this->id=$this->data['Category Key'];
        }
    }


    function find($raw_data,$options) {

        if (isset($raw_data['editor']) and is_array($raw_data['editor'])) {
            foreach($raw_data['editor'] as $key=>$value) {
                if (array_key_exists($key,$this->editor))
                    $this->editor[$key]=$value;

            }
        }

        $this->candidate=array();
        $this->found=false;
        $this->found_key=0;
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
                $data[$key]=$value;

        }

        if (!$data['Category Store Key'] and $data['Category Parent Key']) {
            $parent_category=new Category($data['Category Parent Key']);
            $data['Category Store Key']=$parent_category->data['Category Store Key'];
        }

        $fields=array();


        $sql=sprintf("select `Category Key` from `Category Dimension` where  `Category Parent Key`=%d and `Category Store Key`=%d and `Category Name`=%s ",
                     $data['Category Parent Key'],
                     $data['Category Store Key'],
                     prepare_mysql($data['Category Name'])

                    );
        //print_r($fields);
        foreach($fields as $field) {
            $sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
        }


        // print "$sql";

        $result=mysql_query($sql);
        $num_results=mysql_num_rows($result);
        if ($num_results==1) {
            $row=mysql_fetch_array($result, MYSQL_ASSOC);
            $this->found=true;
            $this->found_key=$row['Category Key'];

        }
        if ($this->found) {
            $this->get_data('id',$this->found_key);
        }

        if ($create and !$this->found) {
            $this->create($data);

        }


    }



    function create($data) {

        if ($data['Category Label']=='' ) {
            $data['Category Label']=$data['Category Name'];
        }

//print_r($data);

// $data=array('`Category Name`'=>$data['Category Name']);

        $nodes=new Nodes('`Category Dimension`');
        $nodes->add_new($data['Category Parent Key'] , $data);

        if ($nodes->id) {
            $this->get_data('id',$nodes->id);
            //print_r($this->data);

            if ($this->data['Category Parent Key']==0) {
                $abstract=_('Category')." (".$this->data['Category Subject'].")  ".$this->data['Category Name']." "._('Created');
                $details=_trim(_('New Category')." (".$this->data['Category Subject'].")  \"".$this->data['Category Name']."\"  "._('added'));
            } else {
                $abstract=_('Category')." (".$this->data['Category Subject'].")  ".$this->data['Category Name']." "._('Created');
                $details=_trim(_('New Category')." ".$this->data['Category Subject'].") \"".$this->data['Category Name']."\"  "._('added'));

            }


            $history_data=array(
                              'History Abstract'=>$abstract,
                              'History Details'=>$details,
                              'Indirect Object Key'=>$this->data['Category Parent Key'],
                              'Indirect Object'=>'Category',

                              'Action'=>'created'
                          );
            $this->add_history($history_data);
            $this->new=true;
            if ($this->data['Category Subject']=='Invoice') {
                $sql=sprintf("insert into `Invoice Category Dimension` (`Category Key`,`Store Key`) values (%d,%d)",$this->id,$this->data['Category Store Key']);
                mysql_query($sql);
            }
            elseif($this->data['Category Subject']=='Supplier') {
                $sql=sprintf("insert into `Supplier Category Dimension` (`Category Key`) values (%d)",$this->id);
                mysql_query($sql);
            }elseif($this->data['Category Subject']=='Part') {
                $sql=sprintf("insert into `Part Category Dimension` (`Category Key`) values (%d)",$this->id);
                mysql_query($sql);
            }



            $parent_category=new Category($data['Category Parent Key']);

            if ($parent_category->id) {
                $parent_category->update_children_data();
            }


        }

    }

    function get($key='') {

        if (isset($this->data[$key]))
            return $this->data[$key];

        switch ($key) {

        }

        return false;
    }

    function get_smarty_tree($link=false) {
        if (!$link)
            return;

//print_r($this);
        $category_keys=preg_split('/\>/',preg_replace('/\>$/','',$this->data['Category Position']));

        $sql=sprintf("select `Category Name`,`Category Key` from `Category Dimension` where `Category Key` in (%s)",join(',',$category_keys));
//print $sql;
        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $category_data[$row['Category Key']]=$row['Category Name'];
        }
        $tree='';
        foreach($category_keys as $key) {
            if (array_key_exists($key, $category_data)) {
                $tree.=sprintf(" <a href='%s?id=%d'>%s</a> &rarr;",$link,$key,$category_data[$key]);
            }
        }
        $tree=preg_replace('/\s*\&rarr\;$/', '', $tree);
        return $tree;

    }






    function delete() {
        $this->deleted=false;
        /* if($this->data['Company Area Number Employees']>0){
        $this->msg=_('Company Area could not be deleted because').' '.gettext($this->data['Company Area Number Employees'],'employee','employees').' '.gettext($this->data['Company Area Number Employees'],'is','are').' '._('associated with it');
        return;
        }

        $this->load_positions();
        foreach($this->positions as $position_key=>$position){
            $position=new CompanyPosition($position_key);
            $position->editor=$this->editor;
            $position->delete();
        }
        $this->load_departments();
        foreach($this->departments as $department_key=>$department){
            $department=new CompanyArea($department_key);
            $department->editor=$this->editor;
            $department->delete();
        }

        */

        $sql=sprintf('delete from `Category Dimension` where `Category Key`=%d',$this->id);
        mysql_query($sql);

        $history_data=array(
                          'History Abstract'=>_('Category deleted').' ('.$this->data['Category Name'].')'
                                             ,'History Details'=>_trim(_('Category')." ".$this->data['Category Name'].' ('.$this->data['Category Key'].') '._('has been permanently') )
                                                                ,'Action'=>'deleted'
                      );
        $this->add_history($history_data);
        $this->deleted=true;

    }

    function sub_category_selected_by_subject($subject_key) {
        $sub_category_keys_selected=array();
        $sql=sprintf("select C.`Category Key` from `Category Bridge` B left join `Category Dimension` C on (C.`Category Key`=B.`Category Key`) where `Category Subject`=%s and `Subject Key`=%d and `Category Parent Key`=%d",
                     prepare_mysql($this->data['Category Subject']),
                     $subject_key,
                     $this->id
                    );
        $res=mysql_query($sql);
        //print $sql;
        while ($row=mysql_fetch_assoc($res)) {
            $sub_category_keys_selected[$row['Category Key']]=$row['Category Key'];
        }
        return $sub_category_keys_selected;
    }


    function get_children_keys() {
        $sql = sprintf("SELECT `Category Key`   FROM `Category Dimension` WHERE `Category Parent Key`=%d ",
                       $this->id
                      );

        $res=mysql_query($sql);
        $children_keys=array();
        while ($row=mysql_fetch_assoc($res)) {
            $children_keys[$row['Category Key']]=$row['Category Key'];
        }
        return $children_keys;

    }




    function get_children_objects() {
        $sql = sprintf("SELECT `Category Key`   FROM `Category Dimension` WHERE `Category Parent Key`=%d order by `Category Name` ",
                       $this->id
                      );
        //  print $sql;
        $res=mysql_query($sql);
        $children_keys=array();
        while ($row=mysql_fetch_assoc($res)) {
            $children_keys[$row['Category Key']]=new Category($row['Category Key']);
        }
        
        return $children_keys;

    }








    function update_children_data() {

        $number_of_children=0;



        $sql = sprintf("SELECT COUNT(*)  as num  FROM `Category Dimension` WHERE `Category Parent Key`=%d and `Category Subject`=%s ",
                       $this->id,
                       prepare_mysql($this->data['Category Subject'])
                      );
        $res=mysql_query($sql);
        $number_of_children=0;
        if ($row=mysql_fetch_assoc($res)) {
            $number_of_children=$row['num'];
        }

        //print "$sql\n";

        $max_deep=0;
        if ($number_of_children) {

            $sql = sprintf("SELECT `Category Position`  FROM `Category Dimension` WHERE `Category Position`	RLIKE '^%s[0-9]+>$' and `Category Subject`=%s ",
                           $this->data['Category Position'],
                           prepare_mysql($this->data['Category Subject'])
                          );



            $res=mysql_query($sql);

            $max_deep=0;
            while ($row=mysql_fetch_assoc($res)) {
                $deep=count(preg_split('/\>/',$row['Category Position']))-2;
                if ($deep>$max_deep)
                    $max_deep=$deep;

            }

        }

        $sql=sprintf("update `Category Dimension` set `Category Children`=%d ,`Category Children Deep`=%d where `Category Key`=%d ",
                     $number_of_children,
                     $max_deep,
                     $this->id
                    );
        mysql_query($sql);

    }

    function update_number_of_subjects() {
        $sql=sprintf("select COUNT(DISTINCT `Subject Key`)  as num from `Category Bridge`  where `Category Key`=%d  ",
                     $this->id
                    );
        $res=mysql_query($sql);
        $num=0;
        if ($row=mysql_fetch_assoc($res)) {
            $num=$row['num'];
        }
        $sql=sprintf("update `Category Dimension` set `Category Number Subjects`=%d where `Category Key`=%d ",
                     $num,
                     $this->id
                    );
        mysql_query($sql);

        $this->update_no_assigned_subjects();
        //        print "$sql\n";
    }



    function update_no_assigned_subjects() {
        $children_keys=$this->get_children_keys();
        $no_assigned_subjects=0;
        $assigned_subjects=0;

        if (count($children_keys)>0) {


            switch ($this->data['Category Subject']) {

            default:
                $table=$this->data['Category Subject'];
                $store=sprintf(" where `%s Store Key`=%d",
                               addslashes($this->data['Category Subject']),
                               $this->data['Category Store Key']);
                break;
            }

            $sql=sprintf("select count(*) as num from `%s Dimension` %s",$table,$store);

            $res=mysql_query($sql);
            if ($row=mysql_fetch_assoc($res)) {
                $total_subjects=$row['num'];

                $sql=sprintf("select COUNT(DISTINCT `Subject Key`)  as num from `Category Bridge`  where `Category Key` in (%s)  ",
                             join(',',$children_keys)
                            );
                $res=mysql_query($sql);
                $assigned_subjects=0;
                if ($row=mysql_fetch_assoc($res)) {
                    $assigned_subjects=$row['num'];
                }
                $no_assigned_subjects=$total_subjects-$assigned_subjects;


            }

        }

        $sql=sprintf("update `Category Dimension` set `Category Children Subjects Not Assigned`=%d,`Category Children Subjects Assigned`=%d where `Category Key`=%d ",
                     $no_assigned_subjects,
                     $assigned_subjects,
                     $this->id
                    );
        // print "$sql\n";
        mysql_query($sql);

    }



    function update_sales_old() {
        $sql="select * from `Store Dimension`";
        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $this->update_sales_store($row['Store Key']);
        }
        mysql_free_result($result);
    }



    function update_product_data_old() {
        $sql="select * from `Store Dimension`";
        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $this->update_store_product_data($row['Store Key']);
        }
        mysql_free_result($result);
    }

    function update_sales_store_old($store_key) {
        // print_r($this->data);

        if ($this->data['Category Subject']!='Product')
            return;


        $on_sale_days=0;

        $sql=sprintf("select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as tto, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P left join `Category Bridge` as B on (B.`Subject Key`=P.`Product ID`)  where `Subject`='Product' and `Category Key`=%d and `Product Store Key`=%d",$this->id,$store_key);
        //print "$sql\n";
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

        $sql=sprintf("select count(Distinct `Order Key`) as pending_orders   from `Order Transaction Fact` OTF left join `Product History Dimension` PH on (PH.`Product Key`=OTF.`Product Key`)  left join `Category Bridge` B  on  (B.`Subject Key`=PH.`Product ID`)   where `Subject`='Product' and  `Current Dispatching State` not in ('Unknown','Dispatched','Cancelled')  and  `Category Key`=%d and `Store Key`=%d",$this->id,$store_key);
        //print $sql;
        $result=mysql_query($sql);
        $pending_orders=0;
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $pending_orders=$row['pending_orders'];
        }
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF   left join `Product History Dimension` PH on (PH.`Product Key`=OTF.`Product Key`)   left join `Category Bridge` B  on  (B.`Subject Key`=PH.`Product ID`)  where `Subject`='Product' and `Category Key`=%d and `Store Key`=%d",$this->id,$store_key);


        //print "$sql\n\n";
        // exit;
        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->data['Product Category Total Invoiced Gross Amount']=$row['gross'];
            $this->data['Product Category Total Invoiced Discount Amount']=$row['disc'];
            $this->data['Product Category Total Invoiced Amount']=$row['gross']-$row['disc'];

            $this->data['Product Category Total Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
            $this->data['Product Category Total Quantity Ordered']=$row['ordered'];
            $this->data['Product Category Total Quantity Invoiced']=$row['invoiced'];
            $this->data['Product Category Total Quantity Delivered']=$row['delivered'];
            $this->data['Product Category Total Days On Sale']=$on_sale_days;
            $this->data['Product Category Valid From']=$_from;
            $this->data['Product Category Valid To']=$_to;
            $this->data['Product Category Total Customers']=$row['customers'];
            $this->data['Product Category Total Invoices']=$row['invoices'];
            $this->data['Product Category Total Pending Orders']=$pending_orders;

            $sql=sprintf("update `Product Category Dimension` set `Product Category Total Invoiced Gross Amount`=%s,`Product Category Total Invoiced Discount Amount`=%s,`Product Category Total Invoiced Amount`=%s,`Product Category Total Profit`=%s, `Product Category Total Quantity Ordered`=%s , `Product Category Total Quantity Invoiced`=%s,`Product Category Total Quantity Delivered`=%s ,`Product Category Total Days On Sale`=%f ,`Product Category Valid From`=%s,`Product Category Valid To`=%s ,`Product Category Total Customers`=%d,`Product Category Total Invoices`=%d,`Product Category Total Pending Orders`=%d  where `Product Category Key`=%d and `Product Category Store Key`=%d  "
                         ,prepare_mysql($this->data['Product Category Total Invoiced Gross Amount'])
                         ,prepare_mysql($this->data['Product Category Total Invoiced Discount Amount'])
                         ,prepare_mysql($this->data['Product Category Total Invoiced Amount'])

                         ,prepare_mysql($this->data['Product Category Total Profit'])
                         ,prepare_mysql($this->data['Product Category Total Quantity Ordered'])
                         ,prepare_mysql($this->data['Product Category Total Quantity Invoiced'])
                         ,prepare_mysql($this->data['Product Category Total Quantity Delivered'])
                         ,$on_sale_days
                         ,prepare_mysql($this->data['Product Category Valid From'])
                         ,prepare_mysql($this->data['Product Category Valid To'])
                         ,$this->data['Product Category Total Customers']
                         ,$this->data['Product Category Total Invoices']
                         ,$this->data['Product Category Total Pending Orders']
                         ,$this->id
                         ,$store_key
                        );
            //  print "$sql\n";
            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");
        }
        // days on sale


        return;

        $on_sale_days=0;



        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from  `Product Dimension` as P left join `Product Category Bridge` as B on (B.`Product Key`=P.`Product Key`)  where `Category Key`=".$this->id;
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
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
                     ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced
                     from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));

        $result=mysql_query($sql);

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
            //  print "$sql\n";


            if (!mysql_query($sql))
                exit("$sql\ncan not update dept sales\n");
        }
        // exit;
        $on_sale_days=0;


        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Store Key`=".$this->id;

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
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
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

        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Store Key`=".$this->id;
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
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross
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

        $on_sale_days=0;
        $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales Type`!='Not for Sale',1,0)) as for_sale   from `Product Dimension` as P where `Product Store Key`=".$this->id;
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
        $sql=sprintf("select    count(Distinct `Customer Key`)as customers ,count(Distinct `Invoice Key`)as invoices ,  sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross   ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced   from `Order Transaction Fact`  OTF    where `Store Key`=%d and  `Invoice Date`>=%s",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));
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


    function update_store_product_data_old($store_key) {
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
        if ($this->data['Category Subject']!='Product')
            return;

        $sql=sprintf("select sum(if(`Product Stage`='In process',1,0)) as in_process, sum(if(`Product Availability Type`='Discontinued',1,0)) as discontinued,sum(if(`Product Sales Type`='Not for sale',1,0)) as not_for_sale,sum(if(`Product Sales Type`='Public Sale',1,0)) as public_sale,sum(if(`Product Sales Type`='Private Sale',1,0)) as private_sale,sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal,sum(if(`Product Availability State`='Low',1,0)) as availability_low,sum(if(`Product Availability State`='Surplus',1,0)) as availability_surplus,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from `Product Dimension`left join `Category Bridge` on (`Subject Key`=`Product ID`)  where `Subject`='Product' and   `Product Store Key`=%d and `Category Key`=%d",$store_key,$this->id);


        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $in_process=$row['in_process'];
            $public_sale=$row['public_sale'];
            $private_sale=$row['private_sale'];
            $discontinued=$row['discontinued'];
            $not_for_sale=$row['not_for_sale'];
            $sale_unknown=0;
            $availability_optimal=$row['availability_optimal'];
            $availability_low=$row['availability_low'];
            $availability_critical=$row['availability_critical'];
            $availability_outofstock=$row['availability_outofstock'];
            $availability_unknown=$row['availability_unknown'];
            $availability_surplus=$row['availability_surplus'];



        }

        $sql=sprintf("update `Product Category Dimension` set `Product Category In Process Products`=%d,`Product Category For Sale Products`=%d ,`Product Category Discontinued Products`=%d ,`Product Category Not For Sale Products`=%d ,`Product Category Unknown Sales State Products`=%d, `Product Category Optimal Availability Products`=%d , `Product Category Low Availability Products`=%d ,`Product Category Critical Availability Products`=%d ,`Product Category Out Of Stock Products`=%d,`Product Category Unknown Stock Products`=%d ,`Product Category Surplus Availability Products`=%d where `Product Category Store Key`=%d and `Product Category Key`=%d  ",
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
                     $store_key,
                     $this->id
                    );
        //print "$sql\n";exit;
        mysql_query($sql);

        $this->get_data('id',$this->id);

    }


    function update_subjects_data() {
        $this->update_up_today();
        $this->update_last_period();
        $this->update_last_interval();
    }


    function update_up_today() {

        switch ($this->data['Category Subject']) {
        case 'Invoice':
            $this->update_invoice_category_up_today_sales();
            break;
        case('Supplier'):
            $this->update_supplier_category_up_today_sales();
            break;
        default:

            break;
        }

    }

    function update_last_period() {

        switch ($this->data['Category Subject']) {
        case 'Invoice':
            $this->update_invoice_category_last_period_sales();
            break;
        case('Supplier'):
            $this->update_supplier_category_last_period_sales();
            break;
        default:

            break;
        }

    }


    function update_last_interval() {

        switch ($this->data['Category Subject']) {
        case 'Invoice':
            $this->update_invoice_category_interval_sales();
            break;
        case('Supplier'):
            $this->update_supplier_category_interval_sales();
            break;
        default:

            break;
        }

    }



    function update_supplier_category_up_today_sales() {
        $this->update_supplier_category_sales('Today');
        $this->update_supplier_category_sales('Week To Day');
        $this->update_supplier_category_sales('Month To Day');
        $this->update_supplier_category_sales('Year To Day');
    }

    function update_supplier_category_last_period_sales() {

        $this->update_supplier_category_sales('Yesterday');
        $this->update_supplier_category_sales('Last Week');
        $this->update_supplier_category_sales('Last Month');
    }


    function update_supplier_category_interval_sales() {
        $this->update_supplier_category_sales('Total');
        $this->update_supplier_category_sales('3 Year');
        $this->update_supplier_category_sales('1 Year');
        $this->update_supplier_category_sales('6 Month');
        $this->update_supplier_category_sales('1 Quarter');
        $this->update_supplier_category_sales('1 Month');
        $this->update_supplier_category_sales('10 Day');
        $this->update_supplier_category_sales('1 Week');
    }

    function update_invoice_category_up_today_sales() {
        $this->update_invoice_category_sales('Today');
        $this->update_invoice_category_sales('Week To Day');
        $this->update_invoice_category_sales('Month To Day');
        $this->update_invoice_category_sales('Year To Day');
    }

    function update_invoice_category_last_period_sales() {

        $this->update_invoice_category_sales('Yesterday');
        $this->update_invoice_category_sales('Last Week');
        $this->update_invoice_category_sales('Last Month');
    }


    function update_invoice_category_interval_sales() {
        $this->update_invoice_category_sales('Total');
        $this->update_invoice_category_sales('3 Year');
        $this->update_invoice_category_sales('1 Year');
        $this->update_invoice_category_sales('6 Month');
        $this->update_invoice_category_sales('1 Quarter');
        $this->update_invoice_category_sales('1 Month');
        $this->update_invoice_category_sales('10 Day');
        $this->update_invoice_category_sales('1 Week');
    }


    function update_supplier_category_sales($interval) {

        //  print $interval;

        switch ($interval) {




        case 'Last Month':
            $db_interval='Last Month';
            $from_date=date('Y-m-d 00:00:00',mktime(0,0,0,date('m')-1,1,date('Y')));
            $to_date=date('Y-m-d 00:00:00',mktime(0,0,0,date('m'),1,date('Y')));

            $from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
            $to_1yb=date('Y-m-d H:i:s',strtotime("$to_date -1 year"));
            //  print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";
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




        //   print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";

        $supplier_category_data["$db_interval Acc Cost"]=0;
        $supplier_category_data["$db_interval Acc Part Sales"]=0;
        $supplier_category_data["$db_interval Acc Profit"]=0;


        $sql=sprintf("select sum(`Supplier $db_interval Acc Parts Cost`) as cost, sum(`Supplier $db_interval Acc Parts Sold Amount`) as sold, sum(`Supplier $db_interval Acc Parts Profit`) as profit   from `Category Bridge` B left join  `Supplier Dimension` I  on ( `Subject Key`=`Supplier Key`)  where `Subject`='Supplier' and `Category Key`=%d " ,
                     $this->id


                    );
        $result=mysql_query($sql);

        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $supplier_category_data["$db_interval Acc Cost"]=$row["cost"];
            $supplier_category_data["$db_interval Acc Part Sales"]=$row["sold"];
            $supplier_category_data["$db_interval Acc Profit"]=$row["profit"];

        }

        $sql=sprintf("update `Supplier Category Dimension` set
                     `$db_interval Acc Cost`=%.2f,
                     `$db_interval Acc Part Sales`=%.2f,
                     `$db_interval Acc Profit`=%.2f
                     where `Category Key`=%d "
                     ,$supplier_category_data["$db_interval Acc Cost"]
                     ,$supplier_category_data["$db_interval Acc Part Sales"]
                     ,$supplier_category_data["$db_interval Acc Profit"]
                     ,$this->id
                    );

        mysql_query($sql);

        //     print "$sql\n";

        if ($from_date_1yb) {
            $supplier_category_data["$db_interval Acc 1YB Cost"]=0;
            $supplier_category_data["$db_interval Acc 1YB Part Sales"]=0;
            $supplier_category_data["$db_interval Acc 1YB Profit"]=0;

            $sql=sprintf("select sum(`Supplier $db_interval Acc 1YB Parts Cost`) as cost, sum(`Supplier $db_interval Acc 1YB Parts Sold Amount`) as sold, sum(`Supplier $db_interval Acc 1YB Parts Profit`) as profit   from `Category Bridge` B left join  `Supplier Dimension` I  on ( `Subject Key`=`Supplier Key`)  where `Subject`='Supplier' and `Category Key`=%d " ,
                         $this->id


                        );
            $result=mysql_query($sql);



            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $supplier_category_data["$db_interval Acc 1YB Cost"]=$row["cost"];
                $supplier_category_data["$db_interval Acc 1YB Part Sales"]=$row["sold"];
                $supplier_category_data["$db_interval Acc 1YB Profit"]=$row["profit"];

            }

            $sql=sprintf("update `Supplier Category Dimension` set
                         `$db_interval Acc 1YB Cost`=%.2f,
                         `$db_interval Acc 1YB Part Sales`=%.2f,
                         `$db_interval Acc 1YB Profit`=%.2f
                         where `Category Key`=%d "
                         ,$supplier_category_data["$db_interval Acc 1YB Cost"]
                         ,$supplier_category_data["$db_interval Acc 1YB Part Sales"]
                         ,$supplier_category_data["$db_interval Acc 1YB Profit"]
                         ,$this->id
                        );

            mysql_query($sql);

        }


    }


    function update_invoice_category_sales($interval) {

        $to_date='';

        switch ($interval) {




        case 'Last Month':
            $db_interval='Last Month';
            $from_date=date('Y-m-d 00:00:00',mktime(0,0,0,date('m')-1,1,date('Y')));
            $to_date=date('Y-m-d 00:00:00',mktime(0,0,0,date('m'),1,date('Y')));

            $from_date_1yb=date('Y-m-d H:i:s',strtotime("$from_date -1 year"));
            $to_1yb=date('Y-m-d H:i:s',strtotime("$to_date -1 year"));
            //  print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";
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

        //   print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";

        $invoice_category_data["$db_interval Acc Invoiced Discount Amount"]=0;
        $invoice_category_data["$db_interval Acc Invoiced Amount"]=0;
        $invoice_category_data["$db_interval Acc Invoices"]=0;
        $invoice_category_data["$db_interval Acc Profit"]=0;
        $invoice_category_data["DC $db_interval Acc Invoiced Amount"]=0;
        $invoice_category_data["DC $db_interval Acc Invoiced Discount Amount"]=0;
        $invoice_category_data["DC $db_interval Acc Profit"]=0;

        $sql=sprintf("select  sum(if(`Invoice Title`='Invoice',1,0)) as invoices  ,IFNULL(sum(`Invoice Items Discount Amount`),0) as discounts,IFNULL(sum(`Invoice Total Net Amount`),0) net  ,IFNULL(sum(`Invoice Total Profit`),0) as profit ,IFNULL(sum(`Invoice Items Discount Amount`*`Invoice Currency Exchange`),0) as dc_discounts,IFNULL(sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`),0) dc_net  ,IFNULL(sum(`Invoice Total Profit`*`Invoice Currency Exchange`),0) as dc_profit from `Category Bridge` B left join  `Invoice Dimension` I  on ( `Subject Key`=`Invoice Key`)  where `Subject`='Invoice' and `Category Key`=%d and  `Invoice Store Key`=%d and `Invoice Date`>=%s %s" ,
                     $this->id,
                     $this->data['Category Store Key'],
                     prepare_mysql($from_date),
                     ($to_date?sprintf('and `Invoice Date`<%s',prepare_mysql($to_date)):'')

                    );
        $result=mysql_query($sql);

//print "$sql\n";
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $invoice_category_data["$db_interval Acc Invoiced Discount Amount"]=$row["discounts"];
            $invoice_category_data["$db_interval Acc Invoiced Amount"]=$row["net"];
            $invoice_category_data["$db_interval Acc Invoices"]=$row["invoices"];
            $invoice_category_data["$db_interval Acc Profit"]=$row["profit"];
            $invoice_category_data["DC $db_interval Acc Invoiced Amount"]=$row["dc_net"];
            $invoice_category_data["DC $db_interval Acc Invoiced Discount Amount"]=$row["dc_discounts"];
            $invoice_category_data["DC $db_interval Acc Profit"]=$row["dc_profit"];
        }

        $sql=sprintf("update `Invoice Category Dimension` set
                     `$db_interval Acc Invoiced Discount Amount`=%.2f,
                     `$db_interval Acc Invoiced Amount`=%.2f,
                     `$db_interval Acc Invoices`=%d,
                     `$db_interval Acc Profit`=%.2f
                     where `Category Key`=%d "
                     ,$invoice_category_data["$db_interval Acc Invoiced Discount Amount"]
                     ,$invoice_category_data["$db_interval Acc Invoiced Amount"]
                     ,$invoice_category_data["$db_interval Acc Invoices"]
                     ,$invoice_category_data["$db_interval Acc Profit"]
                     ,$this->id
                    );

        mysql_query($sql);
//print "$sql\n\n";
        $sql=sprintf("update `Invoice Category Dimension` set
                     `DC $db_interval Acc Invoiced Discount Amount`=%.2f,
                     `DC $db_interval Acc Invoiced Amount`=%.2f,
                     `DC $db_interval Acc Profit`=%.2f
                     where `Category Key`=%d "
                     ,$invoice_category_data["DC $db_interval Acc Invoiced Discount Amount"]
                     ,$invoice_category_data["DC $db_interval Acc Invoiced Amount"]
                     ,$invoice_category_data["DC $db_interval Acc Profit"]
                     ,$this->id
                    );

        mysql_query($sql);



        if ($from_date_1yb) {
            $invoice_category_data["$db_interval Acc 1YB Invoices"]=0;
            $invoice_category_data["$db_interval Acc 1YB Invoiced Discount Amount"]=0;
            $invoice_category_data["$db_interval Acc 1YB Invoiced Amount"]=0;
            $invoice_category_data["$db_interval Acc 1YB Profit"]=0;
            $invoice_category_data["DC $db_interval Acc 1YB Invoiced Discount Amount"]=0;
            $invoice_category_data["DC $db_interval Acc 1YB Invoiced Amount"]=0;
            $invoice_category_data["DC $db_interval Acc 1YB Profit"]=0;

            $sql=sprintf("select count(*) as invoices,sum(`Invoice Items Discount Amount`) as discounts,sum(`Invoice Total Net Amount`) net  ,sum(`Invoice Total Profit`) as profit,sum(`Invoice Items Discount Amount`*`Invoice Currency Exchange`) as dc_discounts,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) dc_net  ,sum(`Invoice Total Profit`*`Invoice Currency Exchange`) as dc_profit  from `Category Bridge` B left join  `Invoice Dimension` I  on ( `Subject Key`=`Invoice Key`)  where `Subject`='Invoice' and `Category Key`=%d and  `Invoice Store Key`=%d and  `Invoice Date`>%s and `Invoice Date`<%s" ,
                         $this->id,
                         $this->data['Category Store Key'],
                         prepare_mysql($from_date_1yb),
                         prepare_mysql($to_1yb)
                        );
            // print "$sql\n\n";
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $invoice_category_data["$db_interval Acc 1YB Invoiced Discount Amount"]=$row["discounts"];
                $invoice_category_data["$db_interval Acc 1YB Invoiced Amount"]=$row["net"];
                $invoice_category_data["$db_interval Acc 1YB Invoices"]=$row["invoices"];
                $invoice_category_data["$db_interval Acc 1YB Profit"]=$row["profit"];
                $invoice_category_data["DC $db_interval Acc 1YB Invoiced Amount"]=$row["dc_net"];
                $invoice_category_data["DC $db_interval Acc 1YB Invoiced Discount Amount"]=$row["dc_discounts"];
                $invoice_category_data["DC $db_interval Acc 1YB Profit"]=$row["dc_profit"];
            }

            $sql=sprintf("update `Invoice Category Dimension` set
                         `$db_interval Acc 1YB Invoiced Discount Amount`=%.2f,
                         `$db_interval Acc 1YB Invoiced Amount`=%.2f,
                         `$db_interval Acc 1YB Invoices`=%.2f,
                         `$db_interval Acc 1YB Profit`=%.2f
                         where `Category Key`=%d "
                         ,$invoice_category_data["$db_interval Acc 1YB Invoiced Discount Amount"]
                         ,$invoice_category_data["$db_interval Acc 1YB Invoiced Amount"]
                         ,$invoice_category_data["$db_interval Acc 1YB Invoices"]
                         ,$invoice_category_data["$db_interval Acc 1YB Profit"]
                         ,$this->id
                        );

            mysql_query($sql);
            // print "$sql\n";
            $sql=sprintf("update `Invoice Category Dimension` set
                         `DC $db_interval Acc 1YB Invoiced Discount Amount`=%.2f,
                         `DC $db_interval Acc 1YB Invoiced Amount`=%.2f,
                         `DC $db_interval Acc 1YB Profit`=%.2f
                         where `Category Key`=%d "
                         ,$invoice_category_data["DC $db_interval Acc 1YB Invoiced Discount Amount"]
                         ,$invoice_category_data["DC $db_interval Acc 1YB Invoiced Amount"]
                         ,$invoice_category_data["DC $db_interval Acc 1YB Profit"]
                         ,$this->id
                        );
// print "$sql\n";
            mysql_query($sql);
        }


    }
                     
                     function get_other_categories(){
                     $sql=sprintf("select * from `Category Dimension` where `Is Category Field Other`='Yes' and `Category Subject`='Customer'");
                     $result=mysql_query($sql);
                     while($row=mysql_fetch_assoc($result)){
                           $other_data[$row['Category Parent Key']]=$row['Category Key'];
                           
                        }
             
                           return $other_data;
                     }

function get_other_value($subject,$subject_key){
	$other_values='';
	$sql=sprintf("select ifnull(group_concat(Distinct `Customer Other Note`),'') as other_value from `Category Bridge` B left join  `Category Dimension` C on (C.`Category Key`=B.`Category Key`)  where `Subject`=%s and `Subject Key`=%d and `Customer Other Note`!='' and  `Is Category Field Other`='Yes' and `Category Parent Key`=%d",
prepare_mysql($subject),
$subject_key,
$this->id
);
//print $sql;
	$result=mysql_query($sql);
        if($row=mysql_fetch_assoc($result)){
		$other_value=$row['other_value'];	
	}
return $other_value;
}

function number_of_children_with_other_value($subject,$subject_key){

	$number_of_children=0;	

	$sql=sprintf(" select  count(Distinct C.`Category Key`) as number_of_children    from `Category Dimension` C left join  `Category Bridge` B on (C.`Category Key`=B.`Category Key`)  where  `Subject`=%s and `Subject Key`=%d and `Is Category Field Other`='Yes' and `Category Parent Key`=%d",
prepare_mysql($subject),
$subject_key,
$this->id);
//print $sql;
	$result=mysql_query($sql);
        if($row=mysql_fetch_assoc($result)){
		$number_of_children=$row['number_of_children'];	
	}
return $number_of_children;
}

function get_children_key_is_other_value(){
	$children_key_is_other_value=0;	

	$sql=sprintf(" select `Category Key`    from `Category Dimension` C  where   `Is Category Field Other`='Yes' and `Category Parent Key`=%d",


$this->id);
//print $sql;
	$result=mysql_query($sql);
        if($row=mysql_fetch_assoc($result)){
		$children_key_is_other_value=$row['Category Key'];	
	}
return $children_key_is_other_value;
}

}
