<?php
include_once('common.php');
$store_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));
$title='';

foreach( $store_period_title as $key=>$value){
$title.=sprintf(',%s:"%s"',$key,$value);
}
$title=preg_replace('/^,/','',$title);
?>

var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;

var info_period_title={<?php echo $title ?>};
var current_store_period='<?php echo $_SESSION['state']['family']['products']['period']?>';

function change_block(){
ids=['details','customers','orders','timeline','sales', 'web_site'];
block_ids=['block_details','block_customers','block_orders','block_timeline','block_sales', 'block_web_site'];

Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=product-block_view&value='+this.id ,{});
}



function change_sales_period(){
  tipo=this.id;
 
  ids=['products_period_yesterday','products_period_last_m','products_period_last_w','products_period_all','products_period_three_year','products_period_year','products_period_six_month','products_period_quarter','products_period_month','products_period_ten_day','products_period_week','products_period_yeartoday','products_period_monthtoday','products_period_weektoday','products_period_today'];

 Dom.removeClass(ids,"selected")
 Dom.addClass(this,"selected")
   period=this.getAttribute('period');
 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=family-products-period&value='+period ,{});

Dom.setStyle(['info_yesterday','info_last_m','info_last_w','info_all','info_three_year','info_year','info_six_month','info_quarter','info_month','info_ten_day','info_week','info_yeartoday','info_monthtoday','info_weektoday','info_today'],'display','none')


Dom.setStyle(['info2_yesterday','info2_last_m','info2_last_w','info2_all','info2_three_year','info2_year','info2_six_month','info2_quarter','info2_month','info2_ten_day','info2_week','info2_yeartoday','info2_monthtoday','info2_weektoday','info2_today'],'display','none')
Dom.setStyle(['info_'+period,'info2_'+period],'display','')

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
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=family-period&value='+period,{});

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


YAHOO.util.Event.addListener(window, "load", function() {
	      tables = new function() {
		
		      
		      
		      var tableid=0;
		      var tableDivEL="table"+tableid;
		      var ColumnDefs = [
					{key:"order", label:"<?php echo _('Number')?>", width:90,className:"aleft", sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					
				      ,{key:"customer_name", label:"<?php echo _('Customer')?>", width:220, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"date", label:"<?php echo _('Date')?>", sortable:true, width:100,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"dispatched", label:"<?php echo _('Dispatched')?>",width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"undispatched", label:"<?php echo _('No Send')?>", width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					];
		      
		      
		      this.dataSource0 = new YAHOO.util.DataSource("ar_orders.php?tipo=withproduct&product_pid="+Dom.get('product_pid').value+"&tableid="+tableid);
		     // alert("ar_orders.php?tipo=withproduct&product_pid="+Dom.get('product_pid').value+"&tableid="+tableid)
		      
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
				   "id","order","customer_name","date","dispatched","undispatched"
				   ]};
		      
		      this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							       this.dataSource0, {
								   //draggableColumns:true,
								   renderLoopSize: 50,generateRequest : myRequestBuilder
								   ,paginator : new YAHOO.widget.Paginator({
									   rowsPerPage:<?php echo (!$_SESSION['state']['product']['orders']['nr']?25:$_SESSION['state']['product']['orders']['nr'] )?>,containers : 'paginator0', 
									 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
									 firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								       })
								   
								   ,sortedBy : {
								      key: "<?php echo $_SESSION['state']['product']['orders']['order']?>",
								       dir: "<?php echo $_SESSION['state']['product']['orders']['order_dir']?>"
								   }
								   ,dynamicData : true
								   
							     }
							       );
		      this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
		      this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
		      this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		      
		    	    this.table0.filter={key:'<?php echo$_SESSION['state']['product']['orders']['f_field']?>',value:'<?php echo$_SESSION['state']['product']['orders']['f_value']?>'};

		      
		   
		      var tableid=1;
		      var tableDivEL="table"+tableid;
		      
		      var ColumnDefs = [
					{key:"customer", label:"<?php echo _('Customer')?>",width:320, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"orders", label:"<?php echo _('Orders')?>",width:70, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"dispatched", label:"<?php echo _('Disp')?>",width:65, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"to_dispatch", label:"<?php echo _('To Disp')?>",width:65, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"nodispatched", label:"<?php echo _('No Disp')?>", width:65, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"charged", label:"<?php echo _('Charged')?>", width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					];
		    
		      
		      this.dataSource1 = new YAHOO.util.DataSource("ar_assets.php?tipo=customers_who_order_product&product_pid="+Dom.get('product_pid').value+"&tableid="+tableid);
	//alert("ar_assets.php?tipo=customers_who_order_product&product_pid="+Dom.get('product_pid').value+"&tableid="+tableid)
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
				  "customer","dispatched","nodispatched","charged","to_dispatch","orders"
				   ]};
		      
		     
		      
		      this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							       this.dataSource1, {
								   //draggableColumns:true,
								   renderLoopSize: 50,generateRequest : myRequestBuilder
								 ,paginator : new YAHOO.widget.Paginator({
									 rowsPerPage:<?php echo (!$_SESSION['state']['product']['orders']['nr']?25:$_SESSION['state']['product']['customers']['nr'] )?>,containers : 'paginator1', 
									 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
									 firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								   
								   ,sortedBy : {
								      key: "<?php echo $_SESSION['state']['product']['customers']['order']?>",
								       dir: "<?php echo $_SESSION['state']['product']['customers']['order_dir']?>"
								   }
								   ,dynamicData : true
								 
							       }
							       );
		      this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
		      this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
		      this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		  		    	    this.table1.filter={key:'<?php echo$_SESSION['state']['product']['customers']['f_field']?>',value:'<?php echo$_SESSION['state']['product']['customers']['f_value']?>'};


 var tableid=3;
		      var tableDivEL="table"+tableid;
		      
		      var ColumnDefs = [
					{key:"pid", label:"<?php echo _('Product ID (Key)')?>",width:125, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"from", label:"<?php echo _('From')?>",width:100, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"to", label:"<?php echo _('To')?>",width:100, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

					,{key:"description", label:"<?php echo _('Description')?>",width:300, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"parts", label:"<?php echo _('Parts')?>",width:65, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
,{key:"sales", label:"<?php echo _('Sales')?>",width:75, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

					];
		    
		      
		      this.dataSource3 = new YAHOO.util.DataSource("ar_assets.php?tipo=product_code_timeline&tableid="+tableid);
		      this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
		      this.dataSource3.connXhrMode = "queueRequests";
		      this.dataSource3.responseSchema = {
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
				  "pid","description","parts","from","to","sales"
				   ]};
		      
		      this.table3 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							       this.dataSource3, {
								   //draggableColumns:true,
								   renderLoopSize: 50,generateRequest : myRequestBuilder
								 ,paginator : new YAHOO.widget.Paginator({
									 rowsPerPage:<?php echo (!$_SESSION['state']['product']['orders']['nr']?25:$_SESSION['state']['product']['code_timeline']['nr'] )?>,containers : 'paginator3', 
									 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
									 firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								   
								   ,sortedBy : {
								      key: "<?php echo $_SESSION['state']['product']['code_timeline']['order']?>",
								       dir: "<?php echo $_SESSION['state']['product']['code_timeline']['order_dir']?>"
								   }
								   ,dynamicData : true
								 
							       }
							       );
		      this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
		      this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
		      this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;

	    
	    };
    });

var manual_check=function(){
    var request='ar_assets.php?tipo=sincro_pages';
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);	
		if(r.ok){
		    Dom.get('no_sincro_pages').style.visibility='hidden';
		    Dom.get('no_sincro_pages').setAttribute('title','');
		    Dom.get('edit_web_messages').innerHTML=r.msg;
	
		}else
		    Dom.get('edit_web_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	    
	});
}

function change_web_configuration(o,product_pid){
region1 = Dom.getRegion(o); 
    region2 = Dom.getRegion('dialog_edit_web_state'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('dialog_edit_web_state', pos);
Dom.get('product_pid').value=product_pid
dialog_edit_web_state.show()

}

function set_web_configuration(value){
  var request='ar_edit_assets.php?tipo=edit_product&key=web_configuration&pid='+Dom.get('product_pid').value+'&newvalue='+value
//   alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
		//	alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state==200) {
		  dialog_edit_web_state.hide()
		  Dom.get('product_web_state_'+r.newdata.pid).innerHTML=r.newdata.icon
		  Dom.get('product_web_configuration_'+r.newdata.pid).innerHTML=r.newdata.formated_web_configuration_bis

		}else{
		  alert(r.msg);
	    }
	    }
	});    
}

