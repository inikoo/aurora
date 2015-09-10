<?php
include_once('common.php');



?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;


var can_add_department=false;
var description_num_changed=0;
var description_warnings= new Object();
var description_errors= new Object();


var scope='company_position';
var scope_edit_ar_file='ar_edit_staff.php';
var scope_key_name='id';
var scope_key=<?php echo $_SESSION['state']['company_position']['id']?>;

	
var parent='staff';
var parent_key_name='id';
var parent_key=<?php echo $_REQUEST['position_key']?>;
// alert(parent_key);
var editing='<?php echo $_SESSION['state']['edit_each_position']['edit']?>';




var validate_scope_data={
'company_position':{

    'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item'
	    ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Position Name')?>'}],'name':'Company_Position_Name','dbname':'Company Position Title'
	    ,'ar':'find','ar_request':'ar_contacts.php?tipo=is_position_name&company_key='+parent_key+'&query='}
    ,'code':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
	     ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Position Code')?>'}]
	     ,'name':'Company_Position_Code' ,'dbname':'Company Position Code','ar':'find','ar_request':'ar_contacts.php?tipo=is_position_code&company_key='+parent_key+'&query='}
     ,'description':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
	     ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Position Description')?>'}]
	     ,'name':'Company_Position_Description' ,'dbname':'Company Position Description','ar':'find','ar_request':'ar_staff.php?tipo=is_staff_alias&staff_key='+parent_key+'&query='}
    


   }
};



var validate_scope_metadata={'company_position':{'type':'edit','ar_file':'ar_edit_staff.php','key_name':'position_key','key':<?php echo $_REQUEST['position_key']?>}};


function validate_code(query){
 validate_general('company_position','code',unescape(query));
}
function validate_name(query){
 validate_general('company_position','name',unescape(query));
}
function validate_description(query){
 validate_general('company_position','description',unescape(query));
}
function reset_new_position(){
 reset_edit_general('company_position');
}
function save_new_position(){
 save_edit_general('company_position');
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
				    {key:"id", label:"<?php echo _('Key')?>", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				    ,{key:"go",label:'',width:20,}
				    ,{key:"code", label:"<?php echo _('Code')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'company_area'}
				    ,{key:"name", label:"<?php echo _('Name')?>", width:340,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'company_area' }
				    ,{key:"delete", label:"", width:170,sortable:false,className:"aleft",action:'delete',object:'department'}
				    ,{key:"delete_type", label:"",hidden:true,isTypeKey:true}
				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_contacts.php?tipo=edit_company_departments&parent=area");
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
									      rowsPerPage:<?php echo$_SESSION['state']['company_areas']['table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
							   ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['company_areas']['table']['order']?>",
							     dir: "<?php echo$_SESSION['state']['company_areas']['table']['order_dir']?>"
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
				       {key:"date",label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author",label:"<?php echo _('Author')?>", width:70,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //     ,{key:"tipo", label:"<?php echo _('Type')?>", width:90,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //,{key:"diff_qty",label:"<?php echo _('Qty')?>", width:90,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"abstract", label:"<?php echo _('Description')?>", width:370,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	    //?tipo=customers&tid=0"
	    
	    this.dataSource1 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=company_position&tableid=1");
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

		    
		    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['position']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['position']['history']['f_value']?>'};


	};
    });






function cancel_add_position(){
   reset_new_position();
    hide_add_position_dialog(); 
}


function hide_add_position_dialog(){
    Dom.get('new_company_position_dialog').style.display='none';
    Dom.get('add_company_position').style.display='';
    Dom.get('save_edit_company_position').style.visibility='hidden';

    Dom.get('reset_edit_company_position').style.visibility='hidden';

}

function show_add_position_dialog(){
    Dom.get('new_company_position_dialog').style.display='';
    Dom.get('add_company_position').style.display='none';
    Dom.get('save_edit_company_position').style.visibility='visible';

    Dom.addClass('save_edit_company_position','disabled');
    Dom.get('reset_edit_company_position').style.visibility='visible';
    Dom.get('Company_Position_Code').focus();


}



function change_block(){
   if(editing!=this.id){

	Dom.get('d_details').style.display='none';
	Dom.get('d_departments').style.display='none';

	Dom.get('d_'+this.id).style.display='';
	Dom.removeClass(editing,'selected');
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=company_area-edit&value='+this.id );
	
	editing=this.id;
    }


}
 

function init(){

    var ids = ["details"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);

    YAHOO.util.Event.addListener('add_company_position', "click", show_add_position_dialog);
    YAHOO.util.Event.addListener('save_edit_company_position', "click",save_new_position);
    YAHOO.util.Event.addListener('reset_edit_company_position', "click", cancel_add_position);
    

    var position_code_oACDS = new YAHOO.util.FunctionDataSource(validate_code);
    position_code_oACDS.queryMatchContains = true;
    var position_code_oAutoComp = new YAHOO.widget.AutoComplete("Company_Position_Code","Company_Position_Code_Container", position_code_oACDS);
    position_code_oAutoComp.minQueryLength = 0; 
    position_code_oAutoComp.queryDelay = 0.1;
    
     var position_name_oACDS = new YAHOO.util.FunctionDataSource(validate_name);
    position_name_oACDS.queryMatchContains = true;
    var position_name_oAutoComp = new YAHOO.widget.AutoComplete("Company_Position_Name","Company_Position_Name_Container", position_name_oACDS);
    position_name_oAutoComp.minQueryLength = 0; 
    position_name_oAutoComp.queryDelay = 0.1;

     var position_description_oACDS = new YAHOO.util.FunctionDataSource(validate_description);
    position_description_oACDS.queryMatchContains = true;
    var position_description_oAutoComp = new YAHOO.widget.AutoComplete("Company_Position_Description","Company_Position_Description_Container", position_description_oACDS);
    position_description_oAutoComp.minQueryLength = 0; 
    position_description_oAutoComp.queryDelay = 0.1;


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
