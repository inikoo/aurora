<?php
include_once('common.php');



?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;


var can_add_department=false;
var description_num_changed=0;
var description_warnings= new Object();
var description_errors= new Object();






function validate_code(query){
 validate_general('company_area','code',unescape(query));
}
function validate_name(query){
 validate_general('company_area','name',unescape(query));
}
function validate_description(query){
 validate_general('company_area','description',unescape(query));
}
function reset_new_area(){
 reset_edit_general('company_area');
}
function save_new_area(){
 save_edit_general('company_area');
}

function reset_new_department(){
 reset_edit_general('company_department');
}
function save_new_department(){
 save_new_general('company_department');
}
function validate_department_code(query){
 validate_general('company_department','code',unescape(query));
}
function validate_department_name(query){
 validate_general('company_department','name',unescape(query));
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

	/*    var tableid=0; // Change if you have more the 1 table
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
									      rowsPerPage:<?php echo$_SESSION['state']['company_area']['departments']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}" })
								     
							   ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['company_area']['departments']['order']?>",
							     dir: "<?php echo$_SESSION['state']['company_area']['departments']['order_dir']?>"
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
	    
	    this.dataSource1 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=company_area&tableid=1");
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
								 rowsPerPage    : <?php echo$_SESSION['state']['company_area']['history']['nr']?>,containers : 'paginator1', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['company_area']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['company_area']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['company_area']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['company_area']['history']['f_value']?>'};


	};
    });






function cancel_add_area(){
   reset_new_area();
    hide_add_area_dialog(); 
}


function hide_add_area_dialog(){
    Dom.get('new_area_dialog').style.display='none';
    Dom.get('add_area').style.display='';
    Dom.get('save_edit_company_area').style.visibility='hidden';

    Dom.get('reset_edit_company_area').style.visibility='hidden';

}

function show_add_area_dialog(){
    Dom.get('new_area_dialog').style.display='';
    Dom.get('add_area').style.display='none';
    Dom.get('save_edit_company_area').style.visibility='visible';

    Dom.addClass('save_edit_company_area','disabled');
    Dom.get('reset_edit_company_area').style.visibility='visible';
    Dom.get('Company_Area_Code').focus();


}


function cancel_add_department(){
   reset_new_department();
    hide_add_department_dialog(); 
}


function hide_add_department_dialog(){
    Dom.get('new_department_dialog').style.display='none';
    Dom.get('add_department').style.display='';
    Dom.get('save_edit_company_department').style.visibility='hidden';

    Dom.get('reset_edit_company_department').style.visibility='hidden';

}

function show_add_department_dialog(){



    Dom.get('new_department_dialog').style.display='';
    Dom.get('add_department').style.display='none';
    Dom.get('save_edit_company_department').style.visibility='visible';

    Dom.addClass('save_edit_company_department','disabled');
    Dom.get('reset_edit_company_department').style.visibility='visible';
    Dom.get('Company_Area_Code').focus();


}




function change_block(){
  

	Dom.get('d_details').style.display='none';
	Dom.get('d_departments').style.display='none';

	Dom.get('d_'+this.id).style.display='';
	Dom.removeClass(['details','departments'],'selected');
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=company_area-edit_block&value='+this.id );



}




   function show_history() {
        Dom.setStyle(['show_history', ''], 'display', 'none')
        Dom.setStyle(['hide_history', 'history_table'], 'display', '')
        YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=company_area-show_history&value=1', {});
    }

    function hide_history() {
        Dom.setStyle(['show_history', ''], 'display', '')
        Dom.setStyle(['hide_history', 'history_table'], 'display', 'none')
        YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=company_area-show_history&value=0', {});
    }



function init(){





 validate_scope_data={
'company_area':{

    'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item'
	    ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Area Name')?>'}],'name':'Company_Area_Name','dbname':'Company Area Name'
	    ,'ar':'find','ar_request':'ar_staff.php?tipo=is_company_area_name&company_key=0&query='}
    ,'code':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
	     ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Area Code')?>'}]
	     ,'name':'Company_Area_Code' ,'dbname':'Company Area Code','ar':'find','ar_request':'ar_staff.php?tipo=is_company_area_code&company_key='+Dom.get('area_key').value+'&query='}
    ,'description':{'changed':false,'validated':true,'required':true,'group':1,'type':'item'
	    ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Area Description')?>'}],'name':'Company_Area_Description','dbname':'Company Area Description'
	    ,'ar':'false','ar_request':'false'}

   }


};


 validate_scope_metadata={'company_area':{'type':'edit','ar_file':'ar_edit_staff.php','key_name':'company_area_key','key':Dom.get('area_key').value}};



    var ids = ["details","departments"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);

    YAHOO.util.Event.addListener('save_edit_company_area', "click",save_new_area);
    YAHOO.util.Event.addListener('reset_edit_company_area', "click", cancel_add_area);
    

    var area_code_oACDS = new YAHOO.util.FunctionDataSource(validate_code);
    area_code_oACDS.queryMatchContains = true;
    var area_code_oAutoComp = new YAHOO.widget.AutoComplete("Company_Area_Code","Company_Area_Code_Container", area_code_oACDS);
    area_code_oAutoComp.minQueryLength = 0; 
    area_code_oAutoComp.queryDelay = 0.1;
    
     var area_name_oACDS = new YAHOO.util.FunctionDataSource(validate_name);
    area_name_oACDS.queryMatchContains = true;
    var area_name_oAutoComp = new YAHOO.widget.AutoComplete("Company_Area_Name","Company_Area_Name_Container", area_name_oACDS);
    area_name_oAutoComp.minQueryLength = 0; 
    area_name_oAutoComp.queryDelay = 0.1;

      var area_description_oACDS = new YAHOO.util.FunctionDataSource(validate_description);
    area_description_oACDS.queryMatchContains = true;
    var area_description_oAutoComp = new YAHOO.widget.AutoComplete("Company_Area_Description","Company_Area_Description_Container", area_description_oACDS);
    area_description_oAutoComp.minQueryLength = 0; 
    area_description_oAutoComp.queryDelay = 0.1;


}

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {trigger:"filter_name1"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });


YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
    });
