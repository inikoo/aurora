<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  01 May 2020  22:25::58  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2020, Inikoo

 Version 3

*/

function get_label_data($code){

    $labels_data=[
        'A4'=>[
            'margin_top'=>0,
            'margin_bottom'=>0,
            'margin_left'=>0,
            'margin_right'=>0,
            'width'=>297,
            'height'=>210,
            'h_spacing'=>0,
            'v_spacing'=>0,
            'cols'=>1,
            'rows'=>1,
            'sheet_width'=>297,
            'sheet_height'=>210,
        ],
        'EU30090'=>[
            'margin_top'=>6,
            'margin_bottom'=>0,
            'margin_left'=>6.5,
            'margin_right'=>0,
            'width'=>97,
            'height'=>69,
            'h_spacing'=>3,
            'v_spacing'=>3,
            'cols'=>2,
            'rows'=>4,
            'sheet_width'=>210,
            'sheet_height'=>297,
        ],
        'EU30036'=>[
            'margin_top'=>0,
            'margin_bottom'=>0,
            'margin_left'=>0,
            'margin_right'=>0,
            'width'=>105,
            'height'=>74.25,
            'h_spacing'=>0,
            'v_spacing'=>0,
            'cols'=>2,
            'rows'=>4,
            'sheet_width'=>210,
            'sheet_height'=>297,
        ],
        'EU30161'=>[
            'margin_top'=>15.3,
            'margin_bottom'=>0,
            'margin_left'=>7.75,
            'margin_right'=>0,
            'width'=>63.5,
            'height'=>29.6,
            'h_spacing'=>2,
            'v_spacing'=>0,
            'cols'=>3,
            'rows'=>9,
            'sheet_width'=>210,
            'sheet_height'=>297,
        ],
        'SK06302900'=>[
            'margin_top'=>15.3,
            'margin_bottom'=>0,
            'margin_left'=>9.75,
            'margin_right'=>0,
            'width'=>63.5,
            'height'=>29.6,
            'h_spacing'=>0,
            'v_spacing'=>0,
            'cols'=>3,
            'rows'=>9,
            'sheet_width'=>210,
            'sheet_height'=>297,
        ],
        'EU30040'=>[
            'margin_top'=>0,
            'margin_bottom'=>0,
            'margin_left'=>0,
            'margin_right'=>0,
            'width'=>70,
            'height'=>29.7,
            'h_spacing'=>0,
            'v_spacing'=>0,
            'cols'=>3,
            'rows'=>10,
            'sheet_width'=>210,
            'sheet_height'=>297,
        ],
        'EU30137'=>[
            'margin_top'=>10,
            'margin_bottom'=>0,
            'margin_left'=>16,
            'margin_right'=>0,
            'width'=>130,
            'height'=>60,
            'h_spacing'=>5,
            'v_spacing'=>5,
            'cols'=>2,
            'rows'=>3,
            'sheet_width'=>297,
            'sheet_height'=>210,
        ],

        'EU30140'=>[
            'margin_top'=>10,
            'margin_bottom'=>7,
            'margin_left'=>42.5,
            'margin_right'=>42.5,
            'width'=>125,
            'height'=>37,
            'h_spacing'=>0,
            'v_spacing'=>3,
            'cols'=>1,
            'rows'=>7,
            'sheet_width'=>210,
            'sheet_height'=>297,
        ],
        'EU30129'=>[
            'margin_top'=>11.5,
            'margin_bottom'=>0,
            'margin_left'=>35,
            'margin_right'=>0,
            'width'=>140,
            'height'=>90,
            'h_spacing'=>0,
            'v_spacing'=>2,
            'cols'=>1,
            'rows'=>3,
            'sheet_width'=>210,
            'sheet_height'=>297,
        ],
        'ES0027D'=>[
            'margin_top'=>13.5,
            'margin_bottom'=>13.5,
            'margin_left'=>5,
            'margin_right'=>5,
            'width'=>65,
            'height'=>30,
            'h_spacing'=>2.5,
            'v_spacing'=>0,
            'cols'=>3,
            'rows'=>9,
            'sheet_width'=>210,
            'sheet_height'=>297,
        ],

    ];


    $label_data=$labels_data[$code];
    $label_data['h_spacing_px']=$label_data['h_spacing']*3.779528;
    $label_data['v_spacing_px']=$label_data['v_spacing']*3.779528;


    return $label_data;

}