<?php
include_once('common.php');
?>
    var Event   = YAHOO.util.Event;

    var Dom   = YAHOO.util.Dom;
var dialog_country_list;
var dialog_wregion_list;
var dialog_postal_code_list;
var dialog_city_list;
var dialog_department_list;
var dialog_family_list;
var dialog_product_list;
var dialog_category_list;

var searched=false;

var current_geo_constrain='billing';

function save_search_list(){


	alert('list');
	var store_id=Dom.get('store_id').value;
	var list_name = Dom.get('list_name').value;
	
	if(list_name==''){
	Dom.get('save_list_msg').innerHTML=Dom.get('error_no_name').innerHTML;
	return;
	}
	
	
	if(Dom.get('dynamic').checked == true)
	{
		var list_type = 'Dynamic';
	}else{
	var list_type = 'Static';	
	}
	
	var awhere=get_awhere();
	
	var request="ar_edit_orders.php?tipo=new_list&list_name="+list_name+'&list_type='+list_type+'&store_id='+store_id+'&awhere='+awhere;
	
	//alert(request);
		YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		   //alert(o.responseText);

		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if (r.state==200) {
			location.href='orders_lists.php?id='+r.customer_list_key;

		    }else
			Dom.get('save_list_msg').innerHTML=r.msg;
		}
	    });  
	
	
	
	

}



var data_returned=function(){
	 if(searched){
	     Dom.get('searching').style.display='none';
	     Dom.get('the_table').style.display='';
	     Dom.get('save_list').style.display='';
	     Dom.get('modify_search').style.display='';
	     Dom.get('submit_search').style.display='none';


	 }	 
    }
    

function show_dont_wish_to_receive(){
Dom.setStyle('show_dont_wish_to_receive','display','none')
Dom.setStyle('tr_dont_wish_to_receive','display','')
}

function show_dont_have(){
Dom.setStyle('show_dont_have','display','none')
Dom.setStyle('tr_dont_have','display','')
}    
    
    
function checkbox_changed_allow(o){

cat=Dom.get(o).getAttribute('cat');

if(cat=='all'){

if(    Dom.hasClass(o,'selected')){
return;
}else{
Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'allow_options'),'selected');
Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'dont_allow_options'),'selected');
Dom.addClass(o,'selected');

}



}else{
Dom.removeClass('allow_all','selected');



cat=Dom.get(o).getAttribute('cat');
this_parent=Dom.get(o).getAttribute('parent');
if(this_parent=='allow_'){
    other_parent='dont_allow_';
}else{
    other_parent='allow_';
}    
if(    Dom.hasClass(o,'selected')){
    Dom.removeClass(o,'selected');
}else{
    Dom.addClass(o,'selected');
    Dom.removeClass(other_parent+cat,'selected');
}




//if(Dom.hasClass(o,'selected')){
//    Dom.removeClass(o,'selected');
//}else{
//    Dom.addClass(o,'selected');
//}




}

}
 
function checkbox_changed_have(o){

cat=Dom.get(o).getAttribute('cat');
this_parent=Dom.get(o).getAttribute('parent');
if(this_parent=='have_'){
    other_parent='dont_have_';
}else{
    other_parent='have_';
}    
if(    Dom.hasClass(o,'selected')){
    Dom.removeClass(o,'selected');
}else{
    Dom.addClass(o,'selected');
    Dom.removeClass(other_parent+cat,'selected');
}



}
 
 
function select_country(oArgs){
	if(current_geo_constrain=='billing'){
		geo_constrain='billing_geo_constraints'
	}else{
		geo_constrain='delivery_geo_constraints'
	}
    var billing_geo_constraints=Dom.get(geo_constrain).value;
    if(billing_geo_constraints!=''){billing_geo_constraints=billing_geo_constraints+','}
    billing_geo_constraints=billing_geo_constraints+tables.table2.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '');
    Dom.get(geo_constrain).value=billing_geo_constraints;
    dialog_country_list.hide();
    hide_filter(true,2)
}

