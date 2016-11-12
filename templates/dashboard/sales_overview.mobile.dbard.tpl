
<div style="padding:10px">

    <button id="menu-speed" class="mdl-button mdl-js-button mdl-button--icon">
        <i class="material-icons">more_vert</i>
    </button>
    <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect" for="menu-speed">
        <li class="mdl-menu__item">Fast</li>
        <li class="mdl-menu__item">Medium</li>
        <li class="mdl-menu__item">Slow</li>
    </ul>

    {$interval_label}
</div>

    <table class="mdl-data-table mdl-js-data-table  mdl-shadow--2dp" style="width:100%">
        <thead>
        <tr>
            <th class="mdl-data-table__cell--non-numeric">{t}Store{/t}</th>
            <th>{t}Sales{/t} </th>
            <th>&Delta;{t}1y{/t}</th>
        </tr>
        </thead>
        <tbody>

        {foreach from=$sales_overview item=record}
            <tr class="{$record.class} small_row">
                <td class=" {if isset($record.label.view) and $record.label.view!='' }link{/if}" {if isset($record.label.view) and $record.label.view!='' }onclick="change_view('{$record.label.view}')" {/if}
                    title="{if isset($record.label.title)}{$record.label.title}{else}{$record.label.label}{/if}  ">{$record.label.short_label}</td>
                <td id="orders_overview_sales_{$record.id}" class="sales width_200 aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}"> {$record.sales}</td>
                <td id="orders_overview_sales_delta_{$record.id}" class="last sales width_100 aright {if !($type=='invoices' or  $type=='invoice_categories')}hide{/if}"
                    title="{$record.sales_1yb}">{$record.sales_delta}</td>
            </tr>
        {/foreach}


        </tbody>
    </table>




    <script>

        $("#content").on("swipeleft",function(){
            alert('left')
        });
        $("#content").on("swiperight",function(){
            alert('swiperight')
        });



    </script>
