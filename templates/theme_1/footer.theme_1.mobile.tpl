{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 August 2018 at 22:24:30 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<div class="outter-elements"  style="clear: both">
    <div class="decoration decoration-margins"></div>


<div class="footer footer-dark">

    <p class="footer-text"   >
        <span onclick="location.href='tel:{$store->get('Telephone')}';"><i class="fa fa-phone padding_right_10" aria-hidden="true"></i> {$store->get('Telephone')}</span><br>
        <span onclick="location.href='mailto:{$store->get('Email')}';"><i class="fa fa-envelope-o padding_right_10" aria-hidden="true"></i> {$store->get('Email')}</span>


    </p>







    <p class="copyright-text">&copy; {t}Copyright{/t} <span class="copyright-year"></span>. {t}All rights reserved{/t}</p>
</div>

</div>