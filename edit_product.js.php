<?php  include_once('common.php');

$money_regex="^[^\\\\d\\\.\\\,]{0,3}(\\\\d{1,3}(\\\,\\\\d{3})*|(\\\\d+))(\\\.\\\\d{2})?$";
print 'var money_regex="'.$money_regex.'";';
$number_regex="^(\\\\d{1,3}(\\\,\\\\d{3})*|(\\\\d+))(\\\.\\\\d{1,})?$";
print 'var number_regex="'.$number_regex.'";';

$parts=preg_split('/\,/',$_REQUEST['parts']);



$_parts='';
foreach($parts as $part){
    if($part)
    $_parts.="'sku$part':{sku : $part, new:false, deleted:false } ,";
}
$_parts=preg_replace("/\,$/","",$_parts);
print "\nvar part_list={ $_parts };";




 ?>
//alert(Dom.get('store_key').value);
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var product_pid='<?php echo $_REQUEST['pid']?>';
var scope='product';
var scope_key=product_pid;
var dialog_family_list;
var dialog_part_list;

var Editor_change_part;

var store_key=<?php echo $_REQUEST['store_key']?>;
//alert(store_key);


function delete_product(){
	
	var request = 'ar_edit_assets.php?tipo=delete_product&pid=' + product_pid
	// alert(request);
if (confirm('Are you sure, you want to delete product '+product_pid+' now?')) {
	YAHOO.util.Connect.asyncRequest('POST', request, {
		success: function(o) {
			//alert(o.responseText);
			var r = YAHOO.lang.JSON.parse(o.responseText);
			if (r.state == 200) {				
				window.location.href = 'family.php?id=' + r.family_key;
			} else {
				alert(r.msg);
			}
		}
	});	
}

}

function change_state(newval, oldval){


	var request = 'ar_edit_assets.php?tipo=edit_product&key=web_configuration&newvalue='+newval+'&oldvalue='+oldval+'&pid=' + product_pid
	 //alert(request);
//return;
	YAHOO.util.Connect.asyncRequest('POST', request, {
		success: function(o) {
			//alert(o.responseText);
			var r = YAHOO.lang.JSON.parse(o.responseText);
			if (r.state == 200) {				
				window.location.reload();
			} else {
				alert(r.msg);
			}
		}
	});	
}

function validate_product_name(query){
 validate_general('product_description','name',unescape(query));
}

