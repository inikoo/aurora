var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

function insert_data(){

 //  return;

   var ar_file='ar_import_csv.php';
    var request=ar_file+'?tipo=insert_data';

    alert(request);


    YAHOO.util.Connect.asyncRequest('POST',request ,{});
}



function read_results(){
    var request='ar_import_csv.php?tipo=import_customer_csv_status';



    //alert(request);
 YAHOO.util.Connect.asyncRequest('POST',request , {


success:function(o) {
//alert(o.responseText)
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.state==200) {
                Dom.get('records_todo').innerHTML=r.data.todo.number;
                Dom.get('records_imported').innerHTML=r.data.done.number;
                Dom.get('records_error').innerHTML=r.data.error.number;
                Dom.get('records_ignored').innerHTML=r.data.ignored.number;

                Dom.get('records_todo_comments').innerHTML=r.data.todo.comments;
                Dom.get('records_imported_comments').innerHTML=r.data.done.comments;
                Dom.get('records_error_comments').innerHTML=r.data.error.comments;
                Dom.get('records_ignored_comments').innerHTML=r.data.ignored.comments;
                if(r.data.todo.number!=0){

                setTimeout("read_results()",100);
                }
            } else {
                //Dom.get('message_error').innerHTML=r.msg;
            }
        }

    });


}

function init(){
 init_search(Dom.get('search_type').value);
//read_results();
//insert_data();
}

YAHOO.util.Event.onDOMReady(init);
