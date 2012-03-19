<?php
include_once('common.php');

$location_type=array('Picking'=>_('Picking'),'Storing'=>_('Storing'),'Loading'=>_('Loading'),'Displaying'=>_('Displaying'),'Other'=>_('Other'));



$l='';$ln='';
foreach($location_type as $key=>$value){
    $l.=",'$value'";
    $ln.=",'$key':'$value'";
}
$l=preg_replace('/^,/','',$l);
$ln=preg_replace('/^,/','',$ln);

print 'var location_type_options=['.$l."];\n";
print 'var location_type_name={'.$ln."};\n";

?>
var warehouse_code='<?php echo $_REQUEST['name']?>';
var editing='<?php echo $_SESSION['state']['warehouse']['edit']?>';

   var Dom   = YAHOO.util.Dom;
     
     
      var area_dialog;
 var shelf_dialog;


var thisonCellClick = function(oArgs) {
    var target = oArgs.target,
                 column = this.getColumn(target),
                          record = this.getRecord(target);

    var recordIndex = this.getRecordIndex(record);
    var data = record.getData();


    switch (column.action) {

  case 'change_area':
   var y=(Dom.getY(target))
   var x=(Dom.getX(target))

  
 // x=x-120;
   // y=y+18;
    Dom.setX('area_dialog', x)
    Dom.setY('area_dialog', y)
  area_dialog.show();
  
  Dom.get('location_key').value=data['id'];
    Dom.get('record_index').value=recordIndex;

   Dom.get('Area_Code').focus();    

    default:

        this.onEventShowCellEditor(oArgs);
        break;
    }
};

function close_area_dialog(){
  area_dialog.hide();

}


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


function location_type_formatter(el, oRecord, oColumn, oData){
el.innerHTML =location_type_name[oData];
}
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var LocationsColumnDefs = [
	    
	       {key:"id", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
	    				       ,{key:"go", label:"", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   
				   				   ,{key:"area", label:"<?php echo _('Area')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, action:'change_area',object:'location'}
				   				   ,{key:"shelf", label:"<?php echo _('Shelf')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'location'}
                                    ,{key:"area_key", label:"", width:60,sortable:false,hidden:true}
                                    ,{key:"shelf_key", label:"", width:60,sortable:false,hidden:true}

				   ,{key:"code", label:"<?php echo _('Code')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'location'}
				      ,{key:"tipo",formatter:location_type_formatter,label:"<?php echo _('Used for')?>",className:"aleft"
				, editor:new YAHOO.widget.RadioCellEditor({radioOptions:location_type_options,disableBtns:true,asyncSubmitter: CellEdit}),object:'location'
			      }
				    
				    
				    
				    ,{key:"max_weight", label:"<?php echo _('Max Weight')?>",width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'location'}
                    ,{key:"max_volumen", label:"<?php echo _('Max Vol')?>",width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'location'}

					 ];
	    //?tipo=locations&tid=0"
	    request="ar_edit_warehouse.php?tipo=locations&parent=warehouse&parent_key="+Dom.get('warehouse_key').value;
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
			 "id"
			 ,"code"
			 
			 ,'max_weight'
			 ,'max_volumen','tipo',"go","shelf_key","shelf","area","area_key"
			 ]};
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, LocationsColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['warehouse']['edit_locations']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['warehouse']['edit_locations']['order']?>",
									 dir: "<?php echo$_SESSION['state']['warehouse']['edit_locations']['order_dir']?>"
								     },
								     dynamicData : true
								  }
								 );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['warehouse']['edit_locations']['f_field']?>',value:'<?php echo$_SESSION['state']['warehouse']['edit_locations']['f_value']?>'};
	  
	  
	     this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table0.subscribe("cellClickEvent", thisonCellClick);

	  
	  
	  
	
	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var LocationsColumnDefs = [
	    				       {key:"go", label:"", width:20,sortable:false,className:"aleft"}

				       ,{key:"wa_key", label:"", hidden:true,action:"none",isPrimaryKey:true}
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
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		fields: [
			 "wa_key"
			 ,"code"
			 ,'description'
			 ,'name','go'
			 ]};
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, LocationsColumnDefs,
								   this.dataSource1
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['warehouse_areas']['table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['warehouse_areas']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['warehouse_areas']['table']['order_dir']?>"
								     },
								     dynamicData : true
								  }
								 );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.filter={key:'<?php echo$_SESSION['state']['warehouse_areas']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['warehouse_areas']['table']['f_value']?>'};
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
	//Dom.get('shelfs_block').style.display='none';
