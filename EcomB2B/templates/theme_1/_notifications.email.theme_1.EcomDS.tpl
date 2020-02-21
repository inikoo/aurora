{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  12:02 pm Friday, 21 February 2020 (MYT), Kuala Lumpur Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}

{if !empty($subscription['Customer SNS Key'])}{assign "sns_key" $subscription['Customer SNS Key']}{else}{assign "sns_key" "new"}{/if}


<table id="notification_subscription_{$sns_key}"  data-sns_key="{$sns_key}"  data-protocol="email" data-channel="ds_notifications"  class="notification_subscription email">

   <tr class="title" >
       <td class="icon">
       <i class="fal fa-fw padding_right_5 fa-envelope"></i>
       </td>
    <td colspan="2">
        <span >{t}JSON Email{/t}</span>
    </td>
   </tr>

    <tr >
        <td class="icon">
            <i class="fa fa-fw fa-check very_discreet hide"></i>
        </td>

        <td>
            {if $sns_key=='new'}
                <span  class="subscribe like_button ">{t}Set up email{/t} <i class="fa fa-arrow-right"></i></span>
                <span class="add_subscription_endpoint hide "><input type="email" class=" endpoint" value="{$customer->get('Customer Main Plain Email')}"/> <i class="save valid changed  fa fa-cloud"></i></span>
            {else}
                {if $subscription['Customer SNS Subscription Status']=='Pending'}
                    <span class="discreet italic">{t name=$subscription['Customer SNS Subscription Endpoint']}An email has been send to %1, please click the confirmation link inside it{/t}</span>
                {/if}

            {/if}
        </td>


    </tr>
    <tbody class="{if $sns_key=='new'}hide{/if}">
    <tr >
        <td class="icon">
            <i class="fa fa-fw fa-check very_discreet hide"></i>
        </td>
        <td>
            <i class="far fa-fe fa-square margin_right_5"></i> {t}Portfolio item stock status updated{/t}
        </td>

    </tr>
    <tr>
        <td class="icon">
            <i class="fa fa-fw fa-check very_discreet hide"></i>
        </td>
        <td>
            <i class="far fa-fe fa-square margin_right_5"></i> {t}Portfolio item price updated{/t}
        </td>

    </tr>
    <tr>
        <td class="icon">
            <i class="fa fa-fw fa-check very_discreet hide"></i>
        </td>
        <td>
            <i class="far fa-fe fa-square margin_right_5"></i> {t}Order updated{/t}
        </td>

    </tr>
    </tbody>

</table>

