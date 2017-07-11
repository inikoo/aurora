{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 July 2017 at 03:08:39 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


{include file="theme_1/_head.theme_1.tpl"}


<body xmlns="http://www.w3.org/1999/html">
<div class="wrapper_boxed">

    <div class="site_wrapper">

        {foreach from=$content.blocks item=$block key=key}

            {include file="{$theme}/blk.{$block.type}.{$theme}.tpl"}


        {/foreach}


    </div>

</div>








</body>

</html>

