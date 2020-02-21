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
        <span >{t}Email{/t}</span>
    </td>
   </tr>

    <tr >
        <td class="icon">
            <i class="fa fa-fw fa-check very_discreet hide"></i>
        </td>

        <td>
            {if $sns_key=='new'}
                <span  class="subscribe like_button italic">{t}Set up email{/t} <i class="fa fa-arrow-right"></i></span>
                <span class="add_subscription_endpoint hide "><input type="email" class=" endpoint" value="{$customer->get('Customer Main Plain Email')}"/> <i class="save valid changed  fa fa-cloud"></i></span>
            {/if}
        </td>


    </tr>

</table>

