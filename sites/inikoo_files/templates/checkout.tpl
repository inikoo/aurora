{include file='header.tpl'}

<div id="bd" style="padding:0px">

<h1>Checkout</h1>


<div id="payment_method" style="">
<table style="border:1px">
<tr><td><input type="radio" name="payment_type" value="paypal" ><img src="https://www.paypal.com/en_US/i/logo/PayPal_mark_37x23.gif"><span style="font-size:11px; font-family: Arial, Verdana;">The safer, easier way to pay.</span></td></tr>
<tr><td><input type="radio" name="payment_type" checked value="bank" ><span>Bank Transfer</span></td></tr>
<tr><td><input type="radio" name="payment_type" value="credit_card" ><span>Credit Card</span></td></tr>
</table>
</div>
<button id="payment_option">Confirm</button>



<div id="basket" style="display:none">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post"> 
<input type="hidden" name="cmd" value="_cart"> 
<input type="hidden" name="upload" value="1"> 
<input type="hidden" name="business" value="migara_1319797030_biz@inikoo.com"> 
<input type="hidden" name="item_name_1" value="Item Name 1"> 
<input type="hidden" name="amount_1" value="1.00"> 
<input type="hidden" name="item_name_2" value="Item Name 2"> 
<input type="hidden" name="amount_2" value="2.00"> 
<input type="submit" value="PayPal"> </form> 
</div>

<div id="bank_transfer" style="display:">
<table>
<tr><td>Account Number: xxxxxxx</td></tr>
<tr><td>Sort Code: xxxxxxx</td></tr>
<tr><td>Branch Name & Address: xxxxxxx</td></tr>
</table>
</div>


<div id="basket_2" style="display:none">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post"> 
<input type="hidden" name="cmd" value="_cart"> 
<input type="hidden" name="upload" value="1"> 
<input type="hidden" name="business" value="migara_1319797030_biz@inikoo.com"> 
{foreach from=$items key=key item=item}
<input type="hidden" name="item_name_{$key+1}" value="{$item.product_name}"> 
<input type="hidden" name="amount_{$key+1}" value="{$item.total}"> 
{/foreach}
<input type="submit" value="PayPal"> </form> 
</div>

</div>



<div>
{include file='footer.tpl'}