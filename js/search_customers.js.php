<?include_once('../common.php');?>


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {




	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
				       {key:"id", label:"<?=$customers_ids[0]?>",width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"name", label:"<?=_('Name')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"location", label:"<?=_('Location')?>",<?=($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?> width:230,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"last_order", label:"<?=_('Last Order')?>",<?=($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"orders", label:"<?=_('Orders')?>",<?=($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"email", label:"<?=_('Email')?>",<?=($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"tel", label:"<?=_('Telephone')?>",<?=($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:false,className:"aright"}

					 ];
	    //?tipo=customers&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_contacts.php?tipo=customers_advanced_search");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
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
								     // sortedBy: {key:"<?=$_SESSION['tables']['customers_list'][0]?>", dir:"<?=$_SESSION['tables']['customers_list'][1]?>"},
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?=$_SESSION['state']['customers']['table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?=$_SESSION['state']['customers']['table']['order']?>",
									 dir: "<?=$_SESSION['state']['customers']['table']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    


	    this.table0.filter={key:'<?=$_SESSION['state']['customers']['table']['f_field']?>',value:'<?=$_SESSION['state']['customers']['table']['f_value']?>'};

	    //   YAHOO.util.Event.addListener('f_input', "keyup",myFilterChangeValue,{table:this.table0,datasource:this.dataSource})
			 
	    
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



YAHOO.util.Event.onContentReady("filtermenu", function () {
	 var oMenu = new YAHOO.widget.Menu("filtermenu", { context:["filter_name0","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name0", "click", oMenu.show, null, oMenu);
    });


YAHOO.util.Event.onContentReady("rppmenu", function () {
	 var oMenu = new YAHOO.widget.Menu("rppmenu", { context:["filter_name0","tr", "bl"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("paginator_info0", "click", oMenu.show, null, oMenu);
    });






var submit_advanced_search = function(e){


    //chack woth radio button is cheked



    var geo_base='all';
    if(Dom.get('geo_group_home').checked)
	geo_base='home';
    else if (Dom.get('geo_group_nohome').checked)
	geo_base='nohome';

    var data={ 
	product_ordered1:Dom.get('product_ordered1').value,
	product_ordered2: Dom.get('product_ordered2').value,
	product_not_ordered1: Dom.get('product_not_ordered1').value,
	product_not_ordered2: Dom.get('product_not_ordered2').value,
	product_not_received1: Dom.get('product_not_received1').value,
	product_not_received2: Dom.get('product_not_received2').value,
	from1:Dom.get('v_calpop1').value,
	from2:Dom.get('v_calpop3').value,
	to1:Dom.get('v_calpop2').value,
	to2:Dom.get('v_calpop4').value,
	geo_base:geo_base,
	mail:Dom.get('with_email').checked,
	tel:Dom.get('with_tel').checked
    }

    var jsonStr = YAHOO.lang.JSON.stringify(data);

    var table=tables.table0;
    var datasource=tables.dataSource0;

    var request='&sf=0&where=' +jsonStr;
    
    alert(request);
    Dom.get('the_table').style.display='';
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);     
}

YAHOO.util.Event.addListener('submit_advanced_search', "click",submit_advanced_search);

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


var ids=['general','contact'];
YAHOO.util.Event.addListener(ids, "click",change_view);


 }

YAHOO.util.Event.onDOMReady(init);
