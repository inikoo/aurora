{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 July 2017 at 23:28:53 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block  ">


    <div class="features_sec32">
        <div class="container">

            <div class="title2">
                <h2><span class="line"></span><span class="text "   >{$data.title}</span><em    >{$data.subtitle}</em></h2>
            </div>

            <div class="clearfix margin_top3"></div>

            {foreach from=$data.columns  item=three_pack_column name=three_pack_columns  }
                <div class="one_third _three_pack {if $smarty.foreach.three_pack_columns.last}last{/if}">

                    <div class="box">

                        <span aria-hidden="true"  class=" _icon simple_line_item_icon  {$three_pack_column.icon}"></span>
                        <br/><br/>
                        <h5   >{$three_pack_column.title}</h5>
                        <p  >{$three_pack_column.text}</p>

                    </div>

                </div>
               

            {/foreach}

        </div>
    </div>

    <div class="clearfix"></div>


</div>

