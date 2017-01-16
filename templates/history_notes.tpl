<div id="edit_history_note_dialog" class="hide textarea_dialog" object="{$state['object']}" key="{$state['key']}"
     history_key="">
    <div class="note_type">
        <div class="label">{t}Permanent{/t} <i onClick="note_type()" id="note_type" class="fa fa-check-square-o fw"></i>
        </div>
    </div>

    <textarea id="history_note_value"></textarea><br>
    <i id="history_note_close_button" class="fa fa-sign-out fa-flip-horizontal fw "
       onclick="close_history_note_dialog()"></i>
    <i id="history_note_save_button" class="fa fa-cloud save fw" onclick="save_history_note()"></i>
</div>
<script>
    function note_type() {
        if ($('#note_type').hasClass('fa-check-square-o')) {
            $('#note_type').removeClass('fa-check-square-o').addClass('fa-square-o')
        } else {
            $('#note_type').addClass('fa-check-square-o').removeClass('fa-square-o')
        }
    }

    function show_history_note_edit_dialog(anchor) {

        if ($('#edit_history_note_dialog').hasClass('hide')) {


            $('#edit_history_note_dialog').removeClass('hide')
            $('#history_note_value').focus()

            if (anchor == 'show_history_note_dialog') {

                $('#note_type').addClass('fa-check-square-o').removeClass('fa-square-o')


                var position = $('#' + anchor).position();


                $('#edit_history_note_dialog').css({
                    'left': position.left - $('#edit_history_note_dialog').width() - $('#' + anchor).width(),
                    'top': position.top
                })
                $('#edit_history_note_dialog').attr('history_key', '')

            } else {

                var position = anchor.closest('td.html-cell').position();
                $('#edit_history_note_dialog').css({
                    'left': position.left,
                    'top': position.top
                })

                $('#history_note_value').val($('#history_note_' + anchor.attr('history_key')).text())
                $('#edit_history_note_dialog').attr('history_key', anchor.attr('history_key'))
            }


        } else {
            close_history_note_dialog()
        }


    }


    function close_history_note_dialog() {
        $('#edit_history_note_dialog').addClass('hide')
    }

    function save_history_note() {

        var history_key = $('#edit_history_note_dialog').attr('history_key')

        var value = $('#history_note_value').val()
        var object = $('#edit_history_note_dialog').attr('object')
        var key = $('#edit_history_note_dialog').attr('key')


        if (history_key == '' && value == '')return;

        var field = 'History Note'

        if (history_key) {
            field = field + ' ' + history_key

        }
        var request = '/ar_edit.php?tipo=edit_field&object=' + object + '&key=' + key + '&field=' + field + '&value=' + fixedEncodeURIComponent(value)
        if (history_key == '') {
            request += '&metadata=' + JSON.stringify({ 'deletable': ($('#note_type').hasClass('fa-check-square-o') ? 'No' : 'Yes')})
        }


        $.getJSON(request, function (data) {


            if (data.state == 200) {
                console.log(data)

                $('#history_note_' + history_key).html(data.formatted_value)
                $('#history_note_msg_' + history_key).html(data.msg)
                if (data.action == 'deleted') {
                    $('#history_note_edit_button_' + history_key).addClass('hide')


                }

                if (history_key == '') {
                    rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters
                    rows.fetch({
                        reset: true
                    });

                }


                close_history_note_dialog()
            } else if (data.state == 400) {


            }
        })

    }

    function show_history_details(key) {

        button = $('#history_details_button_' + key)

        if (button.hasClass('fa-flip-vertical')) {
            button.removeClass('fa-flip-vertical')
        } else {
            button.addClass('fa-flip-vertical')
        }

        if ($('#history_details_' + key).hasClass('hide')) {
            $('#history_details_' + key).removeClass('hide')
        } else {
            $('#history_details_' + key).addClass('hide')

        }

    }

    function strikethrough_note(value, o) {
        var history_key = $(o).attr('history_key')

        var object = $('#edit_history_note_dialog').attr('object')
        var key = $('#edit_history_note_dialog').attr('key')


        var field = 'History Note Strikethrough ' + history_key


        var request = '/ar_edit.php?tipo=edit_field&object=' + object + '&key=' + key + '&field=' + field + '&value=' + fixedEncodeURIComponent(value)


        $.getJSON(request, function (data) {


            if (data.state == 200) {
                console.log(data)

                $('#history_note_' + history_key).html(data.formatted_value)
                $('#history_note_msg_' + history_key).html(data.msg)
                if (value == 'Yes') {
                    $('#history_note_' + history_key).addClass('strikethrough')
                    $('#strikethrough_button_' + history_key).addClass('hide')
                    $('#undo_strikethrough_button_' + history_key).removeClass('hide')

                } else {

                    $('#history_note_' + history_key).removeClass('strikethrough')
                    $('#strikethrough_button_' + history_key).removeClass('hide')
                    $('#undo_strikethrough_button_' + history_key).addClass('hide')

                }


            } else if (data.state == 400) {


            }
        })

    }

    $("#show_history_note_dialog").on("click", function (evt) {
        show_history_note_edit_dialog('show_history_note_dialog')
    });

    $("#app_main").on("click", ".note_buttons", function (evt) {
        show_history_note_edit_dialog($(this))
    });

    $("#app_main").on("click", ".strikethrough_button.fa-strikethrough", function (evt) {
        strikethrough_note('Yes', $(this))
    });
    $("#app_main").on("click", ".strikethrough_button.fa-undo", function (evt) {
        strikethrough_note('No', $(this))
    });


</script>