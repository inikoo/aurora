{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 May 2017 at 12:04:46 GMT+8, Damansara, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



<div class="asset_container" style="padding-top:60px;padding-bottom:60px">


    <span style="margin-right:40px;border:1px solid #ccc;padding:5px 20px" class="button " onclick="change_view('website/{$website->id}/webpage/{$to_launch_webpage_key}')" >{t}Coming soon web page{/t}  <i class="fa fa-television padding_left_10" aria-hidden="true"></i></span>
    <span class="button save valid changed" onclick="launch_website()" >{t}Launch website{/t}  <i class="fa fa-rocket" aria-hidden="true"></i> </span>


</div>


<script>
    function launch_website(){

        var ajaxData = new FormData();

        ajaxData.append("tipo", 'launch_website')
        ajaxData.append("key", '{$website->id}')


        $.ajax({
            url: "/ar_edit_website.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
            complete: function () {
            }, success: function (data) {

                if (data.state == '200') {
                    change_view('store/{$website->get('Website Store Key')}/website',{ "reload":true,"reload_showcase":true})

                } else if (data.state == '400') {
                    swal(data.msg);
                }



            }, error: function () {

            }
        });

    }
</script>




