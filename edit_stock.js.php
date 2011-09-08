var audit_dialog;
var add_stock_dialog;

var Editor_lost_items;
var  Editor_move_items;




function over_can_pick(o){

if(o.getAttribute('can_pick')=='No')
o.src="art/icons/box.png";
else
o.src="art/icons/basket.png";

}

function out_can_pick(o){

if(o.getAttribute('can_pick')=='No')
o.src="art/icons/basket.png";
else
o.src="art/icons/box.png";

}




function set_all_lost() {
    Dom.get('qty_lost').value=Dom.get('lost_max_value').innerHTML;
    Dom.get('lost_why').focus();
}
function save_lost_items() {
    var data=new Object();
    data['qty']=Dom.get('qty_lost').value;
    data['why']=Dom.get('lost_why').value;
    data['action']=Dom.get('lost_action').value;

    data['location_key']=Dom.get('lost_location_key').value
                         data['part_sku']=Dom.get('lost_sku').value;
    location_key=Dom.get('lost_location_key').value;
    sku=Dom.get('lost_sku').value;
    var json_value = YAHOO.lang.JSON.stringify(data);
    var request='ar_edit_warehouse.php?tipo=lost_stock&values=' + my_encodeURIComponent(json_value);


    YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
            //alert(o.responseText);
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.action=='ok') {


                close_lost_dialog();


                Dom.get('part_location_quantity_'+sku+'_'+location_key).setAttribute('quantity',r.qty);
                Dom.get('part_location_quantity_'+sku+'_'+location_key).innerHTML=r.formated_qty;
                if (r.qty<=0) {
                    Dom.get("part_location_lost_items_"+sku+"_"+location_key).style.display='none';
                } else {
                    Dom.get("part_location_lost_items_"+sku+"_"+location_key).style.display='';

                }
                
                
               
                if (r.qty==0) {
                    Dom.get("part_location_delete_"+sku+"_"+location_key).style.display='';

                } else {
                    Dom.get("part_location_delete_"+sku+"_"+location_key).style.display='none';

                }



                
                
                
                
                
                if (r.stock==0) {
                    Dom.get("part_location_move_items_"+sku+"_"+location_key).style.display='none';

                } else {

                    Dom.get("part_location_move_items_"+sku+"_"+location_key).style.display='';
                }

           //     if (Dom.get('stock').innerHTML!=undefined)
                    Dom.get('stock').innerHTML=r.stock;




table_id=1
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];

    var request='&tableid='+table_id;
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);     


            } else if (r.action=='error') {
                alert(r.msg);
            }



        }
    });

}
function audit(sku,location_key) {
    Dom.get("audit_location_key").value=location_key;
    Dom.get("audit_sku").value=sku;

    var pos = Dom.getXY('part_location_audit_'+sku+'_'+location_key);
  
  pos[0]=pos[0]-280
  audit_dialog.show();
    Dom.setXY('Editor_audit', pos);
Dom.get('qty_audit').focus();

}
function lost(sku,location_key) {

    qty=Dom.get('part_location_quantity_'+sku+'_'+location_key).getAttribute('quantity');
    Dom.get('lost_max_value').innerHTML=qty;
    Dom.get('lost_sku').value=sku;
    Dom.get('lost_location_key').value=location_key;


    x= Dom.getX('part_location_lost_items_'+sku+'_'+location_key);
    y= Dom.getY('part_location_lost_items_'+sku+'_'+location_key);

    Dom.setX('Editor_lost_items', x-28);
    Dom.setY('Editor_lost_items', y-4);
    Dom.get('qty_lost').focus();
    Editor_lost_items.show();
}


function add_stock_part_location(sku,location_key) {



    Dom.get("add_stock_location_key").value=location_key;
    Dom.get("add_stock_sku").value=sku;

    var pos = Dom.getXY('part_location_add_stock_'+sku+'_'+location_key);
  
  pos[0]=pos[0]-260
  add_stock_dialog.show();
    Dom.setXY('Editor_add_stock', pos);
Dom.get('qty_add_stock').focus();

}


function delete_part_location(sku,location_key) {

    ar_file='ar_edit_warehouse.php';
    YAHOO.util.Connect.asyncRequest(
        'GET',
    ar_file+'?tipo=delete_part_location&part_sku='+sku+'&location_key='+location_key, {
success: function (o) {

            if (o.responseText == 'Ok') {
                Dom.get('part_location_tr_'+sku+'_'+location_key).parentNode.removeChild(Dom.get('part_location_tr_'+sku+'_'+location_key));




            } else {
                alert(o.responseText);
            }
        },
failure: function (o) {
            alert(o.statusText);
        },
scope:this
    }
    );


}

