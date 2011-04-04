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
function save_search_list()
{


	
	var store_id=Dom.get('store_id').value;
	var list_name = Dom.get('list_name').value;
	
	if(list_name==''){
	Dom.get('save_list_msg').innerHTML=Dom.get('error_no_name').innerHTML;
	return;
	}
	
	
	if(Dom.get('dynamic').checked == true)
	{
		var list_type = 'Dynamic'
	}else{
	var list_type = 'Static';	
	}
	
	var awhere=get_awhere();
	
	var request="ar_contacts.php?tipo=new_list&list_name="+list_name+'&list_type='+list_type+'&store_id='+store_id+'&awhere='+awhere;
	
	
		YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		  // alert(o.responseText);

		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if (r.state==200) {
			location.href='customers_list.php?id='+r.customer_list_key;

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
    
    
    function checkbox_changed_have(o){

cat=Dom.get(o).getAttribute('cat');
this_parent=Dom.get(o).getAttribute('parent');
if(this_parent=='have_')
    other_parent='dont_have_';
else//
    other_parent='have_';
    
if(    Dom.hasClass(o,'selected')){
    Dom.removeClass(o,'selected');
        

}else{
    Dom.addClass(o,'selected');
    Dom.removeClass(other_parent+cat,'selected');

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
    geo_constraints=geo_constraints+tables.table3.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '');
    Dom.get('geo_constraints').value=geo_constraints;
    dialog_postal_code_list.hide();
    hide_filter(true,3)
}

function select_wregion(oArgs){
    var geo_constraints=Dom.get('geo_constraints').value;
    if(geo_constraints!=''){geo_constraints=geo_constraints+','}
    geo_constraints='wr('+geo_constraints+tables.table1.getRecord(oArgs.target).getData('wregion_code').replace(/<.*?>/g, '')+')';
    Dom.get('geo_constraints').value=geo_constraints;
    dialog_wregion_list.hide();
    hide_filter(true,1)
}

function select_city(oArgs){
    var geo_constraints=Dom.get('geo_constraints').value;
    if(geo_constraints!=''){geo_constraints=geo_constraints+','}
    geo_constraints=geo_constraints+tables.table4.getRecord(oArgs.target).getData('city').replace(/<.*?>/g, '');
    Dom.get('geo_constraints').value=geo_constraints;
    dialog_wregion_list.hide();
    hide_filter(true,4)
}
function select_department(oArgs){
    var product_ordered_or=Dom.get('product_ordered_or').value;
    if(product_ordered_or!=''){product_ordered_or=product_ordered_or+','}
    product_ordered_or=product_ordered_or+tables.table5.getRecord(oArgs.target).getData('department_code').replace(/<.*?>/g, '');
    Dom.get('product_ordered_or').value=product_ordered_or;
    dialog_department_list.hide();
    hide_filter(true,5)
}

function select_family(oArgs){
    var product_ordered_or=Dom.get('product_ordered_or').value;
    if(product_ordered_or!=''){product_ordered_or=product_ordered_or+','}
    product_ordered_or=product_ordered_or+tables.table6.getRecord(oArgs.target).getData('family_code').replace(/<.*?>/g, '');
    Dom.get('product_ordered_or').value=product_ordered_or;
    dialog_family_list.hide();
    hide_filter(true,6)
}
function select_product(oArgs){
    var product_ordered_or=Dom.get('product_ordered_or').value;
    if(product_ordered_or!=''){product_ordered_or=product_ordered_or+','}
    product_ordered_or=product_ordered_or+tables.table7.getRecord(oArgs.target).getData('product_code').replace(/<.*?>/g, '');
    Dom.get('product_ordered_or').value=product_ordered_or;
    dialog_product_list.hide();
    hide_filter(true,7)
}
function select_category(oArgs){
    var product_ordered_or=Dom.get('product_ordered_or').value;
    if(product_ordered_or!=''){product_ordered_or=product_ordered_or+','}
    product_ordered_or=product_ordered_or+tables.table8.getRecord(oArgs.target).getData('category_code').replace(/<.*?>/g, '');
    Dom.get('product_ordered_or').value=product_ordered_or;
    dialog_category_list.hide();
    hide_filter(true,8)
}
    
YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


		

	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				        {key:"id", label:"<?php echo$customers_ids[0]?>",width:45,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"name", label:"<?php echo _('Customer Name')?>", width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"location", label:"<?php echo _('Location')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?> width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       	,{key:"contact_since", label:"<?php echo _('Since')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>width:85,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"last_order", label:"<?php echo _('Last Order')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>width:85,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"orders", label:"<?php echo _('Orders')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"activity", label:"<?php echo _('Status')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"contact_name", label:"<?php echo _('Contact Name')?>",width:160,<?php echo($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"email", label:"<?php echo _('Email')?>",width:210,<?php echo($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"telephone", label:"<?php echo _('Telephone')?>", width:137,<?php echo($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},className:"aright"}
				       ,{key:"address", label:"<?php echo _('Contact Address')?>", width:176,<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aleft"}
				       ,{key:"billing_address", label:"<?php echo _('Billing Address')?>", width:170,<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aleft"}
				       ,{key:"delivery_address", label:"<?php echo _('Delivery Address')?>", width:170,<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aleft"}
				       ,{key:"total_payments", label:"<?php echo _('Payments')?>",width:99,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"total_refunds", label:"<?php echo _('Refunds')?>",width:90,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"net_balance", label:"<?php echo _('Balance')?>",width:90,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"balance", label:"<?php echo _('Outstanding')?>",width:90,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"total_profit", label:"<?php echo _('Profit')?>",width:90,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_orders", label:"<?php echo _('Rank Orders')?>",width:121,<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_invoices", label:"<?php echo _('Rank Invoices')?>",width:121,<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_balance", label:"<?php echo _('Rank Balance')?>",width:120,<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_profits", label:"<?php echo _('Rank Profits')?>",width:120,<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ];


	    this.dataSource0 = new YAHOO.util.DataSource("ar_contacts.php?tipo=customers");
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
			 'name',
			 'location',
			 'orders',
			 'email',
			 'telephone',
			 'last_order','activity',
			 'total_payments','contact_name'
			 ,"address"
			 ,"billing_address","delivery_address"
			 ,"total_paymants","total_refunds","net_balance","total_profit","balance","contact_since"
			 ,"top_orders","top_invoices","top_balance","top_profits"
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


//=============

   var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

 this.remove_links = function(elLiner, oRecord, oColumn, oData) {
  elLiner.innerHTML = oData;
         //   if(oRecord.getData("field3") > 100) {
       elLiner.innerHTML=  oData.replace(/<.*?>/g, '')

        };
        
        // Add the custom formatter to the shortcuts
        YAHOO.widget.DataTable.Formatter.remove_links = this.remove_links;

	   
	    var ColumnDefs = [
			
			
                    {key:"flag", label:"",width:10,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

                   ,{key:"code",formatter:"remove_links", label:"<?php echo _('Code')?>",width:30,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			       ,{key:"name", formatter:"remove_links",label:"<?php echo _('Name')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

			     // ,{key:"population", label:"<?php echo _('Population')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			     //  ,{key:"gnp", label:"<?php echo _('GNP')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			      
			   //   ,{key:"wregion", label:"<?php echo _('Region')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				 
			      
	     


			
			
			];
			       
	    this.dataSource2 = new YAHOO.util.DataSource("ar_regions.php?tipo=country_list&tableid=2&nr=20&sf=0");
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
			 "name","flag",'code','population','gnp','wregion'
			 ]};

	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource2
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['world']['countries']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['world']['countries']['order']?>",
									 dir: "<?php echo$_SESSION['state']['world']['countries']['order_dir']?>"
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
	    this.table2.filter={key:'<?php echo$_SESSION['state']['world']['countries']['f_field']?>',value:'<?php echo$_SESSION['state']['world']['countries']['f_value']?>'};
	    //YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown);

// --------------------------------------Postal code table starts here--------------------------------------------------------
//=============

   var tableid=3; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

 this.remove_links = function(elLiner, oRecord, oColumn, oData) {
  elLiner.innerHTML = oData;
         //   if(oRecord.getData("field3") > 100) {
       elLiner.innerHTML=  oData.replace(/<.*?>/g, '')

        };
        
        // Add the custom formatter to the shortcuts
        YAHOO.widget.DataTable.Formatter.remove_links = this.remove_links;

	   
	    var ColumnDefs = [
			
			
                    {key:"flag", label:"",width:10,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

                   ,{key:"code",formatter:"remove_links", label:"<?php echo _('Postal Code')?>",width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			       ,{key:"name", formatter:"remove_links",label:"<?php echo _('Country Name')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

			     // ,{key:"population", label:"<?php echo _('Population')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			     //  ,{key:"gnp", label:"<?php echo _('GNP')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			      
			   //   ,{key:"wregion", label:"<?php echo _('Region')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				 
			      
	     


			
			
			];
			       
	    this.dataSource3 = new YAHOO.util.DataSource("ar_regions.php?tipo=postal_code&tableid=3&nr=20&sf=0");
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
			 "name","flag",'code','population','gnp','wregion'
			 ]};

	    this.table3 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource3
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['world']['countries']['nr']?>,containers : 'paginator3', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['world']['countries']['order']?>",
									 dir: "<?php echo$_SESSION['state']['world']['countries']['order_dir']?>"
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
	    this.table3.filter={key:'<?php echo$_SESSION['state']['world']['countries']['f_field']?>',value:'<?php echo$_SESSION['state']['world']['countries']['f_value']?>'};
	    //YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown);
// --------------------------------------Postal code table ends here----------------------------------------------------------

// --------------------------------------City code table starts here--------------------------------------------------------
   var tableid=4; 
	    var tableDivEL="table"+tableid;

 this.remove_links = function(elLiner, oRecord, oColumn, oData) {
  elLiner.innerHTML = oData;
         //   if(oRecord.getData("field3") > 100) {
       elLiner.innerHTML=  oData.replace(/<.*?>/g, '')

        };
        
        // Add the custom formatter to the shortcuts
        YAHOO.widget.DataTable.Formatter.remove_links = this.remove_links;

	   
	    var ColumnDefs = [
			
			
                    {key:"flag", label:"",width:10,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

                   ,{key:"city",formatter:"remove_links", label:"<?php echo _('City Name')?>",width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			       ,{key:"name", formatter:"remove_links",label:"<?php echo _('Country Name')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

			     // ,{key:"population", label:"<?php echo _('Population')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			     //  ,{key:"gnp", label:"<?php echo _('GNP')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			      
			   //   ,{key:"wregion", label:"<?php echo _('Region')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				 
			      
	     


			
			
			];
			       
	    this.dataSource4 = new YAHOO.util.DataSource("ar_regions.php?tipo=city&tableid=4&nr=20&sf=0");
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
			 "name","flag",'city','population','gnp','wregion'
			 ]};

	    this.table4 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource4
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['world']['city']['nr']?>,containers : 'paginator4', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['world']['city']['order']?>",
									 dir: "<?php echo$_SESSION['state']['world']['city']['order_dir']?>"
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
	    this.table4.filter={key:'<?php echo$_SESSION['state']['world']['city']['f_field']?>',value:'<?php echo$_SESSION['state']['world']['city']['f_value']?>'};
	    //YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown);
// --------------------------------------City code table ends here----------------------------------------------------------


// --------------------------------------Department table starts here--------------------------------------------------------
   var tableid=5; 
	    var tableDivEL="table"+tableid;

 this.remove_links = function(elLiner, oRecord, oColumn, oData) {
  elLiner.innerHTML = oData;
         //   if(oRecord.getData("field3") > 100) {
       elLiner.innerHTML=  oData.replace(/<.*?>/g, '')

        };
        
        // Add the custom formatter to the shortcuts
        YAHOO.widget.DataTable.Formatter.remove_links = this.remove_links;

	   
	    var ColumnDefs = [
			
			 {key:"store_code", formatter:"remove_links",label:"<?php echo _('Store Code')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                    ,{key:"department_code", label:"<?php echo _('Product Department Code')?>",width:160,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

                   ,{key:"department_name",formatter:"remove_links", label:"<?php echo _('Product Department Name')?>",width:160,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      

			     // ,{key:"population", label:"<?php echo _('Population')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			     //  ,{key:"gnp", label:"<?php echo _('GNP')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			      
			   //   ,{key:"wregion", label:"<?php echo _('Region')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				 
			      
	     


			
			
			];
			       
	    this.dataSource5 = new YAHOO.util.DataSource("ar_regions.php?tipo=department&tableid=5&nr=20&sf=0");
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
			 "store_code","department_code",'department_name','population','gnp','wregion'
			 ]};

	    this.table5 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource5
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['world']['department']['nr']?>,containers : 'paginator5', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info5'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['world']['department']['order']?>",
									 dir: "<?php echo$_SESSION['state']['world']['department']['order_dir']?>"
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
     


	    this.table5.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table5.filter={key:'<?php echo$_SESSION['state']['world']['department']['f_field']?>',value:'<?php echo$_SESSION['state']['world']['department']['f_value']?>'};
	    //YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown);
// --------------------------------------Department table ends here----------------------------------------------------------


// --------------------------------------Family table starts here--------------------------------------------------------
   var tableid=6; 
	    var tableDivEL="table"+tableid;

 this.remove_links = function(elLiner, oRecord, oColumn, oData) {
  elLiner.innerHTML = oData;
         //   if(oRecord.getData("field3") > 100) {
       elLiner.innerHTML=  oData.replace(/<.*?>/g, '')

        };
        
        // Add the custom formatter to the shortcuts
        YAHOO.widget.DataTable.Formatter.remove_links = this.remove_links;

	   
	    var ColumnDefs = [
			
			 {key:"store_code", formatter:"remove_links",label:"<?php echo _('Store Code')?>",width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                    ,{key:"family_code", label:"<?php echo _('Product Family Code')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

                   ,{key:"family_name",formatter:"remove_links", label:"<?php echo _('Product Family Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      

			     // ,{key:"population", label:"<?php echo _('Population')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			     //  ,{key:"gnp", label:"<?php echo _('GNP')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			      
			   //   ,{key:"wregion", label:"<?php echo _('Region')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				 
			      
	     


			
			
			];
			       
	    this.dataSource6 = new YAHOO.util.DataSource("ar_regions.php?tipo=family&tableid=6&nr=20&sf=0");
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
			 "store_code","family_code",'family_name','population','gnp','wregion'
			 ]};

	    this.table6 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource6
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['world']['family']['nr']?>,containers : 'paginator6', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info6'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['world']['family']['order']?>",
									 dir: "<?php echo$_SESSION['state']['world']['family']['order_dir']?>"
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
     


	    this.table6.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table6.filter={key:'<?php echo$_SESSION['state']['world']['family']['f_field']?>',value:'<?php echo$_SESSION['state']['world']['family']['f_value']?>'};
	    //YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown);
// --------------------------------------Family table ends here----------------------------------------------------------



// --------------------------------------Product table starts here--------------------------------------------------------
   var tableid=7; 
	    var tableDivEL="table"+tableid;

 this.remove_links = function(elLiner, oRecord, oColumn, oData) {
  elLiner.innerHTML = oData;
         //   if(oRecord.getData("field3") > 100) {
       elLiner.innerHTML=  oData.replace(/<.*?>/g, '')

        };
        
        // Add the custom formatter to the shortcuts
        YAHOO.widget.DataTable.Formatter.remove_links = this.remove_links;

	   
	    var ColumnDefs = [
			
			// {key:"store_code", formatter:"remove_links",label:"<?php echo _('Store Code')?>",width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                    {key:"product_code", label:"<?php echo _('Product Code')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

                   ,{key:"product_name",formatter:"remove_links", label:"<?php echo _('Product Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      

			     // ,{key:"population", label:"<?php echo _('Population')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			     //  ,{key:"gnp", label:"<?php echo _('GNP')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			      
			   //   ,{key:"wregion", label:"<?php echo _('Region')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				 
			      
	     


			
			
			];
			       
	    this.dataSource7 = new YAHOO.util.DataSource("ar_regions.php?tipo=product&tableid=7&nr=20&sf=0");
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
			 "store_code","product_code",'product_name','population','gnp','wregion'
			 ]};

	    this.table7 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource7
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['world']['product']['nr']?>,containers : 'paginator7', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info7'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['world']['product']['order']?>",
									 dir: "<?php echo$_SESSION['state']['world']['product']['order_dir']?>"
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
	    this.table7.filter={key:'<?php echo$_SESSION['state']['world']['family']['f_field']?>',value:'<?php echo$_SESSION['state']['world']['family']['f_value']?>'};
	    //YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown);
// --------------------------------------Product table ends here----------------------------------------------------------


// -----------------------------------------------world regions table starts here --------------
var tableid=1;
		      var tableDivEL="table"+tableid;
		      
		      var ColumnDefs = [
		      		{key:"wregion_code",formatter:"remove_links", label:"<?php echo _('Code')?>",width:30, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"wregion_name",formatter:"remove_links", label:"<?php echo _('World Region')?>",width:120, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			     	,{key:"flags", label:"<?php echo _('Countries')?>",width:240, sortable:false,className:"aleft"}

					];
		    
		      
		      this.dataSource1 = new YAHOO.util.DataSource("ar_regions.php?tipo=wregion&tableid=1&nr=20&sf=0");
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
								      Key: "<?php echo $_SESSION['state']['world']['wregions']['order']?>",
								       dir: "<?php echo $_SESSION['state']['world']['wregions']['order_dir']?>"
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
     

                   
	    this.table1.filter={key:'<?php echo$_SESSION['state']['world']['wregions']['f_field']?>',value:'<?php echo$_SESSION['state']['world']['wregions']['f_value']?>'};
// -------------------------------------------------- continents table starts here --------------------------------------

	
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

    var data={ 
    dont_have:dont_have_array,
    have:have_array,

	geo_constraints:Dom.get('geo_constraints').value,

	product_ordered1:Dom.get('product_ordered_or').value,
	//	product_ordered2: Dom.get('product_ordered2').value,
	product_not_ordered1: Dom.get('product_not_ordered1').value,
	//	product_not_ordered2: Dom.get('product_not_ordered2').value,
	product_not_received1: Dom.get('product_not_received1').value,
	//	product_not_received2: Dom.get('product_not_received2').value,
	ordered_from:Dom.get('v_calpop1').value,
	ordered_to:Dom.get('v_calpop2').value,
customer_created_from:Dom.get('v_calpop3').value,
	customer_created_to:Dom.get('v_calpop4').value,
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

//alert(request)
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



function init(){


YAHOO.util.Event.addListener('clean_table_filter_show2', "click",show_filter,2);
 YAHOO.util.Event.addListener('clean_table_filter_hide2', "click",hide_filter,2);
 var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS2.queryMatchContains = true;
 oACDS2.table_id=2;
 var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2","f_container2", oACDS2);
 oAutoComp2.minQueryLength = 0; 
 
 YAHOO.util.Event.addListener('clean_table_filter_show1', "click",show_filter,1);
 YAHOO.util.Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);
 var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS1.queryMatchContains = true;
 oACDS1.table_id=1;
 var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS1);
 oAutoComp1.minQueryLength = 0; 

    dialog_country_list = new YAHOO.widget.Dialog("dialog_country_list", {context:["country","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_country_list.render();
    Event.addListener("country", "click", dialog_country_list.show,dialog_country_list , true);

    dialog_wregion_list = new YAHOO.widget.Dialog("dialog_wregion_list", {context:["wregion","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_wregion_list.render();
    Event.addListener("wregion", "click", dialog_wregion_list.show,dialog_wregion_list , true);

    dialog_city_list = new YAHOO.widget.Dialog("dialog_city_list", {context:["city","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_city_list.render();
    Event.addListener("city", "click", dialog_city_list.show,dialog_city_list , true);


    dialog_postal_code_list = new YAHOO.widget.Dialog("dialog_postal_code_list", {context:["postal_code","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_postal_code_list.render();
    Event.addListener("postal_code", "click", dialog_postal_code_list.show,dialog_postal_code_list , true);

    dialog_department_list = new YAHOO.widget.Dialog("dialog_department_list", {context:["department","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_department_list.render();
    Event.addListener("department", "click", dialog_department_list.show,dialog_department_list , true);

    dialog_family_list = new YAHOO.widget.Dialog("dialog_family_list", {context:["family","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_family_list.render();
    Event.addListener("family", "click", dialog_family_list.show,dialog_family_list , true);

    dialog_product_list = new YAHOO.widget.Dialog("dialog_product_list", {context:["product","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_product_list.render();
    Event.addListener("product", "click", dialog_product_list.show,dialog_product_list , true);

    dialog_category_list = new YAHOO.widget.Dialog("dialog_category_list", {context:["category","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_category_list.render();
    Event.addListener("category", "click", dialog_category_list.show,dialog_category_list , true);

YAHOO.util.Event.addListener(['submit_search','modify_search'], "click",submit_search);
YAHOO.util.Event.addListener(['product_ordered1'], "keydown",submit_search_on_enter);
YAHOO.util.Event.addListener(['save_list'], "click",save_search_list);


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

cal3 = new YAHOO.widget.Calendar("customer_first_contacted_from","customer_first_contacted_from_Container", { title:"<?php echo _('From Date')?>:", close:true } );
 cal3.update=updateCal;
 cal3.id='3';
 cal3.render();
 cal3.update();
cal3.selectEvent.subscribe(handleSelect, cal3, true); 

cal4 = new YAHOO.widget.Calendar("customer_first_contacted_to","customer_first_contacted_to_Container", { title:"<?php echo _('To Date')?>:", close:true } );
 cal4.update=updateCal;
 cal4.id='4';
 cal4.render();
 cal4.update();
cal4.selectEvent.subscribe(handleSelect, cal4, true); 





//cal2.cfg.setProperty("iframe", true);
//cal2.cfg.setProperty("zIndex", 10);



YAHOO.util.Event.addListener("product_ordered_or_from", "click", cal1.show, cal1, true);
YAHOO.util.Event.addListener("product_ordered_or_to", "click", cal2.show, cal2, true);
YAHOO.util.Event.addListener("customer_first_contacted_from", "click", cal3.show, cal3, true);
YAHOO.util.Event.addListener("customer_first_contacted_to", "click", cal4.show, cal4, true);

}

YAHOO.util.Event.onDOMReady(init);



YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
    });

