<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 January 2017 at 14:06:40 GMT, Sheffield, UK

 Copyright (c) 2017, Inikoo

 Version 3.0
*/


function get_item_quantity($quantity,$to_pick){
        $_quantity = sprintf('<span class="item_quantity button" qty="%s">%s</span>',$quantity,number($quantity));
        
        if($quantity!=$to_pick){
        $_quantity ='<span class="strikethrough  discreet">'.$_quantity.'</span> <span class="error discreet">'.$to_pick.'</error>';
        
        }
        
        return  $_quantity ;
}



function get_item_packed($pending,$itf_key,$part_sku,$packed){

    $pack_pending=$pending-$packed;



    $packed = sprintf('<span class="packed_quantity_done %s">  
 <input class="packed_qty width_50" style="background-color:rgba(192,216,144, 0.2)" ondblclick="show_check_dialog(this)" value="%s" readonly >
  <i  class="fa  fa-check fa-fw button add_packed " aria-hidden="true"/></span>
  
  
  
  

                <span data-settings=\'{"field": "Packed", "transaction_key":%d,"item_key":%d ,"on":1 }\' class="packed_quantity %s"  >
                    <input class="packing width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-plus fa-fw button add_packed %s" aria-hidden="true">
                </span>',


        ( $pack_pending > 0   ? 'hide' : ''),
                      number($packed),



                      $itf_key, $part_sku,

        ($pack_pending > 0  ? '' : 'hide'),
                      $packed,
                      $packed, ''
    );

    return $packed ;
}




function get_picked_offline_input($qty,$pending,$quantity_on_location,$itf_key,$part_sku,$picked,$part_stock,$barcode,$part_reference,$part_description,$part_image_key){

    $to_pick_value='';


    $to_pick=$qty-$picked;

    if($quantity_on_location<1 ){
        $to_pick_value=0;
    }


    $picked_offline_input =sprintf('<span class="picked_quantity_done">  
  
    <i onClick="set_picked_offline_item_as_done(this)" class="picked_offline_status  fa fa-check-circle %s " style="margin-right:20px" aria-hidden="true"></i>

  
  <span class="picked_quantity_cant_pick %s"> 
          <i  class="fa  %s fa-fw button warning add_picked invisible " aria-hidden="true"/>

        <input class="cant_pick width_50" style="background-color:%s"  value="%s" readonly >
        <i  class="fa  %s fa-fw button warning add_picked " aria-hidden="true"/>
   </span>
  

                <span data-settings=\'{"field": "Picked_Offline", "transaction_key":%d,"item_key":%d ,"on":1, "reference":"%s" , "description":"%s" , "image_key":%d  }\' class="picked_quantity %s"  >
                <i onClick="picked_offline_items_qty_change(this)" class="fa  fa-minus fa-fw button picked_offline_items_qty_change " aria-hidden="true"></i>
                    <input class="picked_offline width_50" style="" value="%s" ovalue="%s" barcode="%s"  max="%s"     > <i onClick="picked_offline_items_qty_change(this)" class="fa  fa-plus fa-fw button picked_offline_items_qty_change %s" aria-hidden="true"></i>
                </span>',





        ( $quantity_on_location<1 && $pending>0  ? 'success blocked' : 'super_discreet button'),
        ( $quantity_on_location<1 && $pending>0  ? '' : 'hide'),

        ($part_stock<1?'fa-ban':'fa-clock-o'),

        ($part_stock<1?'rgba(255,55,55, 0.2)':'rgba(255,155,55, 0.2)'),
                     number($to_pick_value),
        ($part_stock<1?'fa-ban':'fa-clock-o'),





                     $itf_key, $part_sku,$part_reference,$part_description,$part_image_key,

        ($pending != 0 && $quantity_on_location>=1   ? '' : 'hide'),
                                   $to_pick_value,
                     $to_pick_value,
                     $barcode,
                                   $to_pick,
                     ''
    );

    return $picked_offline_input ;
}


