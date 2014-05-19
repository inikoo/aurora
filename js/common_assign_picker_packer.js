function show_active_staff_dialog(o) {


    Dom.removeClass(Dom.getElementsByClassName('pack_it_button', 'td', 'pack_it_buttons'), 'selected');
    Dom.removeClass(Dom.getElementsByClassName('pick_it_button', 'td', 'pick_it_buttons'), 'selected');
    Dom.addClass(o, 'selected');

    Dom.get('staff_name_tr').style.display = 'none';
    Dom.get('staff_name_pick_tr').style.display = 'none';

    Dom.get('pack_it_pin_tr').style.display = 'none';
    Dom.get('active_staff_list_dialog').style.display = '';


}

function close_dialog(dialog_name) {
    switch (dialog_name) {
    case 'assign_picker_dialog':
        Dom.get('Assign_Picker_Staff_Name').value = '';
        Dom.get('assign_picker_staff_key').value = '';
        Dom.get('Assign_Picker_Staff_Name').focus();
        Dom.get('assign_picker_sup_password').value = '';
        Dom.removeClass(Dom.getElementsByClassName('assign_picker_button', 'td', 'assign_picker_buttons'), 'selected');
        assign_picker_dialog.hide();
        break;
    case ('pick_it_dialog'):

        Dom.get('pick_it_Staff_Name').value = '';
        Dom.get('pick_it_staff_key').value = '';
        Dom.setStyle('pick_it_pin_tr', 'display', 'none');
        Dom.get("pick_it_pin").innerHTML = '';
        Dom.removeClass(Dom.getElementsByClassName('pick_it_button', 'td', 'assign_picker_buttons'), 'selected');
        Dom.get('pick_it_pin').value = '';
        pick_it_dialog.hide();
        break;
    case 'assign_packer_dialog':
        Dom.get('Assign_Packer_Staff_Name').value = '';
        Dom.get('assign_packer_staff_key').value = '';
        Dom.get('Assign_Packer_Staff_Name').focus();
        Dom.get('assign_packer_sup_password').value = '';
        Dom.removeClass(Dom.getElementsByClassName('assign_packer_button', 'td', 'assign_packer_buttons'), 'selected');
        assign_packer_dialog.hide();
        break;
    case ('pack_it_dialog'):

        Dom.get('pack_it_Staff_Name').value = '';
        Dom.get('pack_it_staff_key').value = '';
        Dom.setStyle('pack_it_pin_tr', 'visibility', 'hidden');
        Dom.get("pack_it_pin_alias").innerHTML = '';
        Dom.removeClass(Dom.getElementsByClassName('pack_it_button', 'td', 'assign_packer_buttons'), 'selected');
        Dom.get('pack_it_password').value = '';
        pack_it_dialog.hide();
        break;
    default:

    }
}


function select_unknown_staff(o){

    dialog_other_staff.hide();
 var staff_key =0;
    var staff_alias = o.innerHTML;

 switch (Dom.get('staff_list_parent_dialog').value) {
    case 'assign_picker':

        Dom.removeClass(Dom.getElementsByClassName('assign_picker_button', 'td', 'assign_picker_buttons'), 'selected');
        Dom.addClass(o, 'selected');
        Dom.get('Assign_Picker_Staff_Name').value = staff_alias;
        Dom.get('assign_picker_staff_key').value = staff_key;
        Dom.get('assign_picker_sup_password').focus();
        Dom.setStyle('Assign_Picker_Staff_Name_tr','display', '')
        Dom.get('Assign_Picker_Staff_Name_label').innerHTML = staff_alias;

                
                
break;
    case 'assign_packer':
        Dom.removeClass(Dom.getElementsByClassName('assign_packer_button', 'td', 'assign_packer_buttons'), 'selected');
        Dom.addClass(o, 'selected');
        Dom.get('Assign_Packer_Staff_Name').value = staff_alias;
        Dom.get('assign_packer_staff_key').value = staff_key;
        Dom.get('assign_packer_sup_password').focus();
        Dom.setStyle('Assign_Packer_Staff_Name_tr','display', '')
        Dom.get('Assign_Packer_Staff_Name_label').innerHTML = staff_alias;


    }


}

