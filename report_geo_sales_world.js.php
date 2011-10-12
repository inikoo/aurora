<?php
include_once('common.php')?>
var Dom   = YAHOO.util.Dom;

var link='report_geo_sales.php'







          
    YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	    
	    //START OF THE TABLE ========================================================================================
	 
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;


	   
	    var ColumnDefs = [
			
			
                    {key:"flag", label:"",width:10,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

                   ,{key:"code", label:"<?php echo _('Code')?>",width:30,sortable:true,className:"aleft",sortOptions:{field: "_code",defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			       ,{key:"name", label:"<?php echo _('Name')?>",width:200,sortable:true,className:"aleft",sortOptions:{field: "_name",defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

			     // ,{key:"population", label:"<?php echo _('Population')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			      // ,{key:"gnp", label:"<?php echo _('GNP')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"invoices_formated", label:"<?php echo _('Invoices')?>",width:200 ,sortable:true,className:"aright",sortOptions:{field: "invoices",defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			        ,{key:"sales_formated", label:"<?php echo _('Sales')?>",width:100 ,sortable:true,className:"aright",sortOptions:{field: "sales",defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			      ,{key:"wregion", label:"<?php echo _('Region')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				 
			      
	     


			
			
			];
			       
	    this.dataSource0 = new YAHOO.util.DataSource("ar_reports.php?tipo=country_sales&tableid=0");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "name","flag",'code','population','gnp','wregion','sales','sales_formated',"_name","_code","invoices",'invoices_formated'
			 ]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['report_geo_sales']['countries']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['report_geo_sales']['countries']['order']?>",
									 dir: "<?php echo$_SESSION['state']['report_geo_sales']['countries']['order_dir']?>"
								     },
								   //  dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	   // this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	 //   this.table0.subscribe("cellClickEvent", this.table0.onEventShowCellEditor);


	   // this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['report_geo_sales']['countries']['f_field']?>',value:'<?php echo $_SESSION['state']['report_geo_sales']['countries']['f_value']?>'};
	    //
	
// -----------------------------------------------world regions table starts here --------------
var tableid=1;
		      var tableDivEL="table"+tableid;
		      
		      var ColumnDefs = [
		      		{key:"wregion_code", label:"<?php echo _('Code')?>",width:80, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"wregion_name", label:"<?php echo _('World Region')?>",width:320, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					// ,{key:"population", label:"<?php echo _('Population')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      // ,{key:"gnp", label:"<?php echo _('GNP')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			     ,{key:"invoices_formated", label:"<?php echo _('Invoices')?>",width:200 ,sortable:true,className:"aright",sortOptions:{field: "invoices",defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			        ,{key:"sales_formated", label:"<?php echo _('Sales')?>",width:100 ,sortable:true,className:"aright",sortOptions:{field: "sales",defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			     
					];
		    
		      
		      this.dataSource1 = new YAHOO.util.DataSource("ar_reports.php?tipo=wregion_sales&tableid=1");
		      this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
		      this.dataSource1.connXhrMode = "queueRequests";
		      this.dataSource1.responseSchema = {
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
				  "wregion_name","wregion_code","population","gnp",'sales','sales_formated',"_name","_code","invoices",'invoices_formated'
				   ]};
		      
		    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource1
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['report_geo_sales']['wregions']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								   
								   ,sortedBy : {
								      key: "<?php echo $_SESSION['state']['report_geo_sales']['wregions']['order']?>",
								       dir: "<?php echo $_SESSION['state']['report_geo_sales']['wregions']['order_dir']?>"
								   }
								  // ,dynamicData : true
								 
							       }
							       );
		      this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
		     // this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
		    //  this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
               //    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.filter={key:'<?php echo$_SESSION['state']['report_geo_sales']['wregions']['f_field']?>',value:'<?php echo$_SESSION['state']['report_geo_sales']['wregions']['f_value']?>'};
// -------------------------------------------------- continents table starts here --------------------------------------
var tableid=2;
		      var tableDivEL="table"+tableid;
		      
		      var ColumnDefs = [
		      		{key:"continent_code", label:"<?php echo _('Code')?>",width:100, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"continent_name", label:"<?php echo _('Continents')?>",width:320, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					// ,{key:"population", label:"<?php echo _('Population')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			   //    ,{key:"gnp", label:"<?php echo _('GNP')?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			         ,{key:"invoices_formated", label:"<?php echo _('Invoices')?>",width:200 ,sortable:true,className:"aright",sortOptions:{field: "invoices",defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			        ,{key:"sales_formated", label:"<?php echo _('Sales')?>",width:100 ,sortable:true,className:"aright",sortOptions:{field: "sales",defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			 
					];
		    
		      
		      this.dataSource2 = new YAHOO.util.DataSource("ar_reports.php?tipo=continent_sales&tableid=2");
		      this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
		      this.dataSource2.connXhrMode = "queueRequests";
		      this.dataSource2.responseSchema = {
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
				  "continent_name","continent_code","population","gnp",'sales','sales_formated',"_name","_code","invoices",'invoices_formated'
				   ]};
		      
		    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource2
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['report_geo_sales']['continents']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								   
								   ,sortedBy : {
								      key: "<?php echo $_SESSION['state']['report_geo_sales']['continents']['order']?>",
								       dir: "<?php echo $_SESSION['state']['report_geo_sales']['continents']['order_dir']?>"
								   }
								//   ,dynamicData : true
								 
							       }
							       );
		      this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
		  //    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
		  //    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
            //       this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'<?php echo$_SESSION['state']['report_geo_sales']['continents']['f_field']?>',value:'<?php echo$_SESSION['state']['report_geo_sales']['continents']['f_value']?>'};	    

	
	};
    });


function change_block(){
ids=['overview','map','continents','wregions','countries'];
block_ids=['block_overview','block_map','block_continents','block_wregions','block_countries'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=report_geo_sales-world-view&value='+this.id ,{});
}



function change_map_link(){
ids=['map_links_countries','map_links_continents','map_links_wregions'];
map_ids=['map_countries','map_continents','map_wregions'];

Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected')
var the_id=this.id.replace("map_links_", "");
Dom.setStyle(map_ids,'display','none');
Dom.setStyle('map_'+the_id,'display','');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=report_geo_sales-world-map_links&value='+escape(      the_id     ),{});
}

 function init(){

 ids=['overview','map','continents','wregions','countries'];
YAHOO.util.Event.addListener(ids, "click",change_block);

 var ids=['map_links_countries','map_links_continents','map_links_wregions'];
YAHOO.util.Event.addListener(ids, "click",change_map_link);

 
YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_show1', "click",show_filter,1);
 YAHOO.util.Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);
  YAHOO.util.Event.addListener('clean_table_filter_show2', "click",show_filter,2);
 YAHOO.util.Event.addListener('clean_table_filter_hide2', "click",hide_filter,2);

 //init_search('country_list');
 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 

var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS1.queryMatchContains = true;
 var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS);
 oAutoComp1.minQueryLength = 0; 

var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS2.queryMatchContains = true;
 var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2","f_container2", oACDS);
 oAutoCom2p.minQueryLength = 0; 
//init_search('world_region');
  alert("x")

 }

 YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
 
YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
    
YAHOO.util.Event.onContentReady("rppmenu2", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu2", {trigger:"rtext_rpp2" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu2", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {  trigger: "filter_name2"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });    