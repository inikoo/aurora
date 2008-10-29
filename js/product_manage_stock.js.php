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
	  Dom.get("loc_stock"+id).innerHTML=location.stock;
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

  Dom.get('total_stock').innerHTML=location_data.stock;

  if(location_data.has_unknown)
      Dom.get("identify_location").style.display='';
  else
      Dom.get("identify_location").style.display='none';

  if(location_data.has_physical){
      Dom.get("change_stock").style.display='';
      Dom.get("modify_location").style.display='';
      Dom.get("new_location").style.display='';
      if(location_data.num_physical>1)
	  Dom.get("move_stock").style.display='';
      else
	  Dom.get("move_stock").style.display='none';
      if(location_data.physical_with_stock>0)
	  Dom.get("move_stock").style.display='';
      else
	  Dom.get("move_stock").style.display='none';
      

  }else{
      Dom.get("change_stock").style.display='none';
      Dom.get("modify_location").style.display='none';
      Dom.get("new_location").style.display='none';
      Dom.get("move_stock").style.display='none';
      Dom.get("damaged_stock").style.display='none';

  }





}


var location_selected= function(){

    if(current_engine=='new_location'){

	Dom.get("new_location_q1").style.display='';
    }
     if(current_engine=='identify_location'){

	Dom.get("identify_location_save").style.display='';
    }
    
}

