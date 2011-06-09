<?php
include_once('common.php');
?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;

var can_add_department=false;
var description_num_changed=0;
var description_warnings= new Object();
var description_errors= new Object();

var scope='ind_staff';
var scope_edit_ar_file='ar_edit_staff.php';
var scope_key_name='id';
var scope_key=0;
	
var parent='company';
var parent_key_name='id';
var parent_key=<?php echo $_REQUEST['company_key']?>;


var validate_scope_data={
'ind_staff':{
    'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item'
	    ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Staff Name')?>'}],'name':'Company_Staff_Name','dbname':'Staff Name'
	    ,'ar':'find','ar_request':'ar_staff.php?tipo=is_company_staff_name&company_key='+parent_key+'&query='}
    ,'id':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
	     ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Staff Id')?>'}]
	     ,'name':'Company_Staff_Id' ,'dbname':'Staff Key','ar':'find','ar_request':'ar_staff.php?tipo=is_company_staff_id&company_key='+parent_key+'&query='}
   }
};

var validate_scope_metadata={'ind_staff':{'type':'edit','ar_file':'ar_edit_staff.php'}};

function validate_id(query){
 validate_general('ind_staff','id',unescape(query));
}
function validate_name(query){
 validate_general('ind_staff','name',unescape(query));
}
function reset_new_staff(){
 reset_edit_general('ind_staff');
}
function save_new_staff(){
 save_new_general('ind_staff');
}


function post_item_updated_actions(branch,r){

key=r.key;
newvalue=r.newvalue;

 if(key=='name')
     Dom.get('title_name').innerHTML=newvalue;
 
 else if(key=='id')
     Dom.get('title_id').innerHTML=newvalue;

 
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


	    var tableid=0; // Change if you have more the 1 table..........
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"staff_key", label:"<?php echo _('Staff Key')?>", width:20,sortable:false,isPrimaryKey:true,hidden:true}
				    ,{key:"go",label:'',width:20,}
				       ,{key:"id", label:"<?php echo _('Staff ID')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'ind_staff'}
				    ,{key:"name", label:"<?php echo _('Staff Name')?>", width:340,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'ind_staff' }
				    ,{key:"delete", label:"", width:170,sortable:false,className:"aleft",action:'delete',object:'ind_staff'}
				    ,{key:"delete_type", label:"",hidden:true,isTypeKey:true}
				     ];

	    //this.dataSource0 = new YAHOO.util.DataSource("ar_edit_staff.php?tipo=edit_company_staff&parent=corporation");
            this.dataSource0 = new YAHOO.util.DataSource("ar_edit_staff.php?tipo=list_members_of_staff_to_edit&parent=corporation");
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
			  'id','staff_key','name','delete','delete_type','go'

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
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}" })
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
	    
	    this.dataSource1 = new YAHOO.util.DataSource("ar_history.php?tipo=indirect_history&parent="+parent+"&parent_key="+parent_key+"&scope=company_staff&tableid=1");
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

		    
		    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['company']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['company']['history']['f_value']?>'};


	};
    });






function cancel_add_staff(){
   reset_new_staff();
    hide_add_staff_dialog(); 
}


function hide_add_staff_dialog(){
    Dom.get('new_staff_dialog').style.display='none';
    Dom.get('add_staff').style.display='';
    Dom.get('save_edit_company_staff').style.visibility='hidden';

    Dom.get('reset_edit_company_staff').style.visibility='hidden';

}

function show_add_staff_dialog(){
    Dom.get('new_staff_dialog').style.display='';
    Dom.get('add_staff').style.display='none';
    Dom.get('save_edit_company_staff').style.visibility='visible';

    Dom.addClass('save_edit_company_staff','disabled');
    Dom.get('reset_edit_company_staff').style.visibility='visible';
    Dom.get('Company_Staff_Id').focus();


}


function init(){
   // var ids = ["description","pictures","web","departments","discounts","charges","shipping","campaigns"]; 
   // YAHOO.util.Event.addListener(ids, "click", change_block);
 YAHOO.util.Event.addListener('add_staff', "click",show_add_staff_dialog);
    YAHOO.util.Event.addListener('save_edit_company_staff', "click",save_new_staff);
    YAHOO.util.Event.addListener('reset_edit_company_staff', "click",cancel_add_staff);
    var store_id_oACDS = new YAHOO.util.FunctionDataSource(validate_id);
    store_id_oACDS.queryMatchContains = true;
    var store_id_oAutoComp = new YAHOO.widget.AutoComplete("Company_Staff_Id","Company_Staff_Id_Container", staff_id_oACDS);
    store_id_oAutoComp.minQueryLength = 0; 
    store_id_oAutoComp.queryDelay = 0.1;    
     var store_name_oACDS = new YAHOO.util.FunctionDataSource(validate_name);
    store_name_oACDS.queryMatchContains = true;
    var store_name_oAutoComp = new YAHOO.widget.AutoComplete("Company_Staff_Name","Company_Staff_Name_Container", staff_name_oACDS);
    store_name_oAutoComp.minQueryLength = 0; 
    store_name_oAutoComp.queryDelay = 0.1;

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
