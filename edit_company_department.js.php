<?php
include_once('common.php');

?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;


var can_add_department=false;
var description_num_changed=0;
var description_warnings= new Object();
var description_errors= new Object();


var scope='company_department';
var scope_edit_ar_file='ar_edit_contacts.php';
var scope_key_name='code';
var scope_key='<?php echo $_SESSION['state']['edit_each_department']['code']?>';

	
var parent='staff';
var parent_key_name='id';
var parent_key=<?php echo $_REQUEST['department_key']?>;

var editing='<?php echo $_SESSION['state']['edit_each_department']['edit']?>';  



var validate_scope_data={
'company_department':{

    'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item'
	    ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Department Name')?>'}],'name':'Company_Department_Name'
	    ,'ar':'find','ar_request':'ar_contacts.php?tipo=is_department_name&department_key='+parent_key+'&query='}
    ,'code':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
	     ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Department Code')?>'}]
	     ,'name':'Company_Department_Code' ,'ar':'find','ar_request':'ar_contacts.php?tipo=is_department_code&department_key='+parent_key+'&query='}
     ,'description':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
	     ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Department Description')?>'}]
	     ,'name':'Company_Department_Description' ,'dbname':'Company Department Description'}
    

   }
};


var validate_scope_metadata={'company_department':{'type':'edit','ar_file':'ar_edit_contacts.php','key_name':'department_key','key':<?php echo $_REQUEST['department_key']?>}};



function validate_code(query){
 validate_general('company_department','code',unescape(query));
}
function validate_name(query){
 validate_general('company_department','name',unescape(query));
}
function validate_alias(query){
 validate_general('company_department','description',unescape(query));
}
function reset_new_department(){
 reset_edit_general('company_department');
}
function save_new_department(){
 save_edit_general('company_department');
}





function post_item_updated_actions(branch,key,newvalue){

 /*if(key=='name')
     Dom.get('title_name').innerHTML=newvalue;
 
 else if(key=='code')
     Dom.get('title_code').innerHTML=newvalue;

 
 var table=tables.table1;
 var datasource=tables.dataSource1;
 var request='';
 datasource.sendRequest(request,table.onDataReturnInitializeTable, table); */
var table_id=1


    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];

  
    var request='&tableid='+table_id+'&sf=0';
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
/*
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"id", label:"<?php echo _('Key')?>", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				    ,{key:"go",label:'',width:20,}
				    ,{key:"code", label:"<?php echo _('Code')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'company_department'}
				    ,{key:"name", label:"<?php echo _('Name')?>", width:340,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'company_department' }
				    ,{key:"delete", label:"", width:170,sortable:false,className:"aleft",action:'delete',object:'department'}
				    ,{key:"delete_type", label:"",hidden:true,isTypeKey:true}
				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_contacts.php?tipo=edit_company_departments&parent=department");
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
			 'id','code','name','delete','delete_type','go'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['company_department']['table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
							   ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['company_department']['table']['order']?>",
							     dir: "<?php echo$_SESSION['state']['company_department']['table']['order_dir']?>"
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

*/
		
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
	    
	    this.dataSource1 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=edit_each_department&tableid=1");
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
								 rowsPerPage    : <?php echo$_SESSION['state']['edit_each_department']['history']['nr']?>,containers : 'paginator1', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['edit_each_department']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['edit_each_department']['history']['order_dir']?>"
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






function cancel_add_department(){
   reset_new_department();
    hide_add_department_dialog(); 
}


function hide_add_department_dialog(){
    Dom.get('new_company_department_dialog').style.display='none';
    Dom.get('add_company_department').style.display='';
    Dom.get('save_edit_company_department').style.visibility='hidden';
    Dom.get('reset_edit_company_department').style.visibility='hidden';

}

function show_add_department_dialog(){
    Dom.get('new_company_department_dialog').style.display='';
    Dom.get('add_company_department').style.display='none';
    Dom.get('save_edit_company_department').style.visibility='visible';

    Dom.addClass('save_edit_company_department','disabled');
    Dom.get('reset_edit_company_department').style.visibility='visible';
    Dom.get('Company_Department_Code').focus();


}





function change_block(){
   if(editing!=this.id){

	Dom.get('d_details').style.display='none';
	//Dom.get('d_departments').style.display='none';

	Dom.get('d_'+this.id).style.display='';
	Dom.removeClass(editing,'selected');
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=company_department-edit&value='+this.id );
	
	editing=this.id;
    }


}

function init(){

    var ids = ["details"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);

    YAHOO.util.Event.addListener('add_company_department', "click", show_add_department_dialog);
    YAHOO.util.Event.addListener('save_edit_company_department', "click", save_new_department);   
    YAHOO.util.Event.addListener('reset_edit_company_department', "click", cancel_add_department);
   
    var department_code_oACDS = new YAHOO.util.FunctionDataSource(validate_code);
    department_code_oACDS.queryMatchContains = true;
    var department_code_oAutoComp = new YAHOO.widget.AutoComplete("Company_Department_Code","Company_Department_Code_Container", department_code_oACDS);
    department_code_oAutoComp.minQueryLength = 0; 
    department_code_oAutoComp.queryDelay = 0.1;
    
     var department_name_oACDS = new YAHOO.util.FunctionDataSource(validate_name);
    department_name_oACDS.queryMatchContains = true;
    var department_name_oAutoComp = new YAHOO.widget.AutoComplete("Company_Department_Name","Company_Department_Name_Container", department_name_oACDS);
    department_name_oAutoComp.minQueryLength = 0; 
    department_name_oAutoComp.queryDelay = 0.1;

    var department_description_oACDS = new YAHOO.util.FunctionDataSource(validate_description);
    department_description_oACDS.queryMatchContains = true;
    var department_description_oAutoComp = new YAHOO.widget.AutoComplete("Company_Department_Description","Company_Department_Description_Container", department_description_oACDS);
    department_description_oAutoComp.minQueryLength = 0; 
    department_description_oAutoComp.queryDelay = 0.1;


   

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
