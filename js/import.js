var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;


function save_upload() {


  if (Dom.get('upload_import_file').value == '') {
       return;
    }

    YAHOO.util.Connect.setForm('upload_form', true, true);
    var request = 'ar_import.php?tipo=upload_file&subject=' + Dom.get('subject').value + '&parent=' + Dom.get('parent').value + "&parent_key=" + Dom.get('parent_key').value
 //   alert(request)
    var uploadHandler = {
        upload: function(o) {
     //   alert(o.responseText)
      // alert(base64_decode(o.responseText))
            var r = YAHOO.lang.JSON.parse(base64_decode(o.responseText));

            if (r.state == 200) {
              
              if(r.action=='uploaded' || r.action=='found_same_user'){
              	window.location.href = "import_review.php?id="+r.imported_records_key+'&reference=subject';
              
              }
              

            } else {
                
                //dialog_attach.show();
                Dom.get('upload_msg').innerHTML = r.msg;
            }
        },
        failure:function(o) {
					  
					    
					}
    };

    YAHOO.util.Connect.asyncRequest('POST', request, uploadHandler);

}

function check_if_file_selected() {

    if (Dom.get('upload_import_file').value == '') {
        Dom.addClass('save_upload_button', 'disabled')
    } else {
        Dom.removeClass('save_upload_button', 'disabled')
    }

}


function change_block() {
    ids = ['upload_file', 'import_history']
    block_ids = ['block_upload_file', 'block_import_history']
    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('block_' + this.id, 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');

}



function init() {
Event.addListener(['upload_file','import_history'], "click",change_block);


    Event.addListener("upload_import_file", "change", check_if_file_selected);
    Event.addListener("save_upload_button", "click", save_upload);

    init_search(Dom.get('search_type').value);

}

YAHOO.util.Event.onDOMReady(init);
