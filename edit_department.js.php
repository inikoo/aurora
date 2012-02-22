<?php include_once('common.php');?>
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;










function change_block(e){
    
     
    // if(this.id=='pictures' || this.id=='discounts'){
	//    Dom.get('info_name').style.display='';
	//}else
	//    Dom.get('info_name').style.display='none';
     
     
	 Dom.get('d_families').style.display='none';
	 Dom.get('d_details').style.display='none';
	 Dom.get('d_discounts').style.display='none';
	 Dom.get('d_pictures').style.display='none';
	 Dom.get('d_web').style.display='none';
	 Dom.get('d_'+this.id).style.display='';
	
	 ids=['families','details','discounts','pictures','web'];

Dom.removeClass(ids,'selected');
	
	 Dom.addClass(this, 'selected');
	 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=department-editing&value='+this.id ,{});
	
  
}
// -------------------------------strts --------------------------------------------------
var CellEdit = function (callback, newValue) {


		var record = this.getRecord(),
		column = this.getColumn(),
		oldValue = this.value,
		datatable = this.getDataTable(),
		recordIndex = datatable.getRecordIndex(record);

		//alert(	'tipo=edit_'+column.object+'&key=' + column.key + '&newvalue=' + encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ //myBuildUrl(datatable,record))


	if(column.object=='department_page_properties')	
					request_page=	'ar_edit_sites.php';			

			else
		request_page=	'ar_edit_assets.php';			

		YAHOO.util.Connect.asyncRequest(
						'POST',
						request_page, {
						    success:function(o) {
							//	alert(o.responseText);
							var r = YAHOO.lang.JSON.parse(o.responseText);
							if (r.state == 200) {

							    if(column.key=='price' || column.key=='unit_price' || column.key=='margin' ){
								
                               datatable.updateCell(record,'unit_price',r.newdata['Unit Price']);
							   datatable.updateCell(record,'margin',r.newdata['Margin']);
                               datatable.updateCell(record,'price',r.newdata['Price']);
                               datatable.updateCell(record,'rrp_info','<?php echo _('Margin')?> '+r.newdata['RRP Margin']);

								
								
								//datatable.updateRow(recordIndex,data);
								callback(true,r.newvalue);
								
							    }else if(column.key=='unit_rrp'  ){
								 datatable.updateCell(record,'unit_rrp',r.newdata['RRP Per Unit']);
                               datatable.updateCell(record,'rrp_info','<?php echo _('Margin')?> '+r.newdata['RRP Margin']);
								
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




function show_new_family_dialog(){
    Dom.setStyle('new_family_dialog','display','');
        Dom.setStyle('cancel_new_family','visibility','visible');
        Dom.setStyle('save_new_family','visibility','visible');
        Dom.addClass('save_new_family','disabled');

 Dom.setStyle(['show_new_family_dialog_button','import_new_family'],'display','none');
}

function save_new_family(){
save_new_general('family')
}


function post_new_create_actions(branch,response) {

cancel_new_family();
}

function cancel_new_family(){
 Dom.setStyle('new_family_dialog','display','none');
        Dom.setStyle('cancel_new_family','visibility','hidden');
        Dom.setStyle('save_new_family','visibility','hidden');
        Dom.addClass('save_new_family','disabled');

 Dom.setStyle(['show_new_family_dialog_button','import_new_family'],'display','');

cancel_new_general('family');


}


function deal_term_save(deal_key){
deal_save(deal_key,'term');
}
function deal_allowance_save(deal_key){
deal_save(deal_key,'allowance');
}
function deal_save(deal_key,key){
	
        
       
        var newValue=Dom.get('deal_'+key+deal_key).value;
        var oldValue=Dom.get('deal_'+key+deal_key).getAttribute('ovalue');

		var request='tipo=edit_deal&key=' + key + '&newvalue=' + 
						encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ '&deal_key='+deal_key

		YAHOO.util.Connect.asyncRequest(
						'POST',
						'ar_edit_assets.php', {
						    success:function(o) {
								alert(o.responseText);
							var r = YAHOO.lang.JSON.parse(o.responseText);
							if (r.state == 200) {

							 
								Dom.get('deal_description'+deal_key).innerHTML=r.description;
								Dom.get('deal_'+key+deal_key).setAttribute=('ovalue',r.newvalue);
								Dom.get('deal_'+key+deal_key).value=r.newvalue;

								Dom.get('deal_'+key+'_save'+deal_key).style.display='none';
								Dom.get('deal_'+key+'_reset'+deal_key).style.display='none';
								
							    }else{
						
								
								
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
function deal_term_reset(deal_key){
    var data=deal_data[deal_key]['terms'];
    old_value=data.ovalue;
    Dom.get('deal_term_term'+deal_key).value=old_value;
    Dom.get('deal_term_save'+deal_key).style.visibility='hidden';
    Dom.get('deal_term_reset'+deal_key).style.visibility='hidden';
}



function deal_term_changed(deal_key){
    var data=deal_data[deal_key]['terms'];
    old_value=Dom.get('deal_term'+deal_key).getAttribute('ovalue');
    new_value=Dom.get('deal_term'+deal_key).value;

    if(old_value!=new_value){
	Dom.get('deal_term_reset'+deal_key).style.visibility='visible';

    switch(data.type){
    case('Order Interval'):

	break;

    case('Family Quantity Ordered'):
	
	
	Dom.get('deal_term_save'+deal_key).style.visibility='visible';

	var validator=/^\d+$/;
	if(!validator.test(new_value)){
	      Dom.get('deal_term_save'+deal_key).style.visibility='hidden';
	}
	break;


    }
    }else{
	
	Dom.get('deal_term_save'+deal_key).style.visibility='hidden';
	Dom.get('deal_term_reset'+deal_key).style.visibility='hidden';

    }

}
function old_deal_allowance_save(item,deal_key){

	var request='ar_edit_assets?tipo=edit_deal&key=' + item+ '&newvalue=' + 
	    encodeURIComponent(value) +  '&oldvalue=' + 
	    '&deal_key='+deal_key;
	//		alert(request)
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    //		   	    alert(o.responseText)
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state==200){
			
			
			
		
		    }else{
			validate_scope_data[branch][r.key].changed=true;
			validate_scope_data[branch][r.key].validated=false;
			Dom.get(validate_scope_data[branch][r.key].name+'_msg').innerHTML=r.msg;
			
		    }
		    
		}
			    
	    });
	}


function deal_allowance_changed(deal_key){
    var data=deal_data[deal_key]['allowances'];
        old_value=Dom.get('deal_allowance'+deal_key).getAttribute('ovalue');

    new_value=Dom.get('deal_allowance'+deal_key).value;
     //alert(old_value+'->'+new_value)
    if(old_value!=new_value){
	Dom.get('deal_allowance_reset'+deal_key).style.visibility='visible';

    switch(data.type){
    case('Get Same Fre'):
	break;
    case('Get Free'):
	break;
    
    case('Percentage Off'):
	
	
	Dom.get('deal_allowance_save'+deal_key).style.visibility='visible';

	var validator=/^(\d+|\.\d+|\d+.|\d+\.\d+)\s*\%?$/;
	if(!validator.test(new_value)){
	      Dom.get('deal_allowance_save'+deal_key).style.visibility='hidden';
	}
	break;


    }
    }else{
	
	Dom.get('deal_allowance_save'+deal_key).style.visibility='hidden';
	Dom.get('deal_allowance_reset'+deal_key).style.visibility='hidden';

    }

}





var description_num_changed=0;
var description_partrnings= new Object();
var description_errors= new Object();



  var change_view=function(e){
	
	var table=tables['table0'];
	var tipo=this.id;
	//	alert(table.view+' '+tipo)
	if(table.view!=tipo){
	    table.hideColumn('name');

	    table.hideColumn('sdescription');
	    table.hideColumn('units');
	    table.hideColumn('units_info');
	    table.hideColumn('price_info');
	    table.hideColumn('price');
	    table.hideColumn('unit_rrp');
	    table.hideColumn('rrp_info');
	    table.hideColumn('code');
	    table.hideColumn('code_price');

	    table.hideColumn('unit_type');
	    table.hideColumn('unit_price');
	    table.hideColumn('margin');

	    table.hideColumn('processing');
	    table.hideColumn('sales_state');
	    table.hideColumn('web_state');
	    table.hideColumn('state_info');
		table.hideColumn('smallname');


	    if(tipo=='view_name'){
		table.showColumn('code');
		table.showColumn('name');

		table.showColumn('sdescription');	

	    }
	    else if(tipo=='view_units'){
		 table.showColumn('code');
		table.showColumn('units');
		table.showColumn('unit_type');

	    }
	     else if(tipo=='view_state'){
		 table.showColumn('code');
		table.showColumn('processing');
		table.showColumn('sales_state');
		table.showColumn('web_state');
		table.showColumn('state_info');
		table.showColumn('smallname');


	    }
	    
	    else if(tipo=='view_price'){
		table.showColumn('code_price');
		table.showColumn('unit_price');
		table.showColumn('margin');
		table.showColumn('units_info');
		
		table.showColumn('price');
		table.showColumn('unit_rrp');
		table.showColumn('price_info');
		table.showColumn('rrp_info');


	    }
	    
	    


	Dom.get(table.view).className="";
	Dom.get(tipo).className="selected";
	table.view=tipo
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=department-edit_view&value=' + escape(tipo),{} );
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
				    
                    ,{key:"sales_type", label:"<?php echo _('Sale Type')?>",width:100, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},object:'family',editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:[{label:"<?php echo _('Public Sale')?>",value:'Public Sale'},{label:"<?php echo _('Private Sale')?>",value:'Private Sale'},{label:"<?php echo _('Not For Sale')?>",value:'Not For Sale'}],disableBtns:true})}

				     ];
		request="ar_edit_assets.php?tipo=edit_families&parent=department&tableid=0&parent_key="+Dom.get('department_key').value;
		
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
			 "code",
			 "name",
			 'sales_type','id','edit','go'
			 ]};
	    
	       this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage:<?php echo $_SESSION['state']['department']['families']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 Key: "<?php echo$_SESSION['state']['department']['families']['order']?>",
									  dir: "<?php echo$_SESSION['state']['department']['families']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table0.table_id=tableid;
        this.table0.subscribe("renderEvent", myrenderEvent);




	    this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table0.subscribe("cellClickEvent", onCellClick);
	    
	    this.table0.view='<?php echo$_SESSION['state']['department']['view']?>';

		

 var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"date",label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author",label:"<?php echo _('Author')?>", width:70,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
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
        this.table1.table_id=tableid;
        this.table1.subscribe("renderEvent", myrenderEvent);
		    
		    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['department']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['department']['history']['f_value']?>'};
	    


     var tableid=6; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
				       {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}
				         ,{key:"go", label:"", width:20,action:"none"}
				       ,{key:"code",label:"<?php echo _('Code')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'department_page_properties'}
				       ,{key:"store_title",label:"<?php echo _('Header Title')?>", <?php echo($_SESSION['state']['department']['edit_pages']['view']=='page_header'?'':'hidden:true,')?>width:400,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'department_page_properties'}
				     	  ,{key:"link_title",label:"<?php echo _('Link Title')?>", <?php echo($_SESSION['state']['department']['edit_pages']['view']=='page_properties'?'':'hidden:true,')?>width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'department_page_properties'}
				     	  ,{key:"url",label:"<?php echo _('URL')?>", <?php echo($_SESSION['state']['department']['edit_pages']['view']=='page_properties'?'':'hidden:true,')?>width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'department_page_properties'}
				     	  ,{key:"page_title",label:"<?php echo _('Browser Title')?>",<?php echo($_SESSION['state']['department']['edit_pages']['view']=='page_html_head'?'':'hidden:true,')?> width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'department_page_properties'}
				     	  ,{key:"page_keywords",label:"<?php echo _('Keywords')?>",<?php echo($_SESSION['state']['department']['edit_pages']['view']=='page_html_head'?'':'hidden:true,')?> width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'department_page_properties'}

				     
				     ,{key:"delete", label:"",width:12,sortable:false,action:'delete',object:'page_store'}		         
				       ];
				       
	 
				       
				       
	    //?tipo=customers&tid=0"
	        this.dataSource6 = new YAHOO.util.DataSource("ar_edit_sites.php?tipo=pages&parent=department&parent_key="+Dom.get('department_key').value+"&tableid=6");

//alert("ar_edit_sites.php?tipo=family_page_list&site_key="+Dom.get('site_key').value+"&parent=family&parent_key="+Dom.get('family_key').value+"&tableid=6")
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
			 "id"
			 ,"go","code","store_title","delete","link_title","url","page_title","page_keywords"

			 ]};

        this.table6 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource6
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    :<?php echo $_SESSION['state']['department']['edit_pages']['nr']?> ,containers : 'paginator6', alpartysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info6'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo $_SESSION['state']['department']['edit_pages']['order']?>",
							     dir: "<?php echo $_SESSION['state']['department']['edit_pages']['order_dir']?>"
							 },
							 dynamicData : true
						     }
						     );
	    
	    this.table6.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table6.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table6.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table6.table_id=tableid;
        this.table6.subscribe("renderEvent", myrenderEvent);

	    this.table6.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table6.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table6.subscribe("cellClickEvent", onCellClick);
		    
	    this.table6.filter={key:'<?php echo $_SESSION['state']['department']['edit_pages']['f_field']?>',value:'<?php echo $_SESSION['state']['department']['edit_pages']['f_value']?>'};




	};
    });