function add_location(sku){
  Dom.get('add_location_sku').value=sku;
   x= Dom.getX('add_location_button');
   y= Dom.getY('add_location_button');
   Dom.setX('Editor_add_location', x);
   Dom.setY('Editor_add_location', y);
   Dom.get('add_location_input').focus();
   Editor_add_location.show();
}

function move(sku,location_key) {

    part_location_element=Dom.get('part_location_move_items_'+sku+'_'+location_key);

    qty=Dom.get('part_location_quantity_'+sku+'_'+location_key).getAttribute('quantity');
    Dom.get('move_sku').value=sku;
    Dom.get('move_sku_formated').innerHTML=Dom.get('part_location_move_items_'+sku+'_'+location_key).getAttribute('sku_formated');
    Dom.get('this_location').innerHTML=Dom.get('part_location_move_items_'+sku+'_'+location_key).getAttribute('location');
    Dom.get('move_stock_left').innerHTML=qty;
    Dom.get('move_stock_left').setAttribute('ovalue',qty);
    Dom.get('move_this_location_key').value=location_key;


    if (qty==0) {
        Dom.get('flow').setAttribute('flow','left');
        Dom.get('flow').innerHTML='<img src="art/icons/arrow_left.png"/>';
    }



    x= Dom.getX('part_location_move_items_'+sku+'_'+location_key);
    y= Dom.getY('part_location_move_items_'+sku+'_'+location_key);


    Dom.setX('Editor_move_items', x-46);
    Dom.setY('Editor_move_items', y-4);
 Dom.get('location_move_to_input').focus();

    Editor_move_items.show();

}

function save_add_stock() {


  var data=new Object();
    data['qty']=Dom.get('qty_add_stock').value;
    data['note']=Dom.get('note_add_stock').value;
    data['location_key']=Dom.get('add_stock_location_key').value
    data['part_sku']=Dom.get('add_stock_sku').value;
    
    var json_value = YAHOO.lang.JSON.stringify(data);
    var request='ar_edit_warehouse.php?tipo=add_stock&values=' + my_encodeURIComponent(json_value);

   YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
            alert(o.responseText);
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                  Dom.get('part_location_quantity_'+r.sku+'_'+r.location_key).setAttribute('quantity',r.qty);
                Dom.get('part_location_quantity_'+r.sku+'_'+r.location_key).innerHTML=r.formated_qty;
                     if (r.newvalue<=0) {
                    Dom.get("part_location_lost_items_"+r.sku+"_"+r.location_key).style.display='none';
                } else {
                    Dom.get("part_location_lost_items_"+r.sku+"_"+r.location_key).style.display='';

                }

                if (r.newvalue==0) {
                    Dom.get("part_location_delete_"+r.sku+"_"+r.location_key).style.display='';

                } else {
                    Dom.get("part_location_delete_"+r.sku+"_"+r.location_key).style.display='none';

                }



                if (r.stock==0) {
                    Dom.get("part_location_move_items_"+r.sku+"_"+r.location_key).style.display='none';

                } else {

                    Dom.get("part_location_move_items_"+r.sku+"_"+r.location_key).style.display='';
                }


              //  if (Dom.get('stock').innerHTML!=undefined)
               //   alert(r.stock)
                  Dom.get('stock').innerHTML=r.stock;

                 close_add_stock_dialog();
table_id=1
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];

    var request='&tableid='+table_id;
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);     



            } else {
                alert(r.msg);
                callback();
            }
        },
failure:function(o) {
            alert(o.statusText);
            callback();
        },
scope:this
    },
    request

    );

}


