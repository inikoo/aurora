<?
include_once('../common.php');
?>

    
    YAHOO.namespace ("supplier"); 


YAHOO.util.Event.addListener(window, "load", function() {
	YAHOO.supplier.XHR_JSON = new function() {
		
		
		this.orderLink=  function(el, oRecord, oColumn, oData) {
		    var url="order.php?id="+oRecord.getData("id");
		    el.innerHTML = oData.link(url);
		};
		this.date=  function(el, oRecord, oColumn, oData) {
		    el.innerHTML = oRecord.getData("date")
		};

		var tableid=2; // Change if you have more the 1 table
		var tableDivEL="table"+tableid;
		
		var SuppliersColumnDefs = [
					   
	  {key:"date", label:"<?=_('Date')?>",className:"aright",width:80}
	  ,{key:"time", label:"<?=_('Time')?>",className:"aleft",width:30}

	  ,{key:"tipo", label:"<?=_('Type')?>", className:"aleft",width:70}
	  ,{key:"description", label:"<?=_('Description')?>",className:"aleft",width:600}
					   ];
		
		this.SuppliersDataSource = new YAHOO.util.DataSource("ar_contacts.php?tipo=customer_history&tid="+tableid);
		this.SuppliersDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
		this.SuppliersDataSource.connXhrMode = "queueRequests";
		this.SuppliersDataSource.responseSchema = {
		    resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "description","date","tipo","time"
			 ]};
		
		this.SuppliersDataSource.doBeforeCallback = mydoBeforeCallback;
		
		
		
		
	 
		this.SuppliersDataTable = new YAHOO.widget.DataTable(tableDivEL, SuppliersColumnDefs,this.SuppliersDataSource, {renderLoopSize: 50});
		
		this.SuppliersDataTable.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
		this.SuppliersDataTable.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.SuppliersDataTable}  } ]);
		this.SuppliersDataTable.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.SuppliersDataTable}  } ]);
		this.SuppliersDataTable.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.SuppliersDataTable}  } ]);
		this.SuppliersDataTable.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.SuppliersDataTable}  } ]);
		this.SuppliersDataTable.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.SuppliersDataTable}  } ]);
		this.SuppliersDataTable.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.SuppliersDataTable}  } ]);
		YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", this.SuppliersDataTable.paginatorMenu.show, null, this.SuppliersDataTable.paginatorMenu);
		this.SuppliersDataTable.paginatorMenu.render(document.body);
		this.SuppliersDataTable.filterMenu = new YAHOO.widget.Menu('filternewmenu'+tableid,  {context:['filterselector'+tableid,"tr", "br"]  });
		this.SuppliersDataTable.filterMenu.addItems([{ text: "<?=_('Supplier Code')?>", onclick:{fn:changeFilter,obj:{col:'code',text:"<?=_('Family Code')?>"},scope:this.SuppliersDataTable}  } ]);
		this.SuppliersDataTable.filterMenu.addItems([{ text: "<?=_('Description')?>", onclick:{fn:changeFilter,obj:{col:'description',text:"<?=_('Description')?>"},scope:this.SuppliersDataTable}  } ]);
		YAHOO.util.Event.addListener('filterselector'+tableid, "click", this.SuppliersDataTable.filterMenu.show, null, this.SuppliersDataTable.filterMenu);
		this.SuppliersDataTable.filterMenu.render(document.body);
		
		this.SuppliersDataTable.myreload=reload;
		this.SuppliersDataTable.sortColumn = mysort;
		this.SuppliersDataTable.id=tableid;
		this.SuppliersDataTable.editmode=false;
		this.SuppliersDataTable.subscribe("initEvent", dataReturn); 
		YAHOO.util.Event.addListener('paginator_next'+tableid, "click", nextpage, this.SuppliersDataTable); 
		YAHOO.util.Event.addListener('paginator_prev'+tableid, "click", prevpage, this.SuppliersDataTable); 
		YAHOO.util.Event.addListener('hidder'+tableid, "click", showtable, this.SuppliersDataTable); 
		YAHOO.util.Event.addListener('resetfilter'+tableid, "click", resetfilter, this.SuppliersDataTable); 
		

	    
	};
    });




function init(){


    function mygetTerms(query) {
	var Dom = YAHOO.util.Dom
	var table=YAHOO.supplier.XHR_JSON.SuppliersDataTable;
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
