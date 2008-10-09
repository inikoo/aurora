<?

include_once('../common.php');



?>





YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


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
	    this.dataSorce0 = new YAHOO.util.DataSource("ar_contacts.php?tipo=customers");
	    this.dataSorce0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSorce0.connXhrMode = "queueRequests";
	    this.dataSorce0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
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

	    //this.dataSorce.doBeforeCallback = mydoBeforeCallback;



	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSorce0
								 , {
								     // sortedBy: {key:"<?=$_SESSION['tables']['customers_list'][0]?>", dir:"<?=$_SESSION['tables']['customers_list'][1]?>"},
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?=$_SESSION['tables']['customers_list'][2]?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?=$_SESSION['tables']['customers_list'][0]?>",
									 dir: "<?=$_SESSION['tables']['customers_list'][1]?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
//  	    this.table0.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
//  	    this.table0.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.table0}  } ]);
//  	    this.table0.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.table0}  } ]);
//  	    this.table0.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.table0}  } ]);
//  	    this.table0.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.table0}  } ]);
//  	    this.table0.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.table0}  } ]);
//  	    this.table0.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.table0}  } ]);
//  	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click", this.table0.paginatorMenu.show, null, this.table0.paginatorMenu);
//  	    this.table0.paginatorMenu.render(document.body);

	    



// 	    this.table0.filterMenu = new YAHOO.widget.Menu('filternewmenu0',  {context:['filterselector0',"tr", "br"]  });
// 	    this.table0.filterMenu.addItems([{ text: "<?=_('Customer Name')?>", onclick:{fn:changeFilter,obj:{col:'name',text:"<?=_('Customer name')?>"},scope:this.table0}  } ]);
// 	    this.table0.filterMenu.addItems([{ text: "<?=$customers_ids[0]?>", onclick:{fn:changeFilter,obj:{col:'id',text:"<?=$customers_ids[0]?>"},scope:this.table0}  } ]);
// 	    this.table0.filterMenu.addItems([{ text: "<?=$customers_ids[1]?>", onclick:{fn:changeFilter,obj:{col:'id2',text:"<?=$customers_ids[1]?>"},scope:this.table0}  } ]);
// 	    this.table0.filterMenu.addItems([{ text: "<?=$customers_ids[2]?>", onclick:{fn:changeFilter,obj:{col:'id3',text:"<?=$customers_ids[2]?>"},scope:this.table0}  } ]);
// 	    this.table0.filterMenu.addItems([{ text: "<?=_('Maximum Orders')?>", onclick:{fn:changeFilter,obj:{col:'max',text:"<?=_('Max Orders')?>"},scope:this.table0}  } ]);
// 	    this.table0.filterMenu.addItems([{ text: "<?=_('Minumum Orders')?>", onclick:{fn:changeFilter,obj:{col:'min',text:"<?=_('Min Orders')?>"},scope:this.table0}  } ]);
// 	    this.table0.filterMenu.addItems([{ text: "<?=_('Maximum Total')?>", onclick:{fn:changeFilter,obj:{col:'maxvalue',text:"<?=_('Max Total')?>"},scope:this.table0}  } ]);
// 	    this.table0.filterMenu.addItems([{ text: "<?=_('Minumum Total')?>", onclick:{fn:changeFilter,obj:{col:'minvalue',text:"<?=_('Min Total')?>"},scope:this.table0}  } ]);
// 	    this.table0.filterMenu.addItems([{ text: "<?=_('Maximum Days Since Last Order')?>", onclick:{fn:changeFilter,obj:{col:'maxdesde',text:"<?=_('Max Days')?>"},scope:this.table0}  } ]);
// 	    this.table0.filterMenu.addItems([{ text: "<?=_('Minumum Days Since Last Order')?>", onclick:{fn:changeFilter,obj:{col:'mindesde',text:"<?=_('Min Days')?>"},scope:this.table0}  } ]);


	    this.table0.filter={key:'<?=$_SESSION['tables']['customers_list'][5]?>',value:'<?=$_SESSION['tables']['customers_list'][6]?>',lastRequest:new Date().getTime()};

	    //   YAHOO.util.Event.addListener('f_input', "keyup",myFilterChangeValue,{table:this.table0,datasource:this.dataSorce})
			 
	    
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


 }

YAHOO.util.Event.onDOMReady(init);
