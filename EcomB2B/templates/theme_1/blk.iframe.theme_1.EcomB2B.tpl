{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 July 2017 at 10:41:44 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

{if $detected_device!='mobile'}

<div class="{$data.type} _block  " style="Width:100%;" h="{$data.height}" w="1240"  >
<iframe onload="var div=$(this).closest('div');div.css({ height: $(this).width()*div.attr('h')/div.attr('w') })" src="https://{$data.src}" width="100%" height="100%" scrolling="no" frameborder="0" allowtransparency="true" allowfullscreen="true"></iframe>
</div>
{else}



{/if}
