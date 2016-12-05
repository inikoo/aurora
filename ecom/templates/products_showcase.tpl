{assign 'see_also'  $category->webpage->get_see_also() }
{assign 'content_data' $category->webpage->get('Content Data')}

 <style>


input {
  position: relative;
  bottom: 2px;

  padding: 4px 6px;
  font-weight: normal;
  color: #555;
  vertical-align: middle;
  background-color: #fff;
  border: 1px solid #ccc;
  margin: 0px;
  font-family: inherit;
  font-size: 100%;
  line-height: normal;
  outline: none;
  -webkit-box-sizing: content-box;
  -moz-box-sizing: content-box;
  box-sizing: content-box;
  -webkit-appearance: none;
  }


    #page_content{
        padding:20px;
        border:1px solid #d0d0d0;
        border-top:none;
        width:992px;
        margin:auto;

        font-family: "Ubuntu",Helvetica,Arial,sans-serif;
        color:red

    }




    #description_block{
        position:relative; width:935px;margin:auto;
        padding:0px;margin-top:20px;

    }

    .webpage_content_header{
        position:relative;float:left}

    #webpage_content_header_image{
        width:250px;left:20px
    }

    #webpage_content_header_text{
        left:100px; width:450px

    }


    #products{
        width:970px;margin:auto;
        margin-top:20px
    }

    #products    .block {
        border: 1px solid #ccc;
        background:#fff;
        padding:0px 0px 0px 0px;

    }

    #products .block:hover{
        border:1px solid #A3C5CC;
    }

    .block.four{
        float:left;width:218px;margin-left:18px;
    }


    .block.product_showcase{
        height:317px}


    .wrap_to_center {

        display: table-cell;
        text-align: center;
        vertical-align: middle;
        width: 218px;
        height: 160px;
        margin-bottom:10px
    }
    .wrap_to_center img {
        vertical-align: bottom;max-width:218px;max-height:160px
    }






    .product_description{
        padding-left:10px;padding-right:10px;display:block;height:51px;
    }

    .product_prices{
        padding-left:10px;padding-right:10px;display:block;height:37px;
    }


    .product_prices.log_out{
        text-align: center;

    }

    .product_prices .product_price{
        color:#236E4B
    }


    .more_info{
        cursor:pointer;position:absolute;width:40px;top:-1px;left:179px}





    .description_block{
        margin-bottom:20px;background:#fff;padding:10px 20px;border:1px solid #eee}




    .ordering.log_out div {
        float:left;width: 50%;background-color: darkorange;color:whitesmoke;text-align: center;
        }

    .ordering.log_out span {
        height:28px;
        padding:7px 20px 5px 10px;
        display:block;height:20px;cursor:pointer;
        font-weight: 800;
    }

    .ordering.log_out span.login_button{
        border-right:1px solid white;
    }


    .ordering.log_out span:hover {
        background-color: brown;
    }


    .ordering{
    }




    .order_input{
        float:left;position:relative;top:2px;border-right:none;border-left:none;height:20px;width:40px
    }



    .product_footer{
        height:28px;position:relative;top:2px;
       padding:7px 20px 3px 10px;
        display:block;height:20px;cursor:pointer;
        float:left;font-weight: 800;
    }


    .can_not_order .product_footer{
        color:#fff;
        background-color: darkgray;cursor:auto;

    }

    .can_not_order.out_of_stock .product_footer{
        color:#fff;
        background-color: darkgray;

    }

    .can_not_order.launching_soon .product_footer{
        color:#fff;
        background-color: darkseagreen;

    }


    .can_not_order .product_footer.label{
        width:154px;
    }

    .can_not_order .product_footer.reminder{
        padding:6px 10px 2px 10px;
        background-color: inherit;
        color: darkgray;border-top:1px solid darkgray;cursor:pointer;
    }
    
     .can_not_order .product_footer.reminder.lock{
       background-color: #f7f7f7
     }

    .can_not_order.launching_soon .product_footer.reminder{
        border-top:1px solid darkseagreen;
        color: darkseagreen;
    }




    .product_footer.favourite{
        padding:6px 10px 2px 10px;
        background-color: inherit;
        color: darkgray;border-top:1px solid #ccc
    }

    .product_footer.favourite i.marked{

        color: deeppink;
    }

    .product_footer.order_button{
        width:102px;
        color:#fff;
        background-color: orange;
    }

    .product_footer.order_button:hover{
        color:#fff;
        background-color: maroon;
    }

    .product_footer.order_button.ordered{
        color:#fff;
        background-color: maroon;
    }


    .product_image{
        cursor:pointer}







    #bottom_see_also{
        margin:auto;padding:0px;margin-top:10px;width:935px
    }

    #bottom_see_also .title{
        font-weight:800;font-size:120%;padding-bottom:10px;
    }

    #bottom_see_also .item{
        height:220px;width:170px;float:left;text-align:center;margin-left:20px

    }
    #bottom_see_also .item:first-of-type{
        margin-left:0px

    }

    #bottom_see_also .item  .image_container{
    border:1px solid #ccc;height:170px;width:170px;;vertical-align:middle;text-align:center;display: table-cell;

    }

    #bottom_see_also .item  .label{
        font-size:90%;margin-top:5px

    }

    #bottom_see_also  img{
        max-height:168px;max-width: 168px;overflow:hidden;}


    .editing{
        border: 1px dashed lightgrey;
    }


    {$category->webpage->get('CSS')}

