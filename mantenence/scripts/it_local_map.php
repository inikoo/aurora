<?php
//date_default_timezone_set('UTC');


function make_seed() {
    list($usec, $sec) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
}
mt_srand(make_seed());
$randval = mt_rand();


$since='2009-01-01';

//require_once '/var/www/aw/stock_functions.php';


$home_country_id=78;

$number_of_digits=5;

//require_once '/home/raul/www/inikoo/dns/dns.php';         // DB connecton configuration file



$tmp_directory='/tmp/';
$out_cvs='/tmp/t3tmp.csv';
$xls_dir="/data/old_orders_w/";
//$xls_dir="/data/ordersy/";
//$xls_dir="tmp/";






$tax_rate=0.175;




$number_header_rows=22;


$_y_map['code']=3;
$_y_map['description']=6;
$_y_map['price']=7+2;
$_y_map['order']=8+2;
$_y_map['reorder']=9+2;
$_y_map['bonus']=11+2;
$_y_map['credit']=14+2;
$_y_map['rrp']=16+2;
$_y_map['discount']=18+2;
$_y_map['units']=5;
$_y_map['supplier_code']=21+2;
$_y_map['supplier_product_code']=20+2;
$_y_map['supplier_product_cost']=25+2;
$_y_map['w']=28+2;


$_map['stipo']=array('row'=>2,'col'=>0);
$_map['ltipo']=array('row'=>2,'col'=>6);
$_map['pickedby']=array('row'=>2,'col'=>14+2);
$_map['parcels']=array('row'=>2,'col'=>18+2);
$_map['packedby']=array('row'=>3,'col'=>14+2);
$_map['weight']=array('row'=>3,'col'=>18+2);
$_map['trade_name']=array('row'=>5,'col'=>6,'tipo'=>'name');
$_map['takenby']=array('row'=>5,'col'=>7+2,'tipo'=>'name');
$_map['customer_num']=array('row'=>5,'col'=>8+2);
$_map['order_num']=array('row'=>5,'col'=>11+2);
$_map['date_order']=array('row'=>5,'col'=>14+2,'tipo'=>'date');
$_map['date_inv']=array('row'=>5,'col'=>16+2,'tipo'=>'date');
$_map['pay_method']=array('row'=>6,'col'=>2);
$_map['address1']=array('row'=>6,'col'=>6,'tipo'=>'name');
$_map['history']=array('row'=>7,'col'=>2);
$_map['address2']=array('row'=>7,'col'=>6,'tipo'=>'name');
$_map['notes']=array('row'=>7,'col'=>8+2);
$_map['total_net']=array('row'=>7,'col'=>18+2);
$_map['gold']=array('row'=>8,'col'=>2);
$_map['address3']=array('row'=>8,'col'=>6,'tipo'=>'name');
$_map['charges']=array('row'=>8,'col'=>14+2);
$_map['tax1']=array('row'=>8,'col'=>18+2);
$_map['city']=array('row'=>9,'col'=>6,'tipo'=>'name');
$_map['total_topay']=array('row'=>9,'col'=>18+2);
$_map['tax2']=false;
$_map['postcode']=array('row'=>10,'col'=>6);
$_map['notes2']=array('row'=>10,'col'=>8+2);
$_map['shipping']=array('row'=>11,'col'=>14+2);
$_map['customer_contact']=array('row'=>13,'col'=>6,'tipo'=>'name');
$_map['phone']=array('row'=>14,'col'=>6,'tipo'=>'string');
$_map['total_order']=array('row'=>14,'col'=>$_y_map['order']);
$_map['total_reorder']=array('row'=>14,'col'=>$_y_map['reorder']);
$_map['total_bonus']=array('row'=>14,'col'=>$_y_map['bonus']);
$_map['total_items_charge_value']=array('row'=>14,'col'=>14+2);
$_map['total_rrp']=array('row'=>14,'col'=>16+2);
$_map['feedback']=array('row'=>16,'col'=>20+2);
$_map['source_tipo']=false;
$_map['extra_id1']=false;
$_map['extra_id2']=false;
$_map['dn_country_code']=array('row'=>5,'col'=>9+2);




$_map_act['name']=2;
$_map_act['contact']=3;
$_map_act['first_name']=17;
$_map_act['a1']=4;
$_map_act['a2']=5;
$_map_act['a3']=6;
$_map_act['town']=7;
$_map_act['country_d2']=8;
$_map_act['postcode']=9;
$_map_act['country']=10;
$_map_act['tel']=12;
$_map_act['fax']=13;
$_map_act['mob']=15;
$_map_act['source']=25;
$_map_act['act']=38;
$_map_act['tax_number']=87;
$_map_act['int_email']=40;