var clear_actions = function(){


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
    Dom.get('move_stock').className='';
    Dom.get('damaged_stock').className='';
    Dom.get('new_location').className='';
    Dom.get('identify_location').className='';

    
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
		    cellLeft.appendChild(span);
		    var span = document.createElement("span");
		    span.setAttribute("id", 'loc_picking_tipo'+r.id );
		    cellLeft.appendChild(span);
		    cellLeft.style.textAlign='right';
		    cellLeft.setAttribute("id", 'loc_pick_info'+r.id );
		    var img = document.createElement("img");
		    img.setAttribute("src", "art/icons/basket.png" );
		    img.setAttribute("style", "cursor:pointer" );
		    img.setAttribute("title", "" );
		    img.setAttribute("id", 'loc_picking_img'+r.id );
		    //img.setAttribute("onclick", "desassociate_loc()" );
		     cellLeft.appendChild(img);


		     var cellLeft = row.insertCell(3);
		     cellLeft.setAttribute("id", 'loc_stock'+r.id );
		     cellLeft.style.textAlign='right';
		     var cellLeft = row.insertCell(4);
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
    
    Dom.get('manage_stock_messages').innerHTML='<?=_('How many outers are currently on the location?')?>'
    Dom.get('manage_stock_engine').innerHTML='<table><tr id="change_stock_qty" ><td><?=_('Number of outers')?></td><td><input id="new_qty" location_id="'+location_data.data[index].id+'"  style="text-align:right;padding:0 3px" type="text"  size="3"  onkeyup="change_stock_ready()"   /> <span style="cursor:pointer" onclick="new_stock_none();">(<?=_('None')?>)</span> <span id="change_stock_continue" style="display:none;padding-left:20px;cursor:pointer;text-decoration:underline" onclick="change_stock_manage('+index+')"><?=_('Continue')?></span></td></tr><tr style="display:none" id="more_outers"><td colspan="3"><span id="more_change"></span> <span><?=_("Outers")?></span>  <span id="more_change_save"  onclick="change_stock_save('+index+')" style="display:none;margin-left:30px;cursor:pointer"  > <?=_('Save')?> <img src="art/icons/disk.png" style="vertical-align:bottom"/></span>   <br> <?=_('Please try to explain why there is more outers than there should be')?>. <b><?=_('If stock has been received plesase add it on')?> <a href="suppliers.php" style="cursor:pointer;text-decoration:underline" ><?=_("Suppliers Area")?></a><b>.</span></td></tr><tr style="display:none" id="less_outers"><td colspan="3"><span id="less_change"></span> <span><?=_('Outers')?></span>  <span id="less_change_save"  onclick="change_stock_save('+index+')" style="display:none;margin-left:30px;cursor:pointer"  > <?=_('Save')?> <img src="art/icons/disk.png" style="vertical-align:bottom"/></span>  <br><?=_('Please give possible reasons of the lost stock ')?>. <b><?=_('If stock has been damaged')?> <span onclick="damaged_stock()" style="cursor:pointer;text-decoration:underline" ><?=_('click here')?></span><b>.</span></td></tr><tr id="change_stock_comments" style="display:none"  ><td><?=_('Explanation')?>:</td><td colspan="2"><textarea id="change_stock_why" onkeyup="change_stock_ready2()" ></textarea></td></tr></table>'};	  


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
	Dom.get('manage_stock_messages').innerHTML='<?=_('So nothing have change, how many outers are currently on the location? ')?>';
	
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
    var request='ar_assets.php?tipo=change_qty&qty='+ escape(qty)+'&id='+ escape(location_id)+'&msg='+ escape(msg);
    // alert(request);
    // return;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//	alert(o.responseText);
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
       // alert(o.responseText)
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
	    Dom.get('manage_stock_messages').innerHTML='<?=_('How many outers were damaged?')?>';
	}else if(qty>max){
	    Dom.get('manage_stock_messages').innerHTML='<?=_('Only')?> '+max+' <?=_('outers in location')?>';
	    
	}else{
	    ok2=true;
	}
    }else if(qty==''){
	Dom.get('manage_stock_messages').innerHTML='<?=_('How many outers were damaged?')?>';
	
    }else{
	Dom.get('manage_stock_messages').innerHTML='<?=_('That is not a number, how many outers were damaged?')?>';	
	
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



     Dom.get('manage_stock_engine').innerHTML='<table><td><?=_('Outers damaged')?>: </td><td style="padding-right:20px" id="damaged_stock_td_qty"> <input id="damaged_stock_qty" location_id="'+location_data.data[index].id+'"  type="text" max="'+location_data.data[index].stock+'" size="3"  onkeyup="damaged_stock_ready()"   /> (<span style="cursor:pointer" onclick="damaged_stock_all();">'+location_data.data[index].stock+'</span> <?=_('max')?>)</td><td id="damaged_stock_save" onclick="damaged_stock_save()" style="cursor:pointer;display:none"><?=_('Save')?> <img src="art/icons/disk.png" style="vertical-align:bottom"/></td><tr><td><?=_('Comments')?>:</td><td colspan="2"><textarea id="damaged_stock_why" onkeyup="damaged_stock_ready()" ></textarea></td></tr></table>'

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
    var request='ar_assets.php?tipo=pml_desassociate_location&id='+ escape(pl_id);

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//	alert(o.responseText);
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
    Dom.get('manage_stock_messages').innerHTML='<?=_('Do you want to dessassocite the location');?>:<span style="padding-left:20px;cursor:pointer" onclick="desassociate_loc_save('+location_id+')">yes</span> <span style="padding-left:20px;cursor:pointer" onclick="clear_actions();">no</span>';
    Dom.get('row_'+location_id).style.background='#ffd7d7';
    Dom.get('loc_del'+location_id).style.visibility='hidden';
};
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

      var request='ar_assets.php?tipo=pml_change_location&new_location_name='+ escape(location_name)+'&id='+ escape(pl_id);
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
		      Dom.get('loc_stock'+old_location_id).setAttribute('id','loc_stock'+new_location_id);
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
    Dom.get('manage_stock_engine').innerHTML='<table border=0><tr style="height:30px"><td style="vertical-align:bottom" class="location_name"  id="move_stock_from" location_id="'+location_data.data[index].id+'"  >'+location_data.data[index].name+'</td><td style="vertical-align:bottom" > &rarr; </td><td style="vertical-align:bottom" id="move_stock_to" class="location_name"><b>?</b></td><td style="padding-left:30px;padding-right:30px;display:none;vertical-align:bottom" id="move_stock_td_qty"> <?=_('Outers')?>:  <input style="vertical-align:bottom" id="move_stock_qty" type="text" max="'+location_data.data[index].stock+'" size="3"  onkeyup="move_stock_ready()" />  (<span style="cursor:pointer" onclick="move_stock_all();">'+location_data.data[index].stock+'</span> <?=_('max')?>)  </td><td id="move_stock_save" onclick="move_stock_save()" style="cursor:pointer;display:none;vertical-align:bottom"><?=_('Save')?> <img src="art/icons/disk.png" style="vertical-align:bottom"/></td></tr></table>'
    
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
    
    Dom.get('manage_stock_messages').innerHTML='<?=_('How many outers did you move?')?>'
    
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
	    Dom.get('manage_stock_messages').innerHTML='<?=_('How many outers did you move?')?>';
	    Dom.get('move_stock_save').style.display='none';
	}else if(qty>max){
	    Dom.get('manage_stock_messages').innerHTML='<?=_('You can not move more than')?> '+max+' <?=_('Outers')?>';
	    Dom.get('move_stock_save').style.display='none';
	    
	}else{
	    Dom.get('manage_stock_messages').innerHTML='<?=_('Save the changes please')?>';
	    Dom.get('move_stock_save').style.display='';
	}
		    }else if(qty==''){
	Dom.get('manage_stock_messages').innerHTML='<?=_('How many outers did you move?')?>';
	Dom.get('move_stock_save').style.display='none';
    }else{
	Dom.get('manage_stock_messages').innerHTML='<?=_('That is not a number, how many outers did you move?')?>';	
	Dom.get('move_stock_save').style.display='none';
    }
};

var move_stock_all=function(index){
    Dom.get('move_stock_qty').value=Dom.get('move_stock_qty').getAttribute('max');
    move_stock_ready();
};


YAHOO.util.Event.onContentReady("manage_stock_locations", function () {
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
	 Event.addListener("new_location", "click", new_location);
	 Event.addListener("identify_location", "click", identify_location);
    });


