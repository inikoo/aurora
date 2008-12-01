<?
    include_once('../common.php');
?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;

var    current_form = 'description';
var    num_changed = 0;
var    num_errors = 0;
var editor;
var editing='<?=$_SESSION['state']['product']['edit']?>';
var cat_list;
jsonString='<?=$_SESSION['state']['product']['shapes'];?>';
try {
    var shapes = YAHOO.lang.JSON.parse(jsonString);
}
catch (e) {
    alert("ERROR:P_PES_JSONDATA");
};
jsonString='<?=$_SESSION['state']['product']['shapes_example'];?>';
try {
    var shapes_example = YAHOO.lang.JSON.parse(jsonString);
}
catch (e) {
    alert("ERROR:P_PES_JSONDATA");
};

var cats=new Object;
var select_cat=function(o,e){
    var tipo=o.getAttribite('cat');
    var cat_id=o.getAttribute('cat_id');
    var cat_name=o.innerHTML;
    
    if(o.className=='selected'){
	delete(cats[cat_id])
	
    }else{

	cats[cat_id]={'tipo':tipo,'name':cat_name};
    }
    display_cats();

}




var is_diferent = function(v1,v2,tipo){

    if(tipo=='money' || tipo=='number'){
	if(parseFloat(v1)!=parseFloat(v2))
	    return true;
	else
	    return false;
    }else{
	if(v1!=v2)
	    return true;
	else
	    return false;
    }
	

}


var change_element= function(o){

       
	var current_class=o.className;
	var tipo=o.getAttribute('tipo');


	if(is_diferent(o.getAttribute("ovalue"),o.value,tipo)){

	    if(current_class==''){
		num_changed++;
		}

	    val = vadilate(o);

	    if(!val){
		if(current_class!='error'){
		    num_errors++;
		}
		o.className='error';
	    }else{
		if(current_class=='error')
		    num_errors--;
		o.className='ok';

	    }

	}else{

	    if(current_class=='ok')
		num_changed--;
	    if(current_class=='error'){
		num_changed--;
		num_errors--;
	    }
	    o.className='';

	}

	if(editing=='suppliers'){
	    interpet_changes(o.getAttribute('supplier_id'));
	}else
	    interpet_changes(o.id);
	


    }

    function price_change(old_value,new_value){
	//	alert(old_value+' '+new_value)
	prefix='';
	old_value=FormatNumber(old_value,'.','',2);
	new_value=FormatNumber(new_value,'.','',2);
	var diff=new_value-old_value;
	if(diff>0)
	    prefix='+'+'<?=$myconf['currency_symbol']?>';
	else
	    prefix='-'+'<?=$myconf['currency_symbol']?>';
	

	if(old_value==0)
	    var per='';
	else{

	    var per=FormatNumber((100*(diff/old_value)).toFixed(1),'<?=$myconf['decimal_point']?>','<?=$myconf['thosusand_sep']?>',1)+'%';
	}
	var diff=FormatNumber(Math.abs(diff).toFixed(2),'<?=$myconf['decimal_point']?>','<?=$myconf['thosusand_sep']?>',2);
	return prefix+diff+' '+per;
    }

function format_rrp(o){
  if(o.value=='')
      {
	  price_changed(o);
      }else{
      o.value=FormatNumber(o.value,'<?=$myconf['decimal_point']?>','<?=$myconf['thosusand_sep']?>',2);
      price_changed(o);
  }

}

