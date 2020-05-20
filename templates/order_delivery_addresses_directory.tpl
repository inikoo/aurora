{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22-07-2019 12:48:15 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version
-->
*}

<table class="delivery_options">


    {if $store->get('Store Can Collect')=='Yes'  and $store->get('Store Collect Address Country 2 Alpha Code')!=''  }
        <tr style="border:none"  data-type="collection" class="button order_delivery_directory_item"  >
            <td class="padding_right_10" >
                <i class="far radio_icon fa-fw {if $order->get('Order For Collection')=='No'}fa-circle{else}fa-scrubber{/if} "   title="{t}Set order for collection{/t}"></i>
            </td>
            <td >
                <i class="fal fa-hand-holding-box fw " title="{t}Order for collection{/t}"></i>
            </td>

            <td class="address" >{t}Order for collection{/t}</td>
        </tr>
    {/if}


    {foreach from=$other_delivery_addresses item=delivery_address key=checksum}
        <tr data-type="delivery" data-other_delivery_address_key="{$delivery_address.other_delivery_address_key}" data-type="{$delivery_address.type}" style="border:none"  class="button order_delivery_directory_item">
            <td class="padding_right_10" >
                <i class="far radio_icon fa-fw {if $order->get('Order Delivery Address Checksum')!=$checksum}fa-circle{else}fa-scrubber{/if}  "   title="{t}Delivery to this address{/t}"  ></i>
            </td>
            <td >
                {if $delivery_address.type=='invoice'}
                    <i class="fal fa-dollar-sign fw"  title="{t}Invoice address{/t}"></i>
                {elseif $delivery_address.type=='delivery'}
                    <i class="fal fa-truck fw"  title="{t}Invoice address{/t}"></i>
                {else}
                    <i class="fal fa-mailbox fw"  title="{t}Other delivery address{/t}"></i>
                {/if}
             </td>

            <td class="address" >{$delivery_address.formatted_value}</td>
        </tr>
    {/foreach}
</table>