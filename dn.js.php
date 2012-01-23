<?php
include_once('common.php');
if(!$user->can_view('orders'))
  exit();
?>


YAHOO.namespace ("orders"); 
var assign_picker_dialog;
var pick_it_dialog;
var pick_assigned_dialog;

var assign_packer_dialog;
var pack_it_dialog;
var pack_assigned_dialog;

YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.orders.XHR_JSON = new function() {


	    this.orderLink=  function(el, oRecord, oColumn, oData) {
		var url="order.php?id="+oRecord.getData("id");
		el.innerHTML = oData.link(url);
	    }
	    
	    this.customerLink=  function(el, oRecord, oColumn, oData) {
		if(oData==null)
		    oData='<?php echo _('Error, no customer name')?>';

		var url="contact.php?id="+oRecord.getData("customer_id");
		el.innerHTML = oData.link(url);
	    }


	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var OrdersColumnDefs = [
				     {key:"code", label:"<?php echo _('Code')?>",width:80,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:"<?php echo _('Description')?>",width:370,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"quantity",label:"<?php echo _('Qty')?>", width:50,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      
				  
					 ];

	    this.OrdersDataSource = new YAHOO.util.DataSource("ar_orders.php?tipo=transactions_in_dn&tid=0");
	    this.OrdersDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.OrdersDataSource.connXhrMode = "queueRequests";
	    this.OrdersDataSource.responseSchema = {
		resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "code","description","quantity"
			

			 //,{key:"families",parser:YAHOO.util.DataSource.parseNumber},
			 //	    {key:"active",parser:YAHOO.util.DataSource.parseNumber}
			 ]};
	    //__You shouls not change anything from here

	    //this.OrdersDataSource.doBeforeCallback = mydoBeforeCallback;



	    this.OrdersDataTable = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
								   this.OrdersDataSource, {
								       renderLoopSize: 50
								   }
								   
								   );
	 
	   // this.OrdersDataTable.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
	   // this.OrdersDataTable.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.OrdersDataTable}  } ]);
	   // this.OrdersDataTable.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.OrdersDataTable}  } ]);
	   // this.OrdersDataTable.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.OrdersDataTable}  } ]);
	   // this.OrdersDataTable.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.OrdersDataTable}  } ]);
	   // this.OrdersDataTable.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.OrdersDataTable}  } ]);
	   // this.OrdersDataTable.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.OrdersDataTable}  } ]);
	   // YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", this.OrdersDataTable.paginatorMenu.show, null, this.OrdersDataTable.paginatorMenu);
	   // this.OrdersDataTable.paginatorMenu.render(document.body);

	    



	   // this.OrdersDataTable.filterMenu = new YAHOO.widget.Menu('filternewmenu0',  {context:['filterselector0',"tr", "br"]  });
	   // this.OrdersDataTable.filterMenu.addItems([{ text: "<?php echo _('Name')?>", onclick:{fn:changeFilter,obj:{col:'name',text:"<?php echo _('Order name')?>"},scope:this.OrdersDataTable}  } ]);
	   // this.OrdersDataTable.filterMenu.addItems([{ text: "<?php echo _('Post Code')?>", onclick:{fn:changeFilter,obj:{col:'postcode',text:"<?php echo _('Post Code')?>"},scope:this.OrdersDataTable}  } ]);
	   // YAHOO.util.Event.addListener('filterselector0', "click", this.OrdersDataTable.filterMenu.show, null, this.OrdersDataTable.filterMenu);
	   // this.OrdersDataTable.filterMenu.render(document.body);
	    
	   // this.OrdersDataTable.myreload=reload;
	    //this.OrdersDataTable.sortColumn = mysort;
	    
	    this.OrdersDataTable.id=tableid;
	    this.OrdersDataTable.editmode=false;

	    //this.OrdersDataTable.subscribe("initEvent", dataReturn); 
	    //YAHOO.util.Event.addListener('paginator_next0', "click", nextpage, this.OrdersDataTable); 
	    //YAHOO.util.Event.addListener('paginator_prev0', "click", prevpage, this.OrdersDataTable); 
	    //YAHOO.util.Event.addListener('hidder0', "click", showtable, this.OrdersDataTable); 
	    //YAHOO.util.Event.addListener('resetfilter0', "click", resetfilter, this.OrdersDataTable); 


	    
	};
    });




function init(){


    function mygetTerms(query) {
	var Dom = YAHOO.util.Dom
	var table=YAHOO.orders.XHR_JSON.OrdersDataTable;
	var data=table.getDataSource();
	var newrequest="&sf=0&f_field="+Dom.get('f_field0').value+"&f_value="+Dom.get('f_input0').value;

	//	alert(newrequest);
	data.sendRequest(newrequest,{success:table.onDataReturnInitializeTable, scope:table});
    };
    var oACDS = new YAHOO.widget.DS_JSFunction(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","filtercontainer0", oACDS);
    oAutoComp.minQueryLength = 0; 
 

 assign_picker_dialog = new YAHOO.widget.Dialog("assign_picker_dialog", {visible : false,close:false,underlay: "none",draggable:false});
 assign_picker_dialog.render();
 pick_assigned_dialog = new YAHOO.widget.Dialog("pick_assigned_dialog", {visible : false,close:false,underlay: "none",draggable:false});
 pick_assigned_dialog.render();
 pick_it_dialog = new YAHOO.widget.Dialog("pick_it_dialog", {visible : false,close:false,underlay: "none",draggable:false});
 pick_it_dialog.render();

assign_packer_dialog = new YAHOO.widget.Dialog("assign_packer_dialog", {visible : false,close:false,underlay: "none",draggable:false});
 assign_packer_dialog.render();
 pack_assigned_dialog = new YAHOO.widget.Dialog("pack_assigned_dialog", {visible : false,close:false,underlay: "none",draggable:false});
 pack_assigned_dialog.render();
 pack_it_dialog = new YAHOO.widget.Dialog("pack_it_dialog", {visible : false,close:false,underlay: "none",draggable:false});
 pack_it_dialog.render();    


dialog_other_staff = new YAHOO.widget.Dialog("dialog_other_staff", {context:["other_staff","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_other_staff.render();


}

YAHOO.util.Event.onDOMReady(init);
