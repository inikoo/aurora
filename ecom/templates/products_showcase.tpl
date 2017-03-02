{assign 'see_also'  $category->webpage->get_see_also() }
{assign 'content_data' $category->webpage->get('Content Published Data')}


{include file="style.tpl" css=$category->webpage->get('Published CSS') }


<div id="page_content">

    {if $category->get('Product Category Status')=='Discontinued'}
        <div  class="section description_block alert alert-error alert-title" style="text-align:center">
            <i class="fa fa-frown-o padding_right_20" aria-hidden="true"></i> {t}Discontinued{/t} <i class="fa fa-frown-o padding_left_20" aria-hidden="true"></i>
        </div>
    {/if}
 
<span id="ordering_settings" class="hide" data-labels='{
    "ordered":"<i class=\"fa fa-thumbs-o-up fa-flip-horizontal fa-fw \" aria-hidden=\"true\"></i> <span class=\"order_button_text\">{t}Ordered{/t}</span>",
    "order":"<i class=\"fa fa-hand-pointer-o fa-fw \" aria-hidden=\"true\"></i>  <span class=\"order_button_text\">{t}Order now{/t}</span>",
    "update":"<i class=\"fa fa-hand-pointer-o fa-fw \" aria-hidden=\"true\"></i>  <span class=\"order_button_text\">{t}Updated{/t}</span>"
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

    {if $category->get('Product Category Status')=='Discontinued'}
        <div  class="section description_block alert alert-error alert-title" style="text-align:center">
            {t}Sorry, but all products in this web page are discontinued{/t}
        </div>
    {/if}
    
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
                   <i class="fa fa-info-circle more_info" aria-hidden="true" title="More info"></i>

                    <img draggable="false" src="{$product->get('Image')}" />
                 </div>
                </a>

                <div class="product_description"  >
                    <span class="code">{$product->get('Code')}</span>
                    <div class="name item_name">{$product->get('Name')}</div>

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
                    <span class="product_footer order_button "><i class="fa fa-hand-pointer-o fa-fw" aria-hidden="true"></i> <span class="order_button_text">{t}Order now{/t}</span></span>
                     {else}
                         <span class="product_footer order_button ordered"><i class="fa  fa-thumbs-o-up fa-flip-horizontal fa-fw" aria-hidden="true"></i> <span class="order_button_text">{t}Ordered{/t}</span></span>
                     {/if}
                     {assign 'favourite_key' {$product->get('Favourite Key',{$customer->id})} }
                     <span class="product_footer  favourite  " favourite_key={$favourite_key} ><i class="fa {if $favourite_key}fa-heart marked{else}fa-heart-o{/if}" aria-hidden="true"></i>  </span>


                </div>



                {/if}
                {else}
                <div class="ordering log_out " >
                    <div ><span onClick="location.href='login.php?from={$page->id}'" class="button login_button label_when_log_out" >{t}Login{/t}</span></div>
                    <div ><span onClick="location.href='registration.php'" class="button register_button label_when_log_out" >{t}Register{/t}</span></div>
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




                    <iframe class="" src="/panel_code.php?id={$product_data.data.key}"      style="position: absolute; height: 100%;width: 100%;padding:0px;margin:0px;background-color: white "
                            marginwidth="0"
                            marginheight="0"
                            hspace="0"
                            vspace="0"
                            frameborder="0"
                            scrolling="no"
                            sandbox="allow-scripts allow-same-origin allow-popups allow-top-navigation"

                    >

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
                    <i class="fa fa-info-circle more_info" aria-hidden="true" title="More info"></i>

                    <img draggable="false" src="{$product->get('Image')}" />
                </div>
            </a>




        <div class="product_description"  >
            <span class="code">{$product->get('Code')}</span>
            <div class="name item_name">{$product->get('Name')}</div>

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

                    <span class="product_footer label " style="width: 300px">{$product->get('Out of Stock Label')}</span>
                    <span style="border-top:none" class="product_footer reminder" reminder_key="{$reminder_key}"><i class="fa {if $reminder_key>0}fa-envelope{else}fa-envelope-o{/if}" aria-hidden="true"></i>  </span>


                </div>
            {else if $product->get('Web State')=='For Sale'}

                <div class="ordering log_in " >
                    {assign 'quantity_ordered' $product->get('Ordered Quantity',$order->id) }
                    <input maxlength=6  class='order_input ' id='but_qty{$product->id}'   type="text"' size='2'  value='{$quantity_ordered}' ovalue='{$quantity_ordered}'>
                    {if $quantity_ordered==''}
                        <span class="product_footer order_button "><i class="fa fa-hand-pointer-o fa-fw" aria-hidden="true"></i> <span class="order_button_text">{t}Order now{/t}</span>></span>
                    {else}
                        <span class="product_footer order_button ordered"><i class="fa  fa-thumbs-o-up fa-flip-horizontal fa-fw" aria-hidden="true"></i> <span class="order_button_text">{t}Ordered{/t}</span></span>
                    {/if}
                    {assign 'favourite_key' {$product->get('Favourite Key',{$customer->id})} }
                    <span class="product_footer  favourite  " favourite_key={$favourite_key} ><i class="fa {if $favourite_key}fa-heart marked{else}fa-heart-o{/if}" aria-hidden="true"></i>  </span>


                </div>



            {/if}
        {else}
            <div class="ordering log_out " >
                <div ><span onClick="location.href='login.php?from={$page->id}'" class="button login_button label_when_log_out" >{t}Login{/t}</span></div>
                <div ><span onClick="location.href='registration.php'" class="button register_button label_when_log_out" >{t}Register{/t}</span></div>
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



{include file="order_products.js.tpl" }
