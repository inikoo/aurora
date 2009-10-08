<?php
include_once('common.php');

print "var description=new Object();\ndescription={'name':{'changed':0,'column':'Product Name'},'special_char':{'changed':0,'column':'Product Special Characteristic'},'details':{'changed':0,'column':'Product Description'}";
if(isset($_REQUEST['cats'])){
    $cats=preg_split('/,/',$_REQUEST['cats']);
    foreach($cats as $cat){
	printf(",'cat_%s>':{'changed':0,'column':'Category'}",$cat);
    }
    print "};\n";
}

?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;

var current_form = 'description';
var num_changed = 0;
var num_errors = 0;
var editor;
var editing='<?php echo $_SESSION['state']['product']['edit']?>';



var description_warnings= new Object();
var description_errors= new Object();


var thousands_sep='<?php echo $_SESSION['locale_info']['thousands_sep']?>';
var decimal_point='<?php echo $_SESSION['locale_info']['decimal_point']?>';
var currency_symbol='<?php if(isset( $_REQUEST['symbol'])){echo $_REQUEST['symbol'];}else{echo $myconf['currency_symbol'];}?>';
var product_id='<?php if(isset( $_REQUEST['product_id'])){echo $_REQUEST['product_id'];}else{exit();}?>';

function undo(tipo){

    if(tipo=='description'){
	//	Dom.get('name').value=Dom.get('name').getAttribute('ovalue');
	//Dom.get('special_char').value=Dom.get('special_char').getAttribute('ovalue');

	for(i in description){
	    if(i.match(/cat_\d+>/g)){
		children=Dom.getChildren(i);
		for(j=0;j<children.length;j++){
		    item=children[j];
		    ovalue=item.getAttribute('ovalue');
		    item.setAttribute('value',ovalue);
		    if(ovalue==1)
			Dom.addClass(item,'selected');
		    else
			Dom.removeClass(item,'selected');
		}
	    }else if(i=='details'){
		//	Dom.get(i).innerHTML=Dom.get(i).getAttribute('ovalue');	
		editor.setEditorHTML(Dom.get(i).getAttribute('ovalue')); 
		
	    }else
		Dom.get(i).value=Dom.get(i).getAttribute('ovalue');	
	
	    description[i].changed=0;
	    
	}
	
	

	//editor.render();
	//description_num_changed=0;
	//Dom.get(tipo+'_save').style.display='none';
	//Dom.get(tipo+'_undo').style.display='none';
	
	//alert(tipo+'_undo');
	
	//Dom.get(tipo+'_num_changes').innerHTML=description_num_changed;
	description_warnings= new Object();
	description_errors= new Object();
	//	alert('x');
    }
    save_menu();
};

// function group_checkbox_changed(o){
    
//     value=o.getAttribute('value');
//     parent_id='cat_'+o.getAttribute('parent')+'>';
//     if(!Dom.hasClass(o,'selected')){

// 	var elements=Dom.getElementsByClassName('selected','span',parent_id);
// 	Dom.removeClass(elements,'selected');
	

// 	Dom.addClass(o,"selected");
// 	o.setAttribute('value',1);
//     }




//     if(o.getAttribute('ovalue')==value)
// 	description_num_changed++;
//     else
// 	description_num_changed--;
    
//     save_menu();


// }




function checkbox_changed(o){
    parent_id='cat_'+o.getAttribute('parent')+'>';
    default_cat='cat'+Dom.get(parent_id).getAttribute('default_cat');

    if(default_cat==o.id){
	value=o.getAttribute('value');
	if(value==0){
	    var elements=Dom.getElementsByClassName('selected','span',parent_id);
	    Dom.removeClass(elements,'selected');
	    for (x in elements){
		Dom.get(elements[x]).setAttribute('value',0);
	    }
	    Dom.addClass(o,"selected");
	    o.setAttribute('value',1);

	}



    }else{

	value=o.getAttribute('value');
	if(value==1){
	    Dom.removeClass(o,"selected");
	    o.setAttribute('value',0);
	}else{
	    Dom.addClass(o,"selected");
	    o.setAttribute('value',1);
	}

	var num_selected=0;
	var elements=Dom.getElementsByClassName('selected','span',parent_id);
	for (x in elements){
	    num_selected++;
	}
	
	//	alert(default_cat);
	if(Dom.hasClass(default_cat,'selected') && num_selected>1){
	    Dom.removeClass(default_cat,'selected');
	    Dom.get(default_cat).setAttribute('value',0);
	}else if(num_selected==0){
	    Dom.addClass(default_cat,'selected');
	    Dom.get(default_cat).setAttribute('value',1);
	}
  	


    }

    var changed=false;
    var elements=Dom.getElementsByClassName('catbox','span',parent_id);
    
    for (x in elements){
	if(elements[x].getAttribute('ovalue')!=elements[x].getAttribute('value')){
	    changed=true;
	    break;
	}
	
    }
  

    if(changed)
	description[parent_id].changed=1;
    else
	description[parent_id].changed=0;
    
    save_menu();


}
function save_menu(){
    if(editing=='description'){
	this_errors=description_errors;
	var this_num_changed=0
	for (i in description){
	    if(description[i].changed==1){
		//	alert(i);
		this_num_changed++;
	    }
	}

	
    }

    if(this_num_changed>0){
	Dom.get(editing+'_save').style.display='';
	Dom.get(editing+'_undo').style.display='';

    }else{
	Dom.get(editing+'_save').style.display='none';
	Dom.get(editing+'_undo').style.display='none';

    }
    Dom.get(editing+'_num_changes').innerHTML=this_num_changed;

    // Dom.get(editing+'_save_div').style.display='';
    errors_div=Dom.get(editing+'_errors');
    // alert(errors);
    errors_div.innerHTML='';


    for (x in this_errors)
	{
	    // alert(errors[x]);
	    Dom.get(editing+'_save').style.display='none';
	    errors_div.innerHTML=errors_div.innerHTML+' '+this_errors[x];
	}
    

}


