{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:17 July 2017 at 10:47:51 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div class="content">
    {counter assign=i start=0 print=false}
    {foreach from=$data.columns  item=feature_column name=feature_columns}
        {foreach from=$feature_column  item=feature_row name=feature_rows}
            {counter}


                {if $i==1 or $i==3 or $i==5} <div class="one-half-responsive"> {/if}



             <div class="single_line_height_plus column-home-center one-half {if $i%2}animate-left{else}last-column  animate-right{/if}">
                    <h5 class="thin">{$feature_row.title}</h5>
                    <p >{$feature_row.text}</p>
                </div>


                    {if $i==2 or $i==4 or $i==6} </div> {/if}


        {/foreach}
    {/foreach}


<div class="clear"></div>
    <div class="decoration-zig-zag decoration-margins"></div>

</div>



