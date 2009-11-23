<?php
include_once('common.php');

?>
var editing='<?php echo $_SESSION['state']['warehouse']['edit']?>';


 var change_shelf_type_view=function(e){

     var tipo=this.getAttribute('tipo');
      var table=tables['table3'];
      if(tipo=='general'){
	  table.hideColumn('length');
	  table.hideColumn('height');
	  table.hideColumn('deep');
	  table.hideColumn('max_weight');
	  table.hideColumn('max_vol');
	  table.showColumn('type');
	  table.showColumn('description');
	  table.showColumn('rows');
	  table.showColumn('columns');
	  table.showColumn('delete');
	  

	  Dom.get('shelf_type_dimensions_view').className='';
	  Dom.get('shelf_type_general_view').className='selected';
      }else{
	 
	  table.showColumn('length');
	  table.showColumn('height');
	  table.showColumn('deep');
	  table.showColumn('max_weight');
	  table.showColumn('max_vol');
	  table.hideColumn('type');
	  table.hideColumn('description');
	  table.hideColumn('rows');
	  table.hideColumn('columns');
	  table.hideColumn('delete');
	  

	  Dom.get('shelf_type_dimensions_view').className='selected';
	  Dom.get('shelf_type_general_view').className='';
      }
      YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=shelf_types-view&value='+escape(tipo));
  }


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var LocationsColumnDefs = [
				       {key:"code", label:"<?php echo _('Code')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"area", label:"<?php echo _('Area')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"shelf", label:"<?php echo _('Shelf')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"shelf_type", label:"<?php echo _('Location Type')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"tipo", label:"<?php echo _('Used for')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      
				      
					 ];
	    //?tipo=locations&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_warehouse.php?tipo=locations");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    rtext:"resultset.rtext",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		fields: [
			 "id"
			 ,"code"
			 ,'location'
			 ,'parts'
			 ,'max_weight'
			 ,'max_volumen','tipo',"area"
			 ]};
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, LocationsColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['warehouse']['locations']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['warehouse']['locations']['order']?>",
									 dir: "<?php echo$_SESSION['state']['warehouse']['locations']['order_dir']?>"
								     },
								     dynamicData : true
								  }
								 );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['warehouse']['locations']['f_field']?>',value:'<?php echo$_SESSION['state']['warehouse']['locations']['f_value']?>'};
	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown);
	
	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var LocationsColumnDefs = [
				       {key:"wa_key", label:"", hidden:true,action:"none",isPrimaryKey:true}
				       ,{key:"code", label:"<?php echo _('Code')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'warehouse_area'}
				       ,{key:"name", label:"<?php echo _('Name')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'warehouse_area'}
				       ,{key:"description", label:"<?php echo _('Description')?>",width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'warehouse_area'}
				    
				       ];
	    //?tipo=locations&tid=0"
	    this.dataSource1 = new YAHOO.util.DataSource("ar_edit_warehouse.php?tipo=warehouse_areas&tableid=1");
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    rtext:"resultset.rtext",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		fields: [
			 "wa_key"
			 ,"code"
			 ,'description'
			 ,'name'
			 ]};
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, LocationsColumnDefs,
								   this.dataSource1
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['warehouse']['warehouse_area']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['warehouse']['warehouse_area']['order']?>",
									 dir: "<?php echo$_SESSION['state']['warehouse']['warehouse_area']['order_dir']?>"
								     },
								     dynamicData : true
								  }
								 );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.filter={key:'<?php echo$_SESSION['state']['warehouse']['warehouse_area']['f_field']?>',value:'<?php echo$_SESSION['state']['warehouse']['warehouse_area']['f_value']?>'};
	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)	

 
	    this.table1.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table1.subscribe("cellMouseoutEvent", this.table1.onEventUnhighlightCell);
	    this.table1.subscribe("cellClickEvent", onCellClick);



	     var tableid=3; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var LocationsColumnDefs = [
				       {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}
				       
				       ,{key:"name", label:"<?php echo _('Name')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type'}
				        ,{key:"type", label:"<?php echo _('Type')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type',<?php echo($_SESSION['state']['shelf_types']['view']!='general'?'hidden:true,':'')?>}
				       ,{key:"description", label:"<?php echo _('Description')?>",width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type',<?php echo($_SESSION['state']['shelf_types']['view']!='general'?'hidden:true,':'')?>}
				       ,{key:"columns", label:"<?php echo _('Columns')?>",width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type',<?php echo($_SESSION['state']['shelf_types']['view']!='general'?'hidden:true,':'')?>}
				       ,{key:"rows", label:"<?php echo _('Rows')?>",width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type',<?php echo($_SESSION['state']['shelf_types']['view']!='general'?'hidden:true,':'')?>}
				       ,{key:"length", label:"<?php echo _('Length')?> (m)",width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type',<?php echo($_SESSION['state']['shelf_types']['view']=='general'?'hidden:true,':'')?>}
				       ,{key:"height", label:"<?php echo _('Height')?> (m)",width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type',<?php echo($_SESSION['state']['shelf_types']['view']=='general'?'hidden:true,':'')?>}
				       ,{key:"deep", label:"<?php echo _('Deep')?> (m)",width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type',<?php echo($_SESSION['state']['shelf_types']['view']=='general'?'hidden:true,':'')?>}
				         ,{key:"max_weight", label:"<?php echo _('Max Weight')?> (Kg)",width:120,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type',<?php echo($_SESSION['state']['shelf_types']['view']=='general'?'hidden:true,':'')?>}
				       ,{key:"max_vol", label:"<?php echo _('Max Volume')?> (L)",width:120,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type',<?php echo($_SESSION['state']['shelf_types']['view']=='general'?'hidden:true,':'')?>}
				       ];
	    //?tipo=locations&tid=0"
	    this.dataSource3 = new YAHOO.util.DataSource("ar_edit_warehouse.php?tipo=shelf_types&tableid=3");
	    this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource3.connXhrMode = "queueRequests";
	    this.dataSource3.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    rtext:"resultset.rtext",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		fields: [
			 "id"
			 ,'description'
			 ,'name','rows','columns','deep','length','height','max_vol','max_weight','type','delete'
			 ]};
	    this.table3 = new YAHOO.widget.DataTable(tableDivEL, LocationsColumnDefs,
								   this.dataSource3
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['shelf_types']['table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['shelf_types']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['shelf_types']['table']['order_dir']?>"
								     },
								     dynamicData : true
								  }
								 );
	    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table3.filter={key:'<?php echo$_SESSION['state']['shelf_types']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['shelf_types']['table']['f_value']?>'};
	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)	

 
	    this.table3.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table3.subscribe("cellMouseoutEvent", this.table3.onEventUnhighlightCell);
	    this.table3.subscribe("cellClickEvent", onCellClick);


	};
    });


function change_block(e){
     if(editing!=this.id){
	
	

	Dom.get('description_block').style.display='none';
	Dom.get('areas_block').style.display='none';
	Dom.get('locations_block').style.display='none';
	
	Dom.get(this.id+'_block').style.display='';
	Dom.removeClass(editing,'selected');
	
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=warehouse-edit&value='+this.id );
	
	editing=this.id;
    }



}

function show_add_area_dialog(){
Dom.get('new_warehouse_area_block').style.display='';
Dom.get('new_warehouse_area_messages').style.display='';

Dom.get('add_area_here').style.display='none';
Dom.get('close_add_area').style.display='';
Dom.get('save_area').style.display='';


}
function hide_add_area_dialog(){
reset_area_data();
Dom.get('new_warehouse_area_block').style.display='none';
Dom.get('new_warehouse_area_messages').style.display='none';

Dom.get('add_area_here').style.display='';
Dom.get('close_add_area').style.display='none';
Dom.get('save_area').style.display='none';
}

var area_data =new Object;


function get_area_data(){
    area_data['Warehouse Key']=Dom.get('warehouse_key').value;
    area_data['Warehouse Area Name']=Dom.get('area_name').value;
    area_data['Warehouse Area Code']=Dom.get('area_code').value;
    area_data['Warehouse Area Description']=Dom.get('area_description').value;

}

function reset_area_data(){
    Dom.get('warehouse_key').value=Dom.get('warehouse_key').getAttribute('ovalue');
    Dom.get('area_name').value=Dom.get('area_name').getAttribute('ovalue');
    Dom.get('area_code').value=Dom.get('area_code').getAttribute('ovalue');
    Dom.get('area_description').innerHTML=Dom.get('area_description').getAttribute('ovalue');

}

function add_area(){
    get_area_data();
  
    var json_value = YAHOO.lang.JSON.stringify(area_data);
    var request='ar_edit_warehouse.php?tipo=new_area&values=' + encodeURIComponent(json_value); 
//alert(request);    
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
	 alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='created'){
		    reset_area_data();
		    var table=tables['table1']
		    var datasource=tables['dataSource1'];
		    
		    datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
		}else if(r.action=='error'){
		    alert(r.msg);
		}
			    

			
	    }
	});
}




