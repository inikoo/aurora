<?php

include_once('common.php');

$email_content_key=$_REQUEST['email_content_key'];
   $sql=sprintf("select `Email Paragraph Key`,`Paragraph Order` from `Email Content Paragraph Dimension` where `Email Content Key`=%d order by `Paragraph Order`",$email_content_key);
        $res=mysql_query($sql);
        $current_order=array();
        while ($row=mysql_fetch_assoc($res)) {
         $current_order[]=$row['Email Paragraph Key'];
        
        }
print "var paragraph_index  = new Object;var targets  = new Object;";

if(count($current_order)){
print "targets=[".join(',',$current_order).",0];";


$target_array=$current_order;
$target_array[]=0;

$paragraph_index_array=array();
foreach($current_order as $paragraph_key){
$_targets=array();
   $found_key=false;
   $next_index_after_found=true;
   foreach($target_array as $target_key){
        if($paragraph_key==$target_key){
            $found_key=true;
           continue;
           
        }
        if($found_key and $next_index_after_found){
              $next_index_after_found=false;
              continue;
        }
        $_targets[]="'target$target_key'";
   
   }
   
   $paragraph_index_array[$paragraph_key]=$_targets;
}

$paragraph_index='';
foreach($paragraph_index_array as $key=>$value){
    $paragraph_index.=",$key:[".join(',',$value)."]";
}
$paragraph_index=preg_replace('/^,/','',$paragraph_index);
print "paragraph_index={ $paragraph_index };";

}
?>



// paragraph_index={8:['target0','target10'],9:['target8','target0'],10:['target9','target8']};
//targets=[0,8,9,10]





  var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var dialog_edit_paragraph;
var myEditor;

function edit_paragraph(o,paragraph_key){
Dom.get('paragraph_title').value=Dom.get('paragraph_title'+paragraph_key).innerHTML;
Dom.get('paragraph_subtitle').value=Dom.get('paragraph_subtitle'+paragraph_key).innerHTML;
Dom.get('paragraph_content').value=Dom.get('paragraph_content'+paragraph_key).innerHTML.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
Dom.get('paragraph_key').value=paragraph_key;
myEditor.setEditorHTML(Dom.get('paragraph_content').value);

dialog_edit_paragraph.show();
}

function new_paragraph(){
Dom.get('paragraph_title').value='';
Dom.get('paragraph_subtitle').value='';
Dom.get('paragraph_content').value='';
Dom.get('paragraph_key').value=0;
myEditor.setEditorHTML(Dom.get('paragraph_content').value);

dialog_edit_paragraph.show();

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
 }

 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));


var request='ar_edit_marketing.php?tipo=edit_email_paragraph&values='+ jsonificated_values


	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//alert(o.responseText)
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
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
   dd[x].isTarget=false;
   dd[x].startDrag = function() { 
          Dom.setStyle(this.targets, 'display', ''); 
          
        Dom.setStyle('add_paragraph', 'display', 'none'); 

    }
    dd[x].endDrag = function() { 
          Dom.setStyle(this.targets, 'display', 'none'); 
                  Dom.setStyle('add_paragraph', 'display', ''); 

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
'target':target
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



for (x in targets){
new YAHOO.util.DDTarget("target"+targets[x]);
}



  
    /*
   
    dd.x.onInvalidDrop = function() { 
        Dom.setStyle('paragraph8', 'top', ''); 
	    Dom.setStyle('paragraph8', 'left', ''); 
    }

    dd.x.onDragDrop = function() { 
        Dom.setStyle('paragraph8', 'display', 'none'); 
        window.location.reload()
    }
    */
//}

/*


//var dd2 = new YAHOO.util.DD("paragraph9","group1");

 
*/
dialog_edit_paragraph = new YAHOO.widget.Dialog("dialog_edit_paragraph", {fixedcenter : true, visible : false,close:true,underlay: "none",draggable:true});
dialog_edit_paragraph.render();
  var myConfig = {
            height: '400px',
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

}
YAHOO.util.Event.onDOMReady(init);