function get_item_picked($pending,$quantity_on_location,$itf_key,$part_sku,$picked,$part_stock,$barcode,$part_reference,$part_description,$part_image_key){


  $picked =sprintf('<span class="picked_quantity_done %s">  
 <input class="picked_qty width_50" style="background-color:rgba(192,216,144, 0.2)" ondblclick="show_check_dialog(this)" value="%s" readonly >
  <i  class="fa  fa-check fa-fw button add_picked " aria-hidden="true"/></span>
  
  
  <span class="picked_quantity_cant_pick %s">  
 <input class="cant_pick width_50" style="background-color:%s" ondblclick="show_check_dialog(this)" value="%s" readonly >
  <i  class="fa  %s fa-fw button warning add_picked " aria-hidden="true"/></span>
  

                <span data-settings=\'{"field": "Picked", "transaction_key":%d,"item_key":%d ,"on":1, "reference":"%s" , "description":"%s" , "image_key":%d  }\' class="picked_quantity %s"  >
                    <input class="picking width_50" value="%s" ovalue="%s" barcode="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-plus fa-fw button add_picked %s" aria-hidden="true">
                </span>',


            ($pending == 0   ? '' : 'hide'), 
            number($picked),
            
            ( $quantity_on_location<1 && $pending>0  ? '' : 'hide'),
            
            
            ($part_stock<1?'rgba(255,55,55, 0.2)':'rgba(255,155,55, 0.2)'),
            number($picked),
            ($part_stock<1?'fa-ban':'fa-clock-o'),

           $itf_key, $part_sku,$part_reference,$part_description,$part_image_key,

            ($pending != 0 && $quantity_on_location>=1   ? '' : 'hide'),
             $picked,
            $picked, $barcode,''
        );

return $picked ;
}


function get_item_location($pending,$quantity_on_location,$date_picked,$location_key,$location_code,$part_stock){

        $location_stock_icon_class = 'button ';
        $stock_in_location=sprintf(_('Stock in location: %s'),$quantity_on_location);

        $stock_quantity_safe_limit=ceil($pending*1.2);
        if($stock_quantity_safe_limit>10)$stock_quantity_safe_limit;


        if($pending == 0) {
            $picked_time=sprintf(_('Picked: %s'),strftime("%a %e %b %Y %H:%M %Z", strtotime($date_picked.' +0:00')));
            $location = sprintf('<i class="fa fa-fw fa-check super_discreet %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class,$picked_time);

        }elseif ($quantity_on_location <= 0) {

            if($part_stock>=$pending){
                $location = sprintf('<i class="fa fa-fw fa-bookmark-o fa-flip-vertical warning %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class,$stock_in_location);

            }else{
                $location = sprintf('<i class="fa fa-fw no_stock_location fa-circle error %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class,$stock_in_location);

            }



        } else if($quantity_on_location<$pending){
            if($part_stock>=$pending){
                $location = sprintf('<i class="fa fa-fw fa-bookmark-o fa-flip-vertical warning %s" aria-hidden="true" title="%s"></i> ', $location_stock_icon_class,$stock_in_location);

            }else{
                if($quantity_on_location<1){
                    $location = sprintf('<i class="fa  fa-fw no_stock_location  fa-circle error %s" aria-hidden="true" title="%s"></i> ',$location_stock_icon_class,$stock_in_location);

                }else{
                    $location = sprintf('<i class="fa fa-fw fa-star-half-o error %s" aria-hidden="true" title="%s"></i> ',$location_stock_icon_class,$stock_in_location);
                }



            }

        }else if($quantity_on_location<$stock_quantity_safe_limit){
            $location = sprintf('<i class="fa fa-fw fa-star warning %s" aria-hidden="true" title="%s"></i> ',$location_stock_icon_class,$stock_in_location);
        }else {
            $location = sprintf('<i class="fa fa-fw fa-star success very_discreet %s" aria-hidden="true" title="%s"></i> ',$location_stock_icon_class,$stock_in_location);
        }


        if($location_code==''){
            $location_code='<span style="font-style: italic">'._('No location !').'</span>';
        }

        $location.=sprintf('<span class="%s location"  location_key = "%d" >%s </span >',
            ($pending>0?'discreet':''),
                          $location_key,
                          $location_code
        );

return $location;

}


?>