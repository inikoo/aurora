<?php

include_once('common.php');

$email_content_key=$_REQUEST['email_content_key'];





$sql=sprintf("select `Email Paragraph Key`,`Paragraph Order` from `Email Content Paragraph Dimension` where `Email Content Key`=%d and `Paragraph Type`='Main' order by `Paragraph Order`",$email_content_key);
$res=mysql_query($sql);
$current_order=array();
while ($row=mysql_fetch_assoc($res)) {
    $current_order[]=$row['Email Paragraph Key'];

}
print "var paragraph_index  = new Object;var targets  = new Object;";

$sql=sprintf("select `Email Paragraph Key`,`Paragraph Order` from `Email Content Paragraph Dimension` where `Email Content Key`=%d and `Paragraph Type`='Side' order by `Paragraph Order`",$email_content_key);
$res=mysql_query($sql);
$side_current_order=array();
while ($row=mysql_fetch_assoc($res)) {
    $side_current_order[]=$row['Email Paragraph Key'];

}
print "\nvar side_paragraph_index  = new Object;var side_targets  = new Object;\n";



if (count($current_order)) {
    print "targets=[".join(',',$current_order).",0];\n";


    $target_array=$current_order;
    $target_array[]=0;

    $paragraph_index_array=array();
    foreach($current_order as $paragraph_key) {
        $_targets=array();
        $found_key=false;
        $next_index_after_found=true;
        foreach($target_array as $target_key) {
            if ($paragraph_key==$target_key) {
                $found_key=true;
                continue;

            }
            if ($found_key and $next_index_after_found) {
                $next_index_after_found=false;
                continue;
            }
            $_targets[]="'target$target_key'";
          

        }
  foreach($side_current_order as $side_key ) {
                $_targets[]="'side_target$side_key'";
            }
            $_targets[]="'side_target0'";
        $paragraph_index_array[$paragraph_key]=$_targets;
    }

    $paragraph_index='';
    foreach($paragraph_index_array as $key=>$value) {
        $paragraph_index.=",$key:[".join(',',$value)."]";
    }

    $paragraph_index=preg_replace('/^,/','',$paragraph_index);
    print "\nparagraph_index={ $paragraph_index };";

}
else{
        print "targets=[0];";
}



if (count($side_current_order)) {
    print "side_targets=[".join(',',$side_current_order).",0];";


    $side_target_array=$side_current_order;
    $side_target_array[]=0;

    $side_paragraph_index_array=array();
    foreach($side_current_order as $side_paragraph_key) {
        $side_targets=array();
        $side_found_key=false;
        $side_next_index_after_found=true;
        foreach($side_target_array as $side_target_key) {
            if ($side_paragraph_key==$side_target_key) {
                $side_found_key=true;
                continue;

            }
            if ($side_found_key and $side_next_index_after_found) {
                $side_next_index_after_found=false;
                continue;
            }
            $side_targets[]="'side_target$side_target_key'";
           
        }
 foreach($current_order as $side_key ) {
                $side_targets[]="'target$side_key'";
            }
            $side_targets[]="'target0'";

        $side_paragraph_index_array[$side_paragraph_key]=$side_targets;
    }

    $side_paragraph_index='';
    foreach($side_paragraph_index_array as $side_key=>$side_value) {
        $side_paragraph_index.=",$side_key:[".join(',',$side_value)."]";
    }
    $side_paragraph_index=preg_replace('/^,/','',$side_paragraph_index);
    print "side_paragraph_index={ $side_paragraph_index };";

}else{
        print "side_targets=[0];";
}



?>

var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var dialog_edit_paragraph;
var myEditor;

function edit_paragraph(o,paragraph_key){
Dom.get('paragraph_title').value=Dom.get('paragraph_title'+paragraph_key).innerHTML;
Dom.get('paragraph_subtitle').value=Dom.get('paragraph_subtitle'+paragraph_key).innerHTML;
Dom.get('paragraph_content').value=Dom.get('paragraph_content'+paragraph_key).innerHTML.replace(/^\s\s*/, '').replace(/\s\s*$/, '');

Dom.get('paragraph_type').value=Dom.get('paragraph_type'+paragraph_key).value;

Dom.get('paragraph_key').value=paragraph_key;
myEditor.setEditorHTML(Dom.get('paragraph_content').value);
Dom.setStyle('dialog_edit_paragraph','display','');
dialog_edit_paragraph.show();
}

function new_paragraph(type){
Dom.get('paragraph_title').value='';
Dom.get('paragraph_subtitle').value='';
Dom.get('paragraph_content').value='';
myEditor.setEditorHTML(Dom.get('paragraph_content').value);

Dom.get('paragraph_type').value=type;

Dom.get('paragraph_key').value=0;



dialog_edit_paragraph.show();

}

function close_edit_paragraph(){
dialog_edit_paragraph.hide();
}


function save_paragraph(){
 myEditor.saveHTML();

 var data_to_update=new Object;
 data_to_update={
 'paragraph_key':Dom.get('paragraph_key').value,
 'email_campaign_key':Dom.get('email_campaign_key').value,
 'email_content_key':Dom.get('email_content_key').value,
'title':Dom.get('paragraph_title').value,
'subtitle':Dom.get('paragraph_subtitle').value,
'content':Dom.get('paragraph_content').value,
'type':Dom.get('paragraph_type').value,
 }


//alert(Dom.get('paragraph_content').value)
 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));


var request='ar_edit_marketing.php?tipo=edit_email_paragraph&values='+ jsonificated_values
//alert(request)

	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//alert(o.responseText)
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		window.parent.update_objects_table();

		    location.reload();
		    
		}
	    });        
	

	


}



