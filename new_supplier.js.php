<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2010 Inikoo
include_once 'common.php';
?>
   var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var Subject='Supplier';
var scope='supplier';
var suggest_country=true;
var suggest_d1=true;
var suggest_d2=true;
var suggest_d3=true;
var suggest_d4=false;
var suggest_d4=false;
var suggest_town=true;
var contact_with_same_email=0;
var subject_found_email=false;
var subject_found=false;
var subject_found_key=0;

var subject_data={
    "Supplier Name":""
    ,"Supplier Main Contact Name":""
    ,"Supplier Tax Number":""
    ,"Supplier Registration Number":""
    ,"Supplier Main Plain Email":""
    ,"Supplier Main Plain Telephone":""
    ,"Supplier Main Plain FAX":""
    ,"Supplier Main Plain Mobile":""
    ,"Supplier Address Line 1":""
    ,"Supplier Address Line 2":""
    ,"Supplier Address Line 3":""
    ,"Supplier Address Town":""
    ,"Supplier Address Postal Code":""
    ,"Supplier Address Country Name":""
    ,"Supplier Address Country Code":""
    ,"Supplier Address Town Second Division":""
    ,"Supplier Address Town First Division":""
    ,"Supplier Address Country First Division":""
    ,"Supplier Address Country Second Division":""
    ,"Supplier Address Country Third Division":""
    ,"Supplier Address Country Forth Division":""
    ,"Supplier Address Country Fifth Division":""

    
};

