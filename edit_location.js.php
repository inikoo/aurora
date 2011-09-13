<?php  include_once('common.php');

 ?>
//alert(Dom.get('store_key').value);
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var location_id=<?php echo $_REQUEST['location_id'] ?>;
var scope='product';
var store_key=1;
var dialog_family_list;
var dialog_part_list;

var Editor_change_part;



function validate_location_code(query){
validate_general('location_description','code',unescape(query));
}
function validate_location_radius(query){
validate_general('location_description','code',unescape(query));
}
function validate_location_deep(query){
validate_general('location_description','code',unescape(query));
}
function validate_location_height(query){
validate_general('location_description','code',unescape(query));
}
function validate_location_width(query){
validate_general('location_description','code',unescape(query));
}

function validate_location_max_weight(query){
validate_general('location_description','code',unescape(query));
}

function validate_location_max_volume(query){
validate_general('location_description','code',unescape(query));
}
function validate_location_max_slots(query){
validate_general('location_description','code',unescape(query));
}
function validate_location_distinct_parts(query){
validate_general('location_description','code',unescape(query));
}




function reset_location(){
reset_edit_general('location_description')
}





function init(){
number_regex="\\d+";
validate_scope_data=
{

    'location_description':{
	'code':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Location_Code','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Location Code')?>'}]}
	,'stock':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Location_Stock_Value','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Stock Value')?>'}]}

	,'radius':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Location_Radius','ar':false,'validation':[{'regexp':number_regex,'invalid_msg':'<?php echo _('Invalid Radius')?>'}]}
	,'deep':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Location_Deep','ar':false,'validation':[{'regexp':number_regex,'invalid_msg':'<?php echo _('Invalid Depth')?>'}]}	
	,'height':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Location_Height','ar':false,'validation':[{'regexp':number_regex,'invalid_msg':'<?php echo _('Invalid Height')?>'}]}	
	,'width':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Location_Width','ar':false,'validation':[{'regexp':number_regex,'invalid_msg':'<?php echo _('Invalid Width')?>'}]}	

	,'volume':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Location_Max_Volume','ar':false,'validation':[{'regexp':number_regex,'invalid_msg':'<?php echo _('Invalid Volume')?>'}]}	
	,'weight':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Location_Max_Weight','ar':false,'validation':[{'regexp':number_regex,'invalid_msg':'<?php echo _('Invalid Weight')?>'}]}


	,'slots':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Location_Max_Slots','ar':false,'validation':[{'regexp':"\\d",'invalid_msg':'<?php echo _('Invalid Number')?>'}]}
	,'parts':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Location_Distinct_Parts','ar':false,'validation':[{'regexp':"\\.+",'invalid_msg':'<?php echo _('Invalid Unit Type')?>'}]}	

	}

    };
	
	

	
validate_scope_metadata={
    'location_description':{'type':'edit','ar_file':'ar_edit_warehouse.php','key_name':'location_key','key':Dom.get('location_key').value}
    

};




 
init_search('locations');

 
//Editor_change_part = new YAHOO.widget.Dialog("Editor_change_part", {width:'450px',close:false,visible:false,underlay: "none",draggable:false});
 //   Editor_change_part.render();
    
    
    






  


    Event.addListener('save_edit_location_description', "click", save_location);
    Event.addListener('reset_edit_location_description', "click", reset_location);


    
    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_location_code);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("Location_Code","Location_Code_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;
   
    var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_location_radius);
    product_name_oACDS.queryMatchContains = true;
    var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Location_Radius","Location_Radius_Container", product_name_oACDS);
    product_name_oAutoComp.minQueryLength = 0; 
    product_name_oAutoComp.queryDelay = 0.1;
	
	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_location_deep);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Location_Deep","Location_Deep_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;


	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_location_height);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Location_Height","Location_Height_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;


	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_location_width);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Location_Width","Location_Width_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;

	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_location_max_weight);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Location_Max_Weight","Location_Max_Weight_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;


   var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_location_max_volume);
    product_name_oACDS.queryMatchContains = true;
    var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Location_Max_Volume","Location_Max_Volume_Container", product_name_oACDS);
    product_name_oAutoComp.minQueryLength = 0; 
    product_name_oAutoComp.queryDelay = 0.1;
	
	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_location_max_slots);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Location_Max_Slots","Location_Max_Slots_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;
	
	
	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_location_distinct_parts);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Location_Distinct_Parts","Location_Distinct_Parts_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;
	
	
	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_location_stock_value);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Location_Stock_Value","Location_Stock_Value_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;
	
	
   
	
	
}



YAHOO.util.Event.onDOMReady(init);





function save_location(){
save_edit_general('location_description');

}


function save_location_old(key,value){

alert('xx');

 var data_to_update=new Object;
 data_to_update={'okey':key,'value':value}

 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));


var request='ar_edit_assets.php?tipo=edit_location&values='+ jsonificated_values+"&location_key="+location_id


//var request='ar_edit_contacts.php?tipo=edit_customer&key=' + key+ '&newvalue=' + value +'&customer_key=' + customer_id
	//alert(request);
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
//alert(o.responseText)
//return;
				var r =  YAHOO.lang.JSON.parse(o.responseText);
				  //alert(r.newvalue);
				if(r.state==200){
			
  
 
            if (r.new_data['type']=='used_for') {
				Dom.removeClass('used_for_'+r.new_data['old_value'],'selected');
				Dom.addClass('used_for_'+r.newvalue,'selected');

            }else if(r.new_data['type']=='shape'){
				
				Dom.removeClass('shape_'+r.new_data['old_value'],'selected');
				Dom.addClass('shape_'+r.newvalue,'selected');
            }else if(r.new_data['type']=='has_stock'){
				Dom.removeClass('has_stock_'+r.new_data['old_value'],'selected');
				Dom.addClass('has_stock_'+r.newvalue,'selected');
            }
        }
        
    }
    });




}