var cat_list;
jsonString='<?php echo $_SESSION['state']['product']['shapes'];?>';
try {
    var shapes = YAHOO.lang.JSON.parse(jsonString);
}
catch (e) {
    alert("ERROR:P_PES_JSONDATA");
};
jsonString='<?php echo $_SESSION['state']['product']['shapes_example'];?>';
try {
    var shapes_example = YAHOO.lang.JSON.parse(jsonString);
}
catch (e) {
    alert("ERROR:P_PES_JSONDATA");
};

var cats=new Object;
function handleSuccess(o){
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
function handleFailure(o){

};
var callback ={
    success:handleSuccess,
    failure:handleFailure,
    argument:['foo','bar']
};

function select_cat(o,e){
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
function to_save_on_enter(e,o){
     var key;     
     if(window.event)
          key = window.event.keyCode; //IE
     else
          key = e.which; //firefox     

     //     alert(key);
     if (key == 13){
	 //	 alert(o.name+'_save');
	
	 o.blur();

     }
 }
function is_diferent(v1,v2,tipo){

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
function change_units(){
	Dom.get("units_cancel").style.visibility='visible';
	Dom.get("change_units_but").style.display='none';
	Dom.get("units").style.display='none';
	Dom.get("v_units").style.display='';
	Dom.get("change_units_price").style.display='';
	Dom.get("change_units_oweight").style.display='';
	Dom.get("change_units_odim").style.display='';
	Dom.get("change_units_odim_example").style.display='';
	Dom.get("change_units_tipo_but").style.display='none';
    }
function change_element(o){

       
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

	if(editing=='parts'){
	    interpet_changes(o.getAttribute('part_id'));
	}else
	    interpet_changes(o.id);
	


    }
function part_changed(o,id){
     var code=Dom.get('v_part_code'+id);
     var cost=Dom.get('v_part_cost'+id);
     name=o.name;
     if(cost.value!=cost.getAttribute('ovalue') || code.value!=code.getAttribute('ovalue')){
	 Dom.get('save_part_'+id).style.visibility='visible';
     }else
	 Dom.get('save_part_'+id).style.visibility='hidden';
     
 }
function formated_price_change(old_value,new_value){
  
    var diff=new_value-old_value;
    return money(diff)+' '+percentage(diff,old_value);

    }

function return_to_old_value(key){
    Dom.get("v_"+key).value=Dom.get("v_"+key).getAttribute('ovalue');
    
    if(key=='description')
	description_changed(Dom.get("v_"+key));

}
function change_to_dependant(){
    Dom.get("product_tipo_dependant").style.display='';

}
function units_save(){
    var units=Dom.get('v_units').value;
    var price=Dom.get('v_price').value;
    var oweight=Dom.get('v_oweight_fcu').value;
    var name='odim_fcu';
    var odim=Dom.get('v_'+name).getAttribute('tipo')+'_'+Dom.get('v_'+name).value;

    var request='ar_assets.php?tipo=ep_update&key=units'+'&value='+escape(units)+'&price='+escape(price)+'&oweight='+escape(oweight)+'&odim='+escape(odim);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.ok){
		    Dom.get("units_cancel").style.visibility='hidden';
		    Dom.get("units_save").style.visibility='hidden';

		    Dom.get("change_units_diff").style.display='none';
		    Dom.get("change_units_but").style.display='';
		    Dom.get("units").style.display='';
		    Dom.get("v_units").style.display='none';
		    Dom.get("change_units_price").style.display='none';
		    Dom.get("change_units_oweight").style.display='none';
		    Dom.get("change_units_odim").style.display='none';
		    Dom.get("change_units_odim_example").style.display='none';
		    Dom.get("change_units_tipo_but").style.display='';
		    Dom.get("v_units").setAttribute('ovalue',units);
		    Dom.get("v_oweight_fcu").setAttribute('ovalue',oweight);
		    Dom.get("v_odim_fcu").setAttribute('ovalue',odim);
		    Dom.get("v_price_fcu").setAttribute('ovalue',price);
		    Dom.get("change_units_price_diff").style.display='none';
		    Dom.get("change_units_oweight_diff").style.display='none';
		    Dom.get("change_units_odim_diff").style.display='none';
		    Dom.get('edit_messages').innerHTML='<span>'+r.msg+'</span>';
		}else
		    Dom.get('edit_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	    
	    });


}
function delete_part(id,name){
    var answer = confirm("<?php echo _('Are you sure you want to desassociate this part')?> ("+name+")");
    if (answer){

	var request='ar_assets.php?tipo=ep_update&key=part_delete'+'&value='+escape(id);
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    //alert(o.responseText);
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.ok){
			el=Dom.get("sup_tr1_"+id);
			el.parentNode.removeChild(el);
			el=Dom.get("sup_tr2_"+id);
			el.parentNode.removeChild(el);
			el=Dom.get("sup_tr3_"+id);
			el.parentNode.removeChild(el);
			el=Dom.get("sup_tr4_"+id);
			el.parentNode.removeChild(el);
		    }else
			alert(r.msg);
		}
		
	    });
    }


}
function delete_image(image_id,image_name){
    var answer = confirm("<?php echo _('Are you sure you want to delete this image')?> ("+image_name+")");
    if (answer){

	

	var request='ar_assets.php?tipo=ep_update&key=img_delete'+'&value='+escape(image_id);
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    //alert(o.responseText);
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.ok){
			Dom.get('image'+image_id).style.display='none';
			if(r.new_principal!=''){
			    var new_principal=r.new_principal;
			    Dom.get('images').setAttribute('principal',new_principal);
			    var new_but=Dom.get('img_set_principal'+new_principal);
			    new_but.setAttribute('title','<?php echo _('Main Image')?>');
			    new_but.setAttribute('principal',1);
			    new_but.setAttribute('src',"art/icons/asterisk_orange.png");		
			    new_but.style.cursor="default";
			}

		    }else
			alert(r.msg);
		}
		
	    });
    }


}
function set_image_as_principal(o){

    if(o.getAttribute('principal')==1)
	return;
    image_id=o.getAttribute('image_id');

    var request='ar_assets.php?tipo=ep_update&key=img_set_principal'+'&value='+escape(image_id);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.ok){
			var old_principal=Dom.get('images').getAttribute('principal');
			var new_principal=image_id;
			Dom.get('images').setAttribute('principal',new_principal);
			var old_but=Dom.get('img_set_principal'+old_principal);
			var new_but=Dom.get('img_set_principal'+new_principal);
			old_but.setAttribute('title','<?php echo _('Set as the principal image')?>');
			old_but.setAttribute('principal',0);
			old_but.setAttribute('src',"art/icons/picture_empty.png");
			old_but.style.cursor="pointer";
			new_but.setAttribute('title','<?php echo _('Main Image')?>');
			new_but.setAttribute('principal',1);
			new_but.setAttribute('src',"art/icons/asterisk_orange.png");		
			new_but.style.cursor="default";
		    }else
			alert(r.msg);
		}
		 
	    });

}
function caption_changed(o){
    if(o.value!=o.getAttribute('ovalue')){
	Dom.get("save_img_caption"+o.getAttribute('image_id')).style.visibility='visible';
    }else
	Dom.get("save_img_caption"+o.getAttribute('image_id')).style.visibility='hidden';

}
function description_changed(o){
	var ovalue=o.getAttribute('ovalue');
	var name=o.name;
	var id=o.id;
	    
	if(ovalue!=o.value){
	    if(id=='name'){
		if(o.value==''){
		    description_errors.description="<?php echo _("The product name can not be empty")?>";
		}
		else if(o.value.lenght>75){
		    description_errors.description="<?php echo _("The product name can not be empty")?>";
		}else
		    delete description_errors.description


	    }else if(id='special_char'){
		if(o.value==''){
		    description_errors.sdescription="<?php echo _("The product short description can not be empty")?>";
		  //   save_menu();
// 		    return;
		}
		if(o.value.lenght>75){
		    description_errors.sdescription="<?php echo _("The product short description can not be longer the 40 characters")?>";
		 //    save_menu();
// 		    return;
		}else
		    delete description_errors.sdescription;
					
	    }
	    description[id].changed=1;

	    
	}
	else{

	    description[id].changed=0;

	}
    
	 save_menu();
	
    }

