<?php  include_once('common.php');

$money_regex="^[^\\\\d\\\.\\\,]{0,3}(\\\\d{1,3}(\\\,\\\\d{3})*|(\\\\d+))(\\\.\\\\d{2})?$";
print 'var money_regex="'.$money_regex.'";';
$number_regex="^(\\\\d{1,3}(\\\,\\\\d{3})*|(\\\\d+))(\\\.\\\\d{1,})?$";
print 'var number_regex="'.$number_regex.'";';

$parts=preg_split('/\,/',$_REQUEST['parts']);

$_parts='';
foreach($parts as $part){
    $_parts.="'sku$part':{sku : $part, new:false, deleted:false } ,";
}
$_parts=preg_replace("/\,$/","",$_parts);
print "\nvar part_list={ $_parts };";


 ?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var product_pid='<?php echo $_REQUEST['product_id']?>';
var validate_scope_data=
{
    'product_description':{
	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Customer Name')?>'}]}
	,'special_characteristic':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Special_Characteristic','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Special Characteristic')?>'}]}
    	,'description':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Description','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Description')?>'}]}

}
    , 'product_price':{
	'price':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Price','ar':false,'validation':[{'regexp':money_regex,'invalid_msg':'<?php echo _('Invalid Price')?>'}]}
	,'rrp':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_RRP','ar':false,'validation':[{'regexp':money_regex,'invalid_msg':'<?php echo _('Invalid Price')?>'}]}
    }
	
  , 'product_weight':{
	'unit_weight':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Unit_Weight','ar':false,'validation':[{'regexp':number_regex,'invalid_msg':'<?php echo _('Invalid Weight')?>'}]}
	,'outer_weight':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Outer_Weight','ar':false,'validation':[{'regexp':number_regex,'invalid_msg':'<?php echo _('Invalid Weight')?>'}]}	

	}

 , 'product_units':{
	'units_per_case':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Units_Per_Case','ar':false,'validation':[{'regexp':"\\d",'invalid_msg':'<?php echo _('Invalid Number')?>'}]}
	,'units_type':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Units_Type','ar':false,'validation':[{'regexp':"\\.+",'invalid_msg':'<?php echo _('Invalid Unit Type')?>'}]}	

	}

    };
var validate_scope_metadata={
    'product_description':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'pid','key':product_pid}
    ,'product_price':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'pid','key':product_pid}
    ,'product_weight':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'pid','key':product_pid}

};

function validate_product_name(query){
 validate_general('product_description','name',unescape(query));
}

function validate_product_special_characteristic(query){
 validate_general('product_description','special_characteristic',unescape(query));
}
function validate_product_description(query){

 validate_general('product_description','description',unescape(query));
}

function validate_product_unit_weight(query){
 validate_general('product_weight','unit_weight',unescape(query));
}
function validate_product_outer_weight(query){
 validate_general('product_weight','outer_weight',unescape(query));
}

function validate_product_price(query){

 validate_general('product_price','price',unescape(query));
 
 if(validate_scope_data.product_price.price.validated){
     var td=Dom.get("price_per_unit");
     var units=parseFloat(td.getAttribute("units"));
     var value=Dom.get(validate_scope_data.product_price.price.name).value;
     price=parseFloat(value.replace(/^[^\d]*/i, ""));
     var rrp=Dom.get(validate_scope_data.product_price.rrp.name).value;
     rrp=parseFloat(rrp.replace(/^[^\d]*/i, ""));

     var cost=parseFloat(td.getAttribute("cost"));
     var old_price=parseFloat(td.getAttribute("old_price"));



     var new_price_per_unit=price/units; 
     Dom.get("price_per_unit").innerHTML=money(new_price_per_unit)+" <?php echo _('per unit')?>";
     Dom.get("price_margin").innerHTML="<?php echo _('Margin')?>: "+percentage(price-cost,price);
     Dom.get("rrp_margin").innerHTML="<?php echo _('Margin')?>: "+percentage(rrp-price,rrp);

     if(price>old_price){
	 diffence="<?php echo _('Price up')?> "+percentage(price-old_price,price);
     }else{
	 diffence="<?php echo _('Price down')?> "+percentage(price-old_price,price);
		 
     }
     
     Dom.get(validate_scope_data.product_price.price.name+"_msg").innerHTML=diffence;
     
 }


}


