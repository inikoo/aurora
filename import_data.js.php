<?php
include_once('common.php');
$store_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));
$title='';

foreach( $store_period_title as $key=>$value){
$title.=sprintf(',%s:"%s"',$key,$value);
}
$title=preg_replace('/^,/','',$title);

?>
 var Dom   = YAHOO.util.Dom;
 var Event  =YAHOO.util.Event;
 var info_period_title={<?php echo $title ?>};
  var current_store_period='<?php echo$_SESSION['state']['stores']['period']?>';


var Dom = YAHOO.util.Dom;

var period='period_<?php echo$_SESSION['state']['store']['period']?>';
var avg='avg_<?php echo$_SESSION['state']['store']['avg']?>';

var change_view=function(e){
    
    var table=tables['table0'];
    var tipo=this.id;
    //	alert(table.view+' '+tipo)
    if(table.view!=tipo){
	table.hideColumn('active');
	table.hideColumn('todo');
	table.hideColumn('discontinued');
	
	table.hideColumn('families');
	table.hideColumn('sales');
	table.hideColumn('profit');
	//    table.hideColumn('stock_value');
	table.hideColumn('stock_error');
	table.hideColumn('outofstock');
	table.hideColumn('surplus');
	table.hideColumn('optimal');
	table.hideColumn('low');
	table.hideColumn('critcal');
	
	if(tipo=='sales'){
	    Dom.get('period_options').style.display='';
	    Dom.get('avg_options').style.display='';
	    table.showColumn('sales');
	    table.showColumn('profit');
	}
	if(tipo=='general'){
	    Dom.get('period_options').style.display='none';
	    Dom.get('avg_options').style.display='none';
	    table.showColumn('active');
	    table.showColumn('todo');
	    table.showColumn('families');
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
	table.view=tipo;
	//alert('ar_sessions.php?tipo=update&keys=store-view&value=' + escape(tipo))
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=store-view&value=' + escape(tipo) ,{
					success:function(o) {
					       
					    }
					    }
	
	);
    }
}



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {





	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"families", label:"<?php echo _('Families')?>", width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"active", label:"<?php echo _('Products')?>",  width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				     ,{key:"discontinued", label:"<?php echo _('Discontinued')?>",  width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    ,{key:"sales", label:"<?php echo _('Sales')?>", width:80,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?php echo _('Profit')?>", width:80,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"aws_p", label:"<?php echo _('Aw S/P')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"awp_p", label:"<?php echo _('Aw P/P')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


				    ,{key:"surplus", label:"<?php echo _('Surplus')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"optimal", label:"<?php echo _('OK')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"low", label:"<?php echo _('Low')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}




				    ,{key:"critical", label:"<?php echo _('Critical')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    



				    ,{key:"outofstock", label:"<?php echo _('Gone')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_error", label:"<?php echo _('Unknown')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=departments&parent=store");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		 rowsPerPage:"resultset.records_perpage",
		    RecordOffset : "resultset.records_offset", 
		       rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		    
		  
		},
		
		fields: [
			 'id',
			 "name","code","aws_p","awp_p",
			 'families',
			 'active',"sales","stock_error","stock_value","outofstock","profit","surplus","optimal","low","critical","todo","discontinued"
			 ]};
	    

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo $_SESSION['state']['store']['table']['nr']+1?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['store']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['store']['table']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
       this.table0.doBeforeLoadData=mydoBeforeLoadData;


	    
	    this.table0.view='<?php echo$_SESSION['state']['store']['view']?>';

      this.table0.filter={key:'<?php echo$_SESSION['state']['store']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['store']['table']['f_value']?>'};






	};
    });





function change_info_period(period){
    var patt=new RegExp("^(year|month|all|week|quarter)$");
    if (patt.test(period)==true && current_store_period!=period){
	//alert('info_'+current_store_period)
	//	alert('ar_sessions.php?tipo=update&keys=store-period&value=');
	Dom.get('info_'+current_store_period).style.display='none';
	Dom.get('info_'+period).style.display='';
	current_store_period=period;

	Dom.get('info_title').innerHTML=info_period_title[period];
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=stores-period&value='+period);

    }

}

function next_info_period(){
    if(current_store_period=='all')
        change_info_period('week');
    else if(current_store_period=='week')    
        change_info_period('month');
    else if(current_store_period=='month')    
        change_info_period('quarter');
    else if(current_store_period=='quarter')    
        change_info_period('year');        
    else if(current_store_period=='year')    
        change_info_period('all');
}

function previous_info_period(){
    if(current_store_period=='all')
        change_info_period('year');
    else if(current_store_period=='week')    
        change_info_period('all');
    else if(current_store_period=='month')    
        change_info_period('week');
    else if(current_store_period=='quarter')    
        change_info_period('month');        
    else if(current_store_period=='year')    
        change_info_period('quarter');
}





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




 function init(){
 
 

 }



YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });



YAHOO.util.Event.onContentReady("info_period_menu", function () {
	 var oMenu = new YAHOO.widget.Menu("info_period_menu", { context:["info_period","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("info_period", "click", oMenu.show, null, oMenu);
    });
    
    
    
    
    
    