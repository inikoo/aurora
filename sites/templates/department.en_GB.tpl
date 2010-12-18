{include file="$head_template"}
<body>
  <div id="container" >
    {include file="$home_header_template"}
    <div id="page_content" style="{$page_data.content_style}"  >
      {include file="$left_menu_template"}
      <div id="central_content" style="width:655px;">
	     {include file="templates/search_input.tpl"}
	<div class="block" id="product_block_layout">
	    {foreach from=$families item=family}
	    <div style="width:100px;height:120px;float:left;margin:10px;margin-bottom:15px">
	      <div style="width:105px;height:105px;border:1px solid #ccc;cursor:pointer" onclick="location.href='family.php?code={$family.code}'" >
		<span style="background-image:url('art/background_fam_code.png') ;color:#fff;padding:2px 5px;position:relative;bottom:4px;left:-5px;font-size:80%"  ><a style="color:#fff"  href="family.php?code={$family.code}" >{$family.code}</a></span>
		
	      </div>
	      <div style="text-align:center;font-size:10px">{$family.name}</div>
	    </div>
	  {/foreach}
	</div>
	
	<div class="block" id="product_list_layout" style="display:none">
	  <table class="families">
	    {foreach from=$families item=family}
	    <tr><td><a href="family.php?code={$family.code}">{$family.code}</a></td><td><a href="family.php?id={$family.code}"   >{$family.name}</a></td></tr>
	    {/foreach}
	  </table>
	</div>
      </div>
      <div style="clear:both"></div>
    </div>
    {include  file="$footer_template"}
</body>
