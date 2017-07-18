

var save_webpage_product_description_timer=false


function save_webpage_product_description(){

    var css='';

    webpage_product_description={}


    css+='#product_description_block{ ' + 'height:'+$('#product_description_block').height()+'px;width:'+$('#product_description_block').width()+'px }'






    var request = '/ar_edit_website.php?tipo=edit_webpage&key=' + {$webpage->id} + '&field=css&value=' + Base64.encode(css)
    // console.log(request)
    $.getJSON(request, function (data) {
        console.log(data)
        if(data.state==200){



            if($('#publish').find('i').hasClass('fa-rocket')) {

                if (data.publish) {
                    $('#publish').addClass('changed valid')
                } else {
                    $('#publish').removeClass('changed valid')
                }
            }

        }

    })


}



$('.product_description_block')

    .resizable(
        {
            stop: function (event, ui) {


                if(save_webpage_product_description_timer)
                    clearTimeout(save_webpage_product_description_timer);
                save_webpage_product_description_timer = setTimeout(function(){ save_webpage_product_description(); }, 750);

            }

        }
    );



$('#page_content').on( "dblclick", ".product_description_block", function() {



    if(! $('#text_edit_toolbar').hasClass('hide')){
        console.log('cacxxxa')
        return
    }

    var position=$(this).position();


    $(this).resizable('destroy').addClass('editing')

    console.log($(this).position())

    $('#text_edit_toolbar').removeClass('hide').css({
        position: 'absolute',
        left:position.left - 25 + "px",
        top: position.top + 25 + "px"
    }).attr('block',$(this).attr('id'))





    $(this).froalaEditor({


        toolbarInline: true,
        charCounterCount: false,
        toolbarButtons: ['bold', 'italic', 'underline', 'strikeThrough', 'color', 'emoticons','insertLink', 'insertImage','insertVideo', '-', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'indent', 'outdent', '-', 'undo', 'redo'],
        toolbarButtonsMD: ['bold', 'italic', 'underline', 'strikeThrough', 'color', 'emoticons','insertLink', 'insertImage','insertVideo', '-', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'indent', 'outdent', '-', 'undo', 'redo'],
        toolbarButtonsSM: ['bold', 'italic', 'underline', 'strikeThrough', 'color', 'emoticons','insertLink', 'insertImage','insertVideo', '-', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'indent', 'outdent', '-', 'undo', 'redo'],
        toolbarButtonsXS: ['bold', 'italic', 'underline', 'strikeThrough', 'color', 'emoticons','insertLink', 'insertImage','insertVideo', '-', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'indent', 'outdent', '-', 'undo', 'redo'],
        defaultImageDisplay: 'inline',

        saveInterval: 500,
        saveParam: 'value',

        saveURL: '/ar_edit_website.php',

        saveMethod: 'POST',
        zIndex: 1000,
        saveParams: {
            tipo: 'webpage_content_data',
            parent: 'page',
            parent_key:  {$webpage->id},
            section: 'product_description',
            block: $(this).attr('id'),
            type: 'text',
            zzz: 'zzz'

        }


    }).on('froalaEditor.save.after', function (e, editor, response) {


        var data=jQuery.parseJSON(response)

        if(data.state==200){



            if($('#publish').find('i').hasClass('fa-rocket')) {

                if (data.publish) {
                    $('#publish').addClass('changed valid')
                } else {
                    $('#publish').removeClass('changed valid')
                }
            }

        }
    })



    $('#text_edit_toolbar .fa-window-close').click(function() {

        $('#text_edit_toolbar').addClass('hide')

        var block= $('#'+$('#text_edit_toolbar').attr('block'))

        block.froalaEditor('destroy')
        block.addClass('fr-view')


block.removeClass('editing')

        block.resizable(
            {
                stop: function (event, ui) {
                    if(save_webpage_product_description_timer)
                        clearTimeout(save_webpage_product_description_timer);
                    save_webpage_product_description_timer = setTimeout(function(){ save_webpage_product_description(); }, 750);

                }

            }

        );

        //



    });








});