function return_to_old_value(key){
    Dom.get("v_"+key).value=Dom.get("v_"+key).getAttribute('ovalue');
    
    if(key=='description')
	description_changed(Dom.get("v_"+key));

}


  function description_changed(o){
	var ovalue=o.getAttribute('ovalue');
	var name=o.name;

	    
	if(ovalue!=o.value){

	    if(o.value==''){
		Dom.get(name+"_change").innerHTML="<?=_("This value can not be empty")?>";
		Dom.get(name+"_save").style.display='none';
		Dom.get(name+"_icon").style.visibility='visible';
		return;
	    }
	    Dom.get(name+"_save").style.display='';
	    Dom.get(name+"_change").innerHTML='';
	    Dom.get(name+"_icon").style.visibility='visible';
	    
	}
	else{
	    Dom.get(name+"_change").innerHTML='';
	    Dom.get(name+"_save").style.display='none';
	    Dom.get(name+"_icon").style.visibility='hidden';
	}
    
	
	
    }







    function price_changed(o){
	var ovalue=o.getAttribute('ovalue');
	var name=o.name;

	    
	if(ovalue!=o.value){
	    Dom.get(name+"_save").style.visibility='visible';
	    if(o.value==''){
		Dom.get(name+"_change").innerHTML='<?=_('RRP value unset')?>';
		Dom.get(name+"_ou").innerHTML='';
	    }else if(ovalue==''){
		value=FormatNumber(o.value,'.','',2);
		factor=FormatNumber(o.getAttribute('factor'),'.','',6);
		Dom.get(name+"_change").innerHTML='<?=_('RRP set to')?> '+'<?=$myconf['currency_symbol']?>'+FormatNumber(value,'<?=$myconf['decimal_point']?>','<?=$myconf['thosusand_sep']?>',2);
		Dom.get(name+"_ou").innerHTML='<?=$myconf['currency_symbol']?>'+FormatNumber((value*factor).toFixed(2),'<?=$myconf['decimal_point']?>','<?=$myconf['thosusand_sep']?>',2);
	    }else{
		value=FormatNumber(o.value,'.','',2);
		factor=FormatNumber(o.getAttribute('factor'),'.','',6);
		change=price_change(ovalue,o.value);
		Dom.get(name+"_change").innerHTML=change;
		Dom.get(name+"_ou").innerHTML='<?=$myconf['currency_symbol']?>'+FormatNumber((value*factor).toFixed(2),'<?=$myconf['decimal_point']?>','<?=$myconf['thosusand_sep']?>',2);
	    }
	}else{
	    Dom.get(name+"_save").style.visibility='hidden';
	}
    
	
	
    }


function save_form(){
    if(current_form == 'description')
	editor.saveHTML();
    YAHOO.util.Connect.setForm(document.getElementById(current_form)); 

    var request = YAHOO.util.Connect.asyncRequest('POST', 'ar_assets.php', callback);

}

var interpet_changes = function(id){
    
    if(editing=='suppliers'){
	if(num_changed>0 && num_errors==0){
	    Dom.get('save_supplier_'+id).style.display='';
	}else
	    Dom.get('save_supplier_'+id).style.display='none';

	
    }else{
	if(num_changed>0 && num_errors==0){
	    Dom.get('save_'+id).style.display='';
	    //  Dom.get('save').className='ok';
	    // Dom.get('exit').className='nook';
	    // YAHOO.util.Event.addListener('save', "click", save_form);
	}else{
	    Dom.get('save_'+id).style.display='none';
	    // YAHOO.util.Event.removeListener('save', "click");
	    // Dom.get('save').className='disabled';
	    //Dom.get('exit').className='ok';
	    
	}
    }
};

function simple_save(name){

    if(name=='dim' || name=='odim')
	var value = Dom.get(name).getAttribute('tipo')+'_'+Dom.get(name).value;
    else
	var value = Dom.get(name).value;

    var request='ar_assets.php?tipo=ep_update&key='+ escape(name)+'&value='+ escape(value);

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);

		var num_ok=0;
		var num_err=0;
		var err_msg='';
		for (x in r.res){
		    if(r.res[x]['res']==1){
			num_changed--;
			Dom.get(x).className='';
			num_ok++;
			Dom.get('save_'+x).style.display='none';
			Dom.get(x).value=r.res[x]['new_value'];
		    }else{
			num_err++;
			err_msg=err_msg+' '+r.res[x]['desc'];
		    }
			
		    
		}
		if(num_err>0)
		    Dom.get('product_messages').innerHTML='<span class="error">'+r.msg+'</span>';
		//	}else
		//    Dom.get('product_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});

}

