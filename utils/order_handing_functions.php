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


function get_item_picked($pending,$quantity_on_location,$itf_key,$part_sku,$picked,$part_stock){


  $picked = sprintf('<span class="picked_quantity_done %s">  
 <input class="picked_qty width_50" style="background-color:rgba(192,216,144, 0.2)" ondblclick="show_check_dialog(this)" value="%s" readonly >
  <i  class="fa  fa-check fa-fw button add_picked " aria-hidden="true"/></span>
  
  
  <span class="picked_quantity_cant_pick %s">  
 <input class="cant_pick width_50" style="background-color:%s" ondblclick="show_check_dialog(this)" value="%s" readonly >
  <i  class="fa  %s fa-fw button warning add_picked " aria-hidden="true"/></span>
  

                <span data-settings=\'{"field": "Picked", "transaction_key":%d,"item_key":%d ,"on":1 }\' class="picked_quantity %s"  >
                    <input class="picking width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-plus fa-fw button add_picked %s" aria-hidden="true">
                </span>',


            ($pending == 0   ? '' : 'hide'), 
            number($picked),
            
            ( $quantity_on_location<1 && $pending>0  ? '' : 'hide'),
            
            
            ($part_stock<1?'rgba(255,55,55, 0.2)':'rgba(255,155,55, 0.2)'),
            number($picked),
            ($part_stock<1?'fa-ban':'fa-clock-o'),

           $itf_key, $part_sku,

            ($pending != 0 && $quantity_on_location>1   ? '' : 'hide'),
             $picked,
            $picked, ''
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

        $location.=sprintf('<span class="%s location"  location_key = "%d" >%s </span >',
            ($pending>0?'discreet':''),
                          $location_key,
                          $location_code
        );

return $location;

}


?>