<?php
include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;
var dialog_new_list;
    


function close_dialog_new_list(){
dialog_new_list.hide();
}

function new_list(store_key){
    location.href='new_customers_list.php?store_key='+store_key;
}


function show_dialog_new_list(){
if(Dom.get('direct_store_key').value){
        location.href='new_customers_list.php?store_key='+Dom.get('direct_store_key').value;

}else{
    dialog_new_list.show();
}
}

function init(){

init_search('customers_store');
dialog_new_list = new YAHOO.widget.Dialog("dialog_new_list", {context:["new_customer_list","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
dialog_new_list.render();


Event.addListener("new_customer_list", "click", show_dialog_new_list);


}

YAHOO.util.Event.onDOMReady(init);



YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
    });