<?include_once('../common.php')?>

    var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
current_engine='';

jsonString='<?=$_SESSION['state']['product']['manage_stock_data'];?>';
try {
    var location_data = YAHOO.lang.JSON.parse(jsonString);
}
catch (e) {
    alert("ERROR:P_PMS_JSONDATA");
};
var deleting=0;
var swaping=0;
var new_parent='';

function exchange(i, j, tableID)
{
	var table = document.getElementByID('tableID');
	var trs = oTable.tBodies[0].getElementsByTagName("tr");
	
	if(i == j+1) {
		oTable.tBodies[0].insertBefore(trs[i], trs[j]);
	} else if(j == i+1) {
		oTable.tBodies[0].insertBefore(trs[j], trs[i]);
	} else {
		var tmpNode = oTable.tBodies[0].replaceChild(trs[i], trs[j]);
		if(typeof(trs[i]) != "undefined") {
			oTable.tBodies[0].insertBefore(tmpNode, trs[i]);
		} else {
			oTable.appendChild(tmpNode);
		}
	}
}

function moverow(row_index, position, tableID)
{
	var table = Dom.get(tableID);

	    
	var trs = table.tBodies[0].getElementsByTagName("tr");


	table.tBodies[0].insertBefore(trs[row_index], trs[position]);
	//	alert(row_index+' '+position);
	

// 	if(i == j+1) {
// 		oTable.tBodies[0].insertBefore(trs[i], trs[j]);
// 	} else if(j == i+1) {
// 		oTable.tBodies[0].insertBefore(trs[j], trs[i]);
// 	} else {
// 		var tmpNode = oTable.tBodies[0].replaceChild(trs[i], trs[j]);
// 		if(typeof(trs[i]) != "undefined") {
// 			oTable.tBodies[0].insertBefore(tmpNode, trs[i]);
// 		} else {
// 			oTable.appendChild(tmpNode);
// 		}
// 	}
}





var refresh= function(){
    new_parent='';

  for (key in location_data.data)
      {
	  var location= location_data.data[key];
	  id= location.location_id;
	  
	  if(location.picking_rank<2)
	      Dom.get("loc_picking_up"+id).style.display='none';
	  else
	      Dom.get("loc_picking_up"+id).style.display='';
		  

	  Dom.get("loc_name"+id).innerHTML=location.name;
	  Dom.get("loc_tipo"+id).innerHTML=location.tipo;
	  Dom.get("loc_stock_units"+id).innerHTML=location.stock_units;
	  Dom.get("loc_stock_outers"+id).innerHTML=location.stock_outers;
	  Dom.get("loc_picking_tipo"+id).innerHTML=' '+location.picking_tipo+' ';
	  
	  
	  if(location.is_physical==1){
	      Dom.get("loc_picking_img"+id).style.display='';
	  }else{
	      Dom.get("loc_picking_img"+id).style.display='none';
	  }
	      

	  if(location.can_pick==1){
	      Dom.get("loc_picking_img"+id).setAttribute('can_pick',1);
	      Dom.get("loc_picking_img"+id).src='art/icons/basket.png';
	  }else{
	      Dom.get("loc_picking_img"+id).src='art/icons/basket_delete.png';
	       Dom.get("loc_picking_img"+id).setAttribute('can_pick',0);
	  }
	  if(location.stock==0)
	      Dom.get("loc_del"+id).style.display='';
	  else
	       Dom.get("loc_del"+id).style.display='none';
      }

  Dom.get('total_stock_units').innerHTML=location_data.stock_units;
  Dom.get('total_stock_outers').innerHTML=location_data.stock_outers;
  if(location_data.has_unknown)
      Dom.get("identify_location").style.display='';
  else
      Dom.get("identify_location").style.display='none';

    //    alert(location_data.num_physical+'y '+location_data.has_physical+'x '+location_data.physical_with_stock);

  if(location_data.has_physical){
      Dom.get("change_stock").style.display='';
      Dom.get("change_location").style.display='';
      Dom.get("new_location").style.display='';
      
      if(location_data.num_physical>1){
	  Dom.get("move_stock").style.display='';

      }else
	  Dom.get("move_stock").style.display='none';
      //      alert(location_data.num_physical_with_stock)
      if(location_data.num_physical_with_stock>0)
	  Dom.get("move_stock").style.display='';
      else
	  Dom.get("move_stock").style.display='none';
      

  }else{
      Dom.get("change_stock").style.display='none';
      Dom.get("new_location").style.display='none';
      Dom.get("move_stock").style.display='none';
      Dom.get("damaged_stock").style.display='none';
      Dom.get("change_location").style.display='none';

  }



  var table=tables.table0;
  var datasource=tables.dataSource0;
  var request='&sf=0';
  datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
}


var location_selected= function(){

    if(current_engine=='new_location'){
	Dom.get("new_location_q1").style.display='';
    }
    else if(current_engine=='identify_location'){
	Dom.get("identify_location_save").style.display='';
    }else if(current_engine=='change_location'){

	Dom.get("change_location_correct").innerHTML=Dom.get("new_location_input").value;
	Dom.get("manage_stock_engine").style.display='';
    }
    
}

