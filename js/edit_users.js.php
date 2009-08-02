<?phpinclude_once('../common.php')?>
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
    var Dom   = YAHOO.util.Dom; 

var id_in_table='';
var  add_user_dialog_staff;
var  add_user_dialog_other;

var  change_staff_password;
var to_save;


 function other_continue(){
     Dom.get('other_data_form').style.display='none';
     Dom.get('other_form').style.display='';
 }
function other_back(){
     Dom.get('other_data_form').style.display='';
     Dom.get('other_form').style.display='none';
 }


function close_me(pre)
{

   switch(pre){
    case('staff'):
// 	Dom.setX('change_staff_password', -1000);
// 	Dom.setY('change_staff_password', -1000);
// 	change_staff_password.cfg.setProperty("visible", false);;
	add_user_dialog_staff.cfg.setProperty("visible", false);;
	break;
   case('supplier'):
       	add_user_dialog_supplier.cfg.setProperty("visible", false);;
	break;
   case('other'):
       	add_user_dialog_other.cfg.setProperty("visible", false);;
	break;	
 case('other2'):
       	add_user_dialog_other2.cfg.setProperty("visible", false);;
	break;	

   }

}

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

var display_dialog=function(tipo){

  var y=(Dom.getY("add_user"))
    var x=(Dom.getX("add_user"))
    var w=Dom.get("add_user").offsetWidth
    x=x-350+w;

    switch(tipo){
    case('staff'):
    Dom.setX('add_user_staff', x)
    Dom.setY('add_user_staff', y)
    Dom.get("staff_list").style.display='';
    Dom.get("staff_form").style.display='none';
    add_user_dialog_staff.show();
    break;
  case('supplier'):
    Dom.setX('add_user_supplier', x)
    Dom.setY('add_user_supplier', y)
    Dom.get("supplier_form").style.display='none';
    add_user_dialog_supplier.show();
    break;
 case('other'):
    Dom.setX('add_user_other', x)
    Dom.setY('add_user_other', y)
    add_user_dialog_other.show();
    break;
 case('other2'):
    Dom.setX('add_user_other2', x)
    Dom.setY('add_user_other2', y)
    add_user_dialog_other2.show();
    break;
    }
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
    var user_name=o.getAttribute('user_name');
    Dom.get("change_staff_password_alias").setAttribute('user_id',user_id);
    Dom.get("change_staff_password_alias").innerHTML=user_name;
    change_staff_password.show();
    
}

var select_staff=function(o){

    var is_in=o.getAttribute('is_in');
    id_in_table=o.getAttribute('staff_id');
    //  o.className='selected';
    Dom.get("staff_list").style.display='none';
    Dom.get("staff_form").style.display='';

    Dom.get("staff_handle").innerHTML=o.innerHTML;
    handle=Dom.get("staff_v_handle").value=o.innerHTML;
    Dom.get('staff_handle_container').style.display='';
    Dom.get('staff_choose_method').style.display='';
    //to_save=id_in_table;
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

var change_staff_pwd=function(){
    
    passwd=sha256_digest(Dom.get('change_staff_passwd1').value);
    user_id=Dom.get('change_staff_password_alias').getAttribute('user_id');
    var request='ar_users.php?tipo=change_passwd&user_id='+escape(user_id)+'&value='+escape(passwd);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
		//		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.ok) {
		    
		    Dom.get('change_staff_passwd1').value='';
		    Dom.get('change_staff_passwd2').value='';

		    change_staff_password.cfg.setProperty("visible", false);
		    Dom.get('change_staff_password_alias').setAttribute('user_id','');
		    Dom.get('change_staff_password_alias').innerHTML='';
		    Dom.get('change_staff_save').style.visibility='hidden';
		    Dom.get('staff_password_meter').style.visibility='hidden';
		}else
		    alert(r.msg);
	    }
	});    
}

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
		    //alert('staff'+id_in_table);
		    Dom.get('staff'+id_in_table).className='selected';
		    Dom.get('staff_save').style.display='none';
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