function delete_list_item (e,id){
    
    cat_td=YAHOO.util.Dom.get('cat_'+id);
    saved=cat_td.getAttribute('saved');
    
    if(cat_td.getAttribute('tipo')==1){
	cat_td.style.textDecoration = 'line-through';
	cat_td.style.color = '#777';
	
	
	YAHOO.util.Dom.get('cat_t_'+id).src='art/icons/arrow_rotate_anticlockwise.png';
	if(saved==1)
	    num_changed++;
	else
	    num_changed--;
	cat_td.setAttribute('tipo',0);
	var new_cat= new Array();
	
	var current_cat=Dom.get('v_cat').value;
	      //	      alert(current_cat);
	current_cat=current_cat.split(',');
	
	
	for (x in current_cat){
	    //		  alert(current_cat[x]+' '+id);
		  if(current_cat[x]!=id)
		      new_cat.push(current_cat[x])
	      }

	      Dom.get('v_cat').value=new_cat.join(',');
	      //alert(Dom.get('v_cat').value)
		  
	      

		  }else{

	      cat_td.style.textDecoration = 'none';
	      cat_td.style.color = '#000';
	      YAHOO.util.Dom.get('cat_t_'+id).src='art/icons/cross.png';
	      if(saved==1)
		  num_changed--;
	      else
		  num_changed++;

	      cat_td.setAttribute('tipo',1);

	      var v_cat=new Array();
	      v_cat=Dom.get('v_cat').value;
	      v_cat=v_cat.split(',');
	      v_cat.push(id);
	      Dom.get('v_cat').value=v_cat.join(',');
	      


	      
	  }
	  //	  alert(num_changed);
    interpet_changes();
}








    var check_number = function(e){
	re=<?=$regex['thousand_sep']?>;
	value=this.value.replace(re,'')
	re=<?=$regex['number']?>;
	re_strict=<?=$regex['strict_number']?>;

	if(!re.test(value)){
	    this.className='text aright error';
	}else if(!re_strict.test(this.value)){
	    this.className='text aright warning';
	}else
	    this.className='text aright ok';
    };

    var check_dimension = function(e,scope){
     
     
	if(typeof(scope)=='undefined')
	    scope=this;
     
	 
	tipo=Dom.get(scope.id+'_shape').selectedIndex;

	if(tipo==0){
	    scope.className='text aright error';
	    return
		}else if(tipo==1)
	    re=<?=$regex['dimension3']?>;
	else if(tipo==3 || tipo==5)
	    re=<?=$regex['dimension2']?>;
	else if(tipo==2 || tipo==4)
	    re=<?=$regex['dimension1']?>;


	re_prepare=<?=$regex['thousand_sep']?>;
	value=scope.value.replace(re_prepare,'')



	if(!re.test(value)){
	    scope.className='text aright error';
	}else
	    scope.className='text aright ok';
    }
    var change_shape= function(e){
	tipo=this.selectedIndex;
	shape_examples=new Array(<?='"'.join('","',$_shape_example).'"'?>)
	Dom.get(this.id+'_ex').innerHTML=shape_examples[tipo];
	check_dimension('',Dom.get(this.id.replace(/_shape/,'')))

    }

    var vadilate = function(o){
	if(o.getAttribute('tipo')=='money' || o.getAttribute('tipo')=='number'  || o.getAttribute('tipo')=='shape2' || o.getAttribute('tipo')=='shape4' ){
	    if(isNaN(o.value))
		 return false;
	    if(o.value.match(/[a-z]/))
		return false;

	}else if(o.getAttribute('tipo')=='shape3' || o.getAttribute('tipo')=='shape5'){
	    if(!o.value.match(/[0-9\.\,]x[0-9\.\,]/))
		return false;
	}else if(o.getAttribute('tipo')=='shape1'){
	    if(!o.value.match(/[0-9\.\,]x[0-9\.\,]x[0-9\.\,]/))
		return false;
	}else if(o.getAttribute('tipo')=='shape0'){
	    return false;
	}else if( o.getAttribute('tipo')=='text_nonull'  ){
	    if(o.value==''){
		return false;
	    }
	}else if(!o.value.match(/[a-z]/))
	    return false;
	
	
	return true;
    }
    var change_textarea=function(e,name){
	//editor.saveHTML(); 
	//html = editor.get('element').value; 

	Dom.get('details_save').style.display='';

    }




var handleSuccess = function(o){
    //    alert(o.responseText);
    var r =  YAHOO.lang.JSON.parse(o.responseText);
    if (r.state == 200) {
	YAHOO.util.Event.removeListener('save', "click");
	Dom.get('save').className='disabled';
	Dom.get('exit').className='ok';
	for (x in r.res){
	    if(r.res[x]['res']==1){
		
		num_changed--;
		Dom.get('c_'+x).style.visibility='visible';
		Dom.get('c_'+x).style.src='art/icons/accept.png';
		var attributes = {opacity: { to: 0 }};
		YAHOO.util.Dom.setStyle('c_'+x, 'opacity', 1);
		var myAnim = new YAHOO.util.Anim('c_'+x, attributes); 
		myAnim.duration = 10; 
		myAnim.animate(); 
		if(x=='details'){
		    Dom.get('i_'+x).style.visibility='hidden';
		}else{
		    Dom.get('v_'+x).className='';
		    Dom.get('v_'+x).setAttribute("ovalue",r.res[x]['new_value']);
		}

	    }else if(r.res[x]['res']==0){
		Dom.get('v_'.x).className='error';
	    }
		
	}

	interpet_changes();

    }
};

