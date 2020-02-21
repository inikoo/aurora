{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  14:18 pm Friday, 21 February 2020 (MYT), Kuala Lumpur Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}

{if !empty($subscription['Customer SNS Key'])}{assign "sns_key" $subscription['Customer SNS Key']}{else}{assign "sns_key" "new"}{/if}

<table id="notification_subscription_{$sns_key}"  data-sns_key="{$sns_key}" data-protocol="https" data-channel="ds_notifications"  class="notification_subscription https">

    <tr class="title" >
        <td class="icon">
            <i class="fal fa-fw padding_right_5 fa-browser"></i>
        </td>
        <td colspan="2">
            <span >{t}Push notification to Https endpoint{/t}</span>
        </td>
    </tr>

    <tr >
        <td class="icon">
            <i class="fa fa-fw fa-check very_discreet hide"></i>
        </td>

        <td>
            {if $sns_key=='new'}
                <span  class="subscribe like_button italic">{t}Set up url{/t} <i class="fa fa-arrow-right"></i></span>
                <span class="add_subscription_endpoint hide ">https://<input type="url" class=" endpoint" value=""/> <i class="save valid changed  fa fa-cloud"></i></span>
            {/if}
        </td>


    </tr>

</table>

