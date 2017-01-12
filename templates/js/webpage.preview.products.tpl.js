 
    var save_panel_image_caption_timer=false;
    var save_panel_image_link_timer=false;

    function product_allowDrop(ev) {
        ev.preventDefault();
    }

    function product_drag(ev) {

       // console.log(ev.target.id)

        ev.dataTransfer.setData("id", ev.target.id);

        ev.dataTransfer.setData("stack_index", ev.target.getAttribute('stack_index'));
        ev.dataTransfer.setData("product_id", ev.target.getAttribute('product_id'));

        ev.dataTransfer.setData("product_code", ev.target.getAttribute('product_code'));


        ev.dataTransfer.setData("html",  ev.target.innerHTML );



    }

    function product_drop(ev) {



        ev.preventDefault();
        var product_showcase=  $( ev.target).closest('div.product_wrap').find('.product_showcase')
        var target_stack_index=product_showcase.attr('stack_index')



        console.log(target_stack_index)

        if(target_stack_index==ev.dataTransfer.getData("stack_index")){
            return;
        }else{





            save_stack_index(ev.dataTransfer.getData("product_id"),product_showcase.attr('product_id'))

        }



    }

    function change_next(pivot_id,html,product_code,product_id,element){

        if(element.attr('id')==undefined   ){
            return;
        }

        var tmp_html = element.html();
        var tmp_product_code = element.attr('product_code');
        var tmp_product_id = element.attr('product_id');

        element.html(html)
        element.attr('product_code',product_code)
        element.attr('product_id',product_id)

        if( element.attr('stack_index')==pivot_id  ){
            return;
        }
        change_next(pivot_id,tmp_html,tmp_product_code,tmp_product_id,element.closest('.product_wrap').next().find('.product_showcase'))


    }

    function  save_stack_index(item_key,target_key) {



        var request = '/ar_edit_website.php?tipo=update_webpage_items_order&item_key=' + item_key+ '&target_key=' +target_key +'&webpage_key='+ $('#webpage_preview').attr('webpage_key')+'&target_section_key=0'
        console.log(request)

        $.getJSON(request, function (data) {

            if(data.state==200){

                $('#products_helper').html(data.products)



                $('#add_panel').removeClass('active')
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
    
    
    
    $('.product_header_text').dblclick(function() {





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

         saveParams: {
             webpage_key: $('#webpage_preview').attr('webpage_key'),
             key: $(this).closest('.product_showcase').attr('index_key'),
             tipo: ($(this).hasClass('related_product')?'update_webpage_related_product': 'update_product_category_index'),
             type: 'header_text',



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

    })


