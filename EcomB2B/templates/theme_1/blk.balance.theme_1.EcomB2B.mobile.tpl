{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  03 April 2020  12:48::32  +0800, Kuala Lumpur , Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}

<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="{$data.type}    {if !$data.show}hide{/if}" data-ar_url="/ar_web_portfolio.php" style="padding-top:0;padding-bottom:{$bottom_margin}px">

    <div class="text_blocks  text_template_2" style=";margin-top: 40px">
        <div  class="text_block" style="text-align: center;font-size: 30px;color:black;margin-bottom: 20px;" >
            <i class="fa fa-piggy-bank"></i> <span style="font-weight: 800" class="customer_balance"></span>

        </div>
        <div  class="text_block" style="text-align: center">



            <form action="" method="post" enctype="multipart/form-data"  class="sky-form" style="box-shadow: none">

                <section class="col col-11">
                    <button  onclick="$(this).find('i').addClass('fa-spinner fa-spin'); window.location = '/top_up.sys' "  style="margin:0px auto;float: none" type="submit" class="button"><b>{if !empty($data._go_top_up_label)}{$data._go_top_up_label}{else}{t}Top up{/t}{/if}</b> <i  class=" fa fa-fw fa-arrow-right" aria-hidden="true"></i> </button>



                </section>


            </form>
        </div>
    </div>


    <div class="portfolio_sub_block "  style="clear:both;margin-bottom: 40px">
        <div class="table_top">
            <span class="title">{if empty($data.labels._main_title)}{t}Balance{/t}{else}{$data.labels._main_title}{/if}</span>
        </div>
        <div id="table_container"></div>
    </div>


</div>

<script>
    $("form").submit(function(e) {
        e.preventDefault();
        e.returnValue = false;
    });

</script>