<?php
include_once('common.php');



?>

var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;


var can_add_department=false;
var description_num_changed=0;
var description_warnings= new Object();
var description_errors= new Object();


var scope='ind_positions';
var scope_edit_ar_file='ar_edit_contacts.php';
var scope_key_name='id';
var scope_key=0;

	
var parent='company';
var parent_key_name='id';
var parent_key=<?php echo $_REQUEST['company_key']?>;






var validate_scope_data={
'ind_positions':{
  
  'area':{'required':true,'validated':false,'name':'Company_Area_Key' ,'dbname':'Company Area Key','validation':[{'regexp':"^\\d+$",'invalid_msg':'<?php echo _('Choose a Company Area')?>'}]}
   , 'department':{'required':true,'validated':false,'name':'Company_Department_Key' ,'dbname':'Company Department Key','validation':[{'regexp':"^\\d+$",'invalid_msg':'<?php echo _('Choose a Company Area')?>'}]}

  
  ,'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item'
	    ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Position Name')?>'}]
	    ,'name':'Position_Name','dbname':'Company Position Name'
	    ,'ar':'find','ar_request':'ar_contacts.php?tipo=is_company_position_name&company_key='+parent_key+'&query='}
    ,'code':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
	     ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Position Code')?>'}]
	     ,'name':'Position_Code' ,'dbname':'Company Position Code'
	     ,'ar':'find','ar_request':'ar_contacts.php?tipo=is_company_position_code&company_key='+parent_key+'&query='}
    

   }
};

var validate_scope_metadata={'ind_positions':{'type':'new','ar_file':'ar_edit_staff.php'}};


function validate_code(query){
 validate_general('ind_positions','code',unescape(query));
}
function validate_name(query){
 validate_general('ind_positions','name',unescape(query));
}

function validate_area(sType,aArgs){

 var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 

	   Dom.get("Company_Area_Key").value = oData[0];
Dom.get("Company_Department_Key").value = '';
  Dom.get("Company_Department").value =  '';

	    myAC.getInputEl().value = oData[2]+' ('+oData[1]+')';
 validate_general('position','area',oData[0]);

if(validate_scope_data['position']['area']['validated']){
    Dom.get('Company_Department_Code').focus();

}


}
function validate_department(sType,aArgs){

 var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 

	   Dom.get("Company_Department_Key").value = oData[0];
	   Dom.get("Company_Area_Key").value = oData[3];
	   Dom.get("Company_Area").value =  oData[5]+' ('+oData[4]+')';

	    myAC.getInputEl().value = oData[2]+' ('+oData[1]+')';
 validate_general('position','department',oData[0]);

if(validate_scope_data['position']['department']['validated']){
    Dom.get('Position_Code').focus();

}


}
function reset_new_position(){
 reset_edit_general('ind_positions');
}
function save_new_position(){

 save_new_general('ind_positions');
}


function post_item_updated_actions(branch,r){

key=r.key;
newvalue=r.newvalue;

 if(key=='name')
     Dom.get('title_name').innerHTML=newvalue;
 
 else if(key=='code')
     Dom.get('title_code').innerHTML=newvalue;

 
 var table=tables.table1;
 var datasource=tables.dataSource1;
 var request='';
 datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
 
}



function post_create_actions(branch){
var table=tables.table1;
 var datasource=tables.dataSource1;
 var request='';
 datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
 
 var table=tables.table0;
 var datasource=tables.dataSource0;
 var request='';
 datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
}




YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"company_position_key", label:"<?php echo _('Key')?>", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				    ,{key:"go",label:'',width:20,}

				   ,{key:"code", label:"<?php echo _('Code')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'ind_positions'}
				    ,{key:"name", label:"<?php echo _('Name')?>", width:340,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'ind_positions' }
				    ,{key:"delete", label:"", width:170,sortable:false,className:"aleft",action:'delete',object:'ind_positions'}
				    ,{key:"delete_type", label:"",hidden:true,isTypeKey:true}
			     //   ,{key:"area", label:"<?php echo _('Area')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_staff.php?tipo=positions&parent=corporation");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",rtext:"resultset.rtext",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 'company_position_key','code','name','delete','delete_type','go','area'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['hr']['positions']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
							   ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['hr']['positions']['order']?>",
							     dir: "<?php echo$_SESSION['state']['hr']['positions']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;


	  
	    this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table0.subscribe("cellClickEvent", onCellClick);


		
 var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"date",label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author",label:"<?php echo _('Author')?>", width:70,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //     ,{key:"tipo", label:"<?php echo _('Type')?>", width:90,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //,{key:"diff_qty",label:"<?php echo _('Qty')?>", width:90,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"abstract", label:"<?php echo _('Description')?>", width:370,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	    //?tipo=customers&tid=0"
	    
	    this.dataSource1 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=position&tableid=1");
	//alert("ar_history.php?tipo=history&type=position&tableid=1")
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
			 "id"
			 ,"note"
			 ,'author','date','tipo','abstract','details'
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource1
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['company']['history']['nr']?>,containers : 'paginator1', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['company']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['company']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['product']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['product']['history']['f_value']?>'};


	};
    });






