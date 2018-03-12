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
<div class="{$data.type} _block  " style="Width:100%;" data-h="{$data.height_mobile}" data-w="420"  >
    <iframe onload="var div=$(this).closest('div');
   // console.log(div.data('w'));
   // console.log(div.data('h'));
   // console.log($( window ).width());
   //  console.log($(this).width()*div.data('h')/div.data('w') );

   $(this).width($( document ).width());
    $(this).height($(this).width()*div.data('h')/div.data('w') );

" class="block_iframe" style="border: 0px;overflow:hidden" src="https://{$data.src_mobile}"   allowfullscreen ></iframe>
</div>
{/if}

