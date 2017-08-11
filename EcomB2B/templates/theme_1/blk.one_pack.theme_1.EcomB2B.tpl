{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 July 2017 at 10:54:33 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block  ">

           <div class="page_title4">
            <div class="container">
                <div class="title"><h1 ><span class="_title" >{$data._title}</span><span class="line"></span></h1></div>
                <h6 class="_subtitle " >{if isset($data._subtitle)}{$data._subtitle}{/if}</h6>
                <br><br><br>
                <div id="block_{$key}_editor" class="_text" style="clear:both;margin-top:50px">
                    {$data._text}
                </div>

            </div>
        </div>


        <div class="clearfix"></div>
          
</div>

