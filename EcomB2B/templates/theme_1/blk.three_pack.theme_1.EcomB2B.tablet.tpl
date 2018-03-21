{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 March 2018 at 18:47:10 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<div id="block_{$key}"  class="{$data.type} _block  ">


<div class="clear"></div>

        <div class="content single_line_height ">


            <h1 class="center-block" style="text-align: center">{$data.title}</h1>
            {if !empty($data.subtitle)}<h5 style="text-align: center;margin-bottom: 20px;position: relative;top:-5px">{$data.subtitle}</h5>{/if}


            {foreach from=$data.columns  item=three_pack_column name=three_pack_columns  }

                <div class=" one-third-responsive  {if $smarty.foreach.three_pack_columns.last}last-column{/if}">
                    <h4 style="text-align: center">{$three_pack_column.title}</h4>
                    <p style="text-align: center" >{$three_pack_column.text}</p>
                </div>


            {/foreach}

        </div>


<div class="clear"></div>

</div>

