<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 19 October 2015 at 16:46:50 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_delivery_note_showcase($data) {

    global $smarty,$user;
    
    if(!$data['_object']->id){
        return "";
    }
    
    $smarty->assign('delivery_note',$data['_object']);
    
    $delivery_note=$data['_object'];
    


$parcels=$delivery_note->get_formated_parcels();
$weight=$delivery_note->data['Delivery Note Weight'];
$consignment=$delivery_note->data['Delivery Note Shipper Consignment'];


$smarty->assign( 'parcels', $parcels);
$smarty->assign( 'weight', ($weight?$delivery_note->get('Weight'):'') );
$smarty->assign( 'consignment', ($consignment?$delivery_note->get('Consignment'):'') );



    return $smarty->fetch('showcase/delivery_note.tpl');
    


}


?>