function select_postal_code(oArgs){
	if(current_geo_constrain=='billing'){
		geo_constrain='billing_geo_constraints'
	}else{
		geo_constrain='delivery_geo_constraints'
	}
    var billing_geo_constraints=Dom.get(geo_constrain).value;
    if(billing_geo_constraints!=''){billing_geo_constraints=billing_geo_constraints+','}
    billing_geo_constraints=billing_geo_constraints+'pc('+tables.table3.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '')+')';
    Dom.get(geo_constrain).value=billing_geo_constraints;
    dialog_postal_code_list.hide();
    hide_filter(true,3)
}

function select_wregion(oArgs){

	if(current_geo_constrain=='billing'){
		geo_constrain='billing_geo_constraints'
	}else{
		geo_constrain='delivery_geo_constraints'
	}
    var billing_geo_constraints=Dom.get(geo_constrain).value;
    if(billing_geo_constraints!=''){billing_geo_constraints=billing_geo_constraints+','}
    billing_geo_constraints=billing_geo_constraints+'wr('+tables.table1.getRecord(oArgs.target).getData('wregion_code').replace(/<.*?>/g, '')+')';
    Dom.get(geo_constrain).value=billing_geo_constraints;
    dialog_wregion_list.hide();
    hide_filter(true,1)
}

function select_city(oArgs){
	if(current_geo_constrain=='billing'){
		geo_constrain='billing_geo_constraints'
	}else{
		geo_constrain='delivery_geo_constraints'
	}
    var billing_geo_constraints=Dom.get(geo_constrain).value;
    if(billing_geo_constraints!=''){billing_geo_constraints=billing_geo_constraints+','}
    billing_geo_constraints=billing_geo_constraints+'t('+tables.table4.getRecord(oArgs.target).getData('city').replace(/<.*?>/g, '')+')';
    Dom.get(geo_constrain).value=billing_geo_constraints;
    dialog_city_list.hide();
    hide_filter(true,4)
}
function select_department(oArgs){
    var product_ordered_or=Dom.get('product_ordered_or').value;
    if(product_ordered_or!=''){product_ordered_or=product_ordered_or+','}
    product_ordered_or=product_ordered_or+'d('+tables.table5.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '')+')';
    Dom.get('product_ordered_or').value=product_ordered_or;
    dialog_department_list.hide();
    hide_filter(true,5)
}

function select_family(oArgs){
    var product_ordered_or=Dom.get('product_ordered_or').value;
    if(product_ordered_or!=''){product_ordered_or=product_ordered_or+','}
    product_ordered_or=product_ordered_or+'f('+tables.table6.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '')+')';
    Dom.get('product_ordered_or').value=product_ordered_or;
    dialog_family_list.hide();
    hide_filter(true,6)
}
function select_product(oArgs){
    var product_ordered_or=Dom.get('product_ordered_or').value;
    if(product_ordered_or!=''){product_ordered_or=product_ordered_or+','}
    product_ordered_or=product_ordered_or+tables.table7.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '');
    Dom.get('product_ordered_or').value=product_ordered_or;
    dialog_product_list.hide();
    hide_filter(true,7)
}
function select_category(oArgs){
    var product_ordered_or=Dom.get('product_ordered_or').value;
    if(product_ordered_or!=''){product_ordered_or=product_ordered_or+','}
    product_ordered_or=product_ordered_or+'cat('+tables.table8.getRecord(oArgs.target).getData('category_code').replace(/<.*?>/g, '')+')';
    Dom.get('product_ordered_or').value=product_ordered_or;
    dialog_category_list.hide();
    hide_filter(true,8)
}
    
YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


		var store_key=Dom.get('store_id').value;

	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [ 
				       {key:"id", label:"<?php echo$customers_ids[0]?>",width:45,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
					   {key:"last_date", label:"<?php echo _('Last Updated')?>", <?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>width:115,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"customer",label:"<?php echo _('Customer')?>", <?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>width:240,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"state", label:"<?php echo _('Status')?>", <?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>width:205,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"total_amount", label:"<?php echo _('Total')?>", <?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>width:110,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       //,{key:"name", label:"<?php echo _('Customer Name')?>", width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //,{key:"location", label:"<?php echo _('Location')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?> width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //	,{key:"contact_since", label:"<?php echo _('Since')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>width:85,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       //,{key:"last_order", label:"<?php echo _('Last Order')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>width:85,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       //,{key:"orders", label:"<?php echo _('Orders')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       //,{key:"activity", label:"<?php echo _('Status')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       //,{key:"contact_name", label:"<?php echo _('Contact Name')?>",width:160,<?php echo($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       //,{key:"email", label:"<?php echo _('Email')?>",width:210,<?php echo($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       //,{key:"telephone", label:"<?php echo _('Telephone')?>", width:137,<?php echo($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},className:"aright"}
				       //,{key:"address", label:"<?php echo _('Contact Address')?>", width:176,<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aleft"}
				       //,{key:"billing_address", label:"<?php echo _('Billing Address')?>", width:170,<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aleft"}
				      // ,{key:"delivery_address", label:"<?php echo _('Delivery Address')?>", width:170,<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aleft"}
				      // ,{key:"total_payments", label:"<?php echo _('Payments')?>",width:99,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       //,{key:"total_refunds", label:"<?php echo _('Refunds')?>",width:90,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"net_balance", label:"<?php echo _('Balance')?>",width:90,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"balance", label:"<?php echo _('Outstanding')?>",width:90,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"total_profit", label:"<?php echo _('Profit')?>",width:90,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"top_orders", label:"<?php echo _('Rank Orders')?>",width:121,<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"top_invoices", label:"<?php echo _('Rank Invoices')?>",width:121,<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"top_balance", label:"<?php echo _('Rank Balance')?>",width:120,<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"top_profits", label:"<?php echo _('Rank Profits')?>",width:120,<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ];


	    this.dataSource0 = new YAHOO.util.DataSource("ar_orders.php?tipo=orders");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.table_id=tableid;

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
			 'id',
			 'last_date',
			 'customer',
			 'total_amount',
			 "state"
			 ]};

		
		

	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;


	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customers']['list']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customers']['list']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customers']['list']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

	    
	    this.table0.subscribe("dataReturnEvent", data_returned);  


	    this.table0.filter={key:'<?php echo$_SESSION['state']['customers']['list']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['list']['f_value']?>'};


