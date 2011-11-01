{include file='header.tpl'}

<div id="bd" style="padding:0px">

<h1>Checkout</h1>


<div id="payment_method" style="">
<table style="border:1px">
<tr><td><input type="radio" name="payment_type" value="paypal" ><img src="https://www.paypal.com/en_US/i/logo/PayPal_mark_37x23.gif"><span style="font-size:11px; font-family: Arial, Verdana;">The safer, easier way to pay.</span></td>
<td>
<div id="basket_2" style="display:none">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post"> 
<input type="hidden" name="cmd" value="_cart"> 
<input type="hidden" name="upload" value="1"> 
<input type="hidden" name="business" value="migara_1319797030_biz@inikoo.com"> 
{foreach from=$items key=key item=item}
<input type="hidden" name="item_name_{$key+1}" value="{$item.product_name}"> 
<input type="hidden" name="amount_{$key+1}" value="{$item.price}"> 
<input type="hidden" name="quantity_{$key+1}" value="{$item.qty}">
{/foreach}
<input type="submit" value="PayPal"> </form> 
</div>
</td>
</tr>
<tr><td><input type="radio" name="payment_type" checked value="bank" ><span>Bank Transfer</span></td></tr>
<tr>
<td>
<div id="bank_transfer" style="display:">
<table>
<tr><td>Account Number: xxxxxxx</td></tr>
<tr><td>Sort Code: xxxxxxx</td></tr>
<tr><td>Branch Name & Address: xxxxxxx</td></tr>
</table>
</div>
</td>
</tr>
<tr><td><input type="radio" name="payment_type" value="credit_card" ><span>Credit Card</span></td></tr>

<tr>
<td>
<!-- your regular form follows -->
<table width=518 border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<h1>Paypal Credit Card Payment</h1>
  <tr bgcolor="#E5E5E5">
    <td height="22" colspan="3" align="left" valign="middle"><strong>&nbsp;Billing Information (required)</strong></td>
  </tr>
  <tr>
    <td height="22" width="180" align="right" valign="middle">First Name:</td>
    <td colspan="2" align="left"><input name="firstName" id="firstName" type="text" size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Last Name:</td>
    <td colspan="2" align="left"><input name="lastName" id="lastName" type="text" size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Company (optional):</td>
    <td colspan="2" align="left"><input name="company" id="company" type="text" size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Street Address:</td>
    <td colspan="2" align="left"><input name="address1" id="address1" type="text" value="" size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Street Address (2):</td>
    <td colspan="2" align="left"><input name="address2" id="address2" type="text" value="" size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">City:</td>
    <td colspan="2" align="left"><input name="city" id="city" type="text" value="" size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">State/Province:</td>
    <td colspan="2" align="left"><input name="state" id="state" type="text" value="" size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Zip/Postal Code:</td>
    <td colspan="2" align="left"><input name="zip" id="zip" type="text" value="" size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Country:</td>
    <td colspan="2" align="left"><input name="country" id="country" type="text" value="" size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Phone:</td>
    <td colspan="2" align="left"><input name="phone" id="phone" type="text" value="" size="50"></td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="left" valign="middle">&nbsp;</td>
  </tr>
  <tr bgcolor="#E5E5E5">
    <td height="22" colspan="3" align="left" valign="middle"><strong>&nbsp;Credit Card (required)</strong></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Credit Card Number:</td>
    <td colspan="2" align="left"><input name="CCNo" id="CCNo" type="text" value="" size="19" maxlength="40"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">CVV2:</td>
    <td colspan="2" align="left"><input name="CVV2" id="CVV2" type="text" value="" size="3" maxlength="3"></td>
  </tr>
    <tr>
    <td height="22" align="right" valign="middle">Card Type:</td>
    <td colspan="2" align="left">
      <SELECT NAME="CCType" id="CCType">
        <OPTION VALUE="" SELECTED>--Card Type--
        <OPTION VALUE="amex">American Express
        <OPTION VALUE="discover">Discover
        <OPTION VALUE="master">Master Card
        <OPTION VALUE="visa">VISA
      </SELECT> 
    </td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Expiry Date:</td>
    <td colspan="2" align="left">
      <SELECT NAME="CCExpiresMonth" id="CCExpiresMonth">
        <OPTION VALUE="" SELECTED>--Month--
        <OPTION VALUE="01">January (01)
        <OPTION VALUE="02">February (02)
        <OPTION VALUE="03">March (03)
        <OPTION VALUE="04">April (04)
        <OPTION VALUE="05">May (05)
        <OPTION VALUE="06">June (06)
        <OPTION VALUE="07">July (07)
        <OPTION VALUE="08">August (08)
        <OPTION VALUE="09">September (09)
        <OPTION VALUE="10">October (10)
        <OPTION VALUE="11">November (11)
        <OPTION VALUE="12">December (12)
      </SELECT> /
      <SELECT NAME="CCExpiresYear" id="CCExpiresYear">
        <OPTION VALUE="" SELECTED>--Year--
        <OPTION VALUE="04">2004
        <OPTION VALUE="05">2005
        <OPTION VALUE="06">2006
        <OPTION VALUE="07">2007
        <OPTION VALUE="08">2008
        <OPTION VALUE="09">2009
        <OPTION VALUE="10">2010
        <OPTION VALUE="11">2011
        <OPTION VALUE="12">2012
        <OPTION VALUE="13">2013
        <OPTION VALUE="14">2014
        <OPTION VALUE="15">2015
      </SELECT>
    </td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="left" valign="middle">&nbsp;</td>
  </tr>
  <tr bgcolor="#E5E5E5">
    <td height="22" colspan="3" align="left" valign="middle"><strong>&nbsp;Additional Information</strong></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Contact Email:</td>
    <td colspan="2" align="left"><input name="contactEmail" id="contactEmail" type="text" value="" size="50"></td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="left" valign="middle">&nbsp;</td>
  </tr>
  <tr>
    <td height="22" align="right" valign="top">Special Notes:</td>
    <td colspan="2" align="left"><textarea name="notes" id="notes" cols="45" rows="4"></textarea></td>
  </tr>
