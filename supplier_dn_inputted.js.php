<?php
    include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;var Event = YAHOO.util.Event;


var active_editor='';
var receiver_list;
var checker_list;
var received_dialog;
var staff_dialog;






var myCellEdit = function (callback, newValue) {
    var record = this.getRecord(),
    column = this.getColumn(),
    oldValue = this.value,
    datatable = this.getDataTable();
    recordIndex = datatable.getRecordIndex(record);
    var data= record.getData();
    var oldCounted=data['counted'];
    ar_file='ar_edit_porders.php';
    
    var request='tipo=edit_'+column.object+'&key=' + column.key + '&newvalue=' + encodeURIComponent(newValue) + '&old_counted=' + encodeURIComponent(oldCounted)  + '&old_quantity=' + encodeURIComponent(oldValue)+ myBuildUrl(datatable,record);
  // alert(ar_file+'?'+request);
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
						
						datatable.updateCell(record,'counted',r.counted);
						
						//if(r.quantity==0 && !show_all){
						//    datatable.deleteRow(record);
						//}
						
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
   
    //return;

    //alert(datatable)
    var recordIndex = this.getRecordIndex(record);

		
    switch (column.action) {
   
   case('edit_object'):
    case('add_object'):
    case('remove_object'):
	var data = record.getData();
	
	if(column.action=='add_object'){
	    var new_qty=parseFloat(data['received_quantity'])+1;
	   Key='quantity';
	}else if(column.action=='remove_object'){
	    var new_qty=parseFloat(data['received_quantity'])-1;
	   Key='quantity';
	    
	}else{
	   Key='counted';
	    var new_qty='Yes';
	    if(data['counted']=='<?php echo _('Yes')?>')
		new_qty='No';
	}
	oldValue=data['received_quantity'];
	oldCounted =data['counted'];
	var ar_file='ar_edit_porders.php';
	request='tipo=edit_'+column.object+'&key='+key+'&newvalue='+new_qty+ '&old_counted=' + encodeURIComponent(oldCounted)  + '&old_quantity=' + encodeURIComponent(oldValue)+'&id='+ data['id'];
	//	alert(ar_file+'?'+request)
	YAHOO.util.Connect.asyncRequest(
					'POST',
					ar_file, {
					    success:function(o) {
						 //alert(o.responseText);
						var r = YAHOO.lang.JSON.parse(o.responseText);
						if (r.state == 200) {
						    for(x in r.data){

							//Dom.get(x).innerHTML=r.data[x];
						    }

					
						    datatable.updateCell(record,'received_quantity',r.quantity);
							datatable.updateCell(record,'counted',r.counted);

					
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


function delete_order() {
    var request='ar_edit_porders.php?tipo=delete_dn&id='+Dom.get('supplier_delivery_note_key').value;
    // alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	      
	    success:function(o) {
		//	  alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    location.href='supplier.php?id='+Dom.get('supplier_key').value;
		}else{
		    Dom.get('delete_dialog_msg').innerHTNML=r.msg;
		}
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
				 
				  ,{key:"description", label:"<?php echo _('Description')?>",width:320, sortable:false,className:"aleft"}
				  ,{key:"used_in", label:"<?php echo _('Used In')?>",width:160, sortable:false,className:"aleft"}

				  ,{key:"dn_quantity",label:"<?php echo _('DN Qty')?>", width:40,sortable:false,className:"aright"}
				  ,{key:"dn_unit_type", label:"<?php echo _('DN U')?>",width:30,className:"aleft"}

			
				  
				  ,{key:"add",label:"", width:3,hidden:true,sortable:false,action:'add_object',object:'new_supplier_dn'}
				  ,{key:"remove",label:"", width:3,hidden:true,sortable:false,action:'remove_object',object:'new_supplier_dn'}




				  ];
		
		
				request="ar_edit_porders.php?tipo=dn_transactions_to_count&supplier_dn_key="+Dom.get('supplier_delivery_note_key').value+"&tableid="+tableid

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
			     "id","code","description","quantity","amount","unit_type","add","remove","used_in","dn_quantity","dn_unit_type","received_quantity","damaged_quantity","counted","add_damaged"
			     ]};
	    
		this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							 this.dataSource0, {
							     //draggableColumns:true,
							     renderLoopSize: 50,generateRequest : myRequestBuilder
							     ,paginator : new YAHOO.widget.Paginator({
								     rowsPerPage:<?php echo$_SESSION['state']['supplier_dn']['products']['nr']?>,containers : 'paginator', 
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


function submit_date_manually(){
    Dom.get('tr_manual_received_date').style.display="none";
    Dom.get('tbody_manual_received_date').style.display="";
    Dom.get('date_type').value='manual';
}

var select_staff=function(o,e){

    var staff_id=o.getAttribute('staff_id');
    var staff_name=o.innerHTML;
    o.className='selected';
	
    Dom.get('received_by').value=staff_id;
    Dom.get('received_by_alias').innerHTML=staff_name;
	
    close_dialog('staff');
};

var select_location=function(o,e){

    var location_key=o.getAttribute('location_key');
    var location_code=o.innerHTML;
    o.className='selected';
	
    Dom.get('location_key').value=location_key;
    Dom.get('location_code').innerHTML=location_code;
	
    close_dialog('location');
};


function close_dialog(tipo){
    switch(tipo){
   
case('delete'):
	delete_dialog.hide();

	break;
    }
  
} 
  
var received_order_save=function(o){

    var received_date=Dom.get('v_calpop1').value;
    var received_time=Dom.get('v_time').value;
    var staff_key=Dom.get('received_by').value;
    var date_type=Dom.get('date_type').value;
    var location_key=Dom.get('location_key').value;


    var request='ar_edit_porders.php?tipo=receive_dn&id='+escape(Dom.get('supplier_delivery_note_key').value)+'&date_type='+escape(date_type)+'&staff_key='+escape(staff_key)+'&received_date='+escape(received_date)+'&received_time='+escape(received_time)+'&location_key='+escape(location_key);
  
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
		    //alert(o.responseText);
		   
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if (r.state == 200) {
			
			location.href='supplier_dn.php?id='+Dom.get('supplier_delivery_note_key').value;

			

		    }else
			alert(r.msg);
	    }
	    });    
};    

     






function init(){
    
 
  alert("x")

  received_dialog = new YAHOO.widget.Dialog("received_dialog", {context:["receive_dn","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    received_dialog.render();

    
    staff_dialog = new YAHOO.widget.Dialog("staff_dialog", {context:["get_receiver","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
    staff_dialog.render();

    location_dialog = new YAHOO.widget.Dialog("location_dialog", {context:["get_location","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
    location_dialog.render();

    
    Event.addListener("receive_dn", "click", received_dialog.show,received_dialog , true);
    Event.addListener("get_receiver", "click", staff_dialog.show,staff_dialog , true);
    Event.addListener("get_location", "click", location_dialog.show,location_dialog , true);



    ids=['set_damages','set_damages_top'];
    YAHOO.util.Event.addListener(ids, "click",set_damages)

    
 


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


