<?php  include_once('common.php');

$page_key=$_REQUEST['page_key'];
$site_key=$_REQUEST['site_key'];

?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var select_page_operation=false;
var dialog_upload_page_content;
var dialog_upload_page_content_files;



var CellEdit = function (callback, newValue) {


		var record = this.getRecord(),
		column = this.getColumn(),
		oldValue = this.value,
		datatable = this.getDataTable(),
		recordIndex = datatable.getRecordIndex(record);


						
		//	if(column.object=='family_page_properties')	
		//			request_page=	'ar_edit_sites.php';			
		//	else
		request_page=	'ar_edit_sites.php';			
						
						
						
		YAHOO.util.Connect.asyncRequest(
						'POST',
						request_page, {
						    success:function(o) {
							//	alert(o.responseText);
							var r = YAHOO.lang.JSON.parse(o.responseText);
							if (r.state == 200) {

							 if(column.key=='description'  ){
								 datatable.updateCell(record,'description_formated',r.newdata['description_formated']);
								callback(true, r.newvalue);
								
							    } else if(column.key=='order'  ){
								 datatable.updateCell(record,'order_formated',r.newdata['order_formated']);
								callback(true, r.newvalue);
								
							    }else{
							
								callback(true, r.newvalue);
								
							    }
							} else {
							    alert(r.msg);
							    callback();
							}
						    },
							failure:function(o) {
							alert(o.statusText);
							callback();
						    },
							scope:this
							},
						'tipo=edit_'+column.object+'&key=' + column.key + '&newvalue=' + 
						encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ 
						myBuildUrl(datatable,record)
						
						);  
 };










function save_see_also_type(value){

var request='ar_edit_sites.php?tipo=edit_page_header&newvalue='+value+"&id="+Dom.get('page_key').value+'&key=Page Store See Also Type&okey='


//var request='ar_edit_contacts.php?tipo=edit_customer&key=' + key+ '&newvalue=' + value +'&customer_key=' + customer_id
//	alert(request);
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
//alert(o.responseText)
				var r =  YAHOO.lang.JSON.parse(o.responseText);
				
				if(r.state==200){
			
  
 
            if (r.newvalue=='Auto' || r.newvalue=='Manual') {
                           Dom.removeClass(['see_also_type_Auto','see_also_type_Manual'],'selected');

               Dom.addClass('see_also_type_'+r.newvalue,'selected');
                          location.href='edit_page.php?id='+r.page_key+'&content_view=header';
            }else{
                alert(r.msg)
            }
            }
        
    }
    });

}




function delete_found_in_page( page_key ) {
	
	var request='ar_edit_sites.php?tipo=delete_found_in_page&id=' + Dom.get('page_key').value +'&found_in_key='+page_key
	           
	          
//alert(request);
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
	            success:function(o){
	      //     alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
        location.href='edit_page.php?id='+r.page_key+'&content_view=header';;
                                  }else{
                                  
                                  
                                  
                                  }
   			}
    });

	
}

function change_number_auto_see_also(e,operation){
	var request='ar_edit_sites.php?tipo=update_see_also_quantity&id='+Dom.get('page_key').value +'&operation='+operation
	YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o){
	                            alert(o.responseText);	
			                    var r =  YAHOO.lang.JSON.parse(o.responseText);
			                    if(r.state==200){
			                    update_page_snapshot();
                                    location.href='edit_page.php?id='+r.page_key+'&content_view=header';;
                                }else{
                                
                                }
   			}
    });
}

function delete_see_also_page( page_key ) {
	var request='ar_edit_sites.php?tipo=delete_see_also_page&id='+Dom.get('page_key').value +'&see_also_key='+page_key
	YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o){
	                            //alert(o.responseText);	
			                    var r =  YAHOO.lang.JSON.parse(o.responseText);
			                    if(r.state==200){
                                    location.href='edit_page.php?id='+r.page_key+'&content_view=header';;
                                }else{
                                
                                }
   			}
    });
}



