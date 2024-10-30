{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 August 2017 at 17:52:41 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<div class="outter-elements" data-code-web-"{$website->get('Code')}"   data-code-"{$store->get('Code')}" style="clear: both">
    <div class="decoration decoration-margins"></div>

<div class="footer footer-dark">
    <p class="footer-text"   >
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