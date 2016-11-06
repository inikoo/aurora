<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 October 2015 at 21:31:00 BST, Birmingham->Malaga (Plane)
 Copyright (c) 2015, Inikoo

 Version 3

*/

$period_tag = get_interval_db_name($parameters['f_period']);


$where = 'where true ';

$table
    = '`Page Store Dimension` PS left join `Page Store Data Dimension` PSD on (PS.`Page Key`=PSD.`Page Key`) left join `Page Dimension` P on (P.`Page Key`=PS.`Page Key`) left join `Site Dimension` S on (S.`Site Key`=PS.`Page Site Key`) ';

switch ($parameters['parent']) {
    case('store'):
        $where .= sprintf(
            ' and `Page Store Key`=%d   ', $parameters['parent_key']
        );
        break;
    case('Website'):
        $where .= sprintf(
            ' and PS.`Page Site Key`=%d', $parameters['parent_key']
        );
        break;
    case('department'):
        $where .= sprintf(
            '  and `Page Parent Key`=%d  and `Page Store Section`="Department Catalogue"  ', $parameters['parent_key']
        );
        break;
    case('product'):
        $where .= sprintf(
            '  and `Page Parent Key`=%d  and `Page Store Section`="Product Description"  ', $parameters['parent_key']
        );
        break;
    case('family'):
        $where .= sprintf(
            '  and `Page Parent Key`=%d  and `Page Store Section`="Family Catalogue"  ', $parameters['parent_key']
        );
        break;
    case('product_form'):
        $where .= sprintf(
            '  and `Product ID`=%d   ', $parameters['parent_key']
        );
        $table .= ' left join `Page Product Dimension` PPD on (PPD.`Page Key`=P.`Page Key`)';
        break;
    default:


}

$group = '';
/*
switch ($elements_type) {
case 'section':


	$_elements='';
	$count_elements=0;
	foreach ($elements_section as $_key=>$_value) {
		if ($_value) {
			$_elements.=',"'.$_key.'"';
			$count_elements++;
		}
	}
	$_elements=preg_replace('/^\,/', '', $_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	}elseif ($count_elements<7) {
		$where.=' and `Page Store Section Type` in ('.$_elements.')' ;
	}


	break;
case 'state':


	$_elements='';
	$count_elements=0;
	foreach ($elements_state as $_key=>$_value) {
		if ($_value) {
			$_elements.=',"'.$_key.'"';
			$count_elements++;
		}
	}
	$_elements=preg_replace('/^\,/', '', $_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	}elseif ($count_elements<2) {
		$where.=' and `Page State` in ('.$_elements.')' ;
	}
	//print count($count_elements);

	break;
case 'flags':


	$_elements='';
	$count_elements=0;
	foreach ($elements_flags as $_key=>$_value) {
		if ($_value) {
			$count_elements++;
			if ($_key=='Blue') {
				$_elements.=",'Blue'";
			}
			elseif ($_key=='Green') {
				$_elements.=",'Green'";
			}
			elseif ($_key=='Orange') {
				$_elements.=",'Orange'";
			}
			elseif ($_key=='Pink') {
				$_elements.=",'Pink'";
			}
			elseif ($_key=='Purple') {
				$_elements.=",'Purple'";
			}
			elseif ($_key=='Red') {
				$_elements.=",'Red'";
			}
			elseif ($_key=='Yellow') {
				$_elements.=",'Yellow'";
			}
		}
	}
	$_elements=preg_replace('/^\,/', '', $_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} elseif ($count_elements<7) {
		$where.=' and `Site Flag` in ('.$_elements.')' ;
	}


	break;

}

*/

$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and `Page Code` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'title' and $f_value != '') {
    $wheref .= " and  `Page Store Title` like '".addslashes($f_value)."%'";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Page Code`';
} elseif ($order == 'url') {
    $order = '`Page URL`';
} elseif ($order == 'period_users') {
    $order = "`Page Store $period_tag Acc Users`";
} elseif ($order == 'period_visitors') {
    $order = "`Page Store $period_tag Acc Visitors`";
} elseif ($order == 'period_sessions') {
    $order = "`Page Store $period_tag Acc Sessions`";
} elseif ($order == 'period_requests') {
    $order = "`Page Store $period_tag Acc Requests`";
} elseif ($order == 'users') {
    $order = "`Page Store Total Acc Users`";
} elseif ($order == 'requests') {
    $order = "`Page Store Total Acc Requests`";
} elseif ($order == 'title') {
    $order = '`Page Store Title`';
} elseif ($order == 'link_title') {
    $order = '`Page Short Title`';
} elseif ($order == 'products') {
    $order = '`Page Store Number Products`';
} elseif ($order == 'list_products') {
    $order = '`Page Store Number List Products`';
} elseif ($order == 'button_products') {
    $order = '`Page Store Number Button Products`';
} elseif ($order == 'products_out_of_stock') {
    $order = '`Page Store Number Out of Stock Products`';
} elseif ($order == 'products_sold_out') {
    $order = '`Page Store Number Sold Out Products`';
} elseif ($order == 'percentage_products_out_of_stock') {
    $order = 'percentage_out_of_stock ';
} elseif ($order == 'type') {
    $order = '`Page Store Section`';
} elseif ($order == 'flag') {
    $order = '`Site Flag`';
} else {
    $order = 'PS.`Page Key`';
}


$sql_totals
    = "select count(Distinct PS.`Page Key`) as num from $table  $where  ";

$fields
    = "*,`Site SSL`,(`Page Store Number Out of Stock Products`/`Page Store Number Products`) as percentage_out_of_stock,`Site Code`,S.`Site Key`,`Page Short Title`,`Page Preview Snapshot Image Key`,`Page Store Section`,`Page Parent Code`,`Page Parent Key`,`Page URL`,P.`Page Key`,`Page Store Title`,`Page Code`
";
?>
