{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 113 March 2018 at 15:15:55 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div id="block_{$key}"  class="{$data.type} _block  ">

            <div class="content single_line_height boxed " style="">
                <h4 >{$data._title}</h4>
                {if !empty($data._subtitle)}<h6 class="_subtitle single_line_height" >{$data._subtitle}</h6>{/if}

                <div style="margin-top: 10px">
                    {$data._text}
                </div>

            </div>

          
</div>

