{include file="$head_template"}
<body class="yui-skin-sam kaktus">
  <div id="container" >
   {include file="$header_template"}
      
      
     
	<div id="top_content" >
	  <div id="found_in">
	   
	   <a href="department.php?code={$family->get('Product Family Main Department Code')}">{$family->get('Product Family Main Department Name')}</a>
	   
	    
	  </div>
	  {include file="templates/search_input.tpl"}
	  
	
	 
	  <div style="clear:both"></div>
	</div>
	
	
	{if !$number_products}
	<div class="no_products_for_sale" >Sorry, There is no products for sale in this family</div>
	
	{/if}
	
	{if !$logged_in and $number_products}
	<div id="register_banner" style="font-size:12px">
	  <div style="text-align:center;font-size:10px;float:right;width:180px;height:120px;margin-right:60px;margin-top:40px">
	    Registered customers login here.
	    <table>
	    <tr><td style="text-align:left">Email:</td></tr>
	    <tr><td><input style="border:1px solid #fff;width:100%" value=""></td></tr>

	    <tr><td style="text-align:left">Password:</td></tr>
	    <tr><td><input style="border:1px solid #fff;width:100%" value=""></td></tr>
	    <tr><td style="text-align:right"><button>Submit</button></td></tr>
	    </table>
	    Forgot you password?
	  </div>

	  <div style="position:relative;left:40px;width:400px;top:30px">
	    <h1>We supply wholesale to the gift trade</h1>
	    <p>To see product prices <b><a href="register.php">please register first</a></b>, it's easy and should not take you more than 5 minutes.</p>
	    <p>Keep in mind that this is a <b>trade only</b> web site. You should intent to resell the items purchased from us.</p>
	    <button>Register</button>
	  </div>


	</div>	 
	
	
	
		<div>
 
	  {foreach from=$products item=product}
	  {if $product.image!='art/nopic.png'}
	  <div style="float:left;margin-top:15px">
	    <img src="{$product.image}" alt="{$product.code}" height="200" style="margin:3px;5px;margin-bottom:7px" />
	    <div style="font-size:10px;text-align:center;border:1px solid #ccc;width:110px;padding:5px;margin:8px;margin-left:20px">
	    <span><b>{$product.code}</b></span><br>
	    <span>{$product.name}</span>

	    </div>
	  </div>
	  {/if}
	  {/foreach}
	</div>
	
	
	
	
	{/if}
	{ if file_exists("splinters/presentation/$page_key.tpl") }
	<div style="font-size:10px;;margin-top:10px;padding:10px">
	  {include file="splinters/presentation/$page_key.tpl"}
	  <div style="clear:both"></div>
	</div>	 
	
	
	
	
	{/if}
	{if $logged_in}
	
        <div class="data_table"  style="clear:both">
     <span id="table_title" class="clean_table_title">{t}Products{/t}</span>

     <div id="table_type">
          <span id="table_type_slideshow" style="float:right;{if !$can_view_slideshow}display:none;{/if}" class="table_type state_details {if $table_type=='slideshow'}selected{/if}">{t}Slideshow{/t}</span>
     <span id="table_type_list" style="float:right;margin-right:10px;{if !$can_view_list}display:none;{/if}" class="table_type state_details {if $table_type=='list'}selected{/if}">{t}List{/t}</span>
     <span id="table_type_thumbnails" style="float:right;margin-right:10px;{if !$can_view_thumbnails}display:none;{/if}" class="table_type state_details {if $table_type=='thumbnails'}selected{/if}">{t}Thumbnails{/t}</span>
     <span id="table_type_manual" style="float:right;margin-right:10px;{if !$can_view_manual}display:none;{/if}" class="table_type state_details {if $table_type=='manual'}selected{/if}">{t}e-Showroom{/t}</span>

     </div>
     
     
<div id="list_options0"></div>
        {include file='templates/table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }

    <div id="thumbnails0" class="thumbnails" style="border-top:1px solid SteelBlue;clear:both;{if $table_type!='thumbnails'}display:none{/if}"></div>
    <div id="table0"   class="data_table_container dtable btable "  style="{if $table_type!='list'}display:none{/if}"   > </div>
    <div id="manual0" class="manual" style="border-top:1px solid SteelBlue;clear:both;{if $table_type!='manual'}display:none{/if}"></div>
    <div id="slideshow0" class="slideshow" style="border-top:1px solid SteelBlue;clear:both;{if $table_type!='slideshow'}display:none{/if}"></div>
    <div id="none0" class="none" style="border-top:1px solid SteelBlue;clear:both;{if $table_type!='none'  }display:none{/if}">{t}Products not availeables{/t}</div>

  
  
  
</div>
	{else}
	<div>
	  
	  {foreach from=$products item=product}
	  {if $product.image!='art/nopic.png'}
	  <div style="float:left;margin-top:15px">
	    <img src="{$product.image}" alt="{$product.code}" height="200" style="margin:3px;5px;margin-bottom:7px" />
	    <div style="font-size:10px;text-align:center;border:1px solid #ccc;width:110px;padding:5px;margin:8px;margin-left:20px">
	    <span><b>{$product.code}</b></span><br>
	    <span>{$product.name}</span>

	    </div>
	  </div>
	  {/if}
	  {/foreach}
	</div>
	
	{/if}
 
    
	<div style="clear:both;height:20px"></div>
    {include  file="$footer_template"}
 </body>