function save_audit() {


  var data=new Object();
    data['qty']=Dom.get('qty_audit').value;
    data['note']=Dom.get('note_audit').value;
    data['location_key']=Dom.get('audit_location_key').value
    data['part_sku']=Dom.get('audit_sku').value;
    
    sku=Dom.get('lost_sku').value;
    var json_value = YAHOO.lang.JSON.stringify(data);
    var request='ar_edit_warehouse.php?tipo=audit_stock&values=' + my_encodeURIComponent(json_value);

   YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
            //alert(o.responseText);
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                  Dom.get('part_location_quantity_'+r.sku+'_'+r.location_key).setAttribute('quantity',r.qty);
                Dom.get('part_location_quantity_'+r.sku+'_'+r.location_key).innerHTML=r.formated_qty;
                     if (r.newvalue<=0) {
                    Dom.get("part_location_lost_items_"+r.sku+"_"+r.location_key).style.display='none';
                } else {
                    Dom.get("part_location_lost_items_"+r.sku+"_"+r.location_key).style.display='';

                }

                if (r.newvalue==0) {
                    Dom.get("part_location_delete_"+r.sku+"_"+r.location_key).style.display='';

                } else {
                    Dom.get("part_location_delete_"+r.sku+"_"+r.location_key).style.display='none';

                }



                if (r.stock==0) {
                    Dom.get("part_location_move_items_"+r.sku+"_"+r.location_key).style.display='none';

                } else {

                    Dom.get("part_location_move_items_"+r.sku+"_"+r.location_key).style.display='';
                }


              //  if (Dom.get('stock').innerHTML!=undefined)
               //   alert(r.stock)
                  Dom.get('stock').innerHTML=r.stock;

                 close_audit_dialog();
table_id=1
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];

    var request='&tableid='+table_id;
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);     



            } else {
                alert(r.msg);
                callback();
            }
        },
failure:function(o) {
            alert(o.statusText);
            callback();
        },
scope:this
    },
    request

    );

}
function close_audit_dialog() {
    Dom.get('qty_audit').value='';
    Dom.get('note_audit').value='';
    audit_dialog.hide();
}

function close_add_stock_dialog() {
    Dom.get('qty_add_stock').value='';
    Dom.get('note_add_stock').value='';
    add_stock_dialog.hide();
}

function close_lost_dialog() {
    Dom.get('qty_lost').value='';
    Dom.get('lost_why').value='';
    Dom.get('lost_action').value='';

    Editor_lost_items.cfg.setProperty('visible',false);
}

function create_part_location_tr(tag,r) {



    var sku=r.sku;
    var formated_sku=r._formated_sku;
    if (tag=='from') {
        var location_key=r.location_key_from;
        var location_code=r.location_code_from;
        var formated_qty=r.formated_qty_from
        var qty=r.qty_from;
    } else if(tag=='to'){
        var location_key=r.location_key_to;
        var location_code=r.location_code_to;
        var formated_qty=r.formated_qty_to;
           var qty=r.qty_to;
    } else{
        var location_key=r.location_key;
        var location_code=r.location_code;
        var formated_qty=r.formated_qty;
       var qty=r.qty;
    }



    oTbl=Dom.get('part_locations');
    oTR= oTbl.insertRow(-1);
    oTR.id='part_location_tr_'+sku+'_'+location_key;

    var oTD= oTR.insertCell(0);
    oTD.innerHTML=  '<a href="location.php?id='+location_key+'">'+location_code+'</a>';


    var oTD= oTR.insertCell(1);
    Dom.addClass(oTD,'quantity');
    oTD.id='part_location_quantity_'+sku+'_'+location_key;
    oTD.setAttribute('quantity',qty);
    oTD.innerHTML=formated_qty;

    var oTD= oTR.insertCell(2);
    Dom.addClass(oTD,'button');
    oTD.innerHTML='<img  id="part_location_audit_'+sku+'_'+location_key+'" src="art/icons/note_edit.png"  title="<?php echo _('audit')?>" alt="<?php echo _('audit')?>" onClick="audit('+sku+','+location_key+')" />';

    var oTD= oTR.insertCell(3);
    Dom.addClass(oTD,'button');
    oTD.innerHTML='<img  sku_formated="'+formated_sku+'" location="'+location_code+'" id="part_location_delete_'+sku+'_'+location_key+'"  id="part_location_delete_'+sku+'_'+location_key+'" src="art/icons/cross_bw.png" title="<?php echo _('delete')?>"  alt="<?php echo _('delete')?>" onClick="delete_part_location('+sku+','+location_key+')" /><img id="part_location_lost_items_'+sku+'_'+location_key+'" src="art/icons/package_delete.png" alt="{t}lost{/t}" onClick="lost('+sku+','+location_key+')" />';

    var oTD= oTR.insertCell(4);
    Dom.addClass(oTD,'button');
    oTD.innerHTML='<img sku_formated="'+formated_sku+'" location="'+location_code+'" id="part_location_move_items_'+sku+'_'+location_key+'"  src="art/icons/package_go.png" alt="{t}move{/t}" onClick="move('+sku+','+location_key+')" />';



}

