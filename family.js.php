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

var current_store_period='<?php echo$_SESSION['state']['department']['period']?>';

var period='period_<?php echo$_SESSION['state']['products']['period']?>';
var avg='avg_<?php echo$_SESSION['state']['products']['avg']?>';

var addtotals =function (){
    //alert("caca");
  var data={code:'<?php echo _('Totals')?>'};
    tables.table0.addRow(data,3);

}


    function get_thumbnails(){
	var table_id=0;
	var request='ar_assets.php?tipo=products&parent=family';
	YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {

		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.resultset.state==200){
		    var container=Dom.get('thumbnails'+table_id);
		    for(x in r.resultset.data){
			if(r.resultset.data[x].type=='item'){
			var img = new YAHOO.util.Element(document.createElement('img')); 
			img.set('src', r.resultset.data[x].image); 
			
			var internal_span = new YAHOO.util.Element(document.createElement('span')); 
			internal_span.set('innerHTML', r.resultset.data[x].code); 
		 
			var div = new YAHOO.util.Element(document.createElement('div')); 
		

			img.appendTo(div); 
			internal_span.appendTo(div); 
		
		

			div.appendTo(container); 
			}
		    }
		      
		}
		
	    }
	    
	    });
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
	table.hideColumn('stock');
	table.hideColumn('stock_value');
	table.hideColumn('sales');
	table.hideColumn('profit');
	table.hideColumn('sold');
	table.hideColumn('margin');
	table.hideColumn('shortname');
	
	table.hideColumn('parts');
	table.hideColumn('supplied');
	table.hideColumn('gmroi');
	
	table.hideColumn('family');
	table.hideColumn('dept');
	table.hideColumn('expcode');  
	table.hideColumn('state');
	table.hideColumn('web');


	if(tipo=='sales'){
	    table.showColumn('sold');
	    table.showColumn('sales');
	    table.showColumn('profit');
	    table.showColumn('margin');
	    table.showColumn('shortname');

	    Dom.get('period_options').style.display='';
	    Dom.get('avg_options').style.display='';
	}else if(tipo=='general'){

	    Dom.get('period_options').style.display='none';
	    Dom.get('avg_options').style.display='none';
	    table.showColumn('name');
	    table.showColumn('state');
	    table.showColumn('web');
	    table.showColumn('stock');
	    
	}else if(tipo=='stock'){
	    table.showColumn('stock');
	    table.showColumn('stock_value');
	    Dom.get('period_options').style.display='none';
	    Dom.get('avg_options').style.display='none';
	    table.showColumn('shortname');
	}else if(tipo=='parts'){
	    table.showColumn('parts');
	    table.showColumn('supplied');
	    table.showColumn('gmroi');
	    Dom.get('period_options').style.display='none';
	    Dom.get('avg_options').style.display='none';
	    
	}else if(tipo=='cats'){
	    Dom.get('period_options').style.display='none';
	    Dom.get('avg_options').style.display='none';
	    table.showColumn('family');
	    table.showColumn('dept');
	    table.showColumn('expcode');
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
	    var ColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:87,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>",width:400,<?php echo(($_SESSION['state']['products']['view']!='general')?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"shortname", label:"<?php echo _('Description')?>",width:110,<?php echo($_SESSION['state']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"state", label:"<?php echo _('State')?>",width:100,<?php echo(($_SESSION['state']['products']['view']!='general')?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"web", label:"<?php echo _('Web')?>",width:100,<?php echo(($_SESSION['state']['products']['view']!='general')?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"sold", label:"<?php echo _('Sold')?>",width:100,<?php echo($_SESSION['state']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"sales", label:"<?php echo _('Sales')?>",width:100,<?php echo($_SESSION['state']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?php echo _('Profit')?>",width:100,<?php echo($_SESSION['state']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"margin", label:"<?php echo _('Margin')?>",width:100,<?php echo($_SESSION['state']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock", label:"<?php echo _('Available')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['products']['view']=='stock' or $_SESSION['state']['products']['view']=='general')  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"parts", label:"<?php echo _('Parts')?>",width:200,<?php echo($_SESSION['state']['products']['view']!='parts'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"supplied", label:"<?php echo _('Supplied by')?>",width:200,<?php echo($_SESSION['state']['products']['view']!='parts'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"gmroi", label:"<?php echo _('GMROI')?>", width:100,sortable:true,className:"aright",<?php echo(($_SESSION['state']['products']['view']=='parts' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    ,{key:"dept", label:"<?php echo _('Main Department')?>",width:300,<?php echo($_SESSION['state']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"expcode", label:"<?php echo _('TC(UK)')?>",width:200,<?php echo($_SESSION['state']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}


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
			 ,"name","stock","stock_value"
			 ,'sales','profit','margin','sold',"parts","supplied","gmroi","family","dept","expcode","shortname","state","web"
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

						     
	    
	    this.table0.view='<?php echo$_SESSION['state']['products']['view']?>';

		





	};
    });


function change_table_type(e,table_id){
if(Dom.hasClass(this, 'state_details_selected'))
 return;
 var elements=Dom.getElementsByClassName('state_details_selected', 'span', 'table_type');
 Dom.removeClass(elements, 'state_details_selected');
 Dom.addClass(this, 'state_details_selected');
 if(this.id=='table_type_list'){
 Dom.get('thumbnails'+table_id).style.display='none'
  Dom.get('table'+table_id).style.display=''
Dom.get('table'+table_id).style.display=''
 
 }else{
  Dom.get('thumbnails'+table_id).style.display=''
 Dom.get('table'+table_id).style.display='none'
Dom.get('list_options'+table_id).style.display='none'
 
 }
 
}

 function init(){
 

 get_thumbnails();

ids=['general','sales','stock','parts','cats'];
 YAHOO.util.Event.addListener(ids, "click",change_view);
 ids=['period_all','period_year','period_quarter','period_month','period_week'];
 YAHOO.util.Event.addListener(ids, "click",change_period,0);
 ids=['avg_totals','avg_month','avg_week',"avg_month_eff","avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click",change_avg,0);
 ids=['table_type_thumbnail','table_type_list'];
 YAHOO.util.Event.addListener(ids, "click",change_table_type,0);
 
     
YAHOO.util.Event.addListener("info_next", "click",next_info_period,0);
YAHOO.util.Event.addListener("info_previous", "click",previous_info_period,0);

    
YAHOO.util.Event.addListener('details', "click",change_details,'family');
//YAHOO.util.Event.addListener('show_percentages', "click",show_percentages,'departments');



 YAHOO.util.Event.addListener('product_submit_search', "click",submit_search,'product');
 YAHOO.util.Event.addListener('product_search', "keydown", submit_search_on_enter,'product');



 }




YAHOO.util.Event.onDOMReady(init);


YAHOO.util.Event.onContentReady("rppmenu", function () {
	 var oMenu = new YAHOO.widget.Menu("rppmenu", { context:["rtext_rpp0","tl", "tr"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("rtext_rpp0", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("filtermenu", function () {
	 var oMenu = new YAHOO.widget.Menu("filtermenu", { context:["filter_name0","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name0", "click", oMenu.show, null, oMenu);
    });
YAHOO.util.Event.onContentReady("info_period_menu", function () {
	 var oMenu = new YAHOO.widget.Menu("info_period_menu", { context:["info_period","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("info_period", "click", oMenu.show, null, oMenu);
    });