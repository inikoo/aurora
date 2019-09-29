<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29-09-2019 13:52:34 MYT, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/


function get_normalized_timezones() {


    $timezones = DateTimeZone::listIdentifiers();


    $_timezones = array_flip($timezones);


    foreach (_normalized_timezones() as $key => $value) {
        unset($_timezones[$key]);
    }


    return array_flip($_timezones);
}


function get_normalized_timezones_formatted_label($timezone) {


    $timezone_label = array(
        "Etc/GMT+12"        => "International Date Line West",
        "Pacific/Pago_Pago" => "(SST) Samoa; Midway Island; Niue",
        "Pacific/Honolulu"  => "(HST) Hawaii; Cook Islands; Tahiti",
        'Pacific/Marquesas' => "(MART) Taiohae; Marquesas Islands; French Polynesia",
        "America/Adak"      => "(HTS/HDT) Adak, Alaska, USA",
        'Pacific/Gambier'   => "(GAMT) Gambier Islands, French Polynesia",
        "Pacific/Pitcairn"  => "(PST) Pitcairn Islands",

        "America/Anchorage"  => "(AKST/AKDT) Alaska, USA",
        "America/Hermosillo" => "(MST) Sonora, Mexico; Arizona USA",


        "America/Los_Angeles" => "(PST/PDT) Pacific time (US and Canada); Tijuana",

        "America/Costa_Rica" => "(CST) Central America",
        "America/Chihuahua"  => "(MST/MDT) Chihuahua, La Paz, Mazatlan",


        "America/Regina" => "Saskatchewan, Canada",


        "America/Denver" => "(MST/MDT) Mountain Time (US and Canada)",
        "America/Regina" => "(CST) Saskatchewan, Canada",


        "Pacific/Galapagos" => "(GALT) Galapagos Islands, Ecuador",
        "Pacific/Easter"    => "(EASST/EAST) Easter Island, Chile",

        "America/Cancun" => "(EST) Cancún Mexico; Jamaica, Cayman",


        "America/Chicago" => "(CST/CDT) Central Time (US and Canada)",

        "America/Mexico_City" => "(CST/CDT) Guadalajara, Mexico City, Monterrey",

        "America/Anguilla" => "(AST) Puerto Rico; Caribbean",
        "America/Asuncion" => "(PYST/PYT) Asuncion, Paraguay",

        "America/Boa_Vista" => "(AMT) Amazon, Brazil",
        "America/Havana"    => "(CST/CDT) Havana, Cuba",


        "America/Detroit" => "(EST/EDT) Eastern Time (US and Canada)",


        "America/Bogota"                 => "Bogota, Lima, Quito",
        "America/Glace_Bay"              => "(AST/ADT)Atlantic Time (Canada)",
        "America/Caracas"                => "Caracas, La Paz",
        "America/Santiago"               => "(CLST/CLT) Santiago",
        "America/Sao_Paulo"              => "Brasilia",
        "America/Argentina/Buenos_Aires" => "(ART) Buenos Aires, Argentina; Georgetown",
        "America/Godthab"                => "Greenland",
        "Etc/GMT+2"                      => "Mid-Atlantic",
        "Atlantic/Azores"                => "Azores",
        "Atlantic/Cape_Verde"            => "Cape Verde Islands",
        "Europe/London"                  => "London, Edinburgh",

        "Africa/Casablanca"  => "Casablanca, Monrovia",
        "Atlantic/Canary"    => "Canary Islands",
        "Europe/Belgrade"    => "Belgrade, Bratislava, Budapest, Ljubljana, Prague",
        "Europe/Sarajevo"    => "Sarajevo, Skopje, Warsaw, Zagreb",
        "Europe/Brussels"    => "Brussels, Copenhagen, Madrid, Paris",
        "Europe/Amsterdam"   => "Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna",
        "Africa/Algiers"     => "West Central Africa",
        "Europe/Bucharest"   => "Bucharest",
        "Africa/Cairo"       => "Cairo",
        "Europe/Helsinki"    => "Helsinki, Kiev, Riga, Sofia, Tallinn, Vilnius",
        "Europe/Athens"      => "Athens, Istanbul, Minsk",
        "Asia/Jerusalem"     => "Jerusalem",
        "Africa/Harare"      => "Harare, Pretoria",
        "Europe/Moscow"      => "Moscow, St. Petersburg, Volgograd",
        "Asia/Kuwait"        => "Kuwait, Riyadh",
        "Africa/Nairobi"     => "Nairobi",
        "Asia/Baghdad"       => "Baghdad",
        "Asia/Tehran"        => "Tehran",
        "Asia/Dubai"         => "Abu Dhabi, Muscat",
        "Asia/Baku"          => "Baku, Tbilisi, Yerevan",
        "Asia/Kabul"         => "Kabul",
        "Asia/Yekaterinburg" => "Ekaterinburg",
        "Asia/Karachi"       => "Islamabad, Karachi, Tashkent",
        "Asia/Kolkata"       => "Chennai, Kolkata, Mumbai, New Delhi",
        "Asia/Kathmandu"     => "Kathmandu",
        "Asia/Dhaka"         => "Astana, Dhaka",
        "Asia/Colombo"       => "Sri Jayawardenepura",
        "Asia/Almaty"        => "Almaty, Novosibirsk",
        "Asia/Rangoon"       => "Yangon Rangoon",
        "Asia/Bangkok"       => "Bangkok, Hanoi, Jakarta",
        "Asia/Krasnoyarsk"   => "Krasnoyarsk",
        "Asia/Shanghai"      => "Beijing, Chongqing, Hong Kong SAR, Urumqi",
        "Asia/Kuala_Lumpur"  => "Kuala Lumpur, Singapore",
        "Asia/Taipei"        => "Taipei",
        "Australia/Perth"    => "Perth",
        "Asia/Irkutsk"       => "Irkutsk, Ulaanbaatar",
        "Asia/Seoul"         => "Seoul",
        "Asia/Tokyo"         => "Osaka, Sapporo, Tokyo",
        "Asia/Yakutsk"       => "Yakutsk",
        "Australia/Darwin"   => "Darwin",
        "Australia/Adelaide" => "Adelaide",
        "Australia/Sydney"   => "Canberra, Melbourne, Sydney",
        "Australia/Brisbane" => "Brisbane",
        "Australia/Hobart"   => "Hobart",
        "Asia/Vladivostok"   => "Vladivostok",
        "Pacific/Guam"       => "Guam, Port Moresby",
        "Asia/Magadan"       => "Magadan, Solomon Islands, New Caledonia",
        "Pacific/Fiji"       => "Fiji Islands, Kamchatka, Marshall Islands",
        "Pacific/Auckland"   => "Auckland, Wellington",
        "Pacific/Tongatapu"  => "Nuku'alofa"
    );


    if (isset(_normalized_timezones()[$timezone])) {
        $timezone = _normalized_timezones()[$timezone];
    }


    if (isset($timezone_label[$timezone])) {
        return $timezone_label[$timezone];
    } else {
        return $timezone;

    }


}

