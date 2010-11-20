

//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
var Dom   = YAHOO.util.Dom;



var myBuildUrl = function(datatable,record) {
    var url = '';
    var cols = datatable.getColumnSet().keys;
    for (var i = 0; i < cols.length; i++) {
        if (cols[i].isPrimaryKey) {
            url += '&' + cols[i].key + '=' + escape(record.getData(cols[i].key));
        }else if (cols[i].isTypeKey) {
            url += '&' + cols[i].key + '=' + escape(record.getData(cols[i].key));
        }
	

    }
    return url;



this.dataSource0 = new YAHOO.util.DataSource("ar_edit_common.php?tipo=common");//-----------------------------------for modify common.php
};





