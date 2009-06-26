<?include_once('../common.php');?>
   var Event = YAHOO.util.Event;
     var Dom   = YAHOO.util.Dom;


function change_plot(o){
  
    
    Dom.get('the_plot').src = 'plot.php?tipo='+o.id;
    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=customers-plot&value='+o.id);
}

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {



	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
				       {key:"id", label:"<?=$customers_ids[0]?>",width:60,sortable:true,<?=($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"name", label:"<?=_('Customer Name')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"location", label:"<?=_('Location')?>",<?=($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?> width:230,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"last_order", label:"<?=_('Last Order')?>",<?=($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"orders", label:"<?=_('Orders')?>",<?=($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"activity", label:"<?=_('Status')?>",<?=($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       
				       // ,{key:"total_payments", label:"<?=_('Total')?>",<?=($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}} 
				       ,{key:"contact_name", label:"<?=_('Contact Name')?>",<?=($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       
				       ,{key:"email", label:"<?=_('Email')?>",<?=($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"telephone", label:"<?=_('Telephone')?>",<?=($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       
				       ,{key:"address", label:"<?=_('Main Address')?>",<?=($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"town", label:"<?=_('Town')?>",<?=($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"postcode", label:"<?=_('Postal Code')?>",<?=($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"region", label:"<?=_('Region')?>",<?=($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"country", label:"<?=_('Country')?>",<?=($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       //				       ,{key:"ship_address", label:"<?=_('Ship to Address')?>",<?=($_SESSION['state']['customers']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"ship_town", label:"<?=_('Town')?>",<?=($_SESSION['state']['customers']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"ship_postcode", label:"<?=_('Postal Code')?>",<?=($_SESSION['state']['customers']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"ship_region", label:"<?=_('Region')?>",<?=($_SESSION['state']['customers']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"ship_country", label:"<?=_('Country')?>",<?=($_SESSION['state']['customers']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"total_payments", label:"<?=_('Payments')?>",<?=($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"total_refunds", label:"<?=_('Refunds')?>",<?=($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"net_balance", label:"<?=_('Balance')?>",<?=($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"balance", label:"<?=_('Outstanding')?>",<?=($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"total_profit", label:"<?=_('Profit')?>",<?=($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"top_orders", label:"<?=_('Rank Orders')?>",<?=($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"top_invoices", label:"<?=_('Rank Invoices')?>",<?=($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"top_balance", label:"<?=_('Rank Balance')?>",<?=($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright"}
				       ,{key:"top_profits", label:"<?=_('Rank Profits')?>",<?=($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright"}

				       

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
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
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
								     // sortedBy: {key:"<?=$_SESSION['tables']['customers_list'][0]?>", dir:"<?=$_SESSION['tables']['customers_list'][1]?>"},
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?=$_SESSION['state']['customers']['table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?=$_SESSION['state']['customers']['table']['order']?>",
									 dir: "<?=$_SESSION['state']['customers']['table']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table0.view='<?=$_SESSION['state']['customers']['view']?>';

	    this.table0.filter={key:'<?=$_SESSION['state']['customers']['table']['f_field']?>',value:'<?=$_SESSION['state']['customers']['table']['f_value']?>'};

	    //   YAHOO.util.Event.addListener('f_input', "keyup",myFilterChangeValue,{table:this.table0,datasource:this.dataSource})
			 
	    
	    //	    var Dom   = YAHOO.util.Dom;
	    //alert(Dom.get('f_input'));

	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)
	
	};
    });




 function init(){
 var Dom   = YAHOO.util.Dom;


 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
 oAutoComp.minQueryLength = 0; 



YAHOO.util.Event.onContentReady("filtermenu", function () {
	 var oMenu = new YAHOO.widget.Menu("filtermenu", { context:["filter_name0","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name0", "click", oMenu.show, null, oMenu);
    });


YAHOO.util.Event.onContentReady("rppmenu", function () {
	 var oMenu = new YAHOO.widget.Menu("rppmenu", { context:["filter_name0","tr", "bl"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("paginator_info0", "click", oMenu.show, null, oMenu);
    });





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
      table.hideColumn('name');
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



      if(tipo=='general'){
	  table.showColumn('name');
	  table.showColumn('location');
	  table.showColumn('last_order');
	  table.showColumn('orders');
	  table.showColumn('total_payments');
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


      YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=customers-view&value='+escape(tipo));
  }



YAHOO.util.Event.addListener('but_show_details', "click",show_details,'customers');
var ids=['general','contact','address','ship_to_address','balance','rank'];
YAHOO.util.Event.addListener(ids, "click",change_view);
//YAHOO.util.Event.addListener('submit_advanced_search', "click",submit_advanced_search);

var search_data={tipo:'customer_name',container:'customer'};
Event.addListener('customer_submit_search', "click",submit_search,search_data);
Event.addListener('customer_search', "keydown", submit_search_on_enter,search_data);

 
}

YAHOO.util.Event.onDOMReady(init);
