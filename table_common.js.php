<?php 

include_once('set_locales.php');
?>
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW


function show_filter(e,table_id){
Dom.get('clean_table_filter_show'+table_id).style.display='none';
Dom.get('clean_table_filter'+table_id).style.display='';
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=stores-table-f_show&value=1',{} );
}

function hide_filter(e,table_id){
Dom.get('clean_table_filter_show'+table_id).style.display='';
Dom.get('clean_table_filter'+table_id).style.display='none';
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=stores-table-f_show&value=0',{} );
remove_filter(table_id)
}

var remove_filter= function (tableid){
    var Dom   = YAHOO.util.Dom;
    Dom.get('f_input'+tableid).value='';
    var table=tables['table'+tableid]

    var datasource=tables['dataSource'+tableid];
    table.filter.value=Dom.get('f_input'+tableid).value;
    var request='&f_field=' +table.filter.key + '&f_value=' + table.filter.value;
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
}




var myRowsPerPageDropdown = function(){return true};
var mydoBeforeSortColumn = function(){return true};
var mydoBeforePaginatorChange = function(e){return true};


var mydoBeforeLoadData = function(oRequest, oResponse, oPayload) {
     //  alert(oResponse.meta.RecordOffset)
     if(oPayload!=undefined){
        oPayload.pagination = {
            rowsPerPage: parseInt(oResponse.meta.rowsPerPage)||5,
            recordOffset: parseInt(oResponse.meta.RecordOffset)||0
        };
       }
        return true;
    };




var myhandleDataReturnPayload= function(oRequest, oResponse, oPayload) {
    oPayload = oPayload ||  {
	totalRecords:0,
	pagination:{
	    //rowsPerPage:3
	    //rowsPerPage:parseInt(oResponse.meta.rowsPerPage),
	    //recordOffset: 12
	},
	sortedBy:
	{key:oResponse.meta.sort_key,
	 dir:oResponse.meta.sort_dir},
	SelectedRows:null,
	SelectedCells:null} ;

    oPayload.filter_msg=oResponse.meta.filter_msg;
  // oPayload.pagination = {  rowsPerPage:parseInt(oResponse.meta.rowsPerPage),recordOffset:0 }
   
   //alert(oResponse.meta.RecordOffset)
    if(oResponse.meta.rtext != undefined)

      YAHOO.util.Dom.get('rtext'+oResponse.meta.tableid).innerHTML=oResponse.meta.rtext;
    
    
    if(oResponse.meta.rtext_rpp != undefined){

	YAHOO.util.Dom.get('rtext_rpp'+oResponse.meta.tableid).innerHTML=oResponse.meta.rtext_rpp;
    }

    YAHOO.util.Dom.get('filter_msg'+oResponse.meta.tableid).innerHTML=oPayload.filter_msg

    oPayload.totalRecords = parseInt(oResponse.meta.totalRecords);
    
    if(oPayload.totalRecords==0){
	    var table=YAHOO.util.Dom.get('table'+oResponse.meta.tableid).getElementsByTagName("table")[0];
	    table.tHead.style.display='none';
	    table.tBodies[0].getElementsByTagName("tr")[0].getElementsByTagName("td")[0].innerHTML='';
	    if(YAHOO.util.Dom.get('filter_div'+oResponse.meta.tableid)!=null){
	        YAHOO.util.Dom.get('filter_div'+oResponse.meta.tableid).style.visibility='hidden';
	    }
    }else{
	    var table=YAHOO.util.Dom.get('table'+oResponse.meta.tableid).getElementsByTagName("table")[0];
	    table.tHead.style.display='';
	    if(YAHOO.util.Dom.get('filter_div'+oResponse.meta.tableid)!=null)
	        YAHOO.util.Dom.get('filter_div'+oResponse.meta.tableid).style.visibility='visible';
       if(oPayload.totalRecords<=oResponse.meta.rowsPerPage)
       Dom.get('paginator'+oResponse.meta.tableid).style.display='none';
      //Dom.get('yui-rec10').style.display='none';
       
    }
    //var data={code:'<?php echo _('Totals')?>'};
    //tables.table0.addRow(data);
    return oPayload;
};








var myRequestBuilder = function(oState, oSelf) {
    // Get states or use defaults

    
    oState = oState || {pagination:null, sortedBy:null};

    var sort = (oState.sortedBy) ? oState.sortedBy.key : "myDefaultColumnKey";

    var dir = (oState.sortedBy && oState.sortedBy.dir === YAHOO.widget.DataTable.CLASS_ASC) ? "" : "desc";

   var startIndex = (oState.pagination) ? oState.pagination.recordOffset : 0;
    var results = (oState.pagination) ? oState.pagination.rowsPerPage : 5;

    // Build custom request
    var request= "&o=" + sort +
    "&od=" + dir +
    "&sf=" + startIndex +
    "&nr=" + results;


    return request;
};


var myRequestBuilderwithTotals = function(oState, oSelf) {
    // Get states or use defaults

    
    oState = oState || {pagination:null, sortedBy:null};

    var sort = (oState.sortedBy) ? oState.sortedBy.key : "myDefaultColumnKey";

    var dir = (oState.sortedBy && oState.sortedBy.dir === YAHOO.widget.DataTable.CLASS_ASC) ? "" : "desc";

   var startIndex = (oState.pagination) ? oState.pagination.recordOffset : 0;
    var results = (oState.pagination) ? oState.pagination.rowsPerPage : 5;

    // Build custom request
    var request= "&o=" + sort +
    "&od=" + dir +
    "&sf=" + startIndex +
    "&nr=" + results;
//alert(request)

    return request;
};




var mygetTerms =function (query) {

    if(this.table_ids==undefined)
	var table_id=0;
    else
	var table_id=this.table_id;

    var Dom   = YAHOO.util.Dom;
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];

    table.filter.value=Dom.get('f_input'+table_id).value;
    var request='&sf=0&f_field=' +table.filter.key + '&f_value=' + table.filter.value;

    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
};

var change_filter=function (key,label,tableid){
    var Dom   = YAHOO.util.Dom;
    var table=tables['table'+tableid];
    if(table.filter.value!=''){
	var datasource=tables['dataSource'+tableid];
	table.filter.value=Dom.get('f_input'+tableid).value;
	var request='&f_field=' +table.filter.key+'&f_value=';
	datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
    }
    table.filter.value='';
    table.filter.key=key;
    Dom.get('f_input'+tableid).value='';
    Dom.get('filter_name'+tableid).innerHTML=label;
}

var change_rpp=function (rpp,tableid){
    var Dom   = YAHOO.util.Dom;
    var table=tables['table'+tableid];
    table.get('paginator').setRowsPerPage(rpp)

}


var change_rpp_with_totals=function (rpp,tableid){
    var Dom   = YAHOO.util.Dom;
    var table=tables['table'+tableid];
    table.get('paginator').setRowsPerPage(rpp+1)

}
