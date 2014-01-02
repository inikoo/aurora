<?php
include_once('common.php');

?>


var category_show_options=[{label:"<?php echo _('Yes')?>", value:"Yes"}, {label:"<?php echo _('No')?>", value:"No"}];
var category_show_name={'Yes':'Yes','No':'No'};


function update_category_history_elements() {

    var ar_file = 'ar_history.php';
    var request = 'tipo=get_family_category_history_elements&parent=category&parent_key=' + Dom.get('category_key').value;
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            for (key in r.elements_number) {
                Dom.get('elements_' + key + '_number').innerHTML = r.elements_number[key]
            }
        },
        failure: function(o) {},
        scope: this
    }, request

    );
}

function post_check_change_subject(){
//  alert(number_checked_assigned_subjects);

 // if()
  
  //  Dom.setStyle(['show_families_edit_options'], 'display', '')

}

function set_checked_all_numbers_assigned_subject() {

    if (assigned_subjects_check_start_type == 'checked') {
        number_checked_assigned_subjects = this.get('paginator').getTotalRecords()
        Dom.get('number_checked_assigned_subjects').innerHTML = number_checked_assigned_subjects;
        
        show_families_edit_buttons(number_checked_assigned_subjects)
        
    }
}

function show_families_edit_buttons(assigned){

//if(assigned>0)
//Dom.setStyle(['show_families_edit_options'], 'display', '')
//else
//Dom.setStyle(['show_families_edit_options'], 'display', 'false')

}


function reset_edit_subjects_fields(){
reset_selected_families_fields();
};

YAHOO.util.Event.addListener(window, "load", function() {



    tables = new function() {
  var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"id", label:"<?php echo _('Key')?>", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				    ,{key:"go",label:'',width:20,}
				    ,{key:"code", label:"<?php echo _('Code')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'subcategory' }
					,{key:"label", label:"<?php echo _('Label')?>", width:340,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'subcategory' }
  					,{key:"delete", label:"", width:100,sortable:false,className:"aleft",action:'dialog_delete',object:'delete_category'}
				    ,{key:"delete_type", label:"",hidden:true,isTypeKey:true}
				    ,{key:"branch_type",label:'',width:80,}				   
		];


request="ar_edit_categories.php?tipo=edit_family_category_list&parent=category&parent_key="+Dom.get('category_key').value;
	//   alert(request)

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
			 'id','code','delete','delete_type','go','label','branch_type'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['family_categories']['edit_categories']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
							   ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['family_categories']['edit_categories']['order']?>",
							     dir: "<?php echo$_SESSION['state']['family_categories']['edit_categories']['order_dir']?>"
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
				       {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				      ,{key:"date", label:"<?php echo _('Date')?>",className:"aright",width:120,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"time", label:"<?php echo _('Time')?>",className:"aleft",width:50}
				      ,{key:"handle", label:"<?php echo _('Author')?>",className:"aleft",width:100,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"note", formatter:this.prepare_note,label:"<?php echo _('Notes')?>",className:"aleft",width:520}
				       ];
		request="ar_history.php?tipo=family_categories&parent=category&parent_key="+Dom.get('category_key').value+"&tableid="+tableid;
	   	    this.dataSource1 = new YAHOO.util.DataSource(request);

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
			 "key"
			 ,"date"
			 ,'time','handle','note'
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource1
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['family_categories']['history']['nr']?>,containers : 'paginator1', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['family_categories']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['family_categories']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table1.table_id=tableid;
        this.table1.subscribe("renderEvent", myrenderEvent);

		    
		    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['family_categories']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['family_categories']['history']['f_value']?>'};



 var tableid=2;
	    var tableDivEL="table"+tableid;
	    
	    
	    
	      this.checkbox_assigned = function(elLiner, oRecord, oColumn, oData) {
	          if (oData == 'wait') {
	              elLiner.innerHTML = oData
	              return;
	          }
	          if (assigned_subjects_check_start_type == 'unchecked') {
	              if (checked_assigned_subjects.indexOf(oRecord.getData("subject_key").toString()) >= 0) {
	                  elLiner.innerHTML = oRecord.getData("checkbox_checked")
	                  this.updateCell(oRecord, 'checked', 1);
	              } else {
	                  elLiner.innerHTML = oRecord.getData("checkbox_unchecked")
	              }
	          } else {
	              if (unchecked_assigned_subjects.indexOf(oRecord.getData("subject_key").toString()) >= 0) {
	                  elLiner.innerHTML = oRecord.getData("checkbox_unchecked")
	              } else {
	                  elLiner.innerHTML = oRecord.getData("checkbox_checked")
	                  this.updateCell(oRecord, 'checked', 1);
	              }
	          }
	      };

	    
	    
	    
	    var ColumnDefs = [ 
	    		    {key:"sku", label:"",width:10, sortable:false,hidden:true}
				  ,{key:"checkbox", label:"", formatter:this.checkbox_assigned,width:18,sortable:false}
				  				  ,{key:"hierarchy", label:"",hidden:(Dom.get('branch_type').value=='Head'?true:false), width:14,sortable:false}

				    ,{key:"code", label:"<?php echo _('Code')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   // ,{key:"reference", label:"<?php echo _('Reference')?>",width:100, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"name", label:"<?php echo _('Name')?>",width:300, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    //  ,{key:"used_in", label:"<?php echo _('Used In')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
		                ,{key:"move", label:"", width:60,sortable:false,className:"aleft",action:'assign',object:'category_subject'}

		                ,{key:"delete", label:"", width:60,sortable:false,className:"aleft",action:'remove',object:'category_subject'}

		];
		request="ar_edit_categories.php?tipo=families_assigned_to_category&tableid="+tableid+"&parent=category&sf=0&parent_key="+Dom.get('category_key').value;
		
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
			 "code","name","subject_key","checkbox","move","subject_key","delete","hierarchy","checkbox_checked","checkbox_unchecked","checked"
			 ]};
	    
	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource2, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['family_categories']['edit_families']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<span id='paginator_info2'>{CurrentPageReport}</span>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['family_categories']['edit_families']['order']?>",
									 dir: "<?php echo $_SESSION['state']['family_categories']['edit_families']['order_dir']?>"
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
        this.table2.subscribe("renderEvent", set_checked_all_numbers_assigned_subject);


	    
	    this.table2.filter={key:'<?php echo $_SESSION['state']['family_categories']['edit_families']['f_field']?>',value:'<?php echo $_SESSION['state']['family_categories']['edit_families']['f_value']?>'};
		




	    var tableid=3;
	    var tableDivEL="table"+tableid;
	    
	    
	     this.checkbox_no_assigned = function(elLiner, oRecord, oColumn, oData) {
        	if(no_assigned_subjects_check_start_type=='unchecked'){     
		   		if(checked_no_assigned_subjects.indexOf(  oRecord.getData("subject_key").toString())>=0){
		   			elLiner.innerHTML =oRecord.getData("checkbox_checked")
				}else{
					elLiner.innerHTML = oRecord.getData("checkbox_unchecked")
				}
			}
			else{
				if(unchecked_no_assigned_subjects.indexOf(  oRecord.getData("subject_key").toString())>=0){
		   			elLiner.innerHTML =oRecord.getData("checkbox_unchecked")
				}else{
					elLiner.innerHTML = oRecord.getData("checkbox_checked")
				}
			}
	    };
	    
	    
	    var ColumnDefs = [ 
	    		    {key:"key", label:"",width:10, sortable:false,hidden:true}
				  ,{key:"checkbox", label:"", formatter:this.checkbox_no_assigned,width:18,sortable:false}
				    ,{key:"code", label:"<?php echo _('Code')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>",width:290, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    //  ,{key:"used_in", label:"<?php echo _('Used In')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
		               ,{key:"move", label:"", width:120,hidden:(Dom.get('branch_type').value!='Head'?false:true),sortable:false,className:"aleft",action:'assign',object:'category_subject'}
		             ,{key:"move_here", label:"", width:120,hidden:(Dom.get('branch_type').value=='Head'?false:true),sortable:false,className:"aleft",action:'assign_here',object:'category_subject'}


		];
		
		request="ar_edit_categories.php?tipo=families_no_assigned_to_category&tableid="+tableid+"&parent=category&sf=0&parent_key="+Dom.get('category_key').value
		//alert(request)
	    this.dataSource3 = new YAHOO.util.DataSource(request);
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
			 "key","subject_key","code","name","used_in","checkbox","move","subject_key","move_here","checkbox_checked","checkbox_unchecked","checked"
			 ]};
	    
	    this.table3 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource3, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['family_categories']['no_assigned_families']['nr']?>,containers : 'paginator3', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<span id='paginator_info3'>{CurrentPageReport}</span>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['family_categories']['no_assigned_families']['order']?>",
									 dir: "<?php echo $_SESSION['state']['family_categories']['no_assigned_families']['order_dir']?>"
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
        this.table3.subscribe("renderEvent", set_checked_all_numbers_no_assigned_subject);


	    
	    this.table3.filter={key:'<?php echo $_SESSION['state']['family_categories']['no_assigned_families']['f_field']?>',value:'<?php echo $_SESSION['state']['family_categories']['no_assigned_families']['f_value']?>'};
		

var tableid=4;
		      var tableDivEL="table"+tableid;
		      
		      var ColumnDefs = [
		      		{key:"formated_sku", label:"SKU",width:60, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"description", label:"<?php echo _('Description')?>",width:200, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			     	,{key:"used_in", label:"<?php echo _('Used In')?>",width:140, sortable:false,className:"aleft"}
			     	,{key:"status", label:"",width:70, sortable:false,className:"aleft"}
                   
					];
		    
		      request="ar_edit_categories.php?tipo=families_no_assigned_to_category&tableid="+tableid+"&parent=category&sf=0&parent_key="+Dom.get('category_key').value;
		      this.dataSource4 = new YAHOO.util.DataSource(request);
		      
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
		    
		      request="ar_edit_categories.php?tipo=category_heads&tableid="+tableid+'&category_subject=Family&root_category_key='+Dom.get('root_category_key').value+'&category_key='+Dom.get('category_key').value;
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






function change_block(){
  
ids=["d_description","d_subcategory","d_families","d_no_assigned"];

	Dom.setStyle(ids,'display','none')
	Dom.setStyle('d_'+this.id,'display','')

	Dom.removeClass(['description','subcategory','families','no_assigned'],'selected');
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=family_categories-edit&value='+this.id ,{});
}

function change_assigned_subjects_view(e, table_id) {
    var tipo = this.getAttribute('name');
    var table = tables['table' + table_id];

    Dom.removeClass(['assigned_subjects_view_category', 'assigned_subjects_view_state', 'assigned_subjects_view_name', 'assigned_subjects_view_weight'], 'selected')

    Dom.addClass(this, 'selected')

    table.hideColumn('description');
    table.hideColumn('supplied_by');
    table.hideColumn('stock');
    table.hideColumn('stock_value');
    table.hideColumn('avg_stock');
    table.hideColumn('avg_stockvalue');
    table.hideColumn('keep_days');
    table.hideColumn('outstock_days');
    table.hideColumn('unknown_days');
    table.hideColumn('gmroi');
    table.hideColumn('sold');
    table.hideColumn('money_in');
    table.hideColumn('delta_sold');
    table.hideColumn('delta_money_in');
    table.hideColumn('profit_sold');
    table.hideColumn('margin');
    table.hideColumn('locations');
    table.hideColumn('used_in');
    table.hideColumn('stock_days');
    table.hideColumn('stock_state');
    table.hideColumn('reference');

    table.hideColumn('description_small');

    if (tipo == 'general') {
        // Dom.setStyle(['family_period_options','avg_options'],'display','none')
        Dom.setStyle(['family_period_options'], 'display', 'none')

        table.showColumn('description');
        table.showColumn('reference');
        table.showColumn('used_in');

    } else if (tipo == 'stock') {
        table.showColumn('description_small');

        //Dom.setStyle(['family_period_options','avg_options'],'display','none')
        Dom.setStyle(['family_period_options'], 'display', 'none')


        table.showColumn('stock');
        table.showColumn('stock_value');

        table.showColumn('stock_days');
        table.showColumn('stock_state');

        //   table.showColumn('avg_stock');
        //  table.showColumn('avg_stockvalue');
        //  table.showColumn('keep_days');
        //  table.showColumn('outstock_days');
        //  table.showColumn('unknown_days');
    } else if (tipo == 'sales') {
        table.showColumn('description_small');

        //Dom.setStyle(['family_period_options','avg_options'],'display','')
        Dom.setStyle(['family_period_options'], 'display', '')

        table.showColumn('sold');
        table.showColumn('money_in');
        table.showColumn('delta_sold');
        table.showColumn('delta_money_in');

    } else if (tipo == 'locations') {

        table.showColumn('description_small');

        // Dom.setStyle(['family_period_options','avg_options'],'display','')
        Dom.setStyle(['family_period_options'], 'display', 'none')
        table.showColumn('locations');
        table.showColumn('stock');
        table.showColumn('stock_value');

    } else if (tipo == 'forecast') {
        table.showColumn('description_small');
        table.showColumn('profit_sold');
        table.showColumn('margin');
        // Dom.setStyle(['family_period_options','avg_options'],'display','')
        Dom.setStyle(['family_period_options'], 'display', '')
        table.showColumn('gmroi');


    }
    change_families_view_save(tipo)
}



function init() {

    init_search('families');

    var ids = ["description", "subcategory", "no_assigned", "families"];
    YAHOO.util.Event.addListener(ids, "click", change_block);

    var ids = ["assigned_subjects_view_category", "assigned_subjects_view_state", "assigned_subjects_view_name", "assigned_subjects_view_weight"];
    YAHOO.util.Event.addListener(ids, "click", change_assigned_subjects_view,2);

}

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("filtermenu0", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {
        trigger: "filter_name0"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);

});


YAHOO.util.Event.onContentReady("rppmenu0", function() {
    rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {
        trigger: "rtext_rpp0"
    });
    rppmenu.render();
    rppmenu.subscribe("show", rppmenu.focus);
});


YAHOO.util.Event.onContentReady("filtermenu2", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {
        trigger: "filter_name2"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);

});


YAHOO.util.Event.onContentReady("rppmenu2", function() {
    rppmenu = new YAHOO.widget.ContextMenu("rppmenu2", {
        trigger: "rtext_rpp2"
    });
    rppmenu.render();
    rppmenu.subscribe("show", rppmenu.focus);
});


YAHOO.util.Event.onContentReady("filtermenu3", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu3", {
        trigger: "filter_name3"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);

});


YAHOO.util.Event.onContentReady("rppmenu3", function() {
    rppmenu = new YAHOO.widget.ContextMenu("rppmenu3", {
        trigger: "rtext_rpp3"
    });
    rppmenu.render();
    rppmenu.subscribe("show", rppmenu.focus);
});



YAHOO.util.Event.onContentReady("rppmenu4", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu4", {
        trigger: "rtext_rpp4"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
});
YAHOO.util.Event.onContentReady("rppmenu5", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu5", {
        trigger: "rtext_rpp5"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
});
