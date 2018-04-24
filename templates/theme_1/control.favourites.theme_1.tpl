{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 November 2017 at 14:11:29 GMT+8, Plane Kuala Lumpur - Bali
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>
    .fav_view_selected{
    border:1px solid #000;background-color:darkseagreen;
    }
</style>

<i class="fa fa-code-fork fa-rotate-90" aria-hidden="true"></i>
<span id="button_with_items" onClick="change_webpage_favourites_view('with_items')" style="margin-left:10px;padding:5px 7px;border:1px solid #ccc" class="button fav_view_selected"><i class="fa fa-heart" aria-hidden="true"></i>
</span> <span id="button_no_items" onClick="change_webpage_favourites_view('no_items')" style="margin-left:5px;padding:5px 7px;border:1px solid #ccc" class="button "><i class="fa fa-heart" aria-hidden="true"></i>
</span>




<script>

    function change_webpage_favourites_view(view) {



            if (view=='no_items') {

                $('#button_with_items').removeClass('fav_view_selected')
                $('#button_no_items').addClass('fav_view_selected')

            } else {


                $('#button_with_items').addClass('fav_view_selected')
                $('#button_no_items').removeClass('fav_view_selected')

            }

        $('#preview')[0].contentWindow.change_webpage_favourites_view(view)


    }

 </script>