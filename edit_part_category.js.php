<?php
include_once('common.php');

?>


var category_show_options=[{label:"<?php echo _('Yes')?>", value:"Yes"}, {label:"<?php echo _('No')?>", value:"No"}];
var category_show_name={'Yes':'Yes','No':'No'};

YAHOO.util.Event.addListener(window, "load", function() {



    tables = new function() {
  var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"id", label:"<?php echo _('Key')?>", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				    ,{key:"go",label:'',width:20,}
				 
				    ,{key:"name", label:"<?php echo _('Code')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'subcategory' }
								    ,{key:"label", label:"<?php echo _('Label')?>", width:340,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'subcategory' }

                                      ,{key:"delete", label:"", width:100,sortable:false,className:"aleft",action:'delete',object:'subcategory'}
				    ,{key:"delete_type", label:"",hidden:true,isTypeKey:true}
				     ];



	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_categories.php?tipo=edit_part_category_list&parent_key="+Dom.get('category_key').value);
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
			 'id','name','delete','delete_type','go','label'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['categories']['table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
							   ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['categories']['table']['order']?>",
							     dir: "<?php echo$_SESSION['state']['categories']['table']['order_dir']?>"
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
	    
	    this.dataSource1 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=part_categories&tableid=1");
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
			 "id"
			 ,"note"
			 ,'author','date','tipo','abstract','details'
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource1
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['part_categories']['history']['nr']?>,containers : 'paginator1', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['part_categories']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['part_categories']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table1.table_id=tableid;
        this.table1.subscribe("renderEvent", myrenderEvent);

		    
		    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['company']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['company']['history']['f_value']?>'};



 var tableid=2;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [ 
	    		    {key:"sku", label:"",width:10, sortable:false,hidden:true}
				  ,{key:"checkbox", label:"", width:14,sortable:false}
				  				  ,{key:"hierarchy", label:"", width:14,sortable:false}

				    ,{key:"formated_sku", label:"<?php echo _('SKU')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"description", label:"<?php echo _('Description')?>",width:300, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"used_in", label:"<?php echo _('Used In')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
		                ,{key:"move", label:"", width:60,sortable:false,className:"aleft",action:'assign',object:'category_subject'}

		                ,{key:"delete", label:"", width:60,sortable:false,className:"aleft",action:'remove',object:'category_subject'}

		];
		request="ar_edit_categories.php?tipo=parts_assigned_to_category&tableid="+tableid+"&parent=category&sf=0&parent_key="+Dom.get('category_key').value;
		
	    this.dataSource2 = new YAHOO.util.DataSource(request);
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    this.dataSource2.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 "sku","formated_sku","description","used_in","checkbox","move","subject_key","delete","hierarchy"
			 ]};
	    
	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource2, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['part_categories']['edit_parts']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<span id='paginator_info2'>{CurrentPageReport}</span>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['part_categories']['edit_parts']['order']?>",
									 dir: "<?php echo $_SESSION['state']['part_categories']['edit_parts']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
       this.table2.table_id=tableid;
        this.table2.subscribe("renderEvent", myrenderEvent);
    this.table2.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table2.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table2.subscribe("cellClickEvent", onCellClick);


	    
	    this.table2.filter={key:'<?php echo $_SESSION['state']['part_categories']['edit_parts']['f_field']?>',value:'<?php echo $_SESSION['state']['part_categories']['edit_parts']['f_value']?>'};
		




	    var tableid=3;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [ 
	    		    {key:"sku", label:"",width:10, sortable:false,hidden:true}
				  ,{key:"checkbox", label:"", width:18,sortable:false}
				    ,{key:"formated_sku", label:"<?php echo _('SKU')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"description", label:"<?php echo _('Description')?>",width:290, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"used_in", label:"<?php echo _('Used In')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
		                  ,{key:"move", label:"", width:120,hidden:(Dom.get('branch_type').value!='Head'?false:true),sortable:false,className:"aleft",action:'assign',object:'category_subject'}
		                ,{key:"move_here", label:"", width:120,hidden:(Dom.get('branch_type').value=='Head'?false:true),sortable:false,className:"aleft",action:'assign_here',object:'category_subject'}


		];
	    this.dataSource3 = new YAHOO.util.DataSource("ar_edit_categories.php?tipo=parts_no_assigned_to_category&tableid="+tableid+"&parent=category&sf=0&parent_key="+Dom.get('category_key').value);
	    this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource3.connXhrMode = "queueRequests";
	    this.dataSource3.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 "sku","formated_sku","description","used_in","checkbox","move","subject_key","move_here"
			 ]};
	    
	    this.table3 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource3, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['part_categories']['no_assigned_parts']['nr']?>,containers : 'paginator3', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<span id='paginator_info3'>{CurrentPageReport}</span>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['part_categories']['no_assigned_parts']['order']?>",
									 dir: "<?php echo $_SESSION['state']['part_categories']['no_assigned_parts']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
       this.table3.table_id=tableid;
        this.table3.subscribe("renderEvent", myrenderEvent);
    this.table3.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table3.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table3.subscribe("cellClickEvent", onCellClick);


	    
	    this.table3.filter={key:'<?php echo $_SESSION['state']['part_categories']['no_assigned_parts']['f_field']?>',value:'<?php echo $_SESSION['state']['part_categories']['no_assigned_parts']['f_value']?>'};
		

var tableid=4;
		      var tableDivEL="table"+tableid;
		      
		      var ColumnDefs = [
		      		{key:"formated_sku", label:"SKU",width:60, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"description", label:"<?php echo _('Description')?>",width:200, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			     	,{key:"used_in", label:"<?php echo _('Used In')?>",width:140, sortable:false,className:"aleft"}
			     	,{key:"status", label:"",width:70, sortable:false,className:"aleft"}
                   
					];
		    
		      
		      this.dataSource4 = new YAHOO.util.DataSource("ar_edit_categories.php?tipo=parts_no_assigned_to_category&tableid="+tableid+"&parent=category&sf=0&parent_key="+Dom.get('category_key').value);
		      
			      this.dataSource4.responseType = YAHOO.util.DataSource.TYPE_JSON;
		      this.dataSource4.connXhrMode = "queueRequests";
		      	    this.dataSource4.table_id=tableid;

		      this.dataSource4.responseSchema = {
			  resultsList: "resultset.data", 
			  metaFields: {
			  rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",

		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" 
			  },
			  
			  fields: [
				  "sku","description","used_in","status","formated_sku"
				   ]};
		      
		    this.table4 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource4
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator4', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info4'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								   
								   ,sortedBy : {
								      key: "formated_sku",
								       dir: ""
								   }
								   ,dynamicData : true
								 
							       }
							       );
		      this.table4.handleDataReturnPayload =myhandleDataReturnPayload;
		      this.table4.doBeforeSortColumn = mydoBeforeSortColumn;
		      this.table4.doBeforePaginatorChange = mydoBeforePaginatorChange;
            this.table4.table_id=tableid;
     			this.table4.subscribe("renderEvent", myrenderEvent);
     
                   this.table4.subscribe("rowMouseoverEvent", this.table4.onEventHighlightRow);
       this.table4.subscribe("rowMouseoutEvent", this.table4.onEventUnhighlightRow);
      this.table4.subscribe("rowClickEvent", select_subject_from_list);
     

                   
	    this.table4.filter={key:'used_in',value:''};




var tableid=5;
		      var tableDivEL="table"+tableid;
		      
		      var ColumnDefs = [
		      		      		{key:"key", label:"",width:10, sortable:false,hidden:true}

		      		,{key:"code", label:"<?php echo _('Code')?>",width:200, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"label", label:"<?php echo _('Label')?>",width:300, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			          
					];
		    
		      request="ar_edit_categories.php?tipo=category_heads&tableid="+tableid+'&category_subject=Part&root_category_key='+Dom.get('root_category_key').value+'&category_key='+Dom.get('category_key').value;
		     
		      this.dataSource5 = new YAHOO.util.DataSource(request);
		      
			      this.dataSource5.responseType = YAHOO.util.DataSource.TYPE_JSON;
		      this.dataSource5.connXhrMode = "queueRequests";
		      	    this.dataSource5.table_id=tableid;

		      this.dataSource5.responseSchema = {
			  resultsList: "resultset.data", 
			  metaFields: {
			  rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",

		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" 
			  },
			  
			  fields: [
				  "code","label","key"
				   ]};
		      
		    this.table5 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource5
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator5', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info5'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								   
								   ,sortedBy : {
								      key: "code",
								       dir: ""
								   }
								   ,dynamicData : true
								 
							       }
							       );
		      this.table5.handleDataReturnPayload =myhandleDataReturnPayload;
		      this.table5.doBeforeSortColumn = mydoBeforeSortColumn;
		      this.table5.doBeforePaginatorChange = mydoBeforePaginatorChange;
            this.table5.table_id=tableid;
     			this.table5.subscribe("renderEvent", myrenderEvent);
     
                   this.table5.subscribe("rowMouseoverEvent", this.table5.onEventHighlightRow);
       this.table5.subscribe("rowMouseoutEvent", this.table5.onEventUnhighlightRow);
      this.table5.subscribe("rowClickEvent", select_category_head_from_list);
     

                   
	    this.table5.filter={key:'code',value:''};



	};
    });