var clear_actions = function(){

    new_parent='';
    Dom.get('new_product_input').value='';

    if(deleting>0){
	Dom.get('row_'+deleting).style.background='#fff';
	Dom.get('loc_del'+deleting).style.visibility='visible';
    }
    if(swaping>0){
	Dom.get('loc_picking_img'+swaping).style.opacity=1;

    }


    Dom.get('manage_stock_messages').innerHTML='';
    Dom.get('manage_stock_engine').innerHTML='';
    Dom.get('manage_stock_desktop').style.display='none';
    Dom.get('manage_stock_locations').style.display='none';
    for (key in location_data.data)
	{
	    var location= location_data.data[key];
	    Event.removeListener("loc_name"+location_data.data[key].location_id, "click");
	    Dom.get("loc_name"+location_data.data[key].location_id).className='';
	}
    
    Dom.get('change_stock').className='';
        Dom.get('change_location').className='';

    Dom.get('move_stock').className='';
    Dom.get('damaged_stock').className='';
    Dom.get('new_location').className='';
    Dom.get('identify_location').className='';
    Dom.get('link_product').className='';
    
};

    
var new_location_save = function(){
    //alert('hola');
    var location_name=Dom.get("new_location_input").value;

    if(location_name=='')
	Dom.get('manage_stock_messages').innerHTML='<?=_('Select a location from the list')?>';
    var can_pick=Dom.get('new_location_can_pick').checked;
    var is_primary=Dom.get('new_location_is_primary').checked;

    var request='ar_assets.php?tipo=pml_new_location&location_name='+ escape(location_name)+'&can_pick='+escape(can_pick)+'&is_primary='+escape(is_primary);

    


   //  alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    Dom.get('new_location_input').value='';
		    var row = Dom.get('location_table').insertRow(r.where);
		    row.setAttribute("id", 'row_'+r.id );
		    row.setAttribute("pl_id", r.pl_id );
		    var cellLeft = row.insertCell(0);
		    cellLeft.setAttribute("id", 'loc_name'+r.id );
		    var cellLeft = row.insertCell(1);
		    cellLeft.setAttribute("id", 'loc_tipo'+r.id );
		    
		    var cellLeft = row.insertCell(2);
		    
		    var span = document.createElement("span");
		    span.innerHTML=' &uarr; ';
		    span.setAttribute("onclick", "rank_up("+r.id+")" );
		    span.setAttribute("style", "cursor:pointer" );
		    span.setAttribute("id", 'loc_picking_up'+r.id );
		    span.setAttribute("rank", +r.picking_rank );
		    span.style.cursor='pointer';
		    if(r.picking_rank<2 || r.picking_rank=='')
			span.style.display='none';
		    cellLeft.appendChild(span);
		    
		    var span = document.createElement("span");
		    span.setAttribute("id", 'loc_picking_tipo'+r.id );
		    cellLeft.appendChild(span);
		    
		    var img = document.createElement("img");
		    img.setAttribute("id", 'loc_picking_img'+r.id );
		    img.setAttribute("can_pick", r.can_pick );
		    img.setAttribute("onclick","swap_picking("+r.id+")"  );
		    img.setAttribute("src", "art/icons/basket.png" );
		    img.setAttribute("style", "position:relative;bottom:1px;vertical-align:bottom;cursor:pointer;" );
		    img.setAttribute("title", "" );

		    cellLeft.appendChild(img);
		    
		    cellLeft.setAttribute("id", 'loc_pick_info'+r.id );
		    cellLeft.style.textAlign='right';


		    var cellLeft = row.insertCell(3);
		    cellLeft.style.textAlign='right';
		    var span = document.createElement("span");
		    span.setAttribute("id", 'loc_stock_units'+r.id );
		    span.innerHTML='0';
		    cellLeft.appendChild(span);

		    var cellLeft = row.insertCell(4);
		    cellLeft.style.textAlign='right';
		    var span = document.createElement("span");
		    span.setAttribute("id", 'loc_stock_outers'+r.id );
		    span.innerHTML='0';
		    cellLeft.appendChild(span);
		    
		    var cellLeft = row.insertCell(5);
		    cellLeft.style.textAlign='right';
		    var span = document.createElement("span");
		    span.setAttribute("style", 'cursor:pointer' );
				    
		    span.setAttribute("id", 'loc_stock_max_units'+r.pl_id );

				    
		    span.setAttribute("onClick", "change_max_units_dialog(this,"+r.pl_id+",'"+location_name+"')");
		    span.innerHTML='<?=_('Not Set')?>';
		     cellLeft.appendChild(span);
		     
		    var cellLeft = row.insertCell(6);
		    var img = document.createElement("img");
		    img.setAttribute("src", "art/icons/cross.png" );
		    img.setAttribute("style", "cursor:pointer" );
		    img.setAttribute("title", "<?=_('Free the location')?>" );
		    img.setAttribute("id", 'loc_del'+r.id );
		    img.setAttribute("onclick", "desassociate_loc("+r.id+")" );
		     
		     cellLeft.appendChild(img);
		     location_data=r.data;
		     clear_actions();
		    
		    refresh();
		    
		}else
		    Dom.get('manage_stock_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});


	};


var new_location_q1_action=function(value){
		if(value==1){
		    Dom.get('new_location_q2').style.display='';
		    Dom.get('new_location_save').style.display=''
		}else{
		    	Dom.get('new_location_save').style.display=''
		}
	    }
	    
	var new_location=function(){
	    clear_actions();
	    
	    current_engine='new_location';
	    this.className='selected';
	    Dom.get('manage_stock_desktop').style.display='';
	    Dom.get('manage_stock_locations').style.display='';
	    Dom.get('manage_stock_engine').innerHTML='<table><tr id="new_location_q1" style="display:none;height:30px"><td><?=_('Can products be picked from here?')?></td><td>  <?=_('Yes')?> <input type="radio" onClick="new_location_q1_action(1)" name="can_pick" id="new_location_can_pick" value="yes" style="vertical-align:bottom"> </td><td> <?=_('No')?><input style="vertical-align:bottom" onClick="new_location_q1_action(0)" type="radio" name="can_pick"  value="no"></td></tr><tr  id="new_location_q2" style="display:none;height:30px" ><td><?=_('Is this the primary picking location?')?> </td><td><?=_('Yes')?> <input id="new_location_is_primary" type="radio" name="primary" value="yes" style="vertical-align:bottom"></td><td>  No<input style="vertical-align:bottom" type="radio" name="primary" checked="checked" value="no"> </td></tr><tr  id="new_location_save" style="display:none;height:30px"  ><td colspan="3" style="cursor:pointer" onclick="new_location_save()" class="aright"><?=_('Save changes')?> <img src="art/icons/disk.png"/></td></tr></table> '
	    Dom.get('manage_stock_messages').innerHTML='<?=_('New location code');?>:';
	    
	};

