<?php


require_once 'common.php';


$sql = sprintf('select  `Product ID`  from `Order Transaction Fact`    where `OTF Category Department Key` is null  group by `Product ID` ');


if ($result2 = $db->query($sql)) {
    foreach ($result2 as $row2) {



        $product = get_object('product', $row2['Product ID']);

        if ($product->get('Product Department Category Key') > 0) {
            $sql = sprintf('update `Order Transaction Fact` set `OTF Category Department Key`=%d   where `Product ID`=%d  and  `OTF Category Department Key` is null ',  $product->get('Product Department Category Key')  , $row2['Product ID']  );

            $db->exec($sql);

        } else {
            print $product->get('Code')." no dept cat \n";


        }


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


$sql = sprintf('select  `Product ID`  from `Order Transaction Fact`    where `OTF Category Family Key` is null  group by `Product ID` ');


if ($result2 = $db->query($sql)) {
    foreach ($result2 as $row2) {

        $product = get_object('product', $row2['Product ID']);

        if ($product->get('Product Family Category Key') > 0) {
            $sql = sprintf('update `Order Transaction Fact` set `OTF Category Family Key`=%d   where `Product ID`=%d  and  `OTF Category Family Key` is null ', $product->get('Product Family Category Key') , $row2['Product ID']);
            $db->exec($sql);

        }


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}