function save_can_pick(sku,location_key){

   ar_file='ar_edit_warehouse.php';
   
   request=ar_file+'?tipo=part_location_update_can_pick&sku='+sku+'&location_key='+location_key+'&can_pick='+Dom.get('part_location_can_pick_'+sku+'_'+location_key).getAttribute('can_pick');
  
   
    YAHOO.util.Connect.asyncRequest(
        'GET',
    request, {
success: function (o) {
//alert(o.responseText)
var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
            
            
       
           if(r.can_pick=='Yes'){
                Dom.get('part_location_can_pick_'+r.sku+'_'+r.location_key).setAttribute('can_pick','No');
             Dom.get('part_location_can_pick_'+r.sku+'_'+r.location_key).src="art/icons/basket.png";
            }else{
                         Dom.get('part_location_can_pick_'+r.sku+'_'+r.location_key).src="art/icons/box.png";

                            Dom.get('part_location_can_pick_'+r.sku+'_'+r.location_key).setAttribute('can_pick','Yes');

            }
            
            }
           
        },
failure: function (o) {
            alert(o.statusText);
        },
scope:this
    }
    );

}


function save_move_items() {

    var data=new Object();
    data['qty']=Dom.get('move_qty').value;
    data['part_sku']=Dom.get('move_sku').value;
    sku=Dom.get('move_sku').value;
    if (Dom.get('flow').getAttribute('flow')=='right') {
        data['from_key']=Dom.get('move_this_location_key').value;
        data['to_key']=Dom.get('move_other_location_key').value;
    } else {
        data['from_key']=Dom.get('move_other_location_key').value;
        data['to_key']=Dom.get('move_this_location_key').value;
    }

    var json_value = YAHOO.lang.JSON.stringify(data);
    var request='ar_edit_warehouse.php?tipo=move_stock&values=' + encodeURIComponent(json_value);

    YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
            //alert(o.responseText);
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.action=='ok') {
                close_lost_dialog();



                if (Dom.get('part_location_quantity_'+sku+'_'+r.location_key_from)==undefined) {
                    create_part_location_tr('from',r);
                }
                //alert(Dom.get('part_location_quantity_'+sku+'_'+r.location_key_to))

                if (Dom.get('part_location_quantity_'+sku+'_'+r.location_key_to)==undefined) {
                    create_part_location_tr('to',r);
                }
  Dom.get('part_location_quantity_'+sku+'_'+r.location_key_from).setAttribute('quantity',r.qty_from);
                Dom.get('part_location_quantity_'+sku+'_'+r.location_key_from).innerHTML=r.formated_qty_from;
                 Dom.get('part_location_quantity_'+sku+'_'+r.location_key_to).setAttribute('quantity',r.qty_to);
                Dom.get('part_location_quantity_'+sku+'_'+r.location_key_to).innerHTML=r.formated_qty_to;
                
                

                Dom.get("part_location_quantity_"+sku+"_"+r.location_key_from).innerHTML=r.formated_qty_from;
                if (r.qty_from<=0) {
                    Dom.get("part_location_lost_items_"+sku+"_"+r.location_key_from).style.display='none';
                } else {
                    Dom.get("part_location_lost_items_"+sku+"_"+r.location_key_from).style.display='';

                }

                if (r.qty_from==0) {
                    Dom.get("part_location_delete_"+sku+"_"+r.location_key_from).style.display='';

                } else {
                    Dom.get("part_location_delete_"+sku+"_"+r.location_key_from).style.display='none';

                }

         Dom.get("part_location_quantity_"+sku+"_"+r.location_key_to).innerHTML=r.formated_qty_to;
                if (r.qty_to<=0) {
                    Dom.get("part_location_lost_items_"+sku+"_"+r.location_key_to).style.display='none';
                } else {
                    Dom.get("part_location_lost_items_"+sku+"_"+r.location_key_to).style.display='';

                }

                if (r.qty_to==0) {
                    Dom.get("part_location_delete_"+sku+"_"+r.location_key_to).style.display='';

                } else {
                    Dom.get("part_location_delete_"+sku+"_"+r.location_key_to).style.display='none';

                }






                 close_move_dialog();
table_id=1
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];

    var request='&tableid='+table_id;
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);     


              

               



            } else if (r.action=='error') {
                alert(r.msg);
            }



        }
    });




}




