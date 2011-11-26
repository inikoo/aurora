<?php
include_once('common.php');
?>

var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;


var can_add_department=false;
var description_num_changed=0;
var description_warnings= new Object();
var description_errors= new Object();



var validate_scope_data={
'store':{
    'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item'
	    ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Store Name')?>'}],'name':'name'
	    ,'ar':'find','ar_request':'ar_assets.php?tipo=is_store_name&query='}
    ,'code':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
	     ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Store Code')?>'}]
	     ,'name':'code','ar':'find','ar_request':'ar_assets.php?tipo=is_store_code&query='}
    ,'contact':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
	     ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Contact')?>'}]
	     ,'name':'contact','ar':false}   
    ,'slogan':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
	     ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Slogan')?>'}]
	     ,'name':'slogan','ar':false}   
	,'email':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
	     ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Email')?>'}]
	     ,'name':'email','ar':false}   
	,'telephone':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
	     ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Telephone')?>'}]
	     ,'name':'telephone','ar':false}
	,'url':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
	     ,'validation':[{'regexp':regexp_valid_www,'invalid_msg':'<?php echo _('Invalid URL')?>'}]
	     ,'name':'url','ar':false}        
	,'fax':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
	     ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid FAX')?>'}]
	     ,'name':'fax','ar':false}   
	,'address':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
	     ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Address')?>'}]
	     ,'name':'address','ar':false} 
	,'marketing_description':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
	     ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid FAX')?>'}]
	     ,'name':'marketing_description','ar':false} 
   }
};

var validate_scope_metadata={
    'store':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'id','key':<?php echo$_SESSION['state']['store']['id']?>}
  
};



function validate_code(query){ validate_general('store','code',unescape(query));}
function validate_name(query){validate_general('store','name',unescape(query));}
function validate_contact(query){validate_general('store','contact',unescape(query));}
function validate_slogan(query){validate_general('store','slogan',unescape(query));}
function validate_email(query){validate_general('store','email',unescape(query));}
function validate_telephone(query){validate_general('store','telephone',unescape(query));}
function validate_url(query){validate_general('store','url',unescape(query));}
function validate_fax(query){validate_general('store','fax',unescape(query));}
function validate_address(query){validate_general('store','address',unescape(query));}
function validate_marketing_description(query){validate_general('store','marketing_description',unescape(query));}