function units_changed(o){
	var ovalue=o.getAttribute('ovalue');
	var name=o.name;

	if(ovalue!=o.value){

	    Dom.get(name+"_save").style.visibility='visible';
	    //Dom.get(name+"_cancel").style.display='none';
	    Dom.get("change_units_diff").style.display='';
	    if(o.value==0){
		Dom.get(name+"_save").style.visibility='hidden';
		Dom.get(name+"_cancel").style.display='';
	   
		Dom.get("change_units_diff").innerHTML='<?php echo _('Error')?>';
	    }else{
		Dom.get("change_units_diff").innerHTML=percentage(ovalue,o.value);
		Dom.get("change_units_price_diff").innerHTML=percentage(Dom.get("v_price_fcu").getAttribute('ovalue'),Dom.get("v_price_fcu").value);	
		Dom.get("change_units_oweight_diff").innerHTML=percentage(Dom.get("v_oweight_fcu").getAttribute('ovalue'),Dom.get("v_oweight_fcu").value);	
		Dom.get("change_units_odim_diff").innerHTML=percentage(Dom.get("v_odim_fcu").getAttribute('ovalue'),Dom.get("v_odim_fcu").value);	

	    
	    }	    
	    
	    
	    
	}else{

	    Dom.get(name+"_save").style.visibility='hidden';
	    //Dom.get(name+"_cancel").style.display='';
	    Dom.get("change_units_diff").style.display='none';
	    
	}
}
function weight_changed(o){
	var ovalue=o.getAttribute('ovalue');
	var name=o.name;
	if(ovalue!=o.value){
	    Dom.get(name+"_save").style.visibility='visible';

		
	}else{
	    Dom.get(name+"_save").style.visibility='hidden';
	}
}
function validate_dim(value,tipo){
    switch(tipo){
    case('shape0'):
	return {ok:false,msg:''};
	break;
    case('shape1'):
	if(!value.match(/^[0-9\<?php echo $_SESSION['locale_info']['decimal_point']?>]+x[0-9\<?php echo $_SESSION['locale_info']['decimal_point']?>]+x[0-9\<?php echo $_SESSION['locale_info']['decimal_point']?>]+$/))
	    return {ok:false,msg:''};
	else{
	    var dim=value.split("x",3);
	    var vol=dim[0]*dim[1]*dim[2];
	    if(vol==0)
		return {ok:false,msg:'<?php echo _('Zero volumen')?>'};
	    else
		return {ok:true,msg:'',vol:vol};

	}
	break;
    case('shape2'):
    case('shape4'):
	if(!value.match(/^[0-9\<?php echo $_SESSION['locale_info']['decimal_point']?>\s]+$/))
	    return {ok:false,msg:''};
	else
	    return {ok:true,msg:''};
	break;	
    case('shape3'):
    case('shape5'):
	if(!value.match(/^[0-9\<?php echo $_SESSION['locale_info']['decimal_point']?>]+x[0-9\<?php echo $_SESSION['locale_info']['decimal_point']?>]+$/))
	    return {ok:false,msg:''};
	else
	    return {ok:true,msg:''};
	break;
    default:
	
    }

    alert(value+" "+tipo);
    return true;

}
function dim_changed(o){


    var tipo=o.getAttribute('tipo');
    var name=o.name;

    if(validate_dim(o.value,tipo).ok){
	var ovalue=o.getAttribute('ovalue');
	if(ovalue!=o.value){
	    Dom.get(name+"_save").style.visibility='visible';
	    
	}else{
	    Dom.get(name+"_save").style.visibility='hidden';
	}
	Dom.get(name+"_alert").style.visibility='hidden';
		
    }else{

	Dom.get(name+"_save").style.visibility='hidden';
	Dom.get(name+"_alert").style.visibility='visible';

    }

}

