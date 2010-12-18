<?php


include_once('common.php');

$store_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));
$title='';

foreach( $store_period_title as $key=>$value){
$title.=sprintf(',%s:"%s"',$key,$value);
}
$title=preg_replace('/^,/','',$title);


?>



var info_period_title={<?php echo $title ?>};
var Dom   = YAHOO.util.Dom;
var Event   = YAHOO.util.Event;

var current_store_period='<?php echo$_SESSION['state']['department']['period']?>';

var period='period_<?php echo$_SESSION['state']['products']['period']?>';
var avg='avg_<?php echo$_SESSION['state']['products']['avg']?>';

var addtotals =function (){
    //alert("caca");
  var data={code:'<?php echo _('Totals')?>'};
    tables.table0.addRow(data,3);

}


   


function change_info_period(period){
    var patt=new RegExp("^(year|month|all|week|quarter)$");
    if (patt.test(period)==true && current_store_period!=period){
	//alert('info_'+current_store_period)
	//	alert('ar_sessions.php?tipo=update&keys=store-period&value=');
	Dom.get('info_'+current_store_period).style.display='none';
	Dom.get('info_'+period).style.display='';
	current_store_period=period;

	Dom.get('info_title').innerHTML=info_period_title[period];
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=department-period&value='+period);

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




    var change_view=function(e){
	tipo=this.id;
	var table=tables['table0'];

	table.hideColumn('name');
	table.hideColumn('smallname');
	
	table.hideColumn('stock');
	table.hideColumn('sales');
	table.hideColumn('profit');
	table.hideColumn('sold');
	table.hideColumn('margin');
	
	table.hideColumn('parts');
	table.hideColumn('supplied');
	table.hideColumn('gmroi');
		table.hideColumn('formated_record_type');  

	table.hideColumn('family');
	//table.hideColumn('dept');
	table.hideColumn('expcode');  
	table.hideColumn('state');
	table.hideColumn('web');
table.hideColumn('stock_state');
	table.hideColumn('stock_forecast');
	if(tipo=='sales'){
	    table.showColumn('sold');
	    table.showColumn('sales');
	    table.showColumn('profit');
	    table.showColumn('margin');
	    table.showColumn('smallname');

	    Dom.get('period_options').style.display='';
	    Dom.get('avg_options').style.display='';
	}else if(tipo=='general'){

	    Dom.get('period_options').style.display='none';
	    Dom.get('avg_options').style.display='none';
	    table.showColumn('name');
	    //table.showColumn('state');
	    table.showColumn('web');
	    table.showColumn('stock');
	    table.showColumn('formated_record_type');  

	}else if(tipo=='stock'){
	    table.showColumn('stock');
	    table.showColumn('stock_value');
	    table.showColumn('smallname');
	    table.showColumn('web');
        table.showColumn('stock_state');
        table.showColumn('stock_forecast');
	    Dom.get('period_options').style.display='none';
	    Dom.get('avg_options').style.display='none';
	}else if(tipo=='parts'){
	    table.showColumn('parts');
	    table.showColumn('supplied');
	    table.showColumn('gmroi');
	    	    table.showColumn('smallname');

	    Dom.get('period_options').style.display='none';
	    Dom.get('avg_options').style.display='none';
	    
	}else if(tipo=='cats'){
	    Dom.get('period_options').style.display='none';
	    Dom.get('avg_options').style.display='none';
	    table.showColumn('family');
	  //  table.showColumn('dept');
	    table.showColumn('expcode');
	    table.showColumn('smallname');

	}



	Dom.get(table.view).className="";
	Dom.get(tipo).className="selected";	
	table.view=tipo;
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=products-view&value='+escape(tipo));
    }


var myRowFormatter = function(elTr, oRecord) {
    if (oRecord.getData('code') =='total') {
        Dom.addClass(elTr, 'total');
    }
    return true;
}; 




YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	    var tableid=0;
	    var tableDivEL="table"+tableid;

	    var myRowFormatter = function(elTr, oRecord) {
		if (oRecord.getData('record_type')=='Discontinued') {
		    Dom.addClass(elTr, 'discontinued');
		}
		return true;
	    }; 




	    var ColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:87,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>",width:390,<?php echo(($_SESSION['state']['products']['view']=='general' )?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
								 ,{key:"smallname", label:"<?php echo _('Name')?>",width:300, sortable:true,className:"aleft",className:"aleft",<?php echo($_SESSION['state']['products']['view']=='general'?'hidden:true,':'')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"formated_record_type", label:"<?php echo _('State')?>",width:100,<?php echo(($_SESSION['state']['products']['view']=='general' or $_SESSION['state']['products']['view']=='stock')?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    //	,{key:"state", label:"<?php echo _('State')?>",width:100,<?php echo(($_SESSION['state']['products']['view']=='general' or $_SESSION['state']['products']['view']=='stock')?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"web", label:"<?php echo _('Web')?>",width:90,<?php echo(($_SESSION['state']['products']['view']=='general' or $_SESSION['state']['products']['view']=='stock' )?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"sold", label:"<?php echo _('Sold')?>",width:100,<?php echo($_SESSION['state']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"sales", label:"<?php echo _('Sales')?>",width:100,<?php echo($_SESSION['state']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?php echo _('Profit')?>",width:100,<?php echo($_SESSION['state']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"margin", label:"<?php echo _('Margin')?>",width:100,<?php echo($_SESSION['state']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock", label:"<?php echo _('Available')?>", width:65,sortable:true,className:"aright",<?php echo(($_SESSION['state']['products']['view']=='stock' or $_SESSION['state']['products']['view']=='general')  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_state", label:"<?php echo _('State')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['products']['view']=='stock' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_forecast", label:"<?php echo _('Forecast')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['products']['view']=='stock' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   
				   ,{key:"parts", label:"<?php echo _('Parts')?>",width:130,<?php echo($_SESSION['state']['products']['view']!='parts'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"supplied", label:"<?php echo _('Supplied by')?>",width:130,<?php echo($_SESSION['state']['products']['view']!='parts'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"gmroi", label:"<?php echo _('GMROI')?>", width:100,sortable:true,className:"aright",<?php echo(($_SESSION['state']['products']['view']=='parts' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    //,{key:"dept", label:"<?php echo _('Main Department')?>",width:200,<?php echo($_SESSION['state']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"expcode", label:"<?php echo _('Tariff Code')?>",width:160,<?php echo($_SESSION['state']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}


			       ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=products&parent=family");
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
			 'id'
			 ,"code"
			 ,"name","stock","stock_value","record_type","formated_record_type"
			 ,'sales','profit','margin','sold',"parts","supplied","gmroi","family","dept","expcode","state","web","smallname"
			 ]};
	    
// 	    var myRowFormatter = function(elTr, oRecord) {
// 		if (oRecord.getData('total')==1) {
// 		    Dom.addClass(elTr, 'total');
// 		}
// 		return true;
// 	    }; 

	    

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							 formatRow: myRowFormatter,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage:<?php echo$_SESSION['state']['products']['table']['nr']+1?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['products']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['products']['table']['order_dir']?>"
								     }
							   ,dynamicData : true  

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginator = mydoBeforePaginatorChange;

		this.table0.filter={key:'<?php echo$_SESSION['state']['family']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['family']['table']['f_value']?>'};

	    
	    this.table0.view='<?php echo$_SESSION['state']['products']['view']?>';

		





	};
    });



 function init(){

  YAHOO.util.Event.addListener('export_csv0', "click",download_csv,'products_in_family');
 YAHOO.util.Event.addListener('export_csv0_in_dialog', "click",download_csv_from_dialog,{table:'export_csv_table0',tipo:'products_in_family'});
  csvMenu = new YAHOO.widget.ContextMenu("export_csv_menu0", {trigger:"export_csv0" });
	 csvMenu.render();
	 csvMenu.subscribe("show", csvMenu.focus);
   
 YAHOO.util.Event.addListener('export_csv0_close_dialog', "click",csvMenu.hide,csvMenu,true);

  init_search('products_store');
 
 
 YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
 
 
 
 get_thumbnails({tipo:'products',parent:'family'});

ids=['general','sales','stock','parts','cats'];
 YAHOO.util.Event.addListener(ids, "click",change_view);
 ids=['period_all','period_year','period_quarter','period_month','period_week'];
 YAHOO.util.Event.addListener(ids, "click",change_period,0);
 ids=['avg_totals','avg_month','avg_week',"avg_month_eff","avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click",change_avg,0);
 ids=['table_type_thumbnail','table_type_list'];
 YAHOO.util.Event.addListener(ids, "click",change_table_type,{table_id:0,parent:'family'});
 
     
YAHOO.util.Event.addListener("info_next", "click",next_info_period,0);
YAHOO.util.Event.addListener("info_previous", "click",previous_info_period,0);

    
YAHOO.util.Event.addListener('details', "click",change_details,'family');
//YAHOO.util.Event.addListener('show_percentages', "click",show_percentages,'departments');



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
