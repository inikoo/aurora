<?

include_once('../common.php');



?>

    


YAHOO.namespace ("orders"); 


YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.orders.XHR_JSON = new function() {


	    this.orderLink=  function(el, oRecord, oColumn, oData) {
		var url="order.php?id="+oRecord.getData("id");
		var url2="<a href=\"file://192.168.1.35"+oRecord.getData("file")+"\">e</a>";
		el.innerHTML = oData.link(url)+' '+url2;
	    }
	    
	    this.customerLink=  function(el, oRecord, oColumn, oData) {
		if(oData==null)
		    oData='<?=_('Error, no customer name')?>';

		var url="customer.php?id="+oRecord.getData("customer_id");
		el.innerHTML = oData.link(url);
	    }
		this.desde=  function(el, oRecord, oColumn, oData) {

		    el.innerHTML = oData+"<?=' '._('days')?>";
	    }

	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var OrdersColumnDefs = [
				       {key:"public_id", label:"<?=_('Number')?>",formatter:this.orderLink,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"customer_name",label:"<?=_('Customer')?>",formatter:this.customerLink,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"date_index", label:"<?=_('Date')?>", sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"desde", label:"<?=_('Since')?>", formatter:this.desde,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"total", label:"<?=_('Total')?>",sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				       
				      //					 {key:"families", label:"<?=_('Customers')?>", sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				      //{key:"active", label:"<?=_('Customers')?>", sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      

					 ];

	    this.OrdersDataSource = new YAHOO.util.DataSource("ar_orders.php?tipo=proinvoice&tid=0");
	    this.OrdersDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.OrdersDataSource.connXhrMode = "queueRequests";
	    this.OrdersDataSource.responseSchema = {
		resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "id",
			 "public_id",
			 "customer_name",
			 "customer_id",
			 "date_index",
			 "total",
			 "titulo",
			 "tipo","desde","file"

			 //,{key:"families",parser:YAHOO.util.DataSource.parseNumber},
			 //	    {key:"active",parser:YAHOO.util.DataSource.parseNumber}
			 ]};
	    //__You shouls not change anything from here

	    this.OrdersDataSource.doBeforeCallback = mydoBeforeCallback;



	    this.OrdersDataTable = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
								   this.OrdersDataSource, {
								  renderLoopSize: 50,
								  sortedBy: {key:"<?=$_SESSION['tables']['proinvoice_list'][0]?>", dir:"<?=$_SESSION['tables']['proinvoice_list'][1]?>"} // Set up initial column headers UI 
								   }
								   
								   );
	 
	    this.OrdersDataTable.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
	    this.OrdersDataTable.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.OrdersDataTable}  } ]);
	    this.OrdersDataTable.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.OrdersDataTable}  } ]);
	    this.OrdersDataTable.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.OrdersDataTable}  } ]);
	    this.OrdersDataTable.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.OrdersDataTable}  } ]);
	    this.OrdersDataTable.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.OrdersDataTable}  } ]);
	    this.OrdersDataTable.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.OrdersDataTable}  } ]);
	    YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", this.OrdersDataTable.paginatorMenu.show, null, this.OrdersDataTable.paginatorMenu);
	    this.OrdersDataTable.paginatorMenu.render(document.body);

	    



	    this.OrdersDataTable.filterMenu = new YAHOO.widget.Menu('filternewmenu0',  {context:['filterselector0',"tr", "br"]  });
	    this.OrdersDataTable.filterMenu.addItems([{ text: "<?=_('Maximum Days Interval')?>", onclick:{fn:changeFilter,obj:{col:'max',text:"<?=_('Max Days')?>"},scope:this.OrdersDataTable}  } ]);
	    this.OrdersDataTable.filterMenu.addItems([{ text: "<?=_('Minumum Days Interval')?>", onclick:{fn:changeFilter,obj:{col:'min',text:"<?=_('Min Days')?>"},scope:this.OrdersDataTable}  } ]);
	    this.OrdersDataTable.filterMenu.addItems([{ text: "<?=_('Order Number')?>", onclick:{fn:changeFilter,obj:{col:'public_id',text:"<?=_('Order Number')?>"},scope:this.OrdersDataTable}  } ]);
	    this.OrdersDataTable.filterMenu.addItems([{ text: "<?=_('Customer Name')?>", onclick:{fn:changeFilter,obj:{col:'customer_name',text:"<?=_('Customer Name')?>"},scope:this.OrdersDataTable}  } ]);
	    YAHOO.util.Event.addListener('filterselector0', "click", this.OrdersDataTable.filterMenu.show, null, this.OrdersDataTable.filterMenu);
	    this.OrdersDataTable.filterMenu.render(document.body);
	    
	    this.OrdersDataTable.myreload=reload;
	    this.OrdersDataTable.sortColumn = mysort;
	    
	    this.OrdersDataTable.id=tableid;
	    this.OrdersDataTable.editmode=false;

	    this.OrdersDataTable.subscribe("initEvent", dataReturn); 
	    YAHOO.util.Event.addListener('paginator_next0', "click", nextpage, this.OrdersDataTable); 
	    YAHOO.util.Event.addListener('paginator_prev0', "click", prevpage, this.OrdersDataTable); 
	    YAHOO.util.Event.addListener('hidder0', "click", showtable, this.OrdersDataTable); 
	    YAHOO.util.Event.addListener('resetfilter0', "click", resetfilter, this.OrdersDataTable); 


	    
	};
    });




function init(){


    function mygetTerms(query) {
	var Dom = YAHOO.util.Dom;

	var table=YAHOO.orders.XHR_JSON.OrdersDataTable;
	var data=table.getDataSource();
	var newrequest="&sf=0&f_field="+Dom.get('f_field0').value+"&f_value="+Dom.get('f_input0').value;

	
	data.sendRequest(newrequest,{success:table.onDataReturnInitializeTable, scope:table});
    };
    var oACDS = new YAHOO.widget.DS_JSFunction(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","filtercontainer0", oACDS);
    oAutoComp.minQueryLength = 0; 
    


    




}

YAHOO.util.Event.onDOMReady(init);




