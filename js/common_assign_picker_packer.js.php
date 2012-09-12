function show_active_staff_dialog(o){


	Dom.removeClass(Dom.getElementsByClassName('pack_it_button', 'td', 'pack_it_buttons'),'selected');
	Dom.removeClass(Dom.getElementsByClassName('pick_it_button', 'td', 'pick_it_buttons'),'selected');
	Dom.addClass(o,'selected');

	Dom.get('staff_name_tr').style.display='none';
	Dom.get('staff_name_pick_tr').style.display='none';

	Dom.get('pack_it_pin_tr').style.display='none';
	Dom.get('pick_it_pin_tr').style.display='none';
	Dom.get('active_staff_list_dialog').style.display='';


}

function close_dialog(dialog_name) {
    switch ( dialog_name ) {
    case 'assign_picker_dialog':
        Dom.get('Assign_Picker_Staff_Name').value='';
        Dom.get('assign_picker_staff_key').value='';
        Dom.get('Assign_Picker_Staff_Name').focus();
        Dom.get('assign_picker_sup_password').value='';
        Dom.removeClass(Dom.getElementsByClassName('assign_picker_button', 'td', 'assign_picker_buttons'),'selected');
        assign_picker_dialog.hide();
        break;
    case('pick_it_dialog'):
    
        Dom.get('pick_it_Staff_Name').value='';
        Dom.get('pick_it_staff_key').value='';
        Dom.setStyle('pick_it_pin_tr','display','none');
        Dom.get("pick_it_pin_alias").innerHTML='';
        Dom.removeClass(Dom.getElementsByClassName('pick_it_button', 'td', 'assign_picker_buttons'),'selected');
        Dom.get('pick_it_password').value='';
        pick_it_dialog.hide();
        break;
          case 'assign_packer_dialog':
        Dom.get('Assign_Packer_Staff_Name').value='';
        Dom.get('assign_packer_staff_key').value='';
        Dom.get('Assign_Packer_Staff_Name').focus();
        Dom.get('assign_packer_sup_password').value='';
        Dom.removeClass(Dom.getElementsByClassName('assign_packer_button', 'td', 'assign_packer_buttons'),'selected');
        assign_packer_dialog.hide();
        break;
    case('pack_it_dialog'):
    
        Dom.get('pack_it_Staff_Name').value='';
        Dom.get('pack_it_staff_key').value='';
        Dom.setStyle('pack_it_pin_tr','visibility','hidden');
        Dom.get("pack_it_pin_alias").innerHTML='';
        Dom.removeClass(Dom.getElementsByClassName('pack_it_button', 'td', 'assign_packer_buttons'),'selected');
        Dom.get('pack_it_password').value='';
        pack_it_dialog.hide();
        break;
    default:

    }
}
function select_staff(o){



var staff_key=o.getAttribute('staff_id');
var staff_alias=o.innerHTML;



Dom.removeClass(Dom.getElementsByClassName('assign_picker_button', 'td', 'assign_picker_buttons'),'selected');
Dom.addClass(o,'selected');
Dom.get('Assign_Picker_Staff_Name').value=staff_alias;
Dom.get('assign_picker_staff_key').value=staff_key;
Dom.get('assign_picker_sup_password').focus();
}

function select_staff_assign_packer(o){

var staff_key=o.getAttribute('staff_id');
var staff_alias=o.innerHTML;



Dom.removeClass(Dom.getElementsByClassName('assign_packer_button', 'td', 'assign_packer_buttons'),'selected');
Dom.addClass(o,'selected');
Dom.get('Assign_Packer_Staff_Name').value=staff_alias;
Dom.get('assign_packer_staff_key').value=staff_key;
Dom.get('assign_packer_sup_password').focus();
}

function select_staff_pick_it(o){
var staff_key=o.getAttribute('staff_id');
var staff_alias=o.innerHTML;
Dom.removeClass(Dom.getElementsByClassName('pick_it_button', 'td', 'pick_it_buttons'),'selected');
Dom.removeClass(Dom.getElementsByClassName('pick_it_button', 'td', 'pick_it_buttons_'),'selected');
Dom.addClass(o,'selected');
dialog_other_staff.hide();
Dom.get('staff_name_pick_tr').style.display='';
Dom.get('pick_it_pin_tr').style.display='';



Dom.get('pick_it_Staff_Name').value=staff_alias;
Dom.get('pick_it_staff_key').value=staff_key;

Dom.setStyle('pick_it_pin_tr','display','');
Dom.get("pick_it_pin_alias").innerHTML=staff_alias;
Dom.get('pick_it_password').focus();
}