function check_part(sku){
checkbox=Dom.get('pna_'+sku);
if(checkbox.getAttribute('checked')==1){
checkbox.src="art/icons/checkbox_unchecked.png";
checkbox.setAttribute('checked',0)
}else{

checkbox.src="art/icons/checkbox_checked.png";
checkbox.setAttribute('checked',1)

}

}


function change_block(){
  
ids=["d_description","d_subcategory","d_parts","d_no_assigned"];

	Dom.setStyle(ids,'display','none')
	Dom.setStyle('d_'+this.id,'display','')

	Dom.removeClass(['description','subcategory','parts','no_assigned'],'selected');
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=part_categories-edit&value='+this.id ,{});
}

function init(){

 validate_scope_data={
'category':{

    'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item'
	    ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Category Code')?>'}],'name':'Category_Name'
	    ,'ar':false,'ar_request':false}
	
	,'label':{'changed':false,'validated':true,'required':true,'group':1,'type':'item'
	    ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Category Label')?>'}],'name':'Category_Label'
	    ,'ar':false,'ar_request':false}
	}

  
};
 validate_scope_metadata={'category':{'type':'edit','ar_file':'ar_edit_categories.php','key_name':'category_key','key':Dom.get('category_key').value}};




 init_search('parts');
 
    var ids = ["description","subcategory","no_assigned","parts"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
 
//    YAHOO.util.Event.addListener('add_category', "click", show_add_category_dialog);
    YAHOO.util.Event.addListener('save_edit_category', "click", save_new_category);   
    YAHOO.util.Event.addListener('reset_edit_category', "click", cancel_add_category); 

  
 
 /*   var staff_id_oACDS = new YAHOO.util.FunctionDataSource(validate_id);
    staff_id_oACDS.queryMatchContains = true;
    var staff_id_oAutoComp = new YAHOO.widget.AutoComplete("Company_Staff_Id","Company_Staff_Id_Container", staff_id_oACDS);
    staff_id_oAutoComp.minQueryLength = 0; 
    staff_id_oAutoComp.queryDelay = 0.1; */
    
     var category_name_oACDS = new YAHOO.util.FunctionDataSource(validate_name);
    category_name_oACDS.queryMatchContains = true;
    var category_name_oAutoComp = new YAHOO.widget.AutoComplete("Category_Name","Category_Name_Container", category_name_oACDS);
    category_name_oAutoComp.minQueryLength = 0; 
    category_name_oAutoComp.queryDelay = 0.1;


//  YAHOO.util.Event.addListener('add_subcategory', "click", show_add_subcategory_dialog);
    YAHOO.util.Event.addListener('save_edit_subcategory', "click", save_new_subcategory);
    YAHOO.util.Event.addListener('reset_edit_subcategory', "click", cancel_add_subcategory); 

   var subcategory_name_oACDS = new YAHOO.util.FunctionDataSource(validate_subcategory_name);
    subcategory_name_oACDS.queryMatchContains = true;
    var subcategory_name_oAutoComp = new YAHOO.widget.AutoComplete("Subcategory_Name","Subcategory_Name_Container", subcategory_name_oACDS);
    subcategory_name_oAutoComp.minQueryLength = 0; 
    subcategory_name_oAutoComp.queryDelay = 0.1; 


   

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
    
    
 YAHOO.util.Event.onContentReady("rppmenu4", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu4", {trigger:"rtext_rpp4" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
 YAHOO.util.Event.onContentReady("rppmenu5", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu5", {trigger:"rtext_rpp5" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

   
