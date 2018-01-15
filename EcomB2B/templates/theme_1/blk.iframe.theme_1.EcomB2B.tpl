{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 July 2017 at 10:41:44 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



<div class="{$data.type} _block  "  style="Width:100%;" data-h="{$data.height}" data-w="1240"  >
    <iframe onload="var div=$(this).closest('div');div.css({ height: $(this).width()*div.data('h')/div.data('w') })"  style="width:100%;border: 0px;overflow:hidden;" src="https://{$data.src}"   allowfullscreen ></iframe>
</div>