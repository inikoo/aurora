<?php  include_once('common.php');
$page_id=$_REQUEST['page_id'];
print "var page_id=$page_id;\n";
 ?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var validate_scope_data=
{
'properties':{
	'title':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'title','ar':false,'dbname':'Page Title'},
	'keywords':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'keywords','ar':false,'dbname':'Page Keywords'}

}
    };
var validate_scope_metadata={
  'properties':{'type':'edit','ar_file':'ar_edit_pages.php','key_name':'id','key':page_id}  
};


function change_block(){
  
   Dom.setStyle(["d_header","d_footer","d_content","d_properties","d_style","d_media","d_setup"],'display','none');
   Dom.removeClass(["header","footer","content","properties","style","media","setup"],'selected');
   Dom.addClass(this, 'selected');
   Dom.setStyle("d_"+this.id,'display','block');

   YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=page-edit&value='+this.id ,{});

}

function init(){


    var ids = ["header","footer","content","properties","style","media","setup"]; 
    Event.addListener(ids, "click", change_block);
    


}


YAHOO.util.Event.onDOMReady(init);



