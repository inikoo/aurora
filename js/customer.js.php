<?include_once('../common.php');?>
    
    YAHOO.util.Event.addListener(window, "load", function() {
	    tables = new function() {
		    
		    var tableid=0; // Change if you have more the 1 table
		    var tableDivEL="table"+tableid;  
		    
		    var ColumnDefs = [
				      {key:"date_index", label:"<?=_('Date')?>",className:"aright",width:150,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					   ,{key:"time", label:"<?=_('Time')?>",className:"aleft",width:50}
					   ,{key:"op", label:"<?=_('Type')?>", className:"aleft",width:70,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"description", label:"<?=_('Description')?>",className:"aleft",width:500}
					   ];
		
		    this.dataSource0  = new YAHOO.util.DataSource("ar_contacts.php?tipo=customer_history&tid="+tableid);
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
		fields: ["description","date_index","op","time" ]};
		    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?=$_SESSION['state']['customer']['table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?=$_SESSION['state']['customer']['table']['order']?>",
									 dir: "<?=$_SESSION['state']['customer']['table']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		    this.table0.filter={key:'<?=$_SESSION['state']['customer']['table']['f_field']?>',value:'<?=$_SESSION['state']['customer']['table']['f_value']?>'};

	    //   YAHOO.util.Event.addListener('f_input', "keyup",myFilterChangeValue,{table:this.table0,datasource:this.dataSource})
			 
	    
	    //	    var Dom   = YAHOO.util.Dom;
	    //alert(Dom.get('f_input'));

	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)
	
	};
    });


function init(){

//     var shortcut_next = new YAHOO.util.KeyListener(document, {keys:89 },  { fn:key_press });
//         alert("cac");
//     shortcut_next.enable();
//     var key_press=function(type, args, obj){
// 	alert("caca");
// 	//	window.location=Dom.get("next").href;
//     }

//     document.documentElement.focus();
//     document.body.focus();

    var alt_shortcuts = function(type, args, obj) {
	if(args[0]==78){
	    window.location=Dom.get("next").href;
	}else if(args[0]==80){
	    window.location=Dom.get("next").href;
	}

    }

    kpl1 = new YAHOO.util.KeyListener(document, { alt:true ,keys:[78,80] }, { fn:alt_shortcuts } );
    kpl1.enable();




}

YAHOO.util.Event.onDOMReady(init);
