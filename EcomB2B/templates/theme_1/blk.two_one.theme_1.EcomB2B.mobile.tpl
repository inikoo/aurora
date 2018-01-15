{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 September 2017 at 17:11:36 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}





<div id="block_{$key}" class="{$data.type} _block  ">


    {foreach from=$data.columns  item=column name=two_one_columns}
            {if $column.type=='two_third'}

             
                  <div class="content footer-links">
                     <h3>{$column._title}</h3>
        <p style="clear: right">{$column._text}</p>

                </div>

            {else}
                
                                  <div class="content footer-links">

                     <h3>{$column._title}</h3>
        <p style="clear: right">{$column._text}</p>
 </div>
            {/if}
        {/foreach}



  


</div>

