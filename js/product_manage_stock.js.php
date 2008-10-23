<?include_once('../common.php')?>
	var Dom   = YAHOO.util.Dom;
	var Event = YAHOO.util.Event;
	jsonString='<?=$_SESSION['state']['product']['manage_stock_data'];?>';
	try {
	    var location_data = YAHOO.lang.JSON.parse(jsonString);
	}
	catch (e) {
	    alert("ERROR:P_PMS_JSONDATA");
	};


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








var clear_actions = function(action){
    Dom.get('manage_stock_messages').innerHTML='';
    Dom.get('manage_stock_engine').innerHTML='';
    Dom.get('manage_stock_desktop').style.display='none';
    
    

    if(action=='move_stock'){
	Dom.get('move_stock').className='';
	 var i=0;
	 for (i=0;i<location_data.physical_locations;i++)
	     {
		 Event.removeListener("loc_name"+location_data.locations[i].id,'click');
		 Dom.get("loc_name"+location_data.locations[i].id).className='';
	     }

    }
    
}

    
    var move_stock =function(){
	    this.className='selected';
	    Dom.get('manage_stock_desktop').style.display='';
	    Dom.get('manage_stock_engine').innerHTML=''
	    Dom.get('manage_stock_messages').innerHTML='<span style="float:right;cursor:pointer" onclick="clear_actions(\'move_stock\');"><?=_('Close')?></span> <?=_('Click the location from were the the stock was moved');?>';
	    var num_locations=location_data.physical_locations;
	    if(num_locations==2){

		if(location_data.locations[0].stock>0){
		    Event.addListener("loc_name"+location_data.locations[0].id, "click", move_stock_duo,0);
		    Dom.get("loc_name"+location_data.locations[0].id).className='selected';
		}
		if(location_data.locations[1].stock>0){
		    Event.addListener("loc_name"+location_data.locations[1].id, "click", move_stock_duo,1);
		    Dom.get("loc_name"+location_data.locations[1].id).className='selected';
		}

		
	    }else if(num_locations>2){
	    var i=0;
	    for (i=0;i<location_data.physical_locations;i++)
		{
		    //    Event.addListener("move_stock", "click", move_stock);
		    //location_data.locations[i].id
		}
	    }
	    
	}

	var save_move_stock = function(from_index,to_index){
	    var qty=1*Dom.get("move_stock_qty").value;
	    if(YAHOO.lang.isNumber(qty)){
		if(qty<=0)
		    Dom.get('manage_stock_messages').innerHTML='<?=_('Outers quantity should be bigger than zero')?>';
		else{
		    Dom.get("loc_stock"+location_data.locations[from_index].id).value=Dom.get("loc_stock"+location_data.locations[from_index].id).value-qty;
		    Dom.get("loc_stock"+location_data.locations[to_index].id).value=Dom.get("loc_stock"+location_data.locations[to_index].id).value+qty;
		    clear_actions('move_stock');
		}
	    }else
		Dom.get('manage_stock_messages').innerHTML='<?=_('Outers quantity should be numeric')?>';	
		    

	    var max=location_data.locations[from_index].stock;

	}



	var move_stock_duo=function(e,from_index){


	    Event.removeListener("loc_name"+location_data.locations[0].id, "click" );
	    Event.removeListener("loc_name"+location_data.locations[1].id, "click" );
	    Dom.get("loc_name"+location_data.locations[0].id).className='';
	    Dom.get("loc_name"+location_data.locations[1].id).className='';





	    if(from_index==0)
		to_index=1;
	    else
		to_index=0;
	    Dom.get('manage_stock_messages').innerHTML='<?=_('Indicate the number of outers moved')?>'
	    var myTable = document.createElement("table");
	    myTable.className='location_state';
	    var myThead = document.createElement("thead");
	    myThead.style.height = '0px';
	    var myTfoot = document.createElement("tfoot");
	    myTfoot.style.height = '0px';
	    var myTbody = document.createElement("tbody");
	    var myRow = document.createElement("tr");
	    var myCell = document.createElement("td");
	    myCell.innerHTML = location_data.locations[from_index].name+' &rarr; '+location_data.locations[to_index].name;
	    myTable.appendChild(myThead);
	    myTable.appendChild(myTfoot);
	    myTable.appendChild(myTbody);
	    myTbody.appendChild(myRow);
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<?=_('Outers')?>: <input type="text" size="3" id="move_stock_qty" value="" /> ('+location_data.locations[from_index].stock+' max)';
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<span>Now</span>';
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<img src="art/icons/disk.png"  onclick="save_move_stock('+from_index+','+to_index+')"  alt="<?=_('save')?>" title="<?=_('Save')?>" />';
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<img src="art/icons/bin.png" onclick="move_stock()" alt="<?=_('cancel')?>" title="<?=_('Cancel')?>" />';

	    myRow.appendChild(myCell);


 	    Dom.get('manage_stock_engine').appendChild(myTable);
	    


	}







YAHOO.util.Event.onContentReady("manage_stock", function () {

	

	 Event.addListener("move_stock", "click", move_stock);
    });
