
var Subject='Customer';
var subject_data={
    "Customer Company Name":""
    ,"Customer Main Contact Name":""
    ,"Customer Main Plain Email":""
    ,"Customer Main Plain Telephone":""
    ,"Customer Address Line 1":""
    ,"Customer Address Line 2":""
    ,"Customer Address Line 3":""
    ,"Customer Address Town":""
    ,"Customer Address Postal Code":""
    ,"Customer Address Country Name":""
    ,"Customer Address Country Code":""
    ,"Customer Address Town Second Division":""
    ,"Customer Address Town First Division":""
    ,"Customer Address Country First Division":""
    ,"Customer Address Country Second Division":""
    ,"Customer Address Country Third Division":""
    ,"Customer Address Country Forth Division":""
    ,"Customer Address Country Fifth Division":""
};  

 var valid_form=false;

var suggest_country=true;
var suggest_d1=true;
var suggest_d2=true;
var suggest_d3=true;
var suggest_d4=true;
var suggest_d4=true;
var suggest_town=false;
var contact_with_same_email=0;
var subject_found_email=false;
var subject_found=false;
var subject_found_key=0;
 var scope='customer';
 
 function get_contact_data(){
     subject_data[Subject+' Company Name']=Dom.get('Company_Name').value;

    subject_data[Subject+' Main Contact Name']=Dom.get('Contact_Name').value;
	subject_data[Subject+' Main Plain Telephone']=Dom.get('Telephone').value;
subject_data[Subject+' Main Plain Email']=Dom.get('Email').value;

}


var validate_scope_data=
{
    'search_field':{'Company_Name':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':false,'group':0,'type':'item', 'name':'Company_Name', 'dbname':'Company Name'}
					,'Contact_Name':{'inputed':false,'validated':false,'regexp':"[^\\d]+",'required':false,'group':0,'type':'item', 'name':'Contact_Name', 'dbname':'Contact Name'}
					,'Email':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':false,'group':0,'type':'item', 'name':'Email', 'dbname':'Email'}
					,'Telephone':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':false,'group':0,'type':'item', 'name':'Telephone', 'dbname':'Telephone'}
	}
}

var validate_scope_metadata={
 'search_field':{'type':'new','ar_file':'ar_search.php','key_name':'store_key','key':'1'}
};
 
YAHOO.util.Event.addListener(window, "load", draw_table);

function draw_table(){
	search_result();
}
 
function search_result(request) {

    tables = new function() {


	var store_key='1';

	var tableid=5; 
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
			
                    {key:"store", label:"<?php echo _('store')?>",width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},action:'select'}
                   ,{key:"key", label:"<?php echo _('key')?>",width:260,action:'select'}
				   ,{key:"name", label:"<?php echo _('name')?>",width:260,action:'select'}
				   ,{key:"address", label:"<?php echo _('address')?>",width:260,action:'select'}
				   
                   
				];
			       
	    //this.dataSource5 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=department_list&store_key=1&tableid=5&nr=20&sf=0");

		if(request)
			this.dataSource5 = new YAHOO.util.DataSource(request);
		else
			this.dataSource5 = new YAHOO.util.DataSource("ar_import.php?tipo=search_field&scope=customers_store&store_key=0&tableid="+tableid+"&nr=20&sf=0");
	
		this.dataSource5.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource5.connXhrMode = "queueRequests";
	    	    this.dataSource5.table_id=tableid;

	    this.dataSource5.responseSchema = {
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
			 "store","key","name","address"
			 ]};


	    this.table5 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource5
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator5', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info5'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "name",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	  
	    this.table5.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table5.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);





 this.table5.subscribe("rowMouseoverEvent", this.table5.onEventHighlightRow);
       this.table5.subscribe("rowMouseoutEvent", this.table5.onEventUnhighlightRow);
   //   this.table5.subscribe("rowClickEvent", select_map);
           	        this.table5.subscribe("cellClickEvent", select_map);            

           this.table5.table_id=tableid;
           this.table5.subscribe("renderEvent", myrenderEvent);



	    this.table5.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table5.filter={key:'code',value:''};
	    //
