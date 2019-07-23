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


    {if $can_collect=='Yes'}
        <tr style="border:none"   onClick="set_order_for_collection(this)" class="button "  >
            <td class="padding_right_10" >
                <i class="far radio_icon fa-fw {if $order->get('Order For Collection')=='No'}fa-circle{else}fa-scrubber{/if} "   title="{t}Set order for collection{/t}"></i>
            </td>
            <td class="" >
                <i class="fal fa-hand-holding-box fw " title="{t}Order for collection{/t}"></i>
            </td>

            <td class="address" >Order for collection</td>
        </tr>
    {/if}


    {foreach from=$other_delivery_addresses item=delivery_address key=checksum}
        <tr  data-other_delivery_address_key="{$delivery_address.other_delivery_address_key}"   data-type="{$delivery_address.type}" style="border:none" onClick="use_delivery_address_form_directory(this)" class="button ">
            <td class="padding_right_10" >
                <i class="far radio_icon fa-fw {if $order->get('Order Delivery Address Checksum')!=$checksum}fa-circle{else}fa-scrubber{/if}  "   title="{t}Delivery to this address{/t}"  ></i>
            </td>
            <td class="" >
                {if $delivery_address.type=='invoice'}
                    <i class="fal  fa-dollar-sign fw"  title="{t}Invoice address{/t}"></i>
                {elseif $delivery_address.type=='delivery'}
                    <i class="fal  fa-truck fw"  title="{t}Invoice address{/t}"></i>
                {else}
                    <i class="fal  fa-mailbox fw"  title="{t}Other delivery address{/t}"></i>
                {/if}
             </td>

            <td class="address" >{$delivery_address.formatted_value}</td>
        </tr>
    {/foreach}
</table>