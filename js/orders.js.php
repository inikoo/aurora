<?
include_once('../common.php');

?>


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    this.orderLink=  function(el, oRecord, oColumn, oData) {
		var url="order.php?id="+oRecord.getData("id");
		el.innerHTML = oData.link(url);
	    }
	    
	    this.customerLink=  function(el, oRecord, oColumn, oData) {
		if(oData==null)
		    oData='<?=_('Error, no customer name')?>';

		var url="customer.php?id="+oRecord.getData("customer_id");
		el.innerHTML = oData.link(url);
	    };
	    
	    
	    
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var OrdersColumnDefs = [
				       {key:"public_id", label:"<?=_('Number')?>", width:80,formatter:this.orderLink,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"titulo", label:"<?=_('Type')?>", width:115,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"customer_name",label:"<?=_('Customer')?>",formatter:this.customerLink, width:280,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"date_index", label:"<?=_('Fecha')?>", width:145,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},

				       {key:"total", label:"<?=_('Total')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      
				      //					 {key:"families", label:"<?=_('Customers')?>", sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				      //{key:"active", label:"<?=_('Customers')?>", sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      

					 ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_orders.php?tipo=orders");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 "id",
			 "public_id",
			 "customer_name",
			 "customer_id",
			 "date_index",
			 "total",
			 "titulo",
			 "tipo"
			 ]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?=$_SESSION['tables']['order_list'][2]?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?=$_SESSION['tables']['order_list'][0]?>",
									 dir: "<?=$_SESSION['tables']['order_list'][1]?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?=$_SESSION['tables']['order_list'][5]?>',value:'<?=$_SESSION['tables']['order_list'][6]?>'};





// 	    this.table0.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
// 	    this.table0.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.table0}  } ]);
// 	    this.table0.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.table0}  } ]);
// 	    this.table0.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.table0}  } ]);
// 	    this.table0.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.table0}  } ]);
// 	    this.table0.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.table0}  } ]);
// 	    this.table0.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.table0}  } ]);
// 	    YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", this.table0.paginatorMenu.show, null, this.table0.paginatorMenu);
// 	    this.table0.paginatorMenu.render(document.body);
// 	    this.table0.filterMenu = new YAHOO.widget.Menu('filternewmenu0',  {context:['filterselector0',"tr", "br"]  });
// 	    this.table0.filterMenu.addItems([{ text: "<?=_('Order Number')?>", onclick:{fn:changeFilter,obj:{col:'public_id',text:"<?=_('Order Number')?>"},scope:this.table0}  } ]);
// 	    this.table0.filterMenu.addItems([{ text: "<?=_('Customer Name')?>", onclick:{fn:changeFilter,obj:{col:'customer_name',text:"<?=_('Customer Name')?>"},scope:this.table0}  } ]);
// 	    this.table0.filterMenu.addItems([{ text: "<?=_('Maximum Days Since')?>", onclick:{fn:changeFilter,obj:{col:'max',text:"<?=_('Max Days')?>"},scope:this.table0}  } ]);
// 	    this.table0.filterMenu.addItems([{ text: "<?=_('Minumum Days Since')?>", onclick:{fn:changeFilter,obj:{col:'min',text:"<?=_('Min Days')?>"},scope:this.table0}  } ]);
// 	    this.table0.filterMenu.addItems([{ text: "<?=_('Maximum Value')?>", onclick:{fn:changeFilter,obj:{col:'maxvalue',text:"<?=_('Max Value')?>"},scope:this.table0}  } ]);
// 	    this.table0.filterMenu.addItems([{ text: "<?=_('Minumum Value')?>", onclick:{fn:changeFilter,obj:{col:'minvalue',text:"<?=_('Min Value')?>"},scope:this.table0}  } ]);


// 	    YAHOO.util.Event.addListener('filterselector0', "click", this.table0.filterMenu.show, null, this.table0.filterMenu);
// 	    this.table0.filterMenu.render(document.body);
	    
// 	    this.table0.myreload=reload;
// 	    this.table0.sortColumn = mysort;
	    
// 	    this.table0.id=tableid;
// 	    this.table0.editmode=false;

// 	    this.table0.subscribe("initEvent", dataReturn); 
// 	    YAHOO.util.Event.addListener('paginator_next0', "click", nextpage, this.table0); 
// 	    YAHOO.util.Event.addListener('paginator_prev0', "click", prevpage, this.table0); 
// 	    YAHOO.util.Event.addListener('hidder0', "click", showtable, this.table0); 
// 	    YAHOO.util.Event.addListener('resetfilter0', "click", resetfilter, this.table0); 


	    
	};
    });




function init(){

    
var Dom   = YAHOO.util.Dom;


 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
 oAutoComp.minQueryLength = 0; 


    

//     	YAHOO.orders.cal2 = new YAHOO.widget.Calendar("cal2","cal2Container", { title:"<?=_('Choose a date')?>:", close:true } );
// 	YAHOO.orders.cal2.update=updateCal;
	

// 	YAHOO.orders.cal2.id=2;
// 	YAHOO.orders.cal2.render();
// 	YAHOO.orders.cal2.update();
// 	YAHOO.orders.cal2.selectEvent.subscribe(handleSelect, YAHOO.orders.cal2, true); 
	


// 	YAHOO.orders.cal1 = new YAHOO.widget.Calendar("cal1","cal1Container", { title:"<?=_('Choose a date')?>:", close:true } );
// 	YAHOO.orders.cal1.update=updateCal;
// 	YAHOO.orders.cal1.id=1;
// 	YAHOO.orders.cal1.render();
// 	YAHOO.orders.cal1.update();
// 	YAHOO.orders.cal1.selectEvent.subscribe(handleSelect, YAHOO.orders.cal1, true); 

// 	YAHOO.util.Event.addListener("calpop1", "click", YAHOO.orders.cal1.show, YAHOO.orders.cal1, true);
// 	YAHOO.util.Event.addListener("calpop2", "click", YAHOO.orders.cal2.show, YAHOO.orders.cal2, true);
		



}

YAHOO.util.Event.onDOMReady(init);






