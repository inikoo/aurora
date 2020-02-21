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

<table border="0" data-protocol="email" data-channel="price_notification"  class="price_notification email">

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
            {if empty($settings.price)}
                <span  class="subscribe like_button italic">{t}Set up email{/t} <i class="fa fa-arrow-right"></i></span>
                <span class="add_subscription_endpoint hide "><input type="email" class=" endpoint" value="{$customer->get('Customer Main Plain Email')}"/> <i class="save valid changed  fa fa-cloud"></i></span>
            {/if}
        </td>


    </tr>

</table>



<h4 style="margin-top: 30px" ><i class="fal fa-fw padding_right_5  fa-browser"></i> {t}HTTP Endpoint{/t}</h4>
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