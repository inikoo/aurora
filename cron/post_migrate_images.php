<?php


require_once __DIR__.'/cron_common.php';

include_once 'utils/image_functions.php';



$sql  = sprintf('select  `Website Key`  from `Website Dimension`    ');
$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {

    $website = get_object('Website', $row['Website Key']);

    $website->fast_update(
        array(
            'Website Mobile Style' => preg_replace('/image_root/', 'wi', $website->data['Website Mobile Style'])
        )
    );



    $mobile_style=json_decode($website->data['Website Mobile Style'], true);


    if($mobile_style!=''){

        foreach($mobile_style  as $_key=>$_value){
            if($_value[1]=='background-image'){
                if(preg_match('/^\/wi.php/',$_value[2],$match)){
                    $mobile_style[$_key][2]='url('.$_value[2].')';
                }


            }

        }


        $website->fast_update(
            array(
                'Website Mobile Style' => json_encode($mobile_style)
            )
        );
    }






    $settings = $website->settings;
    // $style = $website->style;


    $tmp = array();
    foreach ($website->style as $style_data) {
        $tmp[trim($style_data[0]).'|'.trim($style_data[1])] = $style_data[2];

    }
    $style = array();
    foreach ($tmp as $_key => $_value) {
        $_tmp    = preg_split('/\|/', $_key);
        $style[] = array(
            $_tmp[0],
            $_tmp[1],
            $_value
        );
    }

    $height = 60;
    $width  = 80;
    foreach ($style as $style_data) {
        if ($style_data[0] == '#header_logo' and $style_data[1] == 'flex-basis') {
            $width = floatval($style_data[2]);
        }
        if ($style_data[0] == '#top_header' and $style_data[1] == 'height') {
            $height = floatval($style_data[2]);
        }
    }


    if (isset($settings['logo_website'])) {
        $settings['logo_website'] = preg_replace('/image_root/', 'wi', $settings['logo_website']);
        if (preg_match('/id=(\d+)/', $settings['logo_website'], $matches)) {
            $settings['logo_website_website'] = 'wi.php?id='.$matches[1].'&s='.get_image_size($matches[1], $width * 2, $height * 2, 'fit_highest');
        }

    } else {
        $settings['logo_website']         = '';
        $settings['logo_website_website'] = '';
    }

    if (isset($settings['favicon'])) {
        $settings['favicon'] = preg_replace('/image_root/', 'wi', $settings['favicon']);
        if (preg_match('/id=(\d+)/', $settings['favicon'], $matches)) {
            $settings['favicon_website'] = 'wi.php?id='.$matches[1].'&s=32x32';
        }
    } else {
        $settings['favicon']         = '';
        $settings['favicon_website'] = '';
    }

    //    print_r($settings);

    $website->fast_update(
        array(
            'Website Settings' => json_encode($settings)
        )
    );
}


$sql = sprintf('SELECT * FROM `Website Header Dimension` ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $sql = sprintf(
            'update  `Website Header Dimension` set  `Website Header Data`=%s  where `Website Header Key`=%d', prepare_mysql(preg_replace('/image_root/', 'wi', $row['Website Header Data'])), $row['Website Header Key']
        );
        $db->exec($sql);
    }
}

$sql = sprintf('SELECT * FROM `Website Footer Dimension`');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $sql = sprintf(
            'update  `Website Footer Dimension` set  `Website Footer Data`=%s  where `Website Footer Key`=%d', prepare_mysql(preg_replace('/image_root/', 'wi', $row['Website Footer Data'])), $row['Website Footer Key']
        );
        $db->exec($sql);
    }
}


$sql = sprintf('SELECT * FROM `Page Store Dimension`');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $sql = sprintf(
            'update  `Page Store Dimension` set  `Page Store Content Data`=%s  where `Page Key`=%d', prepare_mysql(preg_replace('/image_root/', 'wi', $row['Page Store Content Data'])), $row['Page Key']
        );
        $db->exec($sql);
    }
}
