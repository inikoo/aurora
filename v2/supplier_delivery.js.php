<?php
include_once('common.php');
?>
     var Dom   = YAHOO.util.Dom;
var po_id='<?php echo$_SESSION['state']['porder']['id']?>';
var supplier_id='<?php echo$_SESSION['state']['supplier']['id']?>';

var receivers = new Object;
var checkers= new Object;

var active_editor='';
var receiver_list;
var checker_list;


var match_invoice_open=function(){
    Dom.get("match_invoice").style.display='none'
    Dom.get("match_invoice_dialog").style.display=''
}
var match_invoice_close=function(){
    Dom.get("match_invoice_dialog").style.display='none'
    Dom.get("match_invoice").style.display=''
}
var match_invoice_save=function(){

}


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





var eqty=function(o,id,units){
    Dom.get('diff'+id).innerHTML=0;
    Dom.get('qc'+id).value=o.innerHTML;
    var damaged=Dom.get('du'+id).value;
    Dom.get('uu'+id).innerHTML=o.innerHTML-damaged;
    if(units!=1)
	Dom.get('uo'+id).innerHTML=(o.innerHTML-damaged)/units;

};

var value_checked=function(o,id,units,add_product){
    

	var qty=o.value;

	var eqty=Dom.get('eqty'+id).innerHTML;
	if(eqty=='')
	    eqty=0;
	var diff=qty-eqty;
	if(diff>0)
	    diff='+'+diff;
	Dom.get('diff'+id).innerHTML=diff;
	
	var damaged=Dom.get('du'+id).value;
	Dom.get('uu'+id).innerHTML=qty-damaged;
	if(units!=1)
	    Dom.get('uo'+id).innerHTML=(qty-damaged)/units;

    
	  var request='ar_assets.php?tipo=order_add_item&tipo_order=po&product_id='+escape(o.getAttribute('pid'))+'&qty='+escape(o.value)+'&order_id='+escape(po_id);
	//	alert(request);
	    YAHOO.util.Connect.asyncRequest('POST',request ,{
		    
		    success:function(o) {
			alert(o.responseText)
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if (r.state == 200) {
			    Dom.get('distinct_products').innerHTML=r.data.items;
			    Dom.get('goods').innerHTML=r.data.money.goods;
			    Dom.get('vat').innerHTML=r.data.money.vat;
			    Dom.get('total').innerHTML=r.data.money.total;
			    //Dom.get('oqty'+r.item_data.id).innerHTML=r.item_data.outers;
			    //Dom.get('ep'+r.item_data.id).innerHTML=r.item_data.est_price;
			}
		    }
		});    
	


};

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


var value_damaged=function(o,id,units){
    var damaged=o.value;
    var qty=Dom.get('qc'+id).value;

    Dom.get('uu'+id).innerHTML=qty-damaged;
    if(units!=1)
	Dom.get('uo'+id).innerHTML=(qty-damaged)/units;

}



var add_staff=function(tipo,id,name){
    if(tipo=='receivers')
	receivers[id]={'name':name,'id':id};
    else if (tipo=='checkers')
	checkers[id]={'name':name,'id':id};
}
var delete_staff=function(tipo,id){
    if(tipo=='receivers')
	delete receivers.id;
    else if(tipo=='checkers')
	delete checkers.id;
}

var display_staff=function(tipo){
    var names='';
    if(tipo=='receivers'){
	var i=0;
	
	for( id in receivers){
	    if(i>0)
		names+=', ';
	    if(receivers[id].name!='undefined')
		names+=receivers[id].name;
	    i++;
	}
    }else if(tipo=='checkers'){
	var i=0;
	for( id in checkers){
	      if(i>0)
		names+=', ';
	      if(checkers[id].name!='undefined')
		  names+=checkers[id].name;
	    i++;
	}
    }
    
    return names;

}