var tableid=1;
		      var tableDivEL="table"+tableid;
		      
		      var ColumnDefs = [
		      		{key:"wregion_code", label:"<?php echo _('Code')?>",width:30, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"wregion_name", label:"<?php echo _('World Region')?>",width:120, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			     	,{key:"flags", label:"<?php echo _('Countries')?>",width:240, sortable:false,className:"aleft"}

					];
		    
		      
		      this.dataSource1 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=world_regions_list&tableid=1&nr=20&sf=0");
		      this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
		      this.dataSource1.connXhrMode = "queueRequests";
		      	    this.dataSource1.table_id=tableid;

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
				  "wregion_name","wregion_code","flags"
				   ]};
		      
		    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource1
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								   
								   ,sortedBy : {
								      Key: "wregion_code",
								       dir: ""
								   }
								   ,dynamicData : true
								 
							       }
							       );
		      this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
		      this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
		      this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
                   this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
                   
                   this.table1.subscribe("rowMouseoverEvent", this.table1.onEventHighlightRow);
       this.table1.subscribe("rowMouseoutEvent", this.table1.onEventUnhighlightRow);
      this.table1.subscribe("rowClickEvent", select_wregion);
     

                   
	    this.table1.filter={key:'wregion_code',value:''};

   var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
                    {key:"flag", label:"",width:10,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"code", label:"<?php echo _('Code')?>",width:25,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			       ,{key:"name", label:"<?php echo _('Name')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"wregion", label:"<?php echo _('Region')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

		];
			       
	    this.dataSource2 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=country_list&tableid=2&nr=20&sf=0");
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    	    this.dataSource2.table_id=tableid;

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
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "name","flag",'code','wregion'
			 ]};

	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource2
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table2.subscribe("rowMouseoverEvent", this.table2.onEventHighlightRow);
       this.table2.subscribe("rowMouseoutEvent", this.table2.onEventUnhighlightRow);
      this.table2.subscribe("rowClickEvent", select_country);
     


	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'code',value:''};


   var tableid=3; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var ColumnDefs = [
                   {key:"code", label:"<?php echo _('Postal Code')?>",width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			    ,{key:"flag", label:"",width:10,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

			       ,{key:"name",label:"<?php echo _('Country Name')?>",width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				 ,{key:"times_used", label:"<?php echo _('Times Used')?>",width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			];
			       
	    this.dataSource3 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=postal_codes_list&store_key="+store_key+"&tableid=3&nr=20&sf=0");
	    this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource3.connXhrMode = "queueRequests";
	    	    this.dataSource3.table_id=tableid;

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
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "name","flag",'code','times_used'
			 ]};

	    this.table3 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource3
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator3', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table3.subscribe("rowMouseoverEvent", this.table3.onEventHighlightRow);
       this.table3.subscribe("rowMouseoutEvent", this.table3.onEventUnhighlightRow);
      this.table3.subscribe("rowClickEvent", select_postal_code);
     


	    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table3.filter={key:'code',value:''};

   var tableid=4; 
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
                   {key:"city", label:"<?php echo _('City Name')?>",width:125,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	                    ,{key:"flag", label:"",width:10,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	,{key:"name", label:"<?php echo _('Country Name')?>",width:115,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					 ,{key:"times_used", label:"<?php echo _('Times Used')?>",width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

	];
			       
	    this.dataSource4 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=towns_list&store_key="+store_key+"&tableid=4&nr=20&sf=0");
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
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "name","flag",'city','times_used'
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
									      ,template : "{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "city",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table4.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table4.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table4.subscribe("rowMouseoverEvent", this.table4.onEventHighlightRow);
       this.table4.subscribe("rowMouseoutEvent", this.table4.onEventUnhighlightRow);
      this.table4.subscribe("rowClickEvent", select_city);
     


	    this.table4.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table4.filter={key:'city',value:''};

   var tableid=5; 
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
			
                    {key:"code", label:"<?php echo _('Code')?>",width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				];
			       
	    this.dataSource5 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=department_list&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
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
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "code","name"
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
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table5.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table5.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);





 this.table5.subscribe("rowMouseoverEvent", this.table5.onEventHighlightRow);
       this.table5.subscribe("rowMouseoutEvent", this.table5.onEventUnhighlightRow);
      this.table5.subscribe("rowClickEvent", select_department);
           
           this.table5.table_id=tableid;
           this.table5.subscribe("renderEvent", myrenderEvent);



	    this.table5.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table5.filter={key:'code',value:''};
	    //YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown);
// --------------------------------------Department table ends here----------------------------------------------------------


// --------------------------------------Family table starts here--------------------------------------------------------
   var tableid=6; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
			
                    {key:"code", label:"<?php echo _('Code')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
						
			];
		this.dataSource6 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=family_list&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource6.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource6.connXhrMode = "queueRequests";
	    	    this.dataSource6.table_id=tableid;

	    this.dataSource6.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "code",'name'
			 ]};

	    this.table6 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource6
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator6', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info6'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table6.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table6.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table6.subscribe("rowMouseoverEvent", this.table6.onEventHighlightRow);
       this.table6.subscribe("rowMouseoutEvent", this.table6.onEventUnhighlightRow);
      this.table6.subscribe("rowClickEvent", select_family);
        this.table6.table_id=tableid;
           this.table6.subscribe("renderEvent", myrenderEvent);


	    this.table6.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table6.filter={key:'code',value:''};


   var tableid=7; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
                    {key:"code", label:"<?php echo _('Code')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			  			];
			       
		this.dataSource7 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=product_list&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource7.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource7.connXhrMode = "queueRequests";
	    	    this.dataSource7.table_id=tableid;

	    this.dataSource7.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "code","name"
			 ]};

	    this.table7 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource7
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator7', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info7'>{CurrentPageReport}</strong>{NextPageLink}"
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
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table7.subscribe("rowMouseoverEvent", this.table7.onEventHighlightRow);
       this.table7.subscribe("rowMouseoutEvent", this.table7.onEventUnhighlightRow);
      this.table7.subscribe("rowClickEvent", select_product);
     


	    this.table7.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table7.filter={key:'code',value:''};




	
	};

    });



