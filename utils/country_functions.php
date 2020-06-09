<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 January 2016 at 17:00:00 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

function preferred_countries($country_code) {
    include 'conf/preferred_countries.php';
    if (isset($preferred_countries_data[$country_code])) {
        $preferred_countries = $preferred_countries_data[$country_code];
        array_unshift($preferred_countries, $country_code);

        return $preferred_countries;
    } else {
        return array($country_code);
    }
}


function country_3alpha_to_2alpha($code){

    $data=array ( 'BGD' => 'BD', 'BEL' => 'BE', 'BFA' => 'BF', 'BGR' => 'BG', 'BIH' => 'BA', 'BRB' => 'BB', 'WLF' => 'WF', 'BLM' => 'BL', 'BMU' => 'BM', 'BRN' => 'BN', 'BOL' => 'BO', 'BHR' => 'BH', 'BDI' => 'BI', 'BEN' => 'BJ', 'BTN' => 'BT', 'JAM' => 'JM', 'BVT' => 'BV', 'BWA' => 'BW', 'WSM' => 'WS', 'BES' => 'BQ', 'BRA' => 'BR', 'BHS' => 'BS', 'JEY' => 'JE', 'BLR' => 'BY', 'BLZ' => 'BZ', 'RUS' => 'RU', 'RWA' => 'RW', 'SRB' => 'RS', 'TLS' => 'TL', 'REU' => 'RE', 'TKM' => 'TM', 'TJK' => 'TJ', 'ROU' => 'RO', 'TKL' => 'TK', 'GNB' => 'GW', 'GUM' => 'GU', 'GTM' => 'GT', 'SGS' => 'GS', 'GRC' => 'GR', 'GNQ' => 'GQ', 'GLP' => 'GP', 'JPN' => 'JP', 'GUY' => 'GY', 'GGY' => 'GG', 'GUF' => 'GF', 'GEO' => 'GE', 'GRD' => 'GD', 'GBR' => 'GB', 'GAB' => 'GA', 'SLV' => 'SV', 'GIN' => 'GN', 'GMB' => 'GM', 'GRL' => 'GL', 'GIB' => 'GI', 'GHA' => 'GH', 'OMN' => 'OM', 'TUN' => 'TN', 'JOR' => 'JO', 'HRV' => 'HR', 'HTI' => 'HT', 'HUN' => 'HU', 'HKG' => 'HK', 'HND' => 'HN', 'HMD' => 'HM', 'VEN' => 'VE', 'PRI' => 'PR', 'PSE' => 'PS', 'PLW' => 'PW', 'PRT' => 'PT', 'SJM' => 'SJ', 'PRY' => 'PY', 'IRQ' => 'IQ', 'PAN' => 'PA', 'PYF' => 'PF', 'PNG' => 'PG', 'PER' => 'PE', 'PAK' => 'PK', 'PHL' => 'PH', 'PCN' => 'PN', 'POL' => 'PL', 'SPM' => 'PM', 'ZMB' => 'ZM', 'ESH' => 'EH', 'EST' => 'EE', 'EGY' => 'EG', 'ZAF' => 'ZA', 'ECU' => 'EC', 'ITA' => 'IT', 'VNM' => 'VN', 'SLB' => 'SB', 'ETH' => 'ET', 'SOM' => 'SO', 'ZWE' => 'ZW', 'SAU' => 'SA', 'ESP' => 'ES', 'ERI' => 'ER', 'MNE' => 'ME', 'MDA' => 'MD', 'MDG' => 'MG', 'MAF' => 'MF', 'MAR' => 'MA', 'MCO' => 'MC', 'UZB' => 'UZ', 'MMR' => 'MM', 'MLI' => 'ML', 'MAC' => 'MO', 'MNG' => 'MN', 'MHL' => 'MH', 'MKD' => 'MK', 'MUS' => 'MU', 'MLT' => 'MT', 'MWI' => 'MW', 'MDV' => 'MV', 'MTQ' => 'MQ', 'MNP' => 'MP', 'MSR' => 'MS', 'MRT' => 'MR', 'IMN' => 'IM', 'UGA' => 'UG', 'TZA' => 'TZ', 'MYS' => 'MY', 'MEX' => 'MX', 'ISR' => 'IL', 'FRA' => 'FR', 'IOT' => 'IO', 'SHN' => 'SH', 'FIN' => 'FI', 'FJI' => 'FJ', 'FLK' => 'FK', 'FSM' => 'FM', 'FRO' => 'FO', 'NIC' => 'NI', 'NLD' => 'NL', 'NOR' => 'NO', 'NAM' => 'NA', 'VUT' => 'VU', 'NCL' => 'NC', 'NER' => 'NE', 'NFK' => 'NF', 'NGA' => 'NG', 'NZL' => 'NZ', 'NPL' => 'NP', 'NRU' => 'NR', 'NIU' => 'NU', 'COK' => 'CK', 'XKX' => 'XK', 'CIV' => 'CI', 'CHE' => 'CH', 'COL' => 'CO', 'CHN' => 'CN', 'CMR' => 'CM', 'CHL' => 'CL', 'CCK' => 'CC', 'CAN' => 'CA', 'COG' => 'CG', 'CAF' => 'CF', 'COD' => 'CD', 'CZE' => 'CZ', 'CYP' => 'CY', 'CXR' => 'CX', 'CRI' => 'CR', 'CUW' => 'CW', 'CPV' => 'CV', 'CUB' => 'CU', 'SWZ' => 'SZ', 'SYR' => 'SY', 'SXM' => 'SX', 'KGZ' => 'KG', 'KEN' => 'KE', 'SSD' => 'SS', 'SUR' => 'SR', 'KIR' => 'KI', 'KHM' => 'KH', 'KNA' => 'KN', 'COM' => 'KM', 'STP' => 'ST', 'SVK' => 'SK', 'KOR' => 'KR', 'SVN' => 'SI', 'PRK' => 'KP', 'KWT' => 'KW', 'SEN' => 'SN', 'SMR' => 'SM', 'SLE' => 'SL', 'SYC' => 'SC', 'KAZ' => 'KZ', 'CYM' => 'KY', 'SGP' => 'SG', 'SWE' => 'SE', 'SDN' => 'SD', 'DOM' => 'DO', 'DMA' => 'DM', 'DJI' => 'DJ', 'DNK' => 'DK', 'VGB' => 'VG', 'DEU' => 'DE', 'YEM' => 'YE', 'DZA' => 'DZ', 'USA' => 'US', 'URY' => 'UY', 'MYT' => 'YT', 'UMI' => 'UM', 'LBN' => 'LB', 'LCA' => 'LC', 'LAO' => 'LA', 'TUV' => 'TV', 'TWN' => 'TW', 'TTO' => 'TT', 'TUR' => 'TR', 'LKA' => 'LK', 'LIE' => 'LI', 'LVA' => 'LV', 'TON' => 'TO', 'LTU' => 'LT', 'LUX' => 'LU', 'LBR' => 'LR', 'LSO' => 'LS', 'THA' => 'TH', 'ATF' => 'TF', 'TGO' => 'TG', 'TCD' => 'TD', 'TCA' => 'TC', 'LBY' => 'LY', 'VAT' => 'VA', 'VCT' => 'VC', 'ARE' => 'AE', 'AND' => 'AD', 'ATG' => 'AG', 'AFG' => 'AF', 'AIA' => 'AI', 'VIR' => 'VI', 'ISL' => 'IS', 'IRN' => 'IR', 'ARM' => 'AM', 'ALB' => 'AL', 'AGO' => 'AO', 'ATA' => 'AQ', 'ASM' => 'AS', 'ARG' => 'AR', 'AUS' => 'AU', 'AUT' => 'AT', 'ABW' => 'AW', 'IND' => 'IN', 'ALA' => 'AX', 'AZE' => 'AZ', 'IRL' => 'IE', 'IDN' => 'ID', 'UKR' => 'UA', 'QAT' => 'QA', 'MOZ' => 'MZ', );
    $code=strtoupper($code);

    if(isset($data[$code])){
        return $data[$code];
    }else{
        return 'XX';
    }

}

function country_2alpha_to_3alpha($code){

    $data=array ( 'BD' => 'BGD', 'BE' => 'BEL', 'BF' => 'BFA', 'BG' => 'BGR', 'BA' => 'BIH', 'BB' => 'BRB', 'WF' => 'WLF', 'BL' => 'BLM', 'BM' => 'BMU', 'BN' => 'BRN', 'BO' => 'BOL', 'BH' => 'BHR', 'BI' => 'BDI', 'BJ' => 'BEN', 'BT' => 'BTN', 'JM' => 'JAM', 'BV' => 'BVT', 'BW' => 'BWA', 'WS' => 'WSM', 'BQ' => 'BES', 'BR' => 'BRA', 'BS' => 'BHS', 'JE' => 'JEY', 'BY' => 'BLR', 'BZ' => 'BLZ', 'RU' => 'RUS', 'RW' => 'RWA', 'RS' => 'SRB', 'TL' => 'TLS', 'RE' => 'REU', 'TM' => 'TKM', 'TJ' => 'TJK', 'RO' => 'ROU', 'TK' => 'TKL', 'GW' => 'GNB', 'GU' => 'GUM', 'GT' => 'GTM', 'GS' => 'SGS', 'GR' => 'GRC', 'GQ' => 'GNQ', 'GP' => 'GLP', 'JP' => 'JPN', 'GY' => 'GUY', 'GG' => 'GGY', 'GF' => 'GUF', 'GE' => 'GEO', 'GD' => 'GRD', 'GB' => 'GBR', 'GA' => 'GAB', 'SV' => 'SLV', 'GN' => 'GIN', 'GM' => 'GMB', 'GL' => 'GRL', 'GI' => 'GIB', 'GH' => 'GHA', 'OM' => 'OMN', 'TN' => 'TUN', 'JO' => 'JOR', 'HR' => 'HRV', 'HT' => 'HTI', 'HU' => 'HUN', 'HK' => 'HKG', 'HN' => 'HND', 'HM' => 'HMD', 'VE' => 'VEN', 'PR' => 'PRI', 'PS' => 'PSE', 'PW' => 'PLW', 'PT' => 'PRT', 'SJ' => 'SJM', 'PY' => 'PRY', 'IQ' => 'IRQ', 'PA' => 'PAN', 'PF' => 'PYF', 'PG' => 'PNG', 'PE' => 'PER', 'PK' => 'PAK', 'PH' => 'PHL', 'PN' => 'PCN', 'PL' => 'POL', 'PM' => 'SPM', 'ZM' => 'ZMB', 'EH' => 'ESH', 'EE' => 'EST', 'EG' => 'EGY', 'ZA' => 'ZAF', 'EC' => 'ECU', 'IT' => 'ITA', 'VN' => 'VNM', 'SB' => 'SLB', 'ET' => 'ETH', 'SO' => 'SOM', 'ZW' => 'ZWE', 'SA' => 'SAU', 'ES' => 'ESP', 'ER' => 'ERI', 'ME' => 'MNE', 'MD' => 'MDA', 'MG' => 'MDG', 'MF' => 'MAF', 'MA' => 'MAR', 'MC' => 'MCO', 'UZ' => 'UZB', 'MM' => 'MMR', 'ML' => 'MLI', 'MO' => 'MAC', 'MN' => 'MNG', 'MH' => 'MHL', 'MK' => 'MKD', 'MU' => 'MUS', 'MT' => 'MLT', 'MW' => 'MWI', 'MV' => 'MDV', 'MQ' => 'MTQ', 'MP' => 'MNP', 'MS' => 'MSR', 'MR' => 'MRT', 'IM' => 'IMN', 'UG' => 'UGA', 'TZ' => 'TZA', 'MY' => 'MYS', 'MX' => 'MEX', 'IL' => 'ISR', 'FR' => 'FRA', 'IO' => 'IOT', 'SH' => 'SHN', 'FI' => 'FIN', 'FJ' => 'FJI', 'FK' => 'FLK', 'FM' => 'FSM', 'FO' => 'FRO', 'NI' => 'NIC', 'NL' => 'NLD', 'NO' => 'NOR', 'NA' => 'NAM', 'VU' => 'VUT', 'NC' => 'NCL', 'NE' => 'NER', 'NF' => 'NFK', 'NG' => 'NGA', 'NZ' => 'NZL', 'NP' => 'NPL', 'NR' => 'NRU', 'NU' => 'NIU', 'CK' => 'COK', 'XK' => 'XKX', 'CI' => 'CIV', 'CH' => 'CHE', 'CO' => 'COL', 'CN' => 'CHN', 'CM' => 'CMR', 'CL' => 'CHL', 'CC' => 'CCK', 'CA' => 'CAN', 'CG' => 'COG', 'CF' => 'CAF', 'CD' => 'COD', 'CZ' => 'CZE', 'CY' => 'CYP', 'CX' => 'CXR', 'CR' => 'CRI', 'CW' => 'CUW', 'CV' => 'CPV', 'CU' => 'CUB', 'SZ' => 'SWZ', 'SY' => 'SYR', 'SX' => 'SXM', 'KG' => 'KGZ', 'KE' => 'KEN', 'SS' => 'SSD', 'SR' => 'SUR', 'KI' => 'KIR', 'KH' => 'KHM', 'KN' => 'KNA', 'KM' => 'COM', 'ST' => 'STP', 'SK' => 'SVK', 'KR' => 'KOR', 'SI' => 'SVN', 'KP' => 'PRK', 'KW' => 'KWT', 'SN' => 'SEN', 'SM' => 'SMR', 'SL' => 'SLE', 'SC' => 'SYC', 'KZ' => 'KAZ', 'KY' => 'CYM', 'SG' => 'SGP', 'SE' => 'SWE', 'SD' => 'SDN', 'DO' => 'DOM', 'DM' => 'DMA', 'DJ' => 'DJI', 'DK' => 'DNK', 'VG' => 'VGB', 'DE' => 'DEU', 'YE' => 'YEM', 'DZ' => 'DZA', 'US' => 'USA', 'UY' => 'URY', 'YT' => 'MYT', 'UM' => 'UMI', 'LB' => 'LBN', 'LC' => 'LCA', 'LA' => 'LAO', 'TV' => 'TUV', 'TW' => 'TWN', 'TT' => 'TTO', 'TR' => 'TUR', 'LK' => 'LKA', 'LI' => 'LIE', 'LV' => 'LVA', 'TO' => 'TON', 'LT' => 'LTU', 'LU' => 'LUX', 'LR' => 'LBR', 'LS' => 'LSO', 'TH' => 'THA', 'TF' => 'ATF', 'TG' => 'TGO', 'TD' => 'TCD', 'TC' => 'TCA', 'LY' => 'LBY', 'VA' => 'VAT', 'VC' => 'VCT', 'AE' => 'ARE', 'AD' => 'AND', 'AG' => 'ATG', 'AF' => 'AFG', 'AI' => 'AIA', 'VI' => 'VIR', 'IS' => 'ISL', 'IR' => 'IRN', 'AM' => 'ARM', 'AL' => 'ALB', 'AO' => 'AGO', 'AQ' => 'ATA', 'AS' => 'ASM', 'AR' => 'ARG', 'AU' => 'AUS', 'AT' => 'AUT', 'AW' => 'ABW', 'IN' => 'IND', 'AX' => 'ALA', 'AZ' => 'AZE', 'IE' => 'IRL', 'ID' => 'IDN', 'UA' => 'UKR', 'QA' => 'QAT', 'MZ' => 'MOZ', );
    $code=strtoupper($code);
    if(isset($data[$code])){
        return $data[$code];
    }else{
        return 'UNK';
    }




}