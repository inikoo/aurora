{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 March 2018 at 12:20:41 GMT+8, Sanur Bali, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

{if $wowsbar_footer_data!=''   }
{include file="new_footer.tpl"}
{else}

<div class="outter-elements"  style="clear: both">

<div class="footer footer-dark">

    <p class="footer-text center-block">
        {if $store->get('Telephone')!=''}
        <span onclick="location.href='tel:{$store->get('Telephone')}';"><i class="fa fa-phone padding_right_10" aria-hidden="true"></i> {$store->get('Telephone')}</span><br>
        {/if}
        {if $store->get('Email')!=''}
            <!--email_off-->
        <span onclick="location.href='mailto:{$store->get('Email')}';"><i class="fa fa-envelope-o padding_right_10" aria-hidden="true"></i> {$store->get('Email')}</span>
            <!--/email_off-->
        {/if}
    </p>
    <p class="copyright-text">&copy; {t}Copyright{/t} <span class="copyright-year"></span>. {t}All rights reserved{/t}</p>
</div>
</div>

{/if}