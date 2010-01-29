<?php include_once('common.php')


?>
    var Dom   = YAHOO.util.Dom;


var lost_label='<?php echo '<img src="art/icons/package_delete.png"  alt="'._('Lost').'" />' ?>';
var delete_label='<?php echo '<img src="art/icons/cross.png"  alt="'._('Free location').'" />' ?>';
var move_label='<?php echo '<img src="art/icons/package_go.png"  alt="'._('Move Stock').'" />' ?>';


var Event = YAHOO.util.Event;

var newProductData= new Object;
var list = new Object;
var operation='';

var highlightEditableCell = function(oArgs) {

    var target = oArgs.target;
    column = this.getColumn(target);
    record = this.getRecord(target);
   

    switch (column.action) {
    case 'delete':
	//for(x in target)
	//  alert(x+' '+target[x])
	
	//alert(record.getData('delete'))
	if(record.getData('delete')!='')
	    this.highlightRow(target);
	break;
    case 'move':
	if(record.getData('move')!='')
	    this.highlightCell(target);
	break;

    case 'lost':
	if(record.getData('lost')!='')
	    this.highlightCell(target);
	break;
    default:
	
	if(YAHOO.util.Dom.hasClass(target, "yui-dt-editable") ) {
	    this.highlightCell(target);
	}
    }
};

 var CellEdit = function (callback, newValue) {
     
     var record = this.getRecord(),
     column = this.getColumn(),
     oldValue = this.value,
     datatable = this.getDataTable();
     
     
     ar_file='ar_edit_warehouse.php';
     
     var request='tipo=edit_'+column.object+'&key=' + column.key + '&newvalue=' + encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ myBuildUrl(datatable,record);
     //alert(request);

    YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					    //alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
						
						if(column.key=='qty'){
						    if(r.newvalue==0){
							datatable.updateCell(record,'delete',delete_label);
							datatable.updateCell(record,'lost','');

						    }else{
							datatable.updateCell(record,'delete','');
							datatable.updateCell(record,'lost',lost_label);

						    }							  
						    // alert(r.stock)
						    if(r.stock==0){
							datatable.updateCell(record,'move','');

						    }else{
							datatable.updateCell(record,'move',move_label);

						    }	


						}
						var table=tables.table0;
						var datasource=tables.dataSource0;
						var request='';
						datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    
						callback(true, r.newvalue);





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


var onCellClick = function(oArgs) {
		var target = oArgs.target,
		column = this.getColumn(target),
		record = this.getRecord(target);

		switch (column.action) {
		case 'delete':
		    if(record.getData('delete')!=''){
			if (confirm('Are you sure, you want to delete this row?')) {
			    ar_file='ar_edit_warehouse.php';
			    YAHOO.util.Connect.asyncRequest(
							    'GET',
							    ar_file+'?tipo=delete_'+column.object + myBuildUrl(this,record),
							    {
								success: function (o) {
								    
								if (o.responseText == 'Ok') {
								    this.deleteRow(target);
								} else {
								    alert(o.responseText);
								}
								},
								    failure: function (o) {
								alert(o.statusText);
								},
								    scope:this
								    }
							    );
			}
		}
		break;
		case 'move':
		    Dom.get('move_record_index').value= record.getId();
		    Dom.get('move_sku').value=record.getData('part_sku');
		    Dom.get('move_sku_formated').innerHTML=record.getData('sku');
		    
		    
		    Dom.get('move_stock_left').innerHTML=record.getData('qty');
		    Dom.get('move_stock_left').setAttribute('ovalue',record.getData('qty'));


		    if(record.getData('qty')==0){
			Dom.get('flow').setAttribute('flow','left');
			Dom.get('flow').innerHTML='<img src="art/icons/arrow_left.png"/>';
		    }


		    var x =Dom.getX(this.getCell(target))-Dom.get('Editor_move_items').offsetWidth+this.getCell(target).offsetWidth;
		    var y =Dom.getY(this.getCell(target));
		    Editor_move_items.cfg.setProperty("xy",[x,y]); 
		    Editor_move_items.cfg.setProperty("visible",true); 
		    break;
		case 'lost':
		    var qty=record.getData('qty');
		    Dom.get('lost_max_value').innerHTML=qty;
		    Dom.get('lost_sku').value=record.getData('part_sku');

		    Dom.get('lost_record_index').value= record.getId();

		    var x =Dom.getX(this.getCell(target))-Dom.get('Editor_lost_items').offsetWidth+this.getCell(target).offsetWidth;
		    var y =Dom.getY(this.getCell(target));
		    Editor_lost_items.cfg.setProperty("xy",[x,y]); 
		    Editor_lost_items.cfg.setProperty("visible",true); 

		    //	alert(Editor_lost_items)
		    break;

		default:

		    this.onEventShowCellEditor(oArgs);
		    break;
		}
	    };   

function move_stock_right(){
    if (isNaN(parseFloat(Dom.get("move_stock_right").getAttribute('ovalue')))) {
	return;
    }
    var qty_left=Dom.get("move_stock_left").innerHTML;
    if(qty_left>0){
	 var _qty_change=Dom.get('move_qty').value;
	 if(_qty_change=='')_qty_change=0;
	 var qty_change=parseFloat(_qty_change+' '+qty_change);
	 

	qty_change=qty_change+1;
	Dom.get('move_qty').value=qty_change;
	move_qty_changed();
    }
}

function move_stock_left(){
    
    if (isNaN(parseFloat(Dom.get("move_stock_left").getAttribute('ovalue')))) {
	return;
    }

    var qty_right=Dom.get("move_stock_right").innerHTML;
    if(qty_right>0){
	var _qty_change=Dom.get('move_qty').value;
	if(_qty_change=='')_qty_change=0;
	var qty_change=parseFloat(_qty_change+' '+qty_change);
	qty_change=qty_change+1;
	Dom.get('move_qty').value=qty_change;
	move_qty_changed();
    }
}

function move_qty_changed(){
    var _qty_change=Dom.get('move_qty').value;
    if(_qty_change=='')_qty_change=0;
    var qty_change=parseFloat(_qty_change+' '+qty_change);
    
    if(isNaN(qty_change))
	return;

    if(qty_change<0){
	Dom.addClass('move_qty','error');

	return;
    }else
	Dom.removeClass('move_qty','error');
    
    left_old_value=parseFloat(Dom.get("move_stock_left").getAttribute('ovalue'));
    right_old_value=parseFloat(Dom.get("move_stock_right").getAttribute('ovalue'));

    if(Dom.get('flow').getAttribute('flow')=='right'){
	if(left_old_value < qty_change){
	    Dom.addClass('move_qty','error');
	    qty_change=left_old_value;
	}else
	    Dom.removeClass('move_qty','error');
	left_value=left_old_value-qty_change;
	right_value=right_old_value+qty_change;
    }else{
	if(right_old_value < qty_change){
	    Dom.addClass('move_qty','error');
	    qty_change=right_old_value;
	}else
	    Dom.removeClass('move_qty','error');
	left_value=left_old_value+qty_change;
	right_value=right_old_value-qty_change;


    }
    
    Dom.get("move_stock_left").innerHTML=left_value;
    Dom.get("move_stock_right").innerHTML=right_value;
    
    
    
}



function close_move_dialog(){
    Dom.get('move_stock_right').innerHTML='';
    Dom.get('move_qty').value='';
    Dom.get('location_move_to_input').value='';
    

Editor_move_items.cfg.setProperty('visible',false);
}

var remove_prod=function (pl_id,part_sku){
    var request='ar_assets.php?tipo=pml_desassociate_location&id='+ escape(pl_id)+'&msg=&part_sku='+ escape(part_sku);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		alert(o.responseText)
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
				       {key:"date",label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author",label:"<?php echo _('Author')?>", width:70,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"tipo", label:"<?php echo _('Type')?>", width:90,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //,{key:"diff_qty",label:"<?php echo _('Qty')?>", width:90,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"note", label:"<?php echo _('Description')?>", width:370,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	   
	    this.dataSource0 = new YAHOO.util.DataSource("ar_warehouse.php?tipo=location_stock_history");
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
			 ,'author','date','tipo','diff_qty'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource0
						     , {
							 // sortedBy: {key:"<?php echo$_SESSION['tables']['customers_list'][0]?>", dir:"<?php echo$_SESSION['tables']['customers_list'][1]?>"},
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['product']['stock_history']['nr']?>,containers : 'paginator0', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							     key: "<?php echo$_SESSION['state']['product']['stock_history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['product']['stock_history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table0.filter={key:'<?php echo$_SESSION['state']['product']['stock_history']['f_field']?>',value:'<?php echo$_SESSION['state']['product']['stock_history']['f_value']?>'};
	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown);
	    
	    
	    this.move_formatter = function(elLiner, oRecord, oColumn, oData) {
		var stock=oRecord.getData("part_stock")
                if(stock>0)
		   elLiner.innerHTML =oData;
		else
		    elLiner.innerHTML = '';

	    };

	    this.lost_formatter = function(elLiner, oRecord, oColumn, oData) {
		var qty=oRecord.getData("number_qty")
                if(qty==0)
		    elLiner.innerHTML = '';
		else
		    elLiner.innerHTML =oData;
	    };

	    this.delete_formatter = function(elLiner, oRecord, oColumn, oData) {



		var qty=oRecord.getData("number_qty")
                if(qty==0){
		    elLiner.innerHTML = oData;
		    oColumn.actionx='delete';
		}else{
		    elLiner.innerHTML =''   ;
		    oColumn.actionx='';
		}
		//alert(oColumn.action);
	    };
	    // Add the custom formatter to the shortcuts
	    YAHOO.widget.DataTable.Formatter.move = this.move_formatter;
	    YAHOO.widget.DataTable.Formatter.lost = this.lost_formatter;
	    YAHOO.widget.DataTable.Formatter.delete = this.delete_formatter;




		var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var CustomersColumnDefs = [
				       {key:"sku", label:"<?php echo _('SKU')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"location_key", label:"", hidden:true,isPrimaryKey:true} 
				       ,{key:"part_sku", label:"", hidden:true,isPrimaryKey:true} 
				       ,{key:"description", label:"<?php echo _('Description')?>", width:350,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"can_pick", label:"<?php echo _('Can Pick')?>", width:80,className:"aright" ,editor: new YAHOO.widget.RadioCellEditor({radioOptions:["<?php echo _('Yes')?>","<?php echo _('No')?>"],disableBtns:true,asyncSubmitter: CellEdit}),object:'part_location'}
				       ,{key:"qty", label:"<?php echo _('Qty')?>", width:50,className:"aright", editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'part_location'}
				       //,{key:"audit", label:"<?php echo _('Audit')?>", width:30,className:"aright", editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'part_location',action:'part_location_audit'}
				       ,{key:"move",label:"<?php echo _('Move')?>", width:30,className:"aright",action:'move'}
				       ,{key:"lost", label:"<?php echo _('Lost')?>", width:30,className:"aright",action:'lost'}
				       ,{key:"delete", label:"", width:30,className:"aright",object:'part_location',action:'delete'}
				       // ,{key:"changed_qty", label:"<?php echo _('Change')?>", width:50,className:"aright",hidden:true}
				       //,{key:"new_qty", label:"<?php echo _('New Qty')?>", width:70,className:"aright",hidden:true}
				       // ,{key:"_qty_move", label:"<?php echo _('Moved')?>", width:70,hidden:true,className:"aright"}
				       //,{key:"_qty_damaged", label:"<?php echo _('Damaged')?>", width:70,hidden:true,className:"aright"}
				       //,{key:"_qty_change", label:"<?php echo _('Audit')?>", width:50,hidden:true,className:"aright inputs_yellow"}
				       //,{key:"note", label:"<?php echo _('Note')?>", width:110,className:"aleft",hidden:true}
				       //,{key:"delete", label:"", width:18,className:"aleft"}
				       ];
	    //?tipo=customers&tid=0"
	    this.dataSource1 = new YAHOO.util.DataSource("ar_warehouse.php?tipo=parts_at_location&tableid="+tableid);
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
			 "sku"
			 ,"description"
			 ,'qty'
			 ,'can_pick','move','audit','lost','delete','number_locations','number_qty','part_sku','location_key','part_stock'
		
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource1
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 
							 ,sortedBy : {
							     key: "<?php echo$_SESSION['state']['location']['parts']['order']?>",
							     dir: "<?php echo$_SESSION['state']['location']['parts']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
	    this.table1.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table1.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table1.subscribe("cellClickEvent", onCellClick);



	    this.table1.filter={key:'<?php echo$_SESSION['state']['location']['parts']['f_field']?>',value:'<?php echo$_SESSION['state']['location']['parts']['f_value']?>'};
	    YAHOO.util.Event.addListener('yui-pg1-0-page-report', "click",myRowsPerPageDropdown)

	};
    });


YAHOO.util.Event.onContentReady("location_move_to", function () {
	var oDS = new YAHOO.util.XHRDataSource("ar_warehouse.php");
 	oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	oDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["code","key","stock"]
 	};
 	var oAC = new YAHOO.widget.AutoComplete("location_move_to_input", "location_move_to_container", oDS);
 	oAC.generateRequest = function(sQuery) {
	    
	    var sku=Dom.get("move_sku").value
	    //alert("?tipo=find_location&except_location=<?php echo$_SESSION['state']['location']['id']?>&get_data=sku"+sku+"&query=" + sQuery);
 	    return "?tipo=find_location&except_location=<?php echo$_SESSION['state']['location']['id']?>&get_data=sku"+sku+"&query=" + sQuery ;
 	};
	oAC.forceSelection = true; 
	oAC.itemSelectEvent.subscribe(location_move_to_selected); 
    });

YAHOO.util.Event.onContentReady("location_move_from", function () {
	var oDS = new YAHOO.util.XHRDataSource("ar_warehouse.php");
 	oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	oDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["code","key","stock"]
 	};
 	var oAC = new YAHOO.widget.AutoComplete("location_move_from_input", "location_move_from_container", oDS);
 	oAC.generateRequest = function(sQuery) {
	    
	    var sku=Dom.get("move_sku").value
	    // alert("?tipo=find_location&except_location=<?php echo$_SESSION['state']['location']['id']?>&get_data=sku"+sku+"&query=" + sQuery)
 	    return "?tipo=find_location&except_location=<?php echo$_SESSION['state']['location']['id']?>&get_data=sku"+sku+"&with=stock&query=" + sQuery ;
 	};
	oAC.forceSelection = true; 
	oAC.itemSelectEvent.subscribe(location_move_to_selected); 
    });


