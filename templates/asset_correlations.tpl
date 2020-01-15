{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  13 January 2020  16:28::09  +0800, Kuala Lumpir Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}


<div class="islands_container" data-ar="ar_elastic.php">

    <div class="date_chooser right">

        <div  data-period="all"
             class="fixed_interval {if  $period=='all'}selected{/if}">
            {t}All{/t}
        </div>
        <div data-period="1y"
             class="fixed_interval {if  $period=='1y'}selected{/if}" title="{t}1 year{/t}">
            {t}1Y{/t}
        </div>
        <div  data-period="1q"
             class="fixed_interval {if  $period=='1q'}selected{/if}" title="{t}1 quarter{/t}">
            {t}1q{/t}
        </div>

        <div  data-period="1m"
              class="fixed_interval {if  $period=='1m'}selected{/if}" title="{t}1 month{/t}">
            {t}1m{/t}
        </div>
        <div  data-period="1w"
              class="fixed_interval {if  $period=='1w'}selected{/if}" title="{t}1 week{/t}">
            {t}1w{/t}
        </div>
    </div>

    <div class="islands assets">
        {foreach  from=$tables item=table}
            <table class="island" id="{$table.id}"  data-args='{$table.data}'   >
                <tr>
                    <th colspan="3">
                        {$table.title}
                    </th>
                </tr>
                <tbody class="res">
                {foreach  from=$table.assets item=asset}
                    <tr>
                        <td class="icons">{$asset.icons}</td>
                        <td class="code">{$asset.code}</td>
                        <td class="truncate">{$asset.name}</td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        {/foreach}
    </div>

</div>

