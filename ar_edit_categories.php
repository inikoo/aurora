<?php
require_once 'common.php';
require_once 'ar_edit_common.php';
include_once('class.Category.php');

if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('new_category'):
    $data=prepare_values($_REQUEST,array(
                             'name'=>array('type'=>'string'),
                             'subject'  =>array('type'=>'string'),
                             'store_key'  =>array('type'=>'number'),
                             'parent_key'  =>array('type'=>'number')
                         ));


    add_category($data);
    break;
case('edit_subcategory'):
    $data=prepare_values($_REQUEST,array('id'=>array('type'=>'key'),'newvalue' =>array('type'=>'string'),'key' =>array('type'=>'string_value')));
    edit_categories($data);
    break;

case('edit_categories'):
    $data=prepare_values($_REQUEST,array('id'=>array('type'=>'key'),'newvalue' =>array('type'=>'string'),'key' =>array('type'=>'string_value')));
    edit_categories($data);
    break;
case('edit_category'):
   $data=prepare_values($_REQUEST,array('category_key'=>array('type'=>'key'),'newvalue' =>array('type'=>'string'),'key' =>array('type'=>'string_value')));

   edit_category($data);
    break;
case('edit_subcategory'):
    edit_subcategory();
    break;
case('edit_product_category_list'):
    list_edit_product_categories();
    break;
case('edit_customer_category_list'):
    list_edit_customer_categories();
    break;
case('edit_supplier_category_list'):
    list_edit_supplier_categories();
    break;
case('delete_subcategory'):
    $data=prepare_values($_REQUEST,array(
                             'id'=>array('type'=>'key')
                                  ,'delete_type'=>array('type'=>'string')
                         ));
    delete_categories($data);
    break;

case('delete_categories'):
    $data=prepare_values($_REQUEST,array(
                             'id'=>array('type'=>'key')
                                  ,'delete_type'=>array('type'=>'string')
                         ));
    delete_categories($data);
    break;

}


function add_category($raw_data) {



    $data=array(
              'Category Name'=>$raw_data['name'],
              'Category Subject'=>$raw_data['subject'],
              'Category Store Key'=>   $raw_data['store_key'],
              'Category Parent Key'=>$raw_data['parent_key'],
          );

    $category=new Category('find create',$data);



    if ($category->new) {
        $response= array('state'=>200,'action'=>'created','category_key'=>$category->id);
    } else {
        if ($category->found)
            $response= array('state'=>400,'action'=>'found','category_key'=>$category->found_key);
        else
            $response= array('state'=>400,'action'=>'error','category_key'=>0,'msg'=>$category->msg);
    }


    echo json_encode($response);


}
function list_edit_product_categories() {
    $conf=$_SESSION['state']['categories']['table'];

    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
        
        
     if (isset( $_REQUEST['nr']))
        $number_results=$_REQUEST['nr'];
    else
        $number_results=$conf['nr'];   

 

    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    $subject=$_SESSION['state']['categories']['subject'];
    $subject_key=$_SESSION['state']['categories']['subject_key'];
    $parent_key=$_SESSION['state']['categories']['parent_key'];
    $store_key=$_SESSION['state']['categories']['store_key'];

    $_SESSION['state']['categories']['table']['order']=$order;
    $_SESSION['state']['categories']['table']['order_dir']=$order_direction;
    $_SESSION['state']['categories']['table']['nr']=$number_results;
    $_SESSION['state']['categories']['table']['sf']=$start_from;
    $_SESSION['state']['categories']['table']['where']=$where;
    $_SESSION['state']['categories']['table']['f_field']=$f_field;
    $_SESSION['state']['categories']['table']['f_value']=$f_value;






    $where=sprintf("where   `Category Store Key`=%d  and `Category Subject`=%s and  `Category Parent Key`=%d ",
    $store_key,prepare_mysql($subject),$parent_key);
    if ($subject_key) {
        $where.=sprintf("and `Category Subject Key`=%d",$subject_key); ;
    }




    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Category Name` like '%".addslashes($f_value)."%'";




    $sql="select count(*) as total   from `Category Dimension`   $where $wheref";

    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {
        $total=$row['total'];
    }
    mysql_free_result($res);

//exit;
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {


        $sql="select count(*) as total  from `Category Dimension`    $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('category','categories',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {

        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any category with name like ")." <b>*".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {

        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('categories with name like')." <b>*".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;



    $order='`Category Name`';





    $sql="select *  from `Category Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
    //print $sql;
    $res = mysql_query($sql);
$adata=array();
while($row=mysql_fetch_assoc($res)){

    $name=$row['Category Name'];

    $delete='<img src="art/icons/delete.png"/>';
    $adata[]=array(
                 'go'=>sprintf("<a href='edit_product_category.php?store_id=%d&id=%d'><img src='art/icons/page_go.png' alt='go'></a>",
                 $row['Category Store Key'],
                 $row['Category Key']),
                 'id'=>$row['Category Key'],
                 'name'=>$name,

                 'delete'=>$delete,
                           'delete_type'=>'delete'

             );
}
mysql_free_result($res);



$response=array('resultset'=>
                            array('state'=>200,
                                  'data'=>$adata,
                                  'sort_key'=>$_order,
                                  'sort_dir'=>$_dir,
                                  'tableid'=>$tableid,
                                  'filter_msg'=>$filter_msg,
                                  'rtext'=>$rtext,
                                  'rtext_rpp'=>$rtext_rpp,
                                  'total_records'=>$total_records,
                                  'records_offset'=>$start_from,
                                  'records_perpage'=>$number_results,
                                 )
               );
echo json_encode($response);
}

