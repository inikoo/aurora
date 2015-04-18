var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;

function change_block(e) {

    Dom.setStyle(['description_block', 'images_block'], 'display', 'none');
    Dom.get(this.id + '_block').style.display = '';
    Dom.removeClass(['description', 'images'], 'selected');
    Dom.addClass(this, 'selected');
    //	 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=location-edit&value='+this.id ,{});
}


function save_page_splinter() {
EmailHTMLEditor.saveHTML();
save_edit_general('page_splinter');

}

function reset_page_splinter() {
reset_edit_general('page_splinter')

}





function recapture_preview() {
    Dom.setStyle('recapture_preview_processing', 'display', '')
    Dom.setStyle('recapture_preview', 'display', 'none')
    request = 'ar_edit_sites.php?tipo=update_preview_snapshot&parent=' + Dom.get('splinter_type').value + '&parent_key=' + Dom.get('splinter_key').value
    // alert(request)
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            Dom.setStyle('recapture_preview_processing', 'display', 'none')
            Dom.setStyle('recapture_preview', 'display', '')
            Dom.get('capture_preview_date').innerHTML = ', ' + r.formated_date

            Dom.get('splinter_preview_snapshot').src = 'image.php?id=' + r.image_key

        }
    });
}


function html_editor_changed(){
    validate_scope_data['page_splinter']['source']['changed']=true;
    validate_scope('page_splinter');
}





function init() {
    var ids = ['description', 'images'];
    YAHOO.util.Event.addListener(ids, "click", change_block);

    YAHOO.util.Event.addListener('recapture_preview', "click", recapture_preview);


    validate_scope_data = {
        'page_splinter': {
            'source': {
                'changed': false,
                'validated': true,
                'required': false,
                'group': 1,
                'type': 'item',
                'validation': [],
                'name': 'html_editor',
                'dbname': 'Source',
                'ar': false
            }
        }
    };




    validate_scope_metadata = {
        'page_splinter': {
            'type': 'edit',
            'ar_file': 'ar_edit_sites.php',
            'key_name': 'page_splinter_key',
            'key': Dom.get('splinter_key').value
        }


    };


    init_search('site');
    Event.addListener('save_edit_page_splinter', "click", save_page_splinter);
    Event.addListener('reset_edit_page_splinter', "click", reset_page_splinter);
    
    

       var myConfig = {
        height: '200px',
        width: '890px',
        animate: true,
        dompath: true,
        focusAtStart: true,
         autoHeight: true
    };

    var state = 'off';



        EmailHTMLEditor = new YAHOO.widget.Editor('html_editor', myConfig);


   EmailHTMLEditor.on('toolbarLoaded', function() {

        var codeConfig = {
            type: 'push', label: 'Edit HTML Code', value: 'editcode'
        };
        this.toolbar.addButtonToGroup(codeConfig, 'insertitem');

        this.toolbar.on('editcodeClick', function() {



            var ta = this.get('element'),iframe = this.get('iframe').get('element');

            if (state == 'on') {
                state = 'off';
                this.toolbar.set('disabled', false);
                          this.setEditorHTML(ta.value);
                if (!this.browser.ie) {
                    this._setDesignMode('on');
                }

                Dom.removeClass(iframe, 'editor-hidden');
                Dom.addClass(ta, 'editor-hidden');
                this.show();
                this._focusWindow();
            } else {
                state = 'on';

                this.cleanHTML();

                Dom.addClass(iframe, 'editor-hidden');
                Dom.removeClass(ta, 'editor-hidden');
                this.toolbar.set('disabled', true);
                this.toolbar.getButtonByValue('editcode').set('disabled', false);
                this.toolbar.selectButton('editcode');
                this.dompath.innerHTML = 'Editing HTML Code';
                this.hide();

            }
            return false;
        }, this, true);

        this.on('cleanHTML', function(ev) {
            this.get('element').value = ev.html;
        }, this, true);



        this.on('editorKeyUp', html_editor_changed, this, true);
                this.on('editorDoubleClick', html_editor_changed, this, true);
                this.on('editorMouseDown', html_editor_changed, this, true);
                this.on('buttonClick', html_editor_changed, this, true);

        this.on('afterRender', function() {
            var wrapper = this.get('editor_wrapper');
            wrapper.appendChild(this.get('element'));
            this.setStyle('width', '100%');
            this.setStyle('height', '100%');
            this.setStyle('visibility', '');
            this.setStyle('top', '');
            this.setStyle('left', '');
            this.setStyle('position', '');

            this.addClass('editor-hidden');
        }, this, true);
    }, EmailHTMLEditor, true);
    
   
    
   yuiImgUploader(EmailHTMLEditor, 'html_editor', 'ar_upload_file_from_editor.php','image');
   EmailHTMLEditor._defaultToolbar.titlebar = "";
   
   EmailHTMLEditor.on('editorContentLoaded', function() {

       // var head = this._getDoc().getElementsByTagName('head')[0];



    }, EmailHTMLEditor, true);

    EmailHTMLEditor.render();

    
    

}

YAHOO.util.Event.onDOMReady(init);