function move_stock_right() {
    if (isNaN(parseFloat(Dom.get("move_stock_right").getAttribute('ovalue')))) {
        return;
    }
    var qty_left=Dom.get("move_stock_left").innerHTML;
    if (qty_left>0) {
        var _qty_change=Dom.get('move_qty').value;
        if (_qty_change=='')_qty_change=0;
        var qty_change=parseFloat(_qty_change+' '+qty_change);


        qty_change=qty_change+1;
        Dom.get('move_qty').value=qty_change;
        move_qty_changed();
    }
}
function move_stock_left() {

    if (isNaN(parseFloat(Dom.get("move_stock_left").getAttribute('ovalue')))) {
        return;
    }

    var qty_right=Dom.get("move_stock_right").innerHTML;
    if (qty_right>0) {
        var _qty_change=Dom.get('move_qty').value;
        if (_qty_change=='')_qty_change=0;
        var qty_change=parseFloat(_qty_change+' '+qty_change);
        qty_change=qty_change+1;
        Dom.get('move_qty').value=qty_change;
        move_qty_changed();
    }
}
function move_qty_changed() {
    var _qty_change=Dom.get('move_qty').value;
    if (_qty_change=='')_qty_change=0;
    var qty_change=parseFloat(_qty_change+' '+qty_change);

    if (isNaN(qty_change))
        return;

    if (qty_change<0) {
        Dom.addClass('move_qty','error');

        return;
    } else
        Dom.removeClass('move_qty','error');

    left_old_value=parseFloat(Dom.get("move_stock_left").getAttribute('ovalue'));
    right_old_value=parseFloat(Dom.get("move_stock_right").getAttribute('ovalue'));

    if (Dom.get('flow').getAttribute('flow')=='right') {
        if (left_old_value < qty_change) {
            Dom.addClass('move_qty','error');
            qty_change=left_old_value;
        } else
            Dom.removeClass('move_qty','error');
        left_value=left_old_value-qty_change;
        right_value=right_old_value+qty_change;
    } else {
        if (right_old_value < qty_change) {
            Dom.addClass('move_qty','error');
            qty_change=right_old_value;
        } else
            Dom.removeClass('move_qty','error');
        left_value=left_old_value+qty_change;
        right_value=right_old_value-qty_change;


    }

    Dom.get("move_stock_left").innerHTML=left_value;
    Dom.get("move_stock_right").innerHTML=right_value;



}
function close_add_location_dialog() {
    Dom.get('move_stock_right').innerHTML='';
    
    Editor_add_location.cfg.setProperty('visible',false);
}

function close_move_dialog() {
    Dom.get('move_stock_right').innerHTML='';
    Dom.get('move_qty').value='';
    Dom.get('location_move_to_input').value='';

    Dom.get('flow').setAttribute('flow','right');

    Dom.get('flow').innerHTML='<img src="art/icons/arrow_right.png"/>';





    Editor_move_items.cfg.setProperty('visible',false);
}


function location_move_to_selected(sType, aArgs) {

    var locData= aArgs[2];
    var data = {
"location_code":
        locData[0]
,"location_key":
        locData[1]
,"stock":
        locData[2]
    };
    Dom.get('move_stock_right').innerHTML=data['stock'];
    Dom.get('move_stock_right').setAttribute('ovalue',data['stock']);
    Dom.get('move_other_location_key').value=data['location_key'];
    Dom.get('move_qty').value='';
    move_qty_changed();
};
function add_location_selected(sType, aArgs) {

    var locData= aArgs[2];
    var data = {
"location_code":
        locData[0]
,"location_key":
        locData[1]
,"stock":
        locData[2]
    };
   
   var sku=Dom.get('add_location_sku').value;
   
     var json_value = YAHOO.lang.JSON.stringify(data);
    var request='ar_edit_warehouse.php?tipo=add_part_to_location&location_key='+data.location_key+'&part_sku='+sku; 

    YAHOO.util.Connect.asyncRequest('POST',request , {
success:function(o) {
           // alert(o.responseText);
            var r =  YAHOO.lang.JSON.parse(o.responseText);
            if (r.action=='added') {
                close_add_location_dialog();
                if (Dom.get('part_location_quantity_'+sku+'_'+r.location_key)==undefined) {
                    create_part_location_tr('',r);
                }

               Dom.get('part_location_quantity_'+sku+'_'+r.location_key).setAttribute('quantity',r.qty);
                Dom.get('part_location_quantity_'+sku+'_'+r.location_key).innerHTML=r.formated_qty;
                if (r.qty<=0) {
                    Dom.get("part_location_lost_items_"+sku+"_"+r.location_key).style.display='none';
                } else {
                    Dom.get("part_location_lost_items_"+sku+"_"+r.location_key).style.display='';

                }

                if (r.qty==0) {
                    Dom.get("part_location_delete_"+sku+"_"+r.location_key).style.display='';

                } else {
                    Dom.get("part_location_delete_"+sku+"_"+r.location_key).style.display='none';

                }

         






              

               



            } else if (r.action=='error') {
                alert(r.msg);
            } else if(r.action=='nochange'){
            close_add_location();
            }



        }
    });
   
   
   
   
};