</style>
 
 
<span id="ordering_settings" class="hide" data-labels='{
    "ordered":"<i class=\"fa fa-thumbs-o-up fa-flip-horizontal fa-fw \" aria-hidden=\"true\"></i> {t}Ordered{/t}",
    "order":"<i class=\"fa fa-hand-pointer-o fa-fw \" aria-hidden=\"true\"></i>  {t}Order now{/t}",
    "update":"<i class=\"fa fa-hand-pointer-o fa-fw \" aria-hidden=\"true\"></i>  {t}Update{/t}"
    }'></span>

 
    <div id="description_block" class="description_block" >


        {foreach from=$content_data.description_block.blocks key=id item=data}
       
        
        {if $data.type=='text'}
           
        <div id="{$id}" class="xwebpage_content_header">
            {$data.content}  
        </div>
        {elseif $data.type=='image'}
            <div id="webpage_content_header_image" class="webpage_content_header"  >
                <img  src="{$data.image_src}"  style="width:100%"  />
            </div>
        {/if}
        {/foreach}






        <div style="clear:both"></div>
    </div>
    
    
    
    <div id="products" >
    
    {foreach from=$products item=product key=stack_index}
        <div class="product_wrap">

            <div id="product_target_div_{$stack_index}" stack_index="{$stack_index}"  product_code="{$product->get('Code')}" product_id="{$product->id}"  class="block four product_showcase " style="margin-bottom:20px;position:relative">

                <div style=padding:4px;height:30px;color:brown ;">
                </div>


               <a href="page.php?id={$product->get('Webpage Key')}">  
               <div class="wrap_to_center product_image" >
                    <img draggable="false" class="more_info" src="/art/moreinfo_corner1.png">

                    <img draggable="false" src="{$product->get('Image')}" />
                 </div>
                </a>

                <div class="product_description"  >
                    <span class="code">{$product->get('Code')}</span>
                    <div class="name">{$product->get('Name')}</div>

                </div>

                {if $logged}
                <div class="product_prices log_in " >
                    <div class="product_price">{t}Price{/t}: {$product->get('Price')}</div>
                    {assign 'rrp' $product->get('RRP')}
                    {if $rrp!=''}<div>{t}RRP{/t}: {$rrp}</div>{/if}
                </div>
                {else}
                <div class="product_price log_out hide" >
                    <div class="price italic">{t}For prices, please login or register{/t}</div>
                 </div>
                {/if}

                {if $logged}

                {if $product->get('Web State')=='Out of Stock'}
                    <div class="ordering log_in can_not_order {$product->get('Out of Stock Class')} ">
                        
                        {assign 'reminder_key' {$product->get('Reminder Key',{$user->id})} }

                        <span class="product_footer label ">{$product->get('Out of Stock Label')}</span>
                        <span class="product_footer reminder" reminder_key="{$reminder_key}"><i class="fa {if $reminder_key>0}fa-envelope{else}fa-envelope-o{/if}" aria-hidden="true"></i>  </span>


                    </div>
                {else if $product->get('Web State')=='For Sale'}

                <div class="ordering log_in " >
                {assign 'quantity_ordered' $product->get('Ordered Quantity',$order->id) }
                    <input maxlength=6  class='order_input ' id='but_qty{$product->id}'   type="text"' size='2'  value='{$quantity_ordered}' ovalue='{$quantity_ordered}'>
                     {if $quantity_ordered==''}
                        <span class="product_footer order_button "><i class="fa fa-hand-pointer-o fa-fw" aria-hidden="true"></i> {t}Order now{/t}</span>
                     {else}
                        <span class="product_footer order_button ordered"><i class="fa  fa-thumbs-o-up fa-flip-horizontal fa-fw" aria-hidden="true"></i> {t}Ordered{/t}</span>
                     {/if}
                     {assign 'favourite_key' {$product->get('Favourite Key',{$customer->id})} }
                     <span class="product_footer  favourite  " favourite_key={$favourite_key} ><i class="fa {if $favourite_key}fa-heart marked{else}fa-heart-o{/if}" aria-hidden="true"></i>  </span>


                </div>



                {/if}
                {else}
                <div class="ordering log_out hide" >
                    <div ><span onClick="location.href='login.php?from={$page->id}'" class="button login_button" >{t}Login{/t}</span></div>
                    <div ><span onClick="location.href='registration.php'" class="button register_button" >{t}Register{/t}</span></div>
                </div>
                {/if}

            </div>


        </div>
    {/foreach}
    <div style="clear:both"></div>
    </div>

     <div id="bottom_see_also"  class="{if $see_also|@count eq 0}hide{/if}">
         <div class="title">{t}See also{/t}:</div>
         <div>
         {foreach from=$see_also item=see_also_item name=foo}
                <div class="item" >
                    <div class="image_container" >
                        <a href="http://{$see_also_item->get('URL')}"> <img src="{$see_also_item->get('Image')}" style="" /> </a>
                    </div>
                    <div class="label" >
                        {$see_also_item->get('Name')}
                    </div>
                </div>
            {/foreach}
             </div>
            <div style="clear:both">
            </div>

    </div>
    
    
