{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 July 2017 at 02:57:22 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} "   >

<div class="features_sec30">
    <div class="container">


        {foreach from=$data.columns  item=feature_column name=feature_columns}
            <div class="one_third _col {if $smarty.foreach.feature_columns.last}last{/if}">

                {foreach from=$feature_column  item=feature_row name=feature_rows}
                    <div class="_row">
                    <div class="left  "><i aria-hidden="true" class="six_pack_icon simple_line_item_icon {$feature_row.icon}" icon="{$feature_row.icon}" ></i></div>
                    <div class="right">
                        <h5 class="light six_pack_title" contenteditable="true">{$feature_row.title}</h5>
                        <p class="six_pack_text" contenteditable="true">{$feature_row.text}</p>
                    </div>
                    </div>

                    {if !$smarty.foreach.feature_rows.last}
                        <div class="clearfix margin_top7"></div>
                    {/if}

                {/foreach}


            </div>
        {/foreach}

    </div>
</div>
<div class="clearfix"></div>
</div>

