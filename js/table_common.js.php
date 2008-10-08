YAHOO.widget.DataTable.MSG_EMPTY='<?=_('No records found')?>.';
YAHOO.widget.DataTable.MSG_ERROR='<?=_('Data error')?>.';
YAHOO.widget.DataTable.MSG_LOADING='<?=_('Loading data')?>...';




var myRequestBuilder = function(oState, oSelf) {
    // Get states or use defaults


    oState = oState || {pagination:null, sortedBy:null};

    var sort = (oState.sortedBy) ? oState.sortedBy.key : "myDefaultColumnKey";

    var dir = (oState.sortedBy && oState.sortedBy.dir === YAHOO.widget.DataTable.CLASS_ASC) ? "" : "desc";

   var startIndex = (oState.pagination) ? oState.pagination.recordOffset : 0;
    var results = (oState.pagination) ? oState.pagination.rowsPerPage : 25;

    // Build custom request
    var request= "&o=" + sort +
    "&od=" + dir +
    "&sf=" + startIndex +
    "&nr=" + results;
    return request;
};