function oweight_fcu_changed(o){
    var ovalue=o.getAttribute('ovalue');
    Dom.get('change_units_oweight_diff').innerHTML=percentage(ovalue,o.value);
}
function odim_fcu_changed(o){
    
    tipo_shape=o.getAttribute('tipo');
    data_old=validate_dim(o.getAttribute('ovalue'),tipo_shape);
    data_new=validate_dim(o.value,tipo_shape);
    if(!data_new.ok){
	Dom.get('change_units_odim_diff').innerHTML='<?php echo _('Error')?>';
	
    }else{
	if(data_old.ok)
	    Dom.get('change_units_odim_diff').innerHTML=percentage(data_old.vol,data_new.vol);
    }
}
function price_changed(o){
   
   
    
    var name=o.name;
    var units=parse_number(Dom.get('v_units').value);
    var cost=Dom.get('v_cost').value;
    
    
    var ovalue=parse_money(o.getAttribute('ovalue'));

   
    var value=parse_money(o.value);
    var diff=value-ovalue;
   
    if(name=='price'){
	
	var rrp=units*parse_money(Dom.get('v_rrp').value);
	
	Dom.get("price_change").innerHTML=money(diff,false,true)+' '+percentage(diff,ovalue,1,'NA','%',true);;
	Dom.get("price_ou").innerHTML=money(value/units);
	Dom.get("price_margin").innerHTML=percentage(value-cost,value);
	Dom.get("rrp_margin").innerHTML=percentage(rrp-value,rrp);
	Dom.get('v_price').value=money(value);
    }else if(name=='rrp'){
	var price=parse_money(Dom.get('v_price').value);
	Dom.get("rrp_change").innerHTML=money(diff,false,true)+' '+percentage(diff,ovalue,1,'NA','%',true);;
	Dom.get("rrp_ou").innerHTML=money(value*units);
	//	alert(value*units+' '+price)
	    Dom.get("rrp_margin").innerHTML=percentage((units*value)-price,units*value);
	Dom.get('v_rrp').value=money(value);

    }

    if(ovalue!=value){
	
	Dom.get(name+"_save").style.visibility='visible';
	Dom.get(name+"_undo").style.visibility='visible';
	    
	    
    }else{
	Dom.get(name+"_save").style.visibility='hidden';
	Dom.get(name+"_undo").style.visibility='hidden';
	    
    }
    
	
	
    }
function save_form(){
    if(current_form == 'description')
	editor.saveHTML();
    YAHOO.util.Connect.setForm(document.getElementById(current_form)); 
    var request = YAHOO.util.Connect.asyncRequest('POST', 'ar_assets.php', callback);
    }
function interpet_changes(id){
    
    if(editing=='parts'){
	if(num_changed>0 && num_errors==0){
	    Dom.get('save_part_'+id).style.display='';
	}else
	    Dom.get('save_part_'+id).style.display='none';

	
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
	var value = Dom.get('v_'+name).getAttribute('tipo')+'_'+Dom.get('v_'+name).value;
    else
	var value = Dom.get('v_'+name).value;

    var request='ar_assets.php?tipo=ep_update&key='+ escape(name)+'&value='+ escape(value);
    // alert(request)
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText);
		
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.ok){
		Dom.get(name+'_save').style.visibility='hidden';
		Dom.get('v_'+name).setAttribute('ovalue',value);
		Dom.get('edit_messages').innerHTML=r.msg;
		}else{
		    Dom.get('edit_messages').innerHTML='<span class="error">'+r.msg+'</span>';
		}
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
function check_number(e){
	re=<?php echo $regex['thousand_sep']?>;
	value=this.value.replace(re,'')
	re=<?php echo $regex['number']?>;
	re_strict=<?php echo $regex['strict_number']?>;

	if(!re.test(value)){
	    this.className='text aright error';
	}else if(!re_strict.test(this.value)){
	    this.className='text aright warning';
	}else
	    this.className='text aright ok';
    };
function check_dimension(e,scope){
     
     
	if(typeof(scope)=='undefined')
	    scope=this;
     
	 
	tipo=Dom.get(scope.id+'_shape').selectedIndex;

	if(tipo==0){
	    scope.className='text aright error';
	    return
		}else if(tipo==1)
	    re=<?php echo $regex['dimension3']?>;
	else if(tipo==3 || tipo==5)
	    re=<?php echo $regex['dimension2']?>;
	else if(tipo==2 || tipo==4)
	    re=<?php echo $regex['dimension1']?>;


	re_prepare=<?php echo $regex['thousand_sep']?>;
	value=scope.value.replace(re_prepare,'')



	if(!re.test(value)){
	    scope.className='text aright error';
	}else
	    scope.className='text aright ok';
    }
