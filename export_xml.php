<?php 
    /* here we must specify the version of XML : i.e: 1.0 */ 
    $xml = new DomDocument('1.0'); 
    $xml->load('products.xml'); 

?> 
<html> 
    <head> 
        <title>XML Library</title> 
    </head> 
    <body>
<table border=1 cellpadding=5 cellspacing=0><tr><td>Name</td><td>Code</td><td>State</td><td>Web</td></tr> 
        <?php foreach($xml->getElementsBytagName('product') as $product): 
            /* find the title */ 
            $title = $product->getElementsByTagName('title')->item(0)->firstChild->nodeValue; 

            /* find the author - for simplicity we assume there is only one */ 
             $code = $product->getElementsByTagName('code')->item(0)->firstChild->nodeValue; 
	     $state = $product->getElementsByTagName('state')->item(0)->firstChild->nodeValue; 
	     $web = $product->getElementsByTagName('web')->item(0)->firstChild->nodeValue; 
            ?> 
             
        <div>
<tr style="font-size:12px;font-weight:none;"> 
            <td width="220px"><?php echo($title) ?> </td>
            <td width="100px"><?php echo($code) ?></td>
	    <td width="150px"> <?php echo($state) ?></td> 
            <td> <?php echo($web) ?></td>  
            </tr>
        </div>     
        <?php endforeach; ?> 
</table>
</html> 