// Change stock

var change_stock_from=function(e,index){
    
    for (key in location_data.data)
	{
	    var location= location_data.data[key];
	    Event.removeListener("loc_name"+location_data.data[key].location_id, "click");
	    Dom.get("loc_name"+location_data.data[key].location_id).className='';
	}
    
    Dom.get('manage_stock_messages').innerHTML='<?=_('How many units are currently on the location?')?>'
    Dom.get('manage_stock_engine').innerHTML='<table><tr id="change_stock_qty" ><td><?=_('Number of units')?></td><td><input id="new_qty" location_id="'+location_data.data[index].id+'"  style="text-align:right;padding:0 3px" type="text"  size="3"  onkeyup="change_stock_ready()"   /> <span style="cursor:pointer" onclick="new_stock_none();">(<?=_('None')?>)</span> <span id="change_stock_continue" style="display:none;padding-left:20px;cursor:pointer;text-decoration:underline" onclick="change_stock_manage('+index+')"><?=_('Continue')?></span></td></tr><tr style="display:none" id="more_outers"><td colspan="3"><span id="more_change"></span> <span><?=_("Units")?></span>  <span id="more_change_save"  onclick="change_stock_save('+index+')" style="display:none;margin-left:30px;cursor:pointer"  > <?=_('Save')?> <img src="art/icons/disk.png" style="vertical-align:bottom"/></span>   <br> <?=_('Please try to explain why there is more units than there should be')?>. <b><?=_('If stock has been received plesase add it on')?> <a href="suppliers.php" style="cursor:pointer;text-decoration:underline" ><?=_("Suppliers Area")?></a><b>.</span></td></tr><tr style="display:none" id="less_outers"><td colspan="3"><span id="less_change"></span> <span><?=_('Units')?></span>  <span id="less_change_save"  onclick="change_stock_save('+index+')" style="display:none;margin-left:30px;cursor:pointer"  > <?=_('Save')?> <img src="art/icons/disk.png" style="vertical-align:bottom"/></span>  <br><?=_('Please give possible reasons of the lost stock ')?>. <b><?=_('If stock has been damaged')?> <span onclick="damaged_stock()" style="cursor:pointer;text-decoration:underline" ><?=_('click here')?></span><b>.</span></td></tr><tr id="change_stock_comments" style="display:none"  ><td><?=_('Explanation')?>:</td><td colspan="2"><textarea id="change_stock_why" onkeyup="change_stock_ready2()" ></textarea></td></tr></table>'};	  


var change_stock_ready=function(e){
    
    if(isNaN(Dom.get("new_qty").value) || Dom.get("new_qty").value<0 )
	Dom.get('change_stock_continue').style.display='none';
    else
	Dom.get('change_stock_continue').style.display='';
    
}

var change_stock_ready2=function(e){
   

  if(Dom.get('change_stock_why').value!=''){
	Dom.get('less_change_save').style.display='';
	Dom.get('more_change_save').style.display='';
    }else{
	Dom.get('less_change_save').style.display='none';
	Dom.get('more_change_save').style.display='none';

    }
    
}



var change_stock_manage=function(index){

    var new_stock=Dom.get("new_qty").value;
    var change=new_stock-location_data.data[index].stock;
    if(change==0){
	Dom.get("new_qty").value='';
	Dom.get('change_stock_continue').style.display='none';
	Dom.get('manage_stock_messages').innerHTML='<?=_('So nothing have change, how many units are currently on the location? ')?>';
	
    }else if(change<0){
	Dom.get('change_stock_qty').style.display='none';
	Dom.get('change_stock_continue').style.display='none';
	Dom.get('less_outers').style.display='';
	Dom.get('less_change').innerHTML=change;
	Dom.get('change_stock_comments').style.display='';
    }else{
	Dom.get('change_stock_qty').style.display='none';
	Dom.get('change_stock_continue').style.display='none';
	Dom.get('more_outers').style.display='';
	Dom.get('change_stock_comments').style.display='';
	Dom.get('more_change').innerHTML='+'+change;

    }

}

var new_stock_none=function(){
    Dom.get("new_qty").value=0;Dom.get('change_stock_continue').style.display='';
}

var change_stock=function(){
    clear_actions(current_engine);
    current_engine='change_stock';
    this.className='selected';
    Dom.get('manage_stock_desktop').style.display='';
    Dom.get('manage_stock_engine').innerHTML=''
    Dom.get('manage_stock_messages').innerHTML='<?=_('Click the location where you want to change the stock ');?>';
    
    
	    
    
    for (key in location_data.data){
	var location= location_data.data[key];
		if(location.is_physical){
		    Event.addListener("loc_name"+location_data.data[key].location_id, "click", change_stock_from,key);
		    Dom.get("loc_name"+location_data.data[key].location_id).className='selected';
		}
	    }
	    
	    

	};


var change_stock_save=function(location_id){
    //    alert(location_id);
    //var index=Dom.get('row_'+location_id).rowIndex;
    //var pl_id=Dom.get('row_'+location_id).getAttribute('pl_id');
    var msg=Dom.get('change_stock_why').value;
    var qty=Dom.get("new_qty").value;
    var request='ar_assets.php?tipo=pml_change_qty&qty='+ escape(qty)+'&id='+ escape(location_id)+'&msg='+ escape(msg);
    // alert(request);
    // return;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {

		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    

		    location_data=r.data;
		    clear_actions();
		    refresh();

		}else
		    Dom.get('manage_stock_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});
    
}






// Damaged Stock
	
var damaged_stock_save = function(index){
    var qty=Dom.get("damaged_stock_qty").value;
    var location_id=Dom.get('damaged_stock_qty').getAttribute('location_id');
    var message=Dom.get('damaged_stock_why').value;
    var request='ar_assets.php?tipo=pml_damaged_stock&from='+ escape(location_id)+'&qty='+escape(qty)+'&message='+escape(message);
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {

       var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    //update all stock figures (if were changed else were)
		    location_data=r.data;
		    clear_actions('damaged_stock')
			refresh();
		    
		}else
		    Dom.get('manage_stock_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});
};

var unlink=function(o){
    clear_actions();
    current_engine='link_product';
    o.className='selected';
    Dom.get('manage_stock_desktop').style.display='';
    Dom.get('manage_stock_engine').innerHTML='<span style="padding:5px 20px;cursor:pointer" onCLick="unlink_save()"><?=_('Yes')?></span> <span style="padding:5px 20px;cursor:pointer" onClick="clear_actions()"><?=_('No')?></span>';
    Dom.get('manage_stock_messages').innerHTML='<?=_('Are you sure the you want to unlink this product');?>';
}
var unlink_save=function(){
    var request='ar_assets.php?tipo=pml_unlink';
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    location.reload(true);
		}else{
		     Dom.get('manage_stock_messages').innerHTML='<span class="error">'+r.msg+'</span>';
		}
		    
	    }
	})

}


var link=function(o){
    clear_actions();
    current_engine='link_product';
    o.className='selected';
    Dom.get('manage_stock_desktop').style.display='';
    Dom.get('manage_stock_products').style.display='';
    Dom.get('manage_stock_engine').style.display='none';
    Dom.get('manage_stock_engine').innerHTML='<span style="padding:5px 20px;cursor:pointer" onCLick="link_save()"><?=_('Link')?></span> <span style="padding:5px 20px;cursor:pointer" onClick="clear_actions()"><?=_('Cancel')?></span>';
    Dom.get('manage_stock_messages').innerHTML='<?=_('Choose the product thet you want to link');?>';
};
var link_save=function(){
    var product_id=new_parent;
    var request='ar_assets.php?tipo=pml_link&product_id='+escape(product_id);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    location.reload(true);
		}else{
		     Dom.get('manage_stock_messages').innerHTML='<span class="error">'+r.msg+'</span>';
		}
		    
	    }
	})

}

var damaged_stock=function(){
    clear_actions(current_engine);
    current_engine='damaged_stock';
    this.className='selected';
    Dom.get('manage_stock_desktop').style.display='';
    Dom.get('manage_stock_engine').innerHTML=''
    Dom.get('manage_stock_messages').innerHTML='<?=_('Click the location were the stock was damaged ');?>';
    for (key in location_data.data)
	{
	    var location= location_data.data[key];
	    if(location.is_physical && location.has_stock){

		Event.addListener("loc_name"+location_data.data[key].location_id, "click", damaged_stock_from,key);
		Dom.get("loc_name"+location_data.data[key].location_id).className='selected';
	    }
		
	}
};


var damaged_stock_ready=function(){
    var qty=1*Dom.get("damaged_stock_qty").value;
    var max=Dom.get('damaged_stock_qty').getAttribute('max');
    var ok1=false;
    var ok2=false;

    if(Dom.get('damaged_stock_why').value!='')
	ok1=true;
    else{
	Dom.get('manage_stock_messages').innerHTML='<?=_('Please, try to explain why the stock where damaged and what are you are going to do with it')?>';
    }
    if(YAHOO.lang.isNumber(qty)){
	if(qty<0){
	    Dom.get('manage_stock_messages').innerHTML='<?=_('Do not give me negative numbers please')?>';
	    
	}else if(qty==0){
	    Dom.get('manage_stock_messages').innerHTML='<?=_('How many units were damaged?')?>';
	}else if(qty>max){
	    Dom.get('manage_stock_messages').innerHTML='<?=_('Only')?> '+max+' <?=_('units in location')?>';
	    
	}else{
	    ok2=true;
	}
    }else if(qty==''){
	Dom.get('manage_stock_messages').innerHTML='<?=_('How many units were damaged?')?>';
	
    }else{
	Dom.get('manage_stock_messages').innerHTML='<?=_('That is not a number, how many units were damaged?')?>';	
	
    }
    


    if(ok1 && ok2){
	 Dom.get('manage_stock_messages').innerHTML='<?=_('Save the changes please')?>';
	 Dom.get('damaged_stock_save').style.display='';
    }else
	Dom.get('damaged_stock_save').style.display='none';

};

var damaged_stock_from=function(e,index){
    Dom.get('manage_stock_messages').innerHTML='<?=_('How many outer were damaged')?>'
    Dom.get('manage_stock_engine').style.display='';



     Dom.get('manage_stock_engine').innerHTML='<table><td><?=_('Units damaged')?>: </td><td style="padding-right:20px" id="damaged_stock_td_qty"> <input id="damaged_stock_qty" location_id="'+location_data.data[index].id+'"  type="text" max="'+location_data.data[index].stock_units+'" size="3"  onkeyup="damaged_stock_ready()"   /> (<span style="cursor:pointer" onclick="damaged_stock_all();">'+location_data.data[index].stock_units+'</span> <?=_('max')?>)</td><td id="damaged_stock_save" onclick="damaged_stock_save()" style="cursor:pointer;display:none"><?=_('Save')?> <img src="art/icons/disk.png" style="vertical-align:bottom"/></td><tr><td><?=_('Comments')?>:</td><td colspan="2"><textarea id="damaged_stock_why" onkeyup="damaged_stock_ready()" ></textarea></td></tr></table>'

  for (key in location_data.data)
	{
	    var location= location_data.data[key];
	    Event.removeListener("loc_name"+location_data.data[key].location_id, "click");
	    Dom.get("loc_name"+location_data.data[key].location_id).className='';
	}


}

var damaged_stock_all=function(index){
    Dom.get('damaged_stock_qty').value=Dom.get('damaged_stock_qty').getAttribute('max');
    damaged_stock_ready();
};

// Desassociate location