var handleFailure = function(o){

};



var callback =
{

    success:handleSuccess,
    failure:handleFailure,
    argument:['foo','bar']
};




var add_list_element=function(e){
    
    var box=Dom.get(this.getAttribute('box'));
    var selected=box.selectedIndex;
    var name=box.options[selected].text;
    var id=box.options[selected].getAttribute('cat_id');
    
    // disable parents 
    var parents=box.options[selected].getAttribute('parents');
    if(parents!=''){
	var _parents = new Array();
	_parents = parents.split(',');
	Dom.get('cat_o_'+id).setAttribute('disabled','disabled');
	for (x in _parents){
	    
	    Dom.get('cat_o_'+_parents[x]).setAttribute('disabled','disabled');
	}
    }
    //add tr to the cat table
    
    table=Dom.get(box.name+'_list');
    var newRow = table.insertRow(0);
    var newCell = newRow.insertCell(0);
    newCell.innerHTML = '<img  src="art/icons/cross.png"  id="cat_t_'+id+'" cat_id="'+id+'" style="cursor:pointer" />';
    YAHOO.util.Event.addListener(newCell, "click", delete_list_item,id);
    var newCell = newRow.insertCell(0);
    newCell.innerHTML = name;
    newCell.id='cat_'+id;
    newCell.setAttribute('tipo','1');
    newCell.setAttribute('saves','0');
    
    YAHOO.util.Event.removeListener('add_cat');
    num_changed++;
    

    
    var v_cat=new Array();
    v_cat=Dom.get('v_cat').value;
    v_cat=v_cat.split(',');
    v_cat.push(id);
    Dom.get('v_cat').value=v_cat.join(',');
    
    
	    
	    

    interpet_changes();
}
var prepare_list_element=function(e){
    selected=this.selectedIndex;
    prev=this.getAttribute('prev')
    if(!(prev==0 || selected==prev))
	alert(prev+' '+selected)
	    }

var change_list_element=function(e){
	    
    selected=this.selectedIndex;
    if(selected==0){
	
    }else{
	item_name=this.options[selected].getAttribute('iname')
	this.options[selected].text=item_name;
	YAHOO.util.Event.addListener('add_cat', "click", add_list_element);
	
	prev=this.getAttribute('prev')
	if(prev>0)
	    this.options[prev].text=this.options[prev].getAttribute('sname');
	
	

	this.setAttribute('prev',selected)
    }
}

    function save_price(key){

	new_value=Dom.get('v_'+key).value;
	//	alert(key+' >'+new_value+'<');
	if(key=='rrp' && new_value=='')
	    value='';
	else
	    value=FormatNumber(Dom.get('v_'+key).value,'.','',2);
	var request='ar_assets.php?tipo=ep_update&key='+escape(key)+'&value='+escape(value);
	//	alert(request);
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    //  alert(o.responseText);
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    //for(x in r['res'])
		    //	alert(x+' '+r[x])
		    if(r.ok){
			Dom.get(key+'_change').innerHTML='';
			Dom.get(key+'_save').style.visibility='hidden';
			Dom.get('v_'+key).setAttribute('ovalue',new_value);
		    }else
			alert(r.msg);
		}
		 
	    });
    }


 function save_description(key){

	new_value=Dom.get('v_'+key).value;
	if(key=='rrp' && new_value=='')
	    value='';
	else if(key=='price' && key=='rrp')
	    value=FormatNumber(Dom.get('v_'+key).value,'.','',2);
	else if (key=='details'){
	    	editor.saveHTML(); 
		value = editor.get('element').value; 
	}else
	    value=Dom.get('v_'+key).value;
	var request='ar_assets.php?tipo=ep_update&key='+escape(key)+'&value='+escape(value);
	alert(request);
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    //alert(o.responseText);
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    //for(x in r['res'])
		    //	alert(x+' '+r[x])
		    if(r.ok){
			Dom.get(key+'_change').innerHTML='';
			Dom.get(key+'_save').style.display='none';
			Dom.get(key+'_icon').style.visibility='hidden';

			Dom.get('v_'+key).setAttribute('ovalue',new_value);
		    }else
			alert(r.msg);
		}
		 
	    });
    }