function show_add_shelf_type_dialog(){
Dom.get('new_warehouse_shelf_type_block').style.display='';
Dom.get('new_warehouse_shelf_type_messages').style.display='';

Dom.get('add_shelf_type').style.display='none';
Dom.get('close_add_shelf_type').style.display='';
Dom.get('save_shelf_type').style.display='';


}
function hide_add_shelf_type_dialog(){
reset_shelf_type_data();
Dom.get('new_warehouse_shelf_type_block').style.display='none';
Dom.get('new_warehouse_shelf_type_messages').style.display='none';

Dom.get('add_shelf_type').style.display='';
Dom.get('close_add_shelf_type').style.display='none';
Dom.get('save_shelf_type').style.display='none';
}

var shelf_type_data =new Object;


function get_shelf_type_data(){
   
    shelf_type_data['Shelf Type Name']=Dom.get('shelf_type_name').value;
    shelf_type_data['Shelf Type Description']=Dom.get('shelf_type_description').value;
    shelf_type_data['Shelf Type Type']=Dom.get('shelf_type_type').value;
    shelf_type_data['Shelf Rows']=Dom.get('shelf_type_rows').value;
    shelf_type_data['Shelf Columns']=Dom.get('shelf_type_columns').value;
    shelf_type_data['Shelf Location Length']=Dom.get('shelf_type_length').value;
    shelf_type_data['Shelf Location Deep']=Dom.get('shelf_type_deep').value;
    shelf_type_data['Shelf Location Height']=Dom.get('shelf_type_height').value;
    shelf_type_data['Shelf Location Max Weight']=Dom.get('shelf_type_weight').value;
    shelf_type_data['Shelf Location Max Volume']=Dom.get('shelf_type_volume').value;

}