var desassociate_loc_save=function(location_id){
    var index=Dom.get('row_'+location_id).rowIndex;
    var pl_id=Dom.get('row_'+location_id).getAttribute('pl_id');
    var msg='';
    if(location_id==1)
	msg=Dom.get('desassociate_loc_why').value;
    var request='ar_assets.php?tipo=pml_desassociate_location&id='+ escape(pl_id)+'&msg='+escape(msg);

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    
		    Dom.get('location_table').deleteRow(index);
		    deleting=0;
		    location_data=r.data;
		    clear_actions();
		    refresh();
		    
		}else
		    Dom.get('manage_stock_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});
    
}

var desassociate_loc=function(location_id){

    clear_actions();
    deleting=location_id;
    
    Dom.get('manage_stock_desktop').style.display='';
    Dom.get('manage_stock_engine').innerHTML='';
    if(location_id==1){
	var lost=Dom.get('loc_stock_units1').innerHTML;
	Dom.get('manage_stock_messages').innerHTML='<b>'+lost+'</b> <?=_('units will be declared as lost');?><br><?=_('Try to explaian what happened to them')?>';
	Dom.get('manage_stock_engine').innerHTML='<table><tr><td><?=_('Explanation')?>:</td><td colspan="2"><textarea id="desassociate_loc_why" onkeyup="desassociate_loc_ready2()" ></textarea></td></tr><tr><td></td><td style="text-align:right"></td></tr><tr><td></td><td id="delete_unknown" style="text-align:right">  <span id="desassociate_loc_save" onclick="desassociate_loc_save(1)" style="margin-left:30px;cursor:pointer;display:none;vertical-align:bottom"><?=_('Save')?> <img src="art/icons/disk.png" style="vertical-align:bottom"/></span>  </td></tr></table>';
    }else{
    Dom.get('manage_stock_messages').innerHTML='<?=_('Do you want to desassocite the location');?>:<span style="padding-left:20px;cursor:pointer" onclick="desassociate_loc_save('+location_id+')">yes</span> <span style="padding-left:20px;cursor:pointer" onclick="clear_actions();">no</span>';

    }
    Dom.get('row_'+location_id).style.background='#ffd7d7';
    Dom.get('loc_del'+location_id).style.visibility='hidden';
};
var desassociate_loc_ready2=function(e){
     if(Dom.get('desassociate_loc_why').value!=''){
	Dom.get('desassociate_loc_save').style.display='';

    }else{
	Dom.get('desassociate_loc_save').style.display='none';
	

    }
    
}


// Swap can pick  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

var swap_picking_save=function(action,location_id){
    

    var index=Dom.get('row_'+location_id).rowIndex;
    var pl_id=Dom.get('row_'+location_id).getAttribute('pl_id');
    var request='ar_assets.php?tipo=pml_swap_picking&action='+escape(action)+'&id='+ escape(pl_id);

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    
		    var index=Dom.get('row_'+location_id).rowIndex;
		    //		    alert(index);
		    location_data=r.data;
		    if(action==1)
			moverow(index,location_data.num_picking_areas+1,'location_table');
		    else
			moverow(index,location_data.num_picking_areas+2,'location_table');
		    clear_actions();
		    swaping=0;
		    refresh();
		    
		}else
		    Dom.get('manage_stock_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});
    
}

var swap_picking=function(location_id){

    clear_actions();
    swaping=location_id;

    if(Dom.get('loc_picking_img'+location_id).getAttribute('can_pick')==1)
	var can_pick=false;
    else
	var can_pick=true;
	    
    Dom.get('manage_stock_desktop').style.display='';
    Dom.get('manage_stock_engine').innerHTML='';
    if(can_pick)
	Dom.get('manage_stock_messages').innerHTML='<?=_('Allow picking from this location');?>:<span style="padding-left:20px;cursor:pointer" onclick="swap_picking_save(1,'+location_id+')">yes</span> <span style="padding-left:20px;cursor:pointer" onclick="clear_actions();">no</span>';
    else
	Dom.get('manage_stock_messages').innerHTML='<?=_('Forbid picking from this location');?>:<span style="padding-left:20px;cursor:pointer" onclick="swap_picking_save(0,'+location_id+')">yes</span> <span style="padding-left:20px;cursor:pointer" onclick="clear_actions();">no</span>';


    Dom.get('loc_picking_img'+location_id).style.opacity=0.5;
};
// ----------------------------------------
var rank_up=function(location_id){


    var index=Dom.get('row_'+location_id).rowIndex;
    var pl_id=Dom.get('row_'+location_id).getAttribute('pl_id');
    var request='ar_assets.php?tipo=pml_increse_picking_rank&id='+ escape(pl_id);
    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    
		    var index=Dom.get('row_'+location_id).rowIndex;
		    location_data=r.data;
		    moverow(index,index-1,'location_table');
		    
		    clear_actions();
		    swaping=0;
		    refresh();
		    
		}else
		    Dom.get('manage_stock_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});
    
}
//Change Location +++++++++++++++++++++++++++++++++++++++++++++++++++
 var change_location =function(){
	clear_actions();
	this.className='selected';
	current_engine='change_location';
	
	Dom.get('manage_stock_desktop').style.display='';
	Dom.get('manage_stock_messages').innerHTML='<b><?=_('In this section is inteded to correct the location name')?>.</b><br><?=_('Choose which location you want to fix')?>';


	for (key in location_data.data){
	    var location= location_data.data[key];
	    if(location.is_physical){
		var location_id=location_data.data[key].location_id;
		Event.addListener("loc_name"+location_id, "click", change_location_from,location_id);
		Dom.get("loc_name"+location_data.data[key].location_id).className='selected';
	    }
	}
 };