var other_new_user=function(){
    var handle=Dom.get("other_handle_show").innerHTML;
    var passwd=sha256_digest(Dom.get("other_passwd1").value);
    var name=Dom.get("other_name").value;
    var surname=Dom.get("other_surname").value;
    var email=Dom.get("other_email").value;
    lang_list=document.getElementById("other_lang");
    var lang=lang_list.options[lang_list.selectedIndex].value;
    

    group_input=document.getElementById("other_the_form").group;
    group="";
    for (i=0;i<group_input.length;++ i)
	{
	    if (group_input[i].checked)
		{
		    group=group + group_input[i].value + ",";
		}
	}

    isactive_input=document.getElementById("other_the_form").isactive;
    isactive="";
    for (i=0;i<isactive_input.length;++ i)
	{
	    if (isactive_input[i].checked)
		{
		    isactive=isactive + isactive_input[i].value + ",";
		}
	}

    var request='ar_users.php?tipo=add_user&tipo_user=4&handle='+encodeURIComponent(handle)+'&passwd='+encodeURIComponent(passwd)+'&name='+encodeURIComponent(name)+'&surname='+encodeURIComponent(surname)+'&email='+encodeURIComponent(email)+'&lang='+lang+'&groups='+group+'&isactive='+isactive;
    alert(request);
    return;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
		// alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    add_user_dialog_other2.cfg.setProperty("visible", false);
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
    if(value<0)
	    value=0;

	if(value>100)
	    value=100;

	Dom.get(prefix+'_password_meter_bar').style.visibility='visible';
	
	Dom.get(prefix+'_password_meter_str').innerHTML=strVerdict;
	Dom.get(prefix+'_password_meter_bar').style.width=value+"%";
	Dom.get(prefix+'_password_meter_bar').style.backgroundColor=color;
	if(value>6){

	    Dom.get(prefix+"_passwd").value=pwd;
	    Dom.get(prefix+"_save").style.visibility='visible';
	    Dom.get(prefix+"_passwd2").value='';
	    Dom.get(prefix+"_error_passwd2").style.visibility='visible';
	}else{
	    Dom.get(prefix+"_save").style.visibility='hidden';
	    Dom.get(prefix+"_passwd2").value='';
	    Dom.get(prefix+"_error_passwd2").style.visibility='hidden';
	}

    };


  function init(){


      

       add_user_dialog = new YAHOO.widget.Menu("add_user_dialog", {context:["add_user","tr", "br","beforeShow"]  });
       add_user_dialog.render();
       add_user_dialog.subscribe("show", add_user_dialog.focus);
       YAHOO.util.Event.addListener("add_user", "click", add_user_dialog.show, null, add_user_dialog); 

       add_user_dialog_other = new YAHOO.widget.Menu("add_user_other", {context:["add_user","tr", "br","beforeShow"]  });
       add_user_dialog_other.render();
       add_user_dialog_other.subscribe("show", add_user_dialog_other.focus);
      
       add_user_dialog_other2 = new YAHOO.widget.Menu("add_user_other2", {context:["add_user","tr", "br","beforeShow"]  });
       add_user_dialog_other2.render();
       add_user_dialog_other2.subscribe("show", add_user_dialog_other2.focus);


       add_user_dialog_staff = new YAHOO.widget.Dialog("add_user_staff", {
 	      context:["add_user","tr","tr"]  ,
 	      visible : false,close:false,underlay: "none",draggable:false
	      
 	  });
        add_user_dialog_staff.render();

 

      // change_staff_password = new YAHOO.widget.Menu("change_staff_password",{x:100});
       //change_staff_password.render();
       //change_staff_password.subscribe("show", add_user_dialog_staff.focus);


       change_staff_password = new YAHOO.widget.Dialog("change_staff_password", 
			{ 
			    visible : false,close:false,
			    underlay: "none",draggable:false
			    
			} );
       change_staff_password.render();
       //       change_staff_password.show();

       // Dom.get("change_staff_cancel").onselectstart = function() { return(false); };
      
       
       add_user_dialog_supplier = new YAHOO.widget.Dialog("add_user_supplier", {
	       context:["add_user","tr","tr"]  ,
	       visible : false,close:false,underlay: "none",draggable:false
	       
	   });
       add_user_dialog_supplier.render();





       

  }

 YAHOO.util.Event.onDOMReady(init);

var update_form =function (key) {

    switch(key){
    case('other'):
    if(Dom.get('other_handle').getAttribute('ok')==1 &&  Dom.get('other_email').getAttribute('ok')==1  && ( Dom.get('other_name').getAttribute('ok')==1  ||  Dom.get('other_surname').getAttribute('ok')==1   )  )
	Dom.get('other_continue').style.visibility='visible';
    else
	Dom.get('other_continue').style.visibility='hidden';

	
	break;
	}	Dom.get('other_continue').style.visibility='visible';
};

var validate_handle =function (query) {  query=unescape(query);
    var request='ar_users.php?tipo=valid_handle&handle=' + encodeURIComponent(query);

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		var element=Dom.get('other_handle');var label=Dom.get('other_handle_label');
		if(r.state==200){
		    element.setAttribute('ok',1);label.className='valid';Dom.get('other_handle_show').innerHTML=query;
		}else{
		    element.setAttribute('ok',0);label.className='no_valid';Dom.get('other_handle_show').innerHTML='';
		}
		update_form('other');	
	    }
	});
};
var validate_email =function (email) {
    email=unescape(email);
    var element=Dom.get('other_email');var label=Dom.get('other_email_label');

    if(isValidEmail(email)){
	element.setAttribute('ok',1);
	label.className='valid';
    }else{
	element.setAttribute('ok',0);
	label.className='no_valid';
    }
	update_form('other');	
};

var validate_name =function (name) {
    var element=Dom.get('other_name');
    var label=Dom.get('other_name_label');

    if(name.length>0){
	element.setAttribute('ok',1);
	label.className='valid';

    }else{
	element.setAttribute('ok',0);
	label.className='no_valid';

    }
update_form('other');	
};
var validate_surname =function (surname) {
    var element=Dom.get('other_surname');var label=Dom.get('other_surname_label');
    if(surname.length>0){
	element.setAttribute('ok',1);label.className='valid';
    }else{
	element.setAttribute('ok',0);label.className='no_valid';
    }update_form('other');	
};



YAHOO.util.Event.onContentReady("other_container", function () {

 var oACDS_handle = new YAHOO.util.FunctionDataSource(validate_handle);
 oACDS_handle .queryMatchContains = true;
 var oAutoComp_handle  = new YAHOO.widget.AutoComplete("other_handle","other_container", oACDS_handle );
 oAutoComp_handle .minQueryLength = 0; 

 var oACDS_email = new YAHOO.util.FunctionDataSource(validate_email);
 oACDS_email .queryMatchContains = true;
 var oAutoComp_email  = new YAHOO.widget.AutoComplete("other_email","other_container", oACDS_email );
 oAutoComp_email .minQueryLength = 0; 

var oACDS_name = new YAHOO.util.FunctionDataSource(validate_name);
 oACDS_name .queryMatchContains = true;
 var oAutoComp_name  = new YAHOO.widget.AutoComplete("other_name","other_container", oACDS_name );
 oAutoComp_name .minQueryLength = 0; 
var oACDS_surname = new YAHOO.util.FunctionDataSource(validate_surname);
 oACDS_surname .queryMatchContains = true;
 var oAutoComp_surname  = new YAHOO.widget.AutoComplete("other_surname","other_container", oACDS_surname );
 oAutoComp_surname .minQueryLength = 0; 
    });