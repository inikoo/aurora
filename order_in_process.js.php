<?php
include_once('common.php');
$order_key=0;
if(isset($_REQUEST['order_key']) )
    $order_key=$_REQUEST['order_key'];
print "var order_key=$order_key;";
$customer_key=0;
if(isset($_REQUEST['customer_key']) )
    $customer_key=$_REQUEST['customer_key'];
print "var customer_key=$customer_key;";
?>
var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var dialog_cancel,dialog_edit_shipping;
var change_staff_discount;
YAHOO.namespace ("invoice"); 

var edit_delivery_address;



function post_change_main_delivery_address(){
 
    var ar_file='ar_edit_orders.php';
    request='tipo=update_ship_to_key&order_key='+order_key+'&ship_to_key=0';
	
	YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					 
					   var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
						if(r.result=='updated'){
						edit_delivery_address.hide()
					Dom.get('delivery_address').innerHTML=r.new_value;
						     Dom.setStyle('tr_order_shipping','display','');
						    Dom.setStyle('shipping_address','display','');
						    Dom.setStyle('for_collection','display','none');
						}
					    } else {
						alert(r.msg);
						//	callback();
					    }
					},
					    failure:function(o) {
					    alert(o.statusText);
					    // callback();
					},
					    scope:this
					    },
				    request
				    
				    );  
}



function change_shipping_type(){


new_value=this.getAttribute('value');
var ar_file='ar_edit_orders.php';
	request='tipo=edit_new_order_shipping_type&id='+order_key+'&key=collection&newvalue='+new_value;
	//alert(request);return;
	YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
						if(r.result=='updated'){
						    if(r.new_value=='Yes'){
						    Dom.setStyle('tr_order_shipping','display','none');
						    Dom.setStyle('shipping_address','display','none');
						    Dom.setStyle('for_collection','display','');

						    
						    }else{
						     Dom.setStyle('tr_order_shipping','display','');
						    Dom.setStyle('shipping_address','display','');
						    Dom.setStyle('for_collection','display','none');
						    
						    }
						    
						
						}
						    
						
					    } else {
						alert(r.msg);
						//	callback();
					    }
					},
					    failure:function(o) {
					    alert(o.statusText);
					    // callback();
					},
					    scope:this
					    },
				    request
				    
				    );  
	

}


var myonCellClick = function(oArgs) {


    var target = oArgs.target,
    column = this.getColumn(target),
    record = this.getRecord(target);


    
    datatable = this;
    var records=this.getRecordSet();
    //alert(records.getLength())
   

    //return;

    //alert(datatable)
    var recordIndex = this.getRecordIndex(record);

		
    switch (column.action) {
    case('add_object'):
    case('remove_object'):
	var data = record.getData();

	if(column.action=='add_object')
	    var new_qty=parseFloat(data['quantity'])+1;
	else{
	    qty=parseFloat(data['quantity'])
	    if(qty==0){
	        return;
	    }
	    var new_qty=qty-1;

        }

 var ar_file='ar_edit_orders.php';
	request='tipo=edit_new_order&id='+order_key+'&key=quantity&newvalue='+new_qty+'&oldvalue='+data['quantity']+'&pid='+ data['pid'];
//alert(request)
	YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					   // alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
						for(x in r.data){

						    Dom.get(x).innerHTML=r.data[x];
						}

						if(r.discounts){
						    Dom.get('tr_order_items_gross').style.display='';
						    Dom.get('tr_order_items_discounts').style.display='';

						}else{
						    Dom.get('tr_order_items_gross').style.display='none';
						    Dom.get('tr_order_items_discounts').style.display='none';

						}
						
						/*
							if(r.charges){
						    Dom.get('tr_order_items_charges').style.display='';

						}else{
						    Dom.get('tr_order_items_charges').style.display='none';

						}
						*/
					
						datatable.updateCell(record,'quantity',r.quantity);
						datatable.updateCell(record,'to_charge',r.to_charge);
					


						for(var i=0; i<records.getLength(); i++) {
						    var rec=records.getRecord(i);
						    if(r.discount_data[rec.getData('pid')]!=undefined){
						    datatable.updateCell(rec,'to_charge',r.discount_data[rec.getData('pid')].to_charge);
						    datatable.updateCell(rec,'description',r.discount_data[rec.getData('pid')].description);
						    }
						}

				        if(r.quantity==0){
				       
				            datatable.updateCell(record,'description',r.description);
				            
                            if(Dom.get('products_display_type').value=='ordered_products'){
                            
                            this.deleteRow(target);
				            }
				        }
				
				
					    } else {
						alert(r.msg);
						//	callback();
					    }
					},
					    failure:function(o) {
					    alert(o.statusText);
					    // callback();
					},
					    scope:this
					    },
				    request
				    
				    );  
	
	break;
   
		    
    default:
		    
	this.onEventShowCellEditor(oArgs);
	break;
    }
};   

function change(e,o,tipo){
    switch(tipo){
    case('cancel'):
	if(o.value!=''){
	    enable_save(tipo);

	    if(window.event)
		key = window.event.keyCode; //IE
	    else
		key = e.which; //firefox     
	    
	    if (key == 13)
		save(tipo);


	}else
	    disable_save(tipo);
	break;
    }
};

function enable_save(tipo){
    switch(tipo){
    case('cancel'):
	Dom.get(tipo+'_save').style.visibility='visible';
	break;
    }
};

function disable_save(tipo){
    switch(tipo){
    case('cancel'):
	Dom.get(tipo+'_save').style.visibility='hidden';
	break;
    }
};


function close_dialog(tipo){
    switch(tipo){

    
    case('cancel'):

	Dom.get(tipo+"_input").value='';
	Dom.get(tipo+'_save').style.visibility='hidden';
	dialog_cancel.hide();

	break;
    }
};

var CellEdit = function (callback, newValue) {
      
    var record = this.getRecord(),
    column = this.getColumn(),
    oldValue = this.value,
    datatable = this.getDataTable();
    var records=datatable.getRecordSet();
    var ar_file='ar_edit_orders.php';
    
    var request='tipo=edit_'+column.object+'&id='+order_key+'&key=' + column.key + '&newvalue=' + encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ myBuildUrl(datatable,record);
    //alert('R:'+request);

    YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					    //alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
						for(x in r.data){

						    Dom.get(x).innerHTML=r.data[x];
						}
					      
if(r.discounts){
						    Dom.get('tr_order_items_gross').style.display='';
						}else{
						    Dom.get('tr_order_items_gross').style.display='none';

						}

		if(r.charges){
						    Dom.get('tr_order_items_charges').style.display='';

						}else{
						    Dom.get('tr_order_items_charges').style.display='none';

						}
						

						datatable.updateCell(record,'quantity',r.quantity);
						datatable.updateCell(record,'to_charge',r.to_charge);
					


						for(var i=0; i<records.getLength(); i++) {
						    var rec=records.getRecord(i);
						    if(r.discount_data[rec.getData('pid')]!=undefined){
						    datatable.updateCell(rec,'to_charge',r.discount_data[rec.getData('pid')].to_charge);
						    datatable.updateCell(rec,'description',r.discount_data[rec.getData('pid')].description);
						    }
						}
						callback(true,r.quantity);
						

					    } else {
						alert(r.msg);
						callback();
					    }
					},
					    failure:function(o) {
					    alert(o.statusText);
					    callback();
					},
					    scope:this
					    },
				    request
				    
				    );  
  };


var mygetTerms =function (query) {




    if(this.table_id==undefined)
	var table_id=0;
    else
	var table_id=this.table_id;

    var Dom   = YAHOO.util.Dom;
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];

    table.filter.value=Dom.get('f_input'+table_id).value;
    var request='&tableid='+table_id+'&sf=0&f_field=' +table.filter.key + '&f_value=' + table.filter.value+'&display='+Dom.get('products_display_type').value;
 
  datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
};


YAHOO.util.Event.addListener(window, "load", function() {
 tables  = new function() {

	    
		
	    var tableid=0; 
	    // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;


	    var InvoiceColumnDefs = [
				        {key:"pid", label:"<?php echo _('Product ID')?>", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				     
					,{key:"code", label:"<?php echo _('Code')?>",width:80,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:"<?php echo _('Description')?>",width:400,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"stock",label:"<?php echo _('Able')?>", hidden:(Dom.get('dispatch_state').value=='In Process'?false:true),width:80,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"quantity",label:"<?php echo _('Qty')?>", width:40,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'new_order'}
					,{key:"add",label:"",hidden:(Dom.get('dispatch_state').value=='In Process'?false:true), width:5,sortable:false,action:'add_object',object:'new_order'}
					,{key:"remove",label:"",hidden:(Dom.get('dispatch_state').value=='In Process'?false:true), width:5,sortable:false,action:'remove_object',object:'new_order'}
			     ,{key:"to_charge",label:"<?php echo _('To Charge')?>", width:75,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				,{key:"dispatching_status",label:"<?php echo _('Status')?>" ,hidden:(Dom.get('dispatch_state').value!='In Process'?false:true),width:90,sortable:false,className:"aright"}

				];


	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_orders.php?tipo=transactions_to_process&tid=0&sf=0&f_value=&display="+Dom.get('products_display_type').value);
	    //alert("ar_edit_orders.php?tipo=transactions_to_process&tid=0&sf=0&f_value=&display="+Dom.get('products_display_type').value)
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		fields: [
			 "code"
			 ,"description"
			 ,"quantity"
			 ,"discount"
			 ,"to_charge","gross","tariff_code","stock","add","remove","pid",'dispatching_status'
			 // "promotion_id",
			 ]};
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, InvoiceColumnDefs,
								   this.dataSource0, {
								      renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['order'][$_SESSION['state']['order']['products']['display']]['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['order']['products']['order']?>",
									 dir: "<?php echo$_SESSION['state']['order']['products']['order_dir']?>"
								     }
							   ,dynamicData : true
								   }
								   
								   );
	

	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginator = mydoBeforePaginatorChange;
	    this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table0.subscribe("cellClickEvent", myonCellClick);
	    
	    this.table0.filter={key:'<?php echo$_SESSION['state']['order']['products']['f_field']?>',value:''};
    
    };
  });




