<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
//$connect_to_external=true;
include_once('common.php');
$sql=sprintf("select * from `Deal Dimension`D where D.`Deal Trigger`='Family' and D.`Deal Trigger Key`= %d ",$_SESSION['state']['family']['id']);
$res=mysql_query($sql);
$deal_data="";
while($row=mysql_fetch_array($res)){
  $deal_data.=sprintf(',"%d":{"terms":{"ovalue":"%s","type":"%s"},"allowances":{"ovalue":"%s","type":"%s"}}'."\n"
		      ,$row['Deal Key']
		      ,$row['Deal Terms Metadata']
		      ,$row['Deal Terms Type']
		      ,$row['Deal Allowance Metadata']
		      ,$row['Deal Allowance Type']
		      );

}
mysql_free_result($res);
$deal_data=preg_replace('/^,/','',$deal_data);
$deal_data="var deal_data={\n$deal_data};\n";
print $deal_data;

?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var family_id=<?php echo$_SESSION['state']['family']['id']?>;

var scope_key=<?php echo$_SESSION['state']['family']['id']?>;
var scope='family';
var scope_edit_ar_file='ar_edit_assets.php';
var scope_key_name='id';
var store_key=<?php echo$_SESSION['state']['store']['id']?>;


var validate_scope_metadata={
    'family':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'id','key':<?php echo$_SESSION['state']['family']['id']?>}
    ,'family_page_html_head':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'id','key':<?php echo$_SESSION['state']['family']['id']?>}
    ,'family_page_header':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'id','key':<?php echo$_SESSION['state']['family']['id']?>}
,'family_page_content':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'id','key':<?php echo$_SESSION['state']['family']['id']?>}
};

var validate_scope_data={
    'family':{
	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item'
		,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Family Name')?>'}],'name':'name'
		,'ar':'find','ar_request':'ar_assets.php?tipo=is_family_name&store_key='+store_key+'&query='}
	,'code':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Family Code')?>'}]
		 ,'name':'code','ar':'find','ar_request':'ar_assets.php?tipo=is_family_code&store_key='+store_key+'&query='}
	,'special_char':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
			 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Family Special Characteristic')?>'}]
			 ,'name':'special_char','ar':'find','ar_request':'ar_assets.php?tipo=is_family_special_char&store_key='+store_key+'&query='}
	,'description':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'description','ar':false}
    }
    ,'family_page_html_head':{
	'url':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid URL')?>'}]
		 ,'name':'family_page_html_head_url','ar':false
		 
	}
	,'title':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Title')?>'}]
		 ,'name':'family_page_html_head_title','ar':false
		 
	}
	
	,'keywords':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'family_page_html_head_keywords','ar':false}
    }
,'family_page_header':{
	'store_title':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Title')?>'}]
		 ,'name':'family_page_header_store_title','ar':false
		 
	}
	,'subtitle':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'family_page_header_subtitle','ar':false}
	,'slogan':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'family_page_header_slogan','ar':false}
	,'resume':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'family_page_header_resume','ar':false}
	
    }
,'family_page_content':{
	
	'presentation_template_data':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'family_page_content_presentation_template_data','ar':false}
	
    }


};
function validate_family_page_content_presentation_template_data(query){validate_general('family_page_content','presentation_template_data',unescape(query));}


function validate_family_page_header_store_title(query){validate_general('family_page_header','store_title',unescape(query));}
function validate_family_page_header_subtitle(query){validate_general('family_page_header','subtitle',unescape(query));}
function validate_family_page_header_slogan(query){validate_general('family_page_header','slogan',unescape(query));}
function validate_family_page_header_resume(query){validate_general('family_page_header','resume',unescape(query));}


function validate_family_page_html_head_url(query){validate_general('family_page_html_head','url',unescape(query));}

function validate_family_page_html_head_title(query){validate_general('family_page_html_head','title',unescape(query));}
function validate_family_page_html_head_keywords(query){validate_general('family_page_html_head','keywords',unescape(query));}


function validate_code(query){
   
 validate_general('family','code',unescape(query));
}
function validate_name(query){
 validate_general('family','name',unescape(query));
}
function validate_special_char(query){
 validate_general('family','special_char',unescape(query));
}

function validate_description(query){
   
 validate_general('family','description',unescape(query));
}


function reset_edit_family(){
 reset_edit_general('family');
}
function save_edit_family(){
 save_edit_general('family');
}
function reset_edit_family_page_header(){ reset_edit_general('family_page_header');}
function save_edit_family_page_header(){save_edit_general('family_page_header');}

function reset_edit_family_page_html_head(){ reset_edit_general('family_page_html_head');}
function save_edit_family_page_html_head(){save_edit_general('family_page_html_head');}

function reset_edit_family_page_content(){ reset_edit_general('family_page_content');}
function save_edit_family_page_content(){save_edit_general('family_page_content');}


function post_item_updated_actions(branch,r){
key=r.key;
newvalue=r.newvalue;
 if(key=='name')
     Dom.get('title_name').innerHTML=newvalue;
 
 else if(key=='code')
     Dom.get('title_code').innerHTML=newvalue;

 
 var table=tables.table1;
 var datasource=tables.dataSource1;
 var request='';
 datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
 
}