function show_dialog_page_list(e,type){

switch ( type ) {
	case 'found_in':
		region1 = Dom.getRegion('add_other_found_in_page'); 
    region2 = Dom.getRegion('dialog_page_list'); 
 var pos =[region1.right-region2.width,region1.bottom+2]
    Dom.setXY('dialog_page_list', pos);
		break;
	
	case 'see_also':
	region1 = Dom.getRegion('add_other_see_also_page'); 
    region2 = Dom.getRegion('dialog_page_list'); 
 var pos =[region1.right-region2.width,region1.bottom+2]
    Dom.setXY('dialog_page_list', pos);
	break;
	
	
}


select_page_operation=type;
dialog_page_list.show();
}

function select_page(oArgs){
if(select_page_operation=='found_in'){
select_found_in_page(oArgs)
}else if(select_page_operation=='see_also'){
select_see_also_page(oArgs)
}else{
 dialog_page_list.hide();
}

}



function select_see_also_page(oArgs){


see_also_key=tables.table7.getRecord(oArgs.target).getData('key');

 dialog_page_list.hide();


	var request = 'ar_edit_sites.php?tipo=add_see_also_page&id=' + Dom.get('page_key').value + '&see_also_key=' + see_also_key;
	 //alert(request);

	YAHOO.util.Connect.asyncRequest('POST', request, {
		success: function(o) {
			//salert(o.responseText);
			var r = YAHOO.lang.JSON.parse(o.responseText);
			if (r.state == 200) {
				
                    location.href='edit_page.php?id='+r.page_key+'&content_view=header';;
			} else {


				}	
		}		
	});
 

}


function select_found_in_page(oArgs){


found_in_key=tables.table7.getRecord(oArgs.target).getData('key');

 dialog_page_list.hide();


	var request = 'ar_edit_sites.php?tipo=add_found_in_page&id=' + Dom.get('page_key').value + '&found_in_key=' + found_in_key;
	 

	YAHOO.util.Connect.asyncRequest('POST', request, {
		success: function(o) {
			//alert(o.responseText);
			var r = YAHOO.lang.JSON.parse(o.responseText);
			if (r.state == 200) {
				
                    location.href='edit_page.php?id='+r.page_key+'&content_view=header';;
			} else {


				}	
		}		
	});
 

}


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

   
   		
 var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"date",label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author",label:"<?php echo _('Author')?>", width:70,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					       ,{key:"abstract", label:"<?php echo _('Description')?>", width:370,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	    
	    this.dataSource1 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=page&tableid=1");
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
								 rowsPerPage    : <?php echo$_SESSION['state']['page']['history']['nr']?>,containers : 'paginator1', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['page']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['page']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		       this.table1.table_id=tableid;
     this.table1.subscribe("renderEvent", myrenderEvent);

		    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['page']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['page']['history']['f_value']?>'};


   
   

   var tableid=7; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"key", label:"", hidden:true,action:"none",isPrimaryKey:true}
				     
				       ,{key:"code",label:"<?php echo _('Code')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    		,{key:"type",label:"<?php echo _('Type')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}
  
				      ,{key:"store_title",label:"<?php echo _('Header Title')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}
				    			        
				       ];
				       
	 
				       
				       
	    
	        this.dataSource7 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=page_list&site_key="+Dom.get('site_key').value+"&sf=0&tableid=7");

