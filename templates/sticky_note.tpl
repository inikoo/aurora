<div id="showcase_sticky_note" class="data_container {if $_object->get('Sticky Note')==''}hide{/if} ">
			<div class="sticky_note_button">
				<i class="fa fa-sticky-note button" onClick="show_sticky_note_edit_dialog()"></i> 
			</div>
			<div  class="sticky_note" ondblClick="show_sticky_note_edit_dialog()"> 
				{$_object->get('Sticky Note')} 
			</div>
		</div>

<div id="edit_sticky_note_dialog"  class="hide textarea_dialog" object="{$object}" key="{$key}" field="{$sticky_note_field}">
<textarea id="sticky_note_value">{$_object->get('Sticky Note')}</textarea><br>


<i id="sticky_note_close_button" class="fa fa-sign-out fa-flip-horizontal fw " onclick="close_sticky_note_dialog()"></i> 
 
<i id="sticky_note_save_button" class="fa fa-cloud save fw" onclick="save_sticky_note()"></i> 
</div>
<script>

function show_sticky_note_edit_dialog(anchor) {
    console.log('x')
    if ($('#edit_sticky_note_dialog').hasClass('hide')) {

        $('#edit_sticky_note_dialog').removeClass('hide')
        $('#sticky_note_value').focus()

        if (anchor == 'sticky_note_button') {
            var position = $('#'+anchor).position();
               $('#edit_sticky_note_dialog').css({
            'left': position.left- $('#edit_sticky_note_dialog').width(),
            'top': position.top+$('#'+anchor).height()
        })
        } else {
            var position = $('#showcase_sticky_note .sticky_note').position();
               $('#edit_sticky_note_dialog').css({
            'left': position.left,
            'top': position.top
        })
        }

     
    } else {
        close_sticky_note_dialog()
    }


}


function close_sticky_note_dialog() {
    $('#edit_sticky_note_dialog').addClass('hide')
}

function save_sticky_note() {


    var value = $('#sticky_note_value').val()
    var object = $('#edit_sticky_note_dialog').attr('object')
    var key = $('#edit_sticky_note_dialog').attr('key')
    var field = $('#edit_sticky_note_dialog').attr('field')

    var request = '/ar_edit.php?tipo=edit_field&object=' + object + '&key=' + key + '&field=' + field + '&value=' + fixedEncodeURIComponent(value)

    $.getJSON(request, function(data) {


        if (data.state == 200) {
            console.log(data)
            $('#sticky_note_value').val(data.value)
            $('#showcase_sticky_note .sticky_note').html(data.formatted_value)
            if (data.value == '') {
                $('#showcase_sticky_note').addClass('hide')
                $('#sticky_note_button').removeClass('hide')
           } else {
                $('#showcase_sticky_note').removeClass('hide')
                $('#sticky_note_button').addClass('hide')

            }

            close_sticky_note_dialog()
        } else if (data.state == 400) {


        }
    })

}

$("#navigation").on("sticky_note_button click", function(evt) {
    show_sticky_note_edit_dialog('sticky_note_button')
});



</script>