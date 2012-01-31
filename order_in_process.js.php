<?php
include_once('common.php');
	
$order_key=0;
if(isset($_REQUEST['order_key']) )
    $order_key=$_REQUEST['order_key'];
print "var order_key=$order_key;";

?>


var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var dialog_cancel,dialog_edit_shipping;
var change_staff_discount;
YAHOO.namespace ("invoice"); 

var edit_delivery_address;



var myonCellClick = function(oArgs) {


    var target = oArgs.target,
    column = this.getColumn(target),
    record = this.getRecord(target);


    
    datatable = this;
    var records=this.getRecordSet();
    //alert(records.getLength())
   

    //return;

  
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
						Dom.get('ordered_products_number').value=r.data['ordered_products_number'];
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
   
   case('edit_object'):
  
  
		    change_discount(this.getCell(target),record.getData('otf_key'),record.getId(),record.getData('discount_percentage'))
  
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
					 //   alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
						for(x in r.data){

						    Dom.get(x).innerHTML=r.data[x];
						}
					      Dom.get('ordered_products_number').value=r.data['ordered_products_number'];
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
						

					    } 
					    
					    else {
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
				        {key:"pid", label:"Product ID", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				     
					,{key:"code", label:"Code",width:80,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:"Description",width:400,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"stock",label:"Stock", hidden:(Dom.get('dispatch_state').value=='In Process'?false:true),width:80,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"quantity",label:"Qty", width:40,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'new_order'}
					,{key:"add",label:"",hidden:(Dom.get('dispatch_state').value=='In Process'?false:true), width:5,sortable:false,action:'add_object',object:'new_order'}
					,{key:"remove",label:"",hidden:(Dom.get('dispatch_state').value=='In Process'?false:true), width:5,sortable:false,action:'remove_object',object:'new_order'}
			     ,{key:"to_charge",label:"To Charge", width:75,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},action:'edit_object',object:'transaction_discount_percentage'}
				,{key:"dispatching_status",label:"Status" ,hidden:(Dom.get('dispatch_state').value!='In Process'?false:true),width:90,sortable:false,className:"aright"}
				,{key:"otf_key",label:"" ,hidden:true, width:1}
	            ,{key:"discount_percentage",label:"" ,hidden:true, width:1}
				];

		//alert("ar_edit_orders.php?tipo=transactions_to_process&tid=0&sf=0&f_value=&display="+Dom.get('products_display_type').value);
	  
	  	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_orders.php?tipo=transactions_to_process&tid=0&sf=0&f_value=&display="+Dom.get('products_display_type').value);

	 // alert("ar_edit_orders.php?tipo=transactions_to_process&tid=0&sf=0&f_value=&display="+Dom.get('products_display_type').value)
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
			 ,"to_charge","gross","tariff_code","stock","add","remove","pid",'dispatching_status','otf_key','discount_percentage'
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
								//	alert(o.responseText);

						//return;
						var r = YAHOO.lang.JSON.parse(o.responseText);
						if (r.state == 200) {
								
								if(Dom.get('referral').value=='store_pending_orders'){
								window.location="customers_pending_orders.php?store="+Dom.get('store_key').value;
								}else{
								window.location="customer.php?id="+Dom.get('customer_key').value;
								}
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
Event.addListener("ordered_products", "click", show_only_ordered_products);
Event.addListener("all_products", "click", show_all_products);
   var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
    oAutoComp.minQueryLength = 0; 

Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
  YAHOO.util.Event.addListener("done", "click",create_delivery_note );




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



function checkout_wrong(){
	var path=Dom.get('path').value;
	var items=Dom.get('ordered_products_number').value;
	//alert(items);
	var request=path+'inikoo_files/checkout.php';
	window.location =request;
}
