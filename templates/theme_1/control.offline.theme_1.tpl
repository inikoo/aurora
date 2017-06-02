{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 May 2017 at 18:54:36 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<i class="fa fa-code-fork fa-rotate-90" aria-hidden="true"></i>
<span id="button_home_div" onClick="change_webpage_offline_view('home')" style="margin-left:10px;padding:5px 7px;border:1px solid #ccc" class="button"><i class="fa fa-home" aria-hidden="true"></i>
</span> <span id="button_link_div" onClick="change_webpage_offline_view('link')" style="margin-left:5px;padding:5px 7px;border:1px solid #ccc" class="button very_discreet"><i class="fa fa-share" aria-hidden="true"></i>
</span>




<script>

    function change_webpage_offline_view(view) {


console.log(view)

            if (view=='home') {

                $('#button_home_div').addClass('very_discreet')
                $('#button_link_div').removeClass('very_discreet')

            } else {


                $('#button_home_div').addClass('very_discreet')
                $('#button_link_div').removeClass('very_discreet')

            }

        $('#preview')[0].contentWindow.change_webpage_offline_view(view)


    }

 </script>