function change_shape(e){
	tipo=this.selectedIndex;
	shape_examples=new Array(<?php echo'"'.join('","',$_shape_example).'"'?>)
	Dom.get(this.id+'_ex').innerHTML=shape_examples[tipo];
	check_dimension('',Dom.get(this.id.replace(/_shape/,'')))

    }
function vadilate(o){
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
function change_textarea(e,name){


    
	editor.saveHTML(); 
	var html = editor.get('element').value; 
	var length=html.length;
	o=Dom.get(name);
	olength=o.getAttribute('olength');
	//	alert(olength+' '+length)
	if(olength==length){
	    
	    if(length<=256){
		//	alert(html+' > '+o.getAttribute('ovalue'))
		if(html==o.getAttribute('ovalue')){
		    description['details'].changed=0;
		}else{
		    description['details'].changed=1;
		}
		
	    }else{

		var hash =md5(html);
		ohash=o.getAttribute('ohash');
		if(ohash==hash){
		    description['details'].changed=0;
		}else{
		    description['details'].changed=1;
		}
	    }


	}else
	    description['details'].changed=1; 



	save_menu();
    }
function add_list_element(e){
    
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
function prepare_list_element(e){
    selected=this.selectedIndex;
    prev=this.getAttribute('prev')
    if(!(prev==0 || selected==prev))
	alert(prev+' '+selected)
	    };
function chane_units_tipo(id,name,sname){
    var current=Dom.get('v_units_tipo').getAttribute('ovalue');
    if(id!=current){
	Dom.get('v_units_tipo').innerHTML=name;
	Dom.get('units_tipo_plural').innerHTML=sname;
	Dom.get('v_units_tipo').setAttribute('value',id);
	Dom.get('units_tipo_save').style.visibility='visible';
    }else{
	Dom.get('units_tipo_save').style.visibility='hidden';

    }
    
}
function change_list_element(e){
	    
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


function save_image(key,image_id){
    new_value=Dom.get(key+image_id).value;
    var request='ar_assets.php?tipo=ep_update&key='+escape(key)+'&value='+escape(new_value)+'&image_id='+image_id;
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    alert(o.responseText);
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    //for(x in r['res'])
		    //	alert(x+' '+r[x])
		    if(r.ok){
			
			Dom.get('save_'+key+image_id).style.visibility='hidden';
			Dom.get(key+image_id).setAttribute('ovalue',new_value);
		    }else
			alert(r.msg);
		}
		 
	    });

}