function select_staff(o) {


    var staff_key = o.getAttribute('staff_id');
    var staff_alias = o.innerHTML;

    scope = o.getAttribute('scope')
    
   // alert(scope)
    if (scope == 'picker') {

        Dom.removeClass(Dom.getElementsByClassName('assign_picker_button', 'td', 'assign_picker_buttons'), 'selected');
        Dom.addClass(o, 'selected');
        Dom.get('Assign_Picker_Staff_Name').value = staff_alias;
        Dom.get('assign_picker_staff_key').value = staff_key;
        Dom.get('assign_picker_sup_password').focus();
        Dom.setStyle('Assign_Picker_Staff_Name_tr','display', '')
        
        if(Dom.get('Assign_Picker_Staff_Name_label')!= undefined)
        Dom.get('Assign_Picker_Staff_Name_label').innerHTML = staff_alias;

                
                

    }else if (scope == 'packer') {
        Dom.removeClass(Dom.getElementsByClassName('assign_packer_button', 'td', 'assign_packer_buttons'), 'selected');
        Dom.addClass(o, 'selected');
      
      
      Dom.get('Assign_Packer_Staff_Name').value = staff_alias;
        Dom.get('assign_packer_staff_key').value = staff_key;
        Dom.get('assign_packer_sup_password').focus();
        Dom.setStyle('Assign_Packer_Staff_Name_tr','display', '')
        Dom.get('Assign_Packer_Staff_Name_label').innerHTML = staff_alias;

    }else if (scope == 'pick_it') {
        Dom.removeClass(Dom.getElementsByClassName('assign_picker_button', 'td', 'pick_it_buttons'), 'selected');
        Dom.addClass(o, 'selected');
        Dom.get('pick_it_Staff_Name').value = staff_alias;
        Dom.get('pick_it_staff_key').value = staff_key;
        Dom.get('pick_it_pin').focus();
        Dom.setStyle('pick_it_Staff_Name_tr','display', '')
        Dom.get('pick_it_Staff_Name_label').innerHTML = staff_alias;

                Dom.removeClass('pick_it_save','disabled')
                

    } else if (scope == 'pack_it') {
        Dom.removeClass(Dom.getElementsByClassName('pack_it_button', 'td', 'pack_it_buttons'), 'selected');
        Dom.addClass(o, 'selected');
        Dom.get('Assign_Packer_Staff_Name').value = staff_alias;
        Dom.get('assign_packer_staff_key').value = staff_key;
        Dom.get('assign_packer_sup_password').focus();
        Dom.setStyle('Assign_Packer_Staff_Name_tr','display', '')
        Dom.get('Assign_Packer_Staff_Name_label').innerHTML = staff_alias;


    }


}

function select_staff_assign_packer(o) {

    var staff_key = o.getAttribute('staff_id');
    var staff_alias = o.innerHTML;
    Dom.removeClass(Dom.getElementsByClassName('assign_packer_button', 'td', 'assign_packer_buttons'), 'selected');
    Dom.addClass(o, 'selected');
    Dom.get('Assign_Packer_Staff_Name').value = staff_alias;
    Dom.get('assign_packer_staff_key').value = staff_key;
    Dom.get('assign_packer_sup_password').focus();
}

function select_staff_pick_it(o) {
    var staff_key = o.getAttribute('staff_id');
    var staff_alias = o.innerHTML;
    Dom.removeClass(Dom.getElementsByClassName('pick_it_button', 'td', 'pick_it_buttons'), 'selected');
    Dom.removeClass(Dom.getElementsByClassName('pick_it_button', 'td', 'pick_it_buttons_'), 'selected');
    Dom.addClass(o, 'selected');
    dialog_other_staff.hide();
    Dom.get('staff_name_pick_tr').style.display = '';
    Dom.get('pick_it_pin_tr').style.display = '';



    Dom.get('pick_it_Staff_Name').value = staff_alias;
    Dom.get('pick_it_staff_key').value = staff_key;

    Dom.setStyle('pick_it_pin_tr', 'display', '');
    Dom.get("pick_it_pin").innerHTML = staff_alias;
    Dom.get('pick_it_pin').focus();
}


function pick_it_fast(o,staff_key, dn_key) {
    var request = 'ar_edit_orders.php?tipo=assign_picker&dn_key=' + escape(dn_key) + '&staff_key=' + escape(staff_key) + '&pin=';
   // alert(request)
             if( Dom.get('pick_it_fast_img_'+dn_key) != undefined)
     Dom.get('pick_it_fast_img_'+dn_key).src='art/loading.gif';
  
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            alert(o.responseText)
           var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                start_picking(r.dn_key, r.staff_key)
            } else {
                Dom.get('pick_it_msg').innerHTML = r.msg
            }
        }
    });

}