//alert("ar_edit_sites.php?tipo=family_page_list&site_key="+Dom.get('site_key').value+"&parent=family&parent_key="+Dom.get('family_key').value+"&tableid=7")
	    this.dataSource7.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource7.connXhrMode = "queueRequests";
	    this.dataSource7.responseSchema = {
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
			 "key",
			 "code","store_title","type"

			 ]};

        this.table7 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource7
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    :20 ,containers : 'paginator7', alpartysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info7'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							   key: "code",
									 dir: ""
							 },
							 dynamicData : true
						     }
						     );
	    
	      this.table7.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table7.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table7.subscribe("cellClickEvent", this.table7.onEventShowCellEditor);

 this.table7.subscribe("rowMouseoverEvent", this.table7.onEventHighlightRow);
       this.table7.subscribe("rowMouseoutEvent", this.table7.onEventUnhighlightRow);
      this.table7.subscribe("rowClickEvent", select_page);
        this.table7.table_id=tableid;
           this.table7.subscribe("renderEvent", myrenderEvent);


	    this.table7.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table7.filter={key:'code',value:''};
	    
	    
	    
	    
	    
  var tableid=2; 
	    var tableDivEL="table"+tableid;


function formater_description  (el, oRecord, oColumn, oData) {
		
		 el.innerHTML = oRecord.getData("description_formated");
	    }
function formater_order  (el, oRecord, oColumn, oData) {
		
		 el.innerHTML = oRecord.getData("order_formated");
	    }

	    var CustomersColumnDefs = [
				       {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}
				       				       ,{key:"description_formated", label:"", hidden:true}
				       				       ,{key:"order_formated", label:"", hidden:true}

				     //   ,{key:"go", label:"", width:25,action:"none"}
				        ,{key:"code",label:"<?php echo _('Name')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					    ,{key:"type",label:"<?php echo _('Type')?>", width:60,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					    ,{key:"description",label:"<?php echo _('Description')?>", formatter: formater_description ,width:140,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},object:'page_product_list'
					      ,editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:[
				    {'value':"Units Name",'label':"<?php echo _('<i>units</i> x name')?><br/>"},
				   {'value':"Units Special Characteristic",'label':"<?php echo _('<i>units</i> x <i>description</i>')?><br/>"},
				     {'value':"Units Name RRP",'label':"<?php echo _('<i>units</i> x name RRP')?><br/>"},
				   {'value':"Units Special Characteristic RRP",'label':"<?php echo _('<i>units</i> x <i>description</i> RRP')?><br/>"},

				    ],disableBtns:true})
					    
					    }
					    ,{key:"order",label:"<?php echo _('Order')?>", formatter: formater_order , width:100,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},object:'page_product_list'
					    ,editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:[
				    {'value':"Code",'label':"<?php echo _('Code')?><br/>"},
				   {'value':"Name",'label':"<?php echo _('Name')?><br/>"},
				    {'value':"Special Characteristic",'label':"<?php echo _('Description')?><br/>"},
				     {'value':"Price",'label':"<?php echo _('Price')?><br/>"},
				      {'value':"RRP",'label':"<?php echo _('RRP')?><br/>"},
				      	{'value':"Sales",'label':"<?php echo _('Sales')?><br/>"},
				      {'value':"Date",'label':"<?php echo _('Date')?><br/>"},

				    ],disableBtns:true})}
				   
					    
					    
					    ,{key:"range",label:"<?php echo _('Range')?>", width:100,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'page_product_list'}
					    ,{key:"max",label:"<?php echo _('Limit')?>", width:100,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'page_product_list'}

					    
					    ];
				       
	 
	    var request="ar_edit_sites.php?tipo=page_product_lists&parent=page&parent_key="+Dom.get('page_key').value+"&tableid=2";
	   // alert(request)
	        this.dataSource2 = new YAHOO.util.DataSource(request);

	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    this.dataSource2.responseSchema = {
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
			 ,"go","code","products","type","description","order","range","max","description_formated","order_formated"

			 ]};

        this.table2 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource2
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    :<?php echo $_SESSION['state']['page']['edit_product_list']['nr']?> ,containers : 'paginator2', alpartysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo $_SESSION['state']['page']['edit_product_list']['order']?>",
							     dir: "<?php echo $_SESSION['state']['page']['edit_product_list']['order_dir']?>"
							 },
							 dynamicData : true
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
		    
	    this.table2.filter={key:'<?php echo $_SESSION['state']['page']['edit_product_list']['f_field']?>',value:'<?php echo $_SESSION['state']['page']['edit_product_list']['f_value']?>'};


	    
	      var tableid=3; 
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}
				        ,{key:"go", label:"", width:40,action:"none",className:"aleft"}
				        ,{key:"code",label:"<?php echo _('Code')?>", width:700,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}
					       ];
				       
	 
	    var request="ar_edit_sites.php?tipo=page_product_buttons&parent=page&parent_key="+Dom.get('page_key').value+"&tableid=3";
	   // alert(request)
	        this.dataSource3 = new YAHOO.util.DataSource(request);

	    this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource3.connXhrMode = "queueRequests";
	    this.dataSource3.responseSchema = {
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
			 ,"go","code"

			 ]};

        this.table3 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource3
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    :<?php echo $_SESSION['state']['page']['edit_product_list']['nr']?> ,containers : 'paginator3', alpartysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo $_SESSION['state']['page']['edit_product_list']['order']?>",
							     dir: "<?php echo $_SESSION['state']['page']['edit_product_list']['order_dir']?>"
							 },
							 dynamicData : true
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
		    
	    this.table3.filter={key:'<?php echo $_SESSION['state']['page']['edit_product_list']['f_field']?>',value:'<?php echo $_SESSION['state']['page']['edit_product_list']['f_value']?>'};


	    
	    
	    
	};
    });



function change_block(){
  var ids = ['properties','page_header','page_footer','content','style','media','setup','products']; 
block_ids=['d_properties','d_page_header','d_page_footer','d_content','d_style','d_media','d_setup','d_products'];


if(this.id=='content'){
Dom.setStyle('tabbed_container','margin','0px 0px')
}else{
Dom.setStyle('tabbed_container','margin','0px 20px')

}

Dom.setStyle(block_ids,'display','none');
Dom.setStyle('d_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=page-editing&value='+this.id ,{});
}


function reset_edit_page_header(){ reset_edit_general('page_header');}
function save_edit_page_header(){save_edit_general('page_header');}
function reset_edit_page_html_head(){ reset_edit_general('page_html_head');}
function save_edit_page_html_head(){save_edit_general('page_html_head');}
function reset_edit_page_content(){ reset_edit_general('page_content');}
function save_edit_page_content(){


EmailHTMLEditor.saveHTML();



save_edit_general('page_content');
}
function reset_edit_page_properties(){ reset_edit_general('page_properties');}
function save_edit_page_properties(){save_edit_general('page_properties');}

function validate_page_content_presentation_template_data(query){validate_general('page_content','presentation_template_data',unescape(query));}
function validate_page_header_store_title(query){validate_general('page_header','store_title',unescape(query));}

function validate_page_html_head_resume(query){validate_general('page_properties','resume',unescape(query));}
function validate_page_properties_url(query){validate_general('page_properties','url',unescape(query));}
function validate_page_properties_link_title(query){validate_general('page_properties','link_title',unescape(query));}
function validate_page_properties_page_code(query){validate_general('page_properties','page_code',unescape(query));}
function validate_page_html_head_title(query){validate_general('page_properties','title',unescape(query));}
function validate_page_html_head_keywords(query){validate_general('page_properties','keywords',unescape(query));}

function html_editor_changed(){
    validate_scope_data['page_content']['source']['changed']=true;
  
    
    validate_scope('page_content');
}

function show_dialog_upload_page_content(){

Dom.setStyle('processing_upload_page_content','display','none')
Dom.setStyle(['upload_page_content','cancel_upload_page_content'],'display','')


region1 = Dom.getRegion('show_upload_page_content'); 
    region2 = Dom.getRegion('dialog_upload_page_content'); 
 var pos =[region1.right-region2.width,region1.bottom+2]
    Dom.setXY('dialog_upload_page_content', pos);

dialog_upload_page_content.show()

}
function close_upload_page_content(){

Dom.get('upload_page_content_use_file').value='';

dialog_upload_page_content.hide();
}


