<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 September 2017 at 14:15:24 GMT+8, Kuala Lumpur, Malaysoa
 Copyright (c) 2016, Inikoo

 Version 3

*/

$_handle = fopen("sk.csv", "r");
$handle  = fopen("sk.csv", "r");

chdir('../');

require_once 'keyring/dns.php';
require_once 'keyring/key.php';
require_once 'utils/i18n.php';
require_once 'utils/general_functions.php';
require_once "class.Account.php";


$mem = new Memcached();
$mem->addServer($memcache_ip, 11211);


$dns_db    = 'sk';
$dns_db_aw = 'dw';


$db = new PDO(
    "mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$db_aw = new PDO(
    "mysql:host=$dns_host;dbname=$dns_db_aw;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
);
$db_aw->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$account = new Account($db);


date_default_timezone_set($account->data['Account Timezone']);


require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';
require_once 'class.User.php';

require_once 'class.Site.php';
require_once 'class.Page.php';
require_once 'class.Website.php';
require_once 'class.WebsiteNode.php';
require_once 'class.Webpage.php';
require_once 'class.Product.php';
require_once 'class.Store.php';
require_once 'class.Part.php';
require_once 'class.Agent.php';
require_once 'class.Image.php';
require_once 'class.Attachment.php';

require_once 'class.Public_Product.php';
require_once 'class.Category.php';
require_once 'class.Webpage_Type.php';
include_once 'utils/currency_functions.php';
require_once 'conf/footer_data.php';
require_once 'conf/header_data.php';


$_user = new User('handle', 'raul', 'Contractor');

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => $_user->id,
    'Date'         => gmdate('Y-m-d H:i:s')
);


$sql = sprintf(
    'SELECT * FROM `Attachment Bridge` WHERE `Subject`="Part"   '
);

if ($result = $db_aw->query($sql)) {
    foreach ($result as $row) {


        $attachment_aw = new Attachment('id', $row['Attachment Key'], false, $db_aw);
        if ($attachment_aw->id) {

            $attachment_path = 'cron/tmp/'.$row['Attachment File Original Name'];


            $editor['Date'] = gmdate('Y-m-d H:i:s');


            $part_aw = new Part('sku', $row['Subject Key'], false, $db_aw);


            $part = new Part('reference', $part_aw->get('Part Reference'), false, $db);

            if ($part->id) {

                print $part->get('Reference')."\n";

                // print_r($row);
                $attachment_aw->save_to_file($attachment_path);

                $part->editor = $editor;


                $attachment = $part->add_attachment(
                    array(


                        'Filename'                      => $attachment_path,
                        'Attachment File Original Name' => $row['Attachment File Original Name'],
                        'Attachment Caption'            => $row['Attachment Caption'],
                        'Attachment Subject Type'       => $row['Attachment Subject Type'],
                        'Attachment Public'             => $row['Attachment Public']

                    )
                );
                unlink($attachment_path);



            }


        }

    }
} else {
    print_r($error_info = $db_aw->errorInfo());
    print "$sql\n";
    exit;
}


?>