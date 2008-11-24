<?include_once('../common.php')?>
    var Dom   = YAHOO.util.Dom; 

var id_in_table='';
var  add_user_dialog_staff;

function randPassword()
{
    var numChars = 8;
    var strChars = "23456789ABCDEFGHIJKLMN%PQRSTUVWXYZ$23456789abcdefghijkmnopqrstuvwxyz_123456789";
    var strPass = '';

    for(var i = 0; i < numChars; i++)
    {
      strPass += strChars.charAt(Math.round(Math.random()
        * strChars.length));
    }
    return strPass;
}

var select_staff=function(o){

    //    var is_in=o.getAttribute('is_in');
    id_in_table=o.getAttribute('staff_id');
    o.className='selected';
    // Dom.get("staff_list").style.display='none';
    Dom.get("staff_handle").innerHTML=o.innerHTML;
    handle=Dom.get("staff_v_handle").value=o.innerHTML;
    Dom.get('staff_handle_container').style.display='';
    Dom.get('staff_choose_method').style.display='';

}


var auto_pwd=function(){
    Dom.get("staff_user_definded_dialog").style.display='none';
    Dom.get("staff_auto_dialog").style.display='';
    var pwd=randPassword();
    Dom.get("staff_passwd").innerHTML= pwd;
    Dom.get("staff_passwd1").value= pwd;

    Dom.get('staff_new_save').style.visibility='visible';
    Dom.get("staff_passwd").style.display='';
    Dom.get("staff_user_defined_pwd_but").className='but but_unselected';
    Dom.get("staff_auto_pwd_but").className='but but_selected';

}
var user_defined_pwd=function(){
    Dom.get("staff_auto_dialog").style.display='none';
    Dom.get("staff_user_definded_dialog").style.display='';
    Dom.get('staff_new_save').style.visibility='hidden';
    Dom.get("staff_passwd").style.display='none';
    Dom.get("staff_passwd2").value='';
    Dom.get("staff_passwd1").value='';
    Dom.get('password_meter_bar').style.visibility='hidden';
    Dom.get('password_meter_bar').style.width="0%";
    Dom.get("staff_user_defined_pwd_but").className='but but_selected';
    Dom.get("staff_auto_pwd_but").className='but but_unselected';
}


var match_passwd=function(p2,p1,tipo){
    
    p1=Dom.get(p1).value;
    if(p1==p2){
	Dom.get("error_"+tipo+"_passwd2").style.display='none';
    }else{
	Dom.get("error_"+tipo+"_passwd2").style.display='';
    }

};

var staff_new_user=function(){
    var handle=Dom.get("staff_v_handle").value;
    var passwd=Dom.get("staff_passwd1").value;
    var passwd=sha256_digest(Dom.get("staff_passwd1").value);

    var request='ar_users.php?tipo=add_user&tipo_user=1&handle='+escape(handle)+'&passwd='+escape(passwd)+'&id_in_table='+escape(id_in_table);
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
	     // alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {


		    add_user_dialog_staff.cfg.setProperty("visible", false);
		    var table=tables['table0'];
		    var datasource=tables['dataSource0'];
		    var request='';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    
		    

		}else
		    alert(r.msg);
	    }
	});    
    

}



var change_meter=function(pwd){
    

    value=testPassword(pwd);

    	if(value < 6)
	    {
		strVerdict = "No good enough"
		color='#bd0e00';
	    }else if (value > 5 && value < 15){
	    strVerdict = "very weak"
	    color='#ff7f00';
	}else if (value > 14 && value < 25)
	    {
		strVerdict = "weak"
		color='#ffe500';
	    }
	else if (value > 24 && value < 35)
	    {
		strVerdict = "still weak"
		color='#b2ff00';
	    }
	else if (value > 34 && value < 45)
	    {
		strVerdict = "strong"
		color='#00ff00';
	    }
	else
	    {
		strVerdict = "stronger"
		color="#00ff00";
	    }
	value=2*value;
	if(value>100)
	    value=100;

	Dom.get('password_meter_bar').style.visibility='visible';
	
	Dom.get('password_meter_str').innerHTML=strVerdict;
	Dom.get('password_meter_bar').style.width=value+"%";
	Dom.get('password_meter_bar').style.backgroundColor=color;
	if(value>6){

	    Dom.get("staff_passwd").value=pwd;
	    Dom.get('staff_new_save').style.visibility='visible';
	    Dom.get("staff_passwd2").value='';
	    Dom.get("error_staff_passwd2").style.display='';
	}else{
	    Dom.get('staff_new_save').style.visibility='hidden';
	    Dom.get("staff_passwd2").value='';
	    Dom.get("error_staff_passwd2").style.display='none';
	}

 }

  function init(){

       add_user_dialog = new YAHOO.widget.Menu("add_user_dialog", {context:["add_user","tr", "br","beforeShow"]  });
       add_user_dialog.render();
       add_user_dialog.subscribe("show", add_user_dialog.focus);
       YAHOO.util.Event.addListener("add_user", "click", add_user_dialog.show, null, add_user_dialog); 

       add_user_dialog_others = new YAHOO.widget.Menu("add_user_other", {context:["add_user","tr", "br","beforeShow"]  });
       add_user_dialog_others.render();
      add_user_dialog_others.subscribe("show", add_user_dialog_others.focus);
       add_user_dialog_staff = new YAHOO.widget.Menu("add_user_staff", {context:["add_user","tr", "br","beforeShow"]  });
       add_user_dialog_staff.render();
       add_user_dialog_staff.subscribe("show", add_user_dialog_staff.focus);
      
  }

 YAHOO.util.Event.onDOMReady(init);