function delete_paragraph(paragraph_key){
data_to_update={
 'paragraph_key':paragraph_key,
 'email_campaign_key':Dom.get('email_campaign_key').value,
 'email_content_key':Dom.get('email_content_key').value,
 }

 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));

var request='ar_edit_marketing.php?tipo=delete_email_paragraph&values='+ jsonificated_values

	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
	//	alert(o.responseText)
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		     location.reload();
		    
		}
	    });        
}




function init(){



var dd  = new Object
var ddt  = new Object




for (x in paragraph_index){

   dd[x]= new YAHOO.util.DDProxy("paragraph"+x);
   dd[x].targets=paragraph_index[x];
      dd[x].side_targets=paragraph_index[x];

   dd[x].isTarget=false;
   dd[x].startDrag = function() { 
          Dom.setStyle(this.targets, 'display', ''); 
          
        Dom.setStyle(['add_paragraph','side_add_paragraph'], 'display', 'none'); 

    }
    dd[x].endDrag = function() { 
          Dom.setStyle(this.targets, 'display', 'none'); 
                  Dom.setStyle(['add_paragraph','side_add_paragraph'], 'display', ''); 

    }                            
    dd[x].onInvalidDrop = function() { 
        Dom.setStyle(this.id, 'top', ''); 
	    Dom.setStyle(this.id, 'left', ''); 
    }
     dd[x].onDragDrop = function(event,target) { 
     var data_to_update=new Object;
     
   
     
 data_to_update={
 'paragraph_key':this.id,
 'email_campaign_key':Dom.get('email_campaign_key').value,
 'email_content_key':Dom.get('email_content_key').value,
'target':target,

 }

 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));

        var request='ar_edit_marketing.php?tipo=move_email_paragraph&values='+ jsonificated_values


	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//alert(o.responseText)
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		     location.reload();
		    
		}
	    });        
    }
   
     dd[x].onDragEnter = function(a,b,c) { 
    Dom.addClass(b,'over')
    }
     dd[x].onDragOut = function(a,b,c) { 
    Dom.removeClass(b,'over')
    }
    
}



for (x in targets){
new YAHOO.util.DDTarget("target"+targets[x]);
}


for (x in side_paragraph_index){

   dd[x]= new YAHOO.util.DDProxy("paragraph"+x);
   dd[x].targets=side_paragraph_index[x];
   dd[x].isTarget=false;
   
   
   
   dd[x].startDrag = function() { 

   Dom.setStyle(this.targets, 'display', ''); 
          Dom.setStyle(['add_paragraph','side_add_paragraph'], 'display', 'none'); 

    }
    dd[x].endDrag = function() { 
          Dom.setStyle(this.targets, 'display', 'none'); 
                  Dom.setStyle(['add_paragraph','side_add_paragraph'], 'display', ''); 

    }                            
    dd[x].onInvalidDrop = function() { 
        Dom.setStyle(this.id, 'top', ''); 
	    Dom.setStyle(this.id, 'left', ''); 
    }
     dd[x].onDragDrop = function(event,target) { 
     var data_to_update=new Object;
 data_to_update={
 'paragraph_key':this.id,
 'email_campaign_key':Dom.get('email_campaign_key').value,
 'email_content_key':Dom.get('email_content_key').value,
'target':target,
 }

 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));

        var request='ar_edit_marketing.php?tipo=move_email_paragraph&values='+ jsonificated_values


	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
	//	alert(o.responseText)
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		     location.reload();
		    
		}
	    });        
    }
   
     dd[x].onDragEnter = function(a,b,c) { 
    Dom.addClass(b,'over')
    }
     dd[x].onDragOut = function(a,b,c) { 
    Dom.removeClass(b,'over')
    }
    
}



for (x in side_targets){
new YAHOO.util.DDTarget("side_target"+side_targets[x]);
}


// if side present ane










dialog_edit_paragraph = new YAHOO.widget.Dialog("dialog_edit_paragraph", {fixedcenter : true, visible : false,close:true,underlay: "none",draggable:true});
dialog_edit_paragraph.render();




  var myConfig = {
            height: '300px',
            width: '460px',
            animate: true,
            dompath: true,
            focusAtStart: true,
             toolbar: {
        titlebar: '',
            
           buttons: [
  
    { group: 'textstyle', label: 'Font Style',
        buttons: [
            { type: 'push', label: 'Bold CTRL + SHIFT + B', value: 'bold' },
            { type: 'push', label: 'Italic CTRL + SHIFT + I', value: 'italic' },
            { type: 'push', label: 'Underline CTRL + SHIFT + U', value: 'underline' },
           
        ]
    },
    { type: 'separator' },
    { group: 'indentlist', label: 'Lists',
        buttons: [
            { type: 'push', label: 'Create an Unordered List', value: 'insertunorderedlist' },
            { type: 'push', label: 'Create an Ordered List', value: 'insertorderedlist' }
        ]
    },
    { type: 'separator' },
    { group: 'insertitem', label: 'Insert Item',
        buttons: [
            { type: 'push', label: 'HTML Link CTRL + SHIFT + L', value: 'createlink', disabled: true },
            { type: 'push', label: 'Insert Image', value: 'insertimage' }
        ]
    }
]
}
        };
  myEditor = new YAHOO.widget.Editor('paragraph_content', myConfig); 
    myEditor.render(); 
    
                    Event.addListener("header_image", "click", edit_header_image);


}
YAHOO.util.Event.onDOMReady(init);
function edit_header_image(){

parent.document.getElementById('template_editor_tr').style.display = 'none'; 
parent.document.getElementById('change_template_header_image_tr').style.display = ''; 

button_edit=parent.document.getElementById('change_template_content')
Dom.removeClass(button_edit,'selected')

button_header=parent.document.getElementById('change_template_header_image')
Dom.addClass(button_header,'selected')

}