function _normalized_timezones() {
    return array(
        'Pacific/Niue'   => 'Pacific/Pago_Pago',
        'Pacific/Midway' => 'Pacific/Pago_Pago',

        'Pacific/Rarotonga' => 'Pacific/Honolulu',
        'Pacific/Tahiti'    => 'Pacific/Honolulu',

        'America/Juneau'     => 'Pacific/Anchorage',
        'America/Metlakatla' => 'Pacific/Anchorage',
        'America/Nome'       => 'Pacific/Anchorage',
        'America/Sitka'      => 'Pacific/Anchorage',
        'America/Yakutat'    => 'Pacific/Anchorage',

        'America/Dawson_Creek' => 'America/Hermosillo',
        'America/Fort_Nelson'  => 'America/Hermosillo',
        'America/Creston'      => 'America/Hermosillo',
        'America/Phoenix'      => 'America/Hermosillo',

        'America/Tijuana'    => 'America/Los_Angeles',
        'America/Dawson'     => 'America/Los_Angeles',
        'America/Vancouver'  => 'America/Los_Angeles',
        'America/Whitehorse' => 'America/Los_Angeles',

        'America/Belize'      => 'America/Costa_Rica',
        'America/El_Salvador' => 'America/Costa_Rica',
        'America/Guatemala'   => 'America/Costa_Rica',
        'America/Managua'     => 'America/Costa_Rica',
        'America/Tegucigalpa' => 'America/Costa_Rica',


        'America/Swift_Current' => 'America/Regina',

        'America/Mazatlan' => 'America/Chihuahua',

        'America/Yellowknife'   => 'America/Denver',
        'America/Cambridge_Bay' => 'America/Denver',
        'America/Yellowknife'   => 'America/Denver',
        'America/Edmonton'      => 'America/Denver',
        'America/Boise'         => 'America/Denver',
        'America/Ojinaga'       => 'America/Denver',
        'America/Inuvik'        => 'America/Denver',


        'America/Atikokan' => 'America/Atikokan',

        'America/Bahia_Banderas' => 'America/Mexico_City',
        'America/Monterrey'      => 'America/Mexico_City',
        'America/Merida'         => 'America/Mexico_City',

        'America/Guayaquil'  => 'America/Bogota',
        'America/Lima'       => 'America/Bogota',
        'America/Eirunepe'   => 'America/Bogota',
        'America/Panama'     => 'America/Bogota',
        'America/Rio_Branco' => 'America/Bogota',

        'America/Indiana/Tell_City'      => 'America/Chicago',
        'America/Indiana/Knox'           => 'America/Chicago',
        'America/Winnipeg'               => 'America/Chicago',
        'America/Matamoros'              => 'America/Chicago',
        'America/Menominee'              => 'America/Chicago',
        'America/North_Dakota/Beulah'    => 'America/Chicago',
        'America/North_Dakota/Center'    => 'America/Chicago',
        'America/North_Dakota/New_Salem' => 'America/Chicago',

        'America/Rainy_River'  => 'America/Chicago',
        'America/Rankin_Inlet' => 'America/Chicago',
        'America/Resolute'     => 'America/Chicago',
        'America/Winnipeg'     => 'America/Chicago',


        'America/Campo_Grande' => 'America/Boa_Vista',
        'America/Cuiaba'       => 'America/Boa_Vista',
        'America/Porto_Velho'  => 'America/Boa_Vista',
        'America/Manaus'       => 'America/Boa_Vista',


        'America/New_York'             => 'America/Detroit',
        'America/Nipigon'              => 'America/Detroit',
        'America/Pangnirtung'          => 'America/Detroit',
        'America/New_York'             => 'America/Detroit',
        'America/New_York'             => 'America/Detroit',
        'America/Indiana/Marengo'      => 'America/Detroit',
        'America/Indiana/Petersburg'   => 'America/Detroit',
        'America/Indiana/Vevay'        => 'America/Detroit',
        'America/Indiana/Vincennes'    => 'America/Detroit',
        'America/Indiana/Winamac'      => 'America/Detroit',
        'America/Kentucky/Louisville'  => 'America/Detroit',
        'America/Kentucky/Monticello'  => 'America/Detroit',
        'America/Indiana/Indianapolis' => 'America/Detroit',
        'America/Thunder_Bay'          => 'America/Detroit',
        'America/Toronto'              => 'America/Detroit',
        'America/Thunder_Bay'          => 'America/Detroit',
        'America/Iqaluit'              => 'America/Detroit',
        'America/Port-au-Prince'       => 'America/Detroit',


        'America/Jamaica' => 'America/Cancun',
        'America/Cayman'  => 'America/Cancun',


        'America/Aruba'         => 'America/Anguilla',
        'America/Barbados'      => 'America/Anguilla',
        'America/Blanc-Sablon'  => 'America/Anguilla',
        'America/Antigua'       => 'America/Anguilla',
        'America/Curacao'       => 'America/Anguilla',
        'America/Dominica'      => 'America/Anguilla',
        'America/Grenada'       => 'America/Anguilla',
        'America/Martinique'    => 'America/Anguilla',
        'America/Nassau'        => 'America/Anguilla',
        'America/Puerto_Rico'   => 'America/Anguilla',
        'America/Santo_Domingo' => 'America/Anguilla',
        'America/St_Barthelemy' => 'America/Anguilla',
        'America/Grand_Turk'    => 'America/Anguilla',
        'America/Guadeloupe'    => 'America/Anguilla',
        'America/Guyana'        => 'America/Anguilla',
        'America/Guadeloupe'    => 'America/Anguilla',
        'America/Tortola'       => 'America/Anguilla',
        'America/St_Kitts'      => 'America/Anguilla',
        'America/St_Lucia'      => 'America/Anguilla',
        'America/St_Vincent'    => 'America/Anguilla',
        'America/Port_of_Spain' => 'America/Anguilla',
        'America/St_Vincent'    => 'America/Anguilla',
        'America/St_Thomas'     => 'America/Anguilla',
        'America/St_Vincent'    => 'America/Anguilla',
        'America/Kralendijk'    => 'America/Anguilla',
        'America/Lower_Princes' => 'America/Anguilla',
        'America/Marigot'       => 'America/Anguilla',
        'America/Montserrat'    => 'America/Anguilla',


        'America/La_Paz' => 'America/Caracas',


        'America/Araguaina' => 'America/Sao_Paulo',
        //,BRT
        'America/Bahia'     => 'America/Sao_Paulo',
        'America/Cayenne'   => 'America/Sao_Paulo',


        'America/Halifax'  => 'America/Glace_Bay',
        'America/St_Johns' => 'America/Glace_Bay',

        'America/Argentina/Catamarca'    => 'America/Argentina/Buenos_Aires',
        'America/Argentina/Cordoba'      => 'America/Argentina/Buenos_Aires',
        'America/Argentina/Jujuy'        => 'America/Argentina/Buenos_Aires',
        'America/Argentina/La_Rioja'     => 'America/Argentina/Buenos_Aires',
        'America/Argentina/Mendoza'      => 'America/Argentina/Buenos_Aires',
        'America/Argentina/Salta'        => 'America/Argentina/Buenos_Aires',
        'America/Argentina/San_Luis'     => 'America/Argentina/Buenos_Aires',
        'America/Argentina/San_Juan'     => 'America/Argentina/Buenos_Aires',
        'America/Argentina/Rio_Gallegos' => 'America/Argentina/Buenos_Aires',
        'America/Argentina/Ushuaia'      => 'America/Argentina/Buenos_Aires',
        'America/Argentina/Tucuman'      => 'America/Argentina/Buenos_Aires',


    );
}

function get_timezone_info() {
    return 'UTC'.date('P').' <span class="abbreviation">'.get_timezone_abbreviation().'</span>';
}

function get_timezone_abbreviation($timezone_id = false) {


    if (!$timezone_id) {
        $timezone_id = date_default_timezone_get();
    }

    $dt = new DateTime('now', new DateTimeZone($timezone_id));

    $abbreviation = $dt->format('T');
    if (preg_match('/\d{1,2}$/', $abbreviation)) {
        $abb_list = timezone_abbreviations_list();

        $abb_array = array();
        foreach ($abb_list as $abb_key => $abb_val) {
            foreach ($abb_val as $key => $value) {
                $value['abb'] = $abb_key;
                array_push($abb_array, $value);
            }
        }

        foreach ($abb_array as $key => $value) {
            if ($value['timezone_id'] == $timezone_id) {
                return strtoupper($value['abb']);
            }
        }
        return $abbreviation;
    }else{
        return $abbreviation;
    }


}