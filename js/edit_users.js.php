<?include_once('../common.php')?>
    var Dom   = YAHOO.util.Dom; 

var id_in_table='';
var  add_user_dialog_staff;
var  change_staff_password;
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

var change_passwd=function(o){
    var y=(Dom.getY(o))
    var x=(Dom.getX(o))
    x=x+20;
    //    alert(y);
    Dom.setX('change_staff_password', x)
    Dom.setY('change_staff_password', y)
    //    add_user_dialog_staff.cfg.setProperty("x", "500");
    //add_user_dialog_staff.cfg.setProperty("y", 500);

    var user_id=o.getAttribute('user_id');
    
    change_staff_password.show();
    
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


var auto_pwd=function(prefix){
    
    Dom.get(prefix+"_user_defined_dialog").style.display='none';
    Dom.get(prefix+"_auto_dialog").style.display='';
    var pwd=randPassword();
    Dom.get(prefix+"_passwd").innerHTML= pwd;
    Dom.get(prefix+"_passwd1").value= pwd;

    Dom.get(prefix+'_save').style.visibility='visible';
    Dom.get(prefix+"_passwd").style.display='';
    Dom.get(prefix+"_user_defined_pwd_but").className='tab  but_unselected unselectable_text';
    Dom.get(prefix+"_auto_pwd_but").className='tab selected unselectable_text';

}
var user_defined_pwd=function(prefix){
    Dom.get(prefix+"_auto_dialog").style.display='none';
    Dom.get(prefix+"_user_defined_dialog").style.display='';
    Dom.get(prefix+"_save").style.visibility='hidden';
    Dom.get(prefix+"_passwd").style.display='none';
    Dom.get(prefix+"_passwd2").value='';
    Dom.get(prefix+"_passwd1").value='';
    Dom.get(prefix+'_password_meter_bar').style.visibility='hidden';
    Dom.get(prefix+'_password_meter_bar').style.width="0%";
    Dom.get(prefix+"_user_defined_pwd_but").className='tab  selected unselectable_text';
    Dom.get(prefix+"_auto_pwd_but").className='tab unselectable_text';
}


var match_passwd=function(p2,p1,tipo){
    
    p1=Dom.get(p1).value;
    if(p1==p2){
	Dom.get(tipo+"_error_passwd2").style.visibility='hidden';
    }else{
	Dom.get(tipo+"_error_passwd2").style.visibility='visible';
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



    var change_meter=function(pwd,prefix){
    

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

	Dom.get(prefix+'_password_meter_bar').style.visibility='visible';
	
	Dom.get(prefix+'_password_meter_str').innerHTML=strVerdict;
	Dom.get(prefix+'_password_meter_bar').style.width=value+"%";
	Dom.get(prefix+'_password_meter_bar').style.backgroundColor=color;
	if(value>6){

	    Dom.get(prefix+"_passwd").value=pwd;
	    Dom.get(prefix+"_new_save").style.visibility='visible';
	    Dom.get(prefix+"_passwd2").value='';
	    Dom.get(prefix+"_error_passwd2").style.visibility='visible';
	}else{
	    Dom.get(prefix+"_new_save").style.visibility='hidden';
	    Dom.get(prefix+"_passwd2").value='';
	    Dom.get(prefix+"_error_passwd2").style.visibility='hidden';
	}

    };

var close_dialog=function(prefix){
    switch(prefix){
    case('change_staff'):
    Dom.setX('change_staff_password', -1000)
    Dom.setY('change_staff_password', -1000)
    change_staff_password.cfg.setProperty("visible", false);;

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
      
  //     add_user_dialog_staff = new YAHOO.widget.Dialog("add_user_staff", {
// 	      context:["add_user","tr","tr"]  ,
// 	      visible : false,close:false,underlay: "none",draggable:false,width:550
	      
// 	  });
//        add_user_dialog_staff.render();

 

      // change_staff_password = new YAHOO.widget.Menu("change_staff_password",{x:100});
       //change_staff_password.render();
       //change_staff_password.subscribe("show", add_user_dialog_staff.focus);


       change_staff_password = new YAHOO.widget.Dialog("change_staff_password", 
			{ 
			    visible : false,close:false,
			    underlay: "none",draggable:false,
			    
			} );
       change_staff_password.render();
       //       change_staff_password.show();

       // Dom.get("change_staff_cancel").onselectstart = function() { return(false); };
      
  }

 YAHOO.util.Event.onDOMReady(init);