<?php
include_once('common.php');

$_group=array();

?>
var Dom   = YAHOO.util.Dom;
var add_user_dialog_others;
var add_user_dialog;

var  group_name=new Object;

<?php

$s='';
$sql="select * from `User Group Dimension`  ";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
    $s.="group_name[".$row['User Group Key']."]='".$row['User Group Name']."';";
}
mysql_free_result($res);
print $s;

   
?>
var  store_name=new Object;
var  warehouse_name=new Object;

<?php
  // todo: only list active stores
    $s='';
$sql="select `Warehouse Key`,`Warehouse Code` from `Warehouse Dimension`  ";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
    $s.="warehouse_name[".$row['Warehouse Key']."]='".$row['Warehouse Code']."';";
}
mysql_free_result($res);
print $s;

    $s='';
$sql="select `Store Key`,`Store Code` from `Store Dimension`  ";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
    $s.="store_name[".$row['Store Key']."]='".$row['Store Code']."';";
}
mysql_free_result($res);
print $s;



?>
     var Dom   = YAHOO.util.Dom; 

var id_in_table='';
var  add_user_dialog_staff;
var  add_user_dialog_other;

var  change_staff_password;
var to_save;

    
    var active=function(el, oRecord, oColumn, oData){                                                                                                                                                  
	
	if(oData=='No')                                                                                                                                                                                 
	    el.innerHTML ='<img src="art/icons/status_offline.png" />';                                                                                                                                
	else                                                                                                                                                                                           
	    el.innerHTML = '<img src="art/icons/status_online.png" />';                                                                                                                                
            };                                                                                                                                                                                                 

