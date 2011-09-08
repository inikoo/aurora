<?php
function formated_rrp($data,$options=false) {

    $prefix=true;
    if (isset($options['prefix']))$prefix=$options['prefix'];


    $locale=$data['locale'];

    $rrp=money_locale($data['Product RRP']/$data['Product Units Per Case'],$locale,$data['Product Currency']);
    if ($locale=="de_DE") {
        return ($prefix?'UVP: ':'')."$rrp pro Stück";
    }
    elseif ($locale=="pl_PL") {
        return ($prefix?'SCD: ':'')."$rrp za sztukę";
    }
    elseif ($locale=="es_ES") {
        return ($prefix?'PVP: ':'')."$rrp und";
    }
    elseif($locale=="fr_FR") {
        return ($prefix?'PVC: ':'')."$rrp  /unité PVC";
    }
    else
        return ($prefix?_("RRP").': ':'')."$rrp "._("each");

}


function formated_price($data) {

  


    $locale=$data['locale'];


    $price=money_locale($data['Product Price'],$locale,$data['Product Currency']);
    $price_per_unit=money_locale($data['Product Price']/$data['Product Units Per Case'],$locale,$data['Product Currency']);

    if (!array_key_exists('Label',$data)) {
        $label='price';
    } else
        $label=$data['Label'];

    if ($locale=='de_DE') {
        if (isset($data['price per unit text'])) {
            $str=$data['price per unit text']." $price";
        } else {
            if ($data['Product Units Per Case']>1)
                $str="$price/".$data['Product Units Per Case']." ($price_per_unit pro Stück)";
            else
                $str="$price pro Stück";
        }

        if ($label=='from')
            return 'Preis ab '.$str;
        else if ($label=='price')
            return 'Preis: '.$str;
        else if ($label=='')
            return $str;
        else
            return $label.' '.$str;

    }
    elseif ($locale=='pl_PL') {
        if (isset($data['price per unit text'])) {
            $str=$data['price per unit text']." $price";
        } else {
            if ($data['Product Units Per Case']>1)
                $str="$price/".$data['Product Units Per Case']." ($price_per_unit za sztukę)";
            else
                $str="$price za sztukę";

        }
        if ($data=='from')
            return 'Cena od '.$str;
        else if ($label=='price')
            return 'Cena: '.$str;
        else if ($label=='')
            return $str;
        else
            return $label.' '.$str;

    }
    elseif($locale=='es_ES') {

        if ( is_array($data) and isset($data['price per unit text'])  ) {
            $str=$data['price per unit text']." $price";
        } else {
            if ($data['Product Units Per Case']>1)
                $str="$price/".$data['Product Units Per Case']." ($price_per_unit und)";
            else
                $str="$price und";

        }
        if ($data=='from')
            return 'Precio a partir '.$str;
        elseif($label=='price')
        return 'Precio: '.$str;
        else if ($label=='')
            return $str;
        else
            return $label.' '.$str;
    }
    elseif($locale=='fr_FR') {

        if ( is_array($data) and isset($data['price per unit text'])  ) {
            $str=$data['price per unit text']." $price";
        } else {
            if ($data['Product Units Per Case']>1)
                $str="$price/".$data['Product Units Per Case']." ($price_per_unit par unité)";
            else
                $str="$price par unité";

        }
        if ($data=='from')
            return 'Prix à partir de '.$str;
        elseif($label=='price')
        return 'Prix: '.$str;
        else if ($label=='')
            return $str;
        else
            return $label.' '.$str;
    }
    else {

        if ( is_array($data) and isset($data['price per unit text'])  ) {
            $str=$data['price per unit text']." $price";
        } else {
            if ($data['Product Units Per Case']>1)
                $str="$price/".$data['Product Units Per Case']." ($price_per_unit per unit)";
            else
                $str="$price per unit";

        }
        if ($data=='from')
            return 'Price from '.$str;
        else if ($label=='price')
            return 'Price: '.$str;
        else if ($label=='')
            return $str;
        else
            return $label.' '.$str;


    }



}



function formated_price_per_unit($data) {

    $locale=$data['locale'];


    $price_per_unit=money_locale($data['Product Price']/$data['Product Units Per Case'],$locale,$data['Product Currency']);

    if (!array_key_exists('Label',$data)) {
        $label='price';
    } else
        $label=$data['Label'];

    if ($locale=='de_DE') {

        $str="$price_per_unit pro Stück";
        if ($label=='from')
            return 'Preis ab '.$str;
        else if ($label=='price')
            return 'Preis: '.$str;
        else if ($label=='')
            return $str;
        else
            return $label.' '.$str;

    }
    elseif ($locale=='pl_PL') {

        $str="$price_per_unit za sztukę";


        if ($data=='from')
            return 'Cena od '.$str;
        else if ($label=='price')
            return 'Cena: '.$str;
        else if ($label=='')
            return $str;
        else
            return $label.' '.$str;

    }
    elseif($locale=='fr_FR') {


        $str="$price_per_unit par unité";


        if ($data=='from')
            return 'Prix à partir de '.$str;
        elseif($label=='price')
        return 'Prix: '.$str;
        else if ($label=='')
            return $str;
        else
            return $label.' '.$str;
    }
    else {


        $str="$price_per_unit per unit";


        if ($data=='from')
            return 'Price from '.$str;
        else if ($label=='price')
            return 'Prices: '.$str;
        else if ($label=='')
            return $str;
        else
            return $label.' '.$str;


    }



}




?>