function assign_picker_save(){
Dom.get('pick_it_msg').innerHTML='';
var staff_key=Dom.get('assign_picker_staff_key').value;
 var sup_pwd=   Dom.get('assign_picker_sup_password').value;
var dn_key=Dom.get('assign_picker_dn_key').value;


    var request='ar_edit_orders.php?tipo=assign_picker&dn_key='+escape(dn_key)+'&staff_key='+escape(staff_key)+'&pin='+escape(sup_pwd);
 //  alert(request); return;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
			//	alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state==200) {
		    
		    if(r.action='updated' && Dom.get('operations'+dn_key)){

		    Dom.get('operations'+dn_key).innerHTML=r.operations;
		    
		    }
		    close_dialog('assign_picker_dialog');
		    
		    if(!Dom.get('operations'+dn_key)){
		     location.href='order_pick_aid.php?id='+dn_key;
		    }
		    

		}else{
		Dom.get('pick_it_msg').innerHTML=r.msg
		  
	    }
	    }
	});    

}
function pick_it_save(){
Dom.get('pick_it_msg').innerHTML='';
var staff_key=Dom.get('pick_it_staff_key').value;
var sup_pwd=   Dom.get('pick_it_password').value;
var dn_key=Dom.get('pick_it_dn_key').value;
    var request='ar_edit_orders.php?tipo=pick_it&dn_key='+escape(dn_key)+'&staff_key='+escape(staff_key)+'&pin='+escape(sup_pwd);
  //   alert(request)
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
				//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state==200) {
		    
		    if(r.action='updated'){
		    location.href='order_pick_aid.php?id='+dn_key;
		    }
		    close_dialog('pick_it_dialog');

		}else{
		Dom.get('pick_it_msg').innerHTML=r.msg
		
	    }
	    }
	});    

}
function assign_picker(o,dn_key){

region1 = Dom.getRegion(o); 
    region2 = Dom.getRegion('assign_picker_dialog'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('assign_picker_dialog', pos);



   Dom.get('Assign_Picker_Staff_Name').focus();
   Dom.get('assign_picker_dn_key').value=dn_key;
 Dom.get('staff_list_parent_dialog').value='assign_picker';
    assign_picker_dialog.show();
    
  
    
}
function pick_it(o,dn_key){

var staff_alias='';
var staff_key='';
Dom.get('pick_it_msg').innerHTML='';
Dom.setStyle('pick_it_pin_tr','display','none');

Dom.get('pick_it_Staff_Name').value=staff_alias;
Dom.get('pick_it_staff_key').value=staff_key;
Dom.get("pick_it_pin_alias").innerHTML=staff_alias;

region1 = Dom.getRegion(o); 
    region2 = Dom.getRegion('pick_it_dialog'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('pick_it_dialog', pos);


   Dom.get('pick_it_Staff_Name').focus();
   Dom.get('pick_it_dn_key').value=dn_key;
Dom.get('staff_list_parent_dialog').value='pick_it';
    pick_it_dialog.show();


}
function select_staff_pack_it(o){
var staff_key=o.getAttribute('staff_id');
var staff_alias=o.innerHTML;
Dom.removeClass(Dom.getElementsByClassName('pack_it_button', 'td', 'pack_it_buttons'),'selected');
Dom.removeClass(Dom.getElementsByClassName('pack_it_button', 'td', 'pack_it_buttons_all'),'selected');
Dom.addClass(o,'selected');
dialog_other_staff.hide();
Dom.get('staff_name_tr').style.display='';
Dom.get('pack_it_pin_tr').style.display='';


Dom.get('pack_it_Staff_Name').value=staff_alias;
Dom.get('pack_it_staff_key').value=staff_key;

Dom.setStyle('pack_it_pin_tr','visibility','visible');
Dom.get("pack_it_pin_alias").innerHTML=staff_alias;
Dom.get('pack_it_password').focus();
}
function assign_packer_save(){

var staff_key=Dom.get('assign_packer_staff_key').value;
 var sup_pwd=   Dom.get('assign_packer_sup_password').value;
var dn_key=Dom.get('assign_packer_dn_key').value;
    var request='ar_edit_orders.php?tipo=assign_packer&dn_key='+escape(dn_key)+'&staff_key='+escape(staff_key)+'&pin='+escape(sup_pwd);
     //alert(request)
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
				//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state==200) {
		    
		    if(r.action='updated'  && Dom.get('operations'+dn_key)){
		    Dom.get('operations'+dn_key).innerHTML=r.operations;
		    Dom.get('dn_state'+dn_key).innerHTML=r.dn_state;
		    }
		    close_dialog('assign_packer_dialog');

    if(!Dom.get('operations'+dn_key)){
		     location.href='order_pack_aid.php?id='+dn_key;
		    }

		}else{
		    alert(r.msg);
	    }
	    }
	});    

}
function pack_it_save(){

var staff_key=Dom.get('pack_it_staff_key').value;
var sup_pwd=   Dom.get('pack_it_password').value;
var dn_key=Dom.get('pack_it_dn_key').value;
    var request='ar_edit_orders.php?tipo=pack_it&dn_key='+escape(dn_key)+'&staff_key='+escape(staff_key)+'&pin='+escape(sup_pwd);
     
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
			//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state==200) {
		    
		    if(r.action='updated'){
		    location.href='order_pack_aid.php?id='+dn_key;
		    }
		    close_dialog('pack_it_dialog');

		}else{
		  alert(r.msg);
	    }
	    }
	});    

}
function assign_packer(o,dn_key){
    var y=(Dom.getY(o))
    var x=(Dom.getX(o))
    x=x-120;
    y=y+18;
    Dom.setX('assign_packer_dialog', x)
    Dom.setY('assign_packer_dialog', y)
   Dom.get('Assign_Packer_Staff_Name').focus();
   Dom.get('assign_packer_dn_key').value=dn_key;
Dom.get('staff_list_parent_dialog').value='assign_packer';
    assign_packer_dialog.show();
}
function pack_it(o,dn_key){
    var y=(Dom.getY(o))
    var x=(Dom.getX(o))
    x=x-120;
    y=y+18;
    Dom.setX('pack_it_dialog', x)
    Dom.setY('pack_it_dialog', y)
   Dom.get('pack_it_Staff_Name').focus();
   Dom.get('pack_it_dn_key').value=dn_key;
Dom.get('staff_list_parent_dialog').value='pack_it';
    pack_it_dialog.show();
}

function show_other_staff(o){
/*
var staff_alias='';
var staff_key='';

Dom.get('pick_it_Staff_Name').value=staff_alias;
Dom.get('pick_it_staff_key').value=staff_key;
Dom.get("pick_it_pin_alias").innerHTML=staff_alias;
Dom.get('pack_it_Staff_Name').value=staff_alias;
Dom.get('pack_it_staff_key').value=staff_key;
Dom.get("pack_it_pin_alias").innerHTML=staff_alias;
*/

	Dom.removeClass(Dom.getElementsByClassName('pack_it_button', 'td', 'pack_it_buttons'),'selected');
	//Dom.addClass(e,'selected');


	Dom.removeClass(Dom.getElementsByClassName('pick_it_button', 'td', 'pick_it_buttons'),'selected');
	Dom.removeClass(Dom.getElementsByClassName('assign_picker_button', 'td', 'assign_picker_buttons'),'selected');
	Dom.removeClass(Dom.getElementsByClassName('assign_packer_button', 'td', 'assign_packer_buttons '),'selected');

	Dom.addClass(o,'selected');

	region1 = Dom.getRegion(o); 
	region2 = Dom.getRegion('dialog_other_staff'); 

	var pos =[region1.right-region2.width-20,region1.bottom]

	Dom.setXY('dialog_other_staff', pos);

	dialog_other_staff.show();
	
	
	if(o.getAttribute('td_id')=='other_staff_picker'){
		Dom.get('staff_list_parent_dialog').value='assign_picker';
	}
	
		if(o.getAttribute('td_id')=='other_staff_packer'){
		Dom.get('staff_list_parent_dialog').value='assign_packer';
	}
	
	
}



function select_staff_from_list(oArgs){

//alert(tables.table2)

// alert(oArgs)
//alert(Dom.get('staff_list_parent_dialog').value);
var staff_alias=tables.table2.getRecord(oArgs.target).getData('code');
var staff_key=tables.table2.getRecord(oArgs.target).getData('key');
//alert(staff_alias + ':' + staff_key )



switch(Dom.get('staff_list_parent_dialog').value){
case 'pick_it':
	Dom.get('pick_it_Staff_Name').value=staff_alias;
	Dom.get('pick_it_staff_key').value=staff_key;

	Dom.setStyle('pick_it_pin_tr','display','');
	Dom.get("pick_it_pin_alias").innerHTML=staff_alias;
	Dom.get('pick_it_password').focus();
	break;
case 'pack_it':
	Dom.get('pack_it_Staff_Name').value=staff_alias;
	Dom.get('pack_it_staff_key').value=staff_key;

	Dom.setStyle('pack_it_pin_tr','visibility','visible');
	Dom.get("pack_it_pin_alias").innerHTML=staff_alias;
	Dom.get('pack_it_password').focus();
	break;
case 'assign_picker':
	Dom.get('Assign_Picker_Staff_Name').value=staff_alias;
	Dom.get('assign_picker_staff_key').value=staff_key;
	Dom.get('assign_picker_sup_password').focus();
	break;
case 'assign_packer':
	Dom.get('Assign_Packer_Staff_Name').value=staff_alias;
	Dom.get('assign_packer_staff_key').value=staff_key;
	Dom.get('assign_packer_sup_password').focus();
	break;

}






dialog_other_staff.hide();
}