function get_tipo_order($ltipo,$header) {


    $parent_id='';
    $tipo=0;
    if (preg_match('/proforma/i',$ltipo)) {

        $tipo=20;
    }
    elseif(preg_match('/FOLGESENDUNG/i',$ltipo)) {


        $tipo=8;
        $header['notes']=preg_replace('/^FOLGESENDUNG$/i','',$header['notes2']);



    }
    elseif(preg_match('/^GRATISMUSTERSENDUNG/i',$ltipo)) {
        //$header['notes']=preg_replace('/^ECHANTILLIONi?$/i','',$header['notes']);
        //$header['notes2']=preg_replace('/^ECHANTILLIONi?$/i','',$header['notes2']);
        $tipo=4;


    }
    ELSEif(preg_match('/consegna|DELIVERY NOTE|nota de envio|BON DE COMMANDE|VERSANDANZEIGE/i',$ltipo)) {

        $tipo=1;
    }
    elseif(preg_match('/fattura|FACTURE. sample order|facture|facutura|FACTURE|invoice|RECHNUNG/i',$ltipo)) {
        $tipo=2;
    }
    elseif(preg_match('/cancel|CANCELLATO/i',$ltipo)) {
        $tipo=3;
        $header['notes2']=preg_replace('/^STORNIERT?$/i','',$header['notes2']);


    }
    elseif(preg_match('/donation/i',$ltipo)) {
        $tipo=5;
    }
    elseif(preg_match('/^\s*REPLACEMENT|ERSATZLIEFERUNG|Replacenment|^reemplazo por roturas|^replacement|Damaged item|REPLACMENT|DELIVERY COLLECTION|repplacements|repalcements|Repalcement|Replaceement/i',$ltipo)) {
        $tipo=6;
        $header['notes']=preg_replace('/^Replacement$/i','',$header['notes']);
        $header['notes2']=preg_replace('/^Replacement$/i','',$header['notes2']);
        $header['notes2']=preg_replace('/^replacement$/i','',_trim($header['notes2']));

    }
    elseif(preg_match('/Damaged Parcel|shotages|MISSING|Missing Parcel|missing\s+\d|^reemplazo por falta|SHORTAHGE|shortages|INTEGRAZIONE|Missing From Order|missing form order|Mising from|^Missing Item|Missing - Replacement|^Shortage|Lost Parcel/i',$ltipo)) {

        $tipo=7;
    }
    elseif(preg_match('/^REMBOURSEMENT|RÜCKERSTATTUNG|rimbolso/i',$ltipo)) {
        $tipo=9;
        $header['notes']=preg_replace('/^refund$/i','',$header['notes']);
        $header['notes2']=preg_replace('/^refund$/i','',$header['notes2']);
        $header['notes2']=preg_replace('/^REFUND FOR RETURNED GOODS$/i','',$header['notes2']);
        $header['notes2']=preg_replace('/^refund for damaged and missing items$/i','',$header['notes2']);


    }
    elseif(preg_match('/credit|credit note/i',$ltipo)) {
        $tipo=10;
    }
    elseif(preg_match('/^quote|ANGEBOT/i',$ltipo)) {
        $tipo=11;
    }
    elseif(preg_match('/^return to supplier/i',$ltipo)) {|
        $tipo=12;




    }
    else {
        print "--->".$ltipo."<----\n";
        $tipo=0;
        exit("tipo not found\n");
    }



    $tmp='';

    if (preg_match('/\d{5}/i',$ltipo,$tmp)) {
        $parent_id=$tmp[0];
    }
    elseif(preg_match('/\d{4}/i',$ltipo[0]))
    $parent_id=$tmp;




    if ($tipo==8 and $parent_id=='' and preg_match('/follow on \d{5}/i',$header['notes2'])) {
        $tmp='';

        if (preg_match('/\d{5}/i',$header['notes2'],$tmp)) {
            $parent_id=$tmp[0];
        }
        $header['notes2']='';
    }


    // print "****** $ltipo *** $tmp ***\n";
    /*   if($header['total_topay']>0 and ($tipo==2)){ */
    /*     $tipo=2; */
    /*     if( preg_match('/follow/i',$header['notes2'])) */
    /*       $header['notes2']=''; */
    /*     if( preg_match('/follow/i',$header['notes'])) */
    /*       $header['notes']=''; */
    /*   } */




    if ($header['total_topay']==0) {

        if (preg_match('/Sample|samples.*/i',$header['notes2'])) {
            $header['notes']='';
            $tipo=4;
        }

        if (preg_match('/sostituzione|Repalacements|Replcement|^reemplazo por roturas|^replacement|REPLACMENT|DELIVERY COLLECTION|repplacements|repalcements/i',$header['notes2'])) {
            $header['notes2']='';
            $tipo=6;
        } else if (preg_match('/mancante|MISSING|Missing Parcel|missing\s+\d|^reemplazo por falta|shortages|Missing From Order|missing form order|Mising from|^Missing Item|Missing - Replacement|^Shortage|Lost Parcel/i',$header['notes2'])) {
            $tipo=7;
            $header['notes2']='';
        } else if (preg_match('/sostituzione|replacement|Repalacements|Replcement|^reemplazo por roturas|^replacement|REPLACMENT|DELIVERY COLLECTION|repplacements|repalcements/i',$header['notes'])) {
            $header['notes']='';
            $tipo=6;
        } else if (preg_match('/mancante|MISSING|Missing Parcel|missing\s+\d|^reemplazo por falta|shortages|Missing From Order|missing form order|Mising from|^Missing Item|Missing - Replacement|^Shortage|Lost Parcel/i',$header['notes'])) {
            $tipo=7;
            $header['notes']='';
        }



    }


    return array($tipo,$parent_id,$header);

}




?>