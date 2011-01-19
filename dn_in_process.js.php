<?php
include_once('common.php');
if(!$user->can_view('orders'))
  exit();
?>


YAHOO.namespace ("orders"); 


YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.orders.XHR_JSON = new function() {


	    this.orderLink=  function(el, oRecord, oColumn, oData) {
		var url="order.php?id="+oRecord.getData("id");
		el.innerHTML = oData.link(url);
	    }
	    
	    this.customerLink=  function(el, oRecord, oColumn, oData) {
		if(oData==null)
		    oData='<?php echo _('Error, no customer name')?>';

		var url="contact.php?id="+oRecord.getData("customer_id");
		el.innerHTML = oData.link(url);
	    }


	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var OrdersColumnDefs = [
				     {key:"part", label:"<?php echo _('Part')?>",width:80,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     				     ,{key:"used_in", label:"<?php echo _('Used In')?>",width:330,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				     ,{key:"description", label:"<?php echo _('Description')?>",width:330,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     
				     ,{key:"quantity",label:"<?php echo _('Qty')?>", width:50,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      
				  
					 ];

	    this.OrdersDataSource = new YAHOO.util.DataSource("ar_orders.php?tipo=transactions_in_process_in_dn&tid=0");
	    this.OrdersDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.OrdersDataSource.connXhrMode = "queueRequests";
	    this.OrdersDataSource.responseSchema = {
		resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "part","description","quantity","used_in"
			

			 //,{key:"families",parser:YAHOO.util.DataSource.parseNumber},
			 //	    {key:"active",parser:YAHOO.util.DataSource.parseNumber}
			 ]};
	    //__You shouls not change anything from here

	    //this.OrdersDataSource.doBeforeCallback = mydoBeforeCallback;



	    this.OrdersDataTable = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
								   this.OrdersDataSource, {
								       renderLoopSize: 50
								   }
								   
								   );
	 
	   // this.OrdersDataTable.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
	   // this.OrdersDataTable.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.OrdersDataTable}  } ]);
	   // this.OrdersDataTable.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.OrdersDataTable}  } ]);
	   // this.OrdersDataTable.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.OrdersDataTable}  } ]);
	   // this.OrdersDataTable.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.OrdersDataTable}  } ]);
	   // this.OrdersDataTable.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.OrdersDataTable}  } ]);
	   // this.OrdersDataTable.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.OrdersDataTable}  } ]);
	   // YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", this.OrdersDataTable.paginatorMenu.show, null, this.OrdersDataTable.paginatorMenu);
	   // this.OrdersDataTable.paginatorMenu.render(document.body);

	    



	   // this.OrdersDataTable.filterMenu = new YAHOO.widget.Menu('filternewmenu0',  {context:['filterselector0',"tr", "br"]  });
	   // this.OrdersDataTable.filterMenu.addItems([{ text: "<?php echo _('Name')?>", onclick:{fn:changeFilter,obj:{col:'name',text:"<?php echo _('Order name')?>"},scope:this.OrdersDataTable}  } ]);
	   // this.OrdersDataTable.filterMenu.addItems([{ text: "<?php echo _('Post Code')?>", onclick:{fn:changeFilter,obj:{col:'postcode',text:"<?php echo _('Post Code')?>"},scope:this.OrdersDataTable}  } ]);
	   // YAHOO.util.Event.addListener('filterselector0', "click", this.OrdersDataTable.filterMenu.show, null, this.OrdersDataTable.filterMenu);
	   // this.OrdersDataTable.filterMenu.render(document.body);
	    
	   // this.OrdersDataTable.myreload=reload;
	    //this.OrdersDataTable.sortColumn = mysort;
	    
	    this.OrdersDataTable.id=tableid;
	    this.OrdersDataTable.editmode=false;

	    //this.OrdersDataTable.subscribe("initEvent", dataReturn); 
	    //YAHOO.util.Event.addListener('paginator_next0', "click", nextpage, this.OrdersDataTable); 
	    //YAHOO.util.Event.addListener('paginator_prev0', "click", prevpage, this.OrdersDataTable); 
	    //YAHOO.util.Event.addListener('hidder0', "click", showtable, this.OrdersDataTable); 
	    //YAHOO.util.Event.addListener('resetfilter0', "click", resetfilter, this.OrdersDataTable); 


	    
	};
    });




function init(){


    function mygetTerms(query) {
	var Dom = YAHOO.util.Dom
	var table=YAHOO.orders.XHR_JSON.OrdersDataTable;
	var data=table.getDataSource();
	var newrequest="&sf=0&f_field="+Dom.get('f_field0').value+"&f_value="+Dom.get('f_input0').value;

	//	alert(newrequest);
	data.sendRequest(newrequest,{success:table.onDataReturnInitializeTable, scope:table});
    };
    var oACDS = new YAHOO.widget.DS_JSFunction(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","filtercontainer0", oACDS);
    oAutoComp.minQueryLength = 0; 
    


    




}

YAHOO.util.Event.onDOMReady(init);
