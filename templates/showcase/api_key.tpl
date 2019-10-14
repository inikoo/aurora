{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 January 2018 at 15:41:08 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div class="name_and_categories">
    <span class="strong">{$title}</span></span>

    <div style="clear:both"></div>
</div>

<div class="asset_container">


    <div class="regenerate_button" style="padding:20px 0px">
        <span class=" button unselectable" onclick="regenerate_api_key({$api_key->id})" style="border:1px solid #ccc;padding:10px">{t}Regenerate private key{/t}</span>

    </div>

    <div class="regenerated_qrcode hide">
        <h4 style="margin-bottom: 0px">{t}New private key{/t}:</h4>
        <div>
            <span class="warning small"><i class="fa fa-exclamation-circle"></i> {t}The API private key is a secret information and should be treated as a password, the key will not be shown again{/t} (<b><span class="button" onclick="hide_private_key()">{t}hide{/t}</span>)</b></span>
        </div>
        <div id="api_key_qrcode" style="width: 300px;height: 300px;margin-top:20px;margin-bottom:10px"></div>
        <div class="api_key_qrcode_text small very_discreet italic"></div>

    </div>

</div>


<script>


    function hide_private_key(){
        $('.regenerate_button').removeClass('hide')

        $('.regenerated_qrcode').addClass('hide')

        $('.api_key_qrcode_text').html('')
        $('#api_key_qrcode').empty()

    }

    function regenerate_api_key(api_key) {

        var ajaxData = new FormData();

        ajaxData.append("tipo", 'regenerate_api')
        ajaxData.append("api_key", api_key)


        $.ajax({
            url: "/ar_edit.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {


                if (data.state == '200') {
                    $('.regenerate_button').addClass('hide')

                    $('.regenerated_qrcode').removeClass('hide')

                    $('.api_key_qrcode_text').html(data.qrcode)

                    $('#api_key_qrcode').qrcode({
                        size: 300, text: data.qrcode
                    });

                } else if (data.state == '400') {
                }


            }, error: function () {

            }
        });


    }
</script>
