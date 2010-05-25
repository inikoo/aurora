<?php
include_once('common.php');
?>
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
	                    {key:"customer_key", label:"", hidden:true,action:"none",isPrimaryKey:true}
			    ,{key:"go", label:"", width:20,action:"none"}

			    ,{key:"name", label:"<?php echo _('Customer Name')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
			    
			    ,{key:"contact_name", label:"<?php echo _('Contact Name')?>",<?php echo($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
			    
				       ,{key:"email", label:"<?php echo _('Email')?>",<?php echo($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
				       ,{key:"telephone", label:"<?php echo _('Telephone')?>",<?php echo($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:false,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
				       
				       ,{key:"address", label:"<?php echo _('Main Address')?>",<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
				       ,{key:"town", label:"<?php echo _('Town')?>",<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
				       ,{key:"postcode", label:"<?php echo _('Postal Code')?>",<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
				       ,{key:"region", label:"<?php echo _('Region')?>",<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
				       ,{key:"country", label:"<?php echo _('Country')?>",<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
				       ,{key:"ship_town", label:"<?php echo _('Town')?>",<?php echo($_SESSION['state']['customers']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:true,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
				       ,{key:"ship_postcode", label:"<?php echo _('Postal Code')?>",<?php echo($_SESSION['state']['customers']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:true,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
				       ,{key:"ship_region", label:"<?php echo _('Region')?>",<?php echo($_SESSION['state']['customers']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:true,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
				       ,{key:"ship_country", label:"<?php echo _('Country')?>",<?php echo($_SESSION['state']['customers']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:true,className:"aright",editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'customer'}
				       
				       

					 ];
	    //?tipo=customers&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_contacts.php?tipo=edit_customers");
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
			 'go',
			 'name',
			
			 'email',
			 'telephone',
			 'contact_name'
			 ,"address","town","postcode","region","country"
			 ,"ship_address","ship_town","ship_postcode","ship_region","ship_country","customer_key"
			
			 ]};
	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;



	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
								     // sortedBy: {key:"<?php echo$_SESSION['tables']['customers_list'][0]?>", dir:"<?php echo$_SESSION['tables']['customers_list'][1]?>"},
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customers']['table']['nr']?>,containers : 'paginator', 
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

		    
	    this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table0.subscribe("cellClickEvent", onCellClick);
	    
		    
	    this.table0.view='<?php echo$_SESSION['state']['customers']['view']?>';

	    this.table0.filter={key:'<?php echo$_SESSION['state']['customers']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['table']['f_value']?>'};

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



YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });


YAHOO.util.Event.onContentReady("rppmenu0", function () {
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



YAHOO.util.Event.addListener('details', "click",change_details,'customers');

var ids=['general','contact','address','ship_to_address','balance','rank'];
YAHOO.util.Event.addListener(ids, "click",change_view);
//YAHOO.util.Event.addListener('submit_advanced_search', "click",submit_advanced_search);

var search_data={tipo:'customer_name',container:'customer'};
Event.addListener('customer_submit_search', "click",submit_search,search_data);
Event.addListener('customer_search', "keydown", submit_search_on_enter,search_data);

 
}

YAHOO.util.Event.onDOMReady(init);