//	Dom.get('shelf_types_block').style.display='none';
//	Dom.get('location_types_block').style.display='none';	
	
	Dom.get(this.id+'_block').style.display='';

	Dom.removeClass(editing,'selected');
	
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=warehouse-edit&value='+this.id ,{});
	
	editing=this.id;
    }



}

var description_data =new Object;

function reset_description_data(){
    Dom.get('warehouse_code').value=Dom.get('warehouse_code').getAttribute('ovalue');
    Dom.get('warehouse_name').value=Dom.get('warehouse_name').getAttribute('ovalue');
    document.getElementById('new_warehouse_area_block').style.display='none';
    document.getElementById('new_warehouse_area_block').innerHTML='';
}

function get_description_data(){
    var key = document.getElementById('warehouse_key').value;
    var code = document.getElementById('warehouse_code').value;
    var name = document.getElementById('warehouse_name').value;
    var str = '&key='+key+'&code='+code+'&name='+name;
    return str;
}


function save_description_data(){
    str = get_description_data();
    //var json_value = YAHOO.lang.JSON.stringify(str);
    //alert(json_value);
    var request='ar_edit_warehouse.php?tipo=save_description'+str;
    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText);
		var op =  YAHOO.lang.JSON.parse(o.responseText);
		if(op.trim() == 'error'){
			document.getElementById('new_warehouse_area_block').style.display='block';
			document.getElementById('new_warehouse_area_block').innerHTML='Required Fields are blank';
			Dom.get('warehouse_code').value=Dom.get('warehouse_code').getAttribute('ovalue');
			Dom.get('warehouse_name').value=Dom.get('warehouse_name').getAttribute('ovalue');
			exit;
		}

		if (op.trim() != ''){
			document.getElementById('new_warehouse_area_block').style.display='block';
			document.getElementById('new_warehouse_area_block').innerHTML=op;
		}
	    }
	});
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
    Dom.get('area_description').value=Dom.get('area_description').getAttribute('ovalue');

}

function add_area(){
    get_area_data();
  
    var json_value = YAHOO.lang.JSON.stringify(area_data);
    var request='ar_edit_warehouse.php?tipo=new_area&values=' + encodeURIComponent(json_value); 
//alert(request);    
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText);
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

function change_area_save(){



area_key=Dom.get('area_key').value;
location_key=Dom.get('location_key').value;
record_index=Dom.get('record_index').value;

 var ar_file='ar_edit_warehouse.php';
	request='tipo=edit_location&id='+location_key+'&key=area_key&newvalue='+area_key+'&record_index='+record_index;
	
	YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					 //alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
					    if(r.action=='updated'){
					    

					    
					          datatable=tables['table0'];
					    		    record=datatable.getRecord(0);


		    record=datatable.getRecord(parseFloat(r.record_index));
									    close_area_dialog();

					    datatable.updateCell(record,'area',r.newvalue['code']);
					   
					   datatable.updateCell(record,'area_key',r.newvalue['key']);

					    
                     
                                   
                        
                        }
					  
					
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

function validate_warehouse_code(query){
warehouse_code=query
	//alert(query)
	validate_general('warehouse','warehouse_code',unescape(query));
}
function validate_warehouse_name(query){
	//alert(query)
	validate_general('warehouse','warehouse_name',unescape(query));
}

function save_edit_warehouse(){
 save_edit_general('warehouse');
}


 function init(){
     
      init_search('locations');
     
    area_dialog = new YAHOO.widget.Dialog("area_dialog", {visible : false,close:true,underlay: "none",draggable:false});
    area_dialog.render();

     
     var ids = ["description","areas","locations","shelfs","shelf_types","location_types"]; 
     
     
     
     
     YAHOO.util.Event.addListener(ids, "click", change_block);
     var ids = ["add_area","add_area_here"]; 
     YAHOO.util.Event.addListener(ids, "click", show_add_area_dialog);
     //var ids = ["add_shelf"]; 
     //YAHOO.util.Event.addListener(ids, "click", show_add_shelf_dialog);
     // var ids = ["close_add_shelf"]; 
     //YAHOO.util.Event.addListener(ids, "click", hide_add_shelf_dialog);


     //YAHOO.util.Event.addListener('save_shelf', "click", add_shelf);


     YAHOO.util.Event.addListener('save_area', "click", add_area);
     YAHOO.util.Event.addListener('close_add_area', "click",hide_add_area_dialog );

     YAHOO.util.Event.addListener('add_shelf_type', "click", show_add_shelf_type_dialog);
     YAHOO.util.Event.addListener('save_shelf_type', "click", add_shelf_type);
     YAHOO.util.Event.addListener('close_add_shelf_type', "click",hide_add_shelf_type_dialog );

     var ids=Dom.getElementsByClassName('radio', 'span', 'shelf_type_type_container');
     YAHOO.util.Event.addListener(ids, "click", swap_radio,'shelf_type_type');


     var ids=['shelf_type_general_view','shelf_type_dimensions_view'];
     YAHOO.util.Event.addListener(ids, "click",change_shelf_type_view);


 



   
	



 var oACDS0 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS0.queryMatchContains = true;
 var oAutoComp0 = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS0);
 oAutoComp0.minQueryLength = 0; 




//YAHOO.util.Event.addListener('submit_advanced_search', "click",submit_advanced_search);


 validate_scope_metadata={
     'warehouse':{'type':'new','ar_file':'ar_edit_warehouse.php','key_name':'id','key':<?php echo $_REQUEST['id']?>}
  //ar_edit_warehouse.php?tipo=save_description'+str;
};
var warehouse_code_validated=false;
if(Dom.get('warehouse_code').value!=''){
warehouse_code_validated=true;
}


 validate_scope_data={
 
 	'warehouse':{
	'warehouse_name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','dbname':'Warehouse Name'
		,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Warehouse Name')?>'}],'name':'warehouse_name'
		}
	
	,'warehouse_code':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','dbname':'Warehouse Code'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Warehouse Code')?>'}]
		 ,'name':'warehouse_code','ar':'find','ar_request':'ar_warehouse.php?tipo=is_warehouse_code&warehouse_code='+warehouse_code+'&query='}		 
	}
 };

var warehouse_code_oACDS = new YAHOO.util.FunctionDataSource(validate_warehouse_code);
    warehouse_code_oACDS.queryMatchContains = true;
    var warehouse_code_oAutoComp = new YAHOO.widget.AutoComplete("warehouse_code","warehouse_code_Container", warehouse_code_oACDS);
    warehouse_code_oAutoComp.minQueryLength = 0; 
    warehouse_code_oAutoComp.queryDelay = 0.1;


var warehouse_name_oACDS = new YAHOO.util.FunctionDataSource(validate_warehouse_name);
    warehouse_name_oACDS.queryMatchContains = true;
    var warehouse_name_oAutoComp = new YAHOO.widget.AutoComplete("warehouse_name","warehouse_name_Container", warehouse_name_oACDS);
    warehouse_name_oAutoComp.minQueryLength = 0; 
    warehouse_name_oAutoComp.queryDelay = 0.1;





 }

YAHOO.util.Event.onDOMReady(init);

function warehouse_area_to_selected(sType,aArgs){

 var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 

	   Dom.get("area_key").value = oData[1];



}


YAHOO.util.Event.onContentReady("Area_Code", function () {
	var oDS = new YAHOO.util.XHRDataSource("ar_warehouse.php");
 	oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	oDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["code","key","name"]
 	};
 	var oAC = new YAHOO.widget.AutoComplete("Area_Code", "Area_Code_Container", oDS);
 	oAC.generateRequest = function(sQuery) {
	    
	    var warehouse_key=Dom.get("warehouse_key").value
	    request="?tipo=find_warehouse_area&parent_key="+warehouse_key+"&query=" + sQuery;
	   // alert(request )
 	    return  request;
 	};
 	
 	 oAC.formatResult = function(oResultData, sQuery, sResultMatch) {
        return '<b>'+oResultData[0]+'</b> '+oResultData[2]
        
      
      
    };

 	
	oAC.forceSelection = true; 
	oAC.itemSelectEvent.subscribe(warehouse_area_to_selected); 
    });


YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
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
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
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

 

