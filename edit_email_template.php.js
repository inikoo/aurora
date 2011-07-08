
  var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var dialog_edit_paragraph;


function edit_paragraph(o,paragraph_key){

 var pos = YAHOO.util.Dom.getXY(o);
Dom.setXY('dialog_edit_paragraph', pos);
Dom.get('paragraph_title').value=Dom.get('paragraph_title'+paragraph_key).innerHTML;
Dom.get('paragraph_subtitle').value=Dom.get('paragraph_subtitle'+paragraph_key).innerHTML;
Dom.get('paragraph_content').value=Dom.get('paragraph_content'+paragraph_key).innerHTML;
Dom.get('paragraph_key').value=paragraph_key;


dialog_edit_paragraph.show();

}

function save_paragraph(){
 

 var data_to_update=new Object;
 data_to_update={
 'paragraph_key':Dom.get('paragraph_key').value,
 'email_campaign_key':Dom.get('email_campaign_key').value,
'title':Dom.get('paragraph_title').value,
'subtitle':Dom.get('paragraph_subtitle').value,
'content':Dom.get('paragraph_content').value,
 }

 jsonificated_values=my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));


var request='ar_edit_marketing.php?tipo=edit_email_paragraph&values='+ jsonificated_values
alert(request);return;

	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
	//	alert(o.responseText)
		    var ra =  YAHOO.lang.JSON.parse(o.responseText);
		      for (x in ra){
               r=ra[x]
		    
		    if (r.state==200) {
		    
		    Dom.get('sticky_note_content').innerHTML=r.newvalue;
			
			close_dialog(r.key);

            if(r.newvalue==''){
                Dom.setStyle(['sticky_note_div','sticky_note_bis_tr'],'display','none');
                Dom.setStyle('new_sticky_note_tr','display','');

            }else{
                           

             Dom.setStyle(['sticky_note_div','sticky_note_bis_tr'],'display','');
                Dom.setStyle('new_sticky_note_tr','display','none');
            }

var table=tables['table0'];
			var datasource=tables['dataSource0'];
			var request='';
			datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    
			
		    }else
			Dom.get(tipo+'_msg').innerHTML=r.msg;
		}
		}
	    });        
	

	


}

function init(){


dialog_edit_paragraph = new YAHOO.widget.Dialog("dialog_edit_paragraph", {visible : false,close:true,underlay: "none",draggable:false});
dialog_edit_paragraph.render();



}
YAHOO.util.Event.onDOMReady(init);
