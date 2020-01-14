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


    </div>

    <div class="islands">
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
                        <td>{$asset.icons}</td>
                        <td>{$asset.code}</td>
                        <td>{$asset.name}</td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        {/foreach}
    </div>

</div>

<script>

    $(function () {


        $('#tab').on('click', '.islands_container .date_chooser > div', function () {

            const ar_file = $('.islands_container').data('ar')

            const period= $(this).data('period')

            $('.islands_container .islands table').each(function (i, obj) {

               // console.log($(obj).data('args'))

                const args={ period: period,tipo:$(obj).attr('id'), args:$(obj).data('args')}
                console.log(args)

                $.getJSON('/'+ar_file, {
                    args


                }, function (data) {

                    $(obj).find('tbody.res').html(data.html)

                });

            });


        })


    });
</script>