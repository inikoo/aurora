{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 September 2016 at 10:23:23 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


<div style="padding:10px;border-bottom:1px solid #ccc;text-align:right"
     class="{if !$products_without_auto_web_configuration}hide{/if}">
    <span class="button" data-data='{ "object": "part", "key":"{$part_sku}"}'
          onClick="set_all_products_web_configuration('Online Auto',this)"><i class="fa fa-magic "
                                                                              aria-hidden="true"></i>  <span
                class="padding_left_5">{t}Set all products web configuration as Automatic{/t}</span> </span>
</div>

<script>


    function set_all_products_web_configuration(value, element) {


        if ($(element).hasClass('disabled')) {
            return
        }

        icon = 'fa-magic';


        $(element).find('i.fa').removeClass(icon).addClass('fa-spinner fa-spin')

        var request = '/ar_edit.php?tipo=object_operation&operation=set_all_products_web_configuration&object=' + $(element).data('data').object + '&key=' + $(element).data('data').key + '&metadata=' + JSON.stringify({
                    "value": value})


        $.getJSON(request, function (data) {
            if (data.state == 200) {

                console.log(data)
                if (data.request != undefined) {
                    change_view(data.request)
                } else {
                    change_view(state.request, {
                        'reload_showcase': 1
                    })
                }

            } else if (data.state == 400) {
                $(element).find('i.fa').addClass(icon).removeClass('fa-spinner fa-spin')

            }


        })


    }

</script>