<form id="editor_container_{$editor_data.id}">
    <textarea id="editor_{$editor_data.id}" name="content"
              data-data="{$editor_data.data}">{$editor_data.content}</textarea>
</form>


<script>



    $(function () {

        var editor_data = JSON.parse(atob($('#editor_{$editor_data.id}').data('data')));


        FroalaEditor.DefineIcon('save', {  FA5NAME:'cloud save  editor_container_{$editor_data.id}_save' , template: 'font_awesome_5'});


        FroalaEditor.RegisterCommand('save', {
            title: 'Save',
            focus: true,
            undo: false,
            refreshAfterCallback: false,
            callback: function () {

                save_editor_field($('.editor_container_{$editor_data.id}_save'),editor_data, this.html.get(true))


            }
        });



        var buttons={
            'moreText': {
                'buttons': ['save','bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor', 'inlineClass', 'inlineStyle', 'clearFormatting']
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
            attribution: false,

            pastePlain: true,


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

                    $('.editor_container_{$editor_data.id}_save').addClass('valid changed')

                }
            }




        })





    });

    function save_editor_field(save_button,editor_data,value) {



        var ajaxData = new FormData();

        ajaxData.append("tipo", 'edit_field')
        ajaxData.append("object", editor_data.metadata.object)
        ajaxData.append("key", editor_data.metadata.key)
        ajaxData.append('field', editor_data.metadata.field)
        ajaxData.append('value', value)

        var request_file = '/ar_edit.php';
        $.ajax({
            url: request_file, type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


            complete: function () {

            },
            success: function (data) {

                console.log(save_button)

                save_button.removeClass('valid changed')



            }, error: function () {

            }
        });


    }


</script>