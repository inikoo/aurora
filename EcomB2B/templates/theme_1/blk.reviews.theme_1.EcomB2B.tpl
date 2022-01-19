{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 February 2019 at 13:50:45 GMT+8, Kuala Lumpur Malysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "20"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} " top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">
    {if isset($data.provider) and isset($data.template_id) and   $data.provider=='trust_pilot'}

        <!-- TrustBox script -->
        <script type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script>
        <!-- End TrustBox script -->

        <!-- TrustBox widget - Carousel -->
        <div class="trustpilot-widget" data-locale="{$data.locale}" data-template-id="{$data.template_id}" data-businessunit-id="{$data.business_unit_id}" data-style-height="140px" data-style-width="100%" data-theme="light" data-stars="4,5" data-review-languages="{$data.lang}">
            <a href="{$data.url}" target="_blank" rel="noopener">Trustpilot reviews</a>
        </div>
        <!-- End TrustBox widget -->
    {else}
        {$data.html}
    {/if}
</div>