// --------------------------------------Department table ends here----------------------------------------------------------


/*

	*/
	};

    } 
 
function find_subject(){
   

    var json_value = YAHOO.lang.JSON.stringify(subject_data); 
var json_value_scope = YAHOO.lang.JSON.stringify({scope:scope,store_key:store_key}); 

    var request='ar_contacts.php?tipo=find_customer&values=' + encodeURIComponent(json_value)+'&scope=' + encodeURIComponent(json_value_scope); 
  //   alert(request) ;
//return;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
	//	alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		var old_subject_found=subject_found;
		var old_subject_found_email=subject_found_email;

		if(r.action=='found_email'){
		    subject_found=true;
		    subject_found_email=true;
		    subject_found_key=r.found_key;
		    display_form_state();
		    contact_with_same_email=r.found_key;
		    //alert(subject_found+' '+subject_found_email);
		     //update_save_button();
		}else if(r.action=='found'){
		    subject_found=true;
		    subject_found_email=false;
		    subject_found_key=r.found_key;
		  //  display_form_state();
		    // update_save_button();
		}else if(r.action=='found_candidates'){
		    subject_found=false; subject_found_email=false;
		    subject_found_key=0;
		   //  display_form_state();
		    //  update_save_button();
		}else{
		    subject_found=false; subject_found_email=false;
		    subject_found_key=0; 
		     //  display_form_state();
		     //   update_save_button();
		}
		//if(old_subject_found!=subject_found || old_subject_found_email!=subject_found_email){
		//    update_save_button();
		//	}
		//var old_subject_found=subject_found;
		//var old_subject_found_email=subject_found_email;
if(r.number_candidates==0){
Dom.setStyle('results_info','display','');

}else{
Dom.setStyle('results_info','display','none');
}
		Dom.get("results").innerHTML='';
		var count=0;
		
		for(x in r.candidates_data){
		    
		    Dom.get("results").innerHTML+='<div style="width:100%;"><div style="width:270px;margin:0px 0px 10px 0;float:left;margin-left:40px" class="contact_display">'+r.candidates_data[x]['card']+'</div> <div style="xborder:1px solid green;margin-left:350px;;margin-top:5px"><div id="score_'+r.candidates_data[x]['tipo']+r.candidates_data[x]['key']+'" >'+r.candidates_data[x]['score']+'</div><div style="font-size:80%">'+r.candidates_data[x]['link']+'</div>  <div style="clear:both"></div><div style="clear:both"> </div>';
		    
		    var found_img='';
		    // alert(r.candidates_data[x]['found']);return;
		    if(r.candidates_data[x]['found']==1)
			found_img='<img src="art/icons/award_star_gold_1.png"/>';
		    
		    Dom.get('score_'+r.candidates_data[x]['tipo']+r.candidates_data[x]['key']).innerHTML=star_rating(r.candidates_data[x]['score'],200).innerHTML+found_img+'<span style="font-size:80%;margin-left:0px"> Score ('+Math.round(r.candidates_data[x]['score'])+')</span>';
		    
		    
		    
		    //	    if(count % 2 || count==0)
		    //	Dom.get("results").innerHTML+='<tr>'+td;
		    //else
		    //	Dom.get("results").innerHTML+=td+'</tr>';
		}
		//	Dom.get("results").innerHTML+='</table>';
		
	    }
	});

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


function update_save_button(){
	//	alert(subject_found);
	validate_form();
	
	if(subject_found==true && valid_form){

	    Dom.get('save_new_'+Subject).style.display='none';
	    
	    if(subject_found_email==true){
		Dom.get('email_found_dialog').style.display='';
		Dom.get(Subject+'_found_dialog').style.display='none';

	    }else{
		Dom.get(Subject+'_found_dialog').style.display='';
		Dom.get('email_found_dialog').style.display='none';

	    }
	    
	}else{
	
	    Dom.get('save_new_'+Subject).style.display='';
	    Dom.get(Subject+'_found_dialog').style.display='none';
	    Dom.get('email_found_dialog').style.display='none';

	}
	
    }
