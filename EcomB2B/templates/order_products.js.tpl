

<script>



    $('.reminder').click(function() {
    
        if($(this).hasClass('lock'))return;
    
        $(this).addClass('lock')
    
        var icon= $( this )

    


        if(icon.hasClass('fa-envelope-o')){
          
            icon.removeClass('fa-envelope-o').addClass('fa-envelope').addClass('marked').attr('title','{t}Click to remove notification{/t}')



            var request ='ar_reminders.php?tipo=send_reminder&pid='+ $(this).closest('.product_showcase').attr('product_id')



        }else{
          

            icon.removeClass('fa-envelope').addClass('fa-envelope-o').removeClass('marked').attr('title','{t}Click to be notified by email{/t}')
            var request='ar_reminders.php?tipo=cancel_send_reminder&esr_key='+$(this).attr('reminder_key')

        }
        
        element=$(this)
        
         console.log(request)
        $.getJSON(request, function (data) {
         console.log(data)
         
            if(data.state==200){
                element.removeClass('lock')
                element.attr('reminder_key',data.id)
                    
            }
         
         
        })
        

    });



    $('.favourite').click(function() {
        var icon= $( this ).find('i');


        if(icon.hasClass('fa-heart-o')){
            icon.removeClass('fa-heart-o').addClass('fa-heart').addClass('marked')

        }else{

            icon.removeClass('fa-heart').addClass('fa-heart-o').removeClass('marked')


        }
        
    
        var request = 'ar_basket.php?tipo=update_favorite&pid=' + $(this).closest('.product_showcase').attr('product_id') + '&customer_key=' + {$customer->id} + '&favorite_key=' + $(this).attr('favourite_key')

        console.log(request)
        $.getJSON(request, function (data) {

        })
        
        
        

    });
    
    
    $('.order_row .label').hover(
            function() {


                var input= $( this ).closest('.order_row').find('.order_input');


                console.log(input)

                if(input.val()==''){
                    input.val(1)
                }


            }, function() {
            var input= $( this ).closest('.order_row').find('.order_input');
                if(input.attr('ovalue')=='' && !input.is('[readonly]') ){
                    input.val('')
                }
            }
    );

    $('.order_row .label').click(function() {

        var element=$(this);
        var order_row=$(this).closest('.order_row');
        if($(this).find('i').hasClass('fa-spinner'))return;
     
    
    var input=order_row.find('.order_input')
      
            var order_qty=input.val()
            $(this).find('i').removeClass('fa-hand-pointer-o').addClass('fa-spinner fa-spin  ')
            input.prop('readonly', true);

            var order_key='{$order_key}';
            if(order_key=='')order_key=0;

            if(order_qty>0){
                order_row.addClass('ordered').removeClass('empty')
            }else{
             //   order_row.removeClass('ordered').addClass('empty')

            }

           

        var request = 'ar_web_basket.php?tipo=update_item&key=' + $(this).closest('.product_container').attr('product_id') + '&order_key=' + order_key+ '&qty=' + order_qty+'&webpage_key='+{$webpage->id}+'&page_section_type=Family'

        console.log(request)
        $.getJSON(request, function (data) {
        
          
            if(data.state==200){
            $('#basket_total').html(data.data.order_total)
            $('#number_items').html(data.data.ordered_products_number)
            
         
            if(data.quantity>0){
                element.html($('#ordering_settings').data('labels').ordered)
                order_row.addClass('ordered').removeClass('empty')
            }else{
                element.html($('#ordering_settings').data('labels').order)
                order_row.removeClass('ordered').addClass('empty')
            }

            if(data.quantity==0)data.quantity=''

                input.val(data.quantity).attr('ovalue',data.quantity).prop('readonly', false);
            
            }else if (data.state==201){
            
             window.location.href = 'waiting_payment_confirmation.php?referral_key=' + {$webpage->id}

            
            }
        

        })


    });


    $(".order_input").on('input propertychange', function(){


        $(this).val( $(this).val().replace(/[^\d]/g,'') )

        var order_qty=$(this).val()
        var order_row=$( this ).closest('.order_row')

        var button=order_row.find('.label');

        console.log(button)

        if(order_qty!=$(this).attr('ovalue')){


            button.html( $('#ordering_settings').data('labels').update)
            order_row.addClass('ordered').removeClass('empty')
        }else{

            if(order_qty>0){
                button.html($('#ordering_settings').data('labels').ordered)
                order_row.addClass('ordered').removeClass('empty')

            }else{
                button.html($('#ordering_settings').data('labels').order)
                order_row.removeClass('ordered').addClass('empty')


            }

        }

    });



    $( ".label_when_log_out" ).each(function( index ) {


        var len_fit = 10;
        var un = $(this)


        var len_user_name = un.html().length;
        if(len_fit < len_user_name ) {

            var size_now = parseInt(un.css("font-size"));
            var size_new = size_now * len_fit / len_user_name;
            un.css("font-size", size_new);

        }

    });

    $( ".order_button_text" ).each(function( index ) {


        var len_fit = 9;
        var un = $(this)


        var len_user_name = un.html().length;
        if(len_fit < len_user_name ) {

            var size_now = parseInt(un.css("font-size"));
            var size_new = size_now * len_fit / len_user_name;
          console.log(size_now)
            console.log(size_new)

             un.css("font-size", size_new);

        }

    });


    $( ".item_name" ).each(function( index ) {


        var len_fit = 50; // According to your question, 10 letters can fit in.
        var un = $(this)

        // Get the lenght of user name.
        var len_user_name = un.html().length;
        if(len_fit < len_user_name ) {

            // Calculate the new font size.
            var size_now = parseInt(un.css("font-size"));
            var size_new = size_now * len_fit / len_user_name;

            // Set the new font size to the user name.
            un.css("font-size", size_new);

        }

    });


</script>    