function change_block(e){
   
     
     
     
     Dom.setStyle(['d_products','d_details','d_discounts','d_pictures','d_web'],'display','none');
     
	
	 Dom.get('d_'+this.id).style.display='';
	 Dom.removeClass(['products','details','discounts','pictures','web'],'selected');
	 Dom.addClass(this, 'selected');
	 //alert('ar_sessions.php?tipo=update&keys=family-edit&value='+this.id );
	 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=family-editing&value='+this.id ,{});

   
}

 var CellEdit = function (callback, newValue) {


		var record = this.getRecord(),
		column = this.getColumn(),
		oldValue = this.value,
		datatable = this.getDataTable(),
		recordIndex = datatable.getRecordIndex(record);


		YAHOO.util.Connect.asyncRequest(
						'POST',
						'ar_edit_assets.php', {
						    success:function(o) {
							//	alert(o.responseText);
							var r = YAHOO.lang.JSON.parse(o.responseText);
							if (r.state == 200) {

							    if(column.key=='price' || column.key=='unit_price' || column.key=='margin' ){
								
                               datatable.updateCell(record,'unit_price',r.newdata['Unit Price']);
							   datatable.updateCell(record,'margin',r.newdata['Margin']);
                               datatable.updateCell(record,'price',r.newdata['Price']);
                               datatable.updateCell(record,'rrp_info','<?php echo _('Margin')?> '+r.newdata['RRP Margin']);

								
								
								//datatable.updateRow(recordIndex,data);
								callback(true,r.newvalue);
								
							    }else if(column.key=='unit_rrp'  ){
								 datatable.updateCell(record,'unit_rrp',r.newdata['RRP Per Unit']);
                               datatable.updateCell(record,'rrp_info','<?php echo _('Margin')?> '+r.newdata['RRP Margin']);
								
								callback(true, r.newvalue);
								
							    }else if(column.key=='web_configuration'  ){
								 datatable.updateCell(record,'smallname',r.newdata['description']);
								 datatable.updateCell(record,'formated_web_configuration',r.newdata['formated_web_configuration']);
								 datatable.updateCell(record,'web_configuration',r.newdata['web_configuration']);


                             	// alert(r.newdata['web_configuration'])   
								callback(true, r.newdata['web_configuration']);
								
							    }else{
							
								callback(true, r.newvalue);
								
							    }
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
						'tipo=edit_'+column.object+'&key=' + column.key + '&newvalue=' + 
						encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ 
						myBuildUrl(datatable,record)
						
						);  
 };







function new_product_changed(o){
    if(Dom.get("new_code").value!='' && Dom.get("new_code").value!='')
	Dom.get("add_new_product").style.display='';
    else
	Dom.get("add_new_product").style.display='';


}

function deal_term_save(deal_key){
deal_save(deal_key,'term');
}
function deal_allowance_save(deal_key){
deal_save(deal_key,'allowance');
}
function deal_save(deal_key,key){
	
        
       
        var newValue=Dom.get('deal_'+key+deal_key).value;
        var oldValue=Dom.get('deal_'+key+deal_key).getAttribute('ovalue');

		var request='tipo=edit_deal&key=' + key + '&newvalue=' + 
						encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ '&deal_key='+deal_key

		YAHOO.util.Connect.asyncRequest(
						'POST',
						'ar_edit_assets.php', {
						    success:function(o) {
								alert(o.responseText);
							var r = YAHOO.lang.JSON.parse(o.responseText);
							if (r.state == 200) {

							 
								Dom.get('deal_description'+deal_key).innerHTML=r.description;
								Dom.get('deal_'+key+deal_key).setAttribute=('ovalue',r.newvalue);
								Dom.get('deal_'+key+deal_key).value=r.newvalue;

								Dom.get('deal_'+key+'_save'+deal_key).style.display='none';
								Dom.get('deal_'+key+'_reset'+deal_key).style.display='none';
								
							    }else{
						
								
								
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
function deal_term_reset(deal_key){
    var data=deal_data[deal_key]['terms'];
    old_value=data.ovalue;
    Dom.get('deal_term_term'+deal_key).value=old_value;
    Dom.get('deal_term_save'+deal_key).style.visibility='hidden';
    Dom.get('deal_term_reset'+deal_key).style.visibility='hidden';
}



function deal_term_changed(deal_key){
    var data=deal_data[deal_key]['terms'];
    old_value=Dom.get('deal_term'+deal_key).getAttribute('ovalue');
    new_value=Dom.get('deal_term'+deal_key).value;

    if(old_value!=new_value){
	Dom.get('deal_term_reset'+deal_key).style.visibility='visible';

    switch(data.type){
    case('Order Interval'):

	break;

    case('Family Quantity Ordered'):
	
	
	Dom.get('deal_term_save'+deal_key).style.visibility='visible';

	var validator=/^\d+$/;
	if(!validator.test(new_value)){
	      Dom.get('deal_term_save'+deal_key).style.visibility='hidden';
	}
	break;


    }
    }else{
	
	Dom.get('deal_term_save'+deal_key).style.visibility='hidden';
	Dom.get('deal_term_reset'+deal_key).style.visibility='hidden';

    }

}
function old_deal_allowance_save(item,deal_key){

	var request='ar_edit_assets?tipo=edit_deal&key=' + item+ '&newvalue=' + 
	    encodeURIComponent(value) +  '&oldvalue=' + 
	    '&deal_key='+deal_key;
	//		alert(request)
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    //		   	    alert(o.responseText)
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state==200){
			
			
			
		
		    }else{
			validate_scope_data[branch][r.key].changed=true;
			validate_scope_data[branch][r.key].validated=false;
			Dom.get(validate_scope_data[branch][r.key].name+'_msg').innerHTML=r.msg;
			
		    }
		    
		}
			    
	    });
	}


function deal_allowance_changed(deal_key){
    var data=deal_data[deal_key]['allowances'];
        old_value=Dom.get('deal_allowance'+deal_key).getAttribute('ovalue');

    new_value=Dom.get('deal_allowance'+deal_key).value;
     //alert(old_value+'->'+new_value)
    if(old_value!=new_value){
	Dom.get('deal_allowance_reset'+deal_key).style.visibility='visible';

    switch(data.type){
    case('Get Same Fre'):
	break;
    case('Get Free'):
	break;
    
    case('Percentage Off'):
	
	
	Dom.get('deal_allowance_save'+deal_key).style.visibility='visible';

	var validator=/^(\d+|\.\d+|\d+.|\d+\.\d+)\s*\%?$/;
	if(!validator.test(new_value)){
	      Dom.get('deal_allowance_save'+deal_key).style.visibility='hidden';
	}
	break;


    }
    }else{
	
	Dom.get('deal_allowance_save'+deal_key).style.visibility='hidden';
	Dom.get('deal_allowance_reset'+deal_key).style.visibility='hidden';

    }

}





var description_num_changed=0;
var description_partrnings= new Object();
var description_errors= new Object();



  var change_view=function(e){
	
	var table=tables['table0'];
	var tipo=this.id;
	//	alert(table.view+' '+tipo)
	
	
	
	
	
	
	if(table.view!=tipo){
	    table.hideColumn('name');

	    table.hideColumn('sdescription');
	    table.hideColumn('units');
	    table.hideColumn('units_info');
	    table.hideColumn('price_info');
	    table.hideColumn('price');
	    table.hideColumn('unit_rrp');
	    table.hideColumn('rrp_info');
	    table.hideColumn('code');
	    table.hideColumn('code_price');

	    table.hideColumn('unit_type');
	    table.hideColumn('unit_price');
	    table.hideColumn('margin');

	    table.hideColumn('processing');
	    table.hideColumn('sales_state');
	    table.hideColumn('web_state');
	    table.hideColumn('state_info');
		table.hideColumn('smallname');
		table.hideColumn('sales_type');




	    if(tipo=='view_name'){
		table.showColumn('code');
		table.showColumn('name');

		table.showColumn('sdescription');	

	    }
	    else if(tipo=='view_units'){
		 table.showColumn('code');
		table.showColumn('units');
		table.showColumn('unit_type');

	    }
	     else if(tipo=='view_state'){
		 table.showColumn('code');
		table.showColumn('processing');
		table.showColumn('sales_state');
		table.showColumn('web_state');
		table.showColumn('state_info');
		table.showColumn('smallname');
table.showColumn('sales_type');

	    }
	    
	    else if(tipo=='view_price'){
		table.showColumn('code_price');
		table.showColumn('unit_price');
		table.showColumn('margin');
		table.showColumn('units_info');
		
		table.showColumn('price');
		table.showColumn('unit_rrp');
		table.showColumn('price_info');
		table.showColumn('rrp_info');


	    }
	    
	    


	Dom.get(table.view).className="";
	Dom.get(tipo).className="selected";
	table.view=tipo
	

	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=family-products-edit_view&value=' + escape(tipo),{} );
	}
  }




function update_form(){

    
    if(editing=='description'){
	this_errors=description_errors;
	this_num_changed=description_num_changed

    }

    if(this_num_changed>0){
	Dom.get(editing+'_save').style.display='';
	Dom.get(editing+'_reset').style.display='';

    }else{
	Dom.get(editing+'_save').style.display='none';
	Dom.get(editing+'_reset').style.display='none';

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


//function create_part(){
//    var part_description=Dom.get('new_name').value;
//    if(part_description=='')
//	part_description='??';
//    var part_used_in=Dom.get('new_code').value;
//    if(part_used_in=='')
//	part_used_in='??';


//    var data={sku:'TBC',description:part_description,usedin:part_used_in,partsperpick:1,notes:'',delete:'<img src="art/icons/cross.png">'}
//    tables.table1.addRow(data, 0);
//}

function edit_family_changed(o){
    var ovalue=o.getAttribute('ovalue');
    var name=o.name;
    if(ovalue!=o.value){
	if(name=='code'){
	    if(o.value==''){
		description_errors.code="<?php echo _("The family code can not be empty")?>";
	    }else if(o.value.lenght>16){
		description_errors.code="<?php echo _("The product code can not have more than 16 characters")?>";
	    }else
		delete description_errors.code;
	}
	if(name=='name'){
	    if(o.value==''){
		description_errors.name="<?php echo _("The family name can not be empty")?>";
	    }else if(o.value.lenght>255){
		description_errors.name="<?php echo _("The product code can not have more than 255  characters")?>";
	    }else
		delete description_errors.name;
	}
	if(name=='special_char'){
	    if(o.value==''){
		description_errors.special_char="<?php echo _("The family special characteristic can not be empty")?>";
	    }else
		delete description_errors.special_char;
	}


	if(o.getAttribute('changed')==0){
	    description_num_changed++;
	    o.setAttribute('changed',1);
	}
    }else{
	if(o.getAttribute('changed')==1){
	    description_num_changed--;
	    o.setAttribute('changed',0);
	}
    }
    update_form();
}

function reset(tipo){

    if(tipo=='description'){
	tag='name';
	Dom.get(tag).value=Dom.get(tag).getAttribute('ovalue');
	Dom.get(tag).setAttribute('changed',0);
	tag='code';
	Dom.get(tag).value=Dom.get(tag).getAttribute('ovalue');
	Dom.get(tag).setAttribute('changed',0);
	tag='special_char';
	Dom.get(tag).value=Dom.get(tag).getAttribute('ovalue');
	Dom.get(tag).setAttribute('changed',0);

	description_num_changed=0;
	Dom.get(editing+'_save').style.display='none';
	Dom.get(editing+'_reset').style.display='none';

	Dom.get(editing+'_num_changes').innerHTML=description_num_changed;
	description_partrnings= new Object();
	description_errors= new Object();
	
    }
    update_form();
}

function save(tipo){

    if(tipo=='description'){
	var keys=new Array("code","name","special_char");
	for (x in keys)
	    {
		 key=keys[x];
		 element=Dom.get(key);
		if(element.getAttribute('changed')==1){

		    newValue=element.value;
		    oldValue=element.getAttribute('ovalue');
		    
		    var request='ar_edit_assets.php?tipo=edit_family&key=' + key+ '&newvalue=' + 
			encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ 
			'&id='+family_id;
		  
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
				alert(o.responseText);
				var r =  YAHOO.lang.JSON.parse(o.responseText);
				if(r.state==200){
				    var element=Dom.get(r.key);
				    element.getAttribute('ovalue',r.newvalue);
				    element.value=r.newvalue;
				    element.setAttribute('changed',0);
				    description_num_changed--;
				    var table=tables.table1;
				    var datasource=tables.dataSource1;
				    var request='';
				    datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
				    if(r.key=='name')
					Dom.get('title_name').innerHTML=r.newvalue;
				    
				     if(r.key=='code')
					Dom.get('title_code').innerHTML=r.newvalue;
				    


				}else{
				    Dom.get('description_errors').innerHTML='<span class="error">'+r.msg+'</span>';
				    
				}
				update_form();	
			    }
			    
			});
		}
	    }
	
    }

}


function new_family_changed(o){
    if(Dom.get("new_code").value!='' && Dom.get("new_name").value!=''){
	Dom.get("add_new_family").style.display='';
    }else
	Dom.get("add_new_family").style.display='none';
}


function save_new_family(){

    var msg_div='add_family_messages';

    var code=Dom.get('new_code').value;
    var name=Dom.get('new_name').value;
    var description=Dom.get('new_description').innerHTML;
    var request='ar_edit_assets.php?tipo=new_family&code='+encodeURIComponent(code)+'&name='+encodeURIComponent(name)+'&description='+encodeURIComponent(name);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		    var table=tables['table0'];
		    var datasource=tables['dataSource0'];
		    var request='';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
		    Dom.get(msg_div).innerHTML='';
		}else
		    Dom.get(msg_div).innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	    
	    });

}

 function formater_web_configuration  (el, oRecord, oColumn, oData) {
		
		     el.innerHTML = oRecord.getData("formated_web_configuration");
	    }

