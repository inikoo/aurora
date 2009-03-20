<?include_once('../common.php');?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var department_id=<?=$_SESSION['state']['department']['id']?>;
editing='description';

function new_dept_changed(o){
    if(Dom.get("new_code").value!='' && Dom.get("new_code").value!='')
	Dom.get("add_new_dept").style.display='';
    else
	Dom.get("add_new_dept").style.display='';


}
var description_num_changed=0;
var description_warnings= new Object();
var description_errors= new Object();

function save_menu(){
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


function edit_dept_changed(o){
    var ovalue=o.getAttribute('ovalue');
    var name=o.name;
    if(ovalue!=o.value){
	if(name=='code'){
	    if(o.value==''){
		description_errors.code="<?=_("The department code can not be empty")?>";
	    }else if(o.value.lenght>16){
		description_errors.code="<?=_("The product code can not have more than 16 characters")?>";
	    }else
		delete description_errors.code;
	}
	if(name=='name'){
	    if(o.value==''){
		description_errors.name="<?=_("The department name  can not be empty")?>";
	    }else if(o.value.lenght>255){
		description_errors.name="<?=_("The product code can not have more than 255  characters")?>";
	    }else
		delete description_errors.name;
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
    save_menu();
}

function reset(tipo){

    if(tipo=='description'){
	tag='name';
	Dom.get(tag).value=Dom.get(tag).getAttribute('ovalue');
	Dom.get(tag).setAttribute('changed',0);
	tag='code';
	Dom.get(tag).value=Dom.get(tag).getAttribute('ovalue');
	Dom.get(tag).setAttribute('changed',0);

	description_num_changed=0;
	Dom.get(editing+'_save').style.display='none';
	Dom.get(editing+'_reset').style.display='none';

	Dom.get(editing+'_num_changes').innerHTML=description_num_changed;
	description_warnings= new Object();
	description_errors= new Object();
	
    }
    save_menu();
}

function save(tipo){

    if(tipo=='description'){
	var keys=new Array("code","name");
	for (x in keys)
	    {
		 key=keys[x];
		 element=Dom.get(key);
		if(element.getAttribute('changed')==1){

		    newValue=element.value;
		    oldValue=element.getAttribute('ovalue');
		    
		    var request='ar_assets.php?tipo=edit_department&key=' + key+ '&newvalue=' + 
			encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ 
			'&id='+department_id;

		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
				//alert(o.responseText);
				var r =  YAHOO.lang.JSON.parse(o.responseText);
				if(r.state==200){
				    var element=Dom.get(r.key);
				    element.getAttribute('ovalue',r.newvalue);
				    element.value=r.newvalue;
				    element.setAttribute('changed',0);
				    description_num_changed--;
				 
				}else{
				    Dom.get('description_errors').innerHTML='<span class="error">'+r.msg+'</span>';
				    
				}
				save_menu();	
			    }
			    
			});
		}
	    }
	
    }

}



function init(){
 var Event = YAHOO.util.Event;
    var Dom   = YAHOO.util.Dom;
    
    function mygetTerms(query) {multireload();};
    var oACDS = new YAHOO.widget.DS_JSFunction(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","filtercontainer0", oACDS);
    oAutoComp.minQueryLength = 0; 
    


    


}

YAHOO.util.Event.onDOMReady(init);
