 <form  id="editor_container_{$editor_data.id}">
    <textarea id="editor_{$editor_data.id}" name="content" data-data="{$editor_data.data}">{$editor_data.content}</textarea>
</form>



<script>
 
$.FroalaEditor.DefineIcon('save', { NAME: 'cloud'});
 
$(function() {
   var editor_data=JSON.parse(atob($('#editor_{$editor_data.id}').data('data')))
    
    $('#editor_{$editor_data.id}').froalaEditor(
        {
            saveParam: 'value',
            saveURL: '/ar_edit.php',
            saveMethod: 'GET',
            saveParams: editor_data.metadata,
            pluginsEnabled: editor_data.plugins,
            toolbarInline: false,
            toolbarButtons:['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', '|', 'color', 'emoticons', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', 'insertHR', '|', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', 'undo', 'redo', 'clearFormatting', 'selectAll', 'html','|','save'],
            toolbarButtonsMD:['fullscreen', 'bold', 'italic', 'underline', 'fontFamily', 'fontSize', 'color', 'paragraphStyle', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', 'insertHR', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', 'undo', 'redo', 'clearFormatting','|','save'],
            toolbarButtonsSM:['fullscreen', 'bold', 'italic', 'underline', 'fontFamily', 'fontSize', 'insertLink', 'insertImage', 'insertTable', 'undo', 'redo','|','save'],
            toolbarButtonsXS:['bold', 'italic', 'fontFamily', 'fontSize', 'undo', 'redo','|','save']
        }
    )
    .on('froalaEditor.contentChanged', function (e, editor) {
        $('#editor_container_{$editor_data.id}  div.fr-toolbar i.fa-cloud').addClass('valid save changed')
        $('#editor_container_{$editor_data.id} div.fr-toolbar i.fa-cloud').closest('div').addClass('changed')
    })
    .on('froalaEditor.save.after', function (e, editor, data) {
        $('#editor_container_{$editor_data.id} div.fr-toolbar i.fa-cloud').removeClass('valid save changed')
        $('#editor_container_{$editor_data.id} div.fr-toolbar i.fa-cloud').closest('div').removeClass('changed')
       
       data=JSON.parse(data)
       
       console.log(editor_data)
        if(editor_data.mode=='edit_object'){
            
            var field=editor_data.field
             $('#' + field + '_msg').html(data.msg).addClass('success').removeClass('hide')
           


            console.log(field)
            $('.' + field).html(data.formatted_value)
            
           

          

            if (data.directory_field != '') {
                $('#' + data.directory_field + '_directory').html(data.directory)
                if (data.items_in_directory == 0) {
                    $('#' + data.directory_field + '_field').addClass('hide')
                } else {
                    $('#' + data.directory_field + '_field').removeClass('hide')
                }
            }
            if (data.action == 'new_field') {
                if (data.new_fields) {
                    for (var key in data.new_fields) {
                        create_new_field(data.new_fields[key])
                    }
                }
            }


            close_edit_field(field)

            if (data.other_fields) {
                for (var key in data.other_fields) {
                    update_field(data.other_fields[key])
                }
            }

            if (data.deleted_fields) {
                for (var key in data.deleted_fields) {
                    delete_field(data.deleted_fields[key])
                }
            }

            for (var key in data.update_metadata.class_html) {
                $('.' + key).html(data.update_metadata.class_html[key])
            }

            post_save_actions(field, data)
            
        }
        

    })
});

</script>