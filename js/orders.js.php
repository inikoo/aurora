<?

include_once('../common.php');



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
		    oData='<?=_('Error, no customer name')?>';

		var url="customer.php?id="+oRecord.getData("customer_id");
		el.innerHTML = oData.link(url);
	    }


	     //START OF THE TABLE=========================================================================================================================

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

	    this.OrdersDataSource = new YAHOO.util.DataSource("ar_orders.php?tipo=orders&tid=0");
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
			 "tipo"

			 //,{key:"families",parser:YAHOO.util.DataSource.parseNumber},
			 //	    {key:"active",parser:YAHOO.util.DataSource.parseNumber}
			 ]};
	    //__You shouls not change anything from here

	    this.OrdersDataSource.doBeforeCallback = mydoBeforeCallback;



	    this.OrdersDataTable = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
								   this.OrdersDataSource, {
								  sortedBy: {key:"<?=$_SESSION['tables']['order_list'][0]?>", dir:"<?=$_SESSION['tables']['order_list'][1]?>"},
								  renderLoopSize: 50
								       
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
	    this.OrdersDataTable.filterMenu.addItems([{ text: "<?=_('Order Number')?>", onclick:{fn:changeFilter,obj:{col:'public_id',text:"<?=_('Order Number')?>"},scope:this.OrdersDataTable}  } ]);
	    this.OrdersDataTable.filterMenu.addItems([{ text: "<?=_('Customer Name')?>", onclick:{fn:changeFilter,obj:{col:'customer_name',text:"<?=_('Customer Name')?>"},scope:this.OrdersDataTable}  } ]);
	    this.OrdersDataTable.filterMenu.addItems([{ text: "<?=_('Maximum Days Since')?>", onclick:{fn:changeFilter,obj:{col:'max',text:"<?=_('Max Days')?>"},scope:this.OrdersDataTable}  } ]);
	    this.OrdersDataTable.filterMenu.addItems([{ text: "<?=_('Minumum Days Since')?>", onclick:{fn:changeFilter,obj:{col:'min',text:"<?=_('Min Days')?>"},scope:this.OrdersDataTable}  } ]);
	    this.OrdersDataTable.filterMenu.addItems([{ text: "<?=_('Maximum Value')?>", onclick:{fn:changeFilter,obj:{col:'maxvalue',text:"<?=_('Max Value')?>"},scope:this.OrdersDataTable}  } ]);
	    this.OrdersDataTable.filterMenu.addItems([{ text: "<?=_('Minumum Value')?>", onclick:{fn:changeFilter,obj:{col:'minvalue',text:"<?=_('Min Value')?>"},scope:this.OrdersDataTable}  } ]);


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
	//var Dom = YAHOO.util.Dom
	var table=YAHOO.orders.XHR_JSON.OrdersDataTable;
	table.myreload();
	//var data=table.getDataSource();
	//	var newrequest="&sf=0&f_field="+Dom.get('f_field0').value+"&f_value="+Dom.get('f_input0').value;

	//alert(newrequest);
	//data.sendRequest(newrequest,{success:table.onDataReturnInitializeTable, scope:table});
    };
    var oACDS = new YAHOO.widget.DS_JSFunction(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","filtercontainer0", oACDS);
    oAutoComp.minQueryLength = 0; 
    


    function handleSelect(type,args,obj) {
		var dates = args[0];
		var date = dates[0];
		var year = date[0], month = date[1], day = date[2];


		if(month<10)
		    month='0'+month;
		if(day<10)
		    day='0'+day;

		var txtDate1 = document.getElementById("v_calpop"+this.id);
		txtDate1.value = day + "-" + month + "-" + year;
		this.hide();
    }

    function updateCal() {
	

	var txtDate1 = document.getElementById("v_calpop"+this.id);
	
	if (txtDate1.value != "") {
	    temp = txtDate1.value.split('-');
	    var date=temp[1]+'/'+temp[0]+'/'+temp[2];

	    this.select(date);
	    
	    var selectedDates = this.getSelectedDates();

	    if (selectedDates.length > 0) {
		var firstDate = selectedDates[0];
		this.cfg.setProperty("pagedate", (firstDate.getMonth()+1) + "/" + firstDate.getFullYear());
		this.render();
	    } else {
		alert("<?=_("Cannot select a date before 1/1/2006 or after 12/31/2008")?>");
	    }
	    
	}
    }
    

    	YAHOO.orders.cal2 = new YAHOO.widget.Calendar("cal2","cal2Container", { title:"<?=_('Choose a date')?>:", close:true } );
	YAHOO.orders.cal2.update=updateCal;
	

	YAHOO.orders.cal2.id=2;
	YAHOO.orders.cal2.render();
	YAHOO.orders.cal2.update();
	YAHOO.orders.cal2.selectEvent.subscribe(handleSelect, YAHOO.orders.cal2, true); 
	


	YAHOO.orders.cal1 = new YAHOO.widget.Calendar("cal1","cal1Container", { title:"<?=_('Choose a date')?>:", close:true } );
	YAHOO.orders.cal1.update=updateCal;
	YAHOO.orders.cal1.id=1;
	YAHOO.orders.cal1.render();
	YAHOO.orders.cal1.update();
	YAHOO.orders.cal1.selectEvent.subscribe(handleSelect, YAHOO.orders.cal1, true); 

	YAHOO.util.Event.addListener("calpop1", "click", YAHOO.orders.cal1.show, YAHOO.orders.cal1, true);
	YAHOO.util.Event.addListener("calpop2", "click", YAHOO.orders.cal2.show, YAHOO.orders.cal2, true);
		



}

YAHOO.util.Event.onDOMReady(init);






