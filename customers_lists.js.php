<?php
include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;

var searched=false;

var data_returned=function(){
	 if(searched){
	     Dom.get('searching').style.display='none';
	     Dom.get('the_table').style.display='';
	 }
	 
    }
    
    
    
YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {




	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
				       {key:"id", label:"Id",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"name", label:"<?php echo _('Name')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"location", label:"<?php echo _('Location')?>",<?php echo($_SESSION['state']['customers']['list']['view']=='general'?'':'hidden:true,')?> width:230,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"last_order", label:"<?php echo _('Last Order')?>",<?php echo($_SESSION['state']['customers']['list']['view']=='general'?'':'hidden:true,')?>width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"orders", label:"<?php echo _('Orders')?>",<?php echo($_SESSION['state']['customers']['list']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"email", label:"<?php echo _('Email')?>",<?php echo($_SESSION['state']['customers']['list']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"tel", label:"<?php echo _('Telephone')?>",<?php echo($_SESSION['state']['customers']['list']['view']=='contact'?'':'hidden:true,')?>sortable:false,className:"aright"}

					 ];
	    //?tipo=customers&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_contacts.php?tipo=customers_advanced_search");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    
	    this.dataSource0.responseSchema = {
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
			 "id"
			 ,"name"
			,'location'
			 ,'orders'
			 ,'last_order'
			 ,'super_total','email','tel'
			 //,'flast_order'
			 //,'super_total'
			 //,{key:"families",parser:YAHOO.util.DataSource.parseNumber},
			 //	    {key:"active",parser:YAHOO.util.DataSource.parseNumber}
			 ]};
	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;


	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customers']['list']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customers']['list']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customers']['list']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

	    
	    this.table0.subscribe("dataReturnEvent", data_returned);  


	    this.table0.filter={key:'<?php echo$_SESSION['state']['customers']['list']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['list']['f_value']?>'};

	
	};
    });



var submit_search = function(e){


    //chack woth radio button is cheked

searched=true;

    var geo_base='all';
    if(Dom.get('geo_group_home').checked)
	geo_base='home';
    else if (Dom.get('geo_group_nohome').checked)
	geo_base='nohome';

    var data={ 
	product_ordered1:Dom.get('product_ordered1').value,
	//	product_ordered2: Dom.get('product_ordered2').value,
	product_not_ordered1: Dom.get('product_not_ordered1').value,
	//	product_not_ordered2: Dom.get('product_not_ordered2').value,
	product_not_received1: Dom.get('product_not_received1').value,
	//	product_not_received2: Dom.get('product_not_received2').value,
	from1:Dom.get('v_calpop1').value,
	//	from2:Dom.get('v_calpop3').value,
	to1:Dom.get('v_calpop2').value,
	//	to2:Dom.get('v_calpop4').value,
	geo_base:geo_base,
	mail:Dom.get('with_email').checked,
	tel:Dom.get('with_tel').checked
    }

    var jsonStr = YAHOO.lang.JSON.stringify(data);

    var table=tables.table0;
    var datasource=tables.dataSource0;

    var request='&sf=0&where=' +jsonStr;
    

    Dom.get('the_table').style.display='none';
    Dom.get('searching').style.display='';
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);     

}

var change_view=function(e){
      var tipo=this.id;
      var table=tables['table0'];
      old_view=table.view;
      
      if(tipo=='general'){
	  table.hideColumn('email');
	  table.hideColumn('tel');
	  table.showColumn('location');
	  table.showColumn('last_order');
	  table.showColumn('orders');

	  Dom.get('contact').className='';
	  Dom.get('general').className='selected';
      }else{
	  table.showColumn('email');
	  table.showColumn('tel');
	  table.hideColumn('location');
	  table.hideColumn('last_order');
	  table.hideColumn('orders');
	  Dom.get('contact').className='selected';
	  Dom.get('general').className='';
      }

 }


var submit_search_on_enter=function(e,tipo){
     var key;     
     if(window.event)
          key = window.event.keyCode; //IE
     else
          key = e.which; //firefox     

     if (key == 13)
	 submit_search(e,tipo);
};


function init(){

YAHOO.util.Event.addListener('submit_search', "click",submit_search);
YAHOO.util.Event.addListener(['product_ordered1'], "keydown",submit_search_on_enter);

var ids=['general','contact'];
YAHOO.util.Event.addListener(ids, "click",change_view);

cal2 = new YAHOO.widget.Calendar("cal2","cal2Container", { title:"<?php echo _('Choose a date')?>:", close:true } );

 cal2.update=updateCal;

 cal2.id='2';
 cal2.render();
 cal2.update();
 cal2.selectEvent.subscribe(handleSelect, cal2, true); 

 cal1 = new YAHOO.widget.Calendar("cal1","cal1Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
 cal1.update=updateCal;
 cal1.id='1';
 cal1.render();
 cal1.update();
 cal1.selectEvent.subscribe(handleSelect, cal1, true); 



//cal2.cfg.setProperty("iframe", true);
//cal2.cfg.setProperty("zIndex", 10);



YAHOO.util.Event.addListener("calpop1", "click", cal1.show, cal1, true);
YAHOO.util.Event.addListener("calpop2", "click", cal2.show, cal2, true);


}

YAHOO.util.Event.onDOMReady(init);


YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });




