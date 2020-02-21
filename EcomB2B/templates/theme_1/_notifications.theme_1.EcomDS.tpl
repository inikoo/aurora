{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  11:37 am Thursday, 20 February 2020 (MYT), Kuala Lumpur Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}



{assign 'subscriptions' $customer->get('SNS Subscriptions')}
{foreach from=$subscriptions item=_subscriptions key=subscription_protocol}

    {if $_subscriptions|@count gt 0}
        {foreach from=$_subscriptions item=subscription }
            {include file="theme_1/_notifications.$subscription_protocol.theme_1.EcomDS.tpl" subscription=$subscription}
        {/foreach}
    {else}
        {include file="theme_1/_notifications.$subscription_protocol.theme_1.EcomDS.tpl" subscription=$_subscriptions}
    {/if}




{/foreach}