function new_store_page(){


var request='tipo=new_store_page&store_key='+store_id+'&site_key='+Dom.get('site_key').value

		YAHOO.util.Connect.asyncRequest(
						'POST',
						'ar_edit_sites.php', {
						    success:function(o) {
						    //alert(o.responseText)
							var r = YAHOO.lang.JSON.parse(o.responseText);
							if (r.state == 200) {

								        window.location = "edit_store.php?id=<?php echo$_SESSION['state']['store']['id']?>"
							    }else{
						
                                        alert(r.msg)								
								
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


function reset_edit_store(){
 reset_edit_general('store');
}
function save_edit_store(){
 save_edit_general('store');
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


function change_block(e){
        var ids = ["description","pictures","departments","discounts","charges","shipping","campaigns","website","communications"]; 

	
	if(this.id=='pictures'  ){
	    Dom.get('info_name').style.display='';
	}else
	    Dom.get('info_name').style.display='none';


	Dom.get('d_departments').style.display='none';
	Dom.get('d_pictures').style.display='none';
	Dom.get('d_discounts').style.display='none';
	Dom.get('d_description').style.display='none';
	Dom.get('d_website').style.display='none';

	Dom.get('d_charges').style.display='none';
	Dom.get('d_discounts').style.display='none';
	Dom.get('d_campaigns').style.display='none';
Dom.get('d_communications').style.display='none';
	Dom.get('d_shipping').style.display='none';
	Dom.get('d_'+this.id).style.display='';
	Dom.removeClass(ids,'selected');
	
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=store-edit&value='+this.id ,{});
	
	editing=this.id;
   



}
function new_dept_changed(o){
    if(Dom.get("new_code").value!='' && Dom.get("new_name").value!=''){
	can_add_department=true;
	Dom.removeClass('save_new_department','disabled');
	
    }else{
	Dom.addClass('save_new_department','disabled');
	 can_add_department=false;
    
    }



}
function save_new_department(){
 if(can_add_department==false){
	return;
    }
    var code=Dom.get('new_code').value;
    var name=Dom.get('new_name').value;
  //   var store_key=0;
	
//     for (var i=0; i<Dom.get("new_dept_form").store_key.length; i++)  {
// 	if (Dom.get("new_dept_form").store_key[i].checked)  {
// 	    store_key = Dom.get("new_dept_form").store_key[i].value;
// 	}
//     } 
    


    var request='ar_edit_assets.php?tipo=new_department&code='+encodeURIComponent(code)+'&name='+encodeURIComponent(name);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
	    alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		    var table=tables['table0'];
		    var datasource=tables['dataSource0'];
		    var request='';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
		    
		    Dom.get('new_code').value='';
		    Dom.get('new_name').value='';
		  
		    hide_add_department_dialog();
		    Dom.get('new_department_messages').innerHTML='';
		}else
		    
		    Dom.get('new_department_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	    
	    });

}
YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"id", label:"<?php echo _('Key')?>", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				    ,{key:"go",label:'',width:20,}
				    ,{key:"code", label:"<?php echo _('Code')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'department'}
				    ,{key:"name", label:"<?php echo _('Name')?>", width:340,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'department' }
					,{key:"sales_type", label:"<?php echo _('Sale Type')?>",width:100, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},object:'department',editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:[{label:"<?php echo _('Public Sale')?>",value:'Public Sale'},{label:"<?php echo _('Private Sale')?>",value:'Private Sale'},{label:"<?php echo _('Not For Sale')?>",value:'Not For Sale'}],disableBtns:true})}

			// ,{key:"delete", label:"", width:170,sortable:false,className:"aleft",action:'delete',object:'department'}
				   // ,{key:"delete_type", label:"",hidden:true,isTypeKey:true}
				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_assets.php?tipo=edit_departments&parent=store");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		  	rowsPerPage:"resultset.records_perpage",
		    RecordOffset : "resultset.records_offset", 
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 'id','code','name','delete','delete_type','go','sales_type'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['store']['departments']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
							   ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['store']['departments']['order']?>",
							     dir: "<?php echo$_SESSION['state']['store']['departments']['order_dir']?>"
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
	    
	    this.dataSource1 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=store&tableid=1");
	    
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		  
		 rowsPerPage:"resultset.records_perpage",
		    RecordOffset : "resultset.records_offset", 
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
			 ,'author','date','tipo','abstract','details'
			 ]};
			 
			 
		
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource1
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['product']['history']['nr']?>,containers : 'paginator1', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['store']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['store']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    	
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['product']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['product']['history']['f_value']?>'};

	
	    var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
	    				    {key:"id", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 

				       ,{key:"name",label:"<?php echo _('Name')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'charge' }
				       ,{key:"description",label:"<?php echo _('Description')?>", width:230,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextareaCellEditor({asyncSubmitter: CellEdit}),object:'charge' }
				       	,{key:"editor",label:"", width:230,sortable:false}
				       	,{key:"active",label:"", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				     
				       ];
	    //?tipo=customers&tid=0"
	    
	    this.dataSource2 = new YAHOO.util.DataSource("ar_edit_assets.php?tipo=edit_charges&tableid=2");
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    this.dataSource2.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		   rowsPerPage:"resultset.records_perpage",
		    RecordOffset : "resultset.records_offset", 
		       rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		
		fields: [
			 "name","description","from","to","active","editor","id"
			 ]};
	    
	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource2
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['store']['charges']['nr']?>,containers : 'paginator2', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['store']['charges']['order']?>",
							     dir: "<?php echo$_SESSION['state']['store']['charges']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;

	    this.table2.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table2.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table2.subscribe("cellClickEvent", onCellClick);

		    
		    
	    this.table2.filter={key:'<?php echo $_SESSION['state']['store']['charges']['f_field']?>',value:'<?php echo $_SESSION['state']['store']['charges']['f_value']?>'};


	    
	    var tableid=3; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"name",label:"<?php echo _('Name')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"description",label:"<?php echo _('Description')?>", width:400,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  //     ,{key:"from",label:"<?php echo _('Valid From')?>", width:80,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    //   ,{key:"to",label:"<?php echo _('Valid Until')?>", width:80,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	    //?tipo=customers&tid=0"
	    
	    this.dataSource3 = new YAHOO.util.DataSource("ar_assets.php?tipo=campaigns&parent=store&tableid=3");
	    this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource3.connXhrMode = "queueRequests";
	    this.dataSource3.responseSchema = {
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
			 "name"
			 ,"description","from","to"

			 ]};
	    
	    this.table3 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource3
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['store']['campaigns']['nr']?>,containers : 'paginator3', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['store']['campaigns']['order']?>",
							     dir: "<?php echo$_SESSION['state']['store']['campaigns']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table3.filter={key:'<?php echo $_SESSION['state']['store']['campaigns']['f_field']?>',value:'<?php echo $_SESSION['state']['store']['campaigns']['f_value']?>'};



	       var tableid=4; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"name",label:"<?php echo _('Name')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"description",label:"<?php echo _('Description')?>", width:400,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"from",label:"<?php echo _('Valid From')?>", width:80,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"to",label:"<?php echo _('Valid Until')?>", width:80,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	    //?tipo=customers&tid=0"
	    
	    this.dataSource4 = new YAHOO.util.DataSource("ar_edit_assets.php?tipo=edit_deals&parent=store&tableid=4");
	    this.dataSource4.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource4.connXhrMode = "queueRequests";
	    this.dataSource4.responseSchema = {
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
			 "name"
			 ,"description","from","to"

			 ]};
	    
	    this.table4 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource4
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['store']['deals']['nr']?>,containers : 'paginator4', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info4'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['store']['deals']['order']?>",
							     dir: "<?php echo$_SESSION['state']['store']['deals']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table4.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table4.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table4.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table4.filter={key:'<?php echo $_SESSION['state']['store']['deals']['f_field']?>',value:'<?php echo $_SESSION['state']['store']['deals']['f_value']?>'};





   var tableid=6; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
				       {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}
				         ,{key:"go", label:"", width:20,action:"none"}
				       ,{key:"code",label:"<?php echo _('Code')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'store_page_properties'}
				       ,{key:"store_title",label:"<?php echo _('Header Title')?>", <?php echo($_SESSION['state']['store']['edit_pages']['view']=='page_header'?'':'hidden:true,')?>width:400,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'store_page_properties'}
				     	  ,{key:"link_title",label:"<?php echo _('Link Title')?>", <?php echo($_SESSION['state']['store']['edit_pages']['view']=='page_properties'?'':'hidden:true,')?>width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'store_page_properties'}
				     	  ,{key:"url",label:"<?php echo _('URL')?>", <?php echo($_SESSION['state']['store']['edit_pages']['view']=='page_properties'?'':'hidden:true,')?>width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'store_page_properties'}
				     	  ,{key:"page_title",label:"<?php echo _('Browser Title')?>",<?php echo($_SESSION['state']['store']['edit_pages']['view']=='page_html_head'?'':'hidden:true,')?> width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'store_page_properties'}
				     	  ,{key:"page_keywords",label:"<?php echo _('Keywords')?>",<?php echo($_SESSION['state']['store']['edit_pages']['view']=='page_html_head'?'':'hidden:true,')?> width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'store_page_properties'}

				     
				     ,{key:"delete", label:"",width:12,sortable:false,action:'delete',object:'page_store'}		         
				       ];
				       
	 
				       
				       
	    //?tipo=customers&tid=0"
	        this.dataSource6 = new YAHOO.util.DataSource("ar_edit_sites.php?tipo=pages&site_key="+Dom.get('site_key').value+"&parent=store&parent_key="+Dom.get('store_id').value+"&tableid=6");

