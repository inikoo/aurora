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
var cancel_dialog;
var dn_dialog;
var invoice_dialog;









	function cancel_order_save(){
	var note=Dom.get('cancel_note').value;
	
	 	var request='ar_edit_porders.php?tipo=cancel&note='+escape(note)+'&id='+escape(Dom.get('po_key').value);
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





	 
    


YAHOO.util.Event.addListener(window, "load", function() {
	tables = new function() {
		
		var tableid=0;
		var tableDivEL="table"+tableid;
		var ColumnDefs = [
				  {key:"id", label:"<?php echo _('SPK')?>", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				     
				  ,{key:"code", label:"<?php echo _('Code')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				 
				  ,{key:"description", label:"<?php echo _('Description')?>",width:280, sortable:false,className:"aleft"}
				  ,{key:"store_as", label:"<?php echo _('Parts')?>",width:80, sortable:false,className:"aleft"}

				  ,{key:"used_in", label:"<?php echo _('Products')?>",width:140, sortable:false,className:"aleft"}
				  ,{key:"quantity_static",label:"<?php echo _('Qty')?>",width:40,sortable:false,className:"aright"}
	//			  ,{key:"quantity",label:"<?php echo _('Qty')?>", hidden:true,width:40,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: myCellEdit}),object:'new_porder','action':'change_qty'}
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
			     "id","code","description","quantity","amount","unit_type","add","remove","used_in","quantity_static","store_as"
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

    location.href='supplier_dn.php?new=1&po='+Dom.get('po_key').value+'&number='+encodeURIComponent(number)+'&date='+dn_date;
}


function submit_edit_estimated_delivery(){
    var date=Dom.get('v_calpop_estimated_delivery').value;
    
    var ar_file='ar_edit_porders.php';
	request='tipo=edit_porder&key=estimated_delivery&newvalue='+encodeURIComponent(date)+'&id='+Dom.get('po_key').value;
	//alert(ar_file+'?'+request)
	YAHOO.util.Connect.asyncRequest(
					'POST',
					ar_file, {
					    success:function(o) {
					// alert(o.responseText);
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


  init_search('supplier_products_supplier');
  
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


