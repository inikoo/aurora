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
    <div style="clear:both"></div>
    <div class="{$data.type} _block  " style="-webkit-overflow-scrolling: touch;overflow-y: scroll;Width:100%;height:{$data.height_mobile}px"  data-src="https://{$data.src_mobile}"  data-h="{$data.height_mobile}" data-w="420"  >

        <iframe class="block_iframe" framescrolling="no" style="width:100%;height: 100%;border: 0px;overflow:hidden" src=""   allowfullscreen ></iframe>

    </div>
{/if}