</table>
<p><button id="Submit_CC"  value="Send Secure Form &gt;&gt;">Send Secure Form</button></p>
</td>
</tr>
<tr>
<td>
<table width=518 border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<h1>Inikoo Credit Card</h1>
  <tr bgcolor="#E5E5E5">
    <td height="22" colspan="3" align="left" valign="middle"><strong>&nbsp;Billing Information (required)</strong></td>
  </tr>
  <tr>
    <td height="22" width="180" align="right" valign="middle">First Name:</td>
    <td colspan="2" align="left"><input name="firstName" id="firstName" type="text" size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Last Name:</td>
    <td colspan="2" align="left"><input name="lastName" id="lastName" type="text" size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Company (optional):</td>
    <td colspan="2" align="left"><input name="company" id="company" type="text" size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Street Address:</td>
    <td colspan="2" align="left"><input name="address1" id="address1" type="text" value="" size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Street Address (2):</td>
    <td colspan="2" align="left"><input name="address2" id="address2" type="text" value="" size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">City:</td>
    <td colspan="2" align="left"><input name="city" id="city" type="text" value="" size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">State/Province:</td>
    <td colspan="2" align="left"><input name="state" id="state" type="text" value="" size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Zip/Postal Code:</td>
    <td colspan="2" align="left"><input name="zip" id="zip" type="text" value="" size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Country:</td>
    <td colspan="2" align="left"><input name="country" id="country" type="text" value="" size="50"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Phone:</td>
    <td colspan="2" align="left"><input name="phone" id="phone" type="text" value="" size="50"></td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="left" valign="middle">&nbsp;</td>
  </tr>
  <tr bgcolor="#E5E5E5">
    <td height="22" colspan="3" align="left" valign="middle"><strong>&nbsp;Credit Card (required)</strong></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Credit Card Number:</td>
    <td colspan="2" align="left"><input name="CCNo" id="CCNo" type="text" value="" size="19" maxlength="40"></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">CVV2:</td>
    <td colspan="2" align="left"><input name="CVV2" id="CVV2" type="text" value="" size="3" maxlength="3"></td>
  </tr>
    <tr>
    <td height="22" align="right" valign="middle">Card Type:</td>
    <td colspan="2" align="left">
      <SELECT NAME="CCType" id="CCType">
        <OPTION VALUE="" SELECTED>--Card Type--
        <OPTION VALUE="amex">American Express
        <OPTION VALUE="discover">Discover
        <OPTION VALUE="master">Master Card
        <OPTION VALUE="visa">VISA
      </SELECT> 
    </td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Expiry Date:</td>
    <td colspan="2" align="left">
      <SELECT NAME="CCExpiresMonth" id="CCExpiresMonth">
        <OPTION VALUE="" SELECTED>--Month--
        <OPTION VALUE="01">January (01)
        <OPTION VALUE="02">February (02)
        <OPTION VALUE="03">March (03)
        <OPTION VALUE="04">April (04)
        <OPTION VALUE="05">May (05)
        <OPTION VALUE="06">June (06)
        <OPTION VALUE="07">July (07)
        <OPTION VALUE="08">August (08)
        <OPTION VALUE="09">September (09)
        <OPTION VALUE="10">October (10)
        <OPTION VALUE="11">November (11)
        <OPTION VALUE="12">December (12)
      </SELECT> /
      <SELECT NAME="CCExpiresYear" id="CCExpiresYear">
        <OPTION VALUE="" SELECTED>--Year--
        <OPTION VALUE="04">2004
        <OPTION VALUE="05">2005
        <OPTION VALUE="06">2006
        <OPTION VALUE="07">2007
        <OPTION VALUE="08">2008
        <OPTION VALUE="09">2009
        <OPTION VALUE="10">2010
        <OPTION VALUE="11">2011
        <OPTION VALUE="12">2012
        <OPTION VALUE="13">2013
        <OPTION VALUE="14">2014
        <OPTION VALUE="15">2015
      </SELECT>
    </td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="left" valign="middle">&nbsp;</td>
  </tr>
  <tr bgcolor="#E5E5E5">
    <td height="22" colspan="3" align="left" valign="middle"><strong>&nbsp;Additional Information</strong></td>
  </tr>
  <tr>
    <td height="22" align="right" valign="middle">Contact Email:</td>
    <td colspan="2" align="left"><input name="contactEmail" id="contactEmail" type="text" value="" size="50"></td>
  </tr>
  <tr>
    <td height="22" colspan="3" align="left" valign="middle">&nbsp;</td>
  </tr>
  <tr>
    <td height="22" align="right" valign="top">Special Notes:</td>
    <td colspan="2" align="left"><textarea name="notes" id="notes" cols="45" rows="4"></textarea></td>
  </tr>
</table>
<p><button id="Save_CC"  value="Send Secure Form &gt;&gt;">Save CC Details</button></p>
</td>
</tr>


</table>
</div>
<button id="payment_option">Test Btn</button>










</div>



<div>
{include file='footer.tpl'}