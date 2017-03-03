{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 March 2017 at 11:16:04 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div style="margin-top:20px;border-bottom:1px solid #ccc;">
    <div style=";border-top:1px solid #ccc;border-bottom:1px solid #ccc;padding:10px 20px">
        {t}2 columns{/t}
        <i class="fa fa-hand-lizard-o fa-flip-horizontal button" onClick="select_footer('footer_1')" style="float:right" aria-hidden="true"></i>

    </div>
<iframe style="overflow:hidden;height:90px;width:100%"  width="100%" src="/fdk/footer_1.php">

</iframe>
</div>


<div style="margin-top:20px;border-bottom:1px solid #ccc;">
    <div style=";border-top:1px solid #ccc;border-bottom:1px solid #ccc;padding:10px 20px">{t}Centered rows{/t}
        <i class="fa fa-hand-lizard-o fa-flip-horizontal button" onClick="select_footer('footer_2')" style="float:right" aria-hidden="true"></i>
    </div>
    <iframe style="overflow:hidden;height:220px;width:100%" " width="100%" src="/fdk/footer_2.php">

    </iframe>
</div>


<div style="margin-top:20px;border-bottom:1px solid #ccc;">
    <div style=";border-top:1px solid #ccc;border-bottom:1px solid #ccc;padding:10px 20px">{t}4 columns + 2 columns {/t}
        <i class="fa fa-hand-lizard-o fa-flip-horizontal button" onClick="select_footer('footer_3')" style="float:right" aria-hidden="true"></i>
    </div>
    <iframe style="overflow:hidden;height:165px;width:100%" " width="100%" src="/fdk/footer_3.php">

    </iframe>
</div>


<script>

    function select_footer(template){

        var request = '/ar_edit_website.php?tipo=set_footer_template&object=website&key=' + {$website->id} +'&value=' + template

        $.getJSON(request, function (data) {

            if (data.state == 200) {



            }

        })

    }

</script>