{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 113 March 2018 at 15:15:55 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div id="block_{$key}" class="{$data.type} _block  " style="padding-left:20px;padding-right: 20px;padding-top: 10px;padding-bottom: 30px"   >


    <div class=" boxed  " >
        <h1>{$data._title}</h1>
        {if !empty($data._subtitle)}<h3 class="_subtitle single_line_height">{$data._subtitle}</h3>{/if}

        <div style="margin-top: 10px" class="text">
            {$data._text}
        </div>

    </div>


</div>