var tmponCellClick = function(oArgs) {
		var target = oArgs.target,
		column = this.getColumn(target),
		record = this.getRecord(target);
		switch (column.action) {
		case 'delete':
		    this.deleteRow(target);
		    break;
		default:

		    this.onEventShowCellEditor(oArgs);
		    break;
		}
	    };    var highlightEditableCell = function(oArgs) {
		var target = oArgs.target;
		column = this.getColumn(target);

		switch (column.action) {
		case 'delete':
		    this.highlightRow(target);
		default:
		    if(YAHOO.util.Dom.hasClass(target, "yui-dt-editable")) {
			this.highlightCell(target);
		    }
		}
	    };

	      var unhighlightEditableCell = function(oArgs) {
		var target = oArgs.target;
		column = this.getColumn(target);

		switch (column.action) {
		case 'delete':
		    this.unhighlightRow(target);
		default:
		    if(YAHOO.util.Dom.hasClass(target, "yui-dt-editable")) {
			this.unhighlightCell(target);
		    }
		}
	    };




YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

   


	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"pid", label:"", hidden:true,action:"none",isPrimaryKey:true}
				    ,{key:"go", label:"", width:20,action:"none"}
				    ,{key:"code",<?php echo($_SESSION['state']['family']['products']['edit_view']!='view_price'?'':'hidden:true,')?>  label:"<?php echo _('Code')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"code_price",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_price'?'':'hidden:true,')?>  label:"<?php echo _('Code')?>", width:105,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				   // ,{key:"units_info",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_price'?'':'hidden:true,')?> label:"<?php echo _('Units')?>", width:30,className:"aleft"}
				    
				    ,{key:"name", label:"<?php echo _('Name')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_name'?'':'hidden:true,')?>width:340, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"smallname", label:"<?php echo _('Description')?>",width:380, sortable:true,className:"aleft",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_state'?'':'hidden:true,')?>className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				 //  ,{key:"processing", label:"<?php echo _('Editing State')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_state'?'':'hidden:true,')?>width:220, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},object:'product',editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:["<?php echo _('Editing')?>","<?php echo _('Live')?>"],disableBtns:true})}
				    ,{key:"sales_type", label:"<?php echo _('Sale Type')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_state'?'':'hidden:true,')?>width:100, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},object:'product',editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:["<?php echo _('Public Sale')?>","<?php echo _('Private Sale')?>","<?php echo _('Discontinue')?>","<?php echo _('Not For Sale')?>"],disableBtns:true})}
				    ,{key:"web_configuration" ,formatter: formater_web_configuration , label:"<?php echo _('Web Config')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_state'?'':'hidden:true,')?>width:120, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},object:'product',editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:[{'value':"Online Auto",'label':"<?php echo _('Auto')?><br/>"},{'value':"Online Force For Sale",'label':"<?php echo _('Force online')?><br/>"},{'label':'<?php echo _('Force out of stock')?><br/>','value':"Online Force Out of Stock"},{'label':"<?php echo _('Force offline')?>",'value':'Offline'}],disableBtns:true})}
//formatter: formater_web_configuration 
				    ,{key:"formated_web_configuration" , label:"",hidden:true}

				  //  ,{key:"web_state", label:"<?php echo _('Web Config')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_state'?'':'hidden:true,')?>width:120, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},object:'product',editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:["Online Auto":"<?php echo _('Auto')?>","<?php echo _('Force online')?>","<?php echo _('Force out of stock')?>","<?php echo _('Force offline')?>"],disableBtns:true})}


				    ,{key:"sdescription", label:"<?php echo _('Special Characteristic')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_name'?'':'hidden:true,')?>width:285, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"units", label:"<?php echo _('Units')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_units'?'':'hidden:true,')?>width:40, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"unit_type", label:"<?php echo _('Unit Type')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_units'?'':'hidden:true,')?>width:80, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"price", label:"<?php echo _('Price')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_price'?'':'hidden:true,')?>width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"unit_price", label:"<?php echo _('U Price')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_price'?'':'hidden:true,')?>width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"margin", label:"<?php echo _('Margin')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_price'?'':'hidden:true,')?>width:80, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}

				    ,{key:"price_info", label:"<?php echo _('Price Notes')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_price'?'':'hidden:true,')?>width:140, sortable:false,className:"aleft"}
				    ,{key:"unit_rrp", label:"<?php echo _('Unit RRP')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_price'?'':'hidden:true,')?>width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"rrp_info", label:"<?php echo _('RRP Notes')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_price'?'':'hidden:true,')?>width:120, sortable:false,className:"aleft"}
				    //,{key:"delete", label:"",width:100,className:"aleft",action:"delete",object:'product'}
				    //,{key:"delete_type", label:"",hidden:true,isTypeKey:true}

				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_assets.php?tipo=edit_products&parent=family");
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
			 "code","units_info","code_price",'go','smallname','sales_type','pid',
			 "name",
			 'delete','delete_type','id','sdescription','price','unit_rrp','units','unit_type','rrp_info','price_info','unit_price','margin','processing','sales_state','sales_state','formated_web_configuration','web_configuration'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['family']['products']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alpartysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['family']['products']['order']?>",
									 dir: "<?php echo$_SESSION['state']['family']['products']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;





	    this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table0.subscribe("cellClickEvent", onCellClick);
	    
	    this.table0.view='<?php echo$_SESSION['state']['family']['products']['edit_view']?>';



   var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"date",label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author",label:"<?php echo _('Author')?>", width:70,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //     ,{key:"tipo", label:"<?php echo _('Type')?>", width:90,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //,{key:"diff_qty",label:"<?php echo _('Qty')?>", width:90,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"abstract", label:"<?php echo _('Description')?>", width:370,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	    //?tipo=customers&tid=0"

	    this.dataSource1 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=family&tableid=1");
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
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
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource1
						     , {

							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['family']['history']['nr']?>,containers : 'paginator1', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['family']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['family']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['family']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['family']['history']['f_value']?>'};
	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown);



		

// 	    var tableid=1; // Change if you have more the 1 table
// 	    var tableDivEL="table"+tableid;
// 	    var OrdersColumnDefs = [ 
// 				    {key:"sku", label:"SKU", width:100, action:"none",isPrimaryKey:true}
// 				    ,{key:"description", label:"<?php echo _('Description')?>", width:220,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
// 				    ,{key:"usedin", label:"<?php echo _('Used in')?>",width:100, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
// 				    ,{key:"partsperpick", label:"<?php echo _('Parts/Pick')?>",width:70,className:"aleft"}
// 				    ,{key:"notes", label:"<?php echo _('Notes to Pickers')?>",width:180,className:"aleft"}
// 				    ,{key:"delete", label:"",width:20,className:"aleft",action:"delete",object:'tmp_partlist'}



// 				     ];

// 	    this.dataSource1 = new YAHOO.util.DataSource(YAHOO.util.Dom.get("table_parts_list")); 
// 	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_HTMLTABLE; 
// 	    this.dataSource1.responseSchema = {
// 		fields: [
// 			 "sku",
// 			 "description",
// 			 'usedin','partsperpick','notes','delete'
// 			 ]};
	    
// 	    this.table1 = new YAHOO.widget.DataTable("parts_list_container", OrdersColumnDefs,
// 						     this.dataSource1, {
// 								     sortedBy : {
// 									 key: "sku",
// 									 dir: "desc"
// 								     },
// 								     MSG_EMPTY:"<?php echo _('Please assign a part')?>"
// 						     }
// 						     );



// 	    this.table1.subscribe("cellMouseoverEvent", highlightEditableCell);
// 	    this.table1.subscribe("cellMouseoutEvent", unhighlightEditableCell);
// 	    this.table1.subscribe("cellClickEvent", tmponCellClick);
	    
    var tableid=4; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				          {key:"status",label:"", width:16,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					  ,{key:"name",label:"<?php echo _('Name')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"description",label:"<?php echo _('Description')?>", width:420,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"from",label:"<?php echo _('Valid From')?>", width:80,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"to",label:"<?php echo _('Valid Until')?>", width:80,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	    //?tipo=customers&tid=0"
	    
	    this.dataSource4 = new YAHOO.util.DataSource("ar_edit_assets.php?tipo=edit_deals&parent=family&tableid=4");
	    this.dataSource4.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource4.connXhrMode = "queueRequests";
	    this.dataSource4.responseSchema = {
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
			 "name"
			 ,"description","from","to","status"

			 ]};
 this.table4 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource4
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['store']['deals']['nr']?>,containers : 'paginator4', alpartysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info4'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['store']['deals']['order']?>",
							     dir: "<?php echo$_SESSION['state']['store']['deals']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table4.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table4.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table4.doBeforePaginatorChange = mydoBeforePaginatorChange;

	  
		    
	    this.table4.filter={key:'<?php echo $_SESSION['state']['store']['deals']['f_field']?>',value:'<?php echo $_SESSION['state']['store']['deals']['f_value']?>'};


	     var tableid=5; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"part_sku", label:"", hidden:true,action:"none",isPrimaryKey:true}
				       ,{key:"sku",label:"<?php echo _('SKU')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"picks",label:"<?php echo _('Picks')?>", width:30,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'part_new_product'}
				       ,{key:"description",label:"<?php echo _('Part Name')?>", width:230,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"notes",label:"<?php echo _('Notes for picker')?>", width:170,className:"aleft", editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'part_new_product' }
				       ,{key:"delete",label:"", width:20,className:"aleft",action:'delete',object:'part_new_product'}
				       
				       ];
	    //?tipo=customers&tid=0"
	    
	    this.dataSource5 = new YAHOO.util.DataSource("ar_edit_assets.php?tipo=part_list&product_id=0&tableid=5");
	    //this.dataSource5 =  new YAHOO.util.DataSource("ar_edit_assets.php?tipo=edit_deals&parent=family&tableid=4");
	    this.dataSource5.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource5.connXhrMode = "queueRequests";
	    this.dataSource5.responseSchema = {
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
			 "sku"
			 ,"description","picks","notes","delete","part_sku"

			 ]};
 this.table5 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource5
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    :<?php echo $_SESSION['state']['product']['parts']['nr']?> ,containers : 'paginator5', alpartysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info5'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    Key: "<?php echo $_SESSION['state']['product']['parts']['order']?>",
							     dir: "<?php echo $_SESSION['state']['product']['parts']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table5.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table5.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table5.doBeforePaginatorChange = mydoBeforePaginatorChange;


	    this.table5.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table5.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table5.subscribe("cellClickEvent", onCellClick);
		    
	    this.table5.filter={key:'<?php echo $_SESSION['state']['product']['parts']['f_field']?>',value:'<?php echo $_SESSION['state']['product']['parts']['f_value']?>'};








	};
    });

function cancel_add_product(){
    Dom.get('new_code').value='';
    Dom.get('new_name').value='';
    
    hide_add_product_dialog(); 
}

function select_layout(e){

layout=this.getAttribute("layout");
page_key=Dom.get("edit_family_page").getAttribute("page_key");
checked=this.checked;

   

		var request='tipo=edit_page_layout&layout=' + layout + '&newvalue=' + encodeURIComponent(checked) + '&page_key='+page_key

		YAHOO.util.Connect.asyncRequest(
						'POST',
						'ar_edit_assets.php', {
						    success:function(o) {
							
							var r = YAHOO.lang.JSON.parse(o.responseText);
							if (r.state == 200) {

                                    if(r.newvalue=='Yes'){
                                   
                                    Dom.setStyle("layout_"+layout+"_options","display","");
                                    
                                    
                                    }else{
                                    Dom.setStyle("layout_"+layout+"_options","display","none");
                                    
                                    }
								
							    }else{
						
								
								
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


function hide_add_product_dialog(){
    Dom.get('new_product_dialog').style.display='none';
    Dom.get('add_product').style.display='';
    Dom.get('save_new_product').style.display='none';
    Dom.get('cancel_add_product').style.display='none';
}

function show_add_product_dialog(){
    
    Dom.get('new_product_dialog').style.display='';
    Dom.get('add_product').style.display='none';

    Dom.get('save_new_product').style.display='';

    Dom.addClass('save_new_product','disabled');
    Dom.get('cancel_add_product').style.display='';
    Dom.get('new_code').focus();

}




function init(){




   var ids = ["checkbox_thumbnails","checkbox_list","checkbox_slideshow","checkbox_manual"]; 
    YAHOO.util.Event.addListener(ids, "click", select_layout);



 	YAHOO.util.Event.on('uploadButton', 'click', upload_image);

 
    part_selected= function(sType, aArgs) {
	var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 
	//	    Dom.get("part_sku").value = oData[1];
	
	var ar_file='ar_edit_assets.php';
	YAHOO.util.Connect.asyncRequest(
					'POST',
					ar_file, {
					    success:function(o) {
						alert(o.responseText);
						var r = YAHOO.lang.JSON.parse(o.responseText);
						if (r.state == 200) {
							    var table=tables['table5'];
							    var datasource=tables['dataSource5'];
							    var request='';
							    datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
							    
							} else {
							    alert(r.msg);
							    
							}
						    },
							failure:function(o) {
							alert(o.statusText);
							
						    },
							scope:this
							},
						'tipo=add_part_new_product&sku='+oData['sku']
						
					    );  

	    
	};

  

    
    var ids = ["details","products","discounts","pictures","web"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
    
    // YAHOO.util.Event.addListener('add_product', "click", show_add_product_dialog);
    //YAHOO.util.Event.addListener('save_new_product', "click",save_new_product);
    //YAHOO.util.Event.addListener('cancel_add_product', "click", cancel_add_product);


ids=['view_name','view_price','view_state'];
YAHOO.util.Event.addListener(ids, "click",change_view)

 YAHOO.util.Event.addListener('reset_edit_family', "click", reset_edit_family);
    YAHOO.util.Event.addListener('save_edit_family', "click", save_edit_family);
 YAHOO.util.Event.addListener('reset_edit_family_page_html_head', "click", reset_edit_family_page_html_head);
    YAHOO.util.Event.addListener('save_edit_family_page_html_head', "click", save_edit_family_page_html_head);
 YAHOO.util.Event.addListener('reset_edit_family_page_header', "click", reset_edit_family_page_header);
    YAHOO.util.Event.addListener('save_edit_family_page_header', "click", save_edit_family_page_header);

YAHOO.util.Event.addListener('reset_edit_family_page_content', "click", reset_edit_family_page_content);
    YAHOO.util.Event.addListener('save_edit_family_page_content', "click", save_edit_family_page_content);

var family_code_oACDS = new YAHOO.util.FunctionDataSource(validate_code);
    family_code_oACDS.queryMatchContains = true;
    var family_code_oAutoComp = new YAHOO.widget.AutoComplete("code","code_Container", family_code_oACDS);
    family_code_oAutoComp.minQueryLength = 0; 
    family_code_oAutoComp.queryDelay = 0.1;
    
     var family_name_oACDS = new YAHOO.util.FunctionDataSource(validate_name);
    family_name_oACDS.queryMatchContains = true;
    var family_name_oAutoComp = new YAHOO.widget.AutoComplete("name","name_Container", family_name_oACDS);
    family_name_oAutoComp.minQueryLength = 0; 
    family_name_oAutoComp.queryDelay = 0.1;

   var family_special_char_oACDS = new YAHOO.util.FunctionDataSource(validate_special_char);
    family_special_char_oACDS.queryMatchContains = true;
    var family_special_char_oAutoComp = new YAHOO.widget.AutoComplete("special_char","special_char_Container", family_special_char_oACDS);
    family_special_char_oAutoComp.minQueryLength = 0; 
    family_special_char_oAutoComp.queryDelay = 0.1;

    var family_description_oACDS = new YAHOO.util.FunctionDataSource(validate_description);
    family_description_oACDS.queryMatchContains = true;
    var family_description_oAutoComp = new YAHOO.widget.AutoComplete("description","description_Container", family_description_oACDS);
    family_description_oAutoComp.minQueryLength = 0; 
    family_description_oAutoComp.queryDelay = 0.1;

 var family_page_html_head_url_oACDS = new YAHOO.util.FunctionDataSource(validate_family_page_html_head_url);
    family_page_html_head_url_oACDS.queryMatchContains = true;
    var family_page_html_head_url_oAutoComp = new YAHOO.widget.AutoComplete("family_page_html_head_url","family_page_html_head_url_Container", family_page_html_head_url_oACDS);
    family_page_html_head_url_oAutoComp.minQueryLength = 0; 
    family_page_html_head_url_oAutoComp.queryDelay = 0.1;
    
    
    var family_page_html_head_title_oACDS = new YAHOO.util.FunctionDataSource(validate_family_page_html_head_title);
    family_page_html_head_title_oACDS.queryMatchContains = true;
    var family_page_html_head_title_oAutoComp = new YAHOO.widget.AutoComplete("family_page_html_head_title","family_page_html_head_title_Container", family_page_html_head_title_oACDS);
    family_page_html_head_title_oAutoComp.minQueryLength = 0; 
    family_page_html_head_title_oAutoComp.queryDelay = 0.1;

    var family_page_html_head_keywords_oACDS = new YAHOO.util.FunctionDataSource(validate_family_page_html_head_keywords);
    family_page_html_head_keywords_oACDS.queryMatchContains = true;
    var family_page_html_head_keywords_oAutoComp = new YAHOO.widget.AutoComplete("family_page_html_head_keywords","family_page_html_head_keywords_Container", family_page_html_head_keywords_oACDS);
    family_page_html_head_keywords_oAutoComp.minQueryLength = 0; 
    family_page_html_head_keywords_oAutoComp.queryDelay = 0.1;


 var family_page_header_store_title_oACDS = new YAHOO.util.FunctionDataSource(validate_family_page_header_store_title);
    family_page_header_store_title_oACDS.queryMatchContains = true;
    var family_page_header_store_title_oAutoComp = new YAHOO.widget.AutoComplete("family_page_header_store_title","family_page_header_store_title_Container", family_page_header_store_title_oACDS);
    family_page_header_store_title_oAutoComp.minQueryLength = 0; 
    family_page_header_store_title_oAutoComp.queryDelay = 0.1;
    
 var family_page_header_subtitle_oACDS = new YAHOO.util.FunctionDataSource(validate_family_page_header_subtitle);
    family_page_header_subtitle_oACDS.queryMatchContains = true;
    var family_page_header_subtitle_oAutoComp = new YAHOO.widget.AutoComplete("family_page_header_subtitle","family_page_header_subtitle_Container", family_page_header_subtitle_oACDS);
    family_page_header_subtitle_oAutoComp.minQueryLength = 0; 
    family_page_header_subtitle_oAutoComp.queryDelay = 0.1;
var family_page_header_slogan_oACDS = new YAHOO.util.FunctionDataSource(validate_family_page_header_slogan);
    family_page_header_slogan_oACDS.queryMatchContains = true;
    var family_page_header_slogan_oAutoComp = new YAHOO.widget.AutoComplete("family_page_header_slogan","family_page_header_slogan_Container", family_page_header_slogan_oACDS);
    family_page_header_slogan_oAutoComp.minQueryLength = 0; 
    family_page_header_slogan_oAutoComp.queryDelay = 0.1;

    var family_page_header_resume_oACDS = new YAHOO.util.FunctionDataSource(validate_family_page_header_resume);
    family_page_header_resume_oACDS.queryMatchContains = true;
    var family_page_header_resume_oAutoComp = new YAHOO.widget.AutoComplete("family_page_header_resume","family_page_header_resume_Container", family_page_header_resume_oACDS);
    family_page_header_resume_oAutoComp.minQueryLength = 0; 
    family_page_header_resume_oAutoComp.queryDelay = 0.1;
    

    
    var family_page_content_presentation_template_data_oACDS = new YAHOO.util.FunctionDataSource(validate_family_page_content_presentation_template_data);
    
    family_page_content_presentation_template_data_oACDS.queryMatchContains = true;
    var family_page_content_presentation_template_data_oAutoComp = new YAHOO.widget.AutoComplete("family_page_content_presentation_template_data","family_page_content_presentation_template_data_Container", family_page_content_presentation_template_data_oACDS);
    family_page_content_presentation_template_data_oAutoComp.minQueryLength = 0; 
    family_page_content_presentation_template_data_oAutoComp.queryDelay = 0.1;


}

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



 //var dmenu_data;


 //var dmenu_selected=function(){
 //    var data = {
 //	"sku":dmenu_data[1]
 //	,"description":dmenu_data[2]
 //	,"usedin":dmenu_data[3]
 //  }; 
 //       Dom.get("dmenu_input").value='';

 //  var row={sku:data.sku,description:data.description,usedin:data.usedin,partsperpick:1,notes:'',delete:'<img src="art/icons/cross.png">'}
 //   tables.table1.addRow(row, 0);

 //     alert(tables.table1);
 //}
