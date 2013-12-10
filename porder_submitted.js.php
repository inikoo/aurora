<?php
    include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;var Event = YAHOO.util.Event;
var po_id='<?php echo$_SESSION['state']['porder']['id']?>';
var supplier_id='<?php echo$_SESSION['state']['supplier']['id']?>';
var show_all='<?php echo $_SESSION['state']['porder']['show_all']?>';

var receivers = new Object;
var checkers= new Object;

var active_editor='';
var receiver_list;
var checker_list;
var submit_dialog;
var staff_dialog;
var cancel_dialog;
var dn_dialog;
var invoice_dialog;





var myCellEdit = function (callback, newValue) {
    var record = this.getRecord(),
    column = this.getColumn(),
    oldValue = this.value,
    datatable = this.getDataTable();
    recordIndex = datatable.getRecordIndex(record);
   
    ar_file='ar_edit_porders.php';
    
    var request='tipo=edit_'+column.object+'&key=' + column.key + '&newvalue=' + encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ myBuildUrl(datatable,record);
    //alert(ar_file+'?'+request);

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
						
						datatable.updateCell(record,'amount',r.to_charge);
					
						if(r.quantity==0 && !show_all){
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
						//  alert(o.responseText);
						var r = YAHOO.lang.JSON.parse(o.responseText);
						if (r.state == 200) {
						    for(x in r.data){

							Dom.get(x).innerHTML=r.data[x];
						    }

					

						    datatable.updateCell(record,'quantity',r.quantity);
						    if(r.quantity==0)
							r.to_charge='';
						    datatable.updateCell(record,'amount',r.to_charge);
					
						    if(r.quantity==0 && !show_all){
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
    case('edit_estimated_delivery'):
    estimated_delivery_dialog.hide();
    
    case('submit'):
	submit_dialog.hide();
	Dom.get('tr_manual_submit_date').style.display="";
	Dom.get('tbody_manual_submit_date').style.display="none";
	Dom.get('date_type').value='auto';

	break;
    case('cancel'):
	cancel_dialog.hide();
	break;
    case('staff'):
	staff_dialog.hide();
	break;
    case('dn'):
	dn_dialog.hide();
	break;
    case('invoice'):
	invoice_dialog.hide();
	break;

    }
  
} 




function delete_order() {
    var request='ar_edit_porders.php?tipo=delete&id='+po_id;
    // alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	      
	    success:function(o) {
		//	  alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    location.href='supplier.php?id='+supplier_id;
		}else{
		    alert(r.msg);
		}
	    }
	});    

}






