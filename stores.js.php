<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
?>
var Dom   = YAHOO.util.Dom;

var period='period_<?php echo $_SESSION['state']['stores']['period']?>';
var avg='avg_<?php echo $_SESSION['state']['stores']['avg']?>';





 YAHOO.util.Event.onAvailable("rendertarget", function () {


var oMenu = new YAHOO.widget.Menu("menuwithgroups", { autosubmenudisplay: true } ); 
oMenu.addItems([

    [
        { text: "Yahoo! Mail", url: "http://mail.yahoo.com" },
        { text: "Yahoo! Address Book", url: "http://addressbook.yahoo.com" },
        { text: "Yahoo! Calendar", url: "http://calendar.yahoo.com" },
        { text: "Yahoo! Notepad", url: "http://notepad.yahoo.com" }
    ],

    [

        { text: "Yahoo! Local", url: "http://local.yahoo.com" },
        { text: "Yahoo! Maps", url: "http://maps.yahoo.com" },
        { text: "Yahoo! Travel", url: "http://travel.yahoo.com" },
        { text: "Yahoo! Shopping", url: "http://shopping.yahoo.com" } 
    
    ],

    [

        { text: "Yahoo! Messenger", url: "http://messenger.yahoo.com" },
        { text: "Yahoo! 360", url: "http://360.yahoo.com" },
        { text: "Yahoo! Groups", url: "http://groups.yahoo.com" },
        { text: "Flickr Photo Sharing", url: "http://www.flickr.com" }
    
    ]

]);

oMenu.render("rendertarget"); 




     }
     );





    var change_view=function(e){
	
	var table=tables['table0'];
	var tipo=this.id;
	//	alert(table.view+' '+tipo)
	if(table.view!=tipo){
	    table.hideColumn('active');
	    table.hideColumn('families');
	    table.hideColumn('departments');
	    table.hideColumn('todo');
	    table.hideColumn('discontinued');

	    table.hideColumn('sales');
	    table.hideColumn('profit');
	    //    table.hideColumn('stock_value');
	    table.hideColumn('stock_error');
	    table.hideColumn('outofstock');
	    table.hideColumn('surplus');
	    table.hideColumn('optimal');
	    table.hideColumn('low');
	    table.hideColumn('critcal');
	    table.hideColumn('margin');

	    if(tipo=='sales'){
		Dom.get('period_options').style.display='';
		Dom.get('avg_options').style.display='';
		table.showColumn('sales');
		table.showColumn('profit');
		table.showColumn('margin');

	    }
	    if(tipo=='general'){
		Dom.get('period_options').style.display='none';
		Dom.get('avg_options').style.display='none';
		table.showColumn('active');
		table.showColumn('families');
		table.showColumn('departments');
		table.showColumn('todo');
		table.showColumn('discontinued');

	    }
	    if(tipo=='stock'){
		Dom.get('period_options').style.display='none';
		Dom.get('avg_options').style.display='none';
		
		table.showColumn('surplus');
		table.showColumn('optimal');
		table.showColumn('low');
		table.showColumn('critcal');
		table.showColumn('stock_error');
		table.showColumn('outofstock');
	    }

	    

	
	Dom.get(table.view).className="";
	Dom.get(tipo).className="selected";
	table.view=tipo
	    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=stores-view&value=' + escape(tipo),{} );
	}
  }



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"name", label:"<?php echo _('Name')?>", width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				
				    ,{key:"departments", label:"<?php echo _('Departments')?>", width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				    ,{key:"families", label:"<?php echo _('Families')?>", width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"active", label:"<?php echo _('Products')?>",  width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"discontinued", label:"<?php echo _('Discontinued')?>",  width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"new", label:"<?php echo _('New')?>",  width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				    ,{key:"sales", label:"<?php echo _('Sales')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?php echo _('Profit')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"margin", label:"<?php echo _('Margin')?>", width:120,sortable:false,className:"aright",<?php echo($_SESSION['state']['stores']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


				    ,{key:"surplus", label:"<?php echo _('Surplus')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"optimal", label:"<?php echo _('OK')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"low", label:"<?php echo _('Low')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}




				    ,{key:"critical", label:"<?php echo _('Critical')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    



				    ,{key:"outofstock", label:"<?php echo _('Gone')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_error", label:"<?php echo _('Unknown')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=stores");
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
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 'id',
			 "name",
			 'families','departments',
			 'active',"sales","stock_error","stock_value","outofstock","profit","surplus","optimal","low","critical","code","new","discontinued","margin"
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['stores']['table']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['stores']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['stores']['table']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
       this.table0.doBeforeLoadData=mydoBeforeLoadData;


	    
	    this.table0.view='<?php echo$_SESSION['state']['stores']['view']?>';
	    this.table0.filter={key:'<?php echo$_SESSION['state']['stores']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['stores']['table']['f_value']?>'};

		





	};
    });


function change_period(e,table_id){

    tipo=this.id;
    Dom.get(period).className="";
    Dom.get(tipo).className="selected";	
    period=tipo;
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
    var request='&period=' + this.getAttribute('period');
    // alert(request);
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
}
function change_avg(e,table_id){

    //  alert(avg);
    tipo=this.id;
    Dom.get(avg).className="";
    Dom.get(tipo).className="selected";	
    avg=tipo;
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
    var request='&avg=' + this.getAttribute('avg');
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
}


function change_display_mode(name,label){
    if(name=='percentage'){
	var request='&percentages=1';
    }if(name=='value'){
	var request='&percentages=0&show_default_currency=0';
    }if(name=='value_default_d2d'){
	var request='&percentages=0&show_default_currency=1';
    }

    Dom.get('change_display_mode').innerHTML=label;
    var table=tables['table0'];
    var datasource=tables.dataSource0;
    
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);   

}



 function init(){
 
 
    search_scope='products';
     var store_name_oACDS = new YAHOO.util.FunctionDataSource(search_products);
     store_name_oACDS.queryMatchContains = true;
     var store_name_oAutoComp = new YAHOO.widget.AutoComplete(search_scope+"_search",search_scope+"_search_Container", store_name_oACDS);
     store_name_oAutoComp.minQueryLength = 0; 
     store_name_oAutoComp.queryDelay = 0.15;
     
  

     
 
var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 

 YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);

 ids=['general','sales','stock'];
 YAHOO.util.Event.addListener(ids, "click",change_view)
 ids=['period_all','period_year','period_quarter','period_month','period_week'];
 YAHOO.util.Event.addListener(ids, "click",change_period,0);
 ids=['avg_totals','avg_month','avg_week',"avg_month_eff","avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click",change_avg,0);

 //YAHOO.util.Event.addListener('show_details', "click",show_details,'stores');
 YAHOO.util.Event.addListener('show_percentages', "click",show_percentages,'stores');


 YAHOO.util.Event.addListener('product_submit_search', "click",submit_search,'product');
 YAHOO.util.Event.addListener('product_search', "keydown", submit_search_on_enter,'product');




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

YAHOO.util.Event.onContentReady("change_display_menu", function () {
	 var oMenu = new YAHOO.widget.Menu("change_display_menu", { context:["change_display_mode","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("change_display_mode", "click", oMenu.show, null, oMenu);
  
    });