function cancel_upload_page_content_files(){
Dom.get('upload_page_content_use_file').value='';

dialog_upload_page_content_files.hide();
}
function upload_page_content_file(file){
Dom.get('upload_page_content_use_file').value=file;
upload_page_content();
}
function upload_page_content(){



//Dom.setStyle('processing_upload_page_content','display','')
//Dom.setStyle(['upload_page_content','cancel_upload_page_content'],'display','none')

    YAHOO.util.Connect.setForm('upload_page_content_form', true,true);
    var request='ar_upload_page_content.php?tipo=upload_page_content';
   var uploadHandler = {
      upload: function(o) {
 alert(o.responseText)
	
	var r =  YAHOO.lang.JSON.parse(o.responseText);
	   
	    if(r.state==200){
	     
	     update_page_snapshot_and_reload(r.page_key)
	     
      return;
                
	    }else if(r.state==201){
	        dialog_upload_page_content.hide();
	        region1 = Dom.getRegion('show_upload_page_content'); 
            region2 = Dom.getRegion('dialog_upload_page_content_files'); 
            var pos =[region1.right-region2.width,region1.bottom+2]
            Dom.setXY('dialog_upload_page_content_files', pos);
	        dialog_upload_page_content_files.show();
	        buttons='';
	        for(var i=0; i<r.list.length; i++) {
                buttons=buttons+"<button onClick='upload_page_content_file(\""+r.list[i]+"\")' style='margin-top:0px;margin-bottom:10px' >"+r.list[i]+"</button> ";
            }
	        Dom.get('upload_page_content_files').innerHTML=buttons
        }else
		alert(r.msg);
	    
	    

	}
    };

    YAHOO.util.Connect.asyncRequest('POST',request, uploadHandler);



  };
  
  function update_page_snapshot_and_reload(page_key){
  alert('ar_edit_sites.php?tipo=update_page_snapshot&id='+page_key)
  YAHOO.util.Connect.asyncRequest('POST','ar_edit_sites.php?tipo=update_page_snapshot&id='+page_key,{
  success: function(o) {
  alert(o.responseText)
  // window.location.reload()
  }
  });
  
  }
  
    function update_page_snapshot(){
  YAHOO.util.Connect.asyncRequest('POST','ar_edit_sites.php?tipo=update_page_snapshot&id='+Dom.get('page_key').value,{
  success: function(o) {
   var r = YAHOO.lang.JSON.parse(o.responseText);
   Dom.get('page_preview_snapshot_image').src='image.php?id='+r.image_key
   
  }
  });
  
  }
  
  
  function post_item_updated_actions(branch,r){
  switch ( branch ) {
  	case 'page_header':
  		
  		update_page_snapshot();
  		
  		break;
  	
 
  }

  
  }

function show_more_configuration(){
Dom.setStyle('advanced_configuration','display','')
}
function hide_more_configuration(){
Dom.setStyle('advanced_configuration','display','none')

}


function change_content_block(e,block){

Dom.setStyle('show_page_content_overview_block','display','')
Dom.removeClass(['show_page_header_block','show_page_content_block','show_page_product_list_block','show_page_product_buttons_block','show_page_footer_block'],'selected')
Dom.addClass('show_'+block,'selected')
    Dom.setStyle(['page_header_block','page_content_block','page_product_list_block','page_product_buttons_block','page_footer_block','page_content_overview_block'],'display','none')
    Dom.setStyle(block,'display','')
}

function show_page_content_overview_block(){

Dom.setStyle('show_page_content_overview_block','display','none')
Dom.setStyle('page_content_overview_block','display','')

Dom.removeClass(['show_page_header_block','show_page_content_block','show_page_product_list_block','show_page_product_buttons_block','show_page_footer_block'],'selected')
    Dom.setStyle(['page_header_block','page_content_block','page_product_list_block','page_product_buttons_block','page_footer_block'],'display','none')
  
}

