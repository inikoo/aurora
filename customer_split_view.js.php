<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var dialog_merge;



function open_merge_dialog(){
swap_right();
customer_a=Dom.getElementsByClassName('customer_a','td','customers_table');
Dom.setStyle(customer_a,'opacity',0.5)
dialog_merge.show();
}


function merge(){
 
 if(Dom.get('merge_direction').value=='right'){
 customer_key=Dom.get('customer_b').value;
 customer_to_delete_key=Dom.get('customer_a').value;
 }else{
 customer_key=Dom.get('customer_a').value;
 customer_to_delete_key=Dom.get('customer_b').value;
 }

  Dom.setStyle('merging_buttons','display','none');
  Dom.setStyle('merging','display','');

	var request="ar_edit_contacts.php?tipo=customer_merge&customer_key="+customer_key+"&merge_key="+customer_to_delete_key;

	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//	alert(o.responseText);

		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if (r.state==200) {
			
			location.reload()

		    }else{
	 Dom.setStyle('merging','display','none');
	Dom.get('merge_msg').innerHTML=r.msg;
	}
	
	}
	    });        
	


};


function swap_left(){
Dom.get('merge_direction').value='left';
Dom.setStyle('right_merge','display','none');
Dom.setStyle('left_merge','display','');
customer_a=Dom.getElementsByClassName('customer_a','td','customers_table');
customer_b=Dom.getElementsByClassName('customer_b','td','customers_table');


Dom.setStyle(customer_a,'opacity',1)
Dom.setStyle(customer_b,'opacity',0.5)


}
function swap_right(){
Dom.get('merge_direction').value='right';

customer_a=Dom.getElementsByClassName('customer_a','td','customers_table');
customer_b=Dom.getElementsByClassName('customer_b','td','customers_table');

Dom.setStyle(customer_a,'opacity',0.5)
Dom.setStyle(customer_b,'opacity',1)

Dom.setStyle('left_merge','display','none');
Dom.setStyle('right_merge','display','');
}


function close_merge_dialog(){
 customer_a=Dom.getElementsByClassName('customer_a','td','customers_table');
customer_b=Dom.getElementsByClassName('customer_b','td','customers_table');

Dom.setStyle(customer_a,'opacity',1)
Dom.setStyle(customer_b,'opacity',1)

	
	dialog_merge.hide();

};

 

function init(){




  init_search('customers_store');

dialog_merge = new YAHOO.widget.Dialog("dialog_merge", {context:["open_merge_dialog","tr","br"]  ,visible : false,close:false,underlay: "none",draggable:false});
dialog_merge.render();




Event.addListener("open_merge_dialog", "click", open_merge_dialog , true);
Event.addListener("open_merge_dialog", "click", open_merge_dialog , true);





}


YAHOO.util.Event.onDOMReady(init);