function validate_product_rrp(query){

 validate_general('product_price','rrp',unescape(query));
}




function change_block(e){
 
	if(this.id=='pictures'){
	    Dom.get('info_name').style.display='';
	}else
	    Dom.get('info_name').style.display='none';

	if(this.id=='xprices'){
	    Dom.get('info_price').style.display='';
	}else
	    Dom.get('info_price').style.display='none';
	Dom.get('d_parts').style.display='none';
	Dom.get('d_pictures').style.display='none';
	Dom.get('d_parts').style.display='none';
	Dom.get('d_prices').style.display='none';
	Dom.get('d_dimat').style.display='none';
	Dom.get('d_config').style.display='none';
	Dom.get('d_description').style.display='none';
	Dom.get('d_web').style.display='none';

	Dom.get('d_'+this.id).style.display='';
	
	var ids = ["description","pictures","prices","parts","dimat","config","web"]; 
	Dom.removeClass(ids,'selected');
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=product-edit&value='+this.id,{} );
}

function save_edit_description(){
    save_edit_general('product_description');
}
function reset_edit_description(){
    reset_edit_general('product_description')
}

function save_edit_price(){
    save_edit_general('product_price');
}
function reset_edit_price(){
    reset_edit_general('product_price')
}

function save_edit_weight(){
    save_edit_general('product_weight');
}
function reset_edit_weight(){
    reset_edit_general('product_weight')
}


function reset_part(key){

for(part_key in part_list){

if(part_list[part_key].new  ){

Dom.get('part_editor_table').removeChild(Dom.get('part_list'+part_list[part_key].sku));



}else if (part_list[part_key].deleted){


}else{


key=part_list[part_key].sku;
Dom.get('parts_per_product'+key).value=Dom.get('parts_per_product'+key).getAttribute('ovalue')
Dom.get('pickers_note'+key).value=Dom.get('pickers_note'+key).getAttribute('ovalue');

}

}



part_render_save_buttons();

}

function save_part(){


key=Dom.get("product_part_items").getAttribute("product_part_key");

for(part_key in part_list){
    
    part_list[part_key].ppp=Dom.get('parts_per_product'+part_list[part_key].sku).value;
part_list[part_key].note=Dom.get('pickers_note'+part_list[part_key].sku).value;

}

json_value = YAHOO.lang.JSON.stringify(part_list);





 var request='ar_edit_assets.php?tipo=edit_part_list&key=' + key+ '&newvalue=' + json_value
		
		  
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
				alert(o.responseText);
				var r =  YAHOO.lang.JSON.parse(o.responseText);
				if(r.state==200){
				  
				  if(r.new){
				   window.location.reload( true );
		location.href='edit_product.php?pid='+r.newvalue+'&new';		  
				  }else if(r.changed){
				  
				  if(r.newvalue['Product Part Key']!= undefined){
				  window.location.reload( true );
				  return;
				  }
				  
				    for (sku in  r.newvalue.items){
				  
				  if(r.newvalue.items[sku]['Product Part List Note']!= undefined)
				  
				   
				        Dom.get('pickers_note'+sku).value=r.newvalue.items[sku]['Product Part List Note'];
				         Dom.get('pickers_note'+sku).setAttribute('ovalue',r.newvalue.items[sku]['Product Part List Note']);
			
				    
				    
				    }
				  
				  }
				    reset_part(key)


				}else{
				  
				    
				}
				
			    }
			    
			});



}

function part_render_save_buttons(){
var validated=true;
var changed=false;

 Dom.setStyle('reset_edit_part','visibility','hidden');
 Dom.setStyle('save_edit_part','visibility','hidden');

for(part_key in part_list){

if(part_list[part_key].new || ( !part_list[part_key].new && part_list[part_key].deleted  )  ){
changed=true;
}else{
if(Dom.get('parts_per_product'+part_list[part_key].sku).value!=Dom.get('parts_per_product'+part_list[part_key].sku).getAttribute('ovalue'))changed=true;
if(Dom.get('pickers_note'+part_list[part_key].sku).value!=Dom.get('pickers_note'+part_list[part_key].sku).getAttribute('ovalue'))changed=true;
}

if(!part_list[part_key].deleted ){
  if(!validate_parts_per_product(part_list[part_key].sku))
      validated=false;

}

}

if( changed){
    Dom.setStyle('reset_edit_part','visibility','visible');
}
if(validated && changed){
      Dom.setStyle('save_edit_part','visibility','visible');
}




}


