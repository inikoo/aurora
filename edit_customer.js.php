<?php
    include_once('common.php');
        include_once('class.Customer.php');

    $customer=new Customer($_SESSION['state']['customer']['id']);
    
print "var customer_id='".$_SESSION['state']['customer']['id']."';";
?>
  
var Dom   = YAHOO.util.Dom;
var editing='<?php echo $_SESSION['state']['customer']['edit']?>';


var validate_scope_data={
    'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','regexp':"[a-z\\d]+",'name':'Customer_Name'}
};


function validate_scope(){
var changed=false;
var errors=false;
//alert(validate_scope_data['name'].changed+'v:'+validate_scope_data['name'].validated)


    for(item in validate_scope_data){
    
        if(validate_scope_data[item].changed==true)
            changed=true;
         if(validate_scope_data[item].validated==false)
            errors=true;
    }
    
    if(changed ){
	Dom.get('reset_edit_customer').style.visibility='visible';
	if(!errors)
	    Dom.get('save_edit_customer').style.visibility='visible';
	else
	    Dom.get('save_edit_customer').style.visibility='hidden';

    }else{
        Dom.get('save_edit_customer').style.visibility='hidden';
	Dom.get('reset_edit_customer').style.visibility='hidden';

    }
    
    
    
}
function change_block(e){
     if(editing!=this.id){
	

	


	Dom.get('d_details').style.display='none';
	Dom.get('d_company').style.display='none';

	Dom.get('d_'+this.id).style.display='';

	//	alert(this.id);
	Dom.removeClass(editing,'selected');
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=customer-edit&value='+this.id );
	
	editing=this.id;
    }



}
function validate_customer_name(query){
    query=unescape(query);
    var old_name=Dom.get('Customer_Name').getAttribute('ovalue');
  
    //    alert(trim(query.toLowerCase())+'<-')
  if(old_name.toLowerCase()!=trim(query.toLowerCase())){  
	validate_scope_data.name.changed=true;
	var validator=new RegExp(validate_scope_data.name.regexp,"i");
	if(!validator.test(query)){
	    
	    validate_scope_data.code.validated=false;
	    Dom.get('Customer_Name_msg').innerHTML='<?php echo _('Invalid Customer Name')?>';
	    
	}else{
	    validate_scope_data.name.validated=true;
	    Dom.get('Customer_Name_msg').innerHTML='';
            
	}
    }
    else{
	validate_scope_data.name.validated=true;
	validate_scope_data.name.changed=false;
	
    }
    
    validate_scope();   
    
}
function save_edit_customer(){
    
    for(item in validate_scope_data){
	if(validate_scope_data[item].changed){
	var item_input=Dom.get(validate_scope_data[item].name);
	var request='ar_edit_contacts.php?tipo=edit_customer&key=' + item+ '&newvalue=' + 
	    encodeURIComponent(item_input.value) + 
	    '&customer_key='+customer_id;
	
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    //alert(o.responseText)
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state==200){
			
			validate_scope_data[r.key].changed=false;
			validate_scope_data[r.key].validated=true;
			Dom.get(validate_scope_data[r.key].name).setAttribute('ovalue',r.newvalue);
			Dom.get(validate_scope_data[r.key].name).value=r.newvalue;
			Dom.get(validate_scope_data[r.key].name+'_msg').innerHTML='<?php echo _('Updated')?>';
			
			//var table=tables.table1;
			//	var datasource=tables.dataSource1;
			//var request='';
			//datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
			if(r.key=='name'){
			    Dom.get('title_name').innerHTML=r.newvalue;
			    Dom.get('name').value=r.newvalue;
			    Dom.get('name').setAttribute('ovalue',r.newvalue);

			}
			
		
		    }else{
			validate_scope_data[r.key].changed=true;
			validate_scope_data[r.key].validated=false;
			Dom.get(validate_scope_data[r.key].name+'_msg').innerHTML=r.msg;
			
		    }
		    
		}
			    
	    });
	}
    }
    
    
}
function reset_edit_customer(){
    for(item in validate_scope_data){
	var item_input=Dom.get(validate_scope_data[item].name);
	item_input.value=item_input.getAttribute('ovalue');
	validate_scope_data[item].changed=false;
	validate_scope_data[item].validated=true;
	Dom.get(validate_scope_data[item].name+'_msg').innerHTML='';
    }
    validate_scope(); 
};
function init(){
    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
    oAutoComp.minQueryLength = 0; 
    var ids = ["details","company"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
    
    YAHOO.util.Event.addListener('save_edit_customer', "click", save_edit_customer);
    YAHOO.util.Event.addListener('reset_edit_customer', "click", reset_edit_customer);
    
    var customer_name_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_name);
    customer_name_oACDS.queryMatchContains = true;
    var customer_name_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Name","Customer_Name_Container", customer_name_oACDS);
	customer_name_oAutoComp.minQueryLength = 0; 
	customer_name_oAutoComp.queryDelay = 0.1;
	
	edit_address(<?php echo $customer->data['Customer Main Address Key']?>,'contact_');
	
	edit_address(<?php echo $customer->data['Customer Billing Address Key']?>,'contact_');

}

YAHOO.util.Event.onDOMReady(init);
YAHOO.util.Event.onContentReady("filtermenu", function () {
	var oMenu = new YAHOO.widget.Menu("filtermenu", { context:["filter_name0","tr", "br"]  });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	YAHOO.util.Event.addListener("filter_name0", "click", oMenu.show, null, oMenu);
    });
YAHOO.util.Event.onContentReady("rppmenu", function () {
	var oMenu = new YAHOO.widget.Menu("rppmenu", { context:["filter_name0","tr", "bl"]  });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	YAHOO.util.Event.addListener("paginator_info0", "click", oMenu.show, null, oMenu);
    });

