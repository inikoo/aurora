{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16:31:51 MYT Tuesday, 14 July 2020 Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
*/*}

<style>
    {include file='shipper_chooser.images.tpl'}

    .shipper_chooser{
        display: flex; flex-wrap:   wrap;padding-top: 20px;
    }

    .shipper_option{
        margin:0px 0px 20px 30px;

       display: flex;
        border:1px solid #ccc;padding:10px;

    }



    .shipper_option div{
        flex-grow: 1;

    }
    .shipper_option .image{
        height: 64px;width: 64px;
        background-size:64px;
        background-repeat: no-repeat;
    }
    .shipper_option .label{
        padding-left:10px;width: 100px;
    }


</style>

<script>
    function shipper_selected(element){

    }
</script>

<div class="shipper_chooser " >
    {foreach  from=$shippers_data  key=code item=shipper_data }
        <div class="shipper_option button"  onclick="shipper_selected(this)">
            <div class="image shipper_logo_{$code} " ></div>

                <div class="label" >
                    {$shipper_data.label}
                </div>

        </div>

    {/foreach}

    <div class="shipper_option button"  onclick="shipper_selected(this)">
        <div class="image " style="background-color: grey" ></div>

        <div class="label" >
           {t}Other{/t}
        </div>

    </div>

</div>