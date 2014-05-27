var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;



function change_main_address(address_key,options) {

    var request='ar_edit_contacts.php?tipo=set_main_address&value=' +address_key+'&key='+options.type+'&subject='+options.Subject+'&subject_key='+options.subject_key;
	//alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
             //alert(o.responseText);
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.state==200) {
		window.location.reload();
            } else {
                alert(r.msg);
            }
        }
    });


}

var delete_address=function (address_key,options) {
    var request='ar_edit_contacts.php?tipo=delete_address&value=' +address_key+'&key='+options.type+'&subject='+options.Subject+'&subject_key='+options.subject_key;

    YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
//           alert(o.responseText);
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.action=='deleted') {
		window.location.reload();
            } else if (r.action=='error') {
                alert(r.msg);
            }
        }
    });



};


function init(){


}
Event.onDOMReady(init);