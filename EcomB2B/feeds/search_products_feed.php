<?php


chdir('../');
require_once 'utils/sentry.php';
require_once 'keyring/dns.php';
require_once 'keyring/au_deploy_conf.php';
require_once 'keyring/key.php';
require_once 'utils/i18n.php';
require_once 'utils/general_functions.php';
require_once 'utils/object_functions.php';
require_once "class.Account.php";


$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_PORT);

$db = new PDO(
    "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


require_once 'utils/object_functions.php';
$website=get_object('Website',$website_key);

$store=get_object('Store',$website->get('Store Key'));


$sql="select `Webpage Scope Key`,`Webpage Code` from `Page Store Dimension`  where `Webpage State`='Online' and `Webpage Scope`='Product'  and `Webpage Website Key`=? ";
$stmt = $db->prepare($sql);
$stmt->execute(
    [
        $website->id
    ]
);

$payment_account_key_key = false;
$products=[];


while ($row = $stmt->fetch()) {


    $product=get_object('product',$row['Webpage Scope Key']);

    if($product->id) {

        $products[] = [
            'title'=>$product->get('Product Name'),
            'identity' => $row['Webpage Scope Key'],
            'web_url'=>$website->get('Website URL').'/'.strtolower($row['Webpage Code']),
            'availability'=>$product->get('Product Availability')>1?1:0,
            'price'=>$product->get('Webpage Price'),
            'image_link_s'=>'https://'.$website->get('Website URL').'/wi.php?id='.$product->get('Product Main Image Key').'&s=100x100',
            'image_link_m'=>'https://'.$website->get('Website URL').'/wi.php?id='.$product->get('Product Main Image Key').'&s=200x200',
            'image_link_l'=>'https://'.$website->get('Website URL').'/wi.php?id='.$product->get('Product Main Image Key').'&s=600x600',
            'description'=>$product->get('Product Name'),
            'code'=>$product->get('Product Code'),
            'ean'=>$product->get('Product Barcode Number'),
            'introduced_at'=>$product->get('Product Valid From'),

        ];
    }
}

$xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
array_to_xml($products,$xml_data);
print $xml_data->asXML();



function array_to_xml( $data, &$xml_data ) {
    foreach( $data as $key => $value ) {
        if( is_array($value) ) {
            if( is_numeric($key) ){
                $key = 'item';
            }
            $subnode = $xml_data->addChild($key);
            array_to_xml($value, $subnode);
        } else {
            $xml_data->addChild("$key",htmlspecialchars("$value"));
        }
    }
}