function validate_parts_per_product(key){
var value=Dom.get('parts_per_product'+key).value;
var valid=true;
var msg='';
if(isNaN(parseFloat(value))){
valid=false;
msg='No numeric value';
}
var patt1=new RegExp("[a-zA-Z\.\?]");

if( patt1.test(value)    ){
msg='Invalid Value';
valid=false;
}

if(valid && (value==0 || value<0  )  ){
msg='Invalid Value';
valid=false;
}

Dom.get("parts_per_product_msg"+key).innerHTML=msg;
return valid;

}

function part_changed(o){



part_render_save_buttons();



}


function goto_search_result(subject){
elements_array=Dom.getElementsByClassName('selected', 'tr', subject+'_search_results_table');

tr=elements_array[0];
if(tr!= undefined)

var data={
sku:tr.getAttribute('key')
,fsku:tr.getAttribute('sku')
,description:tr.getAttribute('description')
};

select_part(data)

}
function go_to_result(){
var data={
sku:this.getAttribute('key')
,fsku:this.getAttribute('sku')
,description:this.getAttribute('description')
};

select_part(data)

}

function select_part(data){
//Dom.get('part_search').value='';
//Dom.get('part_search_results').style.display='none';
//Dom.get('the_part_dialog').setAttribute('sku',data.sku);
//Dom.get('part_sku0').innerHTML=data.fsku;
//Dom.get('part_description0').innerHTML=data.description;
//Dom.get('the_part_dialog').style.display='';
//var new_email_container = Dom.get('email_mould').cloneNode(true);


}

function close_add_part_dialog() {
    
    Editor_add_part.cfg.setProperty('visible',false);
}

var part_selected=function(){

    var data = {
	"info":newProductData[0]
	,"sku":newProductData[1]
	,"usedin":newProductData[2]
    }; 
    
  
   alert("xx")

}


function cancel_new_part(){
Dom.get('the_part_dialog').setAttribute('sku','');
Dom.get('part_sku0').innerHTML='';
Dom.get('part_description0').innerHTML='';
Dom.get('pickers_note0').value='';
Dom.get('parts_per_product0').value=1;


Dom.get('the_part_dialog').style.display='none';
}

function add_part(sku){
  x= Dom.getX('add_part');
   y= Dom.getY('add_part');
   Dom.setX('Editor_add_part', x-80);
   Dom.setY('Editor_add_part', y);
   Dom.get('add_part_input').focus();
   Editor_add_part.show();
}
YAHOO.util.Event.onContentReady("add_part_input", function () {
  
  var new_loc_oDS = new YAHOO.util.XHRDataSource("ar_assets.php");
    new_loc_oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
    new_loc_oDS.responseSchema = {
resultsList : "data"
        ,
 fields : ["info","sku","description","usedin","formated_sku"]
    };
    var new_loc_oAC = new YAHOO.widget.AutoComplete("add_part_input", "add_part_container", new_loc_oDS);
  
  
  new_loc_oAC.generateRequest = function(sQuery) {

     return "?tipo=find_part&query=" + sQuery ;

    };
    new_loc_oAC.forceSelection = true;
    new_loc_oAC.itemSelectEvent.subscribe(add_part_selected);
    
});