function init() {

audit_dialog = new YAHOO.widget.Dialog("Editor_audit", {  visible : true,close:false,underlay: "none",draggable:false});
    audit_dialog.render();

add_stock_dialog = new YAHOO.widget.Dialog("Editor_add_stock", {  visible : true,close:false,underlay: "none",draggable:false});
    add_stock_dialog.render();


Editor_lost_items = new YAHOO.widget.Dialog("Editor_lost_items", {close:false,visible:false,draggable:false});
    Editor_lost_items.render();
Editor_move_items = new YAHOO.widget.Dialog("Editor_move_items", {close:false,visible:false,underlay: "none",draggable:false});
    Editor_move_items.render();


Editor_add_location = new YAHOO.widget.Dialog("Editor_add_location", {close:false,visible:true,underlay: "none",draggable:false});
    Editor_add_location.render();
    
// Editor_lost_items = new YAHOO.widget.Dialog("Editor_lost_items", {  visible : false,close:false,underlay: "none",draggable:false});
// Editor_lost_items.render();
// Editor_move_items = new YAHOO.widget.Dialog("Editor_move_items", {  visible : false,close:false,underlay: "none",draggable:false});
// Editor_move_items.render();
}

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("location_move_from", function () {

    var oDS = new YAHOO.util.XHRDataSource("ar_warehouse.php");
    oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
    oDS.responseSchema = {
resultsList : "data"
        ,
fields :
        ["code","key","stock"]
    };
    var oAC = new YAHOO.widget.AutoComplete("location_move_from_input", "location_move_from_container", oDS);
    oAC.generateRequest = function(sQuery) {

        var sku=Dom.get("move_sku").value
                //  var location_key=Dom.get("this_location_key").value
                return "?tipo=find_location&except_location="+location_key+"&get_data=sku"+sku+"&with=stock&query=" + sQuery ;
    };
    oAC.forceSelection = true;
    oAC.itemSelectEvent.subscribe(location_move_to_selected);



});

YAHOO.util.Event.onContentReady("location_move_to", function () {
    var oDS = new YAHOO.util.XHRDataSource("ar_warehouse.php");
    oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
    oDS.responseSchema = {
resultsList : "data"
        ,
fields :
        ["code","key","stock"]
    };
    var oAC = new YAHOO.widget.AutoComplete("location_move_to_input", "location_move_to_container", oDS);
    oAC.generateRequest = function(sQuery) {

        var sku=Dom.get("move_sku").value
                var location_key=Dom.get("move_this_location_key").value
                                 return "?tipo=find_location&except_location="+location_key+"&get_data=sku"+sku+"&query=" + sQuery ;
    };
    oAC.forceSelection = true;
    oAC.itemSelectEvent.subscribe(location_move_to_selected);
});

YAHOO.util.Event.onContentReady("add_location_input", function () {
  
  var new_loc_oDS = new YAHOO.util.XHRDataSource("ar_warehouse.php");
    new_loc_oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
    new_loc_oDS.responseSchema = {
resultsList : "data"
        ,
fields :
        ["code","key","stock"]
    };
    var new_loc_oAC = new YAHOO.widget.AutoComplete("add_location_input", "add_location_container", new_loc_oDS);
  
  
  new_loc_oAC.generateRequest = function(sQuery) {

        var sku=Dom.get("add_location_sku").value
            
      return "?tipo=find_location&except_part_location=1&get_data=sku"+sku+"&query=" + sQuery ;
    };
    new_loc_oAC.forceSelection = true;
    new_loc_oAC.itemSelectEvent.subscribe(add_location_selected);
    
});
