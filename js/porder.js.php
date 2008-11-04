<?include_once('../common.php');?>
    
    
    var value_changed=function(o){

	if(isNaN(o.value)){
	    o.style.background='#fff889';
	}else{
	    var request='ar_assets.php?tipo=po_add_item&p2s_id='+escape(o.getAttribute('pid'))+'&qty='+escape(o.value);
	    alert(request)
	    YAHOO.util.Connect.asyncRequest('POST',request ,{
		    success:function(o) {
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if (r.state == 200) {
			    
			}
		    }
		});    


	}	

    }



YAHOO.util.Event.addListener(window, "load", function() {
	tables = new function() {
		this.productLink=  function(el, oRecord, oColumn, oData) {
		    var url="product.php?id="+oRecord.getData("id");
		    el.innerHTML = oData.link(url);
		};
		this.familyLink=  function(el, oRecord, oColumn, oData) {
		    var url="family.php?id="+oRecord.getData("group_id");
		    el.innerHTML = oData.link(url);
		};


		 var tableid=0;
		    var tableDivEL="table"+tableid;
		var ColumnDefs = [
				    {key:"code", label:"<?=_('Code')?>", width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    //				    ,{key:"fam", label:"<?=_('Family')?>",width:80,formatter:this.familyLink, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"description", label:"<?=_('Description')?>",width:250, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"stock", label:"<?=_('Stock (O,U)')?>",width:90,className:"aright"}
				    ,{key:"stock_time", label:"<?=_('Stock Time')?>",width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    //,{key:"sup_code", label:"<?=_('S Code')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"qty", label:"<?=_('Qty (U)')?>",width:100,className:"aright"}
				    ,{key:"price_unit", label:"<?=_('Price (U)')?>",width:70,className:"aright"}
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
				 "id","family_id","fam","code","description","stock","price_unit","price_outer","delete","p2s_id","sup_code","group_id","qty"
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
 var Dom   = YAHOO.util.Dom;


 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
 oAutoComp.minQueryLength = 0; 




 }

YAHOO.util.Event.onDOMReady(init);