function init(){


 
   Event.addListener('add_auto_see_also_page', "click", change_number_auto_see_also,'add');
   Event.addListener('remove_auto_see_also_page', "click", change_number_auto_see_also,'remove');


  Event.addListener('show_page_header_block', "click", change_content_block,'page_header_block');
  Event.addListener('show_page_content_block', "click", change_content_block,'page_content_block');
  Event.addListener('show_page_product_list_block', "click", change_content_block,'page_product_list_block');
  Event.addListener('show_page_product_buttons_block', "click", change_content_block,'page_product_buttons_block');
  Event.addListener('show_page_footer_block', "click", change_content_block,'page_footer_block');

  Event.addListener('show_page_content_overview_block', "click", show_page_content_overview_block);



  Event.addListener('show_more_configuration', "click", show_more_configuration);
  Event.addListener('hide_more_configuration', "click", hide_more_configuration);


 

  Event.addListener('show_upload_page_content', "click", show_dialog_upload_page_content);
Event.addListener("cancel_upload_page_content", "click", close_upload_page_content);
  Event.addListener('upload_page_content', "click", upload_page_content);
 dialog_upload_page_content = new YAHOO.widget.Dialog("dialog_upload_page_content", {visible : false,close:true,underlay: "none",draggable:false});
    dialog_upload_page_content.render();

 Event.addListener('cancel_upload_page_content_files', "click", cancel_upload_page_content_files);
 dialog_upload_page_content_files = new YAHOO.widget.Dialog("dialog_upload_page_content_files", {visible : false,close:true,underlay: "none",draggable:false});
    dialog_upload_page_content_files.render();



dialog_page_list = new YAHOO.widget.Dialog("dialog_page_list", { visible : false,close:true,underlay: "none",draggable:false});
    dialog_page_list.render();
	

     Event.addListener("add_other_found_in_page", "click", show_dialog_page_list,'found_in', true);
  Event.addListener("add_other_see_also_page", "click", show_dialog_page_list,'see_also' , true);
  


  init_search('site');

    var ids = ['properties','page_header','page_footer','content','style','media','setup','products']; 
    Event.addListener(ids, "click", change_block);

 validate_scope_metadata={
        'page_properties':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'id','key':Dom.get('page_key').value}
        ,'page_html_head':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'id','key':Dom.get('page_key').value}
        ,'page_header':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'id','key':Dom.get('page_key').value}
        ,'page_content':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'id','key':Dom.get('page_key').value}
    };


 validate_scope_data={
 
 
    'page_properties':{
	'url':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid URL')?>'}]
		 ,'name':'page_properties_url','ar':false
		 
	},
	'page_code':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Code')?>'}]
		 ,'name':'page_properties_page_code'
		 ,'ar':'find','ar_request':'ar_sites.php?tipo=is_page_store_code&site_key='+Dom.get('site_key').value+'&query='
	},
	'link_title':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Link Title')?>'}]
		 ,'name':'page_properties_link_title','ar':false
		 
	},
		'title':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Title')?>'}]
		 ,'name':'page_html_head_title','ar':false
		 
	},
	'keywords':{
	'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'page_html_head_keywords','ar':false},
	'resume':{
		'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'page_html_head_resume','ar':false}
	
    }
     ,'page_html_head':{



    }
,'page_header':{
		'store_title':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Title')?>'}]
		 ,'name':'page_header_store_title','ar':false
		 
	}

	
    }
,'page_content':{
	
	'source':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'html_editor','dbname':'Page Store Source','ar':false}
	
    }


};



 YAHOO.util.Event.addListener('reset_edit_page_html_head', "click", reset_edit_page_html_head);
    YAHOO.util.Event.addListener('save_edit_page_html_head', "click", save_edit_page_html_head);

 YAHOO.util.Event.addListener('reset_edit_page_header', "click", reset_edit_page_header);
    YAHOO.util.Event.addListener('save_edit_page_header', "click", save_edit_page_header);