function save(tipo){
    //alert(tipo)
    switch(tipo){
    case('cancel'):
	var value=encodeURIComponent(Dom.get(tipo+"_input").value);
	var ar_file='ar_edit_orders.php'; 
	var request='tipo=cancel&note='+value;
	//alert('R:'+request);
	
	YAHOO.util.Connect.asyncRequest(
					'POST',
					ar_file, {
					    success:function(o) {
						//alert(o.responseText);
						var r = YAHOO.lang.JSON.parse(o.responseText);
						if (r.state == 200) {
						window.location.reload();
						}
					    },
					failure:function(o) {
					    alert(o.statusText);
					    
					},
					scope:this
				    },
				    request
				    
				    );  
    
    
    break;
    }

}

function create_delivery_note(){
	var ar_file='ar_edit_orders.php'; 
    	var request='tipo=send_to_warehouse&order_key='+order_key;



	YAHOO.util.Connect.asyncRequest(
					'POST',
					ar_file, {
					    success:function(o) {
									alert(o.responseText);

						//return;
						var r = YAHOO.lang.JSON.parse(o.responseText);
						if (r.state == 200) {
						window.location="customer.php?id="+customer_key;
						}
					    },
					failure:function(o) {
					    alert(o.statusText);
					    
					},
					scope:this
				    },
				    request
				    
				    );  


}

function open_cancel_dialog(){

    dialog_cancel.show();
    Dom.get('cancel_input').focus();
}


function save_use_calculated_shipping(){
var ar_file='ar_edit_orders.php'; 
    	var request='tipo=use_calculated_shipping&order_key='+order_key;

alert(request);
	YAHOO.util.Connect.asyncRequest(
					'POST',
					ar_file, {
					    success:function(o) {
						//alert(o.responseText);
						var r = YAHOO.lang.JSON.parse(o.responseText);
						if (r.state == 200) {
					
					for(x in r.data){

						    Dom.get(x).innerHTML=r.data[x];
						}
					        Dom.get('order_shipping_method').innerHTML=r.order_shipping_method;
					 Dom.get('shipping_amount').value=r.shipping_amount
						}
						reset_set_shipping()
					    },
					failure:function(o) {
					    alert(o.statusText);
					    
					},
					scope:this
				    },
				    request
				    
				    );  

}

function save_set_shipping(){
value=Dom.get("shipping_amount").value;
var ar_file='ar_edit_orders.php'; 
    	var request='tipo=set_order_shipping&value='+value+'&order_key='+order_key;

//alert(request);
	YAHOO.util.Connect.asyncRequest(
					'POST',
					ar_file, {
					    success:function(o) {
						//alert(o.responseText);
						var r = YAHOO.lang.JSON.parse(o.responseText);
						if (r.state == 200) {
					
					for(x in r.data){

						    Dom.get(x).innerHTML=r.data[x];
						}
					           Dom.get('order_shipping_method').innerHTML=r.order_shipping_method;
					            Dom.get('shipping_amount').value=r.shipping_amount
						}
						reset_set_shipping()
					    },
					failure:function(o) {
					    alert(o.statusText);
					    
					},
					scope:this
				    },
				    request
				    
				    );  

}

function reset_set_shipping(){
dialog_edit_shipping.hide();
}





function close_edit_delivery_address_dialog(){
edit_delivery_address.hide();
}

function change_delivery_address(){

 var y=(Dom.getY('control_panel'))
    var x=(Dom.getX('control_panel'))
Dom.setX('edit_delivery_address_splinter_dialog',x);
Dom.setY('edit_delivery_address_splinter_dialog',y+0);
 edit_delivery_address.show();
}

function show_only_ordered_products(){
 


 Dom.removeClass('all_products','selected')
   Dom.addClass('ordered_products','selected')

   var table=tables['table0'];
   var datasource=tables['dataSource0'];
   var request='&display=ordered_products';
    Dom.get('products_display_type').value='ordered_products';

   datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
}

