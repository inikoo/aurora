var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

function insert_data(){
   var ar_file='ar_import_csv.php';
    var request=ar_file+'?tipo=insert_data'; 
    YAHOO.util.Connect.asyncRequest('POST',request ,{});
}


function init(){
// insert_data();
}

YAHOO.util.Event.onDOMReady(init);

