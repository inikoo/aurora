{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 July 2017 at 03:39:06 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} " style="Width:100%;"  >

    <div class="container">

        {foreach from=$data.columns  item=column name=two_one_columns}
            {if $column.type=='two_third'}

                <div type="{$column.type}" class="two_third _two_one   {if !$smarty.foreach.two_one_columns.last}last{/if}"   >
                    <h4 class="light _title hide"  contenteditable="true"  >{$column._title}</h4>
                    <div class="_text" contenteditable="true">{$column._text}</div>
                </div>

            {else}
                <div type="{$column.type}" class="one_third _two_one {if !$smarty.foreach.two_one_columns.last}last{/if}">
                    <div class="address_info two">
                        <h4 class="light _title"   contenteditable="true"  >{$column._title}</h4>
                        <p class="_text" contenteditable="true">{$column._text}</p>
                    </div>


                </div>

            {/if}
        {/foreach}






    </div>
    <div class="clearfix marb6"></div>

</div>