function show_all_products(){
  Dom.removeClass('ordered_products','selected')
   Dom.addClass('all_products','selected')

   var table=tables['table0'];
   var datasource=tables['dataSource0'];
   var request='&display=all_products';
  Dom.get('products_display_type').value='all_products';
   datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
}


function show_edit_button(e,data){

Dom.setStyle('edit_button_'+data.name,'visibility','visible')

}
function hide_edit_button(e,data){

if(data.name=='shipping' && Dom.get('order_shipping_method').value=='On Demand'){
    return
}    
    
Dom.setStyle('edit_button_'+data.name,'visibility','hidden')

}





function show_dialog_import_transactions_mals_e(){
Dom.get('transactions_mals_e').value='';
dialog_import_transactions_mals_e.show();
}

function save_import_transactions_mals_e(){

 var values=new Object();
values['data']=Dom.get('transactions_mals_e').value;

jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(values));


var ar_file='ar_edit_orders.php'; 
	var request='tipo=import_transactions_mals_e&order_key='+Dom.get('order_key').value+'&values='+jsonificated_values;
	//alert('R:'+request);
	//alert(request);
	YAHOO.util.Connect.asyncRequest(
					'POST',
					ar_file, {
					    success:function(o) {
						alert(o.responseText);
						var r = YAHOO.lang.JSON.parse(o.responseText);
						if (r.state == 200) {
						window.location.reload();
						}
					    },
					failure:function(o) {
					    alert(o.statusText);
					    
					},
					scope:this
				    },
				    request
				    
				    );  

}




function init(){

 init_search('products_store');

Event.addListener("tr_order_shipping", "mouseover", show_edit_button,{'name':'shipping'});
Event.addListener("tr_order_shipping", "mouseout", hide_edit_button,{'name':'shipping'});

Event.addListener("use_calculate_shipping", "click", save_use_calculated_shipping);



Event.addListener("ordered_products", "click", show_only_ordered_products);
Event.addListener("all_products", "click", show_all_products);



 edit_delivery_address = new YAHOO.widget.Dialog("edit_delivery_address_splinter_dialog", 
			{ 
			    visible : false,close:false,
			    underlay: "none",draggable:false
			    
			} );
       edit_delivery_address.render();
    
                        YAHOO.util.Event.addListener("change_delivery_address", "click",change_delivery_address);

    
 

   var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
    oAutoComp.minQueryLength = 0; 

Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);


//---------------------------------------discount search code from here-----------------------
 change_staff_discount = new YAHOO.widget.Dialog("change_staff_discount", 
			{ 
			    visible : false,close:false,
			    underlay: "none",draggable:false
			    
			} );
       change_staff_discount.render();
       //       change_staff_discount.show();

//---------------------------------------discount search ends here---------------------------

        YAHOO.util.Event.addListener(["set_for_collection","set_for_shipping"], "click",change_shipping_type);


var myDialog = new YAHOO.widget.Dialog("myDialog"); 


//alert(Dom.get('cancel'));
  dialog_cancel = new YAHOO.widget.Dialog("dialog_cancel", {context:["cancel","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});

//  alert('xx')

dialog_cancel.render();
  YAHOO.util.Event.addListener("cancel", "click",open_cancel_dialog );
   YAHOO.util.Event.addListener("done", "click",create_delivery_note );

dialog_edit_shipping = new YAHOO.widget.Dialog("dialog_edit_shipping", {context:["edit_button_shipping","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_edit_shipping.render();
Event.addListener("edit_button_shipping", "click", dialog_edit_shipping.show,dialog_edit_shipping , true);

Event.addListener("save_set_shipping", "click", save_set_shipping);
Event.addListener("reset_set_shipping", "click", reset_set_shipping);

dialog_import_transactions_mals_e = new YAHOO.widget.Dialog("dialog_import_transactions_mals_e", {context:["import_transactions_mals_e","tl","tr"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_import_transactions_mals_e.render();
Event.addListener("import_transactions_mals_e", "click", show_dialog_import_transactions_mals_e,true);

Event.addListener("save_import_transactions_mals_e", "click", save_import_transactions_mals_e,true);

}




YAHOO.util.Event.onDOMReady(init);





YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });
//------------------------------------ discount code ------------------------------
var change_discount=function(o){
//alert("caca")
    var y=(Dom.getY(o))
    var x=(Dom.getX(o))
    x=x-90;y=y+15;
    //    alert(y);
    Dom.setX('change_staff_discount', x)
    Dom.setY('change_staff_discount', y)
   
    //    add_user_dialog_staff.cfg.setProperty("x", "500");
    //add_user_dialog_staff.cfg.setProperty("y", 500);
    
    change_staff_discount.show();
    
}
function close_change_discount_dialog(){

	Dom.get('change_discount_value').value='';
	
	

	Dom.get('change_discount_save').style.visibility='hidden';
	change_staff_discount.hide();

    }
