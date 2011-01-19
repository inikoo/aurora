var Dom   = YAHOO.util.Dom;
var Event =YAHOO.util.Event;


function send() {

    value={
summary:
        Dom.get('summary').value,
description:
        Dom.get('description').value,
metadata:
        Dom.get('metadata').value,
email:
        Dom.get('email').value,

    };


    json_value = YAHOO.lang.JSON.stringify(value);
    var request='ar_send_email.php?tipo=report_issue&values=' + json_value;
    YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.state==200) {
                Dom.get('send_from').style.display='none';
                Dom.get('issue_send').style.display='';
            } else {
                Dom.get('message_error').innerHTML=r.msg;
            }
        }

    });

}

function init() {
    Event.addListener( "send", "click",send);
}
Event.onDOMReady(init);
