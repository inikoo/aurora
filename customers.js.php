<?php
include_once('common.php');
?>
   var Event = YAHOO.util.Event;
     var Dom   = YAHOO.util.Dom;

var category_labels={'total':'<?php echo _('Number')?>','growth':'<?php echo _('Growth')?>'};
var period_labels={'m':'<?php echo _('Montly')?>','y':'<?php echo _('Yearly')?>','w':'<?php echo _('Weekly')?>','q':'<?php echo _('Quarterly')?>'};
var pie_period_labels={'m':'<?php echo _('Month')?>','y':'<?php echo _('Year')?>','w':'<?php echo _('Week')?>','q':'<?php echo _('Quarter')?>'};


function  new_customer_from_file(){
location.href='import_data.php?tipo=customers_store';
}

function close_dialog(tipo){
	dialog_new_customer.hide();
}

function new_customer(tipo){
    location.href='new_customer.php?tipo='+tipo;
    dialog_new_customer.hide();
}




YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {



	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
				       {key:"id", label:"<?php echo$customers_ids[0]?>",width:60,sortable:true,<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"name", label:"<?php echo _('Customer Name')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"location", label:"<?php echo _('Location')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?> width:230,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"last_order", label:"<?php echo _('Last Order')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"orders", label:"<?php echo _('Orders')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"activity", label:"<?php echo _('Status')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       
				       // ,{key:"total_payments", label:"<?php echo _('Total')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}} 
				       ,{key:"contact_name", label:"<?php echo _('Contact Name')?>",<?php echo($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       
				       ,{key:"email", label:"<?php echo _('Email')?>",<?php echo($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"telephone", label:"<?php echo _('Telephone')?>",<?php echo($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       
				       ,{key:"address", label:"<?php echo _('Main Address')?>",<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"town", label:"<?php echo _('Town')?>",<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"postcode", label:"<?php echo _('Postal Code')?>",<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"region", label:"<?php echo _('Region')?>",<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"country", label:"<?php echo _('Country')?>",<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       //				       ,{key:"ship_address", label:"<?php echo _('Ship to Address')?>",<?php echo($_SESSION['state']['customers']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"ship_town", label:"<?php echo _('Town')?>",<?php echo($_SESSION['state']['customers']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"ship_postcode", label:"<?php echo _('Postal Code')?>",<?php echo($_SESSION['state']['customers']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"ship_region", label:"<?php echo _('Region')?>",<?php echo($_SESSION['state']['customers']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"ship_country", label:"<?php echo _('Country')?>",<?php echo($_SESSION['state']['customers']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"total_payments", label:"<?php echo _('Payments')?>",<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"total_refunds", label:"<?php echo _('Refunds')?>",<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"net_balance", label:"<?php echo _('Balance')?>",<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"balance", label:"<?php echo _('Outstanding')?>",<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"total_profit", label:"<?php echo _('Profit')?>",<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_orders", label:"<?php echo _('Rank Orders')?>",<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_invoices", label:"<?php echo _('Rank Invoices')?>",<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_balance", label:"<?php echo _('Rank Balance')?>",<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_profits", label:"<?php echo _('Rank Profits')?>",<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				       

					 ];
	    //?tipo=customers&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_contacts.php?tipo=customers");
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
			 'id',
			 'name',
			 'location',
			 'orders',
			 'email',
			 'telephone',
			 'last_order','activity',
			 'total_payments','contact_name'
			 ,"address","town","postcode","region","country"
			 ,"ship_address","ship_town","ship_postcode","ship_region","ship_country"
			 ,"total_paymants","total_refunds","net_balance","total_profit","balance"
			 ,"top_orders","top_invoices","top_balance","top_profits"
			 ]};
	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;



	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
								     // sortedBy: {key:"<?php echo$_SESSION['tables']['customers_list'][0]?>", dir:"<?php echo$_SESSION['tables']['customers_list'][1]?>"},
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customers']['table']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customers']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customers']['table']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table0.view='<?php echo$_SESSION['state']['customers']['view']?>';

	    this.table0.filter={key:'<?php echo$_SESSION['state']['customers']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['table']['f_value']?>'};

	    //   YAHOO.util.Event.addListener('f_input', "keyup",myFilterChangeValue,{table:this.table0,datasource:this.dataSource})
			 
	    
	    //	    var Dom   = YAHOO.util.Dom;
	    //alert(Dom.get('f_input'));

	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)
	
	};
    });




 function init(){
// -------------------------Export(CSV) code for customer list under order --------------------
  YAHOO.util.Event.addListener('export_csv0', "click",download_csv,'customers');
 YAHOO.util.Event.addListener('export_csv0_in_dialog', "click",download_csv_from_dialog,{table:'export_csv_table0',tipo:'customers'});
  csvMenu = new YAHOO.widget.ContextMenu("export_csv_menu0", {trigger:"export_csv0" });
	 csvMenu.render();
	 csvMenu.subscribe("show", csvMenu.focus);
   
 YAHOO.util.Event.addListener('export_csv0_close_dialog', "click",csvMenu.hide,csvMenu,true);
// ------------------------------------------------------------------------------------------
  init_search('customers_store');

YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
 


 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 



function change_table_type(){


	var table_id=0;
   
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];

    var request='&sf=0&type=' +this.getAttribute('table_type');
    Dom.removeClass('restrictions_all_contacts','selected');
      Dom.removeClass('restrictions_all_customers','selected');
    Dom.removeClass('restrictions_active_customers','selected');

  Dom.addClass(this.id,'selected');
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);      

}


  var change_view=function(e){
      var tipo=this.id;
      var table=tables['table0'];
      old_view=table.view;
      
      Dom.get('general').className='';
      Dom.get('contact').className='';
      Dom.get('address').className='';
      Dom.get('ship_to_address').className='';
      Dom.get('balance').className='';
      Dom.get('rank').className='';
      
      Dom.get(tipo).className='selected';
      table.hideColumn('location');
      table.hideColumn('last_order');
      table.hideColumn('orders');
      
      table.hideColumn('email');
      table.hideColumn('telephone');
      table.hideColumn('contact_name');
      
      table.hideColumn('address');
      table.hideColumn('town');
      table.hideColumn('postcode');
      table.hideColumn('region');
      table.hideColumn('country');
      //      table.hideColumn('ship_address');
      table.hideColumn('ship_town');
      table.hideColumn('ship_postcode');
      table.hideColumn('ship_region');
      table.hideColumn('ship_country');
      table.hideColumn('total_payments');
      table.hideColumn('net_balance');
      table.hideColumn('total_refunds');
      table.hideColumn('total_profit');

      table.hideColumn('balance');
      table.hideColumn('top_orders');
      table.hideColumn('top_invoices');
      table.hideColumn('top_balance');
      table.hideColumn('top_profits');
      table.hideColumn('activity');



      if(tipo=='general'){
	  table.showColumn('name');
	  table.showColumn('location');
	  table.showColumn('last_order');
	  table.showColumn('orders');
	  table.showColumn('activity');
	  Dom.get('general').className='selected';
      }else if(tipo=='contact'){
	  table.showColumn('name');
	  table.showColumn('contact_name');
	  table.showColumn('email');
	  table.showColumn('telephone');

      }else if(tipo=='address'){
	  table.showColumn('address');
	  table.showColumn('town');
	  table.showColumn('postcode');
	  table.showColumn('region');
	  table.showColumn('country');
	  Dom.get('address').className='selected';
      }else if(tipo=='ship_to_address'){
	//	  table.showColumn('ship_address');
	  table.showColumn('ship_town');
	  table.showColumn('ship_postcode');
	  table.showColumn('ship_region');
	  table.showColumn('ship_country');

      }else if(tipo=='balance'){
	     table.showColumn('name');
	  table.showColumn('net_balance');
	  table.showColumn('total_refunds');
	  table.showColumn('total_payments');
	  table.showColumn('total_profit');

	  table.showColumn('balance');

      }else if(tipo=='rank'){
	     table.showColumn('name');
	  table.showColumn('top_orders');
	  table.showColumn('top_invoices');
	  table.showColumn('top_balance');
	  table.showColumn('top_profits');

      }


      YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=customers-view&value='+escape(tipo),{});
  }



YAHOO.util.Event.addListener('details', "click",change_details,'customers');

var ids=['general','contact','address','ship_to_address','balance','rank'];
YAHOO.util.Event.addListener(ids, "click",change_view);

var ids=['restrictions_all_contacts','restrictions_all_customers','restrictions_active_customers'];
YAHOO.util.Event.addListener(ids, "click",change_table_type);

//YAHOO.util.Event.addListener('submit_advanced_search', "click",submit_advanced_search);

//var search_data={tipo:'customer_name',container:'customer'};




dialog_new_customer = new YAHOO.widget.Dialog("dialog_new_customer", {context:["new_customer","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
dialog_new_customer.render();
Event.addListener("new_customer", "click", dialog_new_customer.show,dialog_new_customer , true);


 
}

YAHOO.util.Event.onDOMReady(init);







YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });


YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

