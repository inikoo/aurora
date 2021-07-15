<div id="placement_note" style="position:absolute;z-index:99;width: 250px" class="hide">
    <textarea style="width: 250px"></textarea>
</div>

<script>

    $(document).on('input propertychange', '#placement_note textarea', function (evt) {
        placement_note_changed()
    });


    function placement_note_changed() {

        const placement_note = $('#placement_note');


        let element = placement_note.data('element');
        let textarea = placement_note.find('textarea');


        $(element).closest('tr').find('.note').val($(textarea).val());

        console.log(textarea.val())

        if (textarea.val() === '') {
            $(element).closest('tr').find('.add_note').addClass('far').removeClass('fa')

        } else {
            $(element).closest('tr').find('.add_note').removeClass('far').addClass('fa')
        }

    }


    function show_placement_note(element) {

        const placement_note = $('#placement_note');

        placement_note.find('textarea').val($(element).closest('tr').find('.note').val());

        $(element).uniqueId();
        if (placement_note.hasClass('hide') || $(element).attr('id') !== placement_note.data('id')) {

            placement_note.data({
                'element': $(element), 'id': $(element).attr('id')
            });

            placement_note.removeClass('hide');

            let offset = $(element).offset();

            placement_note.offset({
                top: offset.top +22 ,
                left: offset.left
            })




            placement_note.find('textarea').trigger('focus');


        } else {
            placement_note.addClass('hide')

        }


    }

</script>