function validate_code(query){
   
 validate_general('department','code',unescape(query));
}
function validate_name(query){
 validate_general('department','name',unescape(query));
}

function validate_family_code(query){
  
 validate_general('family','code',unescape(query));
}
function validate_family_name(query){
 validate_general('family','name',unescape(query));
}
function validate_family_special_char(query){
 validate_general('family','special_char',unescape(query));
}





function reset_edit_department(){
 reset_edit_general('department');
}
function save_edit_department(){
 save_edit_general('departmenty');
}



function post_new_create_actions(branch,r){

var table_id=0
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
    var request='&tableid='+table_id+'&sf=0';
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);  

}

function post_item_updated_actions(branch,r){
key=r.key;
newvalue=r.newvalue;
 if(key=='name'){
     Dom.get('title_name').innerHTML=newvalue;
     Dom.get('title_name_bis').innerHTML=newvalue;

 }else if(key=='code'){
     Dom.get('title_code').innerHTML=newvalue;
 }
 var table=tables.table1;
 var datasource=tables.dataSource1;
 var request='';
 datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
 
}

function show_new_department_page_dialog(){

var number_sites=Dom.get('number_sites').value;

if(number_sites==0){
return;
}else if(number_sites==1){
new_department_page(Dom.get('site_key').value);
}else{
alert("todo")
}

}

