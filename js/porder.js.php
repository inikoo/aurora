<?include_once('../common.php');
?>
     var Dom   = YAHOO.util.Dom;
var po_id='<?=$_SESSION['state']['po']['id']?>';

var submit_order_save=function(o){
    var date=Dom.get('v_calpop1').value;
    var time=Dom.get('v_time').value;
    var edate=Dom.get('v_calpop2').value;

    var request='ar_assets.php?tipo=order_submit&tipo_order=po&date='+escape(date)+'&time='+escape(time)+'&edate='+escape(edate)+'&order_id='+escape(po_id);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		}
	    }
	});    
}
var receive_order_save=function(o){
    var date=Dom.get('v_calpop3').value;
    var time=Dom.get('v_time3').value;

    var request='ar_assets.php?tipo=order_received&tipo_order=po&date='+escape(date)+'&time='+escape(time)+'&order_id='+escape(po_id);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
		//	alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		}
	    }
	});    
}
var check_order_save=function(o){
    var date=Dom.get('v_calpop4').value;
    var time=Dom.get('v_time4').value;

    var request='ar_assets.php?tipo=order_checked&tipo_order=po&date='+escape(date)+'&time='+escape(time)+'&order_id='+escape(po_id);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		}
	    }
	});    
}
var consolidate_order_save=function(o){
    var date=Dom.get('v_calpop5').value;
    var time=Dom.get('v_time5').value;

    var request='ar_assets.php?tipo=order_consolidated&tipo_order=po&date='+escape(date)+'&time='+escape(time)+'&order_id='+escape(po_id);

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		}
	    }
	});    
}

var cancel_order_save=function(o){
    var date=Dom.get('v_calpop6').value;
    var time=Dom.get('v_time6').value;

    var request='ar_assets.php?tipo=order_cancel&tipo_order=po&date='+escape(date)+'&time='+escape(time)+'&order_id='+escape(po_id);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {

		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		}
	    }
	});    
}




var submit_order=function(o){
    	Dom.get('submit_dialog').style.display='';
}
var receive_order=function(o){
    	Dom.get('receive_dialog').style.display='';
}
var check_order=function(o){
    	Dom.get('check_dialog').style.display='';
}
var consolidate_order=function(o){
    	Dom.get('consolidate_dialog').style.display='';
}
var cancel_order=function(o){
    	Dom.get('cancel_dialog').style.display='';
}


var swap_show_all_products=function(o,show_all){
    o.className='selected but';
    if(show_all){
	Dom.get('clean_table_filter0').style.visibility='visible';
	Dom.get('clean_table_controls0').style.visibility='visible';
	Dom.get('table_po_products').className='but ';
    }else{
	Dom.get('table_all_products').className='but ';
	Dom.get('clean_table_filter0').style.visibility='hidden';
	Dom.get('clean_table_controls0').style.visibility='hidden';

    }tableid=0;
    var table=tables['table'+tableid];
    var datasource=tables['dataSource'+tableid];
    var request='&show_all=' +show_all;

    

    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    
    
};
    


var value_changed=function(o){

	if(isNaN(o.value)){
	    o.style.background='#fff889';
	}else{

	     var request='ar_assets.php?tipo=order_add_item&tipo_order=po&product_id='+escape(o.getAttribute('pid'))+'&qty='+escape(o.value)+'&order_id='+escape(po_id);
	    YAHOO.util.Connect.asyncRequest('POST',request ,{
		    
		    success:function(o) {
			//	alert(o.responseText)
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if (r.state == 200) {
			    Dom.get('distinct_products').innerHTML=r.data.items;
			    Dom.get('goods').innerHTML=r.data.money.goods;
			    Dom.get('vat').innerHTML=r.data.money.vat;
			    Dom.get('total').innerHTML=r.data.money.total;
			    Dom.get('oqty'+r.item_data.id).innerHTML=r.item_data.outers;
			    Dom.get('ep'+r.item_data.id).innerHTML=r.item_data.est_price;
			}
		    }
		});    


	}	

    }



YAHOO.util.Event.addListener(window, "load", function() {
	tables = new function() {
		
		var tableid=0;
		var tableDivEL="table"+tableid;
		var ColumnDefs = [
				  {key:"code", label:"<?=_('Code')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  //				    ,{key:"fam", label:"<?=_('Family')?>",width:80,formatter:this.familyLink, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  ,{key:"description", label:"<?=_('Description')?>",width:270, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  ,{key:"stock", label:"<?=_('Stock O(U)')?>",width:90,className:"aright"}
				  ,{key:"stock_time", label:"<?=_('Stock Time')?>",width:75,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  //,{key:"sup_code", label:"<?=_('S Code')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  
				  ,{key:"expected_qty", label:"<?=_('Qty O[U]')?>",width:100,className:"aright"}
				  ,{key:"price_unit", label:"<?=_('Price (U)')?>",width:65,className:"aright"}
				  ,{key:"expected_price", label:"<?=_('E Cost')?>",width:70,className:"aright"}
				  ];
		
		this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=withsupplier_po&tableid="+tableid);
		
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
			    totalRecords: "resultset.total_records"
			},
			
			fields: [
				 "id","family_id","fam","code","description","stock","price_unit","price_outer","delete","p2s_id","sup_code","group_id","qty","expected_price","price","expected_qty"
				 ]};
	    
		    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource0, {
								 //draggableColumns:true,
								 renderLoopSize: 50,generateRequest : myRequestBuilder
								 ,paginator : new YAHOO.widget.Paginator({
									 rowsPerPage:<?=$_SESSION['state']['supplier']['products']['nr']?>,containers : 'paginator', 
									 pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								 
								 ,sortedBy : {
								     key: "<?=$_SESSION['state']['supplier']['products']['order']?>",
								     dir: "<?=$_SESSION['state']['supplier']['products']['order_dir']?>"
								 }
								 ,dynamicData : true
								 
							     }
							     );
		    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
		    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
		this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table0.filter={key:'<?=$_SESSION['state']['supplier']['products']['f_field']?>',value:'<?=$_SESSION['state']['supplier']['products']['f_value']?>'};
	    }
	    }
    );




 function init(){
     var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS.queryMatchContains = true;
     var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
     oAutoComp.minQueryLength = 0; 

     cal2 = new YAHOO.widget.Calendar("cal2","cal2Container", { title:"<?=_('Choose a date')?>:", close:true } );
     cal2.update=updateCal;
     cal2.id=2;
     cal2.render();
     cal2.update();
     cal2.selectEvent.subscribe(handleSelect, cal2, true); 

     cal1 = new YAHOO.widget.Calendar("cal1","cal1Container", { title:"<?=_('Choose a date')?>:", close:true } );
     cal1.update=updateCal;
     cal1.id=1;
     cal1.render();
     cal1.update();
     cal1.selectEvent.subscribe(handleSelect, cal1, true); 
     cal3 = new YAHOO.widget.Calendar("cal3","cal1Container", { title:"<?=_('Choose a date')?>:", close:true } );
     cal3.update=updateCal;
     cal3.id=3;
     cal3.render();
     cal3.update();
     cal3.selectEvent.subscribe(handleSelect, cal3, true); 
     
     

     YAHOO.util.Event.addListener("calpop1", "click", cal1.show, cal1, true);
     YAHOO.util.Event.addListener("calpop2", "click", cal2.show, cal2, true);
     YAHOO.util.Event.addListener("calpop3", "click", cal3.show, cal3, true);

 }

YAHOO.util.Event.onDOMReady(init);
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



