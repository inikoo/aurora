{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 July 2017 at 10:55:46 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block  "  >

    <div class="container">

        {foreach from=$data.columns  item=column name=two_one_columns}
            {if $column.type=='two_third'}

                <div  class="two_third _two_one   {if !$smarty.foreach.two_one_columns.last}last{/if}"   >
                    <h4 class="light  hide"    >{$column._title}</h4>
                    <div >{$column._text}</div>
                </div>

            {else}
                <div class="one_third _two_one {if !$smarty.foreach.two_one_columns.last}last{/if}">
                    <div class="address_info two">
                        <h4 class="light "     >{$column._title}</h4>
                        <p  >{$column._text}</p>
                    </div>


                </div>

            {/if}
        {/foreach}






    </div>
    <div class="clearfix marb6"></div>

</div>
