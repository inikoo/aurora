<?php include_once('common.php');?>
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var department_id=<?php echo$_SESSION['state']['department']['id']?>;
var editing=<?php echo $_SESSION['state']['department']['edit']?>;
var can_add_family=false;

var scope_key=<?php echo$_SESSION['state']['department']['id']?>;
var scope='department';


var validate_scope_metadata={
    'department':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'id','key':<?php echo$_SESSION['state']['department']['id']?>}
//    ,'department_page_html_head':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'id','key':<?php echo$_SESSION['state']['department']['id']?>}
//    ,'department_page_header':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'id','key':<?php echo$_SESSION['state']['department']['id']?>}
//,'department_page_content':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'id','key':<?php echo$_SESSION['state']['department']['id']?>}
};

var validate_scope_data={
    'department':{
	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item'
		,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Department Name')?>'}],'name':'name'
		,'ar':'find','ar_request':'ar_assets.php?tipo=is_department_name&store_key='+store_key+'&query='}
	,'code':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Department Code')?>'}]
		 ,'name':'code','ar':'find','ar_request':'ar_assets.php?tipo=is_department_code&store_key='+store_key+'&query='}
//	,'special_char':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
//			 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Family Special Characteristic')?>'}]
//			 ,'name':'special_char','ar':'find','ar_request':'ar_assets.php?tipo=is_department_special_char&store_key='+store_key+'&query='}
//	,'description':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'description','ar':false}
    }
//    ,'department_page_html_head':{
//	'url':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
//		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid URL')?>'}]
//		 ,'name':'department_page_html_head_url','ar':false
		 
//	}
//	,'title':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
//		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Title')?>'}]
//		 ,'name':'department_page_html_head_title','ar':false
		 
//	}
	
//	,'keywords':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'department_page_html_head_keywords','ar':false}
//    }
//,'department_page_header':{
//	'store_title':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
//		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Title')?>'}]
//		 ,'name':'department_page_header_store_title','ar':false
		 
//	}
//	,'subtitle':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'department_page_header_subtitle','ar':false}
//	,'slogan':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'department_page_header_slogan','ar':false}
//	,'resume':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'department_page_header_resume','ar':false}
	
 //   }
//,'department_page_content':{
	
//	'presentation_template_data':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'department_page_content_presentation_template_data','ar':false}
	
  //  }


};





function change_block(e){
     if(editing!=this.id){
     
     if(this.id=='pictures' || this.id=='discounts'){
	    Dom.get('info_name').style.display='';
	}else
	    Dom.get('info_name').style.display='none';
     
     
	 Dom.get('d_families').style.display='none';
	 Dom.get('d_description').style.display='none';
	 Dom.get('d_discounts').style.display='none';
	 Dom.get('d_pictures').style.display='none';
	 Dom.get('d_web').style.display='none';
	 Dom.get('d_'+this.id).style.display='';
	 Dom.removeClass(editing,'selected');
	 Dom.addClass(this, 'selected');
	 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=department-edit&value='+this.id ,{});
	editing=this.id;
    }
}






YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {




	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}
				    ,{key:"go", label:"", width:20,action:"none"}
				    ,{key:"code", label:"<?php echo _('Code')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family'}
				    ,{key:"name", label:"<?php echo _('Name')?>",width:300, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family'}
				    ,{key:"delete", label:"",width:100,className:"aleft",action:"delete",object:'family'}
				    ,{key:"delete_type", label:"",hidden:true,isTypeKey:true}

				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_assets.php?tipo=edit_families&parent=department&tableid=0");
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
			 "code",
			 "name",
			 'delete','delete_type','id','edit','go'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['tables']['departments_list'][2]?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['tables']['departments_list'][0]?>",
									 dir: "<?php echo$_SESSION['tables']['departments_list'][1]?>"
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
	    
	    this.table0.view='<?php echo$_SESSION['state']['department']['view']?>';

		

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

	    this.dataSource1 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=department&tableid=1");
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
								 rowsPerPage    : <?php echo$_SESSION['state']['department']['history']['nr']?>,containers : 'paginator1', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['department']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['department']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['department']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['department']['history']['f_value']?>'};
	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown);





	};
    });

// ------------------------------------------------------------------------
function validate_department_page_content_presentation_template_data(query){validate_general('department_page_content','presentation_template_data',unescape(query));}


function validate_department_page_header_store_title(query){validate_general('department_page_header','store_title',unescape(query));}
function validate_department_page_header_subtitle(query){validate_general('department_page_header','subtitle',unescape(query));}
function validate_department_page_header_slogan(query){validate_general('department_page_header','slogan',unescape(query));}
function validate_department_page_header_resume(query){validate_general('department_page_header','resume',unescape(query));}


function validate_department_page_html_head_url(query){validate_general('department_page_html_head','url',unescape(query));}

function validate_department_page_html_head_title(query){validate_general('department_page_html_head','title',unescape(query));}
function validate_department_page_html_head_keywords(query){validate_general('department_page_html_head','keywords',unescape(query));}


function validate_code(query){
   
 validate_general('department','code',unescape(query));
}
function validate_name(query){
 validate_general('department','name',unescape(query));
}
function validate_special_char(query){
 validate_general('department','special_char',unescape(query));
}

function validate_description(query){
   
 validate_general('department','description',unescape(query));
}


function reset_edit_department(){
 reset_edit_general('department');
}
function save_edit_department(){
 save_edit_general('departmenty');
}
function reset_edit_department_page_header(){ reset_edit_general('department_page_header');}
function save_edit_department_page_header(){save_edit_general('department_page_header');}

function reset_edit_department_page_html_head(){ reset_edit_general('department_page_html_head');}
function save_edit_department_page_html_head(){save_edit_general('department_page_html_head');}

function reset_edit_department_page_content(){ reset_edit_general('department_page_content');}
function save_edit_department_page_content(){save_edit_general('department_page_content');}


function post_item_updated_actions(branch,key,newvalue){

 if(key=='name')
     Dom.get('title_name').innerHTML=newvalue;
 
 else if(key=='code')
     Dom.get('title_code').innerHTML=newvalue;

 
 var table=tables.table1;
 var datasource=tables.dataSource1;
 var request='';
 datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
 
}


// ------------------------------------------------------------------------




function init(){
// -----------------------------------------------------------------
   var ids = ["checkbox_thumbnails","checkbox_list","checkbox_slideshow","checkbox_manual"]; 
    YAHOO.util.Event.addListener(ids, "click", select_layout);

// -----------------------------------------------------------------	
 	YAHOO.util.Event.on('uploadButton', 'click', onUploadButtonClick);
// ---------------------------------------------------------------------
 YAHOO.util.Event.addListener('reset_edit_department', "click", reset_edit_family);
    YAHOO.util.Event.addListener('save_edit_department', "click", save_edit_family);


var department_code_oACDS = new YAHOO.util.FunctionDataSource(validate_code);
    department_code_oACDS.queryMatchContains = true;
    var department_code_oAutoComp = new YAHOO.widget.AutoComplete("code","code_Container", family_code_oACDS);
    department_code_oAutoComp.minQueryLength = 0; 
    department_code_oAutoComp.queryDelay = 0.1;
    
     var department_name_oACDS = new YAHOO.util.FunctionDataSource(validate_name);
    department_name_oACDS.queryMatchContains = true;
    var department_name_oAutoComp = new YAHOO.widget.AutoComplete("name","name_Container", family_name_oACDS);
    department_name_oAutoComp.minQueryLength = 0; 
    department_name_oAutoComp.queryDelay = 0.1;

  

    
    var department_page_content_presentation_template_data_oACDS = new YAHOO.util.FunctionDataSource(validate_department_page_content_presentation_template_data);
    
    department_page_content_presentation_template_data_oACDS.queryMatchContains = true;
    var department_page_content_presentation_template_data_oAutoComp = new YAHOO.widget.AutoComplete("department_page_content_presentation_template_data","department_page_content_presentation_template_data_Container", department_page_content_presentation_template_data_oACDS);
    department_page_content_presentation_template_data_oAutoComp.minQueryLength = 0; 
    department_page_content_presentation_template_data_oAutoComp.queryDelay = 0.1;
}
// ---------------------------------------------------------------------
 
    function mygetTerms(query) {multireload();};
    var oACDS = new YAHOO.widget.DS_JSFunction(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","filtercontainer0", oACDS);
    oAutoComp.minQueryLength = 0; 
    
    var ids = ["description","families","discounts","pictures","web"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
    YAHOO.util.Event.addListener('add_family', "click", show_add_family_dialog);
    YAHOO.util.Event.addListener('save_new_family', "click",save_new_family);
    YAHOO.util.Event.addListener('cancel_add_family', "click", cancel_add_family);


}

YAHOO.util.Event.onDOMReady(init);
YAHOO.util.Event.onContentReady("rppmenu0", function () {

	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });
