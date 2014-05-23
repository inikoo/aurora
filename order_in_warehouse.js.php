<?php
include_once('common.php');

?>


var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var dialog_cancel,dialog_edit_shipping,dialog_other_staff;
var process_order_dialog;
var change_staff_discount;
YAHOO.namespace ("invoice"); 

var edit_delivery_address;




YAHOO.util.Event.addListener(window, "load", function() {
 tables  = new function() {

	    
		
	    var tableid=0; 
	    // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;


	    var InvoiceColumnDefs = [
				        {key:"pid", label:"Product ID", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				     
					,{key:"code", label:"Code",width:60,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:"Description",width:330,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"stock",label:"Able", hidden:(Dom.get('dispatch_state').value=='In Process'?false:true),width:80,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"quantity",label:"Ordered", width:100,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"add",label:"",hidden:(Dom.get('dispatch_state').value=='In Process'?false:true), width:5,sortable:false}
					,{key:"remove",label:"",hidden:(Dom.get('dispatch_state').value=='In Process'?false:true), width:5,sortable:false}
			     ,{key:"to_charge",label:"To Charge", width:75,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				,{key:"dispatching_status",label:"Status" ,hidden:(Dom.get('dispatch_state').value!='In Process'?false:true),width:120,sortable:false,className:"aright"}

				];

		//alert("ar_edit_orders.php?tipo=transactions_to_process&tid=0&sf=0&f_value=&display="+Dom.get('products_display_type').value);
		request="ar_orders.php?tipo=transactions_in_warehouse&tid=0&sf=0&f_value=&order_key="+Dom.get('order_key').value+'&store_key='+Dom.get('store_key').value;
	//  alert(request)
	  this.dataSource0 = new YAHOO.util.DataSource(request);
//alert("ar_orders.php?tipo=transactions_in_warehouse&tid=0&sf=0&f_value=&order_key="+Dom.get('order_key').value)

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
									      rowsPerPage:<?php echo$_SESSION['state']['order']['items']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['order']['items']['order']?>",
									 dir: "<?php echo$_SESSION['state']['order']['items']['order_dir']?>"
								     }
							   ,dynamicData : true
								   }
								   
								   );
	

	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginator = mydoBeforePaginatorChange;
	  	
	  	
	  			this.table0.table_id=tableid;
     	this.table0.subscribe("renderEvent", myrenderEvent);

	    this.table0.filter={key:'<?php echo$_SESSION['state']['order']['items']['f_field']?>',value:''};
	    
	    
	    
	    
	    var tableid=2; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
			 {key:"key", label:"",width:100,hidden:true}
                    ,{key:"code", label:"<?php echo _('Alias')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
						
			];
		this.dataSource2 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=active_staff_list&active=Yes&tableid="+tableid+"&nr=20&sf=0");
//alert("ar_quick_tables.php?tipo=active_staff_list&active=Yes&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    	    this.dataSource2.table_id=tableid;

	    this.dataSource2.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "code",'name','key'
			 ]};

	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource2
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table2.subscribe("rowMouseoverEvent", this.table2.onEventHighlightRow);
       this.table2.subscribe("rowMouseoutEvent", this.table2.onEventUnhighlightRow);
      this.table2.subscribe("rowClickEvent", select_staff_from_list);
        this.table2.table_id=tableid;
           this.table2.subscribe("renderEvent", myrenderEvent);


	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'code',value:''};	    
	    
	    
	    
    
    };
  });




function init() {

    init_search('orders_store');

    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
    oAutoComp.minQueryLength = 0;

    Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
    Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);


    Event.addListener("modify_order", "click", modify_order);

  //  Event.addListener("process_order", "click", show_process_order_dialog);

    Event.addListener("approve_dispatching", "click", approve_dispatching);
    Event.addListener("set_as_dispatched", "click", set_as_dispatched);

    Event.addListener("create_invoice", "click", create_invoice);

/*
    process_order_dialog = new YAHOO.widget.Dialog("process_order_dialog", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    process_order_dialog.render();
*/
    dialog_other_staff = new YAHOO.widget.Dialog("dialog_other_staff", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_other_staff.render();


  assign_packer_dialog = new YAHOO.widget.Dialog("assign_packer_dialog", {
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });
    assign_packer_dialog.render();


    assign_picker_dialog = new YAHOO.widget.Dialog("assign_picker_dialog", {
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });
    assign_picker_dialog.render();

    dialog_pick_it = new YAHOO.widget.Dialog("dialog_pick_it", {
        context: ["process_order", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_pick_it.render();

    Event.addListener("close_process_order_dialog", "click", dialog_pick_it.hide, dialog_pick_it, true);
    pick_assigned_dialog = new YAHOO.widget.Dialog("pick_assigned_dialog", {
        context: ["pack_it", "tr", "tl"],
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });

    pick_assigned_dialog.render();
    pick_it_dialog = new YAHOO.widget.Dialog("pick_it_dialog", {
        context: ["process_order", "tr", "br"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    pick_it_dialog.render();


    Event.addListener("process_dn_packing", "click", show_process_dn_packing_dialog);

    dialog_pack_it = new YAHOO.widget.Dialog("dialog_pack_it", {
        context: ["process_dn_packing", "tr", "br"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_pack_it.render();



}



function pick_it_delete(o, dn_key) {
process_order_dialog.hide()


pick_it(o,   Dom.get('current_delivery_note_key').value)

}

function create_invoice(){

Dom.get('create_invoice_img').src='art/loading.gif'
var order_key=Dom.get('order_key').value;


    var request='ar_edit_orders.php?tipo=create_invoice_order&order_key='+escape(order_key);
//  alert(request); //return;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
		//		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state==200) {
		        location.href='invoice.php?id='+r.invoice_key;
		  
		}else{
		alert(r.msg)
		  
	    }
	    }
	});    

}

function change_parcel_type(o){


Dom.get('parcel_type').value=o.getAttribute('valor');

Dom.removeClass(Dom.getElementsByClassName('parcel_type', 'button', 'parcel_type_options'),'selected')
Dom.addClass(o,'selected')


}

function set_as_dispatched(dn_key){

Dom.get('set_as_dispatched_img_'+dn_key).src='art/loading.gif';

ar_file='ar_edit_orders.php';
   	request=ar_file+'?tipo=set_as_dispatched_dn&dn_key='+dn_key
   //alert(request)
   YAHOO.util.Connect.asyncRequest(
        'GET',
    request, {
		success: function (o) {
		//alert(o.responseText)
var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
     				//window.location='dn.php?id='+Dom.get('dn_key').value;
location.reload(); 
            }

        },
failure: function (o) {
            alert(o.statusText);
        },
scope:this
    }
    );
}
function approve_dispatching() {

    Dom.get('approve_dispatching_img').src = 'art/loading.gif';

    ar_file = 'ar_edit_orders.php';
    request = ar_file + '?tipo=approve_dispatching_order&order_key=' + Dom.get('order_key').value;
    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                //window.location='dn.php?id='+Dom.get('dn_key').value;
                location.reload();
            }
        },
        failure: function(o) {
            alert(o.statusText);
        },
        scope: this
    });
}

function close_process_order_dialog(){

Dom.setStyle('process_order_buttons','display','')
Dom.setStyle(['assign_pickers_packers','quick_invoice_buttons','step_by_step_invoice_buttons'],'display','none')


 process_order_dialog.hide()
}

function show_process_order_dialog(){

region1 = Dom.getRegion('process_order'); 
    region2 = Dom.getRegion('process_order_dialog'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('process_order_dialog', pos);
	process_order_dialog.show()
	Dom.get('Assign_Picker_Staff_Name').focus();


}

function show_step_by_step_invoice_dialog(){

Dom.setStyle('process_order_buttons','display','none')
Dom.setStyle(['assign_pickers_packers','step_by_step_invoice_buttons'],'display','')

}


function show_quick_invoice_dialog(){

Dom.setStyle('process_order_buttons','display','none')
Dom.setStyle(['assign_pickers_packers','quick_invoice_buttons'],'display','')


}

function step_by_step_invoice(){
parcels=Dom.get('number_parcels').value
weight=Dom.get('parcels_weight').value
 	var request='ar_edit_orders.php?tipo=assign_picker_and_packer_to_order&parcel_type='+Dom.get('parcel_type')+'&parcels='+parcels+'&weight='+weight+'&order_key='+Dom.get('order_key').value+'&picker_key='+Dom.get('assign_picker_staff_key').value+'&packer_key='+Dom.get('assign_packer_staff_key').value
    
      Dom.setStyle('step_by_step_invoice_buttons_tr','display','none')

   Dom.setStyle('step_by_step_invoice_wait','display','block')
   
   
    
    YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {

		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){     
		    	    window.location='order_pick_aid.php?id='+r.dn_key
			}

		},failure:function(o){
		    
		}
	    
	    });
}


function quick_invoice(){

parcels=Dom.get('number_parcels').value
weight=Dom.get('parcels_weight').value

   Dom.setStyle('quick_invoice_invoice_buttons_tr','display','none')

   Dom.setStyle('quick_invoice_invoice_wait','display','block')

    	var request='ar_edit_orders.php?tipo=quick_invoice&parcel_type='+Dom.get('parcel_type').value+'&parcels='+parcels+'&weight='+weight+'&order_key='+Dom.get('order_key').value+'&picker_key='+Dom.get('assign_picker_staff_key').value+'&packer_key='+Dom.get('assign_packer_staff_key').value
  //  alert(request)
    YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
	//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
	
		
		if(r.state=='200'){     
			location.reload(); 
			//window.location='invoice.php?id='+r.invoice_key
		}

		},failure:function(o){
		    
		}
	    
	    });
               
         


}


function modify_order(){
window.location='order.php?id='+Dom.get('order_key').value+'&amend=1';
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
