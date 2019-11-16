{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 September 2017 at 17:22:18 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<div style="padding-top:20px;">

    <div id="maintabs" class="tabs" style="border-top:1px solid #ccc">
        <div id="tab_orders_in_basket" class="tab left {if $data['tab']=='orders.website.wget'}selected{/if}"
             onclick="change_view('orders/{if $data['parent']=='account'}all{else}{$data['parent_key']}{/if}/dashboard/website')" title="">
            <i class="fa fa-shopping-cart"></i> <span class="label"> {t}Orders{/t} <span ></span></span>
        </div>
        <div id="tab_orders_in_basket_purges" class=" tab left {if $data['tab']=='orders.website.purges.wget'}selected{/if}"
             onclick="change_view('orders/{if $data['parent']=='account'}all{else}{$data['parent_key']}{/if}/dashboard/website/purges')" title="">
            <i class="far fa-skull"></i> <span class="label"> {t}Purges{/t} <span ></span></span>
        </div>
        <div id="tab_orders_in_basket_abandoned_emails" class=" tab left {if $data['tab']=='orders.website.mailshots.wget'}selected{/if}"
             onclick="change_view('orders/{if $data['parent']=='account'}all{else}{$data['parent_key']}{/if}/dashboard/website/mailshots')" title="">
            <i class="far fa-envelope"></i> <span class="label"> {t}Mailshots for orders in basket{/t} <span ></span></span>
        </div>


    </div>


</div>

