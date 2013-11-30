<?php
include_once('common.php');

?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;


var can_add_department=false;
var description_num_changed=0;
var description_warnings= new Object();
var description_errors= new Object();


var scope='company_staff';
var scope_edit_ar_file='ar_edit_staff.php';
var scope_key_name='id';
var scope_key='<?php echo $_SESSION['state']['edit_each_staff']['id']?>';

	
var parent='staff';
var parent_key_name='id';
var parent_key=<?php echo $_REQUEST['staff_key']?>;


var editing='<?php echo $_SESSION['state']['edit_each_staff']['edit']?>';  



var validate_scope_data={
'company_staff':{

    'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item'
	    ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Staff Name')?>'}],'name':'Company_Staff_Name'
	    ,'ar':false,'ar_request':false}
    ,'id':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
	     ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Staff Id')?>'}]
	     ,'name':'Company_Staff_Id' ,'ar':'find','ar_request':'ar_staff.php?tipo=is_staff_id&staff_key='+parent_key+'&query='}
     ,'alias':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
	     ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Staff Alias')?>'}]
	     ,'name':'Company_Staff_Alias' ,'dbname':'Staff Alias','ar':'find','ar_request':'ar_staff.php?tipo=is_staff_alias&staff_key='+parent_key+'&query='}
    

   }
};


var validate_scope_metadata={'company_staff':{'type':'edit','ar_file':'ar_edit_staff.php','key_name':'staff_key','key':<?php echo $_REQUEST['staff_key']?>}};



function validate_id(query){
 validate_general('company_staff','id',unescape(query));
}
function validate_name(query){
 validate_general('company_staff','name',unescape(query));
}
function validate_alias(query){
 validate_general('company_staff','alias',unescape(query));
}
function reset_new_staff(){
 reset_edit_general('company_staff');
}
function save_new_staff(){
 save_edit_general('company_staff');
}





function post_item_updated_actions(branch,r){
key=r.key;
newvalue=r.newvalue;
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
				    ,{key:"code", label:"<?php echo _('Code')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'company_staff'}
				    ,{key:"name", label:"<?php echo _('Name')?>", width:340,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'company_staff' }
				    ,{key:"delete", label:"", width:170,sortable:false,className:"aleft",action:'delete',object:'department'}
				    ,{key:"delete_type", label:"",hidden:true,isTypeKey:true}
				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_contacts.php?tipo=edit_company_departments&parent=staff");
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
									      rowsPerPage:<?php echo$_SESSION['state']['company_staff']['table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
							   ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['company_staff']['table']['order']?>",
							     dir: "<?php echo$_SESSION['state']['company_staff']['table']['order_dir']?>"
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
	    
	    this.dataSource1 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=company_staff&tableid=1");
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
								 rowsPerPage    : <?php echo$_SESSION['state']['edit_each_staff']['history']['nr']?>,containers : 'paginator1', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['edit_each_staff']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['edit_each_staff']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['company']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['company']['history']['f_value']?>'};


	};
    });






function cancel_add_staff(){
   reset_new_staff();
    hide_add_staff_dialog(); 
}


function hide_add_staff_dialog(){
    Dom.get('new_company_staff_dialog').style.display='none';
    Dom.get('add_company_staff').style.display='';
    Dom.get('save_edit_company_staff').style.visibility='hidden';
    Dom.get('reset_edit_company_staff').style.visibility='hidden';

}

function show_add_staff_dialog(){
    Dom.get('new_company_staff_dialog').style.display='';
    Dom.get('add_company_staff').style.display='none';
    Dom.get('save_edit_company_staff').style.visibility='visible';

    Dom.addClass('save_edit_company_staff','disabled');
    Dom.get('reset_edit_company_staff').style.visibility='visible';
    Dom.get('Company_Staff_Id').focus();


}





function change_block(){
   if(editing!=this.id){

	Dom.get('d_details').style.display='none';
	//Dom.get('d_departments').style.display='none';

	Dom.get('d_'+this.id).style.display='';
	Dom.removeClass(editing,'selected');
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=company_staff-edit&value='+this.id );
	
	editing=this.id;
    }


}

function init(){

    var ids = ["details"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);

    YAHOO.util.Event.addListener('add_company_staff', "click", show_add_staff_dialog);
    YAHOO.util.Event.addListener('save_edit_company_staff', "click", save_new_staff);   
    YAHOO.util.Event.addListener('reset_edit_company_staff', "click", cancel_add_staff);
   
    var staff_id_oACDS = new YAHOO.util.FunctionDataSource(validate_id);
    staff_id_oACDS.queryMatchContains = true;
    var staff_id_oAutoComp = new YAHOO.widget.AutoComplete("Company_Staff_Id","Company_Staff_Id_Container", staff_id_oACDS);
    staff_id_oAutoComp.minQueryLength = 0; 
    staff_id_oAutoComp.queryDelay = 0.1;
  
     var staff_name_oACDS = new YAHOO.util.FunctionDataSource(validate_name);
    staff_name_oACDS.queryMatchContains = true;
    var staff_name_oAutoComp = new YAHOO.widget.AutoComplete("Company_Staff_Name","Company_Staff_Name_Container", staff_name_oACDS);
    staff_name_oAutoComp.minQueryLength = 0; 
    staff_name_oAutoComp.queryDelay = 0.1;

    var staff_alias_oACDS = new YAHOO.util.FunctionDataSource(validate_alias);
    staff_alias_oACDS.queryMatchContains = true;
    var staff_alias_oAutoComp = new YAHOO.widget.AutoComplete("Company_Staff_Alias","Company_Staff_Alias_Container", staff_alias_oACDS);
    staff_alias_oAutoComp.minQueryLength = 0; 
    staff_alias_oAutoComp.queryDelay = 0.1;


   

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
