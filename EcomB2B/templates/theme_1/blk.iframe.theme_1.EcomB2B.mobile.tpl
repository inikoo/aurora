{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 September 2017 at 02:03:20 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

{if !empty($data.src_mobile)}
<div class="{$data.type} _block  " style="Width:100%;" h="{$data.height_mobile}" w="420"  >
    <iframe onload="var div=$(this).closest('div');div.css({ height: $(this).width()*div.attr('h')/div.attr('w') });alert(div.attr('h'))" class="block_iframe" src="https://{$data.src_mobile}" width="100%" height="100%" scrolling="no" frameborder="0" allowtransparency="true" allowfullscreen="true"></iframe>
</div>
{/if}

