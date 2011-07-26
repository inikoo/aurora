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
	
	var request="ar_edit_orders.php?tipo=new_dn_list&list_name="+list_name+'&list_type='+list_type+'&store_id='+store_id+'&awhere='+awhere;
	//alert(request);return;
	
		YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		  // alert(o.responseText);

		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if (r.state==200) {
			location.href='orders_list.php?id='+r.customer_list_key;

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
    



	

function hide_invoice(){
		Dom.setStyle('weight_upper','display','none')
		Dom.setStyle('a','display','none')
		Dom.get('weight_upper').value=''
}
 
function checkbox_changed_weight_condition(o){
	cat=Dom.get(o).getAttribute('cat');

	if(cat=='less'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			hide_invoice();
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'weight_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='equal'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			hide_invoice();
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'weight_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='more'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			hide_invoice();
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'weight_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='between'){
		Dom.setStyle('weight_upper','display','')
		Dom.setStyle('a','display','')
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'weight_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}
	
} 


function checkbox_changed_parcel_type_condition(o){
	cat=Dom.get(o).getAttribute('cat');

	if(cat=='box'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'parcel_type_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='pallet'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'parcel_type_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='envelope'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'parcel_type_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}
}

function checkbox_changed_dispatch_method_condition(o){
	cat=Dom.get(o).getAttribute('cat');

	if(cat=='dispatch'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'dispatch_method_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='collection'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'dispatch_method_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='unknown'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'dispatch_method_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='na'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'dispatch_method_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}
}


function checkbox_changed_note_type_condition(o){
	cat=Dom.get(o).getAttribute('cat');

	if(cat=='replacement_and_shortages'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'note_type_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='order'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'note_type_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='replacement'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'note_type_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='shortages'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'note_type_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='sample'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'note_type_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='donation'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'note_type_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}
}

function checkbox_changed_state_condition(o){
	cat=Dom.get(o).getAttribute('cat');

	if(cat=='picking_and_packing'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='packer_assigned'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='ready_to_be_picked'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='picker_assigned'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='picking'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='picked'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='packing'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='packed'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='approved'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='dispatched'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='cancelled'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}else if(cat=='cancelled_to_restock'){
		if(Dom.hasClass(o,'selected')){
			return;
		}else{
			Dom.removeClass(Dom.getElementsByClassName('catbox', 'span', 'state_option'),'selected');
			Dom.addClass(o,'selected');
		}
	}
	
} 


 
function select_country(oArgs){
    var geo_constraints=Dom.get('geo_constraints').value;
    if(geo_constraints!=''){geo_constraints=geo_constraints+','}
    geo_constraints=geo_constraints+tables.table2.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '');
    Dom.get('geo_constraints').value=geo_constraints;
    dialog_country_list.hide();
    hide_filter(true,2)
}

function select_postal_code(oArgs){
    var geo_constraints=Dom.get('geo_constraints').value;
    if(geo_constraints!=''){geo_constraints=geo_constraints+','}
    geo_constraints=geo_constraints+'pc('+tables.table3.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '')+')';
    Dom.get('geo_constraints').value=geo_constraints;
    dialog_postal_code_list.hide();
    hide_filter(true,3)
}

function select_wregion(oArgs){
    var geo_constraints=Dom.get('geo_constraints').value;
    if(geo_constraints!=''){geo_constraints=geo_constraints+','}
    geo_constraints=geo_constraints+'wr('+tables.table1.getRecord(oArgs.target).getData('wregion_code').replace(/<.*?>/g, '')+')';
    Dom.get('geo_constraints').value=geo_constraints;
    dialog_wregion_list.hide();
    hide_filter(true,1)
}

function select_city(oArgs){
    var geo_constraints=Dom.get('geo_constraints').value;
    if(geo_constraints!=''){geo_constraints=geo_constraints+','}
    geo_constraints=geo_constraints+'t('+tables.table4.getRecord(oArgs.target).getData('city').replace(/<.*?>/g, '')+')';
    Dom.get('geo_constraints').value=geo_constraints;
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
				       {key:"id", label:"<?php echo _('Number')?>", width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"date", label:"<?php echo _('Date')?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"type", label:"<?php echo _('Type')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"customer",label:"<?php echo _('Customer')?>", width:280,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"weight",label:"<?php echo _('Weight')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"parcels",label:"<?php echo _('Parcels')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				       


					 ];


	    this.dataSource0 = new YAHOO.util.DataSource("ar_orders.php?tipo=dn");
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
			 "id",
			 "type",
			 "customer",
			 "date",
			 "orders","invoices","weight","parcels"
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


	weight=Dom.getElementsByClassName('selected', 'span', 'weight_option');
	weight_array= new Array();
    for(x in weight){
        weight_array[x]=weight[x].getAttribute('cat');
    }

	state=Dom.getElementsByClassName('selected', 'span', 'state_option');
    state_array= new Array();
    for(x in state){
        state_array[x]=state[x].getAttribute('cat');
    }
	
	note_type=Dom.getElementsByClassName('selected', 'span', 'note_type_option');
    note_type_array= new Array();
    for(x in note_type){
        note_type_array[x]=note_type[x].getAttribute('cat');
    }
	
	dispatch_method=Dom.getElementsByClassName('selected', 'span', 'dispatch_method_option');
    dispatch_method_array= new Array();
    for(x in dispatch_method){
        dispatch_method_array[x]=dispatch_method[x].getAttribute('cat');
    }
	
	parcel_type=Dom.getElementsByClassName('selected', 'span', 'parcel_type_option');
    parcel_type_array= new Array();
    for(x in parcel_type){
        parcel_type_array[x]=parcel_type[x].getAttribute('cat');
    }
	
	
    var data={ 
    weight:weight_array,
	state:state_array,
	note_type:note_type_array,
	dispatch_method:dispatch_method_array,
	parcel_type:parcel_type_array,
	created_date_from:Dom.get('v_calpop1').value,
	created_date_to:Dom.get('v_calpop2').value,
	start_picking_date_from:Dom.get('v_calpop3').value,
	start_picking_date_to:Dom.get('v_calpop4').value,
	finish_picking_date_from:Dom.get('v_calpop5').value,
	finish_picking_date_to:Dom.get('v_calpop6').value,
	start_packing_date_from:Dom.get('v_calpop7').value,
	start_packing_date_to:Dom.get('v_calpop8').value,
	finish_packing_date_from:Dom.get('v_calpop9').value,
	finish_packing_date_to:Dom.get('v_calpop10').value,
	dispatched_approved_date_from:Dom.get('v_calpop11').value,
	dispatched_approved_date_to:Dom.get('v_calpop12').value,
	delivery_note_date_from:Dom.get('v_calpop13').value,
	delivery_note_date_to:Dom.get('v_calpop14').value,
	billing_geo_constraints:Dom.get('billing_geo_constraints').value,
	weight_lower:Dom.get('weight_lower').value,
	weight_upper:Dom.get('weight_upper').value
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


	YAHOO.util.Event.addListener(['submit_search','modify_search'], "click",submit_search);
	YAHOO.util.Event.addListener(['product_ordered1'], "keydown",submit_search_on_enter);
	YAHOO.util.Event.addListener(['save_list'], "click",save_search_list);



//var ids=['general','contact'];
//YAHOO.util.Event.addListener(ids, "click",change_view);

cal1 = new YAHOO.widget.Calendar("created_date_from","created_date_from_Container", { title:"<?php echo _('From Date')?>:", close:true } );
 cal1.update=updateCal;
 cal1.id='1';
 cal1.render();
 cal1.update();
 cal1.selectEvent.subscribe(handleSelect, cal1, true); 

 cal2 = new YAHOO.widget.Calendar("created_date_to","created_date_to_Container", { title:"<?php echo _('To Date')?>:", close:true } );
 cal2.update=updateCal;
 cal2.id='2';
 cal2.render();
 cal2.update();
 cal2.selectEvent.subscribe(handleSelect, cal2, true); 

cal3 = new YAHOO.widget.Calendar("start_picking_date_from","start_picking_date_from_Container", { title:"<?php echo _('From Date')?>:", close:true } );
 cal3.update=updateCal;
 cal3.id='3';
 cal3.render();
 cal3.update();
cal3.selectEvent.subscribe(handleSelect, cal3, true); 

cal4 = new YAHOO.widget.Calendar("start_picking_date_to","start_picking_date_to_Container", { title:"<?php echo _('To Date')?>:", close:true } );
 cal4.update=updateCal;
 cal4.id='4';
 cal4.render();
 cal4.update();
cal4.selectEvent.subscribe(handleSelect, cal4, true); 

cal5 = new YAHOO.widget.Calendar("finish_picking_date_from","finish_picking_date_from_Container", { title:"<?php echo _('From Date')?>:", close:true } );
 cal5.update=updateCal;
 cal5.id='5';
 cal5.render();
 cal5.update();
 cal5.selectEvent.subscribe(handleSelect, cal5, true); 

 cal6 = new YAHOO.widget.Calendar("finish_picking_date_to","finish_picking_date_to_Container", { title:"<?php echo _('To Date')?>:", close:true } );
 cal6.update=updateCal;
 cal6.id='6';
 cal6.render();
 cal6.update();
 cal6.selectEvent.subscribe(handleSelect, cal6, true); 

cal7 = new YAHOO.widget.Calendar("start_packing_date_from","start_packing_date_from_Container", { title:"<?php echo _('From Date')?>:", close:true } );
 cal7.update=updateCal;
 cal7.id='7';
 cal7.render();
 cal7.update();
cal7.selectEvent.subscribe(handleSelect, cal7, true); 

cal8 = new YAHOO.widget.Calendar("start_packing_date_to","start_packing_date_to_Container", { title:"<?php echo _('To Date')?>:", close:true } );
 cal8.update=updateCal;
 cal8.id='8';
 cal8.render();
 cal8.update();
cal8.selectEvent.subscribe(handleSelect, cal8, true); 

cal9 = new YAHOO.widget.Calendar("finish_packing_date_from","finish_packing_date_from_Container", { title:"<?php echo _('From Date')?>:", close:true } );
 cal9.update=updateCal;
 cal9.id='9';
 cal9.render();
 cal9.update();
 cal9.selectEvent.subscribe(handleSelect, cal9, true); 

 cal10 = new YAHOO.widget.Calendar("finish_packing_date_to","finish_packing_date_to_Container", { title:"<?php echo _('To Date')?>:", close:true } );
 cal10.update=updateCal;
 cal10.id='10';
 cal10.render();
 cal10.update();
 cal10.selectEvent.subscribe(handleSelect, cal10, true); 

cal11 = new YAHOO.widget.Calendar("dispatched_approved_date_from","dispatched_approved_date_from_Container", { title:"<?php echo _('From Date')?>:", close:true } );
 cal11.update=updateCal;
 cal11.id='11';
 cal11.render();
 cal11.update();
cal11.selectEvent.subscribe(handleSelect, cal11, true); 

cal12 = new YAHOO.widget.Calendar("dispatched_approved_date_to","dispatched_approved_date_to_Container", { title:"<?php echo _('To Date')?>:", close:true } );
 cal12.update=updateCal;
 cal12.id='12';
 cal12.render();
 cal12.update();
cal12.selectEvent.subscribe(handleSelect, cal12, true); 

cal13 = new YAHOO.widget.Calendar("delivery_note_date_from","delivery_note_date_from_Container", { title:"<?php echo _('From Date')?>:", close:true } );
 cal13.update=updateCal;
 cal13.id='13';
 cal13.render();
 cal13.update();
cal13.selectEvent.subscribe(handleSelect, cal13, true); 

cal14 = new YAHOO.widget.Calendar("delivery_note_date_to","delivery_note_date_to_Container", { title:"<?php echo _('To Date')?>:", close:true } );
 cal14.update=updateCal;
 cal14.id='14';
 cal14.render();
 cal14.update();
cal14.selectEvent.subscribe(handleSelect, cal14, true); 


//cal2.cfg.setProperty("iframe", true);
//cal2.cfg.setProperty("zIndex", 10);



	YAHOO.util.Event.addListener("created_date_from", "click", cal1.show, cal1, true);
	YAHOO.util.Event.addListener("created_date_to", "click", cal2.show, cal2, true);
	YAHOO.util.Event.addListener("start_picking_date_from", "click", cal3.show, cal3, true);
	YAHOO.util.Event.addListener("start_picking_date_to", "click", cal4.show, cal4, true);
	YAHOO.util.Event.addListener("finish_picking_date_from", "click", cal5.show, cal5, true);
	YAHOO.util.Event.addListener("finish_picking_date_to", "click", cal6.show, cal6, true);
	YAHOO.util.Event.addListener("start_packing_date_from", "click", cal7.show, cal7, true);
	YAHOO.util.Event.addListener("start_packing_date_to", "click", cal8.show, cal8, true);
	YAHOO.util.Event.addListener("finish_packing_date_from", "click", cal9.show, cal9, true);
	YAHOO.util.Event.addListener("finish_packing_date_to", "click", cal10.show, cal10, true);
	YAHOO.util.Event.addListener("dispatched_approved_date_from", "click", cal11.show, cal11, true);
	YAHOO.util.Event.addListener("dispatched_approved_date_to", "click", cal12.show, cal12, true);
	YAHOO.util.Event.addListener("delivery_note_date_from", "click", cal13.show, cal13, true);
	YAHOO.util.Event.addListener("delivery_note_date_to", "click", cal14.show, cal14, true);	
	

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