function cancel_add_position(){
   reset_new_position();
    hide_add_position_dialog(); 
}


function hide_add_position_dialog(){
    Dom.get('new_position_dialog').style.display='none';
    Dom.get('add_position').style.display='';
    Dom.get('save_edit_position').style.visibility='hidden';

    Dom.get('reset_edit_position').style.visibility='hidden';

}

function show_add_position_dialog(){


    Dom.get('new_position_dialog').style.display='';
    Dom.get('add_position').style.display='none';
    Dom.get('save_edit_position').style.visibility='visible';

    Dom.addClass('save_edit_position','disabled');
    Dom.get('reset_edit_position').style.visibility='visible';
    Dom.get('Company_Area').focus();


}


function init(){
   // var ids = ["description","pictures","web","positions","discounts","charges","shipping","campaigns"]; 
   // YAHOO.util.Event.addListener(ids, "click", change_block);
 YAHOO.util.Event.addListener('add_position', "click", show_add_position_dialog);

    YAHOO.util.Event.addListener('save_edit_position', "click",save_new_position);
    YAHOO.util.Event.addListener('reset_edit_position', "click", cancel_add_position);




    var store_code_oACDS = new YAHOO.util.FunctionDataSource(validate_code);
    store_code_oACDS.queryMatchContains = true;
    var store_code_oAutoComp = new YAHOO.widget.AutoComplete("Company_Position_Code","Company_Position_Code_Container", store_code_oACDS);
    store_code_oAutoComp.minQueryLength = 0; 
    store_code_oAutoComp.queryDelay = 0.1;
    
    var store_name_oACDS = new YAHOO.util.FunctionDataSource(validate_name);
    store_name_oACDS.queryMatchContains = true;
    var store_name_oAutoComp = new YAHOO.widget.AutoComplete("Company_Position_Name","Company_Position_Name_Container", store_name_oACDS);
    store_name_oAutoComp.minQueryLength = 0; 
    store_name_oAutoComp.queryDelay = 0.1;
    
    
    var area_oDS = new YAHOO.util.XHRDataSource("ar_contacts.php");
 	area_oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	area_oDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["key","code","name"]
 	};
 	var area_oAC = new YAHOO.widget.AutoComplete("Company_Area", "Company_Area_Container", area_oDS);
 	area_oAC.generateRequest = function(sQuery) {
	    
 	    return "?tipo=find_company_area&parent_key="+parent_key+"&query=" + sQuery ;
 	};
	area_oAC.forceSelection = true; 
	area_oAC.itemSelectEvent.subscribe(validate_area); 
    area_oAC.formatResult = function(oResultData, sQuery, sResultMatch) {
	  return oResultData[1]
	};

    var department_oDS = new YAHOO.util.XHRDataSource("ar_contacts.php");
 	department_oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	department_oDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["key","code","name","area_key",'area_code','area_name']
 	};
 	var department_oAC = new YAHOO.widget.AutoComplete("Company_Department", "Company_Department_Container", department_oDS);
 	department_oAC.generateRequest = function(sQuery) {
 	    var area_key=Dom.get('Company_Area_Key').value;
 	    return "?tipo=find_company_department&grandparent_key=0&parent_key="+area_key+"&query=" + sQuery ;
 	};
	department_oAC.forceSelection = true; 
	department_oAC.itemSelectEvent.subscribe(validate_department); 
    department_oAC.formatResult = function(oResultData, sQuery, sResultMatch) {
	  return oResultData[1]
	};
   
    

}

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });


YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
    });
