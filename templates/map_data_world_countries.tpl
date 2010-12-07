<?xml version="1.0" encoding="UTF-8"?>
<map map_file="maps/world.swf" tl_long="{$map_data.tl_long}" tl_lat="{$map_data.tl_lat}" br_long="{$map_data.br_long}" br_lat="{$map_data.br_lat}" zoom_x="{$map_data.zoom_x}" zoom_y="{$map_data.zoom_y}" zoom="{$map_data.zoom}">
    <areas>
{foreach from=$countries_data item=country}
        <area   oid="{$country.code}" title="{$country.title}" mc_name="{$country.code}" url="region.php?{$view}={$country.url_code}" {if $country.link}link_with="{$country.link}"{/if} {if $with_values}value="{$country.value}"{/if}> </area>
{/foreach}
    </areas>
    <movies></movies>
    <labels></labels>
</map>