function validate_product_units(query){
 validate_general('product_units','units_per_case',unescape(query));
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
 
 	var ids = ["description","pictures","prices","parts","dimat","config","web"]; 
 	var block_ids = ["d_description","d_pictures","d_prices","d_parts","d_dimat","d_config","d_web"]; 

 
	
	
	Dom.setStyle(block_ids,'display','none');
		Dom.setStyle('d_'+this.id,'display','');

	

	
	
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

function save_edit_product_units(){
    save_edit_general('product_units');
}
function reset_edit_product_units(){
    reset_edit_general('product_units')
}



function change_unit_type(o){
//ar_edit_assets.php?tipo=edit_product_units&okey=units_per_case&key=units_per_case&newvalue=13d&pid=6539
var value=o.options[o.selectedIndex].value;

	var request = 'ar_edit_assets.php?tipo=edit_product_units&key=unit_type&okey=unit_type' + '&newvalue=' + value+ '&pid=' + product_pid
	 //alert(request);

	YAHOO.util.Connect.asyncRequest('POST', request, {
		success: function(o) {
			//alert(o.responseText);
			var r = YAHOO.lang.JSON.parse(o.responseText);
			if (r.state == 200) {				
				//Dom.get('current_family_code').innerHTML=r.newdata['code'];
			} else {
				}
		}
	});	
}


function select_family(oArgs){

family_key=tables.table2.getRecord(oArgs.target).getData('key');
 dialog_family_list.hide();

	var request = 'ar_edit_assets.php?tipo=edit_product&key=' + 'family_key' + '&newvalue=' + family_key+ '&pid=' + product_pid
	 //alert(request);

	YAHOO.util.Connect.asyncRequest('POST', request, {
		success: function(o) {
			//alert(o.responseText);
			var r = YAHOO.lang.JSON.parse(o.responseText);
			if (r.state == 200) {				
				Dom.get('current_family_code').innerHTML=r.newdata['code'];
			} else {
				}
		}
	});
}

function reset_part(key){

for(part_key in part_list){
	
	if(part_list[part_key].new  ){
		to_delete=Dom.get('part_list'+part_list[part_key].sku);
		to_delete.parentNode.removeChild(to_delete);
				to_delete=Dom.get('sup_tr2_'+part_list[part_key].sku);
		to_delete.parentNode.removeChild(to_delete);
				to_delete=Dom.get('sup_tr3_'+part_list[part_key].sku);
		to_delete.parentNode.removeChild(to_delete);
		
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
 var request='ar_edit_assets.php?tipo=edit_part_list&key=' + key+ '&newvalue=' + json_value+'&pid='+product_pid;
		//alert(request);
		  
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
				//alert(o.responseText);
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

/*
function goto_search_result(subject){
elements_array=Dom.getElementsByClassName('selected', 'tr', subject+'_search_results_table');

tr=elements_array[0];
if(tr!= undefined)

var data={
sku:tr.getAttribute('key')
,fsku:tr.getAttribute('sku')
,description:tr.getAttribute('description')
};

salect_part_from_list(data)

}
function go_to_result(){
var data={
sku:this.getAttribute('key')
,fsku:this.getAttribute('sku')
,description:this.getAttribute('description')
};

salect_part_from_list(data)

}
*/

function salect_part_from_list(oArgs){

sku=tables.table1.getRecord(oArgs.target).getData('sku')


formated_sku=tables.table1.getRecord(oArgs.target).getData('formated_sku')
parts_per_product=1;
note='';
description=tables.table1.getRecord(oArgs.target).getData('description')


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
    oTD.innerHTML='<span class="id">'+formated_sku+'</span> '+description;
    //Dom.setStyle(oTD, 'width', '120px');
        
   oTD.colSpan = 2;
  
    var oTD= oTR.insertCell(2);
    Dom.setStyle(oTD,'text-align','right');
    oTD.innerHTML='<span style="cursor:pointer" onClick="remove_part('+sku+')" ><img src="art/icons/delete_bw.png"/> <?php echo _('Remove')?></span><span onClick="show_change_part_dialog('+sku+',this)"  style="display:none;cursor:pointer;margin-left:15px"><img  src="art/icons/arrow_refresh_bw.png"/> <?php echo _('Change')?></span>';
    oTR= oTbl.insertRow(-1);
      oTR.id="sup_tr2_"+sku;
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


dialog_part_list.hide()
}













function init(){


validate_scope_data=
{
    'product_description':{
	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Customer Name')?>'}]}
	,'special_characteristic':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Special_Characteristic','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Special Characteristic')?>'}]}
    	,'description':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Product_Description','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Description')?>'}]}
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
	//,'units_type':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Units_Type','ar':false,'validation':[{'regexp':"\\.+",'invalid_msg':'<?php echo _('Invalid Unit Type')?>'}]}	

	}

    };
validate_scope_metadata={
    'product_description':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'pid','key':product_pid}
       ,'product_units':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'pid','key':product_pid}

   ,'product_price':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'pid','key':product_pid}
    ,'product_weight':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'pid','key':product_pid}

};


   Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
Event.addListener('clean_table_filter_show1', "click",show_filter,1);
 Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);
 
  var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS2.queryMatchContains = true;
 oACDS2.table_id=2;
 var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2","f_container2", oACDS2);
 oAutoComp2.minQueryLength = 0; 
YAHOO.util.Event.addListener('clean_table_filter_show2', "click",show_filter,2);
 YAHOO.util.Event.addListener('clean_table_filter_hide2', "click",hide_filter,2);
 
 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
  oACDS.table_id=0;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 
 
 var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS1.queryMatchContains = true;
  oACDS1.table_id=1;
 var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS1);
 oAutoComp1.minQueryLength = 0; 
 

 init_search('products_store');
 
//Editor_change_part = new YAHOO.widget.Dialog("Editor_change_part", {width:'450px',close:false,visible:false,underlay: "none",draggable:false});
 //   Editor_change_part.render();
    
    
    

