{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 April 2018 at 13:34:20 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "20"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}


<div id="block_{$key}"  class="{$data.type}  {if !$data.show}hide{/if}"  style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px"  >


    <div class="page_not_found">

        <strong>{$data.labels._strong_title}</strong>
        <br/>
        <b >{$data.labels._title}</b>

        <em >{$data.labels._text}</em>

        <p >{$data.labels._home_guide}</p>

        <div class="clear separator"></div>

        <a href="index.php" class="real_button"><span class="fa fa-home fa-lg"></span>&nbsp; <span>{$data.labels._home_label}</span></a>

    </div>


</div>