var validate_scope_data={
'supplier':{
		    'postal_code':{'inputed':false,'validated':false,'required':false,'group':2,'type':'component','parent':'address'}
		   ,'town':{'inputed':false,'validated':false,'required':false,'group':2,'type':'component','parent':'address','regexp':"[a-z]+"}
		   ,'street':{'inputed':false,'validated':false,'required':false,'group':2,'type':'component','parent':'address','regexp':"[a-z\\d]+"}
		   ,'building':{'inputed':false,'validated':false,'required':false,'group':2,'type':'component','parent':'address','regexp':"[a-z\\d]+"}
		   ,'internal':{'inputed':false,'validated':false,'required':false,'group':2,'type':'component','parent':'address','regexp':"[a-z\\d]+"}

		   ,'country':{'inputed':false,'validated':false,'required':false,'group':2,'type':'component','parent':'address'}
		   ,'address':{'inputed':false,'validated':false,'required':false,'group':1,'type':'item'}
		   ,'email':{'inputed':false,'validated':false,'required':false,'group':1,'type':'item'}
		   ,'telephone':{'inputed':false,'validated':false,'required':false,'group':1,'type':'item','regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*(\\s*(ext|x|e)\\s*\\d+)?$"}
		   ,'company_name':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':true,'group':0,'type':'item'}
		   ,'contact_name':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':false,'group':0,'type':'item'}
}};

var validate_scope_metadata={
'supplier':{'type':'new','ar_file':'ar_edit_suppliers.php','key_name':'supplier_key','key':<?php echo $_SESSION['state']['supplier']['id']?>}

};

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {





  var tableid=100; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

 this.remove_links = function(elLiner, oRecord, oColumn, oData) {
  elLiner.innerHTML = oData;
         //   if(oRecord.getData("field3") > 100) {
       elLiner.innerHTML=  oData.replace(/<.*?>/g, '')

        };
        
        // Add the custom formatter to the shortcuts
        YAHOO.widget.DataTable.Formatter.remove_links = this.remove_links;

	   
	    var ColumnDefs = [
                    {key:"flag", label:"",width:10,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"code",formatter:"remove_links", label:"<?php echo _('Code')?>",width:30,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			       ,{key:"name", formatter:"remove_links",label:"<?php echo _('Name')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			];
			       
	    this.dataSource100 = new YAHOO.util.DataSource("ar_regions.php?tipo=country_list&tableid="+tableid+"&nr=20&sf=0&f_value=");
	    this.dataSource100.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource100.connXhrMode = "queueRequests";
	    	    this.dataSource100.table_id=tableid;

	    this.dataSource100.responseSchema = {
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
			 "name","flag",'code','population','gnp','wregion','code3a','code2a','plain_name','postal_regex','postcode_help'
			 ]};


	    this.table100 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource100
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['world']['countries']['nr']?>,containers : 'paginator100', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info100'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['world']['countries']['order']?>",
									 dir: "<?php echo$_SESSION['state']['world']['countries']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table100.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table100.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table100.subscribe("cellClickEvent", this.table100.onEventShowCellEditor);
this.table100.prefix='';
 this.table100.subscribe("rowMouseoverEvent", this.table100.onEventHighlightRow);
       this.table100.subscribe("rowMouseoutEvent", this.table100.onEventUnhighlightRow);
      this.table100.subscribe("rowClickEvent", select_country_from_list);
     


	    this.table100.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table100.filter={key:'<?php echo$_SESSION['state']['world']['countries']['f_field']?>',value:''};
	    //

};

    });


function validate_telephone(query){
validate_general('supplier','telephone',unescape(query));
}

function select_country_from_list(oArgs){
record=tables.table100.getRecord(oArgs.target)
var data={
    'code':record.getData('code3a'),
    'code2a':record.getData('code2a'),
      'name':record.getData('plain_name'),
    'postal_regex':record.getData('postal_regex'),
    'postcode_help':record.getData('postcode_help')
    
    }
  Dom.get(tables.table100.prefix+'address_country').value= record.getData('plain_name')+ " (" + record.getData('code3a') + ") ";

  change_country(tables.table100.prefix,data);
    dialog_country_list.hide();
    hide_filter(true,2)
}


function validate_company_name (query) {
/*
    var validator=new RegExp(validate_scope_data['supplier'].company_name.regexp,"i");

    if(validator.test(query)){
	validate_scope_data['supplier'].company_name.validated=true;
    }else{
	validate_scope_data['supplier'].company_name.validated=false;
    }
 */
    get_subject_data();
    //find_subject();
    //validate_form();
validate_general('supplier','company_name',unescape(query));
};

/*
function validate_form(){

	
      
	 valid_form=true;
	for (item in validate_scope_data['supplier'] ){
	    if(validate_scope_data['supplier'][item].required==true && validate_scope_data['supplier'][item].validated==false){
		valid_form=false;
		
			    //	    alert(item+' '+validate_scope_data['supplier'][item].required+' '+validate_scope_data['supplier'][item].validated)

	    }
	    if(validate_scope_data['supplier'][item].inputed==true && validate_scope_data['supplier'][item].validated==false){
	//	valid_form=false;
	    }
	}

	var validate_group_id=1;
	var min_valid_items=1;
	var valid_items_in_group=0;
	for (item in validate_scope_data['supplier'] ){
	   
	    if(validate_scope_data['supplier'][item].group==validate_group_id){
		
		if( validate_scope_data['supplier'][item].validated==true && validate_scope_data['supplier'][item].inputed==true ){
		    valid_items_in_group++;
		}
	    }

	}
	//	alert(validate_scope_data['supplier'].email.validated+' '+validate_scope_data['supplier'].email.inputed)
	if(valid_items_in_group<min_valid_items){
	    //valid_form=false;
	}

	
	if(valid_form==true){
	    Dom.removeClass('save_new_'+Subject,'disabled');
	    can_add_subject=true;
	}else{
	    can_add_subject=false;
	    Dom.addClass('save_new_'+Subject,'disabled');
	}
	
}
*/
function get_subject_data(){
    subject_data[Subject+' Name']=Dom.get('Company_Name').value;
        subject_data[Subject+' Tax Number']=Dom.get('Company_Tax_Number').value;
        subject_data[Subject+' Registration Number']=Dom.get('Company_Registration_Number').value;

}

function get_contact_data(){
    subject_data[Subject+' Main Contact Name']=Dom.get('Contact_Name').value;
	subject_data[Subject+' Main Plain Telephone']=Dom.get('Telephone').value;
	subject_data[Subject+' Main Plain FAX']=Dom.get('FAX').value;
	subject_data[Subject+' Main Plain Mobile']=Dom.get('Mobile').value;
subject_data[Subject+' Main Plain Email']=Dom.get('Email').value;

}

function get_data(){
    get_subject_data();
    get_contact_data();
    get_address_data();
   
    get_scope_data();
    get_custom_data();
}

function get_custom_data(){
}

function get_scope_data(){
	if(scope=='supplier'){
		subject_data['Supplier Code']=Dom.get('Supplier_Code').value;
	}
}

function get_address_data(){

    subject_data[Subject+' Address Line 1']=Dom.get('address_internal').value;
    subject_data[Subject+' Address Line 2']=Dom.get('address_building').value;

    subject_data[Subject+' Address Line 3']=Dom.get('address_street').value;
    subject_data[Subject+' Address Town']=Dom.get('address_town').value;
      
    subject_data[Subject+' Address Town Second Division']=Dom.get('address_town_d2').value;
    subject_data[Subject+' Address Town First Division']=Dom.get('address_town_d1').value;
    subject_data[Subject+' Address Postal Code']=Dom.get('address_postal_code').value;
       
    subject_data[Subject+' Address Country Code']=Dom.get('address_country_code').value;
    subject_data[Subject+' Address Country First Division']=Dom.get('address_country_d1').value;
    subject_data[Subject+' Address Country Second Division']=Dom.get('address_country_d2').value;
    subject_data[Subject+' Address Country Third Division']=Dom.get('address_country_d3').value;
    subject_data[Subject+' Address Country Forth Division']=Dom.get('address_country_d4').value;
    
    subject_data[Subject+' Address Country Fifth Division']=Dom.get('address_country_d5').value;


}

function get_contact_data(){
	subject_data[Subject+' Main Contact Name']=Dom.get('Contact_Name').value;
	subject_data[Subject+' Main Plain Telephone']=Dom.get('Telephone').value;
	subject_data[Subject+' Main Plain FAX']=Dom.get('FAX').value;
	subject_data[Subject+' Main Plain Mobile']=Dom.get('Mobile').value;
	subject_data[Subject+' Main Plain Email']=Dom.get('Email').value;

}

function find_subject(){
    get_data();

    var json_value = YAHOO.lang.JSON.stringify(subject_data); 
var json_value_scope = YAHOO.lang.JSON.stringify({scope:scope,store_key:store_key}); 


    var request='ar_contacts.php?tipo=show_posible_customer_matches&values=' + my_encodeURIComponent(json_value)+'&scope=' + my_encodeURIComponent(json_value_scope); 
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
	//	alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		var old_subject_found=subject_found;
		var old_subject_found_email=subject_found_email;
Dom.get('found_email_other_store_customer_key').value=0;
		if(r.action=='found_email'){
		
	
		
		    subject_found=true;
		    subject_found_email=true;
		    subject_found_key=r.found_key;
		    display_form_state();
		    contact_with_same_email=r.found_key;
		    
		    		    Dom.get('email_founded_name').innerHTML=r.found_name;

		    //alert(subject_found+' '+subject_found_email);
		     update_save_button();
		}else if(r.action=='found_email_other_store'){
		
	subject_found_email_other_store=true;
		
		    subject_found=true;
		    subject_found_key=r.found_key;
		    display_form_state();
		    contact_with_same_email=r.found_key;
		    Dom.get('found_email_other_store_customer_key').value=r.found_key;
		    Dom.get('email_founded_name').innerHTML=r.found_name;

		    //alert(subject_found+' '+subject_found_email);
		     update_save_button();
		}else if(r.action=='found'){
		    subject_found=true;
		    subject_found_email=false;
		    subject_found_key=r.found_key;
		    subject_found_name=r.found_name;
		    Dom.get('founded_name').innerHTML=r.found_name;
		    display_form_state();
		     update_save_button();
		}else if(r.action=='found_candidates'){
		    subject_found=false; subject_found_email=false;
		    subject_found_key=0;
		     display_form_state();
		      update_save_button();
		}else{
		    subject_found=false; subject_found_email=false;
		    subject_found_key=0; 
		       display_form_state();
		        update_save_button();
		        
		}


		Dom.get("results").innerHTML='';
		var count=0;
		
		for(x in r.candidates_data){
		    
		    Dom.get("results").innerHTML+='<div style="width:100%;"><div style="width:270px;margin:0px 0px 10px 0;float:left;margin-left:10px" class="contact_display">'+r.candidates_data[x]['card']+'</div> <div style="xborder:1px solid green;margin-left:300px;;margin-top:5px"><div id="score_'+r.candidates_data[x]['tipo']+r.candidates_data[x]['key']+'" >'+r.candidates_data[x]['score']+'</div><div style="font-size:80%">'+r.candidates_data[x]['link']+'</div>  <div style="clear:both"></div><div style="clear:both"> </div>';
		    
		    var found_img='';
		    // alert(r.candidates_data[x]['found']);return;
		    if(r.candidates_data[x]['found']==1)
			found_img='<img src="art/icons/award_star_gold_1.png"/>';
		    
		    Dom.get('score_'+r.candidates_data[x]['tipo']+r.candidates_data[x]['key']).innerHTML=star_rating(r.candidates_data[x]['score'],200).innerHTML+found_img+'<span style="font-size:80%;margin-left:0px"> Score ('+Math.round(r.candidates_data[x]['score'])+')</span>';

		}

		
	    }
	});

}

