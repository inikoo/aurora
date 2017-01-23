<div id="sticky_note_container" class="{if $value==''}hide{/if}"  object="{$object}" key="{$key}"  field="{$field}"    >
    <i style="top:10px" class="fa fa-cog button fa-fw" aria-hidden="true"></i>
    <i style="top:30px" class="fa fa-trash button fa-fw" aria-hidden="true"></i>
    <div id="sticky_note" contenteditable="true" >{$value}</div>
</div>


<script>



    $('#sticky_note_button').click(function() {
        $(this).addClass('hide')
        $('#sticky_note_container').removeClass('hide')
    });

    $('#sticky_note_container').on('click', 'i.fa-trash', function() {
        $('#sticky_note_button').removeClass('hide')
        $('#sticky_note_container').addClass('hide')

        $('#sticky_note').html('')
        save_sticky_note()


    });

    var save_sticky_note_timer=false

    $('#sticky_note_container').on('blur keyup paste copy cut mouseup', '[contenteditable]', function () {


        if(save_sticky_note_timer)
            clearTimeout(save_sticky_note_timer);
        save_sticky_note_timer = setTimeout(function(){
            save_sticky_note()
        }, 400);
        
     

    })



    function save_sticky_note(){



        var object=$('#sticky_note_container').attr('object')
        var key=$('#sticky_note_container').attr('key')
        var field=$('#sticky_note_container').attr('field')
        var value=$('#sticky_note').html()

        var request = '/ar_edit.php?tipo=edit_field&object='+object+'&key='+key+'&field='+field+'&value='+value+'&metadata={}';
        console.log(request)

        var form_data = new FormData();

        form_data.append("tipo", 'edit_field')
        form_data.append("field", field)
        form_data.append("object", object)
        form_data.append("key",key )
        form_data.append("value", value)
        var request = $.ajax({

            url: "/ar_edit.php",
            data: form_data,
            processData: false,
            contentType: false,
            type: 'POST',
            dataType: 'json'

        })



        request.done(function (data) {

            if (data.state == 200) {

            } else if (data.state == 400) {
                sweetAlert(data.msg);

            }

        })


        request.fail(function (jqXHR, textStatus) {
            console.log(textStatus)

            console.log(jqXHR.responseText)


        });

    }
    
 </script>   