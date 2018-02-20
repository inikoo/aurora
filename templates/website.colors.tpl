{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 February 2018 at 18:46:26 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<style>

    #palettes_repo{
        border-bottom:1px solid #ccc;padding:20px 40px
    }

    #palettes_repo .palette_option{
        width:200px;cursor:pointer;float:left;margin-left:20px;color:#999
    }

    #palettes_repo .palette_option:hover{
       color:#777
    }

    #palettes_repo > .palette_option:first{
        margin-left:0px
    }

    #palettes_repo .palette_option img{
        border:1px solid #ccc
    }

    #palettes_repo .palette_option:hover > img{
        border:1px solid #aaa
    }

</style>



{if isset($control_palette_template)}
    {include file=$control_palette_template}

{/if}


<div id="palettes_repo" class="hide" style="border-bottom: 1px solid #ccc">


    <div palette='empty' class="palette_option">
        <img src="/conf/etemplates/empty.png"  />
        <div style="text-align: center">{t}Empty{/t}</div>
    </div>
    <div palette='welcome_default' class="palette_option">
        <img src="/conf/etemplates/welcome_default.png"   />
        <div style="text-align: center">{t}Default{/t}</div>
    </div>
    <div palette='welcome_minimalistic' class="palette_option">
        <img src="/conf/etemplates/welcome_minimalistic.png"  />
        <div style="text-align: center">{t}Minimalistic{/t}</div>
    </div>

    <div palette='welcome_simple' class="palette_option">
        <img src="/conf/etemplates/welcome_simple.png"  />
        <div style="text-align: center">{t}Simple{/t}</div>
    </div>




<div style="clear:both">




</div>

</div>


<div id="colors_preview" style="width: 100%;min-height: 300px;border-bottom:1px solid #ccc">

    <div style="width:1100px;margin: auto;border:1px solid #ccc;height:400px">

    </div>

</div>


<script>



</script>