var select_staff=function(o,e){

    var staff_id=o.getAttribute('staff_id');
    var staff_name=o.innerHTML;
    o.className='selected';
	
    Dom.get('submitted_by').value=staff_id;
    Dom.get('submited_by_alias').innerHTML=staff_name;
	
    close_dialog('staff');

	  
	





}




	var cancel_order_save=function(){
	var note=Dom.get('cancel_note').value;
	
	 	var request='ar_edit_porders.php?tipo=cancel&note='+escape(note)+'&id='+escape(po_id);
	    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
		    success:function(o) {
//alert(o.responseText)
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if (r.state == 200) {
			location.href='porder.php?id='+po_id;
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
				  ,{key:"quantity_static",label:"<?php echo _('Qty')?>",width:40,sortable:false,className:"aright"}
				  ,{key:"quantity",label:"<?php echo _('Qty')?>", hidden:true,width:40,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: myCellEdit}),object:'new_porder','action':'change_qty'}
				  ,{key:"add",label:"", width:3,hidden:true,sortable:false,action:'add_object',object:'new_order'}
				  ,{key:"remove",label:"", width:3,hidden:true,sortable:false,action:'remove_object',object:'new_order'}

				  ,{key:"unit_type", label:"<?php echo _('Unit')?>",width:30,className:"aleft"}
				  ,{key:"amount", label:"<?php echo _('Net Cost')?>",width:50,className:"aright"}
				

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
			     "id","code","description","quantity","amount","unit_type","add","remove","used_in","quantity_static"
			     ]};
	    
		this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							 this.dataSource0, {
							     //draggableColumns:true,
							     renderLoopSize: 50,generateRequest : myRequestBuilder
							     ,paginator : new YAHOO.widget.Paginator({
								     rowsPerPage:<?php echo$_SESSION['state']['porder']['products']['nr']?>,containers : 'paginator0', 
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

function change_show_all(){

    var state=this.getAttribute('state');
    var alter=Dom.get('show_all').getAttribute('atitle');

    var current=Dom.get('show_all').innerHTML;
    Dom.get('show_all').innerHTML=alter;
    Dom.get('show_all').setAttribute('atitle',current);


    if(state==1){
	show_all=0;
	tag='no'
	    Dom.get('show_all').setAttribute('state',0);
    }else{
	show_all=1;
	tag='yes'
	    Dom.get('show_all').setAttribute('state',1);

      
    }
  
    
    var table=tables['table0'];
    var datasource=tables['dataSource0'];
    var request='&show_all='+tag;
    // alert(request);
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
}

function submit_date_manually(){
    Dom.get('tr_manual_submit_date').style.display="none";
    Dom.get('tbody_manual_submit_date').style.display="";
    Dom.get('date_type').value='manual';
}




function dn_order_save(){
    var number=Dom.get('dn_number').value;
    if(number==''){
	Dom.get('dn_dialog_msg').innerHTML='<?php echo _('Supplier Delivery Note number is required')?>';
	return;
    }else{
	Dom.get('dn_dialog_msg').innerHTML='';
    }
    
   var dn_date=Dom.get('v_calpop1').value;

    location.href='supplier_dn.php?new=1&po='+po_id+'&number='+encodeURIComponent(number)+'&date='+dn_date;
}


function submit_edit_estimated_delivery(){
    var date=Dom.get('v_calpop_estimated_delivery').value;
    
    var ar_file='ar_edit_porders.php';
	request='tipo=edit_porder&key=estimated_delivery&newvalue='+encodeURIComponent(date)+'&id='+po_id;
	alert(ar_file+'?'+request)
	YAHOO.util.Connect.asyncRequest(
					'POST',
					ar_file, {
					    success:function(o) {
					 //alert(o.responseText);
						var r = YAHOO.lang.JSON.parse(o.responseText);
						if (r.state == 200) {
						  Dom.get('estimated_delivery').innerHTML=r.newvalue;
						    estimated_delivery_dialog.hide();


						    //	callback(true, r.newvalue);
						} else {
						    alert('xx'+r.msg);
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


function init(){

cal2 = new YAHOO.widget.Calendar("cal2","estimated_delivery_Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
 
 cal2.update=updateCal;
 cal2.id='_estimated_delivery';
 cal2.render();
 cal2.update();
 cal2.selectEvent.subscribe(handleSelect, cal2, true); 
  YAHOO.util.Event.addListener("estimated_delivery_pop", "click", cal2.show, cal2, true);


 estimated_delivery_dialog = new YAHOO.widget.Dialog("edit_estimated_delivery_dialog", {context:["edit_estimated_delivery","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    estimated_delivery_dialog.render();
     Event.addListener("edit_estimated_delivery", "click", estimated_delivery_dialog.show,estimated_delivery_dialog , true);


 cancel_dialog = new YAHOO.widget.Dialog("cancel_dialog", {context:["cancel_po","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    cancel_dialog.render();
     Event.addListener("cancel_po", "click", cancel_dialog.show,cancel_dialog , true);
//alert('x');

    //YAHOO.util.Event.addListener('show_all', "click",change_show_all);

    submit_dialog = new YAHOO.widget.Dialog("submit_dialog", {context:["submit_po","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    submit_dialog.render();
    staff_dialog = new YAHOO.widget.Dialog("staff_dialog", {context:["get_submiter","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
    staff_dialog.render();
   
 dn_dialog = new YAHOO.widget.Dialog("dn_dialog", {context:["dn_po","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dn_dialog.render();

    Event.addListener("dn_po", "click", dn_dialog.show,dn_dialog , true);

 Event.addListener("get_canceller", "click", staff_dialog.show,staff_dialog , true);
 //  alert('x');

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


