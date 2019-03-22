{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 August 2017 at 22:10:58 CEST, Tranava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}
{if !isset($is_devel) or !$is_devel  }
{if $client_tag_google_manager_id!=''}
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={$client_tag_google_manager_id}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
{/if}
{/if}