function list_edit_customer_categories() {
    $conf=$_SESSION['state']['categories']['table'];

    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
        
        
     if (isset( $_REQUEST['nr']))
        $number_results=$_REQUEST['nr'];
    else
        $number_results=$conf['nr'];   

 

    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    $subject=$_SESSION['state']['categories']['subject'];
    $subject_key=$_SESSION['state']['categories']['subject_key'];
    $parent_key=$_SESSION['state']['categories']['parent_key'];
    $store_key=$_SESSION['state']['categories']['store_key'];

    $_SESSION['state']['categories']['table']['order']=$order;
    $_SESSION['state']['categories']['table']['order_dir']=$order_direction;
    $_SESSION['state']['categories']['table']['nr']=$number_results;
    $_SESSION['state']['categories']['table']['sf']=$start_from;
    $_SESSION['state']['categories']['table']['where']=$where;
    $_SESSION['state']['categories']['table']['f_field']=$f_field;
    $_SESSION['state']['categories']['table']['f_value']=$f_value;






    $where=sprintf("where   `Category Store Key`=%d  and `Category Subject`=%s and  `Category Parent Key`=%d ",
    $store_key,prepare_mysql($subject),$parent_key);
    if ($subject_key) {
        $where.=sprintf("and `Category Subject Key`=%d",$subject_key); ;
    }




    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Category Name` like '%".addslashes($f_value)."%'";




    $sql="select count(*) as total   from `Category Dimension`   $where $wheref";

    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {
        $total=$row['total'];
    }
    mysql_free_result($res);

//exit;
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {


        $sql="select count(*) as total  from `Category Dimension`    $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('category','categories',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {

        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any category with name like ")." <b>*".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {

        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('categories with name like')." <b>*".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;



    $order='`Category Name`';





    $sql="select *  from `Category Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
//print $sql;
    $res = mysql_query($sql);
$adata=array();
while($row=mysql_fetch_assoc($res)){

    $name=$row['Category Name'];

    $delete='<img src="art/icons/delete.png"/>';
    $adata[]=array(
                 'go'=>sprintf("<a href='edit_customer_category.php?store_id=%d&id=%d'><img src='art/icons/page_go.png' alt='go'></a>",
                 $row['Category Store Key'],
                 $row['Category Key']),
                 'id'=>$row['Category Key'],
                 'name'=>$name,

                 'delete'=>$delete,
                           'delete_type'=>'delete'

             );
}
mysql_free_result($res);



$response=array('resultset'=>
                            array('state'=>200,
                                  'data'=>$adata,
                                  'sort_key'=>$_order,
                                  'sort_dir'=>$_dir,
                                  'tableid'=>$tableid,
                                  'filter_msg'=>$filter_msg,
                                  'rtext'=>$rtext,
                                  'rtext_rpp'=>$rtext_rpp,
                                  'total_records'=>$total_records,
                                  'records_offset'=>$start_from,
                                  'records_perpage'=>$number_results,
                                 )
               );
echo json_encode($response);
}


