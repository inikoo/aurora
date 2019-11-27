{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 March 2018 at 16:20:34 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div class="content" style="padding-top: 20px;padding-bottom:30px">
    {counter assign=i start=0 print=false}
    {foreach from=$data.columns  item=feature_column name=feature_columns}
        {foreach from=$feature_column  item=feature_row name=feature_rows}
            {counter}

             <div class="one-third-responsive {if $i%3}animate-left{else}last-column  animate-right{/if}">
                    <h5 class="thin">{$feature_row.title}</h5>
                    <p >{$feature_row.text}</p>
                </div>
        {/foreach}
    {/foreach}


<div class="clear"></div>
    <div class="decoration-zig-zag decoration-margins"></div>

</div>



