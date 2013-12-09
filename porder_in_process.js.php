<?php
    include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;var Event = YAHOO.util.Event;

var receivers = new Object;
var checkers= new Object;

var active_editor='';
var receiver_list;
var checker_list;
var submit_dialog;
var staff_dialog;
var delete_dialog;

var myCellEdit = function (callback, newValue) {
    var record = this.getRecord(),
    column = this.getColumn(),
    oldValue = this.value,
    datatable = this.getDataTable();
    recordIndex = datatable.getRecordIndex(record);
    
    ar_file='ar_edit_porders.php';
    
    var request='tipo=edit_'+column.object+'&key=' + column.key + '&newvalue=' + encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ myBuildUrl(datatable,record);
   // alert(ar_file+'?'+request);
    
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
						
						datatable.updateCell(record,'amount',r.to_charge);
					
						if(r.quantity==0 && Dom.get('products_display_type').value=='ordered_products'){
						    datatable.deleteRow(record);
						}
						
						callback(true, r.quantity);
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




var myonCellClick = function(oArgs) {


    var target = oArgs.target,
    column = this.getColumn(target),
    record = this.getRecord(target);


    
    datatable = this;
    var records=this.getRecordSet();
    //alert(records.getLength())
   
    // alert(column.action);
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

	var ar_file='ar_edit_porders.php';
	request='tipo=edit_new_porder&key=quantity&newvalue='+new_qty+'&oldvalue='+data['quantity']+'&id='+ data['id'];
	//alert(ar_file+'?'+request)
	YAHOO.util.Connect.asyncRequest(
					'POST',
					ar_file, {
					    success:function(o) {
						alert(o.responseText);
						var r = YAHOO.lang.JSON.parse(o.responseText);
						if (r.state == 200) {
						    for(x in r.data){

							Dom.get(x).innerHTML=r.data[x];
						    }

					

						    datatable.updateCell(record,'quantity',r.quantity);
						    if(r.quantity==0)
							r.to_charge='';
						    datatable.updateCell(record,'amount',r.to_charge);
					
						    if(r.quantity==0 && Dom.get('products_display_type').value=='ordered_products'){
							this.deleteRow(target);
						    }
						

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



function close_dialog(tipo){
    switch(tipo){
    case('submit'):
	submit_dialog.hide();
	Dom.get('tr_manual_submit_date').style.display="";
	Dom.get('tbody_manual_submit_date').style.display="none";
	Dom.get('date_type').value='auto';

	break;
    case('staff'):
	staff_dialog.hide();

	break;
case('delete'):
	delete_dialog.hide();

	break;
    }
  
} 




function delete_order() {
    var request='ar_edit_porders.php?tipo=delete_po&id'+Dom.get('po_key').value;
    alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	      
	    success:function(o) {
		 // alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    location.href='supplier.php?id='+supplier_id;
		}else{
		    Dom.get('delete_dialog_msg').innerHTNML=r.msg;
		}
	    }
	});    

}


function show_only_ordered_products() {



    Dom.removeClass('all_products', 'selected')
    Dom.addClass('ordered_products', 'selected')

    var table = tables['table0'];
    var datasource = tables['dataSource0'];
    var request = '&display=ordered_products';
    Dom.get('products_display_type').value = 'ordered_products';
    hide_filter('', 0)


    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function show_all_products() {
    Dom.removeClass('ordered_products', 'selected')
    Dom.addClass('all_products', 'selected')

    var table = tables['table0'];
    var datasource = tables['dataSource0'];
    var request = '&display=all_products';
    Dom.get('products_display_type').value = 'all_products';

    hide_filter('', 0)

    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}



var select_staff=function(o,e){

    var staff_id=o.getAttribute('staff_id');
    var staff_name=o.innerHTML;
    o.className='selected';
	
    Dom.get('submitted_by').value=staff_id;
    Dom.get('submited_by_alias').innerHTML=staff_name;
	
    close_dialog('staff');
}


    var submit_order_save=function(o){

	var submit_date=Dom.get('v_calpop1').value;
//	var submit_time=Dom.get('v_time').value;
	var estimated_date=''
//	var date_type=Dom.get('date_type').value;
	var submit_method=Dom.get('submit_method').value;
	var staff_key=Dom.get('submitted_by').value;
	
	var request='ar_edit_porders.php?tipo=submit&submit_method='+escape(submit_method)+'&staff_key='+escape(staff_key)+'&submit_date='+escape(submit_date)+'&id='+escape(Dom.get('po_key').value);
alert(request)
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		
		success:function(o) {
		  //alert(o.responseText)
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if (r.state == 200) {

			location.href='porder.php?id='+Dom.get('po_key').value;

		    }else
			alert(r.msg);
		}
	    });    
    }

	    var swap_show_all_products=function(o){

		var status=o.getAttribute('status');
		//alert(status)

		if(status==0){
		    o.className='selected but';
		    Dom.get('show_items').className='but';
		    var table=tables['table0'];
		    var datasource=tables['dataSource0'];
		    var request='&all_products=0&all_products_supplier=1';
	
		    Dom.get("clean_table_controls0").style.visibility='visible';
		    Dom.get("clean_table_filter0").style.visibility='visible';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    
		}


    
	    };
    


YAHOO.util.Event.addListener(window, "load", function() {
	tables = new function() {
		
		var tableid=0;
		var tableDivEL="table"+tableid;
		var ColumnDefs = [
				  {key:"id", label:"<?php echo _('SPK')?>", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				     
				  ,{key:"code", label:"<?php echo _('Code')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				 
				  ,{key:"description", label:"<?php echo _('Description')?>",width:300, sortable:false,className:"aleft"}
				  ,{key:"used_in", label:"<?php echo _('Used In')?>",width:200, sortable:false,className:"aleft"}

				  ,{key:"quantity",label:"<?php echo _('Qty')?>", width:40,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: myCellEdit}),object:'new_porder','action':'change_qty'}
				  // ,{key:"stock", label:"<?php echo _('Stock O(U)')?>",width:90,className:"aright"}
				  // ,{key:"stock_time", label:"<?php echo _('Stock Time')?>",width:75,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  // ,{key:"expected_qty_edit", label:"<?php echo _('Qty O[U]')?>",width:70,className:"aright"}
				  // ,{key:"expected_qty", label:"<?php echo _('Qty O[U]')?>",width:100,className:"aright"}
				  ,{key:"add",label:"", width:3,sortable:false,action:'add_object',object:'new_order'}
				  ,{key:"remove",label:"", width:3,sortable:false,action:'remove_object',object:'new_order'}

				  ,{key:"unit_type", label:"<?php echo _('Unit')?>",width:30,className:"aleft"}
				  ,{key:"amount", label:"<?php echo _('Net Cost')?>",width:50,className:"aright"}
				  // ,{key:"qty_edit", label:"<?php echo _('Qty [U]')?>",width:50,className:"aright",hidden:true}
				  // ,{key:"diff", label:"<?php echo _('&Delta;U')?>",width:40,className:"aright",hidden:true}
				  //,{key:"damaged_edit", label:"<?php echo _('Damaged')?>",width:60,className:"aright",hidden:true}
				  //,{key:"damaged", label:"<?php echo _('Damaged')?>",width:60,className:"aright"}
				  //,{key:"usable", label:"<?php echo _('In O[U]')?>",width:55,className:"aright"}


				  ];
		request="ar_edit_porders.php?tipo=po_transactions_to_process&tableid="+tableid+'&display='+Dom.get('products_display_type').value+'&id='+Dom.get('po_key').value+'&supplier_key='+Dom.get('supplier_key').value
		//alert(request)
		this.dataSource0 = new YAHOO.util.DataSource(request);
		
		this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
		this.dataSource0.connXhrMode = "queueRequests";
		this.dataSource0.responseSchema = {
		    resultsList: "resultset.data", 
		    metaFields: {
			rowsPerPage:"resultset.records_perpage",
			rtext:"resultset.rtext",
			rtext_rpp:"resultset.rtext_rpp",
			sort_key:"resultset.sort_key",
			sort_dir:"resultset.sort_dir",
			tableid:"resultset.tableid",
			filter_msg:"resultset.filter_msg",
			totalRecords: "resultset.total_records"
		    },
			
		    fields: [
			     "id","code","description","quantity","amount","unit_type","add","remove","used_in"
			     ]};
	    
		this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							 this.dataSource0, {
							     //draggableColumns:true,
							     renderLoopSize: 50,generateRequest : myRequestBuilder
							     ,paginator : new YAHOO.widget.Paginator({
								     rowsPerPage:<?php echo$_SESSION['state']['porder']['products']['nr']?>,containers : 'paginator', 
								     pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								     previousPageLinkLabel : "<",
								     nextPageLinkLabel : ">",
								     firstPageLinkLabel :"<<",
								     lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
								     ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								 })
								 
							     ,sortedBy : {
								 key: "<?php echo$_SESSION['state']['porder']['products']['order']?>",
								 dir: "<?php echo$_SESSION['state']['porder']['products']['order_dir']?>"
							     }
							     ,dynamicData : true
								 
							 }
							 );
		this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
		this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
		this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
		this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
		this.table0.subscribe("cellClickEvent", myonCellClick);


		this.table0.filter={key:'<?php echo$_SESSION['state']['porder']['products']['f_field']?>',value:'<?php echo$_SESSION['state']['porder']['products']['f_value']?>'};
	    }
	    }
    );






function init(){


    submit_dialog = new YAHOO.widget.Dialog("submit_dialog", {context:["submit_po","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    submit_dialog.render();
    staff_dialog = new YAHOO.widget.Dialog("staff_dialog", {context:["get_submiter","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
    staff_dialog.render();
 delete_dialog = new YAHOO.widget.Dialog("delete_dialog", {context:["delete_po","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    delete_dialog.render();
    Event.addListener("submit_po", "click", submit_dialog.show,submit_dialog , true);
    Event.addListener("get_submiter", "click", staff_dialog.show,staff_dialog , true);
    Event.addListener("delete_po", "click", delete_dialog.show,delete_dialog , true);

    var ids=Dom.getElementsByClassName('radio', 'span', 'submit_method_container');
    YAHOO.util.Event.addListener(ids, "click", swap_radio,'submit_method');

    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
    oAutoComp.minQueryLength = 0; 

  
    cal1 = new YAHOO.widget.Calendar("cal1","cal1Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
    cal1.update=updateCal;
    cal1.id='1';
    cal1.render();
    cal1.update();
    cal1.selectEvent.subscribe(handleSelect, cal1, true); 
   


    YAHOO.util.Event.addListener("calpop1", "click", cal1.show, cal1, true);
   
  Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
   Event.addListener("ordered_products", "click", show_only_ordered_products);
    Event.addListener("all_products", "click", show_all_products);


}



YAHOO.util.Event.onDOMReady(init);
YAHOO.util.Event.onContentReady("filtermenu0", function () {
	var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	
    });


YAHOO.util.Event.onContentReady("rppmenu0", function () {
	var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	
    });