var select_staff=function(o,e,tipo){

	var staff_id=o.getAttribute('staff_id');
	var staff_name=o.innerHTML;
	if (e.ctrlKey==1 || receivers.length==0){
	    add_staff(tipo,staff_id,staff_name);
	    o.className='selected';
	    Dom.get(tipo+"_name").innerHTML=display_staff(tipo);
	}else if(e.ctrlKey==0){
	    if(tipo=='receivers'){
		for( id in receivers){
		    Dom.get(tipo+id).className='';
		}
		receivers = new Object;
	    }else if(tipo=='checkers'){
		for( id in checkers){
		    Dom.get(tipo+id).className='';
		}
		checkers = new Object;
	    }


	    Dom.get(tipo+"_name").innerHTML=staff_name;
	    add_staff(tipo,staff_id,staff_name);
	    
	    if(tipo=='receivers')
		receiver_list.cfg.setProperty("visible", false); 
	    else
		checker_list.cfg.setProperty("visible", false); 

	    o.className='selected';
	}


}

var clear_editors=function(){
    Dom.get('cancel_dialog').style.display='none';
    Dom.get('submit_dialog').style.display='none';
    Dom.get('expected_dialog').style.display='none';
    Dom.get('receive_dialog').style.display='none';
    Dom.get('check_dialog').style.display='none';
    Dom.get('consolidate_dialog').style.display='none';
    Dom.get('cancel_dialog').style.display='none';
	active_editor='';
}

var submit_order_save=function(o){
    var date=Dom.get('v_calpop1').value;
    var time=Dom.get('v_time').value;
    var edate=Dom.get('v_calpop2').value;

    var request='ar_assets.php?tipo=order_submit&tipo_order=po&date='+escape(date)+'&time='+escape(time)+'&edate='+escape(edate)+'&order_id='+escape(po_id);
    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
		//	alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {

		    Dom.get("po_title").innerHTML=r.title;
		    Dom.get("submit_noready").style.display='none';
		    Dom.get("submit_ready").style.display='';
		    Dom.get("submit_date").innerHTML=r.date_submited;
		    clear_editors();
		    

		    if(r.date_expected==''){
			Dom.get("expected_ready").style.display='none';
			Dom.get("expected_change").style.display='none';
			Dom.get("expected_noready").style.display='';
			
		    }else{
			Dom.get("expected_ready").style.display='';
			Dom.get("expected_date").innerHTML=r.date_expected;
			Dom.get("expected_noready").style.display='none';
			Dom.get("expected_change").style.display='';
		    }
		    //now change the view to only items in po
		    Dom.get("table_all_products").innerHTML='<?php echo _('Amend order')?>';
		    Dom.get("table_all_products").className='but';
		    Dom.get("table_po_products").className='but selected';

		    var table=tables['table0'];
		    var datasource=tables['dataSource0'];
		    var request='&show_all=0';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    


		    table.hideColumn('stock');
		    table.hideColumn('stock_time');
		    table.hideColumn('expected_qty_edit');
		    table.hideColumn('price_unit');
		    table.hideColumn('expected_price');
		    table.showColumn('expected_qty');	    
		    table.showColumn('sup_code');	    
		    



		}else
		    alert(r.msg);
	    }
	});    
}


var et_order_save=function(o){
    var date=Dom.get('v_calpop7').value;
    var time='12:00:00';

    var request='ar_assets.php?tipo=order_expected&tipo_order=po&date='+escape(date)+'&time='+escape(time)+'&order_id='+escape(po_id);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
		//	alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    	Dom.get("expected_ready").style.display='';
			Dom.get("expected_date").innerHTML=r.date;
			Dom.get("expected_noready").style.display='none';
			Dom.get("expected_change").style.display='';
			clear_editors();
		}else
		    alert(r.msg);
	    }
	});    
}