function validate_form(){
return;
	display_form_state();
      
	 valid_form=true;
	for (item in validate_data ){
	    //	    alert(item+' '+validate_data[item].required+' '+validate_data[item].validated)
	    if(validate_data[item].required==true && validate_data[item].validated==false){
		valid_form=false;
		
	    }
	    if(validate_data[item].inputed==true && validate_data[item].validated==false){
	//	valid_form=false;
	    }
	}

	var validate_group_id=1;
	var min_valid_items=1;
	var valid_items_in_group=0;
	for (item in validate_data ){
	   
	    if(validate_data[item].group==validate_group_id){
		
		if( validate_data[item].validated==true && validate_data[item].inputed==true ){
		    
		    valid_items_in_group++;
		}
	    }

	}
	//	alert(validate_data.email.validated+' '+validate_data.email.inputed)
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
function display_form_state(){
    
    return;
    
  //  if(subject_found==true){
//	Dom.get('mark_'+Subject+'_found').style.display='';
 //   }else{
//	Dom.get('mark_'+Subject+'_found').style.display='none';

 //   }
    
    for (i in validate_data){
	// alert(i+'_valid')
	if(validate_data[i].validated==true)
	    Dom.get(i+'_valid').innerHTML="<img src='art/icons/accept.png'>";
	else{
	    
	    //Dom.get(i+'_valid').innerHTML="<img src='art/icons/cross.png'>";
	}

	if(validate_data[i].inputed==true){
	    
	    Dom.get(i+'_inputed').innerHTML="<img src='art/icons/accept.png'>";
	  


	}else{
	    //Dom.get(i+'_inputed').innerHTML="";
	}
	

    }
}



function validate_contact_name(query) {

 get_contact_data();
    find_subject();
    validate_form();
    return;
    item='contact_name';
    var validator=new RegExp(validate_data[item].regexp,"i");
    if(validator.test(query)){
	validate_data[item].validated=true;
    }else{
	validate_data[item].validated=false;
    }
    get_contact_data();
    find_subject();
    validate_form();
};

function validate_company_name(query) {

 get_contact_data();
    find_subject();
    validate_form();
    return;
   
};


function contact_name_inputed(){
return;
    var item='contact_name';
    var value=Dom.get('Contact_Name').value.replace(/\s+/,"");
    if(value=='')
	validate_data[item].inputed=false;
    else
	validate_data[item].inputed=true;
    display_form_state();
}    
function validate_telephone(original_query) {
    
    var tr=Dom.get('telephone_mould');
    var o=Dom.get('Telephone');
    value=original_query.replace(/[^\d]/g,"");
    //  alert(query)
    var item='telephone';

    if(original_query==''){
	validate_data[item].inputed=false;
	validate_data[item].validated=true;
	Dom.removeClass(tr,'no_validated');
	Dom.removeClass(tr,'validated');

	return;
    }
    else
	validate_data[item].inputed=true;


    var validator=new RegExp(validate_data[item].regexp,"i");
    
    if(validate_data[item].inputed==true){
	if(validator.test(value)){
	    Dom.removeClass(tr,'no_validated');
	    Dom.addClass(tr,'validated');
	    validate_data[item].validated=true;
	}else{
	    Dom.removeClass(tr,'validated');
	    Dom.addClass(tr,'no_validated');
	    validate_data[item].validated=false;
	}
    }else{
	if(validator.test(value) ){
	    Dom.addClass(tr,'validated');
	    validate_data[item].validated=true;
	}else{
	    Dom.removeClass(tr,'validated');
	    
	    validate_data[item].validated=false;

	}
	

    }
    get_contact_data();
    find_subject();
    validate_form();


};
function telephone_inputed(){
return;
    var item='telephone';
    var value=Dom.get('Telephone').value.replace(/\s+/,"");
    //  alert(value)
    if(value=='')
	validate_data[item].inputed=false;
    else
	validate_data[item].inputed=true;

    display_form_state();
    
    //validate_postal_code(postal_code);
    
}    
function edit_founded(){
if(scope=='customer')
    location.href='customer.php?edit='+subject_found_key;

else
    location.href='edit_'+scope+'.php?id='+subject_found_key;

}




function address_changed(query) {
  
    get_address_data();
    find_subject();
    //print_data();
};


function postal_code_inputed(){
return;
    var postal_code=Dom.get('address_postal_code').value.replace(/\s+/,"");
    if(postal_code=='')
	validate_data.postal_code.inputed=false;
    else
	validate_data.postal_code.inputed=true;

    

}
function validate_postal_code(){
 get_address_data();
    validate_form();

    find_subject();
    return;

    var postal_code=Dom.get('address_postal_code').value.replace(/\s+/,"");
    var o=Dom.get("address_postal_code");
    var tr=Dom.get('tr_address_postal_code');
    // alert(postal_regex+' '+postal_code)
    var item='postal_code';
    var valid=postal_regex.test(postal_code);

    if(postal_code!=''){
	validate_data.postal_code.inputed=true;
    }else{
	validate_data.postal_code.inputed=true;
		
    }

Dom.get('address_postal_code_warning').setAttribute('title',postcode_help);
    if(validate_data.postal_code.inputed==true){
	if(valid){
	    Dom.removeClass(tr,'no_validated');
	    Dom.addClass(tr,'validated');
	    validate_data[item].validated=true;
	    
	    Dom.get('address_postal_code_warning').style.visibility='hidden';
	}else{
	    //alert('hard no valid');
	    Dom.removeClass(tr,'validated');
	    Dom.addClass(tr,'no_validated');
	    validate_data[item].validated=false;
	    	    Dom.get('address_postal_code_warning').style.visibility='visible';

	}

    }else{
	
	Dom.removeClass(o,'no_validated');
	if(valid){
	    Dom.addClass(tr,'validated');
	    validate_data[item].validated=true;
	    	    Dom.get('address_postal_code_warning').style.visibility='hidden';

	}else{
	    // alert('no valid');
	    validate_data[item].validated=false;
	    Dom.removeClass(tr,'validated');
	    	    Dom.get('address_postal_code_warning').style.visibility='visible';

	}


    }


}
function email_inputed(){
return;
    var item='email';
    var value=Dom.get('Email').value.replace(/\s+/,"");
    var tr=Dom.get('email_mould')
    if(value=='')
	validate_data[item].inputed=false;
    else{
	validate_data[item].inputed=true;

		    
	if(validate_data.email.validated==true){
	    Dom.removeClass(tr,'no_validated');
	    Dom.addClass(tr,'validated');
	}else{
	    Dom.removeClass(tr,'validated');
	    Dom.addClass(tr,'no_validated');
	}
	
    }
    display_form_state();
    
}
function validate_email_address(email) {
 
     get_contact_data();
    validate_form();

    find_subject();
    return;

    var email=unescape(email);
    var o=Dom.get("Email");
    var tr=Dom.get('email_mould');
    var item='email';
    if(email==''){
	validate_data['email'].inputed=false;
	validate_data.email.validated=true;
	Dom.removeClass(tr,'no_validated');
	Dom.removeClass(tr,'validated');

	return;
    }else
	validate_data.email.inputed=true;

    
    // alert(email+' '+isValidEmail(email))

    if(validate_data.email.inputed==true){
	if(isValidEmail(email)){
	    Dom.removeClass(tr,'no_validated');
	    Dom.addClass(tr,'validated');
	    validate_data.email.validated=true;
	}else{
	    Dom.removeClass(tr,'validated');
	    Dom.addClass(tr,'no_validated');
	    validate_data.email.validated=false;
	}
    }else{
	
	Dom.removeClass(o,'no_validated');
	
	if(isValidEmail(email) ){
	    Dom.addClass(tr,'validated');
	    validate_data.email.validated=true;
	}else{
	    Dom.removeClass(tr,'validated');
	    
	    validate_data.email.validated=false;
	    //alert('x '+validate_data.email.validated);
	}


    }
    get_contact_data();
    validate_form();

    find_subject();
    


};





function print_data(){
    var data='';
    for(x in subject_data)
	data+=" "+x+": "+subject_data[x]+"<br/>";
    Dom.get("results").innerHTML=data;
}
function get_data(){
    get_subject_data();
    get_contact_data();
    get_address_data();
    get_scope_data();
}




function get_scope_data(){
if(scope=='supplier'){
    subject_data['Supplier Code']=Dom.get('Supplier_Code').value;

}
if(scope=='customer'){
    // alert(Dom.get('Store_Key'))
    subject_data['Customer Store Key']=Dom.get('Store_Key').value;

}



}

function select_map(){
}


function advanced_search(){
	//alert('search');

    var values=new Object();

    for (item in validate_scope_data['search_field']) {
        //
        var item_input=Dom.get(validate_scope_data['search_field'][item].name);

		values[validate_scope_data['search_field'][item].dbname]=item_input.value;
    }

    scope_edit_ar_file=validate_scope_metadata['search_field']['ar_file'];
    parent_key=validate_scope_metadata['search_field']['key'];
    parent=validate_scope_metadata['search_field']['key_name'];
    jsonificated_values=YAHOO.lang.JSON.stringify(values);

    var request=scope_edit_ar_file+'?tipo=search_field'+'&scope='+scope+'&store_id=' + store_key+ '&values=' + 	jsonificated_values+"&tableid=5";
	alert(request);
    //YAHOO.util.Connect.asyncRequest('POST',request , {});
	search_result(request);


}

function init(){



   store_key=Dom.get('Store_Key').value;
   scope=Dom.get('Scope').value;



	YAHOO.util.Event.addListener('Telephone', "blur",telephone_inputed);

	YAHOO.util.Event.addListener('Email', "blur",email_inputed);
	YAHOO.util.Event.addListener('address_postal_code', "blur",postal_code_inputed);
	YAHOO.util.Event.addListener('Contact_Name', "blur",contact_name_inputed);


	var ids = ["address_description","address_country_d1","address_country_d2","address_town"
		   ,"address_town_d2","address_town_d1","address_postal_code","address_street","address_internal","address_building"]; 
	YAHOO.util.Event.addListener(ids, "keyup", on_address_item_change_when_creating);
	YAHOO.util.Event.addListener(ids, "change",on_address_item_change_when_creating);
  

	
	if(suggest_d1){
	
	var Countries_d1_DS = new YAHOO.util.XHRDataSource("ar_kbase.php");
	Countries_d1_DS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON; 
	Countries_d1_DS.responseSchema = {resultsList : "data",fields : ["name","code"]};
	Countries_d1_DS.maxCacheEntries = 10;
	var Countries_d1_AC = new YAHOO.widget.AutoComplete("address_country_d1", "address_country_d1_container", Countries_d1_DS); 
	Countries_d1_AC.generateRequest = function(sQuery) 
	    {
		return "?tipo=country_d1&country_2acode="+Dom.get('address_country_2acode').value+"&query=" + sQuery ;
	    };
 	var Country_d1_selected = function(sType, aArgs) {
	    var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 
	    Dom.get("address_country_d1_code").value = oData[1];
	    myAC.getInputEl().value = oData[0] ;
	};
	Countries_d1_AC.itemSelectEvent.subscribe(Country_d1_selected); 
	
	}
	
	if(suggest_d2){
	var Countries_d2_DS = new YAHOO.util.XHRDataSource("ar_kbase.php");
	Countries_d2_DS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON; 
	Countries_d2_DS.responseSchema = {resultsList : "data",fields : ["name","code"]};
	Countries_d2_DS.maxCacheEntries = 10;
	var Countries_d2_AC = new YAHOO.widget.AutoComplete("address_country_d2", "address_country_d2_container", Countries_d2_DS); 
	Countries_d2_AC.generateRequest = function(sQuery) 
	    {
		var request="?tipo=country_d2&country_2acode="+Dom.get('address_country_2acode').value
	        +"&country_d1_code="+Dom.get('address_country_d1_code').value
	        +"&query=" + sQuery ;
		//	alert(request)
		return request;
	    };
 	var Country_d2_selected = function(sType, aArgs) {
	    var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 
	    Dom.get("address_country_d2_code").value = oData[1];
	    myAC.getInputEl().value = oData[0] ;
	};
	Countries_d2_AC.itemSelectEvent.subscribe(Country_d2_selected); 	
	}
	if(suggest_d3){
	var Countries_d3_DS = new YAHOO.util.XHRDataSource("ar_kbase.php");
	Countries_d3_DS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON; 
	Countries_d3_DS.responseSchema = {resultsList : "data",fields : ["name","code"]};
	Countries_d3_DS.maxCacheEntries = 10;
	var Countries_d3_AC = new YAHOO.widget.AutoComplete("address_country_d3", "address_country_d3_container", Countries_d3_DS); 
	Countries_d3_AC.generateRequest = function(sQuery) 
	    {
		return "?tipo=country_d3&country_2acode="+Dom.get('address_country_2acode').value
		+"&country_d1_code="+Dom.get('address_country_d1_code').value
		+"&country_d2_code="+Dom.get('address_country_d2_code').value
		+"&query=" + sQuery ;
	    };
 	var Country_d3_selected = function(sType, aArgs) {
	    var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 
	    Dom.get("address_country_d3_code").value = oData[1];
	    myAC.getInputEl().value = oData[0] ;
	};
	Countries_d3_AC.itemSelectEvent.subscribe(Country_d3_selected); 		
	}
	

	if(suggest_town){

	var Town_DS = new YAHOO.util.XHRDataSource("ar_kbase.php");
	Town_DS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON; 
	Town_DS.responseSchema = {resultsList : "data",fields : ["name"]};
	Town_DS.maxCacheEntries = 10;
	var Town_AC = new YAHOO.widget.AutoComplete("address_town", "address_town_container", Town_DS); 
	Town_AC.generateRequest = function(sQuery) 
	    {
		var request= "?tipo=town&country_2acode="+Dom.get('address_country_2acode').value
		+"&country_d1_code="+Dom.get('address_country_d1_code').value
		+"&country_d2_code="+Dom.get('address_country_d2_code').value	      
		+"&country_d3_code="+Dom.get('address_country_d3_code').value	 

		+"&query=" + sQuery ;
	        alert(request);
		return request;
	    };
 	var Country_1d_selected = function(sType, aArgs) {
	    var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 
	    Dom.get("address_country_d1_code").value = oData[1];
	    myAC.getInputEl().value = oData[0] ;
	};
	//  Town_AC.itemSelectEvent.subscribe(Country_1d_selected); 	
	
	}
	

	if(suggest_country){

	var Countries_DS = new YAHOO.util.FunctionDataSource(match_country);
	Countries_DS.responseSchema = {fields: ["id", "name", "code","code2a","postal_regex","postcode_help"]}
	var Countries_AC = new YAHOO.widget.AutoComplete("address_country", "address_country_container", Countries_DS);
	Countries_AC.forceSelection = true; 
	Countries_AC.useShadow = true;
	Countries_AC.resultTypeList = false;
	Countries_AC.formatResult = countries_format_results;
    var highlightMatch = countries_highlightMatch;
	Countries_AC.itemSelectEvent.subscribe(onCountrySelected);

	}
 

	


 	YAHOO.util.Event.addListener("advanced_search", "click", advanced_search);






 
    } 
YAHOO.util.Event.onDOMReady(init);



