<?php 
include_once('common.php');
?>
var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

var dialog_map;
var dialog_map_select;

function cancel_import() {
    var ar_file = 'ar_import.php';
    var request = ar_file + "?tipo=cancel_import&imported_records_key=" + Dom.get('imported_records_key').value;
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
           // alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
            
                if (Dom.get('subject').value == 'customers' && Dom.get('reference').value == 'subject') {
                    window.location.href = "customers.php?store=" + Dom.get('parent_key').value;
                } else {
                    window.location.href = "import.php?subject=" + Dom.get('subject').value + "&parent=" + Dom.get('parent').value + "&parent_key=" + Dom.get('parent_key').value;
                }
            } else {
                
            }
        }
    });

}


function get_record_data(index) {
    var ar_file = 'ar_import.php';
    var request = ar_file + "?tipo=get_record_data&index=" + index + "&imported_records_key=" + Dom.get('imported_records_key').value;
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
       // alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                Dom.get('call_table').innerHTML = r.result
                                Dom.get('index').value = r.index

            } else {
                alert(r.msg);
            }
        }
    });

}





function ignore_record(index) {
    var ar_file = 'ar_import.php';
    var request = ar_file + '?tipo=ignore_record&index=' + index+ "&imported_records_key=" + Dom.get('imported_records_key').value;
   
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.setStyle(['ignore_record_label', 'unignore'], 'display', '');
                Dom.setStyle(['ignore'], 'display', 'none');
            } else {
                alert(r.msg);
            }
        }
    });
}

function read_record(index) {
    var ar_file = 'ar_import.php';
    var request = ar_file + '?tipo=read_record&index=' + index+ "&imported_records_key=" + Dom.get('imported_records_key').value;
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.setStyle(['ignore_record_label', 'unignore'], 'display', 'none');
                Dom.setStyle(['ignore'], 'display', '');
            } else {
                alert(r.msg);
            }
        }
    });
}

function option_changed(key, option_key) {


    var ar_file = 'ar_import.php';
    var request = ar_file + '?tipo=change_option&key=' + key + '&option_key=' + option_key+ "&imported_records_key=" + Dom.get('imported_records_key').value;
    YAHOO.util.Connect.asyncRequest('POST', request, {});
}

function insert_data() {
    window.location.href = 'imported_records.php?id=' + Dom.get('imported_records_key').value;

}

function show_save_map() {
 
 
 
 
	region1 = Dom.getRegion('new_map'); 
	var pos =[region1.right,region1.top]
	Dom.setXY('dialog_map', pos);

 dialog_map.show();

   
    Dom.setStyle('map_msg', 'display', 'none');
    Dom.setStyle('map_form_table', 'display', '');
    Dom.setStyle('map_form_text', 'color', '#000');
    Dom.setStyle('map_error_used_map_name', 'display', 'none');
    Dom.setStyle('map_form_text_tr', 'display', '');

    Dom.get('map_name').value = '';

  Dom.get('map_name').focus()

}

function browse_maps() {
    dialog_map_select.show();
    //alert('browser maps');
/*
	var ar_file='ar_import.php';
    var request=ar_file+"?tipo=browse_maps&scope="+Dom.get('scope').value+"&parent_key="+Dom.get('parent_key').value;
	alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	
	  success:function(o) {
	//alert(o.responseText)
	  
	//Dom.get('call_table').innerHTML=o.responseText;
		
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		  
		  Dom.get('maps').innerHTML=r.map_data
		}else{
		    //alert(r.msg);
		}
	    }
	});
	*/
}

function save_map(e,overwrite) {
    //alert('save');
    var ar_file = 'ar_import.php';
    var request = ar_file + "?tipo=save_map&subject=" + Dom.get('subject').value +"&parent=" + Dom.get('parent').value + "&parent_key=" + Dom.get('parent_key').value +  "&name=" + Dom.get('map_name').value+"&imported_records_key=" + Dom.get('imported_records_key').value+"&overwrite=" + overwrite;
   
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                Dom.setStyle('map_form_table', 'display', 'none');
                Dom.setStyle('map_msg', 'display', '');


                Dom.get('map_msg').innerHTML = r.msg;

                setTimeout("dialog_map.hide()", 750);
                var table = tables['table5'];
                var datasource = tables['dataSource5'];
                var request = '';
                datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

            } else {

                if (r.type == 'no_name') {
                    Dom.setStyle('map_form_text', 'color', 'red');

                } else if (r.type == 'used_name') {
                    Dom.setStyle('map_error_used_map_name', 'display', '');
                    Dom.setStyle('map_form_text_tr', 'display', 'none');

                } else {
                    alert(r.msg)

                }

            }
        }
    });

}

