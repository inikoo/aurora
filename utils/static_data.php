<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 April 2016 at 14:02:21 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 20156 Inikoo

 Version 3

*/

function get_currencies($db) {

    $data = array();
    $sql  = "SELECT `Currency Name`,`Currency Code`,`Currency Symbol`,`Currency Country 2 Alpha Code` FROM kbase.`Currency Dimension` WHERE `Currency Status`='Active' ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $data[] = array(
            'name' => _($row['Currency Name']).' ('.$row['Currency Code'].')',
            'iso2' => strtolower($row['Currency Country 2 Alpha Code']),
            'code' => $row['Currency Code'],

        );
    }


    usort($data, "cmp");

    $formatted_data = '[ ';
    foreach ($data as $key => $value) {
        $formatted_data .= sprintf(
            '{name:"%s",iso2:"%s",code:"%s"},', $value['name'], $value['iso2'], $value['code']
        );
    }
    $formatted_data = preg_replace('/\,$/', '', $formatted_data);
    $formatted_data .= ']';

    return $formatted_data;

}

function get_country_code_from_currency($db, $currency) {
    $sql  = "SELECT `Currency Country 2 Alpha Code` FROM kbase.`Currency Dimension` WHERE `Currency Code`=? ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $currency
        )
    );
    if ($row = $stmt->fetch()) {
        return $row['Currency Country 2 Alpha Code'];
    } else {
        return 'XX';
    }


}


function get_countries($db) {

    $data = array();
    $sql  = sprintf(
        "SELECT `Country Name`,`Country Local Name`,`Country Code`,`Country Currency Code`,`Country 2 Alpha Code` FROM kbase.`Country Dimension` WHERE `Country Display Address Field`='Yes' "
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $name   = _($row['Country Name']);
            $data[] = array(
                'name'     => (($name == $row['Country Local Name'] or $row['Country Local Name'] == '') ? $name : $name.' ('.$row['Country Local Name'].')'),
                'iso2'     => strtolower($row['Country 2 Alpha Code']),
                'code'     => $row['Country Code'],
                'currency' => $row['Country Currency Code'],
            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    usort($data, "cmp");

    $formatted_data = '[ ';
    foreach ($data as $key => $value) {
        $formatted_data .= sprintf(
            '{name:"%s",iso2:"%s",code:"%s"},', $value['name'], $value['iso2'], $value['code']
        );
    }
    $formatted_data = preg_replace('/\,$/', '', $formatted_data);

    $formatted_data .= ']';

    return $formatted_data;

}


function cmp($a, $b) {
    if ($a['name'] == $b['name']) {
        return 0;
    }

    return ($a['name'] < $b['name']) ? -1 : 1;
}