function get_awhere(){
  dont_have=Dom.getElementsByClassName('selected', 'span', 'dont_have_options');
    dont_have_array= new Array();
    for(x in dont_have){
        dont_have_array[x]=dont_have[x].getAttribute('cat');
    }
have=Dom.getElementsByClassName('selected', 'span', 'have_options');
    have_array= new Array();
    for(x in have){
        have_array[x]=have[x].getAttribute('cat');
    }

allow=Dom.getElementsByClassName('selected', 'span', 'allow_options');
    allow_array= new Array();
    for(x in allow){
        allow_array[x]=allow[x].getAttribute('cat');
    }

dont_allow=Dom.getElementsByClassName('selected', 'span', 'dont_allow_options');
    dont_allow_array= new Array();
    for(x in dont_allow){
        dont_allow_array[x]=dont_allow[x].getAttribute('cat');
    }
    var data={ 
    dont_have:dont_have_array,
    have:have_array,
    allow:allow_array,
    dont_allow:dont_allow_array,
	billing_geo_constraints:Dom.get('billing_geo_constraints').value,
	delivery_geo_constraints:Dom.get('delivery_geo_constraints').value,
	//	product_ordered2: Dom.get('product_ordered2').value,
	product_not_ordered1: Dom.get('product_not_ordered1').value,
	//	product_not_ordered2: Dom.get('product_not_ordered2').value,
	product_not_received1: Dom.get('product_not_received1').value,
	//	product_not_received2: Dom.get('product_not_received2').value,
	product_ordered_or_from:Dom.get('v_calpop1').value,
	product_ordered_or_to:Dom.get('v_calpop2').value,
	order_created_from:Dom.get('v_calpop3').value,
	order_created_to:Dom.get('v_calpop4').value,
	product_ordered_or:Dom.get('product_ordered_or').value,
    }

    return YAHOO.lang.JSON.stringify(data);

   

}


function submit_search(e){


    //chack woth radio button is cheked

searched=true;

  
   var awhere=get_awhere();

	//alert(jsonStr);
    var table=tables.table0;
    var datasource=tables.dataSource0;
	store_id=Dom.get('store_id').value;
    var request='&sf=0&store_id='+store_id+'&where=' +awhere;
    Dom.setStyle('the_table','display','none');
    Dom.setStyle('searching','display','');
    Dom.setStyle('save_dialog','visibility','visible');

alert(request)
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);     

}
var submit_search_on_enter=function(e,tipo){
     var key;     
     if(window.event)
          key = window.event.keyCode; //IE
     else
          key = e.which; //firefox     

     if (key == 13)
	 submit_search(e,tipo);
};

function show_wregion_list(e,geo_constrain){

current_geo_constrain=geo_constrain;
dialog_wregion_list.show();
}

function show_country_list(e,geo_constrain){

current_geo_constrain=geo_constrain;
dialog_country_list.show();
}

function show_city_list(e,geo_constrain){

current_geo_constrain=geo_constrain;
dialog_city_list.show();
}

function show_postal_code_list(e,geo_constrain){

current_geo_constrain=geo_constrain;
dialog_postal_code_list.show();
}