function init(){
dialog_edit_web_state = new YAHOO.widget.Dialog("dialog_edit_web_state", {visible : false,close:true,underlay: "none",draggable:false});
dialog_edit_web_state.render();
image_region=Dom.getRegion('main_image')
if(image_region.height>160){
Dom.setStyle('main_image','height','160px')
Dom.setStyle('main_image','width','')

}


 init_search('products_store');


 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
   oACDS.table_id=0;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 


 var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS1.queryMatchContains = true;
   oACDS1.table_id=1;
 var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS1);
 oAutoComp1.minQueryLength = 0; 


    Event.addListener(['details','customers','orders','timeline','sales', 'web_site'], "click",change_block);


 YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
  YAHOO.util.Event.addListener('clean_table_filter_show1', "click",show_filter,1);
 YAHOO.util.Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);
  YAHOO.util.Event.addListener('clean_table_filter_show2', "click",show_filter,2);
 YAHOO.util.Event.addListener('clean_table_filter_hide2', "click",hide_filter,2);


   ids=['products_period_yesterday','products_period_last_m','products_period_last_w','products_period_all','products_period_three_year','products_period_year','products_period_yeartoday','products_period_six_month','products_period_quarter','products_period_month','products_period_ten_day','products_period_week','products_period_monthtoday','products_period_weektoday','products_period_today'];
 YAHOO.util.Event.addListener(ids, "click",change_sales_period);
   
YAHOO.util.Event.addListener("info_next", "click",next_info_period,0);
YAHOO.util.Event.addListener("info_previous", "click",previous_info_period,0);


     YAHOO.util.Event.onContentReady("web_status_menu", function () {
	     var oMenu = new YAHOO.widget.Menu("web_status_menu", { context:["web_status","tl", "bl"]  });
	     oMenu.render();
	     oMenu.subscribe("show", oMenu.focus);
	     YAHOO.util.Event.addListener("web_status", "click", oMenu.show, null, oMenu);
    });


}
 YAHOO.util.Event.onDOMReady(init);
 
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
