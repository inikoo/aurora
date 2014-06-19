<?php

require_once('common.php');
require_once('class.Store.php');

require_once('class.Invoice.php');





$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if (!$id) {
    exit;
}
$invoice=new Invoice($id);
if (!$invoice->id) {
    exit;
}
//print_r($invoice);
$store=new Store($invoice->data['Invoice Store Key']);
$customer=new Customer($invoice->data['Invoice Customer Key']);


putenv('LC_ALL='.$store->data['Store Locale'].'.UTF-8');
setlocale(LC_ALL,$store->data['Store Locale'].'.UTF-8');
bindtextdomain("inikoo", "./locales");
textdomain("inikoo");



include("external_libs/mpdf/mpdf.php");

$mpdf=new mPDF('win-1252','A4','','',20,15,38,25,10,10);

$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetProtection(array('print'));
$mpdf->SetTitle(_('Invoice').' '.$invoice->data['Invoice Public ID']);
$mpdf->SetAuthor($store->data['Store Name']);
$mpdf->SetWatermarkText("PAID");
$mpdf->showWatermarkText = true;
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->watermarkTextAlpha = 0.03;
//$mpdf->SetDisplayMode('fullpage');
//$mpdf->SetJS('this.print();');    // set when we want to print....

$html = '
<html>
<head>
<style>
body {font-family: sans-serif;
    font-size: 10pt;
}
p {    margin: 0pt;
}
td { vertical-align: top; }
.items td {
    border-left: 0.1mm solid #000000;
    border-right: 0.1mm solid #000000;
    border-bottom: 0.1mm solid #cfcfcf;
}
table thead td { background-color: #EEEEEE;
    text-align: center;
    border: 0.1mm solid #000000;
}
.items td.blanktotal {
    background-color: #FFFFFF;
    border: 0mm none #000000;
    border-top: 0.1mm solid #000000;
    border-right: 0.1mm solid #000000;
}
.items td.totals {
    text-align: right;
    border: 0.1mm solid #000000;
}
div.inline { float:left; }
.clearBoth { clear:both; }

</style>
</head>
<body>

<!--mpdf
<htmlpageheader name="myheader">
<table width="100%"><tr>
<td width="10%" style="color:#000;">
</td>

<td width="50%" style="color:#000;"><span style="font-weight: bold; font-size: 12pt;">Ancient Wisdom Marketing Ltd </span><br />Block B, Parkwood Business Park <br /> Parkwood Road <br/>Sheffield<br />S3 8AL<br />UK<span style="font-size: 15pt;"></td>
<td width="40%" style="text-align: right;">Invoice No.<br /><span style="font-weight: bold; font-size: 12pt;">0012345</span></td>
</tr></table>
</htmlpageheader>

<htmlpagefooter name="myfooter">  

<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
</div>
<table width="100%"><tr>
<td width="33%" style="color:#000;text-align: left;"><small>Ancient Wisdom Marketing Ltd<br>VAT NO:764 2985 89<br>Co Reg No:4108870</small></td>

<td width="33%" style="color:#000;text-align: center">Page {PAGENO} of {nbpg}</td>
<td width="34%" style="text-align: right;"><small>Tell:[+49] (0)831 2531 986<br>Fax:[+44] (0)114 2706571</small></td>
</tr></table>



</div> 



</htmlpagefooter>

<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->

     <div  style="position:absolute; left:675px; top:80px;display:none ">
       </div>

   
 <div  style="position:absolute; left:80px; top:140px;display:nonex ">
       <div style="text-align: left; font-size: 18pt;">INVOICE</div>
       </div>

<table width="100%" style="font-family: sans-serif;" cellpadding="0" border=1>
<tr>
<td width="50%" style="border: 0mm solid #888888;">

<div style="text-align: left">Invoice Date:<b> '.date('jS F Y').'</b></div>
<div style="text-align: left">Order Date: <b>'.date('jS F Y').'</b></div>
</td>

<td width="50%" style="border: 0mm solid #888888;text-align: right">

<div style="text-align: right">'._('No. Parcels').':<b> 6</b></div>
<div style="text-align: right">'._('Weight').': <b>2360 KG</b></div>
<div style="text-align: right">'._('Payment Status').':<b> '.$invoice->get('Payment State').'</b></b></div>
</td>

</tr>
</table>

<table width="100%" style="font-family: sans-serif;" cellpadding="10">
<tr>
<td width="45%" style="border: 0.1mm solid #888888;"><span style="font-size: 7pt; color: #555555; font-family: sans-serif;">'._('Billing address').':</span><br /><br />345 Anotherstreet<br />Little Village<br />Their City<br />CB22 6SO<br />Somewhere in this wild world<br />Near my house</td>
<td width="10%">&nbsp;</td>
<td width="45%" style="border: 0.1mm solid #888888;"><span style="font-size: 7pt; color: #555555; font-family: sans-serif;">'._('Delivery address').':</span><br /><br />345 Anotherstreet<br />Little Village<br />Their City<br />CB22 6SO</td>
</tr>
</table>

<br>
<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
<thead>
<tr>
<td width="8%">'._('Code').'</td>
<td width="60%">DESCRIPTION</td>
<td width="8%">UNIT PRICE</td>
<td width="8%">QUANTITY</td> 
<td width="8%">DISCOUNT</td>
<td width="8%">AMOUNT</td>
</tr>
</thead>
<tbody>
<!-- ITEMS HERE -->

';
  $foundRows = 29;
 for($r=0;$r<$foundRows;$r++){
 
$html = $html.'<tr>
<td align="left">JJB-03</td>
<td>Jumbo Bath Bombs</td>
<td align="right">&pound;2.56</td>
<td align="center">10</td>
<td align="right">&pound;1.22</td>
<td align="right">&pound;25.60</td>
</tr>
<tr>';



}



$html = $html.'<!-- END ITEMS HERE -->
<tr>
<td class="blanktotal" colspan="4" rowspan="6"></td>
<td class="totals">Artikel (netto)</td>
<td class="totals">&pound;1825.60</td>
</tr>
<tr>
<td class="totals">Gebuhr fur kl. Best</td>
<td class="totals">&pound;18.25</td>
</tr>
<tr>
<td class="totals">Versand</td>
<td class="totals">Angef</td>
</tr>
<tr>
<td class="totals">netto</td>
<td class="totals">&pound;42.56</td>
</tr> 






<tr>
<td class="totals">VAT 20%</td>
<td class="totals">&pound;42.56</td>
</tr>
<tr>
<td class="totals">Gesamtbetrag</td>
<td class="totals">&pound;1882.56</td>
</tr>


</tbody>
</table>
<br>
<div style="text-align: center; font-style: italic;"><b>Vielen Dank fur Ihre Bestellung bei AW-Geschenke. </b></div>
<br>
<div style="text-align: center; font-style: italic;">Payment terms per www.ancientwisdom.biz/termsandconditions</div>

</body>
</html>
';

$mpdf->WriteHTML($html);

$mpdf->WriteHTML('<pagebreak resetpagenum="1" pagenumstyle="1" suppress="off" />');

$mpdf->WriteHTML($html);


$mpdf->Output(); exit;




exit;


// <small>&#9742;</small></span> [+49] (0)831 2531 986
?> 