var change_location_from  =function(e,location_id){

     Dom.get('manage_stock_messages').innerHTML='<b><?=_('This form  should be used only to correct the location name')?>.</b><br><?=_('Choose the correct location')?>';
     Dom.get('manage_stock_locations').style.display='';
     Dom.get('manage_stock_engine').style.display='none';
     Dom.get("manage_stock_engine").innerHTML='<table><tr><td><?=_('Correct Location')?>:</td><td id="change_location_correct"></td></tr><tr><td><?=_('Comments')?>:</td><td><textarea id="change_location_why" ></textarea></td></tr><tr><td colspan="2" style="text-align:right"><span  onclick="change_location_save('+location_id+')" style="cursor:pointer;vertical-align:bottom"><?=_('Save')?> <img src="art/icons/disk.png" style="vertical-align:bottom"/></span> </td></tr></table>';
 }

 var change_location_save=function(location_id){
     
     var pl_id=Dom.get('row_'+location_id).getAttribute('pl_id');
     var location_name=Dom.get("new_location_input").value;
      if(location_name=='')
	Dom.get('manage_stock_messages').innerHTML='<?=_('Select a location from the list')?> <span id="identify_location_save" onclick="identify_location_save()" style="margin-left:30px;cursor:pointer;display:none;vertical-align:bottom"><?=_('Save')?> <img src="art/icons/disk.png" style="vertical-align:bottom"/></span>';

      var msg=Dom.get("change_location_why").value;

      var request='ar_assets.php?tipo=pml_change_location&new_location_name='+ escape(location_name)+'&id='+ escape(pl_id)+'&msg='+escape(msg);
      //   alert(request);
      YAHOO.util.Connect.asyncRequest('POST',request ,{
	      success:function(o) {
		  //	  alert(o.responseText);
		  var r =  YAHOO.lang.JSON.parse(o.responseText);
		  if (r.state == 200) {
		      //cahnge location identifiers
		      var old_location_id=location_id;
		      
		      var new_location_id=r.new_location_id;
		      Dom.get('loc_picking_up'+old_location_id).setAttribute('onClick','rank_up('+new_location_id+')');
		      Dom.get('loc_picking_img'+old_location_id).setAttribute('onClick','swap_picking('+new_location_id+')');
		      Dom.get('loc_del'+old_location_id).setAttribute('onClick','desassociate_loc('+new_location_id+')');

		      Dom.get('row_'+old_location_id).setAttribute('id','row_'+new_location_id);
		      Dom.get('loc_name'+old_location_id).setAttribute('id','loc_name'+new_location_id);
		      Dom.get('loc_tipo'+old_location_id).setAttribute('id','loc_tipo'+new_location_id);
		      Dom.get('loc_pick_info'+old_location_id).setAttribute('id','loc_pick_info'+new_location_id);
		      Dom.get('loc_picking_up'+old_location_id).setAttribute('id','loc_picking_up'+new_location_id);
		      Dom.get('loc_picking_tipo'+old_location_id).setAttribute('id','loc_picking_tipo'+new_location_id);
		      Dom.get('loc_picking_img'+old_location_id).setAttribute('id','loc_picking_img'+new_location_id);
		      Dom.get('loc_stock_units'+old_location_id).setAttribute('id','loc_stock_units'+new_location_id);
		      Dom.get('loc_del'+old_location_id).setAttribute('id','loc_del'+new_location_id);
		      

		      

		      location_data=r.data;
		      clear_actions();
		    refresh();
		  }else
		    Dom.get('manage_stock_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});


  }





// Identify location
    var identify_location =function(){
	clear_actions();
	this.className='selected';current_engine='identify_location';
	
	Dom.get('manage_stock_desktop').style.display='';
	Dom.get('manage_stock_messages').innerHTML='<?=_('Location code');?>: <span id="identify_location_save" onclick="identify_location_save()" style="margin-left:30px;cursor:pointer;display:none;vertical-align:bottom"><?=_('Save')?> <img src="art/icons/disk.png" style="vertical-align:bottom"/></span> ';
	Dom.get('manage_stock_locations').style.display='';
	


    }
  var identify_location_save=function(){
      var pl_id=Dom.get('row_1').getAttribute('pl_id');
      var location_name=Dom.get("new_location_input").value;
      if(location_name=='')
	Dom.get('manage_stock_messages').innerHTML='<?=_('Select a location from the list')?> <span id="identify_location_save" onclick="identify_location_save()" style="margin-left:30px;cursor:pointer;display:none;vertical-align:bottom"><?=_('Save')?> <img src="art/icons/disk.png" style="vertical-align:bottom"/></span>';

      var request='ar_assets.php?tipo=pml_change_location&msg=&new_location_name='+ escape(location_name)+'&id='+ escape(pl_id);
      //   alert(request);
      YAHOO.util.Connect.asyncRequest('POST',request ,{
	      success:function(o) {
		  //	  alert(o.responseText);
		  var r =  YAHOO.lang.JSON.parse(o.responseText);
		  if (r.state == 200) {
		      //cahnge location identifiers
		      var old_location_id=1;
		      
		      var new_location_id=r.new_location_id;
		      Dom.get('loc_picking_up'+old_location_id).setAttribute('onClick','rank_up('+new_location_id+')');
		      Dom.get('loc_picking_img'+old_location_id).setAttribute('onClick','swap_picking('+new_location_id+')');
		      Dom.get('loc_del'+old_location_id).setAttribute('onClick','desassociate_loc('+new_location_id+')');

		      Dom.get('row_'+old_location_id).setAttribute('id','row_'+new_location_id);
		      Dom.get('loc_name'+old_location_id).setAttribute('id','loc_name'+new_location_id);
		      Dom.get('loc_tipo'+old_location_id).setAttribute('id','loc_tipo'+new_location_id);
		      Dom.get('loc_pick_info'+old_location_id).setAttribute('id','loc_pick_info'+new_location_id);
		      Dom.get('loc_picking_up'+old_location_id).setAttribute('id','loc_picking_up'+new_location_id);
		      Dom.get('loc_picking_tipo'+old_location_id).setAttribute('id','loc_picking_tipo'+new_location_id);
		      Dom.get('loc_picking_img'+old_location_id).setAttribute('id','loc_picking_img'+new_location_id);
		      Dom.get('loc_stock_units'+old_location_id).setAttribute('id','loc_stock_units'+new_location_id);
		      Dom.get('loc_del'+old_location_id).setAttribute('id','loc_del'+new_location_id);
		      

		      

		      location_data=r.data;
		      clear_actions();
		    refresh();
		  }else
		    Dom.get('manage_stock_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});


  }


// MOVE STOCK -----------------------------------------------------------------------------------------
var move_stock =function(){
    clear_actions();
    current_engine='move_stock';
    this.className='selected';
    Dom.get('manage_stock_desktop').style.display='';
    Dom.get('manage_stock_engine').innerHTML='';
    Dom.get('manage_stock_messages').innerHTML='<?=_('Click the location from were the stock was moved');?>';
    
    var can_from=location_data.num_physical-location_data.num_physical_with_stock;
    var can_to=location_data.num_physical-can_from;

    if(can_from==1 && can_to==1){
	for (key in location_data.data)
	    {
		var location= location_data.data[key];
		if(location.is_physical && location.has_stock)
		    from_index= key;
		if(location.is_physical && !location.has_stock)
		    to_index= key;
	       }
	   move_stock_from('',from_index);
	   move_stock_to('',to_index);
	   
    }else {
	
	for (key in location_data.data)
	    {
		   var location= location_data.data[key];
		   if(location.is_physical && location.has_stock){
		       Event.addListener("loc_name"+location_data.data[key].location_id, "click", move_stock_from,key);
		       Dom.get("loc_name"+location_data.data[key].location_id).className='selected';
		   }
	    }
       }
};

var move_stock_save = function(){
    var qty=Dom.get("move_stock_qty").value;
    var from_id=Dom.get('move_stock_from').getAttribute('location_id')
    var to_id=Dom.get('move_stock_to').getAttribute('location_id')
    
    var request='ar_assets.php?tipo=pml_move_stock&from='+ escape(from_id)+'&to='+escape(to_id)+'&qty='+escape(qty);
    // alert(request);
    // return
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    //update all stock figures (if were changed else were)
		    location_data=r.data;
		    clear_actions('move_stock')
			refresh();
		    
		}else
		    Dom.get('manage_stock_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});
    
};

var move_stock_from=function(e,index){
    Dom.get('manage_stock_messages').innerHTML='<?=_('Choose the location where you moved the stock')?>'
    Dom.get('manage_stock_engine').style.display='';
    Dom.get('manage_stock_engine').innerHTML='<table border=0><tr style="height:30px"><td style="vertical-align:bottom" class="location_name"  id="move_stock_from" location_id="'+location_data.data[index].id+'"  >'+location_data.data[index].name+'</td><td style="vertical-align:bottom" > &rarr; </td><td style="vertical-align:bottom" id="move_stock_to" class="location_name"><b>?</b></td><td style="padding-left:30px;padding-right:30px;display:none;vertical-align:bottom" id="move_stock_td_qty"> <?=_('Units')?>:  <input style="vertical-align:bottom" id="move_stock_qty" type="text" max="'+location_data.data[index].stock_units+'" size="3"  onkeyup="move_stock_ready()" />  (<span style="cursor:pointer" onclick="move_stock_all();">'+location_data.data[index].stock_units+'</span> <?=_('max')?>)  </td><td id="move_stock_save" onclick="move_stock_save()" style="cursor:pointer;display:none;vertical-align:bottom"><?=_('Save')?> <img src="art/icons/disk.png" style="vertical-align:bottom"/></td></tr></table>'
    
    var can_to=location_data.num_physical;
    if(can_to==2){
	for (key in location_data.data)
	    {
		var location= location_data.data[key];
		if(location.is_physical && key!=index)
		    to_index= key;
	    }
		    move_stock_to('',to_index);
    }else {
	
	for (key in location_data.data)
	    {
		var location= location_data.data[key];
		Event.removeListener("loc_name"+location_data.data[key].location_id, "click");
		if(location.is_physical && key!=index  ){
		    Event.addListener("loc_name"+location_data.data[key].location_id, "click", move_stock_to,key);
		    Dom.get("loc_name"+location_data.data[key].location_id).className='selected';
		}else
		    Dom.get("loc_name"+location_data.data[key].location_id).className='';
	    }
    }
};


var move_stock_to=function(e,index){
    
    for (key in location_data.data)
	{
	    var location= location_data.data[key];
	    Event.removeListener("loc_name"+location_data.data[key].location_id, "click");
	    Dom.get("loc_name"+location_data.data[key].location_id).className='';
	}
    
    Dom.get('manage_stock_messages').innerHTML='<?=_('How many units did you move?')?>'
    
    Dom.get('move_stock_to').innerHTML=location_data.data[index].name;
    Dom.get('move_stock_to').setAttribute('location_id',location_data.data[index].id);
    Dom.get('move_stock_td_qty').style.display='';
};	

var move_stock_ready=function(){
    var qty=1*Dom.get("move_stock_qty").value;
    var max=Dom.get('move_stock_qty').getAttribute('max');
    if(YAHOO.lang.isNumber(qty)){
	if(qty<0){
	    Dom.get('manage_stock_messages').innerHTML='<?=_('Do not give me negative numbers please')?>';
	    Dom.get('move_stock_save').style.display='none';
	}else if(qty==0){
	    Dom.get('manage_stock_messages').innerHTML='<?=_('How many units did you move?')?>';
	    Dom.get('move_stock_save').style.display='none';
	}else if(qty>max){
	    Dom.get('manage_stock_messages').innerHTML='<?=_('You can not move more than')?> '+max+' <?=_('Units')?>';
	    Dom.get('move_stock_save').style.display='none';
	    
	}else{
	    Dom.get('manage_stock_messages').innerHTML='<?=_('Save the changes please')?>';
	    Dom.get('move_stock_save').style.display='';
	}
		    }else if(qty==''){
	Dom.get('manage_stock_messages').innerHTML='<?=_('How many units did you move?')?>';
	Dom.get('move_stock_save').style.display='none';
    }else{
	Dom.get('manage_stock_messages').innerHTML='<?=_('That is not a number, how many units did you move?')?>';	
	Dom.get('move_stock_save').style.display='none';
    }
};

var move_stock_all=function(index){
    Dom.get('move_stock_qty').value=Dom.get('move_stock_qty').getAttribute('max');
    move_stock_ready();
};


YAHOO.util.Event.onContentReady("manage_stock_locations", function () {
function init(){
  //   change_max_units = new YAHOO.widget.Dialog("change_max_units", 
// 			{ 
// 			    visible : false,close:false,
// 			    underlay: "none",draggable:false
			    
// 			} );
    //change_max_units.render();
    //    alert("hola");
	 Event.addListener('submit_search', "click",submit_search);
	 Event.addListener('prod_search', "keydown", submit_search_on_enter);
	 
};
YAHOO.util.Event.onDOMReady(init);
 save_max_units=function(){
    var p2l_id= Dom.get("change_max_units_location_name").getAttribute('pl2_id');
    var max_units=Dom.get("change_max_units_value").value;
    var request='ar_assets.php?tipo=pml_change_max_units&key=max_units_per_location&p2l_id='+ escape(p2l_id)+'&value='+escape(max_units);
    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.ok) {
		    Dom.get('loc_stock_max_units'+p2l_id).innerHTML=r.max_units;
		    var table=tables.table0;
		    var datasource=tables.dataSource0;
		    var request='&sf=0';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);     
		    
		}else
		    Dom.get('manage_stock_messages').innerHTML='<span class="error">'+r.msg+'</span>';

		change_max_cancel();
	       }
	   });
};

 change_max_cancel=function(){
    Dom.setX('change_max_units', -1000)
    Dom.setY('change_max_units', -1000)
    Dom.get("change_max_units_location_name").innerHTML='';
    Dom.get("change_max_units_location_name").setAttribute('pl2_id','');
    Dom.get("change_max_units_value").value='';
};

 change_max_units_dialog=function(o,id,name){
    var y=(Dom.getY(o))
    var x=(Dom.getX(o))
    x=x-300;
    //    alert(y);
    Dom.setX('change_max_units', x)
    Dom.setY('change_max_units', y)
    Dom.get("change_max_units_location_name").innerHTML=name;
    Dom.get("change_max_units_location_name").setAttribute('pl2_id',id);
    if(is_numeric(o.innerHTML))
	Dom.get("change_max_units_value").value=o.innerHTML;
    else
	Dom.get("change_max_units_value").value='';

}

YAHOO.util.Event.onDOMReady(init);
	//function init(){
	

	// Use an XHRDataSource
	var oDS = new YAHOO.util.XHRDataSource("ar_assets.php");

 	oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	oDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["name"]
 	};
	
// 	// Instantiate the AutoComplete
 	var oAC = new YAHOO.widget.AutoComplete("new_location_input", "new_location_container", oDS);

// 	// The webservice needs additional parameters
 	oAC.generateRequest = function(sQuery) {
 	    return "?tipo=locations_name&all=0&query=" + sQuery ;
 	};
	oAC.forceSelection = true; 
	


	
	oAC.itemSelectEvent.subscribe(location_selected); 
	


     //};	
	    });


//YAHOO.util.Event.onDOMReady(init);


YAHOO.util.Event.onContentReady("manage_stock", function () {

	
	 Event.addListener("damaged_stock", "click", damaged_stock);
	 Event.addListener("move_stock", "click", move_stock);
	 Event.addListener("change_stock", "click", change_stock);
	 Event.addListener("change_location", "click", change_location);

	 Event.addListener("new_location", "click", new_location);
	 Event.addListener("identify_location", "click", identify_location);
	 
    });

YAHOO.util.Event.onContentReady("manage_stock_products", function () {
	var oDS = new YAHOO.util.XHRDataSource("ar_assets.php");
 	oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	oDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["scode","code","description","current_qty","changed_qty","new_qty","_qty_move","_qty_change","_qty_damaged","note","delete","product_id"]
 	};
 	var oAC = new YAHOO.widget.AutoComplete("new_product_input", "new_product_container", oDS);
 	oAC.generateRequest = function(sQuery) {
 	    return "?tipo=products_name&except=location&except_id=<?=$_SESSION['state']['product']['id']?>&query=" + sQuery ;
 	};

	var myHandler = function(sType, aArgs) {

	    newProductData = aArgs[2];

	};
	oAC.itemSelectEvent.subscribe(myHandler);




	oAC.forceSelection = true; 
	oAC.itemSelectEvent.subscribe(product_selected); 
    });

var product_selected=function(){

    var data = {
	"code":newProductData[1]
	,"description":newProductData[2]
	,"current_qty":newProductData[3]
	,"changed_qty":newProductData[4]
	,"new_qty":newProductData[5]
	,"_qty_move":newProductData[6]
	,"_qty_change":newProductData[7]
	,"_qty_damaged":newProductData[8]
	,"note":newProductData[9]
	,"delete":newProductData[10]
	,"product_id":newProductData[11]
    }; 

    
    new_parent=data.product_id;
    Dom.get('manage_stock_engine').style.display='';

}

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {
	    //START OF THE TABLE=========================================================================================================================
	    
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var CustomersColumnDefs = [
				       {key:"date", label:"<?=_('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author", label:"<?=_('Author')?>", width:70,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"note", label:"<?=_('Description')?>", width:370,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	    //?tipo=customers&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=stock_history");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    rtext:"resultset.rtext",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "id"
			 ,"note"
			 ,'author','date'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource0
						     , {
							 // sortedBy: {key:"<?=$_SESSION['tables']['customers_list'][0]?>", dir:"<?=$_SESSION['tables']['customers_list'][1]?>"},
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?=$_SESSION['state']['product']['stock_history']['nr']?>,containers : 'paginator', alwaysVisible:false,
								 pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							     key: "<?=$_SESSION['state']['product']['stock_history']['order']?>",
							     dir: "<?=$_SESSION['state']['product']['stock_history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table0.filter={key:'<?=$_SESSION['state']['product']['stock_history']['f_field']?>',value:'<?=$_SESSION['state']['product']['stock_history']['f_value']?>'};
	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)
	
	};
    });