YAHOO.util.Event.onContentReady("manage_stock_products", function () {


	var oDS = new YAHOO.util.XHRDataSource("ar_assets.php");
 	oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	oDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["info","sku","description","usedin"]
 	};
 	var oAC = new YAHOO.widget.AutoComplete("new_product_input", "new_product_container", oDS);
 	oAC.generateRequest = function(sQuery) {
	    //alert("ar_assets.php"+"?tipo=part_search&except=location&except_id=<?php echo$_SESSION['state']['location']['id']?>&query=" + sQuery);
 	    return "?tipo=find_part&except=location&except_id=<?php echo$_SESSION['state']['location']['id']?>&query=" + sQuery ;
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
	"info":newProductData[0]
	,"sku":newProductData[1]
	,"usedin":newProductData[2]
    }; 
    
    // add the product

    // alert(data.sku);
    //return;
    var request='ar_edit_warehouse.php?tipo=add_part_to_location&is_primary=false&can_pick=true&location_key=<?php echo$_SESSION['state']['location']['id']?>&msg=&part_sku='+ escape(data.sku);
    //alert(request)
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//	       	alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);

			Dom.get('manage_stock_messages').innerHTML='';
			Dom.get('manage_stock').style.display='none';

		 
			Dom.get('new_product_input').value='';


		if (r.state == 200) {
		    var table=tables.table1;
		    var datasource=tables.dataSource1;
		    var request='';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);      
		    var table=tables.table0;
		    var datasource=tables.dataSource0;
		    var request='';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    
		    
 
		}else
		    Dom.get('product_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	}
	);
    


}
var qty_changed=function(pl_id,part_sku){

    var stock=Dom.get('s'+pl_id).getAttribute('value');



    if(operation=='move_stock'){
	var stock_changed=Dom.get('qm'+pl_id).value;
	var stock_after_change=stock-stock_changed;
	Dom.get('ns'+pl_id).innerHTML=stock_after_change;
	if(stock_after_change<0){
	    Dom.get('ns'+pl_id).color='red';
	    Dom.get('qm'+pl_id).style.background="#fff889";
	    list[pl_id]={'qty':'','part_sku':part_sku}
	}else{
	    Dom.get('ns'+pl_id).color='black';
	    if(stock_changed=='')
		Dom.get('qm'+pl_id).style.background="#ffffff";
	    else{
		Dom.get('qm'+pl_id).style.background="#c7e59d";
	    }
	    list[pl_id]={'qty':stock_changed,'part_sku':part_sku}
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
	    list[pl_id]={'qty':stock_changed,'part_sku':part_sku,'msg':Dom.get('n'+pl_id).value}
	}
	check_audit_form();
    }else if(operation=='damaged_stock'){
	var stock_changed=Dom.get('qd'+pl_id).value;
	var stock_after_change=stock-stock_changed;
	Dom.get('ns'+pl_id).innerHTML=stock_after_change;
	if(stock_after_change<0){
	    
	    Dom.get('qd'+pl_id).style.background="#fff889";
	    list[pl_id]={'qty':'','part_sku':part_sku,'msg':Dom.get('n'+pl_id).value}
	}else{
	    if(stock_changed=='')
		Dom.get('qd'+pl_id).style.background="#ffffff";
	    else{
		Dom.get('qd'+pl_id).style.background="#c7e59d";
	    }
	    list[pl_id]={'qty':stock_changed,'part_sku':part_sku,'msg':Dom.get('n'+pl_id).value}
	}
	check_damaged_form();
    }




};

var change_reset=function(pl_id,part_sku){
    
    Dom.get('cs'+pl_id).innerHTML=0
    Dom.get('qc'+pl_id).value=Dom.get('s'+pl_id).getAttribute('value');

    qty_changed(pl_id,part_sku);
};


var fill_value=function(value,pl_id,part_sku){
    if(Dom.get('s'+pl_id).getAttribute('used')==0){
	Dom.get('qm'+pl_id).value=Dom.get('s'+pl_id).getAttribute('value');
	Dom.get('s'+pl_id).setAttribute('used',1);
    }else{
	Dom.get('qm'+pl_id).value='';
	Dom.get('s'+pl_id).setAttribute('used',0);
    }

    qty_changed(pl_id,part_sku);
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
    //    Dom.get('manage_stock_messages').innerHTML='<?php echo _('Indicate the number of Units damaged')?>';

     Dom.get('manage_stock_messages').innerHTML='<table style="margin:0"><tr><td><?php echo _('Mensage')?>:</td></tr><tr><td><input id="damaged_note" onchange="check_damaged_form()" type="text" style="background:#fff889" /> </td></tr><tr><td colspan=2 ><?php echo _('Indicate the number of units damaged')?></td></tr></table>';

    Dom.get('manage_stock_engine').style.visibility='hidden';
    Dom.get('manage_stock_engine').innerHTML='<span onclick="damaged_stock_save()" style="cursor:pointer"><?php echo _('Save changes')?> <img src="art/icons/disk.png"/></span>';
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
		//		alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		
		if (r.state == 200) {
		    var table=tables.table1;
		    var datasource=tables.dataSource1;
		    var request='';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);  
		    var table=tables.table0;
		    var datasource=tables.dataSource0;
		    var request='';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);  
		    
 
		    clear_all();
		    
		}else
		    Dom.get('product_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});

    
}