function list_edit_supplier_categories() {
    $conf=$_SESSION['state']['categories']['table'];

    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
        
        
     if (isset( $_REQUEST['nr']))
        $number_results=$_REQUEST['nr'];
    else
        $number_results=$conf['nr'];   

 

    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    $subject=$_SESSION['state']['categories']['subject'];
    $subject_key=$_SESSION['state']['categories']['subject_key'];
    $parent_key=$_SESSION['state']['categories']['parent_key'];
    $store_key=$_SESSION['state']['categories']['store_key'];

    $_SESSION['state']['categories']['table']['order']=$order;
    $_SESSION['state']['categories']['table']['order_dir']=$order_direction;
    $_SESSION['state']['categories']['table']['nr']=$number_results;
    $_SESSION['state']['categories']['table']['sf']=$start_from;
    $_SESSION['state']['categories']['table']['where']=$where;
    $_SESSION['state']['categories']['table']['f_field']=$f_field;
    $_SESSION['state']['categories']['table']['f_value']=$f_value;






    $where=sprintf("where   `Category Store Key`=%d  and `Category Subject`=%s and  `Category Parent Key`=%d ",
    $store_key,prepare_mysql($subject),$parent_key);
    if ($subject_key) {
        $where.=sprintf("and `Category Subject Key`=%d",$subject_key); ;
    }




    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Category Name` like '%".addslashes($f_value)."%'";




    $sql="select count(*) as total   from `Category Dimension`   $where $wheref";

    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {
        $total=$row['total'];
    }
    mysql_free_result($res);

//exit;
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {


        $sql="select count(*) as total  from `Category Dimension`    $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('category','categories',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {

        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any category with name like ")." <b>*".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {

        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('categories with name like')." <b>*".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;



    $order='`Category Name`';





    $sql="select *  from `Category Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
// print $sql;
    $res = mysql_query($sql);
$adata=array();
while($row=mysql_fetch_assoc($res)){

    $name=$row['Category Name'];

    $delete='<img src="art/icons/delete.png"/>';
    $adata[]=array(
                 'go'=>sprintf("<a href='edit_supplier_category.php?store_id=%d&id=%d'><img src='art/icons/page_go.png' alt='go'></a>",
                 $row['Category Store Key'],
                 $row['Category Key']),
                 'id'=>$row['Category Key'],
                 'name'=>$name,

                 'delete'=>$delete,
                           'delete_type'=>'delete'

             );
}
mysql_free_result($res);



$response=array('resultset'=>
                            array('state'=>200,
                                  'data'=>$adata,
                                  'sort_key'=>$_order,
                                  'sort_dir'=>$_dir,
                                  'tableid'=>$tableid,
                                  'filter_msg'=>$filter_msg,
                                  'rtext'=>$rtext,
                                  'rtext_rpp'=>$rtext_rpp,
                                  'total_records'=>$total_records,
                                  'records_offset'=>$start_from,
                                  'records_perpage'=>$number_results,
                                 )
               );
echo json_encode($response);
}



function edit_categories($data) {
    $category=new Category($data['id']);

    $translate_keys=array('id'=>'Category Key','name'=>'Category Name');

//if($data['key']=='name'){$data['key']='Category Name';}
    $category->update(array($translate_keys[$data['key']]=>$data['newvalue']));//print($data['key']);
    if ($category->updated) {
        $response=array('state'=>200,'action'=>'updated','key'=>$data['key'],'newvalue'=>$category->new_value);
    } else {
        $response=array('state'=>200,'action'=>'nochange','key'=>$data['key'],'newvalue'=>$data['newvalue']);
    }
    echo json_encode($response);
}

function edit_category($data) {
    $category=new Category($data['category_key']);
    $translate_keys=array('category_key'=>'Category Key','name'=>'Category Name');
    $category->update(array($translate_keys[$data['key']]=>$data['newvalue']));//print($data['key']);
    if ($category->updated) {
        $response=array('state'=>200,'action'=>'updated','key'=>$data['key'],'newvalue'=>$category->new_value);
    } else {
        $response=array('state'=>200,'action'=>'nochange','key'=>$data['key'],'newvalue'=>$data['newvalue']);
    }
    echo json_encode($response);
}



function delete_categories($data) {
    include_once('class.Category.php');
    global $editor;
    $subject=new Category($data['id']);
    if (!$subject->id) {
        $response=array('state'=>400,'msg'=>'Category not found');
        echo json_encode($response);
        return;
    }
    $subject->editor=$editor;
    $subject->delete();
    if ($subject->deleted) {
        $action='deleted';
        $msg=_('Area deleted');

    } else {
        $action='nochage';
        $msg=_('Area could not be deleted');
    }
    $response=array('state'=>200,'action'=>$action);
    echo json_encode($response);
}