//alert("ar_edit_sites.php?tipo=pages&site_key="+Dom.get('site_key').value+"&parent=store&parent_key="+Dom.get('store_id').value+"&tableid=6")
	    this.dataSource6.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource6.connXhrMode = "queueRequests";
	    this.dataSource6.responseSchema = {
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
			 "id","go","code","store_title","delete","link_title","url","page_title","page_keywords"

			 ]};

        this.table6 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource6
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    :<?php echo $_SESSION['state']['store']['edit_pages']['nr']?> ,containers : 'paginator6', alpartysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info6'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo $_SESSION['state']['store']['edit_pages']['order']?>",
							     dir: "<?php echo $_SESSION['state']['store']['edit_pages']['order_dir']?>"
							 },
							 dynamicData : true
						     }
						     );
	    
	 
	    
	    this.table6.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table6.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table6.doBeforePaginatorChange = mydoBeforePaginatorChange;


	    this.table6.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table6.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table6.subscribe("cellClickEvent", onCellClick);
		    
	    this.table6.filter={key:'<?php echo $_SESSION['state']['store']['edit_pages']['f_field']?>',value:'<?php echo $_SESSION['state']['store']['edit_pages']['f_value']?>'};


	  













	};
    });
function cancel_add_department(){
    Dom.get('new_code').value='';
    Dom.get('new_name').value='';
    
    hide_add_department_dialog(); 
}
function hide_add_department_dialog(){
    Dom.get('new_department_dialog').style.display='none';
    Dom.get('add_department').style.display='';
    Dom.get('save_new_department').style.display='none';
    Dom.get('close_add_department').style.display='none';
}
function show_add_department_dialog(){
    Dom.get('new_department_dialog').style.display='';
    Dom.get('add_department').style.display='none';
    Dom.get('save_new_department').style.display='';

    Dom.addClass('save_new_department','disabled');
    Dom.get('close_add_department').style.display='';
    Dom.get('new_code').focus();


}