YAHOO.util.Event.on('uploadButton', 'click', upload_image);






    var ids = ["description","pictures","prices","parts","dimat","config","web"]; 
    Event.addListener(ids, "click", change_block);
    
    Event.addListener('save_edit_product_description', "click", save_edit_description);
    Event.addListener('reset_edit_product_description', "click", reset_edit_description);
    
    Event.addListener('save_edit_product_price', "click", save_edit_price);
    Event.addListener('reset_edit_product_price', "click", reset_edit_price);

    Event.addListener('save_edit_product_weight', "click", save_edit_weight);
    Event.addListener('reset_edit_product_weight', "click", reset_edit_weight);

    Event.addListener('save_edit_product_units', "click", save_edit_product_units);
    Event.addListener('reset_edit_product_units', "click", reset_edit_product_units);
 
    dialog_part_list = new YAHOO.widget.Dialog("dialog_part_list",  {visible : false,close:true,underlay: "none",draggable:false});
    dialog_part_list.render();
    
    Event.addListener("add_part", "click",show_dialog_part_list);

    
    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_product_units);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("Product_Units_Per_Case","Product_Units_Per_Case_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;
    
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

    dialog_family_list = new YAHOO.widget.Dialog("dialog_family_list", {context:["family","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_family_list.render();
	
	
    Event.addListener("edit_family", "click", dialog_family_list.show,dialog_family_list , true);
    
       Event.addListener("filter_name1", "click",change_part_list_filter);

    
}

function change_part_list_filter(){

if(this.getAttribute('state')==undefined){
this.setAttribute('state','sku')
change_filter('sku','SKU',1)
}else if(this.getAttribute('state')=='sku'){
this.setAttribute('state','used_in')
change_filter('used_in','Used In',1)

}else if(this.getAttribute('state')=='used_in'){
this.setAttribute('state','sku')
change_filter('sku','SKU',1)
}
}


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

 var tableid=0; 
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"date",label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author",label:"<?php echo _('Author')?>", width:70,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"abstract", label:"<?php echo _('Description')?>", width:370,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	   

	    this.dataSource0 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=product&tableid=0");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		
		fields: [
			 "id"
			 ,"note"
			 ,'author','date','tipo','abstract','details'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource0
						     , {

							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['product']['history']['nr']?>,containers : 'paginator0', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['product']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['product']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table0.table_id=tableid;
     	this.table0.subscribe("renderEvent", myrenderEvent);	
		    
		    
	    this.table0.filter={key:'<?php echo$_SESSION['state']['product']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['product']['history']['f_value']?>'};





var tableid=1;
		      var tableDivEL="table"+tableid;
		      
		      var ColumnDefs = [
		      		{key:"formated_sku", label:"SKU",width:60, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"description", label:"<?php echo _('Description')?>",width:200, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			     	,{key:"used_in", label:"<?php echo _('Used In')?>",width:140, sortable:false,className:"aleft"}
			     	,{key:"status", label:"",width:70, sortable:false,className:"aleft"}
                   
					];
		    
		      
		      this.dataSource1 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=part_list&tableid=1");
		      
		      
		      
		      
	
		      
		      
		      
		      
		      this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
		      this.dataSource1.connXhrMode = "queueRequests";
		      	    this.dataSource1.table_id=tableid;

		      this.dataSource1.responseSchema = {
			  resultsList: "resultset.data", 
			  metaFields: {
			  rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",

		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" 
			  },
			  
			  fields: [
				  "sku","description","used_in","status","formated_sku"
				   ]};
		      
		    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource1
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								   
								   ,sortedBy : {
								      key: "formated_sku",
								       dir: ""
								   }
								   ,dynamicData : true
								 
							       }
							       );
		      this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
		      this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
		      this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
            this.table1.table_id=tableid;
     			this.table1.subscribe("renderEvent", myrenderEvent);
     
                   this.table1.subscribe("rowMouseoverEvent", this.table1.onEventHighlightRow);
       this.table1.subscribe("rowMouseoutEvent", this.table1.onEventUnhighlightRow);
      this.table1.subscribe("rowClickEvent", salect_part_from_list);
     

                   
	    this.table1.filter={key:'used_in',value:''};



		
   var tableid=2; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
			 {key:"key", label:"",width:100,hidden:true}
                    ,{key:"code", label:"<?php echo _('Code')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
						
			];
		this.dataSource2 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=family_list&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
		//alert("ar_quick_tables.php?tipo=family_list&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    	    this.dataSource2.table_id=tableid;

	    this.dataSource2.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "code",'name','key'
			 ]};

	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource2
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table2.subscribe("rowMouseoverEvent", this.table2.onEventHighlightRow);
       this.table2.subscribe("rowMouseoutEvent", this.table2.onEventUnhighlightRow);
      this.table2.subscribe("rowClickEvent", select_family);
        this.table2.table_id=tableid;
           this.table2.subscribe("renderEvent", myrenderEvent);


	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'code',value:''};


};
    });




YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });


YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });


/*
YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu1 = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	 oMenu1.render();
	 oMenu1.subscribe("show", oMenu1.focus);
	
    });
*/
function close_change_part_dialog(){

Dom.get('change_part').value='';
Dom.setStyle('change_part_selector','display','');
Dom.setStyle('save_change_part','display','none');
Dom.setStyle('change_part_confirmation','display','none');
 Editor_change_part.hide();
}

function change_part_selected(sType, aArgs){
alert("caca")
//remove_part(Dom.get('change_part_sku').value)
//add_part_selected(sType, aArgs);
//close_change_part_dialog();


//alert("s")

//var myAC = aArgs[0]; // reference back to the AC instance 
  //      var elLI = aArgs[1]; // reference to the selected LI element 
//	        var oData = aArgs[2]; // object literal of selected item's result data 

//Dom.get('change_part_new_part').innerHTML=oData[0];

//Dom.get('change_part').value='';
//Dom.setStyle('change_part_selector','display','none');
//Dom.setStyle('save_change_part','display','');
//Dom.setStyle('change_part_confirmation','display','');
}

function show_change_part_dialog(sku,o){

  Dom.get('change_part_sku').value=sku;
   x= Dom.getX(o)-455;
   y= Dom.getY(o);
   Dom.setX('Editor_change_part', x);
   Dom.setY('Editor_change_part', y);
   Dom.get('change_part').focus();
   Editor_change_part.show();
  
}

function show_family_list(o){
	Dom.setStyle('family_list','display','')
}


YAHOO.util.Event.onContentReady("change_part", function () {
  
  var new_loc_oDS = new YAHOO.util.XHRDataSource("ar_assets.php");
    new_loc_oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
    new_loc_oDS.responseSchema = {
resultsList : "data"
        ,

       fields : ["info","sku","description","usedin","formated_sku"]
    };
    var new_loc_oAC = new YAHOO.widget.AutoComplete("change_part", "change_part_container", new_loc_oDS);
  
  
  new_loc_oAC.generateRequest = function(sQuery) {

        sku=Dom.get("change_part_sku").value;
        request=  "?tipo=find_part&except_part="+sku+"&query=" + sQuery ;  
     
     return request;
    };
    new_loc_oAC.forceSelection = true;
    new_loc_oAC.itemSelectEvent.subscribe(change_part_selected);
    
});


function remove_part(sku){


if(part_list['sku'+sku].new  ){
	to_delete=Dom.get('part_list'+part_list[part_key].sku);
		to_delete.parentNode.removeChild(to_delete);
				to_delete=Dom.get('sup_tr2_'+part_list[part_key].sku);
		to_delete.parentNode.removeChild(to_delete);
				to_delete=Dom.get('sup_tr3_'+part_list[part_key].sku);
		to_delete.parentNode.removeChild(to_delete);
delete part_list['sku'+sku];


}else{

part_list['sku'+sku].deleted=true;
Dom.setStyle(['part_list'+sku+'_label1','part_list'+sku+'_label2'],'opacity',0.6);
Dom.setStyle(['part_list'+sku+'_label2'],'text-decoration','line-through');


Dom.setStyle(['sup_tr2_'+sku,'sup_tr3_'+sku],'opacity',0.4);
Dom.setStyle(['part_list'+sku+'_controls'],'display','none');
Dom.setStyle(['part_list'+sku+'_controls2'],'display','');

}

Dom.setStyle(['add_part'],'display','');



part_render_save_buttons();
}


function show_dialog_part_list(){


	region1 = Dom.getRegion('add_part'); 
    region2 = Dom.getRegion('dialog_part_list'); 
	var pos =[region1.right-region2.width+10,region1.bottom]
	Dom.setXY('dialog_part_list', pos);

dialog_part_list.show()
}


function unremove_part(sku){

part_list['sku'+sku].deleted=false;
Dom.setStyle(['part_list'+sku+'_label1','part_list'+sku+'_label2'],'opacity',1);
Dom.setStyle(['part_list'+sku+'_label2'],'text-decoration','none');


Dom.setStyle(['sup_tr2_'+sku,'sup_tr3_'+sku],'opacity',1);
Dom.setStyle(['part_list'+sku+'_controls'],'display','');
Dom.setStyle(['part_list'+sku+'_controls2'],'display','none');


part_render_save_buttons();
}
