<?include_once('../common.php')?>
    var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

var newProductData= new Object;
var list = new Object;

var operation='';


var remove_prod=function (pl_id,product_id){x
    var request='ar_assets.php?tipo=pml_desassociate_location&id='+ escape(pl_id)+'&msg=&product_id='+ escape(product_id);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {

		var r =  YAHOO.lang.JSON.parse(o.responseText);

		if (r.state == 200) {
		    var table=tables.table1;
		    var datasource=tables.dataSource1;
		    var request='';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
		    
		}else
		    Dom.get('product_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});
}


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {
	    //START OF THE TABLE=========================================================================================================================
	    
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var CustomersColumnDefs = [
				       {key:"date", label:"<?=_('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author", label:"<?=_('Author')?>", width:70,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"note", label:"<?=_('Description')?>", width:370,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	    //?tipo=customers&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=location_stock_history");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    rtext:"resultset.rtext",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "id"
			 ,"note"
			 ,'author','date'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource0
						     , {
							 // sortedBy: {key:"<?=$_SESSION['tables']['customers_list'][0]?>", dir:"<?=$_SESSION['tables']['customers_list'][1]?>"},
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?=$_SESSION['state']['product']['stock_history']['nr']?>,containers : 'paginator0', alwaysVisible:false,
								 pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							     key: "<?=$_SESSION['state']['product']['stock_history']['order']?>",
							     dir: "<?=$_SESSION['state']['product']['stock_history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table0.filter={key:'<?=$_SESSION['state']['product']['stock_history']['f_field']?>',value:'<?=$_SESSION['state']['product']['stock_history']['f_value']?>'};
	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)




		var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var CustomersColumnDefs = [
				       {key:"code", label:"<?=_('Code')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"description", label:"<?=_('Description')?>", width:390,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"current_qty", label:"<?=_('Qty')?>", width:50,className:"aright"}
				       ,{key:"changed_qty", label:"<?=_('Change')?>", width:50,className:"aright",hidden:true}
				       ,{key:"new_qty", label:"<?=_('New Qty')?>", width:70,className:"aright",hidden:true}
				       ,{key:"_qty_move", label:"<?=_('Moved')?>", width:70,hidden:true,className:"aright"}
				       ,{key:"_qty_damaged", label:"<?=_('Damaged')?>", width:70,hidden:true,className:"aright"}
				       ,{key:"_qty_change", label:"<?=_('Audit')?>", width:50,hidden:true,className:"aright inputs_yellow"}
				       ,{key:"note", label:"<?=_('Note')?>", width:110,className:"aleft",hidden:true}
				       ,{key:"delete", label:"", width:18,className:"aleft"}
				       ];
	    //?tipo=customers&tid=0"
	    this.dataSource1 = new YAHOO.util.DataSource("ar_assets.php?tipo=products_in_location&tableid="+tableid);
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    rtext:"resultset.rtext",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "code"
			 ,"description"
			 ,'current_qty'
			 ,'changed_qty'
			 ,'new_qty'
			 ,'_qty_move'
			 ,'_qty_change'
			 ,'_qty_damaged'
			 ,'msg','note','delete'
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource1
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 
							 ,sortedBy : {
							     key: "<?=$_SESSION['state']['location']['products']['order']?>",
							     dir: "<?=$_SESSION['state']['location']['products']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table1.filter={key:'<?=$_SESSION['state']['product']['stock_history']['f_field']?>',value:'<?=$_SESSION['state']['product']['stock_history']['f_value']?>'};
	    YAHOO.util.Event.addListener('yui-pg1-0-page-report', "click",myRowsPerPageDropdown)

	};
    });


YAHOO.util.Event.onContentReady("manage_stock_locations", function () {
	var oDS = new YAHOO.util.XHRDataSource("ar_assets.php");
 	oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	oDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["name"]
 	};
 	var oAC = new YAHOO.widget.AutoComplete("new_location_input", "new_location_container", oDS);
 	oAC.generateRequest = function(sQuery) {
 	    return "?tipo=locations_name&all=0except_location&=<?=$_SESSION['state']['location']['id']?>&query=" + sQuery ;
 	};
	oAC.forceSelection = true; 
	oAC.itemSelectEvent.subscribe(location_selected); 
    });
YAHOO.util.Event.onContentReady("manage_stock_products", function () {
	var oDS = new YAHOO.util.XHRDataSource("ar_assets.php");
 	oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	oDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["scode","code","description","current_qty","changed_qty","new_qty","_qty_move","_qty_change","_qty_damaged","note","delete","product_id"]
 	};
 	var oAC = new YAHOO.widget.AutoComplete("new_product_input", "new_product_container", oDS);
 	oAC.generateRequest = function(sQuery) {
 	    return "?tipo=products_name&except=location&except_id=<?=$_SESSION['state']['location']['id']?>&query=" + sQuery ;
 	};

	var myHandler = function(sType, aArgs) {

	    newProductData = aArgs[2];

	};
	oAC.itemSelectEvent.subscribe(myHandler);




	oAC.forceSelection = true; 
	oAC.itemSelectEvent.subscribe(product_selected); 
    });

var product_selected=function(){

    var data = {
	"code":newProductData[1]
	,"description":newProductData[2]
	,"current_qty":newProductData[3]
	,"changed_qty":newProductData[4]
	,"new_qty":newProductData[5]
	,"_qty_move":newProductData[6]
	,"_qty_change":newProductData[7]
	,"_qty_damaged":newProductData[8]
	,"note":newProductData[9]
	,"delete":newProductData[10]
	,"product_id":newProductData[11]
    }; 
    
    // add the product


    var request='ar_assets.php?tipo=pml_new_location&is_primary=false&can_pick=true&location_id=<?=$_SESSION['state']['location']['id']?>&msg=&product_id='+ escape(data.product_id);

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);

		if (r.state == 200) {
		    tables.table1.addRow(data,0);
		    check_audit_form();
		    }else
		    Dom.get('product_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	}
	);
    


}
var qty_changed=function(pl_id,product_id){

    var stock=Dom.get('s'+pl_id).getAttribute('value');



    if(operation=='move_stock'){
	var stock_changed=Dom.get('qm'+pl_id).value;
	var stock_after_change=stock-stock_changed;
	Dom.get('ns'+pl_id).innerHTML=stock_after_change;
	if(stock_after_change<0){
	    Dom.get('ns'+pl_id).color='red';
	    Dom.get('qm'+pl_id).style.background="#fff889";
	    list[pl_id]={'qty':'','product_id':product_id}
	}else{
	    Dom.get('ns'+pl_id).color='black';
	    if(stock_changed=='')
		Dom.get('qm'+pl_id).style.background="#ffffff";
	    else{
		Dom.get('qm'+pl_id).style.background="#c7e59d";
	    }
	    list[pl_id]={'qty':stock_changed,'product_id':product_id}
	}
	check_move_form();
    }else if(operation=='change_stock'){

	var stock_changed=Dom.get('qc'+pl_id).value;
	var stock_after_change=stock_changed-stock;
	


	if(stock_changed==''){
	    Dom.get('qc'+pl_id).style.background="#fff889";

	}else{
	    if(stock_after_change>0)
		Dom.get('cs'+pl_id).innerHTML='+'+stock_after_change;
	    else
		Dom.get('cs'+pl_id).innerHTML=stock_after_change;
	    


	    Dom.get('qc'+pl_id).style.background="#c7e59d";
	    list[pl_id]={'qty':stock_changed,'product_id':product_id,'msg':Dom.get('n'+pl_id).value}
	    }
	check_audit_form();
    }else if(operation=='damaged_stock'){
	var stock_changed=Dom.get('qd'+pl_id).value;
	var stock_after_change=stock-stock_changed;
	Dom.get('ns'+pl_id).innerHTML=stock_after_change;
	if(stock_after_change<0){
	    
	    Dom.get('qd'+pl_id).style.background="#fff889";
	    list[pl_id]={'qty':'','product_id':product_id,'msg':Dom.get('n'+pl_id).value}
	}else{
	    if(stock_changed=='')
		Dom.get('qd'+pl_id).style.background="#ffffff";
	    else{
		Dom.get('qd'+pl_id).style.background="#c7e59d";
	    }
	    list[pl_id]={'qty':stock_changed,'product_id':product_id,'msg':Dom.get('n'+pl_id).value}
	}
	check_damaged_form();
    }




};

var change_reset=function(pl_id,product_id){
    Dom.get('cs'+pl_id).innerHTML=0;
    Dom.get('qc'+pl_id).value=Dom.get('s'+pl_id).getAttribute('value');
    qty_changed(pl_id,product_id);
};


var fill_value=function(value,pl_id,product_id){
    if(Dom.get('s'+pl_id).getAttribute('used')==0){
	Dom.get('qm'+pl_id).value=Dom.get('s'+pl_id).getAttribute('value');
	Dom.get('s'+pl_id).setAttribute('used',1);
    }else{
	Dom.get('qm'+pl_id).value='';
	Dom.get('s'+pl_id).setAttribute('used',0);
    }

    qty_changed(pl_id,product_id);
};

var damaged_stock=function(){

    if(operation=='damaged_stock'){
	clear_all();
	return;
    }
    
    clear_all();
    operation='damaged_stock'
    Dom.get('manage_stock').style.display='';
    this.className='selected';
    //    Dom.get('manage_stock_messages').innerHTML='<?=_('Indicate the number of Units damaged')?>';

     Dom.get('manage_stock_messages').innerHTML='<table style="margin:0"><tr><td><?=_('Mensage')?>:</td></tr><tr><td><input id="damaged_note" onchange="check_damaged_form()" type="text" style="background:#fff889" /> </td></tr><tr><td colspan=2 ><?=_('Indicate the number of units damaged')?></td></tr></table>';

    Dom.get('manage_stock_engine').style.visibility='hidden';
    Dom.get('manage_stock_engine').innerHTML='<span onclick="damaged_stock_save()" style="cursor:pointer"><?=_('Save changes')?> <img src="art/icons/disk.png"/></span>';
    var table=tables.table1;
    //table.hideColumn('current_qty');
    table.hideColumn('delete');
    table.showColumn('_qty_damaged');
    table.showColumn('new_qty');
    table.showColumn('note');
    

};
var damaged_stock_save= function(){
    
    var jsonStr = YAHOO.lang.JSON.stringify(list);


    var msg1=Dom.get("damaged_note").value;
    var request='ar_assets.php?tipo=pml_multiple_damaged&data='+jsonStr+'&msg1='+escape(msg1);

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);

		if (r.state == 200) {
		    var table=tables.table1;
		    var datasource=tables.dataSource1;
		    var request='';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);   
		    clear_all();
		    
		}else
		    Dom.get('product_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});

    
}
var change_stock_save= function(){
    
    var jsonStr = YAHOO.lang.JSON.stringify(list);
    var msg1=Dom.get("audit_name").value;
    var msg2=Dom.get("audit_note").value;
    var request='ar_assets.php?tipo=pml_audit_stocks&data='+jsonStr+'&msg1='+escape(msg1)+'&msg2='+escape(msg2);

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {

		var r =  YAHOO.lang.JSON.parse(o.responseText);
		
		if (r.state == 200) {
		    var table=tables.table1;
		    var datasource=tables.dataSource1;
		    var request='';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);   
		    clear_all();
		    
		}else
		    Dom.get('product_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});

    
}

var move_stock_save= function(){
    
    var jsonStr = YAHOO.lang.JSON.stringify(list);

    var request='ar_assets.php?tipo=pml_move_multiple_stocks&data='+jsonStr+'&toname='+escape(Dom.get('new_location_input').value);

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//		alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		
		if (r.state == 200) {
		    var table=tables.table1;
		    var datasource=tables.dataSource1;
		    var request='';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
		    clear_all();
		}else
		    Dom.get('product_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});

    
}



var move_stock=function(){
    if(operation=='move_stock'){
	clear_all();
	return;
    }
    


     clear_all();
    operation='move_stock';
     Dom.get('manage_stock').style.display='';
    Dom.get('manage_stock_messages').innerHTML='<?=_('Choose which location you want to move the stock')?>';
     this.className='selected';
    Dom.get('manage_stock_locations').style.display='';
      Dom.get('manage_stock_engine').style.visibility='hidden';
    Dom.get('manage_stock_engine').innerHTML='<span onclick="move_stock_save()" style="cursor:pointer"><?=_('Save changes')?> <img src="art/icons/disk.png"/></span>';

    };
var change_stock=function(){
    if(operation=='change_stock'){
	clear_all();
	return;
    }
    
    clear_all();
    operation='change_stock';
    var table=tables.table1;
    table.showColumn('changed_qty');
    table.showColumn('_qty_change');
    table.showColumn('note');
    Dom.get('manage_stock').style.display='';
    Dom.get('manage_stock_products').style.display='';
    Dom.get('manage_stock_messages').innerHTML='<table style="margin:0"><tr><td><?=_('Audit Name & Notes')?>:</td></tr><tr><td><input id="audit_name" onchange="check_audit_form()" type="text" style="background:#fff889" /> <input id="audit_note" onchange="check_audit_form()" type="text" style="background:#fff889" /></td></tr><tr><td>New product found</td></tr></table>';
    Dom.get('manage_stock_engine').style.visibility='hidden';
    Dom.get('manage_stock_engine').innerHTML='<span onclick="change_stock_save()" style="cursor:pointer"><?=_('Save changes')?> <img src="art/icons/disk.png"/></span>';
    this.className='selected';
      
};


var check_move_form=function(){

    var  items_audited=0;
    for (var i in list)
	{
	    if (list[i]['qty'] !=''){
		items_audited=items_audited+1;
	    }
	    
	}
    

    if(items_audited>0)
	var has_items=true;
    
    if(has_items  )
	Dom.get('manage_stock_engine').style.visibility='visible';
    else
	Dom.get('manage_stock_engine').style.visibility='hidden';
    

}



var check_damaged_form=function(){
    has_note=false;
    

    
    if(Dom.get("damaged_note").value!=''){
	var has_note=true;
	Dom.get("damaged_note").style.background='#ffffff';
    }else{
	Dom.get("damaged_note").style.background='#fff889';
    }    

    var  items_audited=0;
    for (var i in list)
	{
	    if (list[i]['qty'] !=''){
		items_audited=items_audited+1;
	    }
	    
	}
    

    if(items_audited>0)
	var has_items=true;
    
    if(has_items && has_note )
	Dom.get('manage_stock_engine').style.visibility='visible';
    else
	Dom.get('manage_stock_engine').style.visibility='hidden';
    

}



var check_audit_form=function(){
    has_note=false;
    has_name=false;
    has_items=false;

    if(Dom.get("audit_name").value!=''){
	Dom.get("audit_name").style.background='#ffffff';
	var has_name=true;
    }else
	Dom.get("audit_name").style.background='#fff889';
  

  if(Dom.get("audit_note").value!='' || has_name){
	var has_note=true;
	Dom.get("audit_note").style.background='#ffffff';
  }else{
      Dom.get("audit_note").style.background='#fff889';
  }    

    var  items_audited=0;
    for (var i in list)
	{
	    if (list[i]['qty'] !=''){
		items_audited=items_audited+1;
	    }
	    
	}
    
    var total_items=tables.table1.getRecordSet().getLength();
    if(items_audited==total_items)
	var has_items=true;
    
    if(has_items && ( has_name || has_note ))
	Dom.get('manage_stock_engine').style.visibility='visible';
    else
	Dom.get('manage_stock_engine').style.visibility='hidden';
    

}

var location_selected=function(){
    var table=tables['table1'];
    table.showColumn('new_qty');
    table.showColumn('_qty_move');
    
    
};


var clear_all=function(){
    operation='';
    Dom.get('change_stock').className='';
    Dom.get('move_stock').className='';
    Dom.get('damaged_stock').className='';
    Dom.get('manage_stock').style.display='none';
    Dom.get('manage_stock_locations').style.display='none';
    Dom.get('manage_stock_products').style.display='none';
    var table=tables.table1;
    table.hideColumn('changed_qty');
    table.hideColumn('_qty_change');
    table.hideColumn('_qty_damaged');
    table.hideColumn('_qty_move');
    table.hideColumn('changed_qty');
    table.hideColumn('new_qty');
    table.hideColumn('note');
    table.showColumn('delete');
    table.showColumn('current_qty');
    list = new Object;
}


function init(){

	
	 Event.addListener("damaged_stock", "click", damaged_stock);
	 Event.addListener("move_stock", "click", move_stock);
	 Event.addListener("change_stock", "click", change_stock);

	
 }

YAHOO.util.Event.onDOMReady(init);
 