function update_page_preview_snapshot(page_key){
  YAHOO.util.Connect.asyncRequest('POST','ar_edit_sites.php?tipo=update_page_preview_snapshot&id='+page_key,{
  success: function(o) {
   var r = YAHOO.lang.JSON.parse(o.responseText);
  }
  });
  }

function new_department_page(site_key){


var request='tipo=new_department_page&department_key='+Dom.get('department_key').value+'&site_key='+site_key

		YAHOO.util.Connect.asyncRequest(
						'POST',
						'ar_edit_sites.php', {
						    success:function(o) {
						    //alert(o.responseText)
							var r = YAHOO.lang.JSON.parse(o.responseText);
							if (r.state == 200) {

								        var table=tables.table6;
 var datasource=tables.dataSource6;
 var request='';
 datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
 update_page_preview_snapshot(r.page_key)
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


function init(){



  ids=['page_properties','page_html_head','page_header'];
 YAHOO.util.Event.addListener(ids, "click",change_edit_pages_view,{'table_id':6,'parent':'page'})


init_search('products_store');
 validate_scope_metadata={
    'department':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'id','key':Dom.get('department_key').value},
    'family':{'type':'new','ar_file':'ar_edit_assets.php','key_name':'department_id','key':Dom.get('department_key').value}
};

 validate_scope_data={
    'department':{
	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item'
		,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Department Name')?>'}],'name':'name'
		,'ar':'find','ar_request':'ar_assets.php?tipo=is_department_name&store_key='+Dom.get('store_key').value+'&query='}
	,'code':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Department Code')?>'}]
		 ,'name':'code','ar':'find','ar_request':'ar_assets.php?tipo=is_department_code&store_key='+Dom.get('store_key').value+'&query='}},
	'family':{
	'name':{'changed':false,'validated':false,'required':true,'group':1,'type':'item','dbname':'Product Family Name'
		,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Family Name')?>'}],'name':'family_name'
		,'ar':'find','ar_request':'ar_assets.php?tipo=is_family_name&store_key='+Dom.get('store_key').value+'&query='}
	,'code':{'changed':false,'validated':false,'required':true,'group':1,'type':'item','dbname':'Product Family Code'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Family Code')?>'}]
		 ,'name':'family_code','ar':'find','ar_request':'ar_assets.php?tipo=is_family_code&store_key='+Dom.get('store_key').value+'&query='}
	,'special_char':{'changed':false,'validated':false,'required':false,'group':1,'type':'item','dbname':'Product Family Special Characteristic'
		,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Special Characteristic')?>'}],'name':'family_special_char'
		,'ar':false,'ar_request':false}
	,'description':{'changed':false,'validated':false,'required':false,'group':1,'type':'textarea','dbname':'Product Family Description'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Family Description')?>'}]
		 ,'name':'family_description','ar':false,'ar_request':false}	 
		 }



		 
};