function change_block(e){
   
  if(editing!=this.id){
	if(this.id=='pictures' || this.id=='prices'){
	    Dom.get('info_name').style.display='';
	}else
	    Dom.get('info_name').style.display='none';

	if(this.id=='prices'){
	    Dom.get('info_price').style.display='';
	}else
	    Dom.get('info_price').style.display='none';
    }


	Dom.get('d_parts').style.display='none';
	Dom.get('d_pictures').style.display='none';
	Dom.get('d_parts').style.display='none';
	Dom.get('d_prices').style.display='none';
	Dom.get('d_dimat').style.display='none';
	Dom.get('d_config').style.display='none';
	Dom.get('d_description').style.display='none';
	Dom.get('d_'+this.id).style.display='';
	Dom.removeClass(editing,'selected');
	
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=product-edit&value='+this.id );
	
	editing=this.id;


}
function change_dim_tipo(tipo){
    Dom.get('dim_shape').innerHTML=shapes[tipo];
    Dom.get('dim_shape_example').innerHTML=shapes_example[tipo];

    Dom.get('v_dim').setAttribute('tipo','shape'+tipo);
    dim_changed(Dom.get('v_dim'));
}
function part_selected(sType, aArgs){
    var myAC = aArgs[0]; // reference back to the AC instance
    var elLI = aArgs[1]; // reference to the selected LI element
    var oData = aArgs[2]; // object literal of selected item's result data

    Dom.get('new_part_form').style.display='';

    Dom.setStyle('current_parts_form', 'opacity', .25); 

    Dom.get('new_part_name').innerHTML=oData.names;
    Dom.get('new_part_form').setAttribute('part_id',oData.id);
    Dom.get('new_part_input').value='';
    Dom.get('new_part_cost').value='';
    Dom.get('new_part_code').value='';


};
function save_part(part_id){
    var cost=Dom.get('v_part_cost'+part_id).value;
    var code=Dom.get('v_part_code'+part_id).value;
    var request='ar_assets.php?tipo=ep_update&key=part&value='+ escape(part_id)+'&sup_cost='+ escape(cost)+'&sup_code='+ escape(code);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
			alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.ok) {
		    Dom.get('save_part_'+part_id).style.visibility='hidden';
		}else
		    Dom.get('edit_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});
};
function save_new_part(){
    var cost=Dom.get('new_part_cost').value;
    var code=Dom.get('new_part_code').value;
    var part_id=Dom.get('new_part_form').getAttribute('part_id');
    var request='ar_assets.php?tipo=ep_update&key=part_new&value='+ escape(part_id)+'&sup_cost='+ escape(cost)+'&sup_code='+ escape(code);

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);

		if (r.ok) {

		    tbody=Dom.get("current_parts_form");
		    
		    var tr = document.createElement("tr");
		    tr.setAttribute("id",'sup_tr1_'+part_id );
		    tr.setAttribute("class","top title" );
		    var td = document.createElement("td");
		    td.setAttribute("class","label" );
		    td.setAttribute("colspan","2" );
		    var img = document.createElement("img");
		    img.setAttribute("class","icon" );
		    img.setAttribute("id","delete_part_"+part_id );
		    img.setAttribute("onClick","delete_part("+part_id+",'"+r.data.code+"')");
		    img.setAttribute("src","art/icons/cross.png");
		    td.appendChild(img);
		    var img = document.createElement("img");
		    img.setAttribute("class","icon" );
		    img.setAttribute("id","save_part_"+part_id );
		    img.setAttribute("onClick","save_part("+part_id+")");
		    img.setAttribute("src","art/icons/disk.png");
		    img.setAttribute("style","visibility:hidden");
		    td.appendChild(img);
		    var txt = document.createElement("textNode");
		    txt.innerHTML=r.data.code;
		    td.appendChild(txt);
		    tr.appendChild(td);
		    tbody.appendChild(tr);


		    var tr = document.createElement("tr");
		    tr.setAttribute("id",'sup_tr2_'+part_id );
		    var td = document.createElement("td");
		    td.setAttribute("class","label" );
		    td.setAttribute("style","width:15em" );
		    td.innerHTML='<?php echo _('Parts product code')?>:';
		    tr.appendChild(td);
		    var td = document.createElement("td");
		    td.setAttribute("style","text-align:left;" );
		    var input = document.createElement("input");
		    input.setAttribute("id","v_part_code"+part_id );
		    input.setAttribute("style","padding-left:2px;text-align:left;width:10em" );
		    input.setAttribute("ovalue",r.data.part_product_code );
		    input.setAttribute("name",'code' );
		    input.setAttribute("onkeyup","part_changed(this,"+part_id+")" );
		    input.value=r.data.part_product_code;
		    td.appendChild(input);
		    tr.appendChild(td);
		    tbody.appendChild(tr);

		    var tr = document.createElement("tr");
		    tr.setAttribute("id",'sup_tr3_'+part_id );
		    var td = document.createElement("td");
		    td.setAttribute("class","label" );
		    td.innerHTML='<?php echo _('Cost per')?> '+r.units_tipo_name+':';
		    tr.appendChild(td);

		    var td = document.createElement("td");
		    td.setAttribute("style","text-align:left" );

		    var txt = document.createElement("textNode");
		    txt.innerHTML=r.currency;

		    var input = document.createElement("input");
		    input.setAttribute("id","v_part_cost"+part_id );
		    input.setAttribute("style","text-align:right;width:6em" );
		    input.setAttribute("ovalue",r.data.price );
		    input.setAttribute("name",'price' );
		    input.setAttribute("onblur","this.value=FormatNumber(this.value,'"+r.decimal_point+"','"+r.thousand_sep+"',4);part_changed(this,"+part_id+")" );
		    input.value=r.data.price;
		    
		    td.appendChild(txt);
		    td.appendChild(input);
		    tr.appendChild(td);
		    tbody.appendChild(tr);
		    var tr = document.createElement("tr");
		    tr.setAttribute("id",'sup_tr4_'+part_id );
		    var td = document.createElement("td");
		    td.setAttribute("colspan","2" );
		    tr.appendChild(td);
		    tbody.appendChild(tr);
		    Dom.get('new_part_form').style.display='none';
		    Dom.setStyle('current_parts_form', 'opacity', 1); 
		}else
		    Dom.get('edit_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});
};
function cancel_new_part(){
    Dom.setStyle('current_parts_form', 'opacity', 1.0); 
    Dom.get('new_part_form').style.display='none';
    Dom.get('new_part_name').innerHTML='';
    Dom.get('new_part_form').setAttribute('part_id','');


}	

function save_description(key){
    var data=new Object();
    var Add_Categories=new Array();
    var Remove_Categories=new Array();

    for (i in description){
	if(description[i].changed==1){
	    if(i.match(/cat_\d+>/g)){
		children=Dom.getChildren(i);
		for(j=0;j<children.length;j++){
		    item=children[j];
		    //alert(item+' '+item.getAttribute('value')   )
		    if(item.getAttribute('value')==1){
		        Add_Categories.push(item.getAttribute('cat_id'));
		    }else{
			Remove_Categories.push(item.getAttribute('cat_id'));
		    }

		}
	    }else if(i=='details'){
		    editor.saveHTML(); 
            data[description[i].column]=editor.get('element').value;    
		
	    }else
		
	        data[description[i].column]=Dom.get(i).value;
	}
    }
    if(Add_Categories.length>0)
	data['Add Categories']=Add_Categories;
    if(Remove_Categories.length>0)
	data['Remove Categories']=Remove_Categories;

    var json_value = YAHOO.lang.JSON.stringify(data);

    var request='ar_edit_assets.php?tipo=edit_product&id='+product_id+'&key=array&value='+escape(json_value);

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		
			alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    
		    
		    
		    if(r.state==200){
		  
			for (t_key in r.updated_fields){
			    if(t_key=='Product Name'){
				Dom.get('name').setAttribute('ovalue',r.updated_fields[t_key][t_key]);
				Dom.removeClass('msg_name','error');
			    }else if(t_key=='Product Special Characteristic'){
				Dom.get('special_char').setAttribute('ovalue',r.updated_fields[t_key][t_key]);
				Dom.removeClass('msg_special_char','error');
			    }else if(t_key=='Product Details'){
				Dom.get('details').setAttribute('ovalue',r.updated_fields[t_key][t_key]);
				Dom.get('olength').setAttribute('ovalue',r.updated_fields[t_key]['Product Description Length']);
				Dom.get('ohash').setAttribute('ovalue',r.updated_fields[t_key]['Product Description MD5 Hash']);
	
				editor.setEditorHTML(r.updated_fields[t_key][t_key]); 
			
			    }else if(t_key=='Product Category'){
			
				var cat_data=r.updated_fields[t_key];

				for(cat_key in cat_data){
				    //				    alert('cat'+cat_key);
				    Dom.get('cat'+cat_key).setAttribute('ovalue',cat_data[cat_key]);
				}
			    }
			}
			
  
			for (t_key in r.errors_while_updating){
			     if(t_key=='Product Name'){
				 Dom.get('msg_name').innerHTML=r.errors_while_updating[t_key].msg;
				 Dom.addClass('msg_name','error');
			     }
			     if(t_key=='Product Special Characteristic'){
				 Dom.get('msg_special_char').innerHTML=r.errors_while_updating[t_key].msg;
				 Dom.addClass('msg_special_char','error');
			     }
			}

		
			
			

			
			Dom.get('description_save').style.display='none';
			Dom.get('description_undo').style.display='none';


			var table=tables.table0;
			var datasource=tables.dataSource0;
			var request='';
			datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    

		}else{
		//Dom.get('edit_messages').innerHTML=r.msg;
		    alert(r.msg);
		}
	    }
	    
	});

	
    }