var change_block = function(e){
    

    Dom.get('d_suppliers').style.display='none';
    Dom.get('d_pictures').style.display='none';
    Dom.get('d_suppliers').style.display='none';
    Dom.get('d_prices').style.display='none';
    Dom.get('d_dimat').style.display='none';
    Dom.get('d_description').style.display='none';
    Dom.get('d_'+this.id).style.display='';
    

    
    Dom.get('suppliers').className='';
    Dom.get('pictures').className='';
    Dom.get('suppliers').className='';
    Dom.get('prices').className='';
    Dom.get('dimat').className='';
    Dom.get('description').className='';
    Dom.get(this.id).className='selected';
    
    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=product-edit&value='+this.id );
    
    editing=this.id;


}

function init(){


    //var ids = ["v_description","v_sdescription"]; 
    //	YAHOO.util.Event.addListener(ids, "keyup", change_element);

	var ids = ["cat_select"]; 
	YAHOO.util.Event.addListener(ids, "change", change_list_element);
	var ids = ["description","pictures","prices","suppliers","dimat"]; 
	YAHOO.util.Event.addListener(ids, "click", change_block);
	
	//	YAHOO.util.Event.addListener(ids, "click", prepare_list_element);


	//	var ids = ["v_details"]; 
	//YAHOO.util.Event.addListener(ids, "keyup", change_textarea);




	//Tooltips
	//var myTooltip = new YAHOO.widget.Tooltip("myTooltip", { context:"upo_label,outall_label,awoutall_label,awoutq_label"} ); 


	//Details textarea editor ---------------------------------------------------------------------
	var texteditorConfig = {
	    height: '300px',
	    width: '730px',
	    dompath: true,
	    focusAtStart: true
	};     

 	editor = new YAHOO.widget.Editor('v_details', texteditorConfig);

	editor._defaultToolbar.buttonType = 'basic';
 	editor.render();

	editor.on('editorKeyUp',change_textarea,'details' );
	//-------------------------------------------------------------


	cat_list = new YAHOO.widget.Menu("catlist", {context:["browse_cat","tr", "br","beforeShow"]  });

	cat_list.render();

	cat_list.subscribe("show", cat_list.focus);

	YAHOO.util.Event.addListener("browse_cat", "click", cat_list.show, null, cat_list); 


}

var change_dim_tipo=function(tipo){
    Dom.get('dim_shape').innerHTML=shapes[tipo];
    Dom.get('dim_shape_example').innerHTML=shapes_example[tipo];

    Dom.get('dim').setAttribute('tipo','shape'+tipo);
    change_element(Dom.get('dim'));
}

YAHOO.util.Event.onContentReady("shapes", function () {

	var oMenu = new YAHOO.widget.Menu("shapes", { context:["dim_shape","tr", "br"]  });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	YAHOO.util.Event.addListener("dim_shape", "click", oMenu.show, null, oMenu);
    });



    YAHOO.util.Event.onDOMReady(init);


var supplier_selected=function(sType, aArgs){
    var myAC = aArgs[0]; // reference back to the AC instance
    var elLI = aArgs[1]; // reference to the selected LI element
    var oData = aArgs[2]; // object literal of selected item's result data

    Dom.get('new_supplier_form').style.display='';

    Dom.setStyle('current_suppliers_form', 'opacity', .25); 

    Dom.get('new_supplier_name').innerHTML=oData.names;
    Dom.get('new_supplier_form').setAttribute('supplier_id',oData.id);
    Dom.get('new_supplier_input').value='';
};