var receive_order_save=function(o){
    var date=Dom.get('v_calpop3').value;
    var time=Dom.get('v_time3').value;
    var by= YAHOO.lang.JSON.stringify(receivers);
    
    var request='ar_assets.php?tipo=order_received&tipo_order=po&done_by='+escape(by)+'&date='+escape(date)+'&time='+escape(time)+'&order_id='+escape(po_id);
    //    alert(request)
    YAHOO.util.Connect.asyncRequest('POST',request ,{

	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    Dom.get("po_title").innerHTML=r.title;
		    Dom.get("receive_dialog").style.display='none';
		    Dom.get("expected_change").style.display='none';
		    Dom.get("expected_noready").style.display='none';
		    Dom.get("cancel_noready").style.display='none';
		    Dom.get("check_noready").style.display='none';
		    Dom.get("submit_noready").style.display='none';
		    Dom.get("receive_dialog").style.display='none';
		    Dom.get("receive_noready").style.display='none';
		    Dom.get("receive_ready").style.display='';
		    Dom.get("receive_date").innerHTML=r.date;


		}else
		    alert(r.msg);
	    }
	});    
}
var check_order_save=function(o){
    var date=Dom.get('v_calpop4').value;
    var time=Dom.get('v_time4').value;
    var by= YAHOO.lang.JSON.stringify(checkers);
    var request='ar_assets.php?tipo=order_checked&tipo_order=po&done_by='+escape(by)+'&date='+escape(date)+'&time='+escape(time)+'&order_id='+escape(po_id);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
		//		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    Dom.get("po_title").innerHTML=r.title;
		    Dom.get("check_dialog").style.display='none';
		    Dom.get("check_noready").style.display='none';
		    Dom.get("check_ready").style.display='';
		    Dom.get("check_date").innerHTML=r.date;

		    
		}else
		    alert(r.msg);
	    }
	});    
}
var consolidate_order_save=function(o){
    var date=Dom.get('v_calpop5').value;
    var time=Dom.get('v_time5').value;

    var request='ar_assets.php?tipo=order_consolidated&tipo_order=po&date='+escape(date)+'&time='+escape(time)+'&order_id='+escape(po_id);

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		}
	    }
	});    
}

var cancel_order_save=function(o){
    var date=Dom.get('v_calpop6').value;
    var time=Dom.get('v_time6').value;

    var request='ar_assets.php?tipo=order_cancel&tipo_order=po&date='+escape(date)+'&time='+escape(time)+'&order_id='+escape(po_id);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {

		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		}
	    }
	});    
}




var submit_order=function(o){
    if(active_editor=='submit')
	clear_editors();
    else{
	clear_editors();
	Dom.get('submit_dialog').style.display='';
	active_editor='submit'
    }

}
var change_et_order=function(o){

    if(active_editor=='expected')
	clear_editors();
    else{
	clear_editors();
	active_editor='expected';
	Dom.get('expected_dialog').style.display='';

    }
}

var receive_order=function(o){
     if(active_editor=='receive')
	clear_editors();
    else{
	clear_editors();
	active_editor='receive';
	Dom.get('receive_dialog').style.display='';

    }
}
var check_order=function(o){
     if(active_editor=='check')
	clear_editors();
    else{
	clear_editors();
	active_editor='check';
	Dom.get('check_dialog').style.display='';
	//Change table check order format
// 	Dom.get("table_all_products").innerHTML='<?php echo _('Amend order')?>';
	Dom.get("show_found").style.display='';
	Dom.get("show_new_found").style.display='';

// 	Dom.get("table_po_products").className='but selected';

 	var table=tables['table0'];
// 	var datasource=tables['dataSource0'];
// 	var request='&show_all=0';
// 	datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    

//	table.hideColumn('description');
//	table.hideColumn('expected_qty');
	table.hideColumn('price_unit');
	table.showColumn('expected_qty');	    

	table.showColumn('qty_edit');	    
	table.showColumn('diff');	    
	table.showColumn('damaged_edit');	    
	table.showColumn('useful');	    

       
    }

}
var consolidate_order=function(o){
    if(active_editor=='consolidate')
	clear_editors();
    else{
	clear_editors();
	active_editor='consolidate';
	Dom.get('consolidate_dialog').style.display='';
    }
    


}
var cancel_order=function(o){
     if(active_editor=='cancel')
	clear_editors();
    else{
	clear_editors();
	active_editor='cancel';
	Dom.get('cancel_dialog').style.display='';
    }


};

var swap_show_items=function(o){

    var status=o.getAttribute('status');

    if(status==0){
	o.className='selected but';
	Dom.get('show_all_products').className='but';
	var table=tables['table0'];
	var datasource=tables['dataSource0'];
	var request='&all_products=0&all_products_supplier=0';
	
	Dom.get("clean_table_controls0").style.visibility='visible';
	Dom.get("clean_table_filter0").style.visibility='visible';
		datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    
    }else if(status==8){
	o.className='selected but';
	Dom.get('show_found').className='but';
	Dom.get('show_new_found').className='but';
	
	var table=tables['table0'];
	
	table.showColumn('expected_qty');
	table.showColumn('damaged_edit');
	table.showColumn('usable');
	
	var datasource=tables['dataSource0'];
	var request='&all_products=0';
	
	Dom.get("clean_table_controls0").style.visibility='visible';
	Dom.get("clean_table_filter0").style.visibility='visible';


	datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    

    }
    
}

var swap_item_found=function(o){
    Dom.get('show_items').className='but'
    Dom.get('show_new_found').className='but'
    o.className='selected but';
    

    var table=tables['table0'];
    
    table.hideColumn('expected_qty');
    table.hideColumn('damaged_edit');
    table.hideColumn('usable');

    var datasource=tables['dataSource0'];
    var request='&all_products=1';

    Dom.get("clean_table_controls0").style.visibility='visible';
    Dom.get("clean_table_filter0").style.visibility='visible';


    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    

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
    
var match_invoice_changes=0;
var match_invoice_errors=0;

var changed=function(o){
    var ovalue=o.getAttribute('ovalue');
    if(ovalue!=o.value){
	if(Dom.get('changed_'+o.name).style.visibility!='visible'){
	    Dom.get('changed_'+o.name).style.visibility='visible';
	    match_invoice_changes++;
	}
    }else{
	if(Dom.get('changed_'+o.name).style.visibility!='hidden'){
	    Dom.get('changed_'+o.name).style.visibility='hidden';	
	    match_invoice_changes--;
	}
    }
    
    if(o.name=='vat' || o.name=='shipping' || o.name=='diff' || o.name=='charges'){

	var total=
  	parseFloat(FormatNumber(Dom.get("v_goods").innerHTML,'.','',2))
  	+parseFloat(FormatNumber(Dom.get('v_vat').value,'.','',2))
  	+parseFloat(FormatNumber(Dom.get('v_shipping').value,'.','',2))
  	+parseFloat(FormatNumber(Dom.get('v_diff').value,'.','',2))
  	+parseFloat(FormatNumber(Dom.get('v_charges').value,'.','',2));
	total=parseFloat(total).toFixed(2);
 	Dom.get("v_total").innerHTML=FormatNumber(total,'.',',',2);
    }


    if(match_invoice_changes>0 && match_invoice_errors==0)
	Dom.get("match_invoice_save").style.display='';
    else
	Dom.get("match_invoice_save").style.display='none';

}


var value_changed=function(o){

	if(isNaN(o.value)){
	    o.style.background='#fff889';
	}else{

	    var request='ar_assets.php?tipo=order_add_item&tipo_order=po&product_id='+escape(o.getAttribute('pid'))+'&qty='+escape(o.value)+'&order_id='+escape(po_id);
	    YAHOO.util.Connect.asyncRequest('POST',request ,{
		    
		    success:function(o) {
		       	//alert(o.responseText)
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if (r.state == 200) {
			    Dom.get('distinct_products').innerHTML=r.data.items;
			    Dom.get('goods').innerHTML=r.data.money.goods;
			    Dom.get('vat').innerHTML=r.data.money.vat;
			    Dom.get('total').innerHTML=r.data.money.total;
			    Dom.get('oqty'+r.item_data.id).innerHTML=r.item_data.outers;
			    Dom.get('ep'+r.item_data.id).innerHTML=r.item_data.est_price;
			}
		    }
		});    


	}	

    }



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
		
		this.dataSource0 = new YAHOO.util.DataSource("ar_edit_porders.php?tipo=po_transactions_to_process&tableid="+tableid);
		
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
									 rowsPerPage:<?php echo$_SESSION['state']['supplier']['products']['nr']?>,containers : 'paginator', 
									 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								 
								 ,sortedBy : {
								    Key: "<?php echo$_SESSION['state']['supplier']['products']['order']?>",
								     dir: "<?php echo$_SESSION['state']['supplier']['products']['order_dir']?>"
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


		this.table0.filter={key:'<?php echo$_SESSION['state']['supplier']['products']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier']['products']['f_value']?>'};
	    }
	    }
    );




 function init(){
     var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS.queryMatchContains = true;
     var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
     oAutoComp.minQueryLength = 0; 

     cal2 = new YAHOO.widget.Calendar("cal2","cal2Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
     cal2.update=updateCal;
     cal2.id=2;
     cal2.render();
     cal2.update();
     cal2.selectEvent.subscribe(handleSelect, cal2, true); 

     cal1 = new YAHOO.widget.Calendar("cal1","cal1Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
     cal1.update=updateCal;
     cal1.id=1;
     cal1.render();
     cal1.update();
     cal1.selectEvent.subscribe(handleSelect, cal1, true); 
     cal3 = new YAHOO.widget.Calendar("cal3","cal3Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
     cal3.update=updateCal;
     cal3.id=3;
     cal3.render();
     cal3.update();
     cal3.selectEvent.subscribe(handleSelect, cal3, true); 
     cal4 = new YAHOO.widget.Calendar("cal4","cal4Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
     cal4.update=updateCal;
     cal4.id=4;
     cal4.render();
     cal4.update();
     cal4.selectEvent.subscribe(handleSelect, cal4, true); 

     
     cal5 = new YAHOO.widget.Calendar("cal5","cal5Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
     cal5.update=updateCal;
     cal5.id=5;
     cal5.render();
     cal5.update();
     cal5.selectEvent.subscribe(handleSelect, cal5, true); 


     cal7 = new YAHOO.widget.Calendar("cal7","cal7Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
     cal7.update=updateCal;
     cal7.id=7;
     cal7.render();
     cal7.update();
     cal7.selectEvent.subscribe(handleSelect, cal7, true); 


     YAHOO.util.Event.addListener("calpop1", "click", cal1.show, cal1, true);
     YAHOO.util.Event.addListener("calpop2", "click", cal2.show, cal2, true);
     YAHOO.util.Event.addListener("calpop3", "click", cal3.show, cal3, true);

     YAHOO.util.Event.addListener("calpop4", "click", cal4.show, cal4, true);
     YAHOO.util.Event.addListener("calpop5", "click", cal5.show, cal5, true);

     YAHOO.util.Event.addListener("calpop7", "click", cal7.show, cal7, true);//expected






     receiver_list = new YAHOO.widget.Menu("receiver_list", {context:["staff_list_row","tr", "br","beforeShow"]  });
     receiver_list.render();
     receiver_list.subscribe("show", receiver_list.focus);
     YAHOO.util.Event.addListener("choose_receiver", "click", receiver_list.show, null, receiver_list);

        checker_list = new YAHOO.widget.Menu("checker_list", {context:["staff_list_row","tr", "br","beforeShow"]  });
     checker_list.render();
     checker_list.subscribe("show", checker_list.focus);
     YAHOO.util.Event.addListener("choose_checker", "click", checker_list.show, null, checker_list); 



 }

YAHOO.util.Event.onDOMReady(init);
YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });


YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.Menu("rppmenu", { context:["filter_name0","tr", "bl"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("paginator_info0", "click", oMenu.show, null, oMenu);
    });


