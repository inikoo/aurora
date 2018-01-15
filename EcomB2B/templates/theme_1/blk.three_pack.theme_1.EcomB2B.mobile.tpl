{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 September 2017 at 17:14:09 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div id="block_{$key}"  class="{$data.type} _block  ">


<div class="clear"></div>

        <div class="content single_line_height">


            <h3>{$data.title}</h3>
            {if !empty($data.subtitle)}<h6>{$data.subtitle}</h6>{/if}


            {foreach from=$data.columns  item=three_pack_column name=three_pack_columns  }

                <div class="column-icon one-half-responsive  {if $smarty.foreach.three_pack_columns.last}last-column{/if}">
                    <h4><i class="ion-ios-bolt color-red-dark"></i>{$three_pack_column.title}</h4>
                    <p  >{$three_pack_column.text}</p>
                </div>


            {/foreach}

        </div>


<div class="clear"></div>
    <div class="decoration-slash decoration-margins"></div>

</div>