function select_map(oArgs) {


    var target = oArgs.target,
        column = this.getColumn(target),
        record = this.getRecord(target);

    var recordIndex = this.getRecordIndex(record);



    ar_file = 'ar_import.php';
    switch (column.action) {
    case ('select'):

        var ar_file = 'ar_import.php';
        var request = ar_file + "?tipo=use_map_options&map_key=" + record.getData('map_key')+"&imported_records_key=" + Dom.get('imported_records_key').value;
       
        YAHOO.util.Connect.asyncRequest('POST', request, {
            success: function(o) {
            //	alert(o.responseText)
                //Dom.get('call_table').innerHTML=o.responseText;
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {

                get_record_data(Dom.get('index').value)


                } else {
                    //alert(r.msg);
                }
            }


        });

/*
    var product_ordered_or=Dom.get('product_ordered_or').value;
    if(product_ordered_or!=''){product_ordered_or=product_ordered_or+','}
    product_ordered_or=product_ordered_or+'d('+tables.table5.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '')+')';
    Dom.get('product_ordered_or').value=product_ordered_or;
	*/
        dialog_map_select.hide();
        hide_filter(true, 5)

        break;
    case ('delete'):

        var request = ar_file + "?tipo=delete_map&map_key=" + record.getData('map_key');
        YAHOO.util.Connect.asyncRequest('POST', request, {
            success: function(o) {
                //alert(o.responseText)
                //Dom.get('call_table').innerHTML=o.responseText;
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {
                    var table = tables['table5'];
                    var datasource = tables['dataSource5'];
                    var request = '';
                    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
                } else {
                    alert(r.msg);
                }
            }


        });


        break;
    }




}

YAHOO.util.Event.addListener(window, "load", function() {

    tables = new function() {


	var parent_key=Dom.get('parent_key').value;

	var tableid=5; 
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
			
                    {key:"name", label:"<?php echo _('Name')?>",width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},action:'select'}
                   ,{key:"map", label:"<?php echo _('Map')?>",width:260,action:'select'}
                    ,{key:"delete", label:"",width:16,className:"aleft",action:'delete',object:'csv_import_map'}
				];
			       
	   request="ar_import.php?tipo=list_maps&subject="+Dom.get('subject').value+"&parent="+Dom.get('parent').value+"&parent_key="+Dom.get('parent_key').value+"&tableid="+tableid+"&nr=20&sf=0"
		
		//alert(request);
		this.dataSource5 = new YAHOO.util.DataSource(request);
	    
		this.dataSource5.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource5.connXhrMode = "queueRequests";
	    	    this.dataSource5.table_id=tableid;

	    this.dataSource5.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "map","name","delete","map_key"
			 ]};


	    this.table5 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource5
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator5', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info5'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "name",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	  
	    this.table5.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table5.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);





 this.table5.subscribe("rowMouseoverEvent", this.table5.onEventHighlightRow);
       this.table5.subscribe("rowMouseoutEvent", this.table5.onEventUnhighlightRow);
   //   this.table5.subscribe("rowClickEvent", select_map);
           	        this.table5.subscribe("cellClickEvent", select_map);            

           this.table5.table_id=tableid;
           this.table5.subscribe("renderEvent", myrenderEvent);



	    this.table5.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table5.filter={key:'code',value:''};
	    //
// --------------------------------------Department table ends here----------------------------------------------------------


/*

	*/
	};

    });

function init() {


    init_search(Dom.get('search_type').value);
    Event.addListener('new_map', "click", show_save_map);
    Event.addListener('browse_maps', "click", browse_maps);
    Event.addListener('save_map', "click", save_map,'No');
    Event.addListener('overwrite_map', "click", save_map,'Yes');
    Event.addListener('cancel_import', "click", cancel_import);





    dialog_map = new YAHOO.widget.Dialog("dialog_map", {
      
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_map.render();

    dialog_map_select = new YAHOO.widget.Dialog("dialog_map_select", {
        context: ["browse_maps", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_map_select.render();


    get_record_data(Dom.get('index').value);
    Event.addListener(['insert_data'], "click", insert_data);


}

YAHOO.util.Event.onDOMReady(init);