function set_all_lost(){
    Dom.get('qty_lost').value=Dom.get('lost_max_value').innerHTML;
    Dom.get('lost_why').focus();
}


function save_move_items(){
    var data=new Object();
    data['qty']=Dom.get('move_qty').value;
    data['part_sku']=Dom.get('move_sku').value;

    if(Dom.get('flow').getAttribute('flow')=='right'){
	data['from_key']=Dom.get('this_location_key').value;
	data['to_key']=Dom.get('other_location_key').value;
    }else{
	data['from_key']=Dom.get('other_location_key').value;
	data['to_key']=Dom.get('this_location_key').value;
    }

    var json_value = YAHOO.lang.JSON.stringify(data);
    var request='ar_edit_warehouse.php?tipo=move_stock&values=' + encodeURIComponent(json_value); 
  
    
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='ok'){
		    Dom.get('flow').setAttribute('flow','right');
		    Dom.get('move_qty').value='';
		    Dom.get('flow').innerHTML='<img src="art/icons/arrow_right.png"/>';
			
		    
		    Editor_move_items.cfg.setProperty("visible",false);
		    datatable=tables['table1'];


		    record=datatable.getRecord(Dom.get("move_record_index").value);
		    datatable.updateCell(record,'qty',r.qty_from);
		    

		    if(r.qty_from==0){
			 datatable.updateCell(record,'delete',delete_label);
			 datatable.updateCell(record,'lost','');
			 
		     }else{
			 datatable.updateCell(record,'delete','');
			 datatable.updateCell(record,'lost',lost_label);

		     }							  
		     // alert(r.stock)
		     if(r.stock==0){
			 datatable.updateCell(record,'move','');
			 
		     }else{
			 datatable.updateCell(record,'move',move_label);
			 
		     }	
		     

		    var table=tables['table0'];
		    var datasource=tables['dataSource0'];
		    datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
		}else if(r.action=='error'){
		    alert(r.msg);
		}
			    

			
	    }
	});    

}
function save_lost_items(){
    var data=new Object();
    data['qty']=Dom.get('qty_lost').value;
    data['why']=Dom.get('lost_why').value;
    data['action']=Dom.get('lost_action').value;
    data['location_key']='<?php echo$_SESSION['state']['location']['id']?>';
    data['part_sku']=Dom.get('lost_sku').value;

    var json_value = YAHOO.lang.JSON.stringify(data);
    var request='ar_edit_warehouse.php?tipo=lost_stock&values=' + encodeURIComponent(json_value); 

    
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='ok'){
		    Dom.get('qty_lost').value='';
		    Dom.get('lost_why').value='';
		    Dom.get('lost_action').value='';

		    Editor_lost_items.cfg.setProperty("visible",false);
		    datatable=tables['table1'];


		    record=datatable.getRecord(Dom.get("lost_record_index").value);
		    datatable.updateCell(record,'qty',r.qty);
		    

		    if(r.qty==0){
			 datatable.updateCell(record,'delete',delete_label);
			 datatable.updateCell(record,'lost','');
			 
		     }else{
			 datatable.updateCell(record,'delete','');
			 datatable.updateCell(record,'lost',lost_label);

		     }							  
		     // alert(r.stock)
		     if(r.stock==0){
			 datatable.updateCell(record,'move','');
			 
		     }else{
			 datatable.updateCell(record,'move',move_label);
			 
		     }	
		    

		    var table=tables['table0'];
		    var datasource=tables['dataSource0'];
		    datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
		}else if(r.action=='error'){
		    alert(r.msg);
		}
			    

			
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
    Dom.get('manage_stock_messages').innerHTML='<?php echo _('Choose which location you want to move the stock')?>';
     this.className='selected';
    Dom.get('manage_stock_locations').style.display='';
      Dom.get('manage_stock_engine').style.visibility='hidden';
    Dom.get('manage_stock_engine').innerHTML='<span onclick="move_stock_save()" style="cursor:pointer"><?php echo _('Save changes')?> <img src="art/icons/disk.png"/></span>';

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
    Dom.get('manage_stock_messages').innerHTML='<table style="margin:0"><tr><td><?php echo _('Audit Name & Notes')?>:</td></tr><tr><td><input id="audit_name" onchange="check_audit_form()" type="text" style="background:#fff889" /> <input id="audit_note" onchange="check_audit_form()" type="text" style="background:#fff889" /></td></tr><tr><td>New part found</td></tr></table>';
    Dom.get('manage_stock_engine').style.visibility='hidden';
    Dom.get('manage_stock_engine').innerHTML='<span onclick="change_stock_save()" style="cursor:pointer"><?php echo _('Save changes')?> <img src="art/icons/disk.png"/></span>';
    this.className='selected';
      
};