function save_price(key){

    var value=Dom.get('v_'+key).value;

    value=parse_money(value);	
    if(key=='rrp'){
	var t_key='Product RRP Per Unit';
    }else if(key=='price'){
	var t_key='Product Price';
    }else
	return;

    var request='ar_edit_assets.php?tipo=edit_product&id='+product_id+'&key='+escape(t_key)+'&value='+escape(value);
    //  alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//	alert(o.responseText)
		
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
	
		if(r.state==200){
		    
		    if(typeof( r.updated_fields[t_key] ) != 'undefined'){
			
			Dom.get('v_'+key).setAttribute('ovalue',r.updated_fields[t_key][t_key]);
			Dom.get('rrp_margin').innerHTML=r.updated_fields[t_key]['RRP Margin'];

			if(t_key=='Product Price'){
			    Dom.get('l_formated_price').innerHTML=r.updated_fields[t_key]['Formated Price'];
			    Dom.get('price_margin').innerHTML=r.updated_fields[t_key]['Margin'];
			}else if(t_key=='Product RRP Per Unit'){
			    if(r.updated_fields[t_key]['RRP']==''){
				Dom.get('tr_rrp_per_unit').style.display='none';
			    }else
				Dom.get('tr_rrp_per_unit').style.display='';
			    Dom.get('l_formated_rrp_per_unit').innerHTML=r.updated_fields[t_key]['RRP Per Unit'];
			    
			}
			
		    }
		    
		    Dom.get(key+'_save').style.visibility='hidden';
		    Dom.get(key+'_change').innerHTML='';
		    Dom.get(key+'_undo').style.visibility='hidden';

		    
		    var table=tables.table0;
		    var datasource=tables.dataSource0;
		    var request='';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    

		}else{

		    alert('error');
		}
	    }
	    
	});
}


function init(){
	var ids = ["cat_select"]; 
	YAHOO.util.Event.addListener(ids, "change", change_list_element);
	var ids = ["description","pictures","prices","parts","dimat","config"]; 
	YAHOO.util.Event.addListener(ids, "click", change_block);
	

	//Details textarea editor ---------------------------------------------------------------------
	var texteditorConfig = {
	    height: '300px',
	    width: '780px',
	    dompath: true,
	    focusAtStart: true
	};     

 	editor = new YAHOO.widget.Editor('details', texteditorConfig);

	editor._defaultToolbar.buttonType = 'basic';
 	editor.render();

	editor.on('editorKeyUp',change_textarea,'details' );
	//-------------------------------------------------------------


	cat_list = new YAHOO.widget.Menu("catlist", {context:["browse_cat","tr", "br","beforeShow"]  });

	cat_list.render();

	cat_list.subscribe("show", cat_list.focus);

	YAHOO.util.Event.addListener("browse_cat", "click", cat_list.show, null, cat_list); 




 var onUploadButtonClick = function(e){
    //the second argument of setForm is crucial,
    //which tells Connection Manager this is a file upload form
    YAHOO.util.Connect.setForm('testForm', true);

    var uploadHandler = {
      upload: function(o) {
	    alert(o.responseText);
	    var r =  YAHOO.lang.JSON.parse(o.responseText);
	    if(r.ok){
		var images=Dom.get('images');
		var image_div=document.createElement("div");
		image_div.setAttribute("id", "image"+r.data.id);
		image_div.setAttribute("class",'image');

		var name_div=document.createElement("div");
		name_div.innerHTML=r.data.name;
		
		
		var picture_img=document.createElement("img");
		picture_img.setAttribute("src", r.data.med);
		picture_img.setAttribute("class", 'picture');

		var operations_div=document.createElement("div");
		operations_div.setAttribute("class",'operations');
		var set_principal_span=document.createElement("span");
		set_principal_span.setAttribute("class",'img_set_principal');
		set_principal_span.style.cursor='pointer';
		
		var set_principal_img=document.createElement("img");
		set_principal_img.setAttribute("id", "img_set_principal"+r.data.id);
		set_principal_img.setAttribute("image_id", r.data.id);


		set_principal_img.setAttribute("onClick", 'set_image_as_principal(this)');
		
		if(r.is_principal==1){
		    Dom.get('images').setAttribute('principal',r.data.id)
		    set_principal_img.setAttribute("principal", 1);
		    set_principal_img.setAttribute("src", 'art/icons/asterisk_orange.png');
		    set_principal_img.setAttribute("title", "<?php echo _('Main Image')?>");
		}else{
		    set_principal_img.setAttribute("principal", 0);
		    set_principal_img.setAttribute("src", 'art/icons/picture_empty.png');
		    set_principal_img.setAttribute("title", "<?php echo _('Set as the principal image')?>");
		}	



		set_principal_span.appendChild(set_principal_img);
		var delete_span=document.createElement("span");
		delete_span.style.cursor='pointer';
		delete_span.innerHTML='<?php echo _('Delete')?> <img src="art/icons/cross.png">';
		delete_span.setAttribute("onClick", 'delete_image('+r.data.id+',"'+r.data.name+'")');

		operations_div.appendChild(set_principal_span);
		operations_div.appendChild(delete_span);


		var caption_div=document.createElement("div");
		caption_div.setAttribute("class",'caption');
		var caption_tag_div=document.createElement("div");
		caption_tag_div.innerHTML='<?php echo _('Caption')?>:';
		var save_caption_span=document.createElement("span");
		save_caption_span.setAttribute("class",'save');
		var save_caption_img=document.createElement("img");
		save_caption_img.setAttribute("src",'art/icons/disk.png');
		save_caption_img.setAttribute("title",'<?php echo _('Save caption')?>');
		save_caption_img.setAttribute("id",'save_img_caption'+r.data.id);
		save_caption_img.setAttribute("onClick",'save_image("img_caption",'+r.data.id+')');
		save_caption_img.setAttribute("class",'caption');


		var caption_textarea=document.createElement("textarea");
		caption_textarea.setAttribute("id",'img_caption'+r.data.id);
		caption_textarea.setAttribute("image_id",r.data.id);
		caption_textarea.setAttribute("ovalue",'');
		caption_textarea.setAttribute("onkeydown",'caption_changed(this)');
		caption_textarea.setAttribute("class",'caption');
		//caption_textarea.style.width='150px';

		save_caption_span.appendChild(save_caption_img);
		caption_div.appendChild(caption_tag_div);
		caption_div.appendChild(save_caption_span);
		caption_div.appendChild(caption_textarea);

		image_div.appendChild(name_div);
		image_div.appendChild(picture_img);
		image_div.appendChild(operations_div);
		image_div.appendChild(caption_div);

		images.appendChild(image_div);


	    }else
		alert(r.msg);
	    
	    

      }
    };
    var request='ar_edit_assets.php?tipo=upload_product_image';
    YAHOO.util.Connect.asyncRequest('POST',request, uploadHandler);
  };
  YAHOO.util.Event.on('uploadButton', 'click', onUploadButtonClick);



}
YAHOO.util.Event.onContentReady("adding_new_part", function () {
	var oDS = new YAHOO.util.XHRDataSource("ar_parts.php");
 	oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	oDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["name","code","id","names"]
 	};

 	var oAC = new YAHOO.widget.AutoComplete("new_part_input", "new_part_container", oDS);
 	oAC.resultTypeList = false; 
	oAC.generateRequest = function(sQuery) {
 	    return "?tipo=parts_name&except_product=<?php echo $_SESSION['state']['product']['pid']?>&query=" + sQuery ;
 	};
	oAC.forceSelection = true; 
	oAC.itemSelectEvent.subscribe(part_selected); 
    });
