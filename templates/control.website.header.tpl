{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 May 2018 at 21:55:54 BST, Sheffield, UK
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}




<div style="padding:10px  20px  0px 20px  ;min-height: 30px;border-bottom:1px solid #ccc " class="control_panel">

    <i onclick=toggle_device()" class="fa fa-desktop"></i>


    <i class="far hide fa-expand-alt button" onclick="toggle_showcase(this)" ></i>


    <span onclick="add_header_text()" class="button" style="margin-left: 30px"><i class="fa fa-plus"></i> {t}Text in main header{/t}</span>
    <span onclick="add_search_text()" class="button" style="margin-left: 30px"><i class="fa fa-plus"></i> {t}Text in search area{/t}</span>





    <span id="save_button" class="" style="float:right" onClick="$('#preview')[0].contentWindow.save_header()"><i class="fa fa-cloud  " aria-hidden="true"></i> {t}Save{/t}</span>


</div>



<iframe id="preview" style="width:100%;height: 1000px" frameBorder="0" src="/website.header.php?&website_key={$website->id}&theme={$theme}"></iframe>

<script>


    function add_header_text(){

        $('#preview')[0].contentWindow.add_header_text()

    }

    function add_search_text(){

        $('#preview')[0].contentWindow.add_search_text()

    }



function toggle_showcase(element){



    if($(element).hasClass('fa-expand-alt')){
        $(element).removeClass('fa-expand-alt').addClass('fa-compress-alt')
        $('#object_showcase').addClass('hide')
    }else{
        $(element).addClass('fa-expand-alt').removeClass('fa-compress-alt')
        $('#object_showcase').removeClass('hide')
    }

}


</script>