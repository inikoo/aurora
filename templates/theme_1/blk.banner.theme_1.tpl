{*
<!--
 About:
 Author: Sasikumaran <sasi@ancientwisdom.biz>
 Created: 25 March 2019 at 21:08:48 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 4
-->
*}

{*{if !isset($data.src_mobile)}
    {assign var="src_mobile" value=""}
{else}
    {assign var="src_mobile" value=$data.src_mobile}
{/if}

{if !isset($data.height_mobile)}
    {assign var="height_mobile" value="250"}
{else}
    {assign var="height_mobile" value=$data.height_mobile}
{/if}

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if} " style="Width:100%;" h="{$data.height}" h_mobile="{$height_mobile}"  src_mobile="{$src_mobile}"  w="1240"  >*}


<div id="banner_1" style="position:relative;height:330px;overflow:hidden;visibility:hidden;">
    <div data-u="loading" class="bannerl-009-spin" style="position:absolute;top:0px;left:0px;width:100%;height:100%;text-align:center;background-color:rgba(0,0,0,0.7);">
        <img style="margin-top:-19px;position:relative;top:50%;width:38px;height:38px;" src="art/sliders/spin.svg" />
    </div>
    <div data-u="slides" style="cursor:default;position:relative;top:0px;left:0px;width:1240px;height:330px;overflow:hidden;">
        <div>
            <img data-u="image" src="art/sliders/blue.jpg"/>
        </div>
        <div>
            <img data-u="image" src="art/sliders/green.jpg" />
        </div>
        <div>
            <img data-u="image" src="art/sliders/red.jpg" />
        </div>
    </div>
    <div data-u="navigator" class="bannerb051" style="position:absolute;bottom:12px;right:12px;" data-autocenter="1" data-scale="0.5" data-scale-bottom="0.75">
        <div data-u="prototype" class="i" style="width:16px;height:16px;">
            <svg  viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
                <circle class="b" cx="8000" cy="8000" r="5800"></circle>
            </svg>
        </div>
    </div>
    <div data-u="arrowleft" class="bannera051" style="width:55px;height:55px;top:0px;left:25px;" data-autocenter="2" data-scale="0.75" data-scale-left="0.75">
        <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
            <polyline class="a" points="11040,1920 4960,8000 11040,14080 "></polyline>
        </svg>
    </div>
    <div data-u="arrowright" class="bannera051" style="width:55px;height:55px;top:0px;right:25px;" data-autocenter="2" data-scale="0.75" data-scale-right="0.75">
        <svg viewbox="0 0 16000 16000" style="position:absolute;top:0;left:0;width:100%;height:100%;">
            <polyline class="a" points="4960,1920 11040,8000 4960,14080 "></polyline>
        </svg>
    </div>
</div>
<script type="text/javascript">banner_1_slider_init();</script>

{*
</div>*}
