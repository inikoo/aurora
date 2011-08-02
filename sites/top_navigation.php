<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/yahoo-dom-event/yahoo-dom-event.js"></script>

<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/dragdrop/dragdrop-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/element/element-min.js"></script>

<script type="text/javascript">
var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;

function show_login_dialog(){
Dom.setStyle('show_login_dialog','display','none');
Dom.setStyle('dialog_login','display','');
}
function hide_login_dialog(){
Dom.setStyle('show_login_dialog','display','');
Dom.setStyle('dialog_login','display','none');
Dom.get('login').value='';
Dom.get('password').value='';
function submit_login(){
if(Dom.get('password').value==''){

return;
}



}
}
Event.addListener("show_login_dialog", "click", show_login_dialog);
Event.addListener("hide_login_dialog", "click", hide_login_dialog);
Event.addListener("submit_login", "click", submit_login);

</script>
<div style="height:22px;z-index:1000;width:855px;background:black;color:white;text-align:right;padding:2px 10px 1px 10px">
<button id="show_login_dialog" style="cursor:pointer">Log In</button>
</div>



<div id="dialog_login" style="display:none;font-size:5px;position:relative;left:575px;background:black;color:white;width:300px">
<table style="font-size:12px;color:white;margin:10px 10px 0px 10px">
<tr><td>Email: </td><td><input style="width:200px" id="login"></td></tr>
<tr><td>Password: </td><td><input style="width:200px" id="password"></td></tr>
<tr style="height:40px"><td></td><td style="text-align:right"><button id="submit_login" style="cursor:pointer">Log In</button></td></tr>

<tr style=""><td></td><td style="text-align:right">Forgot your password? <span>Click Here</span></td></tr>
<tr style=""><td></td><td style="text-align:right">First visit? <span>Register Here</span></td></tr>
<tr style="height:40px"><td></td><td style="text-align:right"><button id="hide_login_dialog" style="cursor:pointer">Close</button></td></tr>

</table>
</div>