function init(){
/*

  init_search('customers_store');
var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS1.queryMatchContains = true;
 oACDS1.table_id=1;
 var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS1);
 oAutoComp1.minQueryLength = 0; 
YAHOO.util.Event.addListener('clean_table_filter_show1', "click",show_filter,1);
 YAHOO.util.Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);
 
 var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS2.queryMatchContains = true;
 oACDS2.table_id=2;
 var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2","f_container2", oACDS2);
 oAutoComp2.minQueryLength = 0; 
YAHOO.util.Event.addListener('clean_table_filter_show2', "click",show_filter,2);
 YAHOO.util.Event.addListener('clean_table_filter_hide2', "click",hide_filter,2);
 
 var oACDS3 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS3.queryMatchContains = true;
 oACDS3.table_id=3;
 var oAutoComp3 = new YAHOO.widget.AutoComplete("f_input3","f_container3", oACDS3);
 oAutoComp3.minQueryLength = 0; 
YAHOO.util.Event.addListener('clean_table_filter_show3', "click",show_filter,3);
 YAHOO.util.Event.addListener('clean_table_filter_hide3', "click",hide_filter,3);
 
 var oACDS4 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS4.queryMatchContains = true;
 oACDS4.table_id=4;
 var oAutoComp4 = new YAHOO.widget.AutoComplete("f_input4","f_container4", oACDS4);
 oAutoComp4.minQueryLength = 0; 
YAHOO.util.Event.addListener('clean_table_filter_show4', "click",show_filter,4);
 YAHOO.util.Event.addListener('clean_table_filter_hide4', "click",hide_filter,4);
 



var oACDS5 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS5.queryMatchContains = true;
 oACDS5.table_id=5;
 var oAutoComp5 = new YAHOO.widget.AutoComplete("f_input5","f_container5", oACDS5);
 oAutoComp5.minQueryLength = 0; 
YAHOO.util.Event.addListener('clean_table_filter_show5', "click",show_filter,5);
 YAHOO.util.Event.addListener('clean_table_filter_hide5', "click",hide_filter,5);
 
 var oACDS6 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS6.queryMatchContains = true;
 oACDS6.table_id=6;
 var oAutoComp6 = new YAHOO.widget.AutoComplete("f_input6","f_container6", oACDS6);
 oAutoComp6.minQueryLength = 0; 
YAHOO.util.Event.addListener('clean_table_filter_show6', "click",show_filter,6);
 YAHOO.util.Event.addListener('clean_table_filter_hide6', "click",hide_filter,6);
 
 var oACDS7 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS7.queryMatchContains = true;
 oACDS7.table_id=7;
 var oAutoComp7 = new YAHOO.widget.AutoComplete("f_input7","f_container7", oACDS7);
 oAutoComp7.minQueryLength = 0; 
YAHOO.util.Event.addListener('clean_table_filter_show7', "click",show_filter,7);
 YAHOO.util.Event.addListener('clean_table_filter_hide7', "click",hide_filter,7);
 
 */
    dialog_country_list = new YAHOO.widget.Dialog("dialog_country_list", {context:["country","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_country_list.render();
    Event.addListener("country", "click", show_country_list, 'billing');
	Event.addListener("country2", "click", show_country_list, 'delivery');
	
    dialog_wregion_list = new YAHOO.widget.Dialog("dialog_wregion_list", {context:["wregion","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_wregion_list.render();
    Event.addListener("wregion", "click", show_wregion_list, 'billing');
	Event.addListener("wregion2", "click", show_wregion_list, 'delivery' );
	
    dialog_city_list = new YAHOO.widget.Dialog("dialog_city_list", {context:["city","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_city_list.render();
    Event.addListener("city", "click", show_city_list, 'billing');
	Event.addListener("city2", "click", show_city_list, 'delivery');

    dialog_postal_code_list = new YAHOO.widget.Dialog("dialog_postal_code_list", {context:["postal_code","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_postal_code_list.render();
    Event.addListener("postal_code", "click", show_postal_code_list, 'billing');
	Event.addListener("postal_code2", "click", show_postal_code_list, 'delivery');

    dialog_department_list = new YAHOO.widget.Dialog("dialog_department_list", {context:["department","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_department_list.render();
    Event.addListener("department", "click", dialog_department_list.show,dialog_department_list , true);

    dialog_family_list = new YAHOO.widget.Dialog("dialog_family_list", {context:["family","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_family_list.render();
    Event.addListener("family", "click", dialog_family_list.show,dialog_family_list , true);

    dialog_product_list = new YAHOO.widget.Dialog("dialog_product_list", {context:["product","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_product_list.render();
    Event.addListener("product", "click", dialog_product_list.show,dialog_product_list , true);

    dialog_category_list = new YAHOO.widget.Dialog("dialog_category_list", {context:["product_category","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_category_list.render();
    Event.addListener("product_category", "click", dialog_category_list.show,dialog_category_list , true);

YAHOO.util.Event.addListener(['submit_search','modify_search'], "click",submit_search);
YAHOO.util.Event.addListener(['product_ordered1'], "keydown",submit_search_on_enter);
YAHOO.util.Event.addListener(['save_list'], "click",save_search_list);

YAHOO.util.Event.addListener(['show_dont_wish_to_receive'], "click",show_dont_wish_to_receive);
YAHOO.util.Event.addListener(['show_dont_have'], "click",show_dont_have);


//var ids=['general','contact'];
//YAHOO.util.Event.addListener(ids, "click",change_view);

cal1 = new YAHOO.widget.Calendar("product_ordered_or_from","product_ordered_or_from_Container", { title:"<?php echo _('From Date')?>:", close:true } );
 cal1.update=updateCal;
 cal1.id='1';
 cal1.render();
 cal1.update();
 cal1.selectEvent.subscribe(handleSelect, cal1, true); 

 cal2 = new YAHOO.widget.Calendar("product_ordered_or_to","product_ordered_or_to_Container", { title:"<?php echo _('To Date')?>:", close:true } );
 cal2.update=updateCal;
 cal2.id='2';
 cal2.render();
 cal2.update();
 cal2.selectEvent.subscribe(handleSelect, cal2, true); 

cal3 = new YAHOO.widget.Calendar("order_created_from","order_created_from_Container", { title:"<?php echo _('From Date')?>:", close:true } );
 cal3.update=updateCal;
 cal3.id='3';
 cal3.render();
 cal3.update();
cal3.selectEvent.subscribe(handleSelect, cal3, true); 

cal4 = new YAHOO.widget.Calendar("order_created_to","order_created_to_Container", { title:"<?php echo _('To Date')?>:", close:true } );
 cal4.update=updateCal;
 cal4.id='4';
 cal4.render();
 cal4.update();
cal4.selectEvent.subscribe(handleSelect, cal4, true); 


/*


//cal2.cfg.setProperty("iframe", true);
//cal2.cfg.setProperty("zIndex", 10);

*/

YAHOO.util.Event.addListener("product_ordered_or_from", "click", cal1.show, cal1, true);
YAHOO.util.Event.addListener("product_ordered_or_to", "click", cal2.show, cal2, true);
YAHOO.util.Event.addListener("order_created_from", "click", cal3.show, cal3, true);
YAHOO.util.Event.addListener("order_created_to", "click", cal4.show, cal4, true);

}

YAHOO.util.Event.onDOMReady(init);



YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu1 = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	 oMenu1.render();
	 oMenu1.subscribe("show", oMenu1.focus);
	
    });
    YAHOO.util.Event.onContentReady("filtermenu2", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {  trigger: "filter_name2"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
     
    });
    YAHOO.util.Event.onContentReady("filtermenu3", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu3", {  trigger: "filter_name3"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
    YAHOO.util.Event.onContentReady("filtermenu4", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu4", {  trigger: "filter_name4"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
    

YAHOO.util.Event.onContentReady("filtermenu6", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu6", {  trigger: "filter_name6"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
YAHOO.util.Event.onContentReady("filtermenu7", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu7", {  trigger: "filter_name7"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 // oMenu.show()
    });    
YAHOO.util.Event.onContentReady("filtermenu5", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu5", {  trigger: "filter_name5"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });