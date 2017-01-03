{assign 'see_also'  $category->webpage->get_see_also() }
{assign 'content_data' $category->webpage->get('Content Published Data')}


{include file="style.tpl" css=$category->webpage->get('Published CSS') }


<div id="page_content">
 
<span id="ordering_settings" class="hide" data-labels='{
    "ordered":"<i class=\"fa fa-thumbs-o-up fa-flip-horizontal fa-fw \" aria-hidden=\"true\"></i> {t}Ordered{/t}",
    "order":"<i class=\"fa fa-hand-pointer-o fa-fw \" aria-hidden=\"true\"></i>  {t}Order now{/t}",
    "update":"<i class=\"fa fa-hand-pointer-o fa-fw \" aria-hidden=\"true\"></i>  {t}Update{/t}"
    }'></span>


    <div id="description_block" class="description_block {$content_data.description_block.class}" >


        {foreach from=$content_data.description_block.blocks key=id item=data}
       
        
        {if $data.type=='text'}
           
        <div id="{$id}" class="webpage_content_header fr-view">
            {$data.content}
        </div>
        {elseif $data.type=='image'}
            <div  id="{$id}" class="webpage_content_header webpage_content_header_image"  >
                <img  src="{$data.image_src}"  style="width:100%"  title="{if isset($data.caption)}{$data.caption}{/if}" />
            </div>
        {/if}
        {/foreach}






        <div style="clear:both"></div>
    </div>
    
    
    
    <div id="products" class="product_blocks ">
    
    {foreach from=$products item=product_data key=stack_index}
        <div class="product_wrap">

            {if $product_data.type=='product'}
            {assign 'product' $product_data.object}
            <div id="product_target_div_{$stack_index}" stack_index="{$stack_index}"  product_code="{$product->get('Code')}" product_id="{$product->id}"  class="product_block product_showcase " style="margin-bottom:20px;position:relative">


                <div class="product_header_text fr-view" >
                    {$product_data.header_text}
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
                <div class="product_prices log_out" >
                    <div >{t}For prices, please login or register{/t}</div>
                 </div>
                {/if}

                {if $logged}

                {if $product->get('Web State')=='Out of Stock'}
                    <div class="ordering log_in can_not_order {$product->get('Out of Stock Class')} ">
                        
                        {assign 'reminder_key' {$product->get('Reminder Key',{$user->id})} }

                        <span class="product_footer label ">{$product->get('Out of Stock Label')}</span>
                        <span class="product_footer reminder" reminder_key="{$reminder_key}"><i  title="{if $reminder_key>0}{t}Click to remove notification{/t}{else}{t}Click to be notified by email{/t}{/if}"   class="fa {if $reminder_key>0}fa-envelope{else}fa-envelope-o{/if}" aria-hidden="true"></i>  </span>


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
                <div class="ordering log_out " >
                    <div ><span onClick="location.href='login.php?from={$page->id}'" class="button login_button" >{t}Login{/t}</span></div>
                    <div ><span onClick="location.href='registration.php'" class="button register_button" >{t}Register{/t}</span></div>
                </div>
                {/if}

            </div>
            {else}
            {if $product_data.data.type=='text'}
                <div id="{$product_data.data.id}" style="position:relative" class=" panel  panel_{$product_data.data.size} {$product_data.data.class}">


                    <div class="panel_content fr-view">
                        {$product_data.data.content}
                    </div>





                </div>
            {elseif $product_data.data.type=='code'}
                <div id="{$product_data.data.id}" code_key="{$product_data.data.key}" style="position:relative;" class=" panel image panel_{$product_data.data.size}">




                    <iframe class="" src="/panel_code.php?id={$product_data.data.key}"  style=" height: 100%;width: 100%;border:none " sandbox="allow-scripts allow-same-origin allow-popups" >

                    </iframe>

                </div>




            {elseif $product_data.data.type=='image'}
                    <div id="{$product_data.data.id}" style="position:relative" class=" panel image panel_{$product_data.data.size}">


                        {if $product_data.data.link!=''}
                            <a href="{$product_data.data.link}"><img  src="{$product_data.data.image_src}"  title="{$product_data.data.caption}" /></a>
                        {else}
                            <img  src="{$product_data.data.image_src}"  title="{$product_data.data.caption}" />
                        {/if}






                    </div>
                {/if}
            {/if}
        </div>
    {/foreach}
    <div style="clear:both"></div>
    </div>


    <div   class="product_blocks {if $related_products|@count eq 0}hide{/if}">
        <div class="title">{t}Related products{/t}:</div>
        {foreach from=$related_products item=product_data key=stack_index}
            {assign 'product' $product_data.object}

            <div class="product_wrap">

        <div id="product_target_div_{$stack_index}" stack_index="{$stack_index}"  product_code="{$product->get('Code')}" product_id="{$product->id}"  class="product_block product_showcase " style="margin-bottom:20px;position:relative">


            <div class="product_header_text fr-view" >
                {$product_data.header_text}
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
            <div class="product_prices log_out" >
                <div >{t}For prices, please login or register{/t}</div>
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
            <div class="ordering log_out " >
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
    </div>
    
<script>



    $('.reminder').click(function() {
    
        if($(this).hasClass('lock'))return;
    
        $(this).addClass('lock')
    
        var icon= $( this ).find('i');

    


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



           var order_key='{$order->id}';
            if(order_key=='')order_key=0;

           
        var element=$(this);
        var request = 'ar_basket.php?tipo=edit_order_transaction&pid=' + $(this).closest('.product_showcase').attr('product_id') + '&order_key=' + order_key+ '&qty=' + order_qty+'&page_key='+{$category->webpage->id}+'&page_section_type=Family'

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