var location_move_to_selected=function(sType, aArgs){
    
    var locData= aArgs[2];
    var data = {
	"location_code":locData[0]
	,"location_key":locData[1]
	,"stock":locData[2]
    }; 
    Dom.get('move_stock_right').innerHTML=data['stock'];
    Dom.get('move_stock_right').setAttribute('ovalue',data['stock']);
    Dom.get('other_location_key').value=data['location_key'];
    Dom.get('move_qty').value='';
    move_qty_changed();
};





function add_product(){
    Dom.get("manage_stock").style.display='';
    Dom.get("manage_stock_messages").innerHTML='<?php echo _('Choose the part the you want to place in this location')?>.';
    Dom.get("manage_stock_products").style.display='';
}

function init(){
 var Dom   = YAHOO.util.Dom;
 var Event = YAHOO.util.Event;
 
 Editor_lost_items = new YAHOO.widget.Panel("Editor_lost_items",{close:false,visible:false}); 
 Editor_lost_items.render();	
 Editor_move_items = new YAHOO.widget.Panel("Editor_move_items",{close:false,visible:false}); 
 Editor_move_items.render();	

YAHOO.util.Event.addListener('details', "click",change_details,'location');


Event.addListener('location_submit_search', "click",submit_search,'location');
 Event.addListener('location_search', "keydown", submit_search_on_enter,'location');
 
 Event.addListener("damaged_stock", "click", damaged_stock);
 Event.addListener("move_stock", "click", move_stock);
 Event.addListener("add_product", "click", add_product);
 Event.addListener("change_stock", "click", change_stock);

	
 }

YAHOO.util.Event.onDOMReady(init);
 