//   var ids = ["checkbox_thumbnails","checkbox_list","checkbox_slideshow","checkbox_manual"]; 
 //   YAHOO.util.Event.addListener(ids, "click", select_layout);


 	//YAHOO.util.Event.on('uploadButton', 'click', onUploadButtonClick);
    YAHOO.util.Event.addListener('new_department_page', "click", show_new_department_page_dialog);

 YAHOO.util.Event.addListener('cancel_new_family', "click", cancel_new_family);
 YAHOO.util.Event.addListener('save_new_family', "click", save_new_family);

 	YAHOO.util.Event.on('uploadButton', 'click', upload_image);



var department_code_oACDS = new YAHOO.util.FunctionDataSource(validate_code);
    department_code_oACDS.queryMatchContains = true;
    var department_code_oAutoComp = new YAHOO.widget.AutoComplete("code","code_Container", department_code_oACDS);
    department_code_oAutoComp.minQueryLength = 0; 
    department_code_oAutoComp.queryDelay = 0.1;
    
     var department_name_oACDS = new YAHOO.util.FunctionDataSource(validate_name);
    department_name_oACDS.queryMatchContains = true;
    var department_name_oAutoComp = new YAHOO.widget.AutoComplete("name","name_Container", department_name_oACDS);
    department_name_oAutoComp.minQueryLength = 0; 
    department_name_oAutoComp.queryDelay = 0.1;

var family_code_oACDS = new YAHOO.util.FunctionDataSource(validate_family_code);
    family_code_oACDS.queryMatchContains = true;
    var family_code_oAutoComp = new YAHOO.widget.AutoComplete("family_code","family_code_Container", family_code_oACDS);
    family_code_oAutoComp.minQueryLength = 0; 
    family_code_oAutoComp.queryDelay = 0.1;
    
     var family_name_oACDS = new YAHOO.util.FunctionDataSource(validate_family_name);
    family_name_oACDS.queryMatchContains = true;
    var family_name_oAutoComp = new YAHOO.widget.AutoComplete("family_name","family_name_Container", family_name_oACDS);
    family_name_oAutoComp.minQueryLength = 0; 
    family_name_oAutoComp.queryDelay = 0.1;


  var family_special_char_oACDS = new YAHOO.util.FunctionDataSource(validate_family_special_char);
    family_special_char_oACDS.queryMatchContains = true;
    var family_special_char_oAutoComp = new YAHOO.widget.AutoComplete("family_special_char","family_special_char_Container", family_special_char_oACDS);
    family_special_char_oAutoComp.minQueryLength = 0; 
    family_special_char_oAutoComp.queryDelay = 0.1;






 
    function mygetTerms(query) {multireload();};
    var oACDS = new YAHOO.widget.DS_JSFunction(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","filtercontainer0", oACDS);
    oAutoComp.minQueryLength = 0; 
    
    var ids = ["details","families","discounts","pictures","web"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
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
