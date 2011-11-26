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

type:
        Dom.get('type').value,
    };


    json_value = YAHOO.lang.JSON.stringify(value);
    var request='ar_send_email.php?tipo=report_issue&values=' + json_value;
 //alert(request)
 Dom.setStyle(['send','cancel'],'display','none');
    Dom.setStyle(['sending'],'display','');

 YAHOO.util.Connect.asyncRequest('POST',request , {
  
  
success:function(o) {
  //alert(o.responseText)
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.state==200) {
                Dom.get('send_from').style.display='none';
                Dom.get('issue_send').style.display='';
                  
  Dom.setStyle(['send','cancel'],'display','');
    Dom.setStyle(['sending'],'display','none');

            } else {
                Dom.get('message_error').innerHTML=r.msg;
				    Dom.setStyle(['sending'],'display','none');
					Dom.get('send_from').style.display='none';
					Dom.setStyle(['send','cancel'],'display','');
					Dom.get('issue_send').style.display='';
            }
        }

    });

}

function init() {
    Event.addListener( "send", "click",send);
}
Event.onDOMReady(init);
