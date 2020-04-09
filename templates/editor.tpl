<form id="editor_container_{$editor_data.id}">
    <textarea id="editor_{$editor_data.id}" name="content"
              data-data="{$editor_data.data}">{$editor_data.content}</textarea>
</form>


<script>



    $(function () {
        var editor_data = JSON.parse(atob($('#editor_{$editor_data.id}').data('data')));


        var buttons={
            'moreText': {
                'buttons': ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor', 'inlineClass', 'inlineStyle', 'clearFormatting']
            },
            'moreParagraph': {
                'buttons': ['alignLeft', 'alignCenter', 'formatOLSimple', 'alignRight', 'alignJustify', 'formatOL', 'formatUL', 'paragraphFormat', 'paragraphStyle', 'lineHeight', 'outdent', 'indent', 'quote']
            },
            'moreRich': {
                'buttons': ['insertLink', 'insertImage', 'insertVideo', 'insertTable', 'emoticons', 'fontAwesome', 'specialCharacters', 'embedly', 'insertFile', 'insertHR']
            },
            'moreMisc': {
                'buttons': ['undo', 'redo', 'fullscreen', 'print', 'getPDF', 'spellChecker', 'selectAll', 'html', 'help'],
                'align': 'right',
                'buttonsVisible': 2
            }
        }



        new FroalaEditor('#editor_{$editor_data.id}', {
            key: '{$smarty.const.FROALA_EDITOR_KEY}',
            saveParam: 'value',
            saveURL: '/ar_edit.php',
            saveMethod: 'GET',
            pastePlain: true,
            saveInterval: 36000000,
            saveParams: editor_data.metadata,

            pluginsEnabled: editor_data.plugins,
            toolbarInline: false,

            charCounterCount: false,
            toolbarButtons: buttons,
            toolbarButtonsMD: buttons,
            toolbarButtonsSM: buttons,
            toolbarButtonsXS: buttons,
            defaultImageDisplay: 'inline',
            fontSize: ['8', '10', '12', '14','16', '18', '30', '60', '96'],
            zIndex: 1000,
            pastePlain: true,




            events: {
                'contentChanged': function () {
                    $('#save_button', window.parent.document).addClass('save button changed valid')
                },
                'save.after': function () {
                    $('#editor_container_{$editor_data.id} div.fr-toolbar i.fa-cloud').removeClass('valid save changed')
                    $('#editor_container_{$editor_data.id} div.fr-toolbar i.fa-cloud').closest('div').removeClass('changed')

                    data = JSON.parse(data);

                    console.log(data);
                    if (editor_data.mode == 'edit_object') {

                        var field = editor_data.field;
                        $('#' + field + '_msg').html(data.msg).addClass('success').removeClass('hide');

                        $('.' + field).html(data.formatted_value);


                        if (data.directory_field != '') {
                            $('#' + data.directory_field + '_directory').html(data.directory);
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
                }
            }
        })





    });

</script>