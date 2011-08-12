<?php
    include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;var Event = YAHOO.util.Event;
var dn_key='<?php echo $_SESSION['state']['supplier_dn']['id'] ?>'
var supplier_id='<?php echo$_SESSION['state']['supplier']['id']?>';
var show_all='<?php echo $_SESSION['state']['porder']['show_all']?>';

var receivers = new Object;
var checkers= new Object;

var active_editor='';
var receiver_list;
var checker_list;
var received_dialog;
var staff_dialog;
var delete_dialog;





var myCellEdit = function (callback, newValue) {
    var record = this.getRecord(),
    column = this.getColumn(),
    oldValue = this.value,
    datatable = this.getDataTable();
    recordIndex = datatable.getRecordIndex(record);
   
    ar_file='ar_edit_porders.php';
    
    
    
    
    var request='tipo=edit_'+column.object+'&key=' + column.key + '&newvalue=' + encodeURIComponent(newValue) +  myBuildUrl(datatable,record)+'&supplier_delivery_note_key='+Dom.get('supplier_delivery_note_key').value;
 

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
						

					
						//if(r.quantity==0 && !show_all){
						//    datatable.deleteRow(record);
						//}
						
						callback(true, r.quantity);
					    } else {
						//alert(r.msg);
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
	    var new_qty=parseFloat(data['dn_quantity'])+1;
	else
	    var new_qty=parseFloat(data['dn_quantity'])-1;

	var ar_file='ar_edit_porders.php';
	request='tipo=edit_new_supplier_dn&key=quantity&newvalue='+new_qty+'&oldvalue='+data['quantity']+'&id='+ data['id']+'&supplier_delivery_note_key='+Dom.get('supplier_delivery_note_key').value;
	//	alert(ar_file+'?'+request);return;
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

					

						    datatable.updateCell(record,'dn_quantity',r.quantity);
						  
					
						    //if(r.quantity==0 && !show_all){
						    //	this.deleteRow(target);
						    // }
						

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
   
case('delete'):
	delete_dialog.hide();

	break;
    }
  
} 




function delete_order() {
    var request='ar_edit_porders.php?tipo=delete_dn&id='+dn_key;
    // alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	      
	    success:function(o) {
		//	  alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    location.href='supplier.php?id='+supplier_id;
		}else{
		    Dom.get('delete_dialog_msg').innerHTNML=r.msg;
		}
	    }
	});    

}







var input_order_save=function(o){
    var request='ar_edit_porders.php?tipo=input_dn&id='+escape(dn_key);

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
		    //alert(o.responseText)
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if (r.state == 200) {
			
			location.href='supplier_dn.php?id='+dn_key;

			

		    }else
			alert(r.msg);
	    }
	    });    
};
    
    




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

				  ,{key:"quantity",label:"<?php echo _('PO Qty')?>", width:40,sortable:false,className:"aright"}
				  // ,{key:"stock", label:"<?php echo _('Stock O(U)')?>",width:90,className:"aright"}
				  // ,{key:"stock_time", label:"<?php echo _('Stock Time')?>",width:75,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  // ,{key:"expected_qty_edit", label:"<?php echo _('Qty O[U]')?>",width:70,className:"aright"}
				  // ,{key:"expected_qty", label:"<?php echo _('Qty O[U]')?>",width:100,className:"aright"}
								  ,{key:"unit_type", label:"<?php echo _('PO U')?>",width:30,className:"aleft"}

			
				  ,{key:"dn_quantity",label:"<?php echo _('DN Qty')?>", width:40,sortable:false,className:"aright",  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: myCellEdit}),object:'new_supplier_dn','action':'change_qty'}
				  ,{key:"dn_unit_type", label:"<?php echo _('DN U')?>",width:30,className:"aleft"}

				  ,{key:"add",label:"", width:3,sortable:false,action:'add_object',object:'new_order'}
				  ,{key:"remove",label:"", width:3,sortable:false,action:'remove_object',object:'new_order'}

				 
				 // ,{key:"amount", label:"<?php echo _('Net Cost')?>",width:50,className:"aright"}
				  // ,{key:"qty_edit", label:"<?php echo _('Qty [U]')?>",width:50,className:"aright",hidden:true}
				  // ,{key:"diff", label:"<?php echo _('&Delta;U')?>",width:40,className:"aright",hidden:true}
				  //,{key:"damaged_edit", label:"<?php echo _('Damaged')?>",width:60,className:"aright",hidden:true}
				  //,{key:"damaged", label:"<?php echo _('Damaged')?>",width:60,className:"aright"}
				  //,{key:"usable", label:"<?php echo _('In O[U]')?>",width:55,className:"aright"}


				  ];
		
		this.dataSource0 = new YAHOO.util.DataSource("ar_edit_porders.php?tipo=dn_transactions_to_process&tableid="+tableid);
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
			     "id","code","description","quantity","amount","unit_type","add","remove","used_in","dn_quantity","dn_unit_type"
			     ]};
	    
		this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							 this.dataSource0, {
							     //draggableColumns:true,
							     renderLoopSize: 50,generateRequest : myRequestBuilder
							     ,paginator : new YAHOO.widget.Paginator({
								     rowsPerPage:<?php echo$_SESSION['state']['supplier_dn']['products']['nr']?>,containers : 'paginator0', 
								     pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								     previousPageLinkLabel : "<",
								     nextPageLinkLabel : ">",
								     firstPageLinkLabel :"<<",
								     lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
								     ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								 })
								 
							     ,sortedBy : {
								 key: "<?php echo$_SESSION['state']['supplier_dn']['products']['order']?>",
								 dir: "<?php echo$_SESSION['state']['supplier_dn']['products']['order_dir']?>"
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


		this.table0.filter={key:'<?php echo$_SESSION['state']['supplier_dn']['products']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier_dn']['products']['f_value']?>'};
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


         
function take_values_from_pos(){

	var ar_file='ar_edit_porders.php';
	request='tipo=take_values_from_pos&dn_key='+dn_key;
	//	alert(ar_file+'?'+request)
	YAHOO.util.Connect.asyncRequest(
					'POST',
					ar_file, {
					    success:function(o) {
						var r = YAHOO.lang.JSON.parse(o.responseText);
						if (r.state == 200) {
						    var tableid=0;
						    var table=tables['table'+tableid];
						    
						    var datasource=tables['dataSource'+tableid];
						    table.filter.value=Dom.get('f_input'+tableid).value;
						    var request='&show_all=no';
						    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       

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
	

}

function init(){

//take_values_from_pos();
   // Event.addListener("take_values_from_pos", "click", take_values_from_pos);

 Event.addListener("save_inputted_dn", "click", input_order_save);


    YAHOO.util.Event.addListener('show_all', "click",change_show_all);
    
  
    delete_dialog = new YAHOO.widget.Dialog("delete_dialog", {context:["delete_dn","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
    delete_dialog.render();
   
    Event.addListener("delete_dn", "click", delete_dialog.show,delete_dialog , true);

  
    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
    oAutoComp.minQueryLength = 0; 

  
      Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);

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


