<?

include_once('../common.php');



?>


YAHOO.namespace ("customers"); 


YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.customers.XHR_JSON = new function() {


	    this.customerLink=  function(el, oRecord, oColumn, oData) {
		    var url="customer.php?id="+oRecord.getData("id");
		    el.innerHTML = oData.link(url);

	    };

	    this.customer_name=  function(el, oRecord, oColumn, oData) {
		if(oData!= null){
		    el.style.color='#000';
		    el.innerHTML = oData;
		}else{
		    el.style.color='#ccc';
		    el.innerHTML = "<?=_('Unknown')?>";
		}

		};


	    this.date=  function(el, oRecord, oColumn, oData) {
		el.innerHTML =oRecord.getData("flast_order") ;
	    };
	    this.total=  function(el, oRecord, oColumn, oData) {
		el.innerHTML =oRecord.getData("ftotal") ;
	    };	
	    this.location=  function(el, oRecord, oColumn, oData) {
		el.innerHTML =oRecord.getData("flocation") ;
	    };	


	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
				       {key:"id", label:"<?=$customers_ids[0]?>", formatter:this.customerLink,width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //				       ,{key:"id2", label:"<?=$customers_ids[1]?>",width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       // ,{key:"id3", label:"<?=$customers_ids[2]?>",width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				       
				       ,{key:"name", label:"<?=_('Name')?>", width:250,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				           ,{key:"location", label:"<?=_('Location')?>", width:230,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"last_order", label:"<?=_('Last Order')?>",width:100,formatter:this.date,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"orders", label:"<?=_('Orders')?>",sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"super_total", label:"<?=_('Total')?>",sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       
				       //					 {key:"families", label:"<?=_('Customers')?>", sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				      //{key:"active", label:"<?=_('Customers')?>", sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      

					 ];
	    //?tipo=customers&tid=0"
	    this.CustomersDataSource = new YAHOO.util.DataSource("ar_contacts.php?tipo=customers");
	    this.CustomersDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.CustomersDataSource.connXhrMode = "queueRequests";
	    this.CustomersDataSource.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "id"
			 ,"name"
			 ,'location'
			 ,'orders'
			 ,'last_order'
			 ,'flast_order'
			 ,'super_total'
			 //,{key:"families",parser:YAHOO.util.DataSource.parseNumber},
			 //	    {key:"active",parser:YAHOO.util.DataSource.parseNumber}
			 ]};
	    //__You shouls not change anything from here

	    //this.CustomersDataSource.doBeforeCallback = mydoBeforeCallback;



	    this.CustomersDataTable = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.CustomersDataSource
								 , {
								     // sortedBy: {key:"<?=$_SESSION['tables']['customers_list'][0]?>", dir:"<?=$_SESSION['tables']['customers_list'][1]?>"},
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : 25,containers : 'paginator0', 
 									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink} <strong>{CurrentPageReport}</strong> {NextPageLink}{LastPageLink}  <select onChange=\"alert(\'ccc  cc\')\"  style=\"opacity:.0;width:120px;height:16px;vertical-align:bottom\"><option value=\"one\">Post Code</option><option value=\"two\">Customer Name</option></select><span style=\"margin-left:-100px\">Customer Name</span> <input size=12/>"


									  })
								     
								     ,sortedBy : {
									 key: "<?=$_SESSION['tables']['customers_list'][0]?>",
									 dir: "<?=$_SESSION['tables']['customers_list'][1]?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    this.CustomersDataTable.handleDataReturnPayload = function(oRequest, oResponse, oPayload) {
		oPayload.totalRecords = parseInt(oResponse.meta.totalRecords);
		return oPayload;
	    }

// 	    this.CustomersDataTable.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
// 	    this.CustomersDataTable.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.CustomersDataTable}  } ]);
// 	    this.CustomersDataTable.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.CustomersDataTable}  } ]);
// 	    this.CustomersDataTable.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.CustomersDataTable}  } ]);
// 	    this.CustomersDataTable.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.CustomersDataTable}  } ]);
// 	    this.CustomersDataTable.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.CustomersDataTable}  } ]);
// 	    this.CustomersDataTable.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.CustomersDataTable}  } ]);
// 	    YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", this.CustomersDataTable.paginatorMenu.show, null, this.CustomersDataTable.paginatorMenu);
// 	    this.CustomersDataTable.paginatorMenu.render(document.body);

	    



// 	    this.CustomersDataTable.filterMenu = new YAHOO.widget.Menu('filternewmenu0',  {context:['filterselector0',"tr", "br"]  });
// 	    this.CustomersDataTable.filterMenu.addItems([{ text: "<?=_('Customer Name')?>", onclick:{fn:changeFilter,obj:{col:'name',text:"<?=_('Customer name')?>"},scope:this.CustomersDataTable}  } ]);
// 	    this.CustomersDataTable.filterMenu.addItems([{ text: "<?=$customers_ids[0]?>", onclick:{fn:changeFilter,obj:{col:'id',text:"<?=$customers_ids[0]?>"},scope:this.CustomersDataTable}  } ]);
// 	    this.CustomersDataTable.filterMenu.addItems([{ text: "<?=$customers_ids[1]?>", onclick:{fn:changeFilter,obj:{col:'id2',text:"<?=$customers_ids[1]?>"},scope:this.CustomersDataTable}  } ]);
// 	    this.CustomersDataTable.filterMenu.addItems([{ text: "<?=$customers_ids[2]?>", onclick:{fn:changeFilter,obj:{col:'id3',text:"<?=$customers_ids[2]?>"},scope:this.CustomersDataTable}  } ]);
// 	    this.CustomersDataTable.filterMenu.addItems([{ text: "<?=_('Maximum Orders')?>", onclick:{fn:changeFilter,obj:{col:'max',text:"<?=_('Max Orders')?>"},scope:this.CustomersDataTable}  } ]);
// 	    this.CustomersDataTable.filterMenu.addItems([{ text: "<?=_('Minumum Orders')?>", onclick:{fn:changeFilter,obj:{col:'min',text:"<?=_('Min Orders')?>"},scope:this.CustomersDataTable}  } ]);
// 	    this.CustomersDataTable.filterMenu.addItems([{ text: "<?=_('Maximum Total')?>", onclick:{fn:changeFilter,obj:{col:'maxvalue',text:"<?=_('Max Total')?>"},scope:this.CustomersDataTable}  } ]);
// 	    this.CustomersDataTable.filterMenu.addItems([{ text: "<?=_('Minumum Total')?>", onclick:{fn:changeFilter,obj:{col:'minvalue',text:"<?=_('Min Total')?>"},scope:this.CustomersDataTable}  } ]);
// 	    this.CustomersDataTable.filterMenu.addItems([{ text: "<?=_('Maximum Days Since Last Order')?>", onclick:{fn:changeFilter,obj:{col:'maxdesde',text:"<?=_('Max Days')?>"},scope:this.CustomersDataTable}  } ]);
// 	    this.CustomersDataTable.filterMenu.addItems([{ text: "<?=_('Minumum Days Since Last Order')?>", onclick:{fn:changeFilter,obj:{col:'mindesde',text:"<?=_('Min Days')?>"},scope:this.CustomersDataTable}  } ]);




// 	    YAHOO.util.Event.addListener('filterselector0', "click", this.CustomersDataTable.filterMenu.show, null, this.CustomersDataTable.filterMenu);
// 	    this.CustomersDataTable.filterMenu.render(document.body);
	    
// 	    this.CustomersDataTable.myreload=reload;
// 	    this.CustomersDataTable.sortColumn = mysort;
	    
// 	    this.CustomersDataTable.id=tableid;
// 	    this.CustomersDataTable.editmode=false;

// 	    this.CustomersDataTable.subscribe("initEvent", dataReturn); 
// 	    YAHOO.util.Event.addListener('paginator_next0', "click", nextpage, this.CustomersDataTable); 
// 	    YAHOO.util.Event.addListener('paginator_prev0', "click", prevpage, this.CustomersDataTable); 
// 	    YAHOO.util.Event.addListener('hidder0', "click", showtable, this.CustomersDataTable); 
// 	    YAHOO.util.Event.addListener('resetfilter0', "click", resetfilter, this.CustomersDataTable); 
		var caca = function(){
		    alert('cc');
		}
		YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",caca)
	    
	};
    });




// function init(){


//     function mygetTerms(query) {
// 	var Dom = YAHOO.util.Dom
// 	var table=YAHOO.customers.XHR_JSON.CustomersDataTable;
// 	table.myreload();

//     };
//     var oACDS = new YAHOO.widget.DS_JSFunction(mygetTerms);
//     oACDS.queryMatchContains = true;
//     var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","filtercontainer0", oACDS);
//     oAutoComp.minQueryLength = 0; 
    


    




// }

// YAHOO.util.Event.onDOMReady(init);