function add_part_selected(sType, aArgs) {

    var part_data= aArgs[2];
    var data = {

"sku":
        part_data[1]
,"formated_sku":
        part_data[4]
,"description":
        part_data[2]        
    };
   

sku=data['sku'];
formated_sku=data['formated_sku'];
parts_per_product=1;
note='';
description=data['description'];


part_list['sku'+sku]={'sku':sku,'new':true,'deleted':false};



 oTbl=Dom.get('part_editor_table');
         

 
    oTR= oTbl.insertRow(-1);
    
               

    
    oTR.id='part_list'+sku;
  
    oTR.setAttribute('sku',sku);
 
    Dom.addClass(oTR,'top'); Dom.addClass(oTR,'title');

    var oTD= oTR.insertCell(0);
    oTD.innerHTML=  '<?php echo _('Part')?>';
    Dom.addClass(oTD,'label');
 
    var oTD= oTR.insertCell(1);
    Dom.addClass(oTD,'sku');
    oTD.innerHTML='<span>'+formated_sku+'</span>';
    Dom.setStyle(oTD, 'width', '120px');
        
    var oTD= oTR.insertCell(2);
    Dom.addClass(oTD,'description');
    Dom.setStyle(oTD, 'width', '350px');
    oTD.innerHTML=description;
  
    var oTD= oTR.insertCell(3);
    oTD.innerHTML='<span style="cursor:pointer"><img onClick="remove_part('+sku+')" src="art/icons/delete_bw.png"/> <?php echo _('Remove')?></span>';
    oTR= oTbl.insertRow(-1);
 
  var oTD= oTR.insertCell(0);
    oTD.innerHTML=  '<?php echo _('Parts Per Product')?>:';
    Dom.addClass(oTD,'label');
    
   var oTD= oTR.insertCell(1);
   oTD.setAttribute('colspan',3);
   oTD.innerHTML='<input style="padding-left:2px;text-align:left;width:3em" value="'+parts_per_product+'" onblur="part_changed(this)"  onkeyup="part_changed(this)" ovalue="'+parts_per_product+'" id="parts_per_product'+sku+'"> <span  id="parts_per_product_msg'+sku+'"></span>';
   
     oTR= oTbl.insertRow(-1);
     oTR.id="sup_tr3_"+sku;
         Dom.addClass(oTR,'last');


  var oTD= oTR.insertCell(0);
    oTD.innerHTML=  '<?php echo _('Notes For Pickers')?>:';
    Dom.addClass(oTD,'label');
    
   var oTD= oTR.insertCell(1);
   oTD.setAttribute('colspan',3);
       Dom.setStyle(oTD, 'text-align', 'left');

   oTD.innerHTML='<input id="pickers_note'+sku+'" style=";width:400px"   onblur="part_changed(this)"  onkeyup="part_changed(this)"     value="'+note+'" ovalue="'+note+'" >';

part_render_save_buttons();
Dom.get('add_part_input').value='';
close_add_part_dialog();

   
};


function init(){






Editor_add_part = new YAHOO.widget.Dialog("Editor_add_part", {close:false,visible:false,underlay: "none",draggable:false});
    Editor_add_part.render();




    var ids = ["description","pictures","prices","parts","dimat","config","web"]; 
    Event.addListener(ids, "click", change_block);
    
    Event.addListener('save_edit_product_description', "click", save_edit_description);
    Event.addListener('reset_edit_product_description', "click", reset_edit_description);
    
    Event.addListener('save_edit_product_price', "click", save_edit_price);
    Event.addListener('reset_edit_product_price', "click", reset_edit_price);

    Event.addListener('save_edit_product_weight', "click", save_edit_weight);
    Event.addListener('reset_edit_product_weight', "click", reset_edit_weight);

    
    var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_name);
    product_name_oACDS.queryMatchContains = true;
    var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_Name","Product_Name_Container", product_name_oACDS);
    product_name_oAutoComp.minQueryLength = 0; 
    product_name_oAutoComp.queryDelay = 0.1;
	
	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_special_characteristic);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_Special_Characteristic","Product_Special_Characteristic_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;


	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_description);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_Description","Product_Description_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;


	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_price);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_Price","Product_Price_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;

	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_rrp);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_RRP","Product_RRP_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;


   var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_unit_weight);
    product_name_oACDS.queryMatchContains = true;
    var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_Unit_Weight","Product_Unit_Weight_Container", product_name_oACDS);
    product_name_oAutoComp.minQueryLength = 0; 
    product_name_oAutoComp.queryDelay = 0.1;
	
	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_outer_weight);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_Outer_Weight","Product_Outer_Weight_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;


}


YAHOO.util.Event.onDOMReady(init);



