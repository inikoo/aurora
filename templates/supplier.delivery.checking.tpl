<div id="placement_note" style="position:absolute;z-index:100" class="hide" scope="">
    <textarea></textarea>
</div>

<script>
    $('#placement_note textarea').bind('input propertychange', function () {
        placement_note_changed()
    });


    function placement_note_changed() {

        var element = $('#placement_note').data('element')
        var textarea = $('#placement_note').find('textarea')


        $(element).closest('tr').find('.note').val($(textarea).val())
        if ($(textarea).val() == '') {
            $(element).closest('tr').find('.add_note').addClass('fa-sticky-note-o').removeClass('fa-sticky-note')

        } else {
            $(element).closest('tr').find('.add_note').removeClass('fa-sticky-note-o').addClass('fa-sticky-note')
        }

    }


    function show_placement_note(element) {

        $('#placement_note').find('textarea').val($(element).closest('tr').find('.note').val())

        $(element).uniqueId()
        console.log($(element).attr('id'))
        if ($('#placement_note').hasClass('hide') || $(element).attr('id') != $('#placement_note').data('id')) {

            $('#placement_note').data(
                    {
                        'element': $(element),
                        'id': $(element).attr('id')
                    }
            )

            $('#placement_note').removeClass('hide')

            var position = $(element).closest('tr').find('.place_qty').position();

            $('#placement_note').css({
                'left': position.left,
                'top': $('#placement_note').position().top - 5
            })


            $('#placement_note').find('textarea').focus()
        } else {
            $('#placement_note').addClass('hide')

        }


    }

</script>
