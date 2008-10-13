

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
var myhandleDataReturnPayload= function(oRequest, oResponse, oPayload) {
    oPayload = oPayload ||  {
	totalRecords:0,
	pagination:{
	    rowsPerPage:parseInt(oResponse.meta.rowsPerPage)
	},
	sortedBy:
	{key:oResponse.meta.sort_key,
	 dir:oResponse.meta.sort_dir},
	SelectedRows:null,
	SelectedCells:null} ;

    oPayload.filter_msg=oResponse.meta.filter_msg;

    //  alert(oResponse.meta.rowsPerPage)

    YAHOO.util.Dom.get('filter_msg'+oResponse.meta.tableid).innerHTML=oPayload.filter_msg
    //    oPayload.sortedBy.key=oResponse.meta.sort_key;
    //oPayload.sortedBy.dir=oResponse.meta.sort_dir;
    oPayload.totalRecords = parseInt(oResponse.meta.totalRecords);


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


var mygetTerms =function (query) {
    var Dom   = YAHOO.util.Dom;
    var table=tables.table0;
    var datasource=tables.dataSource0;
    table.filter.value=Dom.get('f_input0').value;
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


    



// var myFilterChangeValue = function(e,o){
    
//     if(o.table.filter.value!=this.value ){
// 	var current_time=new Date().getTime();
// 	if(current_time-o.table.filter.lastRequest>200){
	    
// 	    o.table.filter.value=this.value;
// 	    var oCallback = {
// 		success : o.table.onDataReturnInitializeTable,
// 		failure : o.table.onDataReturnInitializeTable,
// 		scope : o.table
// 	    };
// 	    var request="&f_field="+o.table.filter.key+"&f_value="+o.table.filter.value
// 		o.datasource.sendRequest(request, oCallback);
// 	    o.table.filter.lastRequest=current_time;
// 	}

//     }
//     return true
// }