var edit_active=function (callback, newValue) {                                                                                                                                                    
    
    var record = this.getRecord(),                                                                                                                                                                 
    column = this.getColumn(),                                                                                                                                                                     
    oldValue = this.value,                                                                                                                                                                         
    datatable = this.getDataTable();                                                                                                                                                               
    // for( x in record)                                                                                                                                                              
    user_id=record.getData('id');  
        staff_id=record.getData('staff_id');                                                                                                                                                                  

    var request='ar_edit_users.php?tipo=edit_staff_user&staff_id='+escape(staff_id)+'&user_id='+escape(user_id)+'&key=' + column.key + '&newvalue=' + escape(newValue) + '&oldvalue=' + escape(oldValue)                                    
//alert(request);                                                                                                                                                                              
    YAHOO.util.Connect.asyncRequest(                                                                                                                                                               
				    'POST',                                                                                                                                                        
				    request, {                                                                                                                                                     
					success:function(o) {                                                                                                                                      
					    var r = YAHOO.lang.JSON.parse(o.responseText);                                                                                                         
					    if (r.state == 200) {  
					    //alert(r.new);
					     if(r.new=='Yes'){
                                                            alert("New customer\nlogin: "+r.new_data.handle+"\npassword:"+r.new_data.password);
                                                      
                                      						datatable.updateCell(record,'password',r.new_data.td_password);
                                                      						datatable.updateCell(record,'id',r.new_data.user_id);

                                                      
                                                      }
					    
					    
                                                            callback(true, r.data);  
                                                            
                                                           
                                                            
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
                                                }                                                                                                                                                              
				    
																								   );                                                                                                                                                             
}                                                                                                                                                                                                  
  var edit_group=function (callback, newValue) {
		
		var record = this.getRecord(),
		column = this.getColumn(),
		oldValue = this.value,
		datatable = this.getDataTable();
		//		for( x in record)
		user_id=record.getData('id');
		var request='ar_edit_users.php?tipo=edit_staff_user&user_id='+escape(user_id)+'&key=' + column.key + '&newvalue=' + escape(newValue) + '&oldvalue=' + escape(oldValue)
		YAHOO.util.Connect.asyncRequest(
						'POST',
						request, {
						    success:function(o) {
							var r = YAHOO.lang.JSON.parse(o.responseText);
							if (r.state == 200) {
							    callback(true, r.data);
							   
							    
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
						}
					        
						);                                              
	    }	
    
    
    YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	    
	    //START OF THE TABLE=========================================================================================================================
	    
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var group=function(el, oRecord, oColumn, oData){
		//  var tmp = oData.split(',');
		if(oData==''){
		      el.innerHTML ='';
		      return;
		}
		var tmp=oData;
		var sgroups='';
		  for(x in tmp){
		      if(sgroups=='')
			  sgroups=group_name[tmp[x]];
		      else
			  sgroups=sgroups+', '+group_name[tmp[x]]
			      }
		el.innerHTML =sgroups;
		
	    };
	    
	       var warehouses=function(el, oRecord, oColumn, oData){
		//  var tmp = oData.split(',');
		if(oData==''){
		      el.innerHTML ='';
		      return;
		}
		var tmp=oData;
		var swarehouses='';
		  for(x in tmp){
		      if(swarehouses=='')
			  swarehouses=warehouse_name[tmp[x]];
		      else
			  swarehouses=swarehouses+', '+warehouse_name[tmp[x]]
			      }
		el.innerHTML =swarehouses;
		
	       };

 var stores=function(el, oRecord, oColumn, oData){
		//  var tmp = oData.split(',');
		if(oData==''){
		      el.innerHTML ='';
		      return;
		}
		var tmp=oData;
		
		var sstores='';
		  for(x in tmp){
		      if(sstores=='')
			  sstores=store_name[tmp[x]];
		      else
			  sstores=sstores+', '+store_name[tmp[x]]
			      }
		el.innerHTML =sstores;
		
	       };


	   
	    var ColumnDefs = [
	     {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}
	     	     ,{key:"customer_id", label:"", hidden:true,action:"none"}

			       ,{key:"password",label:"" ,width:16 }
			      ,{key:"isactive",label:"<?php echo _('State')?>" ,className:'aright',formatter:active,width:45 ,editor: new YAHOO.widget.RadioCellEditor({radioOptions:[{label:"<?php echo _('Yes')?>", value:"Yes"}, {label:"<?php echo _('No')?>", value:"No"}]
			      ,defaultValue:"0",asyncSubmitter:edit_active }) }
			      , {key:"alias", label:"<?php echo _('Login')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      ,{key:"name", label:"<?php echo _('Customer Name')?>",width:180,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
		
			      ];
			       
	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_users.php?tipo=customer_users&tableid=0");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
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
			 "id","isactive","alias","name","email","lang","groups","tipo","active","password","stores","warehouses","customer_id"
			 ]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['users']['staff']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['users']['staff']['order']?>",
									 dir: "<?php echo$_SESSION['state']['users']['staff']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.subscribe("cellClickEvent", this.table0.onEventShowCellEditor);
 this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", this.table0.onEventUnhighlightCell);

	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['users']['staff']['f_field']?>',value:'<?php echo$_SESSION['state']['users']['staff']['f_value']?>'};
	    //
	




	};
    });








   




function close_me(pre){

   switch(pre){
    case('staff'):
// 	Dom.setX('change_staff_password', -1000);
// 	Dom.setY('change_staff_password', -1000);
// 	change_staff_password.cfg.setProperty("visible", false);;
	add_user_dialog_staff.cfg.setProperty("visible", false);;
	break;
   case('supplier'):
       	add_user_dialog_supplier.cfg.setProperty("visible", false);;
	break;
   case('other'):
       	add_user_dialog_other.cfg.setProperty("visible", false);;
	break;	
 case('other2'):
       	add_user_dialog_other2.cfg.setProperty("visible", false);;
	break;	

   }

}

function randPassword(){
    var numChars = 8;
    var strChars = "23456789ABCDEFGHIJKLMN%PQRSTUVWXYZ$23456789abcdefghijkmnopqrstuvwxyz_123456789";
    var strPass = '';

    for(var i = 0; i < numChars; i++)
    {
      strPass += strChars.charAt(Math.round(Math.random()
        * strChars.length));
    }
    return strPass;
}

var display_dialog=function(tipo){

  var y=(Dom.getY("add_user"))
    var x=(Dom.getX("add_user"))
    var w=Dom.get("add_user").offsetWidth
    x=x-350+w;

    switch(tipo){
    case('staff'):
    Dom.setX('add_user_staff', x)
    Dom.setY('add_user_staff', y)
    Dom.get("staff_list").style.display='';
    Dom.get("staff_form").style.display='none';
    add_user_dialog_staff.show();
    break;
   case('supplier'):
    Dom.setX('add_user_supplier', x)
    Dom.setY('add_user_supplier', y)
    Dom.get("supplier_form").style.display='none';
    add_user_dialog_supplier.show();
    break;
   case('other'):
    Dom.setX('add_user_other', x)
    Dom.setY('add_user_other', y)
    add_user_dialog_other.show();
    break;
   case('other2'):
    Dom.setX('add_user_other2', x)
    Dom.setY('add_user_other2', y)
    add_user_dialog_other2.show();
    break;
    }
}

var change_passwd=function(o){
    var y=(Dom.getY(o))
    var x=(Dom.getX(o))
    x=x+20;
    //    alert(y);
    Dom.setX('change_staff_password', x)
    Dom.setY('change_staff_password', y)
    //    add_user_dialog_staff.cfg.setProperty("x", "500");
    //add_user_dialog_staff.cfg.setProperty("y", 500);

    var user_id=o.getAttribute('user_id');
    var user_name=o.getAttribute('user_name');
    Dom.get("change_staff_password_alias").setAttribute('user_id',user_id);
    Dom.get("change_staff_password_alias").innerHTML=user_name;
    change_staff_password.show();
    
}
function close_change_password_dialog(){

	Dom.get('change_staff_passwd1').value='';
	Dom.get('change_staff_passwd2').value='';
	change_meter('','change_staff');
	Dom.get('change_staff_password_meter_bar').style.visibility='hidden';
	Dom.get('change_staff_password_meter_bar').innerHTML='&nbsp;';
	Dom.get('change_staff_password_meter_str').innerHTML='';

	Dom.get('change_staff_save').style.visibility='hidden';
	change_staff_password.hide();

    }
var auto_pwd=function(prefix){

    Dom.get(prefix+"_user_defined_dialog").style.display='none';
    Dom.get(prefix+"_auto_dialog").style.display='';
    var pwd=randPassword();
    Dom.get(prefix+"_passwd").innerHTML= pwd;
    Dom.get(prefix+"_passwd1").value= pwd;

    Dom.get(prefix+'_save').style.visibility='visible';
    Dom.get(prefix+"_passwd").style.display='';
    Dom.get(prefix+"_user_defined_pwd_but").className='tab  but_unselected unselectable_text';
    Dom.get(prefix+"_auto_pwd_but").className='tab selected unselectable_text';

}
var user_defined_pwd=function(prefix){
    Dom.get(prefix+"_auto_dialog").style.display='none';
    Dom.get(prefix+"_user_defined_dialog").style.display='';
    Dom.get(prefix+"_save").style.visibility='hidden';
    Dom.get(prefix+"_passwd").style.display='none';
    Dom.get(prefix+"_passwd2").value='';
    Dom.get(prefix+"_passwd1").value='';
    Dom.get(prefix+'_password_meter_bar').style.visibility='hidden';
    Dom.get(prefix+'_password_meter_bar').style.width="0%";
    Dom.get(prefix+"_user_defined_pwd_but").className='tab  selected unselectable_text';
    Dom.get(prefix+"_auto_pwd_but").className='tab unselectable_text';
}
var match_passwd=function(p2,p1,tipo){
    
    p1=Dom.get(p1).value;
    if(p1==p2){
	Dom.get(tipo+"_error_passwd2").style.visibility='hidden';
    }else{
	Dom.get(tipo+"_error_passwd2").style.visibility='visible';
    }

};
var change_staff_pwd=function(){
    
    passwd=sha256_digest(Dom.get('change_staff_passwd1').value);
    user_id=Dom.get('change_staff_password_alias').getAttribute('user_id');
    var request='ar_edit_users.php?tipo=change_passwd&user_id='+escape(user_id)+'&value='+escape(passwd);
      alert(request);
    //exit;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
				alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state==200) {
		    
		    Dom.get('change_staff_passwd1').value='';
		    Dom.get('change_staff_passwd2').value='';

		    change_staff_password.cfg.setProperty("visible", false);
		    Dom.get('change_staff_password_alias').setAttribute('user_id','');
		    Dom.get('change_staff_password_alias').innerHTML='';
		    Dom.get('change_staff_save').style.visibility='hidden';
		    Dom.get('change_staff_password_meter_bar').style.visibility='hidden';
		    Dom.get('change_staff_password_meter_str').innerHTML='';

		}else
		    alert(r.msg);
	    }
	});    
}
var change_meter=function(pwd,prefix){
    

    value=testPassword(pwd);

    	if(value < 6)
	    {
		strVerdict = "No good enough"
		color='#bd0e00';
	    }else if (value > 5 && value < 15){
	    strVerdict = "very weak"
	    color='#ff7f00';
	}else if (value > 14 && value < 25)
	    {
		strVerdict = "weak"
		color='#ffe500';
	    }
	else if (value > 24 && value < 35)
	    {
		strVerdict = "still weak"
		color='#b2ff00';
	    }
	else if (value > 34 && value < 45)
	    {
		strVerdict = "strong"
		color='#00ff00';
	    }
	else
	    {
		strVerdict = "stronger"
		color="#00ff00";
	    }
	value=2*value;
    if(value<0)
	    value=0;

	if(value>100)
	    value=100;

	Dom.get(prefix+'_password_meter_bar').style.visibility='visible';
	
	Dom.get(prefix+'_password_meter_str').innerHTML=strVerdict;
	Dom.get(prefix+'_password_meter_bar').style.width=value+"%";
	Dom.get(prefix+'_password_meter_bar').style.backgroundColor=color;
	if(value>6){

	    Dom.get(prefix+"_passwd").value=pwd;
	    Dom.get(prefix+"_save").style.visibility='visible';
	    Dom.get(prefix+"_passwd2").value='';
	    Dom.get(prefix+"_error_passwd2").style.visibility='visible';
	}else{
	    Dom.get(prefix+"_save").style.visibility='hidden';
	    Dom.get(prefix+"_passwd2").value='';
	    Dom.get(prefix+"_error_passwd2").style.visibility='hidden';
	}

    };



function change_view(){


 var new_display=this.id;
     var table=tables.table0;
     var datasource=tables.dataSource0;
     if(Dom.hasClass(this,'selected')){
	    Dom.removeClass(this,'selected');
	 var request='&display=';
	 }else{
	 
	 Dom.removeClass(['active','inactive_current','inactive_ex'],'selected')
	 Dom.addClass(this,'selected');
	 var request='&display='+this.id;
	 
	 }
	 
	 
	// if(display)
	//     Dom.removeClass(display,'selected');
	 //Dom.addClass(new_display,'selected');
	 
	 //display=new_display;
	 
	 //var request='&display='+display;
     //}else{
	 //Dom.removeClass(display,'selected');
	 //var request='&display=';
	 
     //}
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       


}

  function init(){


      var ids=['active','inactive_ex','inactive_current'];
YAHOO.util.Event.addListener(ids, "click",change_view);


       change_staff_password = new YAHOO.widget.Dialog("change_staff_password", 
			{ 
			    visible : false,close:false,
			    underlay: "none",draggable:false
			    
			} );
       change_staff_password.render();
       //       change_staff_password.show();

    

       

  }

 YAHOO.util.Event.onDOMReady(init);


