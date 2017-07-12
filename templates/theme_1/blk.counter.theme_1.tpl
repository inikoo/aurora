{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 July 2017 at 14:24:46 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} ">

    <div class="features_sec31">
        <div class="container">

            <div class="counters1 two">


                {assign "column_class" "one_fourth"}

                {foreach from=$data.columns  key=column_key item=counter_column name=counter_columns}
                    <div  link="{$counter_column.link}" number="{$counter_column.number}"  class="{$column_class}  _counter  {if $smarty.foreach.counter_columns.last}last{/if}"><span id="counter_target_{$key}_{$column_key}">0</span> <h4 contenteditable="true">{$counter_column.label}</h4></div>
                {/foreach}


            </div><!-- end counters1 section -->

        </div>
    </div><!-- end features section 31 -->

    <div class="clearfix"></div>


    <script>


        {foreach from=$data.columns key=column_key  item=counter_column name=counter_columns}
        $('#counter_target_{$key}_{$column_key}').animateNumber({
            number: {$counter_column.number},

            numberStep: function (now, tween) {
                var floored_number = Math.floor(now), target = $(tween.elem);

                target.text(floored_number);
            }
        }, 1000*Math.log({$counter_column.number}))

        {/foreach}


        function stop_counter(id){
            $('#'+id).stop()

        }


    </script>

</div>
