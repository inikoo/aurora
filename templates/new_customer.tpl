{include file='header.tpl'}
<div id="bd" style="padding:0 20px">
<span class="nav2 onright"  {if $next.id==0}style="display:none"{/if} ><a id="next" href="customer.php?id={$next.id}">{$next.name} &rarr; </a></span>
<span class="nav2 onright"  {if $prev.id==0}style="display:none"{/if}  ><a id="prev" href="customer.php?id={$prev.id}">&larr; {$prev.name}</a></span>
<span class="nav2 onleft"><a href="customers.php">{t}Customers List{/t}</a></span>


<span class="nav2"><a href="customers.php">{$home}</a></span>


  <div id="yui-main" >



    <div class="yui-b" >
       <h1>New Customer</h1> 

<div   style="float:right;border: 1px solid #ddd; padding: 15px; width: 450px;margin:0px 20px"    >

<div id="lblAddress" style="padding: 0px;margin:0px 0px"></div>


<table  class="edit inbox" style="float:right;backgroud:red" >
  <tr class="buttons" >
<td><span  onclick="supplier_new_user()" id="supplier_save"  class="unselectable_text button"     style="visibility:hidden;margin-right:30px" >{t}Save{/t} <img src="art/icons/disk.png" ></span></td>
    <td style="text-align:left"><span style="margin-left:30px" class="unselectable_text button" onclick="close_me('supplier');" >{t}Cancel{/t} <img src="art/icons/cross.png"/></span></td>

</tr>
</table>

</div>


<div>
<table >
<tr><td>Trade Name:</td><td><input id="trade_name" value=""/></td></tr>
<tr><td>Contact Name:</td><td><input id="contact_name" value=""/></td></tr>
<tr><td>Email:</td><td><input id="email" value=""/></td></tr>
<tr><td>Billing Address:</td><td></td></tr>
</table>

 <form name="Form1" method="post" action="addresses_international.aspx" id="Form1">
   
   <div id="divContainer" style="width: 100; background:red">
				
					


		  <div id="smartAddress" style="float:left"></div>
		  
		  
		  <textarea id=txtCopyText style="display:none"></textarea>
		  
		  
		  
			
		</div>

    </form>

<table border=0 style="clear:both">

<tr><td>Telephone:</td><td><input id="tel" value=""/></td></tr>

</table>

</div>


    </div>


</div> 
</div> 

<div>



{include file='footer.tpl'}