function start_picking(dn_key, staff_key) {
    var request = 'ar_edit_orders.php?tipo=start_picking&dn_key=' + escape(dn_key) + '&pin=&staff_key=' + escape(staff_key);
           if( Dom.get('start_picking_img_'+dn_key) != undefined)

      Dom.get('start_picking_img_'+dn_key).src='art/loading.gif';

    
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
       //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                location.href = 'order_pick_aid.php?id=' + r.dn_key;
            } else {
                Dom.get('pick_it_msg').innerHTML = r.msg
            }
        }
    });

}

function pack_it_fast(o,staff_key, dn_key) {
    var request = 'ar_edit_orders.php?tipo=assign_packer&dn_key=' + escape(dn_key) + '&staff_key=' + escape(staff_key) + '&pin=';
     
     if( Dom.get('pack_it_fast_img_'+dn_key) != undefined)
     Dom.get('pack_it_fast_img_'+dn_key).src='art/loading.gif';

   YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
               start_packing(r.dn_key, r.staff_key)
            } else {
            alert(r.msg)
                //Dom.get('pack_it_msg').innerHTML = r.msg
           }
        }
    });

}

function start_packing(dn_key, staff_key) {
    var request = 'ar_edit_orders.php?tipo=start_packing&dn_key=' + escape(dn_key) + '&pin=&staff_key=' + escape(staff_key);
  
  
  if( Dom.get('start_packing_img_'+dn_key) != undefined)
     Dom.get('start_packing_img_'+dn_key).src='art/loading.gif';
  
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
           //alert(o.responseText)
           var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                location.href = 'order_pack_aid.php?id=' + r.dn_key;
            } else {
                 alert(r.msg)
                //Dom.get('pack_it_msg').innerHTML = r.msg
            }
        }
    });

}




function assign_picker_save() {
    Dom.get('pick_it_msg').innerHTML = '';
    var staff_key = Dom.get('assign_picker_staff_key').value;
    var sup_pwd = Dom.get('assign_picker_sup_password').value;
    var dn_key = Dom.get('assign_picker_dn_key').value;

    var request = 'ar_edit_orders.php?tipo=' + Dom.get('assign_picker_dialog_type').value + '&dn_key=' + escape(dn_key) + '&staff_key=' + escape(staff_key) + '&pin=' + escape(sup_pwd);

    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            //	alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

			 if (!Dom.get('operations' + r.dn_key)) {
                    location.href = 'order_pick_aid.php?id=' + r.dn_key;
                }

                if (r.action = 'updated' && Dom.get('operations' + r.dn_key)) {

                    Dom.get('operations_container' + r.dn_key).innerHTML = r.operations;
                    Dom.get('dn_state' + r.dn_key).innerHTML = r.dn_state;



                }
                close_dialog('assign_picker_dialog');

               


            } else {
                Dom.get('pick_it_msg').innerHTML = r.msg

            }
        }
    });

}


function pick_it_save() {
    Dom.get('pick_it_msg').innerHTML = '';
    var staff_key = Dom.get('pick_it_staff_key').value;
    var sup_pwd = Dom.get('pick_it_pin').value;
    var dn_key = Dom.get('pick_it_dn_key').value;
    var request = 'ar_edit_orders.php?tipo=pick_it&dn_key=' + escape(dn_key) + '&staff_key=' + escape(staff_key) + '&pin=' + escape(sup_pwd);
     alert(request)
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                if (r.action = 'updated') {
                    location.href = 'order_pick_aid.php?id=' + dn_key;
                }
                close_dialog('pick_it_dialog');

            } else {
                Dom.get('pick_it_msg').innerHTML = r.msg

            }
        }
    });

}

function assign_picker(o, dn_key) {
    Dom.setStyle('assign_picker_dialog', 'display','');

    region1 = Dom.getRegion(o);
    region2 = Dom.getRegion('assign_picker_dialog');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('assign_picker_dialog', pos);

    Dom.get('Assign_Picker_Staff_Name').focus();
    Dom.get('assign_picker_dn_key').value = dn_key;
    Dom.get('staff_list_parent_dialog').value = 'assign_picker';
    assign_picker_dialog.show();
}


function pick_it(o, dn_key) {

//alert("xx")

    Dom.setStyle('assign_picker_dialog', 'display','');

    var staff_alias = '';
    var staff_key = '';
    Dom.get('pick_it_msg').innerHTML = '';
    Dom.setStyle('pick_it_pin_tr', 'display', 'none');

    Dom.get('pick_it_Staff_Name').value = staff_alias;
    Dom.get('pick_it_staff_key').value = staff_key;
    Dom.get("pick_it_pin").innerHTML = staff_alias;

    region1 = Dom.getRegion(o);
    region2 = Dom.getRegion('pick_it_dialog');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('pick_it_dialog', pos);


    Dom.get('pick_it_Staff_Name').focus();
    Dom.get('pick_it_dn_key').value = dn_key;
    Dom.get('staff_list_parent_dialog').value = 'pick_it';
    pick_it_dialog.show();


}

function select_staff_pack_it(o) {
    var staff_key = o.getAttribute('staff_id');
    var staff_alias = o.innerHTML;
    Dom.removeClass(Dom.getElementsByClassName('pack_it_button', 'td', 'pack_it_buttons'), 'selected');
    Dom.removeClass(Dom.getElementsByClassName('pack_it_button', 'td', 'pack_it_buttons_all'), 'selected');
    Dom.addClass(o, 'selected');
    dialog_other_staff.hide();
    Dom.get('staff_name_tr').style.display = '';
    Dom.get('pack_it_pin_tr').style.display = '';


    Dom.get('pack_it_Staff_Name').value = staff_alias;
    Dom.get('pack_it_staff_key').value = staff_key;

    Dom.setStyle('pack_it_pin_tr', 'visibility', 'visible');
    Dom.get("pack_it_pin_alias").innerHTML = staff_alias;
    Dom.get('pack_it_password').focus();
}

function assign_packer_save() {

    var staff_key = Dom.get('assign_packer_staff_key').value;
    var sup_pwd = Dom.get('assign_packer_sup_password').value;
    var dn_key = Dom.get('assign_packer_dn_key').value;


    var request = 'ar_edit_orders.php?tipo=' + Dom.get('assign_packer_dialog_type').value + '&dn_key=' + escape(dn_key) + '&staff_key=' + escape(staff_key) + '&pin=' + escape(sup_pwd);
    //alert(request)
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                if (r.action = 'updated' && Dom.get('operations' + dn_key)) {
                    Dom.get('operations' + dn_key).innerHTML = r.operations;
                    Dom.get('dn_state' + dn_key).innerHTML = r.dn_state;
                }
                close_dialog('assign_packer_dialog');

                if (!Dom.get('operations' + dn_key)) {
                    location.href = 'order_pack_aid.php?id=' + dn_key;
                }

            } else {
                alert(r.msg);
            }
        }
    });

}

function pack_it_save() {

    var staff_key = Dom.get('pack_it_staff_key').value;
    var sup_pwd = Dom.get('pack_it_sup_password').value;
    var dn_key = Dom.get('pack_it_dn_key').value;
    var request = 'ar_edit_orders.php?tipo=pack_it&dn_key=' + escape(dn_key) + '&staff_key=' + escape(staff_key) + '&pin=' + escape(sup_pwd);

    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                if (r.action = 'updated') {
                    location.href = 'order_pack_aid.php?id=' + dn_key;
                }
                close_dialog('pack_it_dialog');

            } else {
                alert(r.msg);
            }
        }
    });

}





function approve_packing(dn_key,staff_key, referrer) {
   
    if (Dom.get('approve_packing_img_' + dn_key) != undefined)
    Dom.get('approve_packing_img_' + dn_key).src = 'art/loading.gif';

    ar_file = 'ar_edit_orders.php';
    request = ar_file + '?tipo=approve_packing&dn_key=' + dn_key;
alert(request)

YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                if (referrer == 'warehouse_orders') {
                    Dom.get('operations_container' + r.dn_key).innerHTML = r.operations;
                    Dom.get('dn_state' + r.dn_key).innerHTML = r.dn_state;
                } else if (referrer == 'dn') {
                    window.location = 'dn.php?id=' + r.dn_key;
                }else if (referrer == 'pack_aid') {
					Dom.setStyle('approve_packing','display','none')
					Dom.get('dn_formated_state').innerHTML=r.dn_formated_state
                }


            }
        },
        failure: function(o) {
            alert(o.statusText);
        },
        scope: this
    });
}

function set_as_dispatched(dn_key, staff_key,referrer) {
  if( Dom.get('set_as_dispatched_img_'+dn_key) != undefined)
		Dom.get('set_as_dispatched_img_'+dn_key).src = 'art/loading.gif';

    ar_file = 'ar_edit_orders.php';
    request = ar_file + '?tipo=set_as_dispatched_dn&dn_key=' + dn_key+'&staff_key='+staff_key;
    //alert(request)
    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
          //  alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
              	
                if (referrer == 'warehouse_orders') {
                    Dom.get('operations_container' + r.dn_key).innerHTML = r.operations;
                    Dom.get('dn_state' + r.dn_key).innerHTML = r.dn_state;
                    get_warehouse_orders_numbers('','')

                } else if (referrer == 'dn') {
                    window.location = 'dn.php?id=' + r.dn_key;
                }
              	
            }

        },
        failure: function(o) {
            alert(o.statusText);
        },
        scope: this
    });
}

function pack_all(dn_key,staff_key,referrer) {

  if( Dom.get('pack_all_img_'+dn_key) != undefined)
		Dom.get('pack_all_img_'+dn_key).src = 'art/loading.gif';


    ar_file = 'ar_edit_orders.php';
    request = ar_file + '?tipo=set_packing_aid_sheet_pending_as_packed&dn_key=' + dn_key +'&warehouse_key='+Dom.get('warehouse_key').value+'&staff_key='+staff_key;
    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {
            
                if (referrer == 'warehouse_orders') {
                    Dom.get('operations_container' + r.dn_key).innerHTML = r.operations;
                    Dom.get('dn_state' + r.dn_key).innerHTML = r.dn_state;
                } else if (referrer == 'dn') {
                    window.location = 'dn.php?id=' + r.dn_key;
                } else if (referrer == 'pack_aid') {
                	window.location = 'order_pack_aid.php?id=' + r.dn_key;
                }            
            }

        },
        failure: function(o) {
            alert(o.statusText);
        },
        scope: this
    });
}



function pick_all(dn_key,staff_key,referrer) {


    if( Dom.get('pick_all_img_'+dn_key) != undefined)
		Dom.get('pick_all_img_'+dn_key).src = 'art/loading.gif';

    ar_file = 'ar_edit_orders.php';
    request = ar_file + '?tipo=set_picking_aid_sheet_pending_as_picked&dn_key=' + dn_key+'&staff_key='+staff_key;
   // alert(ar_file+request);return
    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

			if (referrer == 'pick_aid') {
                	window.location = 'order_pick_aid.php?id=' + r.dn_key;
            }         

               

            }

        },
        failure: function(o) {
            alert(o.statusText);
        },
        scope: this
    });


}




function approve_dispatching(dn_key, staff_key,referrer) {
  if( Dom.get('approve_dispatching_img_'+dn_key) != undefined)
		Dom.get('approve_dispatching_img_'+dn_key).src = 'art/loading.gif';

    ar_file = 'ar_edit_orders.php';
    request = ar_file + '?tipo=approve_dispatching_dn&dn_key=' + dn_key+'&staff_key='+staff_key;
    //alert(request)
    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
               
                 if (referrer == 'warehouse_orders') {
                    Dom.get('operations_container' + r.dn_key).innerHTML = r.operations;
                    Dom.get('dn_state' + r.dn_key).innerHTML = r.dn_state;
                } else if (referrer == 'dn') {
                    window.location = 'dn.php?id=' + r.dn_key;
                }
               
               
            }

        },
        failure: function(o) {
            alert(o.statusText);
        },
        scope: this
    });
}



function assign_packer(o, dn_key) {
  Dom.setStyle('assign_packer_dialog','display','')

    region1 = Dom.getRegion(o);
    region2 = Dom.getRegion('assign_packer_dialog');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('assign_packer_dialog', pos);
    
    Dom.get('Assign_Packer_Staff_Name').focus();
    Dom.get('assign_packer_dn_key').value = dn_key;
    Dom.get('staff_list_parent_dialog').value = 'assign_packer';
    assign_packer_dialog.show();
}

function pack_it(o, dn_key) {
Dom.setStyle('pack_it_dialog','display','')
    region1 = Dom.getRegion(o);
    region2 = Dom.getRegion('pack_it_dialog');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('pack_it_dialog', pos);

    Dom.get('pack_it_Staff_Name').focus();
    Dom.get('pack_it_dn_key').value = dn_key;
    Dom.get('staff_list_parent_dialog').value = 'pack_it';
    pack_it_dialog.show();
}

function show_other_staff(o) {
/*
var staff_alias='';
var staff_key='';

Dom.get('pick_it_Staff_Name').value=staff_alias;
Dom.get('pick_it_staff_key').value=staff_key;
Dom.get("pick_it_pin").innerHTML=staff_alias;
Dom.get('pack_it_Staff_Name').value=staff_alias;
Dom.get('pack_it_staff_key').value=staff_key;
Dom.get("pack_it_pin_alias").innerHTML=staff_alias;
*/

    //Dom.removeClass(Dom.getElementsByClassName('pack_it_button', 'td', 'pack_it_buttons'),'selected');
    //Dom.removeClass(Dom.getElementsByClassName('pick_it_button', 'td', 'pick_it_buttons'),'selected');
    //Dom.removeClass(Dom.getElementsByClassName('assign_picker_button', 'td', 'assign_picker_buttons'),'selected');
    //Dom.removeClass(Dom.getElementsByClassName('assign_packer_button', 'td', 'assign_packer_buttons '),'selected');
    //Dom.addClass(o,'selected');

    region1 = Dom.getRegion(o);
    region2 = Dom.getRegion('dialog_other_staff');

    var pos = [region1.right - region2.width, region1.bottom]

    Dom.setXY('dialog_other_staff', pos);

    dialog_other_staff.show();


    if (o.getAttribute('td_id') == 'other_staff_picker') {
        Dom.get('staff_list_parent_dialog').value = 'assign_picker';
    }

    if (o.getAttribute('td_id') == 'other_staff_packer') {
        Dom.get('staff_list_parent_dialog').value = 'assign_packer';
    }


}



function select_staff_from_list(oArgs) {

    //alert(tables.table2)
    
    //alert(Dom.get('staff_list_parent_dialog').value);
    var staff_alias = tables.table2.getRecord(oArgs.target).getData('code');
    var staff_key = tables.table2.getRecord(oArgs.target).getData('key');
    //alert(staff_alias + ':' + staff_key )
    
      

    
    switch (Dom.get('staff_list_parent_dialog').value) {
    case 'pick_it':
        Dom.get('pick_it_Staff_Name').value = staff_alias;
        Dom.get('pick_it_staff_key').value = staff_key;

        Dom.setStyle('pick_it_pin_tr', 'display', '');
        
        
        Dom.get("pick_it_pin").innerHTML = staff_alias;
        Dom.get('pick_it_pin').focus();
        break;
    case 'pack_it':
        Dom.get('pack_it_Staff_Name').value = staff_alias;
        Dom.get('pack_it_staff_key').value = staff_key;

        Dom.setStyle('pack_it_Staff_Name_tr', 'display', '');
        Dom.get("pack_it_Staff_Name_label").innerHTML = staff_alias;

        Dom.setStyle('pack_it_pin_tr', 'display', '');
        Dom.removeClass('pack_it_save','disabled')
        
        
      //  Dom.get("pack_it_pin_alias").innerHTML = staff_alias;
      //  Dom.get('pack_it_sup_password').focus();
        break;
    case 'assign_picker':
        Dom.removeClass(Dom.getElementsByClassName('assign_picker_button', 'td', 'assign_picker_buttons'), 'selected');
        Dom.addClass('picker_show_other_staff', 'selected')
        Dom.get('Assign_Picker_Staff_Name').value = staff_alias;
        Dom.get('assign_picker_staff_key').value = staff_key;
        Dom.get('assign_picker_sup_password').focus();
                Dom.setStyle('Assign_Picker_Staff_Name_tr','display', '')
       // Dom.get('Assign_Picker_Staff_Name_label').innerHTML = staff_alias;

        break;
    case 'assign_packer':
  
        Dom.removeClass(Dom.getElementsByClassName('assign_packer_button', 'td', 'assign_packer_buttons'), 'selected');
        Dom.addClass('packer_show_other_staff', 'selected')

        Dom.get('Assign_Packer_Staff_Name').value = staff_alias;
        Dom.get('assign_packer_staff_key').value = staff_key;
        Dom.get('assign_packer_sup_password').focus();
                Dom.setStyle('Assign_Packer_Staff_Name_tr','display', '')
                    

       // Dom.get('Assign_Packer_Staff_Name_label').innerHTML = staff_alias;
      //  alert("x2")
        Dom.removeClass('pack_it_save','disabled')
//  alert("x")
        break;

    }






    dialog_other_staff.hide();
}