function get_subject_data(){
    subject_data[Subject+' Name']=Dom.get('Company_Name').value;
        subject_data[Subject+' Tax Number']=Dom.get('Company_Tax_Number').value;
        subject_data[Subject+' Registration Number']=Dom.get('Company_Registration_Number').value;

}

function save_new_supplier(e){
   
 
    if(!can_add_subject){
	return;
    }



    get_data();
 Dom.setStyle("creating_message",'display','');
Dom.setStyle(["new_Supplier_buttons"],'display','none');
 
        var ar_file='ar_edit_suppliers.php';
   
  


   var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(subject_data));
   //alert(json_value);
    //var json_value = YAHOO.lang.JSON.stringify(subject_data); 
    var request=ar_file+'?tipo=new_'+scope+'&delete_email='+subject_found_email+'&values=' + json_value; 
	
  alert(request);
//return;

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		alert(o.responseText);

		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='created'){
		  window.location='supplier.php?r=nc&id='+r.supplier_key;    		    
		}else{
		    alert(r.msg);
		}
			    

			
	    }
	});

}


function init(){

	var company_name_oACDS = new YAHOO.util.FunctionDataSource(validate_company_name);
	company_name_oACDS.queryMatchContains = true;
	var company_name_oAutoComp = new YAHOO.widget.AutoComplete("Company_Name","Company_Name_Container", company_name_oACDS);
	company_name_oAutoComp.minQueryLength = 0; 
	company_name_oAutoComp.queryDelay = 0.75;
	company_name_oAutoComp.autoHighlight = false;

	var company_name_oACDS = new YAHOO.util.FunctionDataSource(validate_telephone);
	company_name_oACDS.queryMatchContains = true;
	var company_name_oAutoComp = new YAHOO.widget.AutoComplete("Telephone","Telephone_Container", company_name_oACDS);
	company_name_oAutoComp.minQueryLength = 0; 
	company_name_oAutoComp.queryDelay = 0.75;
	company_name_oAutoComp.autoHighlight = false;


	YAHOO.util.Event.addListener(['save_new_'+Subject,'save_when_founded','force_new'], "click",save_new_supplier);

}

YAHOO.util.Event.onDOMReady(init);