function init(){

  ids=['page_properties','page_html_head','page_header'];
 YAHOO.util.Event.addListener(ids, "click",change_edit_pages_view,{'table_id':6,'parent':'page'})


  init_search('products_store');


    var ids = ["description","pictures","departments","discounts","charges","shipping","campaigns","website","communications"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
    YAHOO.util.Event.addListener('add_department', "click", show_add_department_dialog);
    YAHOO.util.Event.addListener('save_new_department', "click",save_new_department);
    YAHOO.util.Event.addListener('close_add_department', "click", cancel_add_department);


    YAHOO.util.Event.addListener('reset_edit_store', "click", reset_edit_store);
    YAHOO.util.Event.addListener('save_edit_store', "click", save_edit_store);
  YAHOO.util.Event.addListener('new_store_page', "click", new_store_page);

    var store_code_oACDS = new YAHOO.util.FunctionDataSource(validate_code);
    store_code_oACDS.queryMatchContains = true;
    var store_code_oAutoComp = new YAHOO.widget.AutoComplete("code","code_Container", store_code_oACDS);
    store_code_oAutoComp.minQueryLength = 0; 
    store_code_oAutoComp.queryDelay = 0.1;
    
    var store_name_oACDS = new YAHOO.util.FunctionDataSource(validate_name);
    store_name_oACDS.queryMatchContains = true;
    var store_name_oAutoComp = new YAHOO.widget.AutoComplete("name","name_Container", store_name_oACDS);
    store_name_oAutoComp.minQueryLength = 0; 
    store_name_oAutoComp.queryDelay = 0.1;
    
    var store_name_oACDS = new YAHOO.util.FunctionDataSource(validate_contact);
    store_name_oACDS.queryMatchContains = true;
    var store_name_oAutoComp = new YAHOO.widget.AutoComplete("contact","contact_Container", store_name_oACDS);
    store_name_oAutoComp.minQueryLength = 0; 
    store_name_oAutoComp.queryDelay = 0.1;
    
    var store_name_oACDS = new YAHOO.util.FunctionDataSource(validate_slogan);
    store_name_oACDS.queryMatchContains = true;
    var store_name_oAutoComp = new YAHOO.widget.AutoComplete("slogan","slogan_Container", store_name_oACDS);
    store_name_oAutoComp.minQueryLength = 0; 
    store_name_oAutoComp.queryDelay = 0.1;
    
    var store_name_oACDS = new YAHOO.util.FunctionDataSource(validate_email);
    store_name_oACDS.queryMatchContains = true;
    var store_name_oAutoComp = new YAHOO.widget.AutoComplete("email","email_Container", store_name_oACDS);
    store_name_oAutoComp.minQueryLength = 0; 
    store_name_oAutoComp.queryDelay = 0.1;
    
    var store_name_oACDS = new YAHOO.util.FunctionDataSource(validate_telephone);
    store_name_oACDS.queryMatchContains = true;
    var store_name_oAutoComp = new YAHOO.widget.AutoComplete("telephone","telephone_Container", store_name_oACDS);
    store_name_oAutoComp.minQueryLength = 0; 
    store_name_oAutoComp.queryDelay = 0.1;    
    
    var store_name_oACDS = new YAHOO.util.FunctionDataSource(validate_fax);
    store_name_oACDS.queryMatchContains = true;
    var store_name_oAutoComp = new YAHOO.widget.AutoComplete("fax","fax_Container", store_name_oACDS);
    store_name_oAutoComp.minQueryLength = 0; 
    store_name_oAutoComp.queryDelay = 0.1;
    
    var store_name_oACDS = new YAHOO.util.FunctionDataSource(validate_url);
    store_name_oACDS.queryMatchContains = true;
    var store_name_oAutoComp = new YAHOO.widget.AutoComplete("url","url_Container", store_name_oACDS);
    store_name_oAutoComp.minQueryLength = 0; 
    store_name_oAutoComp.queryDelay = 0.1;    
    
    var store_name_oACDS = new YAHOO.util.FunctionDataSource(validate_address);
    store_name_oACDS.queryMatchContains = true;
    var store_name_oAutoComp = new YAHOO.widget.AutoComplete("address","address_Container", store_name_oACDS);
    store_name_oAutoComp.minQueryLength = 0; 
    store_name_oAutoComp.queryDelay = 0.1;      
    
    var store_name_oACDS = new YAHOO.util.FunctionDataSource(validate_marketing_description);
    store_name_oACDS.queryMatchContains = true;
    var store_name_oAutoComp = new YAHOO.widget.AutoComplete("marketing_description","marketing_description_Container", store_name_oACDS);
    store_name_oAutoComp.minQueryLength = 0; 
    store_name_oAutoComp.queryDelay = 0.1;   

 var formObject = document.getElementById('aForm');
 
   // the second argument is true to indicate file upload.
 //  YAHOO.util.Connect.setForm(Dom.get('logo_file_upload_form'), true);
 

   YAHOO.util.Event.addListener('edit_deals_templates', "click",edit_deal_templates)
   YAHOO.util.Event.addListener('close_edit_deals_templates', "click",close_edit_deals_templates)

  

}

function edit_deal_templates(){

Dom.setStyle('d_campaigns','display','')
Dom.setStyle('d_discounts','display','none')

}

function close_edit_deals_templates(){
Dom.setStyle('d_campaigns','display','none')
Dom.setStyle('d_discounts','display','')

}

function charge_changed(key){
Dom.setStyle(['charge_save'+key,'charge_reset'+key],'visibility','visible')
}

function charge_save(key){
	Dom.setStyle(['charge_save'+key,'charge_reset'+key],'display','none')
		Dom.setStyle('charge_saving','display','')

	

	var request='ar_edit_assets.php?tipo=edit_charge&key=charge&newvalue='+Dom.get('charge'+key).value+'&id='+key;
alert(request)
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
				Dom.setStyle('charge_saving','display','none')

		Dom.setStyle(['charge_save'+key,'charge_reset'+key],'display','')

		Dom.setStyle(['charge_save'+key,'charge_reset'+key],'visibility','hidden')

		}else{
		  
	    }
	    }
	    });


}

function charge_reset(key){
Dom.get('charge'+key).value=Dom.get('charge'+key).getAttribute('ovalue');
Dom.setStyle(['charge_save'+key,'charge_reset'+key],'visibility','hidden')

}

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {trigger:"filter_name1"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });

