<?php include_once('common.php')


?>
 var Dom   = YAHOO.util.Dom;
 var Event = YAHOO.util.Event;
 var dialog_add_part;

var lost_label='<?php echo '<img src="art/icons/package_delete.png"  alt="'._('Lost').'" />' ?>';
var delete_label='<?php echo '<img src="art/icons/cross.png"  alt="'._('Free location').'" />' ?>';
var move_label='<?php echo '<img src="art/icons/package_go.png"  alt="'._('Move Stock').'" />' ?>';



var newProductData= new Object;
var list = new Object;
var operation='';

function salect_part_from_list(oArgs){
sku=tables.table2.getRecord(oArgs.target).getData('sku')

  var request='ar_edit_warehouse.php?tipo=add_part_to_location&is_primary=false&can_pick=true&location_key='+Dom.get('location_key').value+'&msg=&part_sku='+ sku;
   // alert(request)
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
			      // 	alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);

			Dom.get('manage_stock_messages').innerHTML='';
			Dom.get('manage_stock').style.display='none';

		 
			Dom.get('new_product_input').value='';


		if (r.state == 200) {
		
		
		
		ids=['details','parts','history'];
		block_ids=['block_details','block_parts','block_history'];
		Dom.setStyle(block_ids,'display','none');
		Dom.setStyle('block_'+'parts','display','');
		Dom.removeClass(ids,'selected');
		Dom.addClass(Dom.get('parts'),'selected');

		YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=location-view&value='+'parts' ,{});
		dialog_add_part.hide()
		
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



function highlightEditableCell(oArgs) {

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
 	case 'audit':
	if(record.getData('audit')!='')
	    this.highlightCell(target);
	break;
	case 'add':
	if(record.getData('add')!='')
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

 				Dom.get('location_move_other_locations').innerHTML='';
    			var request='ar_warehouse.php?tipo=other_locations_quick_buttons&sku=' + record.getData('part_sku')+'&location_key='+Dom.get('location_key').value ;

   				YAHOO.util.Connect.asyncRequest('POST',request , {
					success:function(o) {
	
				var r =  YAHOO.lang.JSON.parse(o.responseText);
 Dom.get('location_move_other_locations').innerHTML=r.other_locations_quick_buttons;

}
});
			

		    Dom.get('move_record_index').value= record.getId();
		    Dom.get('move_sku').value=record.getData('part_sku');
		    Dom.get('move_sku_formated').innerHTML=record.getData('sku');
		    Dom.get('this_location').innerHTML=record.getData('location');
	    	Dom.get('move_stock_left').innerHTML=record.getData('qty');
	    	Dom.get('move_stock_left').setAttribute('ovalue',record.getData('qty'));
	    	
	    	
	    	
	    
	    	
	    	
		  // Dom.get('move_this_location_key')=record.getData('location_key');


		    if(record.getData('qty')==0){
			Dom.get('flow').setAttribute('flow','left');
			Dom.get('flow').innerHTML='<img src="art/icons/arrow_left.png"/>';
		    }

		    var x =Dom.getX(this.getCell(target))-Dom.get('Editor_move_items').offsetWidth+this.getCell(target).offsetWidth;
		    var y =Dom.getY(this.getCell(target));
		   // Editor_move_items.cfg.setProperty("xy",[x,y]); 
		    //Editor_move_items.cfg.setProperty("visible",true); 
		    
		    
		    Dom.setX('Editor_move_items', x);
Dom.setY('Editor_move_items', y);


Editor_move_items.show();
		    
		    
		    
		    break;
		    
				case 'add':
				  Dom.get("add_stock_location_key").value=record.getData('location_key');
    Dom.get("add_stock_sku").value=record.getData('part_sku');;

    var x =Dom.getX(this.getCell(target))-Dom.get('Editor_add_stock').offsetWidth+this.getCell(target).offsetWidth;
		    var y =Dom.getY(this.getCell(target));
		   
		    Dom.setX('Editor_add_stock', x);
    Dom.setY('Editor_add_stock', y);
  add_stock_dialog.show();
        Dom.get('add_record_index').value= record.getId();
Dom.get('qty_add_stock').focus();
		
		
		
		    break;
		    
		    
		    			case 'audit':
		    			
		    		
		    			
				  Dom.get("audit_location_key").value=record.getData('location_key');
    Dom.get("audit_sku").value=record.getData('part_sku');;

    var x =Dom.getX(this.getCell(target))-Dom.get('Editor_audit').offsetWidth+this.getCell(target).offsetWidth;
		    var y =Dom.getY(this.getCell(target));
		   
		    Dom.setX('Editor_audit', x);
    Dom.setY('Editor_audit', y);
  audit_dialog.show();
        Dom.get('audit_record_index').value= record.getId();
Dom.get('qty_audit').focus();
		
		
		
		    break;
		    
		    
		case 'lost':
		
		
		
		
		    var qty=record.getData('qty');
		    Dom.get('lost_max_value').innerHTML=qty;
		    Dom.get('lost_sku').value=record.getData('part_sku');
			Dom.get('lost_location_key').value=record.getData('location_key');

		    Dom.get('lost_record_index').value= record.getId();

		    var x =Dom.getX(this.getCell(target))-Dom.get('Editor_lost_items').offsetWidth+this.getCell(target).offsetWidth;
		    var y =Dom.getY(this.getCell(target));
		   
		  Dom.setX('Editor_lost_items', x);
    Dom.setY('Editor_lost_items', y);
  
    Editor_lost_items.show();
		     Dom.get('qty_lost').focus();
		    break;

		default:

		    this.onEventShowCellEditor(oArgs);
		    break;
		}
	    };   



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {
	    //START OF THE TABLE=========================================================================================================================
	    
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var CustomersColumnDefs = [
				       {key:"date",label:"<?php echo _('Date')?>", width:170,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author",label:"<?php echo _('Author')?>", width:70,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"tipo", label:"<?php echo _('Type')?>", width:90,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //,{key:"diff_qty",label:"<?php echo _('Qty')?>", width:90,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"note", label:"<?php echo _('Description')?>", width:400,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	   
	    this.dataSource0 = new YAHOO.util.DataSource("ar_warehouse.php?tipo=location_stock_history&sf=0");
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
			 "id"
			 ,"note"
			 ,'author','date','tipo','diff_qty'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource0
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['location']['stock_history']['nr']?>,containers : 'paginator0', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['location']['stock_history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['location']['stock_history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table0.table_id=tableid;
     	this.table0.subscribe("renderEvent", myrenderEvent);
		    
		    
	    this.table0.filter={key:'<?php echo$_SESSION['state']['location']['stock_history']['f_field']?>',value:'<?php echo$_SESSION['state']['location']['stock_history']['f_value']?>'};
	    
	    
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
				       {key:"sku", label:"<?php echo _('SKU')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"location_key", label:"", hidden:true,isPrimaryKey:true} 
				       ,{key:"part_sku", label:"", hidden:true,isPrimaryKey:true} 
				       ,{key:"description", label:"<?php echo _('Description')?>", width:440,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //,{key:"can_pick", label:"<?php echo _('Can Pick')?>", width:80,className:"aright" ,editor: new YAHOO.widget.RadioCellEditor({radioOptions:["<?php echo _('Yes')?>","<?php echo _('No')?>"],disableBtns:true,asyncSubmitter: CellEdit}),object:'part_location'}
					,{key:"can_pick", label:"<?php echo _('Can Pick')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"formated_qty", label:"<?php echo _('Qty')?>", width:50,className:"aright",action:'audit'}
				      ,{key:"qty",label:"", width:30,hidden:true}

				      ,{key:"move",label:"<?php echo _('Move')?>", width:30,className:"aright",action:'move'}
				       ,{key:"lost", label:"<?php echo _('Lost')?>", width:30,className:"aright",action:'lost'}
				       				       ,{key:"add", label:"<?php echo _('Add')?>", width:30,className:"aright",action:'add'}

				       ,{key:"delete", label:"", width:30,className:"aright",object:'part_location',action:'delete'}
				     
				       ];
	    //?tipo=customers&tid=0"
	//alert("ar_warehouse.php?tipo=parts_at_location&sf=0&tableid="+tableid);
	    this.dataSource1 = new YAHOO.util.DataSource("ar_warehouse.php?tipo=parts_at_location&sf=0&tableid="+tableid);
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
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
			 "sku"
			 ,"description"
			 ,'qty'
			 ,'can_pick','move','audit','lost','delete','number_locations','number_qty','part_sku','location_key','part_stock','location','formated_qty','add'
		
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource1
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							   ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['location']['parts']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['location']['parts']['order']?>",
							     dir: "<?php echo$_SESSION['state']['location']['parts']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table1.table_id=tableid;
     	this.table1.subscribe("renderEvent", myrenderEvent);
    
	  

		


		    
	    this.table1.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table1.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table1.subscribe("cellClickEvent", onCellClick);



	    this.table1.filter={key:'<?php echo$_SESSION['state']['location']['parts']['f_field']?>',value:'<?php echo$_SESSION['state']['location']['parts']['f_value']?>'};






var tableid=2;
		      var tableDivEL="table"+tableid;
		      
		      var ColumnDefs = [
		      		{key:"formated_sku", label:"SKU",width:60, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"description", label:"<?php echo _('Description')?>",width:200, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			     	,{key:"used_in", label:"<?php echo _('Used In')?>",width:210, sortable:false,className:"aleft"}
			     	,{key:"status", label:"",width:70, hidden:true,sortable:false,className:"aleft"}
                   
					];
		    
		      
		      this.dataSource2 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=part_list&tableid=2&sf=0");
			      
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
		    totalRecords: "resultset.total_records" 
			  },
			  
			  fields: [
				  "sku","description","used_in","status","formated_sku"
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
								      key: "formated_sku",
								       dir: ""
								   }
								   ,dynamicData : true
								 
							       }
							       );
		      this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
		      this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
		      this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
                   
                   this.table2.subscribe("rowMouseoverEvent", this.table2.onEventHighlightRow);
       this.table2.subscribe("rowMouseoutEvent", this.table2.onEventUnhighlightRow);
      this.table2.subscribe("rowClickEvent", salect_part_from_list);
     

                   
	    this.table2.filter={key:'used_in',value:''};











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
	
 	    return "?tipo=find_location&except_location=<?php echo$_SESSION['state']['location']['id']?>&get_data=sku"+sku+"&query=" + sQuery ;
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

function save_audit() {


  var data=new Object();
    data['qty']=Dom.get('qty_audit').value;
    data['note']=Dom.get('note_audit').value;
    data['location_key']=Dom.get('audit_location_key').value
    data['part_sku']=Dom.get('audit_sku').value;
    
    sku=Dom.get('lost_sku').value;
    var json_value = YAHOO.lang.JSON.stringify(data);
    var request='ar_edit_warehouse.php?tipo=audit_stock&values=' + my_encodeURIComponent(json_value);

   YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
            //alert(o.responseText);
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
            	
            		
            
            
            	Dom.get('qty_audit').value='';
				Dom.get('note_audit').value='';
 				audit_dialog.hide();
 				
 				  datatable=tables['table1'];


		    record=datatable.getRecord(Dom.get("audit_record_index").value);
	
		datatable.updateCell(record,'qty',r.qty);
				datatable.updateCell(record,'formated_qty',r.formated_qty);

 		 if(r.qty==0){
			 datatable.updateCell(record,'delete',delete_label);
			 datatable.updateCell(record,'lost','');
			 
		     }else{
			 datatable.updateCell(record,'delete','');
			 datatable.updateCell(record,'lost',lost_label);

		     }							  
		     
		     if(r.stock==0){
			 datatable.updateCell(record,'move','');
			 
		     }else{
			 datatable.updateCell(record,'move',move_label);
			 
		     }	
		     

		    var table=tables['table0'];
		    var datasource=tables['dataSource0'];
		    datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
		    
            
            
            
            
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

}


function save_add_stock() {


  var data=new Object();
    data['qty']=Dom.get('qty_add_stock').value;
    data['note']=Dom.get('note_add_stock').value;
    data['location_key']=Dom.get('add_stock_location_key').value
    data['part_sku']=Dom.get('add_stock_sku').value;
    
    var json_value = YAHOO.lang.JSON.stringify(data);
    var request='ar_edit_warehouse.php?tipo=add_stock&values=' + my_encodeURIComponent(json_value);

   YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
          //  alert(o.responseText);
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                
				Dom.get('qty_add_stock').value='';
				Dom.get('note_add_stock').value='';
 				add_stock_dialog.hide();
 				
 				  datatable=tables['table1'];


		    record=datatable.getRecord(Dom.get("add_record_index").value);
	
		datatable.updateCell(record,'qty',r.qty);
				datatable.updateCell(record,'formated_qty',r.formated_qty);

 		 if(r.qty==0){
			 datatable.updateCell(record,'delete',delete_label);
			 datatable.updateCell(record,'lost','');
			 
		     }else{
			 datatable.updateCell(record,'delete','');
			 datatable.updateCell(record,'lost',lost_label);

		     }							  
		     
		     if(r.stock==0){
			 datatable.updateCell(record,'move','');
			 
		     }else{
			 datatable.updateCell(record,'move',move_label);
			 
		     }	
		     

		    var table=tables['table0'];
		    var datasource=tables['dataSource0'];
		    datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
		    
		

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

}


function save_lost_items() {
    var data=new Object();
    data['qty']=Dom.get('qty_lost').value;
    data['why']=Dom.get('lost_why').value;
    data['action']=Dom.get('lost_action').value;

    data['location_key']=Dom.get('lost_location_key').value
                         data['part_sku']=Dom.get('lost_sku').value;
    location_key=Dom.get('lost_location_key').value;
    sku=Dom.get('lost_sku').value;
    var json_value = YAHOO.lang.JSON.stringify(data);
    var request='ar_edit_warehouse.php?tipo=lost_stock&values=' + encodeURIComponent(json_value);


    YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
            //alert(o.responseText);
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.action=='ok') {


                close_lost_dialog();


                datatable=tables['table1'];


		    record=datatable.getRecord(Dom.get("lost_record_index").value);
		    datatable.updateCell(record,'qty',r.qty);
	datatable.updateCell(record,'formated_qty',r.formated_qty);


  if(r.qty==0){
			 datatable.updateCell(record,'delete',delete_label);
			 datatable.updateCell(record,'lost','');
			 
		     }else{
			 datatable.updateCell(record,'delete','');
			 datatable.updateCell(record,'lost',lost_label);

		     }							  
		     
		     if(r.stock==0){
			 datatable.updateCell(record,'move','');
			 
		     }else{
			 datatable.updateCell(record,'move',move_label);
			 
		     }	
		     

		    var table=tables['table0'];
		    var datasource=tables['dataSource0'];
		    datasource.sendRequest('',table.onDataReturnInitializeTable, table);      



            } else if (r.action=='error') {
                alert(r.msg);
            }



        }
    });

}




function save_move_items(){
    var data=new Object();
    data['qty']=Dom.get('move_qty').value;
    data['part_sku']=Dom.get('move_sku').value;

    if(Dom.get('flow').getAttribute('flow')=='right'){
	data['from_key']=Dom.get('move_this_location_key').value;
	data['to_key']=Dom.get('move_other_location_key').value;
    }else{
	data['from_key']=Dom.get('move_other_location_key').value;
	data['to_key']=Dom.get('move_this_location_key').value;
    }

    var json_value = YAHOO.lang.JSON.stringify(data);
    var request='ar_edit_warehouse.php?tipo=move_stock&values=' + encodeURIComponent(json_value); 
  
    
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='ok'){
		    Dom.get('flow').setAttribute('flow','right');
		    Dom.get('move_qty').value='';
		    Dom.get('flow').innerHTML='<img src="art/icons/arrow_right.png"/>';
			
		    
		    Editor_move_items.cfg.setProperty("visible",false);
		    datatable=tables['table1'];


		    record=datatable.getRecord(Dom.get("move_record_index").value);
		    datatable.updateCell(record,'qty',r.qty_from);
		    datatable.updateCell(record,'formated_qty',r.formated_qty_from);

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

function location_move_to_selected(sType, aArgs){
    
    var locData= aArgs[2];
    var data = {
	"location_code":locData[0]
	,"location_key":locData[1]
	,"stock":locData[2]
    }; 
    Dom.get('move_stock_right').innerHTML=data['stock'];
    Dom.get('move_stock_right').setAttribute('ovalue',data['stock']);
    Dom.get('move_other_location_key').value=data['location_key'];
    Dom.get('move_qty').value='';
    move_qty_changed();
};





function add_product(){
    Dom.get("manage_stock").style.display='';
    Dom.get("manage_stock_messages").innerHTML='<?php echo _('Choose the part the you want to place in this location')?>.';
    Dom.get("manage_stock_products").style.display='';
}


function change_block(){
ids=['details','parts','history'];
block_ids=['block_details','block_parts','block_history'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=location-view&value='+this.id ,{});
}

function show_add_part_dialog(){
dialog_add_part.show();

}

function init(){
  init_search('locations');

    Event.addListener(['details','parts','history'], "click",change_block);




Event.addListener('location_submit_search', "click",submit_search,'location');
 Event.addListener('location_search', "keydown", submit_search_on_enter,'location');
 
 Event.addListener("damaged_stock", "click", damaged_stock);
 Event.addListener("move_stock", "click", move_stock);
 Event.addListener("add_part", "click", show_add_part_dialog);
 Event.addListener("change_stock", "click", change_stock);




	dialog_add_part = new YAHOO.widget.Dialog("dialog_add_part", {context:["add_part","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_add_part.render();

	dialog_add_part = new YAHOO.widget.Dialog("dialog_part_list", {context:["add_part","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_add_part.render();

	
 }

YAHOO.util.Event.onDOMReady(init);




YAHOO.util.Event.onContentReady("rppmenu0", 
function() {
	var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {
		trigger: "rtext_rpp0"
		
	});
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);

	
});

YAHOO.util.Event.onContentReady("filtermenu0", 
function() {
	var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {
		trigger: "filter_name0"
		
	});
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);

	
});
YAHOO.util.Event.onContentReady("rppmenu1", 
function() {
	var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {
		trigger: "rtext_rpp1"
		
	});
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);

	
});

YAHOO.util.Event.onContentReady("filtermenu1", 
function() {
	var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {
		trigger: "filter_name1"
		
	});
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);

	
});
YAHOO.util.Event.onContentReady("rppmenu2", 
function() {
	var oMenu = new YAHOO.widget.ContextMenu("rppmenu2", {
		trigger: "rtext_rpp2"
		
	});
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);

	
});

YAHOO.util.Event.onContentReady("filtermenu2", 
function() {
	var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {
		trigger: "filter_name2"
		
	});
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);

	
});

 

