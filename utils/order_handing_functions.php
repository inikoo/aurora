<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 January 2017 at 14:06:40 GMT, Sheffield, UK

 Copyright (c) 2017, Inikoo

 Version 3.0
*/


function get_item_quantity($required, $available) {
    $_quantity = sprintf('<span class="item_quantity button" qty="%s">%s</span>', $required, number($required));

    if ($required != $available) {
        $_quantity = '<span class="strikethrough  discreet">'.$_quantity.'</span> <span class="error discreet">'.$available.'</error>';

    }

    return $_quantity;
}


function get_item_packed($pending, $itf_key, $part_sku, $packed) {

    $pack_pending = $pending - $packed;


    $packed = sprintf(
        '<span class="packed_quantity_done %s">  
 <input class="packed_qty width_50" style="background-color:rgba(192,216,144, 0.2)" ondblclick="show_check_dialog(this)" value="%s" readonly >
  <i  class="fa  fa-check fa-fw button add_packed " aria-hidden="true"></i></span>
  
  
  
  

                <span data-settings=\'{"field": "Packed", "transaction_key":%d,"item_key":%d ,"on":1 }\' class="packed_quantity %s"  >
                    <input class="packing width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-plus fa-fw button add_packed %s" aria-hidden="true">
                </span>',


        ($pack_pending > 0 ? 'hide' : ''), number($packed),


        $itf_key, $part_sku,

        ($pack_pending > 0 ? '' : 'hide'), $packed, $packed, ''
    );

    return $packed;
}


function get_picked_offline_input($total_required,$total_picked,$picked_in_location,$pending_in_location, $quantity_on_location, $itf_key, $part_sku,  $part_stock,$location_key) {


    $total_pending=$total_required-$total_picked;
    /*
    $to_pick_value = '';


    $to_pick = $qty - $picked;

    if ($quantity_on_location < 1) {
        $to_pick_value = 0;
    }


    if ($quantity_on_location < $to_pick) {
        $max_qty_can_be_picked = $quantity_on_location;
    } else {
        $max_qty_can_be_picked = $to_pick;
    }
    if ($max_qty_can_be_picked < 0) {
        $max_qty_can_be_picked = 0;
    }



*/


    $picked_offline_input=sprintf('
            <span class=" picked_quantity_cant_pick %s"> 
                <i  class="fa  %s fa-fw button warning add_picked invisible " aria-hidden="true"></i>
                <input class="cant_pick width_50" style="text-align: center;background-color:%s"  value="%s" readonly >
                <i  class="fa  %s fa-fw  warning  invisible  " aria-hidden="true"></i>
            </span>',
        ($quantity_on_location < 1 && $total_pending > 0 ? '' : 'hide'),

        ($part_stock < 1 ? 'fa-ban' : 'fa-ban'),

        ($part_stock < 1 ? 'rgba(255,55,55, 0.2)' : 'rgba(255,155,55, 0.2)'), number($picked_in_location), ($part_stock < 1 ? 'fa-ban' : 'fa-ban')
    );


    $picked_offline_input .= sprintf(
        ' <span data-settings=\'{"field": "Picked_Offline", "transaction_key":"%s","item_key":"%s" }\' class="picked_quantity %s"  >
                <i onClick="picked_offline_items_qty_change(this)" class="fa  fa-minus fa-fw button picked_offline_items_qty_change " aria-hidden="true"></i>
                    <input class="picked_offline width_50" style="text-align: center" value="%s" ovalue="%s"  data-to_pick="%s"  data-max="%s"  data-location_key="%d"   > 
                    <i onClick="picked_offline_items_qty_change(this)" class="fa  fa-plus fa-fw button picked_offline_items_qty_change %s" aria-hidden="true"></i>
                </span>',






        $itf_key, $part_sku,

        ($total_pending != 0 && $quantity_on_location >= 1 ? '' : 'hide'),
        ($picked_in_location==0?'':$picked_in_location), ($picked_in_location==0?'':$picked_in_location), $pending_in_location, $quantity_on_location,  $location_key,   ''
    );



    $picked_offline_input= sprintf('<span class="picked_quantity_done">%s</span>',$picked_offline_input);


    return $picked_offline_input;
}







function get_delivery_note_fast_track_packing_input($part_location_exist,$total_required,$total_picked,$picked_in_location,$pending_in_location, $quantity_on_location, $itf_key, $part_sku,  $part_stock,$location_key) {

    if($total_picked==0){


        $total_pending=$total_required-$total_picked;



        $picked_offline_input=sprintf('
            <span class="picked_quantity_cant_pick %s"> 
                <i  class="fa  %s fa-fw button warning add_picked invisible " aria-hidden="true"></i>
                <input class="cant_pick width_50" style="text-align: center;background-color:%s"  value="%s" readonly >
                <i  class="fa  %s fa-fw  warning  invisible  " aria-hidden="true"></i>
            </span>',
            ($part_location_exist=='Yes'? 'hide' : ''),

            ($part_stock < 1 ? 'fa-ban' : 'fa-ban'),

            'rgba(200,200,200, 0.1)', number($picked_in_location), ($part_stock < 1 ? 'fa-ban' : 'fa-ban')
        );


        if($total_required>=$quantity_on_location){
            $icon_plus_button='fa fa-lock';
        }else{
            $icon_plus_button='fa fa-plus';
        }


        $qty=($quantity_on_location<$total_pending?$quantity_on_location:$total_pending);

        if($qty<0){
            $qty=0;
        }

        $picked_offline_input .= sprintf(
            ' <span data-settings=\'{"field": "Picked_Offline", "transaction_key":"%s","item_key":"%s" }\' class="picked_quantity %s"  >
                <i onClick="delivery_note_fast_track_packing_qty_change(this)" class="fa  minus fa-minus fa-fw button picked_offline_items_qty_change " aria-hidden="true"></i>
                    <input class="fast_track_packing width_50" style="text-align: center" value="%s" ovalue="%s"  data-to_pick="%s"  data-max="%s" data-quantity_on_location="%s" data-quantity_on_location_locked=true  data-location_key="%d"   > 
                    <i onClick="delivery_note_fast_track_packing_qty_change(this)" class=" plus %s fa-fw button picked_offline_items_qty_change %s" aria-hidden="true"></i>
                </span>',






            $itf_key, $part_sku,

            ($part_location_exist=='Yes'? '' : 'hide'),
            $qty,
            $qty, $pending_in_location,
            $total_pending,$quantity_on_location,
            $location_key, $icon_plus_button,  ''
        );



        $picked_offline_input= sprintf('<span class="picked_quantity_done">%s</span>',$picked_offline_input);

    }else{
        $picked_offline_input='';

    }


    return $picked_offline_input;
}




function get_item_picked($pending, $quantity_on_location, $itf_key, $part_sku, $picked, $part_stock, $barcode, $part_reference, $part_description, $part_image_key) {


    $picked = sprintf(
        '<span class="picked_quantity_done %s">  
 <input class="picked_qty width_50" style="background-color:rgba(192,216,144, 0.2)" ondblclick="show_check_dialog(this)" value="%s" readonly >
  <i  class="fa  fa-check fa-fw button add_picked " aria-hidden="true"></i></span>
  
  
  <span class="picked_quantity_cant_pick %s">  
 <input class="cant_pick width_50" style="background-color:%s" ondblclick="show_check_dialog(this)" value="%s" readonly >
  <i  class="fa  %s fa-fw button warning add_picked " aria-hidden="true"></i></span>
  

                <span data-settings=\'{"field": "Picked", "transaction_key":%d,"item_key":%d ,"on":1, "reference":"%s" , "description":"%s" , "image_key":%d  }\' class="picked_quantity %s"  >
                    <input class="picking width_50" value="%s" ovalue="%s" barcode="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-plus fa-fw button add_picked %s" aria-hidden="true">
                </span>',


        ($pending == 0 ? '' : 'hide'), number($picked),

        ($quantity_on_location < 1 && $pending > 0 ? '' : 'hide'),


        ($part_stock < 1 ? 'rgba(255,55,55, 0.2)' : 'rgba(255,155,55, 0.2)'), number($picked), ($part_stock < 1 ? 'fa-ban' : 'fa-clock'),

        $itf_key, $part_sku, $part_reference, $part_description, $part_image_key,

        ($pending != 0 && $quantity_on_location >= 1 ? '' : 'hide'), $picked, $picked, $barcode, ''
    );

    return $picked;
}


function get_item_location(
    $pending, $quantity_on_location, $date_picked,
    $location_key, $location_code,
    $part_stock, $part_barcode,$part_distinct_locations, $part_sku,
    $itf_key,$delivery_note_key ) {

    $location_stock_icon_class = 'button ';
    $stock_in_location         = sprintf(_('Stock in location: %s'), $quantity_on_location);

    $stock_quantity_safe_limit = ceil($pending * 1.2);
    if ($stock_quantity_safe_limit > 10) {
        $stock_quantity_safe_limit;
    }



    if ($pending == 0) {
        $picked_time = sprintf(_('Picked: %s'), strftime("%a %e %b %Y %H:%M %Z", strtotime($date_picked.' +0:00')));
        $location    = sprintf('<i class="fa fa-fw fa-check super_discreet %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class, $picked_time);

    } elseif ($quantity_on_location <= 0) {

        if ($part_stock >= $pending) {
            $location = sprintf('<i class="far fa-fw fa-empty-set fa-flip-vertical warning %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class, $stock_in_location);

        } else {
            $location = sprintf('<i class="fa fa-fw no_stock_location fa-circle error %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class, $stock_in_location);

        }


    } else {
        if ($quantity_on_location < $pending) {
            if ($part_stock >= $pending) {
                $location = sprintf('<i class="far fa-fw fa-empty-set fa-flip-vertical warning %s" aria-hidden="true" title="%s"></i><span class="warning">(%s)</span> ', $location_stock_icon_class, $stock_in_location, number($quantity_on_location, 1));

            } else {
                if ($quantity_on_location < 1) {
                    $location = sprintf('<i class="fa  fa-fw no_stock_location  fa-circle error %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class, $stock_in_location);

                } else {
                    $location = sprintf('<i class="far fa-fw fa-star-half error %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class, $stock_in_location);
                }


            }

        } else {


            $location = sprintf('<i class="fa fa-fw fa-star success very_discreet %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class, $stock_in_location);

        }
    }


    if ($location_code == '') {
        $location_code = '<span style="font-style: italic">'._('No location').'!</span>';
    }

    $location .= sprintf(
        '<span class="%s location button"   data-available="%f"  location_key = "%d" >%s </span >', ($pending > 0 ? 'discreet' : ''), $quantity_on_location, $location_key, $location_code
    );
    if ($part_distinct_locations > 1) {
        $location .= ' <i class="fa fa-bars button padding_left_5 discreet_on_hover hide" data-metadata=\'{ "part_sku":"'.$part_sku.'","otf_key":"'.$itf_key.'","delivery_note_key":"'.$delivery_note_key.'"}\' onclick="show_other_part_locations_for_picking(this)"  title="'._('Other locations').'" ></i>';

    }



    return $location.$part_distinct_locations;

}


function get_delivery_note_fast_track_packing_item_location(
    $part_location_exist,
    $pending, $quantity_on_location, $date_picked,
    $location_key, $location_code,
    $part_stock,$part_distinct_locations, $part_sku,
    $itf_key,$delivery_note_key ) {


    //todo remove this it should update_number_locations everything a part location is created / removed
    $part=get_object('Part',$part_sku);
    $part->update_number_locations();
    $part_distinct_locations=$part->get('Part Distinct Locations');

    $location_stock_icon_class = 'button ';
    $stock_in_location         = sprintf(_('Stock in location: %s'), $quantity_on_location);

    $stock_quantity_safe_limit = ceil($pending * 1.2);
    if ($stock_quantity_safe_limit > 10) {
        $stock_quantity_safe_limit;
    }


    if ($pending == 0) {
        $picked_time = sprintf(_('Picked: %s'), strftime("%a %e %b %Y %H:%M %Z", strtotime($date_picked.' +0:00')));
        $location    = sprintf('<i class="fa fa-fw fa-check super_discreet %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class, $picked_time);

    } elseif ($quantity_on_location <= 0) {


            $location = sprintf('<i class="far fa-fw fa-empty-set fa-flip-vertical error %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class, $stock_in_location);




    } else {
        if ($quantity_on_location < $pending) {
            if ($part_stock >= $pending) {
                $location = sprintf('<i class="far fa-fw fa-empty-set fa-flip-vertical warning %s" aria-hidden="true" title="%s"></i><span class="warning">(%s)</span> ', $location_stock_icon_class, $stock_in_location, number($quantity_on_location, 1));

            } else {
                if ($quantity_on_location < 1) {
                    $location = sprintf('<i class="fa  fa-fw no_stock_location  fa-circle error %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class, $stock_in_location);

                } else {
                    $location = sprintf('<i class="far fa-fw fa-star-half error %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class, $stock_in_location);
                }


            }

        } else {


            $location = sprintf('<i class="fa fa-fw fa-star success very_discreet %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class, $stock_in_location);

        }
    }





    if ($location_code == '' or $location_key==1) {
        $location_code = '<span class="error italic">'._('No location').'!</span>';
        $location = sprintf('<i class="fa fa-fw  fa-exclamation-circle error" title="%s"></i> ',sprintf(_('There is no location associated with this part')));

    }else{

        if($part_location_exist=='No') {
            $location = sprintf('<i class="fa fa-fw fa-exclamation-circle error" title="%s"></i> ', sprintf(_('Location %s is not associated with this part'), $location_code));

        }

    }

    $location .= sprintf(
        '<span class="%s %s location button "   data-available="%f"  location_key = "%d" >%s </span >', ($part_location_exist=='Yes'  ? '' : 'error '), ($pending > 0 ? 'discreet' : ''), $quantity_on_location, $location_key, $location_code
    );



    if ($part_distinct_locations > 1 or ($part_location_exist=='No' and $part_distinct_locations > 0  )) {
        $location .= ' <i class="editable_set_picking   fa fa-bars button padding_left_5 discreet_on_hover " data-metadata=\'{ "part_sku":"'.$part_sku.'","otf_key":"'.$itf_key.'","delivery_note_key":"'.$delivery_note_key.'"}\' onclick="show_other_part_locations_for_input_delivery_note_packing(this)"  title="'._('Other locations').'" ></i>';
    }



    return $location;

}