function reset_shelf_type_data(){
    Dom.get('warehouse_key').value=Dom.get('warehouse_key').getAttribute('ovalue');
    Dom.get('shelf_type_name').value=Dom.get('shelf_type_name').getAttribute('ovalue');
    Dom.get('shelf_type_description').innerHTML=Dom.get('shelf_type_description').getAttribute('ovalue');
    Dom.get('shelf_type_rows').value=Dom.get('shelf_type_rows').getAttribute('ovalue');
    Dom.get('shelf_type_columns').value=Dom.get('shelf_type_columns').getAttribute('ovalue');
    Dom.get('shelf_type_deep').value=Dom.get('shelf_type_deep').getAttribute('ovalue');
    Dom.get('shelf_type_height').value=Dom.get('shelf_type_height').getAttribute('ovalue');
    Dom.get('shelf_type_length').value=Dom.get('shelf_type_length').getAttribute('ovalue');
    Dom.get('shelf_type_weight').value=Dom.get('shelf_type_weight').getAttribute('ovalue');
    Dom.get('shelf_type_volume').value=Dom.get('shelf_type_volume').getAttribute('ovalue');
    Dom.get('shelf_type_type').value=Dom.get('shelf_type_type').getAttribute('ovalue');
    swap_this_radio(Dom.get('radio_shelf_type_'+Dom.get('shelf_type_type').getAttribute('ovalue')))

}

function add_shelf_type(){
    get_shelf_type_data();
  
    var json_value = YAHOO.lang.JSON.stringify(shelf_type_data);
    var request='ar_edit_warehouse.php?tipo=new_shelf_type&values=' + encodeURIComponent(json_value); 
    //alert(request);    
    // return;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='created'){
		    reset_shelf_type_data();
		    var table=tables['table3']
		    var datasource=tables['dataSource3'];
		    
		    datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
		}else{
		    Dom.get('new_warehouse_shelf_type_block').innerHTML=r.msg;
		}
			    

			
	    }
	});
}





 function init(){
     var Dom   = YAHOO.util.Dom;
     var ids = ["description","areas","locations","shelfs","shelf_types","location_types"]; 
     YAHOO.util.Event.addListener(ids, "click", change_block);
     var ids = ["add_area","add_area_here"]; 
     YAHOO.util.Event.addListener(ids, "click", show_add_area_dialog);

     YAHOO.util.Event.addListener('save_area', "click", add_area);
     YAHOO.util.Event.addListener('close_add_area', "click",hide_add_area_dialog );

     YAHOO.util.Event.addListener('add_shelf_type', "click", show_add_shelf_type_dialog);
     YAHOO.util.Event.addListener('save_shelf_type', "click", add_shelf_type);
     YAHOO.util.Event.addListener('close_add_shelf_type', "click",hide_add_shelf_type_dialog );

     var ids=Dom.getElementsByClassName('radio', 'span', 'shelf_type_type_container');
     YAHOO.util.Event.addListener(ids, "click", swap_radio);

     var ids=['shelf_type_general_view','shelf_type_dimensions_view'];
     YAHOO.util.Event.addListener(ids, "click",change_shelf_type_view);


 var oACDS0 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS0.queryMatchContains = true;
 var oAutoComp0 = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS0);
 oAutoComp0.minQueryLength = 0; 

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.Menu("filtermenu0", { context:["filter_name0","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name0", "click", oMenu.show, null, oMenu);
    });


YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.Menu("rppmenu0", { context:["filter_name0","tr", "bl"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("paginator_info0", "click", oMenu.show, null, oMenu);
    });

 var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS1.queryMatchContains = true;
 var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS1);
 oAutoComp1.minQueryLength = 0; 

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.Menu("filtermenu1", { context:["filter_name1","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name1", "click", oMenu.show, null, oMenu);
    });


YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.Menu("rppmenu1", { context:["filter_name1","tr", "bl"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("paginator_info1", "click", oMenu.show, null, oMenu);
    });

 



//YAHOO.util.Event.addListener('submit_advanced_search', "click",submit_advanced_search);
 }

YAHOO.util.Event.onDOMReady(init);