var save_supplier=function(supplier_id){
    var cost=Dom.get('v_supplier_cost'+supplier_id).value;
    var code=Dom.get('v_supplier_code'+supplier_id).value;
    var request='ar_assets.php?tipo=ep_update_supplier&op_tipo=update&supplier_id='+ escape(supplier_id)+'&cost='+ escape(cost)+'&code='+ escape(code);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    //retutn to normal classes
		    if(Dom.get('v_supplier_cost'+supplier_id).className=='ok'){
			Dom.get('v_supplier_cost'+supplier_id).className='';
			num_changed--;
		    }
		    if(Dom.get('v_supplier_code'+supplier_id).className=='ok'){
			Dom.get('v_supplier_code'+supplier_id).className='';
			num_changed--;
		    }

		    
		}else
		    Dom.get('edit_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});
};



var save_new_supplier=function(){
    var cost=Dom.get('new_supplier_cost').value;
    var code=Dom.get('new_supplier_code').value;
    var supplier_id=Dom.get('new_supplier_form').getAttribute('supplier_id');
    var request='ar_assets.php?tipo=ep_update_supplier&op_tipo=new&supplier_id='+ escape(supplier_id)+'&cost='+ escape(cost)+'&code='+ escape(code);

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {

		var r =  YAHOO.lang.JSON.parse(o.responseText);

		if (r.state == 200) {


		//     var row = Dom.get('location_table').insertRow(r.where);
// 		    row.setAttribute("id", 'row_'+r.id );
// 		    row.setAttribute("pl_id", r.pl_id );
// 		    var cellLeft = row.insertCell(0);
// 		    cellLeft.setAttribute("id", 'loc_name'+r.id );
// 		    var cellLeft = row.insertCell(1);
// 		    cellLeft.setAttribute("id", 'loc_tipo'+r.id );
// 		    var cellLeft = row.insertCell(2);
// 		    var span = document.createElement("span");
// 		    span.innerHTML=' &uarr; ';
// 		    span.setAttribute("onclick", "rank_up("+r.id+")" );
// 		    span.setAttribute("style", "cursor:pointer" );
// 		    span.setAttribute("id", 'loc_picking_up'+r.id );
// 		    cellLeft.appendChild(span);
// 		    var span = document.createElement("span");
// 		    span.setAttribute("id", 'loc_picking_tipo'+r.id );
// 		    cellLeft.appendChild(span);
// 		    cellLeft.style.textAlign='right';
// 		    cellLeft.setAttribute("id", 'loc_pick_info'+r.id );
// 		    var img = document.createElement("img");
// 		    img.setAttribute("src", "art/icons/basket.png" );
// 		    img.setAttribute("style", "cursor:pointer" );
// 		    img.setAttribute("title", "" );
// 		    img.setAttribute("id", 'loc_picking_img'+r.id );
// 		    //img.setAttribute("onclick", "desassociate_loc()" );
// 		     cellLeft.appendChild(img);


// 		     var cellLeft = row.insertCell(3);
// 		     cellLeft.setAttribute("id", 'loc_stock'+r.id );
// 		     cellLeft.style.textAlign='right';
// 		     var cellLeft = row.insertCell(4);
// 		     var img = document.createElement("img");
// 		     img.setAttribute("src", "art/icons/cross.png" );
// 		     img.setAttribute("style", "cursor:pointer" );
// 		     img.setAttribute("title", "<?=_('Free the location')?>" );
// 		     img.setAttribute("id", 'loc_del'+r.id );
// 		     img.setAttribute("onclick", "desassociate_loc("+r.id+")" );
		     
// 		     cellLeft.appendChild(img);
// 		     location_data=r.data;
// 		     clear_actions();
		    
		}else
		    Dom.get('edit_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});
};
var cancel_new_supplier=function(){
    Dom.setStyle('current_suppliers_form', 'opacity', 1.0); 
    Dom.get('new_supplier_form').style.display='none';
    Dom.get('new_supplier_name').innerHTML='';
    Dom.get('new_supplier_form').setAttribute('supplier_id','');


}	





YAHOO.util.Event.onContentReady("adding_new_supplier", function () {
	var oDS = new YAHOO.util.XHRDataSource("ar_suppliers.php");
 	oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	oDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["name","code","id","names"]
 	};

 	var oAC = new YAHOO.widget.AutoComplete("new_supplier_input", "new_supplier_container", oDS);
 	oAC.resultTypeList = false; 
	oAC.generateRequest = function(sQuery) {
 	    return "?tipo=suppliers_name&except_product=<?=$_SESSION['state']['product']['id']?>&query=" + sQuery ;
 	};
	oAC.forceSelection = true; 
	oAC.itemSelectEvent.subscribe(supplier_selected); 
    });