YAHOO.util.Event.onContentReady("units_tipo_list", function () {
	 var oMenu = new YAHOO.widget.Menu("units_tipo_list", { context:["v_units_tipo","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("v_units_tipo", "click", oMenu.show, null, oMenu);
    });
YAHOO.util.Event.onContentReady("shapes", function () {

	var oMenu = new YAHOO.widget.Menu("shapes", { context:["dim_shape","tr", "br"]  });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	YAHOO.util.Event.addListener("dim_shape", "click", oMenu.show, null, oMenu);
    });
YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.addListener(window, "load", function() {


    tables = new function() {
	    //START OF THE TABLE=========================================================================================================================

	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"date",label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author",label:"<?php echo _('Author')?>", width:70,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //     ,{key:"tipo", label:"<?php echo _('Type')?>", width:90,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //,{key:"diff_qty",label:"<?php echo _('Qty')?>", width:90,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"abstract", label:"<?php echo _('Description')?>", width:370,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	    //?tipo=customers&tid=0"

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=product_history");
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
			 ,'author','date','tipo','abstract','details'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource0
						     , {
							 // sortedBy: {key:"<?php echo$_SESSION['tables']['customers_list'][0]?>", dir:"<?php echo$_SESSION['tables']['customers_list'][1]?>"},
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['product']['stock_history']['nr']?>,containers : 'paginator0', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							     key: "<?php echo$_SESSION['state']['product']['stock_history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['product']['stock_history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table0.filter={key:'<?php echo$_SESSION['state']['product']['stock_history']['f_field']?>',value:'<?php echo$_SESSION['state']['product']['stock_history']['f_value']?>'};
	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown);
	    
	    
	    this.move_formatter = function(elLiner, oRecord, oColumn, oData) {
		var stock=oRecord.getData("part_stock")
                if(stock>0)
		   elLiner.innerHTML =oData;
		else
		    elLiner.innerHTML = '';

	    };

	    this.lost_formatter = function(elLiner, oRecord, oColumn, oData) {
		var qty=oRecord.getData("number_qty")
                if(qty==0)
		    elLiner.innerHTML = '';
		else
		    elLiner.innerHTML =oData;
	    };

	    this.delete_formatter = function(elLiner, oRecord, oColumn, oData) {



		var qty=oRecord.getData("number_qty")
                if(qty==0){
		    elLiner.innerHTML = oData;
		    oColumn.actionx='delete';
		}else{
		    elLiner.innerHTML =''   ;
		    oColumn.actionx='';
		}
		//alert(oColumn.action);
	    };
	    // Add the custom formatter to the shortcuts
	    YAHOO.widget.DataTable.Formatter.move = this.move_formatter;
	    YAHOO.widget.DataTable.Formatter.lost = this.lost_formatter;
	    YAHOO.widget.DataTable.Formatter.delete = this.delete_formatter;


	};
    });
