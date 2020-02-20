{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  11:37 am Thursday, 20 February 2020 (MYT), Kuala Lumpur Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}


<style>

</style>

<h4 >{t}Price change{/t}</h4>
<table border="1">
    <tr data-protocol="email" data-channel="price_notification"  class="price_notification email">
        <td class="label"><i class="fal fa-fw padding_right_5 fa-envelope"></i> {t}Email{/t}</td>
        <td>
            {if empty($settings.price)}
                <span  class="subscribe like_button">{t}Subscribe{/t}</span>
                <span class="add_subscription_endpoint hide "><input type="email" class=" endpoint" value="{$customer->get('Customer Main Plain Email')}"/> <i class="save valid changed  fa fa-cloud"></i></span>
            {/if}
        </td>


    </tr>
    <tr>
        <td class="label"><i class="fal fa-fw padding_right_5  fa-browser"></i> {t}HTTP Endpoint{/t}</td>
        <td><span class="activate like_button">{t}Activate{/t}</td>

    </tr>
</table>

<script>



</script>