YAHOO.util.Event.addListener('reset_edit_page_content', "click", reset_edit_page_content);
    YAHOO.util.Event.addListener('save_edit_page_content', "click", save_edit_page_content);
    
    YAHOO.util.Event.addListener('reset_edit_page_properties', "click", reset_edit_page_properties);
    YAHOO.util.Event.addListener('save_edit_page_properties', "click", save_edit_page_properties);



 var page_properties_page_code_oACDS = new YAHOO.util.FunctionDataSource(validate_page_properties_page_code);
    page_properties_page_code_oACDS.queryMatchContains = true;
    var page_properties_page_code_oAutoComp = new YAHOO.widget.AutoComplete("page_properties_page_code","page_properties_page_code_Container", page_properties_page_code_oACDS);
    page_properties_page_code_oAutoComp.minQueryLength = 0; 
    page_properties_page_code_oAutoComp.queryDelay = 0.1;


 var page_properties_url_oACDS = new YAHOO.util.FunctionDataSource(validate_page_properties_url);
    page_properties_url_oACDS.queryMatchContains = true;
    var page_properties_url_oAutoComp = new YAHOO.widget.AutoComplete("page_properties_url","page_properties_url_Container", page_properties_url_oACDS);
    page_properties_url_oAutoComp.minQueryLength = 0; 
    page_properties_url_oAutoComp.queryDelay = 0.1;
    
    
     var page_properties_link_title_oACDS = new YAHOO.util.FunctionDataSource(validate_page_properties_link_title);
    page_properties_link_title_oACDS.queryMatchContains = true;
    var page_properties_link_title_oAutoComp = new YAHOO.widget.AutoComplete("page_properties_link_title","page_properties_link_title_Container", page_properties_link_title_oACDS);
    page_properties_link_title_oAutoComp.minQueryLength = 0; 
    page_properties_link_title_oAutoComp.queryDelay = 0.1;
    
    var page_html_head_title_oACDS = new YAHOO.util.FunctionDataSource(validate_page_html_head_title);
    page_html_head_title_oACDS.queryMatchContains = true;
    var page_html_head_title_oAutoComp = new YAHOO.widget.AutoComplete("page_html_head_title","page_html_head_title_Container", page_html_head_title_oACDS);
    page_html_head_title_oAutoComp.minQueryLength = 0; 
    page_html_head_title_oAutoComp.queryDelay = 0.1;

    var page_html_head_keywords_oACDS = new YAHOO.util.FunctionDataSource(validate_page_html_head_keywords);
    page_html_head_keywords_oACDS.queryMatchContains = true;
    var page_html_head_keywords_oAutoComp = new YAHOO.widget.AutoComplete("page_html_head_keywords","page_html_head_keywords_Container", page_html_head_keywords_oACDS);
    page_html_head_keywords_oAutoComp.minQueryLength = 0; 
    page_html_head_keywords_oAutoComp.queryDelay = 0.1;


 var page_header_store_title_oACDS = new YAHOO.util.FunctionDataSource(validate_page_header_store_title);
    page_header_store_title_oACDS.queryMatchContains = true;
    var page_header_store_title_oAutoComp = new YAHOO.widget.AutoComplete("page_header_store_title","page_header_store_title_Container", page_header_store_title_oACDS);
    page_header_store_title_oAutoComp.minQueryLength = 0; 
    page_header_store_title_oAutoComp.queryDelay = 0.1;
    


    var page_html_head_resume_oACDS = new YAHOO.util.FunctionDataSource(validate_page_html_head_resume);
    page_html_head_resume_oACDS.queryMatchContains = true;
    var page_html_head_resume_oAutoComp = new YAHOO.widget.AutoComplete("page_html_head_resume","page_html_head_resume_Container", page_html_head_resume_oACDS);
    page_html_head_resume_oAutoComp.minQueryLength = 0; 
    page_html_head_resume_oAutoComp.queryDelay = 0.1;
  
  
  

  
  var oACDS7 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS7.queryMatchContains = true;
 oACDS7.table_id=7;
 var oAutoComp7 = new YAHOO.widget.AutoComplete("f_input7","f_container7", oACDS7);
 oAutoComp7.minQueryLength = 0; 
    
    YAHOO.util.Event.addListener('clean_table_filter_show7', "click",show_filter,7);
 YAHOO.util.Event.addListener('clean_table_filter_hide7', "click",hide_filter,7);


    
       var myConfig = {
        height: Dom.get('content_height').value+'px',
        width: '972px',
        animate: true,
        dompath: true,
        focusAtStart: true,
         autoHeight: true
    };
    
    var state = 'off';
    

    
        EmailHTMLEditor = new YAHOO.widget.Editor('html_editor', myConfig);
   
   
   EmailHTMLEditor.on('toolbarLoaded', function() {
    
        var codeConfig = {
            type: 'push', label: 'Edit HTML Code', value: 'editcode'
        };
        this.toolbar.addButtonToGroup(codeConfig, 'insertitem');
        
        this.toolbar.on('editcodeClick', function() {
        

        
            var ta = this.get('element'),iframe = this.get('iframe').get('element');

            if (state == 'on') {
                state = 'off';
                this.toolbar.set('disabled', false);
                          this.setEditorHTML(ta.value);
                if (!this.browser.ie) {
                    this._setDesignMode('on');
                }

                Dom.removeClass(iframe, 'editor-hidden');
                Dom.addClass(ta, 'editor-hidden');
                this.show();
                this._focusWindow();
            } else {
                state = 'on';
                
                this.cleanHTML();
               
                Dom.addClass(iframe, 'editor-hidden');
                Dom.removeClass(ta, 'editor-hidden');
                this.toolbar.set('disabled', true);
                this.toolbar.getButtonByValue('editcode').set('disabled', false);
                this.toolbar.selectButton('editcode');
                this.dompath.innerHTML = 'Editing HTML Code';
                this.hide();
            
            }
            return false;
        }, this, true);

        this.on('cleanHTML', function(ev) {
            this.get('element').value = ev.html;
        }, this, true);
        
        
        
        this.on('editorKeyUp', html_editor_changed, this, true);
                this.on('editorDoubleClick', html_editor_changed, this, true);
                this.on('editorMouseDown', html_editor_changed, this, true);
                this.on('buttonClick', html_editor_changed, this, true);

        this.on('afterRender', function() {
            var wrapper = this.get('editor_wrapper');
            wrapper.appendChild(this.get('element'));
            this.setStyle('width', '100%');
            this.setStyle('height', '100%');
            this.setStyle('visibility', '');
            this.setStyle('top', '');
            this.setStyle('left', '');
            this.setStyle('position', '');

            this.addClass('editor-hidden');
        }, this, true);
    }, EmailHTMLEditor, true);
        yuiImgUploader(EmailHTMLEditor, 'html_editor', 'ar_upload_file_from_editor.php','image');

  EmailHTMLEditor._defaultToolbar.titlebar = "";
    
    
      EmailHTMLEditor.on('editorContentLoaded', function() {

        var head = this._getDoc().getElementsByTagName('head')[0];

<?php

$css_files=array();

$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Site External File Bridge` where `External File Type`='CSS' and `Site Key`=%d",$site_key);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
   $css_files[]='public_external_file.php?id='.$row['external_file_key'];

}
$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Store External File Bridge` where `External File Type`='CSS' and `Page Key`=%d",$page_key);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
   $css_files[]='public_external_file.php?id='.$row['external_file_key'];

}



foreach($css_files as $css_file){
printf("var link = this._getDoc().createElement('link');\nlink.setAttribute('rel', 'stylesheet');\nlink.setAttribute('type', 'text/css');\nlink.setAttribute('href', '%s');\nhead.appendChild(link);\n\n",
 $css_file
);
}

?>
        

    }, EmailHTMLEditor, true);
    
    EmailHTMLEditor.render();


}




YAHOO.util.Event.onDOMReady(init);




