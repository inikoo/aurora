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
YAHOO.namespace ("invoice"); 


function change_shipping_type(){


new_value=this.getAttribute('value');
var ar_file='ar_edit_orders.php';
	request='tipo=edit_new_order_shipping_type&id='+order_key+'&key=collection&newvalue='+new_value;
	//alert(request);return;
	YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					    //alert(o.responseText);
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
	else
	    var new_qty=parseFloat(data['quantity'])-1;

 var ar_file='ar_edit_orders.php';
	request='tipo=edit_new_order&id='+order_key+'&key=quantity&newvalue='+new_qty+'&oldvalue='+data['quantity']+'&pid='+ data['pid'];
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

						//for (i in r.discount_data){
						//    alert(i+' '+r.discount_data[i].to_charge);
						//}
						
						//	callback(true, r.newvalue);
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



YAHOO.util.Event.addListener(window, "load", function() {
 tables  = new function() {

	    
		
	    var tableid=0; 
	    // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var InvoiceColumnDefs = [
				        {key:"pid", label:"<?php echo _('Product ID')?>", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				     
					,{key:"code", label:"<?php echo _('Code')?>",width:80,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:"<?php echo _('Description')?>",width:400,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     //				     ,{key:"tariff_code", label:"<?php echo _('Tariff Code')?>",width:80,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"stock",label:"<?php echo _('Able')?>", width:80,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"quantity",label:"<?php echo _('Qty')?>", width:40,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'new_order'}
					//,{key:"change",label:"", width:40,className:"aleft",sortable:false}
					,{key:"add",label:"", width:5,sortable:false,action:'add_object',object:'new_order'}
					,{key:"remove",label:"", width:5,sortable:false,action:'remove_object',object:'new_order'}

				     //  ,{key:"gross",label:"<?php echo _('Amount')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     //  ,{key:"discount",label:"<?php echo _('Discounts')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"to_charge",label:"<?php echo _('To Charge')?>", width:75,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ];


	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_orders.php?tipo=transactions_to_process&tid=0");
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
			 ,"to_charge","gross","tariff_code","stock","add","remove","pid"
			 // "promotion_id",
			 ]};
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, InvoiceColumnDefs,
								   this.dataSource0, {
								      renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['products']['table']['nr']+1?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['products']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['products']['table']['order_dir']?>"
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
	    
	    this.table0.view='<?php echo$_SESSION['state']['products']['view']?>';
	    this.table0.filter={key:'<?php echo$_SESSION['state']['products']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['products']['table']['f_value']?>'};
    
    };
  });



function change_show_all(){

  var state=this.getAttribute('state');
  var alter=Dom.get('show_all').getAttribute('atitle');

  var current=Dom.get('show_all').innerHTML;
  Dom.get('show_all').innerHTML=alter;
  Dom.get('show_all').setAttribute('atitle',current);


  if(state==1){
      var show_all='no';
      Dom.get('show_all').setAttribute('state',0);
  }else{
      var show_all='yes';
      Dom.get('show_all').setAttribute('state',1);

      
  }
  
    
   var table=tables['table0'];
   var datasource=tables['dataSource0'];
   var request='&show_all='+show_all;
   // alert(request);
   datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
}

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

						return;
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

function open_cancel_dialog(){

    dialog_cancel.show();
    Dom.get('cancel_input').focus();
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
					Dom.get('given_shipping').innerHTML=r.shipping;
					
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
Dom.get("shipping_amount").value='';
dialog_edit_shipping.hide();
}

function init(){
    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
    oAutoComp.minQueryLength = 0; 

    YAHOO.util.Event.addListener('show_all', "click",change_show_all);
        YAHOO.util.Event.addListener(["set_for_collection","set_for_shipping"], "click",change_shipping_type);

    // YAHOO.util.Event.addListener('done', "click",create_delivery_note);

var myDialog = new YAHOO.widget.Dialog("myDialog"); 


//alert(Dom.get('cancel'));
  dialog_cancel = new YAHOO.widget.Dialog("dialog_cancel", {context:["cancel","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});

//  alert('xx')

dialog_cancel.render();
  YAHOO.util.Event.addListener("cancel", "click",open_cancel_dialog );
   YAHOO.util.Event.addListener("done", "click",create_delivery_note );

dialog_edit_shipping = new YAHOO.widget.Dialog("dialog_edit_shipping", {context:["tr_order_shipping_on_demand","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
dialog_edit_shipping.render();
Event.addListener("set_shipping", "click", dialog_edit_shipping.show,dialog_edit_shipping , true);

Event.addListener("save_set_shipping", "click", save_set_shipping);
Event.addListener("reset_set_shipping", "click", reset_set_shipping);



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