<script>



    $('.reminder').click(function() {
    
        if($(this).hasClass('lock'))return;
    
        $(this).addClass('lock')
    
        var icon= $( this ).find('i');

    


        if(icon.hasClass('fa-envelope-o')){
          
            icon.removeClass('fa-envelope-o').addClass('fa-envelope').addClass('marked')
            var request ='ar_reminders.php?tipo=send_reminder&pid='+ $(this).closest('.product_showcase').attr('product_id')

        }else{
          

            icon.removeClass('fa-envelope').addClass('fa-envelope-o').removeClass('marked')
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
    
    
    $('.order_button').hover(
            function() {
                var input= $( this ).closest('.ordering').find('.order_input');
                if(input.val()==''){
                    $( this ).closest('.ordering').find('.order_input').val(1)
                }


            }, function() {
                var input= $( this ).closest('.ordering').find('.order_input');
                if(input.attr('ovalue')==''){
                    $( this ).closest('.ordering').find('.order_input').val('')
                }
            }
    );
    
     $('.order_button').click(function() {
     
     
        if($(this).find('i').hasClass('fa-spinner'))return;
     
    
    
      
           var order_qty=$(this).prev('input').val()
           $(this).find('i').removeClass('fa-hand-pointer-o').addClass('fa-spinner fa-spin  ')
           $(this).prev('input').prop('readonly', true);

           
           
        var element=$(this);
        var request = 'ar_basket.php?tipo=edit_order_transaction&pid=' + $(this).closest('.product_showcase').attr('product_id') + '&order_key=' + {$order->id} + '&qty=' + order_qty+'&page_key='+{$category->webpage->id}+'&page_section_type=Family'

       
        $.getJSON(request, function (data) {
        
          
            if(data.state==200){
            $('#basket_total').html(data.data.order_total)
            $('#number_items').html(data.data.ordered_products_number)
            
         
            if(data.quantity>0){
                element.html($('#ordering_settings').data('labels').ordered).addClass('ordered')
            }else{
                element.html($('#ordering_settings').data('labels').order).removeClass('ordered')
            }
            
               if(data.quantity==0)data.quantity=''
            
            element.prev('input').val(data.quantity).attr('ovalue',data.quantity).prop('readonly', false);
            
            }else if (data.state==201){
            
             window.location.href = 'waiting_payment_confirmation.php?referral_key=' + {$category->webpage->id}

            
            }
        

        })


    });


    $(".order_input").on('input propertychange', function(){


        $(this).val( $(this).val().replace(/[^\d]/g,'') )

        var order_qty=$(this).val()

        var button=$( this ).closest('.ordering').find('.order_button');

        if(order_qty!=$(this).attr('ovalue')){


            button.html( $('#ordering_settings').data('labels').update).addClass('ordered')
        }else{

            if(order_qty>0){
                button.html($('#ordering_settings').data('labels').ordered).addClass('ordered')
            }else{
                button.html($('#ordering_settings').data('labels').order).removeClass('ordered